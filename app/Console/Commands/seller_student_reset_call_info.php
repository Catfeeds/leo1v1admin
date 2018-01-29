<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class seller_student_reset_call_info extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "command:seller_student_reset_call_info {--day=}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "重置电话拨打信息";


    /**
     * Execute the console command.
     * @return mixed
     */
    public function do_handle()
    {

        $day=$this->get_arg_day();
        if ($day==0) {
            $start_time=time()-900;
            $end_time=time();
        }else{
            $start_time=$day;
            $end_time=$start_time+86400;

        }

        $user_list=$this->task->t_seller_student_system_assign_log->get_check_call_info_list($start_time, $end_time);
        foreach ($user_list as $item )  {
            $id = $item["id"];
            $phone   = $item["phone"];
            $adminid = $item["adminid"];
            $call_info=$this->task->t_tq_call_info->get_list_by_phone_adminid($phone, $adminid);

            $call_info["called_flag"]=$call_info["called_flag"]?1:0;
            $this->task->t_seller_student_system_assign_log->field_update_list($id,$call_info );
        }

    }

    public function get_call_info($call_list )  {

        /*
        `call_count` int(11) NOT NULL COMMENT '拨打次数',
            `called_flag` int(11) NOT NULL COMMENT '拨通情况',
            `call_time` int(11) NOT NULL COMMENT '拨打时长:拨通情况下是拨通时长',
        */

    }

}
