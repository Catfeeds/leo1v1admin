<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class notify_day_report extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:notify_day_report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var  \App\Console\Tasks\TaskController
     */
    public $task;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->task= new \App\Console\Tasks\TaskController();
    }

    public function get_day_data_from_time($name,$start_time)  {
        $end_time=$start_time+86400;

        $seller_count=$this->task->t_seller_month_money_target->get_seller_num_day($start_time);
        if ($seller_count==0) {
            $seller_count=1;
        }
        //新签人数
        $ret_arr=[
            "name" => $name. date("m-d", $start_time),
            "seller_count" => $seller_count,
        ];
        $order_arr=$this->task->t_order_info->seller_info( $start_time,$end_time);

        //呼出量
        $tq_arr=$this->task->t_tq_call_info->tongji_tq_info_all($start_time,$end_time);
        $tq_arr["tq_all_count_avg"] =  intval($tq_arr["tq_all_count"]/$seller_count);
        $tq_arr["tq_duration_count_avg"] =intval( $tq_arr["tq_duration_count"]/$seller_count);
        $tq_arr["tq_duration_count_avg_str"] =  \App\Helper\Common::get_time_format( $tq_arr["tq_duration_count_avg"]);



        //排课
        $set_lesson_arr=$this->task->t_test_lesson_subject_sub_list->get_set_lesson_count_info($start_time,$end_time);
        //试听课
        $test_lesson_arr= $this->task->t_test_lesson_subject_require->tongji_test_lesson_all($start_time,$end_time);
        $ret_arr=array_merge($ret_arr, $order_arr,$tq_arr,$set_lesson_arr,$test_lesson_arr );

        return $ret_arr;
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $opt_date=date("Y-m-d",time(NULL)-86400);
        $l2_opt_date=date("Y-m-d",time(NULL)-86400*2);



        $data= $this->get_day_data_from_time("本日", strtotime($opt_date) );
        echo "111\n";


        $order_money=$data["order_money"];
        $order_user_count=$data["order_user_count"];
        $no_pay_order_money=intval($data["no_pay_order_money"]);
        $no_pay_order_user_count=$data["no_pay_order_user_count"];

        $test_lesson_count=$data["test_lesson_count"];
        $test_lesson_fail_percent=$data["test_lesson_fail_percent"];


        $l2_data= $this->get_day_data_from_time("", strtotime( $l2_opt_date ) );

        $l2_order_money=$l2_data["order_money"];
        $l2_order_user_count=$l2_data["order_user_count"];
        $l2_no_pay_order_money=intval( $l2_data["no_pay_order_money"]);
        $l2_no_pay_order_user_count=$l2_data["no_pay_order_user_count"];

        echo "22222\n";
        $admin_list=["jim","xixi","louis"] ;
        $admin_list=["jim"] ;
        foreach ( $admin_list as $account ) {
            $this->task->t_manager_info->send_wx_todo_msg(
                $account, "系统日报",
                "(前天)$l2_opt_date 新签\n确认   $l2_order_money 元/$l2_order_user_count 个 \n 未确认 $l2_no_pay_order_money 元/$l2_no_pay_order_user_count 个\n ".
                "\n (昨天)$opt_date 新签\n确认   $order_money 元/$order_user_count 个 \n 未确认 $no_pay_order_money 元/$no_pay_order_user_count 个 "
                ,"试听数:$test_lesson_count , 试听失败率: $test_lesson_fail_percent%","/tongji_ss/day_report?start_time=$opt_date");

        }
        echo "33333\n";
    }
}
