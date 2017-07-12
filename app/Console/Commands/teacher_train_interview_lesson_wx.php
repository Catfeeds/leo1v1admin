<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class teacher_train_interview_lesson_wx extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:teacher_train_interview_lesson_wx';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '1对1面试结束1小时判断有无评价';

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
        $ret = $task->t_lesson_info_b2->get_train_lesson_intervie_no_assess_info();
        $time_str = date("Y-m-d H:i:s",time());
        foreach($ret as $val){
            $lessonid = $val["lessonid"];
            $userid = $val["userid"];
            $teacherid = $val["teacherid"];
            $subject_str = E\Esubject::get_desc($val["subject"]);
            $grade_str = E\Egrade::get_desc($val["grade"]);
            $lesson_start_str = date("Y-m-d H:i:s",$val["lesson_start"]);
            $lesson_time = date("Y-m-d",$val["lesson_start"]);
            $start_str = date("H:i",$val["lesson_start"]);
            $end_str = date("H:i",$val["lesson_end"]);
            $lesson_time_str = $lesson_time." ".$start_str."-".$end_str; 

            $t_login =$task->t_lesson_opt_log->get_opt_time_new($lessonid ,$teacherid,1);
            $t_logout =$task->t_lesson_opt_log->get_opt_time_new($lessonid ,$teacherid,2);
            $u_login =$task->t_lesson_opt_log->get_opt_time_new($lessonid ,$userid,1);
            $u_logout =$task->t_lesson_opt_log->get_opt_time_new($lessonid ,$userid,2);
            if(($t_login>0 && (empty($t_logout) || (($t_logout-$t_login) > 600))) && ($u_login>0 && (empty($u_logout) || (($u_logout-$u_login) > 600)))){
                $task->t_manager_info->send_wx_todo_msg_by_adminid ($val["uid"],"面试评审","您有一节面试还未审核,请尽快处理","
面试时间:".$lesson_time_str."
老师姓名:".$val["train_realname"]."
年级科目:".$grade_str."".$subject_str."
日期:".$time_str,"http://admin.yb1v1.com/tea_manage/train_lecture_lesson?lessonid=".$lessonid);
 
            }else{
                //老师未到,系统判断
                $task->t_teacher_record_list->row_insert([
                    "teacherid"          => $userid,
                    "trial_train_status" => 2,
                    "train_lessonid"     => $lessonid,
                    "add_time"           => time(),
                    "type"               => 10,
                    "current_acc"        => $val["account"],
                    "acc"                => $val["account"],
                    "phone_spare"        => $val["phone_spare"]
                ]);
                
            }
            $task->t_lesson_info->field_update_list($lessonid,[
                "train_lesson_wx_after"  =>1 
            ]);


        }
        
       
               

    }
}
