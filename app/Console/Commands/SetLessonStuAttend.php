<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetLessonStuAttend extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SetLessonStuAttend {--day=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '设置课程的学生进入课堂时间';

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
        $day = $this->get_in_value("day",60);
        $start_time = strtotime("- $day day",time());
        $lesson_list = $this->task->t_lesson_info_b3->get_lesson_with_zero_attend($start_time);
        echo count($lesson_list);
        echo PHP_EOL;
        foreach($lesson_list as $l_val){
            $this->task->t_lesson_info_b3->field_update_list($l_val['lessonid'],[
                "stu_attend" => $l_val['opt_time']
            ]);
        }
    }
}
