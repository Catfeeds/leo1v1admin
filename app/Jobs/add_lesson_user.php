<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class add_lesson_user extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $userid;
    var $lessonid_list;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($lessonid_list,$userid)
    {
        //
        $this->lessonid_list=$lessonid_list;
        $this->userid=$userid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $t_open_lesson_user=new \App\Models\t_open_lesson_user();
        foreach($this->lessonid_list as $val){
            \App\Helper\Utils::logger("this lessonid is:".$val['lessonid'] );

            $ret=$t_open_lesson_user->check_lesson_has($val['lessonid'],$this->userid);

            if($ret==0){
                $t_open_lesson_user->row_insert([
                    "lessonid" => $val['lessonid'],
                    "userid"   => $this->userid
                ]);
                \App\Helper\Utils::logger("add succ:".$val['lessonid'].",userid:".$this->userid);
            }
        }
    }
}
