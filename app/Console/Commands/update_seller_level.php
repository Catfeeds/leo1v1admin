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
        foreach($ret_time as $item){//本月
            if($time>=$item['start_time'] && $start_time<$item['end_time']){
                $start_time_this = $item['start_time'];
                $end_time_this = $item['end_time'];
            }
        }
        // foreach($ret_time as $item){//上月
        //     if($start_time_this-1>=$item['start_time'] && $start_time_this-1<$item['end_time']){
        //         $start_time_last = $item['start_time'];
        //         $end_time_last = $item['end_time'];
        //     }
        // }
        // foreach($ret_time as $item){//上上月
        //     if($start_time_last-1>=$item['start_time'] && $start_time_last-1<$item['end_time']){
        //         $start_time_very_last = $item['start_time'];
        //         $end_time_very_last = $item['end_time'];
        //     }
        // }

        $account_role = E\Eaccount_role::V_2;
        $seller_list = $this->task->t_manager_info->get_seller_list_new_two($account_role);
        foreach($seller_list as $item){
            $num = $item['num'];
            $adminid = $item['uid'];
            $level_goal = $item['level_goal'];
            $this_level = $item['seller_level'];
            $become_member_time = $item['create_time'];
            $next_num = $num++;
            $ret_next = $this->task->t_seller_level_goal->get_next_level_by_num($next_num);
            if($ret_next){
                $next_level = $ret_next['seller_level'];
            }
            //统计本月
            $price = $this->task->t_order_info->get_seller_price($start_time_this,$end_time_this,$adminid);
            $price = $price/100;
            if($price>$level_goal){
                $this->task->t_manager_info->field_update_list($adminid,['seller_level'=>$next_level]);
            }

            //统计上个月

            //统计上上个月

        }
    }
}