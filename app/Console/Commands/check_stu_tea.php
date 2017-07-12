<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class check_stu_tea extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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


        $task = new \App\Console\Tasks\TaskController ();
        #$ret_stu = $task->t_week_regular_course->get_stu_info();
        /*
          $ret_lesson_stop_info = $task->t_lesson_info->get_lesson_list_stop_id();
          unset($ret_lesson_stop_info[0]);
          $ret_lesson_end_info = $task->t_lesson_info->get_lesson_list_end_id();
          unset($ret_lesson_end_info[0]);

        */

        //$list=$task->t_lesson_info-> //60
        //
    }
}
