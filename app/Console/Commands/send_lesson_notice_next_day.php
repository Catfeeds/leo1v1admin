<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class send_lesson_notice_next_day extends Command
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
        $lesson_info_list = $task->t_lesson_info_b3->get_next_day_lesson_info();

        foreach($lesson_info_list as $item){
            
        }

    }
}
