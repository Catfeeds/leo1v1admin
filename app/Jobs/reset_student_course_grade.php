<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class reset_student_course_grade extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $type;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($type)
    {
        parent::__construct();
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $task = new \App\Console\Tasks\TaskController();

        $list = $task->t_course_order->get_course_order_grade();
        if($this->type==1){
            foreach($list as $l_val){
                $courseid = $l_val['courseid'];
                $up_grade = $this->get_up_grade($l_val['grade']);
                $task->t_course_order->field_update_list($courseid,[
                    "grade"             => $up_grade,
                    "lesson_grade_type" => 1,
                ]);
                $task->t_lesson_info_b2->update_lesson_grade($courseid,$up_grade);
            }
        }else{
            foreach($list as $s_val){
                $userid   = $s_val['userid'];
                $up_grade = $this->get_up_grade($s_val['grade']);
                $task->t_student_info->field_update_list($userid,[
                    "grade" => $up_grade,
                ]);
            }
            $task->t_course_order->reset_course_lesson_gradse_type(0);
        }
    }

    public function get_up_grade($grade){
        if(in_array($grade,[106,203])){
            $up_grade = (int)substr($grade,0,1)+1;
            $grade    = $up_grade."01";
        }elseif($grade==303){
            $grade = (int)$grade;
        }else{
            $grade = (int)$grade+1;
        }
        return (int)$grade;
    }
}
