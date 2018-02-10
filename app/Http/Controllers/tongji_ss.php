<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;

class tongji_ss extends Controller
{
    use CacheNick;
    use TeaPower;
    var $switch_tongji_database_flag = true;

    public function valid_user_count()
    {
        list($start_time,$end_time)=$this->get_in_date_range( date("Y-m-d",time(NULL)-30*86500),date("Y-m-d",time(NULL)));
        $date_list = \App\Helper\Common::get_date_time_list($start_time, $end_time-1);
        $log_type  = E\Edate_id_log_type::V_VALID_USER_COUNT;
        $from_list = $this->t_id_opt_log->get_date_list($log_type,$start_time,$end_time);

        \App\Helper\Utils::date_list_set_value($date_list,$from_list,"opt_date","user_count","count");
        $from_list=$this->t_lesson_info->get_user_count_list($start_time,$end_time);
        \App\Helper\Utils::date_list_set_value($date_list,$from_list,"opt_date","lesson_user_count","count");
        usort($date_list,function($a,$b){
            return \App\Helper\Common::sort_value_desc_func($a["title"],$b["title"]);
        });
        //dd(2);
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($date_list));
    }

    public function user_count() {
        $this->check_and_switch_tongji_domain();
        $sum_field_list=[
            "add_time_count",
            "call_count",
            "call_old_count",
            "first_revisit_time_count",
            "after_24_first_revisit_time_count",
            "avg_first_time",
            "test_lesson_count",
            "test_lesson_count_succ",
            "seller_require_test_lesson_count",
            "test_lesson_count_succ_new",
            "seller_test_lesson_count",
            "seller_test_lesson_count_succ",
            "order_count",
            "order_count_new",
            "order_count_next",
            "test_lesson_count_fail_need_money",
            "test_lesson_count_fail_need_money_new",
            "seller_test_lesson_count_fail_need_money",
            "seller_test_lesson_count_fail_not_need_money",
            "test_lesson_count_change_time",
            "seller_test_lesson_count_fail_need_money_new",
            "seller_test_lesson_count_stu_tea_join_count",
        ];
        $order_field_arr = array_merge(["title"],$sum_field_list);

        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            = $this->get_in_order_by_str($order_field_arr,"title desc");
        $seller_groupid_ex = $this->get_in_str_val('seller_groupid_ex',"");
        $adminid_list  = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);
        $adminid_right = $this->get_seller_adminid_and_right();
        $this->get_in_int_val( "check_add_time_count",1);
        if(!empty($adminid_right)){
            $this->get_in_int_val( "check_call_old_count",0);
        }else{
            $this->get_in_int_val( "check_call_old_count",1);
        }
        $grade_list=$this->get_in_enum_list(E\Egrade::class);
        $stu_test_paper_flag=$this->get_in_boolean_val("stu_test_paper_flag",-1);

        $this->get_in_int_val( "check_first_revisit_time_count",1);
        $this->get_in_int_val( "check_test_lesson_count",1);
        $this->get_in_int_val( "check_order_count",1);
        $adminid_all = $this->get_seller_adminid_and_branch();

        $admin_revisiterid=$this->get_in_int_val("admin_revisiterid",-1);
        list($start_time,$end_time)=$this->get_in_date_range( date("Y-m-d",time(NULL)-14*86500),date("Y-m-d",time(NULL)));

        $date_list=\App\Helper\Common::get_date_time_list($start_time, $end_time-1);

        $list = $this->t_seller_student_new->get_tongji_add_time($start_time,$end_time,$adminid_list,$adminid_all,$grade_list );
        foreach ($list as $item) {
            $opt_date  = $item["opt_date"];
            $date_item = &$date_list[$opt_date];
            $date_item["add_time_count"]=$item["count"];
        }

        $list = $this->t_seller_student_new->get_tongji_first_revisit_time($start_time,$end_time,$adminid_list,$adminid_all,$grade_list);
        foreach ($list as $item) {
            $opt_date  = $item["opt_date"];
            $add_time  = $item['add_time'];
            $first_call_time  = $item['first_call_time'];
            $date_item = &$date_list[$opt_date];
            $date_item["first_revisit_time_count"]=$item["first_revisit_time_count"];
            $date_item["after_24_first_revisit_time_count"]=$item["after_24_first_revisit_time_count"];
            $date_item["avg_first_time"]=$item["avg_first_time"];
        }
        //seller_require_test_lesson_count
        $list = $this->t_test_lesson_subject_require->tongji_require_test_lesson($start_time,$end_time,$adminid_list,$adminid_all, $grade_list );
        foreach ($list as $item) {
            $opt_date  = $item["opt_date"];
            $date_item = &$date_list[$opt_date];
            $date_item["seller_require_test_lesson_count"] = $item["count"];
        }

        $list = $this->t_test_lesson_subject_require->tongji_test_lesson($start_time,$end_time,$adminid_list,$adminid_all,$grade_list, $stu_test_paper_flag);
        foreach ($list as $item) {
            $opt_date  = date("Y-m-d",$item["opt_time"]);
            $date_item = &$date_list[$opt_date];
            \App\Helper\Utils::array_item_add_value($date_item,"test_lesson_count",1);

            $success_flag          = $item["success_flag"];
            $test_lesson_fail_flag = $item["test_lesson_fail_flag"];
            $lesson_login_status = $item["lesson_login_status"];
            //$check_is_old_teacher  = \App\Helper\Utils::check_teacher_is_old($item['teacher_money_type']);
            $check_is_old_teacher  =0;
            $lesson_user_online_status  = $item["lesson_user_online_status"];


            if ( in_array($lesson_user_online_status,[0,1])) {
                \App\Helper\Utils::array_item_add_value($date_item,"test_lesson_count_succ",1);
                if(!$check_is_old_teacher){
                    \App\Helper\Utils::array_item_add_value($date_item,"test_lesson_count_succ_new",1);
                }
            }else{//fail
                if ( in_array($test_lesson_fail_flag , [1,2,3]) || $success_flag ==0 ) {
                    \App\Helper\Utils::array_item_add_value($date_item,"test_lesson_count_fail_need_money",1);
                    if(!$check_is_old_teacher){
                        \App\Helper\Utils::array_item_add_value($date_item,"test_lesson_count_fail_need_money_new",1);
                    }
                }
            }

            if ($item["require_admin_type"] == E\Eaccount_role::V_2 ) { //seller
                \App\Helper\Utils::array_item_add_value($date_item,"seller_test_lesson_count",1 );
                if ( in_array( $lesson_user_online_status,[0,1] )  ) {
                    \App\Helper\Utils::array_item_add_value($date_item,"seller_test_lesson_count_succ",1 );
                }else{//fail
                    if ( in_array($test_lesson_fail_flag , [1,2,3]) || $success_flag ==0  ) {
                        \App\Helper\Utils::array_item_add_value($date_item,"seller_test_lesson_count_fail_need_money",1 );
                        if(!$check_is_old_teacher){
                            \App\Helper\Utils::array_item_add_value($date_item,"seller_test_lesson_count_fail_need_money_new",1 );
                        }
                    }
                }
                if ( in_array( $lesson_login_status,[0,1] )  ) {
                    \App\Helper\Utils::array_item_add_value($date_item,"seller_test_lesson_count_stu_tea_join_count",1 );

                }
            }
        }

        //得到回访的总数
        $list = $this->t_tongji_date->get_list(1,$start_time,$end_time,-1);
        foreach ($list as $item) {
            $opt_date  = date("Y-m-d",$item["log_time"]);
            $date_item = &$date_list[$opt_date];
            $date_item["call_count"]= @$date_item["call_count"]+$item["count"];
        }

        //合同数
        $list = $this->t_order_info->get_1v1_order_list($start_time,$end_time,"",-1,$adminid_list,$adminid_all  ,-1,$grade_list, $stu_test_paper_flag );
        foreach ($list as $item) {
            $opt_date      = date("Y-m-d",$item["opt_date"]);
            $date_item     = &$date_list[$opt_date];
            $contract_type = $item["contract_type"];
            if ($contract_type==0 ) {
                $date_item["order_count"]     = @$date_item["order_count"]+1;
                $date_item["order_count_new"] = @$date_item["order_count_new"]+1;
            }else if ( $contract_type ==3 ) {
                $date_item["order_count"]      = @$date_item["order_count"]+1;
                $date_item["order_count_next"] = @$date_item["order_count_next"]+1;
            }
        }

        foreach ($date_list as &$d_item ) {
            $d_item["call_old_count"] = @$d_item["call_count"]-@$d_item["first_revisit_time_count"];
            if($d_item["call_old_count"] <0 ) {
                $d_item["call_old_count"] = 0;
            }

            $d_item["seller_test_lesson_count_fail_not_need_money"]
                 = @$d_item["seller_test_lesson_count"]
                 - @$d_item["seller_test_lesson_count_succ"]
                 - @$d_item["seller_test_lesson_count_fail_need_money"]
                 ;
        }

        if (!$order_in_db_flag) {
            \App\Helper\Utils::order_list( $date_list, $order_field_name, $order_type );
        }
        $all_item = ["title" => "全部"];
        \App\Helper\Utils::list_add_sum_item($date_list,$all_item,$sum_field_list);
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($date_list),[
            "data_ex_list"  => $date_list,
            "adminid_right" => $adminid_right
        ]);
    }

    public function user_count_22() {
        //time range
        //  例子  t_seller_student_origin (add_time)
        // 例子 已回访  t_seller_student_new (add_time) jion t_seller_student_origin  g_tq_call_flag

        // 例子 排课数, t_test_lesson_subject_require require_time  accept_flag!=2

        $sum_field_list=[
            "add_time_count",
            "tq_called_count",
            "course_plan_count"
        ];
        $order_field_arr=  array_merge(["title" ] ,$sum_field_list );

        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str($order_field_arr ,"title desc");
        $admin_revisiterid = $this->get_in_int_val("admin_revisiterid",-1);
        list($start_time,$end_time)=$this->get_in_date_range( date("Y-m-d",time(NULL)-14*86500),date("Y-m-d",time(NULL)));
        $date_list=\App\Helper\Common::get_date_time_list($start_time, $end_time-1);
        $list = $this->t_seller_student_origin->get_user_revisit_count_info($start_time,$end_time);
        foreach ($list as $item) {
            $opt_date=date("Y-m-d",$item["add_time"]);
            $date_item= &$date_list[$opt_date];
            $date_item["add_time_count"]=@$date_item["add_time_count"]+1;
            if($item['tq_called_flag'] == 1 || $item['tq_called_flag'] == 2){
                $date_item["tq_called_count"]=@$date_item["tq_called_count"]+1;
            }
        }
        $list = $this->t_test_lesson_subject_require->get_plan_course_info($start_time,$end_time);
        foreach ($list as $item) {
            $opt_date=date("Y-m-d",$item["require_time"]);
            $date_item= &$date_list[$opt_date];
            $date_item["course_plan_count"]=@$date_item["course_plan_count"]+1;
        }

        if (!$order_in_db_flag) {
            \App\Helper\Utils::order_list( $date_list, $order_field_name, $order_type );
        }
        $all_item=["title" => "全部"];
        \App\Helper\Utils::list_add_sum_item( $date_list, $all_item,$sum_field_list );
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($date_list) ,["data_ex_list"=>$date_list]);

    }

    public function seller_count_seller_master() {
        $this->set_in_value("group_adminid",$this->get_account_id() );
        return $this->seller_count();
    }

    public function seller_test_lesson_duration () {
        list($start_time,$end_time)= $this->get_in_date_range_week( 0 );
        $data_list=$this->t_test_lesson_subject_require->tongji_require_time_vs_admin_assign_time_duration($start_time,$end_time);
        //区分
        \App\Helper\Utils::check_env_is_local();


    }

    public function seller_count() {
        $sum_field_list=[
            "all_count",
            "all_count_0",
            "all_count_1",
            "no_call",
            "no_call_0",
            "no_call_1",
            "call_count",
            "invalid_count",
            "no_connect",
            "valid_count",
            "require_test_count",
            "test_lesson_count",
            "fail_need_pay_count",
            "order_count",
            "new_user_count",
        ];
        $order_field_arr = array_merge(["account" ] ,$sum_field_list );
        $grade_list      = $this->get_in_enum_list(E\Egrade::class);
        $group_adminid   = $this->get_in_int_val("group_adminid",-1);

        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str($order_field_arr ,"account desc");

        //   $group_list=$this->t_admin_group_name->get_group_list(2);
        // $groupid=$this->get_in_int_val("groupid", -1);
        list($start_time,$end_time)= $this->get_in_date_range_week( 0 );

        $origin_ex           = $this->get_in_str_val("origin_ex");

        $ret_info=$this->t_test_lesson_subject->get_seller_count( $start_time, $end_time, $grade_list , $origin_ex  );
        $new_user_info=$this->t_test_lesson_subject->get_seller_new_user_count( $start_time, $end_time, $grade_list, $origin_ex  );

        $tl_info=$this->t_test_lesson_subject_require->tongji_test_lesson_group_by_admin_revisiterid($start_time,$end_time ,$grade_list , $origin_ex );
        $tr_info=$this->t_test_lesson_subject_require->tongji_require_test_lesson_group_by_admin_revisiterid($start_time,$end_time,$grade_list , $origin_ex );
        //order info
        $order_info=$this->t_order_info->get_1v1_order_seller_list($start_time,$end_time ,$grade_list,"" , $origin_ex );

        $obj_list=&$ret_info["list"] ;
        foreach ($tl_info["list"] as $tl_item) {
            $k=$tl_item["admin_revisiterid"];

            \App\Helper\Utils::array_item_init_if_nofind($obj_list,$k, ["admin_revisiterid"=>$k] );
            $obj_list[$k]["test_lesson_count"]=$tl_item["test_lesson_count"];
            $obj_list[$k]["fail_need_pay_count"]=$tl_item["fail_need_pay_count"];
            $obj_list[$k]["fail_all_count"]=$tl_item["fail_all_count"];
            $obj_list[$k]["succ_all_count"]=$tl_item["succ_all_count"];

        }


        foreach ($tr_info["list"] as $tr_item) {
            $k=$tr_item["admin_revisiterid"];
            \App\Helper\Utils::array_item_init_if_nofind($obj_list,$k, ["admin_revisiterid"=>$k] );
            $obj_list[$k]["require_test_count"]=$tr_item["require_test_count"];

        }


        foreach ($order_info["list"] as $order_item) {
            $k=$order_item["adminid"];
            \App\Helper\Utils::array_item_init_if_nofind($obj_list,$k, ["admin_revisiterid"=>$k] );
            $obj_list[$k]["order_count"]=$order_item["all_count"];
            $obj_list[$k]["order_money"]=$order_item["all_price"];

        }

        $date_list=$this->t_id_opt_log-> get_seller_tongji($start_time,$end_time,$grade_list);
        foreach ($date_list as $date_item) {
            $k=$date_item["opt_id"];
            \App\Helper\Utils::array_item_init_if_nofind($obj_list,$k, ["admin_revisiterid"=>$k] );
            $obj_list[$k]["assigned_count"]=$date_item["assigned_count"];
            // $obj_list[$k]["get_new_count"]=$date_item["get_new_count"];
            $obj_list[$k]["get_histroy_count"]=$date_item["get_histroy_count"];
        }


        //x
        foreach ($new_user_info['list'] as $new_item) {
            $k=$new_item['admin_revisiterid'];
            \App\Helper\Utils::array_item_init_if_nofind($obj_list,$k, ["admin_revisiterid"=>$k] );
            $obj_list[$k]["new_user_count"]=$new_item["new_user_count"];
        }


        $all_item=["account" => "全部","admin_revisiterid" =>-1, ];
        foreach ($ret_info["list"] as &$item) {
            $item["valid_count"]=@$item["call_count"]-  @$item["invalid_count"]-@$item["no_connect"];
            foreach ($item as $key => $value) {
                if ((!is_int($key)) && ($key != "admin_revisiterid" )) {
                    $all_item[$key]=(@$all_item[$key])+$value;
                }
            }
            $this->cache_set_item_account_nick($item,"admin_revisiterid","account");
        }

        $ret_info = $ret_info['list'];
        $admin_info = $this->t_manager_info->get_admin_member_list();
        $admin_list= & $admin_info['list'] ;
        if ($group_adminid >0) {
            $groupid=$this->t_admin_group_name->get_groupid_by_master_adminid($group_adminid);
            $mark_user_map= $this->t_admin_group_user->get_user_map($groupid);
        }

        foreach ($admin_list as $vk=>&$val){
            $adminid=$val['adminid'];
            if (!isset($ret_info[$adminid ] )
                || ( $group_adminid >0 &&  !isset($mark_user_map[ $adminid ] ) )  )  {
                unset( $admin_list[$vk] );
            }else{

                $val['admin_revisiterid'] = $adminid ;
                $ret_item=@$ret_info[$adminid];
                $val['all_count'] = @$ret_item['all_count'];
                $val['all_count_0'] = @$ret_item['all_count_0'];
                $val['all_count_1'] = @$ret_item['all_count_1'];
                $val['no_call'] = @$ret_item['no_call'];
                $val['no_call_0'] = @$ret_item['no_call_0'];
                $val['no_call_1'] = @$ret_item['no_call_1'];
                $val['call_count'] = @$ret_item['account'];
                $val['all_account'] = @$ret_item['call_count'];
                $val['invalid_count'] = @$ret_item['invalid_count'];
                $val['no_connect'] = @$ret_item['no_connect'];
                $val['valid_count'] = @$ret_item['valid_count'];
                $val['test_lesson_count'] = @$ret_item['test_lesson_count'];
                $val['fail_need_pay_count'] = @$ret_item['fail_need_pay_count'];
                $val['require_test_count'] = @$ret_item['require_test_count'];
                $val['succ_all_count'] = @$ret_item['succ_all_count'];
                $val['fail_all_count'] = @$ret_item['fail_all_count'];
                $val['order_count'] = @$ret_item['order_count'];
                $val['order_money'] = @$ret_item['order_money'];
                $val['global_tq_no_call'] = @$ret_item['global_tq_no_call'];


                $val['new_user_count'] = @$ret_item['new_user_count'];

                $val['assigned_count'] = @$ret_item['assigned_count'];
                // $val['get_new_count'] = @$ret_item['get_new_count'];
                $val['get_histroy_count'] = @$ret_item['get_histroy_count'];


            }
        }

        /*if (!$order_in_db_flag) {
          \App\Helper\Utils::order_list( $ret_info, $order_field_name, $order_type );
          }

          array_unshift($ret_info, $all_item);*/
        $ret_info=\App\Helper\Common::gen_admin_member_data($admin_info['list'],[],0, strtotime( date("Y-m-01",$start_time )   ));
        /*$ret_info= $this->gen_admin_member_data($admin_info['list']);*/
        foreach( $ret_info as &$item ) {
            E\Emain_type::set_item_value_str($item);
        }


        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info),["data_ex_list"=>$ret_info]);


    }

    public function seller_test_lesson_transfor_per() {
        list($start_time,$end_time)= $this->get_in_date_range(0,0,0,[],3);
        if($end_time >= time()){
            $end_time = time();
        }

        $this->t_test_lesson_subject_require->switch_tongji_database();
        $res=[];
        $test_leeson_list=$this->t_test_lesson_subject_require->tongji_test_lesson_group_by_admin_revisiterid($start_time,$end_time );
        foreach($test_leeson_list['list'] as $item){
            $adminid = $item['admin_revisiterid'];
            $res[$adminid]['succ_all_count_for_month']=$item['succ_all_count'];
            $res[$adminid]['succ_green_count_for_month']=$item['green_lesson_count'];
            $res[$adminid]['all_green_count_for_month']=$item['succ_green_count'];
            $res[$adminid]['test_lesson_count_for_month'] = $item['test_lesson_count'];
            $res[$adminid]['fail_all_count_for_month'] = $item['fail_all_count'];
            if($item['test_lesson_count'] != 0){
                $res[$adminid]['lesson_per'] = round($item['fail_all_count']/$item['test_lesson_count'],2);
            }

        }
        $this->t_order_info->switch_tongji_database();
        $order_new = $this->t_order_info->get_1v1_order_list_by_adminid($start_time,$end_time,0);
        foreach($order_new as $k=>$v){
            $res[$k]['all_new_contract_for_month'] = $v['all_new_contract'];
        }
        $order_new_green = $this->t_order_info->get_1v1_order_list_by_adminid_green($start_time,$end_time,0);

        foreach($order_new_green as $k=>$v){
            $res[$k]['all_green_contract_for_month'] = $v['all_green_contract'];
        }

        foreach ($res as $ret_k=> &$res_item) {
            $res_item["adminid"] = $ret_k ;
        }
        $ret_info=\App\Helper\Common::gen_admin_member_data($res);
        foreach( $ret_info as &$item ) {
            E\Emain_type::set_item_value_str($item);

            $item["non_green_count"] = @$item['succ_all_count_for_month']-@$item['succ_green_count_for_month'];
            $item["non_green_order"] = @$item['all_new_contract_for_month']-@$item['all_green_contract_for_month'];
            $item['order_per'] = @$item['succ_all_count_for_month']!=0?(round(@$item['all_new_contract_for_month']/$item['succ_all_count_for_month'],4)*100)."%":0;
            $item['green_order_per'] = @$item['succ_green_count_for_month']!=0?(round(@$item['all_green_contract_for_month']/$item['succ_green_count_for_month'],4)*100)."%":0;
            $item['green_per'] = @$item['succ_all_count_for_month']!=0?(round(@$item['succ_green_count_for_month']/$item['succ_all_count_for_month'],4)*100)."%":0;
             $item['non_green_order_per'] = @$item['non_green_count']!=0?(round(@$item['non_green_order']/$item['non_green_count'],4)*100)."%":0;
        }

        // dd($ret_info);
        \App\Helper\Utils::logger("OUTPUT");

        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info));
    }

    public function origin_count_seller(){
        $check_field_id =$this->get_in_int_val("check_field_id",1);
        $this->set_in_value("tmk_adminid", -2 );
        if (!in_array( $check_field_id , [2,3,4,5] ) ) {
            $this->set_in_value("check_field_id", 5 );
        }

        return $this->origin_count();
    }

    public function origin_count_tmk(){
        $this->set_in_value("tmk_adminid", $this->get_account_id());
        $this->set_in_value("is_history",2);
        $this->set_in_value("sta_data_type",1);
        return $this->channel_statistics();
    }

    public function origin_count_yhyy () {
        $this->set_in_value("origin_ex","自有渠道,用户运营,,,");
        $this->set_in_value("is_history",2);
        $this->set_in_value("sta_data_type",1);
        return $this->channel_statistics();
    }


    public function origin_count_bd () {
        $is_history = $this->get_in_int_val('is_history',1);
        $sta_data_type = $this->get_in_int_val('sta_data_type',1);

        $this->set_in_value("origin_ex","渠道,,,,");
        $this->set_in_value("is_history",$is_history);
        $this->set_in_value("sta_data_type",$sta_data_type);
        return $this->channel_statistics();
    }


    public function origin_publish_jrtt () {
        $origin_ex= "今日头条";
        $this->set_in_value("origin_ex",  $origin_ex );
        return $this->origin_publish_list();
    }

    public function origin_count_bd_simple () {
        $this->set_in_value("origin_ex","渠道,,,,");
        return $this->origin_count_simple_has_intention();

    }

    public function origin_count_yxb() {
        $is_history = $this->get_in_int_val('is_history',1);
        $sta_data_type = $this->get_in_int_val('sta_data_type',1);

        $this->set_in_value("is_history",$is_history);
        $this->set_in_value("sta_data_type",$sta_data_type);
        $this->set_in_value("origin_ex","自有渠道,优学帮,,,");
        return $this->channel_statistics();
    }

    public function origin_count_yxb_simple () {
        $this->set_in_value("origin_ex","优学帮,,,");
        return $this->origin_count_simple_has_intention();
    }



    public function origin_count_not_bd () {
        $this->set_in_value("origin_ex","!BD,,,");
        return $this->origin_count();
    }

    public function origin_count(){
        $origin            = trim($this->get_in_str_val("origin",""));
        $origin_ex         = $this->get_in_str_val('origin_ex', "");
        $seller_groupid_ex = $this->get_in_str_val('seller_groupid_ex', "");
        $adminid_list      = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);
        $admin_revisiterid = $this->get_in_int_val("admin_revisiterid", -1);
        $groupid           = $this->get_in_int_val("groupid",-1);
        $tmk_adminid       = $this->get_in_int_val("tmk_adminid", -1);

        $check_field_id    = $this->get_in_int_val("check_field_id",1);
        $check_field_config=[
            1=> ["渠道","origin", "" ],
            2=> ["年级","grade", E\Egrade::class ],
            3=> ["科目","subject", E\Esubject::class ],
            4=> ["tmk人员","tmk_adminid", "" ],
            5=> ["销售人员","admin_revisiterid", "" ],
            6=> ["渠道等级","origin_level",   E\Eorigin_level::class  ],
        ];

        $data_map=[];
        $check_item=$check_field_config[$check_field_id];
        $field_name       = $check_item[1];
        $field_class_name = $check_item[2];

        list($start_time,$end_time ,$opt_date_str)=$this->get_in_date_range_month( date("Y-m-01"), 0, [
            0 => array( "add_time", "资源进来时间"),
            1 => array("tmk_assign_time","微信运营时间"),
        ] );

        $this->t_seller_student_origin->switch_tongji_database();

        $ret_info = $this->t_seller_student_origin->get_origin_tongji_info($field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid);


        //统计试听课相关信息  ---begin---
        $data_map=&$ret_info["list"];
        //试听信息
        $this->t_test_lesson_subject_require->switch_tongji_database();
        $test_lesson_list=$this->t_test_lesson_subject_require->tongji_test_lesson_origin( $origin, $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid, $origin_ex );
        foreach ($test_lesson_list as  $test_item ) {
            $check_value=$test_item["check_value"];
            \App\Helper\Utils:: array_item_init_if_nofind( $data_map, $check_value,["check_value" => $check_value] );
            $data_map[$check_value]["test_lesson_count"] = $test_item["test_lesson_count"];
            $data_map[$check_value]["distinct_test_count"] = $test_item["distinct_test_count"];
            //  $data_map[$check_value]["succ_test_lesson_count"] = $test_item["succ_test_lesson_count"];
            $data_map[$check_value]["succ_test_lesson_count"] = $test_item["succ_test_lesson_count_system"];
            $data_map[$check_value]["distinct_succ_count"] = $test_item["distinct_succ_count_system"];

        }
        //去掉重复userid
        $distinct_test_lesson_list=$this->t_test_lesson_subject_require->tongji_test_lesson_origin( $origin, $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid, $origin_ex , 1);

        foreach ($distinct_test_lesson_list as  $test_item ) {
            $check_value=$test_item["check_value"];
            //  $data_map[$check_value]["distinct_succ_count"] = $test_item["distinct_succ_count"];
        }


        $require_list=$this->t_test_lesson_subject_require->tongji_require_count_origin( $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid,$origin_ex, $origin);
        foreach ($require_list as  $item ) {
            $check_value=$item["check_value"];
            \App\Helper\Utils:: array_item_init_if_nofind( $data_map, $check_value,["check_value" => $check_value] );
            $data_map[$check_value]["require_count"] = $item["require_count"];
        }
        //统计试听课相关信息  ---begin---

        //统计订单相关信息  ---begin---
        $this->t_order_info->switch_tongji_database();
        //合同
        $order_list= $this->t_order_info->tongji_seller_order_count_origin( $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid,$origin_ex,$opt_date_str, $origin);
        foreach ($order_list as  $order_item ) {
            $check_value=$order_item["check_value"];
            \App\Helper\Utils:: array_item_init_if_nofind( $data_map, $check_value,["check_value" => $check_value ] );

            $data_map[$check_value]["order_count"] = $order_item["order_count"];
            $data_map[$check_value]["user_count"] = $order_item["user_count"];
            $data_map[$check_value]["order_all_money"] = $order_item["order_all_money"];
        }
        //统计订单相关信息  ---end---

        foreach ($data_map as &$item ) {
            if($field_class_name ) {
                $item["title"]= $field_class_name::get_desc($item["check_value"]);
            }else{
                if ($field_name=="tmk_adminid" || $field_name=="admin_revisiterid"  ) {
                    $item["title"]= $this->cache_get_account_nick( $item["check_value"] );
                }else{
                    $item["title"]= $item["check_value"];
                }

            }

            if ($field_name=="origin") {
                $item["origin"]= $item["title"];
            }
        }

        //重组分层数组
        if ($field_name=="origin") {
            $ret_info["list"]= $this->gen_origin_data($ret_info["list"],["avg_first_time"], $origin_ex);
        }

        $origin_type = 0;
        //统计渠道中的优学帮渠道
        if($origin_ex == '优学帮,,,'){
            $origin_type = 1;

            list($all_count,$assigned_count,$tmk_assigned_count,$tq_no_call_count,$tq_called_count,$tq_call_fail_count,
                 $tq_call_succ_valid_count,$tq_call_succ_invalid_count,$tq_call_fail_invalid_count,$have_intention_a_count,
                 $have_intention_b_count,$have_intention_c_count,$require_count,$test_lesson_count,$succ_test_lesson_count,
                 $order_count,$user_count,$order_all_money) = [[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],0];
            $ret  = $this->t_agent->get_agent_info_new(null);
            $userid_arr = [];

            $ret_new = [];
            $ret_info_new = [];
            $id_arr = array_unique(array_column($ret,'id'));
            foreach($ret as &$item){
                if($item['type'] == 1){
                    $userid_arr[] = $item['userid'];
                }
                $item['agent_type'] = $item['type'];
                $item['create_time'] = date('Y-m-d H:i:s',$item['create_time']);
                if($item['lesson_start']){
                    $item['lesson_start'] = date('Y-m-d H:i:s',$item['lesson_start']);
                }else{
                    $item['lesson_start'] = '';
                }

                $id = $item['id'];
                $id_arr_new = array_unique(array_column($ret_new,'id'));
                if(in_array($id,$id_arr_new)){
                }else{
                    if($item['lesson_start']){
                        if($item['lesson_start']>$item['create_time']){
                            $ret_new[] = $item;
                        }
                    }else{
                        $ret_new[] = $item;
                    }
                }
                //例子总数
                $id_arr_new_two = array_unique(array_column($ret_info_new,'id'));
                if(in_array($id,$id_arr_new_two)){
                }else{
                    $ret_info_new[] = $item;
                }
            }
            if(count($userid_arr)>0){
                foreach($ret_new as &$item){
                    //已分配销售
                    if($item['admin_revisiterid']>0){
                        $assigned_count[] = $item;
                    }
                    //TMK有效
                    if($item['tmk_student_status'] == 3){
                        $tmk_assigned_count[] = $item;
                    }
                    //未拨打
                    if($item['global_tq_called_flag'] == 0){
                        $tq_no_call_count[] = $item;
                    }
                    //已拨打
                    if($item['global_tq_called_flag'] != 0){
                        $tq_called_count[] = $item;
                    }
                    //未接通
                    if($item['global_tq_called_flag'] == 1){
                        $tq_call_fail_count[] = $item;
                    }
                    //已拨通-有效
                    if($item['global_tq_called_flag'] == 2 && $item['sys_invaild_flag'] == 0){
                        $tq_call_succ_valid_count[] = $item;
                    }
                    //已拨通-无效
                    if($item['global_tq_called_flag'] == 2 && $item['sys_invaild_flag'] == 1){
                        $tq_call_succ_invalid_count[] = $item;
                    }
                    //未拨通-无效
                    if($item['global_tq_called_flag'] == 1 && $item['sys_invaild_flag'] == 1){
                        $tq_call_fail_invalid_count[] = $item;
                    }
                    //有效意向(A)
                    if($item['global_tq_called_flag'] == 2 && $item['seller_student_status'] == 100){
                        $have_intention_a_count[] = $item;
                    }
                    //有效意向(B)
                    if($item['global_tq_called_flag'] == 2 && $item['seller_student_status'] == 101){
                        $have_intention_b_count[] = $item;
                    }
                    //有效意向(C)
                    if($item['global_tq_called_flag'] == 2 && $item['seller_student_status'] == 102){
                        $have_intention_c_count[] = $item;
                    }
                    //预约数&&上课数
                    // if($item['accept_flag'] == 1 && $item['is_test_user'] == 0 && $item['require_admin_type'] == 2 ){
                    if($item['test_lessonid']){
                        $require_count[] = $item;
                        $test_lesson_count[] = $item;
                    }
                    //试听成功数
                    if($item['lesson_user_online_status'] == 1 ){
                        $succ_test_lesson_count[] = $item;
                    }
                }
            }
            $order_info = $this->t_agent_order->get_all_list();
            foreach($order_info as $item){
                $orderid = $item['orderid'];
                $order_count[] = $item;
                $user_count[] = $item;
                $order_all_money += $item['price'];
            }
            foreach([0,1,2,3,4] as $item){
                $ret_info['list'][$item]['all_count'] = count($ret_info_new);
                $ret_info['list'][$item]['assigned_count'] = count($assigned_count);
                $ret_info['list'][$item]['tmk_assigned_count'] = count($tmk_assigned_count);
                $ret_info['list'][$item]['tq_no_call_count'] = count($tq_no_call_count);
                $ret_info['list'][$item]['tq_called_count'] = count($tq_called_count);
                $ret_info['list'][$item]['tq_call_fail_count'] = count($tq_call_fail_count);
                $ret_info['list'][$item]['tq_call_succ_valid_count'] = count($tq_call_succ_valid_count);
                $ret_info['list'][$item]['tq_call_succ_invalid_count'] = count($tq_call_succ_invalid_count);
                $ret_info['list'][$item]['tq_call_fail_invalid_count'] = count($tq_call_fail_invalid_count);
                $ret_info['list'][$item]['have_intention_a_count'] = count($have_intention_a_count);
                $ret_info['list'][$item]['have_intention_b_count'] = count($have_intention_b_count);
                $ret_info['list'][$item]['have_intention_c_count'] = count($have_intention_c_count);
                $ret_info['list'][$item]['require_count'] = count($require_count);
                $ret_info['list'][$item]['test_lesson_count'] = count($test_lesson_count);
                $ret_info['list'][$item]['succ_test_lesson_count'] = count($succ_test_lesson_count);
                $ret_info['list'][$item]['order_count'] = count($order_count);
                $ret_info['list'][$item]['user_count'] = count($user_count);
                $ret_info['list'][$item]['order_all_money'] = $order_all_money/100;
            }
        }

        //饼图用数据 --begin--
        //地区、年级科目、硬件、渠道等级等统计饼图数据
        $data_list = $this->t_seller_student_origin->get_origin_detail_info($opt_date_str,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list,$tmk_adminid);
        $subject_map      = [];
        $grade_map        = [];
        $has_pad_map      = [];
        $area_map         = [];
        $origin_level_map = [];
        $all_count        = count($data_list);

        foreach ($data_list as $a_item) {
            $subject      = $a_item["subject"];
            $grade        = $a_item["grade"];
            $has_pad      = $a_item["has_pad"];
            $origin_level = $a_item["origin_level"];
            $area_name    = substr($a_item["phone_location"], 0, -6);
            @$subject_map[$subject] ++;
            @$grade_map[$grade] ++;
            @$has_pad_map[$has_pad] ++;
            @$origin_level_map[$origin_level] ++;
            if (strlen($area_name)>5) {
                @$area_map[$area_name] ++;
            } else {
                @$area_map[""] ++;
            }

        }

        $group_list = $this->t_admin_group_name->get_group_list(2);


        //签单统计用饼图
        $order_area_map    = [];
        $order_subject_map = [];
        $order_grade_map   = [];
        //订单信息
        $order_data = $this->t_order_info->tongji_seller_order_info($origin, $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid,$origin_ex,$opt_date_str);
        foreach ($order_data as $a_item) {
            $subject   = $a_item["subject"];
            $grade     = $a_item["grade"];
            $area_name = substr($a_item["phone_location"], 0, -6);
            @$order_subject_map[$subject] ++;
            @$order_grade_map[$grade] ++;

            if (strlen($area_name)>5) {
                @$order_area_map[$area_name] ++;
            } else {
                @$order_area_map[""] ++;
            }

        }

        //试听统计用饼图
        $test_area_map    = [];
        $test_subject_map = [];
        $test_grade_map   = [];
        //试听信息
        $test_data=$this->t_test_lesson_subject_require->tongji_test_lesson_origin_info( $origin, $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid, $origin_ex,'','','',$opt_date_str);
        foreach ($test_data as $a_item) {
            $subject   = $a_item["subject"];
            $grade     = $a_item["grade"];
            $area_name = substr($a_item["phone_location"], 0, -6);
            @$test_subject_map[$subject] ++;
            @$test_grade_map[$grade] ++;

            if (strlen($area_name)>5) {
                @$test_area_map[$area_name] ++;
            } else {
                @$test_area_map[""] ++;
            }

        }

        //饼图用数据 --end--

        return $this->pageView(__METHOD__,$ret_info,[
            "subject_map"      => $subject_map,
            "grade_map"        => $grade_map,
            "has_pad_map"      => $has_pad_map,
            "origin_level_map" => $origin_level_map,
            "area_map"         => $area_map,
            "group_list"       => $group_list,
            "field_name"       => $field_name,
            "origin_type"      => $origin_type,
            "order_area_map"   => $order_area_map,
            "order_subject_map"=> $order_subject_map,
            "order_grade_map"  => $order_grade_map,
            "test_area_map"   => $test_area_map,
            "test_subject_map"=> $test_subject_map,
            "test_grade_map"  => $test_grade_map,
        ]);
    }

    //@desn:试听课相关明细
    public function origin_count_test_lesson_info(){
        $origin            = trim($this->get_in_str_val("origin",""));
        $origin_ex         = $this->get_in_str_val('origin_ex', "");
        $seller_groupid_ex = $this->get_in_str_val('seller_groupid_ex', "");
        $adminid_list      = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);
        $admin_revisiterid = $this->get_in_int_val("admin_revisiterid", -1);
        $groupid           = $this->get_in_int_val("groupid",-1);
        $tmk_adminid       = $this->get_in_int_val("tmk_adminid", -1);
        $check_value       = $this->get_in_str_val("check_value");
        $check_field_id    = $this->get_in_int_val("check_field_id",1);
        $page_info         = $this->get_in_page_info();
        $cond = $this->get_in_str_val('require_count');
        $check_field_config=[
            1=> ["渠道","origin", "" ],
            2=> ["年级","grade", E\Egrade::class ],
            3=> ["科目","subject", E\Esubject::class ],
            4=> ["tmk人员","tmk_adminid", "" ],
            5=> ["销售人员","admin_revisiterid", "" ],
            6=> ["渠道等级","origin_level",   E\Eorigin_level::class  ],
        ];

        $check_item=$check_field_config[$check_field_id];
        $field_name       = $check_item[1];
        $field_class_name = $check_item[2];

        list($start_time,$end_time ,$opt_date_str)=$this->get_in_date_range_month( date("Y-m-01"), 0, [
            0 => array( "add_time", "资源进来时间"),
            1 => array("tmk_assign_time","微信运营时间"),
        ] );

        $this->t_seller_student_origin->switch_tongji_database();

        //试听信息
        $this->t_test_lesson_subject_require->switch_tongji_database();
        $ret_info=$this->t_test_lesson_subject_require->tongji_test_lesson_origin_info('',$field_name,$start_time,$end_time,$adminid_list,$tmk_adminid, $origin_ex ,$check_value,$page_info,$cond,$opt_date_str);
        foreach($ret_info["list"] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start");
            E\Eseller_student_status::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);
            $item["lesson_user_online_status_str"] = \App\Helper\Common::get_set_boolean_color_str(
                $item["lesson_user_online_status"]
            );

            if ($item['success_flag'] != 2) {
                $item['success_flag_str'] = '是';
            } else{
                $item['success_flag_str'] = '否';
            }
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function origin_count_order_info(){
        $origin            = trim($this->get_in_str_val("origin",""));
        $origin_ex         = $this->get_in_str_val('origin_ex', "");
        $seller_groupid_ex = $this->get_in_str_val('seller_groupid_ex', "");
        $adminid_list      = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);
        $admin_revisiterid = $this->get_in_int_val("admin_revisiterid", -1);
        $groupid           = $this->get_in_int_val("groupid",-1);
        $tmk_adminid       = $this->get_in_int_val("tmk_adminid", -1);
        $check_value       = $this->get_in_str_val("check_value");
        $check_field_id    = $this->get_in_int_val("check_field_id",1);
        $page_info         = $this->get_in_page_info();
        $check_field_config=[
            1=> ["渠道","origin", "" ],
            2=> ["年级","grade", E\Egrade::class ],
            3=> ["科目","subject", E\Esubject::class ],
            4=> ["tmk人员","tmk_adminid", "" ],
            5=> ["销售人员","admin_revisiterid", "" ],
            6=> ["渠道等级","origin_level",   E\Eorigin_level::class  ],
        ];

        $check_item=$check_field_config[$check_field_id];
        $field_name       = $check_item[1];
        $field_class_name = $check_item[2];

        list($start_time,$end_time ,$opt_date_str)=$this->get_in_date_range_month( date("Y-m-01"), 0, [
            0 => array( "add_time", "资源进来时间"),
            1 => array("tmk_assign_time","微信运营时间"),
        ] );

        $this->t_seller_student_origin->switch_tongji_database();

        //订单信息
        $ret_info= $this->t_order_info->tongji_seller_order_info( '',$field_name,$start_time,$end_time,$adminid_list,$tmk_adminid,$origin_ex,$opt_date_str,$check_value,$page_info);

        foreach($ret_info["list"] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"pay_time");
            $item['price'] = $item['price']/100;
            E\Esubject::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);
            $item['lesson_all']= $item['lesson_total'] * $item['default_lesson_count']/100;
            $item['lesson_left']= $item['lesson_left'] / 100;
        }
        return $this->pageView(__METHOD__,$ret_info);
    }



    public function origin_count_simple(){
        $origin            = trim($this->get_in_str_val("origin",""));
        $origin_ex         = $this->get_in_str_val('origin_ex', "");
        $seller_groupid_ex = $this->get_in_str_val('seller_groupid_ex', "");
        $adminid_list      = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);
        $admin_revisiterid = $this->get_in_int_val("admin_revisiterid", -1);
        $groupid           = $this->get_in_int_val("groupid",-1);
        $tmk_adminid       = $this->get_in_int_val("tmk_adminid", -1);
        //$check_fie
        $check_field_id    = $this->get_in_int_val("check_field_id",1);
        $check_field_config=[
            1=> ["渠道","origin", "" ],
            2=> ["年级","grade", E\Egrade::class ],
            3=> ["科目","subject", E\Esubject::class ],
            4=> ["tmk人员","tmk_adminid", "" ],
            5=> ["销售人员","admin_revisiterid", "" ],
        ];
        if (!$origin_ex   )  {
            $origin_ex ="xx" ;
        }

        $data_map=[];
        $check_item=$check_field_config[$check_field_id];
        // $check_item=$check_field_config[$check_field_id];
        $field_name       = $check_item[1];
        $field_class_name = $check_item[2];



        list($start_time,$end_time ,$opt_date_str)=$this->get_in_date_range_month( date("Y-m-01"), 0, [
            0 => array( "add_time", "资源进来时间"),
            1 => array("tmk_assign_time","微信运营时间"),
        ] );
        // $this->t_seller_student_origin->switch_tongji_database();
        // $this->t_test_lesson_subject_require->switch_tongji_database();
        // $this->t_order_info->switch_tongji_database();

        $ret_info = $this->t_seller_student_origin->get_origin_tongji_info_not_intention( $field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid);



        $data_map=&$ret_info["list"];

        $require_list=$this->t_test_lesson_subject_require->tongji_require_count_origin( $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid,$origin_ex);
        foreach ($require_list as  $item ) {
            $check_value=$item["check_value"];
            \App\Helper\Utils:: array_item_init_if_nofind( $data_map, $check_value,["check_value" => $check_value] );
            $data_map[$check_value]["require_count"] = $item["require_count"];
        }




        //reset title
        foreach ($data_map as &$item ) {
            if($field_class_name ) {
                $item["title"]= $field_class_name::get_desc($item["check_value"]);
            }else{
                if ($field_name=="tmk_adminid" || $field_name=="admin_revisiterid"  ) {
                    $item["title"]= $this->cache_get_account_nick( $item["check_value"] );
                }else{
                    $item["title"]= $item["check_value"];
                }
            }

            if ($field_name=="origin") {
                $item["origin"]= $item["title"];
            }
        }

        if ($field_name=="origin") {
            $ret_info["list"]= $this->gen_origin_data($ret_info["list"],[], $origin_ex);
        }

        $data_list = $this->t_seller_student_origin->get_origin_detail_info($opt_date_str,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list,$tmk_adminid);
        $subject_map = [];
        $grade_map   = [];
        $has_pad_map = [];
        $area_map    = [];
        $all_count   = count($data_list);
        $group_list= $this->t_admin_group_name->get_group_list(2);
        $ret= $this->pageView(__METHOD__,$ret_info,[
            "group_list"  => $group_list,
            "field_name"  => $field_name,
        ]);
        return $ret;
    }



    public function origin_count_simple_has_intention(){
        $origin            = trim($this->get_in_str_val("origin",""));
        $origin_ex         = $this->get_in_str_val('origin_ex', "");
        $seller_groupid_ex = $this->get_in_str_val('seller_groupid_ex', "");
        $adminid_list      = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);
        $admin_revisiterid = $this->get_in_int_val("admin_revisiterid", -1);
        $groupid           = $this->get_in_int_val("groupid",-1);
        $tmk_adminid       = $this->get_in_int_val("tmk_adminid", -1);
        //$check_fie
        $check_field_id    = $this->get_in_int_val("check_field_id",1);
        $check_field_config=[
            1=> ["渠道","origin", "" ],
            2=> ["年级","grade", E\Egrade::class ],
            3=> ["科目","subject", E\Esubject::class ],
            4=> ["tmk人员","tmk_adminid", "" ],
            5=> ["销售人员","admin_revisiterid", "" ],
        ];

        $data_map=[];
        $check_item=$check_field_config[$check_field_id];
        // $check_item=$check_field_config[$check_field_id];
        $field_name       = $check_item[1];
        $field_class_name = $check_item[2];



        list($start_time,$end_time ,$opt_date_str)=$this->get_in_date_range_month( date("Y-m-01"), 0, [
            0 => array( "add_time", "资源进来时间"),
            1 => array("tmk_assign_time","微信运营时间"),
        ] );
        // $this->t_seller_student_origin->switch_tongji_database();
        // $this->t_test_lesson_subject_require->switch_tongji_database();
        // $this->t_order_info->switch_tongji_database();

        $ret_info = $this->t_seller_student_origin->get_origin_tongji_info( $field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid);

        // dd($ret_info);
        $data_map=&$ret_info["list"];

        $require_list=$this->t_test_lesson_subject_require->tongji_require_count_origin( $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid,$origin_ex);
        foreach ($require_list as  $item ) {
            $check_value=$item["check_value"];
            \App\Helper\Utils:: array_item_init_if_nofind( $data_map, $check_value,["check_value" => $check_value] );
            $data_map[$check_value]["require_count"] = $item["require_count"];
        }




        //reset title
        foreach ($data_map as &$item ) {
            if($field_class_name ) {
                $item["title"]= $field_class_name::get_desc($item["check_value"]);
            }else{
                if ($field_name=="tmk_adminid" || $field_name=="admin_revisiterid"  ) {
                    $item["title"]= $this->cache_get_account_nick( $item["check_value"] );
                }else{
                    $item["title"]= $item["check_value"];
                }
            }

            if ($field_name=="origin") {
                $item["origin"]= $item["title"];
            }
        }

        if ($field_name=="origin") {
            $ret_info["list"]= $this->gen_origin_data_level5($ret_info["list"],[], $origin_ex);
        }

        $data_list = $this->t_seller_student_origin->get_origin_detail_info($opt_date_str,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list,$tmk_adminid);
        $subject_map = [];
        $grade_map   = [];
        $has_pad_map = [];
        $area_map    = [];
        $all_count   = count($data_list);
        $group_list= $this->t_admin_group_name->get_group_list(2);
        return $this->pageView(__METHOD__,$ret_info,[
            "group_list"  => $group_list,
            "field_name"  => $field_name,
        ]);

    }


    //@desn:将数据重组结构
    //@param:$old_list 需要重组的数组
    //@param:$no_sum_list 不需要相加的列
    //@param:$origin_ex 渠道字符串
    public function gen_origin_data($old_list,$no_sum_list=[] ,$origin_ex="")
    {

        $value_map=$this->t_origin_key->get_list( $origin_ex);
        //组织分层用类标识
        $cur_key_index=1;
        $check_init_map_item=function (&$item, $key, $key_class, $value = "") {
            global $cur_key_index;
            if (!isset($item [$key])) {
                $item[$key] = [
                    "value" => $value,
                    "key_class" => $key_class."-".$cur_key_index,
                    "sub_list"=>[] ,
                    "data" => array(),
                ];
                $cur_key_index++;
            }
        };
        //计算相加数据
        $add_data=function (&$item, $add_item ,$self_flag=false) use ( $no_sum_list) {
            $arr=&$item["data"];
            foreach ($add_item as $k => $v) {
                if (!is_int($k) && $k!="origin" &&
                    ($self_flag|| !in_array(  $k ,$no_sum_list ) )
                ) {
                    if (!isset($arr[$k])) {
                        $arr[$k]=0;
                    }
                    $arr[$k]= $arr[$k]+intval($v);
                }
            }

        };

        $all_item=["origin"=>"全部"];

        $check_init_map_item($data_map,"","");
        foreach ($old_list as &$item) {
            $value=trim($item["origin"]);
            //没有key1 key2 key3
            if (!isset($value_map[$value])) {
                $value_map[$value]=[
                    "key1"=>"未定义",
                    "key2"=>"未定义",
                    "key3"=>"未定义",
                    "key4"=>$value,
                    "value"=>$value,
                ];
            }

            $conf=$value_map[$value];

            $key1=$conf["key1"];
            $key2=$conf["key2"];
            $key3=$conf["key3"];
            $key4=$conf["key4"];
            $key0_map=&$data_map[""];
            $add_data($key0_map, $item );

            $check_init_map_item($key0_map["sub_list"] , $key1,"key1" );
            $key1_map=&$key0_map["sub_list"][$key1];
            $add_data($key1_map, $item );

            $check_init_map_item($key1_map["sub_list"] , $key2 ,"key2");
            $key2_map=&$key1_map["sub_list"][$key2];
            $add_data($key2_map, $item );

            $check_init_map_item($key2_map["sub_list"] , $key3 ,"key3");
            $key3_map=&$key2_map["sub_list"][$key3];
            $add_data($key3_map, $item );

            $check_init_map_item($key3_map["sub_list"] , $key4,"key4",$value);
            $key4_map=&$key3_map["sub_list"][$key4];
            $add_data($key4_map, $item, true);

        }
        $list=[];
        //array_unshift($ret_info["list"],$all_item);
        foreach ($data_map as $key0 => $item0) {
            $data=$item0["data"];
            $data["key1"]="全部";
            $data["key2"]="";
            $data["key3"]="";
            $data["key4"]="";
            $data["key1_class"]="";
            $data["key2_class"]="";
            $data["key3_class"]="";
            $data["key4_class"]="";
            $data["level"]="l-0";

            $list[]=$data;
            foreach ($item0["sub_list"] as $key1 => $item1) {
                $data=$item1["data"];
                $data["key1"]=$key1;
                $data["key2"]="";
                $data["key3"]="";
                $data["key4"]="";
                $data["key1_class"]=$item1["key_class"];
                $data["key2_class"]="";
                $data["key3_class"]="";
                $data["key4_class"]="";
                $data["level"]="l-1";

                $list[]=$data;

                foreach ($item1["sub_list"] as $key2 => $item2) {
                    $data=$item2["data"];
                    $data["key1"]=$key1;
                    $data["key2"]=$key2;
                    $data["key3"]="";
                    $data["key4"]="";
                    $data["key1_class"]=$item1["key_class"];
                    $data["key2_class"]=$item2["key_class"];
                    $data["key3_class"]="";
                    $data["key4_class"]="";
                    $data["level"]="l-2";

                    $list[]=$data;
                    foreach ($item2["sub_list"] as $key3 => $item3) {
                        $data=$item3["data"];
                        $data["key1"]=$key1;
                        $data["key2"]=$key2;
                        $data["key3"]=$key3;
                        $data["key4"]="";
                        $data["key1_class"]=$item1["key_class"];
                        $data["key2_class"]=$item2["key_class"];
                        $data["key3_class"]=$item3["key_class"];
                        $data["key4_class"]="";
                        $data["level"]="l-3";

                        $list[]=$data;
                        foreach ($item3["sub_list"] as $key4 => $item4) {
                            $data=$item4["data"];
                            $data["key1"]=$key1;
                            $data["key2"]=$key2;
                            $data["key3"]=$key3;
                            $data["key4"]=$key4;
                            $data["value"] = $item4["value"];
                            $data["key1_class"]=$item1["key_class"];
                            $data["key2_class"]=$item2["key_class"];
                            $data["key3_class"]=$item3["key_class"];
                            $data["key4_class"]=$item4["key_class"];
                            $k4_v=$item4["value"];
                            if ($k4_v != $key4) {
                                $data["key4"]=$key4."/". $k4_v ;
                            }
                            $data["old_key4"]=$key4;
                            $data["level"]="l-4";
                            $list[]=$data;
                        }

                    }

                }


            }
        }

        foreach($list as &$item){
            if($item["level"]=="l-4" && $item["key1"]!="未定义"){
                $item["create_time"] = $value_map[$item['value']]["create_time"];
                if(!empty($item["create_time"])){
                    $item["create_time"] = date('Y-m-d',$item["create_time"]);
                }else{
                    $item["create_time"] = "";
                }
            }else{
                $item["create_time"] = "";
            }
        }
        return $list;
    }



    public function contract_count() {
        list($start_time,$end_time,$opt_date_str)= $this->get_in_date_range(
            -7, 1, 0, [
                0 => array( "order_time", "合同生成时间"),
                1 => array("check_money_time","财务确认时间"),
            ], 0,0
        );

        //$start_time        = $this->get_in_start_time_from_str(date("Y-m-d",(time(NULL)-86400*7)) );
        //$end_time          = $this->get_in_end_time_from_str(date("Y-m-d",(time(NULL)+86400)) );
        $contract_type     = $this->get_in_int_val("contract_type",-1);
        $contract_status   = -1;
        $config_courseid   = -1;
        $is_test_user      = $this->get_in_int_val("is_test_user", 0 , E\Eboolean::class  );
        $studentid         = $this->get_in_studentid(-1);
        $check_money_flag  = $this->get_in_int_val("check_money_flag", -1);
        $origin            = $this->get_in_str_val("origin");
        $from_type         = $this->get_in_int_val("from_type",-1);
        $account_role      = $this->get_in_int_val("account_role",-1);
        $has_money         = -1;
        $sys_operator      = $this->get_in_str_val("sys_operator","");
        $seller_groupid_ex = $this->get_in_str_val('seller_groupid_ex', "");
        $adminid_list      = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);
        $adminid_right     = [] ;
        $adminid_all       = [] ;

        $account=$this->get_account();
        $show_yueyue_flag = false;
        if ($account =="yueyue" || $account=="jim" || $account=="echo" ) {
            $show_yueyue_flag= true;
        }
        //$show_yueyue_flag= true;
        $ret_info=$this->t_order_info->get_order_count_new($start_time,$end_time,$contract_type,$contract_status,$studentid,$config_courseid,$is_test_user, $show_yueyue_flag, $has_money,$check_money_flag,-1,$origin,$from_type,$sys_operator,$account_role,$adminid_list,$adminid_all,$opt_date_str);
        $ret_info = $ret_info['list'];
        $admin_info = $this->t_manager_info->get_admin_member_list();
        $admin_list= & $admin_info['list'] ;
        foreach ($admin_list as $vk=>&$val){
            if( !isset($ret_info[$val['adminid']] ) ) {
                unset( $admin_list[$vk] );
            }else{
                $val['all_price'] = @$ret_info[$val['adminid']]['all_price']/100;
                $val['transfer_introduction_price'] = @$ret_info[$val['adminid']]['transfer_introduction_price']/100;
                $val['new_price'] = @$ret_info[$val['adminid']]['new_price']/100;
                $val['normal_price'] = @$ret_info[$val['adminid']]['normal_price']/100;
                $val['extend_price'] = @$ret_info[$val['adminid']]['extend_price']/100;
                $val['all_price_suc'] = @$ret_info[$val['adminid']]['all_price_suc']/100;
                $val['all_price_fail'] = @$ret_info[$val['adminid']]['all_price_fail']/100;
            }
        }
        $ret_info=\App\Helper\Common::gen_admin_member_data($admin_info['list']);
        // $ret_info=\App\Helper\Common_new::gen_admin_member_data_new(1); // 开发中
        foreach( $ret_info as &$item ) {
            E\Emain_type::set_item_value_str($item);
            $item["del_flag"] = isset($item["del_flag"])?$item["del_flag"]:0;
            if($item['level'] == "l-4" ){
                $item["del_flag_str"] = \App\Helper\Common::get_boolean_color_str($item["del_flag"]);
            }else{
                $item["del_flag_str"] = '';
            }
        }

        $data_list = $this->t_order_info->get_order_count_subject_grade_info($start_time,$end_time,$contract_type,$contract_status,$studentid,$config_courseid,$is_test_user, $show_yueyue_flag, $has_money,$check_money_flag,-1,$origin,$from_type,$sys_operator,$account_role,$adminid_list,$adminid_all);
        $subject_map=[];
        $grade_map=[];
        $subject_count_map=[];
        $grade_count_map=[];
        $phone_map=[];
        $phone_count_map=[];
        foreach ($data_list as $a_item) {
            $subject = $a_item["subject"];
            $grade   = $a_item["grade"];
            $phone   = $a_item["phone_location"];
            @$subject_map[$subject] =@$subject_map[$subject]+$a_item["price"]/100;
            @$grade_map[$grade] =@$grade_map[$grade]+$a_item["price"]/100;
            @$phone_map[$phone] =@$phone_map[$phone]+$a_item["price"]/100;
            @$subject_count_map[$subject] ++;
            @$grade_count_map[$grade] ++;
            @$phone_count_map[$phone] ++;

        }

        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info),[
            "subject_map"       => $subject_map,
            "grade_map"         => $grade_map,
            "phone_map"         => $phone_map,
            "subject_count_map" => $subject_count_map,
            "grade_count_map"   => $grade_count_map,
            "phone_count_map"   => $phone_count_map,
            "adminid_right"     => $adminid_right
        ]);
    }

    public function test_lesson_plan_detail_list_jw() {
        $this->set_in_value("set_lesson_adminid", $this->get_account_id() );
        return $this->test_lesson_plan_detail_list();
    }
    public function test_lesson_plan_detail_list() {
        list($start_time,$end_time)=$this->get_in_date_range_day(0);
        $page_num= $this->get_in_page_num();
        $set_lesson_adminid = $this->get_in_int_val("set_lesson_adminid", -1);
        $subject = $this->get_in_subject(-1);
        $grade  = $this->get_in_grade(-1);
        $success_flag = $this->get_in_int_val("success_flag", -1, E\Eset_boolean::class);
        $test_lesson_fail_flag = $this->get_in_int_val("test_lesson_fail_flag", -1, E\Etest_lesson_fail_flag::class);
        $userid=$this->get_in_userid(-1);
        $require_admin_type=$this->get_in_int_val("require_admin_type", -1, E\Eaccount_role::class);
        $require_adminid=$this->get_in_int_val("require_adminid", -1 );

        $ret_info=$this->t_test_lesson_subject_sub_list->tongji_get_plan_list( $page_num,$start_time,$end_time ,$set_lesson_adminid ,$subject, $grade, $success_flag, $test_lesson_fail_flag, $userid, $require_admin_type,$require_adminid );

        foreach($ret_info["list"]as &$item) {
            $this->cache_set_item_teacher_nick($item);
            $this->cache_set_item_account_nick($item,"require_adminid", "require_admin_nick" );
            $this->cache_set_item_account_nick($item,"set_lesson_adminid", "set_lesson_admin_nick" );
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start","","Y-m-d H:i");
            E\Etest_lesson_fail_flag::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);
            E\Eset_boolean::set_item_value_str($item,"success_flag");
            $item["phone_ex"] = preg_replace('/(1[356789]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$item['phone']);
        }

        return $this->pageView(__METHOD__,$ret_info);
    }

    public function  set_lesson_count() {
        list($start_time,$end_time)=$this->get_in_date_range_week(0);
        $account_role=$this->get_in_account_role();

        $ret_info  = $this->t_test_lesson_subject_require->tongji_set_lesson_info($account_role ,$start_time,$end_time);
        $order_map = $this->t_order_info->tongji_by_grade($account_role,$start_time,$end_time);

        $all_item=["subject_str"=>'全部'];
        foreach ($order_map as $subjectid =>$v_item ) {
            $find_flag=false;
            foreach($ret_info["list"]as $k_list ) {
                if ($k_list["subject"] == $subjectid) {
                    $find_flag=true;
                    break;
                }
            }
            if (!$find_flag) {
                $ret_info["list"][]=[ "subject" => $subjectid ];
            }
        }

        foreach($ret_info["list"]as &$item) {
            E\Esubject::set_item_value_str($item);

            $subject=$item["subject"];
            $order_item=@$order_map[$subject];

            $item["l_1_order_count"]=@$order_item["l_1_order_count"];
            $item["l_2_order_count"]=@$order_item["l_2_order_count"];
            $item["l_3_order_count"]=@$order_item["l_3_order_count"];
            $item["l_all_order_count"]=@$order_item["l_all_order_count"];

            $all_item["l_1_count"]=@$all_item["l_1_count"]+@$item["l_1_count"];
            $all_item["l_1_stu_in_count"]=@$all_item["l_1_stu_in_count"]+@$item["l_1_stu_in_count"];
            $all_item["l_2_count"]=@$all_item["l_2_count"]+@$item["l_2_count"];
            $all_item["l_2_stu_in_count"]=@$all_item["l_2_stu_in_count"]+@$item["l_2_stu_in_count"];
            $all_item["l_3_count"]=@$all_item["l_3_count"]+@$item["l_3_count"];
            $all_item["l_3_stu_in_count"]=@$all_item["l_3_stu_in_count"]+@$item["l_3_stu_in_count"];
            $all_item["l_all_count"]=@$all_item["l_all_count"]+@$item["l_all_count"];
            $all_item["l_all_stu_in_count"]=@$all_item["l_all_stu_in_count"]+@$item["l_all_stu_in_count"];
            $all_item["l_1_order_count"]=@$all_item["l_1_order_count"]+@$item["l_1_order_count"];
            $all_item["l_2_order_count"]=@$all_item["l_2_order_count"]+@$item["l_2_order_count"];
            $all_item["l_3_order_count"]=@$all_item["l_3_order_count"]+@$item["l_3_order_count"];
            $all_item["l_all_order_count"]=@$all_item["l_all_order_count"]+@$item["l_all_order_count"];

        }
        if (count($all_item)>1 ) {
            $ret_info["list"][]=$all_item;
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function require_count_seller()
    {
        list($start_time,$end_time) = $this->get_in_date_range_day(0);
        $seller_groupid_ex = $this->get_in_str_val('seller_groupid_ex', "");
        $adminid_list = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);
        //$adminid_right = $this->get_seller_adminid_and_right();
        $adminid_right = [];//$this->get_seller_adminid_and_right();
        $adminid_all   = [];

        $this->t_test_lesson_subject_require->switch_tongji_database();
        $ret_info=$this->t_test_lesson_subject_require->require_count_seller($start_time, $end_time,$adminid_list,$adminid_all);

        $set_lesson_list= $this->t_test_lesson_subject_require->tongin_set_lesson_time_info($start_time,$end_time,$adminid_list,$adminid_all );
        $call_info_list = $this->t_tq_call_info->get_call_info_by_adminid_list($start_time, $end_time,$adminid_list,$adminid_all);
        \App\Helper\Common::merge_row_data($ret_info["list"] ,$set_lesson_list ,"adminid");
        \App\Helper\Common::merge_row_data($ret_info["list"] ,$call_info_list ,"adminid");

        $start_index=\App\Helper\Utils::get_start_index_from_ret_info($ret_info);
        $ret_info["list"]=\App\Helper\Common::gen_admin_member_data($ret_info['list']);

        foreach($ret_info["list"] as $id => &$item){
            E\Emain_type::set_item_value_str($item);
            $item['id'] = $start_index+$id;
            $item['call_time_long'] = isset($item['call_time_long'])?$item['call_time_long']:0;
            $hour = floor($item['call_time_long']/3600);
            $min = floor($item['call_time_long']%3600/60);
            $sec = floor($item['call_time_long']%3600%60);
            $item['call_time_long'] = $hour.'时'.$min.'分'.$sec.'秒';
        }

        return $this->pageView(__METHOD__,$ret_info,[
            "adminid_right"=>$adminid_right,
        ]);
    }

    public function master_no_assign_count() {
        list($start_time,$end_time)=$this->get_in_date_range_month(0);
        $ret_info=$this->t_test_lesson_subject-> tongji_master_no_assign_count( $start_time,$end_time) ;
        foreach($ret_info["list"] as $id => &$item){
            $this->cache_set_item_account_nick($item,"sub_assign_adminid_2", "sub_assign_admin_nick_2" );
        }
        return $this->pageView(__METHOD__,$ret_info);
    }
    public function require_no_set_lesson_info() {
        list($start_time,$end_time, $opt_date_str )=$this->get_in_date_range(0, 10,0,[
            0=> [ "require_time" ,"申请时间" ],
            1=> [ "stu_request_test_lesson_time" ,"预期上课时间" ],
        ]);
        $data_arr=$this->t_test_lesson_subject_require->require_no_set_lesson_tonoji( $opt_date_str,$start_time, $end_time );
        $all_count=0;
        foreach($data_arr["require_admin_list"] as &$r_item) {
            $this->cache_set_item_account_nick($r_item,"id","id_str");
            $all_count+=$r_item["count"];

        }
        foreach($data_arr["grade_list"] as &$g_item) {
            E\Egrade::set_item_value_str($g_item,"id");
        }
        foreach($data_arr["subject_list"] as &$s_item) {
            E\Esubject::set_item_value_str($s_item, "id");
        }

        $data_arr["all_count"] =$all_count;
        return $this->pageView(__METHOD__,NULL, $data_arr);
    }
    public function teacher_test_lesson_info() {
        list($start_time,$end_time)=$this->get_in_date_range(-120, 0 );
        $page_num= $this->get_in_page_num();
        $test_lesson_flag =$this->get_in_int_val("test_lesson_flag",-1,E\Eboolean::class);
        $l_1v1_flag=$this->get_in_int_val("l_1v1_flag",-1,E\Eboolean::class);
        $tutor_subject= $this->get_in_int_val("tutor_subject", -1, E\Esubject::class);
        $ret_info=$this->t_teacher_info-> tongji_get_test_lesson_info($page_num,$start_time,$end_time,$test_lesson_flag, $l_1v1_flag ,$tutor_subject );
        foreach ( $ret_info["list"]  as &$item   ) {
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            E\Esubject::set_item_value_str($item,"tutor_subject");
        }
        return $this->pageView(__METHOD__, $ret_info );
    }

    public function lesson_device_info() {
        list($start_time,$end_time)=$this->get_in_date_range_month(0);
        $this->t_lesson_info->switch_tongji_database();
        $this->t_order_info->switch_tongji_database();
        $test_lesson_info = $this->t_lesson_info->get_test_lesson_device_info($start_time,$end_time);
        $order_info = $this->t_order_info->get_order_device_info($start_time,$end_time);
        $test_lesson_info["android_count"]
             = $test_lesson_info["all_count"]  - $test_lesson_info["ipad_count"]
             - $test_lesson_info["windows_count"]  - $test_lesson_info["null_count"]
             ;
        $order_info["android_count"]
             = $order_info["all_count"]  - $order_info["ipad_count"]
             - $order_info["windows_count"]  - $order_info["null_count"]
             ;

        $list=[];
        $new_item=function ($name, $field ) use($test_lesson_info,$order_info ) {
            return ["name" => $name,
                    "test_lesson_count" => $test_lesson_info[$field],
                    "order_count" => $order_info[$field],
                    "order_percent" => $test_lesson_info[$field] ? $order_info[$field]/ $test_lesson_info[$field]*100 :0,
            ];
        };


        $list[]=$new_item( "ipad", "ipad_count" );
        $list[]=$new_item( "PC端", "windows_count" );
        $list[]=$new_item( "安卓", "android_count" );
        $list[]=$new_item( "无信息", "null_count" );
        $list[]=$new_item( "全部", "all_count" );

        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($list) );
    }
    public function get_day_data_from_time($name,$start_time,$adminid_list=[],$adminid_all=[])  {
        $end_time=$start_time+86400;

        $seller_count=$this->t_seller_month_money_target->get_seller_num_day($start_time,$adminid_list,$adminid_all);
        if ($seller_count==0) {
            $seller_count=1;
        }
        //新签人数
        $ret_arr=[
            "name" => $name. date("m-d", $start_time),
            "seller_count" => $seller_count,
        ];
        $order_arr=$this->t_order_info->seller_info( $start_time,$end_time,$adminid_list,$adminid_all);

        //呼出量
        $tq_arr=$this->t_tq_call_info->tongji_tq_info_all($start_time,$end_time,$adminid_list,$adminid_all);
        $tq_arr["tq_all_count_avg"] =  intval($tq_arr["tq_all_count"]/$seller_count);
        $tq_arr["tq_duration_count_avg"] =intval( $tq_arr["tq_duration_count"]/$seller_count);
        $tq_arr["tq_duration_count_avg_str"] =  \App\Helper\Common::get_time_format( $tq_arr["tq_duration_count_avg"]);



        //排课
        $set_lesson_arr=$this->t_test_lesson_subject_sub_list->get_set_lesson_count_info($start_time,$end_time,$adminid_list,$adminid_all);
        //试听课
        $test_lesson_arr= $this->t_test_lesson_subject_require->tongji_test_lesson_all($start_time,$end_time,$adminid_list,$adminid_all);
        $ret_arr=array_merge($ret_arr, $order_arr,$tq_arr,$set_lesson_arr,$test_lesson_arr );

        return $ret_arr;
    }

    public function diff_data_precent($name ,$data_list1, $data_list2) {
        $ret_arr=["name" =>$name ];
        $field_list=[
            "seller_count",
            "order_money",
            "order_user_count",
            "pre_price",
            "tq_all_count_avg",
            "tq_duration_count_avg",
            "set_lesson_count",
            "test_lesson_count",
            "test_lesson_fail_count",
            "test_lesson_fail_percent",
        ];

        foreach ($field_list as $item_name ) {
            $ret_arr[$item_name] = \App\Helper\Utils::gen_diff_persent( @$data_list1[$item_name], @$data_list2[$item_name]   );
        }
        $ret_arr["tq_duration_count_avg_str"]=$ret_arr["tq_duration_count_avg"];
        return $ret_arr;
    }

    public function day_report()  {
        $this->check_and_switch_tongji_domain();
        list($start_time,$end_time) = $this->get_in_date_range_day(-1);
        $week_start_time            = $start_time-7*86400;
        $month_start_time=\App\Helper\Utils::get_up_month_day($start_time);
        $seller_groupid_ex         = $this->get_in_str_val('seller_groupid_ex', "");
        $this->t_admin_main_group_name->switch_tongji_database();
        $adminid_list = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);

        // dd($adminid_list);

        // adminid_list=[];
        $total_info_list=[];
        $total_info_list[]=$this->get_day_data_from_time("本日",$start_time,$adminid_list);
        $total_info_list[]=$this->get_day_data_from_time("上周",$week_start_time,$adminid_list);
        $total_info_list[]=$this->get_day_data_from_time("上月",$month_start_time,$adminid_list);
        $total_info_list[]= $this->diff_data_precent( "比-上周",  $total_info_list[1],$total_info_list[0]);
        $total_info_list[]= $this->diff_data_precent( "比-上月",  $total_info_list[2],$total_info_list[0]);
        //比上周


        $this->t_order_info->switch_tongji_database();
        $this->t_test_lesson_subject_require ->switch_tongji_database();
        $this->t_test_lesson_subject_sub_list   ->switch_tongji_database();

        //新签金额列表 本月
        $month_start_time=strtotime( date("Y-m-01",  $start_time));
        $month_end_time=strtotime(date("Y-m-01",  ($month_start_time+86400*32)));
        $month_date_money_list=$this->t_order_info->get_seller_date_money_list($month_start_time,$month_end_time,$adminid_list);
        //insert  null date
        $month_finish_define_money = $this->get_month_finish_define_money($seller_groupid_ex,$start_time);
        if (!$month_finish_define_money) {
            $month_finish_define_money=1600000;
        }
        $month_finish_define_money_2=$month_finish_define_money/100;
        $cur_money=0;
        $today=time(NULL);
        foreach ($month_date_money_list as $date=> &$item ) {
            $date_time=strtotime($date);
            if ($date_time>$today) {

            }else{
                $cur_money+=@$item["money"];
                $item["month_finish_persent"]= intval($cur_money/$month_finish_define_money_2) ;
            }
        }
        $month_date_set_lesson_list=$this->t_test_lesson_subject_sub_list-> get_seller_date_set_lesson_list($month_start_time,$month_end_time,$adminid_list);

        $month_date_test_lesson_list=$this->t_test_lesson_subject_require->get_seller_date_test_lesson_list($month_start_time,$month_end_time,$adminid_list );
        foreach ($month_date_test_lesson_list as $date=> &$t_item ) {
            $date_time=strtotime($date);
            if ($date_time>$today-86400) {
                $t_item["test_lesson_fail_percent"]=null;

            }else{
                $t_item["test_lesson_fail_percent"]=@$t_item["test_lesson_count"]? intval($t_item["test_lesson_fail_count"]*100/$t_item["test_lesson_count"]):0 ;
            }
        }
        return $this->pageView(__METHOD__,null, [
            "total_info_list"  => $total_info_list,
            "g_date_ex_list"=> [
                "month_date_money_list" => $month_date_money_list,
                "month_date_set_lesson_list" => $month_date_set_lesson_list,
                "month_date_test_lesson_list" => $month_date_test_lesson_list,
            ],
            "month_money_info"=>[
                "month_finish_define_money" => $month_finish_define_money,
                "month_finish_money" => $cur_money,
                "month_left_money" => $month_finish_define_money- $cur_money ,
                "month_finish_persent" => intval( $cur_money*100/$month_finish_define_money),
            ],
        ]);


    }

    public function get_month_finish_define_money($seller_groupid_ex,$start_time){
        $this->t_admin_main_group_name->switch_tongji_database();
        $this->t_admin_group_name->switch_tongji_database();
        $this->t_manager_info->switch_tongji_database();
        $this->t_seller_month_money_target->switch_tongji_database();
        $this->t_admin_group_month_time->switch_tongji_database();
        $arr=explode(",",$seller_groupid_ex);
        $main_type="";
        $up_groupid="";
        $groupid="";
        $adminid="";
        $main_type_list =["助教"=>1,"销售"=>2,"教务"=>3];
        if (isset($arr[0]) && !empty($arr[0])){
            $main_type_name= $arr[0];
            $main_type = $main_type_list[$main_type_name];
        }
        if (isset($arr[1])  && !empty($arr[1])){
            $up_group_name= $arr[1];
            $up_groupid = $this->t_admin_main_group_name->get_groupid_by_group_name($up_group_name);
        }
        if (isset($arr[2])  && !empty($arr[2])){
            $group_name= $arr[2];
            $groupid = $this->t_admin_group_name->get_groupid_by_group_name($group_name);
        }
        if (isset($arr[3])  && !empty($arr[3])){
            $account= $arr[3];
            $adminid = $this->t_manager_info->get_id_by_account($account);
        }

        $month = date("Y-m-01",$start_time);
        $groupid_list = [];
        if($adminid){
            $month_finish_define_money=$this->t_seller_month_money_target->field_get_value_2( $adminid,$month,"personal_money");
        }else{
            if($groupid){
                $groupid_list[] = $groupid;
            }else{
                if($up_groupid){
                    $groupid_list = $this->t_admin_group_name->get_groupid_list_new($up_groupid,-1);
                }else{
                    if($main_type){
                        $groupid_list = $this->t_admin_group_name->get_groupid_list_new(-1,$main_type);
                    }
                }
            }
            $month_finish_define_money=$this->t_admin_group_month_time->get_month_money_by_month( $start_time,$groupid_list);
        }

        return $month_finish_define_money;
    }

    public function tmk_test_lesson_count(){
        list($start_time,$end_time)=$this->get_in_date_range(-7, 0 );
        $subject= $this->get_in_int_val("subject", -1);
        $ret = $this->t_test_lesson_subject_require->get_tmk_test_lesson_count_info($start_time,$end_time,$subject);
        $ret_info=\App\Helper\Common::gen_admin_member_data($ret);
        foreach( $ret_info as &$item ) {
            E\Emain_type::set_item_value_str($item);
        }
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info));
    }
    public function new_user_count() {
        list($start_time,$end_time)=$this->get_in_date_range_day(0);
        $origin_ex           = $this->get_in_str_val("origin_ex");

        $list=$this->t_seller_student_new->get_hour_user_count($start_time,$end_time,$origin_ex);

        \App\Helper\Utils::init_hour_list($list,["count"=>0]);

        foreach( $list as &$item) {
            $item["opt_time"]=$start_time+$item["hour"]*3600;
        }
        ksort($list);

        $log_type =2017120201;
        $m_html_list=$this->t_tongji_date->get_list($log_type, $start_time, $end_time,0 );


        $log_type =2017122101;
        $m_html_book_list=$this->t_tongji_date->get_list($log_type, $start_time, $end_time,0 );



        return $this->pageView(__METHOD__,null, [
            "g_data_ex_list"=> [
                "new_user_count_list" => $list,
                "m_html_count_list" =>  $m_html_list,
                "m_html_book_list" =>  $m_html_book_list,
            ],
        ]);

    }

    public function get_origin_info_by_order(){
        list($start_time,$end_time)=$this->get_in_date_range(date('Y-m-01',time()), 0 );
        $origin = trim($this->get_in_str_val("origin",""));
        $origin_ex         = $this->get_in_str_val('origin_ex', "");
        $seller_groupid_ex         = $this->get_in_str_val('seller_groupid_ex', "");
        $adminid_list = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);


        $ret_info = $this->t_seller_student_new->get_origin_info_by_order($start_time,$end_time,$origin,$origin_ex,$adminid_list);
        $ret_info["list"]= $this->gen_origin_data($ret_info["list"]);
        /*foreach($ret_info["list"] as &$item){
          $item["success_per"] = (sprintf("%.2f",$item["success_count"]/$item["all_count"])*100)."%";
          $item["order_per"] = (sprintf("%.2f",$item["order_count"]/$item["all_count"])*100)."%";
          }*/
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function get_teacher_test_lesson_info(){
        if (\App\Helper\Utils::check_env_is_release() ) {
            dd("暂停" );
        }
        $sum_field_list = [
            "work_day",
            "regular_stu_num",
            "teacher_lesson_count_total",
            "test_lesson_num",
            "test_lesson_num_week",
            "all_lesson",
            "success_lesson",
            "lesson_num",
            "test_person_num",
            "kk_num",
            "change_num",
            "success_not_in_lesson",
            "success_per",
            "have_order",
            "order_number",
            "kk_order",
            "change_order",
            "order_num_per",
            "order_per",
            "kk_per",
            "change_per"
        ];
        $order_field_arr = array_merge(["nick"],$sum_field_list);
        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str($order_field_arr,"nick desc");

        $page_num = $this->get_in_page_num();
        list($start_time,$end_time) = $this->get_in_date_range(date('Y-m-01',time()), 0 );
        $teacherid                  = $this->get_in_int_val('teacherid', -1);
        $teacher_account            = $this->get_in_int_val('teacher_account', -1);
        $subject                    = $this->get_in_int_val('subject', -1);
        $subject_num                = $this->get_in_int_val('subject_num', -1);
        $teacher_subject            = $this->get_in_int_val('teacher_subject', -1);
        $identity                   = $this->get_in_int_val('identity', -1);
        $is_new_teacher             = $this->get_in_int_val("is_new_teacher",1);
        $teacher_money_type         = $this->get_in_int_val('teacher_money_type', -1);
        $have_interview_teacher     = $this->get_in_int_val('have_interview_teacher',-1);
        $reference_teacherid        = $this->get_in_int_val('reference_teacherid',-1);
        $grade_part_ex              =$this->get_in_int_val('grade_part_ex',-1);
        $teacher_test_status             =$this->get_in_int_val('teacher_test_status',-1);
        $tea_subject = "";
        $tea_right   = 0;
        $this->get_tea_subject($tea_subject,$tea_right);
        $adminid      = $this->get_account_id();
        if($adminid==478){
            $tea_subject="";
            $tea_right   = 2;
        }

        if($adminid==480){
            $qz_flag=1;
        }else{
            $qz_flag=0;
        }

        $this->t_lesson_info->switch_tongji_database();
        $count    = 0;
        $ret_info = $this->t_lesson_info->tongji_teacher_test_lesson_info_list(
            $start_time,$end_time,$teacherid,$teacher_money_type,$subject,$identity,$is_new_teacher,
            $tea_subject,$teacher_account,$have_interview_teacher,$reference_teacherid,$grade_part_ex,$teacher_subject,$qz_flag);
        $test_person_num= $this->t_lesson_info->get_teacher_test_person_num_list( $start_time,$end_time,$subject,$grade_part_ex);
        $kk_test_person_num= $this->t_lesson_info->get_kk_teacher_test_person_num_list( $start_time,$end_time,$subject,$grade_part_ex);
        $change_test_person_num= $this->t_lesson_info->get_change_teacher_test_person_num_list( $start_time,$end_time,$subject,$grade_part_ex);
        $success_test_lesson_list = $this->t_lesson_info->get_success_test_lesson_list_new($start_time,$end_time,$subject,$grade_part_ex);
        $success_not_in_lesson_list =  $this->t_lesson_info->get_success_not_test_lesson_list_new($start_time,$end_time,$subject,$grade_part_ex);
        $have_order_list =$this->t_lesson_info->get_have_order_list_new($start_time,$end_time,$subject,$grade_part_ex);
        $subject_num_list = $this->t_lesson_info->get_teacher_test_subject_num_list( $start_time,$end_time,$subject,$grade_part_ex);

        $date                              = \App\Helper\Utils::get_month_range(time(),1);
        $teacher_regular_stu_list          = $this->t_lesson_info->get_regular_stu_num($date["sdate"],$date["edate"]);
        $teacher_test_lesson_num_list      = $this->t_lesson_info->get_test_lesson_num_list(time(),time()+21*86400);
        $teacher_lesson_count_total        = $this->t_lesson_info->get_teacher_lesson_count_total(time(),$date["edate"]);
        $date_week                         = \App\Helper\Utils::get_week_range(time(),1);
        $teacher_test_lesson_num_list_week = $this->t_lesson_info->get_test_lesson_num_list(time(),$date_week["edate"]);

        $all      = [];
        $old_time = strtotime("2016-11-20 00:00:00");

        foreach($ret_info["list"] as $k=>&$item){
            $item["test_person_num"] = isset($test_person_num[$item["teacherid"]])?$test_person_num[$item["teacherid"]]["person_num"]:0;
            $item["lesson_num"] = isset($test_person_num[$item["teacherid"]])?$test_person_num[$item["teacherid"]]["lesson_num"]:0;
            $item["have_order"] = isset($test_person_num[$item["teacherid"]])?$test_person_num[$item["teacherid"]]["have_order"]:0;
            $item["order_number"] = isset($success_test_lesson_list[$item["teacherid"]])?$success_test_lesson_list[$item["teacherid"]]["order_number"]:0;
            $item["success_lesson"] = isset($success_test_lesson_list[$item["teacherid"]])?$success_test_lesson_list[$item["teacherid"]]["success_lesson"]:0;
            $item["success_not_in_lesson"] = isset($success_not_in_lesson_list[$item["teacherid"]])?$success_not_in_lesson_list[$item["teacherid"]]["success_not_in_lesson"]:0;
            $item["subject_num"] = isset($subject_num_list[$item["teacherid"]])?$subject_num_list[$item["teacherid"]]["subject_num"]:0;

            //$item["have_order"]= isset($have_order_list[$item["teacherid"]])?$have_order_list[$item["teacherid"]]["have_order"]:0;
            $item["kk_num"] = isset($kk_test_person_num[$item["teacherid"]])?$kk_test_person_num[$item["teacherid"]]["kk_num"]:0;
            $item["kk_order"] = isset($kk_test_person_num[$item["teacherid"]])?$kk_test_person_num[$item["teacherid"]]["kk_order"]:0;
            $item["kk_per"] = !empty($item["kk_num"])?round($item["kk_order"]/$item["kk_num"],4)*100:0;
            $item["change_num"] = isset($change_test_person_num[$item["teacherid"]])?$change_test_person_num[$item["teacherid"]]["change_num"]:0;
            $item["change_order"] = isset($change_test_person_num[$item["teacherid"]])?$change_test_person_num[$item["teacherid"]]["change_order"]:0;
            $item["change_per"] = !empty($item["change_num"])?round($item["change_order"]/$item["change_num"],4)*100:0;

            if(!empty($item["create_time"])){
                $item["create_time_str"] = date("Y-m-d",$item["create_time"]);
            }else{
                $item["create_time_str"] ="";
            }

            if($item['is_freeze']==1){
                $item['status_str'] = "已冻结";
            }elseif($item['limit_plan_lesson_type']>0){
                $item['status_str'] = E\Elimit_plan_lesson_type::get_desc($item['limit_plan_lesson_type']);
            }else{
                $item['status_str'] = "正常";
            }

            $item["success_per"] = sprintf("%.2f",$item["success_lesson"]/$item["all_lesson"])*100;
            $item["order_per"]   = !empty($item["success_lesson"])?round($item["order_number"]/$item["success_lesson"],4)*100:0;
            E\Elevel::set_item_value_str($item,"level");
            E\Eidentity::set_item_value_str($item,"identity");
            E\Eteacher_money_type::set_item_value_str($item);
            $item["work_day"] = round((time()-$item["create_time"])/86400);
            $item["regular_stu_num"]=isset($teacher_regular_stu_list[$item["teacherid"]])?$teacher_regular_stu_list[$item["teacherid"]]["regular_count"]:0;
            $item["test_lesson_num"]=isset($teacher_test_lesson_num_list[$item["teacherid"]])?$teacher_test_lesson_num_list[$item["teacherid"]]["test_lesson_count"]:0;
            $item["test_lesson_num_week"]=isset($teacher_test_lesson_num_list_week[$item["teacherid"]])?$teacher_test_lesson_num_list_week[$item["teacherid"]]["test_lesson_count"]:0;
            $item["teacher_lesson_count_total"]=isset($teacher_lesson_count_total[$item["teacherid"]])?$teacher_lesson_count_total[$item["teacherid"]]["lesson_total"]/100:0;
            $item["order_num_per"] = !empty($item["test_person_num"])?round($item["have_order"]/$item["test_person_num"],4)*100:0;
            $item["kk_lesson_per"] = !empty($item["success_lesson"]-$item["test_person_num"])?round(($item["order_number"]-$item["have_order"])/($item["success_lesson"]-$item["test_person_num"]),4)*100:0;
            $item["add_time_str"] = date('Y-m-d',$item["add_time"]);
            $item["freeze_time_str"] = date('Y-m-d',$item["freeze_time"]);
            $item["limit_plan_lesson_time_str"] = date("Y-m-d",$item["limit_plan_lesson_time"]);

            $count ++;
            if($subject_num==1){
                if($item["subject_num"] >1){
                    unset($ret_info["list"][$k]);
                    $count --;
                }
            }else if($subject_num==2){
                if($item["subject_num"] <=1){
                    unset($ret_info["list"][$k]);
                    $count --;
                }
            }
        }



        if (!$order_in_db_flag) {
            \App\Helper\Utils::order_list( $ret_info["list"], $order_field_name, $order_type );
        }

        $all_item = [
            "nick" => "全部"
        ];
        \App\Helper\Utils::list_add_sum_item($ret_info["list"], $all_item,$sum_field_list);

        foreach($ret_info["list"] as &$item){
            if($item["nick"] == "全部"){
                $item["create_time_str"]        = "";
                $item["teacher_money_type_str"] = "";
                $item["level_str"]              = "";
                $item["work_day"]               = "";
                $item["school"]                 = "";
                $item["interview_access"]       = "";
                $item["account"]                = "";
                $item["identity_str"]           = "";
                $item["add_time_str"]           = "";
                $item["freeze_time_str"]        = "";
                $item["limit_plan_lesson_time_str"] = "";

                if(count($ret_info["list"]) > 2){
                    $item["success_per"] = sprintf("%.2f",$item["success_lesson"]/$item["all_lesson"])*100;
                    $item["order_per"] = round(
                        \App\Helper\Common::div_safe( $item["order_number"],$item["success_lesson"])
                                               ,4)*100;
                    $item["order_num_per"] = !empty($item["test_person_num"])?round($item["have_order"]/$item["test_person_num"],4)*100:0;
                    $item["kk_per"] = !empty($item["kk_num"])?round($item["kk_order"]/$item["kk_num"],4)*100:0;
                    $item["change_per"] = !empty($item["change_num"])?round($item["change_order"]/$item["change_num"],4)*100:0;
                }
            }
        }

        $day_time = $this->get_avg_conversion_time(time(),2);
        $rr = $this->t_lesson_info->get_order_add_time();
        $order_lesson_day = round(($rr["lesson_time"]-$rr["order_time"])/$rr["all_count"]/86400,1);


        return $this->pageView(__METHOD__,$ret_info,[
            "month_start" => time(),
            "start_time"  => $start_time,
            "end_time"    => $end_time,
            "tea_subject" => @$account_info["subject"],
            "tea_right"   => @$tea_right,
            "adminid"     => $this->get_account_id(),
            "day_time"    => $day_time,
            "order_lesson_day"=>$order_lesson_day,
            "count"        =>$count,
            "subject"      =>$subject,
            "teacher_test_status"  =>$teacher_test_status
        ]);
    }

    public function get_tea_subject(&$tea_subject,&$tea_right){
        $adminid      = $this->get_account_id();
        $account_info = $this->t_manager_info->get_teacher_info_by_adminid($adminid);
        $arr          = [329,379,325,478,310,72];

        if($account_info["teacherid"]>0){
            if($account_info["teacherid"] ==61828 ){
                $tea_subject = "(4,5,6,7,8,9,10)";
            }elseif($account_info["teacherid"] ==53289 ){
                $tea_subject = "(1,3)";
            }else if(!empty($account_info["subject"])){
                $tea_subject = "(".$account_info["subject"];
                if(!empty($account_info["second_subject"])) $tea_subject = $tea_subject.",".$account_info["second_subject"];
                if(!empty($account_info["third_subject"])) $tea_subject = $tea_subject.",".$account_info["third_subject"];
                $tea_subject = $tea_subject.")";
            }else{
                $tea_subject = "";
            }
            if(in_array($adminid,$arr)){
                $tea_right = 1;
            }else{
                $tea_right = 0;
            }
        }else{
            $tea_subject = "";
            $tea_right   = 1;
        }
    }

    public function get_test_lesson_history_info(){
        $teacherid = $this->get_in_int_val('teacherid');
        $start_time = $this->get_in_int_val('start_time');
        $end_time = $this->get_in_int_val('end_time');
        $subject                    = $this->get_in_int_val('subject', -1);
        $this->t_lesson_info->switch_tongji_database();
        $ret_info = $this->t_lesson_info->get_teacher_test_lesson_history_info($start_time,$end_time,$teacherid,$subject);
        foreach($ret_info as &$item){
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            $item["grade_str"]=$item["grade"]==100?"小学":($item["grade"]==200?"初中":($item["grade"]==300?"高中":$item["grade_str"]));
            $item["lesson_start_str"]=date("Y-m-d H:i:s",$item["lesson_start"]);
        }
        return  $this->output_succ( [ "data" =>$ret_info] );

    }

    public function get_test_lesson_grade_info(){
        $teacherid = $this->get_in_int_val('teacherid');
        $start_time = $this->get_in_int_val('start_time');
        $end_time = $this->get_in_int_val('end_time');
        $subject                    = $this->get_in_int_val('subject', -1);
        $this->t_lesson_info->switch_tongji_database();
        $this->t_course_order->switch_tongji_database();
        $ret_info = $this->t_lesson_info->get_teacher_test_lesson_history_success_info($start_time,$end_time,$teacherid,$subject);
        $data=[];
        foreach($ret_info as $item){
            $aa = $this->t_course_order->get_have_order_info($item["teacherid"],$item["userid"],$item["subject"]);
            $grade = $item["grade"];
            if($grade>=101 && $grade <=103 ){
                @$data[1]["name"]="小低";
                @$data[1]["lesson"]++;
                if($aa >0){
                   @$data[1]["order"]++;
                }
            }else if($grade>=104 && $grade <=106 ){
                @$data[2]["name"]="小高";
                @$data[2]["lesson"]++;
                if($aa >0){
                   @$data[2]["order"]++;
                }
            }else if($grade>=201 && $grade <=202 ){
                @$data[3]["name"]="初一初二";
                @$data[3]["lesson"]++;
                if($aa >0){
                   @$data[3]["order"]++;
                }
            }else if($grade==203 ){
                @$data[4]["name"]="初三";
                @$data[4]["lesson"]++;
                if($aa >0){
                   @$data[4]["order"]++;
                }
            }else if($grade>=301 && $grade <=302 ){
                @$data[5]["name"]="高一高二";
                @$data[5]["lesson"]++;
                if($aa >0){
                   @$data[5]["order"]++;
                }
            }else if($grade==303 ){
                @$data[6]["name"]="高三";
                @$data[6]["lesson"]++;
                if($aa >0){
                   @$data[6]["order"]++;
                }
            }

        }
        foreach($data as &$val){
            if(!isset($val["order"])){
                $val["order"] = 0;
            }
            $val["per"] = round(@$val["order"]/$val["lesson"],4)*100;
        }

        return  $this->output_succ( [ "data" =>$data] );

    }


    public function get_test_lesson_history_success_info(){
        $teacherid = $this->get_in_int_val('teacherid');
        $start_time = $this->get_in_int_val('start_time');
        $end_time = $this->get_in_int_val('end_time');
        $subject                    = $this->get_in_int_val('subject', -1);
        $this->t_lesson_info->switch_tongji_database();
        $this->t_course_order->switch_tongji_database();
        $ret_info = $this->t_lesson_info->get_teacher_test_lesson_history_success_info($start_time,$end_time,$teacherid,$subject);
        foreach($ret_info as &$item){
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            $item["grade_str"]=$item["grade"]==100?"小学":($item["grade"]==200?"初中":($item["grade"]==300?"高中":$item["grade_str"]));
            $item["lesson_start_str"]=date("Y-m-d H:i:s",$item["lesson_start"]);
            \App\Helper\Utils::unixtime2date_for_item($item, "stu_request_test_lesson_time");
            E\Eregion_version::set_item_value_str($item, "editionid");

            //E\Epad_type::set_item_value_str($item, "has_pad");
            E\Etest_lesson_level::set_item_value_str($item, "stu_test_lesson_level");
            E\Etest_lesson_order_fail_flag::set_item_value_str($item, "test_lesson_order_fail_flag");


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
            $item["draw_url"] = \App\Helper\Common::get_url_ex($item["draw"]);
            $item["audio_url"] = \App\Helper\Common::get_url_ex($item["audio"]);

            $item["stu_test_paper_flag_str"] = \App\Helper\Common::get_test_pager_boolean_color_str(
                $item["stu_test_paper"], $item['tea_download_paper_time']
            );
            //$req_adminid = $item["req_adminid"];
            $phone = $item["phone"];
            $rev_info = $this->t_book_revisit->get_rev_info_by_phone_adminid($phone);
            $ss = "";
            foreach($rev_info as &$v){
                $v["revisit_time_str"] = date("Y-m-d H:i:s",$v["revisit_time"]);
                $ss .= "<p>".$v["revisit_time_str"].":".$v["operator_note"]."</p>";
            }
            $item["rev"]=$ss;
            $aa = $this->t_course_order->get_have_order_info($item["teacherid"],$item["userid"],$item["subject"]);
            if($aa >0){
                $item["have_order"] ="<font color=\"red\">已签</font>";
                $item["fail_info"]="";
            }elseif($item["test_lesson_order_fail_flag"]>0){
                $item["have_order"] ="签约失败";
                $item["fail_info"]="失败类型:".$item["test_lesson_order_fail_flag_str"]."<br>失败说明:".$item["test_lesson_order_fail_desc"];
            }else{
                $item["have_order"] ="未签";
                $item["fail_info"]="";
            }
            $call_str ="";
            $call_revisit_list = $this->t_tq_call_info->get_call_info_list($item["cur_require_adminid"],$item["phone"]);
            $clink_args="?enterpriseId=3005131&userName=admin&pwd=".md5(md5("leoAa123456" )."seed1")  . "&seed=seed1"  ;
            $now = time();
            foreach($call_revisit_list as $ty) {
                $record_url= $ty["record_url"] ;
                if ($now-$ty["start_time"] >1*86400 && (preg_match("/saas.yxjcloud.com/", $record_url  )|| preg_match("/121.196.236.95/", $record_url  ) ) ){
                    $load_wav_self_flag=1;
                }else{
                    $load_wav_self_flag=0;
                }
                if (preg_match("/api.clink.cn/", $record_url ) ) {
                    $record_url .=$clink_args;
                }

                $call_time = date("Y-m-d H:i:s",$ty["start_time"]);
                $call_str .="<p>".$call_time.":<a class=\"audio_show\" data-url=\"".$record_url."\" data-flag=\"".$load_wav_self_flag."\">听录音</a></p>";

            }

            $item["call_rev"] = $call_str;



        }
        return  $this->output_succ( [ "data" =>$ret_info] );

    }

    public function get_teacher_stu_info_new(){
        $teacherid = $this->get_in_int_val('teacherid');
        $date=\App\Helper\Utils::get_month_range(time(),1);
        $date["sdate"] = time()-30*86400;
        //  $date["edate"] = time();
        $regular_stu_list =$this->t_lesson_info->get_regular_stu_num_by_teacher($date["sdate"],$date["edate"],$teacherid);
        $grade_arr = [];
        $subject_arr=[];
        $stu_list=[];
        foreach($regular_stu_list as &$item){
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            @$grade_arr[$item["grade_str"]]++;
            @$subject_arr[$item["subject_str"]]++;
            if(!isset($stu_list[$item["userid"]])){
                $stu_list[$item["userid"]] =$item["userid"];
            }
        }
        $lesson_left =$this->t_student_info->get_all_lesson_left($stu_list);
        return  $this->output_succ( [ "data" =>$regular_stu_list,"grade"=>$grade_arr,"subject"=>$subject_arr,"lesson_left"=>$lesson_left] );

    }

    public function get_teacher_test_lesson_info_new(){
        $teacherid = $this->get_in_int_val('teacherid');
        $this->t_lesson_info->switch_tongji_database();
        $teacher_test_lesson_num_list = $this->t_lesson_info->get_test_lesson_num_list_detail(time(),time()+21*86400,$teacherid);
        foreach($teacher_test_lesson_num_list as &$item){
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            $item["grade_str"]=$item["grade"]==100?"小学":($item["grade"]==200?"初中":($item["grade"]==300?"高中":$item["grade_str"]));
            $item["lesson_start_str"]=date("Y-m-d H:i:s",$item["lesson_start"]);
        }
        return  $this->output_succ( [ "data" =>$teacher_test_lesson_num_list] );
    }
    public function get_teacher_test_lesson_info_week(){
        $teacherid = $this->get_in_int_val('teacherid');
        $date_week = \App\Helper\Utils::get_week_range(time(),1);
        $this->t_lesson_info->switch_tongji_database();
        $teacher_test_lesson_num_list = $this->t_lesson_info->get_test_lesson_num_list_detail(time(),$date_week["edate"],$teacherid);
        foreach($teacher_test_lesson_num_list as &$item){
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            $item["grade_str"]=$item["grade"]==100?"小学":($item["grade"]==200?"初中":($item["grade"]==300?"高中":$item["grade_str"]));
            $item["lesson_start_str"]=date("Y-m-d H:i:s",$item["lesson_start"]);
        }
        return  $this->output_succ( [ "data" =>$teacher_test_lesson_num_list] );
    }

    public function ss_for_eric(){
        $realname=["潘英敏","朱俐","赵云瑶","赵越","段小梅","王茜","于艳","张家红","叶子","王艳芳","史正佳","杨研","程浩","郁雨竹","康静","宋文丽","汤佳琛","陈璐烨","陈宣贝","苗嘉轩","朱思敏","曲鸿儒","张凌","李伟","杨清叶","冯守万","武岩","宁斯同","张萍","李苏","郭学春","王佩文","龙小红","谢楠静","吴琪"];
        $arr = [];
        foreach($realname as $v){
            $tea = $this->t_teacher_info->get_teacherid_by_realname($v);
            foreach($tea as &$val){
                $val["time"]=date("Y-m-d H:i:s",$val["set_lesson_time"]);
            }
            $arr[$v]=$tea;
        }
        $ret =[];
        foreach($arr as $vv){
            if(!empty($vv)){
                foreach($vv as $qq){
                    $ret[]=$qq;
                }
            }
        }
        $ret_info = \App\Helper\Utils::list_to_page_info($ret);
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function get_teacher_test_lesson_info_old(){
        $sum_field_list=[
            "identity_str",
            "level_str",
            "school",
            "create_time_str",
            "work_day",
            "regular_stu_num",
            "test_lesson_num",
            "test_lesson_num_week",
            "all_lesson",
            "success_lesson",
            "success_per",
            "have_order",
            "order_per"
        ];
        $order_field_arr = array_merge(["nick" ] ,$sum_field_list );
        list( $order_in_db_flag, $order_by_str, $order_field_name, $order_type )
            =$this->get_in_order_by_str($order_field_arr ,"nick desc");

        list($start_time,$end_time)=$this->get_in_date_range(date('Y-10-01',time()), date('Y-10-31',time()));
        $page_num           = $this->get_in_page_num();
        $teacherid          = $this->get_in_int_val('teacherid', -1);
        $subject            = $this->get_in_int_val('subject', -1);
        $identity           = $this->get_in_int_val('identity', -1);
        $teacher_money_type = $this->get_in_int_val('teacher_money_type', -1);
        $is_new_teacher     = $this->get_in_int_val("is_new_teacher",1);

        $this->t_lesson_info->switch_tongji_database();
        $ret_info = $this->t_lesson_info->get_teacher_test_info_old(
            $start_time,$end_time,$teacherid,$page_num,$teacher_money_type,$subject,$identity,$is_new_teacher);

        $date=\App\Helper\Utils::get_month_range(time(),1);
        $teacher_regular_stu_list =$this->t_lesson_info->get_regular_stu_num($date["sdate"],$date["edate"]);
        $teacher_test_lesson_num_list = $this->t_lesson_info->get_test_lesson_num_list(time(),time()+21*86400);

        $date_week = \App\Helper\Utils::get_week_range(time(),1);
        $teacher_test_lesson_num_list_week = $this->t_lesson_info->get_test_lesson_num_list(time(),$date_week["edate"]);
        $all = [];
        foreach($ret_info["list"] as &$item){
            if(!empty($item["create_time"])){
                $item["create_time_str"] = date("Y-m-d",$item["create_time"]);
            }else{
                $item["create_time_str"] ="";
            }
            //$item["success_per"] = sprintf("%.2f",$item["success_lesson"]/$item["all_lesson"])*100;
            $item["order_per"] = !empty($item["success_lesson"])?round($item["have_order"]/$item["success_lesson"],4)*100:0;
            E\Elevel::set_item_value_str($item,"level");
            E\Eidentity::set_item_value_str($item,"identity");
            E\Eteacher_money_type::set_item_value_str($item);
            $item["work_day"] = round((time()-$item["create_time"])/86400);
            $item["regular_stu_num"]=isset($teacher_regular_stu_list[$item["teacherid"]])?$teacher_regular_stu_list[$item["teacherid"]]:0;
            $item["test_lesson_num"]=isset($teacher_test_lesson_num_list[$item["teacherid"]])?$teacher_test_lesson_num_list[$item["teacherid"]]["test_lesson_count"]:0;
            $item["test_lesson_num_week"]=isset($teacher_test_lesson_num_list_week[$item["teacherid"]])?$teacher_test_lesson_num_list_week[$item["teacherid"]]["test_lesson_count"]:0;

        }
        if (!$order_in_db_flag) {
            \App\Helper\Utils::order_list( $ret_info["list"], $order_field_name, $order_type );
        }
        $all_item=["nick" => "全部"];
        \App\Helper\Utils::list_add_sum_item($ret_info["list"], $all_item,$sum_field_list );
        foreach($ret_info["list"] as &$item){
            if($item["nick"] == "全部"){
                $item["create_time_str"] = "";
                $item["teacher_money_type_str"] = "";
                $item["level_str"] = "";
                $item["identity_str"] = "";
                $item["work_day"] = "";
                if(count($ret_info["list"]) > 2){
                    $item["order_per"] = !empty($item["success_lesson"])?round($item["have_order"]/$item["success_lesson"],4)*100:0;
                }

                $item["school"]="";
            }
        }
        return $this->pageView(__METHOD__,$ret_info);
    }
    public function rejion_user_list() {
        //重复进入数据
        $origin_ex      = $this->get_in_str_val("origin_ex");
        list($start_time ,$end_time) = $this->get_in_date_range_day(0);
        $need_count = $this->get_in_int_val("need_count", 2 );
        $seller_student_status= $this->get_in_e_seller_student_status(-1);
        $page_num = $this->get_in_page_num();
        $this->t_seller_student_origin->switch_tongji_database();
        $ret_info= $this->t_seller_student_origin->get_rejoin_user_list($page_num ,$origin_ex, $start_time, $end_time,$need_count, $seller_student_status );
        foreach( $ret_info["list"] as &$item ) {
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"last_revisit_time");
            E\Egrade::set_item_value_str($item);
            E\Epad_type::set_item_value_str($item,"has_pad");
            //E\Esubject::set_item_value_str($item);
            //E\Eseller_student_status::set_item_value_str($item);
            $this->cache_set_item_account_nick($item,"admin_revisiterid","admin_revisiter_nick");
        }
        return $this->pageView(__METHOD__,$ret_info);

    }

    public function rejion_count_list() {
        //重复进入数据
        $origin_ex      = $this->get_in_str_val("origin_ex");
        list($start_time ,$end_time) = $this->get_in_date_range_day(0);

        $list= $this->t_seller_student_origin->get_rejoin_count_list($origin_ex, $start_time, $end_time);
        ksort($list);
        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($list) );
    }

    public function require_time_test_lesson_require_time_info() {
        list($start_time ,$end_time) = $this->get_in_date_range(0,-14);
        $list=$this->t_test_lesson_subject_require-> require_time_test_lesson_require_time_date_info($start_time,$end_time);
        //$date_list=\App\Helper\Common::get_date_time_list($start_time, $end_time-1);
        ksort($list);
        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($list) );

    }

    public function invalid_user_list () {
        list($start_time,$end_time)=$this->get_in_date_range( date("Y-m-d",time(NULL)-30*86500),date("Y-m-d",time(NULL)));
        $origin_ex           = $this->get_in_str_val("origin_ex");
        $list=$this->t_seller_student_new->tongji_invalid_count($start_time,$end_time,$origin_ex);
        foreach ($list as &$item) {
            E\Eseller_student_sub_status::set_item_value_str($item);
        }

        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($list) );

    }
    public function order_fail_list() {

        $cur_require_adminid=$this->get_in_int_val("cur_require_adminid",-1);
        list($start_time, $end_time)=$this->get_in_date_range(-30,0);
        $origin_userid_flag = $this->get_in_enum_val(E\Eboolean::class , -1,"origin_userid_flag" );
        $require_admin_type = $this->get_in_enum_val(E\Eaccount_role::class,-1,"require_admin_type");


        $list=$this->t_test_lesson_subject_require->tongji_get_order_fail($start_time, $end_time, $cur_require_adminid,$origin_userid_flag,$require_admin_type);

        foreach ($list as &$item) {
            E\Etest_lesson_order_fail_flag::set_item_value_str($item);
        }

        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($list) );

    }
    public function order_fail_seller_set() {

        list($start_time, $end_time)=$this->get_in_date_range(-30,0);
        $origin_userid_flag = $this->get_in_enum_val(E\Eboolean::class , -1,"origin_userid_flag" );


        $list=$this->t_test_lesson_subject_require-> tongji_order_fail_seller_set($start_time, $end_time, $origin_userid_flag);

        foreach ($list as &$item) {
            $this->cache_set_item_account_nick($item,"cur_require_adminid","cur_require_admin_nick");
        }
        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($list) );

    }




    public function get_test_info_for_dyy(){
        $page_num= $this->get_in_page_num();
        list($start_time,$end_time) = $this->get_in_date_range( date("2016-11-01"),date("2016-11-30") );
        $this->t_test_lesson_subject_require->switch_tongji_database();
        $ret_info = $this->t_test_lesson_subject_require->get_test_lesson_transfor_info_new($start_time,$end_time,$page_num);
        $res = $this->t_test_lesson_subject_require->get_test_succ_ass($start_time,$end_time);

        foreach($ret_info["list"] as &$item){
            foreach($res as $v){
                if(($item["account_role"] == 1 || $item["account_role"]==3) && $v["userid"] == $item["userid"] && $v["subject"]==$item["subject"] && $v["teacherid"]== $item["teacherid"] && $v["grade"] == $item["grade"]){
                    $item["is_order"]="是";
                }
            }
            if($item["order_time"]>0){
                $item["is_order"]="是";
            }
            \App\Helper\Utils::unixtime2date_for_item($item, "add_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "order_time");
            E\Egrade::set_item_value_str($item);
            $item["grade_str"]=$item["grade"]==100?"小学":($item["grade"]==200?"初中":($item["grade"]==300?"高中":$item["grade_str"]));
        }
        return $this->pageView(__METHOD__,$ret_info);
        #dd($ret_info);

    }

    public function get_test_lesson_low_tra_teacher(){
        $adminid      = $this->get_account_id();
        $right_list = $this->get_tea_subject_and_right_by_adminid($adminid);
        $tea_subject = $right_list["tea_subject"];
        $tea_right  = $right_list["tea_right"];
        $qz_flag = $right_list["qz_flag"];

        $subject = $this->get_in_int_val("subject",-1);
        $limit_plan_lesson_type = $this->get_in_int_val("limit_plan_lesson_type",-1);
        $is_record_flag = $this->get_in_int_val("is_record_flag",-1);
        $is_do_sth = $this->get_in_int_val("is_do_sth",-1);

        $day_num    = $this->get_avg_conversion_time();
        $start_time = time()-60*86400;
        $end_time   = time()-$day_num*86400;
        $wx_type = $this->get_in_int_val("wx_type",-1);
        $start_time_ex = $this->get_in_int_val("start_time_ex",0);
        $end_time_ex = $this->get_in_int_val("end_time_ex",0);
        //list($start_time_ex,$end_time_ex) = $this->get_in_date_range(0,0);

        $this->t_lesson_info->switch_tongji_database();
        $teacherid_list = $this->t_lesson_info->get_have_ten_test_lesson_teacher_list($start_time,$subject,$tea_subject,$end_time,$limit_plan_lesson_type,$is_record_flag,$is_do_sth,$wx_type,$start_time_ex,$end_time_ex,$qz_flag);
        $ret_info=[];
        foreach($teacherid_list as $item){
            $teacherid = $item["teacherid"];
            $date_week = \App\Helper\Utils::get_week_range(time(),1);
            $lstart = $date_week["sdate"];
            $lend = $date_week["edate"];
            $test_lesson_num_week = $this->t_lesson_info->get_limit_type_teacher_lesson_num($teacherid,$lstart,$lend);
            $test_lesson_num = $item["suc_count"]>=20?20: $item["suc_count"];
            $ret = $this->t_lesson_info->get_test_lesson_order_info_by_teacherid($teacherid,$start_time,$end_time,$test_lesson_num);
            $subject_str = E\Esubject::get_desc($item["subject"]);
            $identity_str = E\Eidentity::get_desc($item["identity"]);
            $realname = $item["realname"];
            $interview_access = $item["interview_access"];
            $level_str =  E\Elevel::get_desc($item["level"]);
            $start = date("Y-m-d",$ret[9]["lesson_start"]);
            $end = date("m-d",$end_time);
            $day = ceil((time()-$item["create_time"])/86400);
            $ss=0;
            $tt =0;
            foreach($ret as $v){
                /* $userid = $v["userid"];
                   $subject = $v['subject'];
                   $uu = $this->t_course_order->get_have_order_info($teacherid,$userid,$subject);
                   if($uu){
                   $tt ++;
                   }*/
                if($v["course_teacherid"] >0){
                    $tt ++;
                }

                if($v["orderid"]>0){
                    $ss++;
                }
            }
            /*if($ss <$tt){
              $ss = $tt;
              }*/
            if(($tt/$test_lesson_num) < 0.05){
                $ret_info[$teacherid]["teacherid"]=$teacherid;
                $ret_info[$teacherid]["realname"]=$realname;
                $ret_info[$teacherid]["identity_str"]=$identity_str;
                $ret_info[$teacherid]["subject_str"]=$subject_str;
                $ret_info[$teacherid]["school"]=$item["school"];
                $ret_info[$teacherid]["day"]=$day;
                $ret_info[$teacherid]["time"]=$start." - ".$end;
                $ret_info[$teacherid]["order_num"] = $tt;
                $ret_info[$teacherid]["level_str"] = $level_str;
                $ret_info[$teacherid]["interview_access"] = $interview_access;
                $ret_info[$teacherid]["test_lesson_num"] = $test_lesson_num;
                $ret_info[$teacherid]["limit_plan_lesson_type"] = $item["limit_plan_lesson_type"];
                $ret_info[$teacherid]["limit_plan_lesson_type_str"] = E\Elimit_plan_lesson_type::get_desc($item["limit_plan_lesson_type"]);
                $ret_info[$teacherid]["add_time"] = $item["add_time"];
                $ret_info[$teacherid]["add_time_str"] = date('Y-m-d',$item["add_time"]);
                $ret_info[$teacherid]["record_info"] = $item["record_info"];
                $ret_info[$teacherid]["test_lesson_num_week"] = $test_lesson_num_week;
                $ret_info[$teacherid]["limit_plan_lesson_account"] = $item["limit_plan_lesson_account"];
                $ret_info[$teacherid]["limit_plan_lesson_reason"] = $item["limit_plan_lesson_reason"];
                $ret_info[$teacherid]["limit_plan_lesson_time"] = $item["limit_plan_lesson_time"];
                $ret_info[$teacherid]["limit_plan_lesson_time_str"] = date("Y-m-d",$item["limit_plan_lesson_time"]);
                $ret_info[$teacherid]["acc"] = $item["acc"];
                if(!empty($item["freeze_adminid"])){
                    $ret_info[$teacherid]["freeze_adminid_str"] = $this->t_manager_info->get_account($item["freeze_adminid"]);
                }

                $not_grade_arr=explode(",",$item["not_grade"]);
                $not_grade_str="";
                if(!empty($not_grade_arr)){
                    foreach($not_grade_arr as $ss){
                        $not_grade_str  .= E\Egrade::get_desc($ss).",";
                    }
                }
                $ret_info[$teacherid]["not_grade_str"] = trim($not_grade_str,",");


            }

        }

        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($ret_info) ,["tea_right"=>$tea_right]);
    }

    public function get_ten_test_lesson_info(){
        $day_num=$this->get_avg_conversion_time();
        $start_time=time()-60*86400;
        $end_time = time()-$day_num*86400;

        $teacherid = $this->get_in_int_val("teacherid",-1);
        $test_lesson_num= $this->get_in_int_val("test_lesson_num",10);
        $this->t_lesson_info->switch_tongji_database();
        $this->t_book_revisit->switch_tongji_database();
        $this->t_course_order->switch_tongji_database();
        $ret_info = $this->t_lesson_info->get_test_lesson_order_info_by_teacherid_new($teacherid,$start_time,$end_time,$test_lesson_num);

        foreach($ret_info as &$item){
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            $item["grade_str"]=$item["grade"]==100?"小学":($item["grade"]==200?"初中":($item["grade"]==300?"高中":$item["grade_str"]));
            $item["lesson_start_str"]=date("Y-m-d H:i:s",$item["lesson_start"]);
            \App\Helper\Utils::unixtime2date_for_item($item, "stu_request_test_lesson_time");
            E\Eregion_version::set_item_value_str($item, "editionid");

            //E\Epad_type::set_item_value_str($item, "has_pad");
            E\Etest_lesson_level::set_item_value_str($item, "stu_test_lesson_level");


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
            $item["draw_url"] = \App\Helper\Common::get_url_ex($item["draw"]);
            $item["audio_url"] = \App\Helper\Common::get_url_ex($item["audio"]);

            $item["stu_test_paper_flag_str"] = \App\Helper\Common::get_test_pager_boolean_color_str(
                $item["stu_test_paper"], $item['tea_download_paper_time']
            );
            //$req_adminid = $item["req_adminid"];
            $phone = $item["phone"];
            $rev_info = $this->t_book_revisit->get_rev_info_by_phone_adminid($phone);
            $ss = "";
            foreach($rev_info as &$v){
                $v["revisit_time_str"] = date("Y-m-d H:i:s",$v["revisit_time"]);
                $ss .= "<p>".$v["revisit_time_str"].":".$v["operator_note"]."</p>";
            }
            $item["rev"]=$ss;
            $aa = $this->t_course_order->get_have_order_info($item["teacherid"],$item["userid"],$item["subject"]);
            if($aa >0){
                $item["have_order"] ="<font color=\"red\">已签</font>";
            }else{
                $item["have_order"] ="未签";
            }

        }
        return  $this->output_succ( [ "data" =>$ret_info] );

    }


    public function get_week_test_lesson_list(){
        $start_time = time()-14*86400;
        $end_time   = time();
        $teacherid  = $this->get_in_int_val("teacherid",-1);

        $this->t_lesson_info->switch_tongji_database();
        $list = $this->t_lesson_info->get_week_test_lesson_info_new($teacherid,$start_time,$end_time);
        foreach($list as &$item){
            $item["lesson_start_str"] = date("Y-m-d H:i:s",$item["lesson_start"]);
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
        }
        return $this->output_succ(["data"=> $list]);
    }

    public function get_suc_order_lesson_info(){
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");

        $adminid = $this->get_in_int_val("adminid",-1);
        $is_green_flag = $this->get_in_int_val("is_green_flag",-1);
        $require_admin_type = $this->get_in_int_val("require_admin_type",-1);
        //$list = $this->t_test_lesson_subject_require->get_teat_lesson_transfor_info_by_adminid($start_time,$end_time,$adminid,$is_green_flag,$require_admin_type);
        $list = $this->t_test_lesson_subject_require->get_tea_lesson_transfor_info_by_adminid_new($start_time,$end_time,$adminid,$is_green_flag,$require_admin_type);

        // $list = $this->t_test_lesson_subject_sub_list->get_teat_lesson_transfor_info_by_adminid($start_time,$end_time,$adminid);

        foreach($list as &$item){
            $item["lesson_start_str"] = date("Y-m-d H:i:s",$item["lesson_start"]);
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
        }
        return $this->output_succ(["data"=> $list]);
    }

    public function get_suc_seller_top_info(){
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");

        $adminid = $this->get_in_int_val("adminid",-1);

        $list = $this->t_test_lesson_subject_require->get_seller_top_lesson_suc_list($start_time,$end_time,$adminid);


        foreach($list as &$item){
            $item["lesson_start_str"] = date("Y-m-d H:i:s",$item["lesson_start"]);
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            E\Eseller_student_status::set_item_value_str($item,"test_lesson_student_status");
        }
        return $this->output_succ(["data"=> $list]);


    }

    public function teacher_lecture_tongji_day(){
        $start_time = strtotime("2017-01-01");
        $end_time = time();
        $ret_info = $this->t_teacher_lecture_info->get_lecture_info_by_day($start_time,$end_time);
        $arr = $this->t_teacher_record_list->get_train_teacher_interview_info_by_day($start_time,$end_time);

        $lecture_succ = $this->t_teacher_lecture_info->get_lecture_info_by_day($start_time,$end_time,1);
        $train_succ = $this->t_teacher_record_list->get_train_teacher_interview_info_by_day($start_time,$end_time,1);

        foreach($arr as $k=>$val){
            if(isset($ret_info[$k])){
                $ret_info[$k]["all_count"] += $val["all_count"];
                $ret_info[$k]["all_num"] += $val["all_num"];
            }else{
                $ret_info[$k]= $val;
            }
        }
        foreach($ret_info as $k=>&$item){
            @$item["succ"] += @$lecture_succ[$k]["all_count"];
            @$item["succ"] += @$train_succ[$k]["all_count"];
        }
        $ret_list = \App\Helper\Utils::list_to_page_info($ret_info);

        return $this->pageView(__METHOD__,$ret_list);

        //dd($ret_info);


    }

    public function interview_subject_grade_tongji_zs(){
        return $this->interview_subject_grade_tongji();
    }

    public function interview_subject_grade_tongji(){
        list($start_time,$end_time)=$this->get_in_date_range(date('2017-06-18'), date('Y-m-d H:i:s',time()));
        $tongji_type=$this->get_in_int_val("tongji_type",1);
        if($tongji_type==1){
            $res = $this->t_teacher_lecture_info->get_lecture_info_by_subject_new($start_time,$end_time);
            $video_succ = $this->t_teacher_lecture_info->get_lecture_info_by_subject_new($start_time,$end_time,1);
            $one = $this->t_teacher_record_list->get_all_interview_count_by_subject($start_time,$end_time,-1);
            $one_succ = $this->t_teacher_record_list->get_all_interview_count_by_subject($start_time,$end_time,1);
            $all=0;$suc=0;
            foreach($one as $k=>$val){
                if(isset($res[$k])){
                    $res[$k]["all_count"] +=$val["all_count"];
                    $res[$k]["all_num"] +=$val["all_num"];
                }else{
                    $res[$k]=$val;
                }
            }
            foreach($res as $key=>&$item){
                @$item["succ"] +=$video_succ[$key]["all_count"];
                @$item["succ"] +=$one_succ[$key]["all_count"];
                E\Esubject::set_item_value_str($item,"subject");
            }

        }else{
            $res = $this->t_teacher_lecture_info->get_lecture_info_by_grade($start_time,$end_time);
            $video_succ = $this->t_teacher_lecture_info->get_lecture_info_by_grade($start_time,$end_time,1);
            $one = $this->t_teacher_record_list->get_all_interview_count_by_grade($start_time,$end_time,-1);
            $one_succ = $this->t_teacher_record_list->get_all_interview_count_by_grade($start_time,$end_time,1);
            foreach($one as $k=>$val){
                if(isset($res[$k])){
                    $res[$k]["all_count"] +=$val["all_count"];
                    $res[$k]["all_num"] +=$val["all_num"];
                }else{
                    $res[$k]=$val;
                }
            }
            foreach($res as $key=>&$item){
                @$item["succ"] +=$video_succ[$key]["all_count"];
                @$item["succ"] +=$one_succ[$key]["all_count"];
                E\Egrade::set_item_value_str($item,"grade_ex");
            }

        }


        $ret_list = \App\Helper\Utils::list_to_page_info($res);

        return $this->pageView(__METHOD__,$ret_list,["tongji_type"=>$tongji_type]);

    }

    public function teacher_interview_info_tongji_zs(){
        return $this->teacher_interview_info_tongji();
    }
    public function teacher_interview_info_tongji(){
        $sum_field_list = [
            "all_num",
            "all_count",
            "real_all",
            "real_num",
            "suc_count",
            "pass_per",
            "all_pass_per",
            "ave_time",
            "all_lesson",
            "have_order",
            "order_per"
        ];
        $order_field_arr = array_merge(["all_count" ] ,$sum_field_list );

        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str($order_field_arr ,"all_count desc");

        list($start_time,$end_time)=$this->get_in_date_range(date('Y-m-d',time()-7*86400), date('Y-m-d H:i:s',time()));
        $adminid     = $this->get_account_id();
        $right_list  = $this->get_tea_subject_and_right_by_adminid($adminid);
        $tea_subject = $right_list["tea_subject"];
        $tea_right   = $right_list["tea_right"];
        $qz_flag     = $right_list["qz_flag"];
        if($adminid==329){
            $tea_subject="";
        }

        $this->switch_tongji_database();
        $subject             = $this->get_in_int_val("subject",-1);
        $teacher_account     = $this->get_in_int_val('teacher_account', -1);
        $reference_teacherid = $this->get_in_int_val('reference_teacherid',-1);
        $identity            = $this->get_in_int_val('identity', -1);
        $interview_type      = $this->get_in_int_val('interview_type', -1);

        if($interview_type==1){
            $ret_info = $this->t_teacher_lecture_info->get_lecture_info_by_time_new(
                $subject,$start_time,$end_time,$teacher_account,$reference_teacherid,$identity,$tea_subject);
            $real_info = $this->t_teacher_lecture_info->get_lecture_info_by_time_new(
                $subject,$start_time,$end_time,$teacher_account,$reference_teacherid,$identity,$tea_subject,-2);

        }elseif($interview_type==2){
            $ret_info = $this->t_teacher_record_list->get_train_teacher_interview_info(
                $subject,$start_time,$end_time,$teacher_account,$reference_teacherid,$identity,$tea_subject);
            $real_info = $this->t_teacher_record_list->get_train_teacher_interview_info(
                $subject,$start_time,$end_time,$teacher_account,$reference_teacherid,$identity,$tea_subject,-2);

        }else{
            $ret_info = $this->t_teacher_lecture_info->get_lecture_info_by_time_new(
                $subject,$start_time,$end_time,$teacher_account,$reference_teacherid,$identity,$tea_subject);
            $arr = $this->t_teacher_record_list->get_train_teacher_interview_info(
                $subject,$start_time,$end_time,$teacher_account,$reference_teacherid,$identity,$tea_subject);
            $real_info = $this->t_teacher_lecture_info->get_lecture_info_by_time_new(
                $subject,$start_time,$end_time,$teacher_account,$reference_teacherid,$identity,$tea_subject,-2);
            $real_arr = $this->t_teacher_record_list->get_train_teacher_interview_info(
                $subject,$start_time,$end_time,$teacher_account,$reference_teacherid,$identity,$tea_subject,-2);

            foreach($arr["list"] as $k=>$val){
                if(isset($ret_info["list"][$k])){
                    $ret_info["list"][$k]["all_count"] += $val["all_count"];
                    $ret_info["list"][$k]["all_num"] += $val["all_num"];
                }else{
                    $ret_info["list"][$k]= $val;
                }
            }
            foreach($real_arr["list"] as $p=>$pp){
                if(isset($real_info["list"][$p])){
                    $real_info["list"][$p]["all_count"] += $pp["all_count"];
                    $real_info["list"][$p]["all_num"] += $pp["all_num"];
                }else{
                    $real_info["list"][$p]= $pp;
                }

            }
        }

        //dd($real_info);
        $all_con_time =0;
        $all_add_time=0;
        foreach($ret_info["list"] as &$item){
            $item["ave_time"] = (isset($item["all_count_new"]) && !empty($item["all_count_new"]))?round(($item["all_con_time"]-$item["all_add_time"])/$item["all_count_new"]/86400,1):0;
            $all_con_time+=$item["all_con_time"];
            $all_add_time+=$item["all_add_time"];
            $item["real_num"] = isset($real_info["list"][$item["account"]])?$real_info["list"][$item["account"]]["all_count"]:0;
            $item["real_all"] = isset($real_info["list"][$item["account"]])?$real_info["list"][$item["account"]]["all_num"]:0;
            $account = $item["account"];
            if($interview_type==1){
                $teacher_list = $this->t_teacher_lecture_info->get_teacher_list_passed($account,$start_time,$end_time,$subject,$teacher_account,$reference_teacherid,$identity,$tea_subject);
            }elseif($interview_type==2){
                $teacher_list = $this->t_teacher_record_list->get_teacher_train_passed($account,$start_time,$end_time,$subject,$teacher_account,$reference_teacherid,$identity,$tea_subject);
            }else{
                $teacher_list = $this->t_teacher_lecture_info->get_teacher_list_passed($account,$start_time,$end_time,$subject,$teacher_account,$reference_teacherid,$identity,$tea_subject);
                $teacher_arr = $this->t_teacher_record_list->get_teacher_train_passed($account,$start_time,$end_time,$subject,$teacher_account,$reference_teacherid,$identity,$tea_subject);
                foreach($teacher_arr as $k=>$val){
                    if(!isset($teacher_list[$k])){
                        $teacher_list[$k]=$k;
                    }
                }

            }
            //$item["teacher_list"] = $teacher_list;
            $item["suc_count"] = count($teacher_list);
            $item["pass_per"] = !empty($item["real_num"])?(round($item["suc_count"]/$item["real_num"],2))*100:0;
            $item["all_pass_per"] = !empty($item["real_num"])?(round($item["suc_count"]/$item["real_all"],2))*100:0;
            $res = $this->t_lesson_info->get_test_leson_info_by_teacher_list($teacher_list);
            $item["all_lesson"] = $res["all_lesson"];
            $item["have_order"] = $res["have_order"];
            $item["order_per"] =  $item["all_lesson"]==0?0:((round($item["have_order"]/$item["all_lesson"],2))*100);
        }
        // dd($ret_info);

        if (!$order_in_db_flag) {
            \App\Helper\Utils::order_list( $ret_info["list"], $order_field_name, $order_type );
        }
        $all_item=["account" => "全部"];
        \App\Helper\Utils::list_add_sum_item($ret_info["list"], $all_item,$sum_field_list );



        if($interview_type==1){
            $teacher_list_ex = $this->t_teacher_lecture_info->get_teacher_list_passed("",$start_time,$end_time,$subject,$teacher_account,$reference_teacherid,$identity,$tea_subject);
            $video_all =  $this->t_teacher_lecture_info->get_lecture_info_by_all(
                $subject,$start_time,$end_time,$teacher_account,$reference_teacherid,$identity,$tea_subject);
            $video_real =  $this->t_teacher_lecture_info->get_lecture_info_by_all(
                $subject,$start_time,$end_time,$teacher_account,$reference_teacherid,$identity,$tea_subject,-2);

        }elseif($interview_type==2){
            $teacher_list_ex = $this->t_teacher_record_list->get_teacher_train_passed("",$start_time,$end_time,$subject,$teacher_account,$reference_teacherid,$identity,$tea_subject);
            $video_all = $this->t_teacher_record_list->get_train_teacher_interview_info_all(
                $subject,$start_time,$end_time,$teacher_account,$reference_teacherid,$identity,$tea_subject);
            $video_real = $this->t_teacher_record_list->get_train_teacher_interview_info_all(
                $subject,$start_time,$end_time,$teacher_account,$reference_teacherid,$identity,$tea_subject,-2);

        }else{
            $teacher_list_ex = $this->t_teacher_lecture_info->get_teacher_list_passed("",$start_time,$end_time,$subject,$teacher_account,$reference_teacherid,$identity,$tea_subject);
            $teacher_arr_ex = $this->t_teacher_record_list->get_teacher_train_passed("",$start_time,$end_time,$subject,$teacher_account,$reference_teacherid,$identity,$tea_subject);
            foreach($teacher_arr_ex as $k=>$val){
                if(!isset($teacher_list_ex[$k])){
                    $teacher_list_ex[$k]=$k;
                }
            }
            $video_all =  $this->t_teacher_lecture_info->get_lecture_info_by_all(
                $subject,$start_time,$end_time,$teacher_account,$reference_teacherid,$identity,$tea_subject);
            $video_real =  $this->t_teacher_lecture_info->get_lecture_info_by_all(
                $subject,$start_time,$end_time,$teacher_account,$reference_teacherid,$identity,$tea_subject,-2);

            $one_all = $this->t_teacher_record_list->get_train_teacher_interview_info_all(
                $subject,$start_time,$end_time,$teacher_account,$reference_teacherid,$identity,$tea_subject);
            $one_real = $this->t_teacher_record_list->get_train_teacher_interview_info_all(
                $subject,$start_time,$end_time,$teacher_account,$reference_teacherid,$identity,$tea_subject,-2);
            @$video_all["all_num"] += $one_all["all_num"];
            @$video_all["all_count"] += $one_all["all_count"];
            @$video_real["all_num"] += $one_real["all_num"];
            @$video_real["all_count"] += $one_real["all_count"];

        }
        $all_tea_ex = count($teacher_list_ex);

        foreach($ret_info["list"] as &$item){
            if($item["account"]=="全部"){
                $item["all_num"] = $video_all["all_num"];
                $item["all_count"] = $video_all["all_count"];
                $item["real_num"] = $video_real["all_count"];
                $item["real_all"] = $video_real["all_num"];
                $item["pass_per"] = @$item["real_num"]==0?0:(round($all_tea_ex/@$item["real_num"],2))*100;
                $item["ave_time"] = @$item["all_count"]==0?0:round(($all_con_time-$all_add_time)/@$item["all_count"]/86400,1);
                $item["order_per"] =@$item["all_lesson"]==0?0:(round(@$item["have_order"]/@$item["all_lesson"],2))*100;
                $item["all_pass_per"] = (isset($item["real_num"]) && !empty($item["real_num"]))?(round( @$item["suc_count"]/$item["real_all"],2))*100:0;
            }
        }


        return $this->pageView(__METHOD__,$ret_info,["all_tea"=>$all_tea_ex]);
    }

    public function teacher_interview_info_tongji_by_reference_zs(){
        return $this->teacher_interview_info_tongji_by_reference();
    }

    public function teacher_interview_info_tongji_by_reference(){
        $sum_field_list=[
            "all_count",
            "suc_count",
            "pass_per",
            "ave_time",
            "all_lesson",
            "have_order",
            "order_per"
        ];
        $order_field_arr = array_merge(["all_count" ] ,$sum_field_list );

        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str($order_field_arr ,"all_count desc");

        list($start_time,$end_time)=$this->get_in_date_range(date('Y-m-d H:i:s',time()-7*86400), date('Y-m-d H:i:s',time()));
        $adminid      = $this->get_account_id();
        $right_list = $this->get_tea_subject_and_right_by_adminid($adminid);
        $tea_subject = $right_list["tea_subject"];
        $tea_right  = $right_list["tea_right"];
        $qz_flag = $right_list["qz_flag"];
        if($adminid==329){
            $tea_subject="";
        }

        $this->t_teacher_lecture_info->switch_tongji_database();
        $this->t_lesson_info->switch_tongji_database();

        $subject = $this->get_in_int_val("subject",-1);
        $teacher_account            = $this->get_in_int_val('teacher_account', -1);
        $reference_teacherid        = $this->get_in_int_val('reference_teacherid',-1);
        $identity                   = $this->get_in_int_val('identity', -1);
        $ret_info = $this->t_teacher_lecture_info->get_lecture_info_by_reference_new($subject,$start_time,$end_time,$teacher_account,$reference_teacherid,$identity,$tea_subject);
        $all_con_time =0;
        $all_add_time=0;
        //$other=[];
        //$other["realname"]="其他";
        foreach($ret_info["list"] as &$item){
            $item["pass_per"] = (round($item["suc_count"]/$item["all_count"],2))*100;
            $item["ave_time"] = round(($item["all_con_time"]-$item["all_add_time"])/$item["all_count"]/86400,1);
            $all_con_time+=$item["all_con_time"];
            $all_add_time+=$item["all_add_time"];
            $reference = $item["reference"];
            if($reference == -1){
                $item["reference"]="无渠道";
                $teacher_list = $this->t_teacher_lecture_info->get_teacher_list_passed_by_reference_is_null($start_time,$end_time);
                $res = $this->t_lesson_info->get_test_leson_info_by_teacher_list_new(-1,$teacher_list);
                $item["all_lesson"] = $res["all_lesson"];
                $item["have_order"] = $res["have_order"];
                $item["order_per"] =  $item["all_lesson"]==0?0:((round($item["have_order"]/$item["all_lesson"],2))*100);
            }else{
                $teacher_list = $this->t_teacher_lecture_info->get_teacher_list_passed_by_reference($reference,$start_time,$end_time);
                $res = $this->t_lesson_info->get_test_leson_info_by_teacher_list_new($reference,$teacher_list);
                $item["all_lesson"] = $res["all_lesson"];
                $item["have_order"] = $res["have_order"];
                $item["order_per"] =  $item["all_lesson"]==0?0:((round($item["have_order"]/$item["all_lesson"],2))*100);

            }
            /* if($item["realname"]=="" && $item["nick"]==""){
               @$other["all_count"] += $item["all_count"];
               @$other["suc_count"] += $item["suc_count"];
               @$other["all_con_time"] += $item["all_con_time"];
               @$other["all_add_time"] += $item["all_add_time"];
               @$other["all_lesson"] += $item["all_lesson"];
               @$other["have_order"] += $item["have_order"];
               }*/
        }
        if (!$order_in_db_flag) {
            \App\Helper\Utils::order_list( $ret_info["list"], $order_field_name, $order_type );
        }
        $all_item=["reference" => "全部"];
        \App\Helper\Utils::list_add_sum_item($ret_info["list"], $all_item,$sum_field_list );
        foreach($ret_info["list"] as $k=>&$item){
            if($item["reference"]=="全部"){
                $item["pass_per"] = @$item["all_count"]==0?0:(round(@$item["suc_count"]/@$item["all_count"],2))*100;
                $item["ave_time"] = @$item["all_count"]==0?0:round(($all_con_time-$all_add_time)/@$item["all_count"]/86400,1);
                $item["order_per"] =@$item["all_lesson"]==0?0:(round(@$item["have_order"]/@$item["all_lesson"],2))*100;
            }
            /* if($item["realname"]=="" && $item["nick"]==""){
               unset($ret_info["list"][$k]);
               }*/


        }
        /* $other["pass_per"] = @$other["all_count"]==0?0:(round(@$other["suc_count"]/@$other["all_count"],2))*100;
           $other["ave_time"] = @$other["all_count"]==0?0:round(($other["all_con_time"]-$other["all_add_time"])/@$item["all_count"]/86400,1);
           $other["order_per"] =@$other["all_lesson"]==0?0:(round(@$other["have_order"]/@$other["all_lesson"],2))*100;


           array_push($ret_info["list"],$other);*/

        return $this->pageView(__METHOD__,$ret_info);
    }



    private function get_avg_conversion_time($time=0,$decimal_point=0){
        if($time==0){
            $time=time();
        }
        $this->t_lesson_info->switch_tongji_database();

        $start_time_ave = $time-30*86400;
        $res = $this->t_lesson_info->get_all_test_order_info_by_time($start_time_ave);
        $num = 0;
        $arr = 0;
        foreach($res as $item){
            if($item["orderid"]>0 && $item["order_time"]>0 && $item["lesson_start"]>0){
                $num++;
                $arr += ($item["order_time"]-$item["lesson_start"]);
            }
        }

        if($num!=0){
            $day_num = round($arr/$num/86400,$decimal_point);
        }else{
            $day_num = 0;
        }
        return $day_num;
    }

    public function stu_lesson_total_list(){
        list($start_time, $end_time)=$this->get_in_date_range(0,0,0,[],1);

        $select_type   = $this->get_in_int_val("select_type",0);
        $student_type  = $this->get_in_int_val("student_type",0);
        $renewal_rate  = ($this->get_in_float_val("renewal_rate",85))/100;
        $month_cost    = $this->get_in_float_val("month_cost",12);
        $month_cost_ex = $this->get_in_float_val("month_cost_ex",12);
        $order_str     = $this->get_in_int_val("order_str",0);

        $order_str  = $order_str==0?SORT_ASC:SORT_DESC;

        $this->t_lesson_info->switch_tongji_database();
        $this->t_order_info->switch_tongji_database();
        $this->t_student_info->switch_tongji_database();
        $this->t_course_order->switch_tongji_database();

        $check_start_time = $this->get_check_start_time($student_type,$end_time);
        if($select_type==0){
            $this->t_order_info->switch_tongji_database();
            $stu_total_list = $this->t_order_info->get_stu_lesson_total_list($end_time);
        }else{
            $this->t_course_order->switch_tongji_database();
            $stu_total_list = $this->t_course_order->get_stu_assigned_list($check_start_time,$end_time,$student_type);
        }
        $this->t_lesson_info->switch_tongji_database();
        $stu_cost_list = $this->t_lesson_info->get_stu_cost_list($check_start_time,$end_time,$select_type,$student_type);

        $now_month = date("n",$start_time);
        if(in_array($now_month,[7,8])){
            $final_month_cost = $month_cost_ex;
        }else{
            $final_month_cost = $month_cost;
        }

        $list=[];
        $time_count_list=[];
        foreach($stu_cost_list as $cost_val){
            if($select_type==0){
                $total_key   = $cost_val['userid'];
                $subject_str = "";
            }else{
                $total_key   = $cost_val['userid']."_".$cost_val['subject'];
                $subject_str = E\Esubject::get_desc($cost_val['subject']);
            }

            $lesson_cost   = $cost_val['lesson_cost']/100;
            $lesson_total  = $stu_total_list[$total_key]['lesson_total']/100;
            $lesson_left   = $lesson_total-$lesson_cost;
            $renewal_day   = round($lesson_left/$final_month_cost*30);
            $renewal_time  = strtotime("+".$renewal_day."day",$cost_val['lesson_end']);
            $renewal_date  = date("Y-m-d",$renewal_time);
            $renewal_month = date("Y-m",$renewal_time);

            if($lesson_left>0 || $cost_val['type']!=0){
                $list[$total_key]['nick']         = $cost_val['nick'];
                $list[$total_key]['userid']       = $cost_val['userid'];
                $list[$total_key]['subject_str']  = $subject_str;
                $list[$total_key]['lesson_total'] = $lesson_total;
                $list[$total_key]['lesson_cost']  = $lesson_cost;
                $list[$total_key]['lesson_left']  = $lesson_left;
                $list[$total_key]['renewal_day']  = $renewal_date;
                if(!isset($time_count_list[$renewal_month])){
                    $time_count_list[$renewal_month]=0;
                }
                $time_count_list[$renewal_month]++;
                $sort_list[$total_key] = $list[$total_key]["renewal_day"];
            }
        }
        if(isset($sort_list) && is_array($sort_list)){
            array_multisort($sort_list,$order_str,$list);
        }
        if(!empty($time_count_list)){
            foreach($time_count_list as &$count){
                $count=round($count*$renewal_rate);
            }
            ksort($time_count_list);
        }

        $ret_list = \App\Helper\Utils::list_to_page_info($list);

        return $this->pageView(__METHOD__,$ret_list,[
            "time_count_list" => $time_count_list,
            "list_count"      => count($list),
        ]);
    }

    private function get_check_start_time($student_type,$end_time){
        if($student_type==0){
            $check_str = "-1 month";
        }elseif($student_type==2){
            $check_str = "-2 month";
        }elseif($student_type==3){
            $check_str = "-3 month";
        }
        $time = strtotime($check_str,$end_time);
        return $time;
    }

    public function teacher_trial_lesson_list(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);

        $identity       = $this->get_in_int_val("identity");
        $subject        = $this->get_in_int_val("subject");
        $is_new_teacher = $this->get_in_int_val("is_new_teacher",1);
        $count_type     = $this->get_in_int_val("count_type",1);

        $adminid      = $this->get_account_id();
        $right_list = $this->get_tea_subject_and_right_by_adminid($adminid);
        $tea_subject = $right_list["tea_subject"];
        $tea_right  = $right_list["tea_right"];
        $qz_flag = $right_list["qz_flag"];


        $this->t_lesson_info->switch_tongji_database();
        $this->t_course_order->switch_tongji_database();
        $this->t_order_info->switch_tongji_database();
        $this->t_teacher_info->switch_tongji_database();
        $this->t_test_lesson_subject_sub_list->switch_tongji_database();

        $teacher_list = $this->t_lesson_info->get_teacher_trial_lesson_list(
            $start_time,$end_time,$identity,$subject,$is_new_teacher,$tea_subject,$count_type
        );

        foreach($teacher_list as &$tea_val){
            if($tea_val['teacherid']>0){
                E\Elevel::set_item_value_str($tea_val);
                E\Esubject::set_item_value_str($tea_val);
                E\Eteacher_money_type::set_item_value_str($tea_val);
            }
        }

        $ret_list= \App\Helper\Utils::list_to_page_info($teacher_list);
        return $this->pageView(__METHOD__,$ret_list,[
            "teacher_count" => count($teacher_list)
        ]);
    }

    public function get_teacher_trial_rate(){
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time   = strtotime($this->get_in_str_val("end_time"));
        $count_type = $this->get_in_int_val("count_type");

        $teacherid = $this->get_in_int_val("teacherid",0);
        if($teacherid==0){
            return $this->output_err("老师id不能为0");
        }

        $ret_list= $this->t_lesson_info->get_teacher_trial_lesson_list($start_time,$end_time,0,0,0,"",$count_type,$teacherid);

        if($ret_list){

            if($ret_list['all_lesson']>0){
                $ret_list['order_per'] = round($ret_list['order_number']/$ret_list['all_lesson'],4)*100;
            }
            $ret_list['lesson_time'] = date("Y-m-d H:i",$ret_list["min_lesson_start"])."~"
                                     .date("Y-m-d H:i",$ret_list["max_lesson_start"]);
        }else{
            return $this->output_err("没有签单数据!");
        }

        return $this->output_succ([
            "data" => $ret_list
        ]);

    }

    public function teacher_first_test_lesson_week_zj(){
        return $this->teacher_first_test_lesson_week();
    }
    public function teacher_first_test_lesson_week(){
        $adminid      = $this->get_account_id();
        $right_list = $this->get_tea_subject_and_right_by_adminid($adminid);
        $tea_subject = $right_list["tea_subject"];
        $tea_right  = $right_list["tea_right"];
        $qz_flag = $right_list["qz_flag"];
        //沈怡菁老师权限
        if($adminid==486){
            $tea_subject="(4)";
        }
        $this->t_lesson_info->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range(-7,0);
        $subject        = $this->get_in_int_val("subject",-1);
        $record_flag       = $this->get_in_int_val("record_flag",0);
        $record_adminid      = $this->get_in_int_val("record_adminid",-1);
        $ret = $this->t_lesson_info->get_all_first_lesson_teacher($start_time,$end_time,$subject,$tea_subject);
        $teacherid_arr = [];
        foreach($ret as $val){
            $teacherid = $val["teacherid"];
            $teacherid_arr[$teacherid] = $teacherid;
        }
        $ret_info = $this->t_lesson_info->get_teacher_first_test_lesson_info($teacherid_arr,-1,$record_flag,$record_adminid);
        foreach($ret_info["list"] as &$item){
            if(!empty($item["create_time"])){
                $item["create_time_str"] = date("Y-m-d",$item["create_time"]);
            }else{
                $item["create_time_str"] ="";
            }
            E\Elevel::set_item_value_str($item,"level");
            E\Eidentity::set_item_value_str($item,"identity");
            E\Esubject::set_item_value_str($item,"subject");
            E\Egrade_part_ex::set_item_value_str($item,"grade_part_ex");
            $item["day"] = ceil((time()-$item["create_time"])/86400);
            $item["first_lesson_time"] = $ret[$item["teacherid"]]["lesson_start"];
            $item["first_lesson_time_str"] = date("Y-m-d H:i:s",$item["first_lesson_time"]);
            if($item["add_time"]>0){
                $item["add_time_str"] = date("Y-m-d H:i:s",$item["add_time"]);
                $item["record_time"] = round(($item["add_time"]- $item["first_lesson_time"])/86400,1);
            }

        }

        //dd($ret_info);
        return $this->pageView(__METHOD__,$ret_info);



    }

    public function get_first_test_lesson_info(){
        $teacherid = $this->get_in_int_val("teacherid",-1);
        $this->t_lesson_info->switch_tongji_database();
        $ret_info = $this->t_lesson_info->get_first_test_lesson_order_info_by_teacherid_new($teacherid);

        foreach($ret_info as &$item){
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            $item["grade_str"]=$item["grade"]==100?"小学":($item["grade"]==200?"初中":($item["grade"]==300?"高中":$item["grade_str"]));
            $item["lesson_start_str"]=date("Y-m-d H:i:s",$item["lesson_start"]);
            \App\Helper\Utils::unixtime2date_for_item($item, "stu_request_test_lesson_time");
            E\Eregion_version::set_item_value_str($item, "editionid");

            //E\Epad_type::set_item_value_str($item, "has_pad");
            E\Etest_lesson_level::set_item_value_str($item, "stu_test_lesson_level");


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
            $item["draw_url"] = \App\Helper\Common::get_url_ex($item["draw"]);
            $item["audio_url"] = \App\Helper\Common::get_url_ex($item["audio"]);

            $item["stu_test_paper_flag_str"] = \App\Helper\Common::get_test_pager_boolean_color_str(
                $item["stu_test_paper"], $item['tea_download_paper_time']
            );
            //$req_adminid = $item["req_adminid"];
            $phone = $item["phone"];
            $rev_info = $this->t_book_revisit->get_rev_info_by_phone_adminid($phone);
            $ss = "";
            foreach($rev_info as &$v){
                $v["revisit_time_str"] = date("Y-m-d H:i:s",$v["revisit_time"]);
                $ss .= "<p>".$v["revisit_time_str"].":".$v["operator_note"]."</p>";
            }
            $item["rev"]=$ss;
            $aa = $this->t_course_order->get_have_order_info($item["teacherid"],$item["userid"],$item["subject"]);
            if($aa >0){
                $item["have_order"] ="<font color=\"red\">已签</font>";
            }else{
                $item["have_order"] ="未签";
            }

        }
        return  $this->output_succ( [ "data" =>$ret_info] );

    }

    public function get_lecture_video_info(){
        $accept_adminid = $this->get_in_int_val("accept_adminid");
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");
        $this->t_teacher_lecture_info->switch_tongji_database();

        $ret_info = $this->t_teacher_lecture_info->get_lecture_video_info_by_accept_adminid($start_time,$end_time,$accept_adminid);
        foreach($ret_info as &$item){
            E\Esubject::set_item_value_str($item,"subject");
        }
        return  $this->output_succ( [ "data" =>$ret_info] );
    }

    public function get_lecture_all_video_info(){
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");
        $this->t_teacher_lecture_info->switch_tongji_database();

        $ret_info = $this->t_teacher_lecture_info->get_lecture_video_info_by_accept_adminid($start_time,$end_time,-1);
        foreach($ret_info as &$item){
            E\Esubject::set_item_value_str($item,"subject");
        }
        return  $this->output_succ( [ "data" =>$ret_info] );
    }


    public function get_lecture_suc_info(){
        $accept_adminid = $this->get_in_int_val("accept_adminid");
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");
        $this->t_teacher_lecture_info->switch_tongji_database();

        $ret_info = $this->t_teacher_lecture_info->get_lecture_suc_info_by_accept_adminid($start_time,$end_time,$accept_adminid);
        foreach($ret_info as &$item){
            E\Esubject::set_item_value_str($item,"subject");
        }
        return  $this->output_succ( [ "data" =>$ret_info] );
    }

    public function get_lecture_all_suc_info(){
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");
        $this->t_teacher_lecture_info->switch_tongji_database();

        $ret_info = $this->t_teacher_lecture_info->get_lecture_suc_info_by_accept_adminid($start_time,$end_time,-1);
        foreach($ret_info as &$item){
            E\Esubject::set_item_value_str($item,"subject");
        }
        return  $this->output_succ( [ "data" =>$ret_info] );
    }


    public function tongji_zs_teacher_info(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);
        $month_test_lesson_num = $this->get_in_int_val("month_test_lesson_num",22);
        $month = intval(date("m",$start_time));
        $month_except_arr = [2=>1666,3=>2866,4=>3933,5=>5200,6=>6666,7=>6666,8=>6666,9=>6666,10=>6666,11=>6666,12=>6666];
        $except_test_num = @$month_except_arr[$month];
        $except_test_lesson_num = $this->get_in_int_val("except_test_lesson_num",$except_test_num);
        //$month_start = strtotime(date("Y-m-01",time()));
        $last_month_start = strtotime(date("Y-m-01",$start_time-300));
        $last_month = intval(date("m", $last_month_start));
        $this->t_lesson_info->switch_tongji_database();
        $this->t_teacher_lecture_info->switch_tongji_database();


        $all_test_lesson_info = $this->t_lesson_info->get_all_test_lesson_info_by_subject_and_grade($last_month_start,$start_time);
        $subject_grade_arr=[];
        foreach($all_test_lesson_info as $item){
            $subject = $item["subject"];
            $grade = intval($item["grade"]/100)*100;
            $k = $grade."-".$subject;
            @$subject_grade_arr[$k]++;
            @$subject_grade_arr[$grade]++;
            @$subject_grade_arr[$subject]++;
            @$subject_grade_arr["all"]++;
        }
        $subject_grade_arr["xxyw_per"] = round(@$subject_grade_arr["100-1"]/@$subject_grade_arr[1],2)*100;
        $subject_grade_arr["xxsx_per"] = round(@$subject_grade_arr["100-2"]/@$subject_grade_arr[2],2)*100;
        $subject_grade_arr["xxyy_per"] = round(@$subject_grade_arr["100-3"]/@$subject_grade_arr[3],2)*100;
        $subject_grade_arr["xxhx_per"] = round(@$subject_grade_arr["100-4"]/@$subject_grade_arr[4],2)*100;
        $subject_grade_arr["xxwl_per"] = round(@$subject_grade_arr["100-5"]/@$subject_grade_arr[5],2)*100;
        $subject_grade_arr["czyw_per"] = round(@$subject_grade_arr["200-1"]/@$subject_grade_arr[1],2)*100;
        $subject_grade_arr["czsx_per"] = round(@$subject_grade_arr["200-2"]/@$subject_grade_arr[2],2)*100;
        $subject_grade_arr["czyy_per"] = round(@$subject_grade_arr["200-3"]/@$subject_grade_arr[3],2)*100;
        $subject_grade_arr["czhx_per"] = round(@$subject_grade_arr["200-4"]/@$subject_grade_arr[4],2)*100;
        $subject_grade_arr["czwl_per"] = round(@$subject_grade_arr["200-5"]/@$subject_grade_arr[5],2)*100;
        $subject_grade_arr["gzyw_per"] = round(@$subject_grade_arr["300-1"]/@$subject_grade_arr[1],2)*100;
        $subject_grade_arr["gzsx_per"] = round(@$subject_grade_arr["300-2"]/@$subject_grade_arr[2],2)*100;
        $subject_grade_arr["gzyy_per"] = round(@$subject_grade_arr["300-3"]/@$subject_grade_arr[3],2)*100;
        $subject_grade_arr["gzhx_per"] = round(@$subject_grade_arr["300-4"]/@$subject_grade_arr[4],2)*100;
        $subject_grade_arr["gzwl_per"] = round(@$subject_grade_arr["300-5"]/@$subject_grade_arr[5],2)*100;
        $subject_grade_arr["yw_per"] = round(@$subject_grade_arr[1]/@$subject_grade_arr["all"],2)*100;
        $subject_grade_arr["sx_per"] = round(@$subject_grade_arr[2]/@$subject_grade_arr["all"],2)*100;
        $subject_grade_arr["yy_per"] = round(@$subject_grade_arr[3]/@$subject_grade_arr["all"],2)*100;
        $subject_grade_arr["hx_per"] = round(@$subject_grade_arr[4]/@$subject_grade_arr["all"],2)*100;
        $subject_grade_arr["wl_per"] = round(@$subject_grade_arr[5]/@$subject_grade_arr["all"],2)*100;
        $month_need_teacher_num = ceil($except_test_lesson_num/$month_test_lesson_num);
        $month_except_detail_info =[];
        $month_except_detail_info[1][100] =  ceil($month_need_teacher_num*$subject_grade_arr["yw_per"]*$subject_grade_arr["xxyw_per"]/10000);
        $month_except_detail_info[1][200] =  ceil($month_need_teacher_num*$subject_grade_arr["yw_per"]*$subject_grade_arr["czyw_per"]/10000);
        $month_except_detail_info[1][300] =  ceil($month_need_teacher_num*$subject_grade_arr["yw_per"]*$subject_grade_arr["gzyw_per"]/10000);
        $month_except_detail_info[2][100] =  ceil($month_need_teacher_num*$subject_grade_arr["sx_per"]*$subject_grade_arr["xxsx_per"]/10000);
        $month_except_detail_info[2][200] =  ceil($month_need_teacher_num*$subject_grade_arr["sx_per"]*$subject_grade_arr["czsx_per"]/10000);
        $month_except_detail_info[2][300] =  ceil($month_need_teacher_num*$subject_grade_arr["sx_per"]*$subject_grade_arr["gzsx_per"]/10000);
        $month_except_detail_info[3][100] =  ceil($month_need_teacher_num*$subject_grade_arr["yy_per"]*$subject_grade_arr["xxyy_per"]/10000);
        $month_except_detail_info[3][200] =  ceil($month_need_teacher_num*$subject_grade_arr["yy_per"]*$subject_grade_arr["czyy_per"]/10000);
        $month_except_detail_info[3][300] =  ceil($month_need_teacher_num*$subject_grade_arr["yy_per"]*$subject_grade_arr["gzyy_per"]/10000);
        $month_except_detail_info[4][100] =  ceil($month_need_teacher_num*$subject_grade_arr["hx_per"]*$subject_grade_arr["xxhx_per"]/10000);
        $month_except_detail_info[4][200] =  ceil($month_need_teacher_num*$subject_grade_arr["hx_per"]*$subject_grade_arr["czhx_per"]/10000);
        $month_except_detail_info[4][300] =  ceil($month_need_teacher_num*$subject_grade_arr["hx_per"]*$subject_grade_arr["gzhx_per"]/10000);
        $month_except_detail_info[5][100] =  ceil($month_need_teacher_num*$subject_grade_arr["wl_per"]*$subject_grade_arr["xxwl_per"]/10000);
        $month_except_detail_info[5][200] =  ceil($month_need_teacher_num*$subject_grade_arr["wl_per"]*$subject_grade_arr["czwl_per"]/10000);
        $month_except_detail_info[5][300] =  ceil($month_need_teacher_num*$subject_grade_arr["wl_per"]*$subject_grade_arr["gzwl_per"]/10000);
        $lecture_info= $this->t_teacher_lecture_info->get_teacher_lecture_info_by_time_list_new($last_month_start,$start_time);
        $lecture_subject_grade = [];
        foreach($lecture_info as $val){
            $subject = $val["subject"];
            $grade = intval($val["grade"]/100)*100;
            @$lecture_subject_grade[$subject][$grade]++;
        }





        return $this->pageView(__METHOD__,null,[
            "subject_grade_arr"        => $subject_grade_arr,
            "last_month"               => $last_month,
            "month"                    => $month,
            "month_except_detail_info" => $month_except_detail_info,
            "lecture_subject_grade"    => $lecture_subject_grade
        ]);
    }

    public function get_interview_test_lesson_info(){
        $adminid = $this->get_in_int_val("adminid");
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");
        $list = $this->t_teacher_lecture_info->get_interview_test_lesson_info($start_time,$end_time,$adminid);
        foreach($list as &$item){
            $item["order_per"] = !empty($item["person_num"])?round($item["order_num"]/$item["person_num"],4)*100:0;
        }
        return  $this->output_succ( [ "data" =>$list] );

    }

    public function tongji_teaching_and_research_teacher_test_lesson_info(){
        $this->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);
        $tea_arr  = $this->t_manager_info->get_research_teacher_list(4);
        $tea_arr[]=57701;//邓惠元老师
        $list = $this->t_research_teacher_rerward_list->tongji_week_teacher_order_turn_info($start_time,$end_time);
        $first_reward_list = $this->t_research_teacher_rerward_list->tongji_research_teacher_first_reward($start_time,$end_time);
        $order_num_list = $this->t_teacher_lecture_info->get_research_teacher_test_lesson_info($start_time,$end_time,$tea_arr);
        foreach($order_num_list as &$ww){
             $ww["order_per"] = !empty($ww["person_num"])?round($ww["order_num"]/$ww["person_num"],4)*100:0;
             $ww["reward"] = @$list[$ww["uid"]]["reward_count"];
             $ww["first_reward"] = @$first_reward_list[$ww["uid"]]["first_reward_count"];
             $ww["first_reward_num"] = @$first_reward_list[$ww["uid"]]["first_reward_num"];
             $ww["reward_num"] = @$list[$ww["uid"]]["num"];
             if($start_time>= strtotime(date("2017-05-01")) && $ww["order_num"]>=10){
                 if($ww["order_per"]>=35){
                     $ww["order_reward"] = 500;
                 }else if($ww["order_per"]>=25){
                     $ww["order_reward"] = 300;
                 }elseif($ww["order_per"]>=15){
                     $ww["order_reward"] = 100;
                 }else{
                     $ww["order_reward"] = "";
                 }
             }else{
                 $ww["order_reward"] = "";
             }

         }

         //  dd($order_num_list);
         $account_id = $this->get_account_id();
         $list_subject = $this->get_admin_tea_subject_and_arr($account_id);
         $tea_subject = $list_subject["tea_subject"];
         $subject_grade_arr= $list_subject["subject_grade_arr"];

         //检测是否组长
         $master_flag = $list_subject["master_flag"];
         $this->set_in_value("master_flag",$master_flag);
         $master_flag = $this->get_in_int_val("master_flag");

         foreach($subject_grade_arr as $k=>&$item){
                 $res = $this->t_lesson_info->get_research_test_lesson_info_list($item["subject"],$item["grade"],$start_time,$end_time);
                 $item["all_lesson"] = $res["all_lesson"];
                 $item["order_num"] = $res["order_num"];
                 $item["order_per"] = !empty($res["all_lesson"])?round($res["order_num"]/$res["all_lesson"],4)*100:0;
         }
         \App\Helper\Utils::order_list( $subject_grade_arr,"subject", 1);

         $subject_order_per_list = $this->t_lesson_info->get_subject_order_list_new($start_time,$end_time);
         $zh_subject_new=$zh_subject_lesson=$wz_subject_new=$wz_subject_lesson=0;
         foreach($subject_order_per_list as $k=>&$item){
             if($item["subject"]>3){
                 @$zh_subject_new +=$item["order_num"];
                 @$zh_subject_lesson +=$item["all_lesson"];
                 unset($subject_order_per_list[$k]);
             }
             if($item["subject"]==1 || $item["subject"]==3 ){
                 @$wz_subject_new +=$item["order_num"];
                 @$wz_subject_lesson +=$item["all_lesson"];
                 unset($subject_order_per_list[$k]);
             }

             E\Esubject::set_item_value_str($item,"subject");
             $item["order_per"] = !empty($item["all_lesson"])?round($item["order_num"]/$item["all_lesson"],4)*100:0;
         }

         $zh_order_per = !empty($zh_subject_lesson)?round($zh_subject_new/$zh_subject_lesson,4)*100:0;
         $wz_order_per = !empty($wz_subject_lesson)?round($wz_subject_new/$wz_subject_lesson,4)*100:0;
         $subject_order_per_list["-2"]=["order_num"=>$zh_subject_new,"subject_str"=>"综合学科","all_lesson"=>$zh_subject_lesson,"order_per"=>$zh_order_per,"subject"=>"-2"];
         $subject_order_per_list["-3"]=[
             "order_num"   => $wz_subject_new,
             "subject_str" => "文科组",
             "all_lesson"  => $wz_subject_lesson,
             "order_per"   => $wz_order_per,
             "subject"    => "-3"
         ];


         $subject_order_per_list_sub1 = $this->t_lesson_info->get_subject_order_list_new2($start_time,$end_time);
         $zh_subject_new=$zh_subject_lesson=$wz_subject_new=$wz_subject_lesson=0;
         foreach($subject_order_per_list_sub1 as $k=>&$item){
             if($item["subject"]>3){
                 @$zh_subject_new +=$item["order_num"];
                 @$zh_subject_lesson +=$item["all_lesson"];
                 unset($subject_order_per_list_sub1[$k]);
             }
             if($item["subject"]==1 || $item["subject"]==3 ){
                 @$wz_subject_new +=$item["order_num"];
                 @$wz_subject_lesson +=$item["all_lesson"];
                 unset($subject_order_per_list_sub1[$k]);
             }

             E\Esubject::set_item_value_str($item,"subject");
             $item["order_per"] = !empty($item["all_lesson"])?round($item["order_num"]/$item["all_lesson"],4)*100:0;
         }

         $zh_order_per = !empty($zh_subject_lesson)?round($zh_subject_new/$zh_subject_lesson,4)*100:0;
         $wz_order_per = !empty($wz_subject_lesson)?round($wz_subject_new/$wz_subject_lesson,4)*100:0;
         $subject_order_per_list_sub1["-2"]=[
             "order_num"   => $zh_subject_new,
             "subject_str" => "综合学科",
             "all_lesson"  => $zh_subject_lesson,
             "order_per"   => $zh_order_per,
             "subject"    => "-2"
         ];
         $subject_order_per_list_sub1["-3"]=[
             "order_num"   => $wz_subject_new,
             "subject_str" => "文科组",
             "all_lesson"  => $wz_subject_lesson,
             "order_per"   => $wz_order_per,
             "subject"    => "-3"
         ];


         $subject_order_per_list_sub2 = $this->t_lesson_info->get_subject_order_list_new3($start_time,$end_time);
         $zh_subject_new=$zh_subject_lesson=$wz_subject_new=$wz_subject_lesson=0;
         foreach($subject_order_per_list_sub2 as $k=>&$item){
             if($item["subject"]>3){
                 @$zh_subject_new    += $item["order_num"];
                 @$zh_subject_lesson += $item["all_lesson"];
                 unset($subject_order_per_list_sub2[$k]);
             }
             if($item["subject"]==1 || $item["subject"]==3 ){
                 @$wz_subject_new +=$item["order_num"];
                 @$wz_subject_lesson +=$item["all_lesson"];
                 unset($subject_order_per_list_sub2[$k]);
             }

             E\Esubject::set_item_value_str($item,"subject");
             $item["order_per"] = !empty($item["all_lesson"])?round($item["order_num"]/$item["all_lesson"],4)*100:0;
         }

         $zh_order_per = !empty($zh_subject_lesson)?round($zh_subject_new/$zh_subject_lesson,4)*100:0;
         $wz_order_per = !empty($wz_subject_lesson)?round($wz_subject_new/$wz_subject_lesson,4)*100:0;
         $subject_order_per_list_sub2["-2"]=[
             "order_num"   => $zh_subject_new,
             "subject_str" => "综合学科",
             "all_lesson"  => $zh_subject_lesson,
             "order_per"   => $zh_order_per,
             "subject"     => "-2"
         ];
         $subject_order_per_list_sub2["-3"]=[
             "order_num"   => $wz_subject_new,
             "subject_str" => "文科组",
             "all_lesson"  => $wz_subject_lesson,
             "order_per"   => $wz_order_per,
             "subject"    => "-3"
         ];

         foreach($subject_order_per_list as $k=>&$item){
             $item["kk_num"]  = @$subject_order_per_list_sub1[$k]["order_num"];
             $item["hls_num"] = @$subject_order_per_list_sub2[$k]["order_num"];
             $item["kk_per"]  = @$subject_order_per_list_sub1[$k]["order_per"];
             $item["hls_per"] = @$subject_order_per_list_sub2[$k]["order_per"];
         }

         $qz_tea_arr   = $this->t_manager_info->get_research_teacher_list(5);
         $qz_tea_list  = $this->t_lesson_info->get_qz_test_lesson_info_list($qz_tea_arr,$start_time,$end_time);
         $qz_order_num = $qz_all_lesson=0;
         foreach($qz_tea_list as &$val){
             if($tea_subject==-3 || $tea_subject==0){
                 $val["order_per"]=!empty($val["all_lesson"])?round($val["order_num"]/$val["all_lesson"],4)*100:0;
                 // $subject_grade_arr[] = $val;
             }
             $qz_order_num +=$val["order_num"];
             $qz_all_lesson +=$val["all_lesson"];
         }
         $qz_tea_list_kk = $this->t_lesson_info->get_qz_test_lesson_info_list2($qz_tea_arr,$start_time,$end_time);
         $qz_kk_num = $qz_kk_lesson=0;
         foreach($qz_tea_list_kk as &$val){
             $qz_kk_num +=$val["order_num"];
             $qz_kk_lesson +=$val["all_lesson"];
         }
         $qz_tea_list_hls = $this->t_lesson_info->get_qz_test_lesson_info_list3($qz_tea_arr,$start_time,$end_time);
         $qz_hls_num = $qz_hls_lesson=0;
         foreach($qz_tea_list_hls as &$val){
             $qz_hls_num +=$val["order_num"];
             $qz_hls_lesson +=$val["all_lesson"];
         }
         $qz_order_per = !empty($qz_all_lesson)?round($qz_order_num/$qz_all_lesson,4)*100:0;
         $qz_kk_per = !empty($qz_kk_lesson)?round($qz_kk_num/$qz_kk_lesson,4)*100:0;
         $qz_hls_per = !empty($qz_hls_lesson)?round($qz_hls_num/$qz_hls_lesson,4)*100:0;
         // $subject_order_per_list[]=["order_num"=>$qz_order_num,"subject_str"=>"全职老师","all_lesson"=>$qz_all_lesson,"order_per"=>$qz_order_per,"kk_num"=>$qz_kk_num,"kk_per"=>$qz_kk_per,"hls_num"=>$qz_hls_num,"hls_per"=>$qz_hls_per];
         \App\Helper\Utils::order_list( $subject_order_per_list,"order_per", 0);
         $order_reward_list = $order_num_list;
         /*  foreach($order_reward_list as $o=>$n){
             if($n["teacherid"]==130462){
                 unset($order_reward_list[$o]);
             }
             }*/
         \App\Helper\Utils::order_list($order_reward_list,"reward", 0);
         \App\Helper\Utils::order_list( $order_num_list,"order_per", 0);
         $person_kpi = $this->t_research_teacher_kpi_info->get_month_kpi_info(1,$start_time);
         \App\Helper\Utils::order_list( $person_kpi,"total_score", 0);
         $subject_kpi = $this->t_research_teacher_kpi_info->get_month_kpi_info(2,$start_time);
         \App\Helper\Utils::order_list( $subject_kpi,"total_score", 0);
         return $this->pageView(__METHOD__,null,[
             "subject_grade_arr"        => $subject_grade_arr,
             "order_num_list"           => $order_num_list,
             "order_reward_list"        => $order_reward_list,
             "subject_order_per_list"   => $subject_order_per_list,
             "person_kpi"               => $person_kpi,
             "subject_kpi"              => $subject_kpi,
         ]);

    }




    public function tongji_teaching_and_research_teacher_test_lesson_info_zj(){
         $this->t_lesson_info->switch_tongji_database();
         $this->t_research_teacher_rerward_list->switch_tongji_database();
         $this->t_teacher_lecture_info->switch_tongji_database();
         list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);
         $tea_arr  = $this->t_manager_info->get_research_teacher_list(4);
         $tea_arr[]=57701;//邓惠元老师
         $list = $this->t_research_teacher_rerward_list->tongji_week_teacher_order_turn_info($start_time,$end_time);
         $first_reward_list = $this->t_research_teacher_rerward_list->tongji_research_teacher_first_reward($start_time,$end_time);
         $order_num_list = $this->t_teacher_lecture_info->get_research_teacher_test_lesson_info($start_time,$end_time,$tea_arr);
         foreach($order_num_list as &$ww){
             $ww["order_per"] = !empty($ww["person_num"])?round($ww["order_num"]/$ww["person_num"],4)*100:0;
             $ww["reward"] = @$list[$ww["uid"]]["reward_count"];
             $ww["first_reward"] = @$first_reward_list[$ww["uid"]]["first_reward_count"];
             $ww["first_reward_num"] = @$first_reward_list[$ww["uid"]]["first_reward_num"];
             $ww["reward_num"] = @$list[$ww["uid"]]["num"];
             if($start_time>= strtotime(date("2017-05-01")) && $ww["order_num"]>=10){
                 if($ww["order_per"]>=35){
                     $ww["order_reward"] = 500;
                 }else if($ww["order_per"]>=25){
                     $ww["order_reward"] = 300;
                 }elseif($ww["order_per"]>=15){
                     $ww["order_reward"] = 100;
                 }else{
                     $ww["order_reward"] = "";
                 }
             }else{
                 $ww["order_reward"] = "";
             }

         }

         //  dd($order_num_list);
         $account_id = $this->get_account_id();
         $list_subject = $this->get_admin_tea_subject_and_arr($account_id);
         $tea_subject = $list_subject["tea_subject"];
         $subject_grade_arr= $list_subject["subject_grade_arr"];

         //检测是否组长
         $master_flag = $list_subject["master_flag"];
         $this->set_in_value("master_flag",$master_flag);
         $master_flag = $this->get_in_int_val("master_flag");

         foreach($subject_grade_arr as $k=>&$item){
                 $res = $this->t_lesson_info->get_research_test_lesson_info_list($item["subject"],$item["grade"],$start_time,$end_time);
                 $item["all_lesson"] = $res["all_lesson"];
                 $item["order_num"] = $res["order_num"];
                 $item["order_per"] = !empty($res["all_lesson"])?round($res["order_num"]/$res["all_lesson"],4)*100:0;
         }
         \App\Helper\Utils::order_list( $subject_grade_arr,"subject", 1);

         $subject_order_per_list = $this->t_lesson_info->get_subject_order_list_new($start_time,$end_time);
         $zh_subject_new=$zh_subject_lesson=$wz_subject_new=$wz_subject_lesson=0;
         foreach($subject_order_per_list as $k=>&$item){
             if($item["subject"]>3){
                 @$zh_subject_new +=$item["order_num"];
                 @$zh_subject_lesson +=$item["all_lesson"];
                 unset($subject_order_per_list[$k]);
             }
             if($item["subject"]==1 || $item["subject"]==3 ){
                 @$wz_subject_new +=$item["order_num"];
                 @$wz_subject_lesson +=$item["all_lesson"];
                 unset($subject_order_per_list[$k]);
             }

             E\Esubject::set_item_value_str($item,"subject");
             $item["order_per"] = !empty($item["all_lesson"])?round($item["order_num"]/$item["all_lesson"],4)*100:0;
         }

         $zh_order_per = !empty($zh_subject_lesson)?round($zh_subject_new/$zh_subject_lesson,4)*100:0;
         $wz_order_per = !empty($wz_subject_lesson)?round($wz_subject_new/$wz_subject_lesson,4)*100:0;
         $subject_order_per_list["-2"]=["order_num"=>$zh_subject_new,"subject_str"=>"综合学科","all_lesson"=>$zh_subject_lesson,"order_per"=>$zh_order_per,"subject"=>"-2"];
         $subject_order_per_list["-3"]=[
             "order_num"   => $wz_subject_new,
             "subject_str" => "文科组",
             "all_lesson"  => $wz_subject_lesson,
             "order_per"   => $wz_order_per,
             "subject"    => "-3"
         ];


         $subject_order_per_list_sub1 = $this->t_lesson_info->get_subject_order_list_new2($start_time,$end_time);
         $zh_subject_new=$zh_subject_lesson=$wz_subject_new=$wz_subject_lesson=0;
         foreach($subject_order_per_list_sub1 as $k=>&$item){
             if($item["subject"]>3){
                 @$zh_subject_new +=$item["order_num"];
                 @$zh_subject_lesson +=$item["all_lesson"];
                 unset($subject_order_per_list_sub1[$k]);
             }
             if($item["subject"]==1 || $item["subject"]==3 ){
                 @$wz_subject_new +=$item["order_num"];
                 @$wz_subject_lesson +=$item["all_lesson"];
                 unset($subject_order_per_list_sub1[$k]);
             }

             E\Esubject::set_item_value_str($item,"subject");
             $item["order_per"] = !empty($item["all_lesson"])?round($item["order_num"]/$item["all_lesson"],4)*100:0;
         }

         $zh_order_per = !empty($zh_subject_lesson)?round($zh_subject_new/$zh_subject_lesson,4)*100:0;
         $wz_order_per = !empty($wz_subject_lesson)?round($wz_subject_new/$wz_subject_lesson,4)*100:0;
         $subject_order_per_list_sub1["-2"]=[
             "order_num"   => $zh_subject_new,
             "subject_str" => "综合学科",
             "all_lesson"  => $zh_subject_lesson,
             "order_per"   => $zh_order_per,
             "subject"    => "-2"
         ];
         $subject_order_per_list_sub1["-3"]=[
             "order_num"   => $wz_subject_new,
             "subject_str" => "文科组",
             "all_lesson"  => $wz_subject_lesson,
             "order_per"   => $wz_order_per,
             "subject"    => "-3"
         ];


         $subject_order_per_list_sub2 = $this->t_lesson_info->get_subject_order_list_new3($start_time,$end_time);
         $zh_subject_new=$zh_subject_lesson=$wz_subject_new=$wz_subject_lesson=0;
         foreach($subject_order_per_list_sub2 as $k=>&$item){
             if($item["subject"]>3){
                 @$zh_subject_new    += $item["order_num"];
                 @$zh_subject_lesson += $item["all_lesson"];
                 unset($subject_order_per_list_sub2[$k]);
             }
             if($item["subject"]==1 || $item["subject"]==3 ){
                 @$wz_subject_new +=$item["order_num"];
                 @$wz_subject_lesson +=$item["all_lesson"];
                 unset($subject_order_per_list_sub2[$k]);
             }

             E\Esubject::set_item_value_str($item,"subject");
             $item["order_per"] = !empty($item["all_lesson"])?round($item["order_num"]/$item["all_lesson"],4)*100:0;
         }

         $zh_order_per = !empty($zh_subject_lesson)?round($zh_subject_new/$zh_subject_lesson,4)*100:0;
         $wz_order_per = !empty($wz_subject_lesson)?round($wz_subject_new/$wz_subject_lesson,4)*100:0;
         $subject_order_per_list_sub2["-2"]=[
             "order_num"   => $zh_subject_new,
             "subject_str" => "综合学科",
             "all_lesson"  => $zh_subject_lesson,
             "order_per"   => $zh_order_per,
             "subject"     => "-2"
         ];
         $subject_order_per_list_sub2["-3"]=[
             "order_num"   => $wz_subject_new,
             "subject_str" => "文科组",
             "all_lesson"  => $wz_subject_lesson,
             "order_per"   => $wz_order_per,
             "subject"    => "-3"
         ];

         foreach($subject_order_per_list as $k=>&$item){
             $item["kk_num"]  = @$subject_order_per_list_sub1[$k]["order_num"];
             $item["hls_num"] = @$subject_order_per_list_sub2[$k]["order_num"];
             $item["kk_per"]  = @$subject_order_per_list_sub1[$k]["order_per"];
             $item["hls_per"] = @$subject_order_per_list_sub2[$k]["order_per"];
         }

         $qz_tea_arr   = $this->t_manager_info->get_research_teacher_list(5);
         $qz_tea_list  = $this->t_lesson_info->get_qz_test_lesson_info_list($qz_tea_arr,$start_time,$end_time);
         $qz_order_num = $qz_all_lesson=0;
         foreach($qz_tea_list as &$val){
             if($tea_subject==-3 || $tea_subject==0){
                 $val["order_per"]=!empty($val["all_lesson"])?round($val["order_num"]/$val["all_lesson"],4)*100:0;
                 // $subject_grade_arr[] = $val;
             }
             $qz_order_num +=$val["order_num"];
             $qz_all_lesson +=$val["all_lesson"];
         }
         $qz_tea_list_kk = $this->t_lesson_info->get_qz_test_lesson_info_list2($qz_tea_arr,$start_time,$end_time);
         $qz_kk_num = $qz_kk_lesson=0;
         foreach($qz_tea_list_kk as &$val){
             $qz_kk_num +=$val["order_num"];
             $qz_kk_lesson +=$val["all_lesson"];
         }
         $qz_tea_list_hls = $this->t_lesson_info->get_qz_test_lesson_info_list3($qz_tea_arr,$start_time,$end_time);
         $qz_hls_num = $qz_hls_lesson=0;
         foreach($qz_tea_list_hls as &$val){
             $qz_hls_num +=$val["order_num"];
             $qz_hls_lesson +=$val["all_lesson"];
         }
         $qz_order_per = !empty($qz_all_lesson)?round($qz_order_num/$qz_all_lesson,4)*100:0;
         $qz_kk_per = !empty($qz_kk_lesson)?round($qz_kk_num/$qz_kk_lesson,4)*100:0;
         $qz_hls_per = !empty($qz_hls_lesson)?round($qz_hls_num/$qz_hls_lesson,4)*100:0;
         // $subject_order_per_list[]=["order_num"=>$qz_order_num,"subject_str"=>"全职老师","all_lesson"=>$qz_all_lesson,"order_per"=>$qz_order_per,"kk_num"=>$qz_kk_num,"kk_per"=>$qz_kk_per,"hls_num"=>$qz_hls_num,"hls_per"=>$qz_hls_per];
         \App\Helper\Utils::order_list( $subject_order_per_list,"order_per", 0);
         $order_reward_list = $order_num_list;
         /*  foreach($order_reward_list as $o=>$n){
             if($n["teacherid"]==130462){
                 unset($order_reward_list[$o]);
             }
             }*/
         \App\Helper\Utils::order_list($order_reward_list,"reward", 0);
         \App\Helper\Utils::order_list( $order_num_list,"order_per", 0);
         $person_kpi = $this->t_research_teacher_kpi_info->get_month_kpi_info(1,$start_time);
         \App\Helper\Utils::order_list( $person_kpi,"total_score", 0);
         $subject_kpi = $this->t_research_teacher_kpi_info->get_month_kpi_info(2,$start_time);
         \App\Helper\Utils::order_list( $subject_kpi,"total_score", 0);
         return $this->pageView(__METHOD__,null,[
             "subject_grade_arr"        => $subject_grade_arr,
             "order_num_list"           => $order_num_list,
             "order_reward_list"        => $order_reward_list,
             "subject_order_per_list"   => $subject_order_per_list,
             "person_kpi"               => $person_kpi,
             "subject_kpi"              => $subject_kpi,
         ]);

    }






    public function get_research_teacher_grade_info(){
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");
        $subject = $this->get_in_int_val("subject",-1);
        $grade = $this->get_in_int_val("grade",-1);
        $this->t_lesson_info->switch_tongji_database();
        $data = $this->t_lesson_info->get_research_test_lesson_info_list_by_grade($subject,$grade,$start_time,$end_time);

        foreach($data as &$item){
            E\Egrade::set_item_value_str($item,"grade");
            $item["per"] = !empty($item["all_lesson"])?round($item["order_num"]/$item["all_lesson"],4)*100:0;
        }
        return  $this->output_succ( [ "data" =>$data] );

    }

    public function get_subject_order_per_info(){
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");
        $list = $this->t_lesson_info->get_subject_order_list_new($start_time,$end_time);
        foreach($list as $k=>&$item){
             E\Esubject::set_item_value_str($item,"subject");
             if($item["subject"] !=1 && $item["subject"] != 3){
                 unset($list[$k]);
             }
             $item["order_per"] = !empty($item["all_lesson"])?round($item["order_num"]/$item["all_lesson"],4)*100:0;

        }
        return  $this->output_succ( [ "data" =>$list] );

    }

    public function get_research_teacher_reward_info(){
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");
        $adminid = $this->get_in_int_val("adminid",-1);
        $list = $this->t_research_teacher_rerward_list->tongji_research_teacher_rerward_info($start_time,$end_time,$adminid);
        return  $this->output_succ( [ "data" =>$list] );
    }

    public function get_research_teacher_first_reward_info(){
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");
        $adminid = $this->get_in_int_val("adminid",-1);
        $list = $this->t_research_teacher_rerward_list->tongji_research_teacher_first_rerward_info($start_time,$end_time,$adminid);
        return  $this->output_succ( [ "data" =>$list] );
    }

    public function get_ass_stu_kk_suc_info(){
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");
        $adminid = $this->get_in_int_val("adminid",-1);
        $data= $this->t_test_lesson_subject->get_ass_kk_tongji_info_detail_new($start_time,$end_time,$adminid);
        foreach($data as &$item){
            E\Esubject::set_item_value_str($item,"subject");
            $item["time"] = date("Y-m-d H:i:s",$item["lesson_start"]);
        }
        $ass_info = $this->t_month_ass_student_info->get_ass_month_info($start_time);
        $list=@$ass_info[$adminid];
        $list["kk_all"] =$list["kk_num"]+$list["hand_kk_num"];


        return  $this->output_succ( [ "data" =>$data,"list"=>$list] );

    }

    public function new_teacher_test_lesson_info(){
        $page_num = $this->get_in_page_num();
        //list($start_time,$end_time) = $this->get_in_date_range(-7,0);
        list($start_time,$end_time, $opt_date_str)=$this->get_in_date_range(
            -7,0, 1, [
                1 => array("lesson_start","试听课时间"),
            ]
        );
        $have_test_lesson_flag = $this->get_in_int_val("have_test_lesson_flag",-1);
        $this->t_teacher_lecture_info->switch_tongji_database();
        $this->t_lesson_info->switch_tongji_database();

        $subject = $this->get_in_int_val("subject",-1);
        $grade_part_ex = $this->get_in_int_val("grade_part_ex",-1);
        $train_through_new = $this->get_in_int_val("train_through_new",-1);
        $all_teacher_info = $this->t_lesson_info->get_all_teacher_test_lesson_info($start_time,$end_time,$grade_part_ex,$subject,$train_through_new);
        foreach($all_teacher_info as $v){
            @$all_tea++;
            @$all_lesson +=$v["all_lesson"];
        }

        //$ret_info = $this->t_teacher_lecture_info->get_new_teacher_no_test_lesson_info($grade_part_ex,$subject,$train_through_new);
        // $all_count = count($ret_info["list"]);
        $all_count = $this->t_teacher_lecture_info->get_new_teacher_no_test_lesson_info_total($grade_part_ex,$subject,$train_through_new);;

        $ret = $this->t_teacher_lecture_info->get_new_teacher_test_lesson_info($start_time,$end_time,$grade_part_ex,$subject,$train_through_new);
        $tea_arr = [];
        foreach($ret as $k=>$v){
            $tea_arr[] = $k;
        }
        $have_lesson_count = count($ret);
        $no_lesson_count = $all_count-$have_lesson_count;
        $have_per = (round($have_lesson_count/$all_count,4)*100)."%";
        $ret_info = $this->t_teacher_lecture_info->get_new_teacher_no_test_lesson_info($page_num,$grade_part_ex,$subject,$train_through_new,$have_test_lesson_flag,$tea_arr);

        foreach($ret_info["list"] as $k=>&$item){
            if($item["train_through_new_time"] !=0){
                $item["work_day"] = ceil((time()-$item["train_through_new_time"])/86400)."天";
            }else{
                $item["work_day"] ="";
            }
            E\Esubject::set_item_value_str($item,"subject");
            E\Egrade_part_ex::set_item_value_str($item,"grade_part_ex");
            $item["order_num"] = @$ret[$item["teacherid"]]["order_num"];
            $item["all_lesson"] = @$ret[$item["teacherid"]]["all_lesson"];
            $item["per"] = !empty($item["all_lesson"])?round($item["order_num"]/$item["all_lesson"],4)*100:0;
            $first_time  = $this->t_lesson_info->get_first_test_lesson_by_teacher($item["teacherid"]);
            if($first_time>0){
                $item["first_time"] = $first_time;
                $item["first_time_str"] = date("Y-m-d H:i:s",$item["first_time"]);
            }else{
                $item["first_time"]=$item["first_time_str"]="";
            }
            if($item["confirm_time"] >0 && $item["first_time"]>0){
                $item["range_time"] = round(($item["first_time"]-$item["confirm_time"])/86400,1);
            }
            $item["confirm_time_str"] = date("Y-m-d H:i:s",$item["confirm_time"]);

        }
        $arr=["realname"=>"总计","all_lesson"=>0,"order_num"=>0];
        $tea_num = 0;$tea_day=0;$range_time_num=0;$range_time_all=0;
        if($have_test_lesson_flag!=0){
            foreach($ret as $val){
                @$arr["all_lesson"] +=$val["all_lesson"];
                @$arr["order_num"] +=$val["order_num"];
            }
        }
        $time_tongji = $this->t_teacher_lecture_info->get_new_teacher_no_test_lesson_info_tongji($grade_part_ex,$subject,$train_through_new,$have_test_lesson_flag,$tea_arr);
        foreach($time_tongji as $val){
            if($val["confirm_time"] >0 && $val["lesson_start"]>0){
                $range_time = round(($val["lesson_start"]-$val["confirm_time"])/86400,1);
            }
            if(@$range_time>0){
                $range_time_num ++;
                $range_time_all +=$range_time;
            }

        }
        $num_tongji = $this->t_teacher_lecture_info->get_new_teacher_no_test_lesson_info_tongji_num($grade_part_ex,$subject,$train_through_new,$have_test_lesson_flag,$tea_arr);
        foreach($num_tongji as $val){
            if($val["train_through_new_time"] !=0){
                $work_day = ceil((time()-$val["train_through_new_time"])/86400)."天";
            }else{
                $work_day ="";
            }
            if($work_day>0){
                $tea_num++;
            }
            $tea_day +=$work_day;
            if($val["train_through_new"]==1){
                @$train_through_count ++;
            }


        }
        $all_interview_count = $have_lesson_count+$no_lesson_count;
        $arr["per"] = !empty($arr["all_lesson"])?round($arr["order_num"]/$arr["all_lesson"],4)*100:0;
        $arr["range_time"] = !empty($range_time_num)?round($range_time_all/$range_time_num,1):0;
        $arr["work_day"] = !empty($tea_num)?(ceil($tea_day/$tea_num))."天":"";
        // array_unshift($ret_info["list"],$arr);

        $tea_per = isset($all_tea)?(round($have_lesson_count/$all_tea,4)*100)."%":"";
        $lesson_per = isset($all_lesson)?(round($arr["all_lesson"]/$all_lesson,4)*100)."%":"";

        return $this->pageView(__METHOD__,$ret_info,[
            "have_lesson_count"   =>$have_lesson_count,
            "no_lesson_count"     =>$no_lesson_count,
            "have_per"            =>$have_per,
            "tea_per"             =>$tea_per,
            "lesson_per"          =>$lesson_per,
            "all_tea"             =>@$all_tea,
            "all_lesson"          =>@$all_lesson,
            "train_through_count" =>@$train_through_count,
            "all_interview_count" =>@$all_interview_count,
            "arr"                 =>$arr
        ]);

    }

    public function ggg(){
        $time = time()-10*86400;
        $ret = $this->t_test_lesson_subject_sub_list->get_set_and_lesson_time($time);
        foreach($ret as $k=>&$item){
            $item["rang"] =($item["lesson_start"] - $item["set_lesson_time"])/3600;
            if($item["rang"] <=0){
                unset($ret[$k]);
            }
        }
         \App\Helper\Utils::order_list( $ret,"rang", 0);
        dd($ret);
    }

    public function get_jw_set_lesson_time(){
        $start_time = strtotime(date("2017-03-01"));
        $ret_info = $this->t_test_lesson_subject_require->get_jw_set_lesson_time_info($start_time);
        // dd($ret_info);
        $ret=$list=$arr=[];
        /*  foreach($ret_info as $item){
            $adminid = $item["set_lesson_adminid"];
            $week = $item["week"];
            $time= $item["time"];
            $subject = $item["subject"];
            @$ret[$adminid]["account"] = $item["account"];
            if($subject<=5){
                $subject_str=E\Esubject::get_desc($subject);
                @$ret[$adminid][$week+1][$subject]["num"]++;
                @$ret[$adminid][$week+1][$subject]["time"] += $time;
            }else{
                @$ret[$adminid][$week+1][6]["num"]++;
                @$ret[$adminid][$week+1][6]["time"] += $time;
            }
        }
        foreach($ret as $k=>&$val){
            foreach($val as $kk=>&$vv){
                if($kk !="account"){
                    foreach($vv as $kkk=>&$vvv){
                        $vvv["hour"] = round($vvv["time"]/$vvv["num"]/3600,1);
                    }
                }
            }
            }*/
        // dd($ret);
        foreach($ret_info as $item){
            $week = $item["week"];
            $time= $item["time"];
            $subject = $item["subject"];
            if($subject<=5){
                $subject_str=E\Esubject::get_desc($subject);
                @$ret[$week+1][$subject]["num"]++;
                @$ret[$week+1][$subject]["time"] += $time;
            }else{
                @$ret[$week+1][6]["num"]++;
                @$ret[$week+1][6]["time"] += $time;
            }

            $set_time = $item["set_time"];
            $h = $item["h"];
            if($h<21){
                if($subject<=5){
                    $subject_str=E\Esubject::get_desc($subject);
                    @$arr[$week+1][$subject]["num"]++;
                    @$arr[$week+1][$subject]["set_time"] += $set_time;
                }else{
                    @$arr[$week+1][6]["num"]++;
                    @$arr[$week+1][6]["set_time"] += $set_time;
                }

            }else{
                if($subject<=5){
                    $subject_str=E\Esubject::get_desc($subject);
                    @$list[$week+1][$subject]["num"]++;
                    @$list[$week+1][$subject]["set_time"] += $set_time;
                }else{
                    @$list[$week+1][6]["num"]++;
                    @$list[$week+1][6]["set_time"] += $set_time;
                }

            }

        }
        // dd($arr);
        foreach($ret as &$vv){
            foreach($vv as &$kk){
                $kk["hour"] =round($kk["time"]/$kk["num"]/3600,1);
            }
        }
        foreach($arr as &$v){
            foreach($v as &$k){
                $k["hour"] =round($k["set_time"]/$k["num"]/3600,1);
            }
        }
        foreach($list as &$vvv){
            foreach($vvv as &$kkk){
                $kkk["hour"] =round($kkk["set_time"]/$kkk["num"]/3600,1);
            }
        }

        // dd($list);
        return $this->pageView(__METHOD__,null,["ret"=>$ret,"arr"=>$arr,"list"=>$list]);
    }

    public function research_teacher_lesson_detail_info(){
        // echo "还在开发中,请等待!";
        // echo "暂停使用";
        // return;

        $this->t_lesson_info->switch_tongji_database();
        $this->t_course_order->switch_tongji_database();
        $subject =$this->get_in_int_val("subject",-1);
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],3);
        // $start_time = strtotime($this->get_in_str_val("start"));
        //$end_time = strtotime($this->get_in_str_val("end")." 23:59:59");
        $grade = $this->get_in_int_val("grade",-1);
        $have_order = $this->get_in_int_val("have_order",-1);
        $page_count = $this->get_in_int_val("page_count",20);
        $page_num = $this->get_in_page_num();
        if($grade==-1){
            return $this->error_view(
                [
                    " 没有试听详情信息 ",
                    " 请从[教研排行榜] [各年级签单率排行榜] [试听课详情] 点击\"点击查看详情\"进来 ",
                ]
            );

        }
        $ret_info = $this->t_lesson_info->get_test_lesson_info_by_subject_and_grade($page_num,$subject,$grade,$start_time,$end_time,$have_order,$page_count);

        foreach($ret_info["list"] as &$item){
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            $item["lesson_start_str"]=date("Y-m-d H:i:s",$item["lesson_start"]);
            // $res = $this->t_course_order->check_have_course_order($item["teacherid"],$item["userid"],$item["subject"]);
            if($item["c_subject"]>0){
                $item["have_order"]="已签";
            }else{
                $item["have_order"] = "未签";
            }


        }
        return $this->pageView(__METHOD__,$ret_info);
    }







    public function seller_test_lesson_info_tongji(){

        $is_seller_flag = $this->get_in_int_val('seller_flag',0);
        if($is_seller_flag == 1){
            $ret_info = $this->seller_test_lesson_info_by_teacher();
        }else{
            $ret_info = $this->seller_test_lesson_info_tongji_for_seller();
        }
        return $this->pageView(__METHOD__,$ret_info);
    }


    public function seller_test_lesson_info_by_teacher(){ // 处理老师的试听转化率
        $sum_field_list=[
            "work_day",
            "lesson_count",
            "suc_count",
            "lesson_per",
            "order_count",
            "order_per",
            "all_price",
            "money_per",
            "tea_per",
            "range"
        ];
        $order_field_arr=  array_merge(["account" ] ,$sum_field_list );
        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str($order_field_arr ,"account desc");


        //                 return array(false, "", $field_name, $order_flag=="asc" );

        $show_flag = $this->get_in_int_val("show_flag",0);

        $lesson_money = $this->get_in_int_val("lesson_money",477);

        $this->t_lesson_info->switch_tongji_database();

        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],3);

        $ret_info = $this->t_lesson_info_b2->get_teacher_test_lesson_info_for_jy($start_time,$end_time);


        // dd($ret_info);

        foreach($ret_info["list"] as &$item){
            $item["order_per"] = !empty($item["suc_count"])?round($item["order_count"]/$item["suc_count"],4)*100:0;
            if($item["train_through_new_time"] !=0){
                $item["work_day"] = ceil((time()-$item["train_through_new_time"])/86400);
            }else{
                $item["work_day"] ="";
            }

            $item["all_money"]  = $item["lesson_count"]*$lesson_money+$item["order_count"]*60+($item["suc_count"]-$item["order_count"])*30;
            $item["money_per"] = !empty($item["all_money"])?round($item["all_price"]/$item["all_money"]/100,1):0;

            $item["lesson_per"] = !empty($item["lesson_count"])?round($item["suc_count"]/$item["lesson_count"],4)*100:0;

            if($show_flag==1){
                $seller_arr = $this->t_lesson_info_b2->get_test_lesson_info_by_teacherid($item['teacherid'],$start_time, $end_time);
                if(empty($seller_arr)){
                    $item["tea_per"] = 0;
                    $item["range"] = sprintf("%.2f",$item["order_per"]-$item["tea_per"]);
                }else{
                    $ret = $this->t_lesson_info_b2->get_teacher_test_lesson_info_by_seller($start_time,$end_time,$seller_arr);
                    $item["tea_per"] = !empty($ret["lesson_count"])?round($ret["order_count"]/$ret["lesson_count"],4)*100:0;
                    $item["range"] = sprintf("%.2f",$item["order_per"]-$item["tea_per"]);
                }
            }
        }

        // dd($ret_info);

        $num = count($ret_info["list"]);
        if (!$order_in_db_flag) {
            \App\Helper\Utils::order_list( $ret_info["list"], $order_field_name, $order_type );
        }

        $all_item = [
            "account" => "全部"
        ];

        \App\Helper\Utils::list_add_sum_item($ret_info["list"], $all_item,$sum_field_list);


        foreach($ret_info["list"] as &$val){
            if($val["account"]=="全部"){
                $val["work_day"] = $num>0?ceil(@$val["work_day"]/$num):"";
                $val["order_per"] = !empty($val["suc_count"])?round($val["order_count"]/$val["suc_count"],4)*100:0;
                $val["lesson_per"] = !empty($val["lesson_count"])?round($val["suc_count"]/$val["lesson_count"],4)*100:0;
                $val["all_money"]  = $val["lesson_count"]*$lesson_money+$val["order_count"]*60+($val["suc_count"]-$val["order_count"])*30;
                $val["money_per"] = !empty($val["all_money"])?round($val["all_price"]/$val["all_money"]/100,1):0;
                $val["tea_per"] = $val["range"]="";
            }
        }

        return $ret_info;
    }



    public function seller_test_lesson_info_tongji_for_seller(){ // 销售

        $sum_field_list=[
            "work_day",
            "lesson_count",
            "suc_count",
            "lesson_per",
            "order_count",
            "order_per",
            "all_price",
            "money_per",
            "tea_per",
            "range"
        ];
        $order_field_arr=  array_merge(["account" ] ,$sum_field_list );
        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str($order_field_arr ,"account desc");

        $show_flag = $this->get_in_int_val("show_flag",0);
        $lesson_money = $this->get_in_int_val("lesson_money",477);
        $this->t_lesson_info->switch_tongji_database();
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],3);
        $ret_info = $this->t_lesson_info->get_seller_test_lesson_info($start_time,$end_time);

        foreach($ret_info['list'] as $i=>$item){
            if($item['account'] == 'alan'){
                unset($ret_info['list'][$i]);
            }
        }
        // $order_info = $this->t_lesson_info->get_seller_test_lesson_order_info($start_time,$end_time);

        foreach($ret_info["list"] as &$item){
            $item["order_per"] = !empty($item["suc_count"])?round($item["order_count"]/$item["suc_count"],4)*100:0;
            if($item["create_time"] !=0){
                $item["work_day"] = ceil((time()-$item["create_time"])/86400);
            }else{
                $item["work_day"] ="";
            }
            $item["all_money"]  = $item["lesson_count"]*$lesson_money+$item["order_count"]*60+($item["suc_count"]-$item["order_count"])*30;
            $item["money_per"] = !empty($item["all_money"])?round($item["all_price"]/$item["all_money"]/100,1):0;

            $item["lesson_per"] = !empty($item["lesson_count"])?round($item["suc_count"]/$item["lesson_count"],4)*100:0;
            if($show_flag==1){
                $teacherid_arr = $this->t_lesson_info->get_seller_test_lesson_teacher_info($item["cur_require_adminid"],$start_time,$end_time);
                $ret = $this->t_lesson_info->get_seller_teacher_test_lesson_info($start_time,$end_time,$teacherid_arr);
                $item["tea_per"] = !empty($ret["lesson_count"])?round($ret["order_count"]/$ret["lesson_count"],4)*100:0;
                $item["range"] = sprintf("%.2f",$item["order_per"]-$item["tea_per"]);
            }

        }
        $num = count($ret_info["list"]);
        if (!$order_in_db_flag) {
            \App\Helper\Utils::order_list( $ret_info["list"], $order_field_name, $order_type );
        }

        $all_item = [
            "account" => "全部"
        ];
        \App\Helper\Utils::list_add_sum_item($ret_info["list"], $all_item,$sum_field_list);
        foreach($ret_info["list"] as &$val){
            if($val["account"]=="全部"){
                $val["work_day"] = $num>0?ceil(@$val["work_day"]/$num):""; $val["order_per"] = !empty($val["suc_count"])?round($val["order_count"]/$val["suc_count"],4)*100:0;
                $val["lesson_per"] = !empty($val["lesson_count"])?round($val["suc_count"]/$val["lesson_count"],4)*100:0;
                $val["all_money"]  = $val["lesson_count"]*$lesson_money+$val["order_count"]*60+($val["suc_count"]-$val["order_count"])*30;
                $val["money_per"] = !empty($val["all_money"])?round($val["all_price"]/$val["all_money"]/100,1):0;
                $val["tea_per"] = $val["range"]="";
            }
        }

        return $ret_info;
    }








    public function get_seller_test_lesson_success_info(){
        $adminid = $this->get_in_int_val("adminid");
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");
        $seller_flag = $this->get_in_int_val('seller_flag',-1);

        if($seller_flag>0){
            $data = $this->t_lesson_info_b2->get_teacher_test_lesson_order_info_new($start_time,$end_time,$adminid);
        }else{
            $data = $this->t_lesson_info->get_seller_test_lesson_order_info_new($start_time,$end_time,$adminid);
        }
        foreach($data as &$item){
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            $item["lesson_start_str"]=date("Y-m-d H:i:s",$item["lesson_start"]);
            // $res = $this->t_course_order->check_have_course_order($item["teacherid"],$item["userid"],$item["subject"]);
            if($item["orderid"]>0){
                $item["have_order"]="已签";
            }else{
                $item["have_order"] = "未签";
            }
        }

        return  $this->output_succ( [ "data" =>$data] );
    }

    public function get_seller_teacher_test_lesson_per(){
        $adminid = $this->get_in_int_val("adminid");
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");
        $seller_flag = $this->get_in_int_val('seller_flag',-1);


        if($seller_flag > 0){
            $seller_arr = $this->t_lesson_info_b2->get_test_lesson_info_by_teacherid($adminid,$start_time, $end_time);
            $ret = $this->t_lesson_info_b2->get_teacher_test_lesson_info_by_seller($start_time,$end_time,$seller_arr);
            if(empty($seller_arr)){
                $ret = [];
            }
        }else{
            $teacherid_arr = $this->t_lesson_info->get_seller_test_lesson_teacher_info($adminid,$start_time,$end_time);
            $ret = $this->t_lesson_info->get_seller_teacher_test_lesson_info($start_time,$end_time,$teacherid_arr);
        }

        $per = !empty($ret["lesson_count"])?round($ret["order_count"]/$ret["lesson_count"],4)*100:0;
        return  $this->output_succ( [ "data" =>$per] );
    }



    public function tongji_jw_teacher_kpi(){
        $this->t_teacher_info->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range( 0,0,0,[],2);
        $revisit_info = $this->t_teacher_info->get_jw_assign_teacher_info($start_time,$end_time);
        $revisit_in_time_info = $this->t_teacher_info->get_jw_assign_teacher_time_in_info($start_time,$end_time);
        $revisit_out_time_info = $this->t_teacher_info->get_jw_assign_teacher_time_out_info($start_time,$end_time);
        $un_revisit_info = $this->t_teacher_info->get_jw_assign_teacher_un_revisit_info($start_time,$end_time);
        $plan_in_info = $this->t_teacher_info->get_jw_assign_teacher_plan_in_info($start_time,$end_time);
        $plan_out_info = $this->t_teacher_info->get_jw_assign_teacher_plan_out_info($start_time,$end_time);
        $no_plan_info = $this->t_teacher_info->get_jw_assign_teacher_no_plan_info($start_time,$end_time);
        $absence_info = $this->t_teacher_info->get_jw_assign_teacher_absence_info($start_time,$end_time);

        $revisit_teacher_lesson_info = $this->t_teacher_record_list->get_teacher_revisit_lesson_info($start_time,$end_time);
        foreach($revisit_info as &$item){
            $admind = $item["assign_jw_adminid"];
            $item["revisit_time_in"] = @$revisit_in_time_info[$admind]["time_in_num"];
            $item["revisit_time_out"] = @$revisit_out_time_info[$admind]["time_out_num"];
            $item["no_revisit"] = @$un_revisit_info[$admind]["no_revisit_num"];
            $item["plan_in"] = @$plan_in_info[$admind]["plan_in_num"];
            $item["plan_out"] = @$plan_out_info[$admind]["plan_out_num"];
            $item["no_plan"] = @$no_plan_info[$admind]["no_plan_num"];
            $item["absence_num"] = @$absence_info[$admind]["absence_num"];
        }
        return $this->pageView(__METHOD__ ,null, [
            "revisit_info" => @$revisit_info,
            "start"   =>$start_time,
            "end"     =>time(),
            "revisit_teacher_lesson_info" =>$revisit_teacher_lesson_info
        ]);

    }

    public function get_ass_un_revisit_info(){
        $master_adminid = $this->get_in_int_val("adminid");
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");
        $list = $this->t_student_info->get_ass_un_revisit_info_new($master_adminid,$start_time,$end_time);
        foreach($list as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item, "ass_assign_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item, "revisit_time","_str");
        }
        return  $this->output_succ( [ "data" =>$list] );

    }
    public function tongji_ass_leader_kpi(){
        $admind = $this->get_account_id();    
        list($start_time,$end_time) = $this->get_in_date_range( 0,0,0,[],3);
        $ret_info = $this->t_month_ass_student_info->get_ass_month_info($start_time,-1,3);
        foreach($ret_info as $k=>&$val){
            $val["account"] = $this->t_manager_info->get_account($k);
        }
        return $this->pageView(__METHOD__ ,null, [
            "ret_info" => $ret_info
        ]);


        $last_month = strtotime(date("Y-m-01",$start_time-100));
        $month_middle = $start_time+15*86400;
        $ass_list = $this->t_manager_info->get_adminid_list_by_account_role(1);
        // dd($ass_list);
        $user_list = $this->t_month_ass_student_info->get_ass_month_info($last_month);
        // dd($user_list);
        $ret_info=[];
        foreach($user_list as $key=>&$item){
            $userid_list = json_decode($item["userid_list"],true);
            if(empty($userid_list)){
                $userid_list=[];
            }
            @$ass_list[$key]["revisit_num"] = count($userid_list)*2;
            $num=0;
            $bef_info = $this->t_revisit_info->get_ass_revisit_info_new(-1,$start_time,$month_middle,$userid_list);
            foreach($bef_info as $v){
                if($v["num"]>=1){
                    $num++;
                }
            }
            $aft_info = $this->t_revisit_info->get_ass_revisit_info_new(-1,$month_middle,$end_time,$userid_list);
            foreach($aft_info as $v){
                if($v["num"]>=1){
                    $num++;
                }
            }
            @$ass_list[$key]["num"] = $num;

        }
        // foreach($kk_suc as $kk=>$vv){
        //     @$ass_list[$kk]["kk_suc"] = $vv["lesson_count"];
        //     /*$master_adminid_ass = $this->t_admin_group_user->get_master_adminid_by_adminid($kk);
        //     @$ret_info[$master_adminid_ass]["kk_suc"] +=$vv["lesson_count"];
        //     @$ret_info[$master_adminid_ass]["kk_person"] ++;*/

        // }

        $trans_info = $this->t_student_info->get_trans_stu_info_new($start_time,$end_time);
        foreach($trans_info as $kk=>$vv){
            @$ass_list[$kk]["trans_num"] = $vv["num"];
        }

        $this->t_student_info->switch_tongji_database();
        $un_revisit_info = $this->t_student_info->get_un_revisit_stu_info($start_time,$end_time);
        foreach($un_revisit_info as $kk=>$vv){
            @$ass_list[$kk]["un_revisit_num"] = $vv["un_revisit_num"];
        }

        // 处理退费数据
        $reading_info    = $this->t_month_ass_student_info->get_ass_month_info($start_time);

        $refunded_info   = $this->t_order_refund->get_refunded_info($start_time,$end_time,2);

        foreach($refunded_info as $refund_index => &$refund_item){
            $ret_refund = $this->t_refund_analysis->get_configid_by_orderid($refund_item['orderid']);
            foreach($ret_refund as $ret_item){
                @$department = $this->t_order_refund_confirm_config->get_department_name_by_configid(@$ret_item['configid']);
                if ( $department != '助教部' ||  @$ret_item['score']==0) {
                    unset($refunded_info[$refund_index]);
                }
            }
        }


        foreach($refunded_info as $refunded_index => $refunded_item){
            @$ass_list[$refunded_index]["refunded_num"] = $refunded_item["num"];
        }

        foreach($reading_info as $reading_index => $reading_item){
            @$ass_list[$reading_index]["reading_num"] = $reading_item["read_student"];
        }


        //dd($un_revisit_info);
        foreach($ass_list as $aa=>$ss){
            $master_adminid_ass = $this->t_admin_group_user->get_master_adminid_by_adminid($aa,1);
            if(!empty($master_adminid_ass)){
                @$ret_info[$master_adminid_ass]["revisit_num"] +=@$ss["revisit_num"];
                @$ret_info[$master_adminid_ass]["revisit_do"] +=@$ss["num"];
                @$ret_info[$master_adminid_ass]["kk_suc"] +=$ss["kk_suc"];
                @$ret_info[$master_adminid_ass]["trans_num"] +=$ss["trans_num"];
                @$ret_info[$master_adminid_ass]["un_revisit_num"] +=$ss["un_revisit_num"];
                @$ret_info[$master_adminid_ass]["person"] ++;

                @$ret_info[$master_adminid_ass]["refunded_num"] += $ss["refunded_num"];
                @$ret_info[$master_adminid_ass]["reading_num"] += $ss["reading_num"];
            }
        }

        foreach($ret_info as $k=>&$val){
            $val["revisit_per"] = !empty($val["revisit_num"])?round(@$val["revisit_do"]/$val["revisit_num"],4)*100:0;
            if($val["revisit_per"]>=90){
                $val["revisit_score"]=60;
            }elseif($val["revisit_per"]>=80){
                $val["revisit_score"]=50;
            }elseif($val["revisit_per"]>=70){
                $val["revisit_score"]=40;
            }else{
                $val["revisit_score"]=0;
            }

            //退费统计处理

            $num_re = @$val["refunded_num"]+@$val['reading_num'];
            $val['refund_per'] = !empty($num_re)?round(@$val["refunded_num"]/(@$val["refunded_num"]+@$val['reading_num']),4)*100:0;

            if($val["refund_per"]>=1.5){
                // $points = ceil($val['refunded_num'] - ($val['refund_num_all']*0.015));
                $points = $val['refunded_num'];
                $val["refund_score"]=(-10*$points);
            }elseif($val["refund_per"]>=1){
                $val["refund_score"]=0;
            }elseif($val["refund_per"]>=0.5){
                $val["refund_score"]=30;
            }elseif($val["refund_per"]>=0){
                $val["refund_score"]=40;
            }


            $val["kk_suc_avg"] = round($val["kk_suc"]/$val["person"],1);
            $val["kk_score"] = $val["kk_suc_avg"]*2.5;
            $val["trans_num_avg"] = round($val["trans_num"]/$val["person"],1);
            $val["trans_score"] = $val["trans_num_avg"]*5;
            // $val["un_revisit_num_avg"] = round($val["un_revisit_num"]/$val["person"],1);
            $val["un_revisit_score"] = $val["un_revisit_num"]*5;
            $val["account"] = @$ass_list[$k]["account"];
            $val["total_score"] = $val["revisit_score"]+$val["kk_score"]+$val["trans_score"]-$val["un_revisit_score"];
        }
        // dd($ret_info);

        return $this->pageView(__METHOD__ ,null, [
            "ret_info" => $ret_info
        ]);

    }

    public function tongji_ass_kpi_master(){
        $this->set_in_value("adminid",-1);
        return $this->tongji_ass_kpi();
    }

    public function tongji_ass_kpi(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);
        $month_middle = $start_time+15*86400;
        $ass_list = $this->t_manager_info->get_adminid_list_by_account_role(1);

        $adminid = $this->get_account_id();
        if($adminid==349){
            $adminid=297;
        }
        $account_id = $this->get_in_int_val("adminid",$adminid);
        $read_info    = $this->t_month_ass_student_info->get_ass_month_info($start_time);
        $new_info = $this->t_student_info->get_refund_info($start_time,$end_time,0);
        $first_revisit = $this->t_revisit_info->get_ass_first_revisit_info($start_time,$end_time);
        $xq_revisit_first = $this->t_revisit_info->get_ass_xq_revisit_info($start_time,$month_middle);
        $xq_revisit_second = $this->t_revisit_info->get_ass_xq_revisit_info($month_middle,$end_time);
        $kk_succ = $this->t_course_order->get_kk_succ_info($start_time,$end_time);
        $tran_list = $this->t_manager_info->get_all_assistant_renew_list_new($start_time,$end_time);
        foreach($ass_list as $k=>&$item){
            $item["new_stu"]=isset($new_info[$k])?$new_info[$k]["num"]:0;
            $item["read_stu"]=isset($read_info[$k])?$read_info[$k]["read_student"]:0;
            $item["revisit_num"]= $item["read_stu"]- $item["new_stu"];
            $item["first_revisit"] = isset($first_revisit[$k])?$first_revisit[$k]["num"]:0;
            $item["xq_revisit_first"] = isset($xq_revisit_first[$k])?$xq_revisit_first[$k]["num"]:0;
            $item["xq_revisit_second"] = isset($xq_revisit_second[$k])?$xq_revisit_second[$k]["num"]:0;
            $item["kk_succ"] = isset($kk_succ[$k])?$kk_succ[$k]["num"]:0;
            $item["tran_num"] = isset($tran_list[$k])?$tran_list[$k]["tran_num"]:0;
            $ass_master_adminid = $this->t_admin_group_user->get_master_adminid_by_adminid($k);
            if($account_id==-1){

            }else{
                if($ass_master_adminid != $account_id){
                    unset($ass_list[$k]);
                }

            }

        }
        return $this->pageView(__METHOD__ ,null, [
            "ret_info" => $ass_list
        ]);




    }

    public function teacher_test_lesson_money_info(){
        $this->t_lesson_info->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range( 0,0,0,[],3);
        $subject = $this->get_in_int_val("subject",-1);
        $grade   = $this->get_in_int_val("grade",-1);
        $ret_info = $this->t_lesson_info->get_teacher_test_lesson_order_info($start_time,$end_time,$subject,$grade);
        dd($ret_info);
    }

    public function tongji_seller_test_lesson_order_info_zj(){
        return $this->tongji_seller_test_lesson_order_info();
    }

    public function tongji_seller_test_lesson_order_info_zs(){
        return $this->tongji_seller_test_lesson_order_info();
    }


    public function tongji_seller_test_lesson_order_info(){
        list($start_time,$end_time) = $this->get_in_date_range(date('Y-m-01',time()), 0 );
        $seller_groupid_ex    = $this->get_in_str_val('seller_groupid_ex', "");
        $require_adminid_list = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);
        $adminid_right              = $this->get_seller_adminid_and_right();


        $ret_info = $this->t_test_lesson_subject_sub_list->get_seller_test_lesson_order_info_new($start_time,$end_time,$require_adminid_list);
        $grade_arr = $subject_arr = $paper_arr = $location_arr=[];
        foreach($ret_info as $item){
            if($item["stu_test_paper"]){
                if($item["tea_download_paper_time"]>0){
                    $paper="老师已下载";
                }else{
                    $paper="老师未下载";
                }
            }else{
                $paper="无试卷";
            }
            $location=substr($item["phone_location"], 0, -6);
            @$grade_arr[$item["grade"]]["num"]++;
            @$subject_arr[$item["subject"]]["num"]++;
            @$location_arr[$location]["num"]++;
            @$paper_arr[$paper]["num"]++;
            if($item["orderid"]>0){
                @$grade_arr[$item["grade"]]["order"]++;
                @$subject_arr[$item["subject"]]["order"]++;
                @$location_arr[$location]["order"]++;
                @$paper_arr[$paper]["order"]++;
            }

        }


        foreach($subject_arr as $k=>&$v){
            if(!isset($v["order"])) $v["order"]=0;
            $v["per"] = !empty($v["num"])?round(@$v["order"]/$v["num"],4)*100:0;
            $v["name"] = E\Esubject::get_desc($k);
        }
        \App\Helper\Utils::order_list( $subject_arr,"per", 0);
        foreach($grade_arr as $kk=>&$vv){
            if(!isset($vv["order"])) $vv["order"]=0;
            $vv["per"] = !empty($vv["num"])?round(@$vv["order"]/$vv["num"],4)*100:0;
            $vv["name"] = E\Egrade::get_desc($kk);
        }
        \App\Helper\Utils::order_list( $grade_arr,"per", 0);
        foreach($location_arr as $kkk=>&$vvv){
            if(!isset($vvv["order"])) $vvv["order"]=0;
            $vvv["per"] = !empty($vvv["num"])?round(@$vvv["order"]/$vvv["num"],4)*100:0;
            $vvv["name"] = $kkk;
        }
        \App\Helper\Utils::order_list( $location_arr,"per", 0);
        foreach($paper_arr as $kkkk=>&$vvvv){
            if(!isset($vvvv["order"])) $vvvv["order"]=0;
            $vvvv["per"] = !empty($vvvv["num"])?round(@$vvvv["order"]/$vvvv["num"],4)*100:0;
            $vvvv["name"] = $kkkk;
        }
        \App\Helper\Utils::order_list( $paper_arr,"per", 0);


        // dd($origin_info);

        return $this->pageView(__METHOD__ ,null, [
            "subject_arr" => @$subject_arr,
            "grade_arr"   => @$grade_arr,
            "paper_arr"   => @$paper_arr,
            "location_arr"=> @$location_arr,
            "adminid_right"=>$adminid_right,
        ]);


    }







    public function tongji_seller_test_lesson_order_info_for_jx(){ // for 教学管理事业部
        list($start_time,$end_time) = $this->get_in_date_range(date('Y-m-01',time()), 0 );
        $seller_groupid_ex    = $this->get_in_str_val('seller_groupid_ex', "");
        $require_adminid_list = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);
        $adminid_right        = $this->get_seller_adminid_and_right();

        ///  排序处理;
        $sum_field_list=[
            "name_grade",
            "num_grade",
            "order_grade",
            "per_grade",

            "name_subject",
            "num_subject",
            "order_subject",
            "per_subject",

            "name_paper",
            "num_paper",
            "order_paper",
            "per_paper",

            "name_location",
            "num_location",
            "order_location",
            "per_location",
        ];
        $order_field_arr=  $sum_field_list ;

        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str($order_field_arr ,"");
        // return array(false, "", $field_name, $order_flag=="asc" );


        // 排序处理

        $field_name = 'origin';
        $field_class_name = '';

        $this->t_seller_student_origin->switch_tongji_database();

        $origin_info = $this->t_seller_student_origin->get_origin_tongji_info_for_jy('origin', 'add_time' ,$start_time,$end_time,"","","",$require_adminid_list);

        $data_map = &$origin_info['list'];

        $this->t_test_lesson_subject_require->switch_tongji_database();
        $test_lesson_list=$this->t_test_lesson_subject_require->tongji_test_lesson_origin_jx( $field_name,$start_time,$end_time,$require_adminid_list,-1, '' );

        foreach ($test_lesson_list as  $test_item ) {
            $check_value=$test_item["check_value"];
            \App\Helper\Utils:: array_item_init_if_nofind( $data_map, $check_value,["check_value" => $check_value] );
            $data_map[$check_value]["succ_test_lesson_count"] = $test_item["succ_test_lesson_count"];
            $data_map[$check_value]["dic_succ_test_lesson_count"] = $test_item["distinct_succ_count"];
        }


        $this->t_order_info->switch_tongji_database();
        $order_list= $this->t_lesson_info->get_test_person_num_list_subject_other_jx( $start_time,$end_time,$require_adminid_list);
        $order_data = $this->t_order_info->get_node_type_order_data_now("origin",$start_time,$end_time,$require_adminid_list,-1,"","oi.order_time", "");


        foreach ($order_list as  $order_item ) {
            $check_value=$order_item["check_value"];
            \App\Helper\Utils:: array_item_init_if_nofind( $data_map, $check_value,["check_value" => $check_value ] );
            $data_map[$check_value]["order_count"] = $order_item["have_order"];
        }
        foreach ($order_data as  $order_item ) {
            $check_value=$order_item["check_value"];
            \App\Helper\Utils:: array_item_init_if_nofind( $data_map, $check_value,["check_value" => $check_value ] );
            $data_map[$check_value]["order_num"] = $order_item["order_count"];
        }



        foreach ($data_map as &$item ) {
            $item["title"]= $item["check_value"];

            if ($field_name=="origin") {
                $item["origin"]= $item["title"];
            }
        }

        if ($field_name=="origin") {
            $origin_info["list"]= $this->gen_origin_data($origin_info["list"],["avg_first_time"], '');
        }

        // 测试结束



        $ret_info = $this->t_test_lesson_subject_sub_list->get_seller_test_lesson_order_info_new($start_time,$end_time,$require_adminid_list);
        $grade_arr = $subject_arr = $paper_arr = $location_arr=[];
        foreach($ret_info as $item){
            if($item["stu_test_paper"]){
                if($item["tea_download_paper_time"]>0){
                    $paper="老师已下载";
                }else{
                    $paper="老师未下载";
                }
            }else{
                $paper="无试卷";
            }
            $location=substr($item["phone_location"], 0, -6);
            @$grade_arr[$item["grade"]]["num"]++;
            @$subject_arr[$item["subject"]]["num"]++;
            @$location_arr[$location]["num"]++;
            @$paper_arr[$paper]["num"]++;
            if($item["orderid"]>0){
                @$grade_arr[$item["grade"]]["order"]++;
                @$subject_arr[$item["subject"]]["order"]++;
                @$location_arr[$location]["order"]++;
                @$paper_arr[$paper]["order"]++;
            }

        }

        //         // return array(false, "", $field_name, $order_flag=="asc" );

        foreach($subject_arr as $k=>&$v){
            if(!isset($v["order"])) $v["order"]=0;
            $v["per"] = !empty($v["num"])?round(@$v["order"]/$v["num"],4)*100:0;
            $v["name"] = E\Esubject::get_desc($k);
        }

        $paixu_arr = explode('_',$order_field_name);


        // \App\Helper\Utils::order_list( $subject_arr,"per", $order_type);


        foreach($grade_arr as $kk=>&$vv){
            if(!isset($vv["order"])) $vv["order"]=0;
            $vv["per"] = !empty($vv["num"])?round(@$vv["order"]/$vv["num"],4)*100:0;
            $vv["name"] = E\Egrade::get_desc($kk);
        }

        // \App\Helper\Utils::order_list( $grade_arr,"per", 0);

        foreach($location_arr as $kkk=>&$vvv){
            if(!isset($vvv["order"])) $vvv["order"]=0;
            $vvv["per"] = !empty($vvv["num"])?round(@$vvv["order"]/$vvv["num"],4)*100:0;
            $vvv["name"] = $kkk;
        }
        // \App\Helper\Utils::order_list( $location_arr,"per", 0);

        foreach($paper_arr as $kkkk=>&$vvvv){
            if(!isset($vvvv["order"])) $vvvv["order"]=0;
            $vvvv["per"] = !empty($vvvv["num"])?round(@$vvvv["order"]/$vvvv["num"],4)*100:0;
            $vvvv["name"] = $kkkk;
        }
        // \App\Helper\Utils::order_list( $paper_arr,"per", 0);

        $origin_info = @$origin_info['list'];


        // 排序处理
        if(!$order_in_db_flag){
            if($paixu_arr[1] == 'grade' ){
                \App\Helper\Utils::order_list( $grade_arr,$paixu_arr[0], $order_type);
            }elseif($paixu_arr[1] == 'subject'){
                \App\Helper\Utils::order_list( $subject_arr,$paixu_arr[0], $order_type);
            }elseif($paixu_arr[1] == 'location'){
                \App\Helper\Utils::order_list( $location_arr,$paixu_arr[0], $order_type);
            }elseif($paixu_arr[1] == 'paper'){
                \App\Helper\Utils::order_list( $paper_arr,$paixu_arr[0], $order_type);
            }
        }





        // dd($origin_info);

        return $this->pageView(__METHOD__ ,null, [
            "subject_arr" => @$subject_arr,
            "grade_arr"   => @$grade_arr,
            "paper_arr"   => @$paper_arr,
            "location_arr"=> @$location_arr,
            "adminid_right"=>$adminid_right,
            "origin_info"  => $origin_info
        ]);


    }


    public function tongji_seller_test_lesson_paper_order_info(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3 );

        $monthtime_flag = $this->get_in_int_val("monthtime_flag",1);
        // $adminid = $this->get_in_int_val("adminid",-1);
        $ret_info = $this->t_test_lesson_subject_sub_list->get_seller_test_lesson_paper_order_info($start_time,$end_time);
        // $ret_info = $ret_info['list'];
        if($monthtime_flag==1){
            $admin_info = $this->t_manager_info->get_admin_member_list();
        }else{
            $admin_info = $this->t_manager_info->get_admin_member_list_new($start_time);
        }
        $admin_list= & $admin_info['list'] ;

        foreach ($admin_list as $vk=>&$val){
            $adminid=$val['adminid'];
            if (!isset($ret_info[$adminid ]) )  {
                unset( $admin_list[$vk] );
            }else{

                $val['admin_revisiterid'] = $adminid ;
                $ret_item=@$ret_info[$adminid];
                $val['have_download_num'] = @$ret_item['have_download_num'];
                $val['have_download_order'] = @$ret_item['have_download_order'];
                $val['no_download_num'] = @$ret_item['no_download_num'];
                $val['no_download_order'] = @$ret_item['no_download_order'];
                $val['no_paper_num'] = @$ret_item['no_paper_num'];
                $val['no_paper_order'] = @$ret_item['no_paper_order'];
            }
        }

        $ret_info=\App\Helper\Common::gen_admin_member_data($admin_info['list'],[],$monthtime_flag,$start_time);
        foreach($ret_info as &$item){
            $item["have_download_per"] = !empty($item["have_download_num"])?round($item["have_download_order"]/$item["have_download_num"],4)*100:0;
            $item["no_download_per"] = !empty($item["no_download_num"])?round($item["no_download_order"]/$item["no_download_num"],4)*100:0;
            $item["no_paper_per"] = !empty($item["no_paper_num"])?round($item["no_paper_order"]/$item["no_paper_num"],4)*100:0;
            E\Emain_type::set_item_value_str($item);
        }
        $ret_info = \App\Helper\Utils::list_to_page_info($ret_info);
        // dd($ret_info);
        return $this->pageView(__METHOD__ ,$ret_info);


    }



    public function get_seller_require_modify_info(){
        list($start_time,$end_time) = $this->get_in_date_range(date('Y-m-01',time()), 0 );
        $change_type = $this->get_in_int_val("change_type",2);
        $type = $this->get_in_int_val("record_type",-1);
        $res=[];
        if($change_type==1){
            $ret_info = $this->t_teacher_record_list->get_seller_require_modify_info($type,$start_time,$end_time);
            //dd($ret_info);
            $all = $suc=$num=0;
            foreach($ret_info as $key=>&$item){
                //$num++;
                $next_time = $item["add_time"]+3*3600;
                $check_repeat = $this->t_teacher_record_list->check_is_repeat($item["add_time"],$next_time,$item["teacherid"]);
                if($check_repeat==1){
                    unset($ret_info[$key]);
                }else{
                    $lesson_info = $this->t_lesson_info->get_tea_test_lesson_detail_info($item["teacherid"],$item["add_time"]);
                    if($lesson_info){
                        $num++;
                        $item["seller_account"] = @$lesson_info["account"];
                        if(!empty($lesson_info) && $lesson_info["lesson_user_online_status"]<2){
                            $item["lesson_info"]="是";
                            $item["lesson_flag"]=1;
                            $all++;
                            $lessonid = $lesson_info["lessonid"];
                            $ret = $this->t_order_info->check_is_have_order($lessonid);
                            if($ret>0){
                                $suc ++;
                                $item["order_info"] = "是";
                                $item["order_flag"] = 1;
                            }else{
                                $item["order_info"]="否";
                                $item["order_flag"] = 0;
                            }
                        }else{
                            $item["lesson_info"]="否";
                            $item["lesson_flag"]=0;
                            $item["order_info"]="否";
                            $item["order_flag"] = 0;
                        }
                        if($item["type"]==3){
                            E\Elimit_plan_lesson_type::set_item_value_str($item,"limit_plan_lesson_type");
                            E\Elimit_plan_lesson_type::set_item_value_str($item,"limit_plan_lesson_type_old");
                        }else if($item["type"]==7){
                            $item["limit_plan_lesson_type_str"] = "一周限排".$item["limit_week_lesson_num_new"]."节";
                            $item["limit_plan_lesson_type_old_str"] = "一周限排".$item["limit_week_lesson_num_old"]."节";
                        }
                        \App\Helper\Utils::unixtime2date_for_item($item, "add_time","_str");

                    }else{
                        unset($ret_info[$key]);
                    }
                }
            }
            // dd($ret_info);
            $res["lesson"] = $all;
            $res["order"] =$suc;
            $res["realname"]="全部";
            $res["num"] = $num;
        }else{
            $ret_info = $this->t_test_lesson_subject_sub_list->get_seller_limit_require_info($start_time,$end_time);
            foreach($ret_info as &$item){
                @$res["num"]++;
                if($item["lesson_user_online_status"]<2){
                    $item["lesson_flag"]=1;
                    $item["lesson_info"]="是";
                    @$res["lesson"]++;
                }else{
                    $item["lesson_flag"]=0;
                    $item["lesson_info"]="否";
                }
                if($item["orderid"]>0){
                    $item["order_flag"]=1;
                    $item["order_info"] = "是";
                    @$res["order"]++;
                }else{
                    $item["order_flag"]=0;
                    $item["order_info"]="否";
                }
                $item["add_time_str"] = date("Y-m-d H:i:s",$item["limit_accept_time"]);
            }
        }
        if(!isset($res["order"])) $res["order"]=0;
        $res["per"] = !empty($res["lesson"])?round(@$res["order"]/$res["lesson"],4)*100:0;
        $ret_info = \App\Helper\Utils::list_to_page_info($ret_info);
        return $this->pageView(__METHOD__ ,$ret_info, [
            "total" => $res,
            "change_type"=>$change_type
        ]);

        //dd($res);

    }

    public function get_lecture_teacher_test_lesson_info(){
        $end_time = time()-10*86400;
        $start_time = time()-90*86400;
        $ret = $this->t_teacher_lecture_info->get_lecture_teacher_test_lesson_info($start_time,$end_time);
        // dd($ret);
        $arr=[];
        foreach($ret as $val){
            $num = ($val['confirm_time']-$val["add_time"])/86400;
            if($num>0 && $num <= 1){
                @$arr[1]["num"]++;
                if($val["orderid"]>0){
                    @$arr[1]["order"]++;
                }
            }elseif($num>1 && $num <=2){
                @$arr[2]["num"]++;
                if($val["orderid"]>0){
                    @$arr[2]["order"]++;
                }
            }elseif($num>2 && $num <=3){
                @$arr[3]["num"]++;
                if($val["orderid"]>0){
                    @$arr[3]["order"]++;
                }
            }elseif($num>3 && $num <=4){
                @$arr[4]["num"]++;
                if($val["orderid"]>0){
                    @$arr[4]["order"]++;
                }
            }elseif($num>4 && $num <=5){
                @$arr[5]["num"]++;
                if($val["orderid"]>0){
                    @$arr[5]["order"]++;
                }
            }elseif($num>5 && $num <=6){
                @$arr[6]["num"]++;
                if($val["orderid"]>0){
                    @$arr[6]["order"]++;
                }
            }elseif($num>6 && $num <=7){
                @$arr[7]["num"]++;
                if($val["orderid"]>0){
                    @$arr[7]["order"]++;
                }
            }





        }
        foreach($arr as &$item){
            $item["per"] = round(@$item["order"]/$item["num"],4)*100;
        }
        ksort($arr);
        dd($arr);
    }

    public function get_stu_test_paper_download_info(){
        $sum_field_list = [
            "download_per",
            "have_download_num",
            "have_download_order",
            "have_download_per",
            "no_download_num",
            "no_download_order",
            "no_download_per",
            "no_paper_num",
            "no_paper_order",
            "no_paper_per"
        ];
        $order_field_arr = array_merge(["realname"],$sum_field_list);
        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str($order_field_arr,"realname desc");
        $adminid      = $this->get_account_id();
        $right_list = $this->get_tea_subject_and_right_by_adminid($adminid);
        $tea_subject = $right_list["tea_subject"];
        $tea_right  = $right_list["tea_right"];
        $qz_flag = $right_list["qz_flag"];

        list($start_time,$end_time) = $this->get_in_date_range(date('Y-m-01',time()), 0 );
        $subject = $this->get_in_int_val("subject",-1);
        $ret_info = $this->t_test_lesson_subject_sub_list->get_teacher_test_lesson_paper_order_info($start_time,$end_time,$subject,$tea_subject,$qz_flag);

        foreach($ret_info as &$item){
            $item["all_have_paper"] = $item["have_download_num"]+$item["no_download_num"];
            $item["download_per"]  =  !empty($item["all_have_paper"])?round($item["have_download_num"]/$item["all_have_paper"],4)*100:0;
            $item["have_download_per"] = !empty($item["have_download_num"])?round($item["have_download_order"]/$item["have_download_num"],4)*100:0;
            $item["no_download_per"] = !empty($item["no_download_num"])?round($item["no_download_order"]/$item["no_download_num"],4)*100:0;
            $item["no_paper_per"] = !empty($item["no_paper_num"])?round($item["no_paper_order"]/$item["no_paper_num"],4)*100:0;
        }
        if (!$order_in_db_flag) {
            \App\Helper\Utils::order_list( $ret_info, $order_field_name, $order_type );
        }

        $all_item = [
            "realname" => "全部"
        ];
        \App\Helper\Utils::list_add_sum_item($ret_info, $all_item,$sum_field_list);

        foreach($ret_info as &$val){
            $val["all_have_paper"] = $val["have_download_num"]+$val["no_download_num"];
            $val["download_per"]  =  !empty($val["all_have_paper"])?round($val["have_download_num"]/$val["all_have_paper"],4)*100:0;
            $val["have_download_per"] = !empty($val["have_download_num"])?round($val["have_download_order"]/$val["have_download_num"],4)*100:0;
            $val["no_download_per"] = !empty($val["no_download_num"])?round($val["no_download_order"]/$val["no_download_num"],4)*100:0;
            $val["no_paper_per"] = !empty($val["no_paper_num"])?round($val["no_paper_order"]/$val["no_paper_num"],4)*100:0;
        }
        $ret_info = \App\Helper\Utils::list_to_page_info($ret_info);

        //dd($ret_info);
        return $this->pageView(__METHOD__ ,$ret_info);


    }

    public function get_homework_and_work_status_info(){
        list($start_time,$end_time) = $this->get_in_date_range(date('Y-m-01',time()), 0 );
        $adminid      = $this->get_account_id();
        $right_list = $this->get_tea_subject_and_right_by_adminid($adminid);
        $tea_subject = $right_list["tea_subject"];
        $tea_right  = $right_list["tea_right"];
        $qz_flag = $right_list["qz_flag"];

        $ret_info = $this->t_test_lesson_subject_sub_list->get_homework_and_work_status_info($start_time,$end_time,$tea_subject,$qz_flag);
        $arr=["subject_str"=>"全部"];
        foreach($ret_info as &$item){
            @$arr["no_tea_cw"] +=$item["no_tea_cw"];
            @$arr["no_tea_cw_order"] +=$item["no_tea_cw_order"];
            @$arr["have_tea_cw"] +=$item["have_tea_cw"];
            @$arr["have_tea_cw_order"] +=$item["have_tea_cw_order"];
            @$arr["no_homework"] +=$item["no_homework"];
            @$arr["no_homework_order"] +=$item["no_homework_order"];
            @$arr["have_homework"] +=$item["have_homework"];
            @$arr["have_homework_order"] +=$item["have_homework_order"];
            E\Esubject::set_item_value_str($item);
        }
        array_unshift($ret_info,$arr);
        foreach($ret_info as &$val){
            $val["no_tea_cw_per"]  =  !empty($val["no_tea_cw"])?round($val["no_tea_cw_order"]/$val["no_tea_cw"],4)*100:0;
            $val["have_tea_cw_per"] = !empty($val["have_tea_cw"])?round($val["have_tea_cw_order"]/$val["have_tea_cw"],4)*100:0;
            $val["no_homework_per"] = !empty($val["no_homework"])?round($val["no_homework_order"]/$val["no_homework"],4)*100:0;
            $val["have_homework_per"] = !empty($val["have_homework"])?round($val["have_homework_order"]/$val["have_homework"],4)*100:0;
        }
        $ret_info = \App\Helper\Utils::list_to_page_info($ret_info);
        return $this->pageView(__METHOD__ ,$ret_info);
    }

    public function get_jw_no_plan_remind(){
        $accept_adminid = $this->get_account_id();
        if($accept_adminid==74 || $accept_adminid==349){
            $accept_adminid=-1;
        }
        $ret= $this->t_test_lesson_subject_require->get_jw_no_plan_time_list($accept_adminid);
        return $this->pageView(__METHOD__ ,null,["list"=>$ret]);

    }

    public function test_lesson_order_per_subject(){
         list($start_time,$end_time) = $this->get_in_date_range(date('Y-m-01',time()), 0 );
         $this->t_lesson_info->switch_tongji_database();
         $list = $this->t_lesson_info->get_test_lesson_order_per_subject($start_time,$end_time);
         $ret_info = $this->t_teacher_record_list->get_seller_require_modify_info(-1,$start_time,$end_time);
         //dd($ret_info);
         $all = [];
         foreach($ret_info as $key=>$i){
             //$num++;
             $next_time = $i["add_time"]+3*3600;
             $check_repeat = $this->t_teacher_record_list->check_is_repeat($i["add_time"],$next_time,$i["teacherid"]);
             if($check_repeat!=1){
                 $lesson_info = $this->t_lesson_info->get_tea_test_lesson_detail_info($i["teacherid"],$i["add_time"]);
                 if($lesson_info){
                     if(!empty($lesson_info) && $lesson_info["success_flag"]<2){
                         @$all[$lesson_info["subject"]]["lesson_count"]++;
                         $lessonid = $lesson_info["lessonid"];
                         $ret = $this->t_order_info->check_is_have_order($lessonid);
                         if($ret>0){
                             @$all[$lesson_info["subject"]]["order_count"]++;
                         }
                     }

                 }
             }
         }
         $total=["subject_str"=>"全部"];
         foreach($list as $k=>&$v){
             $v["no_cc_lesson_count"] = $v["lesson_count"]- @$all[$k]["lesson_count"];
             $v["no_cc_order_count"] = $v["order_count"] - @$all[$k]["order_count"];
             E\Esubject::set_item_value_str($v);
             @$total["lesson_count"] +=$v["lesson_count"];
             @$total["order_count"]  +=$v["order_count"];
             @$total["no_cc_lesson_count"] +=$v["no_cc_lesson_count"];
             @$total["no_cc_order_count"] +=$v["no_cc_order_count"];
         }
         array_unshift($list,$total);
         foreach($list as &$item){
             $item["no_cc_order_per"] = !empty($item["no_cc_lesson_count"])?round(@$item["no_cc_order_count"]/$item["no_cc_lesson_count"],4)*100:0;
             $item["order_per"] = !empty($item["lesson_count"])?round($item["order_count"]/$item["lesson_count"],4)*100:0;

         }
         $list_info = \App\Helper\Utils::list_to_page_info($list);
         return $this->pageView(__METHOD__ ,$list_info);

    }

    public function get_change_teacher_info(){
        $this->t_test_lesson_subject->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range(date('Y-m-01',time()), 0 );
        // $start_time = strtotime(date("2017-01-05"));
        // $end_time = strtotime(date("2017-05-01"));
        $ret = $this->t_test_lesson_subject->get_ass_change_teacher_tongji_info($start_time,$end_time);
        $tea= $ass=[];
        foreach($ret as $item){
            @$tea[$item["realname"]]["num"]++;
            @$tea[$item["realname"]]["realname"] = $item["realname"];
            @$tea[$item["realname"]]["teacherid"] = $item["old_teacherid"];

            @$ass[$item["account"]]["num"]++;
            @$ass[$item["account"]]["account"] = $item["account"];
            @$ass[$item["account"]]["uid"] = $item["cur_require_adminid"];
        }
        \App\Helper\Utils::order_list( $tea,"num", 0);
        \App\Helper\Utils::order_list( $ass,"num", 0);
        $all = $this->t_teacher_info->get_teacher_list(1,$start_time,$end_time);
        $all["change_tea_num"] = count($tea);
        $all["change_ass_num"] = count($ass);
        foreach($tea as $v){
            @$all["change_tea_all_num"] +=$v["num"];
        }
        foreach($ass as $v){
            @$all["change_ass_all_num"] +=$v["num"];
        }

        // $list_info = \App\Helper\Utils::list_to_page_info($ass);
        return $this->pageView(__METHOD__ ,null,[
            "tea"   =>$tea,
            "ass"   =>$ass,
            "all"   =>$all
        ]);

        //dd($ass);

    }



    public function get_refund_teacher_and_ass_info(){ // 统计老师和助教退费次数
        $this->t_test_lesson_subject->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range(date('Y-m-01',time()), 0 );

        $ret_ass = $this->t_order_refund->get_ass_refund_info_by_qc($start_time,$end_time);
        $ret_tec = $this->t_order_refund->get_tec_refund_info_by_qc($start_time,$end_time);

        foreach($ret_ass as $index_ass => &$item_ass){
            $item_ass['num'] = count($this->t_order_refund->get_refund_count_for_ass($start_time,$end_time,$item_ass['uid']));
        }

        foreach($ret_tec as $index_tec => &$item_tec){
            $item_tec['num'] = count($this->t_order_refund->get_refund_count_for_tec($start_time,$end_time,$item_tec['teacherid']));
        }
        // dd($ret_tec);

        \App\Helper\Utils::order_list( $ret_tec,"num", 0);
        \App\Helper\Utils::order_list( $ret_ass,"num", 0);
        return $this->pageView(__METHOD__ ,null,[
            "tea"   =>$ret_tec,
            "ass"   =>$ret_ass,
        ]);

    }






    public function get_change_teacher_detail_info(){
        $teacherid = $this->get_in_int_val("teacherid");
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");
        $list = $this->t_test_lesson_subject->get_ass_change_teacher_tongji_info($start_time,$end_time,$teacherid);

        return  $this->output_succ( [ "data" =>$list] );
    }

    public function get_change_teacher_detail_info_ass(){
        $adminid = $this->get_in_int_val("adminid");
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");
        $list = $this->t_test_lesson_subject->get_ass_change_teacher_tongji_info($start_time,$end_time,-1,$adminid);

        return  $this->output_succ( [ "data" =>$list] );
    }



    public function get_interview_info_by_time($start_time,$end_time){
        $this->t_teacher_lecture_info->switch_tongji_database();
        $this->t_lesson_info->switch_tongji_database();
        $ret_info = $this->t_manager_info-> get_adminid_list_by_account_role(4);
        $interview_info=$this->t_teacher_lecture_info->get_interview_info_by_account($start_time,$end_time);
        $order_info = $this->t_teacher_lecture_info->get_research_teacher_test_lesson_info_account($start_time,$end_time);
        // $order_info = $this->t_teacher_lecture_info->get_interview_lesson_order_info($start_time,$end_time);
        $first_info = $this->t_lesson_info->get_interview_teacher_first_lesson_info($start_time,$end_time);
        $tea_list = $this->t_lesson_info->get_all_first_lesson_teacher_list($start_time,$end_time);
        $record_list = $this->t_teacher_record_list->get_teacher_record_num_list_account($start_time,$end_time);
        $tea_arr=[];$record_info = [];
        foreach($tea_list as $k=>$val){
            if($val["add_time"]>0){
                $tea_arr[] = $k;
                @$record_info[$val["uid"]]["num"]++;
                @$record_info[$val["uid"]]["record_time"] +=$val["add_time"] - $val["lesson_start"];
            }
        }
        $lesson_info = $this->t_lesson_info->get_teacher_arr_lesson_order_info($start_time,$end_time,$tea_arr);
        $first_lesson_info = $this->t_lesson_info->get_teacher_arr_first_lesson_order_info($start_time,$end_time,$tea_arr);
        $other_record_info  = $this->t_seller_and_ass_record_list->get_seller_and_ass_record_by_account($start_time,$end_time);
        $arr=["account"=>"平均","uid"=>1];
        foreach($ret_info as $k=>&$item){
            $subject_and_grade_arr = $this->get_tea_subject_and_grade_by_adminid_new($k);
            $subject_arr= $subject_and_grade_arr["subject"];
            $grade_arr= $subject_and_grade_arr["grade"];
            $test_person_num= $this->t_lesson_info->get_test_person_num_list_by_subject_grade( $start_time,$end_time,$subject_arr,$grade_arr);
            $test_person_num_other= $this->t_lesson_info->get_test_person_num_list_subject_grade_other( $start_time,$end_time,$subject_arr,$grade_arr);
            $kk_test_person_num= $this->t_lesson_info->get_kk_teacher_test_person_subject_grade_list( $start_time,$end_time,$subject_arr,$grade_arr);
            $change_test_person_num= $this->t_lesson_info->get_change_teacher_test_person_subject_grade_list( $start_time,$end_time,$subject_arr,$grade_arr);
            $item["lesson_num"]  = @$test_person_num["lesson_num"];
            $item["person_num"]  = @$test_person_num["person_num"];
            $item["have_order"]  = @$test_person_num["have_order"];
            $item["lesson_num_other"]  = @$test_person_num_other["lesson_num"];
            $item["have_order_other"]  = @$test_person_num_other["have_order"];
            $item["lesson_num_kk"]  = @$kk_test_person_num["lesson_num"];
            $item["have_order_kk"]  = @$kk_test_person_num["have_order"];
            $item["lesson_num_change"]  = @$change_test_person_num["lesson_num"];
            $item["have_order_change"]  = @$change_test_person_num["have_order"];


            $item["interview_num"] = @$interview_info[$k]["interview_num"];
            @$arr["interview_num"] += @$interview_info[$k]["interview_num"];
            $item["interview_time"] = @$interview_info[$k]["interview_time"];
            @$arr["interview_time"] += @$interview_info[$k]["interview_time"];
            $item["interview_lesson"] =  @$order_info[$k]["person_num"];
            @$arr["interview_lesson"] += @$order_info[$k]["person_num"];
            $item["interview_order"] =  @$order_info[$k]["order_num"];
            @$arr["interview_order"] += @$order_info[$k]["order_num"];
            $item["record_time"] = @$record_info[$k]["record_time"];
            @$arr["record_time"] += @$record_info[$k]["record_time"];
            $item["record_num"] = @$record_info[$k]["num"];
            @$arr["record_num"] += @$record_info[$k]["num"];
            $item["first_lesson"] = @$first_info[$k]["person_num"];
            @$arr["first_lesson"] += @$first_info[$k]["person_num"];
            $item["first_order"] = @$first_info[$k]["order_num"];
            @$arr["first_order"] += @$first_info[$k]["order_num"];
            $item["next_lesson"] = @$lesson_info[$k]["person_num"];
            @$arr["next_lesson"] += @$lesson_info[$k]["person_num"];
            $item["next_order"] = @$lesson_info[$k]["order_num"];
            @$arr["next_order"] += @$lesson_info[$k]["order_num"];
            $item["next_lesson_first"] = @$first_lesson_info[$k]["person_num"];
            @$arr["next_lesson_first"] += @$first_lesson_info[$k]["person_num"];
            $item["next_order_first"] = @$first_lesson_info[$k]["order_num"];
            @$arr["next_order_first"] += @$first_lesson_info[$k]["order_num"];
            $item["other_record_time"]  = @$other_record_info[$k]["deal_time"];
            @$arr["other_record_time"] += @$other_record_info[$k]["deal_time"];
            $item["other_record_num"]  = @$other_record_info[$k]["num"];
            @$arr["other_record_num"] += @$other_record_info[$k]["num"];
            $item["record_num_all"] = @$record_list[$k]["num"];
            @$arr["record_num_all"] += @$record_list[$k]["num"];


        }
        $test_person_num_all= $this->t_lesson_info->get_test_person_num_list_by_subject_grade( $start_time,$end_time);
        $test_person_num_other_all= $this->t_lesson_info->get_test_person_num_list_subject_grade_other( $start_time,$end_time);
        $kk_test_person_num_all= $this->t_lesson_info->get_kk_teacher_test_person_subject_grade_list( $start_time,$end_time);
        $change_test_person_num_all= $this->t_lesson_info->get_change_teacher_test_person_subject_grade_list( $start_time,$end_time);
        $arr["lesson_num"] = $test_person_num_all["lesson_num"];
        $arr["have_order"] = @$test_person_num_all["have_order"];
        $arr["lesson_per"] = !empty($test_person_num_all["person_num"])?round(@$test_person_num_all["have_order"]/$test_person_num_all["person_num"],4)*100:0;
        $arr["lesson_per_other"] = !empty($test_person_num_other_all["lesson_num"])?round($test_person_num_other_all["have_order"]/$test_person_num_other_all["lesson_num"],4)*100:0;
        $arr["lesson_per_kk"] = !empty($kk_test_person_num_all["lesson_num"])?round($kk_test_person_num_all["have_order"]/$kk_test_person_num_all["lesson_num"],4)*100:0;
        $arr["lesson_per_change"] = !empty($change_test_person_num_all["lesson_num"])?round($change_test_person_num_all["have_order"]/$change_test_person_num_all["lesson_num"],4)*100:0;





        array_unshift($ret_info,$arr);
        foreach($ret_info as &$v){
            $v["interview_time_avg"] = !empty($v["interview_num"])?round($v["interview_time"]/$v["interview_num"]/86400,1):0;
            $v["record_time_avg"] = !empty($v["record_num"])?round($v["record_time"]/$v["record_num"]/86400,1):0;
            $v["other_record_time_avg"] = !empty($v["other_record_num"])?round($v["other_record_time"]/$v["other_record_num"]/86400,1):0;
            $v["interview_per"] = !empty($v["interview_lesson"])?round($v["interview_order"]/$v["interview_lesson"],4)*100:0;
            $v["first_per"] = !empty($v["first_lesson"])?round($v["first_order"]/$v["first_lesson"],4)*100:0;
            $v["next_per"] = !empty($v["next_lesson"])?round($v["next_order"]/$v["next_lesson"],4)*100:0;
            $v["first_next_per"] = !empty($v["next_lesson_first"])?round($v["next_order_first"]/$v["next_lesson_first"],4)*100:0;
            $v["add_per"] =  round($v["next_per"]-$v["first_next_per"],2);

            $v["lesson_num_per"] = !empty($arr["lesson_num"])?round(@$v["lesson_num"]/$arr["lesson_num"],4)*100:0;
            if($v["account"] !="平均"){
                $v["lesson_per"] = !empty($v["person_num"])?round(@$v["have_order"]/$v["person_num"],4)*100:0;
                $v["lesson_per_other"] = !empty($v["lesson_num_other"])?round(@$v["have_order_other"]/$v["lesson_num_other"],4)*100:0;
                $v["lesson_per_kk"] = !empty($v["lesson_num_kk"])?round(@$v["have_order_kk"]/$v["lesson_num_kk"],4)*100:0;
                $v["lesson_per_change"] = !empty($v["lesson_num_change"])?round(@$v["have_order_change"]/$v["lesson_num_change"],4)*100:0;
            }
        }

        foreach($ret_info as &$ss){
            foreach($ss as &$vv){
                if(empty($vv)){
                    $vv=0;
                }
            }
        }

        return $ret_info;

    }

    public function get_interview_info_by_time_new($start_time,$end_time){
        $this->t_teacher_lecture_info->switch_tongji_database();
        $this->t_lesson_info->switch_tongji_database();
        $ret_info =[];
        for($i=1;$i<11;$i++){
            $ret_info[$i]=["subject"=>$i];
        }
        $interview_info=$this->t_teacher_lecture_info->get_interview_info_by_subject($start_time,$end_time);
        $order_info = $this->t_teacher_lecture_info->get_research_teacher_test_lesson_info_subject($start_time,$end_time);
        // $order_info = $this->t_teacher_lecture_info->get_interview_lesson_order_info_subject($start_time,$end_time);
        $first_info = $this->t_lesson_info->get_interview_teacher_first_lesson_info_subject($start_time,$end_time);
        $tea_list = $this->t_lesson_info->get_all_first_lesson_teacher_list($start_time,$end_time);
        $record_list = $this->t_teacher_record_list->get_teacher_record_num_list_subject($start_time,$end_time);
        $tea_arr=[];$record_info = [];
        foreach($tea_list as $k=>$val){
            if($val["add_time"]>0){
                $tea_arr[] = $k;
                @$record_info[$val["subject"]]["num"]++;
                @$record_info[$val["subject"]]["record_time"] +=$val["add_time"] - $val["lesson_start"];
            }
        }
        $lesson_info = $this->t_lesson_info->get_teacher_arr_lesson_order_info_subject($start_time,$end_time,$tea_arr);
        $first_lesson_info = $this->t_lesson_info->get_teacher_arr_first_lesson_order_info_subject($start_time,$end_time,$tea_arr);
        $other_record_info  = $this->t_seller_and_ass_record_list->get_seller_and_ass_record_by_subject($start_time,$end_time);
        $test_person_num= $this->t_lesson_info->get_test_person_num_list_by_subject( $start_time,$end_time);
        $test_person_num_other= $this->t_lesson_info->get_test_person_num_list_subject_other( $start_time,$end_time);
        $kk_test_person_num= $this->t_lesson_info->get_kk_teacher_test_person_subject_list( $start_time,$end_time);
        $change_test_person_num= $this->t_lesson_info->get_change_teacher_test_person_subject_list( $start_time,$end_time);



        $arr=["subject_str"=>"平均","subject"=>100];
        $zh = ["subject_str"=>"综合学科","subject"=>200];
        $wz = ["subject_str"=>"文科","subject"=>300];
        $xxk = ["subject_str"=>"小学科","subject"=>400];
        foreach($ret_info as $k=>&$item){
            $item["interview_num"] = @$interview_info[$k]["interview_num"];
            @$arr["interview_num"] += @$interview_info[$k]["interview_num"];
            $item["interview_time"] = @$interview_info[$k]["interview_time"];
            @$arr["interview_time"] += @$interview_info[$k]["interview_time"];
            $item["interview_lesson"] =  @$order_info[$k]["person_num"];
            @$arr["interview_lesson"] += @$order_info[$k]["person_num"];
            $item["interview_order"] =  @$order_info[$k]["order_num"];
            @$arr["interview_order"] += @$order_info[$k]["order_num"];
            $item["record_time"] = @$record_info[$k]["record_time"];
            @$arr["record_time"] += @$record_info[$k]["record_time"];
            $item["record_num"] = @$record_info[$k]["num"];
            @$arr["record_num"] += @$record_info[$k]["num"];
            $item["first_lesson"] = @$first_info[$k]["person_num"];
            @$arr["first_lesson"] += @$first_info[$k]["person_num"];
            $item["first_order"] = @$first_info[$k]["order_num"];
            @$arr["first_order"] += @$first_info[$k]["order_num"];
            $item["next_lesson"] = @$lesson_info[$k]["person_num"];
            @$arr["next_lesson"] += @$lesson_info[$k]["person_num"];
            $item["next_order"] = @$lesson_info[$k]["order_num"];
            @$arr["next_order"] += @$lesson_info[$k]["order_num"];
            $item["next_lesson_first"] = @$first_lesson_info[$k]["person_num"];
            @$arr["next_lesson_first"] += @$first_lesson_info[$k]["person_num"];
            $item["next_order_first"] = @$first_lesson_info[$k]["order_num"];
            @$arr["next_order_first"] += @$first_lesson_info[$k]["order_num"];
            $item["other_record_time"]  = @$other_record_info[$k]["deal_time"];
            @$arr["other_record_time"] += @$other_record_info[$k]["deal_time"];
            $item["other_record_num"]  = @$other_record_info[$k]["num"];
            @$arr["other_record_num"] += @$other_record_info[$k]["num"];
            $item["lesson_num"]  = @$test_person_num[$k]["lesson_num"];
            $item["person_num"]  = @$test_person_num[$k]["person_num"];
            $item["have_order"]  = @$test_person_num[$k]["have_order"];
            $item["lesson_num_other"]  = @$test_person_num_other[$k]["lesson_num"];
            $item["have_order_other"]  = @$test_person_num_other[$k]["have_order"];
            $item["lesson_num_kk"]  = @$kk_test_person_num[$k]["lesson_num"];
            $item["have_order_kk"]  = @$kk_test_person_num[$k]["have_order"];
            $item["lesson_num_change"]  = @$change_test_person_num[$k]["lesson_num"];
            $item["have_order_change"]  = @$change_test_person_num[$k]["have_order"];
            $item["record_num_all"] = @$record_list[$k]["num"];
            @$arr["record_num_all"] += @$record_list[$k]["num"];


            if($k==1 || $k==3){
                @$wz["interview_num"] += @$interview_info[$k]["interview_num"];
                @$wz["interview_time"] += @$interview_info[$k]["interview_time"];
                @$wz["interview_time"] += $item["interview_time"];
                @$wz["interview_num"] += $item["interview_num"];
                @$wz["interview_lesson"] += @$order_info[$k]["person_num"];
                @$wz["interview_order"] += @$order_info[$k]["order_num"];
                @$wz["record_time"] += @$record_info[$k]["record_time"];
                @$wz["record_num"] += @$record_info[$k]["num"];
                @$wz["first_lesson"] += @$first_info[$k]["person_num"];
                @$wz["first_order"] += @$first_info[$k]["order_num"];
                @$wz["next_lesson"] += @$lesson_info[$k]["person_num"];
                @$wz["next_order"] += @$lesson_info[$k]["order_num"];
                @$wz["next_lesson_first"] += @$first_lesson_info[$k]["person_num"];
                @$wz["next_order_first"] += @$first_lesson_info[$k]["order_num"];
                @$wz["other_record_time"] += @$other_record_info[$k]["deal_time"];
                @$wz["other_record_num"] += @$other_record_info[$k]["num"];
                @$wz["lesson_num"] += @$test_person_num[$k]["person_num"];
                @$wz["have_order"] += @$test_person_num[$k]["have_order"];
                @$wz["lesson_num_other"] += @$test_person_num_other[$k]["lesson_num"];
                @$wz["have_order_other"] += @$test_person_num_other[$k]["have_order"];
                @$wz["lesson_num_kk"] += @$kk_test_person_num[$k]["lesson_num"];
                @$wz["have_order_kk"] += @$kk_test_person_num[$k]["have_order"];
                @$wz["lesson_num_change"] += @$change_test_person_num[$k]["lesson_num"];
                @$wz["have_order_change"] += @$change_test_person_num[$k]["have_order"];
                @$wz["record_num_all"] += @$record_list[$k]["num"];
                @$wz["person_num"]  += @$test_person_num[$k]["person_num"];


            }else if($k>3){
                @$zh["interview_num"] += @$interview_info[$k]["interview_num"];
                @$zh["interview_time"] += @$interview_info[$k]["interview_time"];
                @$zh["interview_time"] += $item["interview_time"];
                @$zh["interview_num"] += $item["interview_num"];
                @$zh["interview_lesson"] += @$order_info[$k]["person_num"];
                @$zh["interview_order"] += @$order_info[$k]["order_num"];
                @$zh["record_time"] += @$record_info[$k]["record_time"];
                @$zh["record_num"] += @$record_info[$k]["num"];
                @$zh["first_lesson"] += @$first_info[$k]["person_num"];
                @$zh["first_order"] += @$first_info[$k]["order_num"];
                @$zh["next_lesson"] += @$lesson_info[$k]["person_num"];
                @$zh["next_order"] += @$lesson_info[$k]["order_num"];
                @$zh["next_lesson_first"] += @$first_lesson_info[$k]["person_num"];
                @$zh["next_order_first"] += @$first_lesson_info[$k]["order_num"];
                @$zh["other_record_time"] += @$other_record_info[$k]["deal_time"];
                @$zh["other_record_num"] += @$other_record_info[$k]["num"];
                @$zh["lesson_num"] += @$test_person_num[$k]["lesson_num"];
                @$zh["have_order"] += @$test_person_num[$k]["have_order"];
                @$zh["lesson_num_other"] += @$test_person_num_other[$k]["lesson_num"];
                @$zh["have_order_other"] += @$test_person_num_other[$k]["have_order"];
                @$zh["lesson_num_kk"] += @$kk_test_person_num[$k]["lesson_num"];
                @$zh["have_order_kk"] += @$kk_test_person_num[$k]["have_order"];
                @$zh["lesson_num_change"] += @$change_test_person_num[$k]["lesson_num"];
                @$zh["have_order_change"] += @$change_test_person_num[$k]["have_order"];
                @$zh["record_num_all"] += @$record_list[$k]["num"];
                @$zh["person_num"]  += @$test_person_num[$k]["person_num"];

            }
            if($k >5){
                @$xxk["interview_num"] += @$interview_info[$k]["interview_num"];
                @$xxk["interview_time"] += @$interview_info[$k]["interview_time"];
                @$xxk["interview_time"] += $item["interview_time"];
                @$xxk["interview_num"] += $item["interview_num"];
                @$xxk["interview_lesson"] += @$order_info[$k]["person_num"];
                @$xxk["interview_order"] += @$order_info[$k]["order_num"];
                @$xxk["record_time"] += @$record_info[$k]["record_time"];
                @$xxk["record_num"] += @$record_info[$k]["num"];
                @$xxk["first_lesson"] += @$first_info[$k]["lesson_num"];
                @$xxk["first_order"] += @$first_info[$k]["order_num"];
                @$xxk["next_lesson"] += @$lesson_info[$k]["person_num"];
                @$xxk["next_order"] += @$lesson_info[$k]["order_num"];
                @$xxk["next_lesson_first"] += @$first_lesson_info[$k]["person_num"];
                @$xxk["next_order_first"] += @$first_lesson_info[$k]["order_num"];
                @$xxk["other_record_time"] += @$other_record_info[$k]["deal_time"];
                @$xxk["other_record_num"] += @$other_record_info[$k]["num"];
                @$xxk["lesson_num"] += @$test_person_num[$k]["person_num"];
                @$xxk["have_order"] += @$test_person_num[$k]["have_order"];
                @$xxk["lesson_num_other"] += @$test_person_num_other[$k]["lesson_num"];
                @$xxk["have_order_other"] += @$test_person_num_other[$k]["have_order"];
                @$xxk["lesson_num_kk"] += @$kk_test_person_num[$k]["lesson_num"];
                @$xxk["have_order_kk"] += @$kk_test_person_num[$k]["have_order"];
                @$xxk["lesson_num_change"] += @$change_test_person_num[$k]["lesson_num"];
                @$xxk["have_order_change"] += @$change_test_person_num[$k]["have_order"];
                @$xxk["record_num_all"] += @$record_list[$k]["num"];
                @$xxk["person_num"]  += @$test_person_num[$k]["person_num"];

            }
            E\Esubject::set_item_value_str($item);

        }
        $test_person_num_all= $this->t_lesson_info->get_test_person_num_list_by_subject_grade( $start_time,$end_time);
        $test_person_num_other_all= $this->t_lesson_info->get_test_person_num_list_subject_grade_other( $start_time,$end_time);
        $kk_test_person_num_all= $this->t_lesson_info->get_kk_teacher_test_person_subject_grade_list( $start_time,$end_time);
        $change_test_person_num_all= $this->t_lesson_info->get_change_teacher_test_person_subject_grade_list( $start_time,$end_time);
        $arr["lesson_num"] = $test_person_num_all["lesson_num"];
        $arr["have_order"] = @$test_person_num_all["have_order"];
        $arr["lesson_per"] = !empty($test_person_num_all["person_num"])?round(@$test_person_num_all["have_order"]/$test_person_num_all["person_num"],4)*100:0;
        $arr["lesson_per_other"] = !empty($test_person_num_other_all["lesson_num"])?round($test_person_num_other_all["have_order"]/$test_person_num_other_all["lesson_num"],4)*100:0;
        $arr["lesson_per_kk"] = !empty($kk_test_person_num_all["lesson_num"])?round($kk_test_person_num_all["have_order"]/$kk_test_person_num_all["lesson_num"],4)*100:0;
        $arr["lesson_per_change"] = !empty($change_test_person_num_all["lesson_num"])?round($change_test_person_num_all["have_order"]/$change_test_person_num_all["lesson_num"],4)*100:0;

        array_unshift($ret_info,$xxk);
        array_unshift($ret_info,$zh);
        array_unshift($ret_info,$wz);
        array_unshift($ret_info,$arr);
        foreach($ret_info as &$v){
            $v["interview_time_avg"] = !empty($v["interview_num"])?round($v["interview_time"]/$v["interview_num"]/86400,1):0;
            $v["record_time_avg"] = !empty($v["record_num"])?round($v["record_time"]/$v["record_num"]/86400,1):0;
            $v["other_record_time_avg"] = !empty($v["other_record_num"])?round($v["other_record_time"]/$v["other_record_num"]/86400,1):0;
            $v["interview_per"] = !empty($v["interview_lesson"])?round(@$v["interview_order"]/$v["interview_lesson"],4)*100:0;
            $v["first_per"] = !empty($v["first_lesson"])?round(@$v["first_order"]/$v["first_lesson"],4)*100:0;
            $v["next_per"] = !empty($v["next_lesson"])?round(@$v["next_order"]/$v["next_lesson"],4)*100:0;
            $v["first_next_per"] = !empty($v["next_lesson_first"])?round(@$v["next_order_first"]/$v["next_lesson_first"],4)*100:0;
            $v["add_per"] =  round($v["next_per"]-$v["first_next_per"],2);
            if($v["subject_str"] !="平均"){
                $v["lesson_per"] = !empty($v["person_num"])?round(@$v["have_order"]/$v["person_num"],4)*100:0;
                $v["lesson_per_other"] = !empty($v["lesson_num_other"])?round(@$v["have_order_other"]/$v["lesson_num_other"],4)*100:0;
                $v["lesson_per_kk"] = !empty($v["lesson_num_kk"])?round(@$v["have_order_kk"]/$v["lesson_num_kk"],4)*100:0;
                $v["lesson_per_change"] = !empty($v["lesson_num_change"])?round(@$v["have_order_change"]/$v["lesson_num_change"],4)*100:0;
            }

            $v["lesson_num_per"] = !empty($arr["lesson_num"])?round(@$v["lesson_num"]/$arr["lesson_num"],4)*100:0;
        }

        foreach($ret_info as &$ss){
            foreach($ss as &$vv){
                if(empty($vv)){
                    $vv=0;
                }
            }
        }
        return $ret_info;

    }

    public function research_teacher_kpi_info_new(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);
        $last_time = strtotime(date("Y-m-01",$start_time-100));
        $type_flag = $this->get_in_int_val("type_flag",1);
        $person_kpi = $this->t_research_teacher_kpi_info->get_month_kpi_info(1,$start_time);
        $subject_kpi = $this->t_research_teacher_kpi_info->get_month_kpi_info(2,$start_time);
        $one_subject_kpi = $this->t_research_teacher_kpi_info->get_month_kpi_info(2,$start_time,1);
        \App\Helper\Utils::order_list( $person_kpi,"total_score", 0);
        \App\Helper\Utils::order_list( $subject_kpi,"total_score", 0);
        \App\Helper\Utils::order_list( $one_subject_kpi,"total_score", 0);
        $ret_info = $this->t_research_teacher_kpi_info->get_month_info($type_flag,$start_time);
        $avg=[];
        foreach($ret_info as $k=>$val){
            if($val["name"]=="平均"){
                $avg= $ret_info[$k];
                unset($ret_info[$k]);
            }
        }
        \App\Helper\Utils::order_list( $ret_info,"kid", 0);
        if($avg){
            array_unshift($ret_info,$avg);
        }
        $last_info = $this->t_research_teacher_kpi_info->get_month_info($type_flag,$last_time);
        foreach($ret_info as &$item){
            $kid = $item["kid"];
            $item["last_interview_per"] = @$last_info[$kid]["interview_per"];
            $item["last_first_per"] = @$last_info[$kid]["first_per"];
            $item["last_add_per"] = @$last_info[$kid]["add_per"];
            $item["last_interview_lesson"] = @$last_info[$kid]["interview_lesson"];
            $item["last_interview_order"] = @$last_info[$kid]["interview_order"];
            $item["last_first_lesson"] = @$last_info[$kid]["first_lesson"];
            $item["last_first_order"] = @$last_info[$kid]["first_order"];
            $item["last_first_next_per"] = @$last_info[$kid]["first_next_per"];
            $item["last_next_per"] = @$last_info[$kid]["next_per"];
        }
        $ret_info = \App\Helper\Utils::list_to_page_info($ret_info);
        return $this->pageView(__METHOD__ ,$ret_info,[
            "type_flag"   =>$type_flag,
            "person_kpi"  =>$person_kpi,
            "subject_kpi" =>$subject_kpi,
            "one_subject_kpi" =>$one_subject_kpi,
        ]);


    }
    public function research_teacher_kpi_info(){

        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);
        $last_time = strtotime(date("Y-m-01",$start_time-100));
        $type_flag = $this->get_in_int_val("type_flag",1);
        $score_list = [];
        $group_score_list=[];
        $avg = [];
        $interview_time_standard=3;
        $record_time_standard=4;
        $other_record_time_standard=1;
        $add_per_standard=3;
        $research_num= $this->t_manager_info->get_adminid_num_by_account_role(4);
        if($type_flag==1){
            $ret_info = $this->get_interview_info_by_time($start_time,$end_time);
            $last_info = $this->get_interview_info_by_time($last_time,$start_time);
            $str = "uid";
            $res= $ret_info;
            foreach($res as &$vv){
                if(in_array($vv["uid"],[478,486,754])){
                    $interview_per_standard = 23;
                    $first_per_standard = 20;
                    $lesson_per_standard = 22;
                    $lesson_per_other_standard = 75;
                    $lesson_per_kk_standard = 75;
                    $lesson_per_change_standard = 75;
                }else{
                    $interview_per_standard = 18;
                    $first_per_standard = 15;
                    $lesson_per_standard = 17;
                    $lesson_per_other_standard = 70;
                    $lesson_per_kk_standard = 70;
                    $lesson_per_change_standard = 70;
                }
                $vv["interview_per_range"] = $vv["interview_per"]- $interview_per_standard;
                $vv["first_per_range"] = $vv["first_per"]- $first_per_standard;
                $vv["lesson_per_range"] = $vv["lesson_per"]- $lesson_per_standard;
                $vv["lesson_per_other_range"] = $vv["lesson_per_other"]- $lesson_per_other_standard;
                $vv["lesson_per_kk_range"] = $vv["lesson_per_kk"]- $lesson_per_kk_standard;
                $vv["lesson_per_change_range"] = $vv["lesson_per_change"]- $lesson_per_change_standard;
            }
            foreach($res as $k=>$v){
                if($v["account"]=="平均"){
                    $avg = $res[$k];
                    unset($res[$k]);
                }
            }
            $record_num_standard = !empty($research_num)?round(@$avg["lesson_num"]/$research_num,1):0;
            $interview_time =  $res;
            foreach($interview_time as $k=>$v){
                if($v["interview_time_avg"]==0){
                    unset($interview_time[$k]);
                }
            }

            \App\Helper\Utils::order_list( $interview_time,"interview_time_avg", 1);
            $interview_time_first_uid =  @$interview_time[0]["uid"];
            \App\Helper\Utils::order_list( $res,"interview_per_range", 0);
            $interview_per_first_uid = @$res[0]["uid"];
            $record_time =  $res;
            foreach( $record_time as $k=>$v){
                if($v["record_time_avg"]==0){
                    unset( $record_time[$k]);
                }
            }
            \App\Helper\Utils::order_list( $record_time,"record_time_avg", 1);
            $record_time_first_uid =  @$record_time[0]["uid"];
            \App\Helper\Utils::order_list( $res,"record_num_all", 0);
            $record_num_first_uid = @$res[0]["uid"];
            \App\Helper\Utils::order_list( $res,"first_per_range", 0);
            $first_per_first_uid = @$res[0]["uid"];
            \App\Helper\Utils::order_list( $res,"add_per", 0);
            $add_per_first_uid = @$res[0]["uid"];
            $other_record_time =$res;
            foreach( $other_record_time as $k=>$v){
                if($v["other_record_time_avg"]==0){
                    unset( $other_record_time[$k]);
                }
            }
            \App\Helper\Utils::order_list( $other_record_time,"other_record_time_avg", 1);
            $other_record_time_first_uid =  @$other_record_time[0]["uid"];
            \App\Helper\Utils::order_list( $res,"lesson_num_per", 0);
            $lesson_num_per_first_uid = @$res[0]["uid"];
            \App\Helper\Utils::order_list( $res,"lesson_per_range", 0);
            $lesson_per_first_uid = @$res[0]["uid"];
            \App\Helper\Utils::order_list( $res,"lesson_per_other_range", 0);
            $lesson_per_other_first_uid = @$res[0]["uid"];
            \App\Helper\Utils::order_list( $res,"lesson_per_kk_range", 0);
            $lesson_per_kk_first_uid = @$res[0]["uid"];
            \App\Helper\Utils::order_list( $res,"lesson_per_change_range", 0);
            $lesson_per_change_first_uid = @$res[0]["uid"];

            foreach($res as $u){
                $uid= $u["uid"];
                if($uid==$interview_time_first_uid){
                    if($u["interview_time_avg"] <= $avg["interview_time_avg"] && $u["interview_time_avg"]<=$interview_time_standard){
                        $score_list[$uid]["interview_time_score"] = 5;
                    }else{
                        $score_list[$uid]["interview_time_score"] = 1;
                    }
                }else{
                    if($u["interview_time_avg"] <=$avg["interview_time_avg"] && $u["interview_time_avg"]<=$interview_time_standard && $u["interview_time_avg"] !=0){
                        $score_list[$uid]["interview_time_score"] = 3;
                    }else{
                        $score_list[$uid]["interview_time_score"] = 1;
                    }

                }
                if($uid==$interview_per_first_uid){
                    if($u["interview_per"] >=$avg["interview_per"] && $u["interview_per_range"]>=0){
                        $score_list[$uid]["interview_per_score"] = 5;
                    }else if($u["interview_per"] >=$avg["interview_per"]){
                        $score_list[$uid]["interview_per_score"] = 3;
                    }else{
                        $score_list[$uid]["interview_per_score"]=1;
                    }
                }else{
                    if($u["interview_per"] >=$avg["interview_per"]){
                        $score_list[$uid]["interview_per_score"] = 3;
                    }else{
                        $score_list[$uid]["interview_per_score"] = 1;
                    }

                }
                if($uid==$record_time_first_uid){
                    if($u["record_time_avg"] <=$avg["record_time_avg"] && $u["record_time_avg"]<=$record_time_standard){
                        $score_list[$uid]["record_time_score"] = 5;
                    }else{
                        $score_list[$uid]["record_time_score"] = 1;
                    }
                }else{
                    if($u["record_time_avg"] <$avg["record_time_avg"] && $u["record_time_avg"]<$record_time_standard && $u["record_time_avg"] !=0){
                        $score_list[$uid]["record_time_score"] = 3;
                    }else{
                        $score_list[$uid]["record_time_score"] = 1;
                    }

                }

                if($uid==$record_num_first_uid){
                    if($u["record_num_all"] >=$avg["record_num_all"] && $u["record_num_all"]>=$record_num_standard){
                        $score_list[$uid]["record_num_score"] = 5;
                    }else if($u["record_num_all"] >=$avg["record_num_all"] ){
                        $score_list[$uid]["record_num_score"] = 3;
                    }else{
                        $score_list[$uid]["record_num_score"]=1;
                    }
                }else{
                    if($u["record_num_all"] >=$avg["record_num_all"] ){
                        $score_list[$uid]["record_num_score"] = 3;
                    }else{
                        $score_list[$uid]["record_num_score"] = 1;
                    }

                }

                if($uid==$first_per_first_uid){
                    if($u["first_per"] >=$avg["first_per"] && $u["first_per_range"]>=0){
                        $score_list[$uid]["first_per_score"] = 5;
                    }else if($u["first_per"] >= $avg["first_per"]){
                        $score_list[$uid]["first_per_score"] = 3;
                    }else{
                        $score_list[$uid]["first_per_score"]=1;
                    }
                }else{
                    if($u["first_per"] >= $avg["first_per"]){
                        $score_list[$uid]["first_per_score"] = 3;
                    }else{
                        $score_list[$uid]["first_per_score"] = 1;
                    }

                }
                if($uid==$add_per_first_uid){
                    if($u["add_per"] >=$avg["add_per"] && $u["add_per"]>=$add_per_standard){
                        $score_list[$uid]["add_per_score"] = 5;
                    }else if($u["add_per"] >=$avg["add_per"] ){
                        $score_list[$uid]["add_per_score"] = 3;
                    }else{
                        $score_list[$uid]["add_per_score"]=1;
                    }
                }else{
                    if($u["add_per"] >=$avg["add_per"] ){
                        $score_list[$uid]["add_per_score"] = 3;
                    }else{
                        $score_list[$uid]["add_per_score"] = 1;
                    }

                }

                if($uid==$other_record_time_first_uid){
                    if($u["other_record_time_avg"] <= $avg["other_record_time_avg"] && $u["other_record_time_avg"] <= $other_record_time_standard){
                        $score_list[$uid]["other_record_time_score"] = 5;
                    }else{
                        $score_list[$uid]["other_record_time_score"] = 1;
                    }
                }else{
                    if($u["other_record_time_avg"] <=$avg["other_record_time_avg"] && $u["other_record_time_avg"]<=$other_record_time_standard && $u["other_record_time_avg"] !=0){
                        $score_list[$uid]["other_record_time_score"] = 3;
                    }else{
                        $score_list[$uid]["other_record_time_score"] = 1;
                    }

                }
                if($uid==$lesson_per_first_uid){
                    if($u["lesson_per"] >=$avg["lesson_per"] && $u["lesson_per_range"]>=0){
                        $score_list[$uid]["lesson_per_score"] = 25;
                    }else if($u["lesson_per"] >=$avg["lesson_per"]){
                        $score_list[$uid]["lesson_per_score"] = 15;
                    }else{
                        $score_list[$uid]["lesson_per_score"]=5;
                    }
                }else{
                    if($u["lesson_per"] >=$avg["lesson_per"]){
                        $score_list[$uid]["lesson_per_score"] = 15;
                    }else{
                        $score_list[$uid]["lesson_per_score"] = 5;
                    }

                }
                if($uid==$lesson_per_other_first_uid){
                    if($u["lesson_per_other"] >= $avg["lesson_per_other"] && $u["lesson_per_other_range"]>=0){
                        $score_list[$uid]["lesson_per_other_score"] = 5;
                    }else if($u["lesson_per_other"] >=$avg["lesson_per_other"]){
                        $score_list[$uid]["lesson_per_other_score"] = 3;
                    }else{
                        $score_list[$uid]["lesson_per_other_score"]=1;
                    }
                }else{
                    if($u["lesson_per_other"] >=$avg["lesson_per_other"]){
                        $score_list[$uid]["lesson_per_other_score"] = 3;
                    }else{
                        $score_list[$uid]["lesson_per_other_score"] = 1;
                    }

                }

                if($uid==$lesson_per_kk_first_uid){
                    if($u["lesson_per_kk"] >=$avg["lesson_per_kk"] && $u["lesson_per_kk_range"]>=0){
                        $score_list[$uid]["lesson_per_kk_score"] = 5;
                    }else if($u["lesson_per_kk"] >=$avg["lesson_per_kk"]){
                        $score_list[$uid]["lesson_per_kk_score"] = 3;
                    }else{
                        $score_list[$uid]["lesson_per_kk_score"]=1;
                    }
                }else{
                    if($u["lesson_per_kk"] >=$avg["lesson_per_kk"]){
                        $score_list[$uid]["lesson_per_kk_score"] = 3;
                    }else{
                        $score_list[$uid]["lesson_per_kk_score"] = 1;
                    }

                }

                if($uid==$lesson_per_change_first_uid){
                    if($u["lesson_per_change"] >=$avg["lesson_per_change"] && $u["lesson_per_change_range"]>=0){
                        $score_list[$uid]["lesson_per_change_score"] = 5;
                    }else if($u["lesson_per_change"] >= $avg["lesson_per_change"]){
                        $score_list[$uid]["lesson_per_change_score"] = 3;
                    }else{
                        $score_list[$uid]["lesson_per_change_score"]=1;
                    }
                }else{
                    if($u["lesson_per_change"] >= $avg["lesson_per_change"]){
                        $score_list[$uid]["lesson_per_change_score"] = 3;
                    }else{
                        $score_list[$uid]["lesson_per_change_score"] = 1;
                    }

                }

                if($u["lesson_num_per"]>=15){
                    $score_list[$uid]["lesson_num_per_score"]=5;
                }else if($u["lesson_num_per"]>=10){
                    $score_list[$uid]["lesson_num_per_score"]=3;
                }else if($u["lesson_num_per"]>=5){
                    $score_list[$uid]["lesson_num_per_score"]=1;
                }else{
                    $score_list[$uid]["lesson_num_per_score"]=0;
                }


                $score_list[$uid]["account"] = $u["account"];

            }
            foreach($score_list as &$t){
                $t["total_score"] = $t["interview_time_score"]+$t["interview_per_score"]+$t["record_time_score"]+$t["record_num_score"]+$t["first_per_score"]+$t["add_per_score"]+$t["other_record_time_score"]+$t["lesson_per_score"]+$t["lesson_per_other_score"]+$t["lesson_per_kk_score"]+$t["lesson_per_change_score"]+$t["lesson_num_per_score"];
            }
            \App\Helper\Utils::order_list( $score_list,"total_score", 0);
            // dd($score_list);

        }elseif($type_flag==2){
            $ret_info = $this->get_interview_info_by_time_new($start_time,$end_time);
            $last_info = $this->get_interview_info_by_time_new($last_time,$start_time);
            $str = "subject";
            $res= $ret_info;
            $person_arr = $list_arr=[];
            foreach($res as $kk=>&$vv){
                if(in_array($vv["subject"],[4,5,6,7,8,9,10,200,400])){
                    $interview_per_standard = 23;
                    $first_per_standard = 20;
                    $lesson_per_standard = 22;
                    $lesson_per_other_standard = 75;
                    $lesson_per_kk_standard = 75;
                    $lesson_per_change_standard = 75;
                }else{
                    $interview_per_standard = 18;
                    $first_per_standard = 15;
                    $lesson_per_standard = 17;
                    $lesson_per_other_standard = 70;
                    $lesson_per_kk_standard = 70;
                    $lesson_per_change_standard = 70;
                }
                $vv["interview_per_range"] = $vv["interview_per"]- $interview_per_standard;
                $vv["first_per_range"] = $vv["first_per"]- $first_per_standard;
                $vv["lesson_per_range"] = $vv["lesson_per"]- $lesson_per_standard;
                $vv["lesson_per_other_range"] = $vv["lesson_per_other"]- $lesson_per_other_standard;
                $vv["lesson_per_kk_range"] = $vv["lesson_per_kk"]- $lesson_per_kk_standard;
                $vv["lesson_per_change_range"] = $vv["lesson_per_change"]- $lesson_per_change_standard;
                if(in_array($vv["subject"],[1,2,3,4,5,400])){
                    $person_arr[$kk] = $vv;
                }
                if(in_array($vv["subject"],[2,200,300])){
                    $list_arr[$kk]= $vv;
                }
            }

            foreach($res as $k=>$v){
                if($v["subject_str"]=="平均"){
                    $avg = $res[$k];
                    unset($res[$k]);
                }
            }
            $record_num_standard = round($avg["lesson_num"]/$research_num,1);
            $interview_time_person =  $person_arr;
            foreach($interview_time_person as $k=>$v){
                if($v["interview_time_avg"]==0){
                    unset($interview_time_person[$k]);
                }
            }

            \App\Helper\Utils::order_list( $interview_time_person,"interview_time_avg", 1);
            $interview_time_first_person =  @$interview_time_person[0]["subject"];
            \App\Helper\Utils::order_list(  $person_arr,"interview_per_range", 0);
            $interview_per_first_person =  @$person_arr[0]["subject"];
            $record_time_person = $person_arr;
            foreach( $record_time_person as $k=>$v){
                if($v["record_time_avg"]==0){
                    unset( $record_time_person[$k]);
                }
            }
            \App\Helper\Utils::order_list( $record_time_person,"record_time_avg", 1);
            $record_time_first_person =  @$record_time_person[0]["subject"];
            \App\Helper\Utils::order_list($person_arr,"record_num_all", 0);
            $record_num_first_person = @$person_arr[0]["subject"];
            \App\Helper\Utils::order_list($person_arr,"first_per_range", 0);
            $first_per_first_person = @$person_arr[0]["subject"];
            \App\Helper\Utils::order_list($person_arr,"add_per", 0);
            $add_per_first_person = @$person_arr[0]["subject"];
            $other_record_time_person =$person_arr;
            foreach( $other_record_time_person as $k=>$v){
                if($v["other_record_time_avg"]==0){
                    unset( $other_record_time_person[$k]);
                }
            }
            \App\Helper\Utils::order_list( $other_record_time_person,"other_record_time_avg", 1);
            $other_record_time_first_person =  @$other_record_time_person[0]["subject"];
            \App\Helper\Utils::order_list( $person_arr,"lesson_num_per", 0);
            $lesson_num_per_first_person = @$person_arr[0]["subject"];
            \App\Helper\Utils::order_list( $person_arr,"lesson_per_range", 0);
            $lesson_per_first_person = @$person_arr[0]["subject"];
            \App\Helper\Utils::order_list( $person_arr,"lesson_per_other_range", 0);
            $lesson_per_other_first_person = @$person_arr[0]["subject"];
            \App\Helper\Utils::order_list($person_arr,"lesson_per_kk_range", 0);
            $lesson_per_kk_first_person = @$person_arr[0]["subject"];
            \App\Helper\Utils::order_list( $person_arr,"lesson_per_change_range", 0);
            $lesson_per_change_first_person = @$person_arr[0]["subject"];

            foreach($person_arr as $u){
                $person= $u["subject"];
                if($person==$interview_time_first_person){
                    if($u["interview_time_avg"] <=$avg["interview_time_avg"] && $u["interview_time_avg"]<=$interview_time_standard){
                        $score_list[$person]["interview_time_score"] = 5;
                    }else{
                        $score_list[$person]["interview_time_score"] = 1;
                    }
                }else{
                    if($u["interview_time_avg"] <=$avg["interview_time_avg"] && $u["interview_time_avg"]<=$interview_time_standard && $u["interview_time_avg"] !=0){
                        $score_list[$person]["interview_time_score"] = 3;
                    }else{
                        $score_list[$person]["interview_time_score"] = 1;
                    }

                }
                if($person==$interview_per_first_person){
                    if($u["interview_per"] >=$avg["interview_per"] && $u["interview_per_range"]>=0){
                        $score_list[$person]["interview_per_score"] = 5;
                    }else if($u["interview_per"] >=$avg["interview_per"]){
                        $score_list[$person]["interview_per_score"] = 3;
                    }else{
                        $score_list[$person]["interview_per_score"]=1;
                    }
                }else{
                    if($u["interview_per"] >=$avg["interview_per"]){
                        $score_list[$person]["interview_per_score"] = 3;
                    }else{
                        $score_list[$person]["interview_per_score"] = 1;
                    }

                }
                if($person==$record_time_first_person){
                    if($u["record_time_avg"] <=$avg["record_time_avg"] && $u["record_time_avg"]<=$record_time_standard){
                        $score_list[$person]["record_time_score"] = 5;
                    }else{
                        $score_list[$person]["record_time_score"] = 1;
                    }
                }else{
                    if($u["record_time_avg"] <=$avg["record_time_avg"] && $u["record_time_avg"]<=$record_time_standard && $u["record_time_avg"] !=0){
                        $score_list[$person]["record_time_score"] = 3;
                    }else{
                        $score_list[$person]["record_time_score"] = 1;
                    }

                }

                if($person==$record_num_first_person){
                    if($u["record_num_all"] >=$avg["record_num_all"] && $u["record_num_all"]>=$record_num_standard){
                        $score_list[$person]["record_num_score"] = 5;
                    }else if($u["record_num_all"] >=$avg["record_num_all"] ){
                        $score_list[$person]["record_num_score"] = 3;
                    }else{
                        $score_list[$person]["record_num_score"]=1;
                    }
                }else{
                    if($u["record_num_all"] >=$avg["record_num_all"] ){
                        $score_list[$person]["record_num_score"] = 3;
                    }else{
                        $score_list[$person]["record_num_score"] = 1;
                    }

                }

                if($person==$first_per_first_person){
                    if($u["first_per"] >=$avg["first_per"] && $u["first_per_range"]>=0){
                        $score_list[$person]["first_per_score"] = 5;
                    }else if($u["first_per"] >=$avg["first_per"]){
                        $score_list[$person]["first_per_score"] = 3;
                    }else{
                        $score_list[$person]["first_per_score"]=1;
                    }
                }else{
                    if($u["first_per"] >=$avg["first_per"]){
                        $score_list[$person]["first_per_score"] = 3;
                    }else{
                        $score_list[$person]["first_per_score"] = 1;
                    }

                }
                if($person==$add_per_first_person){
                    if($u["add_per"] >=$avg["add_per"] && $u["add_per"]>=$add_per_standard){
                        $score_list[$person]["add_per_score"] = 5;
                    }else if($u["add_per"] >=$avg["add_per"] ){
                        $score_list[$person]["add_per_score"] = 3;
                    }else{
                        $score_list[$person]["add_per_score"]=1;
                    }
                }else{
                    if($u["add_per"] >=$avg["add_per"] ){
                        $score_list[$person]["add_per_score"] = 3;
                    }else{
                        $score_list[$person]["add_per_score"] = 1;
                    }

                }

                if($person==$other_record_time_first_person){
                    if($u["other_record_time_avg"] <=$avg["other_record_time_avg"] && $u["other_record_time_avg"]<=$other_record_time_standard){
                        $score_list[$person]["other_record_time_score"] = 5;
                    }else{
                        $score_list[$person]["other_record_time_score"] = 1;
                    }
                }else{
                    if($u["other_record_time_avg"] <=$avg["other_record_time_avg"] && $u["other_record_time_avg"]<=$other_record_time_standard && $u["other_record_time_avg"] !=0){
                        $score_list[$person]["other_record_time_score"] = 3;
                    }else{
                        $score_list[$person]["other_record_time_score"] = 1;
                    }

                }
                if($person==$lesson_per_first_person){
                    if($u["lesson_per"] >=$avg["lesson_per"] && $u["lesson_per_range"]>=0){
                        $score_list[$person]["lesson_per_score"] = 25;
                    }else if($u["lesson_per"] >=$avg["lesson_per"]){
                        $score_list[$person]["lesson_per_score"] = 15;
                    }else{
                        $score_list[$person]["lesson_per_score"]=5;
                    }
                }else{
                    if($u["lesson_per"] >=$avg["lesson_per"]){
                        $score_list[$person]["lesson_per_score"] = 15;
                    }else{
                        $score_list[$person]["lesson_per_score"] = 5;
                    }

                }
                if($person==$lesson_per_other_first_person){
                    if($u["lesson_per_other"] >=$avg["lesson_per_other"] && $u["lesson_per_other_range"]>=0){
                        $score_list[$person]["lesson_per_other_score"] = 5;
                    }else if($u["lesson_per_other"] >=$avg["lesson_per_other"]){
                        $score_list[$person]["lesson_per_other_score"] = 3;
                    }else{
                        $score_list[$person]["lesson_per_other_score"]=1;
                    }
                }else{
                    if($u["lesson_per_other"] >=$avg["lesson_per_other"]){
                        $score_list[$person]["lesson_per_other_score"] = 3;
                    }else{
                        $score_list[$person]["lesson_per_other_score"] = 1;
                    }

                }

                if($person==$lesson_per_kk_first_person){
                    if($u["lesson_per_kk"] >=$avg["lesson_per_kk"] && $u["lesson_per_kk_range"]>=0){
                        $score_list[$person]["lesson_per_kk_score"] = 5;
                    }else if($u["lesson_per_kk"] >=$avg["lesson_per_kk"]){
                        $score_list[$person]["lesson_per_kk_score"] = 3;
                    }else{
                        $score_list[$person]["lesson_per_kk_score"]=1;
                    }
                }else{
                    if($u["lesson_per_kk"] >=$avg["lesson_per_kk"]){
                        $score_list[$person]["lesson_per_kk_score"] = 3;
                    }else{
                        $score_list[$person]["lesson_per_kk_score"] = 1;
                    }

                }

                if($person==$lesson_per_change_first_person){
                    if($u["lesson_per_change"] >=$avg["lesson_per_change"] && $u["lesson_per_change_range"]>=0){
                        $score_list[$person]["lesson_per_change_score"] = 5;
                    }else if($u["lesson_per_change"] >=$avg["lesson_per_change"]){
                        $score_list[$person]["lesson_per_change_score"] = 3;
                    }else{
                        $score_list[$person]["lesson_per_change_score"]=1;
                    }
                }else{
                    if($u["lesson_per_change"] >=$avg["lesson_per_change"]){
                        $score_list[$person]["lesson_per_change_score"] = 3;
                    }else{
                        $score_list[$person]["lesson_per_change_score"] = 1;
                    }

                }

                if($u["lesson_num_per"]>=30){
                    $score_list[$person]["lesson_num_per_score"]=5;
                }else if($u["lesson_num_per"]>=20){
                    $score_list[$person]["lesson_num_per_score"]=3;
                }else if($u["lesson_num_per"]>=10){
                    $score_list[$person]["lesson_num_per_score"]=1;
                }else{
                    $score_list[$person]["lesson_num_per_score"]=0;
                }


                $score_list[$person]["subject_str"] = $u["subject_str"];

            }

            $interview_time_list =  $list_arr;
            foreach($interview_time_list as $k=>$v){
                if($v["interview_time_avg"]==0){
                    unset($interview_time_list[$k]);
                }
            }

            \App\Helper\Utils::order_list( $interview_time_list,"interview_time_avg", 1);
            $interview_time_first_list =  @$interview_time_list[0]["subject"];
            \App\Helper\Utils::order_list(  $list_arr,"interview_per_range", 0);
            $interview_per_first_list =  @$list_arr[0]["subject"];
            $record_time_list = $list_arr;
            foreach( $record_time_list as $k=>$v){
                if($v["record_time_avg"]==0){
                    unset( $record_time_list[$k]);
                }
            }
            \App\Helper\Utils::order_list( $record_time_list,"record_time_avg", 1);
            $record_time_first_list =  @$record_time_list[0]["subject"];
            \App\Helper\Utils::order_list($list_arr,"record_num_all", 0);
            $record_num_first_list = @$list_arr[0]["subject"];
            \App\Helper\Utils::order_list($list_arr,"first_per_range", 0);
            $first_per_first_list = @$list_arr[0]["subject"];
            \App\Helper\Utils::order_list($list_arr,"add_per", 0);
            $add_per_first_list = @$list_arr[0]["subject"];
            $other_record_time_list =$list_arr;
            foreach( $other_record_time_list as $k=>$v){
                if($v["other_record_time_avg"]==0){
                    unset( $other_record_time_list[$k]);
                }
            }
            \App\Helper\Utils::order_list( $other_record_time_list,"other_record_time_avg", 1);
            $other_record_time_first_list =  @$other_record_time_list[0]["subject"];
            \App\Helper\Utils::order_list( $list_arr,"lesson_num_per", 0);
            $lesson_num_per_first_list = @$list_arr[0]["subject"];
            \App\Helper\Utils::order_list( $list_arr,"lesson_per_range", 0);
            $lesson_per_first_list = @$list_arr[0]["subject"];
            \App\Helper\Utils::order_list( $list_arr,"lesson_per_other_range", 0);
            $lesson_per_other_first_list = @$list_arr[0]["subject"];
            \App\Helper\Utils::order_list($list_arr,"lesson_per_kk_range", 0);
            $lesson_per_kk_first_list = @$list_arr[0]["subject"];
            \App\Helper\Utils::order_list( $list_arr,"lesson_per_change_range", 0);
            $lesson_per_change_first_list = @$list_arr[0]["subject"];

            foreach($list_arr as $u){
                $list= $u["subject"];
                if($list==2){
                    $list=250;
                }
                if($list==$interview_time_first_list){
                    if($u["interview_time_avg"] <=$avg["interview_time_avg"] && $u["interview_time_avg"]<=$interview_time_standard){
                        $group_score_list[$list]["interview_time_score"] = 5;
                    }else{
                        $group_score_list[$list]["interview_time_score"] = 1;
                    }
                }else{
                    if($u["interview_time_avg"] <=$avg["interview_time_avg"] && $u["interview_time_avg"]<=$interview_time_standard && $u["interview_time_avg"] !=0){
                        $group_score_list[$list]["interview_time_score"] = 3;
                    }else{
                        $group_score_list[$list]["interview_time_score"] = 1;
                    }

                }
                if($list==$interview_per_first_list){
                    if($u["interview_per"] >=$avg["interview_per"] && $u["interview_per_range"]>=0){
                        $group_score_list[$list]["interview_per_score"] = 5;
                    }else if($u["interview_per"] >=$avg["interview_per"]){
                        $group_score_list[$list]["interview_per_score"] = 3;
                    }else{
                        $group_score_list[$list]["interview_per_score"]=1;
                    }
                }else{
                    if($u["interview_per"] >=$avg["interview_per"]){
                        $group_score_list[$list]["interview_per_score"] = 3;
                    }else{
                        $group_score_list[$list]["interview_per_score"] = 1;
                    }

                }
                if($list==$record_time_first_list){
                    if($u["record_time_avg"] <=$avg["record_time_avg"] && $u["record_time_avg"]<=$record_time_standard){
                        $group_score_list[$list]["record_time_score"] = 5;
                    }else{
                        $group_score_list[$list]["record_time_score"] = 1;
                    }
                }else{
                    if($u["record_time_avg"] <=$avg["record_time_avg"] && $u["record_time_avg"]<=$record_time_standard && $u["record_time_avg"] !=0){
                        $group_score_list[$list]["record_time_score"] = 3;
                    }else{
                        $group_score_list[$list]["record_time_score"] = 1;
                    }

                }

                if($list==$record_num_first_list){
                    if($u["record_num_all"] >=$avg["record_num_all"] && $u["record_num_all"]>=$record_num_standard){
                        $group_score_list[$list]["record_num_score"] = 5;
                    }else if($u["record_num_all"] >=$avg["record_num_all"] ){
                        $group_score_list[$list]["record_num_score"] = 3;
                    }else{
                        $group_score_list[$list]["record_num_score"]=1;
                    }
                }else{
                    if($u["record_num_all"] >=$avg["record_num_all"] ){
                        $group_score_list[$list]["record_num_score"] = 3;
                    }else{
                        $group_score_list[$list]["record_num_score"] = 1;
                    }

                }

                if($list==$first_per_first_list){
                    if($u["first_per"] >=$avg["first_per"] && $u["first_per_range"]>=0){
                        $group_score_list[$list]["first_per_score"] = 5;
                    }else if($u["first_per"] >=$avg["first_per"]){
                        $group_score_list[$list]["first_per_score"] = 3;
                    }else{
                        $group_score_list[$list]["first_per_score"]=1;
                    }
                }else{
                    if($u["first_per"] >=$avg["first_per"]){
                        $group_score_list[$list]["first_per_score"] = 3;
                    }else{
                        $group_score_list[$list]["first_per_score"] = 1;
                    }

                }
                if($list==$add_per_first_list){
                    if($u["add_per"] >=$avg["add_per"] && $u["add_per"]>=$add_per_standard){
                        $group_score_list[$list]["add_per_score"] = 5;
                    }else if($u["add_per"] >=$avg["add_per"] ){
                        $group_score_list[$list]["add_per_score"] = 3;
                    }else{
                        $group_score_list[$list]["add_per_score"]=1;
                    }
                }else{
                    if($u["add_per"] >=$avg["add_per"] ){
                        $group_score_list[$list]["add_per_score"] = 3;
                    }else{
                        $group_score_list[$list]["add_per_score"] = 1;
                    }

                }

                if($list==$other_record_time_first_list){
                    if($u["other_record_time_avg"] <=$avg["other_record_time_avg"] && $u["other_record_time_avg"]<=$other_record_time_standard){
                        $group_score_list[$list]["other_record_time_score"] = 5;
                    }else{
                        $group_score_list[$list]["other_record_time_score"] = 1;
                    }
                }else{
                    if($u["other_record_time_avg"] <=$avg["other_record_time_avg"] && $u["other_record_time_avg"]<=$other_record_time_standard && $u["other_record_time_avg"] !=0){
                        $group_score_list[$list]["other_record_time_score"] = 3;
                    }else{
                        $group_score_list[$list]["other_record_time_score"] = 1;
                    }

                }
                if($list==$lesson_per_first_list){
                    if($u["lesson_per"] >=$avg["lesson_per"] && $u["lesson_per_range"]>=0){
                        $group_score_list[$list]["lesson_per_score"] = 25;
                    }else if($u["lesson_per"] >=$avg["lesson_per"]){
                        $group_score_list[$list]["lesson_per_score"] = 15;
                    }else{
                        $group_score_list[$list]["lesson_per_score"]=5;
                    }
                }else{
                    if($u["lesson_per"] >=$avg["lesson_per"]){
                        $group_score_list[$list]["lesson_per_score"] = 15;
                    }else{
                        $group_score_list[$list]["lesson_per_score"] = 5;
                    }

                }
                if($list==$lesson_per_other_first_list){
                    if($u["lesson_per_other"] >=$avg["lesson_per_other"] && $u["lesson_per_other_range"]>=0){
                        $group_score_list[$list]["lesson_per_other_score"] = 5;
                    }else if($u["lesson_per_other"] >=$avg["lesson_per_other"]){
                        $group_score_list[$list]["lesson_per_other_score"] = 3;
                    }else{
                        $group_score_list[$list]["lesson_per_other_score"]=1;
                    }
                }else{
                    if($u["lesson_per_other"] >=$avg["lesson_per_other"]){
                        $group_score_list[$list]["lesson_per_other_score"] = 3;
                    }else{
                        $group_score_list[$list]["lesson_per_other_score"] = 1;
                    }

                }

                if($list==$lesson_per_kk_first_list){
                    if($u["lesson_per_kk"] >=$avg["lesson_per_kk"] && $u["lesson_per_kk_range"]>=0){
                        $group_score_list[$list]["lesson_per_kk_score"] = 5;
                    }else if($u["lesson_per_kk"] >=$avg["lesson_per_kk"]){
                        $group_score_list[$list]["lesson_per_kk_score"] = 3;
                    }else{
                        $group_score_list[$list]["lesson_per_kk_score"]=1;
                    }
                }else{
                    if($u["lesson_per_kk"] >= $avg["lesson_per_kk"]){
                        $group_score_list[$list]["lesson_per_kk_score"] = 3;
                    }else{
                        $group_score_list[$list]["lesson_per_kk_score"] = 1;
                    }

                }

                if($list==$lesson_per_change_first_list){
                    if($u["lesson_per_change"] >=$avg["lesson_per_change"] && $u["lesson_per_change_range"]>=0){
                        $group_score_list[$list]["lesson_per_change_score"] = 5;
                    }else if($u["lesson_per_change"] >=$avg["lesson_per_change"]){
                        $group_score_list[$list]["lesson_per_change_score"] = 3;
                    }else{
                        $group_score_list[$list]["lesson_per_change_score"]=1;
                    }
                }else{
                    if($u["lesson_per_change"] >=$avg["lesson_per_change"]){
                        $group_score_list[$list]["lesson_per_change_score"] = 3;
                    }else{
                        $group_score_list[$list]["lesson_per_change_score"] = 1;
                    }

                }

                if($u["lesson_num_per"]>=30){
                    $group_score_list[$list]["lesson_num_per_score"]=5;
                }else if($u["lesson_num_per"]>=20){
                    $group_score_list[$list]["lesson_num_per_score"]=3;
                }else if($u["lesson_num_per"]>=10){
                    $group_score_list[$list]["lesson_num_per_score"]=1;
                }else{
                    $group_score_list[$list]["lesson_num_per_score"]=0;
                }


                $group_score_list[$list]["subject_str"] = $u["subject_str"];

            }
            foreach($score_list as &$t){
                $t["total_score"] = $t["interview_time_score"]+$t["interview_per_score"]+$t["record_time_score"]+$t["record_num_score"]+$t["first_per_score"]+$t["add_per_score"]+$t["other_record_time_score"]+$t["lesson_per_score"]+$t["lesson_per_other_score"]+$t["lesson_per_kk_score"]+$t["lesson_per_change_score"]+$t["lesson_num_per_score"];
            }
            foreach($group_score_list as &$t){
                $t["total_score"] = $t["interview_time_score"]+$t["interview_per_score"]+$t["record_time_score"]+$t["record_num_score"]+$t["first_per_score"]+$t["add_per_score"]+$t["other_record_time_score"]+$t["lesson_per_score"]+$t["lesson_per_other_score"]+$t["lesson_per_kk_score"]+$t["lesson_per_change_score"]+$t["lesson_num_per_score"];
            }
            //  dd($group_group_score_list);

            \App\Helper\Utils::order_list( $score_list,"total_score", 0);
            \App\Helper\Utils::order_list( $group_score_list,"total_score", 0);



        }
        foreach($last_info as $val){
            foreach($ret_info as &$item){
                if($item[$str] == $val[$str]){
                    $item["last_interview_per"] = $val["interview_per"];
                    $item["last_first_per"] = $val["first_per"];
                    $item["last_add_per"] = $val["add_per"];
                    $item["last_interview_lesson"] = @$val["interview_lesson"];
                    $item["last_interview_order"] = @$val["interview_order"];
                    $item["last_first_lesson"] = @$val["first_lesson"];
                    $item["last_first_order"] = @$val["first_order"];
                    $item["last_first_next_per"] = @$val["first_next_per"];
                    $item["last_next_per"] = @$val["next_per"];
                }
            }
        }
        $ret_info = \App\Helper\Utils::list_to_page_info($ret_info);
        return $this->pageView(__METHOD__ ,$ret_info,[
            "type_flag"   =>$type_flag,
            "score_list"  =>$score_list,
            "group_score_list"  =>$group_score_list,
        ]);

    }
    public function origin_publish_list() {

        list($start_time,$end_time )=$this->get_in_date_range_month( 0 );
        $origin_level= $this->get_in_el_origin_level();
        $opt_date_str="add_time";
        $field_name="origin";
        $origin_ex= $this->get_in_str_val("origin_ex");
        session([
            "ORIGIN_EX"=> "$origin_ex",
        ]);
        $this->t_seller_student_origin->switch_tongji_database();

        $ret_info = $this->t_seller_student_origin->get_origin_tongji_info_single( $field_name,$opt_date_str ,$start_time,$end_time,"",$origin_ex, $origin_level );


        foreach ($ret_info["list"] as &$item ) {
            $item["title"]= $item["check_value"];
            $item["origin"]= $item["title"];
            $item["valid_count"] = $item["all_count"] -$item["tq_no_call_count"]
                                 -$item["tq_call_fail_count"]
                                 -$item["tq_call_succ_invalid_count"]  ;
        }

        if ($field_name=="origin") {
            $ret_info["list"]= $this->gen_origin_data_level5($ret_info["list"],[], $origin_ex);
        }

        // dd($ret_info);
        return $this->pageView(__METHOD__,$ret_info);

    }

    public function origin_publish_kaolagouwu()
    {
        $origin_ex= "活动,考拉";
        $this->set_in_value("origin_ex",  $origin_ex );
        return $this->origin_publish_list();
    }

    public function origin_publish_jinshuju()
    {
        $origin_ex= "公众号,金数据";
        $this->set_in_value("origin_ex",  $origin_ex );
        return $this->origin_publish_list();
    }


    public function origin_publish_bd()
    {
        $origin_ex= "渠道,BD";
        $this->set_in_value("origin_ex",  $origin_ex );

        $acc_id = $this->get_account_id();
        $tmk_not_accept = [287,364,491,501,535,697];//tmk中除 maggie 和 long-张龙 其他人不得查看
        if(in_array($acc_id,$tmk_not_accept)){
            return $this->error_view(
                [
                    "你没有权限查看!"
                ]
            );
        }

        return $this->origin_publish_list();
    }

    public function teacher_trial_count(){
        list($start_time,$end_time) = $this->get_in_date_range_month(0);
        $teacherid          = $this->get_in_int_val("teacherid",-1);
        $teacher_money_type = $this->get_in_int_val("teacher_money_type",-1);
        $subject            = $this->get_in_int_val("subject",-1);

        $list = $this->t_lesson_info->get_teacher_trial_count($start_time,$end_time,$teacherid,$teacher_money_type,$subject);
        foreach($list as &$val){
            $trial_base_money   = \App\Helper\Utils::get_trial_base_price($val['teacher_money_type']);
            $val['trial_money'] = $val['lesson_total']*$trial_base_money;
            E\Eteacher_money_type::set_item_value_str($val);
            E\Esubject::set_item_value_str($val);
        }

        $list = \App\Helper\Utils::list_to_page_info($list);
        return $this->pageView(__METHOD__,$list);
    }

    public function wx_send_use(){

        $start_time = strtotime(date("2017-01-05"));
        $end_time = time();

        $ret = $this->t_teacher_lecture_info->get_new_teacher_test_lesson_info($start_time,$end_time);
        $tea_arr = [];
        foreach($ret as $k=>$v){
            $tea_arr[] = $k;
        }
        $ret_info = $this->t_teacher_lecture_info->get_new_teacher_no_test_lesson_info_wx($tea_arr);
        $i=0;
        foreach($ret_info as $val){
            $this->t_teacher_info->field_update_list($val["teacherid"],["have_test_lesson_flag"=>0]);
            $i++;
        }
        dd($i);



    }

    public function get_teacher_appoinment_lecture_info(){
        // $time = strtotime(date("2017-01-05"));

        //  增加时间筛选

        list($start_time,$end_time) = $this->get_in_date_range_month(0);
        // dd($start_time);
        $time = [
            "start_time" => $start_time,
            "end_time"   => $end_time
        ];

        // dd($time_arr);

        $this->t_teacher_lecture_info->switch_tongji_database();
        $this->t_teacher_lecture_appointment_info->switch_tongji_database();
        // $this->t_lesson_info_b2->switch_tongji_database();
        $this->t_teacher_info->switch_tongji_database();

        $ret = $this->t_teacher_lecture_appointment_info->tongji_teacher_appoinment_lecture_info($time);
        $tea_arr = $this->t_teacher_lecture_appointment_info->get_train_through_tea($time);
        $first_lesson_list = $this->t_lesson_info_b2->get_lesson_tea_num_new($tea_arr,1);
        $fifth_lesson_list = $this->t_lesson_info_b2->get_lesson_tea_num_new($tea_arr,5);

        $app_time = $this->t_teacher_lecture_appointment_info->get_teacher_lecture_time($time);
        $app_time_avg = !empty($app_time["num"])?round($app_time["time"]/$app_time["num"]/86400,1):0;

        $lec_time = $this->t_teacher_lecture_info->get_tea_pass_time($time);
        $lec_time_avg =  !empty($lec_time["num"])?round($lec_time["time"]/$lec_time["num"]/86400,1):0;

        $tran_time = $this->t_teacher_lecture_info->get_tea_tran_pass_time($time);
        $tran_time_avg =  !empty($tran_time["num"])?round($tran_time["time"]/$tran_time["num"]/86400,1):0;

        $first_time =  $this->t_teacher_lecture_info->get_new_teacher_first_lesson_time($time);
        $first_time_avg =  round(($first_time["lesson_time"] - $first_time["confirm_time"])/86400,1);

        $fifth_time =  $this->t_teacher_lecture_info->get_new_teacher_fifth_lesson_time($time);
        $fifth_time_avg =  round(($fifth_time["lesson_time"] - $fifth_time["confirm_time"])/86400,1);

        $tea_limit_info  = $this->t_teacher_info->get_freeze_and_limit_tea_info($time);

        return $this->pageView(__METHOD__,null,[
            "ret"   =>$ret,
            "first_tea_num" =>count($first_lesson_list),
            "fifth_tea_num" =>count($fifth_lesson_list),
            "app_time"    =>$app_time_avg,
            "lec_time"    =>$lec_time_avg,
            "tran_time"    =>$tran_time_avg,
            "fifth_time"  =>$fifth_time_avg,
            "first_time"  =>$first_time_avg,
            "tea_limit_info" =>$tea_limit_info
        ]);
    }



    public function tongji_ass_week_data(){
        list($start_time,$end_time) = $this->get_in_date_range_week(0);
    }



    public function tmk_count(){
        $origin            = trim($this->get_in_str_val("origin",""));
        $origin_ex         = $this->get_in_str_val('origin_ex', "");
        $seller_groupid_ex = $this->get_in_str_val('seller_groupid_ex', "");
        $adminid_list      = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);
        $admin_revisiterid = $this->get_in_int_val("admin_revisiterid", -1);
        $groupid           = $this->get_in_int_val("groupid",-1);
        $tmk_adminid       = $this->get_account_id();
        $origin_level      = $this->get_in_el_origin_level();

        $tmk_access_adminid = [60,188,68,186,349,684,384,323,282,896];
        //wx
        $wx_invaild_flag  = $this->get_in_e_boolean(-1,"wx_invaild_flag");

        if (in_array($tmk_adminid,$tmk_access_adminid)) {
            $tmk_adminid = -1;
        }

        $check_field_id = 4;

        $check_field_config=[
            1=> ["渠道","origin", "" ],
            2=> ["年级","grade", E\Egrade::class ],
            3=> ["科目","subject", E\Esubject::class ],
            4=> ["tmk人员","tmk_adminid", "" ],
            5=> ["销售人员","admin_revisiterid", "" ],
        ];

        $data_map=[];
        $check_item       = $check_field_config[$check_field_id];
        $field_name       = $check_item[1];
        $field_class_name = $check_item[2];

        list($start_time,$end_time ,$opt_date_str)=$this->get_in_date_range_month( date("Y-m-01"), 0, [
            0 => array("tmk_assign_time","微信运营时间"),
            1 => array("add_time","资源进来时间"),
        ] );

        $new_user_info=$this->t_test_lesson_subject->get_seller_new_user_count( $start_time, $end_time ,-1,"", $origin_level ,-1,$wx_invaild_flag);


        $ret_info = $this->t_seller_student_origin->get_tmk_tongji_info($field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid,$origin_level,$wx_invaild_flag);
        $data_map=&$ret_info["list"];

        //dd($ret_info);
        // 处理tmk课程的数量
        $tmk_lesson_num = $this->t_test_lesson_subject_require->get_tmk_lesson_count($field_name,$start_time,$end_time,$tmk_adminid,$origin_level,$wx_invaild_flag);
        // dd($tmk_lesson_num);
        foreach ($tmk_lesson_num as  $tmk_item ) {
            $check_value=$tmk_item["check_value"];
            \App\Helper\Utils:: array_item_init_if_nofind( $data_map, $check_value,["check_value" => $check_value ] );
            $data_map[$check_value]["tmk_count"] = $tmk_item["tmk_count"];
            $data_map[$check_value]["succ_test_lesson_count"] = $tmk_item["succ_test_lesson_count"];
        }

        //合同
        $order_list= $this->t_order_info->tongji_tmk_order_count_origin( $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid,$origin_ex,$origin_level,$wx_invaild_flag);

        // dd($order_list);

        foreach ($order_list as  $order_item ) {
            $check_value=$order_item["check_value"];
            \App\Helper\Utils:: array_item_init_if_nofind( $data_map, $check_value,["check_value" => $check_value ] );
            $data_map[$check_value]["order_count"]     = $order_item["order_count"];
            $data_map[$check_value]["order_all_money"] = number_format($order_item["order_all_money"],2);
        }

        foreach ($data_map as &$item ) {
            if($field_class_name ) {
                $item["title"]= $field_class_name::get_desc($item["check_value"]);
            }else{
                if ($field_name=="tmk_adminid" || $field_name=="admin_revisiterid"  ) {
                    $item["title"]= $this->cache_get_account_nick( $item["check_value"] );
                }else{
                    $item["title"]= $item["check_value"];
                }
            }

            $item["new_user_count"]= @$new_user_info["list"][ $item["check_value"] ]["new_user_count"] *1 ;
            \App\Helper\Utils::logger( "new_count:". $item["check_value"]."-" . $item["new_user_count"] );


            if ($field_name=="origin") {
                $item["origin"]= $item["title"];
            }
        }

        if ($field_name=="origin") {
            $ret_info["list"]= $this->gen_origin_data($ret_info["list"],[], $origin_ex);
        }


        $group_list = $this->t_admin_group_name->get_group_list(2);

        // dd($ret_info);
        return $this->pageView(__METHOD__,$ret_info,[
            "group_list"  => $group_list,
            "field_name"  => $field_name,
        ]);
    }



    public function get_seller_rank_for_jw(){
        $end_time = strtotime(date("Y-m-01",time()));
        $start_time = strtotime(date("Y-m-01",$end_time-100));
        $ret_info= $this->t_order_info->get_1v1_order_seller_list($start_time,$end_time, [-1],"" );

        foreach ($ret_info["list"] as $key=> &$item) {
            $item["index"]=$key+1;
            $item["all_price"] =sprintf("%.2f", $item["all_price"]  );
        }

        return $this->pageView(__METHOD__,$ret_info);

    }


    public function order_tmk_info(){
        $tmk_adminid    = $this->get_in_int_val('adminid');
        $start_time     = strtotime($this->get_in_str_val('start_time'));
        $end_time       = strtotime($this->get_in_str_val('end_time'));
        $page_num       = $this->get_in_page_num();

        $this->t_order_info->switch_tongji_database();

        $ret_info = $this->t_order_info->get_tmk_order_by_adminid( $start_time,$end_time,$tmk_adminid,$page_num );


        foreach($ret_info['list'] as &$tmk_item){
            $tmk_item['cc_nick']         = $this->cache_get_account_nick($tmk_item['check_value']);
            \App\Helper\Utils::unixtime2date_for_item($tmk_item,"order_time");
            \App\Helper\Utils::unixtime2date_for_item($tmk_item,"tmk_assign_time");
            $tmk_item['order_money'] = number_format($tmk_item['order_money'],2);
        }

        return $this->pageView(__METHOD__,$ret_info);
    }


    public function ass_month_info(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);
        $week_info = $this->t_ass_weekly_info->get_all_info($start_time,2);
        $last_month = strtotime(date('Y-m-01',$start_time-100));
        $ass_last_month = $this->t_month_ass_student_info->get_ass_month_info($last_month);
        $lesson_target     = $this->t_ass_group_target->get_rate_target($start_time);
        $assistant_renew_list = $this->t_manager_info->get_all_assistant_renew_list_new($start_time,$end_time);
        //$tran_require_info = $this->t_test_lesson_subject_sub_list->get_tran_require_info($start_time,$end_time);
        $kk_require_info = $this->t_test_lesson_subject_sub_list->get_kk_require_info($start_time,$end_time);
        $ass_list = $this->t_manager_info->get_adminid_list_by_account_role(1);
        $end_info = $this->t_student_info->get_end_class_stu_info($start_time,$end_time);


        $tran_require_info = $this->t_test_lesson_subject_sub_list->tongji_from_ass_test_tran_lesson($start_time,$end_time);
        $agent = $this->t_test_lesson_subject_sub_list->tongji_agent_tran_lesson($start_time,$end_time);
        $ass_month = $this->t_month_ass_student_info->get_ass_month_info($start_time);


        $account_id = $this->get_in_int_val("adminid",-1);
        foreach($ass_list as $k=>&$item){
            $item["warning_student"] = isset($ass_last_month[$k])?$ass_last_month[$k]["warning_student"]:0;
            $item["renw_num"] = isset($assistant_renew_list[$k])?$assistant_renew_list[$k]["renw_num"]:0;
            $item["renw_money"] = isset($assistant_renew_list[$k])?$assistant_renew_list[$k]["renw_price"]/100:0;
            $item["all_money"] = isset($assistant_renew_list[$k])?$assistant_renew_list[$k]["all_price"]/100:0;
            $item["renw_money_one"] = !empty($item["renw_num"])?round($item["renw_money"]/$item["renw_num"],2):0;
            $item["lesson_target"] = $lesson_target ;
            $item["read_student"] = isset($week_info[$k])?$week_info[$k]["read_student"]:0;
            $item["lesson_student"] =  isset($week_info[$k])?$week_info[$k]["lesson_student"]:0;
            $item["lesson_count"] =  isset($week_info[$k])?$week_info[$k]["lesson_count"]/100:0;
            $item["lesson_count_target"] =  $item["read_student"]*$item["lesson_target"];
            $item["teacher_leave_count"] = isset($week_info[$k])?$week_info[$k]["tea_leave_lesson_count"]/100:0;
            $item["student_leave_count"] = isset($week_info[$k])?$week_info[$k]["stu_leave_lesson_count"]/100:0;
            $item["other_count"] = isset($week_info[$k])?$week_info[$k]["other_lesson_count"]/100:0;
            $item["lesson_count_per"] =  isset($week_info[$k])?$week_info[$k]["lesson_count_per"]:0;

            $item["stu_lesson_per"] = isset($week_info[$k])?$week_info[$k]["lesson_per"]:0;

            $item["lesson_money"] = isset($week_info[$k])?$week_info[$k]["lesson_money"]/100:0;

            $item["tran_lesson"] = (isset($tran_require_info[$k])?$tran_require_info[$k]["num"]:0) + (isset($agent[$k])?$agent[$k]["num"]:0);
            $item["tran_order"] = (isset($tran_require_info[$k])?$tran_require_info[$k]["order_num"]:0) + (isset($agent[$k])?$agent[$k]["order_num"]:0);
            $item["tran_money"] = (isset($tran_require_info[$k])?$tran_require_info[$k]["order_money"]/100:0) + (isset($agent[$k])?$agent[$k]["order_money"]/100:0);
            $item["tran_money_one"] = !empty($item["tran_order"])?round($item["tran_money"]/$item["tran_order"],2):0;
            $item["kk_lesson"] = isset($kk_require_info[$k])?$kk_require_info[$k]["num"]:0;
            // $item["kk_succ"] = isset($ass_month[$k])?$ass_month[$k]["kk_num"]:0;
            $item["kk_succ"] = (isset($ass_month[$k])?$ass_month[$k]["kk_num"]:0)+(isset($ass_month[$k])?$ass_month[$k]["hand_kk_num"]:0);

            $item["kk_fail"] = isset($kk_require_info[$k])?$kk_require_info[$k]["fail_num"]:0;
            $item["kk_other"] =  $item["kk_lesson"]-$item["kk_succ"] - $item["kk_fail"];
            $item["refund_student"] = isset($week_info[$k])?$week_info[$k]["refund_student"]:0;
            $item["new_stu_num"] = isset($week_info[$k])?$week_info[$k]["new_stu_num"]:(isset($new_info[$k])?$new_info[$k]["num"]:0);
            $item["end_stu_num"] = isset($week_info[$k])?$week_info[$k]["end_stu_num"]:(isset($end_info[$k])?$end_info[$k]["num"]:0);
            if(empty($item["end_stu_num"]) && isset($end_info[$k])){
                $item["end_stu_num"] = $end_info[$k]["num"];
            }
            $item["refund_money"] = isset($week_info[$k])?$week_info[$k]["refund_money"]/100:0;
            $ass_master_adminid = $this->t_admin_group_user->get_master_adminid_by_adminid($k);
            if($account_id==-1){

            }else{
                if($ass_master_adminid != $account_id){
                    unset($ass_list[$k]);
                }

            }



        }

        $arr=["account"=>"全部"];
        foreach($ass_list as $val){
            @$arr["warning_student"] +=$val["warning_student"];
            @$arr["renw_num"] +=$val["renw_num"];
            @$arr["renw_money"] +=$val["renw_money"];
            @$arr["lesson_target"] =$val["lesson_target"];
            @$arr["read_student"] +=$val["read_student"];
            @$arr["lesson_student"] +=$val["lesson_student"];
            @$arr["lesson_count"] +=$val["lesson_count"];
            @$arr["lesson_count_target"] +=$val["lesson_count_target"];
            @$arr["teacher_leave_count"] +=$val["teacher_leave_count"];
            @$arr["student_leave_count"] +=$val["student_leave_count"];
            @$arr["other_count"] +=$val["other_count"];
            @$arr["lesson_money"] +=$val["lesson_money"];
            @$arr["tran_lesson"] +=$val["tran_lesson"];
            @$arr["tran_order"] +=$val["tran_order"];
            @$arr["tran_money"] +=$val["tran_money"];
            @$arr["kk_lesson"] +=$val["kk_lesson"];
            @$arr["kk_succ"] +=$val["kk_succ"];
            @$arr["kk_fail"] +=$val["kk_fail"];
            @$arr["kk_other"] +=$val["kk_other"];
            @$arr["refund_student"] +=$val["refund_student"];
            @$arr["refund_money"] +=$val["refund_money"];
            @$arr["new_stu_num"] +=$val["new_stu_num"];
            @$arr["end_stu_num"] +=$val["end_stu_num"];
            @$arr["all_money"] +=$val["all_money"];
        }

        $arr["renw_per"] = !empty($arr["warning_student"])?round($arr["renw_num"]/$arr["warning_student"]*100,2):0;
        $arr["renw_money_one"] = !empty($arr["renw_num"])?round($arr["renw_money"]/$arr["renw_num"],2):0;
        $arr["lesson_count_per"] =  !empty($arr["lesson_count_target"])?round($arr["lesson_count"]/$arr["lesson_count_target"]*100,2):0;

        $arr["stu_lesson_per"] = !empty($arr["read_student"])?round($arr["lesson_student"]/$arr["read_student"]*100,2):0;
        $arr["tran_money_one"] = !empty($arr["tran_order"])?round($arr["tran_money"]/$arr["tran_order"],2):0;
        $arr["lesson_personal"] = !empty($arr["lesson_student"])?round($arr["lesson_count"]/$arr["lesson_student"],2):0;
        $arr["refund_per"] = !empty($arr["read_student"])?round($arr["refund_student"]/$arr["read_student"]*100,2):0;
        $arr["renw_target"] = $arr["warning_student"]*0.8*7000;
        $arr["all_money_per"] = !empty($arr["renw_target"])?round($arr["all_money"]/$arr["renw_target"]*100,2):0;

        return $this->pageView(__METHOD__,null,["list"=>$arr]);


        //dd($arr);
    }
    public function ass_weekly_info(){
        $adminid = $this->get_account_id();
        /* if($adminid==349){
            $adminid=297;
            }*/
        //  $adminid = $this->get_ass_leader_account_id($adminid);
        $this->set_in_value("adminid",$adminid);
        return $this-> ass_weekly_info_master();
    }


    public function ass_weekly_info_master(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],2);
        $week_info = $this->t_ass_weekly_info->get_all_info($start_time);
        $month_start = strtotime(date("Y-m-01",$start_time));
        $lesson_target     = $this->t_ass_group_target->get_rate_target($month_start)/4;
        $assistant_renew_list = $this->t_manager_info->get_all_assistant_renew_list_new($start_time,$end_time);
        // $tran_require_info = $this->t_test_lesson_subject_sub_list->get_tran_require_info($start_time,$end_time);
        $kk_require_info = $this->t_test_lesson_subject_sub_list->get_kk_require_info($start_time,$end_time);
        $ass_list = $this->t_manager_info->get_adminid_list_by_account_role(1);
        $new_info = $this->t_student_info->get_refund_info($start_time,$end_time,0);
        $end_info = $this->t_student_info->get_end_class_stu_info($start_time,$end_time);

        //转介绍 转介绍人是助教的,外加优学优享上级介绍人是助教的
        $tran_require_info = $this->t_test_lesson_subject_sub_list->tongji_from_ass_test_tran_lesson($start_time,$end_time);
        $agent = $this->t_test_lesson_subject_sub_list->tongji_agent_tran_lesson($start_time,$end_time);



        $account_id = $this->get_in_int_val("adminid",-1);
        foreach($ass_list as $k=>&$item){
            $item["warning_student"] = isset($week_info[$k])?$week_info[$k]["warning_student"]:0;
            $userid_list = isset($week_info[$k])?$week_info[$k]["warning_student_list"]:"";
            $userid_list = json_decode($userid_list,true);
            if(empty($userid_list)){
                $userid_list=[];
            }
            $assistant_renew_list_plan = $this->t_manager_info->get_all_assistant_renew_list_new($start_time,$end_time,$userid_list,$k);
            $item["renw_num_plan"] = isset($assistant_renew_list_plan[$k]["renw_num"])?$assistant_renew_list_plan[$k]["renw_num"]:0;
            $item["renw_num"] = isset($assistant_renew_list[$k])?$assistant_renew_list[$k]["renw_num"]:0;
            $item["renw_money"] = isset($assistant_renew_list[$k])?$assistant_renew_list[$k]["renw_price"]/100:0;


            // $item["renw_num_plan"] = isset($week_info[$k])?$week_info[$k]["renw_student_in_plan"]:0;
            // $item["renw_num"] = isset($week_info[$k])?$week_info[$k]["renw_student"]:0;
            $item["renw_num_other"] = $item["renw_num"]-$item["renw_num_plan"];
            $item["renw_per"] = !empty($item["warning_student"])?round($item["renw_num"]/$item["warning_student"]*100,2):0;
            // $item["renw_money"] = isset($week_info[$k])?$week_info[$k]["renw_price"]/100:0;
            $item["renw_money_one"] = !empty($item["renw_num"])?round($item["renw_money"]/$item["renw_num"],2):0;
            $item["lesson_target"] = $lesson_target ;
            $item["read_student"] = isset($week_info[$k])?$week_info[$k]["read_student"]:0;
            $item["lesson_student"] =  isset($week_info[$k])?$week_info[$k]["lesson_student"]:0;
            $item["lesson_count"] =  isset($week_info[$k])?$week_info[$k]["lesson_count"]/100:0;
            $item["lesson_count_target"] =  $item["read_student"]*$item["lesson_target"];
            $item["teacher_leave_count"] = isset($week_info[$k])?$week_info[$k]["tea_leave_lesson_count"]/100:0;
            $item["student_leave_count"] = isset($week_info[$k])?$week_info[$k]["stu_leave_lesson_count"]/100:0;
            $item["other_count"] = isset($week_info[$k])?$week_info[$k]["other_lesson_count"]/100:0;
            $item["lesson_count_per"] =  isset($week_info[$k])?$week_info[$k]["lesson_count_per"]:0;

            $item["stu_lesson_per"] = isset($week_info[$k])?$week_info[$k]["lesson_per"]:0;

            $item["lesson_money"] = isset($week_info[$k])?$week_info[$k]["lesson_money"]/100:0;
            $item["tran_lesson"] = (isset($tran_require_info[$k])?$tran_require_info[$k]["num"]:0) + (isset($agent[$k])?$agent[$k]["num"]:0);
            $item["tran_order"] = (isset($tran_require_info[$k])?$tran_require_info[$k]["order_num"]:0) + (isset($agent[$k])?$agent[$k]["order_num"]:0);
            $item["tran_money"] = (isset($tran_require_info[$k])?$tran_require_info[$k]["order_money"]/100:0) + (isset($agent[$k])?$agent[$k]["order_money"]/100:0);

            $item["tran_money_one"] = !empty($item["tran_order"])?round($item["tran_money"]/$item["tran_order"],2):0;
            $item["kk_lesson"] = isset($kk_require_info[$k])?$kk_require_info[$k]["num"]:0;
            $item["kk_succ"] = isset($kk_require_info[$k])?$kk_require_info[$k]["succ_num"]:0;
            $item["kk_fail"] = isset($kk_require_info[$k])?$kk_require_info[$k]["fail_num"]:0;
            $item["kk_other"] =  $item["kk_lesson"]-$item["kk_succ"] - $item["kk_fail"];
            $item["refund_student"] = isset($week_info[$k])?$week_info[$k]["refund_student"]:0;
            $item["new_stu_num"] = isset($week_info[$k])?$week_info[$k]["new_stu_num"]:(isset($new_info[$k])?$new_info[$k]["num"]:0);
            $item["end_stu_num"] = isset($week_info[$k])?$week_info[$k]["end_stu_num"]:(isset($end_info[$k])?$end_info[$k]["num"]:0);
            $item["refund_money"] = isset($week_info[$k])?$week_info[$k]["refund_money"]/100:0;
            $ass_master_adminid = $this->t_admin_group_user->get_master_adminid_by_adminid($k);
            if($account_id==-1){

            }else{
                if($ass_master_adminid != $account_id){
                    unset($ass_list[$k]);
                }

            }



        }

        $arr=["account"=>"全部"];
        foreach($ass_list as $val){
            @$arr["warning_student"] +=$val["warning_student"];
            @$arr["renw_num_plan"] +=$val["renw_num_plan"];
            @$arr["renw_num"] +=$val["renw_num"];
            @$arr["renw_num_other"] +=$val["renw_num_other"];
            @$arr["renw_money"] +=$val["renw_money"];
            @$arr["lesson_target"] =$val["lesson_target"];
            @$arr["read_student"] +=$val["read_student"];
            @$arr["lesson_student"] +=$val["lesson_student"];
            @$arr["lesson_count"] +=$val["lesson_count"];
            @$arr["lesson_count_target"] +=$val["lesson_count_target"];
            @$arr["teacher_leave_count"] +=$val["teacher_leave_count"];
            @$arr["student_leave_count"] +=$val["student_leave_count"];
            @$arr["other_count"] +=$val["other_count"];
            @$arr["lesson_money"] +=$val["lesson_money"];
            @$arr["tran_lesson"] +=$val["tran_lesson"];
            @$arr["tran_order"] +=$val["tran_order"];
            @$arr["tran_money"] +=$val["tran_money"];
            @$arr["kk_lesson"] +=$val["kk_lesson"];
            @$arr["kk_succ"] +=$val["kk_succ"];
            @$arr["kk_fail"] +=$val["kk_fail"];
            @$arr["kk_other"] +=$val["kk_other"];
            @$arr["refund_student"] +=$val["refund_student"];
            @$arr["refund_money"] +=$val["refund_money"];
            @$arr["new_stu_num"] +=$val["new_stu_num"];
            @$arr["end_stu_num"] +=$val["end_stu_num"];
        }

        $arr["renw_per"] = !empty($arr["warning_student"])?round($arr["renw_num"]/$arr["warning_student"]*100,2):0;
        $arr["renw_money_one"] = !empty($arr["renw_num"])?round($arr["renw_money"]/$arr["renw_num"],2):0;
        $arr["lesson_count_per"] =  !empty($arr["lesson_count_target"])?round($arr["lesson_count"]/$arr["lesson_count_target"]*100,2):0;

        $arr["stu_lesson_per"] = !empty($arr["read_student"])?round($arr["lesson_student"]/$arr["read_student"]*100,2):0;
        $arr["tran_money_one"] = !empty($arr["tran_order"])?round($arr["tran_money"]/$arr["tran_order"],2):0;

        return $this->pageView(__METHOD__,null,["list"=>$arr]);


    }

    public function get_refund_student_detail_list(){
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");
        $ret = $this->t_order_refund->get_ass_refund_detail_info($start_time,$end_time);
        foreach($ret as &$item){
            E\Egrade::set_item_value_str($item);
            $item["lesson_left"] = $item["lesson_count_left"]/100;
            $item["real_refund"] = $item["real_refund"]/100;
            $item["order_lesson"] = $item["lesson_total"]*$item["default_lesson_count"]/100;
            $item["sys_operator"] = $this->t_manager_info->get_account($item["refund_userid"]);
        }

        return  $this->output_succ( [ "data" =>$ret] );

    }

    public function get_ass_end_stu_list(){
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");
        $ret = $this->t_student_info->get_end_lesson_stu_list($start_time,$end_time);
        foreach($ret as &$item){
            E\Egrade::set_item_value_str($item);
            $item["lesson_left"] = $item["lesson_count_left"]/100;
        }

        return  $this->output_succ( [ "data" =>$ret] );
    }

    public function get_kk_lesson_detail_list(){
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");
        $ret= $this->t_test_lesson_subject_sub_list->get_kk_require_detail_info($start_time,$end_time);
        foreach($ret as &$item){
            if($item["status"]==1){
                $item["status_str"]="成功";
            }elseif($item["status"]==2){
                $item["status_str"]="失败";
            }else{
                $item["status_str"]="跟进中";
            }

            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);

        }

        return  $this->output_succ( [ "data" =>$ret] );

    }

    public function get_tran_lesson_detail_list(){
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");

        //$ret = $this->t_test_lesson_subject_sub_list->get_tran_require_detail_info($start_time,$end_time);
        $data = $this->t_test_lesson_subject_sub_list->get_from_ass_test_tran_lesson_detail($start_time,$end_time);
        $list = $this->t_test_lesson_subject_sub_list->get_agent_tran_lesson_detail($start_time,$end_time);
        $ret= array_merge($data,$list);
        $arr=[];
        foreach($ret as $item){
            @$arr[$item["userid"]]["userid"] = $item["userid"];
            @$arr[$item["userid"]]["grade"] = $item["grade"];
            @$arr[$item["userid"]]["nick"] = $item["nick"];
            @$arr[$item["userid"]]["account"] = $item["name"];
            @$arr[$item["userid"]]["order_money"] +=$item["price"];
        }
        foreach($arr as &$val){
            if($val["order_money"]<=0){
                $val["status_str"]="未签";
            }else{
                $val["status_str"]= $val["order_money"]/100;
            }

            E\Egrade::set_item_value_str($val);

        }

        return  $this->output_succ( [ "data" =>$arr] );



    }

    public function get_warning_student_detail_list(){
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");
        /*list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],2);
        $start_time = $start_time-21*86400;
        $end_time = $end_time-21*86400;*/
        $week_info = $this->t_ass_weekly_info->get_all_info($start_time);
        $userid_list=[];
        foreach($week_info as $val){
            $user = $val["warning_student_list"];
            $arr = json_decode($user,true);
            if(!empty($arr)){
                foreach($arr as $v){
                    $userid_list[]=$v;
                }
            }
        }
        $ret = $this->t_student_info->get_stu_renw_info($start_time,$end_time,$userid_list);


        foreach($ret as &$item){
            $item["lesson_count_left"] = $item["lesson_count_left"]/100;
            if($item["status"]==0){
                $status = $this->t_month_ass_warning_student_info->get_ass_renw_flag_master_by_userid($item["userid"],$start_time,1);
                if($status==1){
                   $item["status"]="续费";
                }elseif($status==2){
                    $item["status"]="不续费";
                }else{
                    $item["status"]="待跟进";
                }

            }else{
                $item["status"] =  $item["status"]/100;
            }

            E\Egrade::set_item_value_str($item);

        }

        return  $this->output_succ( [ "data" =>$ret] );

    }


    public function tongji_fulltime_teacher_test_lesson_info(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);
        $fulltime_teacher_type = $this->get_in_int_val("fulltime_teacher_type", -1);
        //$end_time = time();
        // $start_time = time()-30*86400;
        // $d = date("m",$end_time-100)- date("m",$start_time+100)+1;
        $m = date("m",$start_time);
        $n = ($end_time - $start_time)/86400/31;
        $d = ($end_time - $start_time)/86400;
        $seller_groupid_ex    = $this->get_in_str_val('seller_groupid_ex', "");
        $adminid_list = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);
        $adminid_right=[0=>"全职老师",1=>"",2=>"",3=>""];
        $ret_info  = $this->t_manager_info->get_research_teacher_list_new(5,$fulltime_teacher_type,$adminid_list);
        $qz_tea_arr=[];
        foreach($ret_info as $yy=>$item){
            if($item["teacherid"] != 97313){
                $qz_tea_arr[] =$item["teacherid"];
            }else{
                unset($ret_info[$yy]);
            }
        }
        $list = $ret_info;
        $lesson_end_time = $this->get_test_lesson_end_time($end_time);

        $qz_tea_list  = $this->t_lesson_info->get_qz_test_lesson_info_list($qz_tea_arr,$start_time,$lesson_end_time);

        $qz_tea_list_kk = $this->t_lesson_info->get_qz_test_lesson_info_list2($qz_tea_arr,$start_time,$lesson_end_time);
        $qz_tea_list_hls = $this->t_lesson_info->get_qz_test_lesson_info_list3($qz_tea_arr,$start_time,$lesson_end_time);
        //整体转化量
        $success_test_lesson_list = $this->t_lesson_info->get_success_test_lesson_list_new($start_time,$end_time,-1,-1,$qz_tea_arr);

        $date_week                         = \App\Helper\Utils::get_week_range(time(),1);
        $week_start = $date_week["sdate"]-14*86400;
        $week_end = $date_week["sdate"]+21*86400;
        $normal_stu_num1 = $this->t_lesson_info_b2->get_tea_stu_num_list($qz_tea_arr,$week_start,$week_end);
        // $normal_stu_num2 = $this->t_week_regular_course->get_tea_stu_num_list($qz_tea_arr);
        //dd($ret_info);
        $lesson_count = $this->t_lesson_info_b2->get_teacher_lesson_count_list($start_time,$end_time,$qz_tea_arr);
        //dd($lesson_count);
        $date                              = \App\Helper\Utils::get_month_range(time(),1);
        $teacher_lesson_count_total        = $this->t_lesson_info->get_teacher_lesson_count_total(time(),$date["edate"],$qz_tea_arr,1);


        $tran_avg= $lesson_avg=[];
        foreach($ret_info as &$item){

            // $item["train_through_new_time_str"]=date("Y-m-d",$item["train_through_new_time"]);
            $item["train_day"] = floor((time()-$item["train_through_new_time"])/86400);
            $item["cc_lesson_num"] =  isset($qz_tea_list[$item["teacherid"]])?$qz_tea_list[$item["teacherid"]]["all_lesson"]:0;
            $item["cc_order_num"] =  isset($qz_tea_list[$item["teacherid"]])?$qz_tea_list[$item["teacherid"]]["order_num"]:0;
            $item["kk_lesson_num"] =  isset($qz_tea_list_kk[$item["teacherid"]])?$qz_tea_list_kk[$item["teacherid"]]["all_lesson"]:0;
            $item["kk_order_num"] =  isset($qz_tea_list_kk[$item["teacherid"]])?$qz_tea_list_kk[$item["teacherid"]]["order_num"]:0;
            $item["hls_lesson_num"] =  isset($qz_tea_list_hls[$item["teacherid"]])?$qz_tea_list_hls[$item["teacherid"]]["all_lesson"]:0;
            $item["hls_order_num"] =  isset($qz_tea_list_hls[$item["teacherid"]])?$qz_tea_list_hls[$item["teacherid"]]["order_num"]:0;
            $item["all_order_num"] =  isset( $success_test_lesson_list[$item["teacherid"]])? $success_test_lesson_list[$item["teacherid"]]["order_number"]:0;

            $item["cc_per"] = !empty($item["cc_lesson_num"])?round($item["cc_order_num"]/$item["cc_lesson_num"]*100,2):0;
            $item["kk_per"] = !empty($item["kk_lesson_num"])?round($item["kk_order_num"]/$item["kk_lesson_num"]*100,2):0;
            $item["hls_per"] = !empty($item["hls_lesson_num"])?round($item["hls_order_num"]/$item["hls_lesson_num"]*100,2):0;
            $item["lesson_all"] = $item["cc_lesson_num"]+$item["kk_lesson_num"]+$item["hls_lesson_num"];
            $item["order_all"] = $item["cc_order_num"]+$item["kk_order_num"]+$item["hls_order_num"];
            $item["all_per"] = !empty($item["lesson_all"])?round($item["order_all"]/$item["lesson_all"]*100,2):0;
            $item["lesson_per"] = $item["lesson_all"]/$d*100;
            if( $item["lesson_per"]>100){
                $item["lesson_per"]=100;
            }
            $item["kk_hls_per"] =  !empty($item["kk_lesson_num"]+$item["hls_lesson_num"])?round(($item["kk_order_num"]+$item["hls_order_num"])/($item["kk_lesson_num"]+$item["hls_lesson_num"])*100,2):0;

            $item["cc_score"] = round($item["cc_per"]*0.75,2);

            $item["kk_hls_score"] = round($item["kk_hls_per"]*0.1,2);
            // $item["hls_score"] = round($item["hls_per"]*0.05,2);
            //$item["lesson_score"] = round($item["lesson_per"]*0.1,2);
            $item["all_score"] = round($item["all_per"]*0.15,2);
            if($item["cc_lesson_num"]>10){
                $cc_num=10;
            }else{
                $cc_num = $item["cc_lesson_num"];
            }

            $item["score"] =  round(($item["cc_score"]+ $item["kk_hls_score"] +$item["all_score"])*$cc_num/10,2);
            if($item["score"]>=95){
                $item["reward"] = 1000;
            }elseif($item["score"]>=85){
                $item["reward"] = 800;
            }elseif($item["score"]>=75){
                $item["reward"] = 600;
            }elseif($item["score"]>=65){
                $item["reward"] = 400;
            }elseif($item["score"]>=55){
                $item["reward"] = 200;
            }



            @$tran_avg["cc_lesson_num"] +=$item["cc_lesson_num"];
            @$tran_avg["cc_order_num"] +=$item["cc_order_num"];
            @$tran_avg["kk_lesson_num"] +=$item["kk_lesson_num"];
            @$tran_avg["kk_order_num"] +=$item["kk_order_num"];
            @$tran_avg["hls_lesson_num"] +=$item["hls_lesson_num"];
            @$tran_avg["hls_order_num"] +=$item["hls_order_num"];
            @$tran_avg["lesson_all"] +=$item["lesson_all"];
            @$tran_avg["order_all"] +=$item["order_all"];
            @$tran_avg["all_order_num"] +=$item["all_order_num"];

        }

        if($m>6 && $m <9){
            $m1 =264;$m2=252;$m3=228;
        }else{

            $m1 =220;$m2=210;$m3=190;
        }
        foreach($list as &$val){
            $val["train_day"] = floor((time()-$val["train_through_new_time"])/86400);
            // $val["train_through_new_time_str"]=date("Y-m-d",$val["train_through_new_time"]);
            $val["normal_stu"] = isset($normal_stu_num1[$val["teacherid"]])?$normal_stu_num1[$val["teacherid"]]["num"]:0;
            $val["week_count"] = isset($normal_stu_num1[$val["teacherid"]])?round($normal_stu_num1[$val["teacherid"]]["lesson_all"]/500):0;
            $val["lesson_count"] = isset($lesson_count[$val["teacherid"]])?$lesson_count[$val["teacherid"]]["lesson_all"]/100:0;
            $val["lesson_count_avg"] = round($val["lesson_count"]/$n,2);
            $grade = $this->t_teacher_info->get_grade_part_ex($val["teacherid"]);
            if($grade==1){
                $num=$m1;
            }elseif($grade==2){
                $num=$m2;
            }elseif($grade==3){
                $num=$m3;
            }elseif($grade==4 || $grade==6){
                $s = $this->t_lesson_info_b2->get_teacher_lesson_grade_count($start_time,$end_time,$val["teacherid"],1);
                $m = $this->t_lesson_info_b2->get_teacher_lesson_grade_count($start_time,$end_time,$val["teacherid"],2);
                $per = !empty($s+$m)?$s/($s+$m):0;
                if($per >= 0.3){
                    $num=$m1;
                }else{
                    $num=$m2;
                }
            }elseif($grade==5 || $grade==7){
                $s = $this->t_lesson_info_b2->get_teacher_lesson_grade_count($start_time,$end_time,$val["teacherid"],2);
                $m = $this->t_lesson_info_b2->get_teacher_lesson_grade_count($start_time,$end_time,$val["teacherid"],3);
                $per = !empty($s+$m)?$s/($s+$m):0;
                if($per >= 0.3){
                    $num=$m2;
                }else{
                    $num=$m3;
                }

            }else{
                $num=200;
            }
            $val["lesson_per"] = round($val["lesson_count"]/$num*100,2);
            $val["lesson_per_month"] = round($val["lesson_count"]/$num/$n*100,2);
            $val["lesson_count_left"] = isset($teacher_lesson_count_total[$val["teacherid"]])?$teacher_lesson_count_total[$val["teacherid"]]["lesson_total"]:0;
            @$lesson_avg["normal_stu"] +=$val["normal_stu"];
            @$lesson_avg["week_count"] +=$val["week_count"];
            @$lesson_avg["lesson_count"] +=$val["lesson_count"];
            @$lesson_avg["lesson_count_avg"] +=$val["lesson_count_avg"];
            @$lesson_avg["lesson_per"] +=$val["lesson_per"];
            @$lesson_avg["lesson_per_month"] +=$val["lesson_per_month"];
            @$lesson_avg["lesson_count_left"] +=$val["lesson_count_left"];
            if($val["lesson_per"]>=140){
                $val["reward"] = 500;
            }elseif($val["lesson_per"]>=130){
                $val["reward"] = 400;
            }elseif($val["lesson_per"]>=120){
                $val["reward"] = 300;
            }elseif($val["lesson_per"]>=110){
                $val["reward"] = 200;
            }elseif($val["lesson_per"]>=100){
                $val["reward"] = 100;
            }

        }
        \App\Helper\Utils::order_list( $ret_info,"score", 0);
        foreach($ret_info as $k=>&$uk){
            if($k==0 && $uk["score"]>=30){
                $uk["other_reward"]=300;
            }elseif($k==1 && $uk["score"]>=30){
                $uk["other_reward"]=200;
            }else if($k==2 && $uk["score"]>=30){
                $uk["other_reward"]=100;
            }


        }
        \App\Helper\Utils::order_list( $list,"lesson_per", 0);
        foreach($list as $ku=>&$ukk){
            if($ku==0 && $ukk["lesson_per"]>=80){
                $ukk["other_reward"]=200;
            }elseif($ku==1 && $ukk["lesson_per"]>=80){
                $ukk["other_reward"]=150;
            }else if($ku==2 && $ukk["lesson_per"]>=80){
                $ukk["other_reward"]=100;
            }


        }

        $tran_count = count($ret_info);
        $lesson_count = count($list);
        $tran_all = $tran_avg;
        $lesson_all = $lesson_avg;
        foreach($tran_avg as $pp=>&$rr){
            $rr = round($rr/$tran_count,2);
        }
        $tran_avg["cc_per"] = !empty($tran_avg["cc_lesson_num"])?round($tran_avg["cc_order_num"]/$tran_avg["cc_lesson_num"]*100,2):0;
        $tran_avg["kk_per"] = !empty($tran_avg["kk_lesson_num"])?round($tran_avg["kk_order_num"]/$tran_avg["kk_lesson_num"]*100,2):0;
        $tran_avg["hls_per"] = !empty($tran_avg["hls_lesson_num"])?round($tran_avg["hls_order_num"]/$tran_avg["hls_lesson_num"]*100,2):0;
        $tran_avg["all_per"] = !empty($tran_avg["lesson_all"])?round($tran_avg["order_all"]/$tran_avg["lesson_all"]*100,2):0;
        $tran_avg["realname"]="平均";
        $tran_all["cc_per"] = !empty($tran_all["cc_lesson_num"])?round($tran_all["cc_order_num"]/$tran_all["cc_lesson_num"]*100,2):0;
        $tran_all["kk_per"] = !empty($tran_all["kk_lesson_num"])?round($tran_all["kk_order_num"]/$tran_all["kk_lesson_num"]*100,2):0;
        $tran_all["hls_per"] = !empty($tran_all["hls_lesson_num"])?round($tran_all["hls_order_num"]/$tran_all["hls_lesson_num"]*100,2):0;
        $tran_all["all_per"] = !empty($tran_all["lesson_all"])?round($tran_all["order_all"]/$tran_all["lesson_all"]*100,2):0;

        $tran_all["realname"]="全部";

        foreach($lesson_avg as $mm=>&$ss){
            if($mm=="lesson_count_left"){
                $ss = round($ss/$lesson_count);
            }else{
                $ss = round($ss/$lesson_count,2);
            }
        }
        $lesson_avg["realname"]="平均";
        $lesson_all["lesson_per"] = @$lesson_avg["lesson_per"];
        $lesson_all["lesson_per_month"] = @$lesson_avg["lesson_per_month"];
        $lesson_all["realname"]="全部";

        array_push($ret_info,$tran_avg);
        array_push($ret_info,$tran_all);
        array_push($list,$lesson_avg);
        array_push($list,$lesson_all);
        return $this->pageView(__METHOD__,null,[
            "ret_info"=>$ret_info,
            "list"=>$list,
            "adminid_right"     => $adminid_right
        ]);

    }


    public function origin_publish_bd_vaild() {

        list($start_time,$end_time )=$this->get_in_date_range_month( 0 );
        $opt_date_str="add_time";
        $field_name="origin";
        // $origin_ex= $this->get_in_str_val("origin_ex");

        $origin_ex = 'BD';
        session([
            "ORIGIN_EX"=> "$origin_ex",
        ]);
        $this->t_seller_student_origin->switch_tongji_database();

        $ret_info = $this->t_seller_student_origin->get_origin_tongji_info_vaild( $field_name,$opt_date_str ,$start_time,$end_time,"",$origin_ex,"");

        foreach ($ret_info["list"] as &$item ) {
            $item["title"]= $item["check_value"];
            $item["origin"]= $item["title"];
        }

        if ($field_name=="origin") {
            $ret_info["list"]= $this->gen_origin_data($ret_info["list"],[], $origin_ex);
        }



        return $this->pageView(__METHOD__,$ret_info);

    }


    public function tongji_zs_reference(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],1);
        $name = $this->get_in_str_val("name","");
        $list = $this->t_teacher_lecture_appointment_info->tongji_zs_reference_info($start_time,$end_time,$name);
        $arr=0;
        foreach($list as $val){
            $arr +=$val["num"];
        }
        $all = ["realname"=>"全部","num"=>$arr];
        array_unshift($list,$all);

        $list = \App\Helper\Utils::list_to_page_info($list);
        return $this->pageView(__METHOD__,$list);
    }


    public function tongji_teacher_1v1_lesson_time(){
        $this->switch_tongji_database();
        $start_time = strtotime("2017-06-18");
        $end_time = strtotime("2017-08-01");
        $ret= $this->t_lesson_info_b2->tongji_1v1_lesson_time($start_time,$end_time);
        $late= $this->t_lesson_info_b2->tongji_1v1_lesson_time_late($start_time,$end_time);
        $late_list=[];
        foreach($late as $e){
            if(!in_array($e["teacherid"],[176999,190394])){
                @$late_list[$e["teacherid"]] +=$e["time"];
            }
        }
        $other_time = $this->t_lesson_info_b2->tongji_1v1_lesson_time_morning($start_time,$end_time);
        foreach($other_time as $ttt){
             @$late_list[$ttt["teacherid"]] +=$ttt["time"];
        }
        $arr=[];$week=[];
        foreach($ret as $val){
            $teacherid = $val["teacherid"];
            @$arr[$teacherid][$val["day"]] = $val["time"];
            @$arr[$teacherid]["realname"] = $val["realname"];
            if($val["week"]==0){

                @$week[$teacherid]["seven"] +=$val["time"];
            }elseif($val["week"]==1){
                @$week[$teacherid]["one"] +=$val["time"];
            }
            @$week[$teacherid]["all"] +=$val["time"];


        }

        $all=["realname"=>"全部"];
        foreach($arr as $k=>&$item){
            $item["seven"] = @$week[$k]["seven"];
            $item["one"] = @$week[$k]["one"];
            $item["all"] = @$week[$k]["all"];
            $item["late"] = @$late_list[$k];
            $item["total"] = $item["all"]+$item["late"];
            @$all["20170618"] += @$item["20170618"];
            @$all["20170619"] += @$item["20170619"];
            @$all["20170625"] += @$item["20170625"];
            @$all["20170626"] += @$item["20170626"];
            @$all["20170702"] += @$item["20170702"];
            @$all["20170703"] += @$item["20170703"];
            @$all["20170709"] += @$item["20170709"];
            @$all["20170710"] += @$item["20170710"];
            @$all["20170716"] += @$item["20170716"];
            @$all["20170717"] += @$item["20170717"];
            @$all["20170717"] += @$item["20170723"];
            @$all["20170717"] += @$item["20170724"];
            @$all["20170717"] += @$item["20170730"];
            @$all["20170717"] += @$item["20170731"];
            @$all["seven"] += @$item["seven"];
            @$all["one"] += @$item["one"];
            @$all["all"] += @$item["all"];
            @$all["late"] += @$item["late"];
            @$all["total"] += @$item["total"];
        }
        array_unshift($arr,$all);

        foreach($arr as &$v){
            foreach($v as $s=>$kk){
                if(!in_array($s,["realname","one","seven","all","late","total"])){
                    $v[$s] = !empty($kk)?round($kk/3600,1)."小时":"";
                }
            }
            $v["seven_hour"] =  !empty($v["seven"])?round($v["seven"]/3600,1)."小时":"";
            $v["seven_day"] = !empty($v["seven"])?round($v["seven"]/3600/8,2)."天":"";
            $v["one_hour"] = !empty($v["one"])?round($v["one"]/3600,1)."小时":"";
            $v["one_day"] = !empty($v["one"])?round($v["one"]/3600/8,2)."天":"";
            $v["all_hour"] = !empty($v["all"])?round($v["all"]/3600,1)."小时":"";
            $v["all_day"] = !empty($v["all"])?round($v["all"]/3600/8,2)."天":"";
            $v["late_hour"] = !empty($v["late"])?round($v["late"]/3600,1)."小时":"";
            $v["late_day"] = !empty($v["late"])?round($v["late"]/3600/8,2)."天":"";
            $v["total_hour"] = !empty($v["total"])?round($v["total"]/3600,1)."小时":"";
            $v["total_day"] = !empty($v["total"])?round($v["total"]/3600/8,2)."天":"";

        }
        $ret_info = \App\Helper\Utils::list_to_page_info($arr);
        return $this->pageView(__METHOD__,$ret_info);

    }


    public function tongji_change_teacher_info(){// 换老师统计
        $this->switch_tongji_database();

        $change_teacher_reason_type  = $this->get_in_int_val('change_teacher_reason_type',-1);
        list($start_time,$end_time)  = $this->get_in_date_range(0,0,0,null,3);
        $account_id = $this->get_account_id();

        // $root_adminid_arr = ['60','72','188','303','323','68','186','349','448','507','684','831','944','1040'];
        $account_role = $this->get_account_role();
        $master_flag = 0;
        $role_arr = ['12','22']; // 研发|师资
        if(in_array($account_role,$role_arr) || in_array($account_id,[889])){
            $master_flag = 1;
        }


        $ass_list = $this->t_admin_group_name->get_group_admin_list($account_id);
        if(empty($ass_list)){
            $adminid_str = $account_id;
        }else{
            $ass_list = array_column($ass_list,'adminid');
            $adminid_str = implode(',',$ass_list);
        }

        $page_info = $this->get_in_page_info();

        $ret_info = $this->t_test_lesson_subject_sub_list->get_ass_require_test_lesson_info_change_teacher($page_info,$start_time,$end_time,$adminid_str,$master_flag,$change_teacher_reason_type,$account_id);

        foreach($ret_info['list'] as &$item){
            E\Echange_teacher_reason_type::set_item_value_str($item,"change_teacher_reason_type");
            E\Egrade::set_item_value_str($item,"grade");
            E\Esubject::set_item_value_str($item,"subject");

            $old_teacher_arr = $this->t_lesson_info_b2->get_old_teacher_nick($item['lesson_start'],$item['subject'],$item['userid']);
            $item['old_teacher_nick'] = $old_teacher_arr['nick'];
            $item['old_teacher_id'] = $old_teacher_arr['teacherid'];

            if($item['success_flag'] == 0){
                $item['success_flag_str'] = "<font color=\"blue\">未设置</font>";
            }elseif($item['success_flag'] == 1){
                $item['success_flag_str'] = "<font color=\"green\">成功</font>";
            }elseif($item['success_flag'] == 2){
                $item['success_flag_str'] = "<font color=\"red\">失败</font>";
            }

            $item['ass_nick'] = $this->cache_get_account_nick($item['require_adminid']);
            $item['test_lesson_time'] = date('Y-m-d H:i:s',$item['lesson_start']);

            $replace_str = 'http://7u2f5q.com2.z0.glb.qiniucdn.com/';
            $img_url_flag = str_replace($replace_str,'',$item['change_teacher_reason_img_url']);
            if(!$img_url_flag){
                $item['change_teacher_reason_img_url'] = '';
            }

            if($item['order_confirm_flag'] == 0){
                $item['order_confirm_flag_str'] = "<font color=\"blue\">未设置</font>";
            }elseif($item['order_confirm_flag'] == 1){
                $item['order_confirm_flag_str'] = "<font color=\"green\">成功</font>";
            }elseif($item['order_confirm_flag'] == 2){
                $item['order_confirm_flag_str'] = "<font color=\"red\">失败</font>";
            }
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function tongji_kuoke_info(){ // 扩课统计
        $this->switch_tongji_database();
        list($start_time,$end_time)  = $this->get_in_date_range(0,0,0,null,3);
        $page_info  = $this->get_in_page_info();
        $account_id = $this->get_account_id();

        $root_adminid_arr = ['60','72','188','303','323','68','186','349','448','507','684','831','944'];
        $master_flag = 0;
        if(in_array($account_id,$root_adminid_arr)){
            $master_flag = 1;
        }

        $ass_list = $this->t_admin_group_name->get_group_admin_list($account_id);

        if(empty($ass_list)){
            $adminid_str = $account_id;
        }else{
            $ass_list = array_column($ass_list,'adminid');
            $adminid_str = implode(',',$ass_list);
        }


        $ret_info = $this->t_test_lesson_subject_sub_list->get_ass_require_test_lesson_info_by_kuoke($page_info,$start_time,$end_time,$adminid_str,$master_flag);

        foreach($ret_info["list"] as &$item){
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            E\Eregion_version::set_item_value_str($item,"editionid");
            if(!empty($item["textbook"])){
                $item["editionid_str"] = $item["textbook"];
            }
            $item['ass_nick'] = $this->cache_get_account_nick($item['require_adminid']);
            E\Etest_lesson_fail_flag::set_item_value_str($item);

            if($item['success_flag'] == 0){
                $item['success_flag_str'] = "<font color=\"blue\">未设置</font>";
            }elseif($item['success_flag'] == 1){
                $item['success_flag_str'] = "<font color=\"green\">成功</font>";
            }elseif($item['success_flag'] == 2){
                $item['success_flag_str'] = "<font color=\"red\">失败</font>";
            }

            if($item['order_confirm_flag'] == 0){
                $item['order_confirm_flag_str'] = "<font color=\"blue\">未设置</font>";
            }elseif($item['order_confirm_flag'] == 1){
                $item['order_confirm_flag_str'] = "<font color=\"green\">成功</font>";
            }elseif($item['order_confirm_flag'] == 2){
                $item['order_confirm_flag_str'] = "<font color=\"red\">失败</font>";
            }

            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start","","Y-m-d H:i");
        }

        return $this->pageView(__METHOD__, $ret_info);
    }


    public function tongji_referral(){// 转介绍
        list($start_time,$end_time)  = $this->get_in_date_range(0,0,0,null,3);
        $page_info = $this->get_in_page_info();
        $account_id = $this->get_account_id();

        $root_adminid_arr = ['60','72','188','303','323','68','186','349','448','507','684','831','944'];
        $master_flag = 0;
        if(in_array($account_id,$root_adminid_arr)){
            $master_flag = 1;
        }

        $ass_list = $this->t_admin_group_name->get_group_admin_list($account_id);

        if(empty($ass_list)){
            $adminid_str = $account_id;
        }else{
            $ass_list = array_column($ass_list,'adminid');
            $adminid_str = implode(',',$ass_list);
        }

        $ret_info = $this->t_test_lesson_subject_sub_list->get_ass_require_test_lesson_info_by_referral($page_info,$start_time,$end_time,$adminid_str,$master_flag);

        foreach( $ret_info['list'] as &$item){
            $item['ass_nick']     =  $this->cache_get_account_nick($item["require_adminid"]) ;
            E\Egrade::set_item_value_str($item,"grade");
            E\Esubject::set_item_value_str($item,"subject");

            if($item['success_flag'] == 0){
                $item['success_flag_str'] = "<font color=\"blue\">未设置</font>";
            }elseif($item['success_flag'] == 1){
                $item['success_flag_str'] = "<font color=\"green\">成功</font>";
            }elseif($item['success_flag'] == 2){
                $item['success_flag_str'] = "<font color=\"red\">失败</font>";
            }

            $is_lesson_time_flag = $this->t_lesson_info_b2->get_lesson_time_flag($item['userid'],$item['teacherid']);


            if($item['order_confirm_flag'] == 0){
                $item['order_confirm_flag_str'] = "<font color=\"blue\">未设置</font>";
            }elseif($item['order_confirm_flag'] == 1){
                $item['order_confirm_flag_str'] = "<font color=\"green\">成功</font>";
            }elseif($item['order_confirm_flag'] == 2){
                $item['order_confirm_flag_str'] = "<font color=\"red\">失败</font>";
            }

            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start","","Y-m-d H:i");
        }

        // dd($ret_info);
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function tongji_change_lesson_by_teacher_jy(){ // 显示兼职老师信息
        $this->set_in_value('is_full_time',1);
        return $this->tongji_change_lesson_for_jy();
    }


    public function tongji_change_lesson_by_full_time_teacher_jy(){ // 全职老师信息
        $this->set_in_value('is_full_time',2);
        return $this->tongji_change_lesson_for_jy();
    }


    public function tongji_change_lesson_for_jy(){
        // return $this->tongji_change_lesson_by_teacher();

        $is_full_time = $this->get_in_int_val('is_full_time');

        $teacher_money_type = $this->get_in_int_val('teacher_money_type',-1);

        $page_num = $this->get_in_page_num();
        $this->switch_tongji_database();
        // $is_full_time = 1;  // 显示兼职老师
        // $this->switch_tongji_database();
        $sum_field_list=[
            "stu_num",
            "valid_count",
            "teacher_come_late_count",
            "teacher_cut_class_count",
            "teacher_change_lesson",
            "teacher_leave_lesson",
            "teacher_money_type",
            "lesson_cancel_reason_type",
        ];
        $order_field_arr=  array_merge(["teacher_nick" ] ,$sum_field_list );

        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str($order_field_arr ,"teacher_nick desc");
        $assistantid= $this->get_in_int_val("assistantid",-1);

        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);

        //权限写死,Erick要求
        $adminid = $this->get_account_id();
        if(in_array($adminid,[72,967])){
            $show_all_flag=1;
        }else{
            $teacher_money_type=6;
            $show_all_flag=0;
        }

        $this->switch_tongji_database();
        $ret_info = $this->t_lesson_info_b2->get_lesson_info_teacher_tongji_jy($start_time,$end_time,$is_full_time,$teacher_money_type,$show_all_flag );
        $this->switch_tongji_database();
        $stu_num_all = $this->t_lesson_info_b2->get_lesson_info_teacher_tongji_jy_stu_num($start_time,$end_time,$is_full_time,$teacher_money_type);

        // dd($ret_info);
        foreach($ret_info as &$item_list){
            $item_list['teacher_nick'] = $this->cache_get_teacher_nick($item_list['teacherid']);

            if($item_list['train_through_new_time'] !=0){
                $item_list["work_time"] = ceil((time()-$item_list["train_through_new_time"])/86400);
            }else{
                $item_list["work_time"] = 0;
            }

            if($item_list['valid_count']>0){
                $item_list['lesson_leavel_rate'] = number_format(($item_list['teacher_leave_lesson']/$item_list['valid_count'])*100,2);
                $item_list['lesson_come_late_rate'] = number_format(($item_list['teacher_come_late_count']/$item_list['valid_count'])*100,2);
                $item_list['lesson_cut_class_rate'] = number_format(($item_list['teacher_cut_class_count']/$item_list['valid_count'])*100,2);
                $item_list['lesson_change_rate'] = number_format(($item_list['teacher_change_lesson']/$item_list['valid_count'])*100,2);
            }else{
                $item_list['lesson_leavel_rate'] = 0;
                $item_list['lesson_come_late_rate'] = 0;
                $item_list['lesson_cut_class_rate'] = 0;
                $item_list['lesson_change_rate'] = 0;
            }

            E\Eteacher_money_type::set_item_value_str($item_list);
        }

        $all_item=["teacher_nick" => "全部" ];
        foreach ($ret_info as &$item) {
            foreach ($item as $key => $value) {
                if ((!is_int($key)) && (($key == "stu_num") || ($key =="valid_count") || ($key == "teacher_come_late_count") || ($key == "teacher_cut_class_count") || ($key =="teacher_change_lesson")||($key == 'teacher_leave_lesson') || ($key == "work_time") )) {
                    $all_item[$key]=(@$all_item[$key])+$value;
                }
            }
        }

        if (!$order_in_db_flag) {
            \App\Helper\Utils::order_list( $ret_info, $order_field_name, $order_type );
        }

        if($is_full_time == 1){
            $all_item['teacher_money_type_str'] = "兼职老师";
        }elseif($is_full_time == 2){
            $all_item['teacher_money_type_str'] = "全职老师";
        }


        $teacher_num = count($ret_info);
        if($teacher_num>0){
            $all_item['work_time'] = number_format($all_item['work_time']/$teacher_num,2);
            $all_item['lesson_leavel_rate'] = number_format($all_item['teacher_leave_lesson']/$all_item['valid_count']*100,2);
            $all_item['lesson_come_late_rate'] = number_format($all_item['teacher_come_late_count']/$all_item['valid_count']*100,2);
            $all_item['lesson_cut_class_rate'] = number_format($all_item['teacher_cut_class_count']/$all_item['valid_count']*100,2);
            $all_item['lesson_change_rate'] = number_format($all_item['teacher_change_lesson']/$all_item['valid_count']*100,2);
        }else{
            $all_item['work_time'] = 0;
            $all_item['lesson_leavel_rate'] = 0;
            $all_item['lesson_come_late_rate'] = 0;
            $all_item['lesson_cut_class_rate'] = 0;
            $all_item['lesson_change_rate'] = 0;
        }


        if($show_all_flag==1){
            array_unshift($ret_info, $all_item);
        }
        $index_num=0;
        foreach($ret_info as &$p_item){
            $p_item["index_num"] = $index_num;
            $index_num++;

            if($p_item["teacher_nick"]=="全部"){
                $p_item["stu_num"]=$stu_num_all;
                $p_item["index_num"]=0;
            }
        }
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info) ,[
            "data_ex_list"=>$ret_info,
            "show_all_flag" =>$show_all_flag
        ]);

    }




    public function tongji_change_lesson_by_teacher(){ // 调课统计-老师
        $this->switch_tongji_database();
        list($start_time,$end_time)  = $this->get_in_date_range(0,0,0,null,3);
        $page_info = $this->get_in_page_info();

        $lesson_cancel_reason_type = $this->get_in_int_val('lesson_cancel_reason_type',-1);

        $ret_info = $this->t_lesson_info_b2->get_lesson_cancel_info_by_teacher($start_time,$end_time,$page_info,$lesson_cancel_reason_type);

        // dd($ret_info);
        foreach($ret_info['list'] as $index=> &$item_list){
            if($item_list['lesson_count_total'] == 0){
                unset($ret_info['list'][$index]);
            }
            $item_list['teacher_nick'] = $this->cache_get_teacher_nick($item_list['teacherid']);

            if($item_list['train_through_new_time'] !=0){
                $item_list["work_time"] = ceil((time()-$item_list["train_through_new_time"])/86400)."天";
            }else{
                $item_list["work_time"] = 0;
            }

            E\Eteacher_money_type::set_item_value_str($item_list);
        }

        // dd($ret_info);
        \App\Helper\Common::sortArrByField($ret_info['list'],'lesson_count_total',true);
        return $this->pageView(__METHOD__,$ret_info);
    }




    public function tongji_change_lesson_by_parent(){ // 调课统计-家长
        list($start_time,$end_time)  = $this->get_in_date_range(0,0,0,null,3);
        $page_info = $this->get_in_page_info();

        $lesson_cancel_reason_type = $this->get_in_int_val('lesson_cancel_reason_type',-1);

        $ret_info = $this->t_lesson_info_b2->get_lesson_cancel_info_by_parent($start_time,$end_time,$page_info,$lesson_cancel_reason_type);

        foreach($ret_info['list'] as $index=>&$item_list){
            if($item_list['lesson_count_total'] == 0){
                unset($ret_info['list'][$index]);
            }

            if($item_list['assistantid']){
                $item_list['ass_nick'] = $this->cache_get_assistant_nick($item_list['assistantid']);
            }else{
                $item_list['ass_nick'] = $this->cache_get_account_nick($item_list['require_adminid']);
            }

        }
        \App\Helper\Common::sortArrByField($ret_info['list'],'lesson_count_total',true);

        return $this->pageView(__METHOD__,$ret_info);
    }


    public function get_no_time_train_lesson_teacher_list(){
        $list = $this->t_lesson_info_b2->get_no_time_train_lesson_teacher_list();
        foreach($list["list"] as &$val){
            if($val["wx_openid"]){
                $val["wx_flag"]="是";
            }else{
                $val["wx_flag"]="否";
            }
        }
        return $this->pageView(__METHOD__,$list);
        dd($list);
    }

    public function get_month_subejct_teacher_num(){
        $start_time = strtotime("2017-01-01");
        $end_time = strtotime("2017-09-01");
        $ret = $this->t_teacher_info->get_month_subejct_teacher_num($start_time,$end_time);
        dd($ret);
        $teacherid = $this->t_teacher_info->get_teacherid_by_phone($reference);
        $zs_id = $this->t_teacher_info->get_zs_id($teacherid);
        if($zs_id>0){
            $accept_adminid = $zs_id;
        }

    }

    public function tongji_lesson_record_info(){

        $first_test = $this->t_teacher_record_list->tongji_record_score_rank_list(1);
        foreach($first_test as &$v){
           $v['subject_str']   = E\Esubject::get_desc($v['subject']);
        }
        $fifth_test = $this->t_teacher_record_list->tongji_record_score_rank_list(2);
        foreach($fifth_test as &$vv){
            $vv['subject_str']   = E\Esubject::get_desc($vv['subject']);
        }

        $first_regular = $this->t_teacher_record_list->tongji_record_score_rank_list(3);
        foreach($first_regular as &$vvv){
            $vvv['subject_str']   = E\Esubject::get_desc($vvv['subject']);
        }

        $fifth_regular = $this->t_teacher_record_list->tongji_record_score_rank_list(4);
        foreach($fifth_regular as &$vvvv){
            $vvvv['subject_str']   = E\Esubject::get_desc($vvvv['subject']);
        }

        $all_record_info = $this->t_teacher_record_list->get_record_flag_info(-1);
        $have_record_info = $this->t_teacher_record_list->get_record_flag_info(1);
        $all_record_info["have_record_tea"] = $have_record_info["teacher_num"];
        $all_record_info["have_record_stu"] = $have_record_info["stu_num"];

        $all_record_info["test_lesson_score"]= $this->t_teacher_record_list->get_record_score_avg(1);
        $all_record_info["regular_lesson_score"] = $this->t_teacher_record_list->get_record_score_avg(2);
        return $this->pageView(__METHOD__,null,[
            "first_test"     =>$first_test,
            "first_regular"  =>$first_regular,
            "fifth_regular"  =>$fifth_regular,
            "fifth_test"     =>$fifth_test,
            "all_record_info"=>$all_record_info,
        ]);
        // dd($list);
    }

    public function get_lesson_tea_stu_info(){
       list($start_time,$end_time)  = $this->get_in_date_range(0,0,0,null,3);

       $list    = E\Esubject::$desc_map;
       unset($list[0]);
       unset($list[11]);
       $arr=[];
       foreach($list as $k=>$v){
           $arr[$k]["subject"]=$v;
       }

       $ret = $this->t_lesson_info_b3->get_lesson_tea_stu_info_new($start_time,$end_time,1);
       $data = $this->t_lesson_info_b3->get_lesson_tea_stu_info_new($start_time,$end_time,2);
       $new_teacher = $this->t_teacher_info->get_through_num_month($start_time,$end_time,1);
       $all_teacher = $this->t_teacher_info->get_through_num_month($start_time,$end_time,2);
       foreach($arr as $k=>&$val){
           $val["stu_num"] = @$ret[$k]["stu_num"];
           $val["tea_num"] = @$ret[$k]["tea_num"];
           $val["test_lesson_num"] = @$data[$k]["test_lesson_num"];
           $val["new_num"] = @$new_teacher[$k]["num"];
           $val["all_num"] = @$all_teacher[$k]["num"];
       }
       return $this->pageView(__METHOD__,null,[
           "list"     =>$arr,
       ]);


    }

    public function get_four_teacher_money_type_info(){
        list($start_time,$end_time)  = $this->get_in_date_range(0,0,0,null,3);
        $grade = $this->get_in_grade(100);
        $list = $this->t_teacher_info->get_teacher_info_by_teacher_money_type(6);
        $tea_arr=[];
        foreach($list as $v){
            $tea_arr[]=$v["teacherid"];
        }
        $cc_list        = $this->t_lesson_info->get_teacher_test_person_num_list( $start_time,$end_time,-1,$grade,$tea_arr,2);
        /*  if(!empty($cc_list)){
            $cc_list = $cc_list[$teacherid];
            $cc_per = !empty($cc_list["person_num"])?round($cc_list["have_order"]/$cc_list["person_num"]*100,2):0;
        }else{
            $cc_per =0;
            }*/
        $cr_list        = $this->t_lesson_info->get_teacher_test_person_num_list( $start_time,$end_time,-1,$grade,$tea_arr,1);
        foreach($list as $k=>&$val){
            $val["cc_lesson_count"] = isset($cc_list[$val["teacherid"]])?$cc_list[$val["teacherid"]]["lesson_num"]:0;
            $val["cc_person_num"] = isset($cc_list[$val["teacherid"]])?$cc_list[$val["teacherid"]]["person_num"]:0;
            $val["cc_have_order"] = isset($cc_list[$val["teacherid"]])?$cc_list[$val["teacherid"]]["have_order"]:0;
            $val["cr_lesson_count"] = isset($cr_list[$val["teacherid"]])?$cr_list[$val["teacherid"]]["lesson_num"]:0;
            $val["cr_person_num"] = isset($cr_list[$val["teacherid"]])?$cr_list[$val["teacherid"]]["person_num"]:0;
            $val["cr_have_order"] = isset($cr_list[$val["teacherid"]])?$cr_list[$val["teacherid"]]["have_order"]:0;
            $val["cc_per"] = !empty($val["cc_person_num"])?round($val["cc_have_order"]/$val["cc_person_num"]*100,2):0;
            $val["cr_per"] = !empty($val["cr_person_num"])?round($val["cr_have_order"]/$val["cr_person_num"]*100,2):0;
            $val["grade_str"] =E\Egrade::get_desc($grade);
            if($val["cc_lesson_count"]==0 &&  $val["cr_lesson_count"]==0){
                unset($list[$k]);
            }

        }
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($list));

    }

    public function get_order_lesson_money_info(){
        $first_month = strtotime("2016-01-01");
        // $end_month = strtotime(date("Y-m-01",time()));
        // $next_month = strtotime(date("Y-m-01",strtotime("+1 months", $first_month)));
        $num = (date("Y",time())-2016)*12+date("m",time())-1+1;

        // $order_money_info = $this->t_order_info->get_order_lesson_money_info($first_month,$next_month);
        //  $order_money_info = $this->t_order_info->get_order_lesson_money_use_info($first_month,$next_month);
        $list=[];
        for($i=1;$i<=$num;$i++){
            $first = strtotime(date("Y-m-01",strtotime("+".($i-1)." months", $first_month)));
            $next = strtotime(date("Y-m-01",strtotime("+1 months", $first)));
            $month = date("Y-m-d",$first);
            /* $order_money_info = $this->t_order_info->get_order_lesson_money_info($first,$next);
            $order_money_month = $this->t_order_info->get_order_lesson_money_use_info($first,$next);
            $list[$month]["stu_num"] = @$order_money_info["stu_num"];
            $list[$month]["all_price"] = @$order_money_info["all_price"];
            $list[$month]["lesson_count_all"] = @$order_money_info["lesson_count_all"];
            foreach($order_money_month as $val){
                $list[$month][$val["time"]]=($val["all_price"]/100)."/".($val["lesson_count_all"]/100);
                }*/
            $list[$month]["month"] = date("Y年m月",$first);
            $list[$month]["month_start"] = $first;


        }
        return $this->pageView(__METHOD__,null,[
            "list"  =>$list ,
            "num"  =>count($list)
        ]);

    }


    //@desn:新版渠道统计
    public function channel_statistics(){
        //  $this->check_and_switch_tongji_domain();
        $origin            = trim($this->get_in_str_val("origin",""));
        $origin_ex         = $this->get_in_str_val('origin_ex', "");
        $seller_groupid_ex = $this->get_in_str_val('seller_groupid_ex', "");
        $adminid_list      = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);
        $admin_revisiterid = $this->get_in_int_val("admin_revisiterid", -1);
        $groupid           = $this->get_in_int_val("groupid",-1);
        $tmk_adminid       = $this->get_in_int_val("tmk_adminid", -1);
        $check_field_id    = $this->get_in_int_val("check_field_id",1);
        $is_history = $this->get_in_int_val('is_history',1);
        $sta_data_type = $this->get_in_int_val('sta_data_type',1);
        $check_field_config=[
            1=> ["渠道","origin", "" ],
            2=> ["年级","grade", E\Egrade::class ],
            3=> ["科目","subject", E\Esubject::class ],
            4=> ["tmk人员","tmk_adminid", "" ],
            5=> ["销售人员","admin_revisiterid", "" ],
            6=> ["渠道等级","origin_level",   E\Eorigin_level::class  ],
        ];

        $data_map=[];
        $check_item=$check_field_config[$check_field_id];
        $field_name       = $check_item[1];
        $field_class_name = $check_item[2];

        list($start_time,$end_time ,$opt_date_str)=$this->get_in_date_range_month( date("Y-m-01"), 0, [
            0 => array( "add_time", "资源进来时间"),
            1 => array("tmk_assign_time","微信运营时间"),
            2 => array("tlsr.require_time","试听申请时间"),
            3 => array("tlsr.accept_time","试听排课时间"),
            4 => array("li.lesson_end","成功试听时间"),
            5 => array("oi.order_time","签单时间"),
        ] );
        //初始化画图用数据
        $subject_map = $grade_map = $has_pad_map = $origin_level_map = $area_map = $test_grade_map = array();
        $order_area_map = $order_subject_map = $order_grade_map = $test_area_map = $test_subject_map = array();
        $test_has_pad_map = $test_origin_level_map = $order_has_pad_map = $order_origin_level_map = array();
        $group_list = array();
        $origin_type = 1;
        //初始化是否显示饼图标识
        $is_show_pie_flag = 0;
        //月初时间戳
        $month_begin = strtotime(date('Y-m-01',$start_time));
        $this->switch_tongji_database();

        if($is_history == 1 && $sta_data_type == 1){
            //漏斗形存档数据
            $ret_info = $this->t_channel_funnel_archive_data->get_list($month_begin,$origin_ex);
        }elseif($is_history == 1 && $sta_data_type == 2){
            //节点型存档数据
            $ret_info = $this->t_channel_node_type_statistics->get_list($month_begin,$origin_ex);
        }elseif($is_history == 2 && $sta_data_type == 2){
            //节点型实时数据
            //例子总量
            $ret_info = $this->t_test_lesson_subject->get_example_num_now($field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid);
            $data_map=&$ret_info["list"];
            //试听预约数
            $test_lesson_require_data = $this->t_test_lesson_subject_require->get_test_lesson_quire_info($origin, $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid,$origin_ex);
            foreach($test_lesson_require_data as $item){
                $channel_name=$item["check_value"];

                \App\Helper\Utils:: array_item_init_if_nofind( $data_map, $channel_name,["check_value" => $channel_name] );
                $data_map[$channel_name]["require_count"] = $item["require_count"];

            }
            //试听信息
            $test_lesson_data = $this->t_test_lesson_subject_require->get_test_lesson_data_now($origin, $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid,$origin_ex);
            foreach ($test_lesson_data as  $test_item ) {
                $channel_name=$test_item["check_value"];

                \App\Helper\Utils:: array_item_init_if_nofind( $data_map, $channel_name,["check_value" => $channel_name] );
                $data_map[$channel_name]["test_lesson_count"] = $test_item["test_lesson_count"];
                $data_map[$channel_name]["distinct_test_count"] = $test_item["distinct_test_count"];
                $data_map[$channel_name]["succ_test_lesson_count"] = $test_item["succ_test_lesson_count"];
                $data_map[$channel_name]["distinct_succ_count"] = $test_item["distinct_succ_count"];
                //试听率
                if(@$data_map[$channel_name]['tq_called_count'])
                    $data_map[$channel_name]["audition_rate"] = number_format($test_item["distinct_succ_count"]/$data_map[$channel_name]['tq_called_count']*100,2);
                else
                    $data_map[$channel_name]["audition_rate"] = '';

            }
            //订单信息
            $order_data = $this->t_order_info->get_node_type_order_data_now($field_name,$start_time,$end_time,$adminid_list,$tmk_adminid,$origin_ex,$opt_date_str, $origin);
            foreach($order_data as $order_item){
                $channel_name=$order_item["check_value"];
                $channel_name= \App\Helper\Utils:: array_item_init_if_nofind($data_map, $channel_name,["check_value" => $channel_name]);

                $data_map[$channel_name]["order_count"] = $order_item["order_count"];
                $data_map[$channel_name]["user_count"] = $order_item["user_count"];
                $data_map[$channel_name]["order_all_money"] = $order_item["order_all_money"];
            }



        }elseif($is_history == 2 && $sta_data_type == 1){
            //漏斗型实时数据
            if(in_array($opt_date_str,['add_time','tmk_assign_time'])){
                //显示饼图
                $is_show_pie_flag = 1;
                $ret_info = $this->t_seller_student_origin->get_origin_tongji_info_new($field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid);

                //统计试听课相关信息  ---begin---
                $data_map=&$ret_info["list"];
                //试听信息
                $test_lesson_list_new = $this->t_seller_student_origin->get_lesson_list_new($field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid);
                // $test_lesson_list=$this->t_test_lesson_subject_require->tongji_test_lesson_origin( $origin, $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid, $origin_ex );
                foreach ($test_lesson_list_new as  $test_item ) {
                    $check_value=$test_item["check_value"];
                    $check_value=\App\Helper\Utils:: array_item_init_if_nofind( $data_map, $check_value,["check_value" => $check_value] );
                    $data_map[$check_value]["require_count"] = $test_item["require_count"];
                    $data_map[$check_value]["distinct_test_count"] = $test_item["distinct_test_count"];
                    $data_map[$check_value]["succ_test_lesson_count"] = $test_item["succ_test_lesson_count"];
                    $data_map[$check_value]["test_lesson_count"] = $test_item["test_lesson_count"];
                    $data_map[$check_value]["distinct_succ_count"] = $test_item["distinct_succ_count"];
                    //试听率
                    if(@$data_map[$check_value]['tq_called_count'])
                        $data_map[$check_value]["audition_rate"] = number_format($test_item["distinct_succ_count"]/$data_map[$check_value]['tq_called_count']*100,2);
                    else
                        $data_map[$check_value]["audition_rate"] = '';

                }
                //统计试听课相关信息  ---begin---

                //统计订单相关信息  ---begin---
                //合同
                $order_list_new = $this->t_seller_student_origin->get_order_list_new($field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid);

                // $order_list= $this->t_order_info->tongji_seller_order_count_origin( $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid,$origin_ex,$opt_date_str, $origin);
                foreach ($order_list_new as  $order_item ) {
                    $check_value=$order_item["check_value"];
                    $check_value=\App\Helper\Utils:: array_item_init_if_nofind( $data_map, $check_value,["check_value" => $check_value ] );

                    $data_map[$check_value]["order_count"] = $order_item["order_count"];
                    $data_map[$check_value]["user_count"] = $order_item["user_count"];
                    $data_map[$check_value]["order_all_money"] = $order_item["order_all_money"];
                }
                //统计订单相关信息  ---end---


                //饼图用数据 --begin--
                //地区、年级科目、硬件、渠道等级等统计饼图数据
                $data_list = $this->t_seller_student_origin->get_origin_detail_info($opt_date_str,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list,$tmk_adminid);
                $all_count        = count($data_list);

                foreach ($data_list as $a_item) {
                    $subject      = $a_item["subject"];
                    $grade        = $a_item["grade"];
                    $has_pad      = $a_item["has_pad"];
                    $origin_level = $a_item["origin_level"];
                    $area_name    = substr($a_item["phone_location"], 0, -6);
                    @$subject_map[$subject] ++;
                    @$grade_map[$grade] ++;
                    @$has_pad_map[$has_pad] ++;
                    @$origin_level_map[$origin_level] ++;
                    if (strlen($area_name)>5) {
                        @$area_map[$area_name] ++;
                    } else {
                        @$area_map[""] ++;
                    }

                }

                $group_list = $this->t_admin_group_name->get_group_list(2);


                //签单统计用饼图
                //订单信息
                $order_data = $this->t_order_info->tongji_seller_order_info($origin, $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid,$origin_ex,$opt_date_str);
                foreach ($order_data as $a_item) {
                    $subject   = $a_item["subject"];
                    $grade     = $a_item["grade"];
                    $area_name = substr($a_item["phone_location"], 0, -6);
                    $has_pad      = $a_item["has_pad"];
                    $origin_level = $a_item["origin_level"];
                    @$order_subject_map[$subject] ++;
                    @$order_grade_map[$grade] ++;
                    @$order_has_pad_map[$has_pad] ++;
                    @$order_origin_level_map[$origin_level] ++;

                    if (strlen($area_name)>5) {
                        @$order_area_map[$area_name] ++;
                    } else {
                        @$order_area_map[""] ++;
                    }

                }

                //试听统计用饼图
                //试听信息
                $test_data=$this->t_test_lesson_subject_require->tongji_test_lesson_origin_info( $origin, $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid, $origin_ex,'','','',$opt_date_str);
                foreach ($test_data as $a_item) {
                    $subject   = $a_item["subject"];
                    $grade     = $a_item["grade"];
                    $area_name = substr($a_item["phone_location"], 0, -6);
                    $has_pad      = $a_item["has_pad"];
                    $origin_level = $a_item["origin_level"];
                    @$test_subject_map[$subject] ++;
                    @$test_grade_map[$grade] ++;
                    @$test_has_pad_map[$has_pad] ++;
                    @$test_origin_level_map[$origin_level] ++;

                    if (strlen($area_name)>5) {
                        @$test_area_map[$area_name] ++;
                    } else {
                        @$test_area_map[""] ++;
                    }

                }

                //饼图用数据 --end--


            }elseif(in_array($opt_date_str,['tlsr.require_time','tlsr.accept_time'])){
                //时间检索[试听申请时间][试听排课时间]用

                $ret_info = $this->t_test_lesson_subject_require->get_funnel_data($field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid);
                $data_map=&$ret_info["list"];
                //计算不重复的订单数[合同人数] ---begin--
                $order_info = $this->t_test_lesson_subject_require->get_distinct_order_info($field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid);
                foreach ($order_info as  $item ) {
                    $check_value=$item["check_value"];
                    $check_value=\App\Helper\Utils:: array_item_init_if_nofind( $data_map, $check_value,["check_value" => $check_value] );
                    $data_map[$check_value]["user_count"] = $item["user_count"];
                    //矫正预约数、上课成功数
                    $data_map[$check_value]["require_count"] = $data_map[$check_value]["require_count"]-($data_map[$check_value]["order_count"]-$data_map[$check_value]["user_count"]);
                    $data_map[$check_value]["succ_test_lesson_count"] = $data_map[$check_value]["succ_test_lesson_count"]-($data_map[$check_value]["order_count"]-$data_map[$check_value]["user_count"]);
                }
                //计算不重复的订单数[合同人数] ---end--

            }elseif(in_array($opt_date_str,['li.lesson_end'])){

                //时间检索[成功试听]用
                $ret_info = $this->t_lesson_info->get_funnel_data($field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid);
                $data_map=&$ret_info["list"];
                //计算不重复的订单数[合同人数] ---begin--
                $order_info = $this->t_lesson_info->get_distinct_order_info($field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid);
                foreach ($order_info as  $item ) {
                    $check_value=$item["check_value"];
                    $check_value=\App\Helper\Utils:: array_item_init_if_nofind( $data_map, $check_value,["check_value" => $check_value] );
                    $data_map[$check_value]["user_count"] = $item["user_count"];
                    //上课成功数
                    $data_map[$check_value]["succ_test_lesson_count"] = $data_map[$check_value]["succ_test_lesson_count"]-($data_map[$check_value]["order_count"]-$data_map[$check_value]["user_count"]);

                }
                //计算不重复的订单数[合同人数] ---end--

            }elseif(in_array($opt_date_str,['oi.order_time'])){

                //时间检索[签单时间]用
                $ret_info = $this->t_order_info->get_funnel_data($field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid);
                $data_map=&$ret_info["list"];
            }

            
        }

        //实时数据需要对数据进行处理
        if($is_history == 2){
            foreach ($data_map as &$item ) {
                if($field_class_name ) {
                    $item["title"]= $field_class_name::get_desc($item["check_value"]);
                }else{
                    if ($field_name=="tmk_adminid" || $field_name=="admin_revisiterid"  ) {
                        $item["title"]= $this->cache_get_account_nick( $item["check_value"] );
                    }else{
                        $item["title"]= @$item["check_value"];
                    }

                }

                if ($field_name=="origin") {
                    $item["origin"]= $item["title"];
                }
            }
            //重组分层数组
            if ($field_name=="origin"){
                $ret_info["list"]= $this->gen_origin_data_level5(
                    $ret_info["list"],
                    ['avg_first_time','consumption_rate','called_rate','effect_rate','audition_rate'],
                    $origin_ex
                );
            } 
        }

        //将显示饼图标识发送到js
        $this->set_filed_for_js('is_show_pie_flag', $is_show_pie_flag);
        return $this->pageView(__METHOD__,$ret_info,[
            "subject_map"      => $subject_map,
            "grade_map"        => $grade_map,
            "has_pad_map"      => $has_pad_map,
            "origin_level_map" => $origin_level_map,
            "area_map"         => $area_map,
            "group_list"       => $group_list,
            "field_name"       => $field_name,
            "origin_type"      => $origin_type,
            "order_area_map"   => $order_area_map,
            "order_subject_map"=> $order_subject_map,
            "order_grade_map"  => $order_grade_map,
            "test_area_map"   => $test_area_map,
            "test_subject_map"=> $test_subject_map,
            "test_grade_map"  => $test_grade_map,
            'is_show_pie_flag' => $is_show_pie_flag,
            "test_has_pad_map"      => $test_has_pad_map,
            "test_origin_level_map" => $test_origin_level_map,
            "order_has_pad_map"      => $order_has_pad_map,
            "order_origin_level_map" => $order_origin_level_map,
            'is_history' => $is_history
        ]);

    }


    //@desn:将数据重组结构
    //@param:$old_list 需要重组的数组
    //@param:$no_sum_list 不需要相加的列
    //@param:$origin_ex 渠道字符串
    public function gen_origin_data_level5($old_list,$no_sum_list=[] ,$origin_ex="")
    {
        $value_map=$this->t_origin_key->get_list( $origin_ex);
        //组织分层用类标识
        $cur_key_index=1;
        $check_init_map_item=function (&$item, $key, $key_class, $value = "") {
            global $cur_key_index;
            if (!isset($item [$key])) {
                $item[$key] = [
                    "value" => $value,
                    "key_class" => $key_class."-".$cur_key_index,
                    "sub_list"=>[] ,
                    "data" => array(),
                ];
                $cur_key_index++;
            }
        };
        //计算相加数据
        $add_data=function (&$item, $add_item ,$self_flag=false) use ( $no_sum_list) {
            $arr=&$item["data"];
            foreach ($add_item as $k => $v) {
                if (!is_int($k) && $k!="origin" && ($self_flag|| !in_array($k,$no_sum_list))){
                    if (!isset($arr[$k])) {
                        $arr[$k]=0;
                    }
                    @$arr[$k]+=$v;
                }
            }

        };

        $all_item=["origin"=>"全部"];
        $check_init_map_item($data_map,"","");
        foreach ($old_list as &$item) {
            $value=trim($item["origin"]);
            //没有key0 key1 key2 key3
            if (!isset($value_map[$value])) {
                $value_map[$value]=[
                    "key0"=>"未定义",
                    "key1"=>"未定义",
                    "key2"=>"未定义",
                    "key3"=>"未定义",
                    "key4"=>$value,
                    "value"=>$value,
                ];
            }

            $conf=$value_map[$value];

            $key0=$conf["key0"];
            $key1=$conf["key1"];
            $key2=$conf["key2"];
            $key3=$conf["key3"];
            $key4=$conf["key4"];
            $key_map=&$data_map[""];
            $add_data($key_map, $item );

            $check_init_map_item($key_map["sub_list"] , $key0,"key0" );
            $key0_map=&$key_map["sub_list"][$key0];
            $add_data($key0_map, $item );

            $check_init_map_item($key0_map["sub_list"] , $key1,"key1" );
            $key1_map=&$key0_map["sub_list"][$key1];
            $add_data($key1_map, $item );

            $check_init_map_item($key1_map["sub_list"] , $key2 ,"key2");
            $key2_map=&$key1_map["sub_list"][$key2];
            $add_data($key2_map, $item );

            $check_init_map_item($key2_map["sub_list"] , $key3 ,"key3");
            $key3_map=&$key2_map["sub_list"][$key3];
            $add_data($key3_map, $item );

            $check_init_map_item($key3_map["sub_list"] , $key4,"key4",$value);
            $key4_map=&$key3_map["sub_list"][$key4];
            $add_data($key4_map, $item, true);
        }
        $list=[];
        foreach ($data_map as $key0 => $item0) {  //第0层
            $data=$item0["data"];
            $data["key0"]="全部";
            $data["key1"]="";
            $data["key2"]="";
            $data["key3"]="";
            $data["key4"]="";
            $data["key0_class"]="";
            $data["key1_class"]="";
            $data["key2_class"]="";
            $data["key3_class"]="";
            $data["key4_class"]="";
            $data["level"]="l-0";

            $list[]=$data;
            foreach ($item0["sub_list"] as $key1 => $item1) {//第1层
                $data=$item1["data"];
                $data["key0"]=$key1;
                $data["key1"]="";
                $data["key2"]="";
                $data["key3"]="";
                $data["key4"]="";
                $data["key0_class"]=$item1["key_class"];
                $data["key1_class"]="";
                $data["key2_class"]="";
                $data["key3_class"]="";
                $data["key4_class"]="";
                $data["level"]="l-1";

                $list[]=$data;

                foreach ($item1["sub_list"] as $key2 => $item2) {//第2层
                    $data=$item2["data"];
                    $data["key0"]=$key1;
                    $data["key1"]=$key2;
                    $data["key2"]="";
                    $data["key3"]="";
                    $data["key4"]="";
                    $data["key0_class"]=$item1["key_class"];
                    $data["key1_class"]=$item2["key_class"];
                    $data["key2_class"]="";
                    $data["key3_class"]="";
                    $data["key4_class"]="";
                    $data["level"]="l-2";

                    $list[]=$data;
                    foreach ($item2["sub_list"] as $key3 => $item3) {//第3层
                        $data=$item3["data"];
                        $data["key0"]=$key1;
                        $data["key1"]=$key2;
                        $data["key2"]=$key3;
                        $data["key3"]="";
                        $data["key4"]="";
                        $data["key0_class"]=$item1["key_class"];
                        $data["key1_class"]=$item2["key_class"];
                        $data["key2_class"]=$item3["key_class"];
                        $data["key3_class"]="";
                        $data["key4_class"]="";
                        $data["level"]="l-3";

                        $list[]=$data;
                        foreach ($item3["sub_list"] as $key4 => $item4) {//第4层
                            $data=$item4["data"];
                            $data["key0"]=$key1;
                            $data["key1"]=$key2;
                            $data["key2"]=$key3;
                            $data["key3"]=$key4;
                            $data["key4"]="";
                            $data["key0_class"]=$item1["key_class"];
                            $data["key1_class"]=$item2["key_class"];
                            $data["key2_class"]=$item3["key_class"];
                            $data["key3_class"]=$item4["key_class"];
                            $data["key4_class"]="";
                            $data["level"]="l-4";
                            $list[]=$data;

                            foreach ($item4["sub_list"] as $key5 => $item5) {//第5层
                                $data=$item5["data"];
                                $data["key0"]=$key1;
                                $data["key1"]=$key2;
                                $data["key2"]=$key3;
                                $data["key3"]=$key4;
                                $data["key4"]=$key5;
                                $data["value"] = $item5["value"];
                                $data["key0_class"]=$item1["key_class"];
                                $data["key1_class"]=$item2["key_class"];
                                $data["key2_class"]=$item3["key_class"];
                                $data["key3_class"]=$item4["key_class"];
                                $data["key4_class"]=$item5["key_class"];
                                $k5_v=$item5["value"];
                                if ($k5_v != $key5) {
                                    $data["key5"]=$key5."/". $k5_v ;
                                }
                                $data["old_key5"]=$key5;
                                $data["level"]="l-5";
                                $list[]=$data;
                            }
                        }

                    }

                }


            }
        }
        // dd($list);
        foreach($list as &$item){
            if($item["level"]=="l-5" && $item["key0"]!="未定义"){
                $item["create_time"] = $value_map[$item['value']]["create_time"];
                if(!empty($item["create_time"])){
                    $item["create_time"] = date('Y-m-d',$item["create_time"]);
                }else{
                    $item["create_time"] = "";
                }
            }else{
                $item["create_time"] = "";
            }
        }
        return $list;
    }

    //@desn:获取该渠道的例子进入量明细
    public function origin_count_example_info(){
        $origin            = trim($this->get_in_str_val("origin",""));
        $origin_ex         = $this->get_in_str_val('origin_ex', "");
        $seller_groupid_ex = $this->get_in_str_val('seller_groupid_ex', "");
        $adminid_list      = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);
        $admin_revisiterid = $this->get_in_int_val("admin_revisiterid", -1);
        $groupid           = $this->get_in_int_val("groupid",-1);
        $tmk_adminid       = $this->get_in_int_val("tmk_adminid", -1);

        $check_field_id    = $this->get_in_int_val("check_field_id",1);
        $check_field_config=[
            1=> ["渠道","origin", "" ],
            2=> ["年级","grade", E\Egrade::class ],
            3=> ["科目","subject", E\Esubject::class ],
            4=> ["tmk人员","tmk_adminid", "" ],
            5=> ["销售人员","admin_revisiterid", "" ],
            6=> ["渠道等级","origin_level",   E\Eorigin_level::class  ],
        ];

        $data_map=[];
        $check_item=$check_field_config[$check_field_id];
        $field_name       = $check_item[1];
        $field_class_name = $check_item[2];

        list($start_time,$end_time ,$opt_date_str)=$this->get_in_date_range_month( date("Y-m-01"), 0, [
            0 => array( "add_time", "资源进来时间"),
            1 => array("tmk_assign_time","微信运营时间"),
            2 => array("tlsr.require_time","试听申请时间"),
            3 => array("tlsr.accept_time","试听排课时间"),
            4 => array("li.lesson_end","成功试听时间"),
            5 => array("oi.order_time","签单时间"),
        ] );
        $check_value       = $this->get_in_str_val("check_value");
        $page_info         = $this->get_in_page_info();
        $cond = $this->get_in_str_val('cond');


        //试听信息
        $ret_info=$this->t_test_lesson_subject->tongji_test_example_origin_info('',$field_name,$start_time,$end_time,$adminid_list,$tmk_adminid, $origin_ex ,$check_value,$page_info,$cond);
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


        }

        return $this->pageView(__METHOD__,$ret_info,[
            'account' => $this->get_account(),
        ]);
    }

    //@desn:更新存档数据
    public function update_archive_data(){
        $sta_data_type = $this->get_in_int_val('sta_data_type');
        if($sta_data_type == 1){//漏斗型
            $funnel_channel = new \App\Console\Commands\funnel_channel_statistics();
            $funnel_channel->handle($job=2);
        }elseif($sta_data_type == 2){//结点型
            $node_type_channel = new \App\Console\Commands\node_type_channel_statistics();
            $node_type_channel->handle($job=2);
        }else{
            return $this->output_err('存档类型错误!');
        }
        return $this->output_succ();
    }
    //@desn:渠道统计信息流
    public function channel_sta_flow(){
        $is_history = $this->get_in_int_val('is_history',1);
        $sta_data_type = $this->get_in_int_val('sta_data_type',1);
        $this->set_in_value("is_history",$is_history);
        $this->set_in_value("sta_data_type",$sta_data_type);
        $this->set_in_value("origin_ex","信息流,,,,");
        return $this->channel_statistics();
    }


}
