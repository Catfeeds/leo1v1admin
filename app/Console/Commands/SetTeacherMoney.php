<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetTeacherMoney extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SetTeacherMoney {--type=}{--day=}{--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每个周期更新老师奖金/工资信息';

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
     * @param int type 1 每周二更新老师园丁奖  2,3 每天更新老师的试听签单奖励 4 更新老师工资列表
     * @param int day  老师签单奖更新的时间周期
     * @param int date 老师工资更新的时间戳
     * @return mixed
     */
    public function handle()
    {
        $task = new \App\Console\Tasks\TeacherMoneyTask();
        $type  = $this->get_in_value('type',2);
        $day   = $this->get_in_value('day',0);
        $date  = $this->get_in_value('date',0);

        if($type==1){
            $task->set_teacher_lesson_total_list();
        }elseif($type==2 || $type==3){
            $task->set_teacher_trial_success_reward($type,$day);
        }elseif($type==4){
            if($date == 0){
                $date = strtotime("-1 month",time());
            }
            $task->set_teacher_salary_list($type,$date);
        }
    }

}
