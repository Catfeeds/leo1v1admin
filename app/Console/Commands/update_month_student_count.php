<?php
namespace App\Console\Commands;
use \App\Enums as E;
class update_month_student_count extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_month_student_count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每月一日,早上4:30更新学生信息,保存本月及上月部分信息';




    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        /**  @var \App\Console\Tasks\TaskController  $task*/
        $task = new \App\Console\Tasks\TaskController();
        



        $start_time = strtotime( date('Y-m-01',time()) );
        $end_time   = strtotime( '+1 month',$start_time );
        $prev_start = strtotime( '-1 month',$start_time );
        $cur_month = [];
        $prev_month = [];
        //本月初付费学员数,订单数
        $all_pay = $task->t_student_info->get_student_list_for_finance_count();
        $cur_month['pay_stu_num'] = $all_pay['userid_count'];
        $cur_month['pay_order_num'] = $all_pay['orderid_count'];

        $user_order_list = $task->t_order_info->get_order_user_list_by_month($start_time);
        $new_user = [];//上月新签

        foreach ( $user_order_list as $item ) {
            if ($item['order_time'] >= $prev_start ){
                $new_user[] = $item['userid'];
                if (!$item['start_time'] && $item['assistantid'] > 0) {//新签订单,未排课,已分配助教
                    @$prev_month['has_ass_num']++;
                } else if (!$item['start_time'] && !$item['assistantid']) {//新签订单,未排课,未分配助教
                    @$prev_month['no_ass_num']++;
                }
            }

        }

        $new_user = array_unique($new_user);
        $prev_month['new_pay_stu_num'] = count($new_user);

        //上月退费名单
        $refund_info = $task->t_order_refund->get_refund_userid_by_month($prev_start,$start_time);
        $prev_month['refund_stu_num'] = $refund_info['userid_count'];
        $prev_month['refund_order_num'] = $refund_info['orderid_count'];
        //上月正常结课学生
        $ret_num = $task->t_student_info->get_user_list_by_lesson_count_new($prev_start,$start_time);
        $prev_month['normal_over_num'] = $ret_num;

        //上月 在读,停课,休学,假期数
        $ret_info = $task->t_student_info->get_student_count_archive();

        foreach($ret_info as $item) {
            if($item['type'] == 0) {
                @$prev_month['study_num']++;
            } else if ($item['type'] == 2) {
                @$prev_month['stop_num']++;
            } else if ($item['type'] == 3) {
                @$prev_month['drop_out_num']++;
            } else if ($item['type'] == 4) {
                @$prev_month['vacation_num']++;
            }
        }

        //上月月续费学员
        $renow_list = $task->t_order_info->get_renow_user_by_month($prev_start,$start_time);
        $renow_user = [];
        foreach ($renow_list as $item) {
            $renow_user[] = $item['userid'];
        }
        //上月预警学员
        $warning_list = $task->t_ass_weekly_info->get_warning_user_by_month($prev_start);
        $warning_renow_num = 0;

        foreach ($warning_list as $item){
            $new = json_decode($item['warning_student_list'], true);
            if(is_array($new)){
                foreach($new as $v) {
                    if( strlen($v)>0){
                        if( in_array($v ,$renow_user) ){
                            $warning_renow_num++;
                        }
                    }
                }
            }
        }
        $prev_month['warning_renow_stu_num']    = $warning_renow_num;
        $prev_month['no_warning_renow_stu_num'] = count($renow_user) - $warning_renow_num;

        //本月预警学员
        $warning_list = $task->t_ass_weekly_info->get_warning_user_by_month($start_time);
        $warning_stu_num = 0;
        foreach ($warning_list as $item){
            $new = json_decode($item['warning_student_list'], true);
            if(is_array($new)){
                foreach($new as $v) {
                    if( strlen($v)>0){
                        $warning_stu_num++;
                    }
                }
            }
        }

        $cur_month['warning_stu_num'] = $warning_stu_num;
        //上月课耗和上月课耗收入
        $lesson_money = $task->t_lesson_info_b3->get_lesson_count_money_info_by_month($prev_start,$start_time);
        $prev_month['lesson_count']       = $lesson_money['lesson_count'];
        $prev_month['lesson_count_money'] = $lesson_money['lesson_count_money'];
        $prev_month['lesson_stu_num']     = $lesson_money['lesson_stu_num'];

        $cur_month['create_time'] = $start_time;

        $id = $task->t_month_student_count->get_id_by_create_time($prev_start);
        $task->t_month_student_count->field_update_list($id,$prev_month);
        $task->t_month_student_count->row_insert($cur_month);

    }
}