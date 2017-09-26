<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckLessonTeacherMoneyType extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:CheckLessonTeacherMoneyType';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每天检测未上课程的工资类型';

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
        $list = $this->task->t_lesson_info_b3->check_lesson_teacher_money_type();
        foreach($list as $val){
            $this->t_lesson_info->field_update_list($val['lessonid'],[
                "teacher_money_type" => $val['teacher_money_type'],
                "level"              => $val['level'],
            ]);
        }
    }




}
