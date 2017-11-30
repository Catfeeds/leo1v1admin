<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class ResetStudentLessonCount extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ResetStudentLessonCount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '重置学生课程的课时';

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
        $start = time();
        $end = strtotime("+1 day",$start);

        $lesson_list = $this->task->t_lesson_info->get_lesson_list_info(-1,$start,$end,E\Elesson_status::V_0);

        if(is_array($lesson_list) && !empty($lesson_list)){
            echo "lessonid|lesson_count|real_lesson_count";
            echo PHP_EOL;
            $check_time = strtotime("2017-12-5");
            $now = time();
            foreach($lesson_list as $l_val){
                if($l_val['reset_lesson_count_flag']==0){
                    if($l_val['subject']!=E\Esubject::V_2 || $now>$check_time ){
                        $real_lesson_count = \App\Helper\Utils::get_lesson_count($l_val['lesson_start'],$l_val['lesson_end']);
                        if($real_lesson_count != $l_val['lesson_count']){
                            echo $l_val['lessonid']."|".$l_val['lesson_count']."|".$real_lesson_count;
                            echo PHP_EOL;


                            // $this->task->t_lesson_info->field_update_list($l_val['lessonid'],[
                            //     "lesson_count" => $real_lesson_count
                            // ]);
                        }
                    }
                }
            }
        }
    }

}
