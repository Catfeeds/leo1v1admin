<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use App\Helper\Utils;

class seller_student_new2 extends Controller
{
    use CacheNick;
    use TeaPower;
    public function tmk_student_list2 () {
        $this-> check_in_not_has_and_set("publish_flag",-1);
        return $this->tmk_student_list();
    }
    public function tmk_student_list () {
        $tmk_adminid =$this->get_account_id();
        $this->set_in_value("tmk_adminid", $tmk_adminid);
        return $this->tmk_student_list_ex();
    }

    public function tmk_student_list_all () {
        $this->set_in_value("tmk_adminid",  -2);
        return $this->tmk_student_list_ex();
    }


    public function tmk_student_list_ex () {
        list($start_time,$end_time, $opt_date_str)=$this->get_in_date_range(-30,0, 1, [
            1 => array("tmk_assign_time","微信运营分配时间"),
            2 => array( "add_time", "资源进入时间"),
            3 => array( "lesson_start", "上课时间"),
        ]);

        $origin             = trim($this->get_in_str_val('origin', ''));
        $page_num           = $this->get_in_page_num();
        $userid             = $this->get_in_userid(-1);
        $admin_revisiterid  = $this->get_in_int_val("admin_revisiterid",-1);
        $tmk_student_status = $this->get_in_int_val('tmk_student_status', -1, E\Etmk_student_status::class);
        $subject            = $this->get_in_subject(-1);
        $has_pad            = $this->get_in_int_val("has_pad", -1, E\Epad_type::class);
        $grade              = $this->get_in_grade();
        $phone_name         = trim($this->get_in_str_val("phone_name"));
        $publish_flag       = $this->get_in_e_boolean(1,"publish_flag");
        $seller_student_stutus = $this->get_in_enum_val(E\Eseller_student_status::class,-1);

        $nick  = "";
        $phone = "";
        if( $phone_name ) {
            if (!($phone_name>0)) {
                $nick=$phone_name;
            }else{
                $phone=$phone_name;
            }
        }

        $check_need_jump_flag= $nick || $phone || ($userid != -1 ) ;
        if ( $check_need_jump_flag ){
            $status_list_str="";
        }

        $tmk_adminid =$this->get_in_int_val("tmk_adminid");


        $ret_info=$this->t_seller_student_new->get_tmk_student_list (
            $page_num, $tmk_adminid,  $userid,  $tmk_student_status ,
            $origin, $opt_date_str, $start_time, $end_time, $grade, $subject,
            $has_pad,$phone, $nick,$admin_revisiterid,$seller_student_stutus,$publish_flag );

        foreach ($ret_info["list"] as &$item) {
            $lesson_start= $item["lesson_start"];
            $notify_lesson_flag_str="";
            $notify_lesson_flag=0;

            \App\Helper\Utils::unixtime2date_for_item($item, "add_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "tmk_assign_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "tmk_next_revisit_time","", "Y-m-d H:i" );
            $item["opt_time"]=$item[$opt_date_str];

            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            E\Epad_type::set_item_value_str($item, "has_pad");
            E\Etmk_student_status::set_item_value_str($item);
            E\Ebook_grade::set_item_value_str($item, "grade");
            E\Eseller_student_status::set_item_value_str($item);
            $this->cache_set_item_account_nick($item,"admin_revisiterid","admin_revisiter_nick");
            $this->cache_set_item_account_nick($item,"sub_assign_adminid_2","sub_assign_admin_2_nick");
            $this->cache_set_item_teacher_nick($item);
            E\Eset_boolean::set_item_value_str($item,"success_flag");
            E\Etest_lesson_fail_flag::set_item_value_str($item,"test_lesson_fail_flag");

            $this->cache_set_item_account_nick($item,"tmk_set_seller_adminid","tmk_set_seller_adminid_nick");
            $this->cache_set_item_account_nick($item,"tmk_adminid","tmk_admin_nick");
            \App\Helper\Utils::hide_item_phone($item);
        }

        return $this->pageView(__METHOD__,$ret_info);
    }

    public function test_lesson_plan_list_jw(){
        return $this->test_lesson_plan_list_jx();
    }

    public function test_lesson_plan_list_jx(){
        $adminid = $this->get_account_id();
        if($adminid==349){
            $adminid=434;
        }
        $this->set_in_value("accept_adminid",$adminid);
        $this->set_in_value("is_jw",1);
        $this->set_in_value("cur_page","test_lesson_plan_list_jx");
        return $this->test_lesson_plan_list();
    }

    public function test_lesson_plan_list_seller(){
        $adminid = $this->get_account_id();
        if($adminid==349 ){
            $adminid=-1;
        }
        $this->set_in_value("limit_require_send_adminid",$adminid);
        $this->set_in_value("limit_require_flag",1);
        return $this->test_lesson_plan_list();
    }

    public function test_lesson_plan_list_ass_leader(){
        $adminid = $this->get_account_id();
        if($adminid==349){
            $adminid=-1;
        }
        $this->set_in_value("limit_require_send_adminid",$adminid);
        $this->set_in_value("limit_require_flag",1);
        return $this->test_lesson_plan_list();

    }

    public function test_lesson_plan_list_jw_leader(){
        $adminid = $this->get_account_id();
        if($adminid==349 || $adminid==478){
            $adminid=-1;
        }
        $this->set_in_value("limit_require_send_adminid",$adminid);
        $this->set_in_value("limit_require_flag",1);
        return $this->test_lesson_plan_list();
    }

    public function test_lesson_plan_list()
    {
        $cur_page = $this->get_in_str_val("cur_page");
        list($start_time,$end_time,$opt_date_str) = $this->get_in_date_range(0,7,1,[
            1 => array("require_time","申请时间"),
            2 => array("stu_request_test_lesson_time", "期待试听时间"),
            4 => array("lesson_start", "上课时间"),
            5 => array("seller_require_change_time ", "销售申请更换时间"),
            6 => array("set_lesson_time", "排课操作时间"),
        ]);

        $adminid      = $this->get_account_id();
        $account_info = $this->t_manager_info->get_teacher_info_by_adminid($adminid);
        if(!empty($account_info)){
            if($account_info["teacherid"]==61828 ){
                $tea_subject= "(4,5)";
            }else if(!empty($account_info["subject"])){
                $tea_subject = "(".$account_info["subject"].")";
            }else{
                $tea_subject= "";
            }
        }else{
            $tea_subject = "";
        }
        $account_role = $this->get_account_role();
        if(($account_role==3 || $account_role==12) ||  in_array($adminid,[895,486,513]) ){
            $tea_subject="";
        }

        $grade                      = $this->get_in_grade();
        $subject                    = $this->get_in_subject();
        $test_lesson_student_status = $this->get_in_int_val('test_lesson_student_status', -1,E\Eseller_student_status::class);
        $lessonid                   = $this->get_in_lessonid(-1);
        $page_num                   = $this->get_in_page_num();
        $userid                     = $this->get_in_userid(-1);
        $accept_flag                = $this->get_in_int_val("accept_flag", -2, E\Eset_boolean::class);
        $success_flag               = $this->get_in_int_val("success_flag", -1, E\Eset_boolean::class);
        $teacherid                  = $this->get_in_teacherid(-1);
        $jw_teacher                 = $this->get_in_int_val("jw_teacher",-1);
        $is_test_user               = $this->get_in_int_val("is_test_user",0, E\Eboolean::class);
        $jw_test_lesson_status      = $this->get_in_int_val("jw_test_lesson_status",-1,E\Ejw_test_lesson_status::class);

        $require_admin_type   = $this->get_in_int_val("require_admin_type", -1,E\Eaccount_role::class);
        $require_adminid      = $this->get_in_int_val("require_adminid",-1);
        $require_assign_flag  = $this->get_in_int_val("require_assign_flag",-1);
        $seller_groupid_ex    = $this->get_in_str_val('seller_groupid_ex', "");
        $require_adminid_list = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);

        $tmk_adminid                = $this->get_in_int_val("tmk_adminid",-1);
        $seller_require_change_flag = $this->get_in_int_val("seller_require_change_flag",-1);
        $ass_test_lesson_type       = $this->get_in_int_val("ass_test_lesson_type",-1, E\Eass_test_lesson_type::class);
        $test_lesson_fail_flag      = $this->get_in_int_val("test_lesson_fail_flag", -1, E\Etest_lesson_fail_flag::class);
        $adminid_right              = $this->get_seller_adminid_and_right();
        // dd($adminid_right);
        // $adminid_right=[0=>"全职老师",1=>"",2=>"",3=>""];
        $accept_adminid             = $this->get_in_int_val("accept_adminid",-1);
        $is_jw                      = $this->get_in_int_val("is_jw",0);
        $is_ass_tran                = $this->get_in_int_val("is_ass_tran",0);
        $limit_require_flag         = $this->get_in_int_val("limit_require_flag",-1);
        $limit_require_send_adminid = $this->get_in_int_val("limit_require_send_adminid",-1);
        $require_id                 = $this->get_in_int_val("require_id",-1);
        $has_1v1_lesson_flag        = $this->get_in_int_val("has_1v1_lesson_flag",-1,E\Eboolean::class);
        $lesson_plan_style          = $this->get_in_int_val("lesson_plan_style",-1);

        $ret_info = $this->t_test_lesson_subject_require->get_plan_list(
            $page_num, $opt_date_str, $start_time,$end_time ,$grade,
            $subject, $test_lesson_student_status,$teacherid, $userid,$lessonid ,
            $require_admin_type , $require_adminid ,$ass_test_lesson_type, $test_lesson_fail_flag,$accept_flag ,
            $success_flag,$is_test_user,$tmk_adminid,$require_adminid_list,$adminid_all=[],
            $seller_require_change_flag,$require_assign_flag, $has_1v1_lesson_flag,$accept_adminid,$is_jw,
            $jw_test_lesson_status,$jw_teacher,$tea_subject,$is_ass_tran,$limit_require_flag,
            $limit_require_send_adminid,$require_id,$lesson_plan_style
        );
        $start_index = \App\Helper\Utils::get_start_index_from_ret_info($ret_info) ;
        foreach($ret_info["list"] as $id => &$item){
            $item['id'] = $start_index+$id;
            $item["lesson_time"] = $item["lesson_start"];
            if($item["require_time"] >= strtotime("2017-09-22 14:30:00")){
                $item["use_new_flag"]=1;
            }else{
                $item["use_new_flag"]=0;
            }
            $item["except_lesson_time"] = $item["stu_request_test_lesson_time"];
            \App\Helper\Utils::unixtime2date_for_item($item, "curl_stu_request_test_lesson_time_end");
            \App\Helper\Utils::unixtime2date_for_item($item, "stu_request_test_lesson_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "set_lesson_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "require_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "lesson_start");
            \App\Helper\Utils::unixtime2date_for_item($item, "confirm_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "limit_require_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item, "limit_accept_time","_str");
            E\Egrade::set_item_value_str($item);
            E\Eregion_version::set_item_value_str($item,"editionid");
            if(!empty($item["textbook"])){
                $item["editionid_str"] = $item["textbook"];
            }
            E\Esubject::set_item_value_str($item);
            if($item['current_lessonid']>0){
                $item['grab_status']=2;
            }
            E\Egrab_status::set_item_value_str($item);
            E\Epad_type::set_item_value_str($item,"has_pad");
            E\Eseller_student_status::set_item_value_str($item,"test_lesson_student_status");
            E\Etest_lesson_level::set_item_value_str($item,"stu_test_lesson_level");
            E\Etest_lesson_order_fail_flag::set_item_value_str($item);
            E\Eboolean::set_item_value_str($item,"stu_test_ipad_flag");
            E\Eboolean::set_item_value_str($item,"grab_flag");
            E\Eset_boolean::set_item_value_str($item,"accept_flag");
            E\Eaccept_flag::set_item_value_str($item,"limit_accept_flag");

            $item["accept_flag_str"]=\App\Helper\Common::get_set_boolean_color_str( $item["accept_flag"] );
            $this->cache_set_item_teacher_nick($item);
            $this->cache_set_item_account_nick($item,"confirm_adminid","confirm_admin_nick");
            $this->cache_set_item_account_nick($item,"tmk_adminid","tmk_admin_nick");

            $stu_request_lesson_time_info=\App\Helper\Utils::json_decode_as_array($item["stu_request_lesson_time_info"], true);
            $str_arr=[];
            foreach ($stu_request_lesson_time_info as $p_item) {
                $str_arr[]=E\Eweek::get_desc($p_item["week"])." "
                    .date('H:i',@$p_item["start_time"])
                    .date('~H:i', $p_item["end_time"]);
            }

            $item["stu_request_lesson_time_info_str"]= join("<br/>", $str_arr);
            $item["success_flag_str"]=\App\Helper\Common::get_set_boolean_color_str( $item["success_flag"] );
            $item["lesson_used_flag_str"]=\App\Helper\Common::get_boolean_color_str(!$item["lesson_del_flag"]);

            E\Eboolean::set_item_value_str($item,"fail_greater_4_hour_flag");
            E\Eboolean::set_item_value_str($item,"advice_flag");
            // E\Eboolean::set_item_value_str($item,"intention_level");
            // $item["intention_level_str"] = \App\Helper\Common::get_boolean_color_str($item["intention_level"]);
            E\Etest_lesson_fail_flag::set_item_value_str($item);
            E\Eass_test_lesson_type::set_item_value_str($item);
            E\Eintention_level::set_item_value_str($item);
            E\Edemand_urgency::set_item_value_str($item);
            E\Equotation_reaction::set_item_value_str($item);
            E\Eacademic_goal::set_item_value_str($item);
            E\Etest_stress::set_item_value_str($item);
            E\Eentrance_school_type::set_item_value_str($item);
            E\Einterest_cultivation::set_item_value_str($item);
            E\Eextra_improvement::set_item_value_str($item);
            E\Ehabit_remodel::set_item_value_str($item);
            E\Egender::set_item_value_str($item);

            if($item['accept_status'] == 0){
                $item['accept_status_str'] = '<font color="blue">未接受</font>';
            }elseif($item['accept_status'] == 1){
                $item['accept_status_str'] = '<font color="green">已接受</font>';
            }elseif($item['accept_status'] == 2){
                $item['accept_status_str'] = '<font color="red">已拒绝</font>';
            }

            $stu_request_test_lesson_time_info=\App\Helper\Utils::json_decode_as_array(
                $item["stu_request_test_lesson_time_info"],true
            );

            $str_arr=[];
            foreach ($stu_request_test_lesson_time_info as $p_item) {
                $str_arr[]= \App\Helper\Utils::fmt_lesson_time(@$p_item["start_time"], $p_item["end_time"]);
            }

            $item["stu_request_test_lesson_time_info_str"] = join("<br/>", $str_arr);
            $item["stu_test_paper_flag_str"] = \App\Helper\Common::get_test_pager_boolean_color_str(
                $item["stu_test_paper"], $item['tea_download_paper_time']
            );

            $this->cache_set_item_account_nick($item, "cur_require_adminid", "require_admin_nick");
            $this->cache_set_item_account_nick($item, "limit_require_adminid", "limit_require_account");
            $this->cache_set_item_account_nick($item, "limit_require_send_adminid", "limit_require_send_account");
            $this->cache_set_item_teacher_nick($item, "limit_require_teacherid", "limit_require_tea_nick");
            if($item['seller_require_change_flag'] > 0){
                $item['require_change_lesson_time_str'] = date("Y-m-d H:i",$item['require_change_lesson_time']);
                $item['seller_require_change_time_str'] = date("Y-m-d H:i",$item['seller_require_change_time']);
                E\Eseller_require_change_flag::set_item_value_str($item);
                $item['is_require_change']="1";
            }else{
                $item['is_require_change']="0";
            }
            if($item['accept_adminid'] > 0){
                $item['is_accept_adminid']="1";
                $this->cache_set_item_account_nick($item,"accept_adminid" ,"accept_account" );
                //t_manager_info->get_account($item['accept_adminid']);
            }else{
                $item['is_accept_adminid']="0";
            }
            $item["phone_ex"] = preg_replace('/(1[356789]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$item['phone']);
            if(is_numeric($item["parent_name"]) && strlen($item["parent_name"])==11){
                $item["parent_name"] = preg_replace('/(1[356789]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$item['parent_name']);
            }
            $item["rebut_info"] = $this->get_rebut_info( $item["rebut_info"]);
            $item["p_wx_openid_str"] =  $item["p_wx_openid"]?"已关注":"未关注";

        }

        $adminid           = $this->get_account_id();
        $admin_work_status = $this->t_manager_info->get_admin_work_status($adminid);

        $jw_teacher_list = $this->t_manager_info->get_jw_teacher_list_new(-1);
        $this->set_filed_for_js("account_role_self",$this->get_account_role());
        $ass_master_flag = $this->check_ass_leader_flag($this->get_account_id());
        $this->set_filed_for_js("ass_master_flag",$ass_master_flag);

        return $this->pageView(__METHOD__,$ret_info,[
            "cur_page"          => $cur_page,
            "adminid_right"     => $adminid_right,
            "admin_work_status" => $admin_work_status,
            "jw_teacher_list"   => $jw_teacher_list,
            "adminid"           => $adminid,
            "account_role"      => $this->get_account_role()
        ]);
    }

    //试听排课拉名单
    public function test_lesson_plan_list_new()
    {
        $ret = $this->t_test_lesson_subject_require->get_plan_list_new();
        $ret_info = [];
        foreach($ret as $key => &$item){
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            $this->cache_set_item_account_nick($item, "cur_require_adminid", "require_admin_nick");
            \App\Helper\Utils::unixtime2date_for_item($item, "require_time");
            $ret_info[$key]['id'] = $key+5001;
            $ret_info[$key]['phone'] = $item['phone'];
            $ret_info[$key]['grade_str'] = $item['grade_str'];
            $ret_info[$key]['subject_str'] = $item['subject_str'];
            $ret_info[$key]['require_admin_nick'] = $item['require_admin_nick'];
            $ret_info[$key]['require_time'] = $item['require_time'];
        }
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info));
    }





    public function test_lesson_no_binding_list(){
        list($start_time,$end_time) = $this->get_in_date_range(-10, 30 );
        $page_num = $this->get_in_page_num();
        $ret_info = $this->t_lesson_info->get_error_test_lesson_list( $page_num, $start_time,$end_time);
        foreach ($ret_info["list"] as &$item )  {
            $this->cache_set_item_student_nick($item);
            $this->cache_set_item_teacher_nick($item);
            $item["lesson_time"] =\App\Helper\Utils::fmt_lesson_time($item["lesson_start"],
                                                                     $item["lesson_end"]);
        }

        return $this->pageView(__METHOD__, $ret_info);

    }


    public function  ass_test_lesson_list()
    {
        $adminid = $this->get_account_id();
        if($adminid==349){
            $adminid=-1;
        }
        $this->set_in_value("require_adminid",$adminid);
        $this->set_in_value("cur_page","ass_test_lesson_list" );
        $this->set_in_value("is_ass_tran",1);

        return $this->test_lesson_plan_list();

    }
    public function  ass_test_lesson_list_tran()
    {
        $this->set_in_value("require_adminid",$this->get_account_id() );
        $this->set_in_value("cur_page","ass_test_lesson_list_tran" );
        $this->set_in_value("is_ass_tran",2);

        return $this->test_lesson_plan_list();

    }

    public function test_lesson_detail_list_jw(){
        return $this->test_lesson_detail_list();
    }
    public function test_lesson_detail_list() {

        list($start_time,$end_time )= $this->get_in_date_range_day(0, 0);
        $page_num=$this->get_in_page_num();
        $lesson_flag=$this->get_in_int_val("lesson_flag",-1);

        $ret_info=$this->t_test_lesson_subject_require->test_lesson_list($page_num,$start_time,$end_time,$lesson_flag);

        foreach($ret_info["list"] as &$item) {
            $lesson_start=$item["lesson_start"];
            $lesson_end=$item["lesson_end"];

            $item["lesson_time"]= \App\Helper\Utils::fmt_lesson_time($lesson_start,$lesson_end);
            $this->cache_set_item_student_nick($item);
            $this->cache_set_item_teacher_nick($item );
            $this->cache_set_item_account_nick($item,"require_adminid","require_admin_nick" );
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);

            E\Etest_lesson_fail_flag::set_item_value_str($item);

            $item["success_flag_str"]=\App\Helper\Common::get_set_boolean_color_str($item["success_flag"]);
            E\Eseller_student_status:: set_item_value_str($item);
            $item["notify_lesson_day1"]=  $item["notify_lesson_day1"]?1:0;
            $item["notify_lesson_day2"]=  $item["notify_lesson_day2"]?1:0;
            E\Eboolean::set_item_value_str($item,"notify_lesson_day1");
            E\Eboolean::set_item_value_str($item,"notify_lesson_day2");

        }

        return $this->pageView(__METHOD__, $ret_info );


    }
    public function seller_get_new_count_list()  {
        $adminid=$this->get_in_int_val("adminid",-1);
        $seller_new_count_type = $this-> get_in_int_val("seller_new_count_type",-1, E\Eseller_new_count_type::class);
        $page_num              = $this->get_in_page_num();
        list($start_time, $end_time)=$this->get_in_date_range_day(0);

        $ret_info=$this->t_seller_new_count->get_list($page_num,$adminid,$seller_new_count_type,$start_time +1 );
        foreach ( $ret_info["list"] as &$item ){
            $this->cache_set_item_account_nick($item);
            E\Eseller_new_count_type::set_item_value_str($item);

            \App\Helper\Utils::unixtime2date_for_item($item,"start_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"end_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            $value_ex_str="";
            switch ( $item["seller_new_count_type"] ) {
            case E\Eseller_new_count_type::V_ADMIN :
                $value_ex_str="操作者:".  $this->cache_get_account_nick($item["value_ex"]) ;
                break;
            case E\Eseller_new_count_type::V_ORDER_ADD:
                $value_ex_str="合同id:". $item["value_ex"] ;
                break;
            default:
                break;
            }

            $item["value_ex_str"]=$value_ex_str;

        }

        return $this->pageView(__METHOD__, $ret_info );

    }

    public function tongji_seller_get_new_count(){
        $adminid=$this->get_in_int_val("adminid",-1);
        list($start_time, $end_time) = $this->get_in_date_range_month(0);
        $ret_info=$this->t_seller_new_count-> tongji_get_admin_list_get_count($adminid,$start_time, $end_time);
        $admin_map=$this->t_seller_new_count->tongji_get_admin_list_count($adminid,$start_time, $end_time);

        //seller_get_new_count_admin_list get_admin_list_count
        foreach ($ret_info["list"] as  &$item ) {
            $adminid=$item["adminid"];
            $item["count"]=@$admin_map[$adminid]["count"];
            $item["left_count"]= @$item["count"]-@$item["get_count"];
            $this->cache_set_item_account_nick($item);
        }

        $ret_info=\App\Helper\Common::gen_admin_member_data($ret_info['list'],[],0, strtotime( date("Y-m-01", $start_time )   ));
        foreach( $ret_info as &$item ) {
            E\Emain_type::set_item_value_str($item);
        }

        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info( $ret_info ));
    }

    public function seller_get_new_count_admin_list(){
        $adminid=$this->get_in_int_val("adminid",-1);
        $month_time = strtotime(date("Y-m-01",time()));
        $ret_info=$this->t_seller_new_count->get_admin_list_get_count_new_new($month_time,$adminid);
        $admin_map=$this->t_seller_new_count->get_admin_list_count_new_new($month_time,$adminid);

        //seller_get_new_count_admin_list get_admin_list_count
        foreach ($ret_info["list"] as  &$item ) {
            $adminid=$item["adminid"];
            $item["count"]=@$admin_map[$adminid]["count"];
            $item["left_count"]= @$item["count"]-@$item["get_count"];
            $this->cache_set_item_account_nick($item);
        }

        $ret_info=\App\Helper\Common::gen_admin_member_data($ret_info['list'],[],0, strtotime( date("Y-m-01",time() )   ));
        foreach( $ret_info as &$item ) {
            E\Emain_type::set_item_value_str($item);
        }

        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info( $ret_info ));
    }

    public function seller_get_new_count_admin_list_new(){
        $adminid=$this->get_in_int_val("adminid",-1);
        list($start_time, $end_time) = $this->get_in_date_range_week(0);
        $this->set_in_value('group_adminid',$this->get_account_id());
        $group_adminid = $this->get_in_int_val("group_adminid",-1);
        $adminid_list=null;
        if ($group_adminid >0) {
            $groupid       = $this->t_admin_group_name->get_groupid_by_master_adminid($group_adminid);
            $mark_user_map = $this->t_admin_group_user->get_user_map($groupid);
            foreach($mark_user_map as $key=>$v){
                $adminid_list[] =$v;
            }
        }
        $ret_info  = $this->t_seller_new_count->get_admin_list_get_count_new($adminid_list);
        $admin_map = $this->t_seller_new_count->get_admin_list_count_new($adminid_list);
        $ret_list = [];
        foreach ($ret_info["list"] as  &$item ) {
            $item=["adminid" =>  $adminid,
                   "count" => 0 ,
                   "get_count"=>0,
                   "left_count"=>0,
                   "admin_nick"=>'',
            ] ;

            $adminid            = $item["adminid"];
            $item["count"]      = @$admin_map[$adminid]["count"];
            $item["left_count"] = @$item["count"]-@$item["get_count"];
            $this->cache_set_item_account_nick($item);
            $ret_list[] = $item;
        }
        $ret_list=\App\Helper\Common::gen_admin_member_data($ret_list,[],0, strtotime( date("Y-m-01",$start_time)));
        foreach( $ret_list as &$ad_item){
            E\Emain_type::set_item_value_str($ad_item);
        }
        // $all_item=["admin_nick"=> "全部" ];
        // $sum_field_list =["get_count","count","left_count"];
        // \App\Helper\Utils::list_add_sum_item($ret_list, $all_item,$sum_field_list);

        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($ret_list));
    }

    public function  seller_no_call_to_free_list() {
        $admin_revisiterid=$this->get_in_int_val("admin_revisiterid",-1);
        $page_num              = $this->get_in_page_num();
        $page_count        = $this->get_in_page_count();

        $global_tq_called_flag = $this->get_in_int_val("global_tq_called_flag", -1 , E\Etq_called_flag::class);
        $seller_student_status = $this->get_in_int_val("seller_student_status", -1 , E\Eseller_student_status::class);

        $ret_info=$this->t_seller_student_new->get_no_call_to_free_list($page_num,$page_count,$admin_revisiterid, $global_tq_called_flag,  $seller_student_status  );
        foreach($ret_info["list"] as &$item ) {
            \App\Helper\Utils::unixtime2date_for_item($item,'admin_assign_time');
            \App\Helper\Utils::unixtime2date_for_item($item,'add_time');
            $this->cache_set_item_student_nick($item);
            E\Etq_called_flag::set_item_value_str($item,"global_tq_called_flag");
            E\Eseller_level::set_item_value_str($item);
            E\Eseller_student_status::set_item_value_str($item);
        }
        return $this->pageView(__METHOD__, $ret_info );
    }

    /**
     * 2018年01月29日关闭抢单库功能
     * 教务将使用新版排课,旧版的抢单库功能暂停使用!
     */
    public function grab_test_lesson_plan(){
        $requireid   = $this->get_in_str_val("requireid");
        $grab_status = $this->get_in_int_val("grab_status",-1);

        return $this->output_err("抢单库功能已关闭!");

        if($requireid=="" || $grab_status==-1){
            return $this->output_err("请确认是否选择申请及其所要改变状态!");
        }

        $ret = $this->t_test_lesson_subject_require->set_grab_status($requireid,$grab_status);
        if(!$ret){
            return $this->output_err("抢单状态更改失败!请重试!");
        }
        return $this->output_succ();
    }

    public function grab_test_lesson_list(){
        $subject = $this->get_in_enum_list(E\Esubject::class);
        $grade   = $this->get_in_enum_list(E\Egrade::class);

        $list = $this->t_test_lesson_subject_require->get_grab_test_lesson_list($subject,1,$grade);
        $num  = 0;
        foreach($list as &$grab_val){
            $num++;
            $grab_val['num'] = $num;
            E\Esubject::set_item_value_str($grab_val);
            E\Egrade::set_item_value_str($grab_val);
            \App\Helper\Utils::unixtime2date_for_item($grab_val,"stu_request_test_lesson_time","_str");
            E\Eregion_version::set_item_value_str($grab_val,"editionid");
            if(!empty($grab_val["textbook"])){
                $grab_val["editionid_str"] = $grab_val["textbook"];
            }
        }

        $list=\App\Helper\Utils::list_to_page_info($list);
        return $this->pageView(__METHOD__, $list );
    }

    public function origin_user_list() {
        $origin=$this->get_in_str_val("origin");
        $origin_ex         = $this->get_in_str_val('origin_ex', "");
        $userid= $this->get_in_userid(-1);
        $page_info= $this->get_in_page_info();
        list($start_time,$end_time )=$this->get_in_date_range(-30,1);

        $ret_info=$this->t_seller_student_origin->get_origin_user_list($page_info, $start_time, $end_time,$userid,$origin,$origin_ex);
        foreach( $ret_info["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            E\Esubject::set_item_value_str($item);
            $same_flag= ($item["cur_origin"] == $item["origin"]  );
            $item["origin_same_flag_str"]= \App\Helper\Common::get_boolean_color_str($same_flag );

        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function get_test_lesson_require_teacher_info(){
        $page_info= $this->get_in_page_info();
        list($start_time,$end_time )=$this->get_in_date_range(0,0,0,[],1);
        $teacherid=$this->get_in_int_val("teacherid",-1);
        $ret_info  = $this->t_test_lesson_require_teacher_list->get_test_lesson_require_teacher_info($page_info,$start_time,$end_time,$teacherid);
        foreach($ret_info["list"] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item, "stu_request_test_lesson_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "add_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item, "require_time","_str");
            E\Egrade::set_item_value_str($item);
            E\Eregion_version::set_item_value_str($item,"editionid");
            if(!empty($item["textbook"])){
                $item["editionid_str"] = $item["textbook"];
            }
            E\Esubject::set_item_value_str($item);
            E\Epad_type::set_item_value_str($item,"has_pad");
            E\Eseller_student_status::set_item_value_str($item,"test_lesson_student_status");
            E\Etest_lesson_level::set_item_value_str($item,"stu_test_lesson_level");
            E\Eboolean::set_item_value_str($item,"stu_test_ipad_flag");
            $stu_request_lesson_time_info=\App\Helper\Utils::json_decode_as_array($item["stu_request_lesson_time_info"], true);
            $str_arr=[];
            foreach ($stu_request_lesson_time_info as $p_item) {
                $str_arr[]=E\Eweek::get_desc($p_item["week"])." "
                    .date('H:i',@$p_item["start_time"])
                    .date('~H:i', $p_item["end_time"]);
            }

            $item["stu_request_lesson_time_info_str"]= join("<br/>", $str_arr);

            $stu_request_test_lesson_time_info=\App\Helper\Utils::json_decode_as_array(
                $item["stu_request_test_lesson_time_info"],true
            );

            $str_arr=[];
            foreach ($stu_request_test_lesson_time_info as $p_item) {
                $str_arr[]= \App\Helper\Utils::fmt_lesson_time(@$p_item["start_time"], $p_item["end_time"]);
            }

            $item["stu_request_test_lesson_time_info_str"] = join("<br/>", $str_arr);
            $item["stu_test_paper_flag_str"] = \App\Helper\Common::get_test_pager_boolean_color_str(
                $item["stu_test_paper"], $item['tea_download_paper_time']
            );

            $this->cache_set_item_account_nick($item, "cur_require_adminid", "require_admin_nick");
            /* $teacher_info = "";
            if($item["teacher_info"]){
                $tea_info = trim($item["teacher_info"],",");
                $arr= explode(",",$tea_info);
                foreach($arr as $val){
                    $name = $this->t_teacher_info->get_realname($val);
                    $teacher_info .= $name.",";
                }
            }
            $item["teacher_info_str"] = trim($teacher_info,",");*/
            $item["realname"] = $this->t_teacher_info->get_realname($item["teacherid"]);

        }
        return $this->pageView(__METHOD__, $ret_info);
    }

    public function get_ass_test_lesson_info_master(){
        $this->set_in_value("master_flag",1);

        return $this->get_ass_test_lesson_info();

    }
    public function get_ass_test_lesson_info_leader(){
        $adminid = $this->get_account_id();
        $master_adminid = $this->get_ass_leader_account_id($adminid);
        $this->set_in_value("master_adminid",$master_adminid);
        return $this->get_ass_test_lesson_info_master();

    }

    public function get_ass_test_lesson_info(){
        $this->switch_tongji_database();
        // $start_time = strtotime("2017-07-20");
        list($start_time,$end_time) = $this->get_in_date_range( -7 ,0 );
        $page_info = $this->get_in_page_info();
        $account_id = $this->get_account_id();
        if($account_id==349){
            $account_id=297;
        }
        $require_adminid = $this->get_in_int_val("require_adminid",$account_id);
        $master_flag = $this->get_in_int_val("master_flag",-1);
        $assistantid = $this->get_in_int_val("assistantid",-1);
        $success_flag = $this->get_in_int_val("success_flag",-1);
        $order_confirm_flag = $this->get_in_int_val("order_confirm_flag",-1);
        $master_adminid = $this->get_in_int_val("master_adminid",-1);
        $lessonid = $this->get_in_int_val('lessonid',-1);

        $ret_info = $this->t_test_lesson_subject_sub_list->get_ass_require_test_lesson_info($page_info,$start_time,$require_adminid,$master_flag,$assistantid,$success_flag,$order_confirm_flag,$master_adminid, $lessonid,$end_time);
        foreach($ret_info["list"] as &$item){
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            E\Eregion_version::set_item_value_str($item,"editionid");
            if(!empty($item["textbook"])){
                $item["editionid_str"] = $item["textbook"];
            }

            E\Etest_lesson_fail_flag::set_item_value_str($item);
            if($item["test_lesson_fail_flag"]==0){
                $item["test_lesson_fail_flag_str"]="";
            }
            E\Eass_test_lesson_order_fail_flag::set_item_value_str($item);
            if($item["ass_test_lesson_order_fail_flag"]==0){
                $item["ass_test_lesson_order_fail_flag_str"]="";
            }


            E\Eass_test_lesson_type::set_item_value_str($item);
            E\Esuccess_flag::set_item_value_str($item);
            E\Esuccess_flag::set_item_value_str($item,"order_confirm_flag");
            if(strpos($item["origin"],"转介绍") !== false ){
                $item["ass_test_lesson_type_str"]=$item["origin"];
                $item["ass_fail_type"]=1;
            }elseif($item["ass_test_lesson_type"]==0){
                $item["ass_test_lesson_type_str"]=$item["origin"];
                $item["ass_fail_type"]=2;
            }else{
                $item["ass_fail_type"]=2;
            }

            \App\Helper\Utils::unixtime2date_for_item($item, "lesson_start","_str");

        }
        $account = $this->get_account();
        $this->set_filed_for_js("account",$account);

        return $this->pageView(__METHOD__, $ret_info,[
            "master_flag"  =>$master_flag
        ]);
    }

    public function get_from_ass_tran_lesson_info_master(){
        $this->set_in_value("master_flag",1);
        return $this->get_from_ass_tran_lesson_info();
    }
    public function get_from_ass_tran_lesson_info(){
        $this->switch_tongji_database();
        list($start_time,$end_time )=$this->get_in_date_range(0,0,0,[],3);
        $master_flag = $this->get_in_int_val("master_flag",0);
        if($master_flag==0){
            $account_id = $this->get_account_id();
        }else{
            $account_id=-1;
        }
        $assistantid = $this->get_in_int_val("assistantid",-1);
        $page_info = $this->get_in_page_info();
        $success_flag = $this->get_in_int_val("success_flag",-1);
        $order_flag = $this->get_in_int_val("order_flag",-1);

        $ret_info = $this->t_test_lesson_subject_sub_list->get_from_ass_test_tran_lesson_info($page_info,$start_time,$end_time,$assistantid,$success_flag,$order_flag,$account_id);
        foreach($ret_info["list"] as &$item){
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            E\Eregion_version::set_item_value_str($item,"editionid");
            E\Esuccess_flag::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item, "lesson_start","_str");
            if($item["orderid"]>0){
                $item["order_flag"]="签单";
            }else{
                $item["order_flag"]="未签";
            }

        }
        return $this->pageView(__METHOD__, $ret_info,[
            "master_flag"  =>$master_flag
        ]);

    }

    public function get_ass_tran_to_seller_detail_info_leader(){
        $this->set_in_value("leader_flag",1);
        return $this->get_ass_tran_to_seller_detail_info();

    }
    public function get_ass_tran_to_seller_detail_info(){
        $add_time = strtotime("2017-08-01");
        $page_info = $this->get_in_page_info();
        $assistantid = $this->get_in_int_val("assistantid",-1);
        $campus_id = $this->get_in_int_val("campus_id",-1);
        $groupid = $this->get_in_int_val("groupid",-1);
        $leader_flag = $this->get_in_int_val("leader_flag",0);
        $account_id = $this->get_account_id();
        $ret_info = $this->t_student_info->get_tran_stu_to_seller_info($add_time,$page_info,$assistantid,$leader_flag,$account_id,$campus_id,$groupid);
        foreach($ret_info["list"] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item, "add_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item, "admin_assign_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item, "ass_assign_time","_str");
            $this->cache_set_item_account_nick($item,"sub_assign_adminid_1","sub_assign_adminid_1_nick");
            $this->cache_set_item_account_nick($item,"sub_assign_adminid_2","sub_assign_adminid_2_nick");
            if(!$item["nick"]){
                $item["nick"]="无名";
            }

        }
        $master_flag=1;
        $campus_list = $this->t_admin_campus_list->get_admin_campus_info();
        $groupid_list = $this->t_admin_group_name->get_group_list (1);
        return $this->pageView(__METHOD__, $ret_info,[
            "master_flag"  =>$master_flag,
            "campus_list"  =>$campus_list,
            "groupid_list" =>$groupid_list
        ]);

    }

    //分配例子统计
    public function seller_student_new_distribution(){
        $origin_ex = $this->get_in_str_val("origin_ex");
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],3);
        if($end_time >= time()){
            $end_time = time();
        }
        $res = [];
        $seller_student_new_list = $this->t_seller_student_new->get_dis_count($start_time,$end_time,$origin_ex);
        foreach($seller_student_new_list as $item){
            $adminid = $item['adminid'];
            $res[$adminid]['auto_get_count'] = $item['auto_get_count'];
            $res[$adminid]['hand_get_count'] = $item['hand_get_count'];
            $res[$adminid]['count'] = $item['count'];
            $res[$adminid]['tmk_count'] = $item['tmk_count'];
        }
        $free_list = $this->t_test_subject_free_list->get_free_count($start_time,$end_time,$origin_ex);
        foreach($free_list as $item){
            $adminid = $item['adminid'];
            $res[$adminid]['free_count'] = $item['free_count'];
        }
        $history_count = $this->t_id_opt_log->get_history_info($start_time,$end_time,$origin_ex);
        foreach($history_count as $item){
            $adminid = $item['adminid'];
            $res[$adminid]['get_free_count'] = $item['get_free_count'];
        }
        $seller_distribution_list = $this->t_seller_edit_log->get_dis_count($start_time,$end_time,$origin_ex);//分配
        foreach($seller_distribution_list as $item){
            $adminid = $item['adminid'];
            $res[$adminid]['global_tq_called_flag'] = $item['global_tq_called_flag'];
            $res[$adminid]['distribution_count'] = $item['count'];
            $res[$adminid]['no_call_count'] = $item['no_call_count'];
        }
        foreach ($res as $ret_k=> &$res_item) {
            $res_item["adminid"] = $ret_k ;
        }
        list($member_new,$member_num_new,$member,$member_num,$become_member_num_l1,$leave_member_num_l1,$become_member_num_l2,$leave_member_num_l2,$become_member_num_l3,$leave_member_num_l3) = [[],[],[],[],0,0,0,0,0,0];
        $ret_info = \App\Helper\Common::gen_admin_member_data($res,[],0,strtotime(date("Y-m-01",$start_time )));
        foreach($ret_info as $key=>&$item){
            $item["become_member_time"] = isset($item["create_time"])?$item["create_time"]:0;
            $item["leave_member_time"] = isset($item["leave_member_time"])?$item["leave_member_time"]:0;
            $item["del_flag"] = isset($item["del_flag"])?$item["del_flag"]:0;
            E\Emain_type::set_item_value_str($item);
            if($item['level'] == "l-4" ){
                \App\Helper\Utils::unixtime2date_for_item($item,"become_member_time",'','Y-m-d');
                \App\Helper\Utils::unixtime2date_for_item($item,"leave_member_time",'','Y-m-d');
                $item["del_flag_str"] = \App\Helper\Common::get_boolean_color_str($item["del_flag"]);
                $item["del_flag"]?$leave_member_num_l3++:$become_member_num_l3++;
                $item["del_flag"]?$leave_member_num_l2++:$become_member_num_l2++;
                $item['become_member_num'] = $become_member_num_l3;
                $item['leave_member_num'] = $leave_member_num_l3;
            }else{
                $item["become_member_time"] = '';
                $item["leave_member_time"] = '';
                $item["del_flag_str"] = '';
                $item['become_member_num'] = '';
                $item['leave_member_num'] = '';
            }

            if($item['level'] == 'l-3'){
                $member[] = [
                    "up_group_name"     => $item['up_group_name'],
                    "group_name"        => $item['group_name'],
                ];
                $member_num[] = [
                    'become_member_num' => $become_member_num_l3,
                    'leave_member_num'  => $leave_member_num_l3,
                ];

                $become_member_num_l3 = 0;
                $leave_member_num_l3 = 0;
            }

            if($item['level'] == 'l-2'){
                $member_new[] = [
                    "up_group_name" => $item['up_group_name'],
                    "group_name"    => $item['group_name'],
                ];
                $member_num_new[] = [
                    'become_member_num' => $become_member_num_l2,
                    'leave_member_num'  => $leave_member_num_l2,
                ];

                $become_member_num_l2 = 0;
                $leave_member_num_l2 = 0;
            }
            if($item['main_type_str'] == '助教'){
                unset($ret_info[$key]);
            }
        }
        foreach($member as $key=>&$item){
            foreach($member_num as $k=>$info){
                if(($key+1) == $k){
                    $item['become_member_num'] = $info['become_member_num'];
                    $item['leave_member_num'] = $info['leave_member_num'];
                }
            }
            $item['become_member_num'] = isset($item['become_member_num'])?$item['become_member_num']:'';
            $item['leave_member_num'] = isset($item['leave_member_num'])?$item['leave_member_num']:'';
        }
        foreach($member_new as $key=>&$item){
            foreach($member_num_new as $k=>$info){
                if(($key+1) == $k){
                    $item['become_member_num'] = $info['become_member_num'];
                    $item['leave_member_num'] = $info['leave_member_num'];
                }
            }
            $item['become_member_num'] = isset($item['become_member_num'])?$item['become_member_num']:'';
            $item['leave_member_num'] = isset($item['leave_member_num'])?$item['leave_member_num']:'';
        }
        foreach($ret_info as &$item){
            if(($item['main_type_str'] == '未定义') or ($item['main_type_str'] == '助教')){
                unset($item);
            }else{
                if($item['level'] == 'l-2'){
                    foreach($member_new as $info){
                        if($item['up_group_name'] == $info['up_group_name']){
                            $item['become_member_num'] = $info['become_member_num'];
                            $item['leave_member_num'] = $info['leave_member_num'];
                        }
                    }
                }else{
                    if($item['level'] == 'l-3'){
                        foreach($member as $info){
                            if($item['group_name'] == $info['group_name']){
                                $item['become_member_num'] = $info['become_member_num'];
                                $item['leave_member_num'] = $info['leave_member_num'];
                            }
                        }
                    }else{
                        $item['become_member_num'] = '';
                        $item['leave_member_num'] = '';
                    }
                }
            }
        }

        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info));
    }

    public function seller_edit_log_list(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);
        $adminid               = $this->get_in_int_val('adminid',-1);
        $adminid               = $adminid>0?$adminid:-1;
        $uid                   = $this->get_in_int_val('uid',-1);
        $user_name             = trim($this->get_in_str_val('user_name',''));
        $hand_get_adminid      = $this->get_in_int_val("hand_get_adminid",-1);
        $origin_ex             = $this->get_in_str_val("origin_ex");
        $global_tq_called_flag = $this->get_in_int_val('global_tq_called_flag',-1);
        $page_info             = $this->get_in_page_info();
        if(in_array($hand_get_adminid,[1,2,3,4,5])){
            $ret_info = $this->t_seller_student_new->get_distribution_list($uid,$hand_get_adminid,$start_time,$end_time,$origin_ex,$page_info,$user_name);
            if(in_array($hand_get_adminid,[1,2])){
                foreach($ret_info['list'] as &$item){
                    $item["adminid"] = 0;
                }
            }
        }else{
            $ret_info = $this->t_seller_edit_log->get_distribution_list($adminid,$start_time,$end_time,$page_info,$global_tq_called_flag,$origin_ex,$user_name,$uid);
        }
        foreach($ret_info['list'] as &$item){
            \App\Helper\Utils::hide_item_phone($item);
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            $item["adminid_nick"]= $item["adminid"]>0?$this->cache_get_account_nick($item["adminid"]):'';
            $item["uid_nick"]= $this->cache_get_account_nick($item["uid"]);
            $item["del_flag_str"] = \App\Helper\Common::get_boolean_color_str($item["del_flag"]);
            $item["global_tq_called_flag_str"] = \App\Helper\Common::get_boolean_color_str($item["global_tq_called_flag"]);
            E\Ehand_get_adminid::set_item_value_str($item);
        }

        return $this->pageView(__METHOD__,$ret_info);
    }

    public function seller_diff_money(){
        list($start_time,$end_time)=$this->get_in_date_range_month(0);
        $res = [];
        $diff_money_def= $this->t_config_date->get_config_value(E\Econfig_date_type::V_MONTH_MARKET_SELLER_DIFF_MONEY ,strtotime( date("Y-m-01", $start_time) ));
        $diff_money_list = $this->t_order_info->get_spec_diff_money_all_new( $start_time,$end_time,E\Eaccount_role::V_2 );
        foreach($diff_money_list as $item){
            $adminid = $item['adminid'];
            $res[$adminid]['diff_money'] = $item['diff_money'];
        }
        foreach ($res as $ret_k=> &$res_item) {
            $res_item["adminid"] = $ret_k ;
        }
        list($member_new,$member_num_new,$member,$member_num,$become_member_num_l1,$leave_member_num_l1,$become_member_num_l2,$leave_member_num_l2,$become_member_num_l3,$leave_member_num_l3) = [[],[],[],[],0,0,0,0,0,0];
        $ret_info = \App\Helper\Common::gen_admin_member_data($res,[],0,strtotime(date("Y-m-01",$start_time )));
        foreach($ret_info as $key=>&$item){
            $item['diff_money_def'] = $diff_money_def;
            $item["become_member_time"] = isset($item["create_time"])?$item["create_time"]:0;
            $item["leave_member_time"] = isset($item["leave_member_time"])?$item["leave_member_time"]:0;
            $item["del_flag"] = isset($item["del_flag"])?$item["del_flag"]:0;
            E\Emain_type::set_item_value_str($item);
            if($item['level'] == "l-4" ){
                \App\Helper\Utils::unixtime2date_for_item($item,"become_member_time",'','Y-m-d');
                \App\Helper\Utils::unixtime2date_for_item($item,"leave_member_time",'','Y-m-d');
                $item["del_flag_str"] = \App\Helper\Common::get_boolean_color_str($item["del_flag"]);
                $item["del_flag"]?$leave_member_num_l3++:$become_member_num_l3++;
                $item["del_flag"]?$leave_member_num_l2++:$become_member_num_l2++;
                $item['become_member_num'] = $become_member_num_l3;
                $item['leave_member_num'] = $leave_member_num_l3;
            }else{
                $item["become_member_time"] = '';
                $item["leave_member_time"] = '';
                $item["del_flag_str"] = '';
                $item['become_member_num'] = '';
                $item['leave_member_num'] = '';
            }

            if($item['level'] == 'l-3'){
                $member[] = [
                    "up_group_name"     => $item['up_group_name'],
                    "group_name"        => $item['group_name'],
                ];
                $member_num[] = [
                    'become_member_num' => $become_member_num_l3,
                    'leave_member_num'  => $leave_member_num_l3,
                ];

                $become_member_num_l3 = 0;
                $leave_member_num_l3 = 0;
            }

            if($item['level'] == 'l-2'){
                $member_new[] = [
                    "up_group_name" => $item['up_group_name'],
                    "group_name"    => $item['group_name'],
                ];
                $member_num_new[] = [
                    'become_member_num' => $become_member_num_l2,
                    'leave_member_num'  => $leave_member_num_l2,
                ];

                $become_member_num_l2 = 0;
                $leave_member_num_l2 = 0;
            }
            if($item['main_type_str'] == '助教'){
                unset($ret_info[$key]);
            }
            if($item['main_type_str'] == '未定义'){
                unset($ret_info[$key]);
            }
        }
        foreach($member as $key=>&$item){
            foreach($member_num as $k=>$info){
                if(($key+1) == $k){
                    $item['become_member_num'] = $info['become_member_num'];
                    $item['leave_member_num'] = $info['leave_member_num'];
                }
            }
            $item['become_member_num'] = isset($item['become_member_num'])?$item['become_member_num']:'';
            $item['leave_member_num'] = isset($item['leave_member_num'])?$item['leave_member_num']:'';
        }
        foreach($member_new as $key=>&$item){
            foreach($member_num_new as $k=>$info){
                if(($key+1) == $k){
                    $item['become_member_num'] = $info['become_member_num'];
                    $item['leave_member_num'] = $info['leave_member_num'];
                }
            }
            $item['become_member_num'] = isset($item['become_member_num'])?$item['become_member_num']:'';
            $item['leave_member_num'] = isset($item['leave_member_num'])?$item['leave_member_num']:'';
        }
        foreach($ret_info as &$item){
            if(($item['main_type_str'] == '未定义') or ($item['main_type_str'] == '助教')){
                unset($item);
            }else{
                if($item['level'] == 'l-2'){
                    foreach($member_new as $info){
                        if($item['up_group_name'] == $info['up_group_name']){
                            $item['become_member_num'] = $info['become_member_num'];
                            $item['leave_member_num'] = $info['leave_member_num'];
                        }
                    }
                }else{
                    if($item['level'] == 'l-3'){
                        foreach($member as $info){
                            if($item['group_name'] == $info['group_name']){
                                $item['become_member_num'] = $info['become_member_num'];
                                $item['leave_member_num'] = $info['leave_member_num'];
                            }
                        }
                    }else{
                        $item['become_member_num'] = '';
                        $item['leave_member_num'] = '';
                    }
                }
            }
        }

        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info));
    }

    //上课统计
    public function seller_student_new_lesson(){
        $origin_ex = $this->get_in_str_val("origin_ex");
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],3);
        if($end_time >= time()){
            $end_time = time();
        }
        $res = [];
        $lesson_list = $this->t_test_lesson_subject_sub_list->get_all_lsit($start_time,$end_time,$origin_ex);
        // $lesson_list = $this->t_test_lesson_subject_require->get_all_lsit($start_time,$end_time,$origin_ex);
        foreach($lesson_list as $item){
            $adminid = $item['adminid'];
            $res[$adminid]['count'] = $item['count'];
            $res[$adminid]['suc_count'] = $item['suc_count'];
            $res[$adminid]['test_count'] = $item['test_count'];
            $res[$adminid]['wheat_count'] = $item['wheat_count'];
        }
        foreach ($res as $ret_k=> &$res_item) {
            $res_item["adminid"] = $ret_k ;
        }
        list($member_new,$member_num_new,$member,$member_num,$become_member_num_l1,$leave_member_num_l1,$become_member_num_l2,$leave_member_num_l2,$become_member_num_l3,$leave_member_num_l3) = [[],[],[],[],0,0,0,0,0,0];
        $ret_info = \App\Helper\Common::gen_admin_member_data($res,[],0,strtotime(date("Y-m-01",$start_time )));
        foreach($ret_info as $key=>&$item){
            if(isset($item['count'])){
                $item['suc_rate'] = ($item['count']>0?round($item['suc_count']/$item['count'],3)*100:0).'%';
                $item['test_rate'] = ($item['suc_count']>0?round($item['test_count']/$item['suc_count'],3)*100:0).'%';
            }else{
                $item['suc_rate'] = '';
                $item['test_rate'] = '';
            }
            $item["become_member_time"] = isset($item["create_time"])?$item["create_time"]:0;
            $item["leave_member_time"] = isset($item["leave_member_time"])?$item["leave_member_time"]:0;
            $item["del_flag"] = isset($item["del_flag"])?$item["del_flag"]:0;
            E\Emain_type::set_item_value_str($item);
            if($item['level'] == "l-4" ){
                \App\Helper\Utils::unixtime2date_for_item($item,"become_member_time",'','Y-m-d');
                \App\Helper\Utils::unixtime2date_for_item($item,"leave_member_time",'','Y-m-d');
                $item["del_flag_str"] = \App\Helper\Common::get_boolean_color_str($item["del_flag"]);
                $item["del_flag"]?$leave_member_num_l3++:$become_member_num_l3++;
                $item["del_flag"]?$leave_member_num_l2++:$become_member_num_l2++;
                $item['become_member_num'] = $become_member_num_l3;
                $item['leave_member_num'] = $leave_member_num_l3;
            }else{
                $item["become_member_time"] = '';
                $item["leave_member_time"] = '';
                $item["del_flag_str"] = '';
                $item['become_member_num'] = '';
                $item['leave_member_num'] = '';
            }

            if($item['level'] == 'l-3'){
                $member[] = [
                    "up_group_name"     => $item['up_group_name'],
                    "group_name"        => $item['group_name'],
                ];
                $member_num[] = [
                    'become_member_num' => $become_member_num_l3,
                    'leave_member_num'  => $leave_member_num_l3,
                ];

                $become_member_num_l3 = 0;
                $leave_member_num_l3 = 0;
            }

            if($item['level'] == 'l-2'){
                $member_new[] = [
                    "up_group_name" => $item['up_group_name'],
                    "group_name"    => $item['group_name'],
                ];
                $member_num_new[] = [
                    'become_member_num' => $become_member_num_l2,
                    'leave_member_num'  => $leave_member_num_l2,
                ];

                $become_member_num_l2 = 0;
                $leave_member_num_l2 = 0;
            }
            if($item['main_type_str'] == '助教'){
                unset($ret_info[$key]);
            }
        }
        foreach($member as $key=>&$item){
            foreach($member_num as $k=>$info){
                if(($key+1) == $k){
                    $item['become_member_num'] = $info['become_member_num'];
                    $item['leave_member_num'] = $info['leave_member_num'];
                }
            }
            $item['become_member_num'] = isset($item['become_member_num'])?$item['become_member_num']:'';
            $item['leave_member_num'] = isset($item['leave_member_num'])?$item['leave_member_num']:'';
        }
        foreach($member_new as $key=>&$item){
            foreach($member_num_new as $k=>$info){
                if(($key+1) == $k){
                    $item['become_member_num'] = $info['become_member_num'];
                    $item['leave_member_num'] = $info['leave_member_num'];
                }
            }
            $item['become_member_num'] = isset($item['become_member_num'])?$item['become_member_num']:'';
            $item['leave_member_num'] = isset($item['leave_member_num'])?$item['leave_member_num']:'';
        }
        foreach($ret_info as &$item){
            if(($item['main_type_str'] == '未定义') or ($item['main_type_str'] == '助教')){
                unset($item);
            }else{
                if($item['level'] == 'l-2'){
                    foreach($member_new as $info){
                        if($item['up_group_name'] == $info['up_group_name']){
                            $item['become_member_num'] = $info['become_member_num'];
                            $item['leave_member_num'] = $info['leave_member_num'];
                        }
                    }
                }else{
                    if($item['level'] == 'l-3'){
                        foreach($member as $info){
                            if($item['group_name'] == $info['group_name']){
                                $item['become_member_num'] = $info['become_member_num'];
                                $item['leave_member_num'] = $info['leave_member_num'];
                            }
                        }
                    }else{
                        $item['become_member_num'] = '';
                        $item['leave_member_num'] = '';
                    }
                }
            }
        }

        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info));
    }

    public function show_order_activity_info() {
        $order_activity_type = $this->get_in_e_order_activity_type();
        $class_map = \App\OrderPrice\Activity\activity_base::$class_map;
        $list = [];
        $activity_config=$this->t_order_activity_config->field_get_list($order_activity_type , "*");
        $class=new \App\OrderPrice\Activity\activity_config_new($activity_config, null);
        $list=$class->get_desc();
        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($list));
    }

    public function tongji_sign_rate(){

        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3 );
        $flag = $this->get_in_int_val('flag',1);
        $is_green_flag = $this->get_in_int_val('is_green_flag', -1);
        $is_down = $this->get_in_int_val('is_down', -1);
        $subject = $this->get_in_el_subject();
        $phone_location = trim($this->get_in_str_val("phone_location"));
        $grade   = $this->get_in_el_grade();
        $has_pad = $this->get_in_has_pad(-1);
        if( $grade[0] != -1 ){
            $grade = join($grade,',');
        } else {
            $grade = -1;
        }
        if( $subject[0] != -1 ) {
            $subject = join($subject, ',');
        } else {
            $subject = -1;
        }
        $ret_info = $this->t_test_lesson_subject->get_sign_count(
            $start_time, $end_time,$flag,$is_green_flag,$is_down,$has_pad,$phone_location,$grade,$subject
        );

        foreach($ret_info as &$item){
            if($flag == 3){
                $item['nick'] = $item['origin'];
            }

            if($item['stu_count']){
                $item['lesson_succ_rate'] = round( $item['lesson_succ_count']*100 / $item['stu_count'], 2);
            } else {
                $item['lesson_succ_rate'] = 0;
            }

            if($item['lesson_succ_count']){
                $item['sign_rate'] = round($item['order_count']*100/ $item['lesson_succ_count'], 2);
            } else {
                $item['sign_rate'] = 0;
            }
        }

        $ret_info = \App\Helper\Utils::order_list_new( $ret_info, 'sign_rate', false );

        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($ret_info));

    }
    public function seller_first_admin_info () {
        list($start_time,$end_time)=$this->get_in_date_range_month(0);
        $list=$this->t_seller_student_new->get_first_admin_info( $start_time, $end_time );

        $ret_info=\App\Helper\Common::gen_admin_member_data($list,[],0, strtotime( date("Y-m-01" )   ));


        foreach( $ret_info as $k => &$item ) {
            E\Emain_type::set_item_value_str($item);
        }
        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($ret_info));


    }

    public function seller_test_lesson_info(){
        $adminid = $this->get_in_int_val('adminid');
        list($start_time,$end_time)=$this->get_in_date_range_month(0);
        $res = [];
        list($res[$adminid]['test_lesson_count'],$res[$adminid]['succ_all_count_for_month'],$res[$adminid]['fail_all_count_for_month'],$res[$adminid]['lesson_per'],$res[$adminid]['kpi'],$res[$adminid]['all_new_contract_for_month'],$res[$adminid][E\Eweek_order::V_1],$res[$adminid][E\Eweek_order::V_2],$res[$adminid][E\Eweek_order::V_3],$res[$adminid][E\Eweek_order::V_4]) = [0,0,0,0,0,0,[],[],[],[]];
        $this->t_test_lesson_subject_require->switch_tongji_database();
        $test_leeson_list=$this->t_test_lesson_subject_require->tongji_test_lesson_group_by_admin_revisiterid_new($start_time,$end_time,$grade_list=[-1] , $origin_ex="",$adminid);
        foreach($test_leeson_list['list'] as $item){
            $res[$adminid]['test_lesson_count'] = $item['test_lesson_count'];
            $res[$adminid]['succ_all_count_for_month']=$item['succ_all_count'];
            $res[$adminid]['fail_all_count_for_month'] = $item['fail_all_count'];
        }
        $arr['test_lesson_count'] = $res[$adminid]['test_lesson_count'];
        $arr['succ_all_count_for_month'] = $res[$adminid]['succ_all_count_for_month'];
        $arr['fail_all_count_for_month'] = $res[$adminid]['fail_all_count_for_month'];
        list($start_time_new,$end_time_new)= $this->get_in_date_range_month(date("Y-m-01"));
        if($end_time_new >= time()){
            $end_time_new = time();
        }
        $ret_new = $this->t_month_def_type->get_month_week_time($start_time_new);
        $this->t_test_lesson_subject_require->switch_tongji_database();
        $test_leeson_list_new = $this->t_test_lesson_subject_require->tongji_test_lesson_group_by_admin_revisiterid_new_three($start_time_new,$end_time_new,$grade_list=[-1] , $origin_ex="",$adminid);
        foreach($test_leeson_list_new['list'] as $item){
            $lesson_start = $item['lesson_start'];
            foreach($ret_new as $info){
                $start = $info['start_time'];
                $end = $info['end_time'];
                $week_order = $info['week_order'];
                if($lesson_start>=$start && $lesson_start<$end && $week_order==E\Eweek_order::V_1){
                    $res[$adminid][$week_order][] = $item;
                }elseif($lesson_start>=$start && $lesson_start<$end && $week_order==E\Eweek_order::V_2){
                    $res[$adminid][$week_order][] = $item;
                }elseif($lesson_start>=$start && $lesson_start<$end && $week_order==E\Eweek_order::V_3){
                    $res[$adminid][$week_order][] = $item;
                }elseif($lesson_start>=$start && $lesson_start<$end && $week_order==E\Eweek_order::V_4){
                    $res[$adminid][$week_order][] = $item;
                }
            }
        }
        foreach($res as $key=>$item){
            $res[$key]['suc_lesson_count_one'] = isset($item[E\Eweek_order::V_1])?count($item[E\Eweek_order::V_1]):0;
            $res[$key]['suc_lesson_count_two'] = isset($item[E\Eweek_order::V_2])?count($item[E\Eweek_order::V_2]):0;
            $res[$key]['suc_lesson_count_three'] = isset($item[E\Eweek_order::V_3])?count($item[E\Eweek_order::V_3]):0;
            $res[$key]['suc_lesson_count_four'] = isset($item[E\Eweek_order::V_4])?count($item[E\Eweek_order::V_4]):0;
            $res[$key]['suc_lesson_count_one_rate'] = $res[$key]['suc_lesson_count_one']<12?0:15;
            $res[$key]['suc_lesson_count_two_rate'] = $res[$key]['suc_lesson_count_two']<12?0:15;
            $res[$key]['suc_lesson_count_three_rate'] = $res[$key]['suc_lesson_count_three']<12?0:15;
            $res[$key]['suc_lesson_count_four_rate'] = $res[$key]['suc_lesson_count_four']<12?0:15;
            $res[$key]['suc_lesson_count_rate_all'] = $res[$key]['suc_lesson_count_one_rate']+$res[$key]['suc_lesson_count_two_rate']+$res[$key]['suc_lesson_count_three_rate']+$res[$key]['suc_lesson_count_four_rate'];
            $res[$key]['suc_lesson_count_rate'] = $res[$key]['suc_lesson_count_rate_all'].'%';
        }
        $arr['suc_lesson_count_one'] = $res[$adminid]['suc_lesson_count_one'];
        $arr['suc_lesson_count_two'] = $res[$adminid]['suc_lesson_count_two'];
        $arr['suc_lesson_count_three'] = $res[$adminid]['suc_lesson_count_three'];
        $arr['suc_lesson_count_four'] = $res[$adminid]['suc_lesson_count_four'];
        $lesson_per = $res[$adminid]['test_lesson_count']!=0?(round($res[$adminid]['fail_all_count_for_month']/$res[$adminid]['test_lesson_count'],3)*100):0;
        $res[$adminid]['lesson_per'] = $res[$adminid]['test_lesson_count']!=0?$lesson_per."%":0;
        $lesson_kpi = $lesson_per<18?40:0;
        $kpi = $lesson_kpi+$res[$adminid]['suc_lesson_count_rate_all'];
        $res[$adminid]['kpi'] = ($kpi && $res[$adminid]['test_lesson_count']>0)>0?$kpi."%":0;
        $manager_info = $this->t_manager_info->field_get_list($adminid,'become_member_time,del_flag');
        if($manager_info["become_member_time"]>0 && ($end_time-$manager_info["become_member_time"])<3600*24*60 && $manager_info["del_flag"]==0){
            $res[$adminid]['kpi'] = "100%";
        }
        $arr['lesson_per'] = $res[$adminid]['lesson_per'];
        $arr['kpi'] = $res[$adminid]['kpi'];

        $this->t_order_info->switch_tongji_database();
        $order_new = $this->t_order_info->get_1v1_order_list_by_adminid($start_time,$end_time,-1,$adminid);
        foreach($order_new as $k=>$v){
            $res[$adminid]['all_new_contract_for_month'] = $v['all_new_contract'];
        }
        $arr['order_per'] = $res[$adminid]['succ_all_count_for_month']!=0?(round($res[$adminid]['all_new_contract_for_month']/$res[$adminid]['succ_all_count_for_month'],3)*100)."%":0;

        return $this->output_succ($arr);
    }

    /**
     * 试听排课页面
     */
    public function select_teacher_for_test_lesson(){
        $require_id    = $this->get_in_int_val("require_id");
        $teacher_tags  = $this->get_in_str_val("teacher_tags");
        $teaching_tags = $this->get_in_str_val("teaching_tags");
        $lesson_tags   = $this->get_in_str_val("lesson_tags");
        $refresh_flag  = $this->get_in_int_val("refresh_flag");
        $userid = $this->get_in_str_val("userid",0);

        $is_test_arr = $this->t_student_info->field_get_list($userid, "is_test_user");
        $is_test = $is_test_arr['is_test_user'];

        $require_info = $this->t_test_lesson_subject_require->get_require_list_by_requireid($require_id);
        if(!empty($require_info)){
            $textbook_map = array_flip(E\Eregion_version::$desc_map);
            if($require_info['textbook']!=''){
                $require_info['region_version'] = $textbook_map[$require_info['textbook']];
            }else{
                $require_info['region_version'] = 0;
            }

            if($require_info['current_lessonid']){
                $lesson_info = $this->t_lesson_info->get_lesson_info($require_info['current_lessonid']);
                $require_info['teacherid'] = $lesson_info['teacherid'];
                $tea_info = $this->t_teacher_info->get_teacher_info($lesson_info['teacherid']);
                $tea_nick  = $tea_info['realname'];
                $tea_phone = $tea_info['phone'];
                $require_info['teacher_info'] = $tea_nick."/".$tea_phone;
                $require_info['lesson_time']  = \App\Helper\Utils::unixtime2date($lesson_info['lesson_start']);
                if($lesson_info['accept_status']==0){
                    $require_info['accept_status_str'] = "未接受";
                }else{
                    $require_info['accept_status_str'] = E\Eaccept_status::get_desc($lesson_info['accept_status']);
                }
            }else{
                if($require_info['green_channel_teacherid']>0){
                    $green_teacher_info = $this->t_teacher_info->get_teacher_info($require_info['green_channel_teacherid']);
                    $require_info['teacherid'] = $require_info['green_channel_teacherid'];
                    $require_info['teacher_info'] = $green_teacher_info['nick']."/".$green_teacher_info['phone'];
                }else{
                    $require_info['teacherid']    = "";
                    $require_info['teacher_info'] = "";
                }

                $require_info['lesson_time']  = \App\Helper\Utils::unixtime2date($require_info['curl_stu_request_test_lesson_time']);
                $require_info['accept_status_str'] = "";
            }

            $identity       = $this->get_in_int_val("identity",$require_info['tea_identity']);
            $gender         = $this->get_in_int_val("gender",$require_info['tea_gender']);
            $tea_age        = $this->get_in_int_val("tea_age",$require_info['tea_age']);
            $teacher_type   = $this->get_in_int_val("teacher_type",$require_info['teacher_type']);
            $region_version = $this->get_in_int_val("region_version",$require_info['region_version']);
            $teacher_info   = $this->get_in_str_val("teacher_info",$require_info['teacher_info']);

            E\Egender::set_item_value_str($require_info);
            E\Egender::set_item_appoint_value_str($require_info,"tea_gender",0,"无要求");
            E\Etea_age::set_item_appoint_value_str($require_info,"tea_age",0,"无要求");
            E\Eidentity::set_item_appoint_value_str($require_info,"tea_identity",0,"无要求");
            E\Eteacher_type::set_item_appoint_value_str($require_info,"teacher_type",0,"无要求");
            E\Egrade::set_item_value_str($require_info);
            E\Esubject::set_item_value_str($require_info);
            E\Equotation_reaction::set_item_value_str($require_info);
            E\Eintention_level::set_item_value_str($require_info);
            E\Eseller_student_status::set_item_value_str($require_info,"test_lesson_student_status");

            $require_info['request_time'] = \App\Helper\Utils::unixtime2date($require_info['curl_stu_request_test_lesson_time']);
            $require_info['request_time_end'] = \App\Helper\Utils::unixtime2date($require_info['curl_stu_request_test_lesson_time_end']);
            $subject_tag_arr = json_decode($require_info['subject_tag'],true);
            if(is_array($subject_tag_arr)){
                $default_tag = "无要求";
                \App\Helper\Utils::set_default_value($require_info['风格性格'], $subject_tag_arr,$default_tag,'风格性格');
                \App\Helper\Utils::set_default_value($require_info['专业能力'], $subject_tag_arr,$default_tag,'专业能力');
                \App\Helper\Utils::set_default_value($require_info['课堂气氛'], $subject_tag_arr,$default_tag,'课堂气氛');
                \App\Helper\Utils::set_default_value($require_info['课件要求'], $subject_tag_arr,$default_tag,'课件要求');
                \App\Helper\Utils::set_default_value($require_info['学科化标签'], $subject_tag_arr,$default_tag,'学科化标签');
            }

            $lesson_start = $require_info['curl_stu_request_test_lesson_time'];
            $lesson_end   = $require_info['curl_stu_request_test_lesson_time_end'];
            $redis_key    = "require_key_".$require_id;
            $tea_list     = $this->get_teacher_list_for_test_lesson(
                $redis_key,$lesson_start,$lesson_end,$require_info['grade'],$require_info['subject'],$refresh_flag
                ,$identity,$gender,$tea_age,$require_info['subject_tag'],$teacher_tags,$lesson_tags,$teaching_tags,$teacher_type
                ,$region_version,$teacher_info,$is_test
            );
        }else{
            $tea_list = [];
        }

        $teacher_tags  = $this->get_tags_list("教师相关","");
        $lesson_tags   = $this->get_tags_list("课堂相关","");
        $teaching_tags = $this->get_tags_list("教学相关","");
        $tea_list      = \App\Helper\Utils::list_to_page_info($tea_list);
        return $this->pageView(__METHOD__,$tea_list,[
            "teacher_tags"  => $teacher_tags,
            "lesson_tags"   => $lesson_tags,
            "teaching_tags" => $teaching_tags,
            "require_info"  => $require_info,
            "userid"        => $userid,
        ]);
    }

    /**
     * 获取筛选项
     */
    public function get_tags_list($tag_l1_sort,$tag_l2_sort){
        $tags_list   = $this->t_tag_library->get_tag_name_list($tag_l1_sort,$tag_l2_sort);
        $select_list = [];
        foreach($tags_list as $val){
            $select_list[]=$val['tag_name'];
        }

        return $select_list;
    }

    /**
     * 试听排课选择老师列表
     * @param string redis_key 老师列表缓存
     * @param int lesson_start 学生试听开始时间
     * @param int lesson_end 学生试听结束时间
     * @param int grade    学生年级
     * @param int subject  学生试听科目
     * @param boolean refresh_flag 强制刷新
     * @param int identity 老师身份
     * @param int gender   老师性别要求
     * @param int tea_age  老师年龄要求
     * @param string subject_tags 学生试听需求老师标签
     * @param string teacher_tags 教师相关标签
     * @param string lesson_tags  课堂相关标签
     * @param string teaching_tags 教学相关标签
     * @param int teahcer_type 老师类型
     * @param int region_version 老师教材版本
     * @param int teacher_info 搜索的老师信息
     * @return array
     */
    public function get_teacher_list_for_test_lesson(
        $redis_key,$lesson_start,$lesson_end,$grade,$subject,$refresh_flag=false,$identity=-1,$gender=-1,$tea_age=-1,
        $subject_tags='',$teacher_tags='',$lesson_tags='',$teaching_tags='',$teacher_type=-1,$region_version=0,$teacher_info='',$is_test
    ){
        $grade_range_part = \App\Helper\Utils::change_grade_to_grade_range_part($grade);
        $ret_list  = \App\Helper\Common::redis_get_json($redis_key);
        if($ret_list === null || $refresh_flag){
            $all_tea_list = $this->t_teacher_info->get_teacher_list_by_subject($subject, $is_test);
            $tea_list     = $this->t_teacher_info->get_teacher_list_for_trial_lesson($lesson_start,$lesson_end,$subject, $is_test);
            $tea_list     = array_merge($all_tea_list, $tea_list);
            \App\Helper\Common::redis_set_expire_value($redis_key,$tea_list,7200);
        }else{
            $tea_list = $ret_list;
        }
        $textbook_map = E\Eregion_version::$desc_map;

        if(!empty($tea_list) && is_array($tea_list)){
            foreach($tea_list as $tea_key => &$tea_val){
                $grade_start = 0;
                $grade_end   = 0;
                $del_flag    = false;
                $limit_week_lesson_num  = $tea_val['limit_week_lesson_num'];
                $limit_plan_lesson_type = $tea_val['limit_plan_lesson_type'];
                $limit_day   = $tea_val['limit_day_lesson_num'];
                $day_num     = isset($tea_val['day_num'])?$tea_val['day_num']:0;
                $limit_week  = $limit_plan_lesson_type==0?$limit_week_lesson_num:$limit_plan_lesson_type;
                $week_num    = isset($tea_val['week_num'])?$tea_val['week_num']:0;
                $limit_month = $tea_val['limit_month_lesson_num'];
                $month_num   = isset($tea_val['month_num'])?$tea_val['month_num']:0;

                if($tea_val['subject']==$subject){
                    $grade_start = $tea_val['grade_start'];
                    $grade_end   = $tea_val['grade_end'];
                }elseif($tea_val['second_subject']==$subject){
                    $grade_start = $tea_val['second_grade_start'];
                    $grade_end   = $tea_val['second_grade_end'];
                }else{
                    $del_flag = true;
                }

                if($grade_range_part>$grade_end || $grade_range_part<$grade_start){
                    $del_flag = true;
                }
                if(($day_num>0 || $week_num>0 || $month_num>0)
                   && ($day_num>=$limit_day || $week_num >=$limit_week || $month_num>=$limit_month)
                ){
                    $del_flag = true;
                }

                $tea_val['age_flag']    = \App\Helper\Utils::check_teacher_age($tea_val['age']);
                $tea_val['is_identity'] = $identity==$tea_val['identity'] && $identity!=0?1:0;
                $tea_val['is_gender']   = $gender==$tea_val['gender'] && $gender!=0?1:0;
                $tea_val['is_age']      = $tea_age==$tea_val['age_flag'] && $tea_age!=0?1:0;
                if($teacher_info!="" && (strstr($tea_val['realname'],$teacher_info) || strstr($tea_val['phone'],$teacher_info))){
                    $tea_val['is_search'] = 1;
                }else{
                    $tea_val['is_search'] = 0;
                }

                $tea_val['is_textbook'] = 0;
                if($tea_val['teacher_textbook']!=""){
                    $teacher_textbook_arr = explode(",",$tea_val['teacher_textbook']);
                    $teacher_textbook_str = [];
                    foreach($teacher_textbook_arr as $textbook_val){
                        $teacher_textbook_str[] = @$textbook_map[$textbook_val];
                    }
                    if(in_array($region_version,$teacher_textbook_arr)){
                        $tea_val['is_textbook'] = 1;
                    }

                    $tea_val['teacher_textbook_str'] = implode(",",$teacher_textbook_str);
                }else{
                    $tea_val['teacher_textbook_str'] = "";
                }

                if($teacher_type==3){
                    $tea_val['is_teacher_type'] = $teacher_type==$tea_val['teacher_type']?1:0;
                }elseif($teacher_type==1){
                    $tea_val['is_teacher_type'] = $tea_val['teacher_type']!=3?1:0;
                }else{
                    $tea_val['is_teacher_type'] = 0;
                }

                if($del_flag){
                    unset($tea_list[$tea_key]);
                }else{
                    $tea_val['match_time'] = $this->match_teacher_free_time($tea_val['free_time_new'],$lesson_start,$lesson_end);
                    $tea_val['tags_str']   = $this->change_teacher_tags_to_string($tea_val['teacher_tags']);
                    $tea_val['match_tags'] = $this->match_tea_tags(
                        $tea_val['teacher_tags'],$subject_tags,$teacher_tags,$lesson_tags,$teaching_tags
                    );
                    $match_time[$tea_key]        = $tea_val['match_time'];
                    $match_tags[$tea_key]        = $tea_val['match_tags'];
                    $identity_list[$tea_key]     = $tea_val['is_identity'];
                    $gender_list[$tea_key]       = $tea_val['is_gender'];
                    $tea_age_list[$tea_key]      = $tea_val['is_age'];
                    $ruzhi_list[$tea_key]        = $tea_val['train_through_new_time'];
                    $teacher_type_list[$tea_key] = $tea_val['is_teacher_type'];
                    $search_list[$tea_key]       = $tea_val['is_search'];
                    $textbook_list[$tea_key]     = $tea_val['is_textbook'];
                    E\Eidentity::set_item_value_str($tea_val);
                    E\Egender::set_item_value_str($tea_val);
                    E\Eteacher_type::set_item_value_str($tea_val);

                    if($tea_val['train_through_new_time']>0){
                        $tea_val['work_day'] = \App\Helper\Utils::change_time_difference_to_day($tea_val['train_through_new_time']);
                    }else{
                        $tea_val['work_day'] = 0;
                    }
                    \App\Helper\Utils::hide_item_phone($tea_val);
                }
            }

            if(!empty($tea_list)){
                array_multisort(
                    $search_list,SORT_DESC,$identity_list,SORT_DESC,$gender_list,SORT_DESC,$tea_age_list,SORT_DESC,
                    $teacher_type_list,SORT_DESC,$textbook_list,SORT_DESC,$match_time,SORT_DESC,$match_tags,SORT_DESC,
                    $ruzhi_list,SORT_DESC,$tea_list
                );
            }
        }
        return $tea_list;
    }

    public function get_item_list(){
        $this->switch_tongji_database();
        // $this->check_and_switch_tongji_domain();
        list($start_time,$end_time)=$this->get_in_date_range_month(0);
        $adminid=$this->get_in_adminid();
        $month= date("Ym",$start_time);
        $call_count = $this->t_tq_call_info->get_call_count_by_adminid($start_time, $end_time,$adminid);
        $test_count = $this->t_test_lesson_subject_require->get_test_count_by_adminid($start_time,$end_time,$adminid);
        $order_count = $this->t_order_info->get_order_count_by_adminid($start_time,$end_time,$adminid);
        $order_refund_count = $this->t_order_refund->get_order_refund_count_by_adminid($start_time,$end_time,$adminid);
        $level10 = $this->t_seller_edit_log->get_10_level($adminid);
        $level11 = $this->t_seller_edit_log->get_11_level($adminid);
        $level11 = $level11>0?$level11:$level10;
        $level12 = $this->t_seller_edit_log->get_12_level($adminid);
        $level12 = $level12>0?$level12:$level11;
        //试听成功数
        $arr['called_times'] = $call_count['called_count'];
        $arr['no_called_times'] = $call_count['no_called_count'];
        $arr['suc_test_lesson_cout'] = $test_count['succ_all_count'];
        $arr['require_lesson_count'] = $test_count['test_lesson_count'];
        $arr['order_count'] = $order_count;
        $arr['refund_count'] = $order_refund_count;
        $arr['level11'] = E\Eseller_level::get_desc($level11);
        $arr['level12'] = E\Eseller_level::get_desc($level12);

        return $this->output_succ($arr);
    }

}
