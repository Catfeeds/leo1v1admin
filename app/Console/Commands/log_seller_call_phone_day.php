<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class log_seller_call_phone_day extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:log_seller_call_phone_day  {--day=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        /**  @var  $tt \App\Console\Tasks\TaskController*/

        $tt     = new \App\Console\Tasks\TaskController();
        $day=$this->option('day');
        if ($day===null) {
            $day=1;
        }

        $log_time = strtotime(date("Y-m-d" ,time()-86400*$day));
        $end_time = $log_time+86400;
        $log_type=1;
        $tt->t_tongji_date->del_log_time($log_type,$log_time);

        //$list=$tt->t_seller_student_info->tongji_last_revisite_time($log_time,$end_time);
        $list=$tt->t_seller_student_new->tongji_last_revisite_time($log_time,$end_time);

        foreach ($list as $item ) {
            $tt->t_tongji_date->add($log_type,$log_time,$item["id"],$item["count"]);
        }
        $now=time(NULL);

        $tq_no_call_count=$tt->t_seller_student_new-> get_all_tq_no_call_count( $now-60*86400, $now );
        $tt->t_tongji_date->add(2,$log_time,0,$tq_no_call_count );
        //
    }
}
