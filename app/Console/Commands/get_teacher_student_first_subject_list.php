<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class get_teacher_student_first_subject_list extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get_teacher_student_first_subject_list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成老师学生第一次该科目的课程信息';

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

        $task = new \App\Console\Tasks\TaskController ();
        $start_time = strtotime("2018-01-01");
        $end_time = time();
        $list  = $task->t_lesson_info_b3->get_teacher_student_first_subject_info($start_time,$end_time);
        dd($list);
 
    }
}
