<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class h5GetPoster extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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


    public function do_change()
    {
        //

        $pdf_lists = $this->task->t_pdf_to_png_info->get_pdf_list_for_doing();

        while(list($key,$item)=each($pdf_lists)){
            $id       = $item['id'];
            $pdf_url  = $item['pdf_url'];
            $lessonid = $item['lessonid'];
            $this->task->t_pdf_to_png_info->field_update_list($id,[
                "id_do_flag" => 2,
                "deal_time"  => time()
            ]);

            $pdf_file_path = $this->get_pdf_download_url($pdf_url);

            $savePathFile = public_path('wximg').'/'.$pdf_url;

            if($pdf_url){
                \App\Helper\Utils::savePicToServer($pdf_file_path,$savePathFile);
                $path = public_path().'/wximg';
                @chmod($savePathFile, 0777);

                $filesize=filesize($savePathFile);

                if($filesize<512){
                    \App\Helper\Utils::logger("filesize_pdf: ".$savePathFile);
                    $this->task->t_pdf_to_png_info->field_update_list($id,[
                        "id_do_flag" => 3, // 文件大小异常
                        "deal_time"  => time()
                    ]);
                    return '';
                }


                $imgs_url_list = $this->pdf2png($savePathFile,$path,$lessonid);
                $file_name_origi = array();
                foreach($imgs_url_list as $item){
                    $file_name_origi[] = @$this->put_img_to_alibaba($item);
                }

                $file_name_origi_str = implode(',',$file_name_origi);

                $ret = $this->task->t_lesson_info->save_tea_pic_url($lessonid, $file_name_origi_str);
                $this->task->t_pdf_to_png_info->field_update_list($id,[
                    "id_do_flag" => 1,
                    "deal_time"  => time()
                ]);

                foreach($imgs_url_list as $item_orgi){
                    @unlink($item_orgi);
                }

                @unlink($savePathFile);
            }
        }


        if ( count( $pdf_lists)==0  )  {
            sleep(20);
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

        $is_exit = file_exists($pdf);


        \App\Helper\Utils::logger("check_pdf $pdf");

        if($is_exit){
            $IM->readImage($pdf);
            foreach($IM as $key => $Var){
                @$Var->setImageFormat('png');
                $Filename = $path."/l_t_pdf_".$lessonid."_".$key.".png" ;
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
