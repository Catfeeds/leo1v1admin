<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;

class teacher_info extends TeaController
{
    use CacheNick;


    public function get_lesson_list_new() {
        $teacherid   = $this->get_login_teacher();
        list($start_time, $end_time) = $this->get_in_date_range(0, 7);
        $lesson_type                 = $this->get_in_int_val('lesson_type',-1);
        $userid                      = $this->get_in_userid();

        $lesson_type_in_str = "";
        switch ($lesson_type){
        case  E\Econtract_type::V_0:
            $lesson_type_in_str="0,1,2,3";
            break;
        case  E\Econtract_type::V_2 :
            $lesson_type_in_str="2";
            break;
        case  E\Econtract_type::V_1100:
            $lesson_type_in_str="1100";
            break;
        case  E\Econtract_type::V_1001 :
            $lesson_type_in_str="1001,1002,1003";
            break;
        case  E\Econtract_type::V_3001 :
            $lesson_type_in_str="3001";
            break;
        default:
            break;
        }

        $get_flag_color_func = function($v){
            if ($v)  {
                $color="green";
            }else{
                $color="red";
            }
            $desc = E\Eboolean::get_desc($v);
            return "<font color=$color>$desc</font>";
        };

        $ret_info = $this->t_lesson_info_b2->get_teacher_lesson_list_new(
            $teacherid,$userid,$start_time,$end_time,$lesson_type_in_str
        );

        if($teacherid==50728 || \App\Helper\Utils::check_env_is_local()){
            $trial_train_list = $this->t_lesson_info_b2->get_trial_train_list($teacherid);
            $ret_info['list'] = array_merge($trial_train_list,$ret_info['list']);
        }

        $train_from_lessonid_list = \App\Helper\Config::get_config("trian_lesson_from_lessonid","train_lesson");
        foreach($ret_info["list"] as &$item){
            $lessonid    = $item["lessonid"];
            $lesson_type = $item['lesson_type'];
            $subject     = $item['subject'];
            $grade       = $item['grade'];
            E\Econtract_type::set_item_value_str($item,"lesson_type");
            E\Egrade::set_item_value_str($item,"grade");

            $item["lesson_time"]     = \App\Helper\Utils::fmt_lesson_time($item["lesson_start"],$item["lesson_end"]);
            $item['tea_comment_str'] = "<font color=red>-</font>";

            if ($lesson_type<1000) {
                if($lesson_type==2){
                    $item['cc_id']=$item['require_adminid'];
                }else{
                    $item['cc_id']= $this->t_assistant_info->get_adminid_by_assistand( $item['assistantid']);
                }
                $item['ass_nick'] = $this->cache_get_assistant_nick($item['assistantid']);
                if($item['userid']>0){
                    $item["stu_nick"] = $this->cache_get_student_nick($item["userid"]);
                }else{
                    $item['stu_nick'] = "";
                }

                $this->check_tea_comment($item);
                if($item['confirm_flag']>=2){
                    $item['tea_comment_str'] = "无需评价";
                }else{
                    $item['tea_comment_str'] = $get_flag_color_func($item['tea_comment']);
                }
                if($item['textbook'] == ""){
                    $item['textbook'] = E\Eregion_version::get_desc($item['editionid']);
                }
            }elseif($lesson_type>=3000 && $lesson_type<4000){
                $ret_homework = $this->t_small_lesson_info->get_pdf_homework($item['lessonid']);
                if ($ret_homework) {
                    $item['homework_status']    = $ret_homework['work_status'];
                    $item['issue_url']          = $ret_homework['issue_url'];
                    $item['pdf_question_count'] = $ret_homework['pdf_question_count'];
                }
            }elseif($item['lesson_type']==1100 && $item['train_type']==4){
                $item['stu_nick']                       = "试听培训学生";
                $item['ass_nick']                       = "沈老师";
                $item['ass_phone']                      = "15214368896";
                $item['lesson_type_str']                = "模拟试听";
                $from_lessonid                          = $train_from_lessonid_list[$subject][$grade];
                $from_lesson_info                       = $this->t_test_lesson_subject->get_from_lesson_info($from_lessonid);
                $item['stu_test_paper']                 = $from_lesson_info['stu_test_paper'];
                $item['stu_request_test_lesson_demand'] = $from_lesson_info['stu_request_test_lesson_demand'];
            }

            $item["pdf_status_str"] = $get_flag_color_func( $item["tea_status"])."/"
                                    . $get_flag_color_func( $item["stu_status"])."/"
                                    . $get_flag_color_func( $item["homework_status"]);
            if (!$item["tea_more_cw_url"] ) {
                $item["tea_more_cw_url"]="[]";
            }

            if($item['stu_request_test_lesson_demand']==""){
                $item['stu_request_test_lesson_demand']="<font color=red>-</font>";
            }
        }
        $student_list = $this->t_lesson_info_b2->get_student_list($teacherid,$start_time,$end_time);
        // dd($ret_info);
        return $this->pageView(__METHOD__,$ret_info,[
            "student_list" => $student_list
        ]);
    }



}