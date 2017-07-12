<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use \App\Enums as E;

class notify_regiter_user extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:notify_regiter_user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每分钟通知注册用户';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       
        $task=new \App\Console\Tasks\TaskController();
        //
        //得到当前要通知的用户
        $now        = time();
        $start_time = $now-$now%60-60;
        $end_time   = $start_time+60;
        //$start_time- = $start_time -3600*8;
        $list=$task->t_seller_student_info->get_need_noti_list($start_time,$end_time);


        foreach ($list as $item ) {
            $phone = $item["phone"];
            $arr   = explode("-", $phone );
            $origin= $item["origin"];
            $subject= $item["subject"];
            $has_pad= $item["has_pad"];
            $grade= $item["grade"];

                $msg= "手机:$phone<br/>"
                        ."渠道:$origin<br/>"
                        ."年级:".E\Egrade::get_desc($grade) ."<br/>"
                        ."科目:".E\Esubject::get_desc($subject) ."<br/>"
                        ."pad:".E\Epad_type::get_desc($has_pad) ."<br/>"
                        ."";
                $task->t_seller_student_new->book_free_lesson_new("",$phone,$grade,$origin,$subject,$has_pad);
                //echo "SEND_EMAIL:$msg";
                /*
                dispatch( new \App\Jobs\SendEmail( "329732001@qq.com",
                                                   $origin."-". $phone ,
                                                   $msg ));
                */
                /*
                dispatch( new \App\Jobs\SendEmail( "857016974@qq.com",
                                                   $origin."-". $phone ,
                                                   $msg ));
                */

        }
    }
}
