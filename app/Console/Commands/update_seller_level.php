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
        list($start_time,$end_time)= $this->get_in_date_range_month(date("Y-m-01"));
        $this->task->t_month_def_type->get_row_by_month_def_type(E\Emonth_def_type::V_1);
        $account_role = E\Eaccount_role::V_2;
        $seller_list = $this->task->t_manager_info->get_seller_list_new_two($account_role);
        foreach($seller_list as $item){
            $this_level = $item['seller_level'];
            $next_level = E\Eseller_level::V_1000;
        }
    }
}