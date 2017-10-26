<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class import_to_teacher_flow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:import_to_teacher_flow {month}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '向表t_teacher_flow中导入数据';

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
        //
        $month = $this->argument('month');
        if ($month > 12 || $month < 1) exit("你输入的月份不对");
        if ($month < 10) {
            $start_time = date("Y-0{$month}-01 00:00:00");
        } else {
            $start_time = date("Y-{$month}-01 00:00:00");
        }
        $end_time = date('Y-m-d', strtotime("$start_time +1 month -1 day"));
        //echo 'start_time:'.$start_time.'end_time:'.$end_time;

        $task = new \App\Console\Tasks\TaskController();
        $start_time = strtotime($start_time);
        $end_time = strtotime($end_time);
        // 导入老师报名时间 accept_adminid招师专员的id
        $info = $task->t_teacher_lecture_appointment_info->get_data_to_teacher_flow($start_time, $end_time);
        foreach($info as $item) {
            $teacherid = $task->t_teacher_flow->get_teacherid_for_phone($item['phone']);
            if (!$teacherid) {
                $task->t_teacher_flow->row_insert([
                    'teacherid' => $item['id'],
                    'phone' => $item['phone'],
                    'answer_begin_time' => $item['answer_begin_time'],
                    "accept_adminid" => $item['accept_adminid']
                ]);
            }
        }
        //trial_lecture_pass_time通过试讲时间subject通过试讲科目grade通过试讲年级
        $lecture = $task->t_teacher_lecture_info->get_data_to_teacher_flow($start_time, $end_time);
        foreach($lecture as $item) {
            $teacherid = $task->t_teacher_flow->get_teacherid_for_phone($item['phone']);
            if ($teacherid) {
                $task->t_teacher_flow->field_update_list($teacherid, [
                    "trial_lecture_pass_time" => $item['confirm_time'],
                    'subject' => $item['subject'],
                    'grade' => $item['grade']
                ]);
            }
        }
        $info = $task->t_teacher_record_list->get_data_to_teacher_flow($start_time, $end_time, 5);
        foreach($info as $item) {
            $time = $task->t_teacher_flow->get_trial_lecture_pass_time($item['teacherid']);
            if ($time) {
                if ($res > $item['add_time']) {
                    $task->t_teacher_flow->field_update_list($item['teacherid'],[
                        'trial_lecture_pass_time' => $res
                    ]);
                }
            } else {            
                $task->t_teacher_flow->field_update_list($item['teacherid'],[
                    'trial_lecture_pass_time' => $item['add_time'],
                    'subject' => $item['subject'],
                    'grade' => $item['grade']
                ]);
            }
        }
        //train_pass_time培训通过时间
        $info = $task->t_teacher_info->get_data_to_teacher_flow($start_time, $end_time);
        foreach($info as $item) {
            $task->t_teacher_flow->field_update_list($item['teacherid'],[
                "train_pass_time" => $item['train_through_new_time']
            ]);
        }
        //simul_test_lesson_pass_time模拟试听通过时间
        $info = $task->t_teacher_record_list->get_data_to_teacher_flow($start_time, $end_time, 4);
        foreach($info as $item) {
            $task->t_teacher_flow->field_update_list($item['teacherid'],[
                "simul_test_lesson_pass_time" => $item['add_time']
            ]);
        }
    }
}
