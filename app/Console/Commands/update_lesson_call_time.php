<?php
namespace App\Console\Commands;
use \App\Enums as E;
class update_lesson_call_time extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_lesson_call_time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';




    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $start_time = strtotime(date('Y-m-d',time(null)).'00:00:00');
        $end_time = $start_time + 24*3600;
        $this->task->t_lesson_info_b2->get_test_lesson_list($start_time,$end_time,-1);
    }
}