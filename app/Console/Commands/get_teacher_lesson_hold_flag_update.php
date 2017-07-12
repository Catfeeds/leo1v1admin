<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class get_teacher_lesson_hold_flag_update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get_teacher_lesson_hold_flag_update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '老师暂停接课权限自动更新';

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
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();
        $time = time()-60*86400;
        $teacher_list = $task->t_teacher_info->get_no_test_lesson_teacher_list($time);
        foreach($teacher_list as &$item){
            $task->t_teacher_info->field_update_list($item["teacherid"],[
                "lesson_hold_flag"        =>1, 
                "lesson_hold_flag_acc"    =>"system", 
                "lesson_hold_flag_time"   =>time(), 
            ]);
        }
                          
    }
}
