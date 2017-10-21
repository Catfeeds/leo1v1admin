<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class add_lesson_grade_user extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $userid_list;
    var $lessonid;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userid_list,$lessonid)
    {
        $this->userid_list = $userid_list;
        $this->lessonid    = $lessonid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $t_open_lesson_user = new \App\Models\t_open_lesson_user();
        foreach($this->userid_list as $val){
            $ret = $t_open_lesson_user->check_lesson_has($this->lessonid,$val['userid']);
            if($ret==0){
                $t_open_lesson_user->row_insert([
                    "lessonid" => $this->lessonid,
                    "userid"   => $val['userid']
                ]);
            }
        }
    }
}
