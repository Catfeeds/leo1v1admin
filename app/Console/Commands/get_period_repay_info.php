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
    protected $description = '百度分期合同还款信息更新';

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
        if($d>13){            
            $month_start = strtotime(date("Y-m-01",time()));
            $due_date = $month_start+14*86400;
        }else{
            $last_month = strtotime("-1 month",time());
            $month_start = strtotime(date("Y-m-01",$last_month));
            $due_date = $month_start+14*86400;

        }
        $list = $task->t_period_repay_list->get_repay_order_info($due_date);
        foreach($list as $val){
            $orderid = $val["orderid"];
            $data = $task->get_baidu_money_charge_pay_info($orderid);
            if($data["status"]==0 && isset($data["data"]) && is_array($data["data"])){
                $ret = $data["data"];
                foreach($ret as $item){
                    $period = $item["period"];
                    if($item["bStatus"] != 48){
                        $item["paidTime"]=0; 
                    }
                    if($item["bStatus"] == 48 && $item["paidTime"]>$item["paidTime"]){
                        $repay_status = 2;
                    }elseif($item["bStatus"] == 48 && $item["paidTime"]<=$item["paidTime"]){
                        $repay_status = 1;
                    }elseif($item["bStatus"] == 144){
                        $repay_status = 3;
                    }else{
                        $repay_status = 0;
                    }

                    $is_exist = $task->t_period_repay_list->get_bid($orderid,$period);
                    if(!$is_exist){
                        $task->t_period_repay_list->row_insert([
                            "orderid" =>$orderid,
                            "period"  =>$period,
                            "bid"     =>$item["bid"],
                            "b_status"=>$item["bStatus"],
                            "paid_time"=>$item["paidTime"],
                            "due_date" =>$item["dueDate"],
                            "money"    =>$item["money"],
                            "paid_money"=>$item["paidMoney"],
                            "un_paid_money"=>$item["unpaidMoney"],
                            "repay_status" =>$repay_status
                        ]);
                    }else{
                        $task->t_period_repay_list->field_update_list_2($orderid,$period,[
                            "bid"     =>$item["bid"],
                            "b_status"=>$item["bStatus"],
                            "paid_time"=>$item["paidTime"],
                            "due_date" =>$item["dueDate"],
                            "money"    =>$item["money"],
                            "paid_money"=>$item["paidMoney"],
                            "un_paid_money"=>$item["unpaidMoney"],
                            "repay_status" =>$repay_status
                        ]);
                    }
                }
            }
            
        }
 
       
        

    }
}
