<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;


class resource extends Controller
{

    use CacheNick;
    var $check_login_flag=true;
    public $tag_arr = [
            1 => ['tag_one' => ['name' => '教材版本','menu' => 'region_version','hide' => ''],
                  'tag_two' => ['name' => '春署秋寒','menu' => 'resource_season','hide' => ''],
                  'tag_three' => ['name' => '','menu' => '','hide' => 'hide'],
                  'tag_four' => ['name' => '','menu' => '','hide' => 'hide']],
            2 => ['tag_one' => ['name' => '教材版本','menu' => 'region_version','hide' => ''],
                  'tag_two' => ['name' => '春署秋寒','menu' => 'resource_season','hide' => ''],
                  'tag_three' => ['name' => '','menu' => '','hide' => 'hide'],
                  'tag_four' => ['name' => '','menu' => '','hide' => 'hide']],
            3 => ['tag_one' => ['name' => '教材版本','menu' => 'region_version','hide' => ''],
                  'tag_two' => ['name' => '试听类型','menu' => 'resource_free','hide' => ''],
                  'tag_three' => ['name' => '难度类型','menu' => 'resource_diff_level','hide' => ''],
                  'tag_four' => ['name' => '学科化标签','menu' => '','hide' => '']],
            4 => ['tag_one' => ['name' => '教材版本','menu' => 'region_version','hide' => ''],
                  'tag_two' => ['name' => '','menu' => '','hide' => 'hide'],
                  'tag_three' => ['name' => '','menu' => '','hide' => 'hide'],
                  'tag_four' => ['name' => '','menu' => '','hide' => 'hide']],
            5 => ['tag_one' => ['name' => '教材版本','menu' => 'region_version','hide' => ''],
                  'tag_two' => ['name' => '','menu' => '','hide' => 'hide'],
                  'tag_three' => ['name' => '','menu' => '','hide' => 'hide'],
                  'tag_four' => ['name' => '','menu' => '','hide' => 'hide']],
            6 => ['tag_one' => ['name' => '年份','menu' => 'resorece_year','hide' => ''],
                  'tag_two' => ['name' => '省份','menu' => '','hide' => ''],
                  'tag_three' => ['name' => '城市','menu' => '','hide' => ''],
                  'tag_four' => ['name' => '','menu' => '','hide' => 'hide']],
            7 => ['tag_one' => ['name' => '一级知识点','menu' => '','hide' => ''],
                  'tag_two' => ['name' => '二级知识点','menu' => '','hide' => ''],
                  'tag_three' => ['name' => '三级知识点','menu' => '','hide' => ''],
                  'tag_four' => ['name' => '','menu' => '','hide' => 'hide']],
            9 => ['tag_one' => ['name' => '教材版本','menu' => 'region_version','hide' => ''],
                  'tag_two' => ['name' => '培训资料','menu' => 'resource_train','hide' => ''],
                  'tag_three' => ['name' => '','menu' => '','hide' => 'hide'],
                  'tag_four' => ['name' => '','menu' => '','hide' => 'hide']],
        ];

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
        $file_title    = $this->get_in_str_val('file_title', '');
        $page_info     = $this->get_in_page_info();
        // if($resource_type == 3 || $resource_type == 9){//没有三级标签
        //     $tag_three = -1;
        // } else if($resource_type == 4 || $resource_type == 5){//没有二，三级标签
        //     $tag_two   = -1;
        //     $tag_three = -1;
        // }

        // if ($resource_type != 3){
        //     $tag_four = -1;
        // }

        $ret_info = $this->t_resource->get_all(
            $use_type ,$resource_type, $subject, $grade, $tag_one, $tag_two, $tag_three, $tag_four,$file_title, $page_info
        );
        foreach($ret_info['list'] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"update_time");
            // E\Egrade::set_item_field_list($item, ["subject","grade","resource_type" ]);

            // \App\Helper\Utils::transform_1tg_0tr($item,'is_use');
            $item['nick'] = $this->cache_get_account_nick($item['edit_adminid']);
            $item['file_size'] = round( $item['file_size'] / 1024,2);
            // if($item['is_use'] == 0) {
            //     $item['is_use_str'] = '否';
            // } else {
            //     $item['is_use_str'] = '是';
            // }
            $tag_arr = $this->tag_arr[ $item['resource_type'] ];

            $item['tag_one_name'] = $tag_arr['tag_one']['name'];
            $item['tag_two_name'] = $tag_arr['tag_two']['name'];
            $item['tag_three_name'] = $tag_arr['tag_three']['name'];
            $item['tag_four_name'] = @$tag_arr['tag_four']['name'];
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
        }

        // dd($ret_info);
        return $this->pageView( __METHOD__,$ret_info,['tag_info' => $this->tag_arr[$resource_type]]);
    }

    public function resource_count(){
        list($start_time,$end_time) = $this->get_in_date_range(-7, 0 );
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
            @$list[$item['subject']][$item['adminid']][$item['resource_type']]['error'] += $visit;//收藏量
            @$list[$item['subject']][$item['adminid']][$item['resource_type']]['use'] += $use;//使用量
        }
        $final_list = [];
        foreach($list as $s=>$item){
            //subject
            foreach($item as $a=>$val){
                //adminid
                //标记,这个人的第一个
                $mark = 1;
                foreach($val as $r=>$v){
                    //resource_type
                    $final_list[] = [
                        'mark'              => $mark,
                        'subject'           => $s,
                        'subject_str'       => E\Esubject::get_desc($s),
                        'adminid'           => $a,
                        'nick'              => $this->cache_get_account_nick($a),
                        'resource_type'     => $r,
                        'resource_type_str' => E\Eresource_type::get_desc($r),
                        'file_num'          => $v['file_num'],
                        'visit_num'         => $v['visit_num'],
                        'error_num'         => $v['error_num'],
                        'use_num'           => $v['use_num'],
                        'visit'             => $v['visit'],
                        'use'               => $v['use'],
                        'error'             => $v['error'],
                        'visit_rate'        => round( $v['visit']/$v['file_num'], 2),
                        'error_rate'        => round( $v['error']/$v['file_num'], 2),
                        'use_rate'          => round( $v['use']/$v['file_num'], 2),
                        'score'             => $v['use_num']*(0.2)+$v['visit_num']*(0.2)+$v['error_num']*(0.6),
                    ];
                    $mark++;
                }
            }
        }
        return $this->pageView( __METHOD__,\App\Helper\Utils::list_to_page_info($final_list),['info' => $ret_info]);
    }

    public function resource_frame(){
        //*******************************待开发**********************************//
        //资源类型数组
        $resource_type_arr = E\Eresource_type::$desc_map;
        //科目数组
        $subject_arr = E\Esubject::$desc_map;
        //年级数组
        $grade_arr = E\Egrade::$desc_map;

        //一级标签
        //教材版本数组
        $region_arr = E\Eregion_version::$desc_map;
        //年份数组
        $year_arr = E\Eresource_year::$desc_map;
        //一级知识点数组
        // $resource_type_arr = E\Eresource_type::$desc_map;

        //二级标签
        //四季
        $season_arr = E\Eresource_season::$desc_map;
        //试听类型
        $free_arr = E\Eresource_free::$desc_map;
        //省份
        $resource_type_arr = E\Eresource_type::$desc_map;
        //二级知识点
        // $resource_type_arr = E\Eresource_type::$desc_map;
        //培训资料数组
        $train_arr = E\Eresource_train::$desc_map;

        //三级标签
        //城市
        // $resource_type_arr = E\Eresource_type::$desc_map;
        //三级知识点
        // $resource_type_arr = E\Eresource_type::$desc_map;
        //难度类型
        $diff_arr = E\Eresource_diff_level::$desc_map;

        //四级标签
        //学科化标签--------待开发
        $diff_arr = E\Eresource_diff_level::$desc_map;

        //将四级标签压入三级标签数组 ----待开发

        //将三级标签压入二级标签数组 ----待开发
        \App\Helper\Utils::push_arr_to_arr($free_arr, $diff_arr, 'resource_diff_level');

        //将二级标签压入一级标签数组 ----待开发
        $region_1 = \App\Helper\Utils::push_arr_to_arr_new($region_arr, $season_arr , 'resource_season');
        $region_3 = \App\Helper\Utils::push_arr_to_arr_new($region_arr, $free_arr ,'resource_free');
        $region_9 = \App\Helper\Utils::push_arr_to_arr_new($region_arr, $train_arr ,'resource_traion');

        //将一级标签压入年级标签 ----待开发
        $grade_1 = \App\Helper\Utils::push_arr_to_arr_new($grade_arr, $region_1 , 'region_version');
        $grade_3 = \App\Helper\Utils::push_arr_to_arr_new($grade_arr, $region_3 , 'region_version');
        $grade_9 = \App\Helper\Utils::push_arr_to_arr_new($grade_arr, $region_9 , 'region_version');

        //将年级压入学科 ----待开发
        $subject_1 = \App\Helper\Utils::push_arr_to_arr_new($subject_arr, $grade_1 , 'region_version');
        $subject_3 = \App\Helper\Utils::push_arr_to_arr_new($subject_arr, $grade_3 , 'region_version');
        $subject_9 = \App\Helper\Utils::push_arr_to_arr_new($subject_arr, $grade_9 , 'region_version');
        foreach($resource_type_arr as $k => &$v){
            $arr = [];
            $key = 'subject_'.$k;
            $arr['name'] = $v;
            $arr['subject'] = @$$key;
            $v = $arr;
        }

        dd($resource_type_arr);
        return $this->pageView( __METHOD__,\App\Helper\Utils::list_to_page_info($info));
    }

    public function add_resource() {
        $use_type     = $this->get_in_int_val('use_type');
        $resource_type = $this->get_in_int_val('resource_type');
        $subject       = $this->get_in_int_val('subject');
        $grade         = $this->get_in_int_val('grade');
        $tag_one       = $this->get_in_int_val('tag_one');
        $tag_two       = $this->get_in_int_val('tag_two');
        $tag_three     = $this->get_in_int_val('tag_three');
        $tag_four      = $this->get_in_int_val('tag_four');
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
        $file_title    = $this->get_in_str_val('file_title');
        $file_hash     = $this->get_in_str_val('file_hash');

        $file_size     = round( $this->get_in_int_val('file_size')/1024, 2);
        $file_type     = $this->get_in_str_val('file_type');
        $file_link     = $this->get_in_str_val('file_link');
        $file_use_type = $this->get_in_int_val('file_use_type');
        //处理文件名
        $dot_pos = strrpos($file_title,'.');
        $file_title = substr($file_title,0,$dot_pos);
        //处理文件类型
        $file_type = trim( strrchr($file_type, '/'), '/' );
        $this->t_resource_file->row_insert([
            'resource_id'   => $resource_id,
            'file_title'    => $file_title,
            'file_type'     => $file_type,
            'file_size'     => $file_size,
            'file_hash'     => $file_hash,
            'file_link'     => $file_link,
            'file_use_type' => $file_use_type,
        ]);
        return $this->output_succ();
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
        ]);

        return $this->output_succ();
    }

    public function reupload_resource() {
        $resource_id   = $this->get_in_int_val('resource_id','');
        $file_id = $this->get_in_int_val('file_id');
        $adminid = $this->get_account_id();
        $time    = time();

        $this->t_resource_file->field_update_list($file_id, ['status' => 2]);
        $this->t_resource_file_visit_info->row_insert([
            'file_id'     => $file_id,
            'visit_type'  => 2,
            'create_time' => $time,
            'visitor_id'  => $adminid,
        ]);

        $this->add_file();

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
                ]);
            }
            return $this->output_succ();
        }
    }

    public function get_list_by_resource_id_js(){
        $page_num = $this->get_in_page_num();
        $resource_id   = $this->get_in_int_val('resource_id', -1);
        $file_use_type = $this->get_in_int_val('file_use_type', -1);

        $ret_list = $this->t_resource_file_visit_info->get_visit_detail( $resource_id, $file_use_type, $page_num);
        foreach ($ret_list['list'] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            // $this->cache_set_item_teacher_nick($item,"teacherid", "tea_nick");
            // \App\Helper\Utils::transform_1tg_0tr($item,"operation");
            // \App\Helper\Utils::transform_1tg_0tr($item,"success_flag");
            $this->cache_set_item_account_nick($item,"visitor_id", 'nick');
            E\Eresource_visit::set_item_value_simple_str($item,'visit_type');
        }
        return $this->output_succ(["data"=> $ret_list]);
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
        if($resource_type == 3 || $resource_type == 9){//没有三级标签
            $tag_three = -1;
        } else if($resource_type == 4 || $resource_type == 5){//没有二，三级标签
            $tag_two   = -1;
            $tag_three = -1;
        }
        if ($resource_type != 3){
            $tag_four = -1;
        }

        $ret_info = $this->t_resource->get_all(
            $use_type ,$resource_type, $subject, $grade, $tag_one, $tag_two, $tag_three, $tag_four,$file_title, $page_info, 1
        );
        foreach($ret_info['list'] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"update_time");
            // \App\Helper\Utils::transform_1tg_0tr($item,'is_use');
            $item['nick'] = $this->cache_get_account_nick($item['edit_adminid']);
            $item['file_size'] = round( $item['file_size'] / 1024,2);
        }

        return $this->pageView( __METHOD__,$ret_info,['tag_info' => $this->tag_arr[$resource_type]]);
    }

}
