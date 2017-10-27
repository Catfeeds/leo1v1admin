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
        $lessonid_arr = array_unique(array_column($ret_info,'lessonid'));
        foreach($roomid_arr as $info){//登录
            $stu_info = [];
            $stu_login = [];
            $seller_info = [];
            $seller_login = [];

            foreach($ret_info as $key=>$item){
                $userid = $item['userid'];
                $roomid = $item['roomid'];
                $role = $item['role'];
                $action = $item['action'];
                $opt_type = $item['opt_type'];
                $test_lesson_opt_flag = $item['test_lesson_opt_flag'];

                if($test_lesson_opt_flag == 1){//测试过
                    continue;
                }
                if($info == $roomid){
                    if($role == E\Erole::V_1 && $action == E\Eaction::V_1){//学生登录退出
                        $stu_info[$key] = $item;
                    }elseif($role == E\Erole::V_6 && $action == E\Eaction::V_1){//cc登录退出
                        $seller_info[$key] = $item;
                    }
                }
            }
            $stu_info = array_values($stu_info);
            $seller_info = array_values($seller_info);
            foreach($stu_info as $key=>$item){
                if($item['opt_type'] == E\Etest_opt_type::V_1){//学生登录
                    $stu_login[$key]['login'] = $item;
                    if($stu_info[$key+1]['opt_type'] == E\Etest_opt_type::V_2){//下一条为学生退出
                        $stu_login[$key]['logout'] = $stu_info[$key+1];
                    }else{
                        $stu_login[$key]['logout'] = [];
                    }
                }
            }
            foreach($seller_info as $key=>$item){
                if($item['opt_type'] == E\Etest_opt_type::V_1){//cc登录
                    $seller_login[$key]['login'] = $item;
                    if($seller_info[$key+1]['opt_type'] == E\Etest_opt_type::V_2){//下一条为cc退出
                        $seller_login[$key]['logout'] = $seller_info[$key+1];
                    }else{
                        $seller_login[$key]['logout'] = [];
                    }
                }
            }
            foreach($stu_login as $item){
                $login_s = $item['login'];
                $logout_s = $item['logout'];
                $login_time_stu = count($login_s)>0?$login_s['opt_time']:'';
                $logout_time_stu = count($logout_s)>0?$logout_s['opt_time']:'';
                $server_ip_stu = $login_s['server_ip'];

                foreach($seller_login as $item_c){
                    $login_c = $item_c['login'];
                    $logout_c = $item_c['logout'];
                    $login_time_seller = count($login_c)>0?$login_c['opt_time']:'';
                    $logout_time_seller = count($logout_c)>0?$logout_c['opt_time']:'';
                    $server_ip_seller = $login_c['server_ip'];
                    if($server_ip_stu != $server_ip_seller){
                        $time_differ = 0;
                        if($logout_time_stu == ''){//学生无退出
                            $time_differ = $logout_time_seller-$login_time_stu;
                        }elseif($logout_time_seller == ''){//销售无退出
                            $time_differ = 0;
                        }else{
                            if($logout_time_seller>=$login_time_stu && $logout_time_seller<=$logout_time_stu){//销售先退出
                                $time_differ = $logout_time_seller-max($login_time_stu,$login_time_seller);
                            }elseif($login_time_seller>=$login_time_stu && $login_time_seller<$logout_time_stu){//学生先退出
                                $time_differ = min($logout_time_stu,$logout_time_seller)-$login_time_seller;
                            }
                        }
                        if($time_differ>300){//不同ip,同时在线>5分钟
                            $task->t_seller_student_new->field_update_list($userid,[
                                'test_lesson_opt_flag'=>1,
                            ]);
                        }
                    }
                }
            }
        }

        foreach($lessonid_arr as $info){//上麦
            $stu_info = [];
            $stu_wheat = [];
            $seller_info = [];
            $seller_wheat = [];

            foreach($ret_info as $key=>$item){
                $lessonid = $item['lessonid'];
                $role = $item['role'];
                $action = $item['action'];
                $opt_type = $item['opt_type'];
                $on_wheat_flag = $item['on_wheat_flag'];
                if($on_wheat_flag == 1){
                    continue;
                }

                if($info == $lessonid){
                    if($role == E\Erole::V_1 && $action == E\Eaction::V_1){//学生上下麦
                        $stu_info[$key] = $item;
                    }elseif($role == E\Erole::V_6 && $action == E\Eaction::V_2){//cc上下麦
                        $seller_info[$key] = $item;
                    }
                }
            }
            $stu_info = array_values($stu_info);
            $seller_info = array_values($seller_info);
            foreach($stu_info as $key=>$item){
                if($item['opt_type'] == E\Etest_opt_type::V_1){//学生上麦
                    $stu_wheat[$key]['login'] = $item;
                    if($stu_info[$key+1]['opt_type'] == E\Etest_opt_type::V_2){//下一条为学生下麦
                        $stu_wheat[$key]['logout'] = $stu_info[$key+1];
                    }else{
                        $stu_wheat[$key]['logout'] = [];
                    }
                }
            }
            foreach($seller_info as $key=>$item){
                if($item['opt_type'] == E\Etest_opt_type_new::V_1){//cc上麦
                    $seller_wheat[$key]['login'] = $item;
                    if($seller_info[$key+1]['opt_type'] == E\Etest_opt_type_new::V_2){//下一条为cc下麦
                        $seller_wheat[$key]['logout'] = $seller_info[$key+1];
                    }else{
                        $seller_wheat[$key]['logout'] = [];
                    }
                }
            }
            foreach($stu_wheat as $item){
                $login_s = $item['login'];
                $logout_s = $item['logout'];
                $login_time_stu = $login_s['opt_time'];
                $logout_time_stu = count($logout_s)>0?$logout_s['opt_time']:'';
                $server_ip_stu = $login_s['server_ip'];
                foreach($seller_wheat as $item_c){
                    $login_c = $item['login'];
                    $logout_c = $item['logout'];
                    $login_time_seller = $login_c['opt_time'];
                    $logout_time_seller = $logout_c['opt_time'];
                    $server_ip_seller = $login_c['server_ip'];

                    if($server_ip_stu != $server_ip_seller){
                        $time_differ = 0;
                        if($logout_time_stu == ''){//学生无下麦信息
                            $time_differ = $logout_time_seller-$login_time_stu;
                        }else{
                            if($logout_time_seller>=$login_time_stu && $logout_time_seller<=$logout_time_stu){//销售先下麦
                                $time_differ = $logout_time_seller-max($login_time_stu,$login_time_seller);
                            }elseif($login_time_seller>=$login_time_stu && $login_time_seller<$logout_time_stu){//学生先上麦
                                $time_differ = min($logout_time_stu,$logout_time_seller)-$login_time_seller;
                            }
                        }
                        if($time_differ>300){//不同ip,同时上麦>5分钟
                            $task->t_lesson_info->field_update_list($info,[
                                'on_wheat_flag'=>1,
                            ]);
                        }
                    }
                }
            }
        }
    }

}