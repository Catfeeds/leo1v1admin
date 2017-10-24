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
        $list = $task->t_period_repay_list->get_period_order_overdue_warning_info($due_date,1);
        // dd($list);
        if(count($list)>0){
            foreach($list as $val){
                //微信推送家长
                $wx = new \App\Helper\Wx();
                // $openid = $val["wx_openid"];
                $openid = "orwGAsxjW7pY7EM5JPPHpCY7X3GA";
                $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";

                $data=[
                    "first"    => "百度分期还款逾期通知",
                    "keyword1" => "百度分期还款逾期",
                    "keyword2" => "家长，您好！由于您本月15日没有按时缴纳百度分期还款，即已发生逾期行为，建议尽快还款，避免出现停课情况，具体还款方式为：登录百度钱包APP进行还款，谢谢您的配合！",
                    "keyword3" => date("Y-m-d H:i:s"),
                    "remark"   => "",
                ];
                $url="";


                $wx->send_template_msg($openid,$template_id,$data,$url);
                dd($list);

                
            }
            
        }
 
       
        

    }
}
