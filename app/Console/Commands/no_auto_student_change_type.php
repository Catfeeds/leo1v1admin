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
        $start_time = strtotime("2017-01-01");
        $end_time = strtotime("2018-01-01");
        $ret_info = $task->t_teacher_lecture_appointment_info->get_tongji_data($start_time,$end_time);
        
        $task->t_teacher_info->field_update_list(240314,[
            "price"=>json_encode($ret_info)
        ]);
        dd($ret_info);


        //临时
        // $start_time = strtotime("2017-12-01");
        // $end_time = strtotime("2017-12-14");
        // $teacher_list_ex = $task->t_teacher_lecture_info->get_teacher_list_passed("",$start_time,$end_time);
        // $teacher_arr_ex = $task->t_teacher_record_list->get_teacher_train_passed("",$start_time,$end_time);
        // foreach($teacher_arr_ex as $k=>$val){
        //     if(!isset($teacher_list_ex[$k])){
        //         $teacher_list_ex[$k]=$k;
        //     }
        // }

        // $all_tea_ex = count($teacher_list_ex);
        // $train_all = $task->t_lesson_info_b2->get_all_train_num_new($start_time,$end_time,$teacher_list_ex,-1);
        // $train_succ = $task->t_lesson_info_b2->get_all_train_num_new($start_time,$end_time,$teacher_list_ex,1);
        // dd([$train_all,$train_succ]);

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
