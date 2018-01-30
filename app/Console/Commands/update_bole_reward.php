<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class update_bole_reward extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_bole_reward';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新伯乐奖';

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
        $tea = new \App\Http\Controllers\teacher_money();
        //$start_time = strtotime(date('Y-m-1', strtotime('-1 month')));
        //$end_time = strtotime(date('Y-m-1', time()));
        $start_time = strtotime(date('Y-m-1', time()));
        $end_time   = time();

        $references = $task->t_teacher_lecture_appointment_info->get_references(); // 获取所有推荐人
        $teacherids = $task->t_teacher_info->get_teacherids(); // 获取所有老师
        $teacher_filter = [320557,420745,437138,330856,271370,271383,271386,271389,271391]; // 青团社,153...,黄桂荣，淅泰, ABCDE
        foreach($references as $key => $item) {
            if (isset($teacherids[$key])) { // 判断当前老师是否是推荐人
                $teacherid = $teacherids[$key]['teacherid'];
                if (in_array($teacherid,$teacher_filter)) continue;
                // 获取推荐人上月推荐人数
                $a_info = $task->t_teacher_lecture_appointment_info->get_money_list($start_time, $end_time, $key);
                // 获取推荐人上月已获伯乐奖人数
                $m_info = $task->t_teacher_money_list->get_money_list($start_time, $end_time, $teacherid);
                if (count($a_info) != count($m_info)) {
                    foreach($a_info as $val) {
                        if(!isset($m_info[$val['teacherid']])) { // 处理丢失数据
                            $tea->update_bole_reward($teacherid,$val['teacherid']);
                            $task->t_user_log->add_data("脚本自动添加伯乐奖 推荐人id:".$teacherid.' 被推荐人id:'.$val['teacherid']);
                        }
                    }
                }
            }
        }
    }
}
