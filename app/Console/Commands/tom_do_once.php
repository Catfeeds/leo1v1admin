<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
use \App\Enums as E;

require_once  app_path("/Libs/Qiniu/functions.php");

class tom_do_once extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:tom_do_once';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    /**
     * task
     *
     * @var \App\Console\Tasks\TaskController
     */

    var $task       ;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->task        = new \App\Console\Tasks\TaskController();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // $start_time = 1509465600;
        // $end_time = 1510156800;
        $ret = $this->task->t_seller_student_new->get_all_list();
        foreach($ret as $item){
            $userid = $item['userid'];
            $add_time = $item['add_time'];
            $seller_add_time = $item['seller_add_time'];
            if($seller_add_time == 0){
                $this->task->t_seller_student_new->field_update_list($userid,[
                    'seller_add_time'=>$add_time,
                ]);
                echo $userid.':'.$item['seller_add_time']."=>".$add_time."\n";
            }
        }



        // $account_role = E\Eaccount_role::V_2;
        // $seller_list = $this->task->t_manager_info->get_seller_list_new_two($account_role);
        // foreach($seller_list as $item){
        //     $adminid = $item['uid'];
        //     $seller_level = $item['seller_level'];
        //     $face_pic = $item['face_pic'];
        //     if($face_pic == ''){
        //         $face_pic = 'http://7u2f5q.com2.z0.glb.qiniucdn.com/fdc4c3830ce59d611028f24fced65f321504755368876.png';
        //     }
        //     $level_face = $item['level_face'];
        //     $level_face_pic = $item['level_face_pic'];
        //     $ret = 0;
        //     if($face_pic && $level_face && $seller_level>0){
        //         $face_pic_str = substr($face_pic,-12,5);
        //         $ex_str = $seller_level.$face_pic_str;
        //         $level_face_pic = $this->get_top_img($adminid,$face_pic,$level_face,$ex_str);
        //         $ret = $this->task->t_manager_info->field_update_list($adminid,['level_face_pic'=>$level_face_pic]);
        //     }
        //     // dd($adminid,$face_pic,$level_face,$level_face_pic,$ret);
        //     echo $adminid.':'."$level_face_pic".",ret:".$ret."\n";
        // }
    }

    //处理等级头像
    public function get_top_img($adminid,$face_pic,$level_face,$ex_str){
        $datapath = $face_pic;
        $datapath_new = $level_face;
        $datapath_type = @end(explode(".",$datapath));
        $datapath_type_new = @end(explode(".",$datapath_new));
        $image_1 = $this->yuan_img($datapath);
        if($datapath_type_new == 'jpg' || $datapath_type_new == 'jpeg'){
            $image_2 = imagecreatefromjpeg($datapath_new);
        }elseif($datapath_type_new == 'png'){
            $image_2 = imagecreatefrompng($datapath_new);
        }elseif($datapath_type_new == 'gif'){
            $image_2 = imagecreatefromgif($datapath_new);
        }elseif($datapath_type_new == 'wbmp'){
            $image_2 = imagecreatefromwbmp($datapath_new);
        }else{
            $image_2 = imagecreatefromstring($datapath_new);
        }
        $image_3 = imageCreatetruecolor(imagesx($image_1),imagesy($image_1));
        // $color = imagecolorallocate($image_3,255,255,255);
        $color = imagecolorallocatealpha($image_3,255,255,255,1);
        imagefill($image_3, 0, 0, $color);
        imageColorTransparent($image_3, $color);

        imagecopyresampled($image_3,$image_2,0,0,0,0,imagesx($image_3),imagesy($image_3),imagesx($image_2),imagesy($image_2));
        imagecopymerge($image_1,$image_3,0,0,0,0,imagesx($image_3),imagesx($image_3),100);
        $tmp_url = "/tmp/".$adminid."_".$ex_str."_gd.png";
        imagepng($image_1,$tmp_url);
        $file_name = \App\Helper\Utils::qiniu_upload($tmp_url);
        $level_face_url = '';
        if($file_name!=''){
            $cmd_rm = "rm /tmp/".$adminid."*.png";
            \App\Helper\Utils::exec_cmd($cmd_rm);
            $domain = config('admin')['qiniu']['public']['url'];
            $level_face_url = $domain.'/'.$file_name;
        }
        return $level_face_url;
    }

    /**
     *  blog:http://www.zhaokeli.com
     * 处理成圆图片,如果图片不是正方形就取最小边的圆半径,从左边开始剪切成圆形
     * @param  string $imgpath [description]
     * @return [type]          [description]
     */
    function yuan_img($imgpath = './tx.jpg') {
        $ext     = pathinfo($imgpath);
        $src_img = null;
        switch ($ext['extension']) {
        case 'jpg':
            $src_img = imagecreatefromjpeg($imgpath);
            break;
        case 'jpeg':
            $src_img = imagecreatefromjpeg($imgpath);
            break;
        case 'png':
            $src_img = imagecreatefrompng($imgpath);
            break;
        }
        $wh  = getimagesize($imgpath);
        $w   = $wh[0];
        $h   = $wh[1];
        $w   = min($w, $h);
        $h   = $w;
        $img = imagecreatetruecolor($w, $h);
        //这一句一定要有
        imagesavealpha($img, true);
        //拾取一个完全透明的颜色,最后一个参数127为全透明
        $bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
        imagefill($img, 0, 0, $bg);
        $r   = $w / 2-20; //圆半径
        $y_x = $r; //圆心X坐标
        $y_y = $r; //圆心Y坐标
        // dd($r,$y_x,$y_y);
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgbColor = imagecolorat($src_img, $x, $y);
                if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r))) {
                    imagesetpixel($img, $x+14, $y+14, $rgbColor);
                }
            }
        }
        return $img;
    }

}
