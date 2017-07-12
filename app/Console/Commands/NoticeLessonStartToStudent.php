<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NoticeLessonStartToStudent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:NoticeLessonStartToStudent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '课前5分钟提醒学生上课';

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
        $task=new \App\Console\Tasks\CommonTask() ;
        $task->notice_lesson_start_to_student();
    }
}
