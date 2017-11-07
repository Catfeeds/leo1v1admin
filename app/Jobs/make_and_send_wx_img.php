<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Enums as E;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class make_and_send_wx_img extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $wx_openid;
    var $header_img ;
    var $phone;
    var $back_img;
    var $qr_code_url;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($wx_openid,$header_img , $phone,$back_img, $qr_code_url  )
    {
        parent::__construct();
        $this->wx_openid   = $wx_openid;
        $this->header_img  = $header_img;
        $this->phone       = $phone;
        $this->back_img    = $back_img;
        $this->qr_code_url = $qr_code_url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

    }

}
