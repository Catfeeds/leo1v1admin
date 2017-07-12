<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NoticeLessonHomework extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:NoticeLessonHomework {--type=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '您有新的课后作业需要完成!';

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
        $task = new \App\Console\Tasks\CommonTask();
        $type = $this->option('type');
        if($type===null){
            $type=1;
        }
        $task->notice_lesson_homework($type);
    }
}
