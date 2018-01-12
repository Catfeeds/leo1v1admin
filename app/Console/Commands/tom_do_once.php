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
        $this->update_cc_no_called_count();
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
