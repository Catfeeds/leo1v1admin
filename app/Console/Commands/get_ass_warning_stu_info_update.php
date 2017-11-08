<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class get_ass_warning_stu_info_update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get_ass_warning_stu_info_update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '助教预警学员学生信息每周最后一天更新';

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
        $time = time()+86400;
        $date_week = \App\Helper\Utils::get_week_range($time,1);
        $lstart = $date_week["sdate"];
        $lend = $date_week["edate"];

        $warning_list_new = $task->t_student_info->get_warning_stu_list_new();
        $warning_stu_list=[];
        // dd($warning_list_new);
        foreach($warning_list_new as $item){
            // $end_week = ceil($item["lesson_count_left"]/$item["lesson_total"]);           
            $task->t_month_ass_warning_student_info->row_insert([
                "adminid"        =>$item["uid"],
                "month"          =>$lstart,
                "userid"         =>$item["userid"],
                "groupid"        =>$item["groupid"],
                "group_name"     =>$item["group_name"],
                "left_count"     =>$item["lesson_count_left"],
                "warning_type"   =>1
            ]);
            @$warning_stu_list[$item["uid"]]["warning_student"]++;
            @$warning_stu_list[$item["uid"]]["userid_list"][]=$item["userid"];

        }

        $ass_list = $task->t_manager_info->get_adminid_list_by_account_role(1);
        foreach($ass_list as $k=>$val){
            if(isset($warning_stu_list[$k])){
                $warning_student = $warning_stu_list[$k]["warning_student"];
                $userid_list = json_encode($warning_stu_list[$k]["userid_list"]);
            }else{
                $warning_student =0;
                $userid_list=[];
                $userid_list = json_encode($userid_list);
            }
            $id =$task->t_ass_weekly_info->get_id_by_unique_record($k,$lstart,1);
            if($id >0){
                $task->t_ass_weekly_info->field_update_list($id,[
                    "warning_student" =>$warning_student,
                    "warning_student_list" =>$userid_list,
                ]);     
            }else{
                $task->t_ass_weekly_info->row_insert([
                    "adminid"   =>$k,
                    "week"      =>$lstart,
                    "warning_student" =>$warning_student,
                    "warning_student_list" =>$userid_list,
                    "time_type"    =>1
                ]);
 
            }

        }


        // dd($ass_list);
       
              
    }
}
