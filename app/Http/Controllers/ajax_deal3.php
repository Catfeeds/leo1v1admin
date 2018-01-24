<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Input;


class ajax_deal3 extends Controller
{
    use CacheNick;
    use TeaPower;

    //获取一节课所有的信息
    public function get_student_lesson_info_by_lessonid(){
        $lessonid    = $this->get_in_int_val("lessonid");
        $userid = 60007;
        $data= $this->t_student_info->field_get_list($userid,"face,nick,realname");
        $first_lesson_time = $this->t_lesson_info_b3->get_stu_first_regular_lesson_time($userid);
        if($first_lesson_time>0){
            $study_day = ceil((time()-$first_lesson_time)/86400); 
        }else{
            $study_day=0;
        }
        $lesson_all = $this->t_lesson_info_b3->get_student_all_lesson_count_list($userid);
        $hour = @$lesson_all["lesson_count"]/100*1.5;
        $data["str1"] = "已经在理优学习了".$study_day."天，完成了".@$lesson_all["lesson_num"]."课次，".(@$lesson_all["lesson_count"]/100)."课时，总计学习了".$hour."小时";
        $data["str2"] = "学习课".@$lesson_all["subject_num"]."门课，".@$lesson_all["tea_num"]."位老师为你服务";

        $lesson_detail = $this->t_lesson_info_b3->get_student_all_lesson_info($userid,0,0);
        $cw_num=$pre_num=$tea_commit=$leave_num=$absence_num=$commit_num=$a_num=$b_num=$c_num=$d_num=$score_total=$check_num=$stu_praise=0;
        foreach($lesson_detail as $val){
           
            if(empty($val["tea_cw_upload_time"]) || $val["tea_cw_upload_time"]>$val["lesson_start"]){
            }else{
                $cw_num++;
                if($val["preview_status"]>0){
                    $pre_num++;
                }
            }
            if($val["stu_performance"]){
                $tea_commit++;
            }
            if($val["lesson_cancel_reason_type"]==11){
                $leave_num++;
            }elseif($val["lesson_cancel_reason_type"]==20){
                $absence_num++;
            }

            if($val["work_status"]>=2){
                $commit_num++;
            }
           
            if($val["work_status"]>=3){
                $score =$val["score"];
                $check_num++;
                if($score=="A"){
                    $score_total +=90;
                    $a_num++;
                }elseif($score=="B"){
                    $score_total +=80;
                    $b_num++;
                }elseif($score=="C"){
                    $score_total +=70;
                    $c_num++;
                }else{
                    $score_total +=50;
                    $d_num++;
                }

            }          
            $stu_praise +=$val["stu_praise"];

        }
        $pre_rate = $cw_num==0?0:round($pre_num/$cw_num*100,2);
        $score_avg = $check_num==0?"0":($score_total/$check_num);
        if($score_avg>=86){
            $score_final = "A";
        }elseif($score_avg>=75){
            $score_final = "B";
        }elseif($score_avg>=60){
            $score_final = "C";
        }elseif($score_avg>0){
            $score_final = "D";
        }else{
            $score_final = "无";
        }
        $data["str3"] = "预习了".$pre_num."次，预习率为".$pre_rate."%，请假了".$leave_num."次，旷课了".$absence_num."次，获赞". $stu_praise."个，得到老师评价".$tea_commit."次";
        $data["str4"] = "提交了".$commit_num."次作业，获得成绩A".$a_num."次，B".$b_num."次，C".$c_num."次，D".$d_num."次，平均成绩为".$score_final;




        
        return $this->output_succ(["data"=>$data]);
    }


}