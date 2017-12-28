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
            $unzipFilePath  =  public_path('wximg').'/'.$uuid; // 解压后的文件

            $data=file_get_contents($h5DownloadUrl);
            file_put_contents($saveH5FilePath, $data);

            /**
             * @ 将目录下的文件批量上传到阿里云
             * @  解压文件包->获取文件包下文件->文件批量上传
             */

            $unzipShell = "unzip $saveH5FilePath";
            shell_exec($unzipShell);

            $handler = opendir($unzipFilePath);
            while (($filename = readdir($handler)) !== false) {//务必使用!==，防止目录下出现类似文件名“0”等情况
                if ($filename != "." && $filename != "..") {
                    $files[] = $filename ;
                }
            }
            @closedir($handler);
            $test_data = '';
            foreach ($files as $value) {
                // echo $value."<br />";
                $test_data.=$value;
            }

            \App\Helper\Utils::logger("test_data_2017-12-28: $test_data");


            exit();

            $config=\App\Helper\Config::get_config("ali_oss");
            $file_name=basename($target);

            $ossClient = new OssClient(
                $config["oss_access_id"],
                $config["oss_access_key"],
                $config["oss_endpoint"], false);


            $bucket=$config["public"]["bucket"];
            $ossClient->uploadFile($bucket, $file_name, $target  );






            /*

              $no = "unzip ./g050c18adf68d373aa34f63db3a906d8.zip ";
              shell_exec($no);

              exit();


              //获取某目录下所有文件、目录名（不包括子目录下文件、目录名）
              $handler = opendir("./tests");
              while (($filename = readdir($handler)) !== false) {//务必使用!==，防止目录下出现类似文件名“0”等情况
              if ($filename != "." && $filename != "..") {
              $files[] = $filename ;
              }
              }
              closedir($handler);

              //打印所有文件名
              foreach ($files as $value) {
              echo $value."<br />";
              }





             */

            // $config=\App\Helper\Config::get_config("ali_oss");
            // $file_name=basename($target);

            // $ossClient = new OssClient(
            //     $config["oss_access_id"],
            //     $config["oss_access_key"],
            //     $config["oss_endpoint"], false);


            // $bucket=$config["public"]["bucket"];
            // $ossClient->uploadFile($bucket, $file_name, $target  );

            // return $config["public"]["url"]."/".$file_name;













            // 压缩包上传七牛
            // $saveH5Upload =  \App\Helper\Utils::qiniu_upload($saveH5FilePath);
            // @unlink($saveH5FilePath);

            // $task->t_resource_file->field_update_list($item['file_id'],[
            //     "zip_url" => $saveH5Upload
            // ]);
        }
    }
    // $h5DownloadUrl = "http://leo1v1.whytouch.com/export.php?uuid=g050c18adf68d373aa34f63db3a906d8&email=michael@leoedu.com&pwd=bbcffc83539bd9069b755e1d359bc70a";

}
