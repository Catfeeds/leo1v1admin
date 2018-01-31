<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

class lesson_manage extends Controller
{
    use LessonPower;

    public function stu_status_count()
    {
        $start_date   = $this->get_in_str_val('start_date',date('Y-m-d', time(NULL)-86400*30 ));
        $end_date     = $this->get_in_str_val('end_date',date('Y-m-d', time(NULL)));
        $start_date_s = strtotime($start_date);
        $end_date_s   = strtotime($end_date)+86400;
        $page_num     = $this->get_in_page_num();

        $startday = date("Ymd",$start_date_s);
        $endday   = date("Ymd",$end_date_s);

        $ret_info = $this->t_tongji->get_tongji_info($startday,$endday,$page_num);
        foreach($ret_info["list"] as &$item) {
            $item["new_course_count"]=intval($item["new_course_count"]);
            $item["old_course_count"]=intval($item["old_course_count"]);
        }

        return $this->Pageview(__METHOD__,$ret_info);
    }


    public function get_lesson_info() {
        $lessonid     = $this->get_in_lessonid();

        $row_data     = $this->t_lesson_info->field_get_list($lessonid,"*");
        $lesson_count = $this->t_course_order->get_default_lesson_count($row_data['courseid']);

        return $this->output_succ( ["data"=>$row_data,"lesson_count"=>$lesson_count]);
    }

    /**
     * 更改课程信息
     * @param int lessonid
     * @param int level
     * @param int grade
     * @param int teacher_type
     * @param int lesson_count
     */
    public function change_lesson_info(){
        if(\App\Helper\Utils::check_env_is_release()){
            return $this->output_err("此功能只能在非正式环境使用！");
        }

        $lessonid           = $this->get_in_int_val("lessonid");
        $level              = $this->get_in_int_val("level");
        $grade              = $this->get_in_int_val("grade");
        $teacher_money_type = $this->get_in_int_val("teacher_money_type");
        $teacher_type       = $this->get_in_int_val("teacher_type");
        $lesson_count       = ($this->get_in_int_val("lesson_count"))*100;

        $lesson_info = $this->t_lesson_info->get_lesson_info($lessonid);
        $update_arr = [];
        \App\Helper\Utils::set_diff_value_for_update($update_arr,$lesson_info,"level",$level);
        \App\Helper\Utils::set_diff_value_for_update($update_arr,$lesson_info,"grade",$grade);
        \App\Helper\Utils::set_diff_value_for_update($update_arr,$lesson_info,"teacher_money_type",$teacher_money_type);
        \App\Helper\Utils::set_diff_value_for_update($update_arr,$lesson_info,"teacher_type",$teacher_type);
        \App\Helper\Utils::set_diff_value_for_update($update_arr,$lesson_info,"lesson_count",$lesson_count);

        $teacherid = $this->t_lesson_info->get_teacherid($lessonid);
        $is_test_user = $this->t_teacher_info->get_is_test_user($teacherid);
        //线上环境只能修改测试老师的数据
        if(!$is_test_user && \App\Helper\Utils::check_env_is_release()){
            return $this->output_err("只能修改测试老师的课程!");
        }

        $ret = $this->t_lesson_info->field_update_list($lessonid, $update_arr);

        //添加课程操作信息的记录
        if(!empty($update_arr)){
            $operate_column=[];
            $operate_before=[];
            foreach($update_arr as $u_key=>$u_val){
                $operate_column[] = $u_key;
                if(isset($lesson_info[$u_key])){
                    $operate_before[$u_key] = $lesson_info[$u_key];
                }else{
                    $operate_before[$u_key] = "";
                }
            }

            $operate_column = implode(",",$operate_column);
            $this->add_lesson_operate_info($lessonid, $operate_column, $operate_before, $update_arr);
        }
        return $this->output_ret($ret);
    }

    /**
     * 获取课程的操作记录
     * @param int lessonid 课程id
     */
    public function get_lesson_operate_info(){
        $lessonid = $this->get_in_int_val("lessonid");

        $ret_list = $this->t_lesson_info_operate_log->get_lesson_operate_info($lessonid);
        foreach($ret_list as $val){
            $val['operate_time_str'] = \App\Helper\Utils::unixtime2date($val['operate_time']);
        }

        return $this->output_succ($ret_list);
    }
}