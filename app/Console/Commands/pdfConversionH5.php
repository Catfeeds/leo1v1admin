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
        $pwd   = 021130; // bbcffc83539bd9069b755e1d359bc70a


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
            // $uuid = substr($uuid_tmp,3);

            $uuid = $uuid_arr['1'];
            \App\Helper\Utils::logger("qiniuupload_james_9999: $uuid");

            //从未达下载
            $h5DownloadUrl = "http://leo1v1.whytouch.com/export.php?uuid=$uuid&email=$email&pwd=$pwd";
            $saveH5FilePath = public_path('wximg').'/'.$uuid.".zip";

            \App\Helper\Utils::logger("qiniuupload_james_788: $saveH5FilePath");
            // \App\Helper\Utils::savePicToServer($h5DownloadUrl,$saveH5FilePath);

            file_put_contents($saveH5FilePath, fopen($h5DownloadUrl, 'r'));

            // //download to ZIP
            // $filename = str_replace('\\', '/', public_path()) . '/downloads_xml/' . date('YmdHis') . '.zip'; // 最终生成的文件名（含路径）
            // // 生成文件
            // $zip = new ZipArchive (); // 使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
            // if ($zip->open($filename, ZIPARCHIVE::CREATE) !== TRUE) {
            //     exit ('无法打开文件，或者文件创建失败');
            // }
            // $list = image::where('event_id', $id)->where('user_id', Auth::user()->id)->with('author')->get();
            // foreach ($list as $key => $value) {
            //     $fingerprint = explode('.', $value->fingerprint)[0];
            //     $zip->addFile(str_replace('\\', '/', public_path()) . '/downloads_xml/' . $fingerprint . '.xml', basename($fingerprint . ' --' . ($key+1) . '.xml')); // 第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
            // }
            // $zip->close(); // 关闭
            // //下面是输出下载;
            // header("Cache-Control: max-age=0");
            // header("Content-Description: File Transfer");
            // header('Content-disposition: attachment; filename=' . basename($filename)); // 文件名
            // header("Content-Type: application/zip"); // zip格式的
            // header("Content-Transfer-Encoding: binary"); // 告诉浏览器，这是二进制文件
            // header('Content-Length: ' . filesize($filename)); // 告诉浏览器，文件大小
            // @readfile($filename);//输出文件;







            // 上传七牛
            $saveH5Upload =  \App\Helper\Utils::qiniu_upload($saveH5FilePath);

            \App\Helper\Utils::logger("qiniuupload_james_1: $saveH5Upload");
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
