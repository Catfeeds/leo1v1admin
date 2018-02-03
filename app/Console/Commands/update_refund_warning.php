<?php

namespace App\Console\Commands;

use \App\Enums as E;
use Illuminate\Console\Command;

class update_refund_warning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_refund_warning';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新退费预警';

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

        $info = $task->t_student_info->get_all_stu();
        // 30天
        $month_end_time = strtotime(date("Y-m-d", time()));
        $month_start_time = strtotime("-1 month", $month_end_time);
        // 二周
        $week_end_time = strtotime(date("Y-m-d", time()));
        $week_start_time = strtotime("-14day", $week_end_time);
        // 四周
        $end_time = strtotime(date("Y-m-d", time()));
        $start_time = strtotime("-28day", $end_time);

        foreach ($info as $key => $item) {
            if ($key % 5000 == 0) sleep(5);
            $userid = $item["userid"];
            // 换老师次数 $tea["count"]
            $tea = $task->t_student_info->get_teacher_count($userid);
            if (!isset($tea["teacherid"])) continue;
            // 新老师上课次数
            $count = $task->t_lesson_info_b3->get_teacher_lesson_count($tea["teacherid"]);
            // 上课次数(30天)
            $lesson_count_month = $task->t_lesson_info_b3->get_lesson_count_month($month_start_time, $month_end_time, $userid);
            // 上课次数(2周)
            $lesson_count_week = $task->t_lesson_info_b3->get_lesson_count_month($week_start_time, $week_end_time, $userid);
            // 单科上课次数(4周)
            $lesson_count = $task->t_lesson_info_b3->get_lesson_count_by_userid($userid);
            $one_count = array_column($lesson_count, "count");
            //var_dump($one_count);
            $reason = [
                "换老师次数" => $tea["count"],
                "新老师上课次数" => $count,
                "上课次数(30天)" => $lesson_count_month,
                "上课次数(2周)" => $lesson_count_week,
                "单科上课次数" => min($one_count)
            ];

            $level = 0;
            if (max($one_count) <= 3) {
                $level = 1;
            }

            if ($lesson_count_week <= 0) {
                $level = 2;
            } else if ($tea["count"] > 1 && $count < 6) {
                $level = 2;
            }


            if (in_array($item["type"], [2,3,4])) {
                $level = 3;
            } else if ($lesson_count_month <= 0) {
                $level = 3;
            } else if ($tea["count"] > 2 && $count < 6) {
                $level = 3;
            }

            $task->t_student_info->field_update_list($userid, [
                "refund_warning_level" => $level,
                "refund_warning_reason" => json_encode($reason)
            ]);

            echo $userid." level : ".$level;
            var_dump($reason);
        }

    }
}
