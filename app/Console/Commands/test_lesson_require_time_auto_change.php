<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class test_lesson_require_time_auto_change extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test_lesson_require_time_auto_change';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '试听申请超过一个月未设置成功系统自动设置为失败';

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
        //
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();
        $end_time = time()-30*86400;
        $start_time = $end_time-86400;
        $require_id_list = $task->t_test_lesson_subject_require->get_no_succ_require_id_list($start_time,$end_time);
        
        foreach($require_id_list as $item){
            
            $task->t_test_lesson_subject_require->field_update_list($item["require_id"],[
                "test_lesson_order_fail_flag"     =>1605,
                "test_lesson_order_fail_set_time" =>time(NULL),
                "test_lesson_order_fail_desc"     =>"超过两周未确定默认为无效",
            ]);

        }

        
    }
}
