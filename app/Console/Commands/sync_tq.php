<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class sync_tq extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sync_tq {--day=}';

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
    public function load_data($start_time, $end_time, $phone="" ) {

        $client     = new \SoapClient("http://webservice.sh.tq.cn/Servers/services/ServerNew?wsdl");

        $adminuin      = 9747409;
        $adminpassword = strtoupper( md5("Aaron1988") );

        //"string getPhoneRecordByClient(string $uin, string $adminuin, string $username, string $adminpassword, string $client_id, string $caller_id, string $called_id, string $startTime, string $endTime, string $is_third)"
        $caller_id=$phone;
        $ret=$client->getPhoneRecordByClient("" ,$adminuin, "" ,$adminpassword, "", $caller_id, "",$start_time, $end_time, "");
        $ret=preg_replace("/gb2312/","utf-8",$ret);

        $ret_list=\App\Helper\Common::xml2array($ret);
        $arr= $ret_list["RECORD"] ;
        if(is_array($arr)){
            array_shift($arr);

            foreach ($arr as $item ) {
                if(isset($item["Start_time"])){
                    if(isset($item["RecordFile"])){
                        if(is_array($item["RecordFile"])){
                            $item["RecordFile"]="";
                        }
                    }

                    $duration=0;
                    if (isset( $item["duration"])) {
                        if(!is_array($item["duration"]) ){
                            $duration_arr=preg_split("/:/", $item["duration"]);
                            $duration=$duration_arr[0]*3600+ $duration_arr[1]*60+ $duration_arr[2];
                        }
                    }

                    if(isset($item["Start_time"])){
                        if(is_array($item["Start_time"]) ){
                            $item["Start_time"]=$item["Insert_time"];
                        }
                    }

                    /*
                      if(is_array($item["End_time"]) ){
                      $item["End_time"]="";
                      }
                    */
                    if(isset($item["Called_id"])){
                        if ( is_array( $item["Called_id"]) ) {
                            continue;
                        }else{

                        }
                    }
                    //UIN

                    $this->task->t_tq_call_info->add(
                        "".$item["PhoneRecId"],
                        "".$item["UIN"],
                        "".$item["Caller_id"],
                        "".$item["Start_time"],
                        "".@$item["End_time"],
                        $duration,
                        "".$item["Is_called_phone"],
                        "".$item["RecordFile"]);

                    $phone=$item["Caller_id"];
                    $call_time =  $item["Start_time"];
                    $tq_called_flag=1;
                    if ( $duration >60 && $item["Is_called_phone"] ) {
                        $tq_called_flag=2;
                    }
                    $this->task->t_seller_student_new->sync_tq($phone ,$tq_called_flag , $call_time );

                }
            }
        }
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
        $start_time = \App\Helper\Utils::unixtime2date($start_time);
        $end_time   = \App\Helper\Utils::unixtime2date($end_time);
        $this->load_data($start_time,$end_time);
    }
}
