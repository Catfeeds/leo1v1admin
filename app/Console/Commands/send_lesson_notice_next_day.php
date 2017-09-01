<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class send_lesson_notice_next_day extends Command
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

        $task->t_lesson_info_b3->switch_tongji_database();

        $lesson_info_list = $task->t_lesson_info_b3->get_next_day_lesson_info();

        foreach($lesson_info_list as $item){
            $this->get_data($item);
        }

    }



    

    public function get_data_tea($item){



        $data_msg = [
            'first' =>""
        ];
    }

    public function get_data_par($item){
        $data_msg = [
            'first' =>""
        ];
    }



    /***
        QdFD9O7SPf1eYO_46ptbVeHPnYwTQjCI4_Vj4-wukC8 // 家长

        {{first.DATA}}
        课程名称：{{keyword1.DATA}}
        上课时间：{{keyword2.DATA}}
        上课地点：{{keyword3.DATA}}
        联系电话：{{keyword4.DATA}}
        {{remark.DATA}}


        // gC7xoHWWX9lmbrJrgkUNcdoUfGER05XguI6dVRlwhUk // 老师帮

        {{first.DATA}}
        上课时间：{{keyword1.DATA}}
        课程类型：{{keyword2.DATA}}
        教师姓名：{{keyword3.DATA}}
        {{remark.DATA}}






     ***/
}
