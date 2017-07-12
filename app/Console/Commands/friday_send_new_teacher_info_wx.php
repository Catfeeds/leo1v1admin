<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class friday_send_new_teacher_info_wx extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:friday_send_new_teacher_info_wx';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每周将新老师试听课信息发送Erick';

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
        $start_time = strtotime(date("2017-01-05"));
        $end_time=time();
        $ret_info = $task->t_teacher_lecture_info->get_new_teacher_no_test_lesson_info();
        $all_count = count($ret_info["list"]);
        
        $ret = $task->t_teacher_lecture_info->get_new_teacher_test_lesson_info($start_time,$end_time);
        $have_lesson_count = count($ret);
        $no_lesson_count = $all_count-$have_lesson_count;
        $have_per = (round($have_lesson_count/$all_count,4)*100)."%";
        $arr=[];
        foreach($ret as $val){
            @$arr["all_lesson"] +=$val["all_lesson"];
        }

              
        //dd($arr);
        $all_teacher_info = $task->t_lesson_info->get_all_teacher_test_lesson_info($start_time,$end_time);
        foreach($all_teacher_info as $v){
            @$all_tea++;
            @$all_lesson +=$v["all_lesson"];
        }
       

        $tea_per = (round($have_lesson_count/$all_tea,4)*100)."%";
        $lesson_per = (round($arr["all_lesson"]/$all_lesson,4)*100)."%";

       
        $task->t_manager_info->send_wx_todo_msg_by_adminid (349,"理优监课组","新老师试听课情况","您好,1月5日至本周五,一共入职新老师".$all_count."位,有试听课的".$have_lesson_count."位,无试听课的".$no_lesson_count."位,新老师使用率为".$have_per.";总老师数为".$all_tea."位,新老师人数占比".$tea_per.";总试听课数为".$all_lesson.",新老师试听占比为".$lesson_per,"");
        $task->t_manager_info->send_wx_todo_msg_by_adminid (72,"理优监课组","新老师试听课情况","您好,1月5日至本周五,一共入职新老师".$all_count."位,有试听课的".$have_lesson_count."位,无试听课的".$no_lesson_count."位,新老师使用率为".$have_per.";总老师数为".$all_tea."位,新老师人数占比".$tea_per.";总试听课数为".$all_lesson.",新老师试听占比为".$lesson_per,"");

      
    
           
       

    }
}
