<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        echo count($lesson_list);
        echo PHP_EOL;
        if(is_array($lesson_list) && !empty($lesson_list)){
            foreach($lesson_list as $l_val){
                $diff_time = $t_val['lesson_end']-$t_val['lesson_start'];
                $lesson_count = $diff_time/40;
            }
        }
    }
}
