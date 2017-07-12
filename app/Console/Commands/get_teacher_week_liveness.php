<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class get_teacher_week_liveness extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get_teacher_week_liveness';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新老师一周活跃度';

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
        $start_time = time()-7*86400;
        $end_time = time();
        $teacher_week_lesson_count_list = $task->t_teacher_info->get_teacher_week_lesson_count_list($start_time,$end_time);
        foreach($teacher_week_lesson_count_list as &$item){
            $lesson_count = $item["lesson_count_total"]/100;

            $task->t_teacher_info->field_update_list($item["teacherid"],["week_liveness"=>$lesson_count]);
            
        }

        //dd($teacher_week_lesson_count_list);
             
        
    }
}
