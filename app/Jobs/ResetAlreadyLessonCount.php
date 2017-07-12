<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetAlreadyLessonCount extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $start;
    var $end;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($start=0,$end=0)
    {
        $this->start = $start;
        $this->end   = $end;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \App\Helper\Utils::logger("reset teacher lesson count has start,start time is :".$this->start." end time is :".$this->end);
        $t_lesson_info = new \App\Models\t_lesson_info();

        $tea_list = $t_lesson_info->get_teacherid_for_reset_lesson_count($this->start,$this->end);
        if(!empty($tea_list) && is_array($tea_list)){
            foreach($tea_list as $val){
                $stu_list = $t_lesson_info->get_student_list_by_teacher($val['teacherid'],$this->start,$this->end);
                if(!empty($stu_list) && is_array($stu_list)){
                    foreach($stu_list as $item){
                        $t_lesson_info->reset_teacher_student_already_lesson_count($val['teacherid'],$item['userid']);
                    }
                }
            }
        }
        \App\Helper\Utils::logger("reset teacher lesson count has finished");
    }

}
