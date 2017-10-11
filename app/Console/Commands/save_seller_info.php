<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Http\Controllers\Controller;


class save_seller_info extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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

        $start_time = strtotime(date('Y-m-01'));
        $end_time = strtotime(date('Y-m-d 0:0:0'))+86400;

        $ret_info['create_time'] = time();

        $ret_info['from_time'] = $start_time;



        $order_info_total = $task->t_order_info->get_total_money($start_time, $end_time);// 总收入

        $referral_order = $task->t_order_info->get_referral_income($start_time, $end_time); //  转介绍

        $ret_info['income_referral'] = $referral_order['referral_price']; // 转介绍收入
        $ret_info['income_new']   = $order_info_total['total_price'] - $referral_order['referral_price']; //  新签
        $ret_info['income_price'] = $order_info_total['total_price'];
        $ret_info['income_num']   = $order_info_total['total_num']; // 有签单的销售人数


        // if($order_info_total['total_num']>0){
        //     $ret_info['aver_count'] = $order_info_total['total_price']/$order_info_total['total_num'];//平均单笔
        // }else{
        //     $ret_info['aver_count'] = 0; //平均单笔
        // }

        $job_info = $task->t_order_info->get_formal_order_info($start_time,$end_time); // 入职完整月人员签单额
        $ret_info['formal_info'] = $job_info['job_price']; // 入职完整月人员签单额
        $ret_info['formal_num']  = $job_info['job_num']; // 入职完整月人员人数

        // if($ret_info['formal_num']>0){
        //     $ret_info['aver_money'] = $ret_info['formal_info']/$ret_info['formal_num']; //平均人效
        // }else{
        //     $ret_info['aver_money'] = 0;
        // }

        // dd($ret_info);
        $seller_groupid_ex = $task->get_in_str_val('seller_groupid_ex', "");
        $adminid_list = $task->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);

        // $main_type = 2;// 销售
        $ret_info['seller_target_income'] = (new tongji_ss())->get_month_finish_define_money(0,$start_time); // 销售月目标收入
        if (!$ret_info['seller_target_income'] ) {
            $ret_info['seller_target_income'] = 1600000;
        }

        $month_finish_define_money_2=$ret_info['seller_target_income']/100;
        $month_start_time = strtotime( date("Y-m-01",  $start_time));
        $month_end_time   = strtotime(date("Y-m-01",  ($month_start_time+86400*32)));
        $month_date_money_list = $task->t_order_info->get_seller_date_money_list($month_start_time,$month_end_time,$adminid_list);
        $ret_info['cur_money']=0;
        $today=time(NULL);
        foreach ($month_date_money_list as $date=> &$item ) {
            $date_time=strtotime($date);
            if ($date_time<=$today) {
                $ret_info['cur_money']+=@$item["money"];
            }
        }
        $ret_info['month_finish_persent'] = $ret_info['cur_money']/$ret_info['seller_target_income'];//月kpi完成率
        $ret_info['month_left_money'] = $ret_info['seller_target_income'] - $ret_info['cur_money'];//

        if($ret_info['seller_target_income']>0){
            $ret_info['seller_kpi'] = $ret_info['income_price']/$ret_info['seller_target_income']*100;
        }else{
            $ret_info['seller_kpi'] = 0;
        }

        // 计算电销人数
        $first_group  = '咨询一部';
        $second_group = '咨询二部';
        $third_group  = '咨询三部';
        $new_group    = '新人营';
        $ret_info['first_num']  = $seller_num_arr['first_num']  = $task->t_admin_group_name->get_group_seller_num($first_group);// 咨询一部
        $ret_info['second_num'] = $seller_num_arr['second_num'] = $task->t_admin_group_name->get_group_seller_num($second_group);// 咨询二部
        $ret_info['third_num']  = $seller_num_arr['third_num']  = $task->t_admin_group_name->get_group_seller_num($third_group);// 咨询三部
        $ret_info['new_num']    = $seller_num_arr['new_num']    = $task->t_admin_group_name->get_group_new_count($new_group);// 新人营
        $ret_info['traing_num'] = $seller_num_arr['traing_num'] = '';// 培训中
        $ret_info['seller_num'] = $ret_info['first_num']+$ret_info['second_num']+$ret_info['third_num']+$ret_info['new_num'];// 咨询一部+咨询二部+咨询三部+新人营
        $ret_info['department_num_info'] = json_encode($seller_num_arr);



        // 金额转化率占比
        $ret_info['high_school_money'] = $task->t_order_info->get_high_money_for_month($start_time, $end_time);
        $ret_info['junior_money']      = $task->t_order_info->get_junior_money_for_month($start_time, $end_time);
        $ret_info['primary_money']     = $task->t_order_info->get_primary_money_for_month($start_time, $end_time);

        if($ret_info['income_price']>0){
            $ret_info['referral_money_rate'] = $ret_info['income_referral']/$ret_info['income_price']*100;
            $ret_info['high_school_money_rate']   =  $ret_info['high_school_money']/$ret_info['income_price']*100;
            $ret_info['junior_money_rate']  = $ret_info['junior_money']/$ret_info['income_price']*100;
            $ret_info['primary_money_rate'] = $ret_info['primary_money']/$ret_info['income_price']*100;
        }else{
            $ret_info['referral_money_rate']    = 0;
            $ret_info['high_school_money_rate'] = 0;
            $ret_info['junior_money_rate']      = 0;
            $ret_info['primary_money_rate']     = 0;
        }

        // 转化率
        $ret_info['seller_invit_num'] = $task->t_test_lesson_subject_require->get_invit_num($start_time, $end_time); // 销售邀约数
        $ret_info['seller_schedule_num'] = $task->t_test_lesson_subject_require->get_seller_schedule_num($start_time, $end_time); // 教务已排课
        $ret_info['test_lesson_succ_num'] = $task->t_lesson_info_b3->get_test_lesson_succ_num($start_time, $end_time); // 试听成功
        $ret_info['new_order_num'] = $order_info_total['total_num']; // 合同数量



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

        $ret_info['seller_plan_invit_month'] = $task->t_test_lesson_subject_require->get_plan_invit_num_for_month($start_time, $end_time); // 试听邀约数[月排课率]
        $ret_info['seller_test_succ_month'] = $task->t_lesson_info_b3->get_test_succ_for_month($start_time, $end_time); // 试听成功数[月到课率]
        $ret_info['order_trans_month'] = $task->t_order_info->get_order_trans_month($start_time, $end_time); // 合同人数[月试听转化率]

        $ret_info['has_tq_succ_sign_month'] = $task->t_seller_student_new->get_tq_succ_num_for_sign($start_time, $end_time); // 拨通电话数量[月签约率]
        $ret_info['order_sign_month'] = $task->t_order_info->get_order_sign_month($start_time, $end_time); // 合同人数[月签约率]

        $ret_info['un_consumed'] = $ret_info['new_stu']-$ret_info['has_called_stu']; // 未消耗例子数



        if($ret_info['has_tq_succ_invit_month_funnel']>0){ //月邀约率
            $ret_info['invit_month_rate'] = $ret_info['seller_invit_month']/$ret_info['has_tq_succ_invit_month_funnel']*100;
        }else{
            $ret_info['invit_month_rate'] = 0;
        }


        if($ret_info['seller_plan_invit_month_funnel']>0){ //月排课率
            $ret_info['test_plan_month_rate'] = $ret_info['seller_schedule_num']/$ret_info['seller_plan_invit_month_funnel']*100;
        }else{
            $ret_info['test_plan_month_rate'] = 0;
        }

        if($ret_info['seller_schedule_num']>0){ //月到课率
            $ret_info['lesson_succ_month_rate'] = $ret_info['seller_test_succ_month_funnel']/$ret_info['seller_schedule_num']*100;
        }else{
            $ret_info['lesson_succ_month_rate'] = 0;
        }


        if($ret_info['seller_test_succ_month_funnel']>0){ //月试听转化率
            $ret_info['trans_month_rate'] = $ret_info['order_trans_month']/$ret_info['seller_test_succ_month_funnel']*100;
        }else{
            $ret_info['trans_month_rate'] = 0;
        }


        if($ret_info['has_tq_succ_sign_month']>0){ //月签约率
            $ret_info['sign_month_rate'] = $ret_info['order_sign_month']/$ret_info['has_tq_succ_sign_month']*100;
        }else{
            $ret_info['sign_month_rate'] = 0;
        }

        if($ret_info['has_called']>0){
            $ret_info['succ_called_rate'] = $ret_info['has_tq_succ']/$ret_info['has_called']*100; //接通率
            $ret_info['claim_num_rate'] = $ret_info['claim_num']/$ret_info['has_called']*100; //认领率
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





        $task->t_seller_tongji_for_month->row_insert($ret_info);
    }
}
