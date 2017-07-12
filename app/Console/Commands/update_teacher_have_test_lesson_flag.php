<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class update_teacher_have_test_lesson_flag extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_teacher_have_test_lesson_flag';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每日更新老师是否上过试听课';

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
        $start_time = time()-86400;
        $end_time = time();
        $list = $task->t_teacher_info->get_no_test_lesson_teacher_lesson_info($start_time,$end_time);
        foreach($list as $item){
            $task->t_teacher_info->field_update_list($item["teacherid"],["have_test_lesson_flag"=>1]);
        }

        //dd($teacher_week_lesson_count_list);
             
        
    }
}
