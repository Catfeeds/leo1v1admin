<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NoticeTeacherSetStuPerformance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:NoticeTeacherSetStuPerformance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '提醒老师评价学生!';

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
        $task=new \App\Console\Tasks\CommonTask();
        $task->notice_teacher_set_stu_performance();
    }
}
