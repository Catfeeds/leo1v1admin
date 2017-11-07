<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class reset_online_count_xmpp_log extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:reset_online_count_xmpp';

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
    public function handle()
    {
        $task=new \App\Console\Tasks\TaskController();

        $xmpp_id_list=$task->t_xmpp_server_config->xmpp_id(); //获取所有xmpp_id
        $start_time=strtotime(date('Y-m-d'));
        $end_time= $start_time+86400;
        for($tmp=$start_time; $tmp<$end_time;$tmp+=300 ) {
            $def_time_list[$tmp]=0;
        }
        $xmpp_id_list[]=[  "server_name" =>"", "id"=>0];

        foreach($xmpp_id_list as $val){
            $date_time_list=$task->t_lesson_info_b3->get_lesson_time_xmpp_list($val['server_name'],$start_time,$end_time);
            $tmp_list=$def_time_list;
            $xmpp_id =$val['id'];
            $time_list=\App\Helper\Utils::get_online_line_timestramp($tmp_list, $date_time_list );

            foreach ($time_list as $logtime => $online_count ) {
                $task->t_online_count_xmpp_log->row_insert([
                    "xmpp_id" => $xmpp_id ,
                    "logtime" => $logtime,
                    "online_count" =>  $online_count,
                ],true);
            }
        }

    }
}
