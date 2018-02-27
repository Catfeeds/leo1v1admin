<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class set_new_user_later_call_free extends Command
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
        //$this->task->t_seller_student_new->get_no_call_to_free_list();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        //$this->task->
        //
    }
}
