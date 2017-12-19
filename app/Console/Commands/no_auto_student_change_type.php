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

             
        $list = $task->t_child_order_info->get_all_payed_prder_info();
        foreach($list as $val){
            $competition_flag = $val["competition_flag"];
            if($competition_flag==1){
                $courseid = "SHLEOZ3101006";
                $arr =[4=>[$courseid]];
                $coursename = "思维拓展在线课程";
            }elseif($val["grade"] >=100 && $val["grade"]<200){
                $courseid = "SHLEOZ3101001";
                $arr =[1=>[$courseid]];
                $coursename = "小学在线课程";
            }elseif($val["grade"] >=200 && $val["grade"]<300){
                $courseid = "SHLEOZ3101011";
                $arr =[2=>[$courseid]];
                $coursename = "初中在线课程";
            }elseif($val["grade"] >=300 && $val["grade"]<400){
                $courseid = "SHLEOZ3101016";
                $arr =[3=>[$courseid]];
                $coursename = "高中在线课程";
            }
            $str = json_encode($arr);
            $task->t_parent_info->field_update_list($val["parentid"],[
                "baidu_class_info" => $str
            ]);

        }
        dd(1111);

        // $start_time = strtotime("2017-09-01");
        // $end_time = strtotime("2017-12-01");
        // $teacher_money_type=6;
        // $list     = $task->t_teacher_info->get_teacher_info_by_money_type($teacher_money_type,$start_time,$end_time);
        // $tea_list = [];
        // foreach($list as $val){
        //     $tea_list[] = $val["teacherid"];
        // }

        // $page_info=1;
        // $ret_info = $task->t_teacher_info->get_teacher_level_info($page_info,$tea_list,$start_time);
        // $tea_arr=[];
        // foreach($ret_info["list"] as $val){
        //     $tea_arr[]=$val["teacherid"];
        // }

        // $test_person_num        = $task->t_lesson_info->get_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);
        // $kk_test_person_num     = $task->t_lesson_info->get_kk_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);
        // $change_test_person_num = $task->t_lesson_info->get_change_teacher_test_person_num_list(
        //     $start_time,$end_time,-1,-1,$tea_arr);
        // $teacher_record_score = $task->t_teacher_record_list->get_test_lesson_record_score($start_time,$end_time,$tea_arr,1);
        // $tea_refund_info      = $task->get_tea_refund_info($start_time,$end_time,$tea_arr);
        // foreach($ret_info["list"] as &$item){
        //     \App\Helper\Utils::unixtime2date_for_item($item,"accept_time","_str");
        //     \App\Helper\Utils::unixtime2date_for_item($item,"require_time","_str");
        //     //  E\Eaccept_flag::set_item_value_str($item);

        //     $teacherid = $item["teacherid"];
        //     $item["lesson_count"] = round($list[$teacherid]["lesson_count"]/300,1);
        //     //  $item["lesson_count_score"] = $task->get_score_by_lesson_count($item["lesson_count"]);
        //     $item["stu_num"] = $list[$teacherid]["stu_num"];
        //     //  $item["stu_num_score"] = $task->get_stu_num_score($item["stu_num"]);

        //     $item["cc_test_num"]    = isset($test_person_num[$teacherid])?$test_person_num[$teacherid]["person_num"]:0;
        //     $item["cc_order_num"]   = isset($test_person_num[$teacherid])?$test_person_num[$teacherid]["have_order"]:0;
        //     $item["cc_order_per"]   = !empty($item["cc_test_num"])?round($item["cc_order_num"]/$item["cc_test_num"]*100,2):0;
        //     //  $item["cc_order_score"] = $task->get_cc_order_score($item["cc_order_num"],$item["cc_order_per"]);
        //     $item["other_test_num"] = (isset($kk_test_person_num[$teacherid])?$kk_test_person_num[$teacherid]["kk_num"]:0)+(isset($change_test_person_num[$teacherid])?$change_test_person_num[$teacherid]["change_num"]:0);
        //     $item["other_order_num"] = (isset($kk_test_person_num[$teacherid])?$kk_test_person_num[$teacherid]["kk_order"]:0)+(isset($change_test_person_num[$teacherid])?$change_test_person_num[$teacherid]["change_order"]:0);
        //     $item["other_order_per"] = !empty($item["other_test_num"])?round($item["other_order_num"]/$item["other_test_num"]*100,2):0;
        //     //  $item["other_order_score"] = $task->get_other_order_score($item["other_order_num"],$item["other_order_per"]);
        //     $item["record_num"] = isset($teacher_record_score[$teacherid])?$teacher_record_score[$teacherid]["num"]:0;
        //     $item["record_score"] = isset($teacher_record_score[$teacherid])?$teacher_record_score[$teacherid]["score"]:0;
        //     $item["record_score_avg"] = !empty($item["record_num"])?round($item["record_score"]/$item["record_num"],1):0;
        //     $item["record_final_score"] = !empty($item["record_num"])?ceil($item["record_score_avg"]*0.3):18;
        //     $item["is_refund"] = (isset($tea_refund_info[$teacherid]) && $tea_refund_info[$teacherid]>0)?1:0;
        //     $item["is_refund_str"] = $item["is_refund"]==1?"<font color='red'>有</font>":"无";
        //     // $item["total_score"] = $item["lesson_count_score"]+$item["cc_order_score"]+ $item["other_order_score"]+$item["record_final_score"]+$item["stu_num_score"];
        //     $item["hand_flag"]=0;
        //     // if($item["teacher_money_type"]==6){
        //     //     E\Enew_level::set_item_value_str($item,"level");
        //     //     E\Enew_level::set_item_value_str($item,"level_after");
        //     // }else{
        //     //     E\Elevel::set_item_value_str($item,"level");
        //     //     E\Elevel::set_item_value_str($item,"level_after");
        //     // }
        //     $exists = $task->t_teacher_advance_list->field_get_list_2($start_time,$teacherid,"teacherid");
        //     if(!$exists){
        //         $task->t_teacher_advance_list->row_insert([
        //             "start_time" =>$start_time,
        //             "teacherid"  =>$teacherid,
        //             "level_before"=>$item["level"],
        //             "lesson_count"=>$item["lesson_count"]*100,
        //             "lesson_count_score"=>@$item["lesson_count_score"],
        //             "cc_test_num"=>$item["cc_test_num"],
        //             "cc_order_num" =>$item["cc_order_num"],
        //             "cc_order_per" =>$item["cc_order_per"],
        //             "cc_order_score" =>@$item["cc_order_score"],
        //             "other_test_num"=>$item["other_test_num"],
        //             "other_order_num" =>$item["other_order_num"],
        //             "other_order_per" =>$item["other_order_per"],
        //             "other_order_score" =>@$item["other_order_score"],
        //             "record_final_score"=>@$item["record_final_score"],
        //             "record_score_avg" =>$item["record_score_avg"],
        //             "record_num"     =>$item["record_num"],
        //             "is_refund"      =>$item["is_refund"],
        //             "total_score"    =>@$item["total_score"],
        //             "teacher_money_type"=>$item["teacher_money_type"],
        //             "stu_num"        =>$item["stu_num"],
        //             "stu_num_score"  =>@$item["stu_num_score"]
        //         ]);

        //     }

        // }
        // dd(111111);


        

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
