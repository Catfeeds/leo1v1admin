<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use OSS\OssClient;

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
        $pwd   = 'bbcffc83539bd9069b755e1d359bc70a';// md5(021130)
        $task=new \App\Console\Tasks\TaskController();

        // $handoutArray = $task->t_resource_file->getResourceList();

        $handoutArray = [
            [
                "file_link" => 'aaf3622180de9ae8967eb7986c10bce61513827415224.pdf',
                "file_id"   => 6,
                "uuid"      => 'g03c6f7a81c9bdba93137ac50d77ea81'
            ]
        ];



        foreach($handoutArray as $item){
            $uuid = $item['uuid'];
            //从未达下载
            $h5DownloadUrl = "http://leo1v1.whytouch.com/export.php?uuid=".$uuid."&email=".$email."&pwd=".$pwd;
            $saveH5FilePath = public_path('wximg').'/'.$uuid.".zip";
            $unzipFilePath  =  public_path('wximg'); // 解压后的文件夹

            $data=file_get_contents($h5DownloadUrl);
            file_put_contents($saveH5FilePath, $data);


            /**
             * @ 将目录下的文件批量上传到阿里云
             * @  解压文件包->获取文件包下文件->文件批量上传
             */

            $unzipShell = "unzip $saveH5FilePath -d $unzipFilePath ";
            shell_exec($unzipShell);

            $handler = opendir($unzipFilePath."/".$uuid);
            while (($filename = readdir($handler)) !== false) {//务必使用!==，防止目录下出现类似文件名“0”等情况
                if ($filename != "." && $filename != "..") {
                    $files[] = $filename ;
                }
            }
            @closedir($handler);
            $test_data = '';

            // 使用七牛上传
            $qiniu     = \App\Helper\Config::get_config("qiniu");
            $bucket    = $qiniu['public']['bucket'];
            $accessKey = $qiniu['access_key'];
            $secretKey = $qiniu['secret_key'];

            // 构建鉴权对象
            $auth = new \Qiniu\Auth ($accessKey, $secretKey);
            $h5Path = "pdfToH5"; // 环境文件夹
            foreach ($files as $key) {
                // 上传到七牛后保存的文件名
                $upkey = $h5Path."/".$uuid."/".$key;

                // 生成上传 Token
                $token = $auth->uploadToken($bucket,$upkey);
                $Upfile = $unzipFilePath."/".$uuid."/".$key;

                // 初始化 UploadManager 对象并进行文件的上传。
                $uploadMgr = new \Qiniu\Storage\UploadManager();

                // 调用 UploadManager 的 putFile 方法进行文件的上传。
                list($ret, $err) = $uploadMgr->putFile($token, $upkey, $Upfile);
                $test_data .= $ret["key"]." ";
                if($key == 'index.html'){
                    \App\Helper\Utils::logger("upkey_qiniu: $upkey");
                    $task->t_resource_file->field_update_list($item['file_id'],[
                        "wx_index" => "https://ybprodpub.leo1v1.com/".$upkey
                    ]);

                }
            }

            // 压缩包上传七牛
            $saveH5Upload =  \App\Helper\Utils::qiniu_upload($saveH5FilePath);
            @unlink($saveH5FilePath);
            $this->deldir($unzipFilePath."/".$uuid); // 删除解压包
            // 七牛 资源域名 https://ybprodpub.leo1v1.com/

            $task->t_resource_file->field_update_list($item['file_id'],[
                "zip_url" => $saveH5Upload
            ]);
        }
    }



    function deldir($dir) {
        //先删除目录下的文件：
        $dh=opendir($dir);
        while ($file=readdir($dh)) {
            if($file!="." && $file!="..") {
                $fullpath=$dir."/".$file;
                if(!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    deldir($fullpath);
                }
            }
        }

        closedir($dh);
        //删除当前文件夹：
        if(rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }


    /***
    *
    *
    配置环境
                // 使用七牛上传
            $qiniu     = \App\Helper\Config::get_config("qiniu");
            $bucket    = $qiniu['public']['bucket'];
            $accessKey = $qiniu['access_key'];
            $secretKey = $qiniu['secret_key'];

            // 构建鉴权对象
            $auth = new \Qiniu\Auth ($accessKey, $secretKey);

            $h5Path = "pdfToH5"; // 环境文件夹


            foreach ($files as $key) {

                // 上传到七牛后保存的文件名
                $upkey = $h5Path."/".$key;

                // 生成上传 Token
                $token = $auth->uploadToken($bucket,$upkey);
                $Upfile = $tmp."/".$key;

                // 初始化 UploadManager 对象并进行文件的上传。
                $uploadMgr = new \Qiniu\Storage\UploadManager();

                // 调用 UploadManager 的 putFile 方法进行文件的上传。
                list($ret, $err) = $uploadMgr->putFile($token, $key, $Upfile);
                // if ($err !== null) {
                //     return false;
                // } else {
                    $test_data .= $ret["key"]." ";
                // }

            }

            \App\Helper\Utils::logger("test_data_qiniu_url_main: $test_data");

            exit();

            // $config=\App\Helper\Config::get_config("ali_oss");
            // $ossClient = new OssClient(
            //     $config["oss_access_id"],
            //     $config["oss_access_key"],
            //     $config["oss_endpoint"],
            //     false
            // );
            // $h5Path = "pdfToH5/".$uuid; // 环境文件夹

            // foreach ($files as $file_name) {
            //     $h5FileName = $h5Path.'/'.$file_name;
            //     $target = $unzipFilePath."/".$uuid."/".$file_name; //本地文件路径

            //     $bucket=$config["public"]["bucket"];
            //     $ossClient->uploadFile($bucket, $h5FileName, $target  );
            //     $downLoad = $config["public"]["url"]."/".$h5FileName;

            //     if($file_name == 'index.html'){ // 作为微信访问页

            //     }
            //     $test_data.=$downLoad." ";
            // }

            // \App\Helper\Utils::logger("test_data_ali_url: $test_data");

            exit();


    *
    *


     ***/

}
