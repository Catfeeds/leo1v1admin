<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums  as E;
class check_office_require_time extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check_office_require_time ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    public function check_kaoqin() {
        $machine_id=11;
        $opt_time=$this->task->t_kaoqin_machine->get_last_post_time($machine_id);
        if ( $opt_time< time(NULL)-120   ) {//
            $time_str=\App\Helper\Utils::unixtime2date($opt_time);
            $this->task->t_manager_info->send_wx_todo_msg("jim","sys","后面考勤 最后一次上报时间 $time_str");
        }

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function do_handle()
    {
        $this->check_kaoqin();

        $last_require_time= \App\Helper\office_cmd::get_last_require_time();
        if ($last_require_time < time(NULL)-120   ) {//
            $last_require_time_str=\App\Helper\Utils::unixtime2date($last_require_time);
            $this->task->t_manager_info->send_wx_todo_msg("jim","sys","空调遥控-树莓派,不工作,最后一次上报时间 $last_require_time_str");
        }
    }
}
