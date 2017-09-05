<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class teacher_fifth_regular_lesson_deal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:teacher_fifth_regular_lesson_deal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '老师第五次常规课信息汇总处理';

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
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task     = new \App\Console\Tasks\TaskController();

        $end_time = time();
        $start_time = $end_time-86400;
        $ret_info = $task->t_lesson_info_b2->get_teacher_fifth_regular_lesson_detail($start_time,$end_time);
        $i=0;
        foreach($ret_info as $val){
            $id = $val["id"];
            if($id>0){
               
            }else{
                $task->t_teacher_record_list->row_insert([
                    "teacherid"      => $val["teacherid"],
                    "type"           => 1,
                    "train_lessonid" => $val["lessonid"],
                    "lesson_style"   => 4,
                    "add_time"       => time()+$i,
                    "userid"         => $val["userid"],
                    "lesson_time" => $val["lesson_start"]

                ]);

            }
            $i=$i+10;

        }
        //dd($ret_info);

    }
}
