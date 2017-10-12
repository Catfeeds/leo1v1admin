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
        //每月一号获取助教的所有在读学生个数,每月1号凌晨2点执行一次
        $time = time();
        // $start_time = strtotime( date("Y-m-1", $time) );
        // $end_time   = strtotime( "+1 month", $start_time() );
        /**  @var    \App\Console\Tasks\TaskController  $task*/
        $task = new \App\Console\Tasks\TaskController();

        // $ret_list = $task->t_manager_info->get_uid_stu_num($start_time, $end_time);
        $ret_list = $task->t_manager_info->get_uid_stu_num();
        foreach ($ret_list as $item) {
            if(is_array($item)) {
                $task->t_revisit_assess_info->row_insert([
                    'uid'         => $item['uid'],
                    'stu_num'     => $item['stu_num'],
                    'create_time' => $time,
                ]);
            }
        }

    }

}
