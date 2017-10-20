<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class send_interview_remind_for_wx extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send_interview_remind_for_wx';

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

        $remind_list = $task->t_interview_remind->get_remind_list($start_time, $end_time);
    }
}
