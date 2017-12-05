<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class funnel_channel_statistics extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:funnel_channel_statistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '渠道结点型数据统计';

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
        echo '测试';
    }
}
