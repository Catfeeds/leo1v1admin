<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class seller_student_system_free extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "command:seller_student_system_free";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "系统释放例子";


    /**
     * Execute the console command.
     * @return mixed
     */
    public function do_handle()
    {
        //14:30  发现没拨打
        $check_time=strtotime("Y-m-d 14:30");
        $now= time(NULL);
        $work_start_time_map=$this->task->t_admin_work_start_time-> get_today_work_start_time_map();
        $check_for_free_user_list=$this->task->t_admin_work_start_time-> get_today_work_start_time_map();
        if ($now>=$check_time ) {

        }
    }

}
