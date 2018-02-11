<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class update_teaching_core_data extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_teaching_core_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '教学事业部核心数据指标';

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
        list($date_1['y'],$date_1['m'])=explode("-",date('Y-m',strtotime("2016-12-01")));
        list($date_2['y'],$date_2['m'])=explode("-",date('Y-m',time()-86400));
        $month_num =  abs($date_1['y']-$date_2['y'])*12 +$date_2['m']-$date_1['m'];
        $month_st = $month_num-2;
        if($month_st<1){
            $month_st=1;
        }
        // dd(1111);
        for($i=$month_st;$i<=$month_num;$i++){
        // for($i=1;$i<=13;$i++){

            $time =strtotime("2016-12-01");
            $start_time=strtotime("+".$i." month",$time);
            $end_time = strtotime("+".($i+1)." month",$time);
        // $start_time =strtotime("2017-01-01");
        //  $end_time =strtotime("2017-02-01");


            //新增无常规课但有试听课老师数
            $reg_list = $task->t_lesson_info_b3->get_teacher_list_by_lesson_time($start_time,$end_time,-2);
            $tes_list = $task->t_lesson_info_b3->get_teacher_list_by_lesson_time($start_time,$end_time,2);
            $tea_nu=0;
            foreach($tes_list as $k=>$v){
                if(!isset($reg_list[$k])){
                    $tea_nu++;
                }
            }

                     


            //新老师数(入职)
            $train_through_all = $task->t_teacher_info->tongji_train_through_info($start_time,$end_time);
            //本月上课老师数
            $tea_num_all = $task->t_teacher_info->get_lesson_teacher_total_info($start_time,$end_time);
            //本月新增上课老师数
            $tea_num_new = $task->t_teacher_info->get_lesson_teacher_total_info($start_time,$end_time,1);
            //本月留存上课老师数
            $tea_num_old = $task->t_teacher_info->get_lesson_teacher_total_info($start_time,$end_time,2);
            //本月之前老师总数
            $tea_num_all_old = $task->t_teacher_info->get_tea_num_by_train_through_time($start_time);
            //本月流失上课老师数
            $tea_num_lose = $tea_num_all_old-$tea_num_old;

            //流失老师数(三个月未上课)
            $two_month_time = strtotime(date("Y-m-01",$start_time-45*86400));
            $tea_num_old_three = $task->t_teacher_info->get_lesson_teacher_total_info($start_time,$end_time,3,$two_month_time);
            $tea_num_lose_three = $tea_num_all_old-$tea_num_old_three;

            //在读学生数
            $tea_lesson_info = $task->t_teacher_info->get_teacher_list(1,$start_time,$end_time);
            $read_stu_num = @$tea_lesson_info["stu_num"];
            //师生比
            $tea_stu_num = $tea_lesson_info["stu_num"]>0?round(@$tea_lesson_info["tea_num"]/@$tea_lesson_info["stu_num"],1):0;       
            $tea_stu_per = !empty($tea_stu_num)?"1:".$tea_stu_num:"";

            //试听课老师数
            $tea_num_all_test = $task->t_teacher_info->get_lesson_teacher_total_info($start_time,$end_time,-1,0,2);
            //常规课老师数
            $tea_num_all_normal = $task->t_teacher_info->get_lesson_teacher_total_info($start_time,$end_time,-1,0,-2);


            //试听课学生与教材匹配度
            $list      = $task->t_lesson_info_b3->get_textbook_match_lesson_and_order_list($start_time,$end_time);
            $all_num   = 0;
            $match_num = 0;
            foreach($list as $val){
                $all_num++;
                if($val['textbook']!="" && isset($region_version[$val['textbook']]) ){
                    $stu_textbook = $region_version[$val['textbook']];
                }else{
                    $stu_textbook = $val['editionid'];
                }
                $tea_textbook = explode(",",$val['teacher_textbook']);
                if(in_array($stu_textbook,$tea_textbook)){
                    $match_num++;
                }       
            }
            $match_rate = $all_num>0?round($match_num/$all_num*100,2):0;

            //新老师入职通过率
            $teacher_list_ex = $task->t_teacher_lecture_info->get_teacher_list_passed("",$start_time,$end_time);
            $teacher_arr_ex = $task->t_teacher_record_list->get_teacher_train_passed("",$start_time,$end_time);
            foreach($teacher_arr_ex as $k=>$val){
                if(!isset($teacher_list_ex[$k])){
                    $teacher_list_ex[$k]=$k;
                }
            }
            $all_tea_ex = count($teacher_list_ex);
            $teacher_list_ex_through = $task->t_teacher_lecture_info->get_teacher_list_passed("",$start_time,$end_time,-1,-1,-1,-1,"",-1,1);
            $teacher_arr_ex_through = $task->t_teacher_record_list->get_teacher_train_passed("",$start_time,$end_time,-1,-1,-1,-1,"",-1,1);
            foreach($teacher_arr_ex_through as $k=>$val){
                if(!isset($teacher_list_ex_through[$k])){
                    $teacher_list_ex_through[$k]=$k;
                }
            }
            $all_tea_ex_through = count($teacher_list_ex_through);

            // $video_real =  $task->t_teacher_lecture_info->get_lecture_info_by_all(
            //     -1,$start_time,$end_time,-1,-1,-1,"",-2);
            // $one_real = $task->t_teacher_record_list->get_train_teacher_interview_info_all(
            //     -1,$start_time,$end_time,-1,-1,-1,"",-2);
            // @$video_real["all_count"] += $one_real["all_count"];
            $new_tea_through_per =  $all_tea_ex>0?round($all_tea_ex_through/$all_tea_ex*100,2):0;

            //新老师入职时长
            //$video_time = $task->t_teacher_lecture_info->get_teacher_througn_detail($start_time,$end_time);
            //$one_time = $task->t_teacher_record_list->get_teacher_througn_detail($start_time,$end_time);
                      
            // $num_total = 0;
            // $time_total=0;
            // foreach($video_time as $v){
            //     $num_total++;
            //     $time_total +=$v["time"];
            // }
            // foreach($one_time as $v){
            //     $num_total++;
            //     $time_total +=$v["time"];
            // }
            // $through_avg_time = $num_total>0?round($time_total/$num_total/86400,1):0;
            $train_through_time_all = $task->t_teacher_info->get_train_through_time_new($start_time,$end_time);
            $through_avg_time = round($train_through_time_all/86400,1);


            //新老师30天留存率/转化率/平均课耗数
            $new_teacher_thirty = $task->t_teacher_info->get_new_teacher_test_info($start_time,$end_time,30);
            $thirty_stay_per =  @$train_through_all["through_all"]>0?round(@$new_teacher_thirty["tea_num"]/$train_through_all["through_all"]*100,2):0;
            $thirty_tran_per =  @$new_teacher_thirty["person_num"]>0?round(@$new_teacher_thirty["have_order"]/@$new_teacher_thirty["person_num"]*100,2):0;
            $thirty_lesson_count_info = $task->t_teacher_info->get_new_teacher_lesson_count_info($start_time,$end_time,30);
            $thirty_lesson_count = @$thirty_lesson_count_info["tea_num"]>0?round(@$thirty_lesson_count_info["all_count"]/$thirty_lesson_count_info["tea_num"]):0;
            //新老师60天留存率/转化率
            $new_teacher_sixty = $task->t_teacher_info->get_new_teacher_test_info($start_time,$end_time,60);
            $sixty_stay_per =  @$train_through_all["through_all"]>0?round(@$new_teacher_sixty["tea_num"]/$train_through_all["through_all"]*100,2):0;
            $sixty_tran_per =  @$new_teacher_sixty["person_num"]>0?round(@$new_teacher_sixty["have_order"]/@$new_teacher_sixty["person_num"]*100,2):0;
             $sixty_lesson_count_info = $task->t_teacher_info->get_new_teacher_lesson_count_info($start_time,$end_time,60);
            $sixty_lesson_count = @$sixty_lesson_count_info["tea_num"]>0?round(@$sixty_lesson_count_info["all_count"]/$sixty_lesson_count_info["tea_num"]):0;

            //新老师90天留存率/转化率
            $new_teacher_ninty = $task->t_teacher_info->get_new_teacher_test_info($start_time,$end_time,90);
            $ninty_stay_per =  @$train_through_all["through_all"]>0?round(@$new_teacher_ninty["tea_num"]/$train_through_all["through_all"]*100,2):0;
            $ninty_tran_per =  @$new_teacher_ninty["person_num"]>0?round(@$new_teacher_ninty["have_order"]/@$new_teacher_ninty["person_num"]*100,2):0;
            $ninty_lesson_count_info = $task->t_teacher_info->get_new_teacher_lesson_count_info($start_time,$end_time,90);
            $ninty_lesson_count = @$ninty_lesson_count_info["tea_num"]>0?round(@$ninty_lesson_count_info["all_count"]/$ninty_lesson_count_info["tea_num"]):0;

            //面试邀约数/面试邀约时长
            $ret = $task->t_teacher_lecture_appointment_info->get_teacher_appoinment_interview_info($start_time,$end_time);
            $app_num = count($ret);
            $plan_num=$plan_time =0;
            foreach($ret as $val){
                $time = $val["add_time"];
                if($val["lesson_start"]>0 && $val["lesson_start"]<$val["add_time"]){
                    $time = $val["lesson_start"];
                }
                if($time>0){
                    $plan_num++;
                    $plan_time += ($time-$val["answer_begin_time"]);
                }
            
            }
            $app_time = $plan_num>0?round($plan_time/$plan_num/86400,1):0;
            //面试通过数/面试通过时长
            $ret_interview = $task->t_teacher_lecture_appointment_info->get_teacher_appoinment_interview_pass_info($start_time,$end_time);
            $interview_pass_num = count($ret_interview);
            $interview_pass_time =0;
            $train_num=0;
            $train_time=0;
            $train_real_num=0;
            $trail_num=0;
            $trail_time=0;
            $through_num=0;
            $through_real_num=0;
            $through_time=0;
            foreach($ret_interview as $val){
                if($val["lesson_start"]>0 && $val["one_add_time"]<$val["confirm_time"] && $val["one_add_time"]>$val["lesson_start"]){
                    $time = $val["one_add_time"]-$val["lesson_start"];
                }elseif($val["confirm_time"]>$val["add_time"]){
                    $time = $val["confirm_time"]-$val["add_time"]; 
                }
                $interview_pass_time +=$time;
                if($val["train_add_time"]>0 ){
                    $train_num++;
                    $tr_time=0;
                    if($val["one_add_time"]>0 && $val["one_add_time"]<$val["confirm_time"] && $val["train_add_time"]>$val["one_add_time"]){
                        $tr_time= $val["train_add_time"]-$val["one_add_time"];
                        $train_real_num++;
                    }elseif($val["confirm_time"]>0 && $val["train_add_time"]>$val["confirm_time"]){
                        $tr_time= $val["train_add_time"]-$val["confirm_time"];
                        $train_real_num++;
                    }
                    $train_time +=$tr_time;

                    if($val["trail_time"]>0){
                        $trail_num++;
                        $trail_time += ($val["trail_time"]-$val["train_add_time"]);
                    }

                
                }
                if($val["train_through_new"]==1){
                    $through_num++;
                    if($val["trail_time"]>0 && $val["simul_test_lesson_pass_time"]>$val["trail_time"]){
                        $through_time += ($val["simul_test_lesson_pass_time"]-$val["trail_time"]);
                        $through_real_num++;
                    }
                }
            
            }

            $interview_pass_time = $interview_pass_num>0?round($interview_pass_time/$interview_pass_num/86400,1):0;

            //新师培训数/时间
            $train_time = $train_real_num>0?round($train_time/$train_real_num/86400,1):0;
            $trail_time = $trail_num>0?round($trail_time/$trail_num/86400,1):0;
            $through_time = $through_real_num>0?round($through_time/$through_real_num/86400,1):0;


            //培训数
            $train_lesson_num = $task->t_lesson_info_b3->get_train_lesson_num($start_time,$end_time);
            //培训参与率,培训通过数
            $train_lesson_part_info = $task->t_lesson_info_b3->get_train_lesson_part_info($start_time,$end_time);
            $all_num= $part_num=$train_tea_num =$train_through_num=0;
            $train_tea_list = [];
        
            foreach($train_lesson_part_info as $val){
                $all_num++;
                if($val["opt_time"]>0){
                    $part_num++; 
                }
                if(!isset($train_tea_list[$val["userid"]])){
                    @$train_tea_list[$val["userid"]]=$val["userid"];
                    $train_tea_num++;
                    if($val["simul_test_lesson_pass_time"]>0){
                        $train_through_num++;
                    }
                }
            }
            $train_part_per =  $all_num>0?round($part_num/$all_num*100,2):0;
            $train_through_per =  $train_tea_num>0?round($train_through_num/$train_tea_num*100,2):0;


            //教务数据
            $set_count_all=$set_count_top=$set_count_green=$set_count_grab=$set_count_normal=$set_lesson_time_all=0;
            $set_count_seller =$set_count_kk=$set_count_hls=0;
            $ret_info   = $task->t_test_lesson_subject_require->get_jw_teacher_test_lesson_info($start_time,$end_time);
            foreach($ret_info as $val){
                $set_count_all+=$val["set_count"];
                $set_count_top+=$val["top_count"];
                $set_count_green+=$val["green_count"];
                $set_count_grab+=$val["grab_count"];
                $set_lesson_time_all+=$val["set_lesson_time_all"];
                $set_count_seller+=$val["seller_count_set"];
                $set_count_kk+=$val["ass_kk_count_set"];
                $set_count_hls+=$val["ass_hls_count_set"];
            }
            $set_count_normal=$set_count_all-$set_count_top- $set_count_green-$set_count_grab;
            // $all_tran    = $task->t_test_lesson_subject_require->get_teat_lesson_transfor_info_type_total($start_time,$end_time);
            // $seller_tran = $task->t_test_lesson_subject_require->get_teat_lesson_transfor_info_type_total($start_time,$end_time,2);
            // $kk_tran = $task->t_test_lesson_subject_require->get_teat_lesson_transfor_info_type_total($start_time,$end_time,1,1);
            // $hls_tran = $task->t_test_lesson_subject_require->get_teat_lesson_transfor_info_type_total($start_time,$end_time,1,2);
            // $top_tran = $task->t_test_lesson_subject_require->get_teat_lesson_transfor_info_type_total($start_time,$end_time,-1,-1,1);
            // $green_tran = $task->t_test_lesson_subject_require->get_teat_lesson_transfor_info_type_total($start_time,$end_time,-1,-1,2);
            // $grab_tran = $task->t_test_lesson_subject_require->get_teat_lesson_transfor_info_type_total($start_time,$end_time,-1,-1,3);
            // $normal_tran = $task->t_test_lesson_subject_require->get_teat_lesson_transfor_info_type_total($start_time,$end_time,-1,-1,4);
            $top_seller_total = $task->t_lesson_info_b3->get_seller_test_lesson_tran_info( $start_time,$end_time,4,1); //咨询/老师1000精排总体
            $top_tran_per = !empty($top_seller_total["person_num"])?round($top_seller_total["have_order"]/$top_seller_total["person_num"]*100,2):0;
            $green_top_seller_total = $task->t_lesson_info_b3->get_seller_test_lesson_tran_info( $start_time,$end_time,5,1); //咨询/老师1000精排总体
            $green_top_tran_per = !empty($green_top_seller_total["person_num"])?round($green_top_seller_total["have_order"]/$green_top_seller_total["person_num"]*100,2):0;

            $green_seller_total = $task->t_lesson_info_b3->get_seller_test_lesson_tran_info( $start_time,$end_time,2,1); //咨询/老师绿色通道总体
            $green_tran_per = !empty($green_seller_total["person_num"])?round($green_seller_total["have_order"]/$green_seller_total["person_num"]*100,2):0;

            $normal_seller_total_grab = $task->t_lesson_info_b3->get_seller_test_lesson_tran_info( $start_time,$end_time,3,1,1); //咨询/老师普通排课总体(抢课)
            $grab_tran_per = !empty($normal_seller_total_grab["person_num"])?round($normal_seller_total_grab["have_order"]/$normal_seller_total_grab["person_num"]*100,2):0;

            $normal_seller_total = $task->t_lesson_info_b3->get_seller_test_lesson_tran_info( $start_time,$end_time,3,1,0); //咨询/老师普通排课总体(非抢课)
            $normal_tran_per = !empty($normal_seller_total["person_num"])?round($normal_seller_total["have_order"]/$normal_seller_total["person_num"]*100,2):0;

            $change_test_person_num= $task->t_lesson_info->get_change_teacher_test_person_num_list_total( $start_time,$end_time,-1,-1,-1,-1,-1,"",-1,-1,-1);
            $hls_tran_per = !empty(@$change_test_person_num["change_num"])?round($change_test_person_num["change_order"]/$change_test_person_num["change_num"]*100,2):0;
            $kk_test_person_num= $task->t_lesson_info->get_kk_teacher_test_person_num_list_total( $start_time,$end_time,-1,-1,-1,-1,-1,"",-1,-1,-1,-1);
            $kk_tran_per = !empty(@$kk_test_person_num["kk_num"])?round($kk_test_person_num["kk_order"]/$kk_test_person_num["kk_num"]*100,2):0;
            $scc_test_info =$task->t_lesson_info->get_teacher_test_person_num_list_total($start_time,$end_time);

            $seller_tran_per = !empty(@$scc_test_info["person_num"])?round($scc_test_info["have_order"]/$scc_test_info["person_num"]*100,2):0;
            
            $success_test_lesson_list_total = $task->t_lesson_info->get_success_test_lesson_list_new_total($start_time,$end_time,-1,-1,-1,-1,-1,"",-1,-1,-1);
            $all_tran_per = !empty(@$success_test_lesson_list_total["success_lesson"])?round($success_test_lesson_list_total["order_number"]/$success_test_lesson_list_total["success_lesson"]*100,2):0;
            $jw_num = count($ret_info);
            $set_count_avg = $jw_num>0?round($set_count_all/$jw_num,1):0;
            $set_time_avg = $set_count_all>0?round($set_lesson_time_all/$set_count_all/86400,1):0;

            //抢课数据
            $grab_info = $task->t_grab_lesson_link_visit_operation->get_teacher_grab_result_info($start_time,$end_time);
            $grab_success_per =  @$grab_info["all_num"]>0?round(@$grab_info["success_num"]/$grab_info["all_num"]*100,2):0;

            //运营数据
            $lesson_list = $task->t_lesson_info_b2->get_lesson_info_teacher_check_total($start_time,$end_time );
            $teacher_come_late_count = @$lesson_list["teacher_come_late_count"];
            $teacher_change_lesson = @$lesson_list["teacher_change_lesson"];
            $teacher_leave_lesson = @$lesson_list["teacher_leave_lesson"];
            $teacher_come_late_per = @$lesson_list["all_num"]>0?round(@$teacher_come_late_count/$lesson_list["all_num"]*100,2):0;
            $teacher_change_per = @$lesson_list["normal_num"]>0?round(@$teacher_change_lesson/$lesson_list["normal_num"]*100,2):0;
            $teacher_leave_per = @$lesson_list["normal_num"]>0?round(@$teacher_leave_lesson/$lesson_list["normal_num"]*100,2):0;

            //换老师申请
            // $change_test_person_num= $task->t_lesson_info->get_change_teacher_test_person_num_list_total( $start_time,$end_time,-1,-1,-1,-1,-1,"",-1,-1,-1);
            $change_tea_num = @$change_test_person_num["change_order"];
            $change_tea_per = $tea_num_all_normal>0?round($change_tea_num/$tea_num_all_normal*100,2):0;

            //老师退费人数
            $list = $task->t_order_refund->get_tea_refund_info_new($start_time,$end_time,[],1);
            $arr=[];
            foreach($list as $val){
                if(($val["value"]=="教学部" || $val["value"]=="老师管理") && $val["score"]>0){
                    @$arr[$val["teacherid"]]++;
                }
            }
            $refund_tea_num = count($arr);

            //常规课数大于30/60/90/120人数
            $thirty_tea_list = $task->t_teacher_info->get_lesson_teacher_total_by_count($start_time,$end_time,30);        
            $thirty = count($thirty_tea_list);
            $sixty_tea_list = $task->t_teacher_info->get_lesson_teacher_total_by_count($start_time,$end_time,60);
            $sixty = count($sixty_tea_list);
            $ninty_tea_list = $task->t_teacher_info->get_lesson_teacher_total_by_count($start_time,$end_time,90);
            $ninty = count($ninty_tea_list);
            $twl_tea_list = $task->t_teacher_info->get_lesson_teacher_total_by_count($start_time,$end_time,120);
            $twl = count($twl_tea_list);

            //流失老师按科目分
            $subject=1;
            $tea_num_all_old = $task->t_teacher_info->get_tea_num_by_train_through_time($start_time,$subject);
            $tea_num_old_three = $task->t_teacher_info->get_lesson_teacher_total_info($start_time,$end_time,3,$two_month_time,-1,$subject);
            $tea_num_lose_three_yuwen = $tea_num_all_old-$tea_num_old_three;
            $subject=2;
            $tea_num_all_old = $task->t_teacher_info->get_tea_num_by_train_through_time($start_time,$subject);
            $tea_num_old_three = $task->t_teacher_info->get_lesson_teacher_total_info($start_time,$end_time,3,$two_month_time,-1,$subject);
            $tea_num_lose_three_shuxue = $tea_num_all_old-$tea_num_old_three;
            $subject=3;
            $tea_num_all_old = $task->t_teacher_info->get_tea_num_by_train_through_time($start_time,$subject);
            $tea_num_old_three = $task->t_teacher_info->get_lesson_teacher_total_info($start_time,$end_time,3,$two_month_time,-1,$subject);
            $tea_num_lose_three_yingyu = $tea_num_all_old-$tea_num_old_three;
            $subject=4;
            $tea_num_all_old = $task->t_teacher_info->get_tea_num_by_train_through_time($start_time,$subject);
            $tea_num_old_three = $task->t_teacher_info->get_lesson_teacher_total_info($start_time,$end_time,3,$two_month_time,-1,$subject);
            $tea_num_lose_three_huaxue = $tea_num_all_old-$tea_num_old_three;
            $subject=5;
            $tea_num_all_old = $task->t_teacher_info->get_tea_num_by_train_through_time($start_time,$subject);
            $tea_num_old_three = $task->t_teacher_info->get_lesson_teacher_total_info($start_time,$end_time,3,$two_month_time,-1,$subject);
            $tea_num_lose_three_wuli = $tea_num_all_old-$tea_num_old_three;
            $subject=-2;
            $tea_num_all_old = $task->t_teacher_info->get_tea_num_by_train_through_time($start_time,$subject);
            $tea_num_old_three = $task->t_teacher_info->get_lesson_teacher_total_info($start_time,$end_time,3,$two_month_time,-1,$subject);
            $tea_num_lose_three_zonghe = $tea_num_all_old-$tea_num_old_three;

            //老师投诉处理

            $complaint_info   = $task->t_complaint_info->get_tea_complaint_list_by_product($start_time,$end_time);
            $complaint_num = count($complaint_info);
            $deal_num = $deal_time=0;
            foreach($complaint_info as $val){
                if($val["deal_time"]>0){
                    $deal_time +=($val["deal_time"]-$val["add_time"]);
                    $deal_num++;
                }
            }
            $deal_time = $deal_num>0?round($deal_time/$deal_num/86400,1):0;




            //全职老师相关数据
            $lesson_end_time = $task->get_test_lesson_end_time($end_time);

            $fulltime_teacher_count = $task->t_manager_info->get_fulltime_teacher_num($end_time);
            $ret_platform_teacher_lesson_count = $task->t_lesson_info_b3->get_teacher_list($start_time,$end_time);//统计平台老师总人数/课时
            $platform_teacher_count = $ret_platform_teacher_lesson_count["tea_num"];//统计平台老师总人数
            $fulltime_lesson_count = $task->t_lesson_info_b3->get_teacher_list($start_time,$end_time,1);//统计全职老师总人数/课时
            $fulltime_teacher_student =$fulltime_lesson_count["stu_num"]; //全职老师所带学生总数
            $platform_teacher_student_list= $task->t_student_info->get_total_student_num(-1);//统计平台学生数
            $platform_teacher_student = $platform_teacher_student_list[0]['platform_teacher_student'];
            $ret_info  = $task->t_manager_info->get_research_teacher_list_new(5,-1);
            $qz_tea_arr=[];
            foreach($ret_info as $yy=>$item){
                if($item["teacherid"] != 97313){
                    $qz_tea_arr[] =$item["teacherid"];
                }else{
                    unset($ret_info[$yy]);
                }
            }
            $lesson_count_qz = $task->t_lesson_info_b2->get_teacher_lesson_count_list($start_time,$end_time,$qz_tea_arr);
            $fulltime_teacher_lesson_count=0;
            foreach($lesson_count_qz as $val){
                $fulltime_teacher_lesson_count +=$val["lesson_all"];//全职老师完成的课耗总数
            }
           
            $platform_teacher_lesson_count = $ret_platform_teacher_lesson_count["lesson_count"];//平台所有老师完成的课耗总数

            $test_person_num_total= $task->t_lesson_info->get_teacher_test_person_num_list_total( $start_time,$lesson_end_time);
           
            $qz_tea_list  = $task->t_lesson_info->get_qz_test_lesson_info_list($qz_tea_arr,$start_time, $lesson_end_time);          
            $tran_avg= $lesson_avg=[];
            foreach($ret_info as &$item){
                $item["cc_lesson_num"] =  isset($qz_tea_list[$item["teacherid"]])?$qz_tea_list[$item["teacherid"]]["all_lesson"]:0;
                $item["cc_order_num"] =  isset($qz_tea_list[$item["teacherid"]])?$qz_tea_list[$item["teacherid"]]["order_num"]:0;
                
                @$tran_avg["cc_lesson_num"] +=$item["cc_lesson_num"];
                @$tran_avg["cc_order_num"] +=$item["cc_order_num"];
            }
            $platform_teacher_cc_lesson = @$test_person_num_total['person_num'];
            $fulltime_teacher_cc_lesson  = @$tran_avg["cc_lesson_num"];
            $platform_teacher_cc_order = @$test_person_num_total['have_order'];
            $fulltime_teacher_cc_order  =@$tran_avg["cc_order_num"];

            if(strtotime(date("Y-m-01",time()-86400)) == $start_time){
                $week_time = time();
            }else{
                $week_time = $end_time;
            }
            $date_week                         = \App\Helper\Utils::get_week_range($week_time,1);
            $week_start = $date_week["sdate"]-14*86400;
            $week_end = $date_week["sdate"]+21*86400;          

            $normal_stu_num = $task->t_lesson_info_b2->get_tea_stu_num_list($qz_tea_arr,$week_start,$week_end);
            foreach($normal_stu_num as $val){
                @$ret['fulltime_normal_stu_num'] +=$val["num"];
            }
            $fulltime_normal_stu_num= @$ret['fulltime_normal_stu_num'];
            $normal_stu_num_all = $task->t_lesson_info_b2->get_tea_stu_num_list([],$week_start,$week_end,false);
            foreach($normal_stu_num_all as $val){
                @$ret['platform_normal_stu_num'] +=$val["num"];
            }
            $platform_normal_stu_num= @$ret['platform_normal_stu_num'];

            //上海全职老师数据
            $fulltime_teacher_count_shanghai = $task->t_manager_info->get_fulltime_teacher_num($end_time,1);//上海全职老师人数
            $fulltime_lesson_count_shanghai = $task->t_lesson_info_b3->get_teacher_list($start_time,$end_time,1,1);//统计上海全职老师总人数/课时
            $fulltime_teacher_student_shanghai =$fulltime_lesson_count_shanghai["stu_num"]; //上海全职老师所带学生总数
            $ret_info_shanghai  = $task->t_manager_info->get_research_teacher_list_new(5,1);
            $qz_tea_arr_shanghai=[];
            foreach($ret_info_shanghai as $yy=>$val){
                if($val["teacherid"] != 97313){
                    $qz_tea_arr_shanghai[] =$val["teacherid"];
                }else{
                    unset($ret_info_shanghai[$yy]);
                }
            }
            $lesson_count_qz_shanghai = $task->t_lesson_info_b2->get_teacher_lesson_count_list($start_time,$end_time,$qz_tea_arr_shanghai);
            $fulltime_teacher_lesson_count_shanghai=0;
            foreach($lesson_count_qz_shanghai as $val){
                $fulltime_teacher_lesson_count_shanghai +=$val["lesson_all"];//上海全职老师完成的课耗总数
            }

           
            $qz_tea_list_shanghai  = $task->t_lesson_info->get_qz_test_lesson_info_list($qz_tea_arr_shanghai,$start_time, $lesson_end_time);          
            $tran_avg_shanghai= $lesson_avg_shanghai=[];
            foreach($ret_info_shanghai as $item_pp){
                $item_pp["cc_lesson_num"] =  isset($qz_tea_list_shanghai[$item_pp["teacherid"]])?$qz_tea_list_shanghai[$item_pp["teacherid"]]["all_lesson"]:0;
                $item_pp["cc_order_num"] =  isset($qz_tea_list_shanghai[$item_pp["teacherid"]])?$qz_tea_list_shanghai[$item_pp["teacherid"]]["order_num"]:0;
                
                @$tran_avg_shanghai["cc_lesson_num"] +=$item_pp["cc_lesson_num"];
                @$tran_avg_shanghai["cc_order_num"] +=$item_pp["cc_order_num"];
            }
            $fulltime_teacher_cc_lesson_shanghai  = @$tran_avg_shanghai["cc_lesson_num"];
            $fulltime_teacher_cc_order_shanghai  =@$tran_avg_shanghai["cc_order_num"];        

            $normal_stu_num_shanghai = $task->t_lesson_info_b2->get_tea_stu_num_list($qz_tea_arr_shanghai,$week_start,$week_end);
            foreach($normal_stu_num_shanghai as $val){
                @$ret['fulltime_normal_stu_num_shanghai'] +=$val["num"];
            }
            $fulltime_normal_stu_num_shanghai= @$ret['fulltime_normal_stu_num_shanghai'];

            //武汉全职老师数据
            $fulltime_teacher_count_wuhan = $task->t_manager_info->get_fulltime_teacher_num($end_time,2);//武汉全职老师人数
            $fulltime_lesson_count_wuhan = $task->t_lesson_info_b3->get_teacher_list($start_time,$end_time,1,2);//统计武汉全职老师总人数/课时
            $fulltime_teacher_student_wuhan =$fulltime_lesson_count_wuhan["stu_num"]; //武汉全职老师所带学生总数
            $ret_info_wuhan  = $task->t_manager_info->get_research_teacher_list_new(5,2);
            $qz_tea_arr_wuhan=[];
            foreach($ret_info_wuhan as $yy=>$val){
                if($val["teacherid"] != 97313){
                    $qz_tea_arr_wuhan[] =$val["teacherid"];
                }else{
                    unset($ret_info_wuhan[$yy]);
                }
            }
            $lesson_count_qz_wuhan = $task->t_lesson_info_b2->get_teacher_lesson_count_list($start_time,$end_time,$qz_tea_arr_wuhan);
            $fulltime_teacher_lesson_count_wuhan=0;
            foreach($lesson_count_qz_wuhan as $val){
                $fulltime_teacher_lesson_count_wuhan +=$val["lesson_all"];//武汉全职老师完成的课耗总数
            }

           
            $qz_tea_list_wuhan  = $task->t_lesson_info->get_qz_test_lesson_info_list($qz_tea_arr_wuhan,$start_time, $lesson_end_time);          
            $tran_avg_wuhan= $lesson_avg_wuhan=[];
            foreach($ret_info_wuhan as $item_pp){
                $item_pp["cc_lesson_num"] =  isset($qz_tea_list_wuhan[$item_pp["teacherid"]])?$qz_tea_list_wuhan[$item_pp["teacherid"]]["all_lesson"]:0;
                $item_pp["cc_order_num"] =  isset($qz_tea_list_wuhan[$item_pp["teacherid"]])?$qz_tea_list_wuhan[$item_pp["teacherid"]]["order_num"]:0;
                
                @$tran_avg_wuhan["cc_lesson_num"] +=$item_pp["cc_lesson_num"];
                @$tran_avg_wuhan["cc_order_num"] +=$item_pp["cc_order_num"];
            }
            $fulltime_teacher_cc_lesson_wuhan  = @$tran_avg_wuhan["cc_lesson_num"];
            $fulltime_teacher_cc_order_wuhan  =@$tran_avg_wuhan["cc_order_num"];        

            $normal_stu_num_wuhan = $task->t_lesson_info_b2->get_tea_stu_num_list($qz_tea_arr_wuhan,$week_start,$week_end);
            foreach($normal_stu_num_wuhan as $val){
                @$ret['fulltime_normal_stu_num_wuhan'] +=$val["num"];
            }
            $fulltime_normal_stu_num_wuhan= @$ret['fulltime_normal_stu_num_wuhan'];
           

            $exist_info = $task->t_teaching_core_data->field_get_list_2($start_time,1,"time");
            $data_arr = [
                "new_train_through_num"=>$train_through_all["through_all"],
                "new_teacher_public"   =>$train_through_all["through_gx"],
                "new_teacher_college"   =>$train_through_all["through_gxs"],
                "new_teacher_outfit"   =>$train_through_all["through_jg"],
                "lesson_teacher_num"   =>$tea_num_all,
                "new_lesson_teacher_num"=>$tea_num_new,
                "old_lesson_teacher_num"=>$tea_num_old,
                "lose_teacher_num"      =>$tea_num_lose,
                "lose_teacher_num_three"=>$tea_num_lose_three,
                "read_stu_num"          =>$read_stu_num ,
                "tea_stu_per"           =>$tea_stu_per,
                "test_teacher_num"      =>$tea_num_all_test,
                "normal_teacher_num"    =>$tea_num_all_normal,
                "test_textbook_rate"    =>$match_rate,
                "new_train_through_per" =>$new_tea_through_per,
                "new_train_through_time"=>$through_avg_time,
                "new_tea_thirty_stay_per"=>$thirty_stay_per,
                "new_tea_sixty_stay_per"=>$sixty_stay_per,
                "new_tea_ninty_stay_per"=>$ninty_stay_per,
                "new_tea_thirty_tran_per"=>$thirty_tran_per,
                "new_tea_sixty_tran_per"=>$sixty_tran_per,
                "new_tea_ninty_tran_per"=>$ninty_tran_per,
                "new_tea_thirty_lesson_count" =>$thirty_lesson_count,
                "new_tea_sixty_lesson_count" =>$sixty_lesson_count,
                "new_tea_ninty_lesson_count" =>$ninty_lesson_count,
                "appointment_num"            =>$app_num,
                "appointment_time"           =>$app_time,
                "interview_pass_num"         =>$interview_pass_num,
                "interview_pass_time"        =>$interview_pass_time,
                "new_teacher_train_num"      =>$train_num,
                "new_teacher_train_time"     =>$train_time,
                "simulated_audition_num"     =>$trail_num,
                "simulated_audition_time"     =>$trail_time,
                "new_teacher_train_throuth_num"=>$through_num,
                "new_teacher_train_throuth_time"=>$through_time,
                "all_new_train_num"             =>$train_lesson_num,
                "train_part_per"                =>$train_part_per,
                "train_pass_per"                =>$train_through_per,
                "set_count_all"                 =>$set_count_all,
                "set_count_top"                 =>$set_count_top,
                "set_count_green"               =>$set_count_green,
                "set_count_grab"                =>$set_count_grab,
                "set_count_normal"              =>$set_count_normal,
                "set_count_all_avg"             =>$set_count_avg,
                "set_count_time_avg"           =>$set_time_avg,
                "set_count_all_per"            =>$all_tran_per,
                "set_count_seller_per"         =>$seller_tran_per,
                "set_count_expand_per"         =>$kk_tran_per,
                "set_count_change_per"         =>$hls_tran_per,
                "set_count_top_per"            =>$top_tran_per,
                "set_count_green_per"          =>$green_tran_per,
                "set_count_grab_per"           =>$grab_tran_per,
                "set_count_normal_per"         =>$normal_tran_per,
                "grab_success_per"             =>$grab_success_per ,
                "teacher_late_num"             =>$teacher_come_late_count,
                "teacher_change_num"           =>$teacher_change_lesson,
                "teacher_leave_num"            =>$teacher_leave_lesson,
                "change_tea_num"               =>$change_tea_num,
                "teacher_refund_num"           =>$refund_tea_num,
                "teacher_late_per"             =>$teacher_come_late_per,
                "teacher_change_per"           =>$teacher_change_per,
                "teacher_leave_per"            =>$teacher_leave_per,
                "change_tea_per"               =>$change_tea_per,
                "thirty_lesson_tea_num"        =>$thirty,
                "sixty_lesson_tea_num"         =>$sixty,
                "ninty_lesson_tea_num"         =>$ninty,
                "hundred_twenty_lesson_tea_num"=>$twl,
                "lose_teacher_num_three_chinese"=>$tea_num_lose_three_yuwen,
                "lose_teacher_num_three_math"=>$tea_num_lose_three_shuxue,
                "lose_teacher_num_three_english"=>$tea_num_lose_three_yingyu,
                "lose_teacher_num_three_chem"=>$tea_num_lose_three_huaxue,
                "lose_teacher_num_three_physics"=>$tea_num_lose_three_wuli,
                "lose_teacher_num_three_multiple"=>$tea_num_lose_three_zonghe,
                "tea_complaint_num"               =>$complaint_num,
                "tea_complaint_deal_time"         =>$deal_time,
                "test_no_reg_num" =>$tea_nu,
                "fulltime_teacher_count" =>$fulltime_teacher_count,
                "fulltime_teacher_student" =>$fulltime_teacher_student,
                // "platform_teacher_student" =>$platform_teacher_student ,
                "fulltime_teacher_lesson_count" =>$fulltime_teacher_lesson_count,
                "platform_teacher_lesson_count" =>$platform_teacher_lesson_count,
                "platform_teacher_cc_lesson" =>$platform_teacher_cc_lesson,
                "platform_teacher_cc_order" =>$platform_teacher_cc_order ,
                "fulltime_teacher_cc_lesson" =>$fulltime_teacher_cc_lesson,
                "fulltime_teacher_cc_order" =>$fulltime_teacher_cc_order,
                // "fulltime_normal_stu_num" =>$fulltime_normal_stu_num ,
                //  "platform_normal_stu_num" =>$platform_normal_stu_num,
                "platform_teacher_count" =>$platform_teacher_count,
                "fulltime_teacher_count_wuhan" =>$fulltime_teacher_count_wuhan ,
                "fulltime_teacher_student_wuhan" =>$fulltime_teacher_student_wuhan,
                "fulltime_teacher_lesson_count_wuhan"=>$fulltime_teacher_lesson_count_wuhan,
                "fulltime_teacher_cc_lesson_wuhan"   =>$fulltime_teacher_cc_lesson_wuhan,
                "fulltime_teacher_cc_order_wuhan"    =>$fulltime_teacher_cc_order_wuhan,
                //  "fulltime_normal_stu_num_wuhan"      =>$fulltime_normal_stu_num_wuhan,
                "fulltime_teacher_count_shanghai" =>$fulltime_teacher_count_shanghai ,
                "fulltime_teacher_student_shanghai" =>$fulltime_teacher_student_shanghai,
                "fulltime_teacher_lesson_count_shanghai"=>$fulltime_teacher_lesson_count_shanghai,
                "fulltime_teacher_cc_lesson_shanghai"   =>$fulltime_teacher_cc_lesson_shanghai,
                "fulltime_teacher_cc_order_shanghai"    =>$fulltime_teacher_cc_order_shanghai,
                // "fulltime_normal_stu_num_shanghai"      =>$fulltime_normal_stu_num_shanghai,
            ];
            if(strtotime(date("Y-m-01",time()-86400)) == $start_time){
                $data_arr["fulltime_normal_stu_num"] = $fulltime_normal_stu_num;
                $data_arr["platform_normal_stu_num"] = $platform_normal_stu_num;
                $data_arr["fulltime_normal_stu_num_wuhan"] = $fulltime_normal_stu_num_wuhan;
                $data_arr["fulltime_normal_stu_num_shanghai"] = $fulltime_normal_stu_num_shanghai;
                $data_arr["platform_teacher_student"] = $platform_teacher_student;
            }
            if($exist_info){
                $task->t_teaching_core_data->field_update_list_2($start_time,1,$data_arr);

            }else{
                $data_arr["time"] = $start_time;
                $data_arr["type"] = 1;
                $task->t_teaching_core_data->row_insert($data_arr);
 
            }          

        }	
    }
}
