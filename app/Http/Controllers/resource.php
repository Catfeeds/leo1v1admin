<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;


class resource extends Controller
{

    use CacheNick;
    var $check_login_flag=true;

    function __construct( ) {
        parent::__construct();
    }

    public function get_all() {
        $use_type      = $this->get_in_int_val('use_type', 1);
        $resource_type = $this->get_in_int_val('resource_type', 1);
        $subject       = $this->get_in_int_val('subject', -1);
        $grade         = $this->get_in_int_val('grade', -1);
        $tag_one       = $this->get_in_int_val('tag_one', -1);
        $tag_two       = $this->get_in_int_val('tag_two', -1);
        $tag_three     = $this->get_in_int_val('tag_three', -1);
        $tag_four      = $this->get_in_int_val('tag_four', -1);
        $file_title    = trim( $this->get_in_str_val('file_title', '') );
        $page_info     = $this->get_in_page_info();

        if($use_type == 1){
            $resource_type = ($resource_type<1)?1:$resource_type;
            $resource_type = ($resource_type>7)?7:$resource_type;
        }else if($use_type == 2){
            $resource_type = 9;
        }else{
            $resource_type = 8;
        }
        $ret_info = $this->t_resource->get_all(
            $use_type ,$resource_type, $subject, $grade, $tag_one, $tag_two, $tag_three, $tag_four,$file_title, $page_info
        );

        $tag_arr = \App\Helper\Utils::get_tag_arr( $resource_type );
        foreach($ret_info['list'] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            \App\Helper\Utils::get_file_use_type_str($item);
            $item['nick'] = $this->cache_get_account_nick($item['visitor_id']);
            $item['file_size'] = round( $item['file_size'] / 1024,2);

            $item['tag_one_name'] = $tag_arr['tag_one']['name'];
            $item['tag_two_name'] = $tag_arr['tag_two']['name'];
            $item['tag_three_name'] = $tag_arr['tag_three']['name'];
            $item['tag_four_name'] = @$tag_arr['tag_four']['name'];
            if($item['ex_num']>0){
                $item['file_use_type_str'] = $item['file_use_type_str'].$item['ex_num'];
            }
            // dd($item);

            E\Egrade::set_item_field_list($item, [
                "subject",
                "grade",
                "resource_type",
                "use_type",
                $tag_arr['tag_one']['menu'] => 'tag_one',
                $tag_arr['tag_two']['menu'] => 'tag_two',
                $tag_arr['tag_three']['menu'] => 'tag_three',
                $tag_arr['tag_four']['menu'] => 'tag_four',
            ]);
            if($item['tag_four'] != -1) {
                $item['tag_four_str'] = \App\Helper\Utils::get_sub_grade_tag($item['subject'],$item['grade'])[ $item['tag_four'] ];
            }

        }

        //查询老师负责的科目,年级
        $sub_grade_info = $this->get_rule_range();

        //获取所有开放的教材版本
        $book = $this->t_resource_agree_info->get_all_resource_type();
        $book_arr = [];
        foreach($book as $v) {
            if( $v['tag_one'] != 0 ){
                array_push($book_arr, intval($v['tag_one']) );
            }
        }

        return $this->pageView( __METHOD__,$ret_info,[
            'resource_type' => $resource_type,
            'tag_info'      => $tag_arr,
            'subject'       => json_encode($sub_grade_info['subject']),
            'grade'         => json_encode($sub_grade_info['grade']),
            'book'          => json_encode($book_arr),
        ]);
    }

    //获取开放的教材版本
    public function get_resource_type_js(){
        $resource_type = $this->get_in_int_val('resource_type');
        $subject       = $this->get_in_int_val('subject');
        $grade         = $this->get_in_int_val('grade');

        $book = $this->t_resource_agree_info->get_all_resource_type($resource_type, $subject, $grade);
        $book_arr = [];
        foreach($book as $v) {
            if( $v['tag_one'] != 0 ){
                array_push($book_arr, intval($v['tag_one']) );
            }
        }

        return $this->output_succ(['book' => $book_arr]);
    }

    public function get_rule_range(){

        $adminid  = $this->get_account_id();

        //判断是不是总监
        $is_master = $this->t_admin_majordomo_group_name->is_master($adminid);
        if ($is_master == false) {
            $data = [
                'subject' => [1,2,3,4,5,6,7,8,9,10],
                'grade' => [101,102,103,104,105,106,201,202,203,301,302,303],
            ];
            return $data;
        }

        //判断是不是主管
        $is_zhuguan = $this->t_admin_main_group_name->is_master($adminid);
        if ($is_zhuguan) {
            $info = $this->t_teacher_info->get_subject_grade_by_adminid($adminid);
            $data = [
                'subject' => $info['subject'],
                'grade' => [101,102,103,104,105,106,201,202,203,301,302,303],
            ];

            return $data;
        }


        $info = $this->t_teacher_info->get_subject_grade_by_adminid($adminid);
        $data = [
            'subject' => $info['subject'],
            'grade'   => \App\Helper\Utils::grade_start_end_tran_grade($info['grade_start'], $info['grade_end']),
        ];

        return $data;
    }

    public function resource_count(){
        list($start_time,$end_time) = $this->get_in_date_range(-30, 0 );
        $ret_info = $this->t_resource->get_count($start_time, $end_time);

        $list = [];

        foreach($ret_info as &$item){
            $visit = ($item['visit_num'] > 0)?1:0;
            $error = ($item['error_num'] > 0)?1:0;
            $use   = ($item['use_num'] > 0)?1:0;
            @$list[$item['subject']][$item['adminid']][$item['resource_type']]['file_num']++;//上传文件数
            @$list[$item['subject']][$item['adminid']][$item['resource_type']]['visit_num'] += $item['visit_num'];//浏览次数
            @$list[$item['subject']][$item['adminid']][$item['resource_type']]['error_num'] += $item['error_num'];//收藏次数
            @$list[$item['subject']][$item['adminid']][$item['resource_type']]['use_num'] += $item['use_num'];//使用次数
            @$list[$item['subject']][$item['adminid']][$item['resource_type']]['visit'] += $visit;//浏览量
            @$list[$item['subject']][$item['adminid']][$item['resource_type']]['error'] += $error;//收藏量
            @$list[$item['subject']][$item['adminid']][$item['resource_type']]['use'] += $use;//使用量
        }
        $final_list = [];
        // dd($list);
        foreach($list as $s=>$item){
            //subject
            //标记,这科目的第一个
            $flag = 1;
           foreach($item as $a=>$val){
                //adminid
                //标记,这个人的第一个
                $mark = 1;
                foreach($val as $r=>$v){
                    //resource_type
                    $subject = ($flag == 1) ? E\Esubject::get_desc($s): '';
                    $nick = ($mark == 1) ? $this->cache_get_account_nick($a): '';
                    $final_list[] = [
                        'mark'              => $mark,
                        'subject'           => $s,
                        'subject_str'       => $subject,
                        'adminid'           => $a,
                        'nick'              => $nick,
                        'resource_type'     => $r,
                        'resource_type_str' => E\Eresource_type::get_desc($r),
                        'file_num'          => $v['file_num'],
                        'visit_num'         => $v['visit_num'],
                        'error_num'         => $v['error_num'],
                        'use_num'           => $v['use_num'],
                        'visit'             => $v['visit'],
                        'use'               => $v['use'],
                        'error'             => $v['error'],
                        'visit_rate'        => round( $v['visit']*100/$v['file_num'], 2) . '%',
                        'error_rate'        => round( $v['error']*100/$v['file_num'], 2) . '%',
                        'use_rate'          => round( $v['use']*100/$v['file_num'], 2) . '%',
                        'score'             => $v['use_num']*(0.2)+$v['visit_num']*(0.2)+$v['error_num']*(0.6),
                    ];
                    $flag++;
                    $mark++;
                }
            }
        }
        return $this->pageView( __METHOD__,\App\Helper\Utils::list_to_page_info($final_list));
    }

    public function resource_frame_new(){
        return $this->pageView( __METHOD__,[]);
    }

    public function get_next_info_js(){
        $info_str = $this->get_in_str_val('info_str','');
        $level = $this->get_in_int_val('level', 0);
        //根据info_str判断查询几个字段
        $arr = explode('-', $info_str);

        //$arr对应信息
        // 0=resource_type, 1=subject, 2=grade, 3=tag_one, 4=tag_two, 5=tag_three, 6=tag_four

        $sel_arr = ['','subject','grade','tag_one','tag_two','tag_three','tag_four'];
        $num = count($arr);
        $select = $sel_arr[$num];
        $is_end = 0;
        //判断是不是最后
        if (in_array($arr[0], [1,2,9]) && $level == 4) {
            $is_end = 1;
        } else if ($arr[0] == 3 && $level == 6){
            $is_end = 1;
        } else if (in_array($arr[0], [4,5]) && $level == 3){
            $is_end = 1;
        } else if (in_array($arr[0], [6,7]) && $level == 5){
            $is_end = 1;
        }

        $data = $this->t_resource_agree_info->get_next_info($select,@$arr[0],@$arr[1],@$arr[2],@$arr[3],@$arr[4],@$arr[5],$is_end);

        $tag_arr = \App\Helper\Utils::get_tag_arr();
        //对应枚举类
        $menu = '';
        foreach($data as &$item){
            if($num < 3){
                E\Egrade::set_item_field_list($item, [$select]);
            } else {
                if($arr[0] <6 || $arr[0] ==9 || ($arr[0]==6 && $num=3) ){
                    $menu = $tag_arr[ $arr[0] ][ $select ]['menu'];
                    $item[$menu] = $item[$select];
                    E\Egrade::set_item_field_list($item, [$menu]);
                }
                //只有resource_type=3的时候才会有num=6
                if($num==6) {
                    $sub_grade = \App\Helper\Utils::get_sub_grade_tag($arr[1], $arr[2]);
                    $item['tag_four_str'] = @$sub_grade[$item['tag_four']];

                }
            }

        }
        if($menu != ''){
            $select = $menu;
        }
        return $this->output_succ(['data' => $data,'select' => $select, 'is_end' => $is_end]);
    }

    public function add_or_del_or_edit(){
        $info_str = $this->get_in_str_val('info_str','');
        $region   = $this->get_in_int_val('region','');
        $do_type  = $this->get_in_str_val('do_type','');
        // $arr      = explode('-', substr($info_str,5));
        $arr      = explode('-', $info_str);
        $adminid  = $this->get_account_id();
        $time     = time();
        $ban_level = count($arr);

        //暂时使用
        $s_g = [
            1 => [101,102,103,104,105,106,201,202,203,301,302,303],
            2 => [101,102,103,104,105,106,201,202,203,301,302,303],
            3 => [101,102,103,104,105,106,201,202,203,301,302,303],
            4 => [201,202,203,301,302,303],
            5 => [201,202,203,301,302,303],
            6 => [201,202,203,301,302,303],
            7 => [201,202,203,301,302,303],
            8 => [201,202,203,301,302,303],
            9 => [201,202,203,301,302,303],
            10 => [201,202,203,301,302,303],
            11 => [201,202,203,301,302,303],
        ];
        if($do_type === 'add'){//添加版本
            if($arr[0] < 3) {//1v1
                $season = E\Eresource_season::$desc_map;
                foreach($season as $key=>$v) {
                    //2017-12-21 暂时改动，一键给该类型科目下所有的年级添加版本
                    foreach($s_g[ $arr[1] ] as $g){
                        $this->t_resource_agree_info->row_insert([
                            'resource_type' => $arr[0],
                            'subject'       => $arr[1],
                            // 'grade'         => $arr[2],
                            'grade'         => $g,
                            'tag_one'       => $region,
                            'tag_two'       => $key,
                            'agree_adminid' => $adminid,
                            'agree_time'    => $time,
                        ]);
                    }
                }
            } else if ($arr[0] == 3){//标准试听课
                $free = E\Eresource_free::$desc_map;
                $diff = E\Eresource_diff_level::$desc_map;
                foreach($free as $f=>$v){
                    foreach($diff as $d=>$val){
                        $sub_grade_arr = \App\Helper\Utils::get_sub_grade_tag($arr[1],@$arr[2]);
                        foreach($sub_grade_arr as $sg => $value){
                            //2017-12-21 暂时改动，一键给该类型科目下所有的年级添加版本

                            foreach($s_g[ $arr[1] ] as $g){
                                $this->t_resource_agree_info->row_insert([
                                    'resource_type' => 3,
                                    'subject'       => $arr[1],
                                    // 'grade'         => @$arr[2],
                                    'grade'         => $g,
                                    'tag_one'       => $region,
                                    'tag_two'       => $f,
                                    'tag_three'     => $d,
                                    'tag_four'      => $sg,
                                    'agree_adminid' => $adminid,
                                    'agree_time'    => $time,
                                ]);
                            }
                        }
                    }
                }
            } else if ($arr[0] == 4 || $arr[0] == 5){
                $this->t_resource_agree_info->row_insert([
                    'resource_type' => $arr[0],
                    'subject'       => $arr[1],
                    'grade'         => $arr[2],
                    'tag_one'       => $region,
                    'agree_adminid' => $adminid,
                    'agree_time'    => $time,
                ]);
            } else if ($arr[0] == 9){
                $train = E\Eresource_train::$desc_map;
                foreach($train as $k=>$v){
                    $this->t_resource_agree_info->row_insert([
                        'resource_type' => $arr[0],
                        'subject'       => $arr[1],
                        'grade'         => $arr[2],
                        'tag_one'       => $region,
                        'tag_two'       => $k,
                        'agree_adminid' => $adminid,
                        'agree_time'    => $time,
                    ]);
                }
            }
        } else if($do_type === 'use'){//启用
            $this->t_resource_agree_info->update_ban(
                $arr[0],$arr[1],@$arr[2],@$arr[3],@$arr[4],@$arr[5],@$arr[6],$adminid,$time,0, $ban_level
            );
        } else if ($do_type === 'ban'){//禁用

            $this->t_resource_agree_info->update_ban(
                $arr[0],$arr[1],@$arr[2],@$arr[3],@$arr[4],@$arr[5],@$arr[6],$adminid,$time,1,$ban_level
            );
        } else if ($do_type === 'del'){//删除版本
            //先查询该版本下是否有上传的文件
            $ret = $this->t_resource->is_has_file($arr[0],$arr[1],@$arr[2],@$arr[3],@$arr[4],@$arr[5],@$arr[6]);
            if($ret > 0){
                return $this->output_err("该版本下有文件,无法删除!");
            }
            if(@$arr[0] > 0){
                $ret = $this->t_resource_agree_info->del_agree($arr[0],$arr[1],@$arr[2],@$arr[3],@$arr[4],@$arr[5],@$arr[6]);
            } else {
                return $this->output_err("信息有误,删除失败!");
            }
        }

        return $this->output_succ();

    }

    public function get_sub_grade_tag_js(){
        $subject = $this->get_in_int_val('subject', -1);
        $grade   = $this->get_in_int_val('grade', -1);

        $data = \App\Helper\Utils::get_sub_grade_tag($subject,$grade);
        return $this->output_succ(['tag' => $data]);
    }

    public function add_resource() {
        $use_type      = $this->get_in_int_val('use_type');
        $resource_type = $this->get_in_int_val('resource_type');
        $subject       = $this->get_in_int_val('subject');
        $grade         = $this->get_in_int_val('grade',0);
        $tag_one       = $this->get_in_int_val('tag_one',0);
        $tag_two       = $this->get_in_int_val('tag_two',0);
        $tag_three     = $this->get_in_int_val('tag_three',0);
        $tag_four      = $this->get_in_int_val('tag_four',0);
        $add_num       = $this->get_in_int_val('add_num');

        $adminid = $this->get_account_id();
        $time    = time();

        for($a = 0; $a < $add_num; $a++){
            $this->t_resource->row_insert([
                'use_type'      => $use_type,
                'resource_type' => $resource_type,
                'subject'       => $subject,
                'grade'         => $grade,
                'tag_one'       => $tag_one,
                'tag_two'       => $tag_two,
                'tag_three'     => $tag_three,
                'tag_four'      => $tag_four,
                'adminid'       => $adminid,
                'create_time'   => $time,
            ]);
        }
        $last_id = $this->t_resource->get_last_insertid();
        return $this->output_succ(['resource_id' => $last_id]);
    }

    public function add_file() {
        $resource_id   = $this->get_in_int_val('resource_id','');
        $file_title    = trim($this->get_in_str_val('file_title'));
        $file_hash     = $this->get_in_str_val('file_hash');

        $file_size     = round( $this->get_in_int_val('file_size')/1024, 2);
        $file_type     = $this->get_in_str_val('file_type');
        $file_link     = $this->get_in_str_val('file_link');
        $file_use_type = $this->get_in_int_val('file_use_type');
        $ex_num        = $this->get_in_int_val('ex_num', 0);
        $is_reupload   = $this->get_in_int_val('is_reupload', 0);
        //处理文件名
        $dot_pos = strrpos($file_title,'.');
        $file_title = substr($file_title,0,$dot_pos);
        //处理文件类型
        $file_type = trim( strrchr($file_type, '/'), '/' );

        if ($file_use_type == 3){
            if($is_reupload == 0){
                $ex_num_max = $this->t_resource_file->get_max_ex_num($resource_id);
                $ex_num     = @$ex_num_max+1;
            } else {
                if($ex_num == 0) {
                    //上传额外文件区间，不属于重新上传
                    $ex_num_max = $this->t_resource_file->get_max_ex_num($resource_id);
                    $ex_num     = @$ex_num_max+1;
                }
            }
        }
        $this->t_resource_file->row_insert([
            'resource_id'   => $resource_id,
            'file_title'    => $file_title,
            'file_type'     => $file_type,
            'file_size'     => $file_size,
            'file_hash'     => $file_hash,
            'file_link'     => $file_link,
            'file_use_type' => $file_use_type,
            'ex_num'        => $ex_num,
        ]);

        $file_id = $this->t_resource_file->get_last_insertid();
        if($is_reupload == 0){
            $adminid = $this->get_account_id();
            $this->t_resource_file_visit_info->row_insert([
                'file_id'     => $file_id,
                'visit_type'  => 9,
                'create_time' => time(),
                'visitor_id'  => $adminid,
                'ip'          => $_SERVER["REMOTE_ADDR"],
            ]);

            return $this->output_succ();
        } else {
            return $file_id;
        }
    }

    public function rename_resource() {
        $file_title  = $this->get_in_str_val('file_title');
        $file_id     = $this->get_in_int_val('file_id');
        $resource_id = $this->get_in_int_val('resource_id');

        $adminid = $this->get_account_id();
        $time    = time();

        $this->t_resource_file->field_update_list($file_id, [
            'file_title'   => $file_title,
        ]);

        $this->t_resource_file_visit_info->row_insert([
            'file_id'     => $file_id,
            'visit_type'  => 1,
            'create_time' => $time,
            'visitor_id'  => $adminid,
            'ip'          => $_SERVER["REMOTE_ADDR"],
        ]);

        return $this->output_succ();
    }

    public function reupload_resource() {
        $resource_id   = $this->get_in_int_val('resource_id','');
        $file_id       = $this->get_in_int_val('file_id','');
        $file_use_type = $this->get_in_int_val('file_use_type', 0);
        $ex_num        = $this->get_in_int_val('ex_num', 0);
        $adminid       = $this->get_account_id();
        $time          = time();

        if($file_id != 0){
            $this->t_resource_file->field_update_list($file_id, ['status' => 2]);
            $visit_type = 2;
        } else {//添加额外文件
            $visit_type = 9;
        }
        $this->set_in_value('is_reupload', 1);
        $file_id = $this->add_file();

        $this->t_resource_file_visit_info->row_insert([
            'file_id'     => $file_id,
            'visit_type'  => $visit_type,
            'create_time' => $time,
            'visitor_id'  => $adminid,
            'ip'          => $_SERVER["REMOTE_ADDR"],
        ]);
        return $this->output_succ();
    }

    public function str_to_arr($str) {
        $str = ltrim($str,'[');
        $str = rtrim($str,']');
        $arr = explode(',', $str);
        $arr = array_unique($arr);
        return $arr;
    }

    public function del_or_restore_resource() {
        $res_id_str  = $this->get_in_str_val('res_id_str','');
        $file_id_str = $this->get_in_str_val('file_id_str','');
        $type   = $this->get_in_str_val('type','');
        //type 0 浏览 １重命名 2上传新版本　3删除　4还原 5 纠错　6彻底删除 7 使用

        $adminid = $this->get_account_id();
        $time    = time();
        if($res_id_str != '') {
            $res_id_arr  = $this->str_to_arr($res_id_str);
            $file_id_arr = $this->str_to_arr($file_id_str);
            foreach($res_id_arr as $id){
                if($type == 3){//删除
                    $this->t_resource->field_update_list($id, ['is_del' => 1]);
                    $this->t_resource_file->update_file_status($id, 1);
                } else if ($type == 4){//还原
                    $this->t_resource->field_update_list($id, ['is_del' => 0]);
                    $this->t_resource_file->update_file_status($id, 0);
                } else if ($type == 6){//彻底删除
                    $this->t_resource->field_update_list($id, ['is_del' => 2]);
                }

            }

            foreach($file_id_arr as $file_id){
                $this->t_resource_file_visit_info->row_insert([
                    'file_id'     => $file_id,
                    'visit_type'  => $type,
                    'create_time' => $time,
                    'visitor_id'  => $adminid,
                    'ip'          => $_SERVER["REMOTE_ADDR"],
                ]);
            }
            return $this->output_succ();
        }
    }

    public function get_list_by_resource_id_js(){
        $page_num      = $this->get_in_page_num();
        $resource_id   = $this->get_in_int_val('resource_id', -1);
        $file_use_type = $this->get_in_int_val('file_use_type', -1);
        $ex_num        = $this->get_in_int_val('ex_num', 0);
        $ret_list      = $this->t_resource_file_visit_info->get_visit_detail( $page_num,$resource_id, $file_use_type, $ex_num);
        foreach ($ret_list['list'] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            $this->cache_set_item_account_nick($item,"visitor_id", 'nick');
            E\Eresource_visit::set_item_value_simple_str($item,'visit_type');
        }
        return $this->output_ajax_table($ret_list);
        // return $this->output_succ(["data"=> $ret_list]);
    }

    public function get_del() {

        $use_type      = $this->get_in_int_val('use_type', 1);
        $resource_type = $this->get_in_int_val('resource_type', 1);
        $subject       = $this->get_in_int_val('subject', -1);
        $grade         = $this->get_in_int_val('grade', -1);
        $tag_one       = $this->get_in_int_val('tag_one', -1);
        $tag_two       = $this->get_in_int_val('tag_two', -1);
        $tag_three     = $this->get_in_int_val('tag_three', -1);
        $tag_four      = $this->get_in_int_val('tag_four', -1);
        $file_title    = $this->get_in_str_val('file_title', '');
        $page_info     = $this->get_in_page_info();

        $ret_info = $this->t_resource->get_all(
            $use_type ,$resource_type, $subject, $grade, $tag_one, $tag_two, $tag_three, $tag_four,$file_title, $page_info, 1
        );
        // dd($ret_info);
        $tag_arr = \App\Helper\Utils::get_tag_arr($resource_type);
        foreach($ret_info['list'] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            $item['nick'] = $this->cache_get_account_nick($item['visitor_id']);
            $item['file_size'] = round( $item['file_size'] / 1024,2);
        }

        return $this->pageView( __METHOD__,$ret_info,['tag_info' => $tag_arr]);
    }

}
