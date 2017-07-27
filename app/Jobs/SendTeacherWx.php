<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTeacherWx extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    var $wx_info;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tea_list,$template_id,$data,$url)
    {
        //
        $this->wx_info=[
            "template_id" => $template_id,
            "data"        => $data,
            "tea_list"    => $tea_list,
            "url"         => $url,
        ];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $wx_info     = $this->wx_info;
        $template_id = $wx_info['template_id'];
        $data        = $wx_info['data'];
        $tea_list    = $wx_info['tea_list'];
        $url         = $wx_info['url'];

        foreach($tea_list as $tea_val){
            if(isset($tea_val['wx_openid'])){
                \App\Helper\Utils::send_teacher_msg_for_wx($tea_val['wx_openid'] ,$template_id,$data,$url);
            }
        }
    }


}
