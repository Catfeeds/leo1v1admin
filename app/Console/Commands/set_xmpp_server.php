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


    }


}