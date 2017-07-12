<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PushWxToParent extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $template_id;
    var $data;
    var $url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($template_id,$data,$url)
    {
        //
        $this->template_id = $template_id;
        $this->data        = $data;
        $this->url         = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $t_parent_info = new \App\Models\t_parent_info();
        $wx   = new \App\Helper\Wx();
        $list = $t_parent_info->get_parent_list();
        foreach($list as $val){
            $openid = $val['wx_openid'];
            $wx->send_template_msg($openid,$this->template_id,$this->data,$this->url);
        }

    }
}
