<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use \App\Enums as E;
class UpdateTeacherLectureAppointment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:UpdateTeacherLectureAppointment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每晚更新老师试讲库老师至例子库中';

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
        $task = new \App\Console\Tasks\TaskController();
        $teacher_info = $task->t_teacher_lecture_info->get_different_teacher_info();

        if(is_array($teacher_info)){
            foreach($teacher_info as $tea_val){
                $task->t_teacher_lecture_appointment_info->row_insert([
                    "answer_begin_time" => $tea_val['add_time'],
                    "answer_end_time"   => $tea_val['add_time'],
                    "name"              => $tea_val['nick'],
                    "grade_ex"          => E\Egrade::get_desc($tea_val['grade']),
                    "subject_ex"        => E\Esubject::get_desc($tea_val['subject']),
                    "teacher_type"      => E\Eidentity::get_desc($tea_val['identity']),
                    "phone"             => $tea_val['phone'],
                ]);
            }
        }
    }

}