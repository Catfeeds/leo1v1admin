<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class send_lesson_plan_tomorrow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $task = new \App\Console\Tasks\TaskController();

        $lesson_start = strtotime('+1 day',strtotime(date('Y-m-d')));
        $lesson_end = $lesson_start+86400;

        $job = new \App\Jobs\send_wx_tomorrow_tea($lesson_start, $lesson_end);
        dispatch($job);
    }
}
