<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class sync_tianrun extends cmd_base
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





    public function load_data($start_time, $end_time ) {

        $url="http://api.clink.cn/interfaceAction/cdrObInterface!listCdrOb.action";

        $this->task->t_manager_info-> get_tquin_uid_map();

        $post_arr=[
            "enterpriseId" => 3005131  ,
            "userName" => "admin" ,
            "pwd" =>md5(md5("leoAa123456" )."seed1")  ,
            "seed" => "seed1",
            "startTime" => date("Y-m-d H:i:s", $start_time),
            "endTime" => date("Y-m-d H:i:s", $end_time),
        ];

        $index_start=0;
        $limit_count =500;
        do {
            $post_arr["start"]  = $index_start;
            $post_arr["limit"]  = $limit_count;
            $return_content= \App\Helper\Net::send_post_data($url, $post_arr );
            $ret=json_decode($return_content, true  );
            $data_list= @$ret["msg"]["data"];
            if (!is_array($data_list)) { break ;}

            foreach ($data_list as $item) {
                $this->do_record($item);
            }
            $index_start+=$limit_count;

        }while ( count($ret["msg" ]["data"]) == $limit_count );
    }

    public function do_record ($item) {
        /*
          [uniqueId] => 10.10.61.69-1502416848.11782
          [customerNumber] => 15601830297
          [customerProvince] => 上海
          [customerCity] => 上海
          [numberTrunk] => 02151368906
          [queueName] =>
          [cno] => 2001
          [clientNumber] => 02145947224
          [status] => 双方接听
          [startTime] => 2017-08-11 10:00:48
          [bridgeTime] => 2017-08-11 10:01:07
          [bridgeDuration] => 00:00:05
          [cost] => 0.000
          [totalDuration] => 00:00:24
          [recordFile] =>
          [inCaseLib] => 不在
          [score] => 0
          [callType] => 点击外呼
          [comment] => 无
          [taskName] =>
          [endReason] => 否
          [userField] =>
          [sipCause] => 200
        */
        //$this->task->t_tq_call_info->
        $cdr_bridged_cno= $item["cno"];
        $uniqueId= $item["uniqueId"];
        $cdr_answer_time = intval( preg_split("/\-/", $uniqueId)[1]);
        $id= ($cdr_bridged_cno<<32 ) + $cdr_answer_time;
        $record_url=  $item["recordFile"];
        $userField = $item["userField"];
        $sipCause = $item['sipCause'];
        $client_number = $item['clientNumber'];
        $endReason = 0;
        if($item['endReason']=='是'){
            $endReason = 2;
        }elseif($item['endReason']=='否'){
            $endReason = 1;
        }
        $db_item=$this->task->t_tq_call_info->field_get_list($id, "id, record_url,cause,client_number,end_reason")  ;
        if ($db_item) { //更新
            $arr = [];
            if($db_item["record_url"] != $record_url){
                $arr['record_url'] = $record_url;
            }
            if($db_item["cause"] != $sipCause){
                $arr['cause'] = $sipCause;
            }
            if($db_item["client_number"] != $client_number){
                $arr['client_number'] = $client_number;
            }
            if($db_item["end_reason"] != $endReason){
                $arr['end_reason'] = $endReason;
            }
            if(count($arr)>0){
                $this->task->t_tq_call_info->field_update_list($id,$arr);
            }
        }else{
            // $bridgeDuration= $item["bridgeDuration"];//通话时长
            // $duration= strtotime("1970-01-01 $bridgeDuration")+28800;//3600*8
            // $cdr_customer_number= $item["customerNumber"];//客户号码
            // $called_flag=( $duration>30)?2:1;
            // $cdr_end_time =strtotime( $item["bridgeTime"] )+  $duration ;//客户接听时间
            // $this->task->t_tq_call_info->add(
            //     $id,
            //     $cdr_bridged_cno,
            //     $cdr_customer_number,
            //     $cdr_answer_time,
            //     $cdr_end_time,
            //     $duration,
            //     $called_flag
            //     ,
            //     "" );
            // $this->task->t_seller_student_new->sync_tq($cdr_customer_number ,$called_flag, $cdr_answer_time, $cdr_bridged_cno );

            $cdr_customer_number= $item["customerNumber"];
            $is_called_flag = ($item['status']=='双方接听')?1:0;
            $obj_start_time = strtotime($item['bridgeTime'])?strtotime($item['bridgeTime']):0;
            $bridgeDuration = $item["bridgeDuration"];
            $duration = strtotime("1970-01-01 $bridgeDuration")+28800;
            $totalDuration = $item['totalDuration'];
            $totalDuration = strtotime("1970-01-01 $totalDuration")+28800;
            $cdr_end_time = $cdr_answer_time + $totalDuration;
            $this->task->t_tq_call_info->add(
                $id,
                $cdr_bridged_cno,
                $cdr_customer_number,
                $cdr_answer_time,
                $cdr_end_time,
                $duration,
                $is_called_flag,
                $record_url,
                0,
                0,
                $obj_start_time,
                $sipCause,
                $client_number,
                $endReason
            );
            $called_flag = ($duration>60)?2:1;
            $this->task->t_seller_student_new->sync_tq($cdr_customer_number ,$called_flag, $cdr_answer_time, $cdr_bridged_cno, $is_called_flag);
        }

    }

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
            $start_time=$now-60*15;
            $end_time=$now;
        }else{
            $start_time=strtotime($day);
            $end_time=$start_time+86400;
        }
        $this->load_data($start_time,$end_time);
    }
}
