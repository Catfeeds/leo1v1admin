<?php

namespace App\Console\Commands;

use \App\Enums as E;

class ytx_sync extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ytx_sync {--day=}';

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

    public function load_data($ytx_account, $start_date, $end_date,$caller= -1  , $check_phone=""  ) {

        $ytx_phone_map = $this->task->t_manager_info->get_ytx_account_map($caller);
        //\App\Helper\Utils::logger("ytx_phone_map:count:".count($ytx_phone_map)  );

        $url="http://121.196.236.95:8080/Verification/TogoalGetCdr";
        $post_arr=[
            "account"=> $ytx_account, //公司账户
            "startD"=> $start_date , //呼叫时间开始区间
            "endD" => $end_date, //呼叫时间结束区间
            "callUid" => "" //呼叫标识 GUID
        ];
        list($return_code, $return_content)= \App\Helper\Net::http_post_data($url,json_encode( $post_arr ));
        //echo "ret:" .$return_code . "\n";
        $data_arr=\App\Helper\Utils::json_decode_as_array($return_content);
        //print_r( $return_content );
        //print_r($data_arr );
        //echo  "count:". count($data_arr["data"] )."\n";
        /*
        [Lid] => 649
              [CallerE164] => 02151136889
              [CalleeE164] => 15602830297
              [CallState] => 1
              [CallTime] => 24
              [CallUrl] => /CallRecord/15602830297_02151136889_65a47ea3-c4af-40e3-865d-3a17a754927f.
              wav
              [StartD] => /Date(1487062798000)/
              [EndD] => /Date(1487062823000)/
              [CallUid] => 65a47ea3-c4af-40e3-865d-3a17a754927f
        */

        $record_count=count( $data_arr["data"] );
        if ($record_count==0) {
            return 0;
            //echo $return_content;
        }

        foreach  ($data_arr["data"] as $i=> $item) {

            $phone=$item["CalleeE164"];

            $rec_start_time=$this->get_time_from_fmt($item["StartD"] );
            $rec_end_time=$this->get_time_from_fmt($item["EndD"] );


            $call_state=$item["CallState"];
            if ($ytx_account=="liyou") {
                $this->task->t_seller_student_new->sync_tq($phone , $call_state+1, $rec_start_time );
            }
            $url_base="http://121.196.236.95:8080";

            \App\Helper\Utils::logger( "check  phone:". $item["CallerE164"] .":". $caller);


            if (
                ( $caller == -1  || $item["CallerE164"] == $caller )
                && ( $check_phone == ""  || $item["CalleeE164"] == $check_phone )
            ) {

                \App\Helper\Utils::logger(" YTX: $ytx_account: PHONE:$phone:". $item["StartD"] .":". $item["CallTime"]);
                $phone= $item["CalleeE164"];

                $cur_caller= $item["CallerE164"]*1;
                $revisit_time =$rec_start_time;
                $id= $item["Lid"] + 1000000000000000 ;
                $this->task->t_tq_call_info->add(
                    $id,
                    $cur_caller ,
                    $phone,
                    $rec_start_time,
                    $rec_end_time,
                    $item["CallTime"]*1,
                    $call_state,
                    $url_base .$item["CallUrl"]);


                if ( $item["CallTime"]>0 &&  $ytx_account =="liyou2"  ) {

                    $sys_operator= @$ytx_phone_map[ $cur_caller ]["account"] ;
                    //\App\Helper\Utils::logger("sys :$cur_caller: $sys_operator  " );

                    if ($sys_operator) {
                        $userid=$this->task->t_phone_to_user->get_userid_by_phone($phone,E\Erole::V_STUDENT );
                        if ( $userid ) {

                            $this->task->t_revisit_info->add_revisit_record($userid, $revisit_time,"" ,"" , $sys_operator, "电话录音",6,  $id);
                            //$this->task->t_student_info->set_ass_revisit_last_week_time($userid, $revisit_time);
                        }
                    }
                }

            }

        }

        return $record_count;

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
            $start_time=$now-3600*2;
            $end_time=$now;
        }else{
            $start_time=strtotime($day);
            $end_time=$start_time+86400;
        }

        $start_date = \App\Helper\Utils::unixtime2date($start_time,"Y-m-d H:i:s");
        $end_date  = \App\Helper\Utils::unixtime2date($end_time ,"Y-m-d H:i:s");
        echo "$start_date, $end_date\n";
        $this->load_data("liyou",$start_date,$end_date);
        $count=$this->load_data("liyou2",$start_date,$end_date);
        echo "liyou2 :$count";
    }
    public function  get_time_from_fmt( $str ) {
        preg_match("/\\/Date\\((.*)\\)\\//", $str,$matches);
        return intval($matches[1]/1000);
    }



}