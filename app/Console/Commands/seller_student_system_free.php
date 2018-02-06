<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class seller_student_system_free extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "command:seller_student_system_free";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "系统释放例子";


    /**
     * Execute the console command.
     * @return mixed
     */
    public function do_handle()
    {
        \App\Helper\Utils::logger("begin");
        //14:30  发现没拨打
        $check_time=strtotime(date("Y-m-d 14:30"));
        $today_start_time=strtotime(date("Y-m-d"));
        $now= time(NULL);
        $work_start_time_map=$this->task->t_admin_work_start_time-> get_today_work_start_time_map();
        $check_for_free_user_list=$this->task->t_admin_work_start_time-> get_today_work_start_time_map();
        if ($now>=$check_time ) {
            $check_free_list= $this->task->t_seller_student_new_b2->get_need_check_free_list();
            foreach( $check_free_list as $item ) {
                $admin_revisiterid = $item["admin_revisiterid"];
                $userid = $item["userid"];
                $phone = $item["phone"];
                $admin_assign_time= $item["admin_assign_time"];
                $check_hold_flag = true;
                $free_flag=!isset($work_start_time_map[$admin_revisiterid] ); //没有登录
                if (!$free_flag) {
                    $check_hold_flag = false;
                    $release_reason_flag = 1;
                    if ($admin_assign_time < $today_start_time ) { //今天之前的例子都free
                        $free_flag=true;
                    }
                }
                if (!$free_flag) {
                    $user_check_time= max( @$work_start_time_map[$admin_revisiterid]["work_start_time"], $admin_assign_time  );
                    //分配并上班6个小时 free
                    if ($now-$user_check_time>6*3600 ) {
                        //TODO
                        $free_flag=true;
                        $release_reason_flag = 2;
                    }
                }
                //再次检测该用户是否已拨通
                $is_through = $this->task->t_tq_call_info->get_is_through($phone,$admin_revisiterid);
                if($is_through)
                    $free_flag=false;

                if ($free_flag) {
                    \App\Helper\Utils::logger("例子释放分析-1userid:$userid adminid:$admin_revisiterid");
                    //清空
                    $userid_list=[$userid];
                    $opt_type ="" ;
                    $opt_adminid= 0;
                    $opt_type=0;
                    $account="系统分配-回收例子";
                    $this->task->t_seller_student_new->set_admin_id_ex( $userid_list, $opt_adminid, 0,$account);
                    if($check_hold_flag)
                        $this->task->t_seller_student_system_assign_log->update_check_flag($userid,$admin_revisiterid);
                    //记录释放日志
                    $this->task->t_seller_student_system_release_log->add_log(
                        $admin_revisiterid,$userid,$phone,$release_reason_flag
                    );
                }
            }
        }else { //  free -1 day
            $check_free_list= $this->task->t_seller_student_new_b2->get_need_check_free_list();

            foreach( $check_free_list as $item ) {
                $free_flag=false;
                $admin_revisiterid = $item["admin_revisiterid"];
                $userid = $item["userid"];
                $phone = $item["phone"];
                $admin_assign_time= $item["admin_assign_time"];
                if ($admin_assign_time < $today_start_time ) { //今天之前的例子都free
                    $free_flag=true;
                    $release_reason_flag = 3;
                }

                $is_through = $this->task->t_tq_call_info->get_is_through($phone,$admin_revisiterid);
                //再次检测该用户是否已拨通
                if($is_through)
                    $free_flag=false;

                if ($free_flag) {
                    $today_start_time_str = date('Y-m-d H:i:s',$today_start_time);
                    $admin_assign_time_str = date('Y-m-d H:i:s',$admin_assign_time);
                    \App\Helper\Utils::logger("例子释放分析-2userid:$userid adminid:$admin_revisiterid today_start_time_str:$today_start_time_str admin_assign_time_str:$admin_assign_time_str");
                    //清空
                    $userid_list=[$userid];
                    $opt_type ="" ;
                    $opt_adminid= 0;
                    $opt_type=0;
                    $account="系统分配-回收例子";
                    //echo "free $userid\n";
                    $this->task->t_seller_student_new->set_admin_id_ex( $userid_list, $opt_adminid, 0,$account);
                    //记录释放日志
                    $this->task->t_seller_student_system_release_log->add_log(
                        $admin_revisiterid,$userid,$phone,$release_reason_flag,$admin_assign_time
                    );

                }
            }
            \App\Helper\Utils::logger("end");
        }

    }

}
