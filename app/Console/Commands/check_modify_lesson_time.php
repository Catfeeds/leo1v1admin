<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class check_modify_lesson_time extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check_modify_lesson_time {--day=} {--always_reset=}';

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
        $this->task= new \App\Console\Tasks\TaskController();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $day=$this->option('day');

        if ( $day == null ){
            $day=date("Y-m-d");
        }


    }
}
