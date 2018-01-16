<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetTeacherSimulateMoney extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SetTeacherSimulateMoney {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新老师的模拟工资';

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
        $teacher_money_task = new \App\Console\Tasks\TeacherSimulateMoneyTask();
        $date  = $this->get_in_value('date',0);
        if($date == 0){
            $date = strtotime("-1 month",time());
        }

        $teacher_money_task->set_teacher_simulate_salary_list($date);
    }
}
