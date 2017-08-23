<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class send_wx_msg_for_test_lesson extends Command
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
        $task = new \App\Console\Tasks\TaskController();

        // 获取试听课 课前30分钟
        $test_lesson_list = $task->t_lesson_info_b2->get_test_lesson_info_halfhour();


    }
}
