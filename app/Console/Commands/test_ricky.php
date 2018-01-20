<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use \App\Enums as E;

class test_ricky extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test_ricky';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '拉取数据';

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
        $task = new \App\Console\Tasks\TaskController();

        // $month = [11, 12];
        // foreach ($month as $item) {
        //     echo $item.'月';
        //     $start_time = strtotime('2017-'.$item.'-1');
        //     if ($item == 12) {
        //         $end_time = strtotime('2018-1-1');
        //     } else {
        //         $end_time = strtotime('2017-'.($item + 1).'-1');
        //     }
        //     $call_count = $task->t_tq_call_info->get_count_called_phone($start_time, $end_time);
        //     echo '当前拨打总数'.$call_count;
        //     $stu_count = $task->t_tq_call_info->get_count_stu($start_time, $end_time);
        //     echo '当前例子总数'.$stu_count;
        //     echo '首次未接通但是被拨打N次后接通的平均拨打次数'.($call_count / $stu_count);
        // }

        // 拉取2017年下单学员的预警数据
        $start_time = strtotime("2017-1-1");
        $end_time = strtotime("2018-1-1");
        $info = $task->t_revisit_info->get_all_info($start_time, $end_time);
        foreach($info as $item) {
            echo $item["userid"]." ".E\Eis_warning_flag::get_desc($item["is_warning_flag"]);
        }
    }
}
