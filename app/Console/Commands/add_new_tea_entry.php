<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class add_new_tea_entry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:add_new_tea_entry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '新师入职(面试,培训-模拟试听)存档';

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
        // 存档
        $task = new \App\Console\Tasks\TaskController();
        $add_time = time();
        $begin = date('Y-m-01 00:00:00', time());
        $start_time = strtotime($begin);
        $end = date('Y-m-d', strtotime("$begin +1 month -1 day"));
        $end_time = strtotime($end);

        // 面试通过人数
        $ret_info = $task->t_teacher_info->get_interview_through_count($start_time, $end_time);
        // 培训参训新师人数
        $train_tea = $task->t_teacher_info->get_train_inter_teacher_count($start_time, $end_time);
        // 科目培训合格
        $train_qual = $task->t_teacher_info->get_subject_train_qual_count($start_time, $end_time);
        // 模拟试听排课人数
        $imit_lesson = $task->t_lesson_info->get_imit_audi_sched_count($start_time, $end_time);
        // 模拟试听上课人数
        $attend_lesson = $task->t_lesson_info->get_attend_lesson_count($start_time, $end_time);
        // 模拟试听通过人数
        $adopt_lesson = $task->t_lesson_info->get_adopt_lesson_count($start_time, $end_time);
        $total['sum'] = 0;
        $total['train_tea_sum'] = 0;
        $total['train_qual_sum'] = 0;
        $total['imit_sum'] = 0;
        $total['attend_sum'] = 0;
        $total['adopt_sum'] = 0;
        foreach($ret_info as $key => &$item) {
            $ret_info[$key]['train_tea_sum'] = $train_tea[$key]['sum'];
            $ret_info[$key]['train_qual_sum'] = $train_qual[$key]['sum'];
            $ret_info[$key]['imit_sum'] = $imit_lesson[$key]['sum'];
            $ret_info[$key]['attend_sum'] = $attend_lesson[$key]['sum'];
            $ret_info[$key]['adopt_sum'] = $adopt_lesson[$key]['sum']; 
            $total['sum'] += $item['sum'];
            $total['train_tea_sum'] += $train_tea[$key]['sum'];
            $total['train_qual_sum'] += $train_qual[$key]['sum'];
            $total['imit_sum'] += $imit_lesson[$key]['sum'];
            $total['attend_sum'] += $attend_lesson[$key]['sum'];
            $total['adopt_sum'] += $adopt_lesson[$key]['sum'];
            if (!isset($item['grade'])) {$item['grade'] = '';}
            $task->t_new_tea_entry->row_insert([
                'subject' => $item['subject'],
                'grade' => $item['grade'],
                'interview_pass_num' => $item['sum'],
                'train_attend_new_tea_num' => $train_tea[$key]['sum'],
                'train_qual_new_tea_num' => $train_qual[$key]['sum'],
                'imit_listen_sched_lesson_num' => $imit_lesson[$key]['sum'],
                'imit_listen_attend_lesson_num' => $attend_lesson[$key]['sum'],
                'imit_listen_pass_lesson_num' => $adopt_lesson[$key]['sum'],
                'add_time' => $add_time
            ]);
        }

        // 添加总计
        $task->t_new_tea_entry->row_insert([
            'subject' => '-2',
            'interview_pass_num' => $total['sum'],
            'train_attend_new_tea_num' => $total['train_tea_sum'],
            'train_qual_new_tea_num' => $total['train_qual_sum'],
            'imit_listen_sched_lesson_num' => $total['imit_sum'],
            'imit_listen_attend_lesson_num' => $total['attend_sum'],
            'imit_listen_pass_lesson_num' => $total['adopt_sum'],
            'add_time' => $add_time
        ]);

        // 面试通过人数
        $type_ret_info = $task->t_teacher_info->get_interview_through_type_count($start_time, $end_time);
        // 培训参训新师人数
        $train_tea = $task->t_teacher_info->get_train_inter_teacher_type_count($start_time, $end_time);
        // 老师类型培训合格
        $train_qual = $task->t_teacher_info->get_subject_train_qual_type_count($start_time, $end_time);
        // 模拟试听排课人数
        $imit_lesson = $task->t_lesson_info->get_imit_audi_sched_type_count($start_time, $end_time);
        // 模拟试听上课人数
        $attend_lesson = $task->t_lesson_info->get_attend_lesson_type_count($start_time, $end_time);
        // 模拟试听通过人数
        $adopt_lesson = $task->t_lesson_info->get_adopt_lesson_type_count($start_time, $end_time);
        $type_total['sum'] = 0;
        $type_total['train_tea_sum'] = 0;
        $type_total['train_qual_sum'] = 0;
        $type_total['imit_sum'] = 0;
        $type_total['attend_sum'] = 0;
        $type_total['adopt_sum'] = 0;
        foreach($type_ret_info as $key => &$item) {
            $type_ret_info[$key]['train_tea_sum'] = $train_tea[$key]['sum'];
            $type_ret_info[$key]['train_qual_sum'] = $train_qual[$key]['sum'];
            $type_ret_info[$key]['imit_sum'] = $imit_lesson[$key]['sum'];
            $type_ret_info[$key]['attend_sum'] = $attend_lesson[$key]['sum'];
            $type_ret_info[$key]['adopt_sum'] = $adopt_lesson[$key]['sum'];
            $type_total['sum'] += $item['sum'];
            $type_total['train_tea_sum'] += $train_tea[$key]['sum'];
            $type_total['train_qual_sum'] += $train_qual[$key]['sum'];
            $type_total['imit_sum'] += $imit_lesson[$key]['sum'];
            $type_total['attend_sum'] += $attend_lesson[$key]['sum'];
            $type_total['adopt_sum'] += $adopt_lesson[$key]['sum'];
            $task->t_new_tea_entry->row_insert([
                'identity' => $item['identity'],
                'interview_pass_num' => $item['sum'],
                'train_attend_new_tea_num' => $train_tea[$key]['sum'],
                'train_qual_new_tea_num' => $train_qual[$key]['sum'],
                'imit_listen_sched_lesson_num' => $imit_lesson[$key]['sum'],
                'imit_listen_attend_lesson_num' => $attend_lesson[$key]['sum'],
                'imit_listen_pass_lesson_num' => $adopt_lesson[$key]['sum'],
                'add_time' => $add_time
            ]);
        }
        // 添加总计
        $task->t_new_tea_entry->row_insert([
            'identity' => '-2',
            'interview_pass_num' => $type_total['sum'],
            'train_attend_new_tea_num' => $type_total['train_tea_sum'],
            'train_qual_new_tea_num' => $type_total['train_qual_sum'],
            'imit_listen_sched_lesson_num' => $type_total['imit_sum'],
            'imit_listen_attend_lesson_num' => $type_total['attend_sum'],
            'imit_listen_pass_lesson_num' => $type_total['adopt_sum'],
            'add_time' => $add_time
        ]);
    }
}
