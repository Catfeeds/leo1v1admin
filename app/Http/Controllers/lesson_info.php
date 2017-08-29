<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

class lesson_info extends Controller
{
    public function update_lesson_info(){
        $lessonid     = $this->get_in_int_val("lessonid");
        $grade        = $this->get_in_int_val("grade");
        $lesson_count = $this->get_in_int_val("lesson_count");

        $update_arr["grade"] = $grade;
        if($lesson_count>0){
            $update_arr["lesson_count"] = $lesson_count*100;
        }

        $ret = $this->t_lesson_info->field_update_list($lessonid,$update_arr);
        if(!$ret){
            return $this->output_err("更新失败！请重试！");
        }
        return $this->output_succ();
    }

    public function reset_lesson_money(){
        $lessonid = $this->get_in_int_val("lessonid");
        if($lessonid==0){
            return $this->output_err("课程id出错！");
        }

        $teacherid = $this->t_lesson_info->get_teacherid($lessonid);
        $teacher_info = $this->t_teacher_info->get_teacher_info($teacherid);

        $ret = $this->t_lesson_info->field_update_list($lessonid,[
            "teacher_money_type" => $teacher_info['teacher_money_type'],
            "level"              => $teacher_info['level'],
        ]);

        if(!$ret){
            return $this->output_err("重置失败或者此课程无需重置！");
        }
        return $this->output_succ();
    }

}