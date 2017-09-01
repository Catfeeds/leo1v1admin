<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums  as E;
class office_close extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:office_close {--without-seller} {--reset} {--check-require-time}';

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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function do_handle()
    {
        $without_seller=$this->option('without-seller');
        $reset=$this->option('reset');
        $check_require_time=$this->option('check-require-time');
        if ($check_require_time) {
            $last_require_time= \App\Helper\office_cmd::get_last_require_time();
            if ($last_require_time < time(NULL)-2*60 ) {//
                $last_require_time_str=\App\Helper\Utils::unixtime2date($last_require_time);
                $this->task->t_manager_info->send_wx_todo_msg("jim","sys","空调遥控-树莓派,不工作,最后一次上报时间 $last_require_time_str");
            }
        } else if ($reset ) {


            $value=24;
            $device_sub_type =0;
            $device_id_list=[3,2];
            $office_device_type=E\Eoffice_device_type::V_1;
            foreach ( $device_id_list as $device_id ) {
                echo "reset $device_id \n";
                $device_opt_type=E\Edevice_opt_type::V_2;
                \App\Helper\office_cmd::add_one($office_device_type,$device_id,$device_opt_type,$device_sub_type ,$value);
                $device_opt_type=E\Edevice_opt_type::V_0;
                \App\Helper\office_cmd::add_one($office_device_type,$device_id,$device_opt_type,$device_sub_type ,$value);
            }

        }else{
            $id_without_list=[];
            if ( $without_seller) {
                $id_without_list=[ 9,10,11,12 ];
            }


            $value=24;
            $device_sub_type =0;
            $office_device_type=E\Eoffice_device_type::V_1;
            $device_opt_type=E\Edevice_opt_type::V_0;
            for($i=1;$i<16;$i++) {
                $device_id=$i;
                if ( !in_array( $device_id, $id_without_list ) ) {
                    echo  "off $device_id \n";
                    \App\Helper\office_cmd::add_one($office_device_type,$device_id,$device_opt_type,$device_sub_type ,$value);
                }
            }
        }

    }
}
