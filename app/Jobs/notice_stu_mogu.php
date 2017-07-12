<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class notice_stu_mogu extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $user_list;
    var $sms_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_list,$sms_id)
    {
        //
        $this->user_list = $user_list;
        $this->sms_id    = $sms_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->user_list as $val){
            if($val!=''){
                \App\Helper\Utils::sms_common($val,$this->sms_id,[],0,"理优1对1");
            }
        }
    }
    
}
