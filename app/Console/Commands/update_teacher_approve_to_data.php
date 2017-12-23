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
        $start_time = strtotime(date('Y-m-d 00:00:00',strtotime('-1 day')));
        $end_time = strtotime(date('Y-m-d 00:00:00', time()));
        $month = [9, 10, 11];
        foreach ($month as $item) {
            $start_time = strtotime('2017-'.$item.'-1');
            $end_time = strtotime('2017-'.($item + 1).'-1');
            $ret_info = $task->t_lesson_info_b3->get_tea_lesson_info_for_approved($start_time, $end_time);

            foreach($ret_info as $item){
                $cc_order_num = $task->t_order_info->get_cc_test_lesson_num($start_time, $end_time, $item['teacherid'],'2');
                $cc_lesson_num = $task->t_order_info->get_cc_lesson_num($start_time, $end_time, $item['teacherid'], '2');
                $cr_order_num  = $task->t_order_info->get_cc_test_lesson_num($start_time, $end_time, $item['teacherid'],'1');
                $cr_lesson_num = $task->t_order_info->get_cc_lesson_num($start_time, $end_time, $item['teacherid'], '1');
                $violation_info = $task->t_lesson_info_b3->get_violation_num($start_time, $end_time, $item['teacherid']);
                $violation_num = array_sum($violation_info);
                var_dump($item);
                echo 'cc_order_num:'.$cc_order_num;
                echo 'cc_lesson_num:'.$cc_lesson_num;
                echo 'cr_order_num:'.$cr_order_num;
                echo 'cr_lesson_num:'.$cr_lesson_num;
                echo 'violation_num:'.$violation_num;
                exit;

                $task->t_teacher_approve_refer_to_data->row_insert([
                    'teacherid' => $item['teacherid'],
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
        exit;
        $ret_info = $task->t_lesson_info_b3->get_tea_lesson_info_for_approved($start_time, $end_time);
        dd($ret_info);

        foreach($ret_info as $item){
            $cc_order_num = $task->t_order_info->get_cc_test_lesson_num($start_time, $end_time, $item['teacherid'],'2');
            $cc_lesson_num = $task->t_order_info->get_cc_lesson_num($start_time, $end_time, $item['teacherid'], '2');
            $cr_order_num  = $task->t_order_info->get_cc_test_lesson_num($start_time, $end_time, $item['teacherid'],'1');
            $cr_lesson_num = $task->t_order_info->get_cc_lesson_num($start_time, $end_time, $item['teacherid'], '1');
            $violation_info = $task->t_lesson_info_b3->get_violation_num($start_time, $end_time, $item['teacherid']);
            $item['violation_num'] = array_sum($violation_info);

            $task->t_teacher_approve_refer_to_data->row_insert([
                'teacherid' => $item['teacherid'],
                'stu_num' => $item['stu_num'],
                'total_lesson_num' => $item['total_lesson_num'],
                'cc_order_num' => $cc_order_num,
                'cc_lesson_num' => $cc_lesson_num,
                'cr_order_num' => $cr_order_num,
                'cr_lesson_num' => $cr_lesson_num,
                'violation_num' => $violation_info,
                'add_time' => $start_time
            ]);
        }

    }
}
