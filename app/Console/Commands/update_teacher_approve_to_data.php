<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class update_teacher_approve_to_data extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_teacher_approve_to_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新老师晋升参考数据';

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
        // 拉取 9,10,11 三月的数据
        // $arr = [9,10,11];
        // foreach($arr as $val) {
        //     echo $val.'月'.PHP_EOL;
        //     $start_time = strtotime("2017-".$val.'-1');
        //     $end_time = strtotime("2017-".($val + 1).'-1');
        //     $ret_info = $task->t_lesson_info_b3->get_tea_lesson_info_for_approved($start_time, $end_time);

        //     foreach($ret_info as $item) {
        //         $teacherid = $item['teacherid'];
        //         $violation_info = $task->t_lesson_info_b3->get_violation_num($start_time, $end_time, $teacherid);
        //         $nick = $task->cache_get_teacher_nick($teacherid);
        //         echo $teacherid.' '.$nick;
        //         foreach ($violation_info as $v) {
        //             echo ' '.$v;
        //         }
        //         echo PHP_EOL;
        //     }
        //     sleep(5);
        // }
        // exit;
        // $arr = [9,10,11];
        // foreach($arr as $val) {
        //     $start_time = strtotime('2017-'.$val.'-1');
        //     $end_time = strtotime('2017-'.($val + 1).'-1');
        //     echo date('Y-m-d H:i:s', $start_time);
        //     echo date('Y-m-d H:i:s', $end_time);
        //             $ret_info = $task->t_lesson_info_b3->get_tea_lesson_info_for_approved($start_time, $end_time);

        // foreach($ret_info as $item){
        //     $teacherid = $item['teacherid'];
        //     $cc_order_num = $task->t_order_info->get_cc_test_lesson_num($start_time, $end_time, $teacherid,'2');
        //     $cc_lesson_num = $task->t_order_info->get_cc_lesson_num($start_time, $end_time, $teacherid, '2');
        //     $cr_order_num  = $task->t_order_info->get_cc_test_lesson_num($start_time, $end_time, $teacherid,'1');
        //     $cr_lesson_num = $task->t_order_info->get_cc_lesson_num($start_time, $end_time, $teacherid, '1');
        //     $violation_info = $task->t_lesson_info_b3->get_violation_num($start_time, $end_time, $teacherid);
        //     $violation_num = array_sum($violation_info);

        //     $id = $task->t_teacher_approve_refer_to_data->get_id_for_teacherid($start_time, $end_time, $teacherid);

        //     if ($id) {
        //         echo '没有';
        //         $task->t_teacher_approve_refer_to_data->field_update_list($id, [
        //             'stu_num' => $item['stu_num'],
        //             'total_lesson_num' => $item['total_lesson_num'],
        //             'cc_order_num' => $cc_order_num,
        //             'cc_lesson_num' => $cc_lesson_num,
        //             'cr_order_num' => $cr_order_num,
        //             'cr_lesson_num' => $cr_lesson_num,
        //             'violation_num' => $violation_num,
        //         ]);
        //     } else {
        //         echo '调用这里';
        //         $task->t_teacher_approve_refer_to_data->row_insert([
        //             'teacherid' => $teacherid,
        //             'stu_num' => $item['stu_num'],
        //             'total_lesson_num' => $item['total_lesson_num'],
        //             'cc_order_num' => $cc_order_num,
        //             'cc_lesson_num' => $cc_lesson_num,
        //             'cr_order_num' => $cr_order_num,
        //             'cr_lesson_num' => $cr_lesson_num,
        //             'violation_num' => $violation_num,
        //             'add_time' => $start_time
        //         ]);

        //     }

        // }
        // }
        $start_time = strtotime(date('Y-m-1', time()));
        $end_time = time();
        // $ret_info = $task->t_lesson_info_b3->get_tea_lesson_info_for_approved($start_time, $end_time);
        $ret_info = $task->t_lesson_info_b3->get_teacher_data($start_time, $end_time);
        //求换老师个数 --begin--
        $distinct_lesson_info = $task->t_lesson_info_b3->get_distinct_lesson_info($start_time, $end_time);
        $teacher_violation_arr = [];
        //记录学生课程情况数组
        $teacher_student_arr = [];
        foreach($distinct_lesson_info as $item){
            if(!@$teacher_violation_arr[$item['teacherid']]){
                //初始化每个老师的数据
                $teacher_violation_arr[$item['teacherid']] = [
                    'turn_teacher_count' => 0
                ];

                $teacher_student_arr[$item['userid']][$item['subject']][] = $item['teacherid'];
            }

            if(@isset($teacher_student_arr[$item['userid']][$item['subject']])
               // && @$teacher_student_arr[$item['userid']][$item['subject']]['teacherid'] != $item['teacherid']
               && !in_array($item['teacherid'],$teacher_student_arr[$item['userid']][$item['subject']])
            ){
                //该学生该课程的老师存在变更
                $length = count($teacher_student_arr[$item['userid']][$item['subject']]);
                $key = $length-1;
                $err_teacher_id = $teacher_student_arr[$item['userid']][$item['subject']][$key];
                if(!isset($teacher_violation_arr[$err_teacher_id]['turn_teacher_count']))
                    $teacher_violation_arr[$err_teacher_id]['turn_teacher_count'] = 0;
                $teacher_violation_arr[$err_teacher_id]['turn_teacher_count'] ++;

                $teacher_student_arr[$item['userid']][$item['subject']][] = $item['teacherid'];
            }
        }

        //求换老师个数 --end--


        foreach($ret_info as $item){
            $teacherid = $item['teacherid'];
            $cc_order_num = $task->t_order_info->get_cc_test_lesson_num($start_time, $end_time, $teacherid,'2');
            $cc_lesson_num = $task->t_order_info->get_cc_lesson_num($start_time, $end_time, $teacherid, '2');
            $cr_order_num  = $task->t_order_info->get_cc_test_lesson_num($start_time, $end_time, $teacherid,'1');
            $cr_lesson_num = $task->t_order_info->get_cc_lesson_num($start_time, $end_time, $teacherid, '1');
            // $violation_info = $task->t_lesson_info_b3->get_violation_num($start_time, $end_time, $teacherid);
            // $violation_num = array_sum($violation_info);
            $violation_num = $item['no_notes_count']+$item['test_lesson_later_count']+$item['regular_lesson_later_count']+$item['no_evaluation_count']+$item['turn_class_count']+$item['ask_for_leavel_count']+$item['test_lesson_truancy_count']+$item['test_lesson_truancy_count'];
            $test_lesson_count = $item['test_lesson_count'];
            $regular_lesson_count = $item['regular_lesson_count'];
            $no_notes_count =$item['no_notes_count'];
            $test_lesson_later_count = $item['test_lesson_later_count'];
            $regular_lesson_later_count = $item['regular_lesson_later_count'];
            $no_evaluation_count = $item['no_evaluation_count'];
            $turn_class_count = $item['turn_class_count'];
            $ask_for_leavel_count = $item['ask_for_leavel_count'];
            $test_lesson_truancy_count = $item['test_lesson_truancy_count'];
            $regular_lesson_truancy_count = $item['regular_lesson_truancy_count'];
            $stu_refund = $item['stu_refund'];

            $id = $task->t_teacher_approve_refer_to_data->get_id_for_teacherid($start_time, $end_time, $teacherid);

            if ($id) {
                $task->t_teacher_approve_refer_to_data->field_update_list($id, [
                    'stu_num' => $item['stu_num'],
                    'total_lesson_num' => $item['total_lesson_num'],
                    'cc_order_num' => $cc_order_num,
                    'cc_lesson_num' => $cc_lesson_num,
                    'cr_order_num' => $cr_order_num,
                    'cr_lesson_num' => $cr_lesson_num,
                    'violation_num' => $violation_num,
                    'test_lesson_count' => $test_lesson_count,
                    'regular_lesson_count' => $regular_lesson_count,
                    'no_notes_count' => $no_notes_count,
                    'test_lesson_later_count' => $test_lesson_later_count,
                    'regular_lesson_later_count' => $regular_lesson_later_count,
                    'no_evaluation_count' => $no_evaluation_count,
                    'turn_class_count' => $turn_class_count,
                    'ask_for_leavel_count' => $ask_for_leavel_count,
                    'test_lesson_truancy_count' => $test_lesson_truancy_count,
                    'regular_lesson_truancy_count' => $regular_lesson_truancy_count,
                    'stu_refund' => $stu_refund
                ]);
            }else{
                $task->t_teacher_approve_refer_to_data->row_insert([
                    'teacherid' => $teacherid,
                    'stu_num' => $item['stu_num'],
                    'total_lesson_num' => $item['total_lesson_num'],
                    'cc_order_num' => $cc_order_num,
                    'cc_lesson_num' => $cc_lesson_num,
                    'cr_order_num' => $cr_order_num,
                    'cr_lesson_num' => $cr_lesson_num,
                    'violation_num' => $violation_num,
                    'test_lesson_count' => $test_lesson_count,
                    'regular_lesson_count' => $regular_lesson_count,
                    'no_notes_count' => $no_notes_count,
                    'test_lesson_later_count' => $test_lesson_later_count,
                    'regular_lesson_later_count' => $regular_lesson_later_count,
                    'no_evaluation_count' => $no_evaluation_count,
                    'turn_class_count' => $turn_class_count,
                    'ask_for_leavel_count' => $ask_for_leavel_count,
                    'test_lesson_truancy_count' => $test_lesson_truancy_count,
                    'regular_lesson_truancy_count' => $regular_lesson_truancy_count,
                    'stu_refund' => $stu_refund,
                    'add_time' => $start_time
                ]);

            }
        }

    }
}
