<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
use \App\Enums as E;

require_once  app_path("/Libs/Qiniu/functions.php");

class update_cc_no_called_count extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_cc_no_called_count';

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
        $this->seller_daily_threshold();
    }

    public function seller_daily_threshold(){
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
        }
    }

}
