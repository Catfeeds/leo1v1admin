<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Enums as E;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

include(app_path("Libs/LaneWeChat/lanewechat.php"));
use LaneWeChat\Core\WeChatOAuth;
use LaneWeChat\Core\AccessToken;
use LaneWeChat\Core\ResponsePassive;
use LaneWeChat\Core\Media;
use LaneWeChat\Core\UserManage;

# 市场部分享海报功能
class marketActivityPoster extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $uid;
    var $bg_url;
    var $bgType;
    var $qr_code_url;
    var $mediaId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    // public function __construct($wx_openid,$bg_url,$qr_code_url,$request,$agent,$type)
    public function __construct($uid,$bg_url,$qr_code_url,$bgType,$mediaId)
    {
        parent::__construct();
        $this->uid         = $uid;
        $this->bg_url      = $bg_url;
        $this->bgType      = $bgType;
        $this->qr_code_url = $qr_code_url;
        $this->mediaId     = $mediaId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $t_manager_info = new \App\Models\t_manager_info();
        # 获取报名链接二维码
        $qr_url  = "/tmp/market_poster_wx_".$uid."png";
        \App\Helper\Utils::get_qr_code_png($this->qr_code_url,$qr_url,5,4,3);

        # 请求微信头像
        $wx_openid    = $t_manager_info->get_wx_openid($uid);
        $wx_config    = \App\Helper\Config::get_config("wx");
        $wx           = new \App\Helper\Wx( $wx_config["appid"] , $wx_config["appsecret"] );
        $access_token = $wx->get_wx_token($wx_config["appid"],$wx_config["appsecret"]);
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$wx_openid."&lang=zh_cn";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($output,true);

        //强制刷新token
        if ( !array_key_exists('headimgurl', $data) ){
            $access_token = $wx->get_wx_token($wx_config["appid"],$wx_config["appsecret"],true);
            $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$wx_openid."&lang=zh_cn";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $output = curl_exec($ch);
            curl_close($ch);
            $data = json_decode($output,true);
        }

        $headimgurl = $data['headimgurl'];
        //下载头像，制作图片
        $datapath = "/tmp/market_wx_".$uid."_headimg.jpg";
        $wgetshell = 'wget -O '.$datapath.' "'.$headimgurl.'" ';
        shell_exec($wgetshell);


        \App\Helper\Utils::logger("wx_headimgurl:". $headimgurl);
        $image_5 = @imagecreatefromjpeg($datapath);
        if(!$image_5) {
            $image_5 = @imagecreatefrompng($datapath);
        }
        $image_6 = imageCreatetruecolor(160,160);     //新建微信头像图
        $color = imagecolorallocate($image_6, 255, 255, 255);

        imagefill($image_6, 0, 0, $color);
        imageColorTransparent($image_6, $color);
        imagecopyresampled($image_6,$image_5,0,0,0,0,imagesx($image_6),imagesy($image_6),imagesx($image_5),imagesy($image_5));

        $image_1 = @imagecreatefromjpeg($this->bg_url);
        if(!$image_1){
            $image_1 = @imagecreatefrompng($this->bg_url);
        }

        $image_2 = imagecreatefrompng($qr_url);     //二维码
        $image_3 = imageCreatetruecolor(imagesx($image_1),imagesy($image_1));     //新建图
        $image_4 = imageCreatetruecolor(176,176);     //新建二维码图
        imagecopyresampled($image_3,$image_1,0,0,0,0,imagesx($image_1),imagesy($image_1),imagesx($image_1),imagesy($image_1));
        imagecopyresampled($image_4,$image_2,0,0,0,0,imagesx($image_4),imagesy($image_4),imagesx($image_2),imagesy($image_2));
        imagecopymerge($image_3,$image_4,287,1100,0,0,imagesx($image_4),imagesy($image_4),100);

        $r = 80; //圆半径
        for ($x = 0; $x < 160; $x++) {
            for ($y = 0; $y < 160; $y++) {
                $rgbColor = imagecolorat($image_6, $x, $y);
                $a = $x-$r;
                $b = $y-$r;
                if ( ( ( $a*$a + $b*$b) <= ($r * $r) ) ) {
                    $n_x = $x+295;
                    $n_y = $y+28;
                    imagesetpixel($image_3, $n_x, $n_y, $rgbColor);
                }
            }
        }

        $agent_qr_url = "/tmp/market_wx_".$uid."_member.png";
        imagepng($image_3,$agent_qr_url);

        imagedestroy($image_1);
        imagedestroy($image_2);
        imagedestroy($image_3);
        imagedestroy($image_4);
        imagedestroy($image_5);
        imagedestroy($image_6);

        $type = 'image';
        $num = rand();
        $img_Long = file_get_contents($agent_qr_url);
        file_put_contents( public_path().'/wximg/'.$num.'.png',$img_Long );
        $img_url = public_path().'/wximg/'.$num.'.png';
        $img_url = realpath($img_url);

        $mediaId = Media::upload($img_url, $type);
        \App\Helper\Utils::logger("mediaId info:". json_encode($mediaId));

        \App\Helper\Utils::logger("upload_img_END");
        $mediaId = $mediaId['media_id'];
        unlink($img_url);

        $t_agent->set_add_type_2( $id );

        $txt_arr = [
            'touser'   => $wx_openid,
            'msgtype'  => 'image',
            "image"=> [
                "media_id" => "$mediaId"
            ],
        ];

        $txt = self::ch_json_encode($txt_arr);
        $token = AccessToken::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token;
        $txt_ret = self::https_post($url,$txt);

        \App\Helper\Utils::logger("IMAGE_RET $txt_ret ");

        $cmd_rm = "rm /tmp/yxyx_wx_".$phone."*";
        \App\Helper\Utils::exec_cmd($cmd_rm);
    }

    public static function https_post($url,$data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public static function ch_json_encode($data) {


        $ret = self::ch_urlencode($data);
        $ret = json_encode($ret);

        return urldecode($ret);
    }

    public static function ch_urlencode($data) {
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
