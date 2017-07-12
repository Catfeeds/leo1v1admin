<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


class BindUserid2Phone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:BindUserid2Phone';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '电话用户 在注册时，进行绑定';

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
        $task=new \App\Console\Tasks\CommonTask() ;
        $task->set_bind_phone_to_userid();
    }
}
