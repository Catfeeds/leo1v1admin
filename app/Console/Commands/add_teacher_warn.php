<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class add_teacher_warn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:add_teacher_warn';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '添加老师预警数据';

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
        // 每日凌晨二点更新数据
        $date = strtotime('-1day');
        $start_time = strtotime(date('Y-m-d 00:00:00', $date));
        $end_time = strtotime(date('Y-m-d 23:59:59', $date));
        // 删除测试用户
        $data = $task->t_teacher_warn->get_info_for_test_user();
        foreach($data as $val) {
            $task->t_teacher_warn->row_delete($val['teacheri']);
        }
        exit;


        // 第一次加载所有数据
        $start_time = strtotime('2017-1-1');
        $end_time = time();

        //$data = $task->t_teacher_warn->get_info_for_time($start_time, $end_time);

        $info = $task->t_teacher_info->get_teacher_warn_info($start_time, $end_time);
        foreach($info as $item) {
            $minute=floor(($item['tea_attend'] - $item['lesson_start'])%86400/60);
            $five_num = $fift_num = $leave_num = 0;
            if ($minute > 15) {
                $fift_num = 1;
            }
            if ($minute > 5) {
                $five_num = 1;
            }
            if ($item['tea_late_minute'] >= 20) {
                $leave_num = 1;
            }

            $id = $task->t_teacher_warn->get_info_for_teacherid($item['teacherid'], $item['lesson_start']);
            if ($id) {
            //if (isset($data[$item['teacherid'].'_'.$item['lesson_start']])) {
                //$index = $item['teacherid'].'_'.$item['lesson_start'];
                //$id = $data[$index]['id'];
                $task->t_teacher_warn->field_update_list($id, [
                    'five_num' => $five_num,
                    'fift_num' => $fift_num,
                    'leave_num' => $leave_num
                ]);
                continue;
            }
            if ($five_num != 0 || $fift_num != 0 || $leave_num != 0) {
                $task->t_teacher_warn->row_insert([
                    'teacherid' => $item['teacherid'],
                    'five_num' => $five_num,
                    'fift_num' => $fift_num,
                    'leave_num' => $leave_num,
                    'lessonid' => $item['lessonid'],
                    'lesson_start' => $item['lesson_start'],
                    'add_time' => time()
                ]);
            }
        }
        //$data = $task->t_teacher_warn->get_info_for_time($start_time, $end_time);

        $info = $task->t_lesson_info->get_teacher_warn_info($start_time, $end_time);
        foreach($info as $item) {
            if ($item['teacherid'] == 0) continue;
            $id = $task->t_teacher_warn->get_info_for_teacherid($item['teacherid'], $item['lesson_start']);
            if ($id) {
                //dd($id);
                //$index = $item['teacherid'].'_'.$item['lesson_start'];
                //$id = $data[$index]['id'];
                if ($item['type'] == 21) { // 处理旷课
                    $task->t_teacher_warn->field_update_list($id, [
                        "absent_num" => 1
                    ]);
                }
                if ($item['type'] == 2) { // 处理调课
                    $task->t_teacher_warn->field_update_list($id, [
                        "adjust_num " => 1
                    ]);
                }
                if ($item['type'] == 12) { // 处理请假
                    $task->t_teacher_warn->field_update_list($id, [
                        "ask_leave_num" => 1
                    ]);
                }
            } else {
                $params = [
                    'teacherid' => $item['teacherid'],
                    'lessonid' => $item['lessonid'],
                    'lesson_start' => $item['lesson_start'],
                    'add_time' => time()
                ];
                if ($item['type'] == 21) { // 处理旷课
                    $params = array_merge($params, ['absent_num' => 1]);
                    $task->t_teacher_warn->row_insert($params);
                }
                if ($item['type'] == 2) { // 处理调课
                    $params = array_merge($params, ['adjust_num' => 1]);
                    $task->t_teacher_warn->row_insert($params);
                }
                if ($item['type'] == 12) { // 处理请假
                    $params = array_merge($params, ['ask_leave_num' => 1]);
                    $task->t_teacher_warn->row_insert($params);
                }
            }
        }

        $data = $task->t_teacher_warn->get_info_for_time($start_time, $end_time);

        $info = $task->t_teacher_money_list->get_teacher_warn_info($start_time, $end_time);
        foreach ($info as $item) {
            $lesson_start = $task->t_lesson_info->get_lesson_start($item['lessonid']);
            if ($lesson_start == 0) continue;
            if (isset($data[$item['teacherid'].'_'.$lesson_start])) {
                $task->t_teacher_warn->field_update_list($id, [
                    "big_order_num " => 1
                ]);
            } else {
                $task->t_teacher_warn->row_insert([
                    "teacherid" => $item['teacherid'],
                    "big_order_num" => 1,
                    'lessonid' => $item['lessonid'],
                    'lesson_start' => $lesson_start,
                    "add_time" => time()
                ]);
            }
        }

        // 删除测试用户
        $data = $task->t_teacher_warn->get_info_for_test_user();
        foreach($data as $val) {
            $task->t_teacher_warn->row_delete($val);
        }
    }
}
