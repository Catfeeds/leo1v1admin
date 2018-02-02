<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
use \App\Enums as E;

require_once  app_path("/Libs/Qiniu/functions.php");

class update_actual_threshold extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_actual_threshold';

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
        $time = time();
        $start_time = strtotime(date('Y-m-d 09:00:00'));
        $end_time = strtotime(date('Y-m-d'))+3600*24;
        if($time>$start_time && $time<$end_time && date('w')!=2){
            $this->update_actual_threshold();
        }
    }

    public function update_actual_threshold(){
        $time = strtotime(date('Y-m-d'));
        list($start_time,$end_time) = [$time,$time+3600*24];
        $ret_call = $this->task->t_seller_get_new_log->get_list_by_time($start_time, $end_time,$call_flag=1);
        if($ret_call){
            if(time()>$ret_call[0]['create_time']+1800){
                $count_adminid = count(array_unique(array_column($ret_call, 'adminid')));
                $count_call = count(array_unique(array_column($ret_call, 'userid')));
                if($count_adminid>=5 && $count_call>=10){
                    $ret_called = $this->task->t_seller_get_new_log->get_list_by_time($start_time,$end_time,$call_flag=2);
                    $count_called = count(array_unique(array_column($ret_called, 'userid')));
                    $rate = $count_call>0?(round($count_called/$count_call, 4)*100):0;
                    $this->task->t_seller_edit_log->row_insert([
                        'type'=>E\Eseller_edit_log_type::V_6,
                        'new'=>$rate,
                        'create_time'=>time(),
                    ]);
                    $this->send_wx_threshold($rate,$time,$start_time,$end_time,$count_call,$count_call-$count_called);
                }
                if(time()>strtotime(date('Y-m-d 23:25:00')) && time()<strtotime(date('Y-m-d 23:35:00'))){
                    $ret_called = $this->task->t_seller_get_new_log->get_list_by_time($start_time,$end_time,$call_flag=2);
                    $count_called = count(array_unique(array_column($ret_called, 'userid')));
                    $rate = $count_call>0?(round($count_called/$count_call, 4)*100):0;
                    $this->send_wx_daily($rate,$time,$start_time,$end_time,$count_call,$count_call-$count_called);
                }
            }
        }
    }

    public function send_wx_threshold($rate,$time,$start_time,$end_time,$count_call,$count_no_called){
        $threshold = $this->task->t_seller_edit_log->get_threshold($time);
        $threshold_max = $threshold[0]['new'];
        $threshold_min = $threshold[1]['new'];
        $ret_threshold = $this->task->t_seller_edit_log->get_actual_threshold($start_time,$end_time);
        if(count($ret_threshold) == 1){
            if($rate<=$threshold_min){//红色
                $this->update_send_wx_flag($ret_threshold[0]['id'],2,$threshold_max,$threshold_min,$count_call,$count_no_called,$start_time,$end_time);
            }elseif($rate<=$threshold_max && $rate>$threshold_min){//黄色
                $this->update_send_wx_flag($ret_threshold[0]['id'],1,$threshold_max,$threshold_min,$count_call,$count_no_called,$start_time,$end_time);
            }
        }elseif(count($ret_threshold)>1){
            if($ret_threshold[1]['new']>$threshold_max){
                if($ret_threshold[0]['new']<=$threshold_min){//红色
                    $this->update_send_wx_flag($ret_threshold[0]['id'],2,$threshold_max,$threshold_min,$count_call,$count_no_called,$start_time,$end_time);
                }elseif($ret_threshold[0]['new']<=$threshold_max && $ret_threshold[0]['new']>$threshold_min){//黄色
                    $this->update_send_wx_flag($ret_threshold[0]['id'],1,$threshold_max,$threshold_min,$count_call,$count_no_called,$start_time,$end_time);
                }
            }elseif($ret_threshold[1]['new']>$threshold_min && $ret_threshold[1]['new']<=$threshold_max){
                if($ret_threshold[0]['new']<=$threshold_min){//红色
                    $this->update_send_wx_flag($ret_threshold[0]['id'],2,$threshold_max,$threshold_min,$count_call,$count_no_called,$start_time,$end_time);
                }
            }
        }
    }

    public function update_send_wx_flag($id,$flag,$threshold_max,$threshold_min,$count_call,$count_no_called,$start_time,$end_time){
        $this->task->t_seller_edit_log->field_update_list($id, [
            'old'=>$flag,
        ]);

        $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";
        $theme = $flag==1?"新例子电话接通率警报—黄色":"新例子电话接通率警报—红色";
        $color = $flag==1?'黄色':'红色';
        $threshold_desc = $flag==1?"今预警线：":"今警戒线：";
        $threshold = $flag==1?$threshold_max:$threshold_min;
        $desc = "警报时间：".date("Y-m-d H:i:s")."\n"
              ."警报级别：".$color."\n"
              .$threshold_desc.$threshold."%"."\n"
              ."拨打量：".$count_call."\n"
              ."拨不通：".$count_no_called;
        $account_arr = ['tom','应怡莉','alan'];
        foreach($account_arr as $account){
            $this->task->t_manager_info->send_template_msg(
                $account,
                $template_id,
                [
                    "first"    => "",
                    "keyword1" => $theme,
                    "keyword2" => "",
                    "keyword3" => date("Y-m-d H:i:s"),
                    "remark"   => $desc,
                ],
                $url='http://admin.leo1v1.com/tongji_ex/threshold_detail?color='.$color.'&threshold_line='.$threshold.'&count_call='.$count_call.'&count_no_called='.$count_no_called.'&type=1'.'&start_time='.$start_time.'&end_time='.$end_time
            );
        }
    }

    public function send_wx_daily($rate,$time,$start_time,$end_time,$count_call,$count_no_called){
        $threshold = $this->task->t_seller_edit_log->get_threshold($time);
        $threshold_max = $threshold[0]['new'];
        $threshold_min = $threshold[1]['new'];
        $threshold_count = $this->task->t_seller_edit_log->get_threshold_count($start_time,$end_time);
        list($count_y,$count_r) = isset($threshold_count[0]['count_y'])?[$threshold_count[0]['count_y'],$threshold_count[0]['count_r']]:[0,0];

        $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";
        $theme = "新例子电话接通率报告";
        $desc = "今预警线：".$threshold_max."%"."\n"
              ."今警戒线：".$threshold_min."%"."\n"
              ."黄色警报：".$count_y."\n"
              ."红色警报：".$count_r."\n"
              ."总拨通率：".$rate."%"."\n"
              ."总拨打量：".$count_call."\n"
              ."总拨不通：".$count_no_called;
        $account_arr = ['tom','应怡莉','alan'];
        foreach($account_arr as $account){
            $this->task->t_manager_info->send_template_msg(
                $account,
                $template_id,
                [
                    "first"    => "",
                    "keyword1" => $theme,
                    "keyword2" => "",
                    "keyword3" => date("Y-m-d H:i:s"),
                    "remark"   => $desc,
                ],
                $url='http://admin.leo1v1.com/tongji_ex/threshold_detail?threshold_max='.$threshold_max.'&threshold_min='.$threshold_min.'&count_y='.$count_y.'&count_r='.$count_r.'&rate='.$rate.'&count_call='.$count_call.'&count_no_called='.$count_no_called.'&type=2'.'&start_time='.$start_time.'&end_time='.$end_time
            );
        }
    }
}
