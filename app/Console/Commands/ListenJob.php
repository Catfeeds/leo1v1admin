<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ListenJob extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ListenJob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '监控Job进程,如果超过2分钟Job数量都保持在20以上,则进行报警';

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
        $job_count = $this->task->t_jobs->get_all_count();
        // if($job_count>20){

        // }

    }
}
