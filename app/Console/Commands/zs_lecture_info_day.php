<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class zs_lecture_info_day extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:zs_lecture_info_day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '招师每日预约面试情况';

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
        $start_time = strtotime(date("Y-m-d",time()-100));
        $end_time=time();
        $all_total =0;
        $ret_info  = $task->t_teacher_lecture_appointment_info->tongji_teacher_lecture_appoiment_info_by_accept_adminid($start_time,$end_time);

        $video_account = $task->t_teacher_lecture_info->get_lecture_info_by_zs($start_time,$end_time);
        $video_account_real = $task->t_teacher_lecture_info->get_lecture_info_by_zs($start_time,$end_time,-2);
        $video_account_pass = $task->t_teacher_lecture_info->get_lecture_info_by_zs($start_time,$end_time,1);
        $one_account = $task->t_teacher_record_list->get_all_interview_count_by_zs($start_time,$end_time,-1);
        $one_account_real = $task->t_teacher_record_list->get_all_interview_count_by_zs($start_time,$end_time,-2);
        $one_account_pass = $task->t_teacher_record_list->get_all_interview_count_by_zs($start_time,$end_time,1);
        $str=[];
        foreach($ret_info as $k=>$item){
            $accept_adminid       = $item["accept_adminid"];
            $account = $task->t_manager_info->get_account($accept_adminid);
            $all_total   += $item["all_count"];
            $item["video_account"] = @$video_account[$accept_adminid]["all_count"];
            $item["video_account_real"] = @$video_account_real[$accept_adminid]["all_count"];
            $item["video_account_pass"] = @$video_account_pass[$accept_adminid]["all_count"];
            $item["one_account"] = @$one_account[$accept_adminid]["all_count"];
            $item["one_account_real"] = @$one_account_real[$accept_adminid]["all_count"];
            $item["one_account_pass"] = @$one_account_pass[$accept_adminid]["all_count"];
            $item["video_per"] = !empty( $item["video_account_real"] )?round( $item["video_account_pass"]/$item["video_account_real"]*100,2):0;
            $item["one_per"] = !empty( $item["one_account_real"] )?round( $item["one_account_pass"]/$item["one_account_real"]*100,2):0;
            $item["all_per"] = !empty( $item["one_account_real"]+$item["video_account_real"] )?round( ($item["one_account_pass"]+$item["video_account_pass"])/($item["one_account_real"]+$item["video_account_real"])*100,2):0;
            $item["all"] = $item["video_account"] + $item["one_account"];
            $item["all_real"] = $item["video_account_real"] + $item["one_account_real"];
            $item["all_pass"] = $item["video_account_pass"] + $item["one_account_pass"];
            @$str[$accept_adminid] =$account.":".$item["all_count"]."-".$item["one_account"]."/".$item["video_account"]."/".$item["all"]."、实到-".$item["one_account_real"]."/".$item["video_account_real"]."/".$item["all_real"]."、通过-".$item["one_account_pass"]."/".$item["video_account_pass"]."/".$item["all_pass"];
            //@$str[$accept_adminid] =$account.":邀约-面".$item["one_account"]."/录".$item["video_account"]."/总".$item["all"]."、实到-面".$item["one_account_real"]."/录".$item["video_account_real"]."/总".$item["all_real"]."、通过-面".$item["one_account_pass"]."/录".$item["video_account_pass"]."/总".$item["all_pass"];



        }
        $s ="";
        foreach($str as $vv){
            $s .=$vv."\n";
        }
        $data =[];

        $video_all =  $task->t_teacher_lecture_info->get_lecture_info_by_all_new(
            -1,$start_time,$end_time,-1,-1,-1,"");
        $video_real =  $task->t_teacher_lecture_info->get_lecture_info_by_all(
            -1,$start_time,$end_time,-1,-1,-1,"",-2);

        $one_all = $task->t_teacher_record_list->get_train_teacher_interview_info_all(
            -1,$start_time,$end_time,-1,-1,-1,"");
        $one_real = $task->t_teacher_record_list->get_train_teacher_interview_info_all(
            -1,$start_time,$end_time,-1,-1,-1,"",-2);
        @$data["video_count"] =  $video_all["all_count"];
        @$data["video_real"] =  $video_real["all_count"];
        @$data["one_count"] = $one_all["all_count"];
        @$data["one_real"] = $one_real["all_count"];

        $all_count =  $data["video_count"]+$data["one_count"];
        $all_real =  $data["video_real"]+$data["one_real"];

        $teacher_list_ex = $task->t_teacher_lecture_info->get_teacher_list_passed("",$start_time,$end_time);
        @$data["video_succ"] = count($teacher_list_ex);
        $teacher_arr_ex = $task->t_teacher_record_list->get_teacher_train_passed("",$start_time,$end_time);
        @$data["one_succ"] = count($teacher_arr_ex);
        foreach($teacher_arr_ex as $k=>$val){
            if(!isset($teacher_list_ex[$k])){
                $teacher_list_ex[$k]=$k;
            }
        }

        $data["all_succ"] = count($teacher_list_ex);
        $data["video_per"] = !empty($data["video_real"])?round($data["video_succ"]/$data["video_real"]*100,2):0;
        $data["one_per"] = !empty($data["one_real"])?round($data["one_succ"]/$data["one_real"]*100,2):0;

        $all_str ="总体:".$all_total."-".$data["one_count"]."/".$data["video_count"]."/".$all_count."、实到-".$data["one_real"]."/".$data["video_real"]."/".$all_real."、通过-".$data["one_succ"]."/".$data["video_succ"]."/".$data["all_succ"];

        $date = date("Y-m-d",time()-100);
        $admin_list = [72,349,448,967,492];
        // $admin_list = [349];
        foreach($admin_list as $yy){
            $task->t_manager_info->send_wx_todo_msg_by_adminid ($yy,"招师日报","招师项目进度汇总","\n今日报名数:".$all_total."\n面试试讲数:通过".$data["one_succ"].",预约".$data["one_count"]."\n录制试讲数:通过".$data["video_succ"].",提交".$data["video_count"]."\n审核通过数:".$data["all_succ"],"http://admin.yb1v1.com/main_page/zs_teacher_new?date_type=null&opt_date_type=0&start_time=".$date."&end_time=".$date);
        }



        //质监信息推送
        /*  $subject=-1;
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



        $admin_list = [349];
        $admin_list = [72,349,448,329];

        foreach($admin_list as $yy){
            $task->t_manager_info->send_wx_todo_msg_by_adminid ($yy,"质检日报","质监月项目进度汇总","\n面试数通过人数:".$all_tea_ex."/".$video_real["all_count"]."\n模拟试听审核数(一审):".$train_first_all["pass_num"]."/".$train_first_all["all_num"]."\n模拟试听审核数(二审):".$train_second_all["all_num"]."\n第一次试听审核:".$test_first_all."\n第一次常规审核:".$regular_first_all."\n第五次试听审核:".$test_five_all."\n第五次常规审核:".$regular_five_all,"http://admin.yb1v1.com/main_page/quality_control?date_type_config=undefined&date_type=null&opt_date_type=0&start_time=".$date."&end_time=".$date."&subject=-1 ");
            }*/





    }
}
