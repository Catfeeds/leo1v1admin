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
        $tag_five      = $this->get_in_int_val('tag_five', -1);
        $file_title    = trim( $this->get_in_str_val('file_title', '') );
        $has_comment   = $this->get_in_int_val('has_comment', -1);
        $has_error     = $this->get_in_int_val('has_error', -1);
        $id_order      = $this->get_in_int_val('id_order', 1);
        $paper_assort  = $this->get_in_int_val('paper_assort', 0);
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
            $use_type ,$resource_type, $subject, $grade, $tag_one, $tag_two, $tag_three, $tag_four,$tag_five,$file_title, $page_info,0,0,$has_comment,
            $has_error,$id_order
        );
        $r_mark = 0;
        $index  = 1;
        $tag_arr = \App\Helper\Utils::get_tag_arr( $resource_type );

        foreach($ret_info['list'] as &$item){
            if($r_mark == $item['resource_id']){
                $index++;
            } else {
                $r_mark = $item['resource_id'];
                $index = 1;
            }
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            \App\Helper\Utils::get_file_use_type_str($item, $index);
            $item['nick'] = $this->cache_get_account_nick($item['visitor_id']);

            $item['tag_one_name'] = $tag_arr['tag_one']['name'];
            $item['tag_two_name'] = $tag_arr['tag_two']['name'];
            $item['tag_three_name'] = $tag_arr['tag_three']['name'];
            $item['tag_four_name'] = @$tag_arr['tag_four']['name'];
            $item['tag_five_name'] = @$tag_arr['tag_five']['name'];
            // dd($item);
            $item['file_size_str'] = $item['file_size'] > 1024 ? round( $item['file_size'] / 1024,2)."M" : $item['file_size']."kb";
            E\Egrade::set_item_field_list($item, [
                "subject",
                "grade",
                "resource_type",
                "use_type",
                $tag_arr['tag_one']['menu'] => 'tag_one',
                $tag_arr['tag_two']['menu'] => 'tag_two',
                $tag_arr['tag_three']['menu'] => 'tag_three',
                $tag_arr['tag_four']['menu'] => 'tag_four',
                $tag_arr['tag_five']['menu'] => 'tag_five',
            ]);
            $item['tag_one_str'] = E\Eregion_version::get_desc($item['tag_one']);

            if( $item['resource_type'] == 1 ){
                $item['tag_five_str'] = E\Eresource_diff_level::get_desc($item['tag_five']);
            }else{
                $item['tag_five_str'] = E\Eresource_volume::get_desc($item['tag_five']);
            }

            if( in_array($item['resource_type'],[1,2,9])){
                $item['tag_two_str'] = E\Eresource_season::get_desc($item['tag_two']);
            }

            if($item['resource_type'] == 3 ) {
                $item['tag_three_str'] = E\Eresource_diff_level::get_desc($item['tag_three']);
            }


            $item['comment'] = null;
            if(!empty($item['comment_id'])){
                $item['comment'] = $this->t_resource_file_evalutation->get_count($item['file_id']);
            }

            $item['error'] = null;
            if(!empty($item['error_id'])){
                $item['error'] = $this->t_resource_file_error_info->get_count($item['file_id']);
            }

        }
        //dd($ret_info['list']);

        //获取所有开放的教材版本
        $book = $this->t_resource_agree_info->get_all_resource_type($resource_type, $subject, $grade);
        $book_arr = [];
        if($book){
            foreach($book as $v) {
                if( $v['tag_one'] != 0 ){
                    array_push($book_arr, intval($v['tag_one']) );
                }
            }
        }

        $sub_grade_info = $this->get_rule_range();
        $is_teacher = 0;
        if($this->get_account_role() == 4){
            $is_teacher = 1;
            if( $sub_grade_info['subject'] == 0 && $sub_grade_info['grade'] == 0 ){
                $err_mg = "你是教研老师，但没有所教科目，无法查看当前页面，请找相关人员开启该权限";
                return $this->view_with_header_info ( "common.resource_no_power", [],[
                    "_ctr"          => "xx",
                    "_act"          => "xx",
                    "js_values_str" => "",
                    'err_mg' => $err_mg
                ] );

            }

            if( $subject > 0 && !in_array($subject,$sub_grade_info['subject'])){
                $err_mg = "你不是教当前科目的教研老师，没有权限查看当前科目";
                return $this->view_with_header_info ( "common.resource_no_power", [],[
                    "_ctr"          => "xx",
                    "_act"          => "xx",
                    "js_values_str" => "",
                    'err_mg' => $err_mg
                ] );

            }

            if( $grade > 0 && !in_array($grade,$sub_grade_info['grade'])){
                $err_mg = "你不是教当前年级段的教研老师，没有权限查看当前年级";
                return $this->view_with_header_info ( "common.resource_no_power", [],[
                    "_ctr"          => "xx",
                    "_act"          => "xx",
                    "js_values_str" => "",
                    'err_mg' => $err_mg
                ] );

            }

        }

        return $this->pageView( __METHOD__,$ret_info,[
            '_publish_version'    => 20180226171440,
            'tag_info'      => $tag_arr,
            'subject'       => json_encode($sub_grade_info['subject']),
            'grade'         => json_encode($sub_grade_info['grade']),
            'book'          => json_encode($book_arr),
            'resource_type' => $resource_type,
            'is_teacher'   => $is_teacher,
        ]);
    }

    //获取开放的教材版本
    public function get_resource_type_js(){
        $resource_type = $this->get_in_int_val('resource_type');
        $subject       = $this->get_in_int_val('subject');
        $grade         = $this->get_in_int_val('grade');

        $book = $this->t_resource_agree_info->get_all_resource_type($resource_type, $subject, $grade);
        $book_arr = [];
        if($book){
            foreach($book as $v) {
                if( $v['tag_one'] != 0 ){
                    array_push($book_arr, intval($v['tag_one']) );
                }
            }
        }
   
        return $this->output_succ(['book' => $book_arr]);
    }

    //根据科目、年级、教材获取学科标签
    public function get_sub_grade_book_tag(){
        $subject       = $this->get_in_int_val('subject');
        $grade         = $this->get_in_int_val('grade');
        $bookid        = $this->get_in_int_val('bookid',-1);
        $resource_type        = $this->get_in_int_val('resource_type',-1);
        $season_id        = $this->get_in_int_val('season_id',-1);
        $data = $this->t_sub_grade_book_tag->get_tag_by_sub_grade($subject,$grade,$bookid,$resource_type,$season_id);
         
        return $this->output_succ(['tag' => $data]);

    }

    //学科化标签
    public function sub_grade_book_tag(){
        $subject       = $this->get_in_int_val('subject',-1);
        $grade         = $this->get_in_int_val('grade',-1);
        $bookid        = $this->get_in_int_val('bookid',-1);
        $season_id     = $this->get_in_int_val('season_id',-1);
        $resource_type      = $this->get_in_int_val('resource_type',-1);

        $page_num        = $this->get_in_page_num();
        $page_count      = $this->get_in_int_val('page_count',20);
        $book = $this->t_resource_agree_info->get_all_resource_type($resource_type, $subject, $grade);
        $book_arr = [];
        if($book){   
            $book_uni = array_column($book, 'tag_one');
            $book_uni = array_unique($book_uni);
            foreach( $book_uni as $k=>&$v){
                if($v != 0){
                    $book_arr[] = (int)$v;
                }
            }
        }

        $ret_info = $this->t_sub_grade_book_tag->get_list($subject,$grade,$bookid,$resource_type,$season_id,$page_num,$page_count);
        if($ret_info){
            foreach($ret_info['list'] as &$var){
                $var['subject_str'] = E\Esubject::get_desc($var['subject']);
                $var['grade_str'] = E\Egrade::get_desc($var['grade']);
                $var['book_str'] = E\Eregion_version::get_desc($var['bookid']);
                $var['resource_str'] = E\Eresource_type::get_desc($var['resource_type']);
                $var['season_str'] = E\Eresource_season::get_desc($var['season_id']);
            }
        }
        //dd($ret_info['list'] );
        return $this->pageView( __METHOD__,$ret_info,[
            '_publish_version'    => 201801291349,
            'book'          => json_encode($book_arr),
            'resource_type' => $resource_type
        ]);
    }

    public function get_book_by_grade_sub(){
        $subject       = $this->get_in_int_val('subject',1);
        $grade         = $this->get_in_int_val('grade',201);
        $book = $this->t_resource_agree_info->get_all_resource_type(-1, $subject, $grade);

        // if(!$book){
        //     $book = [3,4,12,15,16,29,50000];
        // }

        return $this->output_succ(['book' => $book]);
    }

    public function batch_add_sub_grade_tag(){
        $subject       = $this->get_in_int_val('subject');
        $grade         = $this->get_in_int_val('grade');
        $bookid        = $this->get_in_int_val('bookid');
        $season_id     = $this->get_in_int_val('season_id');
        $resource_type      = $this->get_in_int_val('resource_type');
        if($resource_type != 1){
            $season_id = 0;
        }
        $tag_arr       = $this->get_in_str_val('tag_arr');
   
        $data = [
            'subject'  => $subject,
            'grade'    => $grade,
            'bookid'   => $bookid,
            'resource_type' => $resource_type,
            'season_id' => $season_id,
            'tag'      => ''
        ];
        $i = 0;
        $all = count($tag_arr);
        if( is_array($tag_arr) || count($tag_str) > 0){
            foreach($tag_arr as $tag){
                if(!empty($tag)){
                    $data['tag'] = trim($tag);
                    $info = $this->add_each_sub_tag($data);
                    $info == 1 ? $i += 1 : '';
                }
            }
        }
        return $this->output_succ('总共添加条数：'.$all.' 添加成功条数：'.$i);
    }

    public function add_sub_grade_tag(){
        $subject       = $this->get_in_int_val('subject');
        $grade         = $this->get_in_int_val('grade');
        $bookid         = $this->get_in_int_val('bookid');
        $season_id     = $this->get_in_int_val('season_id',-1);
        $resource_type      = $this->get_in_int_val('resource_type',-1);
        if($resource_type != 1){
            $season_id = 0;
        }
        $tag         = trim($this->get_in_str_val('tag'));

        $data = [
            'subject'  => $subject,
            'grade'    => $grade,
            'bookid'   => $bookid,
            'resource_type' => $resource_type,
            'season_id' => $season_id,
            'tag'      => $tag
        ];

        $info = $this->add_each_sub_tag($data);
        return $this->output_succ('添加成功');
    }

    public function edit_sub_grade_tag(){
        $id       = $this->get_in_int_val('id');
        $subject       = $this->get_in_int_val('subject');
        $grade         = $this->get_in_int_val('grade');
        $bookid         = $this->get_in_int_val('bookid');
        $season_id     = $this->get_in_int_val('season_id',-1);
        $resource_type      = $this->get_in_int_val('resource_type',1);
        if($resource_type != 1){
            $season_id = 0;
        }
        $tag         = trim($this->get_in_str_val('tag'));

        $data = [
            'subject'  => $subject,
            'grade'    => $grade,
            'bookid'   => $bookid,
            'resource_type' => $resource_type,
            'season_id' => $season_id,
            'tag'      => $tag
        ];

        if(!$this->t_sub_grade_book_tag->is_can_edit_tag($id,$data)){
            $this->t_sub_grade_book_tag->field_update_list($id,$data);
            return $this->output_succ('编辑成功');
        }else{
            return $this->output_succ('编辑失败');
        };
    }

    public function batch_dele_sub_grade_tag(){
        $id_str = $this->get_in_str_val('id_str');
        if($id_str){
            $id_str = "(".$id_str.")";
            $this->t_sub_grade_book_tag->batch_dele_tag($id_str);
        }
        return $this->output_succ('删除成功');
    }

    public function dele_sub_grade_tag(){
        $id = $this->get_in_int_val('id');
        $this->t_sub_grade_book_tag->dele_tag($id);
        return $this->output_succ('删除成功');
    }

    //添加单条学科化标签
    private function add_each_sub_tag($data){
        
        if(!$this->t_sub_grade_book_tag->is_can_add_tag($data)){
            if($this->t_sub_grade_book_tag->row_insert($data)){
                return 1;
            }else{
                return 2;
            };
        }else{
            return 0;
        }
    }

    //调整顺序
    public function order_sub_grade_tag(){
        $up_tag = $this->get_in_str_val('up_tag');
        $down_tag = $this->get_in_str_val('down_tag');
        $up_id = $this->get_in_int_val('up_id');
        $down_id = $this->get_in_int_val('down_id');
        if(!empty($up_tag) && !empty($down_tag) && $up_id && $down_id){
            $this->t_sub_grade_book_tag->field_update_list($up_id,["tag"=>$down_tag]);
            $this->t_sub_grade_book_tag->field_update_list($down_id,["tag"=>$up_tag]);
        }
        return $this->output_succ('排序成功');
    }

    public function get_rule_range(){

        $adminid  = $this->get_account_id();
        $role = $this->get_account_role();
    
        $data = [
            'subject' => [1,2,3,4,5,6,7,8,9,10],
            'grade' => [101,102,103,104,105,106,201,202,203,301,302,303],
        ];

        //判断是不是教研总监
      
        if ($adminid == 1171 ) {
            $data = [
                'subject' => [1,2,3,4,5,6,7,8,9,10],
                'grade' => [101,102,103,104,105,106,201,202,203,301,302,303],
            ];
            return $data;
        }

        //教研老师只能看他所教的科目和年级
        $info = $this->t_teacher_info->get_subject_grade_by_adminid($adminid);
        if( $info && $role == 4){
            if( $info['grade_start'] > 0 && $info['grade_end'] > 0 && $info['subject'] > 0 ){                          
                $grade_arr = \App\Helper\Utils::grade_start_end_tran_grade($info['grade_start'], $info['grade_end']);
                $grade = [];
                $data = [
                    'subject' => [(int)$info['subject']],
                ];

                if($adminid == 793){
                    array_push($data['subject'],4);
                }

                foreach( $grade_arr as $var ){
                    $grade[] = (int)$var;
                }

                $data['grade'] = $grade;
            }else{
                $data = [
                    'subject' => 0,
                    'grade'   => 0
                ];
            }
        }
        return $data;
    }

    public function resource_count(){
        $sum_field_list=[
            "file_num",
            "visit",
            "visit_rate",
            "visit_num",
            "use",
            "use_rate",
            "use_num",
            "error",
            "error_rate",
            "error_num",
            "score",
        ];
        $order_field_arr=  $sum_field_list ;
        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str($order_field_arr ,"");

        list($start_time,$end_time) = $this->get_in_date_range(-30, 0 );
        $subject = $this->get_in_int_val("subject", -1);
        $grade   = $this->get_in_int_val("grade", -1);
        $resource_type = $this->get_in_int_val("resource_type", -1);
        $teacherid     = $this->get_in_int_val("teacherid",-1); //
        $type          = $this->get_in_int_val("type",1);
        if($type == 1){
            $page_num      = $this->get_in_page_num();
            $page_count    = $page_num['page_count'];
            //dd($page_num);
            if($teacherid > 1){
                $phone = $this->t_teacher_info->get_phone($teacherid);
                $adminid = $this->t_manager_info->get_id_by_phone($phone);  
            }else{
                $adminid = -1;
            }
            
            $ret_info = $this->t_resource->get_count($start_time, $end_time, $subject, $grade, $resource_type,$adminid);
            $list = [];
            $total = [];

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
                        $mark = 1;
                        $flag = 1;
                        $subject = ($flag == 1) ? E\Esubject::get_desc($s): '';
                        $nick = ($mark == 1) ? $this->cache_get_account_nick($a): '';
                        $final_list[] = [
                            'mark'              => $mark,
                            'subject'           => $s,
                            'subject_str'       => $subject,
                            'adminid'           => $a,
                            'nick'              => $nick,
                            'grade_str'         => $grade == -1? "全部" : E\Egrade::get_desc($grade),
                            'resource_type'     => $r,
                            'resource_type_str' => E\Eresource_type::get_desc($r),
                            'file_num'          => $v['file_num'],
                            'visit_num'         => $v['visit_num'],
                            'error_num'         => $v['error_num'],
                            'use_num'           => $v['use_num'],
                            'visit'             => $v['visit'],
                            'use'               => $v['use'],
                            'error'             => $v['error'],
                            'visit_rate'        => round( $v['visit']*100/$v['file_num'], 2) ,
                            'error_rate'        => round( $v['error']*100/$v['file_num'], 2) ,
                            'use_rate'          => round( $v['use']*100/$v['file_num'], 2) ,
                            'score'             => $v['use_num']*(0.2)+$v['visit_num']*(0.2)+$v['error_num']*(0.6),
                        ];
                        $flag++;
                        $mark++;
                        // if($count < $page_count){
                        //     @$total['file_num'] += $v["file_num"];
                        //     @$total["visit_num"] += $v["visit_num"];
                        //     @$total["error_num"] += $v["error_num"];
                        //     @$total["use_num"] += $v["use_num"];
                        //     @$total["visit"] += $v["visit"];
                        //     @$total["error"] += $v["error"];
                        //     @$total["use"] += $v["use"];
                        // }
                        // ++$count;
                        
                    }
                }
            }
            

            if (!$order_in_db_flag) {
                \App\Helper\Utils::order_list( $final_list, $order_field_name, $order_type );
            }
            $count = 0;
            $start_num = ($page_num['page_num'] - 1) * $page_num['page_count'];
            $end_num   = ($page_num['page_num'] ) * $page_num['page_count'];
            foreach ($final_list as $key => $v) {

                if($start_num <= $key && $key < $end_num){
                    @$total['file_num'] += $v["file_num"];
                    @$total["visit_num"] += $v["visit_num"];
                    @$total["error_num"] += $v["error_num"];
                    @$total["use_num"] += $v["use_num"];
                    @$total["visit"] += $v["visit"];
                    @$total["error"] += $v["error"];
                    @$total["use"] += $v["use"];
                }
                ++$count;
            }
            $display = 0;
            if (@$total) {
                $display = 1;
                @$total["visit_rate"] = round( $total['visit']*100/$total['file_num'], 2) ;
                @$total["error_rate"] = round( $total['error']*100/$total['file_num'], 2) ;
                @$total["use_rate"] = round( $total['use']*100/$total['file_num'], 2) ;
            }
            //dd($final_list)
            $ret_arr = \App\Helper\Utils::array_to_page($page_num,$final_list);
            //dd($final_list);


            return $this->pageView( __METHOD__,$ret_arr, [
                "total" => @$total,
                "type"  => $type,
                "display" => $display,
            ]);
        }else if($type >= 2){
            $ret_info = $this->t_resource->get_count_new($start_time, $end_time,$type);

            $final_list = [];
            // dd($list);
            foreach($ret_info as $s=>$v){
                $arr = [
                    'mark'              => 1,
                    'file_num'          => $v['file_num'],
                    'visit_num'         => $v['visit_num'],
                    'error_num'         => $v['error_num'],
                    'use_num'           => $v['use_num'],
                    'visit'             => $v['visit'],
                    'use'               => $v['user'],
                    'error'             => $v['error'],
                    'visit_rate'        => round( $v['visit']*100/$v['file_num'], 2) ,
                    'error_rate'        => round( $v['error']*100/$v['file_num'], 2) ,
                    'use_rate'          => round( $v['user']*100/$v['file_num'], 2) ,
                    'score'             => $v['use_num']*(0.2)+$v['visit_num']*(0.2)+$v['error_num']*(0.6),
                ];
                if($type == 2){
                    $arr['adminid'] = $this->cache_get_account_nick($v["adminid"]);
                }else if($type == 3){
                    $arr['subject'] = E\Esubject::get_desc($v["subject"]);
                }else if($type == 4){
                    $arr['grade']   = E\Egrade::get_desc($v["grade"]);
                }else if($type == 5){
                    $arr['resource_type'] = E\Eresource_type::get_desc($v["resource_type"]);
                }else if($type == 6){
                    $arr['subject'] = E\Esubject::get_desc($v["subject"]); 
                    $arr['grade']   = E\Egrade::get_desc($v["grade"]);
                }
                $final_list[] = $arr;
                @$total['file_num'] += $v["file_num"];
                @$total["visit_num"] += $v["visit_num"];
                @$total["error_num"] += $v["error_num"];
                @$total["use_num"] += $v["use_num"];
                @$total["visit"] += $v["visit"];
                @$total["error"] += $v["error"];
                @$total["use"] += $v["user"];
            }
            $display = 0;
            if (@$total) {
                $display = 1;
                @$total["visit_rate"] = round( $total['visit']*100/$total['file_num'], 2) ;
                @$total["error_rate"] = round( $total['error']*100/$total['file_num'], 2) ;
                @$total["use_rate"] = round( $total['use']*100/$total['file_num'], 2) ;
            }

            if (!$order_in_db_flag) {
                \App\Helper\Utils::order_list( $final_list, $order_field_name, $order_type );
            }
            //$ret_arr = \App\Helper\Utils::array_to_page($page_num,$final_list);
            //dd($final_list);
            return $this->pageView( __METHOD__,\App\Helper\Utils::list_to_page_info($final_list), [
                "total" => @$total,
                "type"  => $type,
                "display" => $display,
            ]);
        }
        
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
        if (in_array($arr[0], [2,4,5,9]) && $level == 4) {
            $is_end = 1;
        } else if ($arr[0] == 3 && $level == 6){
            $is_end = 1;
        } else if (in_array($arr[0], [1,6,7]) && $level == 5){
            $is_end = 1;
        }

        //$data = $this->t_resource_agree_info->get_next_info($select,@$arr[0],@$arr[1],@$arr[2],@$arr[3],@$arr[4],@$arr[5],$is_end);
        if( ( @$arr[0] == 1 && $level == 5 ) || ( $arr[0] == 3 && $level == 6 ) ){
            //资源类型 1对1精品课 标准试听课
            $select = 'tag_four';
        }

        if( ( @$arr[0] == 4 && $level == 4 ) || ( $arr[0] == 5 && $level == 4 ) ){
            //资源类型 测评库 电子教材
            $select = 'tag_five';
        }

        $data = $this->t_resource->get_next_tag($select,@$arr[0],@$arr[1],@$arr[2],@$arr[3],@$arr[4],@$arr[5],$is_end);

        $tag_arr = \App\Helper\Utils::get_tag_arr();
        //对应枚举类
        $menu = '';
        foreach($data as &$item){
            if($num < 3){
                E\Egrade::set_item_field_list($item, [$select]);
            } else {
                if($arr[0] <6 || $arr[0] ==9 || ($arr[0]==6 && $num=3) ){
                    $menu = $tag_arr[ $arr[0] ][ $select ]['menu'];
                    if($menu != ''){
                        $item[$menu] = @$item[ $select ];
                        //只有resource_type=3的时候才会有num=6
                        E\Egrade::set_item_field_list($item, [$menu]);
                    }
                }
                \App\Helper\Utils::logger("教材遍历:".json_encode($item));

                \App\Helper\Utils::logger("教材学科层级num:$num,资源类型resource_type:".$arr[0]);
                if( ( $arr[0] == 1 && $num == 5 ) || ( $arr[0] == 3 && $num == 6 )) {
                    $tag =  $this->t_sub_grade_book_tag->get_tag_by_id(@$item[ $select ]);
                    $item['tag_four_str'] = '';
                    if($tag){                     
                        $item['tag_four_str'] = $tag['tag'];
                    }   

                }
            }

        }
        if($menu != ''){
            $select = $menu;
        }
        return $this->output_succ(['data' => $data,'select' => $select, 'is_end' => $is_end]);
    }

    public function get_next_tag(){
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
        if (in_array($arr[0], [2,4,5,9]) && $level == 4) {
            $is_end = 1;
        } else if ( in_array($arr[0], [1,7]) && $level == 5 ){
            $is_end = 1;
        } else if ( in_array($arr[0], [3]) && $level == 6 ){
            $is_end = 1;
        } else if ( in_array($arr[0], [6]) && $level == 7 ){
            $is_end = 1;
        }

        $data = [];
        if($select == 'subject'){
            $sub = E\Esubject::$desc_map;
            foreach($sub as $k => $var ){
                if( $k != 0 ){
                    $data[] = [
                        'subject' => $k,
                        'subject_str' => $var,
                    ];
                }
            }
        }

        if( $select == 'grade' ){
            $subject = $arr[1];
            $s_g = [
                1 => [101,102,103,104,105,106,201,202,203,301,302,303],
                2 => [101,102,103,104,105,106,201,202,203,301,302,303],
                3 => [101,102,103,104,105,106,201,202,203,301,302,303],
                4 => [203,301,302,303],
                5 => [202,203,301,302,303],
                6 => [201,202,203,301,302,303],
                7 => [201,202,203,301,302,303],
                8 => [201,202,203,301,302,303],
                9 => [201,202,203,301,302,303],
                10 => [201,202,203,301,302,303],
                11 => [201,202,203,301,302,303],
            ];

            $gra = $s_g[$subject]; 
            foreach($gra as $var ){                
                $data[] = [
                    'grade' => $var,
                    'grade_str' => E\Egrade::get_desc($var),
                ];                
            }
        }

        if( $select == 'tag_one' && $arr[0] != 7 && $arr[0] != 6 ){
            //教材
            $book = $this->t_resource_agree_info->get_all_resource_type($arr[0], $arr[1], $arr[2]);
            $book_arr = [];
            if($book){
                foreach($book as $v) {
                    if( $v['tag_one'] != 0 && $v['tag_one'] != 2016 && $v['tag_one'] != 2015){
                        array_push($book_arr, intval($v['tag_one']) );
                    }
                }
            }
            foreach($book_arr as $v){
                $data[] = [
                    'tag_one'   => (string)$v,
                    'region_version' => (string)$v,
                    'region_version_str' => E\Eregion_version::get_desc($v),
                ];
            }
            $select = "region_version";
        }

        if( $select == 'tag_one' && $arr[0] == 6 ){
            $resource = E\Eresource_volume::$desc_map;
            foreach( $resource as $k => $v){
                $data[] = [
                    'tag_two' => $arr[2],
                    'resource_volume' => $k,
                    'resource_volume_str' => $v
                ];
            }
            $select = "resource_volume";
        }

        if( $select == 'tag_two' ){
            if( in_array($arr[0], [1,2]) ){
                $resource = E\Eresource_season::$desc_map;
                foreach( $resource as $k => $v){
                    $data[] = [
                        'tag_two' => $arr[3],
                        'resource_season' => $k,
                        'resource_season_str' => $v
                    ];
                }
                $select = "resource_season";
            }
            if( $arr[0] == 3 ){
                $resource = E\Eresource_free::$desc_map;
                foreach( $resource as $k => $v){
                    $data[] = [
                        'tag_two' => $arr[3],
                        'resource_free' => $k,
                        'resource_free_str' => $v
                    ];
                }
                $select = "resource_free";
            } 
            if( in_array($arr[0], [4,5]) ){
                $resource = E\Eresource_volume::$desc_map;
                foreach( $resource as $k => $v){
                    $data[] = [
                        'tag_two' => $arr[3],
                        'resource_volume' => $k,
                        'resource_volume_str' => $v
                    ];
                }
                $select = "resource_volume";

            }

            if( $arr[0] == 6 ){
       
                $data = [
                    [
                        'tag_two' => $arr[3],
                        'resource_train' => 0,
                        'resource_train_str' => "按教材版本分类"
                    ],[
                        'tag_two' => $arr[3],
                        'resource_train' => 1,
                        'resource_train_str' => "按省市分类"
                    ]
                ];
                
                $select = "resource_train";
            } 

            if( $arr[0] == 9 ){
                $resource = E\Eresource_train::$desc_map;
                foreach( $resource as $k => $v){
                    $data[] = [
                        'tag_two' => $arr[3],
                        'resource_train' => $k,
                        'resource_train_str' => $v
                    ];
                }
                $select = "resource_train";
            } 
            
        }

        if( $select == 'tag_three' ){
            if( $arr[0] == 1 ){              
                $tag_arr = $this->t_sub_grade_book_tag->get_tag_by_sub_grade($arr[1],$arr[2],$arr[3],$arr[0],$arr[4]);
                //dd($tag_arr);
                foreach($tag_arr as $v){
                    $data[] = [
                        'tag_four' => $v['id'],
                        'tag_four_str' => $v['tag']
                    ];
                }
                $select = 'tag_four';
            }

            if( $arr[0] == 3 ){
                $resource = E\Eresource_diff_level::$desc_map;
                foreach( $resource as $k => $v){
                    $data[] = [
                        'tag_two' => $arr[3],
                        'resource_diff_level' => $k,
                        'resource_diff_level_str' => $v
                    ];
                }
                $select = "resource_diff_level";
            }

            if( $arr[0] == 6 ){
                //试卷库
                if($arr[4] == 0){
                    //教材
                    $book = $this->t_resource_agree_info->get_all_resource_type($arr[0], $arr[1], $arr[2]);
                    $book_arr = [];
                    if($book){
                        foreach($book as $v) {
                            if( $v['tag_one'] != 0 && $v['tag_one'] != 2016 && $v['tag_one'] != 2015){
                                array_push($book_arr, intval($v['tag_one']) );
                            }
                        }
                    }
                    foreach($book_arr as $v){
                        $data[] = [
                            'tag_one'   => (string)$v,
                            'region_version' => (string)$v,
                            'region_version_str' => E\Eregion_version::get_desc($v),
                        ];
                    }
                    $select = "region_version";
                }

                if($arr[4] == 1 ){
                    $data[] = [
                        'tag_three' => $arr[3]
                    ];
                    $select = "get_province";
                }
            } 

        }

        if( $select == 'tag_four' ){
            if( $arr[0] == 3 ){
                $tag_arr = $this->t_sub_grade_book_tag->get_tag_by_sub_grade($arr[1],$arr[2],$arr[3],$arr[0],-1);
                //dd($tag_arr);
                foreach($tag_arr as $v){
                    $data[] = [
                        'tag_four' => $v['id'],
                        'tag_four_str' => $v['tag']
                    ];
                }
                $select = 'tag_four';
            }

            if( $arr[0] == 6 && $arr[4] == 1 ){
                $data[] = [
                    'tag_four' => $arr[4]
                ];
                $select = "get_city";
            }

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

    //添加教材
    public function add_book_resource(){
        $info_str = $this->get_in_str_val('info_str','');
        $arr      = explode('-', $info_str);
        $region   = $this->get_in_str_val('book','');
        $resource = $this->get_in_str_val('resource','');

        $adminid  = $this->get_account_id();
        $time     = time();
        $ban_level = count($arr);

        $s_g = [
            1 => [ [101,102,103,104,105,106] , [201,202,203], [301,302,303] ],
            2 => [ [101,102,103,104,105,106] , [201,202,203], [301,302,303] ],
            3 => [ [101,102,103,104,105,106] , [201,202,203], [301,302,303] ],
            4 => [ [203], [301,302,303] ],
            5 => [ [202,203], [301,302,303] ],
            6 => [ [201,202,203], [301,302,303] ],
            7 => [ [201,202,203], [301,302,303] ],
            8 => [ [201,202,203], [301,302,303] ],
            9 => [ [201,202,203], [301,302,303] ],
            10 => [ [201,202,203], [301,302,303] ],
            11 => [ [201,202,203], [301,302,303] ],
        ];

        $resource = explode(',', $resource);
        $text_book = explode(',', $region);
        $modify_grade = [];

        if( in_array($arr[0],$resource) ) {
            $grade = @$arr[2];
            if($grade){                    
                foreach( $s_g[ $arr[1] ] as $k => $g_arr){
                    if(in_array($grade, $g_arr)){
                        $modify_grade = $g_arr;
                    }
                }           
            }
        }

        if( in_array($arr[0],$resource) ) {
            foreach( $resource as $type){                      
                foreach($modify_grade as $grade){
                    foreach($text_book as $book){                                            
                        $data = [
                            'resource_type' => $type,
                            'subject'       => $arr[1],
                            'grade'         => $grade,
                            'tag_one'       => $book,
                            'agree_adminid' => $adminid,
                            'agree_time'    => $time,
                        ];

                        $is_exit = $this->t_resource_agree_info->get_exit($data);
                        if(!$is_exit){
                            $this->t_resource_agree_info->row_insert($data);
                        }
                    }
                }
            }
        }
        return $this->output_succ();
    }

    public function get_resource_type(){
        $ret  = \App\Helper\Utils::list_to_page_info([]);
        $data = [];
        $re_book = [1,2,3,4,5,6,9];
        foreach( $re_book as $v){
            $data[] = [
                'resource_id' => $v,
                'resource_type' => E\Eresource_type::get_desc($v)
            ];
        }

        $ret['list'] = $data;
        return $this->output_ajax_table($ret);
    }

    public function add_or_del_or_edit_new(){
        $info_str = $this->get_in_str_val('info_str','');
        $region   = $this->get_in_int_val('region','');
        $do_type  = $this->get_in_str_val('do_type','');
        $resource = $this->get_in_str_val('resource','');
        $arr      = explode('-', $info_str);
        $adminid  = $this->get_account_id();
        $time     = time();
        $ban_level = count($arr);

        $return = [ 'status'=>200 ];

        //暂时使用
        $s_g = [
            1 => [ [101,102,103,104,105,106] , [201,202,203], [301,302,303] ],
            2 => [ [101,102,103,104,105,106] , [201,202,203], [301,302,303] ],
            3 => [ [101,102,103,104,105,106] , [201,202,203], [301,302,303] ],
            4 => [ [203], [301,302,303] ],
            5 => [ [202,203], [301,302,303] ],
            6 => [ [201,202,203], [301,302,303] ],
            7 => [ [201,202,203], [301,302,303] ],
            8 => [ [201,202,203], [301,302,303] ],
            9 => [ [201,202,203], [301,302,303] ],
            10 => [ [201,202,203], [301,302,303] ],
            11 => [ [201,202,203], [301,302,303] ],
        ];

        $resource = explode(',', $resource);
        $modify_grade = [];
        if( in_array($arr[0],$resource) ) {
            $grade = @$arr[2];
            if($grade){                    
                foreach( $s_g[ $arr[1] ] as $k => $g_arr){
                    if(in_array($grade, $g_arr)){
                        $modify_grade = $g_arr;
                    }
                }           
            }
        }

        if($do_type === 'add'){//添加版本
            if( in_array($arr[0],$resource) ) {
                foreach( $resource as $r_type){                      
                    foreach($modify_grade as $grade){
                        $this->t_resource_agree_info->row_insert([
                            'resource_type' => $r_type,
                            'subject'       => $arr[1],
                            'grade'         => $grade,
                            'tag_one'       => $region,
                            'agree_adminid' => $adminid,
                            'agree_time'    => $time,
                        ]);
                    }
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
                $return['status'] = 201;
            } else {
                return $this->output_err("信息有误,删除失败!");
            }
        }

        return $this->output_succ($return);

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
        $tag_five      = $this->get_in_int_val('tag_five',0);
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
                'tag_five'      => $tag_five,
                'adminid'       => $adminid,
                'create_time'   => $time,
            ]);
        }
        $last_id_arr = $this->t_resource->get_latest_id($add_num);
        $resource_id_arr = array_column($last_id_arr, 'resource_id');
        return $this->output_succ(['resource_id_arr' => json_encode($resource_id_arr)]);
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

    public function add_multi_file() {
        $multi_data = $this->get_in_str_val('multi_data');
        $is_reupload   = $this->get_in_int_val('is_reupload', 0);

        if( is_string($multi_data)){
            $multi_data = json_decode($multi_data,true);
        }
        if( $multi_data && is_array($multi_data)){
            foreach( $multi_data as $data){
                if( array_key_exists("file_title", $data) && array_key_exists("file_type", $data)){                                   
                    $ex_num        = 0;
                    //处理文件名
                    $file_title = &$data['file_title'];
                    $dot_pos = strrpos($file_title,'.');
                    $file_title = substr($file_title,0,$dot_pos);
                    //处理文件类型
                    $file_type = trim( strrchr($data['file_type'], '/'), '/' );
                    $resource_id = $data['resource_id'];
                    $file_use_type = $data['file_use_type'];
                    $file_size = round( $data['file_size']/1024, 2);
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
                    $insert_data = [
                        'resource_id'   => $resource_id,
                        'file_title'    => $file_title,
                        'file_type'     => $file_type,
                        'file_size'     => $file_size,
                        'file_hash'     => $data['file_hash'],
                        'file_link'     => $data['file_link'],
                        'file_use_type' => $file_use_type,
                        'ex_num'        => $ex_num,

                    ];
                    $this->t_resource_file->row_insert($insert_data);
                    $file_id = $this->t_resource_file->get_last_insertid();
                    $adminid = $this->get_account_id();
                    $this->t_resource_file_visit_info->row_insert([
                        'file_id'     => $file_id,
                        'visit_type'  => 9,
                        'create_time' => time(),
                        'visitor_id'  => $adminid,
                        'ip'          => $_SERVER["REMOTE_ADDR"],
                    ]);                
                }
            }
        }
        return $this->output_succ();
        \App\Helper\Utils::logger("多文件: ".json_encode($multi_data));
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
        $file_id       = $this->get_in_int_val('file_id',0);
        $file_title    = trim($this->get_in_str_val('file_title'));
        $file_hash     = $this->get_in_str_val('file_hash');
        $file_size     = round( $this->get_in_int_val('file_size')/1024, 2);
        $file_type     = $this->get_in_str_val('file_type');
        $file_link     = $this->get_in_str_val('file_link');
        $file_use_type = $this->get_in_int_val('file_use_type');

        $ex_num        = $this->get_in_int_val('ex_num', 0);
        $reupload      = $this->get_in_int_val('reupload', 0);
        $adminid       = $this->get_account_id();
        $time          = time();
        $is_wx         = $this->get_in_int_val("is_wx",0);
        $error_id_str  = $this->get_in_str_val("error_id_arr",-1);
        if($file_id != 0){
            $this->t_resource_file->field_update_list($file_id, [
                'file_title' => $file_title,
                'file_hash'  => $file_hash,
                'file_size'  => $file_size,
                'file_type'  => $file_type,
                'file_link'  => $file_link,
                'file_use_type'  => $file_use_type,
            ]);
            $visit_type = 2;
        } else {//添加额外文件
            $visit_type = 9;
            $this->set_in_value('is_reupload', 1);
            $file_id = $this->add_file();
        }

        $this->t_resource_file_visit_info->row_insert([
            'file_id'     => $file_id,
            'visit_type'  => $visit_type,
            'create_time' => $time,
            'visitor_id'  => $adminid,
            'ip'          => $_SERVER["REMOTE_ADDR"],
        ]);
        \App\Helper\Utils::logger("wrong id:".json_encode($error_id_str));
        if($is_wx > 0 && $error_id_str > 0){
            $teacher_url = ''; //待定
            $template_id_teacher  = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";  // 待办事项
              
            $reload_adminid = $this->t_resource_file->get_reload_adminid($file_id);
            if($reload_adminid){
                $reload_phone = $this->t_manager_info->get_phone($reload_adminid);
                $reload_nick = $this->t_manager_info->get_name($reload_adminid);
                $reload_wx = $this->t_teacher_info->get_wx_openid_by_phone($reload_phone);

                \App\Helper\Utils::logger("重传人手机:$reload_phone 微信$reload_wx");

                $data['first']      = " 您好，$reload_nick 老师，您负责的讲义“ $file_title ”已被理优更改，感谢您对理优的监督与支持。";
                $data['keyword1']   = " 讲义重传通知";
                $data['keyword2']   = " 请随时查看理优新的讲义资料";
                $data['keyword3']   = date('Y-m-d');
                $data['remark']     = "让我们共同努力，让理优明天更美好";
                \App\Helper\Utils::send_teacher_msg_for_wx($reload_wx,$template_id_teacher,
                                                           $data,$teacher_url);
            }

            $error_id_arr = is_array($error_id_str) ? $error_id_str : json_decode($error_id_str,true);
            foreach($error_id_arr as $k => $error){         
                if( !$error ) {
                    unset( $error_id_arr[$k] );
                }                 
            }
            $errid_str = join(',',$error_id_arr);
            $errid_str = "(".$errid_str.")";
            $info = $this->t_resource_file->get_teacherinfo_new($errid_str);
            $wx_openid = "";
            if($info){      
                foreach( $info as $var ){
                    if( $wx_openid != $var['wx_openid'] ){
                        \App\Helper\Utils::logger("admin do sth:".json_encode($var));
                        $wx_openid = $var['wx_openid'];                        
                        $file_name    = $var['file_title'];
                        $teacher_nick = $var['nick'];
                        $data['first']      = " 您好，$teacher_nick 老师，您报错的讲义“ $file_name ”已被理优更改，感谢您对理优的监督与支持。";
                        $data['keyword1']   = " 讲义重传通知";
                        $data['keyword2']   = " 请随时查看理优新的讲义资料";
                        $data['keyword3']   = date('Y-m-d');
                        $data['remark']     = "让我们共同努力，让理优明天更美好";
                        \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id_teacher,
                                                                   $data,$teacher_url);
                    }
                }              
            }
        }

        if( $reupload == 3 ){
            $data = $this->t_resource_file_error_info->get_error_by_file_id($file_id);
            $return = [];
            if($data){
                foreach($data as $var){
                    $this->t_resource_file_error_info->field_update_list($var['id'],[
                        "status" => 2,
                        "reupload_adminid" => $adminid,
                        "reupload_time"  => $time,
                    ]);

                }
                $return['reupload_time'] = date("Y-m-d h:i:s", $time);
                $return['reupload_name'] = $this->t_manager_info->get_name($adminid);
            }
            return $this->output_succ($return);
        }else{
            return $this->output_succ();
        }
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
                    $this->t_resource_file->update_file_status($id, 2);
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
        $resource_type = $this->get_in_int_val('resource_type',1);
        $subject       = $this->get_in_int_val('subject', -1);
        $grade         = $this->get_in_int_val('grade', -1);
        $tag_one       = $this->get_in_int_val('tag_one', -1);
        $tag_two       = $this->get_in_int_val('tag_two', -1);
        $tag_three     = $this->get_in_int_val('tag_three', -1);
        $tag_four      = $this->get_in_int_val('tag_four', -1);
        $tag_five      = $this->get_in_int_val('tag_five', -1);
        $file_title    = $this->get_in_str_val('file_title', '');
        $is_del        = $this->get_in_int_val('is_del', 1);
        $status        = $this->get_in_int_val('status', 1);
        $page_info     = $this->get_in_page_info();

        $ret_info = $this->t_resource->get_all(
            $use_type ,$resource_type, $subject, $grade, $tag_one, $tag_two, $tag_three, $tag_four,$tag_five,$file_title, $page_info,$is_del,$status
        );
        $r_mark = 0;
        $index  = 1;
        $tag_arr = \App\Helper\Utils::get_tag_arr( $resource_type );

        // dd($ret_info);
        $tag_arr = \App\Helper\Utils::get_tag_arr($resource_type);
        foreach($ret_info['list'] as &$item){
            if($r_mark == $item['resource_id']){
                $index++;
            } else {
                $r_mark = $item['resource_id'];
                $index = 1;
            }
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            \App\Helper\Utils::get_file_use_type_str($item, $index);
            $item['nick'] = $this->cache_get_account_nick($item['visitor_id']);
            $item['file_size'] = round( $item['file_size'] / 1024,2);

            $item['tag_one_name'] = $tag_arr['tag_one']['name'];
            $item['tag_two_name'] = $tag_arr['tag_two']['name'];
            $item['tag_three_name'] = $tag_arr['tag_three']['name'];
            $item['tag_four_name'] = @$tag_arr['tag_four']['name'];
            $item['tag_five_name'] = @$tag_arr['tag_five']['name'];
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
                $tag_arr['tag_five']['menu'] => 'tag_five',
            ]);
            $item['tag_one_str'] = E\Eregion_version::get_desc($item['tag_one']);

            if( $item['resource_type'] == 1 ){
                $item['tag_five_str'] = E\Eresource_diff_level::get_desc($item['tag_five']);
            }else{
                $item['tag_five_str'] = E\Eresource_volume::get_desc($item['tag_five']);
            }
            if($item['resource_type'] == 3 ) {
                $item['tag_three_str'] = E\Eresource_diff_level::get_desc($item['tag_three']);
            }

        }

        return $this->pageView( __METHOD__,$ret_info,['tag_info' => $tag_arr]);
    }

    //查询被彻底删除的文件
    public function get_total_del() {

        $use_type      = $this->get_in_int_val('use_type', -1);
        $resource_type = $this->get_in_int_val('resource_type',-1);
        $subject       = $this->get_in_int_val('subject', -1);
        $grade         = $this->get_in_int_val('grade', -1);
        $file_title    = $this->get_in_str_val('file_title', '');
        $is_del        = $this->get_in_int_val('is_del', -1);
        $status        = $this->get_in_int_val('status', 2);
        $page_info     = $this->get_in_page_info();

        $ret_info = $this->t_resource->get_total_del(
            $use_type ,$resource_type, $subject, $grade,$file_title, $page_info,$is_del,$status
        );
        $r_mark = 0;
        $index  = 1;
        $tag_arr = \App\Helper\Utils::get_tag_arr( $resource_type );

        // dd($ret_info);
        $tag_arr = \App\Helper\Utils::get_tag_arr($resource_type);
        foreach($ret_info['list'] as &$item){
            if($r_mark == $item['resource_id']){
                $index++;
            } else {
                $r_mark = $item['resource_id'];
                $index = 1;
            }
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            \App\Helper\Utils::get_file_use_type_str($item, $index);
            $item['nick'] = $this->cache_get_account_nick($item['visitor_id']);
            $item['file_size'] = round( $item['file_size'] / 1024,2);

            $item['tag_one_name'] = $tag_arr['tag_one']['name'];
            $item['tag_two_name'] = $tag_arr['tag_two']['name'];
            $item['tag_three_name'] = $tag_arr['tag_three']['name'];
            $item['tag_four_name'] = @$tag_arr['tag_four']['name'];
            $item['tag_five_name'] = @$tag_arr['tag_five']['name'];
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
                $tag_arr['tag_five']['menu'] => 'tag_five',
            ]);
            $item['tag_one_str'] = E\Eregion_version::get_desc($item['tag_one']);

            if( $item['resource_type'] == 1 ){
                $item['tag_five_str'] = E\Eresource_diff_level::get_desc($item['tag_five']);
            }else{
                $item['tag_five_str'] = E\Eresource_volume::get_desc($item['tag_five']);
            }
            if($item['resource_type'] == 3 ) {
                $item['tag_three_str'] = E\Eresource_diff_level::get_desc($item['tag_three']);
            }

        }

        return $this->pageView( __METHOD__,$ret_info,['tag_info' => $tag_arr]);
    }

    public function batch_del_resource(){
        $res_id_str  = $this->get_in_str_val('res_id_str','');
        $file_id_str = $this->get_in_str_val('file_id_str','');
        $file_link_str = $this->get_in_str_val('file_link_str','');
        $type   = $this->get_in_str_val('type','');

        $adminid = $this->get_account_id();
        $time    = time();
        if($res_id_str != '') {
            $res_id_arr  = !is_array($res_id_str) ? $this->str_to_arr($res_id_str) : $res_id_str;
            $file_id_arr = !is_array($file_id_str) ? $this->str_to_arr($file_id_str) : $file_id_str;
            

            if(!is_array($file_link_str)){
                $file_link_arr = $this->str_to_arr($file_link_str);
            }else{
                $file_link_arr = $file_link_str;
            }

            $id_str = "";
            foreach($res_id_arr as $id){
                if( $id != ''){
                    $id_str .= $id.',';
                }
            }

            //删除文件
            foreach( $file_link_arr as $file){
                $file_name = trim($file);
                $file_name = ltrim($file_name,'"');
                $file_name = rtrim($file_name,'"');

                $exits = \App\Helper\Utils::qiniu_teacher_file_stat($file_name);
                if($file_name && $exits){
                    $return = \App\Helper\Utils::qiniu_teacher_file_del($file_name);
                }
            }
            if($id_str){
                $id_str = "(".substr($id_str,0,-1).")";
                //echo $id_str;
                $this->t_resource->batch_del($id_str);
                $this->t_resource_file->batch_del($id_str);
            }
            // foreach($file_id_arr as $file_id){
            //     $this->t_resource_file_visit_info->row_insert([
            //         'file_id'     => $file_id,
            //         'visit_type'  => $type,
            //         'create_time' => $time,
            //         'visitor_id'  => $adminid,
            //         'ip'          => $_SERVER["REMOTE_ADDR"],
            //     ]);
            // }
            return $this->output_succ();
        }

    }

    public function batch_del_file(){
        $file_link_str = $this->get_in_str_val('file_link_str','');
        if($file_link_str){
            //删除文件
            if(!is_array($file_link_str)){
                $file_link_arr = $this->str_to_arr($file_link_str);
            }else{
                $file_link_arr = $file_link_str;
            }
            foreach( $file_link_arr as $file){
                $file_name = trim($file);
                $file_name = ltrim($file_name,'"');
                $file_name = rtrim($file_name,'"');
                //echo $file_name;
                $exits = \App\Helper\Utils::qiniu_teacher_file_stat($file_name);
                if( $file_name && $exits){
                    $result = \App\Helper\Utils::qiniu_teacher_file_del($file_name);
                }
            }
        }     
        return $this->output_succ();
    }

    public function total_del_file(){
        $file_name      = trim($this->get_in_str_val('file_name'));
        $tea_res_id = $this->get_in_int_val("tea_res_id");

        //预览理优资料
        $file_link = $this->t_resource_file->get_file_link($tea_res_id);
        if(!$file_link){
            return $this->output_err('信息有误，预览失败！');
        }

        $store=new \App\FileStore\file_store_tea();
        $auth=$store->get_auth();
        $authUrl = $auth->privateDownloadUrl("http://teacher-doc.leo1v1.com/".$file_link );

        $exits = \App\Helper\Utils::qiniu_teacher_file_stat($file_name);

        if($file_name && $exits){
            $result = ['status' => 200,'url' => $authUrl ];
        }else{
            $result = ['status' => 500,'url' => $authUrl ];
        }
        return $this->output_succ($result);
 
    }

    public function get_comment(){
        $file_id = $this->get_in_int_val("file_id");

        $result = ['status'=>201];

        $data = $this->t_resource_file_evalutation->get_list($file_id);

        if($data){
            $comment_num = count($data); //评价人数
            $comment = [
                'status'=>200,'comment_num' => $comment_num,
                "quality_score_1" => 0,"quality_score_2" => 0,"quality_score_3" => 0,"quality_score_4" => 0,"quality_score_5" => 0,
                "quality_score_rate_1" => 0,"quality_score_rate_2" => 0,"quality_score_rate_3" => 0,"quality_score_rate_4" => 0,"quality_score_rate_5" => 0,
                "quality_score_average" => 0,

                "help_score_1" => 0,"help_score_2" => 0,"help_score_3" => 0,"help_score_4" => 0,"help_score_5" => 0,
                "help_score_rate_1" => 0,"help_score_rate_2" => 0,"help_score_rate_3" => 0,"help_score_rate_4" => 0,"help_score_rate_5" => 0,
                "help_score_average" => 0,

                "overall_score_1" => 0,"overall_score_2" => 0,"overall_score_3" => 0,"overall_score_4" => 0,"overall_score_5" => 0,
                "overall_score_rate_1" => 0,"overall_score_rate_2" => 0,"overall_score_rate_3" => 0,"overall_score_rate_4" => 0,"overall_score_rate_5" => 0,
                "overall_score_average" => 0,

                "detail_score_1" => 0,"detail_score_2" => 0,"detail_score_3" => 0,"detail_score_4" => 0,"detail_score_5" => 0,
                "detail_score_rate_1" => 0,"detail_score_rate_2" => 0,"detail_score_rate_3" => 0,"detail_score_rate_4" => 0,"detail_score_rate_5" => 0,
                "detail_score_average" => 0,
 
                "size_score_1" => 0,"size_score_2" => 0,"size_score_3" => 0,
                "size_score_rate_1" => 0,"size_score_rate_2" => 0,"size_score_rate_3" => 0,

                "gap_score_1" => 0,"gap_score_2" => 0,"gap_score_3" => 0,
                "gap_score_rate_1" => 0,"gap_score_rate_2" => 0,"gap_score_rate_3" => 0,

                "bg_score_1" => 0,"bg_score_2" => 0,"bg_score_3" => 0,
                "bg_score_rate_1" => 0,"bg_score_rate_2" => 0,"bg_score_rate_3" => 0,

                "type_score_1" => 0,"type_score_2" => 0,"type_score_3" => 0,
                "type_score_rate_1" => 0,"type_score_rate_2" => 0,"type_score_rate_3" => 0,

                "answer_score_1" => 0,"answer_score_2" => 0,"answer_score_3" => 0,
                "answer_score_rate_1" => 0,"answer_score_rate_2" => 0,"answer_score_rate_3" => 0,

                "suit_score_1" => 0,"suit_score_2" => 0,"suit_score_3" => 0,
                "suit_score_rate_1" => 0,"suit_score_rate_2" => 0,"suit_score_rate_3" => 0,
            ];

            $time = [
                "time_1"=>0,"time_2"=>0,"time_3"=>0,"time_4"=>0,"time_5"=>0,"time_6"=>0,"time_7"=>0,"time_8"=>0,"time_9"=>0,"time_10"=>0,"time_11"=>0,
                "time_rate_1"=>0,"time_rate_2"=>0,"time_rate_3"=>0,"time_rate_4"=>0,"time_rate_5"=>0,"time_rate_6"=>0,
                "time_rate_7"=>0,"time_rate_8"=>0,"time_rate_9"=>0,"time_rate_10"=>0,"time_rate_11"=>0,
            ];
            foreach($data as $var){
                $this->get_comment_num($var['quality_score'],$comment['quality_score_1'],$comment['quality_score_2'],
                                       $comment['quality_score_3'],$comment['quality_score_4'],$comment['quality_score_5']);

                $this->get_comment_num($var['help_score'],$comment['help_score_1'],$comment['help_score_2'],
                                       $comment['help_score_3'],$comment['help_score_4'],$comment['help_score_5']);

                $this->get_comment_num($var['overall_score'],$comment['overall_score_1'],$comment['overall_score_2'],
                                       $comment['overall_score_3'],$comment['overall_score_4'],$comment['overall_score_5']);

                $this->get_comment_num($var['detail_score'],$comment['detail_score_1'],$comment['detail_score_2'],
                                       $comment['detail_score_3'],$comment['detail_score_4'],$comment['detail_score_5']);

                $this->get_comment_num($var['size'],$comment['size_score_1'],$comment['size_score_2'],$comment['size_score_3']);

                $this->get_comment_num($var['gap'],$comment['gap_score_1'],$comment['gap_score_2'],$comment['gap_score_3']);

                $this->get_comment_num($var['bg_picture'],$comment['bg_score_1'],$comment['bg_score_2'],$comment['bg_score_3']);

                $this->get_comment_num($var['text_type'],$comment['type_score_1'],$comment['type_score_2'],$comment['type_score_3']);

                $this->get_comment_num($var['answer'],$comment['answer_score_1'],$comment['answer_score_2'],$comment['answer_score_3']);

                $this->get_comment_num($var['suit_student'],$comment['suit_score_1'],$comment['suit_score_2'],$comment['suit_score_3']);

                $this->get_time_length($time,$var['time_length'],$var['resource_type']);
             
            }

            $this->get_comment_average_score($comment_num,$comment['quality_score_average'],$comment['quality_score_1'],$comment['quality_score_2'],
                                   $comment['quality_score_3'],$comment['quality_score_4'],$comment['quality_score_5']);

            $this->get_comment_average_score($comment_num,$comment['help_score_average'],$comment['help_score_1'],$comment['help_score_2'],
                                   $comment['help_score_3'],$comment['help_score_4'],$comment['help_score_5']);

            $this->get_comment_average_score($comment_num,$comment['overall_score_average'],$comment['overall_score_1'],$comment['overall_score_2'],
                                   $comment['overall_score_3'],$comment['overall_score_4'],$comment['overall_score_5']);

            $this->get_comment_average_score($comment_num,$comment['detail_score_average'],$comment['detail_score_1'],$comment['detail_score_2'],
                                   $comment['detail_score_3'],$comment['detail_score_4'],$comment['detail_score_5']);

            $comment = array_merge($comment,$time);

            $comment = $this->get_comment_average_num($comment);

            return $this->output_succ($comment);
        }

        return $this->output_succ($result);
    }

    private function get_comment_num($score,&$val_1,&$val_2,&$val_3,&$val_4 = null,&$val_5 = null){
        switch($score){
            case 1:
                $val_1 += 1;
                break;
            case 2:
                $val_2 += 1;
                break;
            case 3:
                $val_3 += 1;
                break;
            case 4:
                $val_4 += 1;
                break;
            case 5:
                $val_5 += 1;
                break;
                
            default:
                break;
            }
    }

    private function get_time_length(&$time,$time_length,$resource_type){
        if($resource_type == 3){
            switch(trim($time_length)){
            case "30分钟":
                $time['time_1'] += 1;
                break;
            case "40分钟":
                $time['time_2'] += 1;
                break;
            case "50分钟":
                $time['time_3'] += 1;
                break;
            case "60分钟":
                $time['time_4'] += 1;
                break;
            case "70分钟":
                $time['time_5'] += 1;
                break;
            case "80分钟":
                $time['time_6'] += 1;
                break;
            case "其他":
                $time['time_7'] += 1;
                break;  
            default:
                $time['time_8'] += 1;
                break;
            }

        }else{
            switch(trim($time_length)){
            case "90分钟":
                $time['time_1'] += 1;
                break;
            case "100分钟":
                $time['time_2'] += 1;
                break;
            case "110分钟":
                $time['time_3'] += 1;
                break;
            case "120分钟":
                $time['time_4'] += 1;
                break;
            case "130分钟":
                $time['time_5'] += 1;
                break;
            case "140分钟":
                $time['time_6'] += 1;
                break;
            case "150分钟":
                $time['time_7'] += 1;
                break;
            case "160分钟":
                $time['time_8'] += 1;
                break;
            case "170分钟":
                $time['time_9'] += 1;
                break;
            case "180分钟":
                $time['time_10'] += 1;
                break;
            case "其他":
                $time['time_11'] += 1;
                break;
            default:
                $time['time_11'] += 1;
                break;
            }

        }
    }

    private function get_comment_average_score($num,&$score,&$val_1,&$val_2,&$val_3,&$val_4 = 0,&$val_5 = 0){
        $score = round ( ($val_1*1 + $val_2*2 + $val_3*3 + $val_4*4 + $val_5*5 )/$num ,1 );
    }

    private function get_comment_average_num($comment){
        $comment_num = $comment['comment_num'];
        foreach( $comment as $k => $v){
            $pos = strpos($k,'rate');
            if($pos > 0){
                $score = substr($k,0,$pos).substr($k,$pos+5);
                $ave = round ( ($comment[$score] / $comment_num),4)*100;
                $comment[$k] = $ave."%";
            }
        }
        return $comment;
    }

    //预览
    public function tea_look_resource() {
        $tea_res_id = $this->get_in_int_val("tea_res_id");
        //预览理优资料
        $file_link = $this->t_resource_file->get_file_link($tea_res_id);
        if(!$file_link){
            return $this->output_err('信息有误，预览失败！');
        }

        $store=new \App\FileStore\file_store_tea();
        $auth=$store->get_auth();
        $authUrl = $auth->privateDownloadUrl("http://teacher-doc.leo1v1.com/".$file_link );
        return $this->output_succ(["url" => $authUrl]);
        
    }

}
