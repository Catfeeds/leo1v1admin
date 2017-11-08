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
    protected $signature = 'command:test_command';

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
        $start_time = strtotime("2017-11-2 18:00");
        $end_time = strtotime("2017-11-2 21:20");
        $lesson_list = $this->task->t_lesson_info->get_lesson_list_info(-1,$start_time,$end_time);
        foreach($lesson_list as $l_val){
            if($l_val['confirm_flag']==4){
                $diff_time = $l_val['lesson_end']-$l_val['lesson_start'];
                if($diff_time==5400){
                    $real_lesson_count = 100;
                }else{
                    $real_lesson_count = $diff_time/2400*100/2;
                }
                if($real_lesson_count!=$l_val['lesson_count']){
                    echo $l_val['lessonid']."|".$l_val['lesson_count']."|".$real_lesson_count;
                    echo PHP_EOL;
                }
            }
        }
    }
}
