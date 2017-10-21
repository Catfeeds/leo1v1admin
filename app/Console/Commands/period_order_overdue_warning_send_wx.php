<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class period_order_overdue_warning_send_wx extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:period_order_overdue_warning_send_wx';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每月16日逾期预警学生微信推送';

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
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();        
        $d= date("d");
        if($d>15){            
            $month_start = strtotime(date("Y-m-01",time()));
            $due_date = $month_start+14*86400;
        }else{
            $last_month = strtotime("-1 month",time());
            $month_start = strtotime(date("Y-m-01",$last_month));
            $due_date = $month_start+14*86400;

        }
        $list = $task->t_period_repay_list->get_period_order_overdue_warning_info($due_date,3);
        if(count($list)>0){
            foreach($list as $val){
                //微信推送家长
                
            }
            
        }
 
       
        

    }
}
