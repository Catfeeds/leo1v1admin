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
        $account_role = E\Eaccount_role::V_2;
        $seller_list = $this->task->t_manager_info->get_seller_list_new_two($account_role);
        foreach($seller_list as $item){
            $adminid = $item['uid'];
            $face_pic = $item['face_pic'];
            if($face_pic == ''){
                $face_pic = 'http://7u2f5q.com2.z0.glb.qiniucdn.com/fdc4c3830ce59d611028f24fced65f321504755368876.png';
            }
            $level_face = $item['level_face'];
            $level_face_pic = $item['level_face_pic'];
            $ret = 0;
            if($level_face_pic == '' && $level_face != ''){
                $level_face_pic = $this->get_top_img($adminid,$face_pic,$level_face);
                $ret = $this->task->t_manager_info->field_update_list($adminid,['level_face_pic'=>$level_face_pic]);
            }
            // dd($adminid,$face_pic,$level_face,$level_face_pic,$ret);
            echo $adminid.':'."$level_face_pic".",ret:".$ret."\n";
        }
    }

    //处理等级头像
    public function get_top_img($adminid,$face_pic,$level_face){
        $datapath = $face_pic;
        $datapath_new = $level_face;
        $datapath_type = @end(explode(".",$datapath));
        $datapath_type_new = @end(explode(".",$datapath_new));
        if($datapath_type == 'jpg' || $datapath_type == 'jpeg'){
            $image_1 = imagecreatefromjpeg($datapath);
        }elseif($datapath_type == 'png'){
            $image_1 = imagecreatefrompng($datapath);
        }elseif($datapath_type == 'gif'){
            $image_1 = imagecreatefromgif($datapath);
        }elseif($datapath_type == 'wbmp'){
            $image_1 = imagecreatefromwbmp($datapath);
        }else{
            $image_1 = imagecreatefromstring($datapath);
        }
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
        $tmp_url = "/tmp/".$adminid."_gk.png";
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


}
