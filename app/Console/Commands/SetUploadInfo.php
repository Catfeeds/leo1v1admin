<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetUploadInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SetUploadInfo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '课堂结束10分钟生成视频';

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
        $task=new \App\Console\Tasks\CommonTask() ;
        $task->set_upload_info();
    }
}
