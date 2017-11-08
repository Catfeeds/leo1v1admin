<?php

namespace App\Console\Commands;

use \App\Enums as E;

class set_xmpp_server extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:set_xmpp_server {--day=}';

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
        $start_time=$this->get_arg_day();
        $end_time = $start_time +86400;

        $check_start_time= time(NULL) +3600*4 ;
        if ( $start_time < $check_start_time  ){
            $start_time = $check_start_time;
        }

        $this->task->t_lesson_info_b3->get_lesson_list($start_time ,$end_time);

    }


}