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
    protected $description = '重置之后一天内学生未上课课程的课时,老师类型';

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
        //2018-1-27日前排好的90分钟课时不更新
        $check_lesson_count_time = strtotime("2018-1-27");

        if(is_array($lesson_list) && !empty($lesson_list)){
            foreach($lesson_list as $l_val){
                $update_arr = [];
                //检测课程的课时是否正确
                if($l_val['reset_lesson_count_flag']==0 && $l_val['lesson_type']!=2){
                    $real_lesson_count = \App\Helper\Utils::get_lesson_count($l_val['lesson_start'],$l_val['lesson_end']);
                    $diff_time = $l_val['lesson_end']-$l_val['lesson_start'];

                    //2018-1-27日前排好的90分钟课时不更新
                    if($diff_time!=5400 || $l_val['operate_time']>$check_lesson_count_time){
                        if($real_lesson_count != $l_val['lesson_count']){
                            $update_arr["lesson_count"] = $real_lesson_count;
                        }
                        if(in_array($l_val['confirm_flag'],[E\Econfirm_flag::V_3,E\Econfirm_flag::V_4])){
                            $update_arr['lesson_count'] /=2;
                        }
                    }
                }

                //检测课程的老师类型是否正确
                if($l_val['teacher_type']!=$l_val['lesson_teacher_type']){
                    $update_arr = [
                        "teacher_type" => $l_val['teacher_type'],
                    ];
                }

                if(!empty($update_arr)){
                    $old_lesson_info = [
                        "lesson_count" => $l_val['lesson_count'],
                        "teacher_type" => $l_val['teacher_type'],
                    ];
                    $this->task->t_lesson_info->field_update_list($l_val['lessonid'],$update_arr);
                    $this->task->t_lesson_info_operate_log->add_lesson_operate_info(
                        $l_val['lessonid'],"lesson_count,teacher_type",$old_lesson_info,$update_arr,"Command","ResetStudentLessonCount"
                    );
                }
            }
        }
    }




}
