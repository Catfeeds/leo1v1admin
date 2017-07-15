<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class ass_wx_remind_send_day extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ass_wx_remind_send_day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '助教微信提醒学生复课';

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
        $time = strtotime(date("Y-m-d",time()));
        $ret = $task->t_student_info->get_stu_wx_remind_info($time);

        foreach($ret as $val){
            $recover_time = date("Y-m-d",$val["recover_time"]);
            $task->t_manager_info->send_wx_todo_msg_by_adminid ($val["uid"],"复课提醒","学生复课提醒",$val["name"]."老师,你好,学生:".$val["nick"]."将于".$recover_time."复课,请知悉!","");
            


        }
        
        
       
               

    }
}
