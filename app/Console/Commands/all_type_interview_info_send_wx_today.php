<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class all_type_interview_info_send_wx_today extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:all_type_interview_info_send_wx_today';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每日9点发送前一天面试信息';

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
        $end_time = time();

       
        $video_all = $task->t_teacher_lecture_info->get_all_interview_count($start_time,$end_time,-1);
        $video_succ = $task->t_teacher_lecture_info->get_all_interview_count($start_time,$end_time,1);
        $one_all = $task->t_teacher_record_list->get_all_interview_count($start_time,$end_time,-1);
        $one_succ = $task->t_teacher_record_list->get_all_interview_count($start_time,$end_time,1);
        $all_day = $video_all+$one_all;
        $succ_day =  $video_succ+$one_succ;
        $all_day_per = round($all_day/60*100,2)."%";
        $succ_day_per = round($succ_day/18*100,2)."%";
        $start = strtotime("2017-06-18");
        $video = $task->t_teacher_lecture_info->get_all_interview_count($start,$end_time,-1);
        $video_succ_all = $task->t_teacher_lecture_info->get_all_interview_count($start,$end_time,1);
        $one = $task->t_teacher_record_list->get_all_interview_count($start,$end_time,-1);
        $one_succ_all = $task->t_teacher_record_list->get_all_interview_count($start,$end_time,1);
        $all = $video+$one;
        $succ = $video_succ_all+$one_succ_all;
        $all_per = round($all/2200*100,2)."%";
        // $succ_per = round($succ/650*100,2)."%";
        $arr=["72","329","310","448"];
        // $arr=["349","72"];
        $s= date("Y-m-d",$start_time);
        $e = date("Y-m-d ",$start_time);

        
        $teacher_list = $task->t_teacher_lecture_info->get_teacher_list_passed("",$start,$end_time);
        $teacher_arr = $task->t_teacher_record_list->get_teacher_train_passed("",$start,$end_time);
        foreach($teacher_arr as $k=>$val){
            if(!isset($teacher_list[$k])){
                $teacher_list[$k]=$k; 
            }
        }
       

        $all_tea = count($teacher_list);
        $succ_per = round($all_tea/650*100,2)."%";
        foreach($arr as $val){
            $task->t_manager_info->send_wx_todo_msg_by_adminid ($val,"今日面试信息报告","招聘月项目进度汇总","今日面试数:".$all_day."人,当日完成率:".$all_day_per.";通过面试数:".$succ_day."人,当日完成率:".$succ_day_per.";本月面试数:".$all."人,完成率:".$all_per.";通过面试数:".$all_tea."人,KPI完成率:".$succ_per.";
招聘月KPI指标:每日需面试人数:60人,面试通过18人,累计面试2200人,通过650人,考核周期6月18日到7月31号,大家加油冲锋!","http://admin.yb1v1.com/tongji_ss/teacher_interview_info_tongji?order_by_str=all_count desc&date_type=null&opt_date_type=0&start_time=".$s."&end_time=".$e); 
        }
        // return;

        //文科数据

        $video_all = $task->t_teacher_lecture_info->get_all_interview_count($start_time,$end_time,-1,1);
        $video_succ = $task->t_teacher_lecture_info->get_all_interview_count($start_time,$end_time,1,1);
        $one_all = $task->t_teacher_record_list->get_all_interview_count($start_time,$end_time,-1,1);
        $one_succ = $task->t_teacher_record_list->get_all_interview_count($start_time,$end_time,1,1);
        $all_day = $video_all+$one_all;
        $succ_day =  $video_succ+$one_succ;
        $all_day_per = round($all_day/28*100,2)."%";
        $succ_day_per = round($succ_day/8*100,2)."%";
        $video = $task->t_teacher_lecture_info->get_all_interview_count($start,$end_time,-1,1);
        $video_succ_all = $task->t_teacher_lecture_info->get_all_interview_count($start,$end_time,1,1);
        $one = $task->t_teacher_record_list->get_all_interview_count($start,$end_time,-1,1);
        $one_succ_all = $task->t_teacher_record_list->get_all_interview_count($start,$end_time,1,1);
        $all = $video+$one;
        $succ = $video_succ_all+$one_succ_all;
        // $all_per = round($all/2200*100,2)."%";
        $succ_per = round($succ/280*100,2)."%";
        $arr4=["72","329","448"];
        //$arr4=["349"];
        foreach($arr4 as $val){
            
            $task->t_manager_info->send_wx_todo_msg_by_adminid ($val,"今日文科面试信息报告","招聘月项目进度汇总","今日面试数:".$all_day."人,当日完成率:".$all_day_per.";通过面试数:".$succ_day."人,当日完成率:".$succ_day_per.";本月文科面试数:".$all."人,通过面试数:".$succ."人,KPI完成率:".$succ_per.";
招聘月文科KPI指标:每日需面试人数:28人,面试通过8人,累计需入职280人,考核周期6月18日到7月31号,大家加油冲锋!",""); 

        }

        
        //数学数据
        $video_all = $task->t_teacher_lecture_info->get_all_interview_count($start_time,$end_time,-1,2);
        $video_succ = $task->t_teacher_lecture_info->get_all_interview_count($start_time,$end_time,1,2);
        $one_all = $task->t_teacher_record_list->get_all_interview_count($start_time,$end_time,-1,2);
        $one_succ = $task->t_teacher_record_list->get_all_interview_count($start_time,$end_time,1,2);
        $all_day = $video_all+$one_all;
        $succ_day =  $video_succ+$one_succ;
        $all_day_per = round($all_day/30*100,2)."%";
        $succ_day_per = round($succ_day/9*100,2)."%";
        $video = $task->t_teacher_lecture_info->get_all_interview_count($start,$end_time,-1,2);
        $video_succ_all = $task->t_teacher_lecture_info->get_all_interview_count($start,$end_time,1,2);
        $one = $task->t_teacher_record_list->get_all_interview_count($start,$end_time,-1,2);
        $one_succ_all = $task->t_teacher_record_list->get_all_interview_count($start,$end_time,1,2);
        $all = $video+$one;
        $succ = $video_succ_all+$one_succ_all;
        // $all_per = round($all/2200*100,2)."%";
        $succ_per = round($succ/310*100,2)."%";
        $arr5=["72","310","448"];
        //$arr5=["349"];
        foreach($arr5 as $val){
            
            $task->t_manager_info->send_wx_todo_msg_by_adminid ($val,"今日数学面试信息报告","招聘月项目进度汇总","今日面试数:".$all_day."人,当日完成率:".$all_day_per.";通过面试数:".$succ_day."人,当日完成率:".$succ_day_per.";本月数学面试数:".$all."人,通过面试数:".$succ."人,KPI完成率:".$succ_per.";
招聘月数学KPI指标:每日需面试人数:30人,面试通过9人,累计需入职315人,考核周期6月18日到7月31号,大家加油冲锋!",""); 

        }

        //综合学科数据
        $video_all = $task->t_teacher_lecture_info->get_all_interview_count($start_time,$end_time,-1,3);
        $video_succ = $task->t_teacher_lecture_info->get_all_interview_count($start_time,$end_time,1,3);
        $one_all = $task->t_teacher_record_list->get_all_interview_count($start_time,$end_time,-1,3);
        $one_succ = $task->t_teacher_record_list->get_all_interview_count($start_time,$end_time,1,3);
        $all_day = $video_all+$one_all;
        $succ_day =  $video_succ+$one_succ;
        $all_day_per = round($all_day/6*100,2)."%";
        $succ_day_per = round($succ_day/2*100,2)."%";
        $video = $task->t_teacher_lecture_info->get_all_interview_count($start,$end_time,-1,3);
        $video_succ_all = $task->t_teacher_lecture_info->get_all_interview_count($start,$end_time,1,3);
        $one = $task->t_teacher_record_list->get_all_interview_count($start,$end_time,-1,3);
        $one_succ_all = $task->t_teacher_record_list->get_all_interview_count($start,$end_time,1,3);
        $all = $video+$one;
        $succ = $video_succ_all+$one_succ_all;
        // $all_per = round($all/2200*100,2)."%";
        $succ_per = round($succ/120*100,2)."%";
        $arr6=["72","478","448","871"];
        //$arr6=["349"];
        foreach($arr6 as $val){
            
            $task->t_manager_info->send_wx_todo_msg_by_adminid ($val,"今日综合学科面试信息报告","招聘月项目进度汇总","今日面试数:".$all_day."人,当日完成率:".$all_day_per.";通过面试数:".$succ_day."人,当日完成率:".$succ_day_per.";本月综合学科面试数:".$all."人,通过面试数:".$succ."人,KPI完成率:".$succ_per.";
招聘月综合学科KPI指标:每日需面试人数:6人,面试通过2人,累计需入职120人,考核周期6月18日到7月31号,大家加油冲锋!",""); 

        }


        $train_all = $task->t_lesson_info_b2->get_all_train_num($start,$end_time,$teacher_list,-1);
        $train_succ = $task->t_lesson_info_b2->get_all_train_num($start,$end_time,$teacher_list,1);
        dd($train_succ);
        $train_real = $task->t_lesson_info_b2->get_all_train_num_real($start,$end_time,$teacher_list,-1);
        $train_all_per = round($train_all/650*100,2)."%";
        $train_succ_per = round($train_succ/$train_all*100,2)."%";
        $arr2=["72","478","486","448","349"];
        //$arr2=["349"];
        //$arr2=["349","72"];

        foreach($arr2 as $val){
            $task->t_manager_info->send_wx_todo_msg_by_adminid ($val,"本月培训信息报告","招聘月项目进度汇总","本月累计入职数:".$all_tea."人,参与培训数:".$train_all."人,培训实到人数:".$train_real."人,通过培训数:".$train_succ."人,培训完成率:".$train_all_per.",培训通过率:".$train_succ_per.";
招聘月KPI指标:一周需要进行多次培训,累计需要培训通过650人,考核周期6月18日到7月31号,大家加油冲锋!",""); 
        }

        $arr3=["72","492","513","448","790","747","871"];
        // $arr3=["349"];
        //$arr3=["349","72"];
        $lecture_appointment_num = $task->t_teacher_lecture_appointment_info->get_lecture_appointment_num($start_time,$end_time);
        $video_apply = $task->t_teacher_lecture_info->get_video_apply_num($start_time,$end_time);
        $one_apply = $task->t_lesson_info_b2->get_one_apply_num($start_time,$end_time);
        $total_apply = $video_apply+$one_apply;
        $video_all= $task->t_teacher_lecture_info->get_video_apply_num($start,$end_time);
        $one_all = $task->t_lesson_info_b2->get_one_apply_num($start,$end_time);
        $all_apply = $video_all+$one_all;
        $apply_per = round($all_apply/2200*100,2)."%";
        
        foreach($arr3 as $val){
            $task->t_manager_info->send_wx_todo_msg_by_adminid ($val,"本月例子信息报告","招聘月项目进度汇总","今日报名数:".$lecture_appointment_num."人,今日试讲申请数:".$total_apply."人,录制试讲申请数:".$video_apply."人,面试试讲申请数:".$one_apply."人,累计试讲完成率:".$apply_per.";
招聘月KPI指标:每日提交面试申请人数60人,累计提交面试申请人数2200人,考核周期6月18日到7月31号,大家加油冲锋!",""); 

        }






        
       
    }
}
