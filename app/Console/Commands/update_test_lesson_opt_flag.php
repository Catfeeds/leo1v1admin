<?php
namespace App\Console\Commands;
use \App\Enums as E;
class update_test_lesson_opt_flag extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_test_lesson_opt_flag';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';




    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $end_time = strtotime(date('Y-m-d',time(null)).'00:00:00');
        $start_time = $end_time - 24*3600*3;
        $task = new \App\Console\Tasks\TaskController();
        // $ret_info = $task->t_test_lesson_opt_log->get_room_list($start_time,$end_time);
        $ret_info = $task->t_test_lesson_opt_log->get_room_lesson_list($start_time,$end_time);
        $roomid_arr = array_unique(array_column($ret_info,'roomid'));
        $roomid_arr = array_unique(array_column($ret_info,'lessonid'));
        foreach($roomid_arr as $info){//登录
            $stu_login = [];
            $stu_logout = [];
            $seller_login = [];
            $seller_logout = [];
            foreach($ret_info as $item){
                $roomid = $item['roomid'];
                $role = $item['role'];
                $action = $item['action'];
                $opt_type = $item['opt_type'];

                if($info == $roomid && $action == E\Eaction::V_1){
                    if($role == E\Erole::V_1 && $opt_type == E\Etest_opt_type::V_1){//学生登录
                        $stu_login = $item;
                    }elseif($role == E\Erole::V_1 && $opt_type == E\Etest_opt_type::V_2){//学生退出
                        $stu_logout = $item;
                    }elseif($role == E\Erole::V_6 && $opt_type == E\Etest_opt_type::V_1){//cc登录
                        $seller_login = $item;
                    }elseif($role == E\Erole::V_6 && $opt_type == E\Etest_opt_type::V_2){//cc退出
                        $seller_logout = $item;
                    }
                }
            }
            if(count($stu_login)>0 && count($stu_logout)>0 && count($seller_login)>0 && count($seller_logout)>0){
                $userid = $stu_login['userid'];
                $login_time_stu = $stu_login['opt_time'];
                $logout_time_stu = $stu_logout['opt_time'];
                $server_ip_stu = $stu_login['server_ip'];
                $login_time_seller = $seller_login['opt_time'];
                $logout_time_seller = $seller_logout['opt_time'];
                $server_ip_seller = $seller_logout['server_ip'];
                $test_lesson_opt_flag = $task->t_seller_student_new->field_get_value($userid,'test_lesson_opt_flag');
                if($server_ip_stu != $server_ip_seller && $test_lesson_opt_flag==0){
                    if($logout_time_seller>=$login_time_stu && $logout_time_seller<=$logout_time_stu){//销售先退出
                        $time_differ = $logout_time_seller-max($login_time_stu,$login_time_seller);
                    }elseif($login_time_seller>=$login_time_stu && $login_time_seller<$logout_time_stu){//学生先退出
                        $time_differ = min($logout_time_stu,$logout_time_seller)-$login_time_seller;
                    }
                    if($time_differ>300){//不同ip,同时在线>5分钟,未测试成功
                        $task->t_seller_student_new->field_update_list($userid,[
                            'test_lesson_opt_flag'=>1,
                        ]);
                    }
                }
            }
        }

        foreach($lessonid_arr as $info){//上麦
            $stu_login = [];
            $stu_logout = [];
            $seller_login = [];
            $seller_logout = [];
            foreach($ret_info as $item){
                $lessonid = $item['lessonid'];
                $role = $item['role'];
                $action = $item['action'];
                $opt_type = $item['opt_type'];

                if($info == $lessonid && $action == E\Eaction::V_2){
                    if($role == E\Erole::V_1 && $opt_type == E\Etest_opt_type_new::V_1){//学生上麦
                        $stu_login = $item;
                    }elseif($role == E\Erole::V_1 && $opt_type == E\Etest_opt_type_new::V_2){//学生下麦
                        $stu_logout = $item;
                    }elseif($role == E\Erole::V_6 && $opt_type == E\Etest_opt_type_new::V_1){//cc上麦
                        $seller_login = $item;
                    }elseif($role == E\Erole::V_6 && $opt_type == E\Etest_opt_type_new::V_2){//cc下麦
                        $seller_logout = $item;
                    }
                }
            }
            if(count($stu_login)>0 && count($stu_logout)>0 && count($seller_login)>0 && count($seller_logout)>0){
                $login_time_stu = $stu_login['opt_time'];
                $logout_time_stu = $stu_logout['opt_time'];
                $server_ip_stu = $stu_login['server_ip'];
                $login_time_seller = $seller_login['opt_time'];
                $logout_time_seller = $seller_logout['opt_time'];
                $server_ip_seller = $seller_logout['server_ip'];
                $test_lesson_opt_flag = $task->t_seller_student_new->field_get_value($userid,'test_lesson_opt_flag');
                if($server_ip_stu != $server_ip_seller && $test_lesson_opt_flag==0){
                    if($logout_time_seller>=$login_time_stu && $logout_time_seller<=$logout_time_stu){//销售先下麦
                        $time_differ = $logout_time_seller-max($login_time_stu,$login_time_seller);
                    }elseif($login_time_seller>=$login_time_stu && $login_time_seller<$logout_time_stu){//学生先上麦
                        $time_differ = min($logout_time_stu,$logout_time_seller)-$login_time_seller;
                    }
                    if($time_differ>300){//不同ip,同时在线>5分钟,未测试成功
                        $task->t_lesson_info->field_update_list($info,[
                            'on_wheat_flag'=>1,
                        ]);
                    }
                }
            }
        }


    }

}