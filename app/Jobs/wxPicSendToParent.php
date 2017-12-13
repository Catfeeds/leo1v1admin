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
        $this->delete();// 防止队列失败后 重复推送
        $t_parent_info  = new \App\Models\t_parent_info();
        $t_parent_send_mgs_log = new  \App\Models\t_parent_send_mgs_log();
        $wx = new \App\Helper\Wx() ;
        $media_id = $this->media_id;

        // $parent_list = $t_parent_info->get_parent_opend_list();

        $user_list_all = UserManage::getFansList($next_openId='');
        $parent_list = $user_list_all['data']['openid'];



        // $parent_list = [
            // 'orwGAs_IqKFcTuZcU1xwuEtV3Kek',

            // ["wx_openid"=>'orwGAs6R4UremX_fhr24MvStIxJc',
            //  "parentid" => 111
            // ],
            // "wx_openid"=>'orwGAs_IqKFcTuZcU1xwuEtV3Kek',
            // ],
            // ["wx_openid"=>'orwGAswh6yMByNDpPz8ToUPNhRpQ',
            //  "parentid" => 333

            // ],
            // ["wx_openid"=>'  ',
            //  "parentid" => 444

            // ],

        // ];

        $next = 'orwGAs1JT0ADjb3CsVfCmBVVrzpM';
        foreach($parent_list as $v){
            $check_flag = $t_parent_send_mgs_log->is_has($v);
            if($check_flag != 1){

                $txt_arr = [
                    'touser'   => $v,// james
                    'msgtype'  => 'image',
                    "image"=>[
                        "media_id"=>$media_id
                    ]
                ];

                $appid_tec     = config('admin')['wx']['appid'];
                $appsecret_tec = config('admin')['wx']['appsecret'];

                $token = $wx->get_wx_token($appid_tec,$appsecret_tec);

                $txt = $this->ch_json_encode($txt_arr);
                $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token;
                $txt_ret = $this->https_post($url,$txt);

                $txt_arr = json_decode($txt_ret,true);


                if($txt_arr['errcode'] == 0){
                    $t_parent_send_mgs_log->row_insert([
                        // "parentid"     => $v['parentid'],
                        "create_time"  => time(),
                        "is_send_flag" => 5 // 市场活动推送图片
                    ]);
                }
            }

            if($next == $v){
                $wx->send_template_msg( "orwGAs_IqKFcTuZcU1xwuEtV3Kek", 'IyYFpK8WkMGDMqMABls0WdZyC0-jV6xz4PFYO0eja9Q', [] ,$url="" );
            }

        }

    }


    public function https_post($url,$data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public function ch_json_encode($data) {


        $ret = self::ch_urlencode($data);
        $ret = json_encode($ret);

        return urldecode($ret);
    }

    public function ch_urlencode($data) {
        if (is_array($data) || is_object($data)) {
            foreach ($data as $k => $v) {
                if (is_scalar($v)) {
                    if (is_array($data)) {
                        $data[$k] = urlencode($v);
                    } else if (is_object($data)) {
                        $data->$k = urlencode($v);
                    }
                } else if (is_array($data)) {
                    $data[$k] = self::ch_urlencode($v); //递归调用该函数
                } else if (is_object($data)) {
                    $data->$k = self::ch_urlencode($v);
                }
            }
        }

        return $data;
    }


}
