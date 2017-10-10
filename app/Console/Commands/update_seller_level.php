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
        list($start_time,$end_time)= $this->get_in_date_range_month(date("Y-m-01"));
        $ret_time = $this->task->t_month_def_type->get_all_list();
        foreach($ret_time as $item){
            if($time>=$item['start_time'] && $start_time<$item['end_time']){
                $start_time = $item['start_time'];
                $end_time = $item['end_time'];
            }
        }
        list($start_time,$end_time)= $this->get_in_date_range_month(date("Y-m-01"));
        foreach($ret_time as $item){
            if($start_time-1>=$item['start_time'] && $start_time-1<$item['end_time']){
                $start_time_last = $item['start_time'];
                $end_time_last = $item['end_time'];
            }
        }

        $this->task->t_month_def_type->get_row_by_month_def_type(E\Emonth_def_type::V_1);
        $account_role = E\Eaccount_role::V_2;
        $seller_list = $this->task->t_manager_info->get_seller_list_new_two($account_role);
        foreach($seller_list as $item){
            $this_level = $item['seller_level'];
            $num = $item['num'];

            //统计本月
            $start_time_this = $start_time;
            $end_time_this = $end_time;

            //统计上个月
            $start_time_last = $start_time-1;
            $end_time_last = $end_time;

            //统计上上个月

            $next_num = $num++;
            $ret_next = $this->task->t_seller_level_goal->get_next_level_by_num($next_num);
            if($ret_next){
                $next_level = $ret_next['seller_level'];
            }
        }
    }
}