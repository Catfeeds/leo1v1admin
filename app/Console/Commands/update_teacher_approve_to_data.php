<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class update_teacher_approve_to_data extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_teacher_approve_to_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新老师晋升参考数据';

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
        $task = new \App\Console\Tasks\TaskController();
        $start_time = strtotime(date('Y-m-1', time()));
        $end_time = time();
        $ret_info = $task->t_lesson_info_b3->get_tea_lesson_info_for_approved($start_time, $end_time);

        foreach($ret_info as $item){
            $teacherid = $item['teacherid'];
            $cc_order_num = $task->t_order_info->get_cc_test_lesson_num($start_time, $end_time, $teacherid,'2');
            $cc_lesson_num = $task->t_order_info->get_cc_lesson_num($start_time, $end_time, $teacherid, '2');
            $cr_order_num  = $task->t_order_info->get_cc_test_lesson_num($start_time, $end_time, $teacherid,'1');
            $cr_lesson_num = $task->t_order_info->get_cc_lesson_num($start_time, $end_time, $teacherid, '1');
            $violation_info = $task->t_lesson_info_b3->get_violation_num($start_time, $end_time, $teacherid);
            $violation_num = array_sum($violation_info);

            $id = $task->t_teacher_approve_refer_to_data->get_id_for_teacherid($start_time, $end_time, $teacherid);
            echo $id;

            if ($id) {
                echo '调用更新';
                $task->t_teacher_approve_refer_to_data->field_update_list($id, [
                    'stu_num' => $item['stu_num'],
                    'total_lesson_num' => $item['total_lesson_num'],
                    'cc_order_num' => $cc_order_num,
                    'cc_lesson_num' => $cc_lesson_num,
                    'cr_order_num' => $cr_order_num,
                    'cr_lesson_num' => $cr_lesson_num,
                    'violation_num' => $violation_num,
                ]);
            } else {
                echo '调用添加';
                $task->t_teacher_approve_refer_to_data->row_insert([
                    'teacherid' => $teacherid,
                    'stu_num' => $item['stu_num'],
                    'total_lesson_num' => $item['total_lesson_num'],
                    'cc_order_num' => $cc_order_num,
                    'cc_lesson_num' => $cc_lesson_num,
                    'cr_order_num' => $cr_order_num,
                    'cr_lesson_num' => $cr_lesson_num,
                    'violation_num' => $violation_num,
                    'add_time' => $start_time
                ]);

            }
        }

    }
}
