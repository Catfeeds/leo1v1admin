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
     * 添加一节一对一常规课
     */
    public function add_1v1_normal_lesson(){
        $courseid = $this->t_course_order->add_course_info_new($orderid,$userid,$grade,$subject,100,2,0,1,1,0,$teacherid);
        $lessonid = $this->t_lesson_info->add_lesson(
            $courseid,0,$userid,0,2,
            $teacherid,0,$lesson_start,$lesson_end,$grade,
            $subject,100,$teacher_info["teacher_money_type"],$teacher_info["level"]
        );
        $this->t_homework_info->add(
            $courseid,0,$userid,$lessonid,$grade,$subject,$teacherid
        );
    }

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

        $error_info = "";
        $check_lesson_count = $this->t_lesson_info->check_lesson_count_for_change($lessonid, $lesson_count);
        if (!$check_lesson_count){
            $error_info = "课时数太大，无法修改！\n请确认该课时包的分配课时是否充足！";
        }
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
     * @param string operate_column 修改字段
     * @param array|string operate_before 修改前课程信息
     * @param array|string operate_after  修改后课程信息
     * @return boolean
     */
    public function add_lesson_operate_info($lessonid,$operate_column,$operate_before,$operate_after){
        $uid          = $this->get_account_id();
        $operate_time = time();
        if(is_array($operate_before)){
            $operate_before = json_encode($operate_before);
        }
        if(is_array($operate_after)){
            $operate_after = json_encode($operate_after);
        }
        if(isset($_SERVER['HTTP_REFERER'])){
            $operate_referer = substr($_SERVER['HTTP_REFERER'],0,1000);
        }else{
            $operate_referer = "非浏览器操作";
        }
        if(isset($_SERVER['REQUEST_URI'])){
            $operate_request = substr($_SERVER['REQUEST_URI'],0,1000);
        }else{
            $operate_request = "没有请求地址";
        }
        $ret = $this->t_lesson_info_operate_log->row_insert([
            "lessonid"        => $lessonid,
            "operate_column"  => $operate_column,
            "operate_before"  => $operate_before,
            "operate_after"   => $operate_after,
            "operate_time"    => $operate_time,
            "operate_referer" => $operate_referer,
            "operate_request" => $operate_request,
            "uid"             => $uid,
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
        return $this->output_err($ret,"添加修改课时记录失败！");
    }

    /**
     * 添加删除课程的操作信息
     * @param int lessonid 课程id
     * @param int old_del_flag 修改前删除标识
     * @param int new_del_flag 修改后删除标识
     * @return string
     */
    public function add_cancel_lesson_operate_info($lessonid,$old_lesson_del_flag=0,$new_lesson_del_flag=1){
        $operate_column = "lesson_del_flag";
        $operate_before = json_encode(["lesson_del_flag" => $old_lesson_del_flag]);
        $operate_after  = json_encode(["lesson_del_flag" => $new_lesson_del_flag]);
        $ret = $this->add_lesson_operate_info($lessonid,$operate_column,$operate_before,$operate_after);
        return $this->output_ret($ret,"取消课程记录添加失败！");
    }

    /**
     * 添加修改课程时间的操作信息
     * @param int lessonid 课程id
     * @param int old_lesson_start 修改前课程开始时间
     * @param int new_lesson_start 修改后课程开始时间
     * @param int old_lesson_end   修改前课程结束时间
     * @param int new_lesson_end   修改后课程结束时间
     * @return string
     */
    public function add_lesson_time_operate_info($lessonid,$old_lesson_start,$new_lesson_start,$old_lesson_end,$new_lesson_end){
        $operate_column = "lesson_start,lesson_end";
        $operate_before = json_encode([
            "lesson_start" => $old_lesson_start,
            "lesson_end"   => $old_lesson_end,
        ]);
        $operate_after  = json_encode([
            "lesson_start" => $new_lesson_start,
            "lesson_end"   => $new_lesson_end,
        ]);
        $ret = $this->add_lesson_operate_info($lessonid,$operate_column,$operate_before,$operate_after);
        return $this->output_ret($ret,"设置课程时间记录添加失败！");
    }

    /**
     * 添加修改课程状态的操作信息
     * @param int lessonid 课程id
     * @param int old_lesson_status 修改前课程状态
     * @param int new_lesson_status 修改后课程状态
     * @return string
     */
    public function add_lesson_status_operate_info($lessonid,$old_lesson_status,$new_lesson_status){
        $operate_column = "lesson_status";
        $operate_before = json_encode(["lesson_status" => $old_lesson_status]);
        $operate_after  = json_encode(["lesson_status" => $new_lesson_status]);
        $ret = $this->add_lesson_operate_info($lessonid,$operate_column,$operate_before,$operate_after);
        return $this->output_ret($ret,"修改课程状态记录添加失败！");
    }

    /**
     * 添加修改课时确认的操作信息
     * @param int lessonid 课程id
     * @param int old_confirm_flag 修改前课时确认状态
     * @param int new_confirm_flag 修改后课时确认状态
     * @return string
     */
    public function add_lesson_confirm_flag_operate_info($lessonid,$old_confirm_flag,$new_confirm_flag){
        $operate_column = "confirm_flag";
        $operate_before = json_encode(["confirm_flag" => $old_confirm_flag]);
        $operate_after  = json_encode(["confirm_flag" => $new_confirm_flag]);
        $ret = $this->add_lesson_operate_info($lessonid,$operate_column,$operate_before,$operate_after);
        return $this->output_ret($ret,"修改课时确认状态记录添加失败！");
    }

    /**
     * 检测老师时间段内是否空闲
     * @param int teacherid 老师id
     * @param int cur_lessonid 当前排课的lessonid，不检测此lessonid
     * @param int lesson_start 检测的开始时间
     * @param int lesson_end   检测的结束时间
     */
    public function check_teacher_time_free($teacherid,$cur_lessonid,$lesson_start,$lesson_end){
        $check_flag = $this->t_lesson_info->check_teacher_time_free($teacherid, $cur_lessonid, $lesson_start, $lesson_end);
        if($check_flag){
            $error_lessonid = $check_flag['lessonid'];
            return $this->output_err(
                "<div>有现存的老师课程与该课程时间冲突！"
                +"<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/>"
                +"<div> "
            );
        }else{
            return true;
        }
    }

    /**
     * 检测学生时间段内是否空闲
     * @param int teacherid 老师id
     * @param int cur_lessonid 当前排课的lessonid，不检测此lessonid
     * @param int lesson_start 检测的开始时间
     * @param int lesson_end   检测的结束时间
     */
    public function check_student_time_free($userid,$cur_lessonid,$lesson_start,$lesson_end){
        $check_flag = $this->t_lesson_info->check_student_time_free($userid, $cur_lessonid, $lesson_start, $lesson_end);
        if($check_flag){
            $error_lessonid = $check_flag['lessonid'];
            return $this->output_err(
                "<div>有现存的学生课程与该课程时间冲突！"
                +"<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/>"
                +"<div> "
            );
        }else{
            return true;
        }
    }



}
