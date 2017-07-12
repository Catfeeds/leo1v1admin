<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ass_stu_warning_renw_info_update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ass_stu_warning_renw_info_update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '助教预警学员续费后信息更新';

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
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();
        $time = time();
        $date_week = \App\Helper\Utils::get_week_range($time,1);
        $lstart = $date_week["sdate"];
        $lend = $date_week["edate"];
        $ret_info    = $task->t_month_ass_warning_student_info->get_ass_stu_warning_info($lstart);
    
        foreach($ret_info as $item){
            $price=  $task->t_order_info->check_order_info_new($item["userid"],$item["account"],$lstart,$lend);
            if($price){
                $task->t_month_ass_warning_student_info->field_update_list_2($item["userid"],$lstart,[
                    "ass_renw_flag"     =>1,
                    "master_renw_flag"  =>1,
                    "renw_price"        =>$price
                ]);
            }            

        }

        // dd($ass_list);
       
              
    }
}
