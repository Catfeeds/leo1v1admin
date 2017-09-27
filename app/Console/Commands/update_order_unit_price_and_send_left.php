<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class update_order_unit_price_and_send_left extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_order_unit_price_and_send_left';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '合同课时单价更新以及退费后赠送合同更新';

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
        $ret = $task->t_order_refund->get_end_class_stu_order();
        foreach($ret as $l){           
            $task->t_order_info->field_update_list($l["so_orderid"],[
                "lesson_left"  =>0,
                "contract_status" =>3
            ]);
            $rt = $task->t_order_refund->check_order_is_refund($l["so_orderid"]);
            if($rt != 1){
                $task->t_order_refund->row_insert([
                    "userid"        => $l["userid"],
                    "orderid"       => $l["so_orderid"],
                    "should_refund" => $l["lesson_left"],
                ]);
            }

        }
        //dd($ret);
        $start_time = strtotime("2013-06-16");
        $end_time = time();
        $ret_info = $task->t_order_info->get_order_unit_price($start_time,$end_time);
        $list=[];
        foreach($ret_info as $val){
            @$list[$val["orderid"]][] = $val;
        }
        $ret = [];
        foreach($list as $k=>$v){
            $price=0; $lesson_count=0;$lesson_count_sub=0;$orderid_list=[1=>$k];
            foreach($v as $ss){
                $lesson_count= $ss["lesson_total"]*$ss["default_lesson_count"];
                $lesson_count_sub += $ss["lesson_total_sub"]*$ss["default_lesson_count_sub"];
                $orderid_list[] = $ss["orderid_sub"];
                $price = $ss["price"];
            }
            $lesson_count_all = $lesson_count+$lesson_count_sub;
            $unit_price = round($price/$lesson_count_all,2)*100;
            foreach($orderid_list as $item){
                if($item>0){
                    $task->t_order_info->field_update_list($item,[
                        "unit_price"  =>$unit_price 
                    ]);
                }
            }
        }

    }
}
