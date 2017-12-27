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
                "file_link" => '037ab4c73279591d363017b22e6b86521513827415246.pdf',
                "file_id"   => 4,
                "uuid"      => 'gfa6a2e9768a5cc4c12ba11fbc6a8ff2'
            ]
        ];



        foreach($handoutArray as $item){
            $uuid = $item['uuid'];
            //从未达下载
            $h5DownloadUrl = "http://leo1v1.whytouch.com/export.php?uuid=".$uuid."&email=".$email."&pwd=".$pwd;
            $saveH5FilePath = public_path('wximg').'/'.$uuid.".zip";

            $data=file_get_contents($h5DownloadUrl);
            file_put_contents($saveH5FilePath, $data);

            // 上传七牛
            $saveH5Upload =  \App\Helper\Utils::qiniu_upload($saveH5FilePath);
            @unlink($saveH5FilePath);

            $task->t_resource_file->field_update_list($item['file_id'],[
                "zip_url" => $saveH5Upload
            ]);
        }
    }
    // $h5DownloadUrl = "http://leo1v1.whytouch.com/export.php?uuid=g050c18adf68d373aa34f63db3a906d8&email=michael@leoedu.com&pwd=bbcffc83539bd9069b755e1d359bc70a";

}
