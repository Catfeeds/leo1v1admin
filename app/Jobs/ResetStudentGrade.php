<?php

namespace App\Jobs;

use \App\Enums as E;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetStudentGrade extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $arr;
    var $type;
    /**
     * Create a new job instance.
     * @param arr  待处理数组
     * @param type 1 课程包年级 2 学生年级
     * @return void
     */
    public function __construct($arr,$type)
    {
        parent::__construct();
        $this->arr  = $arr;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->type==1){
            foreach($this->arr as $l_val){
                $courseid = $l_val['courseid'];
                $up_grade = $this->get_up_grade($l_val['grade']);
                $this->task->t_course_order->field_update_list($courseid,[
                    "grade"             => $up_grade,
                    "lesson_grade_type" => 1,
                ]);
                $this->task->t_lesson_info_b2->update_lesson_grade($courseid,$up_grade);
            }
        }else{
            foreach($this->arr as $s_val){
                $userid   = $s_val['userid'];
                $up_grade = $this->get_up_grade($s_val['grade']);
                $this->task->t_student_info->field_update_list($userid,[
                    "grade" => $up_grade,
                ]);
            }
            $this->task->t_course_order->reset_course_lesson_gradse_type(0);
        }
    }

    public function get_up_grade($grade){
        if(in_array($grade,[106,203])){
            $up_grade = (int)substr($grade,0,1)+1;
            $grade    = $up_grade."01";
        }else{
            $grade=(int)$grade+1;
        }
        return (int)$grade;
    }
}
