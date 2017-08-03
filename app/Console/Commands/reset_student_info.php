<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class reset_student_info extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:reset_student_info {--type=}';

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
        $type=$this->option('type');
        if ($type===null) {
            $type="hour";
        }else{
            $type="hour";
        }
    }
}
