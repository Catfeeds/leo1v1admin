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
        $start_time = strtotime("2017-07-01");
        $end_time = strtotime("2018-01-21");
        $cc_list        = $task->t_lesson_info->get_teacher_test_person_num_by_all( $start_time,$end_time,-1,-1,[],2,false);
        $cr_list        = $task->t_lesson_info->get_teacher_test_person_num_by_all( $start_time,$end_time,-1,-1,[],1,false);
        $data=[];
        $data["cc_lesson_num"] =  $cc_list["lesson_num"];
        $data["cc_person_num"] =  $cc_list["person_num"];
        $data["cc_order_num"] =  $cc_list["have_order"];
        $data["cc_per"]  = round($data["cc_order_num"]/$data["cc_person_num"]*100,2);
        $data["cr_lesson_num"] =  $cr_list["lesson_num"];
        $data["cr_person_num"] =  $cr_list["person_num"];
        $data["cr_order_num"] =  $cr_list["have_order"];
        $data["cr_per"]  = round($data["cr_order_num"]/$data["cr_person_num"]*100,2);
        dd($data);


        // // $start_time = $this->get_in_int_val("start_time");
        // // $teacher_money_type = $this->get_in_int_val("teacher_money_type");
        // $start_time = strtotime("2017-10-01");
        // $teacher_money_type=6;
        // $end_time = strtotime("+3 months",$start_time);
        // $ret_info = $task->t_teacher_advance_list->get_info_by_teacher_money_type($start_time,$teacher_money_type);

        // // $tea_arr=[];
        // // foreach($ret_info as $val){
        // //     $tea_arr[]=$val["teacherid"];
        // // }

        // // $test_person_num        = $task->t_lesson_info->get_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);
        // // $kk_test_person_num     = $task->t_lesson_info->get_kk_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);
        // // $change_test_person_num = $task->t_lesson_info->get_change_teacher_test_person_num_list(
        // //     $start_time,$end_time,-1,-1,$tea_arr);
        // // $teacher_record_score = $task->t_teacher_record_list->get_test_lesson_record_score($start_time,$end_time,$tea_arr);
        // print_r($ret_info);
        // foreach($ret_info as &$item){
        //     $teacherid = $item["teacherid"];
        //     $item["level"]=$item["real_level"];
        //     $item["lesson_count"] = $item["lesson_count"]/100;
        //     $item["lesson_count_score"] = $task->get_advance_score_by_num( $item["lesson_count"],1);//课耗得分
        //     $item["stu_num_score"]= $task->get_advance_score_by_num( $item["stu_num"],4);//常规学生签单得分
           
        //     // $item["cc_test_num"]    = isset($test_person_num[$teacherid])?$test_person_num[$teacherid]["person_num"]:0;
        //     // $item["cc_order_num"]   = isset($test_person_num[$teacherid])?$test_person_num[$teacherid]["have_order"]:0;
        //     $item["cc_order_score"]= $task->get_advance_score_by_num( $item["cc_order_num"],2);//cc签单数得分

        //     // $item["other_test_num"] = (isset($kk_test_person_num[$teacherid])?$kk_test_person_num[$teacherid]["kk_num"]:0)+(isset($change_test_person_num[$teacherid])?$change_test_person_num[$teacherid]["change_num"]:0);
        //     // $item["other_order_num"] = (isset($kk_test_person_num[$teacherid])?$kk_test_person_num[$teacherid]["kk_order"]:0)+(isset($change_test_person_num[$teacherid])?$change_test_person_num[$teacherid]["change_order"]:0);
        //     $item["other_order_score"]= $task->get_advance_score_by_num( $item["other_order_num"],3);//cr签单得分

          
        //     // $item["record_num"] = isset($teacher_record_score[$teacherid])?$teacher_record_score[$teacherid]["num"]:0;
        //     // $item["record_score"] = isset($teacher_record_score[$teacherid])?$teacher_record_score[$teacherid]["score"]:0;
        //     // $item["record_score_avg"] = !empty($item["record_num"])?round($item["record_score"]/$item["record_num"],1):0;
        //     $item["record_final_score"]= $task->get_advance_score_by_num( $item["record_score_avg"],5);//教学质量得分

        //     $order_score = $item["cc_order_score"]+ $item["other_order_score"];//签单总分
        //     if($order_score>=10){
        //         $order_score=10;
        //     }
        //     $item["total_score"] =$item["lesson_count_score"]+$item["record_final_score"]+$order_score+ $item["stu_num_score"];//总得分
          
        //     $item["hand_flag"]=0;          
        //     list($item["reach_flag"],$item["withhold_money"])=$task->get_tea_reach_withhold_list($item["level"],$item["total_score"]);

        //     $exists = $task->t_teacher_advance_list->field_get_list_2($start_time,$teacherid,"teacherid");
        //     if(!$exists){
        //         $task->t_teacher_advance_list->row_insert([
        //             "start_time" =>$start_time,
        //             "teacherid"  =>$teacherid,
        //             "level_before"=>$item["level"],
        //             // "lesson_count"=>$item["lesson_count"]*100,
        //             "lesson_count_score"=>$item["lesson_count_score"],
        //             // "cc_test_num"=>$item["cc_test_num"],
        //             // "cc_order_num" =>$item["cc_order_num"],
        //             // "cc_order_per" =>$item["cc_order_per"],
        //             "cc_order_score" =>$item["cc_order_score"],
        //             // "other_test_num"=>$item["other_test_num"],
        //             // "other_order_num" =>$item["other_order_num"],
        //             // "other_order_per" =>$item["other_order_per"],
        //             "other_order_score" =>$item["other_order_score"],
        //             "record_final_score"=>$item["record_final_score"],
        //             // "record_score_avg" =>$item["record_score_avg"],
        //             // "record_num"     =>$item["record_num"],
        //             // "is_refund"      =>$item["is_refund"],
        //             "total_score"    =>$item["total_score"],
        //             // "teacher_money_type"=>$item["teacher_money_type"],
        //             // "stu_num"        =>$item["stu_num"],
        //             "stu_num_score"  =>$item["stu_num_score"]
        //         ]);

        //     }else{
        //         $task->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[
        //             "level_before"=>$item["level"],
        //             // "lesson_count"=>$item["lesson_count"]*100,
        //             "lesson_count_score"=>$item["lesson_count_score"],
        //             // "cc_test_num"=>$item["cc_test_num"],
        //             // "cc_order_num" =>$item["cc_order_num"],
        //             // "cc_order_per" =>$item["cc_order_per"],
        //             "cc_order_score" =>$item["cc_order_score"],
        //             // "other_test_num"=>$item["other_test_num"],
        //             // "other_order_num" =>$item["other_order_num"],
        //             // "other_order_per" =>$item["other_order_per"],
        //             "other_order_score" =>$item["other_order_score"],
        //             "record_final_score"=>$item["record_final_score"],
        //             // "record_score_avg" =>$item["record_score_avg"],
        //             // "record_num"     =>$item["record_num"],
        //             // "is_refund"      =>$item["is_refund"],
        //             "total_score"    =>$item["total_score"],
        //             // "teacher_money_type"=>$item["teacher_money_type"],
        //             // "stu_num"        =>$item["stu_num"],
        //             "stu_num_score"  =>$item["stu_num_score"],
        //             "reach_flag"     =>$item["reach_flag"],
        //             "withhold_money" =>$item["withhold_money"]*100
        //         ]);
        //         echo 111;

        //     }

        // }

        // // $ret_info = $task->t_teacher_advance_list->get_info_by_teacher_money_type($start_time,$teacher_money_type);
        // dd(4444);
        // dd($ret_info);

         
        

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
