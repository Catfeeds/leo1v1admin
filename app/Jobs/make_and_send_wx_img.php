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
    var $phone;
    var $bg_url;
    var $headimgurl;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($wx_openid,$phone,$bg_url,$headimgurl )
    {
        parent::__construct();
        $this->wx_openid   = $wx_openid;
        $this->phone       = $phone;
        $this->bg_url      = $bg_url;
        $this->headimgurl  = $headimgurl;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (\App\Helper\Utils::check_env_is_test() ) {
            $www_url="test.www.leo1v1.com";
        }else{
            $www_url="www.leo1v1.com";
        }

        $text         = "http://$www_url/market-invite/index.html?p_phone=".$this->phone."&type=2";
        $qr_url       = "/tmp/".$this->phone.".png";
        // $bg_url       = "http://7u2f5q.com2.z0.glb.qiniucdn.com/4fa4f2970f6df4cf69bc37f0391b14751506672309999.png";
        \App\Helper\Utils::get_qr_code_png($text,$qr_url,5,4,3);

        $image_5 = imagecreatefromjpeg($this->headimgurl);
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

        $cmd_rm = "rm /tmp/yxyx_".$phone.".png";//删除老图
        \App\Helper\Utils::exec_cmd($cmd_rm);

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

    }

}
