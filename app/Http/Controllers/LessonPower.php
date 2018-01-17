<?php
namespace App\Http\Controllers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log ;
use \App\Enums as E;

/**
 * @use \App\Http\Controllers\Controller
 */
trait LessonPower{

    /**
     * 检测课时确认的时间
     */
    public function check_lesson_confirm_time_by_lessonid($lessonid){
        $lesson_start = $this->t_lesson_info->get_lesson_start($lessonid);
        $check_flag   = \App\Helper\Utils::check_teacher_salary_time($lesson_start);
        if(!$check_flag){
            return $this->output_err("超出确认时间,无法确认课时!");
        }else{
            return true;
        }
    }

    /**
     * 1.每月6号之后,助教无法修改课程的课时数;    如:一节2018年1月1日当天任何时间的课程,在2018年1月6日0点之后无法修改
     * 2.助教无法修改 常规课上奥数课 之外的课程;  注:"常规课上奥数课标识"需要在学生的课程包列表的"课程包信息"处找到并修改
     * @param int lessonid
     * @param int lesson_count
     * @return boolean
     * @author adrian
     */
    public function update_lesson_count_2018_1_6($lessonid,$lesson_count){
        $lesson_info = $this->t_lesson_info->get_lesson_info($lessonid);
        $course_info = $this->t_course_order->get_course_info($lesson_info['courseid']);

        $lesson_start = $lesson_info['lesson_start'];
        $lesson_end   = $lesson_info['lesson_end'];
        $check_time   = strtotime("+1 month",strtotime(date("Y-m-05",$lesson_start)));
        $now          = time();

        $error_info   = "";
        if($lesson_count==0){
            $error_info = "课时不能为0，若要取消课时，请在课程管理中取消！";
        }
        if($check_time<$now){
            $error_info = "课程设置超时，无法更改!\n<font color='red'>下次请在本节课的下个月5号前更改！</font>";
        }

        if($error_info!=""){
            return $this->output_err($error_info);
        }else{
            $ret = $this->t_lesson_info->field_update_list($lessonid, [
                "lesson_count" => $lesson_count
            ]);
            $this->add_lesson_count_operate_info($lessonid,$lesson_info['lesson_count'],$lesson_count);
            return $this->output_ret($ret);
        }
    }


    /**
     * 添加课程操作信息
     * @param int lessonid 课程id
     * @param int operate_column 修改字段
     * @param int operate_before 修改前课程信息
     * @param int operate_after  修改后课程信息
     * @return boolean
     */
    public function add_lesson_operate_info($lessonid,$operate_column,$operate_before,$operate_after){
        $uid          = $this->get_account_id();
        $operate_time = time();
        $ret = $this->t_lesson_info_operate_log->row_insert([
            "lessonid"       => $lessonid,
            "operate_column" => $operate_column,
            "operate_before" => $operate_before,
            "operate_after"  => $operate_after,
            "uid"            => $uid,
            "operate_time"   => $operate_time,
        ]);
        if($ret){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 添加修改课时的操作信息
     * @param int lessonid 课程id
     * @param int old_lesson_count 修改前课时数
     * @param int new_lesson_count 修改后课时数
     * @return string
     */
    public function add_lesson_count_operate_info($lessonid,$old_lesson_count,$new_lesson_count){
        $operate_column = "lesson_count";
        $operate_before = json_encode(["lesson_count" => $old_lesson_count]);
        $operate_after  = json_encode(["lesson_count" => $new_lesson_count]);
        $ret = $this->add_lesson_operate_info($lessonid,$operate_column,$operate_before,$operate_after);
        if($ret){
            return $this->output_succ();
        }else{
            return $this->output_err("添加修改课时记录失败！");
        }
    }


}
