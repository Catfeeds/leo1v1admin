<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;
use App\Helper\Utils;

class commend_teacher_info_send_wx extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:commend_teacher_info_send_wx';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '教研上课结束后微信接收销售推荐老师申请';

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
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();
        $time = time()-86400;
        $list = $task->t_change_teacher_list->get_no_wx_send_time_list($time,2);
        foreach($list as $val){
            $accept_adminid = $val["accept_adminid"];
            $adminid = $val["ass_adminid"];
            $id = $val["id"];
            $accept_account = $task->t_manager_info->get_account($accept_adminid);
            $account = $task->t_manager_info->get_account($adminid);
            $teacherid = $task->t_teacher_info->get_teacherid_by_adminid($accept_adminid);
            $check_lesson_on = $task->t_lesson_info_b2->check_lesson_on($teacherid);

            if($check_lesson_on != 1){
                $task->t_change_teacher_list->field_update_list($id,["wx_send_time"=>time()]);
                $task->t_manager_info->send_wx_todo_msg_by_adminid ($accept_adminid,"推荐老师","销售推荐老师申请","销售".$account."老师申请推荐老师,请尽快处理","http://admin.yb1v1.com/tea_manage_new/get_seller_require_commend_teacher_info?id=".$id);
                
            }
            
        }
       

        

    }
}
