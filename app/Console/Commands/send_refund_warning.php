<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class send_refund_warning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "command:send_refund_warning";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "退费预警推送(助教)";

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
        $task = new \App\Console\Tasks\TaskController();

        // $task->t_manager_info->row_insert([
        //     "account" => "ricky",
        //     "name" => "曹朋",
        //     "wx_openid" => "orwGAs6R4UremX_fhr24MvStIxJc",
        //     "phone" => "13585593461"
        // ]);

        $account = "ricky";
        $from_user = "退费预警";
        $header_msg = "";
        $msg = "三级预警：100".PHP_EOL."二级预警：10".PHP_EOL."一级预警：1";
        $url = "http://admin.leo1v1.com/user_manage/ass_archive_ass";

        $task->t_manager_info->send_wx_todo_msg($account, $from_user, $header_msg, $msg, $url);
    }
}
