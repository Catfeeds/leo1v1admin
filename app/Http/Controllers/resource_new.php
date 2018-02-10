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
            'identity'  => 'other'
        ];

        //判断是不是总监
        $is_master = $this->t_admin_majordomo_group_name->is_master($adminid);
        if ($is_master) {
            $data = [
                'subject' => [1,2,3,4,5,6,7,8,9,10],
                'grade' => [101,102,103,104,105,106,201,202,203,301,302,303],
                'identity'  => 'major'
            ];
            return $data;
        }

        //教研老师只能看他所教的科目和年级
        $info = $this->t_teacher_info->get_subject_grade_by_adminid($adminid);
        if( $info && $role == 4){
            if( $info['grade_start'] > 0 && $info['grade_end'] > 0 && $info['subject'] > 0 ){

                //判断是不是主管
                $is_zhuguan = $this->t_admin_main_group_name->is_master($adminid);
                if ($is_zhuguan) {
                    $info = $this->t_teacher_info->get_subject_grade_by_adminid($adminid);
                    $data = [
                        'subject' => [(int)$info['subject']],
                        'grade' => [101,102,103,104,105,106,201,202,203,301,302,303],
                        'identity'  => 'director'
                    ];

                    return $data;
                }

                //普通组员
                $grade_arr = \App\Helper\Utils::grade_start_end_tran_grade($info['grade_start'], $info['grade_end']);
                $grade = [];
                $data = [
                    'subject' => [(int)$info['subject']],
                    'identity'  => 'member'
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
        $file_id       = intval($this->get_in_str_val("file_id",-1));
        $page_info     = $this->get_in_page_info();

        if( $error_type == -1){
            $sub_error_type = -1;
        }
        if($use_type == 1){
            $resource_type = ($resource_type<1)?1:$resource_type;
            $resource_type = ($resource_type>7)?7:$resource_type;
        }else if($use_type == 2){
            $resource_type = 9;
        }else{
            $resource_type = 8;
        }
        $ret_info = $this->t_resource->get_all_error($start_time,$end_time,$error_type,$sub_error_type,
            $file_id,$use_type ,$resource_type, $subject, $grade, 
            $tag_one, $tag_two, $tag_three, $tag_four,$tag_five, $page_info
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
            $item['picture_one'] = '';
            $item['picture_two'] = '';
            $item['picture_three'] = '';
            $item['picture_four'] = '';
            $item['picture_five'] = '';
            if($item['error_picture'] != ''){
                $arr =json_decode($item['error_picture']);
                $item['picture_one'] = @$arr[0];
                $item['picture_two'] = @$arr[1];
                $item['picture_three'] = @$arr[2];
                $item['picture_four'] = @$arr[3];
                $item['picture_five'] = @$arr[4];
            }
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

        //返回二级错误选项
        $sub_error_arr = $this->get_sub_error($error_type);

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
        //dd($ret_info);
        return $this->pageView( __METHOD__,$ret_info,[
            '_publish_version'    => 20180204111439,
            'tag_info'      => $tag_arr,
            'subject'       => json_encode($sub_grade_info['subject']),
            'grade'         => json_encode($sub_grade_info['grade']),
            'identity'      => $sub_grade_info['identity'],
            'book'          => json_encode($book_arr),
            'resource_type' => $resource_type,
            'is_teacher'   => $is_teacher,
            'sub_error_arr' => $sub_error_arr,
        ]);
    } 

    private function get_sub_error($error_type){
        $err_sub = ['-1'=>'请先选择一级错误'];
        if( $error_type >=0 ){
            switch($error_type){
            case 0:
                $err_sub = E\Eresource_knowledge::$desc_map;
                break;
            case 1:
                $err_sub = E\Eresource_question_answer::$desc_map;;
                break;
            case 2:
                $err_sub = E\Eresource_code_error::$desc_map;
                break;
            case 3:
                $err_sub = E\Eresource_content::$desc_map;
                break;
            case 4:
                $err_sub = E\Eresource_whole::$desc_map;
                break;
            case 5:
                $err_sub = E\Eresource_picture::$desc_map;
                break;
            case 6:
                $err_sub = E\Eresource_font::$desc_map;
                break;
            case 7:
                $err_sub = E\Eresource_difficult::$desc_map;
                break;

            default:
                break;
            }
 
        }
        if(count($err_sub)>1){
            $new = ['-1'=>"全部"];
            $err_sub = array_merge($new,$err_sub);
        }
        return $err_sub;
    }

    public function get_error_by_file_id(){
        $file_id    = $this->get_in_int_val("file_id",-1);
        $result = ['status'=>201];
        $data = $this->t_resource_file_error_info->get_error_by_file_id($file_id);
        if($data){
            foreach($data as &$item){
                $item['error_type_str'] = E\Eresource_error::get_desc($item['error_type']);
                $item['sub_error_type_str'] = \App\Helper\Utils::get_sub_error_type($item['error_type'],$item['sub_error_type']);
                \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
                \App\Helper\Utils::unixtime2date_for_item($item,"first_check_time");
                \App\Helper\Utils::unixtime2date_for_item($item,"second_check_time");
                \App\Helper\Utils::unixtime2date_for_item($item,"reupload_time");
                $item['first_check_name'] = "";
                $item['second_check_name'] = "";
                if($item['first_check_adminid'] > 0){
                    $item['first_check_name'] = $this->t_manager_info->get_name($item['first_check_adminid']);
                }
                if($item['second_check_adminid'] > 0){
                    $item['second_check_name'] = $this->t_manager_info->get_name($item['second_check_adminid']);
                }
                if($item['reupload_adminid'] > 0){
                    $item['reupload_name'] = $this->t_manager_info->get_name($item['reupload_adminid']);
                }

            }
            $result = ['status'=>200,'count'=>count($data),'list'=>$data];
        }
        return $this->output_succ($result);

    }

    public function file_err_agree(){
        $error_id    = $this->get_in_int_val("id",-1);
        $file_id    = $this->get_in_int_val("file_id",-1);
        $result = ['status'=>201];

        $data = ['status'=>1];
        $modify = $this->t_resource_file_error_info->field_update_list($error_id,$data);
        if($modify){
            $result['status'] = 200;
        }
        return $this->output_succ($result);
    }

    public function file_err_refuse(){
        $error_id    = $this->get_in_int_val("id",-1);
        $file_id    = $this->get_in_int_val("file_id",-1);
        $status    = $this->get_in_int_val("status",3);
        $result = ['status'=>201];
        $adminid =  $this->get_account_id();
        $role = $this->get_account_role();
        $name = $this->get_account();

        $file_subject = $this->t_resource->get_file_subject($error_id);
        $subject = $file_subject['subject'];
        $subject_str = E\Esubject::get_desc($subject);
        \App\Helper\Utils::logger($role."当前角色: ".E\Eaccount_role::get_desc($role)." 操作人".$name." 当前科目".$subject." ".$subject_str);
        // if($role != 4){
        //     $result['msg'] = '你不是教研,无权初审或者复审';
        //     return $this->output_succ($result);
        // }

        //判断是不是科目主管
        $is_zhuguan = 0;
        $check = "";
        $checkid = 0;
        if($status == 3){
            switch((int)$subject){
            default:
                $check = "李红涛";
                $checkid = 793;
                break;
            case 1:
                $check = "张敏";
                $checkid = 849;
                break;
            case 2:
                $check = "谢元浩";
                $checkid = 1269;
                break;
            case 3:
                $check = "许千千";
                $checkid = 329;
                break;
            }

            if( (int)($adminid) == $checkid ){
                $is_zhuguan = 1;               
            }

            \App\Helper\Utils::logger("审查人 $name 审查id $adminid 有权人$check 有权id $checkid");

            if ( $is_zhuguan  == 0 ) {
                $result['msg'] = "科目$subject_str 只有 $check 有权初审驳回，你不是教研组长无权驳回";
                return $this->output_succ($result);
            }
        }

        //判断是不是总监
        if($status == 4 && $adminid != 1171 ){
            $result['msg'] = '该讲义只有教研总监复审驳回，你无权驳回';
            return $this->output_succ($result);            
        }
        //判断是不是主管
        // $is_zhuguan = $this->t_admin_main_group_name->is_master($adminid);
        // \App\Helper\Utils::logger("主管: ".$is_zhuguan);
        // if (!$is_zhuguan && $status == 3 ) {
        //     $result['msg'] = '你不是教研组长无权初审';
        //     return $this->output_succ($result);
        // }

        //判断是不是总监
        // $is_master = $this->t_admin_majordomo_group_name->is_master($adminid);
        // \App\Helper\Utils::logger("总监: ".$is_master);
        // if (!$is_master && $status == 4 ) {
        //     $result['msg'] = '你不是教研总监无权复审';
        //     return $this->output_succ($result);

        // }

        $file = $this->t_resource_file_error_info->get_error_by_error_id($error_id);
        // \App\Helper\Utils::logger("文件: ".json_encode($file));
        if($file){
            if( $file['status'] == 0 ){
                $result['msg'] = '文件尚未同意修改，你无法初审驳回';
                return $this->output_succ($result);
            };
            if( $file['status'] == 1 ){
                $result['msg'] = '文件尚未上传，你无法初审驳回';
                return $this->output_succ($result);
            };
            if( $file['status'] == 3 && $status == 3 ){
                $result['msg'] = '初审已经驳回，无法重复驳回';
                return $this->output_succ($result);
            };
            if( $file['status'] == 4 ){
                $result['msg'] = '复审已经驳回，无法再次驳回';
                return $this->output_succ($result);
            };

        }

        if( $status == 3 ){
            $data = [
                'status' => 3,
                'first_check_adminid' =>  $adminid,
                'first_check_time' => time(),
            ];
            $result['first_check_time'] = $data['first_check_time'];
            \App\Helper\Utils::unixtime2date_for_item($result,"first_check_time");
            $result['first_check_name'] = $name;
        }

        if( $status == 4 ){
            $data = [
                'status' => 4,
                'second_check_adminid' =>  $adminid,
                'second_check_time' => time(),
            ];
            $result['second_check_time'] = $data['second_check_time'];
            \App\Helper\Utils::unixtime2date_for_item($result,"second_check_time");
            $result['second_check_name'] = $name;
        }

        $modify = $this->t_resource_file_error_info->field_update_list($error_id,$data);
        if($modify){
            $result['status'] = 200;
        }else{
            $result['msg'] = '审查失败';
        }

        return $this->output_succ($result);

    }


    public function admin_manage() {
        list($start_time,$end_time) = $this->get_in_date_range( 0,0,0,[],3);
        $use_type      = $this->get_in_int_val('use_type', 1);
        $resource_type = $this->get_in_int_val('resource_type', 1);
        $subject       = $this->get_in_int_val('subject', -1);
        $grade         = $this->get_in_int_val('grade', -1);
        $tag_one       = $this->get_in_int_val('tag_one', -1);
        $tag_two       = $this->get_in_int_val('tag_two', -1);
        $tag_three     = $this->get_in_int_val('tag_three', -1);
        $tag_four      = $this->get_in_int_val('tag_four', -1);
        $tag_five      = $this->get_in_int_val('tag_five', -1);
        $page_info     = $this->get_in_page_info();
        $adminid       = $this->get_in_int_val("adminid",-1);
        $reload_adminid = $this->get_in_int_val("reload_adminid",-1);
        $kpi_adminid   = $this->get_in_int_val("kpi_adminid",-1);

        $status        = $this->get_in_int_val("status",-1);
        if($adminid > 1){
            $adminid_phone = $this->t_teacher_info->get_phone($adminid);
            $adminid = $this->t_manager_info->get_id_by_phone($adminid_phone);  
        }else{
            $adminid = -1;
        }
        if($reload_adminid > 1){
            $reload_adminid_phone = $this->t_teacher_info->get_phone($reload_adminid);
            $reload_adminid = $this->t_manager_info->get_id_by_phone($reload_adminid_phone);  
        }else{
            $reload_adminid = -1;
        }
        if($kpi_adminid > 1){
            $kpi_adminid_phone = $this->t_teacher_info->get_phone($kpi_adminid);
            $kpi_adminid = $this->t_manager_info->get_id_by_phone($kpi_adminid_phone);  
        }else{
            $kpi_adminid = -1;
        }


        if($use_type == 1){
            $resource_type = ($resource_type<1)?1:$resource_type;
            $resource_type = ($resource_type>7)?7:$resource_type;
        }else if($use_type == 2){
            $resource_type = 9;
        }else{
            $resource_type = 8;
        }
        $ret_info = $this->t_resource->get_all_info($use_type ,$resource_type, $subject, $grade, 
            $tag_one, $tag_two, $tag_three, $tag_four,$tag_five, $page_info,$adminid,$reload_adminid,$kpi_adminid,$status
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
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            \App\Helper\Utils::get_file_use_type_str($item, $index);
            $item['nick'] = $this->cache_get_account_nick($item['visitor_id']);
            $item['admin_nick'] = $this->cache_get_account_nick($item['adminid']);
            $item['reload_adminid_str'] = $this->cache_get_account_nick($item['reload_adminid']);
            $item['kpi_adminid_str'] = $this->cache_get_account_nick($item['kpi_adminid']);

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

            if($item['reload_status'] == 0){
                $item['reload_status_str'] = "申请更换";
                $item['reload_status_string'] = "申请更换";
            }elseif($item['reload_status'] == 1){
                $item['reload_status_str'] = "待审批";
                $item['reload_status_string'] = "取消更换";
            }


            if($item['kpi_status'] == 0){
                $item['kpi_status_str'] = "申请更换";
                $item['kpi_status_string'] = "申请更换";
            }elseif($item['kpi_status'] == 1){
                $item['kpi_status_str'] = "待审批";
                $item['kpi_status_string'] = "取消更换";
            }
         
        }

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
        //dd($ret_info);
        $sub_grade_info = $this->get_rule_range();
        $is_teacher = 0;
        //dd($ret_info);
        return $this->pageView( __METHOD__,$ret_info,[
            '_publish_version'    => 20180204111439,
            'tag_info'      => $tag_arr,
            'subject'       => json_encode($sub_grade_info['subject']),
            'grade'         => json_encode($sub_grade_info['grade']),
            'identity'      => $sub_grade_info['identity'],
            'book'          => json_encode($book_arr),
            'resource_type' => $resource_type,
            'is_teacher'   => $is_teacher,
        ]);
    }


    public function apply_change_adminid(){
        $type    = $this->get_in_int_val("type",-1);
        $file_id = $this->get_in_int_val("file_id",-1);
        $teacherid = $this->get_in_int_val("teacherid",-1);

        if($teacherid > 1){
            $phone = $this->t_teacher_info->get_phone($teacherid);
            $adminid = $this->t_manager_info->get_id_by_phone($phone);  
        }else{
            $adminid = -1;
        }
        $resource_id = $this->get_in_int_val("resource_id",-1);
        $change_adminid = $this->get_account_id();
        if($type == 1){
            $data = [
                "reload_status" => 1
            ];
            $ret = $this->t_resource_file->field_update_list($file_id,$data);

            $data_new = [
                "file_id" => $file_id,
                "add_time"=> time(),
                "teacherid" => $adminid,
                "change_adminid" => $change_adminid,
                "result"    => "申请",
                "action"    => "申请-更换修改重传负责人",
            ];
            $ret_info = $this->t_resource_change_record->row_insert($data_new);
            return $this->output_succ();
        }elseif($type == 2){
            $data = [
                "kpi_status" => 1
            ];
            $ret = $this->t_resource_file->field_update_list($file_id,$data);

            $data_new = [
                "file_id" => $file_id,
                "add_time"=> time(),
                "teacherid" => $adminid,
                "change_adminid" => $change_adminid,
                "result"    => "申请",
                "action"    => "申请-更换讲义统计负责人",
            ];
            $ret_info = $this->t_resource_change_record->row_insert($data_new);
            return $this->output_succ();
        }
        
    }

    public function cancel_change_adminid(){
        $type    = $this->get_in_int_val("type",-1);
        $file_id = $this->get_in_int_val("file_id",-1);
        $resource_id = $this->get_in_int_val("resource_id",-1);
        $change_adminid = $this->get_account_id();
        if($type == 1){
            $data = [
                "reload_status" => 0
            ];
            $ret = $this->t_resource_file->field_update_list($file_id,$data);

            $data_new = [
                "file_id" => $file_id,
                "add_time"=> time(),
                "change_adminid" => $change_adminid,
                "result"    => "取消",
                "action"    => "取消-更换修改重传负责人",
            ];
            $ret_info = $this->t_resource_change_record->row_insert($data_new);
            return $this->output_succ();
        }elseif($type == 2){
            $data = [
                "kpi_status" => 0
            ];
            $ret = $this->t_resource_file->field_update_list($file_id,$data);

            $data_new = [
                "file_id" => $file_id,
                "add_time"=> time(),
                "change_adminid" => $change_adminid,
                "result"    => "取消",
                "action"    => "取消-更换讲义统计负责人",
            ];
            $ret_info = $this->t_resource_change_record->row_insert($data_new);
            return $this->output_succ();
        }
        
    }

    public function get_record_info(){
        $type    = $this->get_in_int_val("type",-1);
        $file_id = $this->get_in_int_val("file_id",-1);
        $resource_id = $this->get_in_int_val("resource_id",-1);
        $change_adminid = $this->get_account_id();
        
        if($type == 1){
            $ret = $this->t_resource_file->get_record_info_type_one($file_id);
            return $this->output_succ(["data"=>$ret]);
        }elseif($type == 2){
            $ret = $this->t_resource_file->get_record_info_type_two($file_id);
            return $this->output_succ(["data"=>$ret]);
        }
        
    }

    public function change_status(){
        $id = $this->get_in_int_val("id",-1);
        $status = $this->get_in_int_val("status",-1);
        $file_id = $this->get_in_int_val("file_id",-1);
        $type    = $this->get_in_int_val("type",-1);
        $change_adminid = $this->get_account_id();

        $reload_adminid = $this->t_resource_change_record->get_teacherid($id);
        $kpi_adminid    = $this->t_resource_change_record->get_teacherid($id);

        if($type == 1){
            if($status == 2){
                $data = [
                    "reload_status" => 0,
                    "reload_adminid" => $reload_adminid,
                ];
                $ret = $this->t_resource_file->field_update_list($file_id,$data);

                $data_new = [
                    "file_id" => $file_id,
                    "add_time"=> time(),
                    "change_adminid" => $change_adminid,
                    "result"    => "同意",
                    "action"    => "审批-更换修改重传负责人",
                ];
                $ret_info = $this->t_resource_change_record->row_insert($data_new);
                return $this->output_succ();
            }elseif ($status == 3){
                $data = [
                    "reload_status" => 0
                    //"reload_adminid" => $reload_adminid,
                ];
                $ret = $this->t_resource_file->field_update_list($file_id,$data);

                $data_new = [
                    "file_id" => $file_id,
                    "add_time"=> time(),
                    "change_adminid" => $change_adminid,
                    "result"    => "拒绝",
                    "action"    => "审批-更换修改重传负责人",
                ];
                $ret_info = $this->t_resource_change_record->row_insert($data_new);
                return $this->output_succ();
            }
        }elseif($type == 2){
            if($status == 2){
                $data = [
                    "kpi_status" => 0,
                    "kpi_adminid" => $kpi_adminid,
                ];
                $ret = $this->t_resource_file->field_update_list($file_id,$data);

                $data_new = [
                    "file_id" => $file_id,
                    "add_time"=> time(),
                    "change_adminid" => $change_adminid,
                    "result"    => "同意",
                    "action"    => "审批-更换讲义统计负责人",
                ];
                $ret_info = $this->t_resource_change_record->row_insert($data_new);
                return $this->output_succ();
            }elseif($status == 3){
                $data = [
                    "kpi_status" => 0,
                    //"reload_adminid" => $reload_adminid,
                ];
                $ret = $this->t_resource_file->field_update_list($file_id,$data);

                $data_new = [
                    "file_id" => $file_id,
                    "add_time"=> time(),
                    "change_adminid" => $change_adminid,
                    "result"    => "拒绝",
                    "action"    => "审批-更换讲义统计负责人",
                ];
                $ret_info = $this->t_resource_change_record->row_insert($data_new);
                return $this->output_succ();
            }
        }
    }

    public function apply_change_adminid_multi(){
        $type    = $this->get_in_int_val("type",-1);
        $file_id_str = $this->get_in_str_val("file_id",'');
        $teacherid = $this->get_in_int_val("teacherid",-1);
        $file_id_group=\App\Helper\Utils::json_decode_as_int_array($file_id_str);

        foreach ($file_id_group as $key => $value) {
            $file_id = $value;
            if($teacherid > 1){
                $phone = $this->t_teacher_info->get_phone($teacherid);
                $adminid = $this->t_manager_info->get_id_by_phone($phone);  
            }else{
                $adminid = -1;
            }
            $change_adminid = $this->get_account_id();
            if($type == 1){
                $data = [
                    "reload_status" => 1
                ];
                $ret = $this->t_resource_file->field_update_list($file_id,$data);

                $data_new = [
                    "file_id" => $file_id,
                    "add_time"=> time(),
                    "teacherid" => $adminid,
                    "change_adminid" => $change_adminid,
                    "result"    => "申请",
                    "action"    => "申请-更换修改重传负责人",
                ];
                $ret_info = $this->t_resource_change_record->row_insert($data_new);
                //return $this->output_succ();
            }elseif($type == 2){
                $data = [
                    "kpi_status" => 1
                ];
                $ret = $this->t_resource_file->field_update_list($file_id,$data);

                $data_new = [
                    "file_id" => $file_id,
                    "add_time"=> time(),
                    "teacherid" => $adminid,
                    "change_adminid" => $change_adminid,
                    "result"    => "申请",
                    "action"    => "申请-更换讲义统计负责人",
                ];
                $ret_info = $this->t_resource_change_record->row_insert($data_new);
                //return $this->output_succ();
            }
        }
        
        return $this->output_succ();
    }
    public function change_status_multi(){
        $status  = $this->get_in_int_val("status",-1);
        $type    = $this->get_in_int_val("type",-1);
        $file_id_str = $this->get_in_str_val("file_id",'');
        $file_id_group=\App\Helper\Utils::json_decode_as_int_array($file_id_str);


        $change_adminid = $this->get_account_id();


        foreach ($file_id_group as $key => $value) {
            $file_id = $value;
            $reload_adminid = $this->t_resource_change_record->get_teacherid_by_file_id_reload($file_id);
            $kpi_adminid    = $this->t_resource_change_record->get_teacherid_by_file_id_kpi($file_id);

            if($type == 1){
                if($status == 2){
                    $data = [
                        "reload_status" => 0,
                        "reload_adminid" => $reload_adminid,
                    ];
                    $ret = $this->t_resource_file->field_update_list($file_id,$data);

                    $data_new = [
                        "file_id" => $file_id,
                        "add_time"=> time(),
                        "change_adminid" => $change_adminid,
                        "result"    => "同意",
                        "action"    => "审批-更换修改重传负责人",
                    ];
                    $ret_info = $this->t_resource_change_record->row_insert($data_new);
                    //return $this->output_succ();
                }elseif ($status == 3){
                    $data = [
                        "reload_status" => 0
                        //"reload_adminid" => $reload_adminid,
                    ];
                    $ret = $this->t_resource_file->field_update_list($file_id,$data);

                    $data_new = [
                        "file_id" => $file_id,
                        "add_time"=> time(),
                        "change_adminid" => $change_adminid,
                        "result"    => "拒绝",
                        "action"    => "审批-更换修改重传负责人",
                    ];
                    $ret_info = $this->t_resource_change_record->row_insert($data_new);
                    //return $this->output_succ();
                }
            }elseif($type == 2){
                if($status == 2){
                    $data = [
                        "kpi_status" => 0,
                        "kpi_adminid" => $kpi_adminid,
                    ];
                    $ret = $this->t_resource_file->field_update_list($file_id,$data);

                    $data_new = [
                        "file_id" => $file_id,
                        "add_time"=> time(),
                        "change_adminid" => $change_adminid,
                        "result"    => "同意",
                        "action"    => "审批-更换讲义统计负责人",
                    ];
                    $ret_info = $this->t_resource_change_record->row_insert($data_new);
                    //return $this->output_succ();
                }elseif($status == 3){
                    $data = [
                        "kpi_status" => 0,
                        //"reload_adminid" => $reload_adminid,
                    ];
                    $ret = $this->t_resource_file->field_update_list($file_id,$data);

                    $data_new = [
                        "file_id" => $file_id,
                        "add_time"=> time(),
                        "change_adminid" => $change_adminid,
                        "result"    => "拒绝",
                        "action"    => "审批-更换讲义统计负责人",
                    ];
                    $ret_info = $this->t_resource_change_record->row_insert($data_new);
                    // return $this->output_succ();
                }
            }
        }

        return $this->output_succ();
    }

}
