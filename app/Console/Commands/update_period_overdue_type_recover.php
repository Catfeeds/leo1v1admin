<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class update_period_overdue_type_recover extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_period_overdue_type_recover';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '逾期预警/停课学员状态更新';

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
       
        //逾期预警学员
        //获取预警学员列表
        $warn_stu_list = $task->t_student_info->get_stu_detail_list_by_type(5);
        if(!empty($warn_stu_list)){
            foreach($warn_stu_list as $tt){
               
                $list = $task->t_period_repay_list->get_period_order_overdue_warning_info($due_date,3,5,-1,$tt["userid"]);
                if(count($list)>0){
                    $warn_num=0;
                    foreach($list as $val){
                        $orderid = $val["orderid"];
                        $data = $task->get_baidu_money_charge_pay_info($orderid);
                        if($data["status"]==0 && isset($data["data"]) && is_array($data["data"])){
                            $ret = $data["data"];
                            foreach($ret as $item){
                                $period = $item["period"];                       

                                if($due_date==$item["dueDate"] && $item["bStatus"]==48){
                                    $task->t_period_repay_list->field_update_list_2($orderid,$period,[
                                        "bid"     =>$item["bid"],
                                        "b_status"=>$item["bStatus"],
                                        "paid_time"=>$item["paidTime"],
                                        "due_date" =>$item["dueDate"],
                                        "money"    =>$item["money"],
                                        "paid_money"=>$item["paidMoney"],
                                        "un_paid_money"=>$item["unpaidMoney"],
                                        "repay_status" =>2
                                    ]);
                                    $warn_num++;

                                   
                                }
                            }
 
                        }
                        if(count($list)==$warn_num){
                            $userid = $tt["userid"];
                            $old_type= $task->t_student_info->get_type($userid);
                            $target_type_info = $task->t_student_type_change_list->get_info_by_userid_last($userid);
                            $target_type = $target_type_info["type_before"];
                            $task->t_student_info->get_student_type_update($userid,$target_type);
                            $task->t_student_type_change_list->row_insert([
                                "userid"    =>$userid,
                                "add_time"  =>time(),
                                "type_before" =>$old_type,
                                "type_cur"    =>$target_type,
                                "change_type" =>5,
                                "adminid"     =>0,
                                "reason"      =>"系统更新"
                            ]);
                            $task->t_manager_info->send_wx_todo_msg_by_adminid (349,"逾期预警状态变更","学员预警逾期状态变更通知",$userid."学生逾期预警已还款,状态已恢复","");
 
                        }
                    }
                }
            }
        }



        //逾期停课学员
        $stop_stu_list = $task->t_student_info->get_stu_detail_list_by_type(6);
        if(!empty($stop_stu_list)){
            foreach($stop_stu_list as $yy){            
                $ret_info = $task->t_period_repay_list->get_period_order_overdue_warning_info($due_date,-1,6,-1,$yy["userid"]);
                if(count($ret_info)>0){
                    $i=0;
                    $openid="";
                    $ass_uid=0;
                    foreach($ret_info as $val){
                        $orderid = $val["orderid"];
                        $openid = $val["wx_openid"];
                        $ass_uid = $val["uid"];
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
                                }elseif($item["bStatus"] == 144 || ($item["bStatus"] == 112 && $item["dueDate"] < strtotime(date("Y-m-d",time())))){
                                    $repay_status = 3;
                                }else{
                                    $repay_status = 0;
                                }
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
                                if($due_date>$item["dueDate"] && in_array($item["bStatus"],[112,144])){
                                    $i++;
                                }
                                if($due_date==$item["dueDate"] && in_array($item["bStatus"],[112,144])){
                                    $range_time = time()-$due_date;
                                    if($range_time>4*86400){
                                        $i++;
                                    }
                                }
                                              
                            }
                            
 
                        }
                

                              
                    }

                    if($i==0){
                        $userid = $yy["userid"];
                        //无逾期,变更状态
                        $target_type_info = $task->t_student_type_change_list->get_info_by_userid_type_before_last($userid);
                        $target_type = $target_type_info["type_before"];
                        $old_type= $task->t_student_info->get_type($userid);                       
                        $task->t_student_info->get_student_type_update($userid,$target_type);
                        $task->t_student_type_change_list->row_insert([
                            "userid"    =>$userid,
                            "add_time"  =>time(),
                            "type_before" =>$old_type,
                            "type_cur"    =>$target_type,
                            "change_type" =>5,
                            "adminid"     =>0,
                            "reason"      =>"系统更新"
                        ]);
                        $task->t_manager_info->send_wx_todo_msg_by_adminid (349,"逾期停课状态变更","学员逾期停课状态变更通知",$userid."学生逾期停课已还款,状态已恢复","");

                        //微信推送家长
                        $wx = new \App\Helper\Wx();
                        // $openid = "orwGAsxjW7pY7EM5JPPHpCY7X3GA";
                        $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";

                        $data=[
                            "first"    => "逾期停课恢复通知",
                            "keyword1" => "逾期停课恢复通知",
                            "keyword2" => "家长，您好！您的逾期停课处理已恢复，现您可以联系您的助教老师，确认上课安排，谢谢！",
                            "keyword3" => date("Y-m-d H:i:s"),
                            "remark"   => "",
                        ];
                        $url="";


                        $wx->send_template_msg($openid,$template_id,$data,$url);


                        //微信推送助教
                        $ass_oponid = $task->t_manager_info->get_wx_openid($ass_uid);
                        //$ass_oponid = $task->t_manager_info->get_wx_openid(349);
                        $account = $task->t_manager_info->get_account($ass_uid);
                        $nick = $task->t_student_info->get_nick($userid);
                        $data=[
                            "first"    => "逾期停课恢复通知",
                            "keyword1" => "逾期停课恢复通知",
                            "keyword2" => $account."老师，您好！您的".$nick."学员已完成逾期补缴款，即已解除停课处理，麻烦老师尽快联系家长，并为家长进行排课，谢谢！",
                            "keyword3" => date("Y-m-d H:i:s"),
                            "remark"   => "",
                        ];

                        $wx->send_template_msg($ass_oponid,$template_id,$data,$url);




                        //dd($list);





                            
                    }

            
                }
            }
        }
 
       
        

    }
}
