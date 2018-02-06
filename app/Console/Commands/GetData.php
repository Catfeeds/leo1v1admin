<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetData extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:GetData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '拉数据';

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
        $ret_list = $this->task->t_student_info->get_student_for_stop_study();
        foreach($ret_list as &$val){
            $order_start = \App\Helper\Utils::unixtime2date($val['contract_starttime']);
            $order_end   = \App\Helper\Utils::unixtime2date($val['contract_endtime']);
            if($val['contract_endtime']<time()){
                $val['useful_flag'] = "过期";
            }else{
                $val['useful_flag'] = "未过期";
            }
            echo $val['userid']."|".$val['nick']."|".$val['ass_nick']."|".$val['group_name']."|".$val['useful_flag']
                               ."|".$order_start."|".$order_end;
            echo PHP_EOL;
        }


    }
}
