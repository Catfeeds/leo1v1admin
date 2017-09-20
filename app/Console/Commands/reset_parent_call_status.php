<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class reset_parent_call_status extends Command
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
        $task= new \App\Console\Tasks\TaskController();

        $seller_student = $task->t_seller_student_new->get_all_stu_uid();

    }
}
