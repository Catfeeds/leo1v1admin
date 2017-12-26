<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class week_report extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:week_report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '周报数据统计';

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
     * @desn:每周三调用生成上一个周二到本周二的数据
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
