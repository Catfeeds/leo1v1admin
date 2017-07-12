<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class sync_tianrun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sync_tianrun {--day=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    /**
     * @var  \App\Console\Tasks\TaskController
     */
    public $task;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->task= new \App\Console\Tasks\TaskController();
    }



    public function load_data($start_time, $end_time ) {

        $url="http://api.clink.cn/interfaceAction/cdrObInterface!listCdrOb.action";


        $post_arr=[
            "enterpriseId" => 3005046,
            "userName" => "admin" ,
            "pwd" =>md5(md5("tianrun123456" )."seed1")  ,
            "seed" => "seed1",
            "startTime" => date("Y-m-d H:i:s", $start_time),
            "endTime" => date("Y-m-d H:i:s", $end_time),
        ];

        $limit_count =500;
        $index_start=0;
        do {
            $post_arr["start"]  = $index_start;
            $post_arr["limit"]  = $limit_count;
            $return_content= \App\Helper\Net::send_post_data($url, $post_arr );
            $ret=json_decode($return_content, true  );
            print_r($ret);
            $index_start+=$limit_count;
        }while ( count($ret["msg" ]["data"]) == $limit_count );



    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
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

        $this->load_data($start_time,$end_time);
        //
    }
}
