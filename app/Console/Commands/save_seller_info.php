<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;



class save_seller_info extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:save_seller_info  {--s=} {--e=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 月末保存整月信息
        $task=new \App\Console\Tasks\TaskController();

        $start_time = strtotime($this->option('s'));
        $end_time   = strtotime($this->option('e'));

        if($start_time == null && $end_time == null ){
            $end_time   = strtotime(date('Y-m-01'));
            $start_time = strtotime(date("Y-m-01", ($end_time-86400*20)));
        }


        $ret_info['data_type'] = "月报数据: ".date('Y-m-d 0:0:0',$start_time)." ~ ".date("Y-m-d 0:0:0",$end_time);

        $ret_info['create_time'] = time();

        $ret_info['from_time'] = $start_time;

        $new_order_info = $task->t_order_info->get_new_order_money($start_time, $end_time);// 全部合同信息[部包含新签+转介绍]

        $referral_order = $task->t_order_info->get_referral_income($start_time, $end_time); //  转介绍

        // $ret_info['new_order_num'] = $new_order_info['order_num_new'] + $referral_order['referral_num']; // 合同数量
        $ret_info['new_order_num'] = $new_order_info['order_num_new'] ; // 合同数量

        $ret_info['referral_money'] = $referral_order['referral_price']; // 转介绍收入
        $ret_info['new_money']      = $new_order_info['total_price'] ; //   全部收入[新签+转介绍]
        $ret_info['order_cc_num']    = $new_order_info['total_num'] ; // 有签单的销售人数

        $adminid_list = $task->t_admin_main_group_name->get_adminid_list_new("销售,,,");

        $main_type = 2;// 销售
        $ret_info['seller_target_income'] = $this->get_month_finish_define_money(0,$start_time); // 销售月目标收入
        if (!$ret_info['seller_target_income'] ) {
            $ret_info['seller_target_income'] = 1600000;
        }

        // $month_finish_define_money_2 = $ret_info['seller_target_income']/100;
        $month_end_time   = strtotime(date("Y-m-01",  $end_time));
        $month_start_time = strtotime(date("Y-m-01",  ($month_end_time-86400*20)));
        $month_date_money_list = $task->t_order_info->get_seller_date_money_list($month_start_time,$month_end_time,$adminid_list);
        $ret_info['formal_info']=0;  // 完成金额
        $today=time(NULL);
        foreach ($month_date_money_list as $date=> &$item ) {
            $date_time=strtotime($date);
            if ($date_time<=$today) {
                $ret_info['formal_info']+=@$item["money"];
            }
        }

        // 计算电销人数
        $first_group  = '咨询一部';
        $second_group = '咨询二部';
        $third_group  = '咨询三部';
        $new_group    = '新人营';
        $ret_info['one_department']    = $task->t_admin_group_name->get_group_seller_num($first_group,$start_time);// 咨询一部
        $ret_info['two_department']    = $task->t_admin_group_name->get_group_seller_num($second_group, $start_time);// 咨询二部
        $ret_info['three_department']  = $task->t_admin_group_name->get_group_seller_num($third_group, $start_time);// 咨询三部
        $ret_info['new_department']    = $task->t_admin_group_name->get_group_seller_num($new_group, $start_time);// 新人营
        $ret_info['train_department']  = 0;// 培训中

        $ret_info['formal_num']    = $task->t_admin_group_name->get_entry_month_num($start_time,$end_time);// 入职完整月人数
        $ret_info['all_order_price'] = $task->t_admin_group_name->get_entry_total_price($start_time,$end_time);// 入职完整月人数签单总额

        // 金额转化率占比
        $ret_info['high_school_money'] = $task->t_order_info->get_high_money_for_month($start_time, $end_time);
        $ret_info['junior_money']      = $task->t_order_info->get_junior_money_for_month($start_time, $end_time);
        $ret_info['primary_money']     = $task->t_order_info->get_primary_money_for_month($start_time, $end_time);

        // 转化率
        $ret_info['seller_invit_num'] = $task->t_test_lesson_subject_require->get_invit_num($start_time, $end_time); // 试听邀约数
        $ret_info['seller_schedule_num'] = $task->t_test_lesson_subject_require->get_seller_schedule_num($start_time, $end_time); // 教务已排课
        $ret_info['test_succ_num'] = $task->t_lesson_info_b3->get_test_lesson_succ_num($start_time, $end_time); // 试听成功


        // $ret_info['has_tq_succ'] = $task->t_seller_student_new->get_tq_succ_num($start_time, $end_time); // 拨通电话数量
        $ret_info['has_tq_succ'] = $task->t_seller_student_new->get_tq_succ_num($start_time, $end_time); // 拨通电话数量

        //  外呼情况
        $ret_info['seller_call_num'] = $ret_info['has_called'] =  $task->t_tq_call_info->get_tq_succ_num($start_time, $end_time);//  呼出量
        $ret_info['has_called_stu'] = $task->t_tq_call_info->get_has_called_stu_num($start_time, $end_time); // 已拨打例子


        $ret_info['claim_num'] = $task->t_seller_student_new->get_claim_num($start_time, $end_time);//  认领量

        $ret_info['new_stu'] = $task->t_seller_student_new->get_new_stu_num($start_time, $end_time); // 本月新进例子数


        $ret_info['cc_called_num'] = $task->t_tq_call_info->get_cc_called_num($start_time, $end_time);// 拨打的cc量
        $ret_info['cc_call_time'] = $task->t_tq_call_info->get_cc_called_time($start_time, $end_time); // cc通话时长
        $ret_info['seller_invit_month'] = $task->t_test_lesson_subject_require->get_invit_num_for_month($start_time, $end_time); // 销售邀约数[月邀约数]
        $ret_info['has_tq_succ_invit_month']  = $task->t_seller_student_new->get_tq_succ_for_invit_month($start_time, $end_time); // 已拨通[月邀约数]

        // 新增
        $ret_info['seller_schedule_num_month'] = $task->t_test_lesson_subject_require->get_seller_schedule_num_month($start_time, $end_time); // 教务已排课[月排课率]

        $ret_info['seller_plan_invit_month'] = $task->t_test_lesson_subject_require->get_plan_invit_num_for_month($start_time, $end_time); // 试听邀约数[月排课率]





        $ret_info['seller_test_succ_month'] = $task->t_lesson_info_b3->get_test_succ_for_month($start_time, $end_time); // 试听成功数[月到课率]

        $ret_info['seller_schedule_num_has_done_month'] = $task->t_test_lesson_subject_require->get_seller_schedule_num($start_time, $end_time); // 试听排课[月到课率]


        $ret_info['order_trans_month'] = $task->t_order_info->get_order_trans_month($start_time, $end_time); // 合同人数[月试听转化率]

        $ret_info['has_tq_succ_sign_month'] = $task->t_seller_student_new->get_tq_succ_num_for_sign($start_time, $end_time); // 拨通电话数量[月签约率]
        $ret_info['order_sign_month'] = $task->t_order_info->get_order_sign_month($start_time, $end_time); // 合同人数[月签约率]


        $task->t_seller_tongji_for_month->row_insert($ret_info);


        // 更新漏斗型数据
        $task->t_seller_tongji_for_month->update_funnel_date($start_time, $ret_info['seller_invit_month'], $ret_info['has_tq_succ_invit_month'], $ret_info['seller_plan_invit_month'], $ret_info['seller_test_succ_month'], $ret_info['order_trans_month'], $ret_info['order_sign_month'], $ret_info['has_tq_succ_sign_month'], $ret_info['has_called_stu'],  $ret_info['seller_schedule_num_month'],$ret_info['seller_schedule_num_has_done_month'] );

    }




    public function get_month_finish_define_money($seller_groupid_ex,$start_time){
        $task = new \App\Console\Tasks\TaskController();
        $task->t_admin_main_group_name->switch_tongji_database();
        $task->t_admin_group_name->switch_tongji_database();
        $task->t_manager_info->switch_tongji_database();
        $task->t_seller_month_money_target->switch_tongji_database();
        $task->t_admin_group_month_time->switch_tongji_database();
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
            $up_groupid = $task->t_admin_main_group_name->get_groupid_by_group_name($up_group_name);
        }
        if (isset($arr[2])  && !empty($arr[2])){
            $group_name= $arr[2];
            $groupid = $task->t_admin_group_name->get_groupid_by_group_name($group_name);
        }
        if (isset($arr[3])  && !empty($arr[3])){
            $account= $arr[3];
            $adminid = $task->t_manager_info->get_id_by_account($account);
        }

        $month = date("Y-m-01",$start_time);
        $groupid_list = [];
        if($adminid){
            $month_finish_define_money=$task->t_seller_month_money_target->field_get_value_2( $adminid,$month,"personal_money");
        }else{
            if($groupid){
                $groupid_list[] = $groupid;
            }else{
                if($up_groupid){
                    $groupid_list = $task->t_admin_group_name->get_groupid_list_new($up_groupid,-1);
                }else{
                    if($main_type){
                        $groupid_list = $task->t_admin_group_name->get_groupid_list_new(-1,$main_type);
                    }
                }
            }
            $month_finish_define_money=$task->t_admin_group_month_time->get_month_money_by_month( $start_time,$groupid_list);
        }

        return $month_finish_define_money;
    }

}
