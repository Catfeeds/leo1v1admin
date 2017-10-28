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
    protected $signature = 'command:import_to_teacher_flow';

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
        //按天导入数据 (脚本执行时间为每天凌晨二点)
        $time = strtotime("-1 day");
        $start_time = strtotime(date('Y-m-d 00:00:00', $time));
        $end_time = strtotime(date('Y-m-d 23:59:59', $time));

        $task = new \App\Console\Tasks\TaskController();

        $start_time  = strtotime("2017-1-1");
        $end_time = time();

        // $tea_list = $task->t_teacher_info->get_teacher_flow_list($start_time, $end_time);
        // if(!empty($tea_list)){
        //     foreach($tea_list as $val){
        //         $task->t_teacher_flow->row_insert_ignore([
        //             "teacherid"              => $val['teacherid'],
        //             "phone"                  => $val['phone'],
        //             "train_through_new_time" => $val['train_through_new_time'],
        //         ]);
        //     }
        // }

        // 导入老师报名时间 accept_adminid招师专员的id
        $where = ["answer_begin_time=0"];
        $info = $task->t_teacher_flow->get_all_list($where);
        foreach($info as $teacherid => $item) {
            $info = $task->t_teacher_lecture_appointment_info->get_data_to_teacher_flow($info['phone']);
            dd($info);
            $task->t_teacher_flow->field_update_list($teacherid, [
                'answer_begin_time' => $info['answer_begin_time'],
                "accept_adminid" => $info['accept_adminid']
            ]);
        }
        exit;
        $tea_phone = implode(",",$tea_phone_list);
        dd($tea_phone);

        $info = $task->t_teacher_lecture_appointment_info->get_data_to_teacher_flow($start_time, $end_time,$tea_phone);
        foreach($info as $item) {
            $teacherid = $task->t_teacher_flow->get_id_for_phone($item['phone']);
            if ($teacherid) {
                $task->t_teacher_flow->field_update_list($teacherid, [
                    'answer_begin_time' => $item['answer_begin_time'],
                    "accept_adminid" => $item['accept_adminid']
                ]);
            }
        }

        // $where = ["trial_lecture_pass_time=0"];
        // $info = $task->t_teacher_flow->get_all_list($where);
        // foreach($info as $item) {
        //     if ($item['teacherid']) {
        //         $lecture = $task->t_teacher_lecture_info->get_data_to_teacher_flow($start_time, $end_time, $item['phone']);
                
        //         if ($lecture) {
        //             $task->t_teacher_flow->field_update_list($item['teacherid'], [
        //                 "trial_lecture_pass_time" => $lecture['confirm_time'],
        //                 'subject' => $lecture['subject'],
        //                 'grade' => $lecture['grade']
        //             ]);
        //         }
        //     }
        // }

        // // 录制试讲
        // $where = ["trial_lecture_pass_time=0"];
        // $info = $task->t_teacher_flow->get_all_list($where);
        // foreach($info as $item) {
        //     if ($item['teacherid']) {
        //         $lecture = $task->t_teacher_record_list->get_data_to_teacher_flow($start_time, $end_time, 5, $item['teacherid']);
        //         if ($lecture) {
        //             $task->t_teacher_flow->field_update_list($item['teacherid'], [
        //                 "trial_lecture_pass_time" => $lecture['add_time'],
        //                 'subject' => $lecture['subject'],
        //                 'grade' => $lecture['grade']
        //             ]);
        //         }

        //     }
        // }

        // // trial_lecture_pass_time通过试讲时间subject通过试讲科目grade通过试讲年级
        // $lecture = $task->t_teacher_lecture_info->get_data_to_teacher_flow($start_time, $end_time);
        // foreach($lecture as $item) {
        //     $teacherid = $task->t_teacher_flow->get_id_for_phone($item['phone']);
        //     if ($teacherid) {
        //         $task->t_teacher_flow->field_update_list($teacherid, [
        //             "trial_lecture_pass_time" => $item['confirm_time'],
        //             'subject' => $item['subject'],
        //             'grade' => $item['grade']
        //         ]);
        //     }
        // }

        // $info = $task->t_teacher_record_list->get_data_to_teacher_flow($start_time, $end_time, 5);
        // foreach($info as $item) {
        //     $time = $task->t_teacher_flow->get_trial_lecture_pass_time($item['phone']);
        //     if ($item['add_time']>$time) {
        //         // if ($time > $item['add_time']) {
        //         //     $task->t_teacher_flow->field_update_list($item['id'],[
        //         //         'trial_lecture_pass_time' => $time
        //         //     ]);
        //         // }
        //         $task->t_teacher_flow->field_update_list($item['teacherid'],[
        //             'trial_lecture_pass_time' => $item['add_time'],
        //             'subject' => $item['subject'],
        //             'grade' => $item['grade'],
        //         ]);
        //     }
        // }

        //simul_test_lesson_pass_time模拟试听通过时间
        // $info = $task->t_teacher_record_list->get_data_to_teacher_flow($start_time, $end_time, 4);
        // foreach($info as $item) {
        //     // $teacherid = $task->t_teacher_flow->get_id_for_phone($item['phone']);
        //     $task->t_teacher_flow->field_update_list($item['teacherid'],[
        //         "simul_test_lesson_pass_time" => $item['add_time']
        //     ]);
        // }

        $where = ['simul_test_lesson_pass_time=0'];
        $info = $task->t_teacher_flow->get_all_list($where);
        foreach($info as $item) {
            if ($item['teacherid']) {
                $lecture = $task->t_teacher_record_list->get_data_to_teacher_flow($start_time, $end_time, 4, $item['teacherid']);
                if ($lecture) {
                    $task->t_teacher_flow->field_update_list($item['teacherid'], [
                        "simul_test_lesson_pass_time" => $lecture['add_time'],
                    ]);
                }

            }
        }
    }
}
