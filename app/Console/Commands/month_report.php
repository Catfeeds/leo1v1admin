<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class month_report extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:month_report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成月报!';

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
     * @desn:每月2号调用生成上月1号到本月1号数据
     * @return mixed
     */
    public function handle()
    {
        $time_now = date('d');
        if($time_now == 2){
            $end_time = strtotime(date('Y-m-01'));
            $start_time = strtotime('- 1 month',$end_time);
            $report = new \App\Console\Commands\week_report();
            $data_arr = $report->get_week_of_monthly_report($start_time, $end_time, $data_arr=[]);
            $id = $this->task->t_week_of_monthly_report->get_id_by_time_type($start_time,$report_type=2);
            if($data_arr){//添加统计数据
                if($id)
                    $this->task->t_week_of_monthly_report->job_row_update($id,$start_time,$report_type=2,$data_arr);
                else
                    $this->task->t_week_of_monthly_report->job_row_insert($start_time,$report_type=2,$data_arr);
            }
            echo 'month report ok!';

        }else echo '统计时间出错!';
    }
}
