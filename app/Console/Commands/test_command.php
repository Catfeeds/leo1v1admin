<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class test_command extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test_command {--data=}';

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
        $data = $this->get_in_value("data","");
        $begin_time = strtotime("2017-7-1");

        $reference_num = $this->t_teacher_lecture_appointment_info->get_reference_num(
            $data,1,$begin_time
        );
        echo $reference_num;
        echo PHP_EOL;
    }


}
