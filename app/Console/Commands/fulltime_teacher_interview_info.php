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
        dd($all_num);

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
                    
        $video_all =  $task->t_teacher_lecture_info->get_lecture_info_by_all(
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
        $admin_list = [72,349,448,967];
        //$admin_list = [349];
        foreach($admin_list as $yy){
            $task->t_manager_info->send_wx_todo_msg_by_adminid ($yy,"招师日报","招师项目进度汇总","\n今日报名数:".$all_total."\n面试试讲数:通过".$data["one_succ"].",预约".$data["one_count"]."\n录制试讲数:通过".$data["video_succ"].",预约".$data["video_count"]."\n审核通过数:".$data["all_succ"],"http://admin.yb1v1.com/main_page/zs_teacher_new?date_type=null&opt_date_type=0&start_time=".$date."&end_time=".$date);
        }



    }
}
