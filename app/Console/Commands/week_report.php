<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class week_report extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:week_report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '周报数据统计';

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
     * @desn:每周三调用生成上一个周二到本周二的数据
     * @return mixed
     */
    public function handle()
    {
        // if($time_now == 'Wednesday'){
        // }else echo '统计时间出错!';
        $time_now = date('l');
        $start_time = strtotime('last Tuesday');
        $end_time = strtotime('+ 8 day',$start_time)-1;
        $report = new \App\Http\Controllers\report();
        $data_arr = $report->get_week_of_monthly_report($start_time, $end_time, $data_arr=[]);
        if($data_arr)//添加统计数据
            $this->task->t_week_of_monthly_report->job_row_insert($start_time,$report_type=1,$data_arr);
        echo 'week report ok!';
    }
}
