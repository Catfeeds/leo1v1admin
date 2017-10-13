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
            $orderid = $val["child_orderid"];
            $data = $task->get_baidu_money_charge_pay_info($orderid);
            if($data["status"]==0 && isset($data["data"]) && is_array($data["data"])){
                $ret = $data["data"];
                foreach($ret as $item){
                    $period = $item["period"];
                    $is_exist = $task->t_period_repay_list->get_bid($orderid,$period);
                    if(!$is_exist){
                        if($item["bStatus"] != 48){
                            $item["paidTime"]=0; 
                        }
                        $task->t_period_repay_list->row_insert([
                            "orderid" =>$orderid,
                            "period"  =>$period,
                            "bid"     =>$item["bid"],
                            "b_status"=>$item["bStatus"],
                            "paid_time"=>$item["paidTime"],
                            "due_date" =>$item["dueDate"],
                            "money"    =>$item["money"],
                            "paid_money"=>$item["paidMoney"],
                            "un_paid_money"=>$item["unpaidMoney"]
                        ]);
                    }
                }
            }
            
        }
 
       
        

    }
}
