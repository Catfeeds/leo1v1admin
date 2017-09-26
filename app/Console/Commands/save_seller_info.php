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

        $ret_info['from_time'] = '';

        $ret_info['income_new']      = $task->t_order_info->get_new_income($start_time, $end_time); //  新签
        $ret_info['income_referral'] = $task->t_order_info->get_referral_income($start_time, $end_time); //  转介绍

        $ret_info['income_price'] = $ret_info['income_new']['all_price']+$ret_info['income_referral']['all_price'];
        $ret_info['income_count'] = $ret_info['income_new']['all_count']+$ret_info['income_referral']['all_count'];


        $ret_info['income_num']  = $task->t_order_info->get_income_num($start_time, $end_time); // 有签单的销售人数

        $ret_info['formal_info'] = $task->t_order_info->get_formal_order_info($start_time,$end_time); // 入职完整月人员签单额

        $ret_info['formal_num']  = $task->t_manager_info->get_formal_num($start_time, $end_time); // 入职完整月人员人数

        $total_price = 0;

        $month = date('Y-m-01');
        $main_type = 2;// 销售
        $ret_info['seller_target_income'] = $task->t_admin_group_month_time->get_all_target($month, $main_type); // 销售月目标


        // 计算电销人数
        $first_group  = '咨询一部';
        $second_group = '咨询二部';
        $third_group  = '咨询三部';
        $new_group    = '新人营';
        $ret_info['first_num']  = $task->t_admin_group_name->get_group_seller_num($first_group);// 咨询一部
        $ret_info['second_num'] = $task->t_admin_group_name->get_group_seller_num($second_group);// 咨询二部
        $ret_info['third_num']  = $task->t_admin_group_name->get_group_seller_num($third_group);// 咨询三部
        $ret_info['new_num']    = $task->t_admin_group_name->get_group_new_count($new_group);// 新人营
        $ret_info['traing_num'] = '';// 培训中
        $ret_info['seller_num'] = $ret_info['first_num']+$ret_info['second_num']+$ret_info['third_num']+$ret_info['new_num'];// 咨询一部+咨询二部+咨询三部+新人营


        $seller_num_arr['first_num']  = $ret_info['first_num'];
        $seller_num_arr['second_num'] = $ret_info['second_num'];
        $seller_num_arr['third_num']  = $ret_info['third_num'];
        $seller_num_arr['new_num']    = $ret_info['new_num'];
        $seller_num_arr['traing_num'] = $ret_info['traing_num'];

        $ret_info['department_num_info'] = json_encode($seller_num_arr);

        // 金额转化率占比
        $ret_info['referral_money'] = $task->t_order_info->get_referral_money_for_month($start_time, $end_time);
        $ret_info['high_school_money'] = $task->t_order_info->get_high_money_for_month($start_time, $end_time);
        $ret_info['junior_money']      = $task->t_order_info->get_junior_money_for_month($start_time, $end_time);
        $ret_info['primary_money']     = $task->t_order_info->get_primary_money_for_month($start_time, $end_time);


        // 月邀请率
        // 合同人数
        $ret_info['seller_order_num'] = $task->t_order_info->get_order_num($start_time, $end_time);

        // 转化率
        $ret_info['seller_invit_num'] = $task->t_tongji_seller_top_info->get_invit_num($start_time); // 销售邀约数

        $ret_info['seller_schedule_num'] = $task->t_test_lesson_subject_sub_list->get_seller_schedule_num($start_time); // 教务已排课

        $ret_info['test_lesson_succ_num'] = $task->t_lesson_info_b3->get_test_lesson_succ_num($start_time, $end_time); // 试听成功

        $ret_info['new_order_num'] = $task->t_order_info->get_new_order_num($start_time, $end_time); // 新签合同

        $ret_info['has_tq_succ'] = $task->t_seller_student_new->get_tq_succ_num($start_time, $end_time); // 拨通电话数量


        //  外呼情况
        $ret_info['seller_call_num'] = $task->t_tq_call_info->get_tq_succ_num($start_time, $end_time);//  呼出量

        $ret_info['claim_num'] = $task->t_seller_student_new->get_claim_num($start_time, $end_time);//  认领量

        $ret_info['has_called'] = $task->t_seller_student_new->get_called_num($start_time, $end_time); // 已拨打

        $ret_info['new_stu'] = $task->t_seller_student_new->get_new_stu_num($start_time, $end_time); // 本月新进例子数

        $ret_info['cc_called_num'] = $task->t_tq_call_info->get_cc_called_num($start_time, $end_time);// 拨打的cc量

        $ret_info['cc_call_time'] = $task->t_tq_call_info->get_cc_called_time($start_time, $end_time); // cc通话时长

        $task->t_seller_tongji_for_month->row_insert($ret_info);
    }
}
