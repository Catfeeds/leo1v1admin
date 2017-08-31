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
    protected $signature = 'command:office_close';

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
        $office_device_type=E\Eoffice_device_type::V_1;
        $value=24;
        $device_sub_type =0;
        $device_opt_type=E\Edevice_opt_type::V_0;
        for($i=3;$i<4;$i++) {
            $device_id=$i;
            \App\Helper\office_cmd::add_one($office_device_type,$device_id,$device_opt_type,$device_sub_type ,$value);
        }
    }
}
