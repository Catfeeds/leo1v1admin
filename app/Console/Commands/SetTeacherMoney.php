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
    protected $signature = 'command:SetTeacherMoney {--type=}{--day=}';

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
     * @param type  1 每周二更新老师荣誉榜
     2,3 每天更新老师的试听签单奖励
     * @param day 老师签单奖更新的时间周期
     * @return mixed
     */
    public function handle()
    {
        $task = new \App\Console\Tasks\TeacherMoneyTask();
        $type = $this->option('type');
        $day  = $this->option('day');
        \App\Helper\Utils::logger("set_teacher_money_day:".$day);

        if($type===null){
            $type = 1;
        }
        if($day===null){
            $day = 0;
        }

        if($type==1){
            $task->set_teacher_lesson_total_list();
        }elseif($type==2 || $type==3){
            $task->set_teacher_trial_success_reward($type,$day);
        }
    }

}
