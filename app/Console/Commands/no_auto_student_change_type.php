<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class no_auto_student_change_type extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:no_auto_student_change_type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '手动修改学生类型的学生,系统刷新学生类型';

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

        $task = new \App\Console\Tasks\TaskController ();
        $time = strtotime(date("Y-m-d",time()));        
        $user_stop = $task->t_student_info->get_no_auto_stop_stu_list($time);
        foreach($user_stop as $item){
            $check_lesson = $task->t_lesson_info_b2->check_have_regular_lesson($time,time(),$item["userid"]);
            if($check_lesson==1){
                $task->t_student_info->get_student_type_update($item["userid"],0);
                $task->t_student_info->field_update_list($item["userid"],[
                   "is_auto_set_type_flag" =>0 
                ]);
                $task->t_student_type_change_list->row_insert([
                    "userid"    =>$item["userid"],
                    "add_time"  =>time(),
                    "type_before" =>$item["type"],
                    "type_cur"    =>0,
                    "change_type" =>1,
                    "adminid"     =>0,
                    "reason"      =>"系统更新"
                ]);

            }
        }

        $ret_student_end_info = $task->t_student_info->get_student_list_end_id(-1);
        foreach($ret_student_end_info as $val){
            $task->t_student_info->get_student_type_update($val["userid"],1);
            $task->t_student_type_change_list->row_insert([
                "userid"    =>$val["userid"],
                "add_time"  =>time(),
                "type_before" =>$val["type"],
                "type_cur"    =>1,
                "change_type" =>1,
                "adminid"     =>0,
                "reason"      =>"系统更新"
            ]);
            $task->delete_teacher_regular_lesson($val["userid"],1);
            $refund_time = $task->t_order_refund->get_last_apply_time($val["userid"]);
            $last_lesson_time = $task->t_student_info->get_last_lesson_time($val["userid"]);
            
            if(empty($refund_time) || $refund_time>$last_lesson_time){
                $assistantid = $task->t_student_info->get_assistantid($val["userid"]);
                $adminid = $task->t_assistant_info->get_adminid_by_assistand($assistantid);
                $month = strtotime(date("Y-m-01",time()));
                $ass_info = $task->t_month_ass_student_info->get_ass_month_info($month,$adminid,1);
                if($ass_info){
                    $num = @$ass_info[$adminid]["end_no_renw_num"]+1;
                    $task->t_month_ass_student_info->get_field_update_arr($adminid,$month,1,[
                        "end_no_renw_num" =>$num
                    ]);

                }else{
                    $task->t_month_ass_student_info->row_insert([
                        "adminid" =>$adminid,
                        "month"   =>$month,
                        "end_no_renw_num"=>1,
                        "kpi_type" =>1
                    ]);
                }

 
            }

        }

    }
}
