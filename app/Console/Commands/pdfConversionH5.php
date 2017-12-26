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
        /**
         * @ 从七牛下载->上传未达->从未达下载->上传到七牛
         * @ michael@leoedu.com 密码 ： 021130
        **/
        $store=new \App\FileStore\file_store_tea();
        $auth=$store->get_auth();
        $email = "michael@leoedu.com";
        $pwd   = 021130;

        $handoutArray = $this->task->t_resource->getResourceList();
        foreach($handoutArray as $item){
            //七牛下载
            $pdf_file_path = $auth->privateDownloadUrl("http://teacher-doc.leo1v1.com/". $item['file_link'] );
            $savePathFile = public_path('wximg').'/'.$item['file_link'];
            \App\Helper\Utils::savePicToServer($pdf_file_path,$savePathFile);
            @chmod($savePathFile, 0777);

            //上传未达
            $cmd  = "curl -F doc=@'$savePathFile' 'http://leo1v1.whytouch.com/mass_up.php?token=bbcffc83539bd9069b755e1d359bc70a&mode=-1&aut=leoedu&fn=".$item['file_link'].".pdf'";
            $uuid = shell_exec($cmd);

            //从未达下载
            $h5DownloadUrl = "http://leo1v1.whytouch.com/export.php?uuid=$uuid&email=$email&pwd=$pwd";
            $saveH5FilePath = public_path('wximg').'/'.$uuid.".zip";
            \App\Helper\Utils::savePicToServer($h5DownloadUrl,$saveH5FilePath);


        }

        $path = $this->get_in_str_val('path');
        $cmd  = "curl -F doc=@'$path' 'http://leo1v1.whytouch.com/mass_up.php?token=bbcffc83539bd9069b755e1d359bc70a&mode=-1&aut=James&fn=新文件.pdf'";
        $uuid = shell_exec($cmd);
        dd($uuid);

        $file_link = $this->get_in_str_val("link");
        $store=new \App\FileStore\file_store_tea();
        $auth=$store->get_auth();
        $authUrl = $auth->privateDownloadUrl("http://teacher-doc.leo1v1.com/". $file_link );
        return $authUrl;
        dd($authUrl);
        return $this->output_succ(["url" => $authUrl]);





    }
}
