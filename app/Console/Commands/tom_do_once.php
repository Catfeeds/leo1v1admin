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
        $ret = $this->task->t_seller_student_new->get_all_list($start_time=1512057600,$end_time=1514736000);
        foreach($ret as $item){
            $userid = $item['userid'];
            $phone = $item['phone'];
            $last_contact_cc = $item['last_contact_cc'];
            if($last_contact_cc==0){
                $last_call = $this->task->t_tq_call_info->get_last_call_by_phone($phone);
                $adminid = isset($last_call['adminid'])?$last_call['adminid']:0;
                if($adminid>0){
                    $this->task->t_seller_student_new->field_update_list($userid,['last_contact_cc'=>$adminid]);
                    echo $userid.':'.$last_contact_cc."=>".$adminid."\n";
                }
            }
        }
    }

}
