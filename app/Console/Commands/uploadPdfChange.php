<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class uploadPdfChange extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:uploadPdfChange';

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
                "file_link" => '037ab4c73279591d363017b22e6b86521513827415246.pdf',
                "file_id"   => 4
            ]
        ];


        foreach($handoutArray as $item){
            //七牛下载
            $pdf_file_path = $auth->privateDownloadUrl("http://teacher-doc.leo1v1.com/".$item['file_link'] );
            $savePathFile = public_path('wximg').'/'.$item['file_link'];
            \App\Helper\Utils::savePicToServer($pdf_file_path,$savePathFile);
            @chmod($savePathFile, 0777);

            //上传未达
            $cmd  = "curl -F doc=@'$savePathFile' 'http://leo1v1.whytouch.com/mass_up.php?token=bbcffc83539bd9069b755e1d359bc70a&mode=-1&aut=leoedu&fn=".$item['file_link'].".pdf'";
            $uuid_tmp = shell_exec($cmd);
            $uuid_arr = explode(':', $uuid_tmp);
            $uuid = $uuid_arr[1];

            $this->task->t_resource_file->field_update_list($item['file_id'],[
                "uuid" => $uuid
            ]);
        }

    }
}
