<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class period_order_overdue_stop_send_wx extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:period_order_overdue_stop_send_wx';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每月19日逾期停课学生微信推送';

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
        $list = $task->t_period_repay_list->get_period_order_overdue_warning_info($due_date,3,-1,2);
        // dd($list);
        if(count($list)>0 && ($d>=19 || $d <=15)){
            foreach($list as $val){
                //微信推送家长
                $wx = new \App\Helper\Wx();
                $openid = $val["wx_openid"];
                $openid = "orwGAsxjW7pY7EM5JPPHpCY7X3GA";
                $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";

                $data=[
                    "first"    => "百度分期还款逾期停课通知",
                    "keyword1" => "百度分期还款逾期",
                    "keyword2" => "家长，您好！由于您未在指定日期内完成百度分期还款，即已发生逾期行为，现对您做出停课处理，为避免耽误孩子的学习，请尽快登录百度钱包完成还款，即可恢复正常上课！",
                    "keyword3" => date("Y-m-d H:i:s"),
                    "remark"   => "",
                ];
                $url="";


                if($openid){
                    $wx->send_template_msg($openid,$template_id,$data,$url);
                    $task->t_period_repay_list->field_update_list_2($val["orderid"],$val["period"],[
                        "stop_wx_send_flag"=>1
                    ]);

                }else{
                    $task->t_period_repay_list->field_update_list_2($val["orderid"],$val["period"],[
                        "stop_wx_send_flag"=>2
                    ]);

                }

                //已排未上课程删除
                $task->t_lesson_info_b3->del_lesson_no_start_by_userid($userid);


                //微信推送助教
                /*  $ass_oponid = $task->t_manager_info->get_wx_openid($val["uid"]);
                $ass_oponid = $task->t_manager_info->get_wx_openid(349);
                $account = $task->t_manager_info->get_account($val["uid"]);
                $data=[
                    "first"    => "百度分期还款逾期通知",
                    "keyword1" => "百度分期还款逾期",
                    "keyword2" => $account."老师，您好！您的".$val["nick"]."学员在使用百度分期产品的过程中，已发生逾期行为，请及时联系学员家长，及时告知登录百度钱包进行还款，以免造成停课处理，谢谢您的配合！",
                    "keyword3" => date("Y-m-d H:i:s"),
                    "remark"   => "",
                ];

                $wx->send_template_msg($ass_oponid,$template_id,$data,$url);*/


                // //微信推送销售
                // // $val["sys_operator"]="jack";
                // $uid = $task->t_manager_info->get_id_by_account($val["sys_operator"]);
                // $del_flag = $task->t_manager_info->get_del_flag($uid);
                // if($del_flag==1){
                //     //查找组长
                //     $uid_master = $task->t_admin_group_user->get_master_adminid_by_adminid($uid);
                //     $del_flag_master = $task->t_manager_info->get_del_flag($uid_master);
                //     if($del_flag_master==0 && $uid_master>0){
                //         $uid = $uid_master;
                //     }else{
                //         $uid_leader = $task->t_admin_group_user->get_main_master_adminid($uid);
                //         $del_flag_leader = $task->t_manager_info->get_del_flag($uid_leader);
                //         if($del_flag_leader==0 && $uid_leader>0){
                //             $uid = $uid_leader;
                //         }else{
                //             $uid=349;
                //         }
                //     }
                // }
                // $cc_oponid = $task->t_manager_info->get_wx_openid($uid);





                //dd($list);


                
            }
            
        }
 
       
        

    }
}
