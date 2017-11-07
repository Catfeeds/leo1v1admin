<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Enums as E;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

include( app_path("Wx/Yxyx/lanewechat_yxyx.php") );
use Yxyx\Core\Media;
use Yxyx\Core\AccessToken;
use LaneWeChat\Core\ResponsePassive;


class make_and_send_wx_img extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $wx_openid;
    var $id;
    var $request;
    var $phone;
    var $bg_url;
    var $qr_code_url;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id,$wx_openid,$phone,$bg_url,$qr_code_url,$request )
    {
        parent::__construct();
        $this->wx_openid   = $wx_openid;
        $this->phone       = $phone;
        $this->bg_url      = $bg_url;
        $this->id          = $id;
        $this->request     = $request;
        $this->qr_code_url = $qr_code_url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $qr_url       = "/tmp/".$this->phone.".png";
        \App\Helper\Utils::get_qr_code_png($this->qr_code_url,$qr_url,5,4,3);

        //请求微信头像
        $wx_config    = \App\Helper\Config::get_config("yxyx_wx");
        $wx           = new \App\Helper\Wx( $wx_config["appid"] , $wx_config["appsecret"] );
        $access_token = $wx->get_wx_token($wx_config["appid"],$wx_config["appsecret"]);
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$this->wx_openid."&lang=zh_cn";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($output,true);
        $headimgurl = $data['headimgurl'];

        $image_5 = imagecreatefromjpeg($headimgurl);
        $image_6 = imageCreatetruecolor(160,160);     //新建微信头像图
        $color = imagecolorallocate($image_6, 255, 255, 255);
        imagefill($image_6, 0, 0, $color);
        imageColorTransparent($image_6, $color);
        imagecopyresampled($image_6,$image_5,0,0,0,0,imagesx($image_6),imagesy($image_6),imagesx($image_5),imagesy($image_5));

        $image_1 = imagecreatefrompng($this->bg_url);     //背景图
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

        $agent_qr_url = "/tmp/yxyx_".$this->phone.".png";
        imagepng($image_3,$agent_qr_url);

        $cmd_rm = "rm /tmp/".$this->phone.".png";
        \App\Helper\Utils::exec_cmd($cmd_rm);

        imagedestroy($image_1);
        imagedestroy($image_2);
        imagedestroy($image_3);
        imagedestroy($image_4);
        imagedestroy($image_5);
        imagedestroy($image_6);
        // return $agent_qr_url;

        // $img_url = '/tmp/yxyx_'.$phone.'.png';
        $type = 'image';
        $num = rand();
        $img_Long = file_get_contents($agent_qr_url);
        file_put_contents( public_path().'/wximg/'.$num.'.png',$img_Long );
        $img_url = public_path().'/wximg/'.$num.'.png';
        $img_url = realpath($img_url);

        $mediaId = Media::upload($img_url, $type);
        \App\Helper\Utils::logger("mediaId info:". json_encode($mediaId));

        $mediaId = $mediaId['media_id'];
        unlink($img_url);

        $cmd_rm = "rm /tmp/yxyx_".$this->phone.".png";
        \App\Helper\Utils::exec_cmd($cmd_rm);

        $t_agent = new \App\Models\t_agent();
        $t_agent->set_add_type_2( $this->id );

        $txt_arr = [
            'touser'   => $this->request['tousername'] ,
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


}
