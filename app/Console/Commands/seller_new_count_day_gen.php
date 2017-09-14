<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class seller_new_count_day_gen extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:seller_new_count_day_gen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每天为每个人生成可抢例子数据';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function do_handle()
    {
        $config=\App\Helper\Config::get_seller_new_user_day_count();
        $admin_list=$this->task->t_manager_info->get_seller_list();
        $unallot_info=$this->task->t_test_lesson_subject->get_unallot_info();
        $all_uncall_count=$unallot_info["all_uncall_count" ] ;
        $add_count_ex=0;
        if ( $all_uncall_count>1000)  {
            $add_count_ex=4;
        }else if ( $all_uncall_count>500)  {
            $add_count_ex=2;
        }else if ( $all_uncall_count>200)  {
            $add_count_ex=1;
        }

        $seller_new_count_type=E\Eseller_new_count_type::V_DAY ;
        $value_ex=0;
        foreach($admin_list as $item ) {
            $adminid=$item["uid"];
            $seller_level=$item["seller_level"];
            $count=@$config[$seller_level];
            if ($count) {
                $count+= $add_count_ex;
            }

            $start_time=strtotime(date("Y-m-d"), time(NULL));
            $end_time=$start_time+86400-1;

            


            $existed_flag=$this->task->t_seller_new_count->check_adminid_seller_new_count_type_start_time (  $adminid, $seller_new_count_type, $start_time  );
            if (!$existed_flag) {
                $this->task->t_seller_new_count->add(
                    $start_time,$end_time, $seller_new_count_type,$count  ,$adminid,$value_ex);
            }
        }
    }
}
