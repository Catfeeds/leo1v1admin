<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums  as E;
class power_back extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:power_back';

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
    public function do_handle()
    {

        $this->task->t_power_back->back();
    }
}
