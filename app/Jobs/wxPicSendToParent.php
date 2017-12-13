<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use LaneWeChat\Core\Media;

use LaneWeChat\Core\AccessToken;

use LaneWeChat\Core\ResponsePassive;

use Illuminate\Http\Request;

use LaneWeChat\Core\WeChatOAuth;

use LaneWeChat\Core\UserManage;

use LaneWeChat\Core\TemplateMessage;


include(app_path("Libs/LaneWeChat/lanewechat.php"));


class wxPicSendToParent extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    var $media_id;
    public function __construct($media_id)
    {
        //
        $this->media_id = $media_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $this->delete();// 防止队列失败后 重复推送
        $t_parent_info  = new \App\Models\t_parent_info();

        // $parent_list = $t_parent_info->get_parent_opend_list();

        $parent_list[] = [
            "wx_openid"=>'orwGAs_IqKFcTuZcU1xwuEtV3Kek'
        ];

        $media_id = $this->media_id;

        foreach($parent_list as $v){
            $txt_arr = [
                'touser'   => $v['wx_openid'],// james
                'msgtype'  => 'image',
                "image"=>[
                    "media_id"=>$media_id
                ]
            ];



            $appid_tec     = config('admin')['wx']['appid'];
            $appsecret_tec = config('admin')['wx']['appsecret'];

            $wx = new \App\Helper\Wx() ;
            $token = $wx->get_wx_token($appid_tec,$appsecret_tec);

            $txt = $this->ch_json_encode($txt_arr);
            $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token;
            $txt_ret = $this->https_post($url,$txt);


        }

    }
}
