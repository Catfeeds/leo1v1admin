<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;
use App\Helper\Utils;

class tongji_teacher_week_order_info_wx extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:tongji_teacher_week_order_info_wx';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '一周教研老师面试的老师试听转化所获奖励汇总';

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
        $time = time()-7*86400;
        // $list = $task->t_course_order->tongji_week_teacher_order_turn_info($time);
        $list = $task->t_research_teacher_rerward_list->tongji_week_teacher_order_turn_info($time,time());
        $all_money=0;
        $str="";
        foreach($list as $item){
            $reward_count = $item["reward_count"]/100;
            $all_money += $reward_count;
            $str .= $item["realname"]."老师签约奖".$reward_count."元,";
        }
        $str=rtrim($str,",");
        
        $task->t_manager_info->send_wx_todo_msg_by_adminid (72,"理优面试组","一周签约奖汇总","Erick老师你好,本周签约奖共".$all_money."元,其中".$str,"");
        $task->t_manager_info->send_wx_todo_msg_by_adminid (448,"理优面试组","一周签约奖汇总","rolon老师你好,本周签约奖共".$all_money."元,其中".$str,"");

        

    }
}
