<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
use \App\Enums as E;

require_once  app_path("/Libs/Qiniu/functions.php");

class update_seller_add_time extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_seller_add_time';

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
        $seller_add_time = strtotime(date('Y-m-d'));
        $ret = [];
        $limit = ceil(2000/$count);
        for($i=1;$i<=$count+1;$i++){
            $start_time = $start;
            $end_time = strtotime('+1 month',$start);
            $ret = $this->task->t_test_lesson_subject->get_all_list($start_time,$end_time,$limit);
            foreach($ret as $item){
                $userid = $item['userid'];
                $this->task->t_seller_student_new->field_update_list($userid,[
                    'seller_add_time'=>$seller_add_time,
                ]);
                // echo $userid.':'.$item['seller_add_time']."=>".$seller_add_time."\n";
            }
            $start = strtotime('+1 month',$start);
        }
    }

}
