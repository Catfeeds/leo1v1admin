<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetTeacherMoney extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SetTeacherMoney {--type=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每个周期更新老师奖金信息';

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
     * type=1  每周二更新老师荣誉榜
     * type=2  每天更新第三版老师的试听签单奖励
     * @return mixed
     */
    public function handle()
    {
        $task = new \App\Console\Tasks\TeacherMoneyTask();
        $type = $this->option('type');
        if($type===null){
            $type = 1;
        }

        if($type==1){
            $task->set_teacher_lesson_total_list();
        }elseif($type==2 || $type==3){
            $task->set_teacher_trial_success_reward($type);
        }
    }

}
