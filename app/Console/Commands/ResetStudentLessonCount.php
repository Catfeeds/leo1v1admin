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

        $lesson_list = $this->task->t_lesson_info->get_lesson_list_info(0,$start,$end,E\Elesson_status::V_0);

        if(is_array($lesson_list) && !empty($lesson_list)){
            echo "lessonid|lesson_count|real_lesson_count|diff_time";
            echo PHP_EOL;
            foreach($lesson_list as $l_val){
                $diff_time = $t_val['lesson_end']-$t_val['lesson_start'];
                if($diff_time == 90){
                    $real_lesson_count = 200;
                }else{
                    $real_lesson_count = $diff_time/40*100;
                }
                if($real_lesson_count != $t_val['lesson_count']){
                    echo $t_val['lessonid']."|".$t_val['lesson_count']."|".$real_lesson_count."|".$diff_time;
                    echo PHP_EOL;
                    // $this->task->t_lesson_info->field_update_list($t_val['lessonid'],[
                    //     "lesson_count" => $real_lesson_count
                    // ]);
                }
            }
        }
    }
}
