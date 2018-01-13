<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
use \App\Enums as E;

require_once  app_path("/Libs/Qiniu/functions.php");

class tom_do_once extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:tom_do_once';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    /**
     * task
     *
     * @var \App\Console\Tasks\TaskController
     */

    var $task       ;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->task        = new \App\Console\Tasks\TaskController();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /*
        $ret = $this->task->t_seller_student_new->get_all_list($start_time=1514736000,$end_time=1515513600);
        foreach($ret as $item){
            $userid = $item['userid'];
            $phone = $item['phone'];
            $last_contact_cc = $item['last_contact_cc'];
            $this->task->t_lesson_info_b3->get_test_succ_count($start_time, $end_time);
            if($last_contact_cc==0){
                $last_call = $this->task->t_tq_call_info->get_last_call_by_phone($phone);
                $adminid = isset($last_call['adminid'])?$last_call['adminid']:0;
                if($adminid>0){
                    $this->task->t_seller_student_new->field_update_list($userid,['last_contact_cc'=>$adminid]);
                    echo $userid.':'.$last_contact_cc."=>".$adminid."\n";
                }
            }
        }
        */

        // $month=$this->option('month');
        $month = '2018-01-13';
        if ($month) {
            $start_time=strtotime( date("Y-m-01", strtotime( $month)) );
            $end_time= strtotime("+1 month",  $start_time );
            echo "do $start_time, $end_time \n";

        }else{
            $now=time(NULL);
            $start_time=strtotime( date("Y-m-01",$now));
            $end_time=$now;
        }
		$group_start_time=$start_time;
		if( $start_time  ==  strtotime("2017-10-01")  ) {
			$start_time  = strtotime("2017-10-03");
		}
		if( $start_time  ==  strtotime("2017-09-01")  ) {
			$end_time = strtotime("2017-10-03");
		}

        $this->task->t_order_info->switch_tongji_database();
        $this->task->t_test_lesson_subject_require->switch_tongji_database();

        //
        $tongji_type= E\Etongji_type::V_SELLER_MONTH_REQUIRE_COUNT;
        $list= $this->task->t_test_lesson_subject_require->tongji_require_count($start_time,$end_time);

        //
        $tongji_type= E\Etongji_type::V_SELLER_MONTH_SUCC_TEST_LESSON_COUNT;
        $test_lesson_succ_list=$this->task->t_test_lesson_subject_require->tongji_test_lesson_succ_count($start_time,$end_time);

        //
        $tongji_type= E\Etongji_type::V_SELLER_MONTH_ORDER_COUNT;
        $order_count_list= $this->task->t_order_info->tongji_seller_order_count($start_time,$end_time);

        //
        $tongji_type= E\Etongji_type::V_SELLER_MONTH_ORDER_PERCENT;

        $order_count_map=[];
        foreach ($order_count_list as $item) {
            $order_count_map[$item["adminid"]]=$item["value"];
        }


        $order_percent_list=[];
        foreach( $test_lesson_succ_list as $item ) {
            $adminid=$item["adminid"];
            if ($item["value"]  >=10 ) {
                $order_percent_list[]=[
                    "adminid" =>$adminid,
                    "value" => ((@$order_count_map[$adminid])/$item["value"]) *100,
                ];
            }
        }
        \App\Helper\Utils::order_list($order_percent_list,"value",false);
        $tongji_type= E\Etongji_type::V_SELLER_MONTH_ORDER_MONEY;
        $order_money_list=[];
        foreach($order_count_list as $item) {
            $adminid=$item["adminid"];
            $order_money_list[]=[
                "adminid" =>$adminid,
                "value" => $item["money"] ,
            ];
        }
        \App\Helper\Utils::order_list($order_money_list,"value",false);
        /*$tongji_type= E\Etongji_type::V_SELLER_MONTH_ASSIGN_COUNT;
        $assign_count_list = $this->task->t_seller_student_new->tongji_assign_count_list($start_time,$end_time);
        $this->task->t_tongji_seller_top_info->update_list($tongji_type,$group_start_time,$assign_count_list);
        $tongji_type= E\Etongji_type::V_SELLER_MONTH_LESSON_PLAN;
        $lesson_count_list = $this->task->t_test_lesson_subject_sub_list->tongji_lesson_count_list($start_time,$end_time);
        $this->task->t_tongji_seller_top_info->update_list($tongji_type,$group_start_time,$lesson_count_list);*/
        $tongji_type= E\Etongji_type::V_SELLER_MONTH_ORDER_PERSON_COUNT;
        $order_person_count_list= $this->task->t_order_info->tongji_seller_order_person_count($start_time,$end_time);

        $tongji_type= E\Etongji_type::V_SELLER_MONTH_FAIL_LESSON_PERCENT;
        $test_lesson_list=$this->task->t_test_lesson_subject_require->tongji_test_lesson_group_by_admin_revisiterid($start_time,$end_time );
        $test_lesson_fail_per = $test_lesson_list["list"];
        $test_lesson_all_count= [] ;
        $test_lesson_fail_count= [] ;
        foreach($test_lesson_fail_per as &$item){
            $adminid=$item["admin_revisiterid"];
            $item["adminid"] = $adminid ;
            if($item['test_lesson_count'] != 0){
                $item['value'] = round($item['fail_all_count']/$item['test_lesson_count'],2)*100;
            }else{
                $item['value']=0;
            }
            if($adminid == 1298){
                echo $item['test_lesson_count'].'/'.$item['fail_all_count'];
            }
            // $test_lesson_all_count[]= [ "adminid" =>$adminid , "value"=> $item['test_lesson_count']  ] ;
            // $test_lesson_fail_count[]= [ "adminid" =>$adminid , "value"=> $item['fail_all_count']  ] ;
        }


        // $this->update_cc_no_called_count();
    }

    /**
     * @name tom
     * @abstract [cc_called_count,cc_no_called_count,cc_no_called_count_new,first_called_cc,last_contact_cc]
     */
    public function update_cc_no_called_count(){
        $min   = $this->task->t_seller_student_new->get_min_add_time();
        $max   = $this->task->t_seller_student_new->get_max_add_time();
        $date1 = explode('-',date('y-m-d',$min));
        $date2 = explode('-',date('y-m-d',$max));
        $count = abs($date1[0] - $date2[0]) * 12 + abs($date1[1] - $date2[1]);
        $start = strtotime(date('y-m-1',$min));
        $end   = strtotime(date('y-m-1',$max));
        for($i=1;$i<=$count+1;$i++){
            $start_time = $start;
            $end_time = strtotime('+1 month',$start);
            $ret = $this->task->t_seller_student_new->get_all_list($start_time,$end_time);
            foreach($ret as $item){
                $arr = [];
                $userid = $item['userid'];
                $phone = $item['phone'];
                $cc_called_count = $item['cc_called_count'];
                $cc_no_called_count = $item['cc_no_called_count'];
                $cc_no_called_count_new = $item['cc_no_called_count_new'];
                $cc_first_called_cc = $item['first_called_cc'];
                $cc_first_revisit_time = $item['first_revisit_time'];
                $cc_last_contact_time = $item['last_contact_time'];
                $cc_last_called_cc = $item['last_contact_cc'];
                $cc_first_get_cc = $item['first_get_cc'];
                $cc_test_lesson_flag = $item['test_lesson_flag'];
                $cc_orderid = $item['orderid'];

                $called_count = $this->task->t_tq_call_info->get_called_count($phone,1);
                $no_called_count = $this->task->t_tq_call_info->get_called_count($phone,0);
                $first_revisit_time = $this->task->t_tq_call_info->get_first_revisit_time($phone);
                $last_contact_time = $this->task->t_tq_call_info->get_first_revisit_time($phone,$desc='desc');
                $first_called_cc = $this->task->t_tq_call_info->get_first_called_cc($phone);
                $last_called_cc = $this->task->t_tq_call_info->get_first_called_cc($phone,$desc='desc');
                $first_get_cc = $this->task->t_tq_call_info->get_first_get_cc($phone,$desc='asc');
                $first_test_lessonid = $this->task->t_lesson_info_b2->get_first_test_lesson($userid);
                $orderid = $this->task->t_order_info->get_last_orderid_by_userid($userid);

                if($cc_called_count != $called_count){
                    $arr['cc_called_count'] = $called_count;
                }
                if($cc_no_called_count_new != $no_called_count){
                    $arr['cc_no_called_count_new'] = $no_called_count;
                }
                if($cc_no_called_count==0 && $called_count==0 && $no_called_count>0){
                    $arr['cc_no_called_count'] = $no_called_count;
                }
                if($cc_no_called_count>0 && $called_count>0){
                    $arr['cc_no_called_count'] = 0;
                }
                if($cc_first_called_cc == 0){
                    $arr['first_called_cc'] = $first_called_cc;
                }
                if($cc_last_called_cc == 0){
                    $arr['last_contact_cc'] = $last_called_cc;
                }
                if($first_get_cc>0){
                    $arr['first_get_cc'] = $first_get_cc;
                }
                if($cc_test_lesson_flag == 0 && $first_test_lessonid>0){
                    $arr['test_lesson_flag'] = $first_test_lessonid;
                }
                if($cc_orderid == 0 && $orderid>0){
                    $arr['orderid'] = $orderid;
                }
                $arr['first_revisit_time'] = $cc_first_revisit_time;
                $arr['last_contact_time'] = $cc_last_contact_time;
                if(count($arr)>0){
                    if(isset($arr['first_get_cc'])){
                        echo $userid.':'.$cc_first_get_cc."=>".$first_get_cc."\n";
                    }
                    if(isset($arr['test_lesson_flag'])){
                        echo $userid.':'.$cc_test_lesson_flag."=>".$first_test_lessonid."\n";
                    }
                    if(isset($arr['orderid'])){
                        echo $userid.':'.$cc_orderid."=>".$orderid."\n";
                    }
                    $ret = $this->task->t_seller_student_new->field_update_list($userid,$arr);
                }
            }
            $start = strtotime('+1 month',$start);
        }
    }

}
