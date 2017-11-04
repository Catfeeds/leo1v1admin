<?php
namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendParentWx extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    var $wx_info;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_list,$template_id,$data,$url)
    {
        //
        $this->wx_info=[
            "parent_list"  => $user_list,
            "template_id" => $template_id,
            "data"        => $data,
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
        $user_list   = $wx_info['parent_list'];
        $url         = $wx_info['url'];

        foreach($user_list as $user_val){
            if(isset($user_val['wx_openid'])){
                \App\Helper\Utils::send_wx_to_parent($user_val['wx_openid'] ,$template_id,$data,$url);
            }
        }
    }
}
