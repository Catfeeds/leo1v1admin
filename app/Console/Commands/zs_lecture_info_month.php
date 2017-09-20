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
    protected $description = '质监每月推送数据';

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
        //every week
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();

        $time = 


        $subject = $task->get_in_int_val("subject",-1);
        //dd($date);
        $account_role = 9;
        $kpi_flag = 1;
        $teacher_info = $task->t_manager_info->get_adminid_list_by_account_role($account_role);//return->uid,account,nick,name
        foreach($teacher_info as $kk=>$vv){
            if(in_array($kk,[992,891,486,871,1058,1080])){
                unset($teacher_info[$kk]);
            }
        }
        $tea_subject = "";

        //面试人数
        $real_info = $task->t_teacher_lecture_info->get_lecture_info_by_time_new(
            $subject,$start_time,$end_time,-1,-1,-1,$tea_subject,-2);
        $real_arr = $task->t_teacher_record_list->get_train_teacher_interview_info(
            $subject,$start_time,$end_time,-1,-1,-1,$tea_subject,-2);
        foreach($real_arr["list"] as $p=>$pp){
            if(isset($real_info["list"][$p])){
                $real_info["list"][$p]["all_count"] += $pp["all_count"];
                $real_info["list"][$p]["all_num"] += $pp["all_num"];
            }else{
                $real_info["list"][$p]= $pp;
            }

        }
        //模拟试听审核
        $train_first = $task->t_teacher_record_list->get_trial_train_lesson_first($start_time,$end_time,1,$subject);
        $train_second = $task->t_teacher_record_list->get_trial_train_lesson_first($start_time,$end_time,2,$subject);

        //第一次试听/第一次常规
        $test_first = $task->t_teacher_record_list->get_test_regular_lesson_first($start_time,$end_time,1,$subject);
        $test_first_per = $task->t_teacher_record_list->get_test_regular_lesson_first_per($start_time,$end_time,1,$subject);


         //第5次试听
        $test_five = $task->t_teacher_record_list->get_test_regular_lesson_first($start_time,$end_time,2,$subject);

        $test_five_per = $task->t_teacher_record_list->get_test_regular_lesson_first_per($start_time,$end_time,2,$subject);
        //第一次常规
        //dd($test_first_per);
        $regular_first = $task->t_teacher_record_list->get_test_regular_lesson_first($start_time,$end_time,3,$subject);
        $regular_first_per = $task->t_teacher_record_list->get_test_regular_lesson_first_per($start_time,$end_time,3,$subject);

        //第5次常规
        $regular_five = $task->t_teacher_record_list->get_test_regular_lesson_first($start_time,$end_time,4,$subject);

        $regular_five_per = $task->t_teacher_record_list->get_test_regular_lesson_first_per($start_time,$end_time,4,$subject);
        $all_count=0;
        $real_num = $suc_count = $train_first_all= $train_first_pass = $train_second_all = $test_first_all = $regular_first_all=$test_five_all=$regular_five_all=0;
        foreach($teacher_info as &$item){
            $item["real_num"] = isset($real_info["list"][$item["account"]])?$real_info["list"][$item["account"]]["all_count"]:0;
            $account = $item["account"];
            $teacher_list = $task->t_teacher_lecture_info->get_teacher_list_passed($account,$start_time,$end_time,$subject,-1,-1,-1,$tea_subject);
            $teacher_arr = $task->t_teacher_record_list->get_teacher_train_passed($account,$start_time,$end_time,$subject,-1,-1,-1,$tea_subject);
            foreach($teacher_arr as $k=>$val){
                if(!isset($teacher_list[$k])){
                    $teacher_list[$k]=$k;
                }
            }

            $item["suc_count"] = count($teacher_list);
            $item["train_first_all"] = isset($train_first[$account])?$train_first[$account]["all_num"]:0;
            $item["train_first_pass"] = isset($train_first[$account])?$train_first[$account]["pass_num"]:0;
            $item["train_second_all"] = isset($train_second[$account])?$train_second[$account]["all_num"]:0;

            $item["test_first"] = isset($test_first[$account])?$test_first[$account]["all_num"]:0;
            $item["test_five"] = isset($test_five[$account])?$test_five[$account]["all_num"]:0;
            $item["regular_first"] = isset($regular_first[$account])?$regular_first[$account]["all_num"]:0;
            $item["regular_five"] = isset($regular_five[$account])?$regular_five[$account]["all_num"]:0;

            $item["all_num"] = $item["real_num"]+ $item["train_first_all"]+$item["train_second_all"]+ $item["test_first"]+ $item["regular_first"]+$item["test_five"]+$item["regular_five"];
            if($item["uid"]==481){
                $item["all_num"] +=7;
            }
            $item["all_target_num"] = 250;
            if(in_array($item["uid"],[486,754,1011,329])){
                $item["all_target_num"]=150;
            }elseif(in_array($item["uid"],[913,923,892])){
                $item["all_target_num"]=400;
            }elseif(in_array($item["uid"],[478])){
                 $item["all_target_num"]=50;
            }elseif(in_array($item["uid"],[895,683])){
                 $item["all_target_num"]=100;
            }

            $all_count +=$item["all_target_num"];
            $item["per"] = round($item["all_num"]/$item["all_target_num"]*100,2);
            if($kpi_flag==1){
                $real_num += $item["real_num"];
                $suc_count += $item["suc_count"];
                $train_first_all += $item["train_first_all"];
                $train_first_pass += $item["train_first_pass"];
                $train_second_all += $item["train_second_all"];
                $test_first_all += $item["test_first"];
                $test_five_all += $item["test_five"];
                $regular_first_all += $item["regular_first"];
                $regular_five_all += $item["regular_five"];
            }
        }
        if($kpi_flag==1){
            $arr=[];
            $arr=["name"=>"总计",
            ];
            $arr["real_num"] = $real_num;
            $arr["suc_count"] = $suc_count;
            $arr["train_first_all"] = $train_first_all;
            $arr["train_first_pass"] = $train_first_pass;
            $arr["train_second_all"] = $train_second_all;
            $arr["test_first"] = $test_first_all;
            $arr["test_five"] = $test_five_all;
            $arr["regular_first"] = $regular_first_all;
            $arr["regular_five"] = $regular_five_all;
            $arr["all_num"] = $real_num+$train_first_all+$test_first_all+$regular_first_all+$train_second_all+$test_five_all+$regular_five_all;
        }
        $num = count($teacher_info);
        if($all_count){
            $arr["per"] = round($arr["all_num"]/$all_count*100,2);
        }else{
            $arr["per"] = 0;
        }

        $arr["all_target_num"] = $all_count;
        array_unshift($teacher_info,$arr);
        $admin_list = [72,349,448,329];
        //$admin_list = [944];
        foreach($admin_list as $yy){
          foreach ($teacher_info as $key => $value) {
             if($value['name'] == "总计"){
                 $task->t_manager_info->send_wx_todo_msg_by_adminid (
                    $yy,
                    "质检月报",
                    "质监月项目进度汇总",
                    "\n面试数通过人数:".
                    $value['real_num']."/".
                    $value['suc_count'].
                    "\n模拟试听审核数(一审):".$value['train_first_all']."/".$value['train_first_pass'].
                    "\n模拟试听审核数(二审):".$value['train_second_all'].
                    "\n第一次试听审核:".$value['test_first'].
                    "\n第一次常规审核:".$value['regular_first'].
                    "\n总体完成率:".$value['per'].'%',
                    "http://admin.yb1v1.com/main_page/quality_control_kpi?date_type_config=undefined&date_type=null&opt_date_type=0&start_time=".$start_date."&end_time=".$end_date."&subject=-1 ");
                }
            }
        }


        foreach ($teacher_info as $key => $value) {
            if(isset($value['uid'])){
                $task->t_manager_info->send_wx_todo_msg_by_adminid (
                $value['uid'],
                "质检月报",
                "质监月项目进度汇总",
                "\n面试数通过人数:".
                $value['real_num']."/".
                $value['suc_count'].
                "\n模拟试听审核数(一审):".$value['train_first_all']."/".$value['train_first_pass'].
                "\n模拟试听审核数(二审):".$value['train_second_all'].
                "\n第一次试听审核:".$value['test_first'].
                "\n第一次常规审核:".$value['regular_first'].
                "\n总体完成率:".$value['per'].'%',
                "http://admin.yb1v1.com/main_page/quality_control_kpi?date_type_config=undefined&date_type=null&opt_date_type=0&start_time=".$start_date."&end_time=".$end_date."&subject=-1 ");
            }
        }
    }
}
