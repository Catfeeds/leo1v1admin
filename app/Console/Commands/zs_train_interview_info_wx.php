<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class zs_train_interview_info_wx extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:zs_train_interview_info_wx';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '面试老师未进课堂或版本需要更新推送招师';

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
        $ret = $task->t_lesson_info_b2->get_train_intervie_lessoning_info();
        $time_str = date("Y-m-d H:i:s",time());
        foreach($ret as $val){
            $lessonid = $val["lessonid"];
            $userid = $val["userid"];
            $subject_str = E\Esubject::get_desc($val["subject"]);
            $grade_str = E\Egrade::get_desc($val["grade"]);
            $lesson_start_str = date("Y-m-d H:i:s",$val["lesson_start"]);
            $lesson_time = date("Y-m-d",$val["lesson_start"]);
            $start_str = date("H:i",$val["lesson_start"]);
            $end_str = date("H:i",$val["lesson_end"]);
            $lesson_time_str = $lesson_time." ".$start_str."-".$end_str; 

            $t_login =$task->t_lesson_opt_log->get_opt_time_new($lessonid ,$userid,1);
            if(empty($t_login) && $val["accept_adminid"]>0){
                $task->t_manager_info->send_wx_todo_msg_by_adminid ($val["accept_adminid"],"进入课堂提醒","有一位老师未进入课堂","
面试时间:".$lesson_time_str."
老师姓名:".$val["train_realname"]."
联系电话:".$val["phone_spare"]."
年级科目:".$grade_str."".$subject_str."
日期:".$time_str,"http://admin.yb1v1.com/tea_manage/train_lecture_lesson?lesson_status=-1&lessonid=".$lessonid);
            }


        }


        $list = $task->t_lesson_info_b2->get_train_intervie_user_agent_info();
        foreach($list as $v){
            $user_agent = json_decode($v["user_agent"],true);
            $agent = $user_agent["device_model"]." ".$user_agent["version"];
            $task->t_manager_info->send_wx_todo_msg_by_adminid ($v["accept_adminid"],"更新提示",$v["realname"]."老师的软件需要更新","当前版本为".$agent,"");
            $task->t_teacher_info->field_update_list($v["teacherid"],[
               "user_agent_wx_update" =>1 
            ]);

        }
        
       
               

    }
}
