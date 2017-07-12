<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class update_teacher_leave_info extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_teacher_leave_info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '老师请假信息更新暂停接课';

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
             
        $leave_start_list = $task->t_teacher_leave_info->get_teacher_leave_no_hold_list();
        foreach($leave_start_list as $val){
            $task->t_teacher_info->field_update_list($val["teacherid"],[
               "lesson_hold_flag" =>1
            ]);
        }

        $leave_end_list = $task->t_teacher_leave_info->get_teacher_leave_end_hold_list();
        foreach($leave_end_list as $item){
            $task->t_teacher_info->field_update_list($item["teacherid"],[
                "lesson_hold_flag" =>0
            ]);
        }

        // dd($leave_end_list);
              
    }
}
