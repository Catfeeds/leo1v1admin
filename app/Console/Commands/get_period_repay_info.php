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
        if($d>15){            
            $month_start = strtotime(date("Y-m-01",time()));
            $due_date = $month_start+14*86400;
        }else{
            $last_month = strtotime("-1 month",time());
            $month_start = strtotime(date("Y-m-01",$last_month));
            $due_date = $month_start+14*86400;

        }

       
        $list = $task->t_period_repay_list->get_repay_order_info($due_date);
        if(count($list)>0){
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
                        if($item["bStatus"] == 48 && $item["paidTime"]>$item["dueDate"]){
                            $repay_status = 2;
                        }elseif($item["bStatus"] == 48 && $item["paidTime"]<=$item["dueDate"]){
                            $repay_status = 1;
                        }elseif($item["bStatus"] == 144 || ($item["bStatus"] == 112 && $item["dueDate"] < time())){
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

                        if($d==16 && $due_date==$item["dueDate"] && in_array($item["bStatus"],[112,144])){
                            $parent_orderid= $task->t_child_order_info->get_parent_orderid($orderid);
                            $userid = $task->t_order_info->get_userid($parent_orderid);
                            $old_type= $task->t_student_info->get_type($userid);
                            if($old_type != 6){
                                $task->t_student_info->get_student_type_update($userid,5);
                                $task->t_student_type_change_list->row_insert([
                                    "userid"    =>$userid,
                                    "add_time"  =>time(),
                                    "type_before" =>$old_type,
                                    "type_cur"    =>0,
                                    "change_type" =>5,
                                    "adminid"     =>0,
                                    "reason"      =>"系统更新"
                                ]);
                                $task->t_manager_info->send_wx_todo_msg_by_adminid (349,"逾期预警","学员预警逾期通知",$userid."学生逾期未还款,状态已变更为预警逾期","");
                            }

                        }

                        if($d==19 &&  $due_date==$item["dueDate"] && in_array($item["bStatus"],[112,144])){
                            $parent_orderid= $task->t_child_order_info->get_parent_orderid($orderid);
                            $userid = $task->t_order_info->get_userid($parent_orderid);
                            $old_type= $task->t_student_info->get_type($userid);
                            if($period==1){
                                if($old_type != 6){
                                    $task->t_student_info->get_student_type_update($userid,6);
                                    $task->t_student_type_change_list->row_insert([
                                        "userid"    =>$userid,
                                        "add_time"  =>time(),
                                        "type_before" =>$old_type,
                                        "type_cur"    =>0,
                                        "change_type" =>6,
                                        "adminid"     =>0,
                                        "reason"      =>"系统更新"
                                    ]);
                                    $task->t_manager_info->send_wx_todo_msg_by_adminid (349,"逾期停课","学员预警停课通知",$userid."学生逾期未还款,状态已变更为预警停课","");
                                
                                }
                            }
 
                        }
                        
                    }
                }
            }
            
        }

        //非首次逾期学员判断
        if($d>=19 || $d <=15){
            $no_first_list = $task->t_period_repay_list->get_no_first_overdue_repay_list($due_date);
            foreach($no_first_list as $v){
                $orderid = $v["orderid"];
                $userid = $v["userid"];
                $check_overdue_history = $task->t_period_repay_list->check_overdue_history_flag($due_date,$orderid);
                if($check_overdue_history){
                    $old_type= $task->t_student_info->get_type($userid);
                   
                    $task->t_student_info->get_student_type_update($userid,6);
                    $task->t_student_type_change_list->row_insert([
                        "userid"    =>$userid,
                        "add_time"  =>time(),
                        "type_before" =>$old_type,
                        "type_cur"    =>0,
                        "change_type" =>6,
                        "adminid"     =>0,
                        "reason"      =>"系统更新"
                    ]);
                    $task->t_manager_info->send_wx_todo_msg_by_adminid (349,"逾期停课","学员预警停课通知",$userid."学生逾期未还款,状态已变更为预警停课","");
                                                       
 
                }else{
                    $period_info = $task->t_child_order_info->get_period_info_by_userid($userid,$orderid);
                    if(!empty($period_info)){
                     

                        //已还款金额
                        $pay_price=$task->t_period_repay_list->get_paid_money_all($orderid);
                    
                        //已支付金额
                        $pay_price +=$period_info["price"]-$period_info["period_price"];

                        //课时单价
                        $per_price = $period_info["discount_price"]/$period_info["default_lesson_count"]/$period_info["lesson_total"];

                        $parent_orderid= $task->t_child_order_info->get_parent_orderid($orderid);
                        //已消耗课时
                        $order_use =  $period_info["default_lesson_count"]*$period_info["lesson_total"]-$period_info["lesson_left"];

                        //得到合同消耗课次段折扣
                        $discount_per = $task->get_order_lesson_discount_per($parent_orderid,$order_use);
                        $money_use = $per_price*$order_use*$discount_per;
                        $money_contrast = ($money_use-$pay_price)/100;
                        $day_start = strtotime(date("Y-m-d",time()));
                        if($money_contrast>=1){
                            $old_type= $task->t_student_info->get_type($userid);
                   
                            $task->t_student_info->get_student_type_update($userid,6);
                            $task->t_student_type_change_list->row_insert([
                                "userid"    =>$userid,
                                "add_time"  =>time(),
                                "type_before" =>$old_type,
                                "type_cur"    =>0,
                                "change_type" =>6,
                                "adminid"     =>0,
                                "reason"      =>"系统更新"
                            ]);
                            $task->t_manager_info->send_wx_todo_msg_by_adminid (349,"逾期停课","学员预警停课通知",$userid."学生逾期未还款,状态已变更为预警停课","");
 
                        }elseif($money_contrast>0 && $money_contrast<1){
                            //判断当天有无课程
                            //  $plan_lesson_count = $task->t_lesson_info_b3->get_lesson_count_sum($userid,$day_start,$lesson_start);
                            $first_lesson_start = $task->t_lesson_info_b3->get_first_lesson_start($userid,$day_start);
                            if($first_lesson_start>0){
                                //删除之后的课
                                $task->t_lesson_info_b3->delete_lesson_by_time_userid($userid,$first_lesson_start);
                            }
                            //已排超出课是否要清除,待确认
                        
                        }else{
                            //可排课量
                            $left_plan_count = floor(($pay_price-$money_use)/($per_price*$discount_per*100));

                            //已排课量
                            $plan_lesson_count = $task->t_lesson_info_b3->get_lesson_count_sum($userid,$day_start,0);
                            $plan_lesson_count = $plan_lesson_count/100;

                            //两者比较,若已排课量超出,则清除超出部分
                            if( $left_plan_count<$plan_lesson_count){                          
                                $lesson_list = $task->t_lesson_info_b3->get_lesson_count_list_new($userid,$day_start,0);
                                $first_lesson_start=0;
                                $lesson_count_total=0;
                                foreach($lesson_list as $var){
                                    if($lesson_count_total>=($left_plan_count*100)){
                                        break;
                                    }
                                    $lesson_count_total +=$var["lesson_count"];
                                    $first_lesson_start = $var["lesson_start"];
                                }
                                //删除之后的课
                                $task->t_lesson_info_b3->delete_lesson_by_time_userid($userid,$first_lesson_start);

                            
                            }
                        
                        
                        }
                    }
                    


 
                }
            }
        }
 
       
        

    }
}
