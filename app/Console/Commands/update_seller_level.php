<?php
namespace App\Console\Commands;
use \App\Enums as E;
class update_seller_level extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_seller_level';

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
        $time = time(null);
        $ret_time = $this->task->t_month_def_type->get_all_list();
        $firstday = date("Y-m-01");
        $lastday = date("Y-m-d",strtotime("$firstday +1 month -1 day"));
        list($start_time_this,$end_time_this)= [strtotime($firstday),strtotime($lastday)];
        foreach($ret_time as $item){//本月
            if($time>=$item['start_time'] && $time<$item['end_time']){
                $start_time_this = $item['start_time'];
                $end_time_this = $item['end_time'];
            }
        }
        $timestamp = strtotime(date("Y-m-01"));
        $firstday_last  = date('Y-m-01',strtotime(date('Y',$timestamp).'-'.(date('m',$timestamp)-1).'-01'));
        $lastday_last   = date('Y-m-d',strtotime("$firstday_last +1 month -1 day"));
        list($start_time_last,$end_time_last)= [strtotime($firstday_last),strtotime($lastday_last)];
        foreach($ret_time as $item){//上月
            if($start_time_this-1>=$item['start_time'] && $start_time_this-1<$item['end_time']){
                $start_time_last = $item['start_time'];
                $end_time_last = $item['end_time'];
            }
        }
        $timestamp_very_last=strtotime(date("Y-m-01"));
        $firstday_very_last=date('Y-m-01',strtotime(date('Y',$timestamp_very_last).'-'.(date('m',$timestamp_very_last)-2).'-01'));
        $lastday_very_last=date('Y-m-d',strtotime("$firstday_very_last +1 month -1 day"));
        list($start_time_very_last,$end_time_very_last)= [strtotime($firstday_very_last),strtotime($lastday_very_last)];
        foreach($ret_time as $item){//上上月
            if($start_time_last-1>=$item['start_time'] && $start_time_last-1<$item['end_time']){
                $start_time_very_last = $item['start_time'];
                $end_time_very_last = $item['end_time'];
            }
        }
        $account_role = E\Eaccount_role::V_2;
        $seller_list = $this->task->t_manager_info->get_seller_list_new_two($account_role);
        $ret_level_goal = $this->task->t_seller_level_goal->get_all_list_new();
        foreach($seller_list as $item){
            $adminid = $item['uid'];
            $this_level = $item['seller_level'];
            $become_member_time = $item['create_time'];
            $ret_this = $this->task->t_seller_level_goal->field_get_list($item['seller_level'],'*');
            $num = isset($ret_this['num'])?$ret_this['num']:0;
            $level_goal = isset($ret_this['level_goal'])?$ret_this['level_goal']:0;
            $next_goal = $level_goal;
            $next_num = $num + 1;
            $ret_next = $this->task->t_seller_level_goal->get_next_level_by_num($next_num);
            if($ret_next){
                $next_goal = $ret_next['level_goal'];
            }
            //统计本月
            $price = $this->task->t_order_info->get_seller_price($start_time_this,$end_time_this,$adminid);
            $price = $price/100;
            if($price>$next_goal){
                echo $adminid.':'."\n";
                foreach($ret_level_goal as $item){
                    if($price >= $item['level_goal']){
                        $next_level = $item['level_goal'];
                    }
                }
                // $this->task->t_manager_info->field_update_list($adminid,['seller_level'=>$next_level]);
            }

            //统计上个月

            //统计上上个月

        }
    }
}