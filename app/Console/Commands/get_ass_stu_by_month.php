<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class get_ass_stu_by_month extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get_ass_stu_by_month';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        //每月一号获取助教的所有在读学生个数
        // $now = strtotime( date("Y-m-d H:i:00", time()) );
        // $end_time   = $now - 7*24*3600;
        // $start_time = $end_time - 60;
        // /**  @var    \App\Console\Tasks\TaskController  $task*/
        // $task=new \App\Console\Tasks\TaskController();

        // $ret_list   = $task->t_revisit_info->get_overtime_by_now($start_time, $end_time);
        // foreach ($ret_list as $item) {
        //     if(is_array($item)) {
        //         $task->t_revisit_warning_overtime_info->row_insert([
        //             'userid'       => $item['userid'],
        //             'revisit_time' => $item['revisit_time'],
        //             'sys_operator' => $item['sys_operator'],
        //             'create_time'  => time(),
        //             'deal_time'    => 0,
        //             'deal_type'    => 0
        //         ]);
        //     }
        // }

    }

}
