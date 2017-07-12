<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NoticeTeacherFeedbackToAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:NoticeTeacherFeedbackToAdmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每天检查老师反馈,提醒后台人员审核.';

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
        $list = $task->t_teacher_feedback_list->get_admin_list();

        $has_push   = [];
        $from_user  = "反馈系统";
        $header_msg = "今天有未处理的老师反馈，请及时处理。";
        $msg        = "";

        foreach($list as $val){
            $acc = "";
            if(in_array($val['feedback_type'],[201,202,203,204,205])){
                if($val['accept_adminid']){
                    if(!in_array($val['accept_adminid'],$has_push)){
                        $acc = $task->t_manager_info->get_account($val['accept_adminid']);
                        $has_push[] = $val['accept_adminid'];
                    }
                }elseif($val['assistantid']){
                    if(!in_array($val['assistantid'],$has_push)){
                        $acc = $task->t_assistant_info->get_account_by_id($val['assistantid']);
                        $has_push[] = $val['assistantid'];
                    }
                }
            }else{
                if(!in_array("adrian",$has_push)){
                    $acc        = "adrian";
                    $has_push[] = $acc;
                }
            }

            if($acc){
                $task->t_manager_info->send_wx_todo_msg($acc,$from_user,$header_msg,$msg,"");
            }
        }
    }
}
