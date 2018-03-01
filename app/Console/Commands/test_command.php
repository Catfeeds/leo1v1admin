<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class test_command extends cmd_base
{
    use \App\Http\Controllers\TeaPower;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test_command {--year=}{--month=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '测试命令';

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
        $year  = $this->get_in_value("year",2017);
        $month = $this->get_in_value("month",1);

        $test_code = new \App\Http\Controllers\test_code();
        $ret = $test_code->test_money($year,$month);
        dd($ret);
    }



}
