<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ListenJob extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ListenJob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '监控Job进程,如果超过2分钟Job数量都保持在20以上,则进行报警';

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
        $job_count = $this->task->t_jobs->get_all_count();
        $job_redis_key = "ListenJobTimestamp";
        $pre_time = \App\Helper\Common::redis_get($job_redis_key);

        if($job_count>=20){
            if($pre_time>0){
                $diff_time = time()-$pre_time;
                if($diff_time>120){
                    //发送报警
                    $admin_list = ["jim","jack","adrian", "tom","james", "boby", "sam","abner","ricky","顾培根"];
                    $title      = "job异常!";
                    $content    = "job进程持续2分钟大于20个";
                    foreach($admin_list as $account){
                        $this->task->t_manager_info->send_wx_todo_msg($account,"",$title,$content,"","");
                    }
                }
            }else{
                \App\Helper\Common::redis_set($job_redis_key, time());
            }
        }elseif($pre_time!=null){
            \App\Helper\Common::redis_del($job_redis_key);
        }
    }

}
