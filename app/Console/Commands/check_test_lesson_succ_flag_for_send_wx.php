<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class check_test_lesson_succ_flag_for_send_wx extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    public $task;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->task= new \App\Console\Tasks\TaskController();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        // 昨天结束的试听课
        $test_lesson_list_yes = $this->task->t_lesson_info_b2->get_test_lesson_success_list_yes();

        foreach( $test_lesson_list_yes as $item_yes){
            if($item_yes['success_flag'] == 0){
                //课时成功未设置
            }elseif($item_yes['order_confirm_flag'] == 0){
                // 助教未设置试听课结果
            }
        }


        $test_lesson_list_two_day_ago = $this->task->t_lesson_info_b2->get_test_lesson_success_list_two_days_ago();

        $test_lesson_list_three_day_ago  = $this->task->t_lesson_info_b2->get_test_lesson_success_list_three_days_ago();


    }
}
