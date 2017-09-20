<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class reset_parent_call_status extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:res';

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
        //
        $task= new \App\Console\Tasks\TaskController();

        $seller_student_arr = $task->t_seller_student_new->get_all_stu_uid();

        foreach($seller_student_arr as $val){
            $call_flag = $task->t_tq_call_info->check_call_status($val['phone']);

            if($call_flag){
                $task->t_seller_student_new->field_update_list($val['userid'],[
                    "global_call_parent_flag" => 2 // 已接通
                ]);
            }else{
                $task->t_seller_student_new->field_update_list($val['userid'],[
                    "global_call_parent_flag" => 1 // 未接通
                ]);
            }
        }


    }
}
