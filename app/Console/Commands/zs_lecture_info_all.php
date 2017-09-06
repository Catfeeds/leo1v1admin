<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class zs_lecture_info_all extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:zs_lecture_info_all';

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

        //整体完成率
        $start_time = strtotime("2017-08-11");
        $end_time = time();
        $subject=-1;
        $tea_subject="";
		
		$teacher_info = $task->t_manager_info->get_adminid_list_by_account_role(-2);//return->uid,account,nick,name
		foreach($teacher_info as $kk=>$vv){
		    if(in_array($kk,[992,891,486,871,1058])){
                unset($teacher_info[$kk]);
		    }
		}
	

		$all_count=0;
    
		foreach($teacher_info as &$item){
		   
		    $item["all_target_num"] = 250;
		    if(in_array($item["uid"],[486,754,329,1011])){
                $item["all_target_num"]=150;
		    }elseif(in_array($item["uid"],[913,923,892])){
                $item["all_target_num"]=400;
		    }elseif(in_array($item["uid"],[478])){
                $item["all_target_num"]=50;
            }elseif(in_array($item["uid"],[895,683])){
                $item["all_target_num"]=100;
            }

		    $all_count +=$item["all_target_num"];
		}       

		//面试总计

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
		$train_second_all = $task->t_teacher_record_list->get_trial_train_lesson_all($start_time,$end_time,2,$subject);

		//第一次试听/第一次常规总计/第五次试听/第五次常规
		$test_first_all = $task->t_teacher_record_list->get_test_regular_lesson_all($start_time,$end_time,1,$subject);
		$regular_first_all = $task->t_teacher_record_list->get_test_regular_lesson_all($start_time,$end_time,3,$subject);
		$test_five_all = $task->t_teacher_record_list->get_test_regular_lesson_all($start_time,$end_time,2,$subject);
		$regular_five_all = $task->t_teacher_record_list->get_test_regular_lesson_all($start_time,$end_time,4,$subject);

		$all_num = $video_real["all_count"]+$train_first_all["all_num"]+$train_second_all["all_num"]+$test_first_all+$regular_first_all+$test_five_all+$regular_five_all;
        $num = count($teacher_info);
		// $all_count = ($num-2)*250+300;
        if($all_count){
            $all_per = round($all_num/$all_count*100,2);  
        }else{
            $all_per = 0;
        }

        $admin_list = [349];
        $admin_list = [72,349,448,329];
        $date1 = "2017-08-11";
        $date2 = date("Y-m-d H:i:s",time());


        foreach($admin_list as $yy){
            $task->t_manager_info->send_wx_todo_msg_by_adminid ($yy,"整体进度","质监月项目进度汇总","\n面试数通过人数:".$all_tea_ex."/".$video_real["all_count"]."\n模拟试听审核数(一审):".$train_first_all["pass_num"]."/".$train_first_all["all_num"]."\n模拟试听审核数(二审):".$train_second_all["all_num"]."\n第一次试听审核:".$test_first_all."\n第一次常规审核:".$regular_first_all."\n第五次试听审核:".$test_five_all."\n第五次常规审核:".$regular_five_all."\n整体完成率:".$all_per."%","http://admin.yb1v1.com/main_page/quality_control?date_type_config=undefined&date_type=null&opt_date_type=0&start_time=".$date1."&end_time=".$date2."&subject=-1");
        }





    }
}
