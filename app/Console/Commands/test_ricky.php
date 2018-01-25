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

        // 拉取90分钟补偿数据
        $month = [11];
        //$lesson = $task->t_teacher_feedback_list->get_lesson_list();
        //$order = $task->t_teacher_feedback_list->get_order_list();
        foreach ($month as $item) {
            $start_time = strtotime('2017-'.$item.'-1');
            if ($item == 12) {
                $end_time = strtotime('2018-1-1');
            } else {
                $end_time = strtotime('2017-'.($item + 1).'-1');
            }
            $info = $task->t_teacher_feedback_list->get_90_list($start_time, $end_time);
            //echo "长度 : ".count($info);
            foreach($info as $item) {
                //var_dump($item);
                echo $task->cache_get_teacher_nick($item["teacherid"]).",";
                $lesson = $task->t_teacher_feedback_list->get_lesson_list($item["teacherid"], $item["lessonid"]);
                //var_dump($lesson);
                $userid = $lesson["userid"];
                echo $task->cache_get_student_nick($lesson["userid"]).",";
                echo $item["lessonid"].",";
                echo $task->cache_get_assistant_nick($lesson["assistantid"]).",";
                echo date("Y-m-d H:i:s", $lesson["lesson_start"]).",";
                $order = $task->t_teacher_feedback_list->get_order_list($userid);
                echo date("Y-m-d H:i:s", $order).PHP_EOL;
            }
        }

        

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
