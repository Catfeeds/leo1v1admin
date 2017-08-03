<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;


// 引入鉴权类
use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

require_once(app_path("/Libs/OSS/autoload.php"));
use OSS\OssClient;

use OSS\Core\OssException;


class deal_pdf_to_image extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $pdf_url;
    public $lessonid;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($pdf_url, $lessonid)
    {
        //
        $this->pdf_url   = $pdf_url;
        $this->lessonid  = $lessonid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $t_lesson_info = new  \App\Models\t_lesson_info();

        $pdf_url  = $this->pdf_url;
        $lessonid = $this->lessonid;
        $pdf_file_path = $this->get_pdf_download_url($pdf_url);

        $savePathFile = public_path('wximg').'/'.$pdf_url;

        if($pdf_url){
            \App\Helper\Utils::savePicToServer($pdf_file_path,$savePathFile);

            $path = public_path().'/wximg';

            @chmod($savePathFile, 0777);

            $imgs_url_list = @$this->pdf2png($savePathFile,$path,$lessonid);

            $file_name_origi = array();
            foreach($imgs_url_list as $item){
                $file_name_origi[] = $this->put_img_to_alibaba($item);
            }

            $file_name_origi_str = implode(',',$file_name_origi);

            $ret = $t_lesson_info->save_tea_pic_url($lessonid, $file_name_origi_str);

            foreach($imgs_url_list as $item_orgi){
                @unlink($item_orgi);
            }

            @unlink($savePathFile);
        }

    }


    public function get_pdf_download_url($file_url)
    {
        if (preg_match("/http/", $file_url)) {
            return $file_url;
        } else {
            return \App\Helper\Utils::gen_download_url($file_url);
        }
    }


        //
    public function pdf2png($pdf,$path, $lessonid){

        if(!extension_loaded('imagick')){
            return false;
        }
        if(!$pdf){
            return false;
        }
        $IM =new \imagick();
        $IM->setResolution(100,100);
        $IM->setCompressionQuality(100);

        @$IM->readImage($pdf);
        foreach($IM as $key => $Var){
            @$Var->setImageFormat('png');
            $Filename = $path."/l_t_pdf_".$lessonid."_".$key.".png" ;
            if($Var->writeImage($Filename)==true){
                $Return[]= $Filename;
            }
        }

        return $Return;
    }


    public function put_img_to_alibaba($target){
        try {
            $config=\App\Helper\Config::get_config("ali_oss");
            $file_name=basename($target);

            $ossClient = new OssClient(
                $config["oss_access_id"],
                $config["oss_access_key"],
                $config["oss_endpoint"], false);


            $bucket=$config["public"]["bucket"];
            $ossClient->uploadFile($bucket, $file_name, $target  );

            \App\Helper\Utils::logger('shangchun55'. $config["public"]["url"]."/".$file_name);

            return $config["public"]["url"]."/".$file_name;

        } catch (OssException $e) {
            \App\Helper\Utils::logger( "init OssClient fail");
            return "" ;
        }

    }






}
