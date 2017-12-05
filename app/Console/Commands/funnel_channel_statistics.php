<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class funnel_channel_statistics extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:funnel_channel_statistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '渠道结点型数据统计';

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
        //
        $begin_time = strtotime(date('Y-11-01'));
        $end_time = strtotime('+1 month -1 second',$begin_time);
        $this->task->t_seller_student_origin->switch_tongji_database();
        $example_info = $this->task->t_seller_student_new->get_month_example_info($begin_time,$end_time);
        foreach($example_info as $item){
            $phone = $item['phone'];
            echo "$phone ok";
            \App\Helper\funnel_channel_sta::insert_funnel_channel_sta($item);
        }
    }
}
