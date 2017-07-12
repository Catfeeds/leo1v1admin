<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NoticeLessonHomeworkNotFinish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:NoticeLessonHomeworkNotFinish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '2天仍未做作业提醒';

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
        $task->notice_lesson_homework_not_finish();
    }
}
