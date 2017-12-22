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
        $day=$this->option('day');
        if ($day===null) {
            $now=time(NULL);
            $start_time=$now-60*15;
            $end_time=$now;
        }else{
            $start_time=strtotime($day);
            $end_time=$start_time+86400;
        }
        echo $day.':'.$start_time.'-'.$end_time;

        // $ret = $this->task->t_seller_student_new->get_all_list($start_time=0,$end_time=0);
        // $userid_arr = array_unique(array_column($ret,'userid'));
        // foreach($userid_arr as $item){
        //     $num = 0;
        //     $userid = $item;
        //     $cc_no_called_count = 0;
        //     foreach($ret as $info){
        //         if($item == $info['userid']){
        //             $is_called_phone = $info['is_called_phone'];
        //             $cc_no_called_count = $info['cc_no_called_count'];
        //             $admin_role = $info['admin_role'];
        //             if($is_called_phone == 1 && $admin_role==E\Eaccount_role::V_2){
        //                 $num = 0;
        //                 break;
        //             }elseif($is_called_phone == 0 && isset($info['is_called_phone']) && $admin_role==E\Eaccount_role::V_2){
        //                 $num += 1;
        //             }
        //         }
        //     }
        //     if($num != $cc_no_called_count){
        //         $this->task->t_seller_student_new->field_update_list($userid,['cc_no_called_count'=>$num]);
        //         echo $userid.':'.$cc_no_called_count."=>".$num."\n";
        //     }
        // }
    }

}
