<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class fulltime_teacher_lesson_count_send_wx extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fulltime_teacher_lesson_count_send_wx';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '全职老师每周排课信息推送';

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
        $end_time = time();
        $start_time = $end_time-7*86400;
        $month_start = strtotime(date("Y-m-01",time()));
        $ret_info  = $task->t_manager_info->get_research_teacher_list_new(5);
        $qz_tea_arr=[];
        foreach($ret_info as $item){
            $qz_tea_arr[] =$item["teacherid"];
        }
        $lesson_week = $lesson_month=0;
        $lesson_count_week = $task->t_lesson_info_b2->get_teacher_lesson_count_list($start_time,$end_time,$qz_tea_arr);
        foreach($lesson_count_week as $val){
            $lesson_week += $val["lesson_all"]/100;
        }

        $lesson_count_month = $task->t_lesson_info_b2->get_teacher_lesson_count_list($month_start,$end_time,$qz_tea_arr);
        foreach($lesson_count_month as $item){
            $lesson_month += $item["lesson_all"]/100;
        }
       
        $arr=["448","871","349"];
        // $arr=["349"];
        foreach($arr as $val){
            
            $task->t_manager_info->send_wx_todo_msg_by_adminid ($val,"全职老师周课时消耗汇总","全职老师周课时消耗汇总","本周全职老师共完成课时:".$lesson_week.",本月全职老师共完成课时:".$lesson_month,""); 

        }

       
       
    }
}
