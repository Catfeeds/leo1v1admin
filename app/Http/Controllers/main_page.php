<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use Illuminate\Support\Facades\Redis ;

class main_page extends Controller
{

    var $switch_tongji_database_flag = true;
    use CacheNick;
    use TeaPower;
    function __construct()  {
        parent::__construct();
    }
    public  function market() {
        list($start_time,$end_time)=$this->get_in_date_range_month(0);
        $role_2_diff_money= $this->t_order_info-> get_spec_diff_money_all( $start_time,$end_time,E\Eaccount_role::V_2 );
        $role_1_diff_money= $this->t_order_info-> get_spec_diff_money_all( $start_time,$end_time,E\Eaccount_role::V_1 );

    }

    public  function admin() {
        $adminid = $this->get_account_id();
        list($start_time,$end_time)=$this->get_in_date_range_day(0);

        //得到短信信息
        $sms_list=$this->t_sms_msg->tongji_type_get_list($start_time,$end_time);
        $lesson_info=$this->t_lesson_info->tongji_count($start_time, $end_time);
        $record_server_list=$this->t_lesson_info->tongji_record_server_info($start_time, $end_time);
        $server_map= $this->t_audio_record_server->get_server_map();
        $all_max_record_count=0;

        $all_active_count=0;
        foreach ($record_server_list as &$s_item ) {
            $s_item["max_record_count"]= @$server_map[ $s_item["server"]]["max_record_count"];
            $all_active_count+=$s_item["active_count"];
            $all_max_record_count+= $s_item["max_record_count"];
        }
        $record_server_list[]=[
            "max_record_count" => $all_max_record_count,
            "active_count" => $all_active_count  ];

        foreach ($sms_list as &$item)  {
            E\Esms_type::set_item_value_str($item, "type");
        }
        $sys_assign=$this->t_seller_student_system_assign_count_log->get_last_item();
        $day_system_assign_count = 0;
        if(Redis::get('day_system_assign_count'))
            $day_system_assign_count = Redis::get('day_system_assign_count');


        $sys_info=[
            ["当前IP", $this->get_in_client_ip() ],
            ["job count:" ,$this->t_jobs->get_all_count() ],
            ["课时审查时间节点",\App\Helper\Config::get_lesson_confirm_start_time()],
            ["",""],
            ["系统分配例子时间", \App\Helper\Utils::unixtime2date( $sys_assign["logtime"])],
            ["新例子剩余", $sys_assign["new_count"]],
            ["新例子需要", $sys_assign["need_new_count"]],
            ["新例子配置", $day_system_assign_count],
            ["新例子已分配", $sys_assign["new_count_assigned"]],

            ["未联系剩余", $sys_assign["no_connected_count"]],
            ["未联系已分配", $sys_assign["no_connected_count_assigned"]],
            ["未联系需要", $sys_assign["need_no_connected_count"]],

        ];


        return $this->pageView(__METHOD__ ,null, [
            "sms_list" => $sms_list,
            "sys_info" => $sys_info,
            "lesson_info" => $lesson_info,
            "record_server_list" => $record_server_list,
        ]);
    }


    public function get_seller_total_info(){ // cc 总表信息
        $this->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range( 0 ,0,0,[],2 );
        $opt_date_type = $this->get_in_int_val("opt_date_type",2);
        $history_data = $this->get_in_int_val('history_data');
        $nowTime = time();
        $onlineTime = strtotime('2018-01-8');
        $sellerNumArr = [];

        if($opt_date_type == 2){
            $start_time = $start_time+86400;
            $end_time = $end_time+86400;
        }

        $ret_info_arr['list'] = $this->t_seller_tongji_for_month->get_history_data($start_time);
        $ret_info = &$ret_info_arr['list'];


        if($history_data){ // 0:是历史数据 1:否历史数据
            if($ret_info){
                // $order_info_total = $this->t_order_info->get_total_money($start_time, $end_time);// 总收入

                if($ret_info['seller_target_income']>0){
                    $ret_info['month_finish_persent'] = $ret_info['formal_info']/$ret_info['seller_target_income']*100;//月kpi完成率
                }

                $ret_info['month_left_money'] = $ret_info['seller_target_income'] - $ret_info['formal_info'];//

                if($ret_info['new_order_num']>0){ //平均单笔
                    $ret_info['aver_count'] = $ret_info['new_money']/$ret_info['new_order_num'];
                }else{
                    $ret_info['aver_count'] = 0;
                }

                if($ret_info['formal_num']>0){ //平均人效[入职完整月]
                    $ret_info['aver_money'] = $ret_info['all_order_price']/$ret_info['formal_num'];
                }else{
                    $ret_info['aver_money'] = 0;
                }


                if($ret_info['order_cc_num']>0){ //平均人效 all cc签单额/all cc签单人数
                    $ret_info['aver_money_cc'] = $ret_info['new_money']/$ret_info['order_cc_num'];
                }else{
                    $ret_info['aver_money_cc'] = 0;
                }



                # 咨询各部人数修改
                # 2018-1-9 之后
                if($start_time>$onlineTime){
                    $sellerNumArr = json_decode($ret_info['sellerNumData'],true);
                    $sellerNum = 0;
                    foreach($sellerNumArr as $sellerNumItem){
                        $sellerNum+=$sellerNumItem['seller_num'];
                    }
                    $ret_info['seller_num'] = $sellerNum;
                }else{
                    # 2018-1-9 以前
                    $ret_info['seller_num'] = $ret_info['one_department']+$ret_info['two_department']+$ret_info['three_department']+$ret_info['new_department']+$ret_info['train_department']+$ret_info['five_department'];// 咨询一部+咨询二部+咨询三部+新人营
                }




                // 金额转化率占比
                if($ret_info['new_money']>0){
                    $ret_info['referral_money_rate'] = $ret_info['referral_money']/$ret_info['new_money']*100;
                    $ret_info['high_school_money_rate']   =  $ret_info['high_school_money']/$ret_info['new_money']*100;
                    $ret_info['junior_money_rate']  = $ret_info['junior_money']/$ret_info['new_money']*100;
                    $ret_info['primary_money_rate'] = $ret_info['primary_money']/$ret_info['new_money']*100;
                }else{
                    $ret_info['referral_money_rate']    = 0;
                    $ret_info['high_school_money_rate'] = 0;
                    $ret_info['junior_money_rate']      = 0;
                    $ret_info['primary_money_rate']     = 0;
                }


                //  外呼情况

                $ret_info['un_consumed'] = $ret_info['new_stu']-$ret_info['has_called_stu']; // 未消耗例子数

                if($ret_info['has_tq_succ_invit_month_funnel']>0){ //月邀约率
                    $ret_info['invit_month_rate'] = $ret_info['seller_invit_month_funnel']/$ret_info['has_tq_succ_invit_month_funnel']*100;
                }else{
                    $ret_info['invit_month_rate'] = 0;
                }


                if($ret_info['seller_plan_invit_month_funnel']>0){ //月排课率
                    $ret_info['test_plan_month_rate'] = $ret_info['seller_schedule_num_month_funnel']/$ret_info['seller_plan_invit_month_funnel']*100;
                    //seller_plan_invit_month_funnel
                }else{
                    $ret_info['test_plan_month_rate'] = 0;
                }

                if($ret_info['seller_schedule_num_has_done_month_funnel']>0){ //月到课率
                    $ret_info['lesson_succ_month_rate'] = $ret_info['seller_test_succ_month_funnel']/$ret_info['seller_schedule_num_has_done_month_funnel']*100;
                }else{
                    $ret_info['lesson_succ_month_rate'] = 0;
                }


                if($ret_info['seller_test_succ_month_funnel']>0){ //月试听转化率
                    $ret_info['trans_month_rate'] = $ret_info['order_trans_month_funnel']/$ret_info['seller_test_succ_month_funnel']*100;
                }else{
                    $ret_info['trans_month_rate'] = 0;
                }


                if($ret_info['has_tq_succ_sign_month_funnel']>0){ //月签约率
                    $ret_info['sign_month_rate'] = $ret_info['order_sign_month_funnel']/$ret_info['has_tq_succ_sign_month_funnel']*100;
                }else{
                    $ret_info['sign_month_rate'] = 0;
                }

                if($ret_info['has_called']>0){
                    $ret_info['succ_called_rate'] = $ret_info['has_tq_succ']/$ret_info['has_called_stu']*100; //接通率
                    $ret_info['claim_num_rate'] = $ret_info['claim_num']/$ret_info['has_called_stu']*100; //认领率
                }else{
                    $ret_info['claim_num_rate'] = 0;
                    $ret_info['succ_called_rate'] = 0;
                }


                if($ret_info['seller_num']>0){ // 人均通时
                    $ret_info['called_rate'] = $ret_info['cc_call_time']/$ret_info['seller_num'];
                }else{
                    $ret_info['called_rate'] = 0;
                }

                if($ret_info['cc_called_num']>0){
                    $ret_info['aver_called'] = $ret_info['seller_call_num']/$ret_info['cc_called_num']; // 人均呼出量
                    $ret_info['invit_rate'] = $ret_info['seller_invit_num']/$ret_info['cc_called_num']; // 人均邀约率
                }else{
                    $ret_info['aver_called'] = 0;
                    $ret_info['invit_rate'] = 0;
                }

                if($ret_info['new_stu']>0){ //月例子消耗数
                    $ret_info['stu_consume_rate'] = $ret_info['has_called_stu']/$ret_info['new_stu']*100;
                }else{
                    $ret_info['stu_consume_rate'] = 0;
                }
            }


        }else{ // 历史数据 [从数据库中取]
            if($ret_info){

                if($ret_info['seller_target_income']>0){
                    $ret_info['month_finish_persent'] = $ret_info['formal_info']/$ret_info['seller_target_income']*100;//月kpi完成率
                }

                $ret_info['month_left_money'] = $ret_info['seller_target_income'] - $ret_info['formal_info'];//


                if($ret_info['new_order_num']>0){ //平均单笔
                    $ret_info['aver_count'] = $ret_info['new_money']/$ret_info['new_order_num'];
                }else{
                    $ret_info['aver_count'] = 0;
                }

                if($ret_info['formal_num']>0){ //平均人效 [入职完整月]
                    $ret_info['aver_money'] = $ret_info['all_order_price']/$ret_info['formal_num'];
                }else{
                    $ret_info['aver_money'] = 0;
                }

                if($ret_info['order_cc_num']>0){ //平均人效 all cc签单额/all cc签单人数
                    $ret_info['aver_money_cc'] = $ret_info['new_money']/$ret_info['order_cc_num'];
                }else{
                    $ret_info['aver_money_cc'] = 0;
                }


                # 咨询各部人数修改
                if($start_time>$onlineTime){
                    $sellerNumArr = json_decode($ret_info['sellerNumData'],true);
                    $sellerNum = 0;
                    foreach($sellerNumArr as $sellerNumItem){
                        $sellerNum+=$sellerNumItem['seller_num'];
                    }
                    $ret_info['seller_num'] = $sellerNum;
                }else{
                    # 2018-1-9 之前
                    $ret_info['seller_num'] = $ret_info['one_department']+$ret_info['two_department']+$ret_info['three_department']+$ret_info['new_department']+$ret_info['train_department']+$ret_info['five_department'];// 咨询一部+咨询二部+咨询三部+新人营
                }


                // 金额转化率占比
                if($ret_info['new_money']>0){
                    $ret_info['referral_money_rate'] = $ret_info['referral_money']/$ret_info['new_money']*100;
                    $ret_info['high_school_money_rate']   =  $ret_info['high_school_money']/$ret_info['new_money']*100;
                    $ret_info['junior_money_rate']  = $ret_info['junior_money']/$ret_info['new_money']*100;
                    $ret_info['primary_money_rate'] = $ret_info['primary_money']/$ret_info['new_money']*100;
                }else{
                    $ret_info['referral_money_rate']    = 0;
                    $ret_info['high_school_money_rate'] = 0;
                    $ret_info['junior_money_rate']      = 0;
                    $ret_info['primary_money_rate']     = 0;
                }

                //  外呼情况
                $ret_info['un_consumed'] = $ret_info['new_stu']-$ret_info['has_called_stu']; // 未消耗例子数
                if($ret_info['has_tq_succ_invit_month']>0){ //月邀约率
                    $ret_info['invit_month_rate'] = $ret_info['seller_invit_month']/$ret_info['has_tq_succ_invit_month']*100;
                }else{
                    $ret_info['invit_month_rate'] = 0;
                }

                if($ret_info['seller_plan_invit_month']>0){ //月排课率
                    $ret_info['test_plan_month_rate'] = $ret_info['seller_schedule_num_month']/$ret_info['seller_plan_invit_month']*100;
                }else{
                    $ret_info['test_plan_month_rate'] = 0;
                }

                if($ret_info['seller_schedule_num_has_done_month']>0){ //月到课率
                    $ret_info['lesson_succ_month_rate'] = $ret_info['seller_test_succ_month']/$ret_info['seller_schedule_num_has_done_month']*100;
                }else{
                    $ret_info['lesson_succ_month_rate'] = 0;
                }


                if($ret_info['seller_test_succ_month']>0){ //月试听转化率
                    $ret_info['trans_month_rate'] = $ret_info['order_trans_month']/$ret_info['seller_test_succ_month']*100;
                }else{
                    $ret_info['trans_month_rate'] = 0;
                }


                if($ret_info['has_tq_succ_sign_month']>0){ //月签约率
                    $ret_info['sign_month_rate'] = $ret_info['order_sign_month']/$ret_info['has_tq_succ_sign_month']*100;
                }else{
                    $ret_info['sign_month_rate'] = 0;
                }

                if($ret_info['has_called']>0){
                    $ret_info['succ_called_rate'] = $ret_info['has_tq_succ']/$ret_info['has_called_stu']*100; //接通率
                    $ret_info['claim_num_rate'] = $ret_info['claim_num']/$ret_info['has_called_stu']*100; //认领率
                }else{
                    $ret_info['claim_num_rate'] = 0;
                    $ret_info['succ_called_rate'] = 0;
                }


                if($ret_info['seller_num']>0){ // 人均通时
                    $ret_info['called_rate'] = $ret_info['cc_call_time']/$ret_info['seller_num'];
                }else{
                    $ret_info['called_rate'] = 0;
                }

                if($ret_info['cc_called_num']>0){
                    $ret_info['aver_called'] = $ret_info['seller_call_num']/$ret_info['cc_called_num']; // 人均呼出量
                    $ret_info['invit_rate'] = $ret_info['seller_invit_num']/$ret_info['cc_called_num']; // 人均邀约率
                }else{
                    $ret_info['aver_called'] = 0;
                    $ret_info['invit_rate'] = 0;
                }

                if($ret_info['new_stu']>0){ //月例子消耗数
                    $ret_info['stu_consume_rate'] = $ret_info['has_called_stu']/$ret_info['new_stu']*100;
                }else{
                    $ret_info['stu_consume_rate'] = 0;
                }
            }


        }

        $ret_info_arr["page_info"] = array(
            "total_num"      => 1,
            "per_page_count" => 100000,
            "page_num"       => 1,
        );

        if(!$ret_info_arr['list']){
            $ret_info_arr['list'] = [];
        }

        # 咨询各部门人数获取方式变更标示 2018-1-10 James
        $isTranFlag = 0;
        if($start_time>$onlineTime){
            $isTranFlag = 1;
        }

        return $this->pageView(__METHOD__, $ret_info_arr,[
            "ret_info"   => $ret_info_arr['list'],
            "isTranFlag" => $isTranFlag,
            "sellerNumArr" => $sellerNumArr
        ]);
    }


    public function seller()
    {
        $order_by_str = $this->get_in_str_val('order_by_str','');
        list($start_time,$end_time)= $this->get_in_date_range_month(date("Y-m-01"));
        $time = time(null);
        $ret_time = $this->t_month_def_type->get_all_list();
        foreach($ret_time as $item){//本月
            if($start_time>=$item['start_time'] && $start_time<$item['end_time']){
                $start_time = $item['start_time'];
                $end_time = $item['end_time'];
                break;
            }
        }
        $group_start_time = $start_time;
        $start_first = date('Y-m-01',$start_time);
        $adminid=$this->get_account_id();

        //判断top25,排课情况每月40
        $account_role = $this->t_manager_info->get_account_role($adminid);
        $self_top_info =$this->t_tongji_seller_top_info->get_admin_top_list($adminid,  $group_start_time );
        if(isset($self_top_info[6]["top_index"]) || $adminid == 349){
            $rank = @$self_top_info[6]["top_index"];
            if(($account_role ==2 && $rank<=25) || $adminid == 349){
                $top_num = $this->t_test_lesson_subject_require->get_seller_top_require_num($start_time,$end_time,$adminid);
                $seller_top_flag=1;
            }else{
                $seller_top_flag=0;
                $top_num =0;
            }
        }else{
            $seller_top_flag=0;
            $top_num =0;
        }



        //组长
        $test_seller_id = $this->get_in_int_val("test_seller_id",-1);
        $seller_account = $this->t_manager_info->get_account($test_seller_id);
        $son_adminid = $this->t_admin_main_group_name->get_son_adminid($adminid);
        $son_adminid_arr = [];
        foreach($son_adminid as $item){
            $son_adminid_arr[] = $item['adminid'];
        }
        array_unshift($son_adminid_arr,$adminid);
        $require_adminid_arr = array_unique($son_adminid_arr);
        $group_type = count($require_adminid_arr)>1?1:0;

        $adminid = in_array($test_seller_id,$require_adminid_arr)?$test_seller_id:$adminid;
        /* if($adminid==349){
           $adminid=397;
           }*/
        $self_groupid = $this->t_admin_group_user->get_groupid_by_adminid(2 , $adminid );
        $get_self_adminid = $this->t_admin_group_name->get_master_adminid($self_groupid);
        if($adminid == $get_self_adminid){
            $is_group_leader_flag = 1;
        }else{
            $is_group_leader_flag = 0;
        }
        $major_groupid = $this->t_admin_majordomo_group_name->get_master_adminid_by_adminid($adminid);
        if($major_groupid>0){
            $is_group_leader_flag = 1;
        }
        $self_info= $this->t_order_info->get_1v1_order_seller($this->get_account(),
                                                              $start_time,$end_time );

        $ret_info= $this->t_order_info->get_1v1_order_seller_list($start_time,$end_time);

        $groupid =$this->get_in_int_val("groupid",-1);

        if($groupid == -1) {
            $groupid=$this->t_admin_group_user->get_groupid_by_adminid(2 , $adminid );
            $group_name="组内";
        }else{
            $group_name=$this->t_admin_group_name->get_group_name($groupid);
        }
        $group_self_list = $this->t_order_info->get_1v1_order_seller_list_group_self($start_time,$end_time,$groupid);
        $group_list      = $this->t_order_info->get_1v1_order_seller_list_group($start_time,$end_time,-1,$start_first,$order_by_str);
        //经理
        $main_groupid = $this->t_admin_main_group_name->get_groupid_by_adminid($adminid);
        foreach($group_list as $key=>&$item){
            $item['all_price'] = $item['all_price']/100;
            $all_price = $item['all_price'];
            $month_money = isset($item['month_money'])?$item['month_money']:0;
            $item['finish_per'] = $month_money>0?$all_price/$month_money:0;
            $item['finish_per'] = round($item['finish_per']*100,1).'%';
            if(!in_array($this->get_account(),['班洁','tom','jim']) && $key>4 && $item['groupid']!=$self_groupid && $item['up_groupid'] != $main_groupid){
                $item["all_price"] = "***";
            }
        }

        $ret_info_first = [];
        $ret_info_two = [];
        foreach ($ret_info["list"] as $key=> &$item) {
            $item["index"]=$key+1;
            $item["all_price"] =sprintf("%.2f", $item["all_price"]  );
            if($key == 0){
                $ret_info_first = $item;
            }elseif($key == 1){
                $ret_info_two = $item;
            }
        }
        $ret_info["list"] = array_filter($ret_info["list"]);
        if(count($ret_info["list"])>2){
            $ret_info["list"][0] = $ret_info_two;
            $ret_info["list"][1] = $ret_info_first;
        }

        $self_top_info =$this->t_tongji_seller_top_info->get_admin_top_list( $adminid,  $group_start_time );
        $this->get_in_int_val("self_groupid",$self_groupid);
        $this->get_in_int_val("is_group_leader_flag",$is_group_leader_flag);

        //得到预期的试听成功数
        //date("y-m-01");

        // 本月签单率
        list($start_time,$end_time)=$this->get_in_date_range_month(date("Y-m-01") );
        $tongji_type=$this->get_in_int_val("tongji_type",5, E\Etongji_type::class); //5 代表查询本月签单率
        $ret_info_num = $this->t_tongji_seller_top_info->get_list_top($tongji_type,$start_time);
        foreach($ret_info_num as &$item) {
            $this->cache_set_item_account_nick($item);
            $item['value'] = $item['value'].'%';
        }


        $day= strtotime(date("Y-m-d") );
        $w=  date("w") ;
        if ($w==0)  $w=7;

        if (in_array($w,[5,6,7])) {
            $hw_start_time=$day-($w-5)*86400;
            $hw_end_time= $hw_start_time + 3*86400;
        }else{
            $hw_start_time=$day-($w-1)*86400;
            $hw_end_time= $hw_start_time + 4*86400;
        }



        $half_week_info= $this->t_order_info->get_1v1_order_seller_list($hw_start_time,$hw_end_time, [-1],"limit 5" );


        foreach ($half_week_info["list"] as $key=> &$item) {
            $item["all_price"] =sprintf("%.2f", $item["all_price"]  );
        }
        $self_top_info =$this->t_tongji_seller_top_info->get_admin_top_list( $adminid,  $group_start_time );
        //提成刺激
        $money_info = $this->seller_month_money_list($adminid);
        $self_money['differ_price'] = $money_info['next_all_price']-$money_info['all_price'];
        $self_money['differ_money'] = $money_info['next_money']-$money_info['money'];
        //上周试听取消率
        $time = strtotime(date('Y-m-d',time()).'00:00:00');
        $week = date('w',$time);
        if($week == 0){
            $week = 7;
        }elseif($week == 1){
            $week = 8;
        }
        // dd($self_top_info);

        $end_time = $time-3600*24*($week-2);
        $start_time = $end_time-3600*24*7;
        $week_start_time = date('m/d',$start_time);
        $week_end_time = date('m/d',$end_time);
        $test_fail_info = $this->t_tongji_seller_top_info->get_admin_top_list($adminid,$start_time);
        $self_top_info[13]["value"] = isset($test_fail_info[13]["value"])?$test_fail_info[13]["value"]:0;
        $self_top_info[14]["value"] = isset($test_fail_info[14]["value"])?$test_fail_info[14]["value"]:0;
        $self_top_info[15]["value"] = isset($test_fail_info[15]["value"])?$test_fail_info[15]["value"]:0;
        $self_top_info[15]["top_index"] = isset($test_fail_info[15]["top_index"])?$test_fail_info[15]["top_index"]:0;


        //今日需回访
        $today = date('Y-m-d',time());
        $before_week_today = date('Y-m-d',strtotime($today."00:00:00")-3600*24*7);
        $row_item = $this->t_seller_student_new-> get_lesson_status_count($adminid );
        $next_revisit_count = isset($row_item['next_revisit_count'])?$row_item['next_revisit_count']:0;
        $next_time_str = "date_type=1&opt_date_type=0&start_time=".$before_week_today."&end_time=".$today;
        // dd($ret_info);
        $adminid = $this->get_account_id();
        list($start_time,$end_time)=$this->get_in_date_range_month(date("Y-m-01") );
        $month_start = strtotime( date('Y-m-01', $start_time) );
        $next_week = strtotime('next week',$month_start);
        $week_2    = date('y-m-d 23:59:59',$next_week);
        $end_time = strtotime($week_2);
        //判断是不是助长
        $no_order = [];
        $is_zuzhang = $this->t_admin_group_name->is_master($adminid);
        if ($is_zuzhang) {
            $no_order = $this->t_manager_info->get_no_order_list(1,$adminid,$month_start,$end_time);
        }
        //判断是不是主管
        $is_zhuguan = $this->t_admin_main_group_name->is_master($adminid);
        if ($is_zhuguan) {
            $no_order = $this->t_manager_info->get_no_order_list(2,$adminid,$month_start,$end_time);
        }

        //判断是不是总监
        $is_master = $this->t_admin_majordomo_group_name->is_master($adminid);
        if ($is_master == false) {
            $no_order = $this->t_manager_info->get_no_order_list(3,$adminid,$month_start,$end_time);
        }
        //判断是不是月第二周的周二，是否弹出
        $cur_time = time();
        $cur_month = strtotime( date('Y-m-01', $cur_time) );
        $next_week = strtotime('next week',$cur_month);//周一
        $next_week_2 = strtotime('+1 day',$next_week);//周二

        $force_flag = 'false';
        $is_sir = false;
        $is_time = false;
        //李子璇，tina,谢总
        if($adminid == 898 || $adminid == 1165 || $adminid == 323 ) {
            $is_sir = true;
            $no_order = $this->t_manager_info->get_no_order_list(4,$adminid,$month_start,$end_time);
        }
        if( $cur_time >= $next_week_2 && $cur_time < $next_week_2+86400 ) {//是节点
            $is_time = true;
        }
        if($is_zuzhang || $is_zhuguan || $is_master){//是sir
            $is_sir = true;
        }

        if($is_time && $is_sir){
            $force_flag = 'true';
            $alert_time = $this->t_manager_info->get_alert_time_by_uid($adminid);
            if( $alert_time > strtotime('today') ) {
                $force_flag = 'false';
            }
        }

        return $this->pageView(__METHOD__, $ret_info, [
            "ret_info_num"           => $ret_info_num,
            "group_list"             => $group_list,
            "group_self_list"        => $group_self_list ,
            "group_name"             => $group_name,
            "self_info"              => $self_info,
            "half_week_info"         => $half_week_info["list"],
            "self_top_info"          => $self_top_info,
            "self_groupid"           => $self_groupid,
            "is_group_leader_flag"   => $is_group_leader_flag,
            "test_lesson_need_count" => $this->t_seller_month_money_target->get_test_lesson_count($adminid,date("Y-m-01") ),
            "self_money"             => $self_money,
            "start_time"             => $week_start_time,
            "end_time"               => $week_end_time,
            "group_type"             => $group_type,
            "seller_account"         => $seller_account,
            "top_num"                => $top_num,
            "seller_top_flag"        => $seller_top_flag,
            "next_revisit_count"     => $next_revisit_count,
            "next_time_str"          => $next_time_str,
            "is_master"              => $is_master,
            "is_sir"                 => $is_sir,
            "force_flag"             => $force_flag,
            "no_order"               => @$no_order,
        ]);
    }

    public function update_alert_time(){
        $adminid = $this->get_account_id();
        $this->t_manager_info->field_update_list($adminid,['alert_time' => time()]);
        return $this->output_succ();
    }

    public function seller_gold_room()
    {
        $order_by_str = $this->get_in_str_val('order_by_str','');
        list($start_time,$end_time)= $this->get_in_date_range_month(date("Y-m-01"));
        $time = time(null);
        $ret_time = $this->t_month_def_type->get_all_list();
        foreach($ret_time as $item){//本月
            if($start_time>=$item['start_time'] && $start_time<$item['end_time']){
                $start_time = $item['start_time'];
                $end_time = $item['end_time'];
                break;
            }
        }
        $group_start_time = $start_time;
        $start_first = date('Y-m-01',$start_time);
        $adminid=$this->get_account_id();

        //判断top25,排课情况每月40
        $account_role = $this->t_manager_info->get_account_role($adminid);
        $self_top_info =$this->t_tongji_seller_top_info->get_admin_top_list($adminid,  $group_start_time );
        if(isset($self_top_info[6]["top_index"]) || $adminid == 349){
            $rank = @$self_top_info[6]["top_index"];
            if(($account_role ==2 && $rank<=25) || $adminid == 349){
                $top_num = $this->t_test_lesson_subject_require->get_seller_top_require_num($start_time,$end_time,$adminid);
                $seller_top_flag=1;
            }else{
                $seller_top_flag=0;
                $top_num =0;
            }
        }else{
            $seller_top_flag=0;
            $top_num =0;
        }
        //组长&主管
        $test_seller_id = $this->get_in_int_val("test_seller_id",-1);
        $seller_account = $this->t_manager_info->get_account($test_seller_id);
        $son_adminid = $this->t_admin_main_group_name->get_son_adminid($adminid);
        $son_adminid_arr = [];
        foreach($son_adminid as $item){
            $son_adminid_arr[] = $item['adminid'];
        }
        array_unshift($son_adminid_arr,$adminid);
        $require_adminid_arr = array_unique($son_adminid_arr);
        $group_type = count($require_adminid_arr)>1?1:0;

        $adminid = in_array($test_seller_id,$require_adminid_arr)?$test_seller_id:$adminid;
        $self_groupid = $this->t_admin_group_user->get_groupid_by_adminid(2 , $adminid );
        $get_self_adminid = $this->t_admin_group_name->get_master_adminid($self_groupid);
        if($adminid == $get_self_adminid){
            $is_group_leader_flag = 1;
        }else{
            $is_group_leader_flag = 0;
        }
        $self_info= $this->t_order_info->get_1v1_order_seller($this->get_account(),$start_time,$end_time );
        $ret_info= $this->t_order_info->get_1v1_order_seller_list_new($start_time,$end_time);
        $groupid =$this->get_in_int_val("groupid",-1);
        if($groupid == -1) {
            $groupid=$this->t_admin_group_user->get_groupid_by_adminid(2 , $adminid );
            $group_name="组内";
        }else{
            $group_name=$this->t_admin_group_name->get_group_name($groupid);
        }
        $group_self_list = $this->t_order_info->get_1v1_order_seller_list_group_self($start_time,$end_time,$groupid);
        $group_list      = $this->t_order_info->get_1v1_order_seller_list_group_new($start_time,$end_time,-1,$start_first,$order_by_str);
        foreach($group_list as &$item){
            $item['all_price'] = $item['all_price']/100;
            $all_price = $item['all_price'];
            $month_money = isset($item['month_money'])?$item['month_money']:0;
            $item['finish_per'] = $month_money>0?$all_price/$month_money:0;
            $item['finish_per'] = round($item['finish_per']*100,1);
        }

        $ret_info_first = [];
        $ret_info_two = [];
        foreach ($ret_info["list"] as $key=> &$item) {
            $item["index"]=$key+1;
            $item["all_price"] =sprintf("%.2f", $item["all_price"]  );
            if($key == 0){
                $ret_info_first = $item;
            }elseif($key == 1){
                $ret_info_two = $item;
            }
        }
        $ret_info["list"] = array_filter($ret_info["list"]);
        if(count($ret_info["list"])>2){
            $ret_info["list"][0] = $ret_info_two;
            $ret_info["list"][1] = $ret_info_first;
        }

        $self_top_info =$this->t_tongji_seller_top_info->get_admin_top_list( $adminid,  $group_start_time );
        $this->get_in_int_val("self_groupid",$self_groupid);
        $this->get_in_int_val("is_group_leader_flag",$is_group_leader_flag);

        //得到预期的试听成功数
        //date("y-m-01");

        // 本月签单率
        list($start_time,$end_time)=$this->get_in_date_range_month(date("Y-m-01") );
        $tongji_type=$this->get_in_int_val("tongji_type",5, E\Etongji_type::class); //5 代表查询本月签单率
        $ret_info_num = $this->t_tongji_seller_top_info->get_list_top($tongji_type,$start_time);
        foreach($ret_info_num as &$item) {
            $this->cache_set_item_account_nick($item);
            $item['value'] = $item['value'].'%';
        }


        $day= strtotime(date("Y-m-d") );
        $w=  date("w") ;
        if ($w==0)  $w=7;

        if (in_array($w,[5,6,7])) {
            $hw_start_time=$day-($w-5)*86400;
            $hw_end_time= $hw_start_time + 3*86400;
        }else{
            $hw_start_time=$day-($w-1)*86400;
            $hw_end_time= $hw_start_time + 4*86400;
        }



        $half_week_info= $this->t_order_info->get_1v1_order_seller_list($hw_start_time,$hw_end_time, [-1],"limit 5" );
        foreach ($half_week_info["list"] as $key=> &$item) {
            $item["all_price"] =sprintf("%.2f", $item["all_price"]  );
        }
        $self_top_info =$this->t_tongji_seller_top_info->get_admin_top_list( $adminid,  $group_start_time );
        //提成刺激
        $money_info = $this->seller_month_money_list($adminid);
        $self_money['differ_price'] = $money_info['next_all_price']-$money_info['all_price'];
        $self_money['differ_money'] = $money_info['next_money']-$money_info['money'];
        //上周试听取消率
        $time = strtotime(date('Y-m-d',time()).'00:00:00');
        $week = date('w',$time);
        if($week == 0){
            $week = 7;
        }elseif($week == 1){
            $week = 8;
        }
        // dd($self_top_info);

        $end_time = $time-3600*24*($week-2);
        $start_time = $end_time-3600*24*7;
        $week_start_time = date('m/d',$start_time);
        $week_end_time = date('m/d',$end_time);
        $test_fail_info = $this->t_tongji_seller_top_info->get_admin_top_list($adminid,$start_time);
        $self_top_info[13]["value"] = isset($test_fail_info[13]["value"])?$test_fail_info[13]["value"]:0;
        $self_top_info[14]["value"] = isset($test_fail_info[14]["value"])?$test_fail_info[14]["value"]:0;
        $self_top_info[15]["value"] = isset($test_fail_info[15]["value"])?$test_fail_info[15]["value"]:0;
        $self_top_info[15]["top_index"] = isset($test_fail_info[15]["top_index"])?$test_fail_info[15]["top_index"]:0;

        //今日需回访
        $today = date('Y-m-d',time());
        $before_week_today = date('Y-m-d',strtotime($today."00:00:00")-3600*24*7);
        $row_item = $this->t_seller_student_new-> get_lesson_status_count($adminid );
        $next_revisit_count = isset($row_item['next_revisit_count'])?$row_item['next_revisit_count']:0;
        $next_time_str = "date_type=1&opt_date_type=0&start_time=".$before_week_today."&end_time=".$today;
        // dd($ret_info);
        //判断是不是总监
        $adminid   = $this->get_account_id();
        $is_master = $this->t_admin_majordomo_group_name->is_master($adminid);
        return $this->pageView(__METHOD__, $ret_info, [
            "ret_info_num"           => $ret_info_num,
            "group_list"             => $group_list,
            "group_self_list"        => $group_self_list ,
            "group_name"             => $group_name,
            "self_info"              => $self_info,
            "half_week_info"         => $half_week_info["list"],
            "self_top_info"          => $self_top_info,
            "self_groupid"           => $self_groupid,
            "is_group_leader_flag"   => $is_group_leader_flag,
            "test_lesson_need_count" => $this->t_seller_month_money_target->get_test_lesson_count($adminid,date("Y-m-01") ),
            "self_money"             => $self_money,
            "start_time"             => $week_start_time,
            "end_time"               => $week_end_time,
            "group_type"             => $group_type,
            "seller_account"         => $seller_account,
            "top_num"                => $top_num,
            "seller_top_flag"        => $seller_top_flag,
            "next_revisit_count"     => $next_revisit_count,
            "next_time_str"          => $next_time_str,
            "is_master"              => $is_master,
        ]);
    }

    public function seller_month_money_list($adminid) {
        list($start_time,$end_time)=$this->get_in_date_range_month(0);
        $month= date("Ym",$start_time);
        switch ( $month ) {
        case "201702" :
        case "201703" :
            $arr=\App\Strategy\sellerOrderMoney\seller_order_money_base::get_info_by_type(
                $month, $adminid, $start_time, $end_time ) ;
            break;
        default:
            $arr=\App\Strategy\sellerOrderMoney\seller_order_money_base::get_cur_info(
                $adminid, $start_time, $end_time ) ;
            break;
        }
        $arr_next = \App\Strategy\sellerOrderMoney\seller_order_money_base::get_cur_info_next(
            $adminid, $start_time, $end_time );
        $arr['next_all_price'] = $arr_next['all_price'];
        $arr['next_money'] = $arr_next['money'];
        return $arr;
    }

    public  function assistant() {
        $this->switch_tongji_database();

        $end_time = strtotime( date("Y-m-d") );
        $end_time_date = date("Y-m-d") ;
        $start_time   = $end_time-100*86400;
        $start_time_date  = date("Y-m-d",$start_time);
        $account = $this->get_account();
        if($account=="jack"){
            $account="ninasun";
        }
        $assistantid=$this->t_assistant_info->get_assistantid( $account );
        if($assistantid==0){
            $assistantid = -1;
        }
        $ret_info = $this->t_gift_consign->get_consign_status_list($start_time,$end_time,$assistantid);

        //
        list($start_time,$end_time) = $this->get_in_date_range( date("Y-m-01",time(NULL)) ,0 );
        // $lesson_count_list=$this->t_lesson_info_b2->get_confirm_lesson_list_new($start_time,$end_time);
        $lesson_count_list= $this->t_month_ass_student_info->get_ass_month_info_lesson($start_time);
        // dd($lesson_count_list);
        //

        $lesson_all=0;$user_all=0;
        /*foreach($lesson_count_list['list'] as &$item ){
            // $item["assistant_nick"] =$this->cache_get_assistant_nick($item["assistantid"]);
            $lesson_all += $item["lesson_count"];
            $user_all += $item["user_count"];
            }*/
        $xs = !empty($user_all)?round($lesson_all/$user_all/100,1):0;
        $stu_info=$this->t_student_info->tongji_assisent($assistantid);

        $assistant_renew_list = $this->t_assistant_info->get_all_assistant_renew($start_time,$end_time);
        $all_money_ass = 0;
        foreach($assistant_renew_list as &$val){
            $val['bye_total']=$val['all_total']-$val['give_total'];
            $all_money_ass += $val["all_price"];
        }
        #dd($assistant_renew);exit;

        // $ass_adminid = $this->get_account_id();
        // $now = time();
        // $three = $now - 86400*7;
        // $warning_count = $this->t_revisit_info->get_ass_revisit_warning_count($ass_adminid, $three,-1);
        // $warning_type_num = [
        //     'warning_type_one' =>0,
        //     'warning_type_two' =>0,
        //     'warning_type_three' =>0
        // ];
        // foreach($warning_count as $item){
        //     \App\Helper\Utils::revisit_warning_type_count($item, $warning_type_num);
        // }

        $opt_date_type = $this->get_in_int_val("opt_date_type",3);
        // dd($opt_date_type);
        if($opt_date_type==3){
            $cur_start = $start_time;
            $cur_end = $end_time;

        }else{
            $cur_start = strtotime(date('Y-m-01',$end_time));
            $cur_end = strtotime(date('Y-m-01',$cur_start+40*86400));
        }
        // $three_count = $this->t_revisit_warning_overtime_info->get_ass_warning_overtime_count($ass_adminid, -1, $cur_start, $cur_end);
        // $warning_type_num['warning_type_three'] = $three_count;


        // //月回访信息
        // $month_list = $this->t_revisit_assess_info->get_month_assess_info_by_uid($ass_adminid, $cur_start, $cur_end);
        // $month_info = @$month_list[0];
        // $month_info["call_num"]= \App\Helper\Common::get_time_format_minute(@$month_info["call_num"]);
        // //当天回访信息
        // $start_time = strtotime( "today" );
        // $end_time   = strtotime("tomorrow");
        // $today_info = $this->t_manager_info->get_today_assess_info_by_uid($ass_adminid, $start_time, $end_time);
        // $call_num   = $this->t_revisit_call_count->get_today_call_count($ass_adminid, $start_time, $end_time);
        // $today_info["call_num"]= \App\Helper\Common::get_time_format_minute($call_num);
        // $today_info['goal'] = ceil(@$today_info['stu_num']/10);


        $ass_month= $this->t_month_ass_student_info->get_ass_month_info_payroll($cur_start);
        $master_arr=[];
        foreach($ass_month as &$ass_val){
            $list=$this->get_ass_percentage_money_list($ass_val);
            $ass_val["lesson_price_money"] = $list["lesson_money"];
            $ass_val["kk_money"] = $list["kk_money"];
            $ass_val["renw_money"] = $list["renw_money"];
            $ass_val["tran_num_money"] = $list["tran_num_money"];
            $ass_val["cc_tran_price"] = $list["cc_tran_money"];
            $ass_val["all_money"] = $list["all_money"];
            if(!isset($master_arr[$ass_val["master_adminid"]])){
                $master_arr[$ass_val["master_adminid"]] =$ass_val["master_adminid"];
            }
        }

        \App\Helper\Utils::order_list( $ass_month,"all_money", 0 );
        $i=1;
        foreach($ass_month as &$v){
            $v["num_range"]=$i;
            $i++;
        }
        $account_id = $this->get_account_id();
        $account_role = $this->get_account_role();
        if($account_role==12 || $account_id==396 || $account_id==186){

        }elseif(in_array($account_id,$master_arr)){
            foreach($ass_month as $k=>$tt){
                if($tt["master_adminid"] != $account_id){
                    unset($ass_month[$k]);
                }
            }
        }else{
            foreach($ass_month as $k=>$tt){
                if($tt["adminid"] != $account_id){
                    unset($ass_month[$k]);
                }
            }

        }
        $assign_lesson_count = $this->t_assistant_info->get_assign_lesson_count($assistantid);
        $used_assign_lesson_count = $this->t_order_info->get_assign_lesson_count_by_account($account);

        $refund_warning = $this->t_student_info->get_refund_warning($assistantid);
        foreach($refund_warning as $re_val) {
            @$refund_warning["total"] += $re_val;
        }

        return $this->pageView(__METHOD__ ,null, [
            "ret_info" => $ret_info,
            "end_time" => $end_time_date,
            "start_time" => $start_time_date,
            "assistantid" => $assistantid,
            "lesson_count_list" => $lesson_count_list,
            "stu_info" => $stu_info,
            "assistant_renew_list" => $assistant_renew_list,
            "all_money_ass"=>$all_money_ass,
            "lesson_all"   =>$lesson_all,
            "user_all"     =>$user_all,
            "xs"           =>$xs,
            "warning"      => @$warning_type_num,
            "month_info"   => @$month_info,
            "today_info"   => @$today_info,
            "ass_month"    =>  $ass_month,
            "assign_lesson_count"=>$assign_lesson_count,
            "used_assign_lesson_count"=>$used_assign_lesson_count,
            "refund_warning" => $refund_warning
        ]);

    }

    public  function assistant_leader() {
        dd("暂停使用");
        $account_id = $this->get_account_id();
        $main_type = 1;
        $is_master = $this->t_admin_main_group_name->check_is_master($main_type,$account_id);
        if($is_master>0 || $account_id==349 ){
            $up_master_adminid=-1;
        }else{
            $up_master_adminid=0;
        }
        list($start_time,$end_time) = $this->get_in_date_range( date("Y-m-01",time(NULL)) ,0 );

        //$start = date('Y-m-d',$start_time);

        $this->t_manager_info->switch_tongji_database();
        $target_info = $this->t_manager_info->get_assistant_month_target_info($start_time);

        $assistant_stu_info=$this->t_manager_info->get_assistant_stu_info_new($end_time);
        $except_revisit_info=[];
        $stu_is_read=[];
        foreach($assistant_stu_info as $item){
            $uid = $item["uid"];
            $ass_assign_time = $item["ass_assign_time"];
            if($ass_assign_time < $start_time){
                @$except_revisit_info[$uid] +=4;
            }else{
                @$except_revisit_info[$uid] ++;
            }
            @$stu_is_read[$uid]++;
        }

        $revisit_info = $this->t_manager_info->tongji_assistant_revisit_info($start_time,$end_time);
        foreach($revisit_info as $k=>&$item){
            $item["except_revisit_count"] = @$except_revisit_info[$k];
            $item["revisit_per"] = !empty($item["except_revisit_count"])?round($item["revisit_count"]/$item["except_revisit_count"],4)*100:0;
        }
        $lesson_count_list = $this->t_manager_info->get_assistant_lesson_count_info($start_time,$end_time);
        foreach($lesson_count_list as $k=>&$item){
            $item["stu_is_read"]=@$stu_is_read[$k];
            $item["read_xs"] = !empty($item["stu_is_read"])?round($item["lesson_count"]/$item["stu_is_read"]):0;
            $item["lesson_xs"] = round($item["lesson_count"]/$item["user_count"]);
        }

        $assistant_renew_list = $this->t_manager_info->get_all_assistant_renew_new($start_time,$end_time);
        $all_money_ass = 0;
        foreach($assistant_renew_list as  &$val){
            $val['buy_total']=$val['all_total']-$val['give_total'];
            $all_money_ass += $val["all_price"];
        }

        $jk_stu = $this ->t_manager_info->get_assistant_jk_stu_info();
        $assistant_admin_info = $this->t_manager_info->get_assistant_admin_member_list($up_master_adminid,$account_id);


        $admin_list= &$assistant_admin_info['list'] ;


        foreach ($admin_list as $vk=>&$val){
            $adminid=$val['adminid'];
            $val['admin_revisiterid'] = $adminid ;
            $ret_item=@$revisit_info[$adminid];
            $val['revisit_count'] = @$ret_item['revisit_count'];
            $val['except_revisit_count'] = @$ret_item['except_revisit_count'];
            $val['revisit_per'] = @$ret_item['revisit_per'];
            $val['no_call'] = @$ret_item['no_call'];
            $lesson_item = @$lesson_count_list[$adminid];
            $val['lesson_count'] = @$lesson_item['lesson_count'];
            $val['user_count'] = @$lesson_item['user_count'];
            $val['stu_is_read'] = @$lesson_item['stu_is_read'];
            $val['read_xs'] = @$lesson_item['read_xs'];
            $val['lesson_xs'] = @$lesson_item['lesson_xs'];
            $renew_item = @$assistant_renew_list[$adminid];
            $val['all_student'] = @$renew_item['all_student'];
            $val['all_price'] = @$renew_item['all_price'];
            $val['give_total'] = @$renew_item['give_total'];
            $val['buy_total'] = @$renew_item['buy_total'];
            $val['jk_num'] = @$jk_stu[$adminid]['jk_num'];
            $val['lesson_target'] = @$target_info["list"][$adminid]['lesson_target'];

        }
        $ret_info=\App\Helper\Common::gen_admin_member_data($assistant_admin_info['list']);
        foreach( $ret_info as &$item ) {
            E\Emain_type::set_item_value_str($item);
            if($item["level"] != "l-4"){
                $item["revisit_per"] = !empty($item["except_revisit_count"])?round($item["revisit_count"]/$item["except_revisit_count"],4)*100:0;
                $item["read_xs"] = !empty($item["stu_is_read"])?round($item["lesson_count"]/$item["stu_is_read"]):0;
                $item["lesson_xs"] =  !empty($item["user_count"])?round($item["lesson_count"]/$item["user_count"]):0;

                $item["lesson_target"]="";
            }else{
                $item["target_per"] =  (!empty($item["lesson_target"])?round($item["read_xs"]/$item["lesson_target"],4):0)."%";
            }

        }


        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info),["data_ex_list"=>$ret_info]);

        // dd($ret_info);

    }


    public function jw_teacher(){
        list($start_time,$end_time) = $this->get_in_date_range( date("Y-m-01",time(NULL)) ,0 );
        // $this->t_test_lesson_subject_require->switch_tongji_database();
        // $res        = $this->t_test_lesson_subject_sub_list->get_teat_lesson_transfor_info($start_time,$end_time);

        $none_total = $this->t_test_lesson_subject_require->get_none_total_info($start_time,$end_time);
        $no_assign_total = $this->t_test_lesson_subject_require->get_no_assign_total_info($start_time,$end_time);
        $all_total  = 0;

        $month_start = strtotime(date("Y-m-01",time(NULL)));
        // $revisit_info = $this->t_teacher_info->get_jw_assign_teacher_info($start_time,$end_time);
        //重新 开发 JIM TODO
        //$res        = $this->t_test_lesson_subject_require->get_teat_lesson_transfor_info($start_time,$end_time);
        $res=[] ;
        $ret_info   = $this->t_test_lesson_subject_require->get_jw_teacher_test_lesson_info($start_time,$end_time);
        $tra_info =  $this->t_jw_teacher_month_plan_lesson_info->get_info_by_month_new($start_time);
        // $all        = $task->t_test_lesson_subject_require->get_teat_lesson_transfor_info_type($start_time,$end_time);
        // $ass       = $task->t_test_lesson_subject_require->get_teat_lesson_transfor_info_type($start_time,$end_time,1);
        // $seller        = $this->t_test_lesson_subject_require->get_teat_lesson_transfor_info_type($start_time,$end_time,2);
        //  $ass_green        = $this->t_test_lesson_subject_require->get_teat_lesson_transfor_info_type($start_time,$end_time,1,1);
        //$seller_green        = $this->t_test_lesson_subject_require->get_teat_lesson_transfor_info_type($start_time,$end_time,2,1);

        foreach($ret_info as &$item){
            $item["all_count"] = $item["all_count"]-$item["back_other_count"];

            if($start_time == strtotime(date("2017-01-01"))){
                $s = strtotime(date("2017-01-01 00:00:00"));
                $e = strtotime(date("2017-01-03 12:00:00"));
                $bc_info   = $this->t_test_lesson_subject_require->get_jw_teacher_test_lesson_info_bc($s,$e);
                $item["all_count"] += @$bc_info[$item["accept_adminid"]]["all_count"];
                $item["set_count"] += @$bc_info[$item["accept_adminid"]]["set_count"];
                $item["gz_count"] += @$bc_info[$item["accept_adminid"]]["gz_count"];
                $item["back_count"] += @$bc_info[$item["accept_adminid"]]["back_count"];
                $item["un_count"] += @$bc_info[$item["accept_adminid"]]["un_count"];
            }
            // $item["tra_per"] = $item["all_count"]==0?"0":(round($item["tra_count"]/$item["all_count"],4)*100);
            $item["tra_count"] = @$tra_info[$item["accept_adminid"]]["tran_count"];
            $item["tra_count_ass"] = @$tra_info[$item["accept_adminid"]]["tran_count_ass"];
            $item["tra_count_seller"] = @$tra_info[$item["accept_adminid"]]["tran_count_seller"];
            $item["tra_count_green"] = @$tra_info[$item["accept_adminid"]]["tran_count_green"];
            $item["tra_per_str"] = @$tra_info[$item["accept_adminid"]]["tran_per"];
            $item["set_per"] = $item["all_count"]==0?"无":(round($item["set_count"]/$item["all_count"],4)*100)."%";
            $item["ass_green_tran_count"] = @$tra_info[$item["accept_adminid"]]["ass_tran_green_count"];
            $item["seller_green_tran_count"] = @$tra_info[$item["accept_adminid"]]["seller_tran_green_count"];
            $item["tran_count_seller_top"] = @$tra_info[$item["accept_adminid"]]["tran_count_seller_top"];
            $item["top_per"] = $item["top_count"]==0?"无":(round($item["tran_count_seller_top"]/$item["top_count"],4)*100)."%";


            $all_total += $item["set_count"];
        }

        $day_start = strtotime(date("Y-m-d",time()));
        $day_end = $day_start+86400;
        $test_lesson_info = $this->t_test_lesson_subject_require->get_test_lesson_info_time($day_start,$day_end);
        $cur_num = 0;
        foreach($test_lesson_info as &$val){
            $h_s = $val["h"];
            $h_e = $h_s+1;
            if($h_s<10){
                $h_s="0".$h_s;
            }
            if($h_e<10){
                $h_e="0".$h_e;
            }

            $val["hour"] = $h_s.":00 -".$h_e.":00";
            $cur_num +=$val["num"];

        }
        $week_start = $day_start+86400;
        $week_end = $day_start+7*86400;
        $day_arr=[];
        $week_test_lesson = $this->t_test_lesson_subject_require->get_test_lesson_info_time_week($week_start,$week_end);
        $test_week=[];
        $test_num=[];
        foreach($week_test_lesson as $ss){
            $day = date("m-d",$ss["stu_request_test_lesson_time"]);
            $h_s = date("H",$ss["stu_request_test_lesson_time"]);
            $h_e = $h_s+1;
            if($h_e<10){
                $h_e="0".$h_e;
            }

            $hour = $h_s.":00 -".$h_e.":00";
            @$test_week[$day][$hour]++;
            @$test_num[$day]++;
        }

        //dd($test_week);
        //$lecture_info = $this->t_teacher_lecture_appointment_info->get_teacher_lecture_tongji($start_time,$end_time);
        \App\Helper\Utils::order_list( $ret_info,"all_count", 0 );
        return $this->pageView(__METHOD__ ,null, [
            "ret_info" => $ret_info,
            "all_total" => $all_total,
            "none_total"=> $none_total,
            "no_assign_total"=> $no_assign_total,
            "test_lesson_info"=>$test_lesson_info,
            "test_week"=>$test_week,
            "cur_num"   =>$cur_num,
            "test_num"  =>$test_num
        ]);
    }

    public function quality_control_kpi(){
        $this->set_in_value("account_role",9);
        $this->set_in_value("kpi_flag",1);
        return $this->quality_control();
    }

    public function quality_control_jy(){
        return $this->quality_control();
    }

    public function  quality_control(){
        $this->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],1 );
        $subject = $this->get_in_int_val("subject",-1);
        $account_role = $this->get_in_int_val("account_role",-2);
        $kpi_flag = $this->get_in_int_val("kpi_flag",0);
        $teacher_info = $this->t_manager_info->get_adminid_list_by_account_role($account_role);//return->uid,account,nick,name
        foreach($teacher_info as $kk=>$vv){
            if(in_array($kk,[992,891,486,871,1058,1080])){
                unset($teacher_info[$kk]);
            }
        }
        // $teacher_info[349]= ["uid"=>349,"account"=>"jack","name"=>"jack"];
        $tea_subject = "";

        //面试人数
        $real_info = $this->t_teacher_lecture_info->get_lecture_info_by_time_new(
            $subject,$start_time,$end_time,-1,-1,-1,$tea_subject,-2);
        $lecture_list_info=$real_info;
        $real_arr = $this->t_teacher_record_list->get_train_teacher_interview_info(
            $subject,$start_time,$end_time,-1,-1,-1,$tea_subject,-2);
        $one_list_info = $real_arr;
        foreach($real_arr["list"] as $p=>$pp){
            if(isset($real_info["list"][$p])){
                $real_info["list"][$p]["all_count"] += $pp["all_count"];
                $real_info["list"][$p]["all_num"] += $pp["all_num"];
            }else{
                $real_info["list"][$p]= $pp;
            }

        }
        //模拟试听审核
        $train_first = $this->t_teacher_record_list->get_trial_train_lesson_first($start_time,$end_time,1,$subject);
        $train_second = $this->t_teacher_record_list->get_trial_train_lesson_first($start_time,$end_time,2,$subject);

        //第一次试听/第一次常规
        $test_first = $this->t_teacher_record_list->get_test_regular_lesson_first($start_time,$end_time,1,$subject);
        $test_first_per = $this->t_teacher_record_list->get_test_regular_lesson_first_per($start_time,$end_time,1,$subject);


         //第5次试听
        $test_five = $this->t_teacher_record_list->get_test_regular_lesson_first($start_time,$end_time,2,$subject);

        $test_five_per = $this->t_teacher_record_list->get_test_regular_lesson_first_per($start_time,$end_time,2,$subject);
        //第一次常规
        //dd($test_first_per);
        $regular_first = $this->t_teacher_record_list->get_test_regular_lesson_first($start_time,$end_time,3,$subject);
        $regular_first_per = $this->t_teacher_record_list->get_test_regular_lesson_first_per($start_time,$end_time,3,$subject);

        //第5次常规
        $regular_five = $this->t_teacher_record_list->get_test_regular_lesson_first($start_time,$end_time,4,$subject);

        $regular_five_per = $this->t_teacher_record_list->get_test_regular_lesson_first_per($start_time,$end_time,4,$subject);


        $all_count=0;
        $total_test_first_per = 0;
        $total_test_five_per = 0;
        $total_test_first_num = 0;
        $total_test_five_num = 0;
        $total_regular_first_per = 0;
        $total_regular_first_num = 0;
        $total_regular_five_per = 0;
        $total_regular_five_num = 0;
        $real_num = $suc_count = $train_first_all= $train_first_pass = $train_second_all=$train_second_pass = $test_first_all = $regular_first_all=$test_five_all=$regular_five_all=$lecture_inter_num=$one_inter_num=$lecture_succ=$one_succ=0;
        foreach($teacher_info as &$item){
            $item["real_num"] = isset($real_info["list"][$item["account"]])?$real_info["list"][$item["account"]]["all_count"]:0;
            $account = $item["account"];
            $item["lecture_inter_num"] = isset($lecture_list_info["list"][$account])?$lecture_list_info["list"][$account]["all_count"]:0;
            $item["one_inter_num"] = isset($one_list_info["list"][$account])?$one_list_info["list"][$account]["all_count"]:0;
            $teacher_list = $this->t_teacher_lecture_info->get_teacher_list_passed($account,$start_time,$end_time,$subject,-1,-1,-1,$tea_subject);
            $item["lecture_succ"] = count($teacher_list);
            $teacher_arr = $this->t_teacher_record_list->get_teacher_train_passed($account,$start_time,$end_time,$subject,-1,-1,-1,$tea_subject);
            $item["one_succ"] = count($teacher_arr);
            foreach($teacher_arr as $k=>$val){
                if(!isset($teacher_list[$k])){
                    $teacher_list[$k]=$k;
                }
            }

            $item["suc_count"] = count($teacher_list);
            $item["train_first_all"] = isset($train_first[$account])?$train_first[$account]["all_num"]:0;
            $item["train_first_pass"] = isset($train_first[$account])?$train_first[$account]["pass_num"]:0;
            $item["train_second_all"] = isset($train_second[$account])?$train_second[$account]["all_num"]:0;
            $item["train_second_pass"] = isset($train_second[$account])?$train_second[$account]["pass_num"]:0;

            $item["test_first"] = isset($test_first[$account])?$test_first[$account]["all_num"]:0;
            $item["test_first_per"] = isset($test_first_per[$account])?round($test_first_per[$account]["all_time"]/$test_first_per[$account]["all_num"]):0;
            if($item["test_first_per"] > 0){
                $total_test_first_per += $test_first_per[$account]["all_time"];
                $total_test_first_num += $test_first_per[$account]["all_num"];
            }

            $item["test_first_per_str"] = "";
            if($item["test_first_per"]){
                if($item["test_first_per"]/60>0){
                    $item["test_first_per_str"] = round($item["test_first_per"]/60)."分".($item["test_first_per"]%60)."秒";
                }else{
                    $item["test_first_per_str"] .= "秒";
                }
            }


            $item["test_five"] = isset($test_five[$account])?$test_five[$account]["all_num"]:0;
            $item["test_five_per"] = isset($test_five_per[$account])?round($test_five_per[$account]["all_time"]/$test_five_per[$account]["all_num"]):0;
            if($item["test_five_per"] > 0){
                $total_test_five_per += $test_five_per[$account]["all_time"];
                $total_test_five_num += $test_five_per[$account]["all_num"];
            }

            $item["test_five_per_str"] = "";
            if($item["test_five_per"]){
                if($item["test_five_per"]/60>0){
                    $item["test_five_per_str"] = round($item["test_five_per"]/60)."分".($item["test_five_per"]%60)."秒";
                }else{
                    $item["test_five_per_str"] .= "秒";
                }
            }


            $item["regular_first"] = isset($regular_first[$account])?$regular_first[$account]["all_num"]:0;
            $item["regular_first_per"] = isset($regular_first_per[$account])?round($regular_first_per[$account]["all_time"]/$regular_first_per[$account]["all_num"]):0;
            if($item["regular_first_per"]){
                $total_regular_first_per += $regular_first_per[$account]["all_time"];
                $total_regular_first_num += $regular_first_per[$account]["all_num"];
            }
            $item["regular_first_per_str"] = "";
            if($item["regular_first_per"]){
                if($item["regular_first_per"]/60>0){
                    $item["regular_first_per_str"] = round($item["regular_first_per"]/60)."分".($item["regular_first_per"]%60)."秒";
                }else{
                    $item["regular_first_per_str"] .= "秒";
                }
            }

            $item["regular_five"] = isset($regular_five[$account])?$regular_five[$account]["all_num"]:0;
            $item["regular_five_per"] = isset($regular_five_per[$account])?round($regular_five_per[$account]["all_time"]/$regular_five_per[$account]["all_num"]):0;
            if($item["regular_five_per"]){
                $total_regular_five_per += $regular_five_per[$account]["all_time"];
                $total_regular_five_num += $regular_five_per[$account]["all_num"];
            }
            $item["regular_five_per_str"] = "";
            if($item["regular_five_per"]){
                if($item["regular_five_per"]/60>0){
                    $item["regular_five_per_str"] = round($item["regular_five_per"]/60)."分".($item["regular_five_per"]%60)."秒";
                }else{
                    $item["regular_five_per_str"] .= "秒";
                }
            }



            $item["all_num"] = $item["real_num"]+ $item["train_first_all"]+$item["train_second_all"]+ $item["test_first"]+ $item["regular_first"]+$item["test_five"]+$item["regular_five"];
            if($item["uid"]==481){
                $item["all_num"] +=7;
            }
            $item["all_target_num"] = 250;
            if(in_array($item["uid"],[486,754,1011,329])){
                $item["all_target_num"]=150;
            }elseif(in_array($item["uid"],[913,923,892])){
                $item["all_target_num"]=400;
            }elseif(in_array($item["uid"],[478])){
                 $item["all_target_num"]=50;
            }elseif(in_array($item["uid"],[895,683])){
                 $item["all_target_num"]=100;
            }

            $all_count +=$item["all_target_num"];
            $item["per"] = round($item["all_num"]/$item["all_target_num"]*100,2);
            if($kpi_flag==1){
                $real_num += $item["real_num"];
                $suc_count += $item["suc_count"];
                $lecture_inter_num += $item["lecture_inter_num"];
                $one_inter_num += $item["one_inter_num"];
                $lecture_succ += $item["lecture_succ"];
                $one_succ += $item["one_succ"];
                $train_first_all += $item["train_first_all"];
                $train_first_pass += $item["train_first_pass"];
                $train_second_all += $item["train_second_all"];
                $train_second_pass += $item["train_second_pass"];
                $test_first_all += $item["test_first"];
                $test_five_all += $item["test_five"];
                $regular_first_all += $item["regular_first"];
                $regular_five_all += $item["regular_five"];
            }
        }

        $total_test_first_per_str ="";
        if($total_test_first_num>0){
            $total_test_first_per = isset($total_test_first_num)?round($total_test_first_per/$total_test_first_num):0;
        }else{
            $total_test_first_per = 0;
        }
        if($total_test_first_per){
            if($total_test_first_per/60>0){
                $total_test_first_per_str = round($total_test_first_per/60)."分".($total_test_first_per%60)."秒";
            }else{
                $total_test_first_per_str .= "秒";
            }
        }

        $total_test_five_per_str ="";
        if($total_test_five_num>0){
            $total_test_five_per = isset($total_test_five_num)?round($total_test_five_per/$total_test_five_num):0;
        }else{
            $total_test_five_per = 0;
        }
        if($total_test_five_per){
            if($total_test_five_per/60>0){
                $total_test_five_per_str = round($total_test_five_per/60)."分".($total_test_five_per%60)."秒";
            }else{
                $total_test_five_per_str .= "秒";
            }
        }


        $total_regular_first_per_str = "";
        if($total_regular_first_num>0){
            $total_regular_first_per = isset($total_regular_first_num)?round($total_regular_first_per/$total_regular_first_num):0;
        }else{
            $total_regular_first_per = 0;
        }
        if($total_regular_first_per){
            if($total_regular_first_per/60>0){
                $total_regular_first_per_str = round($total_regular_first_per/60)."分".($total_regular_first_per%60)."秒";
            }else{
                $total_regular_first_per_str .= "秒";
            }
        }

        $total_regular_five_per_str = "";
        if($total_regular_five_num>0){
            $total_regular_five_per = isset($total_regular_five_num)?round($total_regular_five_per/$total_regular_five_num):0;
        }else{
            $total_regular_five_per = 0;
        }
        if($total_regular_five_per){
            if($total_regular_five_per/60>0){
                $total_regular_five_per_str = round($total_regular_five_per/60)."分".($total_regular_five_per%60)."秒";
            }else{
                $total_regular_five_per_str .= "秒";
            }
        }



        \App\Helper\Utils::order_list( $teacher_info,"per", 0 );
        if($kpi_flag==0){

            //面试总计
            $teacher_list_ex = $this->t_teacher_lecture_info->get_teacher_list_passed("",$start_time,$end_time,$subject,-1,-1,-1,$tea_subject);
            $lecture_succ = count($teacher_list_ex);
            $teacher_arr_ex = $this->t_teacher_record_list->get_teacher_train_passed("",$start_time,$end_time,$subject,-1,-1,-1,$tea_subject);
            $one_succ = count($teacher_arr_ex);
            foreach($teacher_arr_ex as $k=>$val){
                if(!isset($teacher_list_ex[$k])){
                    $teacher_list_ex[$k]=$k;
                }
            }
            $video_real =  $this->t_teacher_lecture_info->get_lecture_info_by_all(
                $subject,$start_time,$end_time,-1,-1,-1,$tea_subject,-2);
            $lecture_inter_num = @$video_real["all_count"];

            $one_real = $this->t_teacher_record_list->get_train_teacher_interview_info_all(
                $subject,$start_time,$end_time,-1,-1,-1,$tea_subject,-2);
            $one_inter_num =  @$one_real["all_count"];
            @$video_real["all_count"] += $one_real["all_count"];

            $all_tea_ex = count($teacher_list_ex);

            //模拟试听总计
            $train_first_all = $this->t_teacher_record_list->get_trial_train_lesson_all($start_time,$end_time,1,$subject);
            $train_second_all = $this->t_teacher_record_list->get_trial_train_lesson_all($start_time,$end_time,2,$subject);

            //第一次试听/第一次常规总计/第五次试听/第五次常规总计
            $test_first_all = $this->t_teacher_record_list->get_test_regular_lesson_all($start_time,$end_time,1,$subject);

            $test_five_all = $this->t_teacher_record_list->get_test_regular_lesson_all($start_time,$end_time,2,$subject);

            $regular_first_all = $this->t_teacher_record_list->get_test_regular_lesson_all($start_time,$end_time,3,$subject);
            $regular_five_all = $this->t_teacher_record_list->get_test_regular_lesson_all($start_time,$end_time,4,$subject);

            $all_num = $video_real["all_count"]+$train_first_all["all_num"]+$train_second_all["all_num"]+$test_first_all+$regular_first_all+$test_five_all+$regular_five_all;
            $arr=["name"=>"总计","real_num"=>$video_real["all_count"],"suc_count"=>$all_tea_ex,"train_first_all"=>$train_first_all["all_num"],"train_first_pass"=>$train_first_all["pass_num"],"train_second_all"=>$train_second_all["all_num"],
                    "test_first"=>$test_first_all,
                    "test_five" =>$test_five_all,
                    "regular_first"=>$regular_first_all,
                    "regular_five"=>$regular_five_all,
                    "all_num"=>$all_num,
                    "test_first_per_str" => $total_test_first_per_str,
                    "test_five_per_str" => $total_test_five_per_str,
                  "regular_first_per_str" => $total_regular_first_per_str,
                  "regular_five_per_str" => $total_regular_five_per_str,
                  "lecture_inter_num"   =>$lecture_inter_num,
                  "one_inter_num"   =>$one_inter_num,
                  "lecture_succ"    =>$lecture_succ,
                  "one_succ"        =>$one_succ

            ];
        }elseif($kpi_flag==1){
            $arr=[];
            $arr=["name"=>"总计",
                 "test_first_per_str" => $total_test_first_per_str,
                 "test_five_per_str" => $total_test_five_per_str,
                  "regular_first_per_str" => $total_regular_first_per_str,
                  "regular_five_per_str" => $total_regular_five_per_str,
            ];

            $arr["real_num"] = $real_num;
            $arr["suc_count"] = $suc_count;
            $arr["lecture_succ"] = $lecture_succ;
            $arr["one_succ"] = $one_succ;
            $arr["lecture_inter_num"] = $lecture_inter_num;
            $arr["one_inter_num"] = $one_inter_num;
            $arr["train_first_all"] = $train_first_all;
            $arr["train_first_pass"] = $train_first_pass;
            $arr["train_second_all"] = $train_second_all;
            $arr["train_second_pass"] = $train_second_pass;
            $arr["test_first"] = $test_first_all;
            $arr["test_five"] = $test_five_all;
            $arr["regular_first"] = $regular_first_all;
            $arr["regular_five"] = $regular_five_all;
            $arr["all_num"] = $real_num+$train_first_all+$test_first_all+$regular_first_all+$train_second_all+$test_five_all+$regular_five_all;
        }

        $num = count($teacher_info);
        // $all_count = ($num-2)*250+300;
        if($all_count){
            $arr["per"] = round($arr["all_num"]/$all_count*100,2);
        }else{
            $arr["per"] = 0;
        }

        $arr["all_target_num"] = $all_count;



        array_unshift($teacher_info,$arr);
        $ret_info = \App\Helper\Utils::list_to_page_info($teacher_info);

        return $this->pageView(__METHOD__,$ret_info);
    }

    public function zs_teacher(){
        list($start_time,$end_time) = $this->get_in_date_range( date("Y-m-01",time(NULL)) ,0 );

        //$this->switch_tongji_database();
        $this->check_and_switch_tongji_domain();
        $res_subject = $this->t_teacher_lecture_info->get_lecture_info_by_subject_new($start_time,$end_time);
        $video_succ_subject = $this->t_teacher_lecture_info->get_lecture_info_by_subject_new($start_time,$end_time,1);
        $one_subject = $this->t_teacher_record_list->get_all_interview_count_by_subject($start_time,$end_time,-1);
        $one_succ_subject = $this->t_teacher_record_list->get_all_interview_count_by_subject($start_time,$end_time,1);
        $all_subject=["subject_str"=>"总计"];$all_grade=["grade_ex_str"=>"总计"];
        foreach($one_subject as $k=>$val){
            if(isset($res_subject[$k])){
                $res_subject[$k]["all_count"] +=$val["all_count"];
                $res_subject[$k]["all_num"] +=$val["all_num"];
            }else{
                $res_subject[$k]=$val;
            }
        }
        foreach($res_subject as $key=>&$t){
            @$t["succ"] +=$video_succ_subject[$key]["all_count"];
            @$t["succ"] +=$one_succ_subject[$key]["all_count"];
            @$t["succ_num"] +=$video_succ_subject[$key]["all_num"];
            @$t["succ_num"] +=$one_succ_subject[$key]["all_num"];

            E\Esubject::set_item_value_str($t,"subject");
            $teacher_list = $this->t_teacher_lecture_info->get_teacher_list_passed("",$start_time,$end_time,$t["subject"]);
            $teacher_arr = $this->t_teacher_record_list->get_teacher_train_passed("",$start_time,$end_time,$t["subject"]);
            foreach($teacher_arr as $k=>$l){
                if(!isset($teacher_list[$k])){
                    $teacher_list[$k]=$k;
                }
            }
            $t["train_num"] = $this->t_lesson_info_b2->get_all_train_num_new($start_time,$end_time,$teacher_list,-1,false);
            $t["train_succ"] = $this->t_lesson_info_b2->get_all_train_num_new($start_time,$end_time,$teacher_list,1,false);
            $t["trial_train_num"] = $this->t_lesson_info_b2->get_all_trial_train_num($start_time,$end_time,$teacher_list,-1,false);
            $t["trial_train_succ"] = $this->t_lesson_info_b2->get_all_trial_train_num($start_time,$end_time,$teacher_list,1,false);
            $t["succ_per"] = !empty($t["all_count"])?round($t["succ"]/$t["all_count"]*100,2):0;
            $t["succ_num_per"] = !empty($t["all_num"])?round($t["succ_num"]/$t["all_num"]*100,2):0;
            $t["train_per"] = !empty($t["train_num"])?round($t["train_succ"]/$t["train_num"]*100,2):0;
            $t["trial_train_per"] = !empty($t["trial_train_num"])?round($t["trial_train_succ"]/$t["trial_train_num"]*100,2):0;
            @$all_subject["succ"] +=$t["succ"];
            @$all_subject["succ_num"] +=$t["succ_num"];
            @$all_subject["all_count"] +=$t["all_count"];
            @$all_subject["all_num"] +=$t["all_num"];
            @$all_subject["train_num"] +=$t["train_num"];
            @$all_subject["train_succ"] +=$t["train_succ"];

        }
        $all_subject["succ_per"] = !empty($all_subject["all_count"])?round($all_subject["succ"]/$all_subject["all_count"]*100,2):0;
        $all_subject["succ_num_per"] = !empty($all_subject["all_num"])?round($all_subject["succ_num"]/$all_subject["all_num"]*100,2):0;
        $all_subject["train_per"] = !empty($all_subject["train_num"])?round($all_subject["train_succ"]/$all_subject["train_num"]*100,2):0;



        $res_grade = $this->t_teacher_lecture_info->get_lecture_info_by_grade($start_time,$end_time);
        $video_succ_grade = $this->t_teacher_lecture_info->get_lecture_info_by_grade($start_time,$end_time,1);
        $one_grade = $this->t_teacher_record_list->get_all_interview_count_by_grade($start_time,$end_time,-1);
        $one_succ_grade = $this->t_teacher_record_list->get_all_interview_count_by_grade($start_time,$end_time,1);
        foreach($one_grade as $k=>$val){
            if(isset($res_grade[$k])){
                $res_grade[$k]["all_count"] +=$val["all_count"];
                $res_grade[$k]["all_num"] +=$val["all_num"];
            }else{
                $res_grade[$k]=$val;
            }
        }
        foreach($res_grade as $key=>&$i){
            @$i["succ"] +=$video_succ_grade[$key]["all_count"];
            @$i["succ"] +=$one_succ_grade[$key]["all_count"];
            @$i["succ_num"] +=$video_succ_grade[$key]["all_num"];
            @$i["succ_num"] +=$one_succ_grade[$key]["all_num"];
            $teacher_list = $this->t_teacher_lecture_info->get_teacher_list_passed("",$start_time,$end_time,-1,-1,-1,-1,"",$i["grade_ex"]);
            $teacher_arr = $this->t_teacher_record_list->get_teacher_train_passed("",$start_time,$end_time,-1,-1,-1,-1,"",$i["grade_ex"]);
            foreach($teacher_arr as $k=>$l){
                if(!isset($teacher_list[$k])){
                    $teacher_list[$k]=$k;
                }
            }
            $i["train_num"] = $this->t_lesson_info_b2->get_all_train_num_new($start_time,$end_time,$teacher_list,-1);
            $i["train_succ"] = $this->t_lesson_info_b2->get_all_train_num_new($start_time,$end_time,$teacher_list,1);
            $i["trial_train_num"] = $this->t_lesson_info_b2->get_all_trial_train_num($start_time,$end_time,$teacher_list,-1,false);
            $i["trial_train_succ"] = $this->t_lesson_info_b2->get_all_trial_train_num($start_time,$end_time,$teacher_list,1,false);
            $i["succ_per"] = !empty($i["all_count"])?round($i["succ"]/$i["all_count"]*100,2):0;
            $i["succ_num_per"] = !empty($i["all_num"])?round($i["succ_num"]/$i["all_num"]*100,2):0;
            $i["train_per"] = !empty($i["train_num"])?round($i["train_succ"]/$i["train_num"]*100,2):0;
            $i["trial_train_per"] = !empty($i["trial_train_num"])?round($i["trial_train_succ"]/$i["trial_train_num"]*100,2):0;
            @$all_grade["succ"] +=$i["succ"];
            @$all_grade["succ_num"] +=$i["succ_num"];
            @$all_grade["all_count"] +=$i["all_count"];
            @$all_grade["all_num"] +=$i["all_num"];
            @$all_grade["train_num"] +=$i["train_num"];
            @$all_grade["train_succ"] +=$i["train_succ"];



            E\Egrade::set_item_value_str($i,"grade_ex");
        }
        $all_grade["succ_per"] = !empty($all_grade["all_count"])?round($all_grade["succ"]/$all_grade["all_count"]*100,2):0;
        $all_grade["succ_num_per"] = !empty($all_grade["all_num"])?round($all_grade["succ_num"]/$all_grade["all_num"]*100,2):0;
        $all_grade["train_per"] = !empty($all_grade["train_num"])?round($all_grade["train_succ"]/$all_grade["train_num"]*100,2):0;

        // array_push($res_grade,$all_grade);
        // array_push($res_subject,$all_subject);


        $list = $this->t_teacher_lecture_info->get_lecture_info_by_time_new(
            -1,$start_time,$end_time,-1,-1,-1,"");
        $arr = $this->t_teacher_record_list->get_train_teacher_interview_info(
            -1,$start_time,$end_time,-1,-1,-1,"");

        foreach($arr["list"] as $k=>$val){
            if(isset($list["list"][$k])){
                $list["list"][$k]["all_count"] += $val["all_count"];
                $list["list"][$k]["all_num"] += $val["all_num"];
            }else{
                $list["list"][$k]= $val;
            }
        }

        foreach($list["list"] as &$item2){
            $account = $item2["account"];
            $teacher_list = $this->t_teacher_lecture_info->get_teacher_list_passed($account,$start_time,$end_time);
            $teacher_arr = $this->t_teacher_record_list->get_teacher_train_passed($account,$start_time,$end_time);
            foreach($teacher_arr as $k=>$val){
                if(!isset($teacher_list[$k])){
                    $teacher_list[$k]=$k;
                }
            }

            $item2["suc_count"] = count($teacher_list);
            $item2["pass_per"] = (round($item2["suc_count"]/$item2["all_count"],2))*100;
            $item2["all_pass_per"] = (round($item2["suc_count"]/$item2["all_num"],2))*100;
            $res = $this->t_lesson_info->get_test_leson_info_by_teacher_list($teacher_list);
            $item2["all_lesson"] = $res["all_lesson"];
            $item2["have_order"] = $res["have_order"];
            $item2["order_per"] =  $item2["all_lesson"]==0?0:((round($item2["have_order"]/$item2["all_lesson"],2))*100);
        }

        $all_item=["account" => "全部"];
        $sum_field_list = [
            "all_num",
            "all_count",
            "suc_count",
            "pass_per",
            "all_pass_per",
            "ave_time",
            "all_lesson",
            "have_order",
            "order_per"
        ];

        \App\Helper\Utils::list_add_sum_item($list["list"], $all_item,$sum_field_list );

        $teacher_list_ex = $this->t_teacher_lecture_info->get_teacher_list_passed("",$start_time,$end_time);
        $teacher_arr_ex = $this->t_teacher_record_list->get_teacher_train_passed("",$start_time,$end_time);
        foreach($teacher_arr_ex as $k=>$val){
            if(!isset($teacher_list_ex[$k])){
                $teacher_list_ex[$k]=$k;
            }
        }

        $all_tea_ex = count($teacher_list_ex);
        $train_all = $this->t_lesson_info_b2->get_all_train_num_new($start_time,$end_time,$teacher_list_ex,-1);
        $train_succ = $this->t_lesson_info_b2->get_all_train_num_new($start_time,$end_time,$teacher_list_ex,1);
        $train_succ_per = !empty($train_all)?round($train_succ/$train_all*100,2)."%":"";
        $trial_train_all = $this->t_lesson_info_b2->get_all_trial_train_num($start_time,$end_time,$teacher_list_ex,-1);
        $trial_train_succ = $this->t_lesson_info_b2->get_all_trial_train_num($start_time,$end_time,$teacher_list_ex,1);
        $trial_train_succ_per = !empty($trial_train_all)?round($trial_train_succ/$trial_train_all*100,2)."%":"";



        foreach($list["list"] as &$item1){
            if($item1["account"]=="全部"){
                $item1["pass_per"] = @$item1["all_count"]==0?0:(round($all_tea_ex/@$item1["all_count"],2))*100;
                $item1["order_per"] =@$item1["all_lesson"]==0?0:(round(@$item1["have_order"]/@$item1["all_lesson"],2))*100;
                $item1["all_pass_per"] = !empty(@$item1["all_num"])?(round( @$item1["suc_count"]/$item1["all_num"],2))*100:0;
                $item1["succ_num"] =  $all_tea_ex;
                $item1["train_all"] = $train_all;
                $item1["train_succ"] = $train_succ;
                $item1["train_per"] =  $train_succ_per;
                $item1["trial_train_all"] = $trial_train_all;
                $item1["trial_train_succ"] = $trial_train_succ;
                $item1["trial_train_per"] =  $trial_train_succ_per;
                $data = $item1;
            }
        }

        $lecture_identity = $this->t_teacher_lecture_info->get_lecture_info_by_identity($start_time,$end_time);
        $lecture_identity_succ = $this->t_teacher_lecture_info->get_lecture_info_by_identity($start_time,$end_time,1);
        $train_identity = $this->t_teacher_record_list->get_all_interview_count_by_identity($start_time,$end_time,-1);
        $train_identity_succ = $this->t_teacher_record_list->get_all_interview_count_by_identity($start_time,$end_time,1);
        foreach($train_identity as $k=>$val){
            if(isset($lecture_identity[$k])){
                $lecture_identity[$k]["all_count"] +=$val["all_count"];
                $lecture_identity[$k]["all_num"] +=$val["all_num"];
            }else{
                $lecture_identity[$k]=$val;
            }
        }
        foreach($lecture_identity as $key=>&$n){
            @$n["succ"] +=$lecture_identity_succ[$key]["all_count"];
            @$n["succ"] +=$train_identity_succ[$key]["all_count"];
            @$n["succ_num"] +=$lecture_identity_succ[$key]["all_num"];
            @$n["succ_num"] +=$train_identity_succ[$key]["all_num"];
            $teacher_list = $this->t_teacher_lecture_info->get_teacher_list_passed("",$start_time,$end_time,-1,-1,-1,$n["identity_ex"]);
            $teacher_arr = $this->t_teacher_record_list->get_teacher_train_passed("",$start_time,$end_time,-1,-1,-1,$n["identity_ex"]);
            foreach($teacher_arr as $k=>$l){
                if(!isset($teacher_list[$k])){
                    $teacher_list[$k]=$k;
                }
            }
            $n["train_num"] = $this->t_lesson_info_b2->get_all_train_num_new($start_time,$end_time,$teacher_list,-1);
            $n["train_succ"] = $this->t_lesson_info_b2->get_all_train_num_new($start_time,$end_time,$teacher_list,1);
            $n["trial_train_num"] = $this->t_lesson_info_b2->get_all_trial_train_num($start_time,$end_time,$teacher_list,-1,false);
            $n["trial_train_succ"] = $this->t_lesson_info_b2->get_all_trial_train_num($start_time,$end_time,$teacher_list,1,false);

            $n["succ_per"] = !empty($n["all_count"])?round($n["succ"]/$n["all_count"]*100,2):0;
            $n["succ_num_per"] = !empty($n["all_num"])?round($n["succ_num"]/$n["all_num"]*100,2):0;
            $n["train_per"] = !empty($n["train_num"])?round($n["train_succ"]/$n["train_num"]*100,2):0;
            $n["trial_train_per"] = !empty($n["trial_train_num"])?round($n["trial_train_succ"]/$n["trial_train_num"]*100,2):0;

            E\Eidentity::set_item_value_str($n,"identity_ex");
            if(in_array($n["identity_ex"],[1,2,127])){
                unset($lecture_identity[$key]);
            }
        }


        return $this->pageView(__METHOD__ ,null, [
            // "ret_info"    => $ret_info,
            // "all_total"   => $all_total,
            //"video_total" => $video_total,
            // "suc_total"   => $suc_total,
            "res_grade"   =>$res_grade,
            "res_subject" =>$res_subject,
            "data"        =>$data,
            // "data1"        =>$data1,
            "res_identity"=>$lecture_identity
        ]);
    }

    public function zs_teacher_old(){
        return $this->zs_teacher();
    }

    public function zs_teacher_new(){
        $this->check_and_switch_tongji_domain();
        // $this->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range( 0 ,0,0,[],1 );

        $all_total = $system_total=$self_total=$no_call_total=0;
        $ret_info = $this->t_manager_info->get_admin_work_status_info(8);

        foreach($ret_info as $i=>$val){ // 傅文莉要求在首页不显示ta
            if($val['uid'] == 967){
                unset($ret_info[$i]);
            }
        }
        if($end_time >= strtotime("2017-11-09")){
            $ret_info[] =["uid"=>1250,"account"=>"招师其他","name"=>"招师其他","admin_work_status"=>0];
        }
        if($end_time <= strtotime("2018-01-01")){
            $ret_info[] =["uid"=>513,"account"=>"徐月","name"=>"徐月","admin_work_status"=>0];
        }

        $zs_entry_list=$zs_video_list = $zs_one_list= $ret_info;

        $list  = $this->t_teacher_lecture_appointment_info->tongji_teacher_lecture_appoiment_info_by_accept_adminid($start_time,$end_time);
        $list1  = $this->t_teacher_lecture_appointment_info->tongji_no_call_count_by_accept_adminid();


        $video_account = $this->t_teacher_lecture_info->get_lecture_info_by_zs_new($start_time,$end_time);
        $video_account_real = $this->t_teacher_lecture_info->get_lecture_info_by_zs($start_time,$end_time,-2);
        $video_account_pass = $this->t_teacher_lecture_info->get_lecture_info_by_zs($start_time,$end_time,1);
        $one_account = $this->t_teacher_record_list->get_all_interview_count_by_zs($start_time,$end_time,-1);
        $one_account_real = $this->t_teacher_record_list->get_all_interview_count_by_zs($start_time,$end_time,-2);
        $one_account_pass = $this->t_teacher_record_list->get_all_interview_count_by_zs($start_time,$end_time,1);
        foreach($ret_info as $k=>&$item){
            $accept_adminid       = $item["uid"];
            $item["all_count"] = @$list[$accept_adminid]["all_count"];
            $item["no_call_count"] = @$list1[$accept_adminid]["no_call_count"];
            $reference = $this->get_zs_reference($accept_adminid);
            $item["self_count"] = $this->t_teacher_lecture_appointment_info->get_self_count($reference,$start_time,$end_time);
            $item["system_count"] = $item["all_count"]-$item["self_count"];
            $all_total   += $item["all_count"];
            $no_call_total   += $item["no_call_count"];
            $system_total   += $item["system_count"];
            $self_total   += $item["self_count"];
            $item["video_account"] = @$video_account[$accept_adminid]["all_count"];
            $item["video_account_real"] = @$video_account_real[$accept_adminid]["all_count"];
            $item["video_account_pass"] = @$video_account_pass[$accept_adminid]["all_count"];
            $item["one_account"] = @$one_account[$accept_adminid]["all_count"];
            $item["one_account_real"] = @$one_account_real[$accept_adminid]["all_count"];
            $item["one_account_pass"] = @$one_account_pass[$accept_adminid]["all_count"];
            $item["video_per"] = !empty( $item["video_account_real"] )?round( $item["video_account_pass"]/$item["video_account_real"]*100,2):0;
            $item["one_per"] = !empty( $item["one_account_real"] )?round( $item["one_account_pass"]/$item["one_account_real"]*100,2):0;
            $item["all_per"] = !empty( $item["one_account_real"]+$item["video_account_real"] )?round( ($item["one_account_pass"]+$item["video_account_pass"])/($item["one_account_real"]+$item["video_account_real"])*100,2):0;
        }

        \App\Helper\Utils::order_list( $ret_info,"all_per", 0 );
        $data =[];

        $video_all =  $this->t_teacher_lecture_info->get_lecture_info_by_all_new(
            -1,$start_time,$end_time,-1,-1,-1,"");
        $video_real =  $this->t_teacher_lecture_info->get_lecture_info_by_all(
            -1,$start_time,$end_time,-1,-1,-1,"",-2);

        $one_all = $this->t_teacher_record_list->get_train_teacher_interview_info_all(
            -1,$start_time,$end_time,-1,-1,-1,"");
        $one_real = $this->t_teacher_record_list->get_train_teacher_interview_info_all(
            -1,$start_time,$end_time,-1,-1,-1,"",-2);
        @$data["video_count"] =  $video_all["all_count"];
        @$data["video_real"] =  $video_real["all_count"];
        @$data["one_count"] = $one_all["all_count"];
        @$data["one_real"] = $one_real["all_count"];



        $teacher_list_ex = $this->t_teacher_lecture_info->get_teacher_list_passed("",$start_time,$end_time);
        @$data["video_succ"] = count($teacher_list_ex);
        $teacher_arr_ex = $this->t_teacher_record_list->get_teacher_train_passed("",$start_time,$end_time);
        @$data["one_succ"] = count($teacher_arr_ex);
        foreach($teacher_arr_ex as $k=>$val){
            if(!isset($teacher_list_ex[$k])){
                $teacher_list_ex[$k]=$k;
            }
        }

        $data["all_succ"] = count($teacher_list_ex);

        \App\Helper\Utils::order_list( $ret_info,"all_per", 0 );
        $data["video_per"] = !empty($data["video_real"])?round($data["video_succ"]/$data["video_real"]*100,2):0;
        $data["one_per"] = !empty($data["one_real"])?round($data["one_succ"]/$data["one_real"]*100,2):0;


        $video_pass = $one_pass=[];
        for($i=1;$i<=10;$i++){
            $video_pass[$i] = $this->t_teacher_lecture_info->get_teacher_passed_num_by_subject_grade($start_time,$end_time,$i);
            $one_pass[$i] = $this->t_teacher_record_list->get_teacher_passes_num_by_subject_grade($start_time,$end_time,$i);
            // 入职人数
            $entry_pass[$i] = $this->t_teacher_info->get_teacher_passes_num_by_subject_grade($start_time,$end_time,$i);
        }
        foreach($zs_video_list as &$ui){
            $uid      = $ui["uid"];
            $ui["xxyw"] = @$video_pass[1][$uid]["primary_num"];
            $ui["czyw"] = @$video_pass[1][$uid]["middle_num"];
            $ui["gzyw"] = @$video_pass[1][$uid]["senior_num"];
            $ui["xxsx"] = @$video_pass[2][$uid]["primary_num"];
            $ui["czsx"] = @$video_pass[2][$uid]["middle_num"];
            $ui["gzsx"] = @$video_pass[2][$uid]["senior_num"];
            $ui["xxyy"] = @$video_pass[3][$uid]["primary_num"];
            $ui["czyy"] = @$video_pass[3][$uid]["middle_num"];
            $ui["gzyy"] = @$video_pass[3][$uid]["senior_num"];
            $ui["czhx"] = @$video_pass[4][$uid]["middle_num"];
            $ui["gzhx"] = @$video_pass[4][$uid]["senior_num"];
            $ui["czwl"] = @$video_pass[5][$uid]["middle_num"];
            $ui["gzwl"] = @$video_pass[5][$uid]["senior_num"];
            $ui["czsw"] = @$video_pass[6][$uid]["middle_num"];
            $ui["gzsw"] = @$video_pass[6][$uid]["senior_num"];
            $ui["kx"] = @$video_pass[10][$uid]["primary_num"]+@$video_pass[10][$uid]["middle_num"]+@$video_pass[10][$uid]["senior_num"];
            $ui["other"] = @$video_pass[7][$uid]["primary_num"]+@$video_pass[7][$uid]["middle_num"]+@$video_pass[7][$uid]["senior_num"]+@$video_pass[8][$uid]["primary_num"]+@$video_pass[8][$uid]["middle_num"]+@$video_pass[8][$uid]["senior_num"]+@$video_pass[9][$uid]["primary_num"]+@$video_pass[9][$uid]["middle_num"]+@$video_pass[9][$uid]["senior_num"];

        }

        foreach($zs_one_list as &$uy){
            $uid      = $uy["uid"];
            $uy["xxyw"] = @$one_pass[1][$uid]["primary_num"];
            $uy["czyw"] = @$one_pass[1][$uid]["middle_num"];
            $uy["gzyw"] = @$one_pass[1][$uid]["senior_num"];
            $uy["xxsx"] = @$one_pass[2][$uid]["primary_num"];
            $uy["czsx"] = @$one_pass[2][$uid]["middle_num"];
            $uy["gzsx"] = @$one_pass[2][$uid]["senior_num"];
            $uy["xxyy"] = @$one_pass[3][$uid]["primary_num"];
            $uy["czyy"] = @$one_pass[3][$uid]["middle_num"];
            $uy["gzyy"] = @$one_pass[3][$uid]["senior_num"];
            $uy["czhx"] = @$one_pass[4][$uid]["middle_num"];
            $uy["gzhx"] = @$one_pass[4][$uid]["senior_num"];
            $uy["czwl"] = @$one_pass[5][$uid]["middle_num"];
            $uy["gzwl"] = @$one_pass[5][$uid]["senior_num"];
            $uy["czsw"] = @$one_pass[6][$uid]["middle_num"];
            $uy["gzsw"] = @$one_pass[6][$uid]["senior_num"];
            $uy["kx"] = @$one_pass[10][$uid]["primary_num"]+@$one_pass[10][$uid]["middle_num"]+@$one_pass[10][$uid]["senior_num"];
            $uy["other"] = @$one_pass[7][$uid]["primary_num"]+@$one_pass[7][$uid]["middle_num"]+@$one_pass[7][$uid]["senior_num"]+@$one_pass[8][$uid]["primary_num"]+@$one_pass[8][$uid]["middle_num"]+@$one_pass[8][$uid]["senior_num"]+@$one_pass[9][$uid]["primary_num"]+@$one_pass[9][$uid]["middle_num"]+@$one_pass[9][$uid]["senior_num"];

        }

        $entry_total = 0;
        foreach($zs_entry_list as &$uy){
            $uid      = $uy["uid"];
            $uy["xxyw"] = @$entry_pass[1][$uid]["primary_num"];
            $uy["czyw"] = @$entry_pass[1][$uid]["middle_num"];
            $uy["gzyw"] = @$entry_pass[1][$uid]["senior_num"];
            $uy["xxsx"] = @$entry_pass[2][$uid]["primary_num"];
            $uy["czsx"] = @$entry_pass[2][$uid]["middle_num"];
            $uy["gzsx"] = @$entry_pass[2][$uid]["senior_num"];
            $uy["xxyy"] = @$entry_pass[3][$uid]["primary_num"];
            $uy["czyy"] = @$entry_pass[3][$uid]["middle_num"];
            $uy["gzyy"] = @$entry_pass[3][$uid]["senior_num"];
            $uy["czhx"] = @$entry_pass[4][$uid]["middle_num"];
            $uy["gzhx"] = @$entry_pass[4][$uid]["senior_num"];
            $uy["czwl"] = @$entry_pass[5][$uid]["middle_num"];
            $uy["gzwl"] = @$entry_pass[5][$uid]["senior_num"];
            $uy["czsw"] = @$entry_pass[6][$uid]["middle_num"];
            $uy["gzsw"] = @$entry_pass[6][$uid]["senior_num"];
            $uy["kx"] = @$entry_pass[10][$uid]["primary_num"]+@$entry_pass[10][$uid]["middle_num"]+@$entry_pass[10][$uid]["senior_num"];
            $uy["other"] = @$entry_pass[7][$uid]["primary_num"]+@$entry_pass[7][$uid]["middle_num"]+@$entry_pass[7][$uid]["senior_num"]+@$entry_pass[8][$uid]["primary_num"]+@$entry_pass[8][$uid]["middle_num"]+@$entry_pass[8][$uid]["senior_num"]+@$entry_pass[9][$uid]["primary_num"]+@$entry_pass[9][$uid]["middle_num"]+@$entry_pass[9][$uid]["senior_num"];
            $entry_total += $uy['xxyw'] + $uy['czyw'] + $uy['gzyw'] + $uy['xxsx'] + $uy['czsx'] + $uy['gzsx'] + $uy['xxyy'] + $uy['czyy'] + $uy['gzyy'] + $uy['czhx'] + $uy['gzhx'] + $uy['czwl'] + $uy['gzwl'] + $uy['czsw'] + $uy['gzsw'] + $uy['kx'] + $uy['other'];
        }

        // print_r($zs_video_list);
        //print_r($zs_one_list);
        // dd($rrrr);



        $this->set_filed_for_js("acc_name",$this->get_account());

        return $this->pageView(__METHOD__ ,null, [
            "ret_info"    => $ret_info,
            "all_total"   => $all_total,
            "no_call_total"   => $no_call_total,
            "system_total"   => $system_total,
            "self_total"   => $self_total,
            "data"        =>$data,
            "zs_one_list" =>@$zs_one_list,
            "zs_video_list"=>@$zs_video_list,
            "zs_entry_list" => @$zs_entry_list,
            "entry_total" => @$entry_total,
        ]);
    }

    public function assistant_new() {
        $this->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);
        $opt_date_type = $this->get_in_int_val("opt_date_type",3);
        //dd($opt_date_type);

        if($opt_date_type==1){
            $cur_start = strtotime(date('Y-m-01',$start_time));
            $mid_time = strtotime(date('Y-m-15',$start_time));
            $cur_end = strtotime(date('Y-m-01',$mid_time+30*86400));
        }else if($opt_date_type==2){
            $cur_start = strtotime(date('Y-m-01',$end_time));
            $mid_time = strtotime(date('Y-m-15',$end_time));
            $cur_end = strtotime(date('Y-m-01',$mid_time+30*86400));
        }else if($opt_date_type==3){
            $cur_start = $start_time;
            $cur_end = $end_time;
        }
        $last_month = strtotime(date('Y-m-01',$cur_start-100));
        $month_start = strtotime(date("Y-m-01",time(NULL)));
        $account_id = $this->get_account_id();
        if($account_id==349){
            $account_id = 297;
        }
        // $account_id = 324;
        //$master_adminid = $this->t_admin_group_user->get_master_adminid_by_adminid($account_id);
        //$up_master_adminid = $this->t_admin_main_group_name->get_up_group_adminid( $master_adminid);
        $ass_last_month = $this->t_month_ass_student_info->get_ass_month_info($last_month);
        $lesson_count_list_old=[];
        $ass_list = $this->t_manager_info->get_adminid_list_by_account_role(1);

        $lesson_target = $this->t_ass_group_target->get_rate_target($cur_start);

        $kk_require= $this->t_test_lesson_subject->get_ass_kk_tongji_all_info($start_time,$end_time);
        // @$kk_suc= $this->t_test_lesson_subject->get_ass_kk_tongji_info($start_time,$end_time);
        $stu_info_all = $this->t_student_info->get_ass_stu_info_new();
        $ass_month= $this->t_month_ass_student_info->get_ass_month_info($cur_start);
        //  $assistant_renew_list = $this->t_manager_info->get_all_assistant_renew_list_new($start_time,$end_time);
        foreach($ass_list as $k=>&$item){
            $item["warning_student"]  = isset($ass_month[$k]["warning_student"])?$ass_month[$k]["warning_student"]:0;
            $item["read_student"]     = isset($ass_month[$k]["read_student"])?$ass_month[$k]["read_student"]:0;
            $item["stop_student"]     = isset($ass_month[$k]["stop_student"])?$ass_month[$k]["stop_student"]:0;
            $item["all_student"]      = isset($ass_month[$k]["all_student"])?$ass_month[$k]["all_student"]:0;
            $item["month_stop_student"]  = isset($ass_month[$k]["month_stop_student"])?$ass_month[$k]["month_stop_student"]:0;
            $item["lesson_ratio"]  = isset($ass_month[$k]["lesson_ratio"])?$ass_month[$k]["lesson_ratio"]:0;
            $item["lesson_total"]  = isset($ass_month[$k]["lesson_total"])?$ass_month[$k]["lesson_total"]/100:0;
            // $item["renw_price"]  = isset($assistant_renew_list[$k]["renw_price"])?$assistant_renew_list[$k]["renw_price"]/100:0;//续费金额
            $item["renw_price"]  = isset($ass_month[$k]["renw_price"])?$ass_month[$k]["renw_price"]/100:0;//续费金额
            //$item["tran_price"]  = isset($assistant_renew_list[$k]["tran_price"])?$assistant_renew_list[$k]["tran_price"]/100:0;//转介绍金额
            $item["tran_price"]  = isset($ass_month[$k]["tran_price"])?$ass_month[$k]["tran_price"]/100:0;//转介绍金额
            //$item["renw_student"]  = isset($assistant_renew_list[$k]["all_student"])?$assistant_renew_list[$k]["all_student"]:0;//续费学生数
            $item["renw_student"]  = isset($ass_month[$k]["renw_student"])?$ass_month[$k]["renw_student"]:0;//续费学生数

            $item["refund_student"]  = isset($ass_month[$k]["refund_student"])?$ass_month[$k]["refund_student"]:0;
            $item["read_student_last"]  = isset($ass_month[$k]["read_student_last"])?@$ass_month[$k]["read_student_last"]:0;
            $item["all_price"]     = $item["renw_price"]+$item["tran_price"];
            $item["lesson_target"]         = $lesson_target;
            $item["lesson_per"]            = !empty($item["lesson_target"])?round($item["lesson_ratio"]/$item["lesson_target"],4)*100:0;
            $item["renw_target"]           = @$ass_last_month[$k]["warning_student"]*0.8*8000;
            $item["renw_per"]              = !empty($item["renw_target"])?round($item["all_price"]/$item["renw_target"],4)*100:0;
            $item["renw_stu_target"]       = ceil(@$ass_last_month[$k]["warning_student"]*0.8);
            $item["renw_stu_per"]          = !empty($item["renw_stu_target"])?round($item["renw_student"]/$item["renw_stu_target"],4)*100:0;
            $item["kk_suc"]                = (isset($ass_month[$k]["kk_num"])?$ass_month[$k]["kk_num"]:0)+(isset($ass_month[$k]["hand_kk_num"])?$ass_month[$k]["hand_kk_num"]:0);
            //$item["kk_suc"]                =@$kk_suc[$k]["lesson_count"];
            $item["kk_require"]            =@$kk_require[$k]["all_count"];
            $item["except_num"]            =@$stu_info_all[$k]["except_num"];
            $item["except_count"]            =@$stu_info_all[$k]["except_count"];
            $item["lesson_total_old"]  = !empty(@$ass_last_month[$k]["lesson_total_old"])?@$ass_last_month[$k]["lesson_total_old"]/100:(round($item["read_student_last"]*$item["lesson_ratio"],1));
            $item["lesson_student"]  = isset($ass_month[$k]["lesson_student"])?$ass_month[$k]["lesson_student"]:0;//在读学生
            $item["hand_tran_num"]  = isset($ass_month[$k]["hand_tran_num"])?$ass_month[$k]["hand_tran_num"]:0;//手动确认转介绍人数
            $item["cc_tran_money"]  = isset($ass_month[$k]["cc_tran_money"])?$ass_month[$k]["cc_tran_money"]/100:0;//CC转介绍金额

        }

        if(in_array(date("d",$start_time),[28,29,30,31])){
            foreach($ass_month as $ks=>$vss){
                $userid_list = json_decode($vss["userid_list_last"],true);
                if(empty($userid_list)){
                    $userid_list=[];
                }

                $lesson_count_list_old[$ks]=$this->t_manager_info->get_assistant_lesson_count_old($start_time,$end_time,$ks,$userid_list);
            }

            foreach($ass_list as $k=>&$dal){
                $dal["lesson_ratio"]  = !empty(@$ass_last_month[$k]["read_student"])?round(@$lesson_count_list_old[$k]/@$ass_last_month[$k]["read_student"]/100,1):0;
                $dal["lesson_total_old"]      = @$lesson_count_list_old[$k]/100;
            }
        }

        $stu_info = @$ass_list[$account_id];

        if($opt_date_type==2){
            $date_week = \App\Helper\Utils::get_week_range($start_time,1);
        }else{
            $date_week = \App\Helper\Utils::get_week_range(time(),1);
        }
        $lstart = $date_week["sdate"];
        $lend = $date_week["edate"];
        $stu_info["week_stu_num"] = $this->t_lesson_info->get_week_stu_num_info($lstart,$lend,$account_id);


        $lesson_list =  $renw_list =  $ass_list;
        \App\Helper\Utils::order_list( $lesson_list,"lesson_ratio", 0 );
        \App\Helper\Utils::order_list( $renw_list,"renw_price", 0 );

        return $this->pageView(__METHOD__ ,null, [
            "stu_info" => $stu_info,
            "lesson_list"  =>$lesson_list,
            "renw_list"   =>$renw_list
        ]);


        // dd($stu_info);
        //dd($up_master_adminid);

    }

    public function assistant_leader_new() {
        $this->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);

        $opt_date_type = $this->get_in_int_val("opt_date_type",3);

        if($opt_date_type==1){
            $cur_start = strtotime(date('Y-m-01',$start_time));
            $mid_time = strtotime(date('Y-m-15',$start_time));
            $cur_end = strtotime(date('Y-m-01',$mid_time+30*86400));
        }else if($opt_date_type==2){
            $cur_start = strtotime(date('Y-m-01',$end_time));
            $mid_time = strtotime(date('Y-m-15',$end_time));
            $cur_end = strtotime(date('Y-m-01',$mid_time+30*86400));
        }else if($opt_date_type==3){
            $cur_start = $start_time;
            $cur_end = $end_time;
        }
        $last_month  = strtotime(date('Y-m-01',$cur_start-100));
        $month_start = strtotime(date("Y-m-01",time(NULL)));
        $account_id  = $this->get_account_id();
        $master_adminid    = $this->t_admin_group_user->get_master_adminid_by_adminid($account_id);
        $up_master_adminid = $this->t_admin_main_group_name->get_up_group_adminid( $master_adminid);
        $ass_last_month    = $this->t_month_ass_student_info->get_ass_month_info($last_month);

        $lesson_count_list_old=[];
        $ass_list = $this->t_manager_info->get_adminid_list_by_account_role(1);

        $lesson_target     = $this->t_ass_group_target->get_rate_target($cur_start);
        $kk_require        = $this->t_test_lesson_subject->get_ass_kk_tongji_all_info($start_time,$end_time);
        // $lesson_money_list = $this->t_manager_info->get_assistant_lesson_money_info($start_time,$end_time);
        $stu_info_all      = $this->t_student_info->get_ass_stu_info_new();
        // $end_stu_info_new = $this->t_student_info->get_end_class_stu_info($start_time,$end_time);
        $ass_month= $this->t_month_ass_student_info->get_ass_month_info($cur_start);
        // $assistant_renew_list = $this->t_manager_info->get_all_assistant_renew_list_new($start_time,$end_time);
        // $new_info = $this->t_student_info->get_new_assign_stu_info($start_time,$end_time);
        foreach($ass_list as $k=>&$item){
            $item["warning_student"]  = isset($ass_month[$k]["warning_student"])?$ass_month[$k]["warning_student"]:0;
            $item["read_student"]     = isset($ass_month[$k]["read_student"])?$ass_month[$k]["read_student"]:0;
            $item["stop_student"]     = isset($ass_month[$k]["stop_student"])?$ass_month[$k]["stop_student"]:0;
            $item["all_student"]      = isset($ass_month[$k]["all_student"])?$ass_month[$k]["all_student"]:0;
            $item["month_stop_student"]  = isset($ass_month[$k]["month_stop_student"])?$ass_month[$k]["month_stop_student"]:0;
            $item["lesson_ratio"]  = isset($ass_month[$k]["lesson_ratio"])?$ass_month[$k]["lesson_ratio"]:0;
            $item["lesson_total"]  = isset($ass_month[$k]["lesson_total"])?$ass_month[$k]["lesson_total"]/100:0;
            // $item["renw_price"]  = isset($assistant_renew_list[$k]["renw_price"])?$assistant_renew_list[$k]["renw_price"]/100:0;//续费金额
            $item["renw_price"]  = isset($ass_month[$k]["renw_price"])?$ass_month[$k]["renw_price"]/100:0;//续费金额
            //$item["tran_price"]  = isset($assistant_renew_list[$k]["tran_price"])?$assistant_renew_list[$k]["tran_price"]/100:0;//转介绍金额
            $item["tran_price"]  = isset($ass_month[$k]["tran_price"])?$ass_month[$k]["tran_price"]/100:0;//转介绍金额
            //$item["renw_student"]  = isset($assistant_renew_list[$k]["all_student"])?$assistant_renew_list[$k]["all_student"]:0;//续费学生数
            $item["renw_student"]  = isset($ass_month[$k]["renw_student"])?$ass_month[$k]["renw_student"]:0;//续费学生数

            $item["read_student_last"]  = isset($ass_month[$k]["read_student_last"])?@$ass_month[$k]["read_student_last"]:0;
            $item["all_price"]     = $item["renw_price"]+$item["tran_price"];
            $item["lesson_target"]         = $lesson_target;
            $item["lesson_per"]            = !empty($item["lesson_target"])?round($item["lesson_ratio"]/$item["lesson_target"],4)*100:0;
            $item["renw_target"]           = @$ass_last_month[$k]["warning_student"]*0.8*8000;
            $item["renw_per"]              = !empty($item["renw_target"])?round($item["all_price"]/$item["renw_target"],4)*100:0;
            $item["renw_stu_target"]       = ceil(@$ass_last_month[$k]["warning_student"]*0.8);
            $item["renw_stu_per"]          = !empty($item["renw_stu_target"])?round($item["renw_student"]/$item["renw_stu_target"],4)*100:0;
            // $item["kk_suc"]                = isset($ass_month[$k]["kk_num"])?$ass_month[$k]["kk_num"]:0;
            $item["kk_suc"]                = (isset($ass_month[$k]["kk_num"])?$ass_month[$k]["kk_num"]:0)+(isset($ass_month[$k]["hand_kk_num"])?$ass_month[$k]["hand_kk_num"]:0);

            //$item["kk_suc"]                =@$kk_suc[$k]["lesson_count"];
            $item["kk_require"]            =@$kk_require[$k]["all_count"];
            $item["except_num"]            =@$stu_info_all[$k]["except_num"];
            $item["except_count"]            =@$stu_info_all[$k]["except_count"];
            // $item["lesson_money"]          = @$lesson_money_list[$k]["lesson_price"]/100;
            $item["lesson_money"]          = isset($ass_month[$k]["lesson_money"])?$ass_month[$k]["lesson_money"]/100:0;//课耗收入
            $item["cc_tran_money"]          = isset($ass_month[$k]["cc_tran_money"])?$ass_month[$k]["cc_tran_money"]/100:0;//CC转介绍


            $item["lesson_total_old"]  = !empty(@$ass_last_month[$k]["lesson_total_old"])?@$ass_last_month[$k]["lesson_total_old"]/100:(round($item["read_student_last"]*$item["lesson_ratio"],1));
            $item["refund_student"]  = isset($ass_month[$k]["refund_student"])?$ass_month[$k]["refund_student"]:0;
            $item["new_refund_money"]  = isset($ass_month[$k]["new_refund_money"])?$ass_month[$k]["new_refund_money"]/100:0;
            $item["renw_refund_money"]  = isset($ass_month[$k]["renw_refund_money"])?$ass_month[$k]["renw_refund_money"]/100:0;
            // $item["new_student"]  = isset($new_info[$k]["num"])?$new_info[$k]["num"]:0;//新签人数
            $item["new_student"]  = isset($ass_month[$k]["new_student"])?$ass_month[$k]["new_student"]:0;//新签人数
            // $item["new_lesson_count"]  = isset($new_info[$k]["lesson_count"])?$new_info[$k]["lesson_count"]/100:0;//购买课时
            $item["new_lesson_count"]  = isset($ass_month[$k]["new_lesson_count"])?$ass_month[$k]["new_lesson_count"]/100:0;//购买课时
            //$item["end_stu_num"]  = isset($end_stu_info_new[$k]["num"])?$end_stu_info_new[$k]["num"]:0;//结课学生
            $item["end_stu_num"]  = isset($ass_month[$k]["end_stu_num"])?$ass_month[$k]["end_stu_num"]:0;//结课学生
            //$item["lesson_student"]  = isset($lesson_info[$k]["user_count"])?$lesson_info[$k]["user_count"]:0;//在读学生
            $item["lesson_student"]  = isset($ass_month[$k]["lesson_student"])?$ass_month[$k]["lesson_student"]:0;//在读学生


        }


        if(in_array(date("d",$start_time),[28,29,30,31])){
            foreach($ass_month as $ks=>$vss){
                $userid_list = json_decode($vss["userid_list_last"],true);
                if(empty($userid_list)){
                    $userid_list=[];
                }

                $lesson_count_list_old[$ks]=$this->t_manager_info->get_assistant_lesson_count_old($start_time,$end_time,$ks,$userid_list);
            }

            foreach($ass_list as $k=>&$dal){
                $dal["lesson_ratio"]  = !empty(@$ass_last_month[$k]["read_student"])?round(@$lesson_count_list_old[$k]/@$ass_last_month[$k]["read_student"]/100,1):0;
                $dal["lesson_total_old"]      = @$lesson_count_list_old[$k]/100;
            }
        }

        // dd($ass_list);
        $ass_list1 = $ass_list2 = $ass_list3 =   $ass_list;
        $ass_leader_arr = $this->t_admin_group_name->get_leader_list(1);
        $ass_group=[];
        foreach($ass_list1 as $key=>$val){
            // echo $key;
            // $master_adminid_ass = $this->t_admin_group_user->get_master_adminid_by_adminid($key);
            $master_adminid_ass = $val["master_adminid"];
            @$ass_group[$master_adminid_ass]["warning_student"]  += $val["warning_student"];
            @$ass_group[$master_adminid_ass]["read_student"]     += $val["read_student"];
            @$ass_group[$master_adminid_ass]["stop_student"]     += $val["stop_student"];
            @$ass_group[$master_adminid_ass]["all_student"]      += $val["all_student"];
            @$ass_group[$master_adminid_ass]["month_stop_student"]  += $val["month_stop_student"];
            @$ass_group[$master_adminid_ass]["lesson_total"]     += $val["lesson_total"];
            @$ass_group[$master_adminid_ass]["lesson_money"]     += $val["lesson_money"];
            @$ass_group[$master_adminid_ass]["lesson_total_old"]     += $val["lesson_total_old"];
            @$ass_group[$master_adminid_ass]["tran_price"]       += $val["tran_price"];
            @$ass_group[$master_adminid_ass]["renw_price"]       += $val["renw_price"];
            @$ass_group[$master_adminid_ass]["all_price"]        += $val["all_price"];
            @$ass_group[$master_adminid_ass]["renw_student"]     += $val["renw_student"];
            @$ass_group[$master_adminid_ass]["read_student_last"]  += $val["read_student_last"];
            @$ass_group[$master_adminid_ass]["renw_target"]           += $val["renw_target"];
            @$ass_group[$master_adminid_ass]["renw_stu_target"]       += $val["renw_stu_target"];
            @$ass_group[$master_adminid_ass]["kk_suc"]       += $val["kk_suc"];
            @$ass_group[$master_adminid_ass]["kk_require"]       += $val["kk_require"];
            @$ass_group[$master_adminid_ass]["except_num"]       += $val["except_num"];
            @$ass_group[$master_adminid_ass]["except_count"]       += $val["except_count"];
            @$ass_group[$master_adminid_ass]["refund_student"]       += $val["refund_student"];
            @$ass_group[$master_adminid_ass]["new_refund_money"]       += $val["new_refund_money"];
            @$ass_group[$master_adminid_ass]["renw_refund_money"]       += $val["renw_refund_money"];
            @$ass_group[$master_adminid_ass]["new_student"]       += $val["new_student"];
            @$ass_group[$master_adminid_ass]["new_lesson_count"]       += $val["new_lesson_count"];
            @$ass_group[$master_adminid_ass]["end_stu_num"]       += $val["end_stu_num"];
            @$ass_group[$master_adminid_ass]["lesson_student"]       += $val["lesson_student"];
            @$ass_group[$master_adminid_ass]["cc_tran_money"]       += $val["cc_tran_money"];
            @$ass_group[$master_adminid_ass]["group_name"]  = $val["group_name"];
        }

        foreach($ass_group as $key=>&$v){
            // $v["account"] = $this->t_manager_info->get_account($key);
            $v["lesson_ratio"]          = !empty($v["read_student_last"])?round($v["lesson_total_old"]/$v["read_student_last"],1):0;
            $v["lesson_target"]         =$lesson_target;
            $v["lesson_per"]            =!empty($v["lesson_target"])?round($v["lesson_ratio"]/$v["lesson_target"],4)*100:0;
            $v["renw_per"]             =!empty($v["renw_target"])?round($v["all_price"]/$v["renw_target"],4)*100:0;
            $v["renw_stu_per"]            =!empty($v["renw_stu_target"])?round($v["renw_student"]/$v["renw_stu_target"],4)*100:0;

        }
        unset($ass_group[0]);

        // dd($ass_group);
        if($account_id==349){
            $account_id=297;
        }
        $stu_info=@$ass_group[$account_id];
        $ass_list_group=[];
        foreach($ass_list3 as $k=>$item2){
            $ass_master_adminid = $this->t_admin_group_user->get_master_adminid_by_adminid($k);
            if($ass_master_adminid == $account_id){
                $ass_list_group[$k]=$item2;
            }
        }
        \App\Helper\Utils::order_list( $ass_list_group,"lesson_ratio", 0 );


        \App\Helper\Utils::order_list( $ass_list,"lesson_ratio", 0 );
        \App\Helper\Utils::order_list( $ass_group,"lesson_ratio", 0 );

        $uid_str = $this->t_manager_info->get_uid_str_by_adminid($master_adminid);
        $now = time();
        $three = $now - 86400*7;
        $warning_count = $this->t_revisit_info->get_ass_revisit_warning_count($master_adminid, $three,$uid_str);

        $warning_type_num = [
            'warning_type_one' =>0,
            'warning_type_two' =>0,
            'warning_type_three' =>0,
        ];
        foreach($warning_count as $item){
            \App\Helper\Utils::revisit_warning_type_count($item, $warning_type_num);
        }

        $three_count = $this->t_revisit_warning_overtime_info->get_ass_warning_overtime_count($master_adminid, $uid_str, $cur_start, $cur_end);
        $warning_type_num['warning_type_three'] = $three_count;

        //月回访信息
        $month_info = $this->t_revisit_assess_info->get_month_assess_info_by_uid( $master_adminid, $cur_start, $cur_end,$uid_str);

        $leader_revisit_info = [];
        //组长回访统计
        $leader_stu_num = $this->t_revisit_assess_info->get_stu_num_info( $uid_str, $cur_start, $cur_end);
        $leader_revisit_info['leader_goal'] = ceil($leader_stu_num / 10);
        $leader_revisit_info['leader_revisited'] = $this->t_manager_info->get_leader_revisit_info( $master_adminid,$cur_start, $cur_end);
        $leader_revisit_info['nick'] = $this->cache_get_account_nick($master_adminid);
        foreach( $month_info as &$item) {
            $item["call_num"]= \App\Helper\Common::get_time_format_minute(@$item["call_num"]);
        }

        $refund_warning = $this->t_student_info->get_refund_warning();
        foreach($refund_warning as $re_val) {
            @$refund_warning["total"] += $re_val;
        }
        // dd($month_info);

        return $this->pageView(__METHOD__ ,null, [
            "stu_info" => @$stu_info,
            "ass_list"  =>@$ass_list,
            // "ass_group"   =>@$ass_group[$account_id],
            "ass_list_group" =>@$ass_list_group,
            "warning"       => $warning_type_num,
            "month_info" =>$month_info,
            "leader_revisit_info" =>$leader_revisit_info,
            "refund_warning" => $refund_warning
        ]);

    }

    public function assistant_main_leader_new() {
        $this->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);

        $opt_date_type = $this->get_in_int_val("opt_date_type",3);

        if($opt_date_type==1){
            $cur_start = strtotime(date('Y-m-01',$start_time));
            $mid_time = strtotime(date('Y-m-15',$start_time));
            $cur_end = strtotime(date('Y-m-01',$mid_time+30*86400));
        }else if($opt_date_type==2){
            $cur_start = strtotime(date('Y-m-01',$end_time));
            $mid_time = strtotime(date('Y-m-15',$end_time));
            $cur_end = strtotime(date('Y-m-01',$mid_time+30*86400));
        }else if($opt_date_type==3){
            $cur_start = $start_time;
            $cur_end = $end_time;
        }
        $last_month  = strtotime(date('Y-m-01',$cur_start-100));
        $month_start = strtotime(date("Y-m-01",time(NULL)));
        $account_id  = $this->get_account_id();
        $master_adminid    = $this->t_admin_group_user->get_master_adminid_by_adminid($account_id);
        $up_master_adminid = $this->t_admin_main_group_name->get_up_group_adminid( $master_adminid);

        $ass_last_month    = $this->t_month_ass_student_info->get_ass_month_info($last_month);
        $lesson_count_list_old=[];
        $ass_list = $this->t_manager_info->get_adminid_list_by_account_role(1);



        $lesson_target     = $this->t_ass_group_target->get_rate_target($cur_start);
        $kk_require        = $this->t_test_lesson_subject->get_ass_kk_tongji_all_info($start_time,$end_time);
        // $lesson_money_list = $this->t_manager_info->get_assistant_lesson_money_info($start_time,$end_time);
        $stu_info_all      = $this->t_student_info->get_ass_stu_info_new();
        $ass_month= $this->t_month_ass_student_info->get_ass_month_info($cur_start);
        //  $assistant_renew_list = $this->t_manager_info->get_all_assistant_renew_list_new($start_time,$end_time);
        // $new_info = $this->t_student_info->get_new_assign_stu_info($start_time,$end_time);
        // $end_stu_info_new = $this->t_student_info->get_end_class_stu_info($start_time,$end_time);
        //  $lesson_info = $this->t_lesson_info_b2->get_ass_stu_lesson_list($start_time,$end_time);
        foreach($ass_list as $k=>&$item){
            $item["warning_student"]  = isset($ass_month[$k]["warning_student"])?$ass_month[$k]["warning_student"]:0;
            $item["read_student"]     = isset($ass_month[$k]["read_student"])?$ass_month[$k]["read_student"]:0;
            $item["stop_student"]     = isset($ass_month[$k]["stop_student"])?$ass_month[$k]["stop_student"]:0;
            $item["all_student"]      = isset($ass_month[$k]["all_student"])?$ass_month[$k]["all_student"]:0;
            $item["month_stop_student"]  = isset($ass_month[$k]["month_stop_student"])?$ass_month[$k]["month_stop_student"]:0;
            $item["lesson_ratio"]  = isset($ass_month[$k]["lesson_ratio"])?$ass_month[$k]["lesson_ratio"]:0;
            $item["lesson_total"]  = isset($ass_month[$k]["lesson_total"])?$ass_month[$k]["lesson_total"]/100:0;
            // $item["renw_price"]  = isset($assistant_renew_list[$k]["renw_price"])?$assistant_renew_list[$k]["renw_price"]/100:0;//续费金额
            $item["renw_price"]  = isset($ass_month[$k]["renw_price"])?$ass_month[$k]["renw_price"]/100:0;//续费金额
            //$item["tran_price"]  = isset($assistant_renew_list[$k]["tran_price"])?$assistant_renew_list[$k]["tran_price"]/100:0;//转介绍金额
            $item["tran_price"]  = isset($ass_month[$k]["tran_price"])?$ass_month[$k]["tran_price"]/100:0;//转介绍金额
            //$item["renw_student"]  = isset($assistant_renew_list[$k]["all_student"])?$assistant_renew_list[$k]["all_student"]:0;//续费学生数
            $item["renw_student"]  = isset($ass_month[$k]["renw_student"])?$ass_month[$k]["renw_student"]:0;//续费学生数

            $item["read_student_last"]  = isset($ass_month[$k]["read_student_last"])?@$ass_month[$k]["read_student_last"]:0;
            $item["all_price"]     = $item["renw_price"]+$item["tran_price"];
            $item["lesson_target"]         = $lesson_target;
            $item["lesson_per"]            = !empty($item["lesson_target"])?round($item["lesson_ratio"]/$item["lesson_target"],4)*100:0;
            $item["renw_target"]           = @$ass_last_month[$k]["warning_student"]*0.8*8000;
            $item["renw_per"]              = !empty($item["renw_target"])?round($item["all_price"]/$item["renw_target"],4)*100:0;
            $item["renw_stu_target"]       = ceil(@$ass_last_month[$k]["warning_student"]*0.8);
            $item["renw_stu_per"]          = !empty($item["renw_stu_target"])?round($item["renw_student"]/$item["renw_stu_target"],4)*100:0;
            // $item["kk_suc"]                = isset($ass_month[$k]["kk_num"])?$ass_month[$k]["kk_num"]:0;
            $item["kk_suc"]                = (isset($ass_month[$k]["kk_num"])?$ass_month[$k]["kk_num"]:0)+(isset($ass_month[$k]["hand_kk_num"])?$ass_month[$k]["hand_kk_num"]:0);

            $item["kk_require"]            =@$kk_require[$k]["all_count"];
            $item["except_num"]            =@$stu_info_all[$k]["except_num"];
            $item["except_count"]            =@$stu_info_all[$k]["except_count"];
            // $item["lesson_money"]          = @$lesson_money_list[$k]["lesson_price"]/100;//课耗收入
            $item["lesson_money"]          = isset($ass_month[$k]["lesson_money"])?$ass_month[$k]["lesson_money"]/100:0;//课耗收入
            //$item["lesson_total_old"]  = intval($item["read_student_last"]*$item["lesson_ratio"]);
            $item["lesson_total_old"]  = !empty(@$ass_last_month[$k]["lesson_total_old"])?@$ass_last_month[$k]["lesson_total_old"]/100:(round($item["read_student_last"]*$item["lesson_ratio"],1));
            $item["refund_student"]  = isset($ass_month[$k]["refund_student"])?$ass_month[$k]["refund_student"]:0;
            $item["new_refund_money"]  = isset($ass_month[$k]["new_refund_money"])?$ass_month[$k]["new_refund_money"]/100:0;
            $item["renw_refund_money"]  = isset($ass_month[$k]["renw_refund_money"])?$ass_month[$k]["renw_refund_money"]/100:0;
            // $item["new_student"]  = isset($new_info[$k]["num"])?$new_info[$k]["num"]:0;//新签人数
            $item["new_student"]  = isset($ass_month[$k]["new_student"])?$ass_month[$k]["new_student"]:0;//新签人数
            // $item["new_lesson_count"]  = isset($new_info[$k]["lesson_count"])?$new_info[$k]["lesson_count"]/100:0;//购买课时
            $item["new_lesson_count"]  = isset($ass_month[$k]["new_lesson_count"])?$ass_month[$k]["new_lesson_count"]/100:0;//购买课时
            //$item["end_stu_num"]  = isset($end_stu_info_new[$k]["num"])?$end_stu_info_new[$k]["num"]:0;//结课学生
            $item["end_stu_num"]  = isset($ass_month[$k]["end_stu_num"])?$ass_month[$k]["end_stu_num"]:0;//结课学生
            //$item["lesson_student"]  = isset($lesson_info[$k]["user_count"])?$lesson_info[$k]["user_count"]:0;//在读学生
            $item["lesson_student"]  = isset($ass_month[$k]["lesson_student"])?$ass_month[$k]["lesson_student"]:0;//在读学生
            $item["cc_tran_money"]          = isset($ass_month[$k]["cc_tran_money"])?$ass_month[$k]["cc_tran_money"]/100:0;//CC转介绍




        }
        if(in_array(date("d",$start_time),[28,29,30,31])){
            foreach($ass_last_month as $ks=>$vss){
                $userid_list = json_decode($vss["userid_list_last"],true);
                if(empty($userid_list)){
                    $userid_list=[];
                }

                $lesson_count_list_old[$ks]=$this->t_manager_info->get_assistant_lesson_count_old($start_time,$end_time,$ks,$userid_list);
            }

            foreach($ass_list as $k=>&$dal){
                $dal["lesson_ratio"]  = !empty(@$ass_last_month[$k]["read_student"])?round(@$lesson_count_list_old[$k]/@$ass_last_month[$k]["read_student"]/100,1):0;
                $dal["lesson_total_old"]      = @$lesson_count_list_old[$k]/100;
            }
        }


        // dd($ass_list);
        $ass_list1 = $ass_list2 = $ass_list3 =   $ass_list;
        $ass_leader_arr = $this->t_admin_group_name->get_leader_list(1);
        $ass_group=[];
        foreach($ass_list1 as $key=>$val){
            // echo $key;
            //  $master_adminid_ass_list = $this->t_admin_group_user->get_master_adminid_group_info($key);
            // $master_adminid_ass = $master_adminid_ass_list["master_adminid"];
            $master_adminid_ass = $val["master_adminid"];
            @$ass_group[$master_adminid_ass]["warning_student"]  += $val["warning_student"];
            @$ass_group[$master_adminid_ass]["read_student"]     += $val["read_student"];
            @$ass_group[$master_adminid_ass]["stop_student"]     += $val["stop_student"];
            @$ass_group[$master_adminid_ass]["all_student"]      += $val["all_student"];
            @$ass_group[$master_adminid_ass]["month_stop_student"]  += $val["month_stop_student"];
            @$ass_group[$master_adminid_ass]["lesson_total"]     += $val["lesson_total"];
            @$ass_group[$master_adminid_ass]["lesson_money"]     += $val["lesson_money"];
            @$ass_group[$master_adminid_ass]["lesson_total_old"]     += $val["lesson_total_old"];
            @$ass_group[$master_adminid_ass]["tran_price"]       += $val["tran_price"];
            @$ass_group[$master_adminid_ass]["renw_price"]       += $val["renw_price"];
            @$ass_group[$master_adminid_ass]["all_price"]        += $val["all_price"];
            @$ass_group[$master_adminid_ass]["renw_student"]     += $val["renw_student"];
            @$ass_group[$master_adminid_ass]["read_student_last"]  += $val["read_student_last"];
            @$ass_group[$master_adminid_ass]["renw_target"]           += $val["renw_target"];
            @$ass_group[$master_adminid_ass]["renw_stu_target"]       += $val["renw_stu_target"];
            @$ass_group[$master_adminid_ass]["kk_suc"]       += $val["kk_suc"];
            @$ass_group[$master_adminid_ass]["kk_require"]       += $val["kk_require"];
            @$ass_group[$master_adminid_ass]["except_num"]       += $val["except_num"];
            @$ass_group[$master_adminid_ass]["except_count"]       += $val["except_count"];
            @$ass_group[$master_adminid_ass]["refund_student"]       += $val["refund_student"];
            @$ass_group[$master_adminid_ass]["new_refund_money"]       += $val["new_refund_money"];
            @$ass_group[$master_adminid_ass]["renw_refund_money"]       += $val["renw_refund_money"];
            @$ass_group[$master_adminid_ass]["new_student"]       += $val["new_student"];
            @$ass_group[$master_adminid_ass]["new_lesson_count"]       += $val["new_lesson_count"];
            @$ass_group[$master_adminid_ass]["end_stu_num"]       += $val["end_stu_num"];
            @$ass_group[$master_adminid_ass]["lesson_student"]       += $val["lesson_student"];
            @$ass_group[$master_adminid_ass]["cc_tran_money"]       += $val["cc_tran_money"];
            @$ass_group[$master_adminid_ass]["group_name"]  = $val["group_name"];


        }

        foreach($ass_group as $key=>&$v){
            // $v["account"] = $this->t_manager_info->get_account($key);
            $v["lesson_ratio"]          = !empty($v["read_student_last"])?round($v["lesson_total_old"]/$v["read_student_last"],1):0;
            $v["lesson_target"]         =$lesson_target;
            $v["lesson_per"]            =!empty($v["lesson_target"])?round($v["lesson_ratio"]/$v["lesson_target"],4)*100:0;
            $v["renw_per"]             =!empty($v["renw_target"])?round($v["all_price"]/$v["renw_target"],4)*100:0;
            $v["renw_stu_per"]            =!empty($v["renw_stu_target"])?round($v["renw_student"]/$v["renw_stu_target"],4)*100:0;
            if(empty($v["group_name"])){
                unset($ass_group[$key]);
            }

        }
        unset($ass_group[0]);

        $stu_info=[];
        foreach($ass_list2 as $item1){
            @$stu_info["warning_student"]  += @$item1["warning_student"];
            @$stu_info["read_student"]     += @$item1["read_student"];
            @$stu_info["stop_student"]     += @$item1["stop_student"];
            @$stu_info["all_student"]      += @$item1["all_student"];
            @$stu_info["month_stop_student"]  += @$item1["month_stop_student"];
            @$stu_info["lesson_total"]     += @$item1["lesson_total"];
            @$stu_info["lesson_money"]     += @$item1["lesson_money"];
            @$stu_info["lesson_total_old"]     += @$item1["lesson_total_old"];
            @$stu_info["tran_price"]       += @$item1["tran_price"];
            @$stu_info["renw_price"]       += @$item1["renw_price"];
            @$stu_info["all_price"]        += @$item1["all_price"];
            @$stu_info["renw_student"]     += @$item1["renw_student"];
            @$stu_info["read_student_last"]  += @$item1["read_student_last"];
            @$stu_info["kk_suc"]          += @$item1["kk_suc"];
            @$stu_info["kk_require"]          += @$item1["kk_require"];
            @$stu_info["except_count"]          += @$item1["except_count"];
            @$stu_info["except_num"]          += @$item1["except_num"];
            @$stu_info["refund_student"]          += @$item1["refund_student"];
            @$stu_info["new_refund_money"]          += @$item1["new_refund_money"];
            @$stu_info["renw_refund_money"]          += @$item1["renw_refund_money"];
            @$stu_info["new_student"]          += @$item1["new_student"];
            @$stu_info["new_lesson_count"]          += @$item1["new_lesson_count"];
            @$stu_info["end_stu_num"]          += @$item1["end_stu_num"];
            @$stu_info["lesson_student"]          += @$item1["lesson_student"];
            //$item["lesson_per"]            = !empty($item["lesson_target"])?round($item["lesson_ratio"]/$item["lesson_target"],4)*100:0;
            @$stu_info["renw_target"]           += @$item1["renw_target"];
            //$item["renw_per"]              = !empty($item["renw_target"])?round($item["renw_price"]/$item["renw_target"],4)*100:0;
            @$stu_info["renw_stu_target"]       += @$item1["renw_stu_target"];
            @$stu_info["cc_tran_money"]       += @$item1["cc_tran_money"];
            //$item["renw_stu_per"]          = !empty($item["renw_stu_target"])?round($item["renw_student"]/$item["renw_stu_target"],4)*100:0;
        }
        $stu_info["lesson_ratio"]          = !empty($stu_info["read_student_last"])?round($stu_info["lesson_total_old"]/$stu_info["read_student_last"],1):0;
        $stu_info["lesson_target"]         =$lesson_target;
        $stu_info["lesson_per"]            =!empty($stu_info["lesson_target"])?round($stu_info["lesson_ratio"]/$lesson_target,4)*100:0;
        $stu_info["renw_per"]             =!empty($stu_info["renw_target"])?round($stu_info["all_price"]/$stu_info["renw_target"],4)*100:0;
        $stu_info["renw_stu_per"]            =!empty($stu_info["renw_stu_target"])?round($stu_info["renw_student"]/$stu_info["renw_stu_target"],4)*100:0;


        \App\Helper\Utils::order_list( $ass_list,"lesson_ratio", 0 );
        \App\Helper\Utils::order_list( $ass_group,"lesson_ratio", 0 );

        //查询离职助教
        $del_flag = $this->t_manager_info->get_del_ass_list(1);
        foreach($del_flag as $tp){
            $melon_info =@$ass_month[$tp["uid"]];
            $melon_info_last = @$ass_last_month[$tp["uid"]];
            if(!empty($melon_info)){
                $melon_info["lesson_total"] = @$melon_info["lesson_total"]/100;
                $melon_info["tran_price"] = @$melon_info["tran_price"]/100;
                $melon_info["renw_price"] = @$melon_info["renw_price"]/100;
                $melon_info["all_price"] = $melon_info["tran_price"]+$melon_info["renw_price"];
                $melon_info["lesson_target"] = $lesson_target;
                $melon_info["renw_target"] = @$melon_info_last["warning_student"]*0.8*8000;
                $melon_info["renw_per"] = !empty($melon_info["renw_target"])?round($melon_info["all_price"]/$melon_info["renw_target"]*100,2):0;
                $melon_info["renw_stu_target"] = @$melon_info_last["warning_student"]*0.8;
                $melon_info["renw_stu_per"] = !empty($melon_info["renw_target"])?round($melon_info["all_price"]/$melon_info["renw_target"]*100,2):0;
                $melon_info["kk_suc"] = $melon_info["kk_num"];
                $melon_info["lesson_money"] = $melon_info["lesson_money"]/100;
                $melon_info["lesson_total_old"] = @$melon_info["lesson_total_old"]/100;
                $melon_info["new_refund_money"]  = $melon_info["new_refund_money"]/100;
                $melon_info["renw_refund_money"]  = $melon_info["renw_refund_money"]/100;
                $melon_info["new_lesson_count"]  = $melon_info["new_lesson_count"]/100;
                $melon_info["cc_tran_money"]  = $melon_info["cc_tran_money"]/100;
                $melon_info["account"]=$tp["name"];
                $melon_info["nick"]=$tp["name"];

                array_push($ass_list,$melon_info);

            }

        }

        //田梦影数据

        //田梦茹数据
        /* $ruby_info =@$ass_month[386];
        $ruby_info_last = @$ass_last_month[386];
        if(!empty($ruby_info)){
            $ruby_info["lesson_total"] = @$ruby_info["lesson_total"]/100;
            $ruby_info["tran_price"] = @$ruby_info["tran_price"]/100;
            $ruby_info["renw_price"] = @$ruby_info["renw_price"]/100;
            $ruby_info["all_price"] = $ruby_info["tran_price"]+$ruby_info["renw_price"];
            $ruby_info["lesson_target"] = $lesson_target;
            $ruby_info["renw_target"] = @$ruby_info_last["warning_student"]*0.8*8000;
            $ruby_info["renw_per"] = !empty($ruby_info["renw_target"])?round($ruby_info["all_price"]/$ruby_info["renw_target"]*100,2):0;
            $ruby_info["renw_stu_target"] = @$ruby_info_last["warning_student"]*0.8;
            $ruby_info["renw_stu_per"] = !empty($ruby_info["renw_target"])?round($ruby_info["all_price"]/$ruby_info["renw_target"]*100,2):0;
            $ruby_info["kk_suc"] = $ruby_info["kk_num"];
            $ruby_info["lesson_money"] = $ruby_info["lesson_money"]/100;
            $ruby_info["lesson_total_old"] = @$ruby_info["lesson_total_old"]/100;
            $ruby_info["new_refund_money"]  = $ruby_info["new_refund_money"]/100;
            $ruby_info["renw_refund_money"]  = $ruby_info["renw_refund_money"]/100;
            $ruby_info["new_lesson_count"]  = $ruby_info["new_lesson_count"]/100;
            $ruby_info["account"]="田梦茹";
            $ruby_info["nick"]="田梦茹";

            array_push($ass_list,$ruby_info);

            }*/

        $adminid = $this->get_account_id();
        $uid_str = $this->t_manager_info->get_uid_str_by_adminid($adminid);
        $now = time();
        $three = $now - 86400*7;
        $warning_count = $this->t_revisit_info->get_ass_revisit_warning_count(-1, $three,$uid_str);

        $warning_type_num = [
            'warning_type_one' =>0,
            'warning_type_two' =>0,
            'warning_type_three' =>0,
        ];
        foreach($warning_count as $item){
            \App\Helper\Utils::revisit_warning_type_count($item, $warning_type_num);
        }

        $three_count = $this->t_revisit_warning_overtime_info->get_ass_warning_overtime_count(-1, $uid_str, $cur_start, $cur_end);
        $warning_type_num['warning_type_three'] = $three_count;

        //月回访信息
        $month_info = $this->t_revisit_assess_info->get_month_assess_info_by_uid( -1, $cur_start, $cur_end,$uid_str);
        foreach( $month_info as &$item) {
            $item["call_num"]= \App\Helper\Common::get_time_format_minute(@$item["call_num"]);
        }

        //各组长回访信息
        $leader_info = $this->t_admin_group_name->get_stu_num_leader($cur_start, $cur_end);
        foreach ($leader_info as &$item) {
            $item['revisit_num'] = $this->t_manager_info->get_leader_revisit_info( $item['master_adminid'],$cur_start, $cur_end);
            $item['goal'] = ceil($item['stu_num'] /10 );
            // $item['nick'] = $this->cache_get_account_nick($item['master_adminid']);
        }

        return $this->pageView(__METHOD__ ,null, [
            "stu_info" => @$stu_info,
            "ass_list"  =>@$ass_list,
            "ass_group"   =>@$ass_group,
            "ass_list_group" =>@$ass_list_group,
            "warning"       => $warning_type_num,
            "month_info" =>$month_info,
            "leader_info" => $leader_info,
        ]);

    }

    public function teacher_management_info(){
        $this->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);
        $top_seller_total = $this->t_lesson_info_b3->get_seller_test_lesson_tran_info( $start_time,$end_time,1,1); //咨询/老师1000精排总体
        $top_seller_total["per"] = !empty($top_seller_total["person_num"])?round($top_seller_total["have_order"]/$top_seller_total["person_num"]*100,2):0;

        $green_seller_total = $this->t_lesson_info_b3->get_seller_test_lesson_tran_info( $start_time,$end_time,2,1); //咨询/老师绿色通道总体
        $green_seller_total["per"] = !empty($green_seller_total["person_num"])?round($green_seller_total["have_order"]/$green_seller_total["person_num"]*100,2):0;

        $normal_seller_total_grab = $this->t_lesson_info_b3->get_seller_test_lesson_tran_info( $start_time,$end_time,3,1,1); //咨询/老师普通排课总体(抢课)
        $normal_seller_total_grab["per"] = !empty($normal_seller_total_grab["person_num"])?round($normal_seller_total_grab["have_order"]/$normal_seller_total_grab["person_num"]*100,2):0;

        $normal_seller_total = $this->t_lesson_info_b3->get_seller_test_lesson_tran_info( $start_time,$end_time,3,1,0); //咨询/老师普通排课总体(非抢课)
        $normal_seller_total["per"] = !empty($normal_seller_total["person_num"])?round($normal_seller_total["have_order"]/$normal_seller_total["person_num"]*100,2):0;
        $top_jw_total = $this->t_lesson_info_b3->get_seller_test_lesson_tran_info( $start_time,$end_time,1,2); //教务1000精排总体
        $top_jw_total["per"] = !empty($top_jw_total["person_num"])?round($top_jw_total["have_order"]/$top_jw_total["person_num"]*100,2):0;

        $green_jw_total = $this->t_lesson_info_b3->get_seller_test_lesson_tran_info( $start_time,$end_time,2,2); //教务绿色通道总体
        $green_jw_total["per"] = !empty($green_jw_total["person_num"])?round($green_jw_total["have_order"]/$green_jw_total["person_num"]*100,2):0;
        $normal_jw_total_grab = $this->t_lesson_info_b3->get_seller_test_lesson_tran_info( $start_time,$end_time,3,2,1); //教务普通排课总体(抢课)
        $normal_jw_total_grab["per"] = !empty($normal_jw_total_grab["person_num"])?round($normal_jw_total_grab["have_order"]/$normal_jw_total_grab["person_num"]*100,2):0;
        $normal_jw_total = $this->t_lesson_info_b3->get_seller_test_lesson_tran_info( $start_time,$end_time,3,2,0); //教务普通排课总体(非抢课)
        $normal_jw_total["per"] = !empty($normal_jw_total["person_num"])?round($normal_jw_total["have_order"]/$normal_jw_total["person_num"]*100,2):0;

        //咨询
        $seller_all = $this->t_lesson_info_b3->get_seller_test_lesson_tran_seller( $start_time,$end_time,-1,1);
        $top_seller_all = $this->t_lesson_info_b3->get_seller_test_lesson_tran_seller( $start_time,$end_time,1,1);
        $green_seller_all = $this->t_lesson_info_b3->get_seller_test_lesson_tran_seller( $start_time,$end_time,2,1);
        $normal_seller_all = $this->t_lesson_info_b3->get_seller_test_lesson_tran_seller( $start_time,$end_time,3,1);
        foreach($seller_all as $k=>&$val){
            $val["per"] = !empty($val["person_num"])?round($val["have_order"]/$val["person_num"]*100,2):0;
            $val["top_num"] = @$top_seller_all[$k]["person_num"];
            $val["top_order"] = @$top_seller_all[$k]["have_order"];
            $val["green_num"] = @$green_seller_all[$k]["person_num"];
            $val["green_order"] = @$green_seller_all[$k]["have_order"];
            $val["normal_num"] = @$normal_seller_all[$k]["person_num"];
            $val["normal_order"] = @$normal_seller_all[$k]["have_order"];
            $val["top_per"] = !empty($val["top_num"])?round($val["top_order"]/$val["top_num"]*100,2):0;
            $val["green_per"] = !empty($val["green_num"])?round($val["green_order"]/$val["green_num"]*100,2):0;
            $val["normal_per"] = !empty($val["normal_num"])?round($val["normal_order"]/$val["normal_num"]*100,2):0;

        }

        \App\Helper\Utils::order_list( $seller_all,"per", 0 );
        /* foreach($seller_all as $s=>$v){
            if($s>9){
                unset($seller_all[$s]);
            }
            }*/

        //老师
        $tea_all = $this->t_lesson_info_b3->get_seller_test_lesson_tran_tea( $start_time,$end_time,-1,1);
        $top_tea_all = $this->t_lesson_info_b3->get_seller_test_lesson_tran_tea( $start_time,$end_time,1,1);
        $green_tea_all = $this->t_lesson_info_b3->get_seller_test_lesson_tran_tea( $start_time,$end_time,2,1);
        $normal_tea_all = $this->t_lesson_info_b3->get_seller_test_lesson_tran_tea( $start_time,$end_time,3,1);
        foreach($tea_all as $kk=>&$valll){

            $valll["per"] = !empty($valll["person_num"])?round($valll["have_order"]/$valll["person_num"]*100,2):0;
            $valll["top_num"] = @$top_tea_all[$kk]["person_num"];
            $valll["top_order"] = @$top_tea_all[$kk]["have_order"];
            $valll["green_num"] = @$green_tea_all[$kk]["person_num"];
            $valll["green_order"] = @$green_tea_all[$kk]["have_order"];
            $valll["normal_num"] = @$normal_tea_all[$kk]["person_num"];
            $valll["normal_order"] = @$normal_tea_all[$kk]["have_order"];
            $valll["top_per"] = !empty($valll["top_num"])?round($valll["top_order"]/$valll["top_num"]*100,2):0;
            $valll["green_per"] = !empty($valll["green_num"])?round($valll["green_order"]/$valll["green_num"]*100,2):0;
            $valll["normal_per"] = !empty($valll["normal_num"])?round($valll["normal_order"]/$valll["normal_num"]*100,2):0;
            /* if($valll["person_num"] <10){
                unset($tea_all[$kk]);
                }*/

        }

        \App\Helper\Utils::order_list( $tea_all,"per", 0 );
        /*foreach($tea_all as $s=>$v){
            if($s>9){
                unset($tea_all[$s]);
            }
            }*/

        //教务
        $jw_all = $this->t_lesson_info_b3->get_seller_test_lesson_tran_jw( $start_time,$end_time,-1,2);
        $top_jw_all = $this->t_lesson_info_b3->get_seller_test_lesson_tran_jw( $start_time,$end_time,1,2);
        $green_jw_all = $this->t_lesson_info_b3->get_seller_test_lesson_tran_jw( $start_time,$end_time,2,2);
        $normal_jw_all = $this->t_lesson_info_b3->get_seller_test_lesson_tran_jw( $start_time,$end_time,3,2);
        foreach($jw_all as $kk=>&$vall){
            $vall["per"] = !empty($vall["person_num"])?round($vall["have_order"]/$vall["person_num"]*100,2):0;
            $vall["top_num"] = @$top_jw_all[$kk]["person_num"];
            $vall["top_order"] = @$top_jw_all[$kk]["have_order"];
            $vall["green_num"] = @$green_jw_all[$kk]["person_num"];
            $vall["green_order"] = @$green_jw_all[$kk]["have_order"];
            $vall["normal_num"] = @$normal_jw_all[$kk]["person_num"];
            $vall["normal_order"] = @$normal_jw_all[$kk]["have_order"];
            $vall["top_per"] = !empty($vall["top_num"])?round($vall["top_order"]/$vall["top_num"]*100,2):0;
            $vall["green_per"] = !empty($vall["green_num"])?round($vall["green_order"]/$vall["green_num"]*100,2):0;
            $vall["normal_per"] = !empty($vall["normal_num"])?round($vall["normal_order"]/$vall["normal_num"]*100,2):0;

        }

        \App\Helper\Utils::order_list( $jw_all,"per", 0 );







        return $this->pageView(__METHOD__ ,null, [
            "top_jw_total" => $top_jw_total,
            "green_jw_total" => $green_jw_total,
            "normal_jw_total" => $normal_jw_total,
            "normal_jw_total_grab" => $normal_jw_total_grab,
            "top_seller_total" => $top_seller_total,
            "green_seller_total" => $green_seller_total,
            "normal_seller_total" => $normal_seller_total,
            "normal_seller_total_grab" => $normal_seller_total_grab,
            "seller_all" => $seller_all,
            "tea_all" => $tea_all,
            "jw_all" => $jw_all,
        ]);


    }

    public function recruit_division() {
        $this->set_in_value('recruit','');
        return $this->recruit();
    }

    // 招师例子流 培训通过 至 模拟试听通过
    public function train_recruit_division() {
        $this->set_in_value("recruit","train");
        return $this->recruit();
    }

    public function recruit()
    {
        list($start_time, $end_time) = $this->get_in_date_range_day(0);
        $recruit      = $this->get_in_str_val("recruit");
        $history_data = $this->get_in_int_val('history_data');

        $total    = $this->t_new_tea_entry->get_total();
        if ($history_data) { // 没有历史数据
            $ret_info = $this->recruit_array_init(1);
            $tea_list = $this->t_teacher_flow->get_tea_list($start_time, $end_time);
            // 参训人数
            //$train_tea = $this->t_teacher_info->get_train_inter_teacher_count($start_time, $end_time);
            // 排课人数
            //$imit = $this->t_lesson_info->get_imit_audi_sched_count($start_time, $end_time);
            // 上课人数
            //$attend = $this->t_lesson_info->get_attend_lesson_count($start_time, $end_time);
            $train_tea=$imit=$attend='';
            foreach($tea_list as $id => $val) {
                $ret_info = $this->accumulation_recruit($ret_info, $id, $val, $train_tea, $imit, $attend, $end_time, 1);
            }
            $total['sum'] = 0;
            $total['imit_sum'] = 0;
            $total['attend_sum'] = 0;
            $total['adopt_sum'] = 0;
            $total['train_tea_sum'] = 0;
            $total['train_qual_sum'] = 0;
            foreach($ret_info as &$item) {
                if (isset($item['grade'])) {
                    E\Esubject::set_item_value_str($item, "subject");
                    E\Egrade::set_item_value_str($item, "grade");
                } else {
                    $item['grade_str'] = '';
                    E\Esubject::set_item_value_str($item, "subject");
                }
                if ($recruit == 'train') {
                    $total['train_tea_sum'] += $item['train_tea_sum'];
                    $total['train_qual_sum'] += $item['train_qual_sum'];
                }
                $total['sum'] += $item['sum'];
                $total['imit_sum'] += $item['imit_sum'];
                $total['attend_sum'] += $item['attend_sum'];
                $total['adopt_sum'] += $item['adopt_sum'];
            }
            $type_ret_info = $this->recruit_array_init();
            foreach($tea_list as $id => $val) {
                $type_ret_info = $this->accumulation_recruit($type_ret_info, $id, $val, $train_tea, $imit, $attend, $end_time);
            }
            $type_total['sum'] = 0;
            $type_total['train_tea_sum'] = 0;
            $type_total['train_qual_sum'] = 0;
            $type_total['imit_sum'] = 0;
            $type_total['attend_sum'] = 0;
            $type_total['adopt_sum'] = 0;
            foreach($type_ret_info as $key => &$item) {
                E\Eidentity::set_item_value_str($item, "identity");
                if ($recruit == 'train') {
                    $type_total['train_tea_sum'] += $item['train_tea_sum'];
                    $type_total['train_qual_sum'] += $item['train_qual_sum'];
                }
                $type_total['sum'] += $item['sum'];
                $type_total['imit_sum'] += $item['imit_sum'];
                $type_total['attend_sum'] += $item['attend_sum'];
                $type_total['adopt_sum'] += $item['adopt_sum'];
            }
        } else {
            $ret_info = $this->t_new_tea_entry->get_subject_list();
            foreach($ret_info as &$item) {
                if (isset($item['grade'])) {
                    E\Esubject::set_item_value_str($item, "subject");
                    E\Egrade::set_item_value_str($item, "grade");
                } else {
                    $item['grade_str'] = '';
                    E\Esubject::set_item_value_str($item, "subject");
                }
            }
            $type_total = $this->t_new_tea_entry->get_identity_total();
            $type_ret_info = $this->t_new_tea_entry->get_identity_list();
            foreach($type_ret_info as &$item) {
                E\Eidentity::set_item_value_str($item, "identity");
            }
        }
        return $this->pageView(__METHOD__, null, [
            "ret_info" => $ret_info,
            "type_ret_info" => $type_ret_info,
            "total" => $total,
            "type_total" => $type_total,
            'recruit' => $recruit
        ]);
    }

    public function accumulation_recruit($info, $id, $item, $train_tea, $imit, $attend, $end_time, $type='')
    {
        if ($type) {
            if ($item['subject'] == 1 && $item['grade'] >= 100 && $item['grade'] < 200) $key = 0;
            if ($item['subject'] == 1 && $item['grade'] >= 200 && $item['grade'] < 300) $key = 1;
            if ($item['subject'] == 1 && $item['grade'] >= 300) $key = 2;
            if ($item['subject'] == 2 && $item['grade'] >= 100 && $item['grade'] < 200) $key = 3;
            if ($item['subject'] == 2 && $item['grade'] >= 200 && $item['grade'] < 300) $key = 4;
            if ($item['subject'] == 2 && $item['grade'] >= 300) $key = 5;
            if ($item['subject'] == 3 && $item['grade'] >= 100 && $item['grade'] < 200) $key = 6;
            if ($item['subject'] == 3 && $item['grade'] >= 200 && $item['grade'] < 300) $key = 7;
            if ($item['subject'] == 3 && $item['grade'] >= 300) $key = 8;
            if ($item['subject'] == 4) $key = 9;
            if ($item['subject'] == 5) $key = 10;
            if ($item['subject'] == 6) $key = 11;
            if ($item['subject'] == 10) $key = 12;
        } else {
            if ($item['identity'] == 0) $key = 0;
            if ($item['identity'] == 5) $key = 1;
            if ($item['identity'] == 6) $key = 2;
            if ($item['identity'] == 7) $key = 3;
            if ($item['identity'] == 8) $key = 4;
        }

        if (isset($key)) {
            $info[$key]['sum'] ++;
            $train_tea_sum = $this->t_teacher_info->get_train_inter_teacher_count($id);
            if ($train_tea_sum) $info[$key]['train_tea_sum'] ++;
            //if (isset($train_tea[$id])) $info[$key]['train_tea_sum'] ++;
            if ($item['train_through_new_time'] && $item['train_through_new_time'] < $end_time) $info[$key]['train_qual_sum'] ++;
            // 模拟试听总排课人数
            $imit_sum = $this->t_lesson_info->get_imit_audi_sched_count($end_time, $id);
            if ($imit_sum) {$info[$key]['imit_sum']++; $info[$key]['attend_sum']++;}
            //if (isset($imit[$id]) && $imit[$id]['lesson_start'] < $end_time) $info[$key]['imit_sum'] ++;
            //if (isset($attend[$id]) && $attend[$id]['lesson_start'] < $end_time) $info[$key]['attend_sum'] ++;
            if ($item['simul_test_lesson_pass_time'] && $item['simul_test_lesson_pass_time'] < $end_time) $info[$key]['adopt_sum'] ++;
        }
        return $info;
    }

    public function recruit_array_init($type=''){
        if($type) {
            $info = [['subject'=>1,'grade'=>100],['subject'=>1,'grade'=>200],['subject'=>1,'grade'=>300],['subject'=>2,'grade'=>100],['subject'=>2,'grade'=>200],['subject'=>2,'grade'=>300],['subject'=>3,'grade'=>100],['subject'=>3,'grade'=>200],['subject'=>3,'grade'=>300],['subject'=>4],['subject'=>5],['subject'=>6],['subject'=>10]];
        } else {
            $info = [['identity'=>0],['identity'=>5],['identity'=>6],['identity'=>7],['identity'=>8]];
        }
        foreach ($info as $key => $item) {
            $info[$key]['sum'] = 0;
            $info[$key]['train_tea_sum'] = 0;
            $info[$key]['train_qual_sum'] = 0;
            $info[$key]['imit_sum'] = 0;
            $info[$key]['attend_sum'] = 0;
            $info[$key]['adopt_sum'] = 0;
        }
        return $info;
    }
    //@desn:修改新例子需要配额
    public function edit_system_allocates_num(){
        $system_allocates_num = $this->get_in_int_val('system_allocates_num');
        Redis::set('day_system_assign_count',$system_allocates_num);
        $aa = Redis::get('day_system_assign_count');
        \App\Helper\Utils::logger("aa $aa");
        return $this->output_succ();
    }
}
