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
        $start_time = strtotime("2017-12-01");
        $end_time = strtotime("2017-12-21");

        $all_total = $system_total=$self_total=$no_call_total=0;
        $ret_info = $task->t_manager_info->get_admin_work_status_info(8);

        foreach($ret_info as $i=>$val){ // 傅文莉要求在首页不显示ta
            if($val['uid'] == 967){
                unset($ret_info[$i]);
            }
        }
        if($end_time >= strtotime("2017-11-09")){
            $ret_info[] =["uid"=>1250,"account"=>"招师其他","name"=>"招师其他","admin_work_status"=>0];
        }
        if($end_time <= strtotime("2018-01-01")){
            $ret_info[] =["uid"=>513,"account"=>"徐月","name"=>"徐月","admin_work_status"=>0];
        }

        $zs_entry_list=$zs_video_list = $zs_one_list= $ret_info;

        $list  = $task->t_teacher_lecture_appointment_info->tongji_teacher_lecture_appoiment_info_by_accept_adminid($start_time,$end_time);
        $list1  = $task->t_teacher_lecture_appointment_info->tongji_no_call_count_by_accept_adminid();


        $video_account = $task->t_teacher_lecture_info->get_lecture_info_by_zs_new($start_time,$end_time);
        $video_account_real = $task->t_teacher_lecture_info->get_lecture_info_by_zs($start_time,$end_time,-2);
        $video_account_pass = $task->t_teacher_lecture_info->get_lecture_info_by_zs($start_time,$end_time,1);
        $one_account = $task->t_teacher_record_list->get_all_interview_count_by_zs($start_time,$end_time,-1);
        $one_account_real = $task->t_teacher_record_list->get_all_interview_count_by_zs($start_time,$end_time,-2);
        $one_account_pass = $task->t_teacher_record_list->get_all_interview_count_by_zs($start_time,$end_time,1);
        foreach($ret_info as $k=>&$item){
            $accept_adminid       = $item["uid"];
            $item["all_count"] = @$list[$accept_adminid]["all_count"];
            $item["no_call_count"] = @$list1[$accept_adminid]["no_call_count"];
            $reference = $task->get_zs_reference($accept_adminid);
            $item["self_count"] = $task->t_teacher_lecture_appointment_info->get_self_count($reference,$start_time,$end_time);
            $item["system_count"] = $item["all_count"]-$item["self_count"];
            $all_total   += $item["all_count"];
            $no_call_total   += $item["no_call_count"];
            $system_total   += $item["system_count"];
            $self_total   += $item["self_count"];
            $item["video_account"] = @$video_account[$accept_adminid]["all_count"];
            $item["video_account_real"] = @$video_account_real[$accept_adminid]["all_count"];
            $item["video_account_pass"] = @$video_account_pass[$accept_adminid]["all_count"];
            $item["one_account"] = @$one_account[$accept_adminid]["all_count"];
            $item["one_account_real"] = @$one_account_real[$accept_adminid]["all_count"];
            $item["one_account_pass"] = @$one_account_pass[$accept_adminid]["all_count"];
            $item["video_per"] = !empty( $item["video_account_real"] )?round( $item["video_account_pass"]/$item["video_account_real"]*100,2):0;
            $item["one_per"] = !empty( $item["one_account_real"] )?round( $item["one_account_pass"]/$item["one_account_real"]*100,2):0;
            $item["all_per"] = !empty( $item["one_account_real"]+$item["video_account_real"] )?round( ($item["one_account_pass"]+$item["video_account_pass"])/($item["one_account_real"]+$item["video_account_real"])*100,2):0;
        }

        \App\Helper\Utils::order_list( $ret_info,"all_per", 0 );
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

        \App\Helper\Utils::order_list( $ret_info,"all_per", 0 );
        $data["video_per"] = !empty($data["video_real"])?round($data["video_succ"]/$data["video_real"]*100,2):0;
        $data["one_per"] = !empty($data["one_real"])?round($data["one_succ"]/$data["one_real"]*100,2):0;


        $video_pass = $one_pass=[];
        for($i=1;$i<=10;$i++){
            $video_pass[$i] = $task->t_teacher_lecture_info->get_teacher_passed_num_by_subject_grade($start_time,$end_time,$i);
            $one_pass[$i] = $task->t_teacher_record_list->get_teacher_passes_num_by_subject_grade($start_time,$end_time,$i);
            // 入职人数
            $entry_pass[$i] = $task->t_teacher_info->get_teacher_passes_num_by_subject_grade($start_time,$end_time,$i);
        }
        foreach($zs_video_list as &$ui){
            $uid      = $ui["uid"];
            $ui["xxyw"] = @$video_pass[1][$uid]["primary_num"];
            $ui["czyw"] = @$video_pass[1][$uid]["middle_num"];
            $ui["gzyw"] = @$video_pass[1][$uid]["senior_num"];
            $ui["xxsx"] = @$video_pass[2][$uid]["primary_num"];
            $ui["czsx"] = @$video_pass[2][$uid]["middle_num"];
            $ui["gzsx"] = @$video_pass[2][$uid]["senior_num"];
            $ui["xxyy"] = @$video_pass[3][$uid]["primary_num"];
            $ui["czyy"] = @$video_pass[3][$uid]["middle_num"];
            $ui["gzyy"] = @$video_pass[3][$uid]["senior_num"];
            $ui["czhx"] = @$video_pass[4][$uid]["middle_num"];
            $ui["gzhx"] = @$video_pass[4][$uid]["senior_num"];
            $ui["czwl"] = @$video_pass[5][$uid]["middle_num"];
            $ui["gzwl"] = @$video_pass[5][$uid]["senior_num"];
            $ui["czsw"] = @$video_pass[6][$uid]["middle_num"];
            $ui["gzsw"] = @$video_pass[6][$uid]["senior_num"];
            $ui["kx"] = @$video_pass[10][$uid]["primary_num"]+@$video_pass[10][$uid]["middle_num"]+@$video_pass[10][$uid]["senior_num"];
            $ui["other"] = @$video_pass[7][$uid]["primary_num"]+@$video_pass[7][$uid]["middle_num"]+@$video_pass[7][$uid]["senior_num"]+@$video_pass[8][$uid]["primary_num"]+@$video_pass[8][$uid]["middle_num"]+@$video_pass[8][$uid]["senior_num"]+@$video_pass[9][$uid]["primary_num"]+@$video_pass[9][$uid]["middle_num"]+@$video_pass[9][$uid]["senior_num"];

        }

        foreach($zs_one_list as &$uy){
            $uid      = $uy["uid"];
            $uy["xxyw"] = @$one_pass[1][$uid]["primary_num"];
            $uy["czyw"] = @$one_pass[1][$uid]["middle_num"];
            $uy["gzyw"] = @$one_pass[1][$uid]["senior_num"];
            $uy["xxsx"] = @$one_pass[2][$uid]["primary_num"];
            $uy["czsx"] = @$one_pass[2][$uid]["middle_num"];
            $uy["gzsx"] = @$one_pass[2][$uid]["senior_num"];
            $uy["xxyy"] = @$one_pass[3][$uid]["primary_num"];
            $uy["czyy"] = @$one_pass[3][$uid]["middle_num"];
            $uy["gzyy"] = @$one_pass[3][$uid]["senior_num"];
            $uy["czhx"] = @$one_pass[4][$uid]["middle_num"];
            $uy["gzhx"] = @$one_pass[4][$uid]["senior_num"];
            $uy["czwl"] = @$one_pass[5][$uid]["middle_num"];
            $uy["gzwl"] = @$one_pass[5][$uid]["senior_num"];
            $uy["czsw"] = @$one_pass[6][$uid]["middle_num"];
            $uy["gzsw"] = @$one_pass[6][$uid]["senior_num"];
            $uy["kx"] = @$one_pass[10][$uid]["primary_num"]+@$one_pass[10][$uid]["middle_num"]+@$one_pass[10][$uid]["senior_num"];
            $uy["other"] = @$one_pass[7][$uid]["primary_num"]+@$one_pass[7][$uid]["middle_num"]+@$one_pass[7][$uid]["senior_num"]+@$one_pass[8][$uid]["primary_num"]+@$one_pass[8][$uid]["middle_num"]+@$one_pass[8][$uid]["senior_num"]+@$one_pass[9][$uid]["primary_num"]+@$one_pass[9][$uid]["middle_num"]+@$one_pass[9][$uid]["senior_num"];

        }

        $entry_total = 0;
        foreach($zs_entry_list as &$uy){
            $uid      = $uy["uid"];
            $uy["xxyw"] = @$entry_pass[1][$uid]["primary_num"];
            $uy["czyw"] = @$entry_pass[1][$uid]["middle_num"];
            $uy["gzyw"] = @$entry_pass[1][$uid]["senior_num"];
            $uy["xxsx"] = @$entry_pass[2][$uid]["primary_num"];
            $uy["czsx"] = @$entry_pass[2][$uid]["middle_num"];
            $uy["gzsx"] = @$entry_pass[2][$uid]["senior_num"];
            $uy["xxyy"] = @$entry_pass[3][$uid]["primary_num"];
            $uy["czyy"] = @$entry_pass[3][$uid]["middle_num"];
            $uy["gzyy"] = @$entry_pass[3][$uid]["senior_num"];
            $uy["czhx"] = @$entry_pass[4][$uid]["middle_num"];
            $uy["gzhx"] = @$entry_pass[4][$uid]["senior_num"];
            $uy["czwl"] = @$entry_pass[5][$uid]["middle_num"];
            $uy["gzwl"] = @$entry_pass[5][$uid]["senior_num"];
            $uy["czsw"] = @$entry_pass[6][$uid]["middle_num"];
            $uy["gzsw"] = @$entry_pass[6][$uid]["senior_num"];
            $uy["kx"] = @$entry_pass[10][$uid]["primary_num"]+@$entry_pass[10][$uid]["middle_num"]+@$entry_pass[10][$uid]["senior_num"];
            $uy["other"] = @$entry_pass[7][$uid]["primary_num"]+@$entry_pass[7][$uid]["middle_num"]+@$entry_pass[7][$uid]["senior_num"]+@$entry_pass[8][$uid]["primary_num"]+@$entry_pass[8][$uid]["middle_num"]+@$entry_pass[8][$uid]["senior_num"]+@$entry_pass[9][$uid]["primary_num"]+@$entry_pass[9][$uid]["middle_num"]+@$entry_pass[9][$uid]["senior_num"];
            $entry_total += $uy['xxyw'] + $uy['czyw'] + $uy['gzyw'] + $uy['xxsx'] + $uy['czsx'] + $uy['gzsx'] + $uy['xxyy'] + $uy['czyy'] + $uy['gzyy'] + $uy['czhx'] + $uy['gzhx'] + $uy['czwl'] + $uy['gzwl'] + $uy['czsw'] + $uy['gzsw'] + $uy['kx'] + $uy['other'];
        }

        // print_r($zs_video_list);
        //print_r($zs_one_list);
        // dd($rrrr);



        //$this->set_filed_for_js("acc_name",$this->get_account());

        dd([
            "ret_info"    => $ret_info,
            "all_total"   => $all_total,
            "no_call_total"   => $no_call_total,
            "system_total"   => $system_total,
            "self_total"   => $self_total,
            "data"        =>$data,
            // "zs_one_list" =>@$zs_one_list,
            // "zs_video_list"=>@$zs_video_list,
            // "zs_entry_list" => @$zs_entry_list,
            "entry_total" => @$entry_total,
        ]);

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
