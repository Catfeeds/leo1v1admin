<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class student_assign_for_ass_auto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:student_assign_for_ass_auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '学生自动分配助教';

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
        //  return ;
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();
        $time= time()-10*86400;
        $userid_list_new = $task->t_order_info->get_no_ass_stu_info_list();
        foreach($userid_list_new as $item){
            $userid = $item["userid"];
            $master_adminid = $task->t_admin_group_user->get_master_adminid_by_adminid($item["uid"]);
            $r = $task->t_student_info->field_update_list($item["userid"],[
                "ass_master_adminid"=>$master_adminid,
                "master_assign_time"=>time()
            ]);
            if($r){

                $account = $task->t_manager_info->get_account($master_adminid);
                $task->t_manager_info->send_wx_todo_msg  (
                    $account,
                    "销售-".$item["sys_operator"],
                    "交接单 更新 || 合同生效",
                    "学生".$item["nick"],
                    "http://admin.yb1v1.com/user_manage_new/ass_contract_list?studentid=$userid");

            }

        }

        $main_type=1;
        $ass_leader_arr = $task->t_admin_group_name->get_leader_list($main_type);
        $ass_leader_arr=[297,299];
        $num_all = count($ass_leader_arr);
        $userid_list = $task->t_order_info->get_no_ass_stu_info(1,$time);
        $userid = $userid_list["userid"];

        $i=0;
        foreach($ass_leader_arr as $val){
            $json_ret=\App\Helper\Common::redis_get_json("ASS_AUTO_ASSIGN_$val");
            if (!$json_ret) {
                $json_ret=0;
            }
            \App\Helper\Common::redis_set_json("ASS_AUTO_ASSIGN_$val", $json_ret);
            if($json_ret==1){
                $i++;
            }
            // echo $json_ret;
        }
        if($i==$num_all){
             foreach($ass_leader_arr as $val){
                 \App\Helper\Common::redis_set_json("ASS_AUTO_ASSIGN_$val", 0);
             }
        }

        if($userid>0){
            foreach($ass_leader_arr as $val){
                $json_ret=\App\Helper\Common::redis_get_json("ASS_AUTO_ASSIGN_$val");
                echo $val."<br>";
                echo $json_ret."<br>";
                if($json_ret==0){
                    $ret = $task->t_student_info->field_update_list($userid,[
                        "ass_master_adminid"=>$val,
                        "master_assign_time"=>time()
                    ]);
                    if($ret){
                        \App\Helper\Common::redis_set_json("ASS_AUTO_ASSIGN_$val", 1);
                        $account = $task->t_manager_info->get_account($val);
                        $task->t_manager_info->send_wx_todo_msg  (
                            $account,
                            "销售-".$userid_list["sys_operator"],
                            "交接单 更新 || 合同生效",
                            "学生".$userid_list["nick"],
                            "http://admin.yb1v1.com/user_manage_new/ass_contract_list?studentid=$userid");
                        break;

                    }

                }
            }


        }


        /*if(isset($stu_list[$i])){
                $task->t_student_info->field_update_list($stu_list[$i]["userid"],[
                    "ass_master_adminid"=>$val,
                    "master_assign_time"=>time()
                ]);
                $i++;
                } */



    }
}
