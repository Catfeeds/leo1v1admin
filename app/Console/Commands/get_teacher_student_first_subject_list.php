<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class get_teacher_student_first_subject_list extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get_teacher_student_first_subject_list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成老师学生第一次该科目的课程信息';

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
        /**  @var   $task \App\Console\Tasks\TaskController */

        $task = new \App\Console\Tasks\TaskController ();
        $start_time = strtotime("2018-01-01");
        $end_time = strtotime("2018-01-06");
        $list  = $task->t_lesson_info_b3->get_teacher_student_first_subject_info($start_time,$end_time);
        $i=0;
        foreach($list as $val){
            $id = $val["id"];
            if($id>0){
                $info =$task->t_teacher_record_list->get_record_info($id);
                $lessonid =$task->t_teacher_record_list->get_train_lessonid($id);
                $info .= "lessonid".$lessonid." change to".$val["lessonid"].",时间:".time().";";
                $task->t_teacher_record_list->field_update_list($id,[
                    "lesson_time" => $val["lesson_start"],
                    "record_info" => $info,
                    "train_lessonid"=>$val["lessonid"]
                ]);

            }else{
                $add_time = time()+$i;
                $task->t_teacher_record_list->row_insert([
                    "teacherid"      => $val["teacherid"],
                    "userid"         => $val["userid"],
                    "lesson_subject" => $val["subject"],
                    "lesson_time"    => $val["lesson_start"],
                    "train_lessonid" => $val["lessonid"],
                    "add_time"       => $add_time,
                    "type"           => 18
                ]);
                $i++;
            }
        }
        dd($list);
 
    }
}
