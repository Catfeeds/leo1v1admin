<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class get_ass_weekly_info extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get_ass_weekly_info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '助教周报信息更新';

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
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();
        $time = time()-86400;
        $date_week = \App\Helper\Utils::get_week_range($time,1);
        $start_time = $date_week["sdate"];
        $end_time = $date_week["edate"];
        $week_info = $task->t_ass_weekly_info->get_all_info($start_time);
        $month_start = strtotime(date("Y-m-01",$start_time));
        $stu_info_all = $task->t_student_info->get_ass_stu_info_new();
        $lesson_info = $task->t_lesson_info_b2->get_ass_stu_lesson_list($start_time,$end_time);
        $no_lesson_info = $task->t_lesson_info_b2->get_no_lesson_tongji($start_time,$end_time);
        $lesson_money_list = $task->t_manager_info->get_assistant_lesson_money_info($start_time,$end_time);
        $assistant_renew_list = $task->t_manager_info->get_all_assistant_renew_list_new($start_time,$end_time);
       
        $refund_info = $task->t_order_refund->get_ass_refund_info($start_time,$end_time);
        $new_info = $task->t_student_info->get_new_assign_stu_info($start_time,$end_time);
        $end_info = $task->t_student_info->get_end_class_stu_info($start_time,$end_time);

        $ass_list = $task->t_manager_info->get_adminid_list_by_account_role(1);

        //类型在读学生列表
        $userid_list = $task->t_student_info->get_read_student_ass_info();

        $registered_userid_list = $task->t_student_info->get_read_student_ass_info(-2);//在册学员名单


        foreach($ass_list as $k=>&$item){
            $item["read_student"] = isset($stu_info_all[$k])?$stu_info_all[$k]["read_count"]:0;
            $item["lesson_student"] =  isset($lesson_info[$k])?$lesson_info[$k]["user_count"]:0;
            $item["lesson_count"] =  isset($lesson_info[$k])?$lesson_info[$k]["lesson_count"]:0;
            $item["teacher_leave_count"] = isset($no_lesson_info[$k])?$no_lesson_info[$k]["teacher_leave_count"]:0;
            $item["student_leave_count"] = isset($no_lesson_info[$k])?$no_lesson_info[$k]["student_leave_count"]:0;
            $item["other_count"] = isset($no_lesson_info[$k])?$no_lesson_info[$k]["other_count"]:0;
            $item["lesson_count_per"] = !empty($item["lesson_count_target"])?round($item["lesson_count"]/$item["lesson_count_target"]*100,2):0;
            $item["stu_lesson_per"] = !empty($item["read_student"])?round($item["lesson_student"]/$item["read_student"]*100,2):0;

            $item["lesson_money"] = isset($lesson_money_list[$k])?$lesson_money_list[$k]["lesson_price"]:0;
            $item["refund_student"] = isset($refund_info[$k])?$refund_info[$k]["num"]:0;
            $item["refund_money"] = isset($refund_info[$k])?$refund_info[$k]["refund_money"]:0;

            $userid_list = isset($week_info[$k])?$week_info[$k]["warning_student_list"]:"";
            $userid_list = json_decode($userid_list,true);
            if(empty($userid_list)){
                $userid_list=[];
            }
            $assistant_renew_list_plan = $task->t_manager_info->get_all_assistant_renew_list_new($start_time,$end_time,$userid_list,$k);
            $item["renw_num_plan"] = isset($assistant_renew_list_plan[$k]["renw_num"])?$assistant_renew_list_plan[$k]["renw_num"]:0;
            $item["renw_num"] = isset($assistant_renew_list[$k])?$assistant_renew_list[$k]["renw_num"]:0;
            // $item["renw_num_other"] = $item["renw_num"]-$item["renw_num_plan"];
            // $item["renw_per"] = !empty($item["warning_stu"])?round($item["renw_num"]/$item["warning_stu"]*100,2):0;
            $item["renw_money"] = isset($assistant_renew_list[$k])?$assistant_renew_list[$k]["renw_price"]:0;
            $item["new_stu_num"] = isset($new_info[$k])?$new_info[$k]["num"]:0;
            $item["end_stu_num"] = isset($end_info[$k])?$end_info[$k]["num"]:0;

            $week_exist = $task->t_ass_weekly_info->get_id_by_unique_record($k,$start_time,1);

            $item["read_student_list"]  = @$userid_list[$k];
            $item["registered_student_list"]  = @$registered_userid_list[$k];
            if($item["registered_userid_list"]){
                $registered_userid_arr = json_decode($item["registered_userid_list"],true);
                $item["registered_student_num"] = count($registered_userid_arr);
            }else{
                $item["registered_student_num"]=0;
            }

            if($week_exist>0){
                $task->t_ass_weekly_info->field_update_list($week_exist,[
                    "read_student"    => $item["read_student"],
                    "lesson_student"  => $item["lesson_student"],
                    "lesson_count"    => $item["lesson_count"],
                    "tea_leave_lesson_count"   =>$item["teacher_leave_count"],
                    "stu_leave_lesson_count"   =>$item["student_leave_count"],
                    "other_lesson_count"       =>$item["other_count"],
                    "lesson_per"               =>$item["stu_lesson_per"],
                    "lesson_count_per"         =>$item["lesson_count_per"],
                    "lesson_money"             =>$item["lesson_money"],
                    "refund_student"           =>$item["refund_student"],
                    "refund_money"             =>$item["refund_money"],
                    "renw_student"             => $item["renw_num"],
                    "renw_student_in_plan"     =>$item["renw_num_plan"],
                    "renw_price"               => $item["renw_money"],
                    "new_stu_num"              =>$item["new_stu_num"],
                    "end_stu_num"              =>$item["end_stu_num"],
                    "read_student_list"        =>$item["read_student_list"],
                    "registered_student_list"  =>$item["registered_student_list"],
                    "registered_student_num"   =>$item["registered_student_num"],
                ]);
            }else{
                $task->t_ass_weekly_info->row_insert([
                    "adminid"    =>$k,
                    "week"       =>$start_time,
                    "read_student"    => $item["read_student"],
                    "lesson_student"  => $item["lesson_student"],
                    "lesson_count"    => $item["lesson_count"],
                    "tea_leave_lesson_count"   =>$item["teacher_leave_count"],
                    "stu_leave_lesson_count"   =>$item["student_leave_count"],
                    "other_lesson_count"       =>$item["other_count"],
                    "lesson_per"               =>$item["stu_lesson_per"],
                    "lesson_count_per"         =>$item["lesson_count_per"],
                    "lesson_money"             =>$item["lesson_money"],
                    "refund_student"           =>$item["refund_student"],
                    "refund_money"             =>$item["refund_money"],
                    "renw_student"             => $item["renw_num"],
                    "renw_student_in_plan"     =>$item["renw_num_plan"],
                    "renw_price"               => $item["renw_money"],
                    "new_stu_num"              =>$item["new_stu_num"],
                    "end_stu_num"              =>$item["end_stu_num"],
                    "time_type"                =>1,
                    "read_student_list"        =>$item["read_student_list"],
                    "registered_student_list"  =>$item["registered_student_list"],
                    "registered_student_num"   =>$item["registered_student_num"],
                ]);
            }


        }


        // dd($ass_list);
       
              
    }
}
