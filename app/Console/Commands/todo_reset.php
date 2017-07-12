<?php

namespace App\Console\Commands;

use \App\Enums as E;

class todo_reset extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:todo_reset {--day=}';

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
        //下次回访,任务
        $start_time=$this->get_arg_day();
        if (!$start_time){
            $start_time=strtotime(date("Y-m-d"));
        }

        $end_time= $start_time+86400;
        echo "$start_time ,$end_time \n";
        $page_info=null;
        $ret_info=$this->task->t_todo->get_list($page_info,-1,-1,-1,$start_time,$end_time);
        foreach( $ret_info["list"] as $item) {
            $todoid=$item["todoid"];
            \App\Todo\todo_base::do_reset($todoid);
        }
    }
}