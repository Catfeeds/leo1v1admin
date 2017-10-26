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
        $task = new \App\Console\Tasks\TaskController();
        $ret_info = $task->t_test_lesson_opt_log->get_room_list();
        $roomid_arr = array_unique(array_column($ret_info,'roomid'));
        foreach($roomid_arr as $info){
            $ret_stu_login = [];
            $ret_stu_logout = [];
            $ret_seller_login = [];
            $ret_seller_logout = [];
            $item = $ret_info[$info];
            $roomid = $item['roomid'];
            $role = $item['role'];
            $opt_type = $item['opt_type'];

            if($role == E\Erole::V_1 && $opt_type == E\Etest_opt_type::V_1){//学生登录
                $ret_stu_login = $item;
            }elseif($role == E\Erole::V_1 && $opt_type == E\Etest_opt_type::V_2){//学生退出
                $ret_stu_logout = $item;
            }elseif($role == E\Erole::V_6 && $opt_type == E\Etest_opt_type::V_2){//cc登录
                $ret_seller_login = $item;
            }elseif($role == E\Erole::V_6 && $opt_type == E\Etest_opt_type::V_2){//cc退出
                $ret_seller_logout = $item;
            }

            $job=(new \App\Jobs\lesson_check($ret))->delay(60);
            dispatch($job);
        }
    }

}