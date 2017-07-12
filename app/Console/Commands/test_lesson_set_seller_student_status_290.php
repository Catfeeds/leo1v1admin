<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class test_lesson_set_seller_student_status_290 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test_lesson_set_seller_student_status_290';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var  \App\Console\Tasks\TaskController
     */
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
        $this->task->t_test_lesson_subject-> set_seller_student_status_290_at_time();
        //
    }
}
