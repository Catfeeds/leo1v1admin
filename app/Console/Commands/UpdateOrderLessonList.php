<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;

class UpdateOrderLessonList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:UpdateOrderLessonList {--day=}';

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
     *
     * @return mixed
     */
    public function handle()
    {
        $day = $this->option('day');
        $now = time(NULL);
        if ($day===null) {
            $start_time = $now-86400*2;
        }else{
            $start_time = $now-$day*86400;
        }
        $end_time = $now;

        $competition_arr=[0,1];
        foreach($competition_arr as $val){
            $job = new \App\Jobs\UpdateOrderLessonList($val,$start_time,$end_time);
            dispatch($job);
        }
    }

}