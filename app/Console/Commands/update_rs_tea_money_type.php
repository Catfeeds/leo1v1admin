<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class update_rs_tea_money_type extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_rs_tea_money_type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新教研老师 老师工资类型:在职老师薪资，等级:C';

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
        $info = $task->t_manager_info->get_rs_tea_info();
        if ($info) {
            foreach($info as $item) {
                if (!$item["teacherid"]) echo "调用成功";
                // if ($item["teacher_type"] != 4 || $item["teacher_money_type"] != 0) {
                //     echo $item["teacherid"]." ";
                //     $task->t_manager_info->field_update_list($item["teacherid"], [
                //         "teacher_type" => 4,
                //         "teacher_money_type" => 0
                //     ]);
                //     $task->t_user_log->add_data("脚本自动修改教研老师工资类型 修改前:老师类型->".$item["teacher_type"]."老师工资类型->".$item["teacher_money_type"]);
                // }
            }
        }

    }
}
