<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class get_period_repay_info extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get_period_repay_info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '百度分期合同还款信息生成';

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
                   
        $list = $task->t_child_order_info->get_period_list(1,"baidu");
        foreach($list as $val){
            $data = $task->get_baidu_money_charge_pay_info($val["child_orderid"]);
            
        }
 
       
        

    }
}
