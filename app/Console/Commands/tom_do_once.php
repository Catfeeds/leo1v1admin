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
        $min   = $this->task->t_seller_student_new->get_min_add_time();
        $max   = $this->task->t_seller_student_new->get_max_add_time();
        $date1 = explode('-',date('Y-m-d',$min));
        $date2 = explode('-',date('Y-m-d',$max));
        $count = abs($date1[0] - $date2[0]) * 12 + abs($date1[1] - $date2[1]);
        $start = strtotime(date('Y-m-1',$min));
        $end   = strtotime(date('Y-m-1',$max));
        $ret = [];
        $userid_arr = [];
        for($i=1;$i<=$count+1;$i++){
            $start_time = $start;
            $end_time = strtotime('+1 month',$start);
            $ret = $this->task->t_seller_student_new->get_all_list($start_time,$end_time);
            $userid_arr = array_unique(array_column($ret,'userid'));
            foreach($userid_arr as $item){
                $num = 0;
                $userid = $item;
                $cc_no_called_count = 0;
                foreach($ret as $info){
                    if($item == $info['userid']){
                        $is_called_phone = $info['is_called_phone'];
                        $cc_no_called_count = $info['cc_no_called_count'];
                        if($is_called_phone == 1){
                            $num = 0;
                            break;
                        }elseif($is_called_phone == 0){
                            $num += 1;
                        }
                    }
                }
                if($num != $cc_no_called_count){
                    $this->task->t_seller_student_new->field_update_list($userid,['cc_no_called_count'=>$num]);
                    echo $userid.':'.$cc_no_called_count."=>".$num."\n";
                }
            }
        }
    }

}
