<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class test_command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test_command {--type=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '测试内部调用command功能';

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
        $type = $this->option("type");
        if($type==1){
            return "succ";
        }else{
            return "error";
        }
    }
}
