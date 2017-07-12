<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;
use App\Helper\Utils;

class teacher_limit_lesson_num_change extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:teacher_limit_lesson_num_change';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '老师周接课数变更通知';

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
        $date_week = \App\Helper\Utils::get_week_range(time(),1);
        $lstart = $date_week["sdate"];
        $lend = $date_week["edate"];
        $lstart_next = $date_week["sdate"]+7*86400;
        $lend_next = $date_week["edate"]+7*86400;

        $teacher_arr = $task->t_teacher_info->get_limit_lesson_num_change_list();
        // dd($teacher_arr);
        foreach($teacher_arr as $item){
            $teacherid = $item["teacherid"];
            $week_num= $item["limit_week_lesson_num"];
            $test_lesson_num = $task->t_lesson_info->get_limit_type_teacher_lesson_num($teacherid,$lstart,$lend);
            $test_lesson_num_next = $task->t_lesson_info->get_limit_type_teacher_lesson_num($teacherid,$lstart_next,$lend_next);
            //dd($test_lesson_num_next);
            if($test_lesson_num >= $week_num && $test_lesson_num_next >= $week_num){
                $task->t_manager_info->send_wx_todo_msg_by_adminid (72,"理优面试组","周试听课排课数量满额通知","Erick老师你好,".$item["realname"]."老师一周试听课限制排".$week_num."节,本周以及下周排课已满,请确认!","");
                $task->t_manager_info->send_wx_todo_msg_by_adminid (448,"理优面试组","周试听课排课数量满额通知","rolon老师你好,".$item["realname"]."老师一周试听课限制排".$week_num."节,本周以及下周排课已满,请确认!","");

 
            }
        }
       
        
               

    }
}
