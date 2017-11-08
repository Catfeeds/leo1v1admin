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
        $lesson_list=$this->task->t_lesson_info_b3-> get_lesson_list_for_set_xmpp_server($start_time ,$end_time);
        $ret_info=$this->task->t_xmpp_server_config->get_list(null);
        $server_map=[];
        foreach ( $ret_info["list"] as $item ) {
            $weights=$item["weights"]/100;
            for ($i=0;$i<$weights; $i++ ) {
                $server_map[ ]= $item["server_name"];
            }
        }

        $server_map_count=count($server_map);
        foreach ( $lesson_list as  $item ) {
            $server_name= $server_map[rand()%$server_map_count];
            $this->task->t_lesson_info->field_update_list($item["lessonid"],[
                "xmpp_server_name" => $server_name
            ]);
        }

    }


}