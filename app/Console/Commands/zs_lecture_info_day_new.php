<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class zs_lecture_info_day_new extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:zs_lecture_info_day_new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '质监每日推送当天数据';

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
        //every day
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();
        $start_time = strtotime(date("Y-m-d",time()-100));
        $end_time=time();
        $subject=-1;
        $tea_subject="";
        $teacher_list_ex = $task->t_teacher_lecture_info->get_teacher_list_passed("",$start_time,$end_time,$subject,-1,-1,-1,$tea_subject);
        $teacher_arr_ex = $task->t_teacher_record_list->get_teacher_train_passed("",$start_time,$end_time,$subject,-1,-1,-1,$tea_subject);
        foreach($teacher_arr_ex as $k=>$val){
            if(!isset($teacher_list_ex[$k])){
                $teacher_list_ex[$k]=$k;
            }
        }
        $video_real =  $task->t_teacher_lecture_info->get_lecture_info_by_all(
            $subject,$start_time,$end_time,-1,-1,-1,$tea_subject,-2);

        $one_real = $task->t_teacher_record_list->get_train_teacher_interview_info_all(
            $subject,$start_time,$end_time,-1,-1,-1,$tea_subject,-2);
        @$video_real["all_count"] += $one_real["all_count"];

        $all_tea_ex = count($teacher_list_ex);

        //模拟试听总计
        $train_first_all = $task->t_teacher_record_list->get_trial_train_lesson_all($start_time,$end_time,1,$subject);
        if(empty($train_first_all["pass_num"])){
            $train_first_all["pass_num"]=0;
        }
        $train_second_all = $task->t_teacher_record_list->get_trial_train_lesson_all($start_time,$end_time,2,$subject);

        //第一次试听/第一次常规总计
        $test_first_all = $task->t_teacher_record_list->get_test_regular_lesson_all($start_time,$end_time,1,$subject);
        $regular_first_all = $task->t_teacher_record_list->get_test_regular_lesson_all($start_time,$end_time,3,$subject);
        $all_num = $video_real["all_count"]+$train_first_all["all_num"]+$test_first_all+$regular_first_all+$train_second_all["all_num"];

        //第五次试听
        $test_five_all = $task->t_teacher_record_list->get_test_regular_lesson_all($start_time,$end_time,2,$subject);
        //第五次常规
        $regular_five_all = $task->t_teacher_record_list->get_test_regular_lesson_all($start_time,$end_time,4,$subject);


        $arr=["name"=>"总计","real_num"=>$video_real["all_count"],"suc_count"=>$all_tea_ex,"train_first_all"=>$train_first_all["all_num"],"train_first_pass"=>$train_first_all["pass_num"],"train_second_all"=>$train_second_all["all_num"],"test_first"=>$test_first_all,"regular_first"=>$regular_first_all,"all_num"=>$all_num];

        //$admin_list = [349];
        //$admin_list = [72,349,448,329];
        $admin_list = 944;

        foreach($admin_list as $yy){
            $task->t_manager_info->send_wx_todo_msg_by_adminid ($yy,"质检日报","质监月项目进度汇总","\n面试数通过人数:".$all_tea_ex."/".$video_real["all_count"]."\n模拟试听审核数(一审):".$train_first_all["pass_num"]."/".$train_first_all["all_num"]."\n模拟试听审核数(二审):".$train_second_all["all_num"]."\n第一次试听审核:".$test_first_all."\n第一次常规审核:".$regular_first_all,"http://admin.yb1v1.com/main_page/quality_control?date_type_config=undefined&date_type=null&opt_date_type=0&start_time=".$date."&end_time=".$date."&subject=-1 ");
        }
    }
}
