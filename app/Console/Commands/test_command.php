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
        // $type = $this->option("type");
        echo "this is test command";
        sleep(2);
        echo "sleep finish";
        $this->info("show info");

        $bar = $this->output->createProgressBar(3);
        sleep(1);
        $bar->advance();
        sleep(2);
        $bar->advance();
        sleep(2);
        $bar->advance();

        $bar->finish();
    }
}
