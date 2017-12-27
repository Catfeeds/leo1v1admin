<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class pdfConversionH5 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:pdfConversionH5';

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
        /**
         * @ 从七牛下载->上传未达->从未达下载->上传到七牛
         * @ michael@leoedu.com 密码 ： 021130
        **/
        $store=new \App\FileStore\file_store_tea();
        $auth=$store->get_auth();
        $email = "michael@leoedu.com";
        // $pwd   = md5(021130); // bbcffc83539bd9069b755e1d359bc70a
        $pwd   = 'bbcffc83539bd9069b755e1d359bc70a';


        // $handoutArray = $this->task->t_resource->getResourceList();
        $handoutArray = [
            [
                "file_link" => '037ab4c73279591d363017b22e6b86521513827415246.pdf'
            ]
        ];
        foreach($handoutArray as $item){
            //七牛下载
            $pdf_file_path = $auth->privateDownloadUrl("http://teacher-doc.leo1v1.com/". $item['file_link'] );
            $savePathFile = public_path('wximg').'/'.$item['file_link'];
            \App\Helper\Utils::savePicToServer($pdf_file_path,$savePathFile);
            @chmod($savePathFile, 0777);

            //上传未达
            $cmd  = "curl -F doc=@'$savePathFile' 'http://leo1v1.whytouch.com/mass_up.php?token=bbcffc83539bd9069b755e1d359bc70a&mode=-1&aut=leoedu&fn=".$item['file_link'].".pdf'";
            $uuid_tmp = shell_exec($cmd);
            $uuid_arr = explode(':', $uuid_tmp);

            $uuid = $uuid_arr['1'];
            \App\Helper\Utils::logger("qiniuupload_james_9999: $uuid");

            //从未达下载
            // $h5DownloadUrl = "http://leo1v1.whytouch.com/export.php?uuid=$uuid&email=$email&pwd=$pwd";
            $h5DownloadUrl = "http://leo1v1.whytouch.com/export.php?uuid=g050c18adf68d373aa34f63db3a906d8&email=michael@leoedu.com&pwd=bbcffc83539bd9069b755e1d359bc70a";
            $saveH5FilePath = public_path('wximg').'/'.$uuid.".zip";
            // $saveH5FilePath = '/home/james/admin_yb1v1/public/wximg/'.$uuid.".zip";

            // $cmdDownload = "curl $h5DownloadUrl -o $saveH5FilePath";
            // \App\Helper\Utils::savePicToServer($h5DownloadUrl,$saveH5FilePath);
            // shell_exec($cmdDownload);

            $data=file_get_contents($h5DownloadUrl);
            echo strlen($data);
            \App\Helper\Utils::logger("pdfConversionH5_james111:".$data);

            // file_put_contents($saveH5FilePath, $data);

            // \App\Helper\Utils::logger("qiniuupload_james_1000: $h5DownloadUrl");
            // \App\Helper\Utils::logger("qiniuupload_james_788: $saveH5FilePath");
            // \App\Helper\Utils::savePicToServer($h5DownloadUrl,$saveH5FilePath);

            // 上传七牛
            // $saveH5Upload =  \App\Helper\Utils::qiniu_upload($saveH5FilePath);

            // \App\Helper\Utils::logger("qiniuupload_james_1: $saveH5Upload");
            //ok:gf15a4973b034c84d4f631be74b21741.zip
        }
    }

    public function curl_download($url, $dir){
        $ch = curl_init($url);
        $fp = fopen($dir, "wb");
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $res=curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        return $res;
    }
}
