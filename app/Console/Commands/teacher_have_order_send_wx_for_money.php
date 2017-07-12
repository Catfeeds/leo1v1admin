<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;
use App\Helper\Utils;

class teacher_have_order_send_wx_for_money extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:teacher_have_order_send_wx_for_money';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '教研老师面试的老师试听转化后发送微信通知奖励情况';

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
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task       = new \App\Console\Tasks\TaskController();
        $end_time   = time();
        $start_time = time()-30*86400;

        $time = time()-20*86400;
        $list = $task->t_course_order->get_teacher_order_turn_info($time);
        //dd($list);
        foreach($list as &$item){
            $teacherid = $item["interview_teacherid"];
            $item["add_time_str"] = date("Y-m-d H:i:s",$item["add_time"]);
            $order_per_info  =$task->t_lesson_info->get_teacher_month_order_info($start_time,$end_time,$item["teacherid"]);
            $order_per = !empty($order_per_info["success_lesson"])?round($order_per_info["order_number"]/$order_per_info["success_lesson"],4)*100:0;
            $account_info  = $task->t_manager_info->get_account_role_by_teacherid($teacherid);
            $reward=0;
            $lessonid = $item["ass_from_test_lesson_id"];
            $first_reward=0;
            if($lessonid>0){
                $lesson_start = $task->t_lesson_info->get_lesson_start($lessonid);
                $ret = $task->t_lesson_info->check_is_first($lessonid,$item["teacherid"]);
                if($ret != 1){
                    $first_reward=50; 
                }
            }            
            if($account_info["account_role"]==4 || $account_info["account_role"]==5){
                if($order_per>=35){
                    $reward=20;
                }elseif($order_per>=25){
                    $reward=10;
                }elseif($order_per>=15){
                    $reward=5;
                }
            }else{
                if($order_per>=35){
                    $reward=10;
                }elseif($order_per>=25){
                    $reward=5;
                }elseif($order_per>=15){
                    $reward=2.5;
                }
            }
           
            if($reward>0 || $first_reward>0){
                $reward_money = $reward*100;
                $first_reward_money = $first_reward*100;
                /**
                 * 模板ID   : QYcUGRqWzGKAD7W1sCtvUYBw3cyr9gL5kx5wh8r2jj8
                 * 标题课程 : 签约成功通知
                 * {{first.DATA}}
                 * 客户姓名：{{keyword1.DATA}}
                 * 签约时间：{{keyword2.DATA}}
                 *{{remark.DATA}}
                 */
                $data=[];
                $template_id      = "QYcUGRqWzGKAD7W1sCtvUYBw3cyr9gL5kx5wh8r2jj8";
                if($reward>0 && $first_reward>0){
                    $data['first']    = $item["interview_nick"].",你负责面试的老师".$item["realname"]."成功签单,该老师本月试听转化率为".$order_per."%,你获得".$reward."元的奖励;本单为该老师首签,您获得首签奖".$first_reward."元,请继续加油!";

                }elseif($reward>0 && $first_reward==0){
                   $data['first']    = $item["interview_nick"].",你负责面试的老师".$item["realname"]."成功签单,该老师本月试听转化率为".$order_per."%,你获得".$reward."元的奖励,请继续加油!"; 
                }else{
                     $data['first']    = $item["interview_nick"].",你负责面试的老师".$item["realname"]."成功签单,本单为该老师首签,您获得首签奖".$first_reward."元,请继续加油!";
                }
                $data['keyword1'] = $item["nick"];
                $data['keyword2'] = $item["add_time_str"];
                $data['remark']   = "25%的试听转化率就靠你了,Come on";
                $openid = $task->t_teacher_info->get_wx_openid($teacherid);
                $openid2 = "oJ_4fxGZQHlRENGlUeA7Tn1nSeII";
                if(isset($openid) && isset($template_id)){
                    \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data);
                    \App\Helper\Utils::send_teacher_msg_for_wx($openid2,$template_id,$data);
                
                    $task->t_course_order->field_update_list($item["courseid"],[
                        "send_wx_flag"     =>1
                    ]);
                    $task->t_research_teacher_rerward_list->row_insert([
                        "courseid"   =>$item["courseid"],
                        "adminid"    =>$item["uid"],
                        "add_time"   =>$item["add_time"],
                        "teacherid"  =>$item["teacherid"],
                        "reward"     =>$reward_money,
                        "first_reward"=>$first_reward_money
                    ]);
                }

            }else{
                $task->t_course_order->field_update_list($item["courseid"],[
                    "send_wx_flag"     =>2
                ]);

            }
           
        }
        

    }
}
