<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class fulltime_teacher_data extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fulltime_teacher_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '全职老师数据存档';

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
        $task=new \App\Console\Tasks\TaskController();
        $start_time = 1488297600;
        $end_time   = time();

        $first_time  = strtotime(date('Y-m-01',$start_time));
        $second_time = strtotime(date('Y-m-01',$end_time));
        $i = $first_time;
        $montharr = [];
        while($i  <= $second_time){
            $montharr[] = date('Y-m-01',$i);
            $i = strtotime('+1 month', $i);
        }
        $i = 0;
        $subject_chinese = [];
        $subject_math = [];
        $subject_english = [];
        $date_list = [];
        foreach ($montharr as $key => $value) {
            $time1 = strtotime($value);
            $month = date('Y-m',$time1);
            $time2 = strtotime('+1 month',$time1);
            $student_num = $task->t_teacher_info->get_student_number($time1,$time2);
            $lesson_count = $task->t_manager_info->get_fulltime_teacher_lesson_count($time1,$time2);
            $cc_transfer_all = $task->t_manager_info->get_fulltime_teacher_cc_transfer($time1,$time2);
            $cc_transfer_sh = $task->t_manager_info->get_fulltime_teacher_cc_transfer($time1,$time2,1);
            $cc_transfer_wh = $task->t_manager_info->get_fulltime_teacher_cc_transfer($time1,$time2,2);
            $cc_transfer_all_per = $cc_transfer_all['all_lesson'] > 0?100 * round(100*$cc_transfer_all['order_num']/$cc_transfer_all['all_lesson'],2):0;
            $cc_transfer_sh_per = $cc_transfer_sh['all_lesson'] > 0?100 * round(100*$cc_transfer_sh['order_num']/$cc_transfer_sh['all_lesson'],2):0;
            $cc_transfer_wh_per = $cc_transfer_wh['all_lesson']>0?100 * round(100*$cc_transfer_wh['order_num']/$cc_transfer_wh['all_lesson'],2):0;
            $data_all = [
                "create_time"     => $time1,
                "time_range"      => date("Y-m-d",$time1).'--'.date("Y-m-d",$time2),
                "teacher_type"    => 0,//all
                "student_num"     => $student_num['stu_num'],
                "lesson_count"    => $lesson_count['lesson_all'],
                "cc_transfer_per" => $cc_transfer_all_per
            ];
            $data_sh = [
                "create_time"     => $time1,
                "time_range"      => date("Y-m-d",$time1).'--'.date("Y-m-d",$time2),
                "teacher_type"    => 1,//sh
                "student_num"     => $student_num['sh_num'],
                "lesson_count"    => $lesson_count['sh_lesson_all'],
                "cc_transfer_per" => $cc_transfer_sh_per
            ];
            $data_wh = [
                "create_time"     => $time1,
                "time_range"      => date("Y-m-d",$time1).'--'.date("Y-m-d",$time2),
                "teacher_type"    => 2,//wh
                "student_num"     => $student_num['wh_num'],
                "lesson_count"    => $lesson_count['wh_lesson_all'],
                "cc_transfer_per" => $cc_transfer_wh_per
            ];
            $create_time = $time1;

            $ret_all = $task->t_fulltime_teacher_data->get_info_by_type_and_time(0,$create_time);
            if($ret_all>0){
                $task->t_fulltime_teacher_data->field_update_list($ret_all,$data_all);
            }else{
                $task->t_fulltime_teacher_data->row_insert($data_all);
            }

            $ret_sh = $task->t_fulltime_teacher_data->get_info_by_type_and_time(1,$create_time);
            if($ret_sh>0){
                $task->t_fulltime_teacher_data->field_update_list($ret_sh,$data_sh);
            }else{
                $task->t_fulltime_teacher_data->row_insert($data_sh);
            }

            $ret_wh = $task->t_fulltime_teacher_data->get_info_by_type_and_time(2,$create_time);
            if($ret_wh>0){
                $task->t_fulltime_teacher_data->field_update_list($ret_wh,$data_wh);
            }else{
                $task->t_fulltime_teacher_data->row_insert($data_wh);
            }

        }
    }
}
