<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class StdentResetLessonCount extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $studentid;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($studentid)
    {
        $this->studentid=$studentid;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        /**  @var  $tt \App\Models\t_student_info */
        $tt= new \App\Models\t_student_info () ;
        $tt->reset_lesson_count($this->studentid);  
        //
    }
}
