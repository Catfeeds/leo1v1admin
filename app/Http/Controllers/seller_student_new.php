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
        // $adminid=$this->get_account_id();
        // $self_groupid=$this->t_admin_group_name->get_groupid_by_master_adminid($adminid);
        // if(!$self_groupid ) {
        //     return $this->error_view(["你不是销售主管"]);
        // }

        // $this->set_in_value("self_groupid", $self_groupid );
        // $this->set_in_value("sub_assign_adminid_2",$adminid );
        // if (!$this->check_in_has( "admin_revisiterid")) {
        //     $this->set_in_value("admin_revisiterid", 0);
        // }

        $button_show_flag = 1;
        $adminid=$this->get_account_id();
        $majordomo_groupid=$this->t_admin_majordomo_group_name->is_master($adminid);
        $admin_main_groupid=$this->t_admin_main_group_name->is_master($adminid);
        $self_groupid=$this->t_admin_group_name->is_master($adminid);
        if($majordomo_groupid>0){//总监
            $button_show_flag = 0;
        }elseif($admin_main_groupid>0){//经理
            $button_show_flag = 0;
        }elseif($self_groupid>0){//组长
            $button_show_flag = 0;
        }else{
            return $this->error_view(["你不是销售主管"]);
        }

        $this->set_in_value("majordomo_groupid",$majordomo_groupid);
        $this->set_in_value("admin_main_groupid",$admin_main_groupid);
        $this->set_in_value("self_groupid", $self_groupid );
        $this->set_in_value("button_show_flag",$button_show_flag);
        // $this->set_in_value("sub_assign_adminid_2",$adminid );
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

    //转介绍待分配例子--总监
    public function assign_member_list_master ( ) {
        $adminid=$this->get_account_id();

        $main_master_flag = $this->t_admin_majordomo_group_name->check_is_master(2,$adminid);
        if($adminid==349){
            $main_master_flag=1;
        }
        if ($main_master_flag !=1) {
            return $this->error_view(["你不是销售总监"]);
        }

        $this->set_in_value("main_master_flag", $main_master_flag);
        // $this->set_in_value("admin_revisiterid", 0);
        // $this->set_in_value("sub_assign_adminid_2", 0);

        return $this->assign_sub_adminid_list();
    }

    //分配例子
    public function assign_sub_adminid_list(){
        $majordomo_groupid = $this->get_in_int_val("majordomo_groupid",-1);
        $admin_main_groupid = $this->get_in_int_val("admin_main_groupid",-1);
        $self_groupid = $this->get_in_int_val("self_groupid",-1);
        $button_show_flag = $this->get_in_int_val('button_show_flag',1);
        $seller_student_assign_type= $this->get_in_el_seller_student_assign_type();

        list($start_time,$end_time,$opt_date_str)= $this->get_in_date_range(
            -30*6, 1, 0, [
            0 => array( "add_time", "资源进来时间"),
            4 => array("sub_assign_time_2","分配给主管时间"),
            5 => array("admin_assign_time","分配给组员时间"),
            6 => array("tmk_assign_time","微信分配时间"),
            ], 0,0, true
        );
        list( $order_in_db_flag,$order_by_str,$order_field_name,$order_type)=$this->get_in_order_by_str( );

        $userid            = $this->get_in_userid(-1);
        $origin            = trim($this->get_in_str_val('origin', ''));
        $origin_ex         = $this->get_in_str_val('origin_ex', "");
        $grade             = $this->get_in_el_grade();
        $subject           = $this->get_in_subject(-1);
        $phone_location    = trim($this->get_in_str_val('phone_location', ''));
        $admin_revisiterid = $this->get_in_int_val('admin_revisiterid', -1);
        $tq_called_flag    = $this->get_in_int_val("tq_called_flag", -1,E\Etq_called_flag::class);
        $global_tq_called_flag = $this->get_in_int_val("global_tq_called_flag",-1,E\Etq_called_flag::class);
        $seller_student_status = $this->get_in_el_seller_student_status();

        $page_num                  = $this->get_in_page_num();
        $page_count                = $this->get_in_page_count();
        $has_pad                   = $this->get_in_int_val("has_pad", -1, E\Epad_type::class);
        $origin_assistantid        = $this->get_in_int_val("origin_assistantid",-1  );
        $tmk_adminid               = $this->get_in_int_val("tmk_adminid",-1, "");
        $account_role              = $this->get_in_enum_list(E\Eaccount_role::class, -1 );
        $origin_level              = $this-> get_in_el_origin_level("0,1,2,3,4");
        $seller_student_sub_status = $this->get_in_enum_val(E\Eseller_student_sub_status::class,-1);
        $tmk_student_status        = $this->get_in_int_val("tmk_student_status",-1,E\Etmk_student_status::class);
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
        $call_phone_count= $this->get_in_intval_range("call_phone_count");
        $call_count= $this->get_in_intval_range("call_count");
        $suc_test_count= $this->get_in_intval_range("suc_test_count");
        $main_master_flag= $this->get_in_int_val("main_master_flag", 0);
        $self_adminid = $this->get_account_id();
        $origin_count = $this->get_in_intval_range("origin_count");

        if($self_adminid==349){
            $self_adminid=-1;
        }
        $this->switch_tongji_database();

        //总监查看所有转介绍
        if($main_master_flag==1){
            $majordomo_groupid = $this->t_admin_majordomo_group_name->get_master_adminid_by_adminid($self_adminid);
            $button_show_flag = 0;
            $sub_assign_adminid_2      = $this->get_in_int_val("sub_assign_adminid_2", -1); 
        }else{
            $sub_assign_adminid_2      = $this->get_in_int_val("sub_assign_adminid_2", 0); 
        }

        //主管查看下级例子
        $admin_revisiterid_list = [];
        if($button_show_flag == 0){
            $son_adminid = [];
            $son_adminid_arr = [];
            if($majordomo_groupid>0){//总监
                $son_adminid = $this->t_admin_main_group_name->get_son_adminid_by_up_groupid($majordomo_groupid);
            }elseif($admin_main_groupid>0){//经理
                $son_adminid = $this->t_admin_group_name->get_son_adminid_by_up_groupid($admin_main_groupid);
            }elseif($self_groupid>0){//组长
                $son_adminid = $this->t_admin_group_user->get_son_adminid_by_up_groupid($self_groupid);
            }
            foreach($son_adminid as $item){
                if($item['adminid']>0){
                    $son_adminid_arr[] = $item['adminid'];
                }
            }
            array_unshift($son_adminid_arr,$this->get_account_id());
            $admin_revisiterid_list = array_unique($son_adminid_arr);
        }
        $nick = '王彦奇';
        $ret_info = $this->t_seller_student_new->get_assign_list(
            $page_num,$page_count,$userid,$admin_revisiterid,$seller_student_status,
            $origin,$opt_date_str,$start_time,$end_time,$grade,
            $subject,$phone_location,$origin_ex,$has_pad,$sub_assign_adminid_2,
            $seller_resource_type,$origin_assistantid,$tq_called_flag,$global_tq_called_flag,$tmk_adminid,
            $tmk_student_status,$origin_level,$seller_student_sub_status, $order_by_str,$publish_flag,
            $admin_del_flag ,$account_role , $sys_invaild_flag ,$seller_level, $wx_invaild_flag,$do_filter,
            $first_seller_adminid ,$suc_test_count,$call_phone_count,$call_count,
            $main_master_flag,$self_adminid, $origin_count,$admin_revisiterid_list,
            $seller_student_assign_type
        );

        $start_index=\App\Helper\Utils::get_start_index_from_ret_info($ret_info);
        foreach( $ret_info["list"] as $index=> &$item ) {
            $lass_call_time_space = $item['last_revisit_time']?(time()-$item['last_revisit_time']):(time()-$item['add_time']);
            $item['last_call_time_space'] = (int)($lass_call_time_space/86400);

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
            E\Eseller_student_assign_type::set_item_value_str($item);
            E\Epad_type::set_item_value_str($item,"has_pad");
            E\Etq_called_flag::set_item_value_str($item,"global_tq_called_flag");
            E\Eorigin_level::set_item_value_str($item);
            $this->cache_set_item_account_nick($item,"sub_assign_adminid_2","sub_assign_admin_2_nick");
            $this->cache_set_item_account_nick($item,"admin_revisiterid","admin_revisiter_nick");
            $this->cache_set_item_account_nick($item,"origin_assistantid","origin_assistant_nick");
            $this->cache_set_item_account_nick($item,"tmk_adminid","tmk_admin_nick");
            $this->cache_set_item_account_nick($item,"competition_call_adminid","competition_call_admin_nick");
            $this->cache_set_item_account_nick($item,"require_adminid","require_admin_nick");

            $this->cache_set_item_account_nick_time ($item, "first_tmk_valid_desc",
                                                     "first_tmk_set_valid_admind",
                                                     "first_tmk_set_valid_time" );


            $this->cache_set_item_account_nick_time ($item, "first_tmk_set_cc_desc",
                                                     "tmk_set_seller_adminid",
                                                     "first_tmk_set_seller_time" );

            $this->cache_set_item_account_nick_time ($item, "first_set_master_desc",
                                                     "first_admin_master_adminid",
                                                     "first_admin_master_time" );

            $this->cache_set_item_account_nick_time ($item, "first_set_cc_desc",
                                                     "first_admin_revisiterid",
                                                     "first_admin_revisiterid_time" );


            E\Eseller_student_status::set_item_value_str($item,"first_seller_status");
            \App\Helper\Utils::hide_item_phone($item);
        }

        // 未分配信息
        if($self_groupid >0) { //主管
            $unallot_info=$this->t_test_lesson_subject->get_unallot_info_sub_assign_adminid_2($sub_assign_adminid_2);
        }else{
            $unallot_info=$this->t_test_lesson_subject->get_unallot_info( );
        }
        $this->set_filed_for_js('button_show_flag',$button_show_flag);
        //测试模拟系统分配系统释放
        if(\App\Helper\Utils::check_env_is_test() || \App\Helper\Utils::check_env_is_local())
            $env_is_test = 1;
        else
            $env_is_test = 0;
        return $this->pageView(__METHOD__,$ret_info,[
            "unallot_info" => $unallot_info,
            "show_list_flag" => $show_list_flag,
            "button_show_flag" => $button_show_flag,
            'account' => $this->get_account(),
            'env_is_test' => $env_is_test
        ]);
    }

    //TMK例子库
    public function tmk_seller_student_new(){
        $this->switch_tongji_database();
        $self_groupid          = $this->get_in_int_val("self_groupid",-1);
        $userid                = $this->get_in_userid(-1);
        $page_num              = $this->get_in_page_num();
        $global_tq_called_flag = $this->get_in_el_tq_called_flag("-1", "global_tq_called_flag");
        $grade    = $this->get_in_el_grade();
        $subject = $this->get_in_el_subject();
        list($start_time,$end_time) = $this->get_in_date_range(-7,1);

        $seller_student_status      = $this->get_in_int_val('seller_student_status', -1, E\Eseller_student_status::class);

        $ret_info = $this->t_seller_student_new->get_tmk_list( $start_time, $end_time, $seller_student_status, $page_num,$global_tq_called_flag , $grade,$subject);

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
            \App\Helper\Utils::hide_item_phone($item);
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
        $is_test_no_return = $this->get_in_int_val('is_test_no_return');
        \App\Helper\Utils::logger("is_test_no_return:$is_test_no_return");
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
        $page_count            = $this->get_in_int_val("page_count",10);
        if ($opt_date_str=="admin_assign_time" && $start_time== strtotime(date("Y-m-d")) ) {
            //新例子页面不要分页
            $page_count=10000;
        }

        $adminid_list          = $this->get_in_str_val("adminid_list");
        $origin_assistant_role = $this->get_in_int_val("origin_assistant_role",-1,E\Eaccount_role::class  );
        $origin                = trim($this->get_in_str_val('origin', ''));
        $page_num              = $this->get_in_page_num();
        $userid                = $this->get_in_userid(-1);
        $seller_student_status = $this->get_in_el_seller_student_status();
        $seller_groupid_ex     = $this->get_in_str_val('seller_groupid_ex', "");
        $seller_groupid_ex_new     = $this->get_in_str_val('seller_groupid_ex_new', "");
        $require_adminid_list  = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);

        $phone_location        = trim($this->get_in_str_val('phone_location', ''));
        $require_admin_type    = $this->get_in_int_val("require_admin_type",-1);
        $subject               = $this->get_in_subject(-1);
        $has_pad               = $this->get_in_int_val("has_pad", -1, E\Epad_type::class);

        $seller_level   = $this->get_in_el_seller_level();
        $tq_called_flag              = $this->get_in_int_val("tq_called_flag", -1,E\Etq_called_flag::class);
        $global_tq_called_flag = $this->get_in_int_val("global_tq_called_flag", -1,E\Etq_called_flag::class);
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
        $current_require_id_flag= $this->get_in_e_boolean(-1,"current_require_id_flag");

        $favorite_flag = $this->get_in_str_val("favorite_flag",-1);
        $favorite_flag = $favorite_flag!=-1?$this->get_account_id():0;

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

        // $ftf = json_encode($require_adminid_list);
        // \App\Helper\Utils::logger("XX111 adminid_list:$ftf");
        //查看下级
        $require_adminid_list_new = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex_new);
        $show_son_flag = false;
        if(count($require_adminid_list_new)>0){//查看下级人员的
            $adminid = $this->get_account_id();
            $son_adminid = $this->t_admin_main_group_name->get_son_adminid($adminid);
            $son_adminid_arr = [];
            foreach($son_adminid as $item){
                $son_adminid_arr[] = $item['adminid'];
            }
            array_unshift($son_adminid_arr,$adminid);
            $require_adminid_arr = array_unique($son_adminid_arr);
            $group_type = count($require_adminid_arr)>1?1:0;
            $intersect = array_intersect($require_adminid_list_new,$require_adminid_arr);
            if(count($intersect)>0){
                $show_son_flag = true;
                $require_adminid_list_new = $intersect;
            }
        }
        //判断用户是否要获取试听未回访用户
        $phone_list = [];
        if($is_test_no_return == 1){
            $phone_str = $this->t_cc_no_return_call->field_get_value($admin_revisiterid, 'no_call_str');
            $phone_list = explode(',', $phone_str);
        }

        $ret_info = $this->t_seller_student_new->get_seller_list(
            $page_num, $admin_revisiterid,  $status_list_str, $userid, $seller_student_status ,
            $origin, $opt_date_str, $start_time, $end_time, $grade, $subject,
            $phone_location,   $has_pad, $seller_resource_type,$origin_assistantid  ,
            $tq_called_flag , $phone, $nick ,$origin_assistant_role ,$success_flag,
            $seller_require_change_flag,$adminid_list, $group_seller_student_status ,$tmk_student_status,$require_adminid_list,
            $page_count,$require_admin_type ,$origin_userid,$end_class_flag ,$seller_level ,
            $current_require_id_flag,$favorite_flag ,$global_tq_called_flag,$show_son_flag,$require_adminid_list_new,$phone_list) ;
        $now=time(null);
        $notify_lesson_check_end_time=strtotime(date("Y-m-d", $now+86400*2));
        $next_day=$notify_lesson_check_end_time-86400;
        $notify_lesson_check_start_time=$now - 3600;

        foreach ($ret_info["list"] as &$item) {
            \App\Helper\Utils::hide_item_phone($item);
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
            $this->cache_set_item_student_nick($item);
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

            if($item['test_lesson_order_fail_flag'] == null){
                $item['test_lesson_order_fail_flag'] = 0;
            }
            if(in_array($item['test_lesson_order_fail_flag'],[1001,1002,1003,1004])){//自身原因
                $item['test_lesson_order_fail_flag_one'] = 10;
            }elseif(in_array($item['test_lesson_order_fail_flag'],[1101,1102,1103])){//价格原因
                $item['test_lesson_order_fail_flag_one'] = 11;
            }elseif(in_array($item['test_lesson_order_fail_flag'],[1201,1202])){//品牌信任度
                $item['test_lesson_order_fail_flag_one'] = 12;
            }elseif(in_array($item['test_lesson_order_fail_flag'],[1301,1302,1303,1304,1305,1306,1307,1308,1309,1310,1311,1312,1313,])){//教学能力
                $item['test_lesson_order_fail_flag_one'] = 13;
            }elseif(in_array($item['test_lesson_order_fail_flag'],[1401,1402,1403])){//教学态度
                $item['test_lesson_order_fail_flag_one'] = 14;
            }elseif(in_array($item['test_lesson_order_fail_flag'],[1501,1502,1503,1504])){//产品问题
                $item['test_lesson_order_fail_flag_one'] = 15;
            }elseif(in_array($item['test_lesson_order_fail_flag'],[1601,1602,1603,1604])){//时间问题
                $item['test_lesson_order_fail_flag_one'] = 16;
            }elseif(in_array($item['test_lesson_order_fail_flag'],[1701])){//考虑中
                $item['test_lesson_order_fail_flag_one'] = 17;
            }else{//未设置
                $item['test_lesson_order_fail_flag_one'] = 0;
            }
        }
        $count_info =$this->t_seller_student_new->get_seller_count_list(
            $admin_revisiterid,  $status_list_str, $userid, $seller_student_status ,
            $origin, $opt_date_str, $start_time, $end_time, $grade, $subject,
            $phone_location,   $has_pad, $seller_resource_type,$origin_assistantid  ,
            $tq_called_flag , $phone, $nick ,$origin_assistant_role ,$success_flag,
            $seller_require_change_flag,$adminid_list,$tmk_student_status,$require_adminid_list,
            $require_admin_type ) ;

        $ret_info["count_info"] = $count_info;
        $ret_info["show_son_flag"] = $show_son_flag;
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
        $cur_page       = $this->get_in_int_val("cur_page");
        $page_hide_list = $this->get_page_hide_list($cur_page);
        $account        = $this->get_account();
        $account_role   = $this->get_account_role();

        $ret_info = $this->seller_student_list_data();
        unset($ret_info["count_info"]);

        $adminid = $this->get_account_id();
        $start_time = strtotime(date("Y-m-01",strtotime(date("Y-m-01",time()))-86400));
        $self_top_info =$this->t_tongji_seller_top_info->get_admin_top_list( $adminid,  $start_time );
        $ranking = @$self_top_info[6]["top_index"];

        //销售主管以上列表
        $seller_master_list = $this->t_admin_group_name->get_all_master_adminid_list(2);
        $seller_master_list[] = "349";
        $seller_master_list[] = "448";
        $is_seller_master= 1;
        //添加测试标识[前端用]
        if(\App\Helper\Utils::check_env_is_test() || \App\Helper\Utils::check_env_is_local()){
            $this->set_filed_for_js("env_is_test",1);
            $env_is_test = 1;
        }else{
            $this->set_filed_for_js("env_is_test",0);
            $env_is_test = 0;
        }
        //获取用户身份[是否系统分配用户]
        $seller_student_assign_type = $this->t_manager_info->field_get_value($adminid, 'seller_student_assign_type');

        $this->set_filed_for_js("jack_flag",$adminid);
        $this->set_filed_for_js("account_role",$account_role);
        $this->set_filed_for_js("account",$account);
        $this->set_filed_for_js("admin_seller_level", session("seller_level" ) );
        return $this->pageView(__METHOD__,$ret_info,[
            "page_hide_list"   => $page_hide_list,
            "cur_page"         => $cur_page,
            "is_seller_master" => $is_seller_master,
            "account_role"     => $account_role,
            "account"          => $account,
            "show_son_flag"    => $ret_info['show_son_flag'],
            'seller_student_assign_type' => $seller_student_assign_type,
            'env_is_test' => $env_is_test
        ]);
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
                return $this->output_err("有新例子未拨打",["need_deal_cur_user_flag" =>true]);
            }
            if(!$this->t_seller_student_new->check_admin_add($adminid,$get_count,$max_day_count )){
                return $this->output_err("目前你持有的例子数[$get_count]>=最高上限[$max_day_count]");
            }
            if(!$this->t_seller_new_count->get_free_new_count_id($adminid,"获取新例子")){
                return $this->output_err("今天的配额,已经用完了");
            }
            //试听成功未回访
            $this->refresh_test_call_end();
            $lesson_call_end = $this->t_lesson_info_b2->get_call_end_time_by_adminid($adminid);
            $userid_new = $lesson_call_end['userid'];
            if($userid_new){
                return $this->output_err("有试听课成功未回访",["userid" =>$userid_new,'adminid'=>$adminid]);
            }

            $row_data= $this->t_seller_student_new->field_get_list($userid,"competition_call_time, competition_call_adminid, admin_revisiterid,phone");
            $competition_call_time = $row_data["competition_call_time"];
            $competition_call_adminid = $row_data["competition_call_adminid"];
            $admin_revisiterid = $row_data["admin_revisiterid"];
            if($admin_revisiterid !=  0  && $admin_revisiterid != $adminid) {
                return $this->output_err("已经被人抢了1");
            }
            if($competition_call_adminid != $adminid &&  $now- $competition_call_time  < 3600  ) { //
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

            //抢新log
            $ret_log = $this->t_seller_get_new_log->get_row_by_adminid_userid($adminid,$userid);
            if(!$ret_log){
                $this->t_seller_get_new_log->row_insert([
                    'adminid'=>$adminid,
                    'userid'=>$userid,
                    'create_time'=>time(),
                ]);
            }
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

    public function test_lesson_fail_list() {
        $ret_info = $this->test_lesson_fail_list_data();
        return $this->pageView(__METHOD__, $ret_info);
    }

    public function test_lesson_fail_list_data() {
        list($start_time,$end_time)= $this->get_in_date_range(-80,0 );
        $page_num   = $this->get_in_page_num();
        $phone_name = trim($this->get_in_str_val("phone_name"));
        $user_info  = trim($this->get_in_str_val('user_info',''));
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
        $ret_info= $this->t_seller_student_new->get_free_seller_fail_list($page_num,  $start_time, $end_time , $this->get_account_id(), $grade, $has_pad, $subject,$origin,$nick,$phone,$user_info);
        foreach ($ret_info["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item, "add_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "free_time");
            E\Epad_type::set_item_value_str($item, "has_pad");
            E\Esubject::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);
            \App\Helper\Utils::hide_item_phone($item);
            $item["cc_nick"]= $this->cache_get_account_nick($item["free_adminid"]);
        }

        return $ret_info;
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
        // list($start_time,$end_time)= $this->get_in_date_range(-80,0 );
        list($start_time,$end_time,$opt_date_str)= $this->get_in_date_range(
            0,0,0,[
                0 => array("n.seller_add_time","例子翻新时间"),
                1 => array("n.free_time","回流公海时间"),
                2 => array("n.add_time","资源进来时间"),
                3 => array("l.lesson_start","试听成功时间"),
                4 => array("n.last_revisit_time","最后CC联系时间"),
            ], 1,0, true
        );
        if(($end_time - $start_time)>3600*24*3){
            $end_time = $start_time+3600*24*3;
        }
        // dd($start_time,$end_time);
        $page_num   = $this->get_in_page_num();
        $phone_name = trim($this->get_in_str_val("phone_name"));
        $phone_location = trim($this->get_in_str_val("phone_location"));
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
        // $test_lesson_count_flag=$this->get_in_int_val('test_lesson_count_flag',E\Etest_lesson_count_flag::V_1);
        $test_lesson_count_flag=$this->get_in_int_val('test_lesson_count_flag',-1);
        $test_lesson_fail_flag = $this->get_in_enum_val(E\Etest_lesson_order_fail_flag::class,-1);
        $origin=trim($this->get_in_str_val("origin",""));

        $return_publish_count = $this->get_in_int_val('return_publish_count',-1);
        $cc_called_count      = $this->get_in_int_val('cc_called_count',-1);
        $cc_no_called_count_new = $this->get_in_int_val('cc_no_called_count_new',-1);
        $call_admin_count     = $this->get_in_int_val('call_admin_count',-1);
        $this->t_seller_student_new->switch_tongji_database();
        // $ret_info= $this->t_seller_student_new->get_free_seller_list($page_num,  $start_time, $end_time , $this->get_account_id(), $grade, $has_pad, $subject,$origin,$nick,$phone);
        $ret_info = $this->t_seller_student_new->get_free_seller_list_new($page_num,  $start_time, $end_time,$opt_date_str , $this->get_account_id(), $grade, $has_pad, $subject,$origin,$nick,$phone,$test_lesson_count_flag,$test_lesson_fail_flag,$phone_location,$return_publish_count,$cc_called_count,$cc_no_called_count_new,$call_admin_count);
        foreach ($ret_info["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item, "add_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "free_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "last_revisit_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "lesson_start");
            E\Epad_type::set_item_value_str($item, "has_pad");
            E\Esubject::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);
            E\Etest_lesson_order_fail_flag::set_item_value_str($item);
            \App\Helper\Utils::hide_item_phone($item);
            $item["free_nick"]= $this->cache_get_account_nick( $item["free_adminid"]);
        }

        return $ret_info;
    }

    public function get_free_seller_list_data_new() {
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
        $ret_info= $this->t_seller_student_new->get_free_seller_list($page_num,$start_time,$end_time,$this->get_account_id(),$grade, $has_pad,$subject,$origin,$nick,$phone,$suc_test_flag=1);
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
        $log_type = E\Edate_id_log_type::V_SELLER_GET_HISTORY_COUNT;
        $adminid = $this->get_account_id();
        $start_time = strtotime(date("Y-m-d"));
        $end_time = time();
        $history_count = $this->t_id_opt_log->get_history_count($log_type,$adminid,$start_time,$end_time);
        $left_count = (30-$history_count)>0?30-$history_count:0;
        $acc= $this->get_account();
        return $this->pageView(__METHOD__, $ret_info,[
            'left_count'=>$left_count,
            "acc"  =>$acc
        ]);
    }

    public function get_free_seller_test_fail_list () {
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
        $origin_levle_arr = [];
        $ret_info=$this->t_test_lesson_subject_require->get_order_fail_list($page_num,$start_time, $end_time, $cur_require_adminid,$origin_userid_flag,$order_flag,$test_lesson_fail_flag,$userid);
        foreach ($ret_info["list"] as &$item ) {
            $origin_levle_arr[] = $item['origin_level'];
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
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_end","","H:i");
            \App\Helper\Utils::unixtime2date_for_item($item,"test_lesson_order_fail_set_time");
            if(in_array($item['test_lesson_order_fail_flag'],[1001,1002,1003,1004])){//自身原因
                $item['test_lesson_order_fail_flag_one'] = 10;
            }elseif(in_array($item['test_lesson_order_fail_flag'],[1101,1102,1103])){//价格原因
                $item['test_lesson_order_fail_flag_one'] = 11;
            }elseif(in_array($item['test_lesson_order_fail_flag'],[1201,1202])){//品牌信任度
                $item['test_lesson_order_fail_flag_one'] = 12;
            }elseif(in_array($item['test_lesson_order_fail_flag'],[1301,1302,1303,1304,1305,1306,1307,1308,1309,1310,1311,1312,1313,])){//教学能力
                $item['test_lesson_order_fail_flag_one'] = 13;
            }elseif(in_array($item['test_lesson_order_fail_flag'],[1401,1402,1403])){//教学态度
                $item['test_lesson_order_fail_flag_one'] = 14;
            }elseif(in_array($item['test_lesson_order_fail_flag'],[1501,1502,1503,1504])){//产品问题
                $item['test_lesson_order_fail_flag_one'] = 15;
            }elseif(in_array($item['test_lesson_order_fail_flag'],[1601,1602,1603,1604])){//时间问题
                $item['test_lesson_order_fail_flag_one'] = 16;
            }elseif(in_array($item['test_lesson_order_fail_flag'],[1701])){//考虑中
                $item['test_lesson_order_fail_flag_one'] = 17;
            }elseif(in_array($item['test_lesson_order_fail_flag'],[1801])){//已经签单
                $item['test_lesson_order_fail_flag_one'] = 18;
            }else{//未设置
                $item['test_lesson_order_fail_flag_one'] = 0;
            }
        }
        $origin_levle_arr = array_unique($origin_levle_arr);
        $origin_info = $this->t_origin_key->get_key1_list_by_origin_level_arr($origin_levle_arr);
        foreach($ret_info["list"] as &$item){
            foreach($origin_info as $info){
                if(($item['origin_level'] == $info['origin_level']) && $item['origin_level']){
                    $item['key1'] = $info['key1'];
                }
            }
            $item['key1'] = isset($item['key1'])?$item['key1']:'注册';
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function test_lesson_order_fail_list_new(){
        $userid = $this->get_in_int_val('userid',-1);
        $cur_require_adminid = $this->get_account_id();
        $account_role = $this->get_account_role();
        $ret_info = $this->t_test_lesson_subject_require->get_test_fail_row($cur_require_adminid,$userid);
        $ret = isset($ret_info['require_id'])?$ret_info['require_id']:0;
        if(in_array($cur_require_adminid,[68,1093,1122]) || $account_role != E\Eaccount_role::V_2){//测试
            $ret = 0;
        }
        return $ret;
    }

    public function test_lesson_order_fail_list_mul(){
        $admin_revisiterid=$this->get_account_id();
        $user_list = $this->t_seller_student_new->get_no_hold_list($admin_revisiterid);
        $userid = isset($user_list[0]['userid'])?$user_list[0]['userid']:-1;
        $cur_require_adminid = $this->get_account_id();
        $ret_info = $this->t_test_lesson_subject_require->get_test_fail_row_new_tow($cur_require_adminid,$userid);
        $ret['ret'] = isset($ret_info['require_id'])?$ret_info['require_id']:0;
        $ret['userid'] = $userid?$userid:0;
        return $ret;
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
        $grade                 = $this->get_in_el_grade();
        $subject               = $this->get_in_el_subject();
        list($start_time,$end_time) = $this->get_in_date_range(-7,1);

        $seller_student_status = $this->get_in_int_val('seller_student_status',-1,E\Eseller_student_status::class);

        $ret_info = $this->t_seller_student_new->get_tmk_list($start_time,$end_time,$seller_student_status,$page_num,$global_tq_called_flag,$grade,$subject);

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
            \App\Helper\Utils::hide_item_phone($item);
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
    public function deal_new_user_tmk(){
        return $this->deal_new_user();
    }

    public function deal_new_user(){
        $adminid = $this->get_account_id();

        if ($this->t_manager_info->get_seller_student_assign_type($adminid) ==  E\Eseller_student_assign_type::V_1  ) {
           return  $this->error_view([
                "你的例子分配规则,被设置为:系统分配,可以在 <所有用户> 中看到推送给你的例子",
                "抢单不可用",
            ]);
        }
        //拨通未满60s
        $last_get_time = $this->t_seller_get_new_log->get_last_get_time($adminid);
        if(time()-$last_get_time<660){
            $cmd= new \App\Console\Commands\sync_tianrun();
            $count=$cmd->load_data($last_get_time,time());
        }
        $count = $this->t_seller_get_new_log->get_cc_end_count($adminid,strtotime(date('Y-m-d',time())),time());
        if($count>=6 && ($this->t_manager_info->field_get_value($adminid, 'get_new_flag') == 0)){
            return  $this->error_view([
                "当日满6次通话未满60s主动挂断电话，禁止继续抢新"
            ]);
        }


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


        $now=time(NULL);
        $cur_hm=date("H",$now)*60+date("i",$now);
        $cur_week=date("w",$now);
        if (in_array( $cur_week*1,[6,0])) {//周六,周日00:00~11:00
            $limit_arr=array ([0,11*60] );
        }elseif(in_array( $cur_week*1,[1,3,4,5] )){//周一,周三,周四,周五 0:00-13:30
            $limit_arr=array( [0, 13*60+30]);
        }else{//周二 00:00~06:00
            $limit_arr=array( [0, 6*60]);
            //$limit_arr=array( [0, 10*60 ] );
        }
        if(date('Y-m-d',time()) == '2018-02-01'){
            $limit_arr=array( [0, 14*60]);
        }
        $seller_level=$this->t_manager_info->get_seller_level($this->get_account_id() );
        $this->set_filed_for_js("seller_level",$seller_level);
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
            false ||
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
                    ["user_info"=>null , "count_info"=>$count_info, "errors" => $errors,'count_new'=>$count,'left_count_new'=>6-$count]
                );

            }else{
                $errors=[];
            }
        }else{
            $success_flag=true;
        }

        $this->set_filed_for_js("open_flag",$success_flag?1:0);
        //list($start_time,$end_time)= $this->get_in_date_range(-7,0 );
        if ($userid==0) {
            return $this->pageView(
                __METHOD__ , null,
                ["user_info"=>null, "count_info"=>$count_info,'count_new'=>$count,'left_count_new'=>6-$count ]
            );
        }


        # 处理该学生的通话状态 [james]
        // $ccNoCalledNum = $this->t_seller_student_new->get_cc_no_called_count($userid);
        // $hasCalledNum = $this->t_tq_call_info->getAdminidCalledNum($adminid);
        // // $this->set_filed_for_js("hasCalledNum", $hasCalledNum);
        // $this->set_filed_for_js("hasCalledNum", 3);
        # 处理该学生的通话状态 [james-end]




        $this->set_filed_for_js("userid", $userid);
        $test_lesson_subject_id = $this->t_test_lesson_subject->get_test_lesson_subject_id($userid);

        $this->set_filed_for_js("test_lesson_subject_id", $test_lesson_subject_id);
        $this->set_filed_for_js("account_seller_level", session("seller_level" ) );
        $ret_info=$this->t_seller_student_new->get_seller_list( 1, -1, "", $userid );
        $user_info= @$ret_info["list"][0];
        if (!$user_info) {
            return $this->pageView(
                __METHOD__ , null,
                ["user_info"=>null , "count_info"=>$count_info,'count_new'=>$count,'left_count_new'=>6-$count]
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
                ["user_info"=>null , "count_info"=>$count_info,'count_new'=>$count,'left_count_new'=>6-$count]
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
            ["user_info"=>$user_info , "count_info"=>$count_info,'count_new'=>$count,'left_count_new'=>6-$count]
        );

    }

    public function refresh_test_call_end(){
        $adminid = $this->get_account_id();
        $lesson_call_end = $this->t_lesson_info_b2->get_call_end_time_by_adminid_new($adminid);
        if(count($lesson_call_end)>0){
            foreach($lesson_call_end as $item){
                $this->t_lesson_info_b2->get_test_lesson_list(0,0,-1,$item['lessonid']);
            }
        }
    }

    public function no_lesson_call_end_time_list(){
        $adminid = $this->get_in_int_val('adminid');
        $admin_nick = $this->cache_get_account_nick($adminid);
        $phone = $this->get_in_str_val('phone');
        $this->switch_tongji_database();
        $lesson_call_end = $this->t_lesson_info_b2->get_call_end_time_by_adminid_new($adminid);
        $tquin = $this->t_manager_info->get_tquin($adminid);
        $lesson_call_list = $this->t_tq_call_info->get_list_by_phone((int)$tquin,$phone);
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($lesson_call_end),['admin_nick'=>$admin_nick]);
    }

    public function refresh_call_end(){
        $lessonid = $this->get_in_int_val('lessonid');
        $ret = $this->t_lesson_info_b2->get_test_lesson_list(0,0,-1,$lessonid);
        $this->refresh_test_call_end();

        return $ret;
    }

    public function set_call_end_time(){
        $ret = 1;
        $adminid = $this->get_account_id();
        if(in_array($adminid,[898,831,778,843])){
            $lessonid = $this->get_in_int_val('lessonid');
            $ret = $this->t_test_lesson_subject_sub_list->field_update_list($lessonid, [
                "call_end_time"    => time(null),
            ]);
        }

        return $ret;
    }

    public function seller_get_test_lesson_list(){
        $adminid=$this->get_account_id();
        $list=$this->t_seller_student_new->get_test_lesson_list($adminid);
        foreach( $list as &$item ) {
            $this->cache_set_item_student_nick($item);
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start");
        }
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($list));

    }

    public function test_lesson_cancle_rate(){
        $adminid = $this->get_account_id();
        $userid = $this->get_in_int_val('userid');
        $time = strtotime(date('Y-m-d',time()).'00:00:00');
        $week = date('w',$time);
        if($week == 0){
            $week = 7;
        }elseif($week == 1){
            $week = 8;
        }
        $end_time = $time-3600*24*($week-2);
        $start_time = $end_time-3600*24*7;
        list($count,$count_del) = [0,0];
        $tongji_type=E\Etongji_type::V_SELLER_WEEK_FAIL_LESSON_PERCENT;
        $self_top_info =$this->t_tongji_seller_top_info->get_admin_week_fail_percent($adminid,$start_time,$tongji_type);
        if($self_top_info>25){//上周取消率>25%,查看当天是否有过排课申请
            $start_time = $time;
            $end_time = $time+3600*24;
            // $ret_info = $this->t_lesson_info_b2->get_seller_week_lesson_row($start_time,$end_time,$adminid);
            $require_id = $this->t_test_lesson_subject_require->get_test_lesson_require_row($start_time,$end_time,$adminid);
            $ret['ret'] = $require_id?1:2;
            $review_suc = $this->t_test_lesson_subject_require_review->get_row_by_adminid_userid($adminid,$userid);
            if($review_suc){
                $ret['ret'] = 2;
            }
            $ret['rate'] = $self_top_info;
        }else{//本周取消率
            $start_time = $time-3600*24*($week-2);
            $end_time = time();
            // $ret_info = $this->t_lesson_info_b2->get_seller_week_lesson_new($start_time,$end_time,$adminid);
            // foreach($ret_info as $item){
            //     if($item['lesson_del_flag']){
            //         $count_del++;
            //     }
            //     $count++;
            // }
            // $del_rate = ($count?($count_del/$count):0)*100;
            $ret_info = $this->t_test_lesson_subject_require->tongji_test_lesson_group_by_admin_revisiterid($start_time,$end_time,$grade_list=[-1] , $origin_ex="",$adminid);
            $test_count = isset($ret_info['list'][0]['test_lesson_count'])?$ret_info['list'][0]['test_lesson_count']:0;
            $fail_all_count = isset($ret_info['list'][0]['fail_all_count'])?$ret_info['list'][0]['fail_all_count']:0;
            $del_rate = $test_count>0?round($fail_all_count/$test_count,2)*100:0;
            if($del_rate>20){
                $ret['ret'] = 3;
            }else{
                $ret['ret'] = 4;
            }
            $ret['rate'] = $del_rate;
        }
        return $ret;
    }

    public function seller_student_new_favorite(){
        $userid = $this->get_in_int_val('userid');
    }

    public function get_this_new_user(){
        $phone = $this->get_in_int_val('phone');
        $userid=$this->t_phone_to_user->get_userid_by_phone($phone);
        $competition_call_adminid = $this->get_account_id();
        $ret = 0;
        $admin_revisiterid = $this->t_seller_student_new->get_row_by_admin_revisiterid($userid,$competition_call_adminid);
        if($admin_revisiterid){//认领过
            $ret = 3;
            return $ret;
        }
        $tquin = $this->t_manager_info->get_tquin($competition_call_adminid);
        $is_called_flag = $this->t_tq_call_info->get_call_info_row($tquin,$phone);
        if($is_called_flag == 0){//未拨通
            $ret = 2;
            return $ret;
        }
        //近1小时内有拨通过
        if($this->t_seller_new_count->check_and_add_new_count($competition_call_adminid ,"获取新例子",$userid)){
            $account = $this->t_manager_info->get_account( $competition_call_adminid );
            $this->t_seller_student_new->set_admin_info(0, [$userid], $competition_call_adminid,0);
            $ret_update = $this->t_book_revisit->add_book_revisit(
                $phone,
                "操作者:  抢单 [$account] ",
                "system"
            );
            $this->t_seller_student_new->field_update_list($userid,[
                'admin_revisiterid' => $competition_call_adminid,
                "admin_assign_time" => time(NULL),
                'hand_get_adminid'  => E\Ehand_get_adminid::V_2,
            ]);
            $ret = 1;
        }
        return $ret;
    }



    //助教主管新增例子
    public function get_new_student_ass_leader(){
        list($start_time,$end_time)= $this->get_in_date_range(0,0,0,[],3);
        $page_info= $this->get_in_page_info();
        $assistantid = $this->get_in_int_val("assistantid",-1);
        $ret_info = $this->t_seller_student_new->get_ass_leader_assign_stu_info($start_time,$end_time,$page_info,$assistantid);
        foreach($ret_info["list"] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item, "add_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item, "ass_assign_time","_str");
            $this->cache_set_item_account_nick($item,"admin_assignerid","admin_assignerid_nick");
            $this->cache_set_item_account_nick($item,"origin_assistantid","origin_assistant_nick");

        }

        return $this->pageView(__METHOD__,$ret_info);
    }

    /**
     *个人中心-分享知识库
     *
     */
    public function share_knowledge(){
        $arr['total_test_pic_info_num'] = $this->t_yxyx_test_pic_info->get_total();
        $arr['total_new_list_num']      = $this->t_yxyx_new_list->get_total();
        return $this->pageView(__METHOD__,null,["arr"=>$arr]);
    }

    public function get_stu_request_test_lesson_time_by_adminid(){
        $cur_day = $this->get_in_int_val('cur_day','');
        $adminid = $this->get_account_id();
        if($cur_day == '') {
            $cur_day = strtotime('today');
        } else {
            $cur_day = strtotime( date('Y-m-d', $cur_day) );
        }

        $end_time = $cur_day + 84600;
        $ret_info = $this->t_test_lesson_subject->get_stu_request_test_lesson_time_by_adminid($adminid, $cur_day, $end_time);
        $list = [];
        if(count($ret_info) > 0) {
            foreach($ret_info as $item){
                $time = date('H:i',$item['stu_request_test_lesson_time']);
                if(!in_array($time, $list)) {
                    $list[] = $time;
                }
            }
        }
        return $this->output_succ(['list' => []]);
    }

    //@desn:调用系统分配command
    public function system_assign(){
        $system_assign = new \App\Console\Commands\seller_student_system_assign();
        $system_assign->handle();
        return $this->output_succ();
    }
    //@desn:调用系统释放command
    public function system_free(){
        $system_free = new \App\Console\Commands\seller_student_system_free();
        $system_free->handle();
        return $this->output_succ();
    }
    public function call_back(){
        $lessonid = $this->get_in_int_val('lessonid');
        $this->t_test_lesson_subject_sub_list->field_update_list($lessonid, [
            'call_end_time' => time(NULL)
        ]);
        return $this->output_succ();
    }

}
