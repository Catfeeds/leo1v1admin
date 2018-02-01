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

        // $this->update_cc_call();
        $this->update_tq_call_info();
        // $this->give_seller_new_count();
        // $this->update_seller_edit_log();
        // $this->update_seller_student_origin();
        // $this->seller_daily_threshold();
        // $this->update_actual_threshold();
        // $this->update_seller_student_origin_new();
    }

    public function update_cc_call(){
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
            // $this->update_cc_no_called_count($start_time,$end_time);
            // $this->update_distribution_count($start_time,$end_time);

            $start = strtotime('+1 month',$start);
        }
    }

    /**
     * @name tom
     * @abstract [cc_called_count,cc_no_called_count,cc_no_called_count_new,first_called_cc,last_contact_cc]
     */
    public function update_cc_no_called_count($start_time,$end_time){
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
            $cc_last_revisit_time = $item['last_revisit_time'];
            $cc_first_contact_time = $item['first_contact_time'];
            $cc_last_contact_time = $item['last_contact_time'];
            $cc_last_called_cc = $item['last_contact_cc'];
            $cc_first_get_cc = $item['first_get_cc'];
            $cc_test_lesson_flag = $item['test_lesson_flag'];
            $cc_orderid = $item['orderid'];

            $called_count = $this->task->t_tq_call_info->get_called_count($phone,1);
            $no_called_count = $this->task->t_tq_call_info->get_called_count($phone,0);

            $first_called_cc = $this->task->t_tq_call_info->get_first_called_cc($phone);
            $first_revisit_time = $this->task->t_tq_call_info->get_first_revisit_time($phone);
            $last_revisit_time = $this->task->t_tq_call_info->get_first_revisit_time($phone,$desc='desc');
            $first_contact_time = $this->task->t_tq_call_info->get_first_revisit_time($phone,$desc='asc',$called_flag=1);
            $last_contact_time = $this->task->t_tq_call_info->get_first_revisit_time($phone,$desc='desc',$called_flag=1);
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
            $arr['first_revisit_time'] = $first_revisit_time;
            $arr['last_revisit_time'] = $last_revisit_time;
            $arr['first_contact_time'] = $first_contact_time;
            $arr['last_contact_time'] = $last_contact_time;
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
    }

    public function update_tq_call_info(){
        $time_arr = [
            [
                'start_time'=>strtotime('2017-09-30'),
                'end_time'=>strtotime('2017-10-31')
            ],[
                'start_time'=>strtotime('2017-08-31'),
                'end_time'=>strtotime('2017-09-30')
            ],[
                'start_time'=>strtotime('2017-07-31'),
                'end_time'=>strtotime('2017-08-31')
            ],[
                'start_time'=>strtotime('2017-06-30'),
                'end_time'=>strtotime('2017-07-31')
            ],[
                'start_time'=>strtotime('2017-05-31'),
                'end_time'=>strtotime('2017-06-30')
            ],[
                'start_time'=>strtotime('2017-04-30'),
                'end_time'=>strtotime('2017-05-31')
            ],[
                'start_time'=>strtotime('2017-03-31'),
                'end_time'=>strtotime('2017-04-30')
            ],[
                'start_time'=>strtotime('2017-02-28'),
                'end_time'=>strtotime('2017-03-31')
            ],[
                'start_time'=>strtotime('2017-01-31'),
                'end_time'=>strtotime('2017-02-28')
            ],[
                'start_time'=>strtotime('2016-12-31'),
                'end_time'=>strtotime('2017-01-31')
            ],[
                'start_time'=>strtotime('2016-11-30'),
                'end_time'=>strtotime('2016-12-31')
            ],[
                'start_time'=>strtotime('2016-10-31'),
                'end_time'=>strtotime('2016-11-30')
            ],[
                'start_time'=>strtotime('2016-09-30'),
                'end_time'=>strtotime('2016-10-31')
            ],[
                'start_time'=>strtotime('2016-08-31'),
                'end_time'=>strtotime('2016-09-30')
            ],[
                'start_time'=>strtotime('2016-07-31'),
                'end_time'=>strtotime('2016-08-31')
            ],[
                'start_time'=>strtotime('2016-06-30'),
                'end_time'=>strtotime('2016-07-31')
            ],[
                'start_time'=>strtotime('2016-05-31'),
                'end_time'=>strtotime('2016-06-30')
            ],[
                'start_time'=>strtotime('2016-04-30'),
                'end_time'=>strtotime('2016-05-31')
            ]
        ];
        foreach($time_arr as $item){
            $start_time = $item['start_time'];
            $end_time = $item['end_time'];
            $count = ($end_time-$start_time)/(3600*24);
            for ($i=1; $i<=$count; $i++)
            {
                $start_time = $start_time+3600*24;
                for ($j=8; $j<=24; $j++)
                {
                    $start_time_new = $start_time+3600*$j;
                    $end_time_new = $start_time_new+3600;

                    $url="http://api.clink.cn/interfaceAction/cdrObInterface!listCdrOb.action";
                    $post_arr=[
                        "enterpriseId" => 3005131  ,
                        "userName" => "admin" ,
                        "pwd" =>md5(md5("leoAa123456" )."seed1")  ,
                        "seed" => "seed1",
                        "startTime" => date('Y-m-d H:i:s',$start_time_new),
                        "endTime" => date('Y-m-d H:i:s',$end_time_new),
                    ];
                    $post_arr["start"]  = 0;
                    $post_arr["limit"]  = 1000;
                    $return_content= \App\Helper\Net::send_post_data($url, $post_arr );
                    $ret=json_decode($return_content, true  );
                    $data_list = @$ret["msg"]["data"];
                    if(is_array($data_list)){
                        foreach($data_list as $item){
                            $cdr_bridged_cno= $item["cno"];
                            $uniqueId= $item["uniqueId"];
                            $cdr_answer_time = intval( preg_split("/\-/", $uniqueId)[1]);
                            $id= ($cdr_bridged_cno<<32 ) + $cdr_answer_time;
                            $sipCause = $item['sipCause'];
                            $client_number = $item['clientNumber'];
                            $endReason = 0;
                            if($item['endReason']=='是'){//客户挂
                                $endReason = 2;
                            }elseif($item['endReason']=='否'){//销售挂
                                $endReason = 1;
                            }
                            $ret = $this->task->t_tq_call_info->field_get_list($id, '*');
                            $arr = [];
                            if($ret['cause'] != $sipCause){
                                $arr['cause'] = $sipCause;
                            }
                            if($ret['client_number'] != $client_number){
                                $arr['client_number'] = $client_number;
                            }
                            if($ret['end_reason'] != $endReason){
                                $arr['end_reason'] = $endReason;
                            }
                            if(count($arr)>0){
                                $ret = $this->task->t_tq_call_info->field_update_list($id, $arr);
                                echo $id.':'.$ret."\n";
                            }
                        }
                    }
                }
            }
        }
    }

    public function give_seller_new_count(){
        $start_time = strtotime(date('Y-m-d'));
        $end_time = strtotime(date('Y-m-d',strtotime('+1 day')))-1;
        $seller_list = $this->task->t_manager_info->get_item_seller_list();
        foreach($seller_list as $item){
            $adminid = $item['uid'];
            $account = $item['account'];
            $new_count_id = $this->task->t_seller_new_count->get_item_day_row($adminid,$start_time,$end_time);
            if($new_count_id == 0){
                $this->task->t_seller_new_count->add($start_time,$end_time,E\Eseller_new_count_type::V_1,$count=5,$adminid,$value_ex=0);
            }
            $ret = 1;
            echo $account.':'.$new_count_id.'=>'.$ret."\n";
        }
    }

    public function update_seller_edit_log(){
        $ret = $this->task->t_seller_edit_log->get_item_list();
        foreach($ret as $item){
            $id = $item['id'];
            $adminid = $item['uid'];
            $phone = $item['phone'];
            $start_time = $item['create_time'];
            $end_time = time();
            $first_revisit_time = $this->task->t_tq_call_info->get_item_row($adminid,$phone,$call_flag=-1,$start_time,$end_time);
            $first_contact_time = $this->task->t_tq_call_info->get_item_row($adminid,$phone,$call_flag=1,$start_time,$end_time);
            $arr = [];
            if($first_revisit_time != $item['first_revisit_time']){
                $arr['first_revisit_time'] = $first_revisit_time;
            }
            if($first_contact_time != $item['first_contact_time']){
                $arr['first_contact_time'] = $first_contact_time;
            }
            if(count($arr)>0){
                $this->task->t_seller_edit_log->field_update_list($id, $arr);
                echo $id.':'.$first_revisit_time.'=>'.$first_contact_time."\n";
            }
        }
    }

    public function update_distribution_count($start_time,$end_time){
        $ret = $this->task->t_seller_student_new->get_all_list($start_time, $end_time);
        foreach($ret as $item){
            $distribution_count = $this->task->t_seller_edit_log->get_item_count($item['userid']);
            if($item['distribution_count'] != $distribution_count){
                $this->task->t_seller_student_new->field_update_list($item['userid'], ['distribution_count'=>$distribution_count]);
                echo $item['userid'].':'.$item['distribution_count'].'=>'.$distribution_count."\n";
            }
        }
    }

    public function update_seller_student_origin(){
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
            $ret = $this->task->t_seller_student_origin->get_all_list($start_time,$end_time);
            foreach($ret as $item){
                $arr = [];
                $userid = $item['userid'];
                $origin = $item['origin'];
                $add_time = $item['add_time'];
                $is_exist_count = $this->task->t_seller_student_origin->get_item_count($userid,$min,$add_time);
                if($is_exist_count>0){
                    $this->task->t_seller_student_origin->field_update_list_2($userid, $origin, ['is_exist_count'=>$is_exist_count]);
                    echo $userid.':'.$origin.'=>'.$is_exist_count."\n";
                }
            }
            $start = strtotime('+1 month',$start);
        }
    }

    public function update_seller_student_origin_new(){
        $min   = $this->task->t_seller_student_origin->get_min_add_time($desc='asc');
        $max   = $this->task->t_seller_student_origin->get_min_add_time($desc='desc');
        $date1 = explode('-',date('y-m-d',$min));
        $date2 = explode('-',date('y-m-d',$max));
        $count = abs($date1[0] - $date2[0]) * 12 + abs($date1[1] - $date2[1]);
        $start = strtotime(date('y-m-1',$min));
        $end   = strtotime(date('y-m-1',$max));
        for($i=1;$i<=$count+1;$i++){
            $start_time = $start;
            $end_time = strtotime('+1 month',$start);
            $ret = $this->task->t_seller_student_origin->get_all_list($start_time,$end_time);
            foreach($ret as $item){
                $arr = [];
                $userid = $item['userid'];
                $origin = $item['origin'];
                $add_time = $item['add_time'];
                $last_suc_lessonid = $item['last_suc_lessonid'];
                $last_orderid = $item['last_orderid'];
                $next_add_time = $this->task->t_seller_student_origin->get_next_add_time($userid,$add_time);
                $add_time_max = $next_add_time>0?$next_add_time:time();
                $lessonid = $this->task->t_lesson_info_b3->get_last_test_lessonid($userid,$add_time,$add_time_max);
                if($lessonid>0 && $last_suc_lessonid!=$lessonid){
                    $arr['last_suc_lessonid'] = $lessonid;
                }
                $orderid = $this->task->t_order_info->get_last_orderid($userid,$add_time,$add_time_max);
                if($orderid>0 && $last_orderid!=$orderid){
                    $arr['last_orderid'] = $orderid;
                }
                if(count($arr)>0){
                    $ret = $this->task->t_seller_student_origin->field_update_list_2($userid, $origin, $arr);
                    echo $userid.':'.$origin.'=>'.$ret."\n";
                }
            }
            $start = strtotime('+1 month',$start);
        }
    }

    public function seller_daily_threshold(){
        // $ret = $this->task->t_seller_get_new_log->get_list_by_time($start_time=1516204800,$end_time=1516982400);
        // $ret_info = [];
        // foreach($ret as $item){
        //     $ret_info[$item['adminid']][$item['userid']][] = $item;
        // }
        // foreach($ret_info as $item){
        //     foreach($item as $info){
        //         foreach($info as $key=>$info_k){
        //             if($key>0){
        //                 $ret = $this->task->t_seller_get_new_log->row_delete($info_k['id']);
        //                 echo $info_k['id'].':'.$key.'=>'.$ret."\n";
        //             }
        //         }
        //     }
        // }
        list($start_time,$end_time,$time,$ret,$ret_info) = [0,0,strtotime(date('Y-m-d')),[],[]];
        $ret_threshold = $this->task->t_seller_edit_log->get_threshold($time);
        if(!$ret_threshold && date('w')!=2){
            for($i=1;$i<=12;$i++){
                $start_time = $time-3600*24*$i;
                $end_time = $start_time+3600*24;
                if(date('w',$start_time) != 2){
                    $ret_info[$i]['start_time'] = $start_time;
                    $ret_info[$i]['end_time'] = $end_time;
                    if(count($ret_info)==10){
                        break;
                    }
                }
            }
            foreach($ret_info as $item){
                $start_time = $item['start_time'];
                $end_time = $item['end_time'];
                $ret_call = $this->task->t_seller_get_new_log->get_list_by_time($start_time,$end_time,$call_flag=1);
                $count_call = count(array_unique(array_column($ret_call, 'userid')));
                $ret_called = $this->task->t_seller_get_new_log->get_list_by_time($start_time,$end_time,$call_flag=2);
                $count_called = count(array_unique(array_column($ret_called, 'userid')));
                $ret[$start_time]['call_count'] = $count_call;
                $ret[$start_time]['called_count'] = $count_called;
                $ret[$start_time]['rate'] = $count_call>0?(round($count_called/$count_call, 4)*100):0;
            }
            $rate_arr = array_column($ret, 'rate');
            $rate_avg = round(array_sum($rate_arr)/count($rate_arr),4);
            foreach($ret as $start_time=>$item){
                $ret[$start_time]['dif_square'] = round(pow($item['rate']-$rate_avg,2),2);
            }
            $pow_sqrt = round(sqrt(array_sum(array_column($ret, 'dif_square'))/(count($ret)-1)),2);

            $count_call_all = array_sum(array_column($ret, 'call_count'));
            $count_called_all = array_sum(array_column($ret, 'called_count'));
            $threshold_max = $count_call_all>0?(round($count_called_all/$count_call_all,4)*100):0;
            $threshold_min = $threshold_max-$pow_sqrt;
            $this->task->t_seller_edit_log->row_insert([
                'type'=>E\Eseller_edit_log_type::V_4,
                'new'=>$threshold_max,
                'create_time'=>$time,
            ]);
            $this->task->t_seller_edit_log->row_insert([
                'type'=>E\Eseller_edit_log_type::V_5,
                'new'=>$threshold_min,
                'create_time'=>$time,
            ]);
            echo date('Y-m-d',$time).'=>'.$threshold_min.'~'.$threshold_max;
        }
    }


}
