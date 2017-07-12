<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendStuMessage extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $stu_list;
    var $type;
    var $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($stu_list,$type,$data)
    {
        parent::__construct();
        $this->stu_list = $stu_list;
        $this->type     = $type;
        $this->data     = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->stu_list as $stu_val){
            \App\Helper\Utils::sms_common($stu_val['phone'],$this->type,$this->data);
        }
    }
}
