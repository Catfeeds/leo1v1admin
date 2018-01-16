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

}
