<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class check_test_lesson_succ_flag_for_send_wx extends Command
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
    public $task;


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

        $test_lesson_list = $this->task->t_lesson_info_b2->get_test_lesson_success_list();



    }
}
