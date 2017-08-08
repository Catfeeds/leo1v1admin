<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCommonEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;


    var $list;
    var $title;
    var $message;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($list,$title,$message)
    {
        //
        $this->list=$list;
        $this->title=$title;
        $this->message=$message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        
        foreach($this->list as $val){
            if(isset($val['email']) && $val['email']!=''){
                \App\Helper\Common::send_paper_mail($val['email'],$this->title,$this->message);
            }
        }
    }
}
