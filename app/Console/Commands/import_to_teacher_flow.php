<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;
class import_to_teacher_flow extends cmd_base
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
        $task = new \App\Console\Tasks\TaskController();
        //按天导入数据 (脚本执行时间为每天凌晨二点)
        $time = strtotime("-1 day");
        $start_time = strtotime(date('Y-m-d 00:00:00', $time));
        $start_time = strtotime('2017-1-1')
        $end_time = time();

        $tea_list = $task->t_teacher_info->get_teacher_flow_list($start_time, $end_time);
        if(!empty($tea_list)){
            foreach($tea_list as $val){
                $task->t_teacher_flow->row_insert_ignore([
                    "teacherid"              => $val['teacherid'],
                    "phone"                  => $val['phone'],
                    "train_through_new_time" => $val['train_through_new_time'],
                ]);
            }
        }

        // 刷培训合格时间
        $where = ["train_through_new_time=0"];
        $info = $task->t_teacher_flow->get_all_list($where);
        foreach($info as $teacherid => $item) {
            $ret = $task->t_teacher_info->get_train_through_new_time($teacherid);
            if ($ret) {
                $task->t_teacher_flow->field_update_list($teacherid, [
                    'train_through_new_time' => $ret
                ]);
            }
        }

        // 导入老师报名时间 accept_adminid招师专员的id
        $where = ["answer_begin_time=0"];
        $info = $task->t_teacher_flow->get_all_list($where);
        foreach($info as $teacherid => $item) {
            $ret = $task->t_teacher_lecture_appointment_info->get_data_to_teacher_flow($item['phone']);
            if ($ret) {
                $task->t_teacher_flow->field_update_list($teacherid, [
                    'answer_begin_time' => $ret['answer_begin_time'],
                    "accept_adminid" => $ret['accept_adminid']
                ]);
            } 
        }

        //面试通过时间
        $lecture = $task->t_teacher_lecture_info->get_data_to_teacher_flow($start_time,$end_time);

        $where = ["trial_lecture_pass_time=0"];
        $info = $task->t_teacher_flow->get_all_list($where);
        foreach($info as $teacherid => $item) {
            if (!isset($lecture[$item['phone']])) continue;
            $task->t_teacher_flow->field_update_list($teacherid, [
                "trial_lecture_pass_time" => $lecture[$item['phone']]['confirm_time'],
                "subject" => $lecture[$item['phone']]['subject'],
                "grade" => $lecture[$item['phone']]['grade']
            ]);
        }

        //面试试讲通过时间
        $lecture = $task->t_teacher_record_list->get_data_to_teacher_flow($start_time, $end_time, E\Etrain_type::V_5);

        // $where = ['trial_lecture_pas=0'];
        $info = $task->t_teacher_flow->get_all_list($where);
        foreach($info as $teacherid => $item) {
            if (!isset($lecture[$item['teacherid']])) continue;
            if ($lecture[$item['teacherid']]['add_time'] < $item['trial_lecture_pass_time'] || $item['trial_lecture_pass_time'] == 0) {
                $task->t_teacher_flow->field_update_list($teacherid,[
                    "trial_lecture_pass_time" => $lecture[$item['teacherid']]['add_time'],
                    //"subject" => $lecture[$item['teacherid']]['subject'],
                    //'grade' => $lecture[$item['teacherid']]['grade']
                ]);
            }
        }

        // 模拟试听通过时间
        $where = ['simul_test_lesson_pass_time=0'];
        $info  = $task->t_teacher_flow->get_all_list($where);
        foreach($info as $teacherid => $item) {
            $lecture = $task->t_teacher_record_list->get_data_to_teacher_flow_id(E\Etrain_type::V_4, $teacherid);
            // if ($lecture && ($lecture['add_time'] > $item['trial_lecture_pass_time'] )) {
            $task->t_teacher_flow->field_update_list($teacherid, [
                "simul_test_lesson_pass_time" => $lecture['add_time'],
            ]);
            // }
        }

        // 刷科目年级
        $where = ['grade=0'];
        $info = $task->t_teacher_flow->get_all_list($where);
        foreach($info as $teacherid => $item) {
            $subject = $task->t_lesson_info->get_subject_for_teacherid($teacherid);
            if ($subject) {
                $task->t_teacher_flow->field_update_list($teacherid,[
                    "subject" => $subject['subject'],
                    "grade" => $subject['grade']
                ]);
            }
        }

    }
}
