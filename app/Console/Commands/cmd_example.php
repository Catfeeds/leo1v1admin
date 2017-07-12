<?php

namespace App\Console\Commands;

use \App\Enums as E;

class cmd_example extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cmd_example {--day=}';

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
    public function do_handle()
    {
        $day=$this->option('day');
        if ($day===null) {
            $now=time(NULL);
            $start_time=$now-3600*2;
            $end_time=$now;
        }else{
            $start_time=strtotime($day);
            $end_time=$start_time+86400;
        }
    }

}