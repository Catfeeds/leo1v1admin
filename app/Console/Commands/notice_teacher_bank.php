<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class notice_teacher_bank extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:notice_teacher_bank ';

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
        $job = new \App\Jobs\send_wx_teacher_for_bankcard();
        dispatch($job);
    }
}
