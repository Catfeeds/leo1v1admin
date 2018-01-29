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
        //14:30  发现没拨打
        $check_time=strtotime(date("Y-m-d 14:30"));
        $today_start_time=strtotime(date("Y-m-d"));
        $now= time(NULL);
        //cc开始工作时间表
        $work_start_time_map=$this->task->t_admin_work_start_time-> get_today_work_start_time_map();
        //cc开始工作时间表
        $check_for_free_user_list=$this->task->t_admin_work_start_time-> get_today_work_start_time_map();
        if ($now>=$check_time ) {
            //3天内分配未拨通的量
            $check_free_list= $this->task->t_seller_student_new_b2->get_need_check_free_list();
            foreach( $check_free_list as $item ) {
                $check_hold_flag = true;
                $admin_revisiterid = $item["admin_revisiterid"];
                $userid = $item["userid"];
                $print_arr =[
                    'admind' => $admin_revisiterid,
                    'userid' => $userid,
                ];
                $admin_assign_time= $item["admin_assign_time"];
                $free_flag=!isset($work_start_time_map[$admin_revisiterid] ); //没有登录
                if (!$free_flag) {
                    $check_hold_flag = false;
                    if ($admin_assign_time < $today_start_time ) { //今天之前的例子都free
                        $free_flag=true;
                    }
                }
                if (!$free_flag) {
                    //一般都为开始上班时间
                    $user_check_time= max( @$work_start_time_map[$admin_revisiterid]["work_start_time"], $admin_assign_time  );
                    //分配并上班6个小时 free
                    if ($now-$user_check_time>6*3600 ) {
                        //TODO
                        //$free_flag=true;
                    }
                }

                if ($free_flag) {
                    //清空
                    $userid_list=[$userid];
                    $opt_type ="" ;
                    $opt_adminid= 0;
                    $opt_type=0;
                    $account="系统分配-回收例子";
                    $this->task->t_seller_student_new->set_admin_id_ex( $userid_list, $opt_adminid, 0,$account);
                    print_r($print_arr);
                    if($check_hold_flag)
                        $this->task->t_seller_student_system_assign_log->update_check_flag($userid,$admin_revisiterid,$check_hold_flag);
                }
            }
        }else { //  free -1 day
            $check_free_list= $this->task->t_seller_student_new_b2->get_need_check_free_list();
            $free_flag=false;
            foreach( $check_free_list as $item ) {
                $admin_revisiterid = $item["admin_revisiterid"];
                $userid = $item["userid"];
                $print_arr =[
                    'admind' => $admin_revisiterid,
                    'userid' => $userid,
                ];
                $admin_assign_time= $item["admin_assign_time"];
                if ($admin_assign_time < $today_start_time ) { //今天之前的例子都free
                    $free_flag=true;
                }
                if ($free_flag) {
                    //清空
                    $userid_list=[$userid];
                    $opt_type ="" ;
                    $opt_adminid= 0;
                    $opt_type=0;
                    $account="系统分配-回收例子";
                    //echo "free $userid\n";
                    $this->task->t_seller_student_new->set_admin_id_ex( $userid_list, $opt_adminid, 0,$account);
                    print_r($print_arr);
                    // $check_hold_flag = true;
                    // $this->task->t_seller_student_system_assign_log->update_check_flag($userid,$admin_revisiterid);
                }
            }
        }
        echo 'ok!';

    }

}
