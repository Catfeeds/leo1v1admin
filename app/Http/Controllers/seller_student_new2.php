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
        list($start_time,$end_time, $opt_date_str)=$this->get_in_date_range(
            -30,0, 1, [
                1 => array("tmk_assign_time","微信运营分配时间"),
                2 => array( "add_time", "资源进入时间"),
                3 => array( "lesson_start", "上课时间"),
            ]
        );

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
        $seller_student_stutus= $this->get_in_enum_val(E\Eseller_student_status::class,-1);

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
        if($adminid==349){
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
        if($adminid==349){
            $adminid=-1;
        }
        $this->set_in_value("limit_require_send_adminid",$adminid);
        $this->set_in_value("limit_require_flag",1);
        return $this->test_lesson_plan_list();
    }

    public function test_lesson_plan_list()
    {
        $cur_page = $this->get_in_str_val("cur_page");
        list($start_time,$end_time,$opt_date_str) = $this->get_in_date_range(0, 7, 1, [
            1 => array("require_time","申请时间"),
            2 => array("stu_request_test_lesson_time", "期待试听时间"),
            4 => array("lesson_start", "上课时间"),
            5 => array("seller_require_change_time ", "销售申请更换时间"),
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
        $accept_adminid             = $this->get_in_int_val("accept_adminid",-1);
        $is_jw                      = $this->get_in_int_val("is_jw",0);
        $is_ass_tran                = $this->get_in_int_val("is_ass_tran",0);
        $limit_require_flag         = $this->get_in_int_val("limit_require_flag",-1);
        $limit_require_send_adminid = $this->get_in_int_val("limit_require_send_adminid",-1);
        $require_id                 = $this->get_in_int_val("require_id",-1);
        $has_1v1_lesson_flag        = $this->get_in_int_val("has_1v1_lesson_flag",-1,E\Eboolean::class);

        $ret_info = $this->t_test_lesson_subject_require->get_plan_list(
            $page_num, $opt_date_str, $start_time,$end_time ,$grade,
            $subject, $test_lesson_student_status,$teacherid, $userid,$lessonid ,
            $require_admin_type , $require_adminid ,$ass_test_lesson_type, $test_lesson_fail_flag,$accept_flag ,
            $success_flag,$is_test_user,$tmk_adminid,$require_adminid_list,$adminid_all=[],
            $seller_require_change_flag,$require_assign_flag, $has_1v1_lesson_flag,$accept_adminid,$is_jw,
            $jw_test_lesson_status,$jw_teacher,$tea_subject,$is_ass_tran,$limit_require_flag,$limit_require_send_adminid,$require_id
        );

        $start_index = \App\Helper\Utils::get_start_index_from_ret_info($ret_info) ;
        foreach($ret_info["list"] as $id => &$item){
            $item['id'] = $start_index+$id;
            $item["lesson_time"] = $item["lesson_start"];
            $item["except_lesson_time"] = $item["stu_request_test_lesson_time"];
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

        }

        $adminid           = $this->get_account_id();
        $admin_work_status = $this->t_manager_info->get_admin_work_status($adminid);

        $jw_teacher_list = $this->t_manager_info->get_jw_teacher_list_new();

        return $this->pageView(__METHOD__,$ret_info,[
            "cur_page"          => $cur_page,
            "adminid_right"     => $adminid_right,
            "admin_work_status" => $admin_work_status,
            "jw_teacher_list"   => $jw_teacher_list,
            "adminid"           => $adminid
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
        // dd($ret_info);
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
        $ret_info=$this->t_seller_new_count->get_admin_list_get_count($adminid);
        $admin_map=$this->t_seller_new_count->get_admin_list_count($adminid);

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
        // dd($ret_list);
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

    public function grab_test_lesson_plan(){
        $requireid   = $this->get_in_str_val("requireid");
        $grab_status = $this->get_in_int_val("grab_status",-1);

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

        $list = $this->t_test_lesson_subject_require->get_grab_test_lesson_list($subject,1);
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
        list($start_time,$end_time) = $this->get_in_date_range( "2017-07-20" ,0 );
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
}
