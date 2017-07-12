<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ytx_sync_wav extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ytx_sync_wav {--day=}';

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



    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $audio_file_dir="/home/data2/work/audio";

        $day=$this->option('day');
        if ($day===null) {
            $now=time(NULL);
            $start_time=$now-3600*2;
            $end_time=$now;
        }else{
            $start_time=strtotime($day);
            $end_time=$start_time+86400;
        }
        $list=$this->task->t_tq_call_info->get_list($start_time,$end_time);
        foreach ($list as $item) {
            $url=$item["record_url"];
            $url_arr=preg_split("/\//",  $url );
            /*
            Array
                (
                    [0] => http:
                    [1] =>
                    [2] => saas.yxjcloud.com
                    [3] => CallRecord
                    [4] => 13180760227_51136974_aae1c3ea-a029-43eb-aa64-84c89209e927.wav
                )
            */
            if ( (count($url_arr)>=5) && ($url_arr[2] == "saas.yxjcloud.com" || $url_arr[2] == "121.196.236.95:8080" )  ) { 
                //下载
                $file_name= $url_arr[4];
                $obj_file_name="$audio_file_dir/$file_name";
                if (@filesize($obj_file_name)<1000) {
                    \App\Helper\Utils::logger("DO $url");
                    echo "Do: $url\n";
                    \App\Helper\Common::httpcopy($url, $obj_file_name,60*5 );
                }

            }


        }

        //
    }
}
