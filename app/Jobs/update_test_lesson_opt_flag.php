<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class update_test_lesson_opt_flag extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    var $userid;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userid)
    {
        //
        $this->userid = $userid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $task = new \App\Console\Tasks\TaskController();
        $userid = $this->userid;
        $ret_stu = $task->t_test_lesson_opt_log->get_test_lesson_opt_list_by_userid($userid);
        foreach($ret as $item){
            $roomid = $item['roomid'];
            $server_ip = $item['server_ip'];
            $userid = $item['userid'];
            $action = $item['action'];
            $opt_type = $item['opt_type'];
            $opt_time = $item['opt_time'];

            $teacherid = $item['teacherid'];
            $server_ip_seller = $item['server_ip_seller'];
            $action_seller = $item['action_seller'];
            $opt_type_seller = $item['opt_type_seller'];
            $opt_time_seller = $item['opt_time_selelr'];
        }
    }
}
