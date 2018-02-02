<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;


class resource_new extends Controller
{

    use CacheNick;
    var $check_login_flag=true;

    function __construct( ) {
        parent::__construct();
    }
    public function get_rule_range(){

        $adminid  = $this->get_account_id();
        $role = $this->get_account_role();
    
        $data = [
            'subject' => [1,2,3,4,5,6,7,8,9,10],
            'grade' => [101,102,103,104,105,106,201,202,203,301,302,303],
        ];

        //判断是不是总监
        // $is_master = $this->t_admin_majordomo_group_name->is_master($adminid);
        // if ($is_master) {
        //     $data = [
        //         'subject' => [1,2,3,4,5,6,7,8,9,10],
        //         'grade' => [101,102,103,104,105,106,201,202,203,301,302,303],
        //     ];
        //     return $data;
        // }

        //判断是不是主管
        // $is_zhuguan = $this->t_admin_main_group_name->is_master($adminid);
        // if ($is_zhuguan) {
        //     $info = $this->t_teacher_info->get_subject_grade_by_adminid($adminid);
        //     $data = [
        //         'subject' => $info['subject'],
        //         'grade' => [101,102,103,104,105,106,201,202,203,301,302,303],
        //     ];

        //     return $data;
        // }

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

    public function get_error() {
        list($start_time,$end_time) = $this->get_in_date_range( 0,0,0,[],3);
        $error_type    = $this->get_in_int_val("error_type",-1);
        $sub_error_type= $this->get_in_int_val("sub_error_type",-1);
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
        $page_info     = $this->get_in_page_info();

        if($use_type == 1){
            $resource_type = ($resource_type<1)?1:$resource_type;
            $resource_type = ($resource_type>7)?7:$resource_type;
        }else if($use_type == 2){
            $resource_type = 9;
        }else{
            $resource_type = 8;
        }
        $ret_info = $this->t_resource->get_all_error($start_time,$end_time,$error_type,$sub_error_type,
            $use_type ,$resource_type, $subject, $grade, $tag_one, $tag_two, $tag_three, $tag_four,$tag_five, $page_info
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
            \App\Helper\Utils::unixtime2date_for_item($item,"c_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            \App\Helper\Utils::get_file_use_type_str($item, $index);
            $item['nick'] = $this->cache_get_account_nick($item['visitor_id']);


            //培训库
            if($item['etype'] == 9){

            }else{
                $item['error_type_str'] = E\Eresource_error::get_desc($item['error_type']);
                $item['sub_error_type_str'] = \App\Helper\Utils::get_sub_error_type($item['error_type'],$item['sub_error_type']);
            }

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
         
        }
        //dd($ret_info['list']);

        //获取所有开放的教材版本
        //$book = $this->t_resource_agree_info->get_all_resource_type();
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

        return $this->pageView( __METHOD__,$ret_info,[
            '_publish_version'    => 20180131161439,
            'tag_info'      => $tag_arr,
            'subject'       => json_encode($sub_grade_info['subject']),
            'grade'         => json_encode($sub_grade_info['grade']),
            'book'          => json_encode($book_arr),
            'resource_type' => $resource_type,
            'is_teacher'   => $is_teacher,
        ]);
    } 



}
