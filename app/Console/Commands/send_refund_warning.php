<?php

namespace App\Console\Commands;

use \App\Enums as E;
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

        $info = $task->t_manager_info->get_ass_info(E\Eaccount_role::V_1);
        foreach($info as $key=>$val) {
            $assistantid = $task->t_assistant_info->get_assistantid( $val["account"] );
            $refund_warning = $task->t_student_info->get_refund_warning($assistantid);
            $account = $val["account"];
            $from_user = "退费预警";
            $header_msg = "";
            $one = $two = $three = 0;
            if ($refund_warning['one']) $one = $refund_warning['one'];
            if ($refund_warning['two']) $two = $refund_warning["two"];
            if ($refund_warning['three']) $three = $refund_warning["three"];
            $msg = "三级预警：$three".PHP_EOL."二级预警：$two".PHP_EOL."一级预警：$one";
            $url = "http://admin.leo1v1.com/user_manage/ass_archive_ass";

            $task->t_user_log->add_data("助教 $key:".$account."待办主题:".$from_user." 预警级别三二一:".$three."-".$two."-".$one);

            $task->t_manager_info->send_wx_todo_msg($account, $from_user, $header_msg, $msg, $url);
        }

        // $account = "ricky";
        // $from_user = "退费预警";
        // $header_msg = "";
        // $msg = "三级预警：100".PHP_EOL."二级预警：10".PHP_EOL."一级预警：1";
        // $url = "http://admin.leo1v1.com/user_manage/ass_archive_ass";

        //$task->t_manager_info->send_wx_todo_msg($account, $from_user, $header_msg, $msg, $url);
    }
}
