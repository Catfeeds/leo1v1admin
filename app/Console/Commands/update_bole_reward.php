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
        $start_time = 0;
        $end_time = time();

        $references = $task->t_teacher_lecture_appointment_info->get_references();
        $teacherids = $task->t_teacher_info->get_teacherids();
        //echo json_encode($references);
        // echo json_encode($teacherids);
        foreach($references as $key => $item) {
            if (isset($teacherids[$key])) {
                $a_info = $task->t_teacher_lecture_appointment_info->get_money_list($start_time, $end_time, $key);
                $m_info = $task->t_teacher_money_list->get_money_list($start_time, $end_time, $teacherids[$key]['teacherid']);
                if (count($a_info) != count($m_info)) {
                    foreach($a_info as $val) {
                        echo $teacherids[$key]['teacherid'].' --- '.$val['teacherid'];
                            //$tea->update_bole_reward($teacherids[$key]['teacherid'],$val['teacherid']);
                    }
                }
            }
        }
    }
}
