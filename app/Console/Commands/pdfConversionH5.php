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
        //
        $pdfList = $this->task->t_pdf_to_png_info->get_pdf_list_for_doing();

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
