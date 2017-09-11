<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetTeacherLesson extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $time;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($arr,$time)
    {
        //
        parent::__construct();
        $this->arr  = $arr;
        $this->time = $time;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->arr as $val){
            
        }
    }
}
