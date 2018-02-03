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

        $tquin_map=$this->task->t_manager_info-> get_tquin_uid_map();

        //"string getPhoneRecordByClient(string $uin, string $adminuin, string $username, string $adminpassword, string $client_id, string $caller_id, string $called_id, string $startTime, string $endTime, string $is_third)"
        $caller_id=$phone;
        $ret=$client->getPhoneRecordByClient("" ,$adminuin, "" ,$adminpassword, "", $caller_id, "",$start_time, $end_time, "");
        $ret=preg_replace("/gb2312/","utf-8",$ret);

        $ret_list=\App\Helper\Common::xml2array($ret);
        $arr= $ret_list["RECORD"] ;

        if(is_array($arr)){
            array_shift($arr);

            /*
              {"PhoneRecId":"33676335","UIN":"9747576","NickName":[],"Admin_uin":"9747409","Insert_time":"1507547011","Caller_id":"18762207248","Called_id":"6031","Call_style":"3","Call_type":[],"Deal_state":"0","Resume":[],"Visitor_entry":[],"Visitor_last_page":[],"Visitor_comes":[],"Client_uin":[],"Client_id":[],"Rand":[],"Is_called_phone":"1","Call_type_code":[],"Serialno":"0","Caller_ip":[],"Serial_wiseid":[],"RecordFile":"http:\/\/mdb.tq.cn\/mj\/filelist.do?type=voiceRecords&uid=7c65601e-ace1-11e7-a052-93c1e15497be&callagent_time=&token=","Start_time":"1507547011","End_time":"1507547036","Queuename":[],"Third_phone_id":[],"Media_id":[],"Area_id":"8625516","Area_name":"\u6c5f\u82cf\u7701\u5f90\u5dde\u5e02\u4e2d\u56fd\u79fb\u52a8","duration":"00:00:07","Satisfaction_degree":[],"Seatid":"6105","Pathway":"1","Dnis":[],"Caller_queue_time":[],"Caller_stime":"1507547029","Hangup_side":[],"Ring_duration":"00:00:18","Phone_create_time":"1507547011","Phone_hangup_time":"1507547036","FsuniqueId":"7c65601e-ace1-11e7-a052-93c1e15497be","Insert_db_time":"1507547041"}
            */

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

                    $obj_start_time=0;
                    if (isset ( $item["Caller_stime"])) {
                        if(!is_array($item["Caller_stime"]) ){
                            $obj_start_time=$item["Caller_stime"];
                        }
                    }
                    /*
                      if(is_array($item["End_time"]) ){
                      $item["End_time"]="";
                      }
                    */
                    if(isset($item["Called_id"])){
                        if ( is_array( $item["Called_id"]) ) {
                           // continue;
                        }else{

                        }
                    }
                    //UIN

                    $tquin=  $item["UIN"];
                    $this->task->t_tq_call_info->add(
                        "".$item["PhoneRecId"],
                        "".$tquin,
                        "".$item["Caller_id"],
                        "".$item["Start_time"],
                        "".@$item["End_time"],
                        $duration,
                        "".$item["Is_called_phone"],
                        "".$item["RecordFile"],0,0, $obj_start_time);

                    $phone=$item["Caller_id"];
                    $call_time =  $item["Start_time"];
                    $tq_called_flag=1;
                    $is_called_phone= $item["Is_called_phone"]?1:0;
                    if ($duration >60 && $item["Is_called_phone"] ) {
                        $tq_called_flag=2;
                    }
                    $admin_info = @$tquin_map[$tquin];
                    $adminid=0;
                    $admin_role=0;
                    if ($admin_info) {
                        $adminid    = $admin_info ["uid"];
                        $admin_role = $admin_info ["account_role"];
                    }

                    $this->task->t_seller_student_new->sync_tq($phone,$tq_called_flag,$call_time,$tquin,$is_called_phone,$duration);
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
