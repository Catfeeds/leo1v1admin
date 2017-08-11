<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;

include(app_path("Libs/LaneWeChat/lanewechat.php"));


class seller_student_new extends Controller
{
    use CacheNick;
    //分配例子--主管
    public function assign_member_list ( ) {
        $adminid=$this->get_account_id();
        $self_groupid=$this->t_admin_group_name->get_groupid_by_master_adminid($adminid);
        if (!$self_groupid) {
            return $this->error_view(["你不是销售主管"]);
        }

        $this->set_in_value("self_groupid", $self_groupid );
        $this->set_in_value("sub_assign_adminid_2",$adminid );
        if (!$this->check_in_has( "admin_revisiterid")) {
            $this->set_in_value("admin_revisiterid", 0);
        }


        return $this->assign_sub_adminid_list();
    }
    public function  tmk_assign_sub_adminid_list() {
        $this->set_in_value("has_pad", E\Epad_type::V_10 );
        $this->set_in_value("sub_assign_adminid_2", -1);
        return $this->assign_sub_adminid_list();

    }


    public function do_filter(){
        // $this->set_in_value("filter_flag", 1);
        session(['filter_flag'=>1]);
        return $this->output_succ();
        // return $this->assign_sub_adminid_list();
    }

    //分配例子
    public function assign_sub_adminid_list(){
        $self_groupid = $this->get_in_int_val("self_groupid",-1);

        list($start_time,$end_time,$opt_date_str)= $this->get_in_date_range(
            -30*6, 0, 0, [
            0 => array( "add_time", "资源进来时间"),
            4 => array("sub_assign_time_2","分配给主管时间"),
            5 => array("admin_assign_time","分配给组员时间"),
            6 => array("tmk_assign_time","微信分配时间"),
            ], 0
        );

        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type)=$this->get_in_order_by_str( );

        $userid            = $this->get_in_userid(-1);
        $origin            = trim($this->get_in_str_val('origin', ''));
        $origin_ex         = $this->get_in_str_val('origin_ex', "");
        // dd($origin_ex);
        $grade             = $this->get_in_el_grade();
        $subject           = $this->get_in_subject(-1);
        $phone_location    = trim($this->get_in_str_val('phone_location', ''));
        $admin_revisiterid = $this->get_in_int_val('admin_revisiterid', -1);
        $tq_called_flag    = $this->get_in_int_val("tq_called_flag", -1,E\Etq_called_flag::class);
        $global_tq_called_flag = $this->get_in_int_val("global_tq_called_flag", -1,E\Etq_called_flag::class);
        $seller_student_status = $this->get_in_e_seller_student_status(-1);

        $page_num              = $this->get_in_page_num();
        $page_count            = $this->get_in_page_count();
        $has_pad               = $this->get_in_int_val("has_pad", -1, E\Epad_type::class);
        $sub_assign_adminid_2  = $this->get_in_int_val("sub_assign_adminid_2", 0);
        $origin_assistantid    = $this->get_in_int_val("origin_assistantid",-1  );
        $tmk_adminid           = $this->get_in_int_val("tmk_adminid",-1, "");
        $account_role     = $this->get_in_enum_list(E\Eaccount_role::class, -1 );
        $origin_level          = $this-> get_in_el_origin_level("0,1,2,3,4");
        $seller_student_sub_status = $this->get_in_enum_val(E\Eseller_student_sub_status::class,-1);
        $tmk_student_status        = $this->get_in_int_val("tmk_student_status", -1, E\Etmk_student_status::class);
        $seller_resource_type      = $this->get_in_int_val("seller_resource_type",0, E\Eseller_resource_type::class);
        $sys_invaild_flag  =$this->get_in_e_boolean(0,"sys_invaild_flag");
        $publish_flag  = $this->get_in_e_boolean(1,"publish_flag");
        $show_list_flag = $this->get_in_int_val("show_list_flag", 0);
        $seller_level = $this->get_in_el_seller_level();

        $admin_del_flag  = $this->get_in_e_boolean(-1 ,"admin_del_flag");
        //wx
        $wx_invaild_flag  =$this->get_in_e_boolean(-1,"wx_invaild_flag");
        //dd($wx_invaild_flag);
        $do_filter = $this->get_in_e_boolean(-1,'filter_flag');
        $first_seller_adminid= $this->get_in_int_val('first_seller_adminid', -1);
        $call_phone_count= $this->get_in_int_val("call_phone_count", -1);
        $this->switch_tongji_database();

        $this->t_seller_student_new->switch_tongji_database();
        $ret_info = $this->t_seller_student_new->get_assign_list(
            $page_num,$page_count,$userid,$admin_revisiterid,$seller_student_status,
            $origin,$opt_date_str,$start_time,$end_time,$grade,
            $subject,$phone_location,$origin_ex,$has_pad,$sub_assign_adminid_2,
            $seller_resource_type,$origin_assistantid,$tq_called_flag,$global_tq_called_flag,$tmk_adminid,
            $tmk_student_status,$origin_level,$seller_student_sub_status, $order_by_str,$publish_flag
            ,$admin_del_flag ,$account_role , $sys_invaild_flag ,$seller_level, $wx_invaild_flag,$do_filter,$first_seller_adminid ,$call_phone_count );
        $start_index=\App\Helper\Utils::get_start_index_from_ret_info($ret_info);

        foreach( $ret_info["list"] as $index=> &$item ) {
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"tmk_assign_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"sub_assign_time_2");
            \App\Helper\Utils::unixtime2date_for_item($item,"admin_assign_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"competition_call_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"first_contact_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"first_call_time");
            $item["opt_time"] = $item[$opt_date_str];
            $item["index"]    = $start_index + $index ;
            E\Eseller_student_status::set_item_value_str($item);
            E\Eseller_student_sub_status::set_item_value_str($item);
            E\Etmk_student_status::set_item_value_str($item);
            E\Ebook_grade::set_item_value_str($item,"grade");
            E\Eseller_resource_type::set_item_value_str($item);
            E\Eboolean::set_item_value_str($item,"sys_invaild_flag");
            E\Esubject::set_item_value_str($item);
            E\Epad_type::set_item_value_str($item,"has_pad");
            E\Etq_called_flag::set_item_value_str($item,"global_tq_called_flag");
            E\Eorigin_level::set_item_value_str($item);
            $this->cache_set_item_account_nick($item,"sub_assign_adminid_2","sub_assign_admin_2_nick");
            $this->cache_set_item_account_nick($item,"admin_revisiterid","admin_revisiter_nick");
            $this->cache_set_item_account_nick($item,"origin_assistantid","origin_assistant_nick");
            $this->cache_set_item_account_nick($item,"tmk_adminid","tmk_admin_nick");
            $this->cache_set_item_account_nick($item,"competition_call_adminid","competition_call_admin_nick");
            $this->cache_set_item_account_nick($item,"require_adminid","require_admin_nick");

        }

        // 未分配信息
        if ($self_groupid >0 ) { //主管
            $unallot_info=$this->t_test_lesson_subject->get_unallot_info_sub_assign_adminid_2($sub_assign_adminid_2);
        }else{
            $unallot_info=$this->t_test_lesson_subject->get_unallot_info( );
        }
        return $this->pageView(__METHOD__,$ret_info,[
           "unallot_info" => $unallot_info
        ]);
    }

    public function get_page_hide_list($cur_page) {
        $page_hide_list=[];
        if( $cur_page ==0 ) {
            $page_hide_list["stu_test_paper_flag_str"] = true;
        }

        if( $cur_page <110 ) {
            $page_hide_list["teacher_nick"] = true;
            $page_hide_list["lesson_start"] = true;
        }
        return $page_hide_list;
    }

    public function seller_student_list_data(){
        $status_list_str = $this->get_in_str_val("status_list_str");
        $no_jump         = $this->get_in_int_val("no_jump",0);
        $this->set_filed_for_js("account_seller_level", session("seller_level" ) );

        list($start_time,$end_time,$opt_date_str)= $this->get_in_date_range(
            -180, 0, 4, [
                0 => array( "add_time", "资源进来时间"),
                1 => array("next_revisit_time","下次回访时间"),
                2 => array("last_revisit_time","最后一次回访"),
                3 => array("require_time","申请时间"),
                4 => array("admin_assign_time","分配时间"),
                5 => array("lesson_start","上课时间"),
                6 => array("seller_require_change_time","申请更改时间"),
                7 => array("pay_time","签单时间"),
                // 8 => array("last_lesson_time","结课时间"),
            ], 0
        );

        $adminid_list          = $this->get_in_str_val("adminid_list");
        $origin_assistant_role = $this->get_in_int_val("origin_assistant_role",-1,E\Eaccount_role::class  );
        $origin                = trim($this->get_in_str_val('origin', ''));
        $page_num              = $this->get_in_page_num();
        $userid                = $this->get_in_userid(-1);
        $seller_student_status = $this->get_in_int_val('seller_student_status', -1, E\Eseller_student_status::class);
        $seller_groupid_ex     = $this->get_in_str_val('seller_groupid_ex', "");
        $require_adminid_list  = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);

        $phone_location        = trim($this->get_in_str_val('phone_location', ''));
        $page_count            = $this->get_in_int_val("page_count",10);
        $require_admin_type    = $this->get_in_int_val("require_admin_type",-1);
        $subject               = $this->get_in_subject(-1);
        $has_pad               = $this->get_in_int_val("has_pad", -1, E\Epad_type::class);

        $seller_level   = $this->get_in_el_seller_level();
        $tq_called_flag              = $this->get_in_int_val("tq_called_flag", -1,E\Etq_called_flag::class);
        $seller_resource_type        = $this->get_in_int_val("seller_resource_type",-1, E\Eseller_resource_type::class);
        $origin_assistantid          = $this->get_in_int_val("origin_assistantid",-1  );
        $origin_userid = $this->get_in_int_val("origin_userid",-1  );
        $admin_revisiterid           = $this->get_account_id();
        /* if($admin_revisiterid==349){
            $admin_revisiterid=735;
            }*/
        $admin_revisiterid           = $this->get_in_int_val( "admin_revisiterid", $admin_revisiterid );
        $success_flag                = $this->get_in_int_val("success_flag", -1, E\Eset_boolean::class);
        $seller_require_change_flag  = $this->get_in_int_val("seller_require_change_flag",-1);
        $end_class_flag              = $this->get_in_int_val("end_class_flag",-1);
        $group_seller_student_status = $this->get_in_enum_val(E\Egroup_seller_student_status::class,-1);
        $tmk_student_status          = $this->get_in_e_tmk_student_status(-1);
        $phone_name                  = trim($this->get_in_str_val("phone_name"));

        $grade = -1;
        $nick  = "";
        $phone = "";
        if($phone_name){
            if (!($phone_name>0)) {
                $nick=$phone_name;
            }else{
                $phone=$phone_name;
            }
        }
        $check_need_jump_flag= $nick || $phone || ($userid != -1 ) ;
        if ( $check_need_jump_flag){
            $status_list_str="";
        }

        $ret_info = $this->t_seller_student_new->get_seller_list(
            $page_num, $admin_revisiterid,  $status_list_str, $userid, $seller_student_status ,
            $origin, $opt_date_str, $start_time, $end_time, $grade, $subject,
            $phone_location,   $has_pad, $seller_resource_type,$origin_assistantid  ,
            $tq_called_flag , $phone, $nick ,$origin_assistant_role ,$success_flag,
            $seller_require_change_flag,$adminid_list, $group_seller_student_status ,$tmk_student_status,$require_adminid_list,
            $page_count,$require_admin_type ,$origin_userid,$end_class_flag ,$seller_level  ) ;
        $now=time(null);
        $notify_lesson_check_end_time=strtotime(date("Y-m-d", $now+86400*2));
        $next_day=$notify_lesson_check_end_time-86400;
        $notify_lesson_check_start_time=$now - 3600;

        foreach ($ret_info["list"] as &$item) {
            if($item['call_end_time']){
                $item["call_end_time"] = date('Y-m-d H:i',$item['call_end_time']);
            }else{
                $item["call_end_time"] = '';
            }
            if($item["lesson_start"] > 0){
                $item["lesson_plan_status"] = "已排课";
            }else{
                $item["lesson_plan_status"] = "未排课";
            }
            $lesson_start= $item["lesson_start"];
            $parent_confirm_time = $item["parent_confirm_time"];
            $notify_lesson_flag_str="";
            $notify_lesson_flag=0;

            E\Eregion_version::set_item_value_str($item,"editionid");
            $item["stu_request_test_lesson_time_old"] = $item["stu_request_test_lesson_time"];
            \App\Helper\Utils::unixtime2date_for_item($item, "add_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "parent_confirm_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "last_revisit_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "admin_assign_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "require_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "seller_require_change_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "lesson_start","", "Y-m-d H:i");
            \App\Helper\Utils::unixtime2date_for_item($item, "next_revisit_time", "", "Y-m-d H:i");
            \App\Helper\Utils::unixtime2date_for_item($item, "confirm_time", "", "Y-m-d H:i");
            \App\Helper\Utils::unixtime2date_for_item($item, "pay_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "last_lesson_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "stu_request_test_lesson_time");
            $item["user_agent"]=\App\Helper\Utils::get_user_agent_info($item["user_agent"]);


            \App\Helper\Common::set_item_enum_flow_status($item,"stu_test_paper_flow_status");
            $item["opt_time"]=$item[$opt_date_str];

            $item["last_revisit_msg_sub"]=mb_substr($item["last_revisit_msg"], 0, 40, "utf-8");
            $item["user_desc_sub"]=mb_substr($item["user_desc"], 0, 40, "utf-8");
            E\Ebook_grade::set_item_value_str($item,"grade");
            E\Esubject::set_item_value_str($item);
            E\Epad_type::set_item_value_str($item, "has_pad");
            E\Eseller_student_status::set_item_value_str($item);
            E\Ebook_grade::set_item_value_str($item, "grade");
            E\Etq_called_flag::set_item_value_str($item );

            $item["success_flag_str"]=\App\Helper\Common::get_set_boolean_color_str( $item["success_flag"] );
            E\Eboolean::set_item_value_str($item,"fail_greater_4_hour_flag");
            $item["lesson_used_flag_str"]=\App\Helper\Common::get_boolean_color_str(!$item["lesson_del_flag"]);
            E\Etest_lesson_fail_flag::set_item_value_str($item);
            E\Etest_lesson_order_fail_flag::set_item_value_str($item);
            $this->cache_set_item_account_nick($item,"confirm_adminid","confirm_admin_nick");

            $this->cache_set_item_teacher_nick($item);
            $this->cache_set_item_account_nick($item,"origin_assistantid","origin_assistant_nick");
            $this->cache_set_item_student_nick($item,"origin_userid","origin_user_nick");
            $this->cache_set_item_account_nick($item,"admin_revisiterid","admin_revisiter_nick");
            /*
            if ($item["lesson_del_flag"] ) { //
                $item["lesson_start"] ="";
                $item["teacher_nick"] ="";
            }
            */


            $item["stu_test_paper_flag_str"]=\App\Helper\Common::get_test_pager_boolean_color_str(
                $item["stu_test_paper"],
                $item['tea_download_paper_time']);

            $notify_lesson_flag_str="";
            $notify_lesson_flag=0;

            $notify_lesson_flag_str="";
            $notify_lesson_flag=0;

            if ($item["lesson_del_flag"]==0) {



                if (    $lesson_start >= $notify_lesson_check_start_time
                        && $lesson_start< $notify_lesson_check_end_time
                ) {
                    $notify_lesson_day1=$item["notify_lesson_day1"];
                    $notify_lesson_day2=$item["notify_lesson_day2"];
                    if ($lesson_start<$next_day && $notify_lesson_day1 ==0) { // 今天的课
                        $notify_lesson_flag_str="<font color=red>未通知</font>";
                        $notify_lesson_flag=1;
                    } elseif ($lesson_start>=$next_day && $notify_lesson_day2 ==0) { // 今天的课
                        $notify_lesson_flag_str="<font color=red>未通知</font>";
                        $notify_lesson_flag=1;
                    } else {
                        $notify_lesson_flag=2;
                        $notify_lesson_flag_str="<font color=green>已通知</font>";
                    }
                }
            }
            $item["notify_lesson_flag_str"]=$notify_lesson_flag_str;
            $item["notify_lesson_flag"]=$notify_lesson_flag;
            if ($lesson_start) {
                /*
                if (!$parent_confirm_time  ) {
                    $item["notify_lesson_flag_str"].="<font color=red> 家长微信未确认 </font>";
                }else{
                    $item["notify_lesson_flag_str"].="家长确认时间:".$item["parent_confirm_time"]   ;
                }
                */
            }

            $this->cache_set_item_account_nick($item,"accept_adminid","accept_admin_nick");
            E\Eseller_require_change_flag::set_item_value_str($item,"seller_require_change_flag");
            if(empty($item["seller_require_change_flag"])) $item["seller_require_change_flag_str"]="";
            if(!empty($item["lesson_total"])) {
                $item["lesson_price"] = $item["price"]/$item["lesson_total"];
            }else{
                $item["lesson_price"] = "";
            }
            $item["all_price"] = $item["price"]+$item["discount_price"];
        }


        $count_info =$this->t_seller_student_new->get_seller_count_list(
            $admin_revisiterid,  $status_list_str, $userid, $seller_student_status ,
            $origin, $opt_date_str, $start_time, $end_time, $grade, $subject,
            $phone_location,   $has_pad, $seller_resource_type,$origin_assistantid  ,
            $tq_called_flag , $phone, $nick ,$origin_assistant_role ,$success_flag,
            $seller_require_change_flag,$adminid_list,$tmk_student_status,$require_adminid_list,
            $require_admin_type ) ;

        $ret_info["count_info"] = $count_info;
        return $ret_info;

    }

    public function find_user() {
        $phone=trim($this->get_in_phone());
        $page_num= $this-> get_in_page_num();
        $ret_info= $this->t_test_lesson_subject->get_list_by_phone($page_num,$phone );
        foreach ($ret_info["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item, "add_time");
            E\Esubject::set_item_value_str($item);
            E\Eseller_resource_type::set_item_value_str($item);
            $this->cache_set_item_account_nick($item,"sub_assign_adminid_2", "sub_assign_admin_2_nick" );
            $this->cache_set_item_account_nick($item,"admin_revisiterid", "admin_revisiter_nick" );
            //更新数据
            if ($item["require_adminid"] != $item["admin_revisiterid"] ) { //数据有误
                $this->t_test_lesson_subject->field_update_list($item["test_lesson_subject_id"],[
                    "require_adminid" => $item["admin_revisiterid"],
                ]);
            }
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function seller_student_list( )
    {
        $this->switch_tongji_database();
        $cur_page       = $this->get_in_int_val("cur_page");
        $page_hide_list = $this->get_page_hide_list($cur_page);

        $ret_info = $this->seller_student_list_data();

        unset($ret_info["count_info"]);

        $adminid = $this->get_account_id();
        $start_time = strtotime(date("Y-m-01",strtotime(date("Y-m-01",time()))-86400));
        $self_top_info =$this->t_tongji_seller_top_info->get_admin_top_list( $adminid,  $start_time );
        $ranking = @$self_top_info[6]["top_index"];

        //销售主管以上列表
        $seller_master_list = $this->t_admin_group_name->get_all_master_adminid_list(2);

        $seller_master_list[] = "349";
        if(in_array($adminid,$seller_master_list)){
            $is_seller_master= 1;
        }else{
            $is_seller_master= 0;
        }
        //test
        $this->set_filed_for_js("jack_flag",$adminid);
        $this->set_filed_for_js("admin_seller_level", session("seller_level" ) );
        // dd($ret_info);
        return $this->pageView(__METHOD__,$ret_info, [
            "page_hide_list" => $page_hide_list,
            "cur_page" => $cur_page,
            "is_seller_master"=>$is_seller_master
        ] );
    }

    //销售自己的例子
    public function seller_student_list_ex( $id,$status_list_str="" )
    {
        if ($status_list_str=="") {
            $status_list_str=$id;
        }
        if ($status_list_str==-1) {
            $status_list_str="";
        }

        $this->set_in_value("status_list_str",$status_list_str) ;
        $this->set_in_value("cur_page","$id") ;
        return $this->seller_student_list();
    }

    public function seller_student_list_all( )
    {
        return $this->seller_student_list_ex(10000, -1);
    }


    public function seller_student_list_0( )
    {
        return $this->seller_student_list_ex(0,"0,2");
    }

    public function seller_student_list_103( )
    {
        if (!$this->check_in_has("seller_student_status") ) {
            $this->set_in_value("seller_student_status",  100);

        }
        return $this->seller_student_list_ex(103,"100,103");
    }
    public function seller_student_list_101( )
    {
        return $this->seller_student_list_ex(101,"101,102");
    }

    public function seller_student_list_110( )
    {
        return $this->seller_student_list_ex(110,"110,120");
    }

    public function seller_student_list_200( )
    {
        return $this->seller_student_list_ex(200);
    }
    public function seller_student_list_1( )
    {
        return $this->seller_student_list_ex(1);
    }


    public function seller_student_list_210( )
    {
        //$this->set_in_value("date_type", 5 ); //上课时间
        return $this->seller_student_list_ex(210);
    }

    //5.新增待开课（已排课未上课，体现试卷，测试完成情况；该栏最多显示20条，如超过，先为老课程选择转化成功或失败原因）
    public function seller_student_list_220( )
    {
        return $this->seller_student_list_ex(220);
    }

    public function seller_student_list_290( )
    {
        return $this->seller_student_list_ex(290);
    }


    //6.待跟进（课后从待开课中选择未签跟进A档、未签跟进B档、未签跟进C档）
    public function seller_student_list_301( )
    {
        return $this->seller_student_list_ex(301, "300,301,302,420");
    }



    //1增加待跟进（所有未联系用户）
    //2.新增优先跟进（未排课跟进意向大）

    //3.新增待排课（所有提交未排课）
    //4.新增待通知（已排课未确认）
    //5.新增待开课（已排课未上课，体现试卷，测试完成情况；该栏最多显示20条，如超过，先为老课程选择转化成功或失败原因）

    //6.待跟进（课后从待开课中选择未签跟进A档、未签跟进B档、未签跟进C档）
    //7.待付费（支付审核）
    //8.待交接（交接档案，合同，发票，大礼包）
    //9.签约完成


    public function tmk_no_called_list()
    {
        $this->set_in_value("tmk_flag",1);
        return $this->no_called_list();
    }

    public function get_one_new_user(){
        $now=time(NULL);
        $page_num=1;
        $grade=-1;
        $has_pad=-1;
        $subject=-1;
        $origin="";
        $phone="";
        $adminid=$this->get_account_id();
        $t_flag=0;
        $ret_info= $this->t_seller_student_new->get_new_list($page_num, $now-30*3*86400 ,$now, $grade, $has_pad, $subject,$origin,$phone,$adminid ,$t_flag );
        $userid=@ $ret_info["list"][0]["userid"];
        if ($userid) {
            $lesson_call_end = [];
            $key="DEAL_NEW_USER_$adminid";
            $old_userid=\App\Helper\Common::redis_get($key)*1;
            $old_row_data= $this->t_seller_student_new->field_get_list($old_userid,"competition_call_time, competition_call_adminid, admin_revisiterid ,tq_called_flag ");

            if (
                $old_row_data["tq_called_flag"] ==0 &&
                $old_row_data["admin_revisiterid"] !=$adminid
                &&  $now- $old_row_data["competition_call_time"] < 3600  ) {
                return $this->output_err("有新例子tq未拨打",["need_deal_cur_user_flag" =>true]);

            }

            if(!$this->t_seller_student_new->check_admin_add($adminid,$get_count,$max_day_count )){
                return $this->output_err("目前你持有的例子数[$get_count]>=最高上限[$max_day_count]");
            }

            if (!$this->t_seller_new_count->get_free_new_count_id($adminid,"获取新例子"))  {
                return $this->output_err("今天的配额,已经用完了");
            }
            //检查是否有成功试听未回访
            // $lesson_call_end = $this->t_lesson_info_b2->get_call_end_time_by_adminid($adminid);
            // $userid_new = $lesson_call_end['userid'];
            // if($userid_new){
            //     return $this->output_err("有试听课成功未回访",["userid" =>$userid_new]);
            // }

            $row_data= $this->t_seller_student_new->field_get_list($userid,"competition_call_time, competition_call_adminid, admin_revisiterid,phone ");
            $competition_call_time = $row_data["competition_call_time"];
            $competition_call_adminid = $row_data["competition_call_adminid"];
            $admin_revisiterid = $row_data["admin_revisiterid"];
            if ($admin_revisiterid !=  0  && $admin_revisiterid != $adminid) {
                return $this->output_err("已经被人抢了1");
            }
            if ( $competition_call_adminid != $adminid &&  $now- $competition_call_time  < 3600  ) { //
                return $this->output_err("已经被人抢了2");
            }

            $this->t_test_subject_free_list ->row_insert([
                "add_time" => time(NULL),
                "userid" =>   $userid,
                "adminid" => $adminid,
                "test_subject_free_type" => 0,
            ],false,true);
            $this->t_seller_student_new->field_update_list($userid,[
                "competition_call_time" => $now,
                "tq_called_flag" => 0,
                "competition_call_adminid" =>$adminid,
                "user_desc" => "",
            ]);

            $this->t_test_lesson_subject->field_update_list($userid,[
                "seller_student_status" => 0,
                "stu_request_test_lesson_demand" => "",
            ]);

            \App\Helper\Common::redis_set($key, $userid );

            return $this->output_succ(["phone" => $row_data["phone"]] );

        }else{
            return $this->output_err("没有资源了");

        }
    }

    public function get_new_list_data()
    {
        $cur_hm=date("H")*60+date("i");
        $limit_arr=array( 10*60, 14*60,
                           18*60, 19*60, 20*60 ,22*60,23*60+50 );
        $seller_level=$this->t_manager_info->get_seller_level($this->get_account_id() );
        $success_flag=false;
        $time_str_list=[];


        foreach( $limit_arr  as $limit_time ) {
            /*
            if (!($seller_level==1 || $seller_level==2 )) {
                $limit_time+=10;
            }
            */
            $limit_time=$limit_time%1440;

            if ($cur_hm >= $limit_time && $cur_hm < $limit_time+10 ) { //间隔10分钟
                $success_flag=true;
            }
            $time_str_list[]=sprintf("%02d:%02d~%02d:%02d",
                                     $limit_time/60, $limit_time%60,
                                     ($limit_time+10)/60%24, ($limit_time+10)%60);
        }
        $errors=[];
        if( ! (
             true
            || $this->check_power( E\Epower::V_TEST)
        )  ){
            if ( !$success_flag) {
                $errors=[
                    "抢新学生,当前关闭!!",
                    "开抢时间:每天 ".  join(",",$time_str_list) ,
                    "当前时间:". date("H:i:s"),
                ];
            }else{
                $errors=[];
            }
        }else{
            $success_flag=true;
        }

        //list($start_time,$end_time)= $this->get_in_date_range(-7,0 );
        $page_num   = $this->get_in_page_num();

        $grade=$this->get_in_grade(-1);
        $has_pad=$this->get_in_has_pad(-1);
        $subject=$this->get_in_subject(-1);
        $origin=trim($this->get_in_str_val("origin",""));
        $t_flag=$this->get_in_int_val("t_flag", 0);
        $now=time(NULL);
        $adminid=$this->get_account_id();
        $phone=$this->get_in_phone();

        if ($success_flag) {
            $ret_info= $this->t_seller_student_new->get_new_list($page_num, $now-30*3*86400 ,$now, $grade, $has_pad, $subject,$origin,$phone,$adminid ,$t_flag );
            foreach ($ret_info["list"] as &$item) {
                \App\Helper\Utils::unixtime2date_for_item($item, "add_time");
                E\Epad_type::set_item_value_str($item, "has_pad");
                E\Esubject::set_item_value_str($item);
                E\Egrade::set_item_value_str($item);
                \App\Helper\Utils::hide_item_phone($item);
                E\Eorigin_level::set_item_value_str($item);
            }
        }else{
            $ret_info=\App\Helper\Utils::list_to_page_info([]);
        }
        /*
        $max_day_count=$this->t_manager_info->get_max_get_new_user_count($adminid,$v1,$v2);
        $cur_count=\App\Helper\Common::redis_get_json_date("SELLER_NEW_USER1_$adminid");
        */
        $count_info=$this->t_seller_new_count->get_now_count_info($adminid);
        $max_day_count= $count_info["count"];
        $cur_count= $count_info["get_count"];

        $ret_arr = [];
        $ret_arr["ret_info"] =  $ret_info;
        $ret_arr["max_day_count"] =  $max_day_count;
        $ret_arr["cur_count"] =  $cur_count;
        $ret_arr["left_count"] =  $max_day_count-$cur_count;
        $ret_arr["errors"] =  $errors;

        return $ret_arr;
    }

    //抢单列表
    public function competition_list(){

    }

    public function get_new_list_tmk(){
        if ($this->get_in_int_val("t_flag") ==0) {
            $this->set_in_value("t_flag",1);
        }
        return $this->get_new_list();
    }

    public function get_new_list(){

        if ($this->get_in_int_val("t_flag")==0) {
            return $this->error_view(["不再使用,请在  <a href=\"/seller_student_new/deal_new_user\">  抢新学生  </a> 拨打 "]);
        }

        $ret_arr       = $this->get_new_list_data();

        $ret_info      = $ret_arr["ret_info"] ;
        $max_day_count = $ret_arr["max_day_count"];
        $cur_count     = $ret_arr["cur_count"] ;
        $left_count    = $ret_arr["left_count"];
        $errors        = $ret_arr["errors"] ;
        $seller_level  = $this->t_manager_info->get_seller_level($this->get_account_id() );
        $this->set_filed_for_js("seller_level", $seller_level);

        return $this->pageView(__METHOD__, $ret_info,[
            "max_day_count" => $max_day_count*1,
            "cur_count" => $cur_count*1,
            "left_count" => $left_count,
            "errors" => $errors,
        ]);

    }
    public function no_called_list()
    {
        //list($start_time,$end_time)= $this->get_in_date_range(-7,0 );
        $page_num   = $this->get_in_page_num();
        $this->get_in_int_val("tmk_flag",0);

        $grade=$this->get_in_grade(-1);
        $has_pad=$this->get_in_has_pad(-1);
        $subject=$this->get_in_subject(-1);
        $origin=trim($this->get_in_str_val("origin",""));
        $ret_info= $this->t_seller_student_new->get_no_called_list($page_num, $this->get_account_id(), $grade, $has_pad, $subject,$origin );
        foreach ($ret_info["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item, "add_time");
            E\Epad_type::set_item_value_str($item, "has_pad");
            E\Esubject::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);
            \App\Helper\Utils::hide_item_phone($item);
        }
        return $this->pageView(__METHOD__, $ret_info);

    }

    public function test_lesson_no_order_list_data() {

        $this->switch_tongji_database();

        $page_num = $this->get_in_page_num();
        list($start_time,$end_time) = $this->get_in_date_range(-80,0 );
        $grade                      = $this->get_in_grade(-1);
        $phone                      = trim($this->get_in_phone());
        $can_reset_seller_flag      = 1;

        $adminid  = $this->get_account_id();
        $date_key = date("Ymd");


        $json_ret=\App\Helper\Common::redis_get_json("SELLER_TEST_LESSON_USER_$adminid");

        if (!$json_ret || $json_ret["opt_date"] != $date_key ) {
            $json_ret=[
                "opt_date" => $date_key ,
                "opt_count" => 0,
            ];
            \App\Helper\Common::redis_set_json("SELLER_TEST_LESSON_USER_$adminid", $json_ret);
        }

        $seller_level = $this->t_manager_info->get_seller_level( $adminid);

        $seller_level_str    = E\Eseller_level::get_desc($seller_level);
        $seller_level_config = \App\Helper\Config::get_seller_test_lesson_user_month_limit();
        $level_limit_count   = @$seller_level_config[$seller_level];
        $last_count          = $level_limit_count - $json_ret['opt_count'];

       $ret_info = $this->t_test_lesson_subject->get_test_lesson_lost_user_list(
           $page_num, $start_time, $end_time,$grade, $adminid,   $phone
       );

        foreach($ret_info['list'] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start");
            E\Egrade::set_item_value_str($item);
            E\Egender::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            \App\Helper\Utils::hide_item_phone($item);
        }

        $ret_arr=[];
        $ret_arr["ret_info"] = $ret_info;
        $ret_arr["seller_level_str"] = $seller_level_str;
        $ret_arr["opt_count"] =$json_ret['opt_count'] ;
        $ret_arr["last_count"] = $last_count;
        return $ret_arr;

    }

    public function test_lesson_no_order_list() {
        $ret_arr = $this->test_lesson_no_order_list_data();
        $ret_info         = $ret_arr["ret_info"];
        $seller_level_str = $ret_arr["seller_level_str"];
        $opt_count        = $ret_arr["opt_count"];
        $last_count       = $ret_arr["last_count"];
        return $this->pageView(__METHOD__, $ret_info, [
            "seller_level_str"  => $seller_level_str,
            "opt_count"  => $opt_count,
            "last_count"  => $last_count
        ]);

    }

    public function ass_seller_student_list() {
        $this->set_in_value("origin_assistantid",$this->get_account_id());
        $this->set_in_value("admin_revisiterid",-1);
        $this->set_in_value("cur_page",10001);
        $this->check_in_not_has_and_set("date_type",0);

        return $this->seller_student_list();
    }

    public function seller_seller_student_list(){
        return $this->ass_seller_student_list();
    }

    public function ass_master_seller_master_student_list() {
        if (!$this->check_in_has("origin_assistantid") ) {
            $this->set_in_value("origin_assistantid",-2); //是转介绍
        }
        $this->set_in_value("admin_revisiterid",-1);
        $this->set_in_value("cur_page",10002);

        $adminid = $this->get_account_id();
        $list    = $this->t_admin_group_user->get_userid_list_by_master_adminid($adminid);

        $userid_list=[];
        foreach($list as $item) {
            $userid_list[]=$item["adminid"];
        }
        $adminid_list = join(",", $userid_list);
        \App\Helper\Utils::logger("XX111 adminid_list:$adminid_list");
        $this->set_in_value("adminid_list", $adminid_list);

        return $this->seller_student_list();
    }


    public function ass_master_seller_student_list() {
        if (!$this->check_in_has("origin_assistantid") ) {
            $this->set_in_value("origin_assistantid", -2); //是转介绍
        }
        $this->set_in_value("admin_revisiterid", -1 );
        $this->set_in_value("cur_page",10002);
        return $this->seller_student_list();
    }

    public  function get_hold_list() {
        $hold_flag=$this->get_in_int_val("hold_flag",0, E\Eboolean::class );
        $phone_name = trim($this->get_in_str_val("phone_name"));
        $nick  = "";
        $phone = "";
        if($phone_name){
            if (!($phone_name>0)) {
                $nick=$phone_name;
            }else{
                $phone=$phone_name;
            }
        }
        $page_num              = $this->get_in_page_num();
        $seller_student_status = $this->get_in_int_val('seller_student_status', -1, E\Eseller_student_status::class);
        $subject               = $this->get_in_subject(-1);
        $grade = $this->get_in_grade();
        $page_count        = $this->get_in_page_count();


        $admin_revisiterid=$this->get_account_id();

        $ret_info=$this->t_seller_student_new->get_hold_list($page_num,$page_count,$admin_revisiterid,$hold_flag, $subject, $grade, $seller_student_status,$nick,$phone );
        $start_index=\App\Helper\Utils::get_start_index_from_ret_info($ret_info);
        $now=time(NULL);

        foreach( $ret_info["list"] as $index=> &$item ) {
            $item["index"]=$start_index+$index;

            $set_not_hold_err_msg="";
            if ($item["lesson_start"] > $now ) {
                $set_not_hold_err_msg="有上课时间,却还没上课";
            }else if ( $item["admin_revisiterid"]== $item["cur_require_adminid"]  &&  $item["lesson_start"] && $item["lesson_count_left"] ==0 && $item["test_lesson_order_fail_flag"]==0 ){ //还没签单
                $set_not_hold_err_msg="还没签单,需要设置未签原因";
            }

            $item["set_not_hold_err_msg"]=$set_not_hold_err_msg;


            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"next_revisit_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start");
            E\Eseller_student_status::set_item_value_str($item);
            $item["hold_flag_str"] = \App\Helper\Common::get_boolean_color_str($item["hold_flag"] );
            E\Ebook_grade::set_item_value_str($item,"grade");
            E\Esubject::set_item_value_str($item);
            E\Epad_type::set_item_value_str($item,"has_pad");
        }
        $seller_level=$this->t_manager_info->get_seller_level($admin_revisiterid);
        $hold_config=\App\Helper\Config::get_seller_hold_user_count();
        //dd($ret_info);
        return $this->pageView(__METHOD__,$ret_info,[
            "hold_define_count"  =>  $hold_config[$seller_level],
            "hold_cur_count"  => $this->t_seller_student_new->get_hold_count($admin_revisiterid) ,
        ]);
    }
    public function get_free_seller_list_data() {
        list($start_time,$end_time)= $this->get_in_date_range(-80,0 );
        $page_num   = $this->get_in_page_num();
        $phone_name = trim($this->get_in_str_val("phone_name"));
        $nick  = "";
        $phone = "";
        if($phone_name){
            if (!($phone_name>0)) {
                $nick=$phone_name;
            }else{
                $phone=$phone_name;
            }
        }


        $grade=$this->get_in_grade(-1);
        $has_pad=$this->get_in_has_pad(-1);
        $subject=$this->get_in_subject(-1);
        $origin=trim($this->get_in_str_val("origin",""));
        $this->t_seller_student_new->switch_tongji_database();
        $ret_info= $this->t_seller_student_new->get_free_seller_list($page_num,  $start_time, $end_time , $this->get_account_id(), $grade, $has_pad, $subject,$origin,$nick,$phone);
        foreach ($ret_info["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item, "add_time");
            E\Epad_type::set_item_value_str($item, "has_pad");
            E\Esubject::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);
            \App\Helper\Utils::hide_item_phone($item);
        }

        return $ret_info;
    }

    public function get_free_seller_list () {
        $ret_info=$this->get_free_seller_list_data();
        return $this->pageView(__METHOD__, $ret_info);
    }

    public function wiki()  {
        $accountid=$this->get_account_id();
        $key="wiki_".md5("asdfa-$accountid-".rand() );
        \App\Helper\Common::redis_set($key, $this->get_account() );
        $this->get_in_str_val("wiki_key",$key);
        return $this->pageView(__METHOD__, null);
    }
    public function seller_student_ws() {
        return $this->pageView(__METHOD__, null);
    }


    public function test_lesson_order_fail_list() {
        $cur_require_adminid=$this->get_in_int_val("cur_require_adminid",-1);
        $this->get_in_int_val("hide_cur_require_adminid",0);

        list($start_time, $end_time)=$this->get_in_date_range(-30,0);

        $page_num          = $this->get_in_page_num();
        $origin_userid_flag = $this->get_in_enum_val(E\Eboolean::class , -1,"origin_userid_flag" );
        $order_flag = $this->get_in_enum_val(E\Eboolean::class , -1 ,"order_flag");
        $test_lesson_fail_flag = $this->get_in_enum_val(E\Etest_lesson_order_fail_flag::class , -1 );
        $userid=$this->get_in_userid(-1 );

        $ret_info=$this->t_test_lesson_subject_require->get_order_fail_list($page_num,$start_time, $end_time, $cur_require_adminid,$origin_userid_flag,$order_flag,$test_lesson_fail_flag,$userid);
        foreach ($ret_info["list"] as &$item ) {
            $this->cache_set_item_student_nick($item);
            $this->cache_set_item_teacher_nick($item);
            $this->cache_set_item_account_nick ($item,"cur_require_adminid",
                                                "cur_require_admin_nick");
            E\Etest_lesson_fail_flag::set_item_value_str($item);
            E\Etest_lesson_order_fail_flag::set_item_value_str($item);
            E\Econtract_status::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start","","Y-m-d H:i");
            \App\Helper\Utils::unixtime2date_for_item($item,"test_lesson_order_fail_set_time");

        }

        return $this->pageView(__METHOD__,$ret_info);
    }

    public function test_lesson_order_fail_list_ass() {
        return $this->test_lesson_order_fail_list_seller();
    }

    public function test_lesson_order_fail_list_seller() {
        $this->set_in_value("cur_require_adminid", $this->get_account_id());
        $this->set_in_value("hide_cur_require_adminid",1);
        return $this->test_lesson_order_fail_list();
    }

    public function erweima(){
        $token = \LaneWeChat\Core\AccessToken::getAccessToken();
        $phone=$this->get_in_phone();
        // dd($token);

        $ch = curl_init();
        $post_data_array = array(
            "expire_seconds" =>604800,
            "action_name"    =>"QR_LIMIT_STR_SCENE",
            "action_info"    => array(
                "scene"      => array(
                    "scene_str" => $phone
                )
            )
        );

        $post_data_json = json_encode($post_data_array);

        $url_post = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$token;
        $timeout = 5;
        curl_setopt ($ch, CURLOPT_URL,$url_post);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_json);

        // curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        // echo $file_contents;
        \App\Helper\Utils::logger("file_contents:$file_contents");

        $file_contents_array = json_decode($file_contents,true);
        $ticket_post = @$file_contents_array["ticket"];

        return $this->curl_get($ticket_post);
    }


    public function curl_get ($ticket_post) {
        $url_get = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($ticket_post);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_get);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
         curl_close($ch);
        //打印获得的数据
       return $output;

    }

    public function tel_student_list(){

        $this->switch_tongji_database();
        $self_groupid          = $this->get_in_int_val("self_groupid",-1);
        $userid                = $this->get_in_userid(-1);
        $page_num              = $this->get_in_page_num();
        $global_tq_called_flag = $this->get_in_el_tq_called_flag("-1", "global_tq_called_flag");
        $grade    = $this->get_in_el_grade();
        $subject = $this->get_in_el_subject();
        list($start_time,$end_time) = $this->get_in_date_range(-60,1);

        /*
        $max_end_time= strtotime(date( "Y-m-d" ))  -1*86400;
        if ($end_time > $max_end_time) {
            $end_time= $max_end_time;
        }
        */

        $seller_student_status      = $this->get_in_int_val('seller_student_status', -1, E\Eseller_student_status::class);

        $ret_info = $this->t_seller_student_new->get_tmk_list( $start_time, $end_time, $seller_student_status, $page_num,$global_tq_called_flag , $grade,$subject);

        // dd($ret_info);

        $real_page_num = $ret_info["page_info"]["page_num"]-1;
        foreach( $ret_info["list"] as $index=> &$item ) {
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"sub_assign_time_2");
            \App\Helper\Utils::unixtime2date_for_item($item,"admin_assign_time");
            $item["index"]    =  $index +1;
            E\Eseller_student_status::set_item_value_str($item);
            E\Eseller_student_sub_status::set_item_value_str($item);
            E\Ebook_grade::set_item_value_str($item,"grade");
            E\Esubject::set_item_value_str($item);
            E\Epad_type::set_item_value_str($item,"has_pad");
            E\Etq_called_flag::set_item_value_str($item,"global_tq_called_flag");
            E\Eorigin_level::set_item_value_str($item);
            $this->cache_set_item_account_nick($item,"sub_assign_adminid_2","sub_assign_admin_2_nick");
            $this->cache_set_item_account_nick($item,"admin_revisiterid","admin_revisiter_nick");
            $this->cache_set_item_account_nick($item,"origin_assistantid","origin_assistant_nick");
            $this->cache_set_item_account_nick($item,"tmk_adminid","tmk_admin_nick");
        }

        // 未分配信息
        if ($self_groupid >0 ) { //主管
            $unallot_info=$this->t_test_lesson_subject->get_unallot_info_sub_assign_adminid_2($sub_assign_adminid_2);
        }else{
            $unallot_info=$this->t_test_lesson_subject->get_unallot_info( );
        }

        return $this->pageView(__METHOD__,$ret_info,[
           "unallot_info" => $unallot_info
        ]);
    }
    public function deal_new_user_tmk( ) {
        return $this->deal_new_user();
    }

    public function deal_new_user( ) {
        $adminid = $this->get_account_id();

        //申明 js 变量
        $this->set_filed_for_js("phone", "","string");
        $this->set_filed_for_js("open_flag",0);

        $this->set_filed_for_js("userid",0 );
        $this->set_filed_for_js("test_lesson_subject_id", 0);
        $this->set_filed_for_js("account_seller_level", 0 );

        $count_info=$this->t_seller_new_count->get_now_count_info($adminid);
        $count_info["left_count"] = $count_info["count"]-  $count_info["get_count"];

        $key="DEAL_NEW_USER_$adminid";
        $userid=\App\Helper\Common::redis_get($key)*1;

        if ($userid==0) {
            return $this->pageView(
                __METHOD__ , null,
                ["user_info"=>null, "count_info"=>$count_info ]
            );
        }

        $now=time(NULL);

        $cur_hm=date("H",$now)*60+date("i",$now);
        $cur_week=date("w",$now);
        if (in_array( $cur_week*1,[6,0] ) ) {
            $limit_arr=array( [0,11*60] );
        }else{
            $limit_arr=array( [0, 10*60 ], [14*60, 15*60] );
        }
        $seller_level=$this->t_manager_info->get_seller_level($this->get_account_id() );
        $success_flag=true;
        $time_str_list=[];

        foreach( $limit_arr  as $limit_item) {
            $limit_start=$limit_item[0];
            $limit_end=$limit_item[1];

            if ($cur_hm >= $limit_start && $cur_hm < $limit_end  ) { //间隔60分钟
                $success_flag=false;
            }
            $time_str_list[]=sprintf("%02d:%02d~%02d:%02d",
                                     $limit_start/60, $limit_start%60,
                                     $limit_end/60, $limit_end%60);
        }


        $errors=[];
        if(  (
            true ||
             !$this->check_power( E\Epower::V_TEST)
        )  ){
            if ( !$success_flag) {
                $errors=[
                    "抢新学生,当前关闭!!",
                    "关闭时间: ".  join(",",$time_str_list) ,
                    "当前时间:". date("H:i:s"),
                ];

                return $this->pageView(
                    __METHOD__ , null,
                    ["user_info"=>null , "count_info"=>$count_info, "errors" => $errors ]
                );

            }else{
                $errors=[];
            }
        }else{
            $success_flag=true;
        }

        $this->set_filed_for_js("open_flag",$success_flag?1:0);
        //list($start_time,$end_time)= $this->get_in_date_range(-7,0 );


        $this->set_filed_for_js("userid", $userid);
        $test_lesson_subject_id = $this->t_test_lesson_subject->get_test_lesson_subject_id($userid);

        $this->set_filed_for_js("test_lesson_subject_id", $test_lesson_subject_id);
        $this->set_filed_for_js("account_seller_level", session("seller_level" ) );

        $ret_info=$this->t_seller_student_new->get_seller_list( 1, -1, "", $userid );
        $user_info= @$ret_info["list"][0];
        if (!$user_info) {

            return $this->pageView(
                __METHOD__ , null,
                ["user_info"=>null , "count_info"=>$count_info]
            );


        }

        $this->set_filed_for_js("phone", $user_info["phone"]);

        $competition_call_adminid=$user_info["competition_call_adminid"];
        $competition_call_time=$user_info["competition_call_time"];
        $now = time(NULL);
        //超时
        if ($competition_call_adminid==$adminid && $now > $competition_call_time +3600   ){

            return $this->pageView(
                __METHOD__ , null,
                ["user_info"=>null , "count_info"=>$count_info]
            );


        }

        E\Etq_called_flag::set_item_value_str($user_info);
        E\Egrade::set_item_value_str($user_info);
        E\Esubject::set_item_value_str($user_info);
        E\Epad_type::set_item_value_str($user_info,"has_pad");
        \App\Helper\Utils::unixtime2date_for_item($user_info,"add_time");
        $this->cache_set_item_account_nick($user_info, "admin_revisiterid", "admin_revisiter_nick" );

        return $this->pageView(
            __METHOD__ , null,
            ["user_info"=>$user_info , "count_info"=>$count_info]
        );

    }

    public function refresh_call_end(){
        $lessonid = $this->get_in_int_val('lessonid');
        $ret = $this->t_lesson_info_b2->get_test_lesson_list($start_time = -1,$end_time = -1,-1,$lessonid);
        return $ret;
    }

}
