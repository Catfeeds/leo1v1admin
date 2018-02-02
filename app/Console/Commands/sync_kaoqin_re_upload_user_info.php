<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class sync_kaoqin_re_upload_user_info extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "command:sync_kaoqin_re_upload_user_info ";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "上报考勤信息,处理过期数据";


    /**
     * Execute the console command.
     * @return mixed
     */
    public function do_handle()
    {

        $this->task->t_manager_info->sync_kaoqin_re_upload_user_info();

    }

}
