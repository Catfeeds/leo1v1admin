<?php
namespace App\Console\Commands;
use \App\Enums as E;
class set_every_month_student_score extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:set_every_month_student_score';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '设置每月学生成绩信息记录';




    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $time = strtotime(date("Y-m"));
        $this->task->t_student_score_info->set_every_month_student_score($time);
    }
}