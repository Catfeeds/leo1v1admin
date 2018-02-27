<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class fulltime_teacher_interview_info extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fulltime_teacher_interview_info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '全职老师面试信息推送';

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
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();
        $start_time = strtotime(date("Y-m-d",time()));
        $end_time=time();  
        $all_num  = $task->t_teacher_lecture_appointment_info->get_all_full_time_num($start_time,$end_time); 
        $lesson_all = $task->t_lesson_info_b2->get_fulltime_teacher_interview_info($start_time,$end_time);
        $interview_num = $task->t_teacher_record_list->get_fulltime_teacher_interview_info($start_time,$end_time,-2);
        $pass_num = $task->t_teacher_record_list->get_fulltime_teacher_interview_info($start_time,$end_time,1);

        $admin_list = [349,480,986,1043,1171,1453,1446];
        //$admin_list = [349];
        foreach($admin_list as $yy){
            $task->t_manager_info->send_wx_todo_msg_by_adminid ($yy,"全职老师面试信息","全职老师面试信息","\n当日注册人数:".$all_num."\n邀请成功人数:".$lesson_all."\n一面面试人数:".$interview_num."\n一面通过人数:".$pass_num,"");
        }



    }
}
