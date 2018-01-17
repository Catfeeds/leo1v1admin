<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;

class UpdateSetLessonFullNum extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:UpdateSetLessonFullNum {--month=}';

    /**
     * The console command description.
     * 全勤奖已于2017年3月1日0时0分停止运行
     * @var string
     */
    protected $description = '每天晚上更新全勤奖的课次';

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
        //全勤奖已于2017年3月1日0时0分停止运行
        return false;

        $month = $this->option('month');
        if($month==null){
            $month="0";
        }

        $start_date = strtotime("2016-12-1");
        $start_time = strtotime(date("Y-m-01",time()));
        $time = strtotime("-".$month."month",$start_time);
        if($time<$start_date || $month=="0"){
            $start = $start_date;
        }else{
            $start = $time;
        }

        $job = new \App\Jobs\UpdateSetLessonFullNum($start);
        dispatch($job);
    }

}
