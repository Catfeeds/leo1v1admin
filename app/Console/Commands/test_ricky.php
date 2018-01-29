<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use \App\Enums as E;

class test_ricky extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test_ricky';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '拉取数据';

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

        //90分钟 --- 排课时间、课程ID、老师姓名、学生姓名、上课时间、助教姓名、学生合同创建时间（第一份合同）
        // 常规课表
        $info = $task->t_week_regular_course->get_all_info();
        // 寒假课表
        //$info = $task->t_winter_week_regular_course->get_all_info();
        foreach($info as $item) {
            $teacherid = $item["teacherid"];
            $userid = $item["userid"];
            $start_time = explode("-", $item["start_time"]);
            $date = $start_time[0];
            $time = $start_time[1];
            $count = ($item["end_time"] * 100) - ($time * 100);
            if ($count >= 80 && $count <= 100) {
                if ($date <= 3) $start_time = strtotime("2018-1-".(28 + $date)." ".$time);
                else $start_time = strtotime("2018-2-".($date - 3)." ".$time);
                $lesson = $task->t_week_regular_course->get_info_for_start_time($teacherid, $userid, $start_time);
                if ($lesson) {
                    echo date("Y-m-d H:i:s", $lesson["operate_time"]).",";
                    echo $lesson["lessonid"].",";
                    echo $task->cache_get_teacher_nick($teacherid).",";
                    echo $task->cache_get_student_nick($userid).",";
                    echo date("Y-m-d H:i:s", $lesson["lesson_start"]).",";
                    echo ($lesson["lesson_count"] / 100)."课时".",";
                    echo ($item["end_time"] * 100)." ".($time * 100)." ".$count.",";
                    echo $task->cache_get_assistant_nick($lesson["assistantid"]).",";
                    $order = $task->t_teacher_feedback_list->get_order_list($userid);
                    echo date("Y-m-d H:i:s", $order).PHP_EOL;
                }
            }
        }
        exit;

        //助教、组别、学生ID、学生姓名、第一次合同创建时间、科目、科目更换老师次数、未消耗课时、学员类型
        $info = $task->t_student_info->get_list_count_left();
        $group = $task->t_admin_group_name->get_ass_group_name(E\Emain_type::V_1);
        foreach ($info as $item) {
            $userid = $item["userid"];
            $list = $task->t_course_order->get_list($userid);
            $lesson_count = 0;
            foreach($list as $val) {
                $lesson_count += $val["no_finish_lesson_count"]/100;
            }
            if (!$lesson_count) continue;
            $aid = $item["assistantid"];
            echo $task->cache_get_assistant_nick($aid).",";
            $groud_id = $task->t_admin_group->get_group_id_by_aid2($aid);
            if (isset($group[$groud_id])) {
                $group_name = $group[$groud_id]["group_name"];
            } else {
                $group_name = $task->t_admin_group_name->get_group_name_by_groupid($groud_id);
            }
            echo $group_name.",";
            $userid = $item["userid"];
            echo $userid.",";
            echo $item["nick"].",";
            $order = $task->t_teacher_feedback_list->get_order_list($userid);
            echo date("Y-m-d H:i:s", $order).",";
            $subject = $task->t_student_info->get_list_subject($userid);
            $subj = "";
            foreach ($subject as $val) {
                $subject = $val["subject"];
                $count = $task->t_student_info->get_teacher_count($userid, $subject);
                $subj .= E\Esubject::get_desc($val["subject"])."(".$count.")-";
            }
            if ($subj) {
                $subj = substr($subj,0,-1);
            }
            echo $subj.",";
            //$count = $task->t_student_info->get_teacher_count($userid);
            //echo $count.",";
            echo $lesson_count.",";
            echo E\Estudent_type::get_desc($item["type"]).PHP_EOL;
        }

        // 拉取90分钟补偿数据
        // $month = [8,9,10,11,12,1];
        // foreach ($month as $item) {
        //     $start_time = strtotime('2017-'.$item.'-1');
        //     if ($item == 12) {
        //         $end_time = strtotime('2018-1-1');
        //     } else {
        //         $end_time = strtotime('2017-'.($item + 1).'-1');
        //     }
        //     if ($item == 1) {
        //         $start_time = strtotime("2018-1-1");
        //         $end_time = strtotime("2018-2-1");
        //     }
        //     echo $item."月".PHP_EOL;
        //     $info = $task->t_teacher_feedback_list->get_90_list($start_time, $end_time);
        //     foreach($info as $val) {
        //         if (!($val["teacherid"] && $val["lessonid"])) continue;
        //         echo $task->cache_get_teacher_nick($val["teacherid"]).",";
                
        //         $userid = $val["userid"];
        //         echo $task->cache_get_student_nick($userid).",";
        //         echo $val["lessonid"].",";
        //         echo $task->cache_get_assistant_nick($val["assistantid"]).",";
        //         echo date("Y-m-d H:i:s", $val["lesson_start"]).",";
        //         $order = $task->t_teacher_feedback_list->get_order_list($userid);
        //         echo date("Y-m-d H:i:s", $order).PHP_EOL;
        //     }
        // }

        

        // $month = [11, 12];
        // foreach ($month as $item) {
        //     echo $item.'月';
            // $start_time = strtotime('2017-'.$item.'-1');
            // if ($item == 12) {
            //     $end_time = strtotime('2018-1-1');
            // } else {
            //     $end_time = strtotime('2017-'.($item + 1).'-1');
            // }
        //     $call_count = $task->t_tq_call_info->get_count_called_phone($start_time, $end_time);
        //     echo '当前拨打总数'.$call_count;
        //     $stu_count = $task->t_tq_call_info->get_count_stu($start_time, $end_time);
        //     echo '当前例子总数'.$stu_count;
        //     echo '首次未接通但是被拨打N次后接通的平均拨打次数'.($call_count / $stu_count);
        // }

        // 拉取2017年下单学员的预警数据
        // $start_time = strtotime("2017-1-1");
        // $end_time = strtotime("2018-1-1");
        // $info = $task->t_revisit_info->get_all_info($start_time, $end_time);
        // $stu = $task->t_student_info->get_test_user();
        // $stus = [];
        // foreach($stu as $val) {
        //     array_push($stus, $val["userid"]);
        // }
        // foreach($info as $item) {
        //     if (in_array($item["userid"], $stus)) continue;
        //     echo $item["userid"]." ".E\Eis_warning_flag::get_desc($item["is_warning_flag"]).PHP_EOL;
        // }
        // //$user = exec("who | cut -d' ' -f1");
        // // $filename = "/tmp/userid.log";
        // $info = file_get_contents($filename);
        // $info = explode("\n", $info);
        // foreach ($info as $key => $item) {
        //     if ($key % 1000 == 0) sleep(5);
        //     $userid = str_replace(',', '', $item);
        //     $count = $task->t_lesson_info_b3->get_subject_count($userid);
        //     echo $userid." ".$count.PHP_EOL;
        // }
        //$info = implode(" ", $info);
        //dd(trim($info));
    }


}
