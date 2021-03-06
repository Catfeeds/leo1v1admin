<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


// 引入鉴权类
use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

require_once(app_path("/Libs/OSS/autoload.php"));
use OSS\OssClient;

use OSS\Core\OssException;



class h5GetPoster extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:h5GetPoster';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $this->do_change();
    }


    /**
     * 修改1: pdf讲义地址修改 - 2018-01-05 by james
     */
    public function do_change()
    {
        //
        $this->task=new \App\Console\Tasks\TaskController();
        $store=new \App\FileStore\file_store_tea();
        $auth=$store->get_auth();

        // $auth = new \Qiniu\Auth(
        //     \App\Helper\Config::get_qiniu_access_key(),
        //     \App\Helper\Config::get_qiniu_secret_key()
        // );



        $pdf_lists = $this->task->t_resource_file->getH5PosterInfo();

        while(list($key,$item)=each($pdf_lists)){
            $pdf_url  = $item['file_link'];
            $id = $item['file_id'];
            $this->task->t_resource_file->field_update_list($id,[
                "change_status" => 2,
            ]);

            # 此下载域名需要 已更换 boby
            // $config=\App\Helper\Config::get_config("qiniu");
            // $bucket_info=$config["private_url"]['url'];

            // $pdf_file_path = $auth->privateDownloadUrl($bucket_info.'/'.$pdf_url );
            $pdf_file_path = $auth->privateDownloadUrl("http://teacher-doc.leo1v1.com/".$pdf_url);


            $savePathFile = public_path('wximg').'/'.$pdf_url;
            if($pdf_url){
                \App\Helper\Utils::savePicToServer($pdf_file_path,$savePathFile);
                $path = public_path().'/wximg';
                @chmod($savePathFile, 0777);

                // $filesize=filesize($savePathFile);

                $imgs_url_list = $this->pdf2png($savePathFile,$path,$id);
                $file_name_origi = array();
                foreach($imgs_url_list as $i=> $item){
                    $file_name_origi[] = @$this->put_img_to_alibaba($item);
                }


                $file_name_origi_str = implode(',',$file_name_origi);

                $this->task->t_resource_file->field_update_list($id,[
                    "filelinks" => $file_name_origi_str,
                    "change_status" => 1,
                    "file_poster"   => $file_name_origi[0]
                ]);

                foreach($imgs_url_list as $item_orgi){
                    @unlink($item_orgi);
                }

                @unlink($savePathFile);

            }
        }

        if ( count( $pdf_lists)==0  )  {
            sleep(2);
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
    public function pdf2png($pdf,$path, $id){

        if(!extension_loaded('imagick')){
            return false;
        }
        if(!$pdf){
            return false;
        }
        $IM =new \imagick();
        $IM->setResolution(100,100);
        $IM->setCompressionQuality(100);

        $is_exit = file_exists($pdf);

        if($is_exit){
            try{
                @$IM->readImage($pdf);
            }catch (Exception $e) {
                echo 'Caught exception_H5: ',  $e->getMessage(), "\n";
                $IM->clear();
                return [];
            }

            foreach($IM as $key => $Var){
                @$Var->setImageFormat('png');
                $Filename = $path."/handoutToPng_".$id."_".$key.".png" ;
                if($Var->writeImage($Filename)==true){
                    $Return[]= $Filename;
                }
            }
            $IM->clear();
            return $Return;
        }else{
            $IM->clear();
            return [];
        }


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

            return $config["public"]["url"]."/".$file_name;

        } catch (OssException $e) {
            \App\Helper\Utils::logger( "init OssClient fail");
            return "" ;
        }

    }

}
