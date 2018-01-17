<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;

class UpdateOrderLessonList extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:UpdateOrderLessonList {--day=}{--type=}{--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每天晚上更新合同消耗情况';

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
     * @param int day 更新的天数
     * @param int type
     * 1 更新有效的1v1正式课的收入
     * 2 更新所有没有课时收入的课程
     * @param int date 时间戳
     * @return mixed
     */
    public function handle()
    {
        $day  = $this->get_in_value('day');
        $type = $this->get_in_value('type',1);
        $date = $this->get_in_value('date',time());

        $now = time(NULL);

        if ($day==0) {
            $start_time = $now-86400*2;
        }else{
            $start_time = $now-$day*86400;
        }
        $end_time = $now;

        if($type==1){
            $competition_arr = [0,1];
            foreach($competition_arr as $val){
                $job = new \App\Jobs\UpdateOrderLessonList($val,$start_time,$end_time);
                dispatch($job);
            }
        }elseif($type==2){
            $this->set_lesson_all_money($date);
        }
    }

    /**
     * 更新月份内所有的
     */
    public function set_lesson_all_money($time){
        $month_time = \App\Helper\Utils::get_month_range($time, true);

    }

}