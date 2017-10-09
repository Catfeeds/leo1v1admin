<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use \App\Enums as E;
class gen_top_info extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:gen_top_info {--month=}';

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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {



        $month=$this->option('month');
        if ($month) {
            $start_time=strtotime( date("Y-m-01", strtotime( $month)) );
            $end_time= strtotime("+1 month",  $start_time );
            echo "do $start_time, $end_time \n";

        }else{
            $now=time(NULL);
            $start_time=strtotime( date("Y-m-01",$now));
            $end_time=$now;
        }
		$group_start_time=$start_time;
		if( $start_time  ==  strtotime("2017-10-01")  ) {
			$start_time  = strtotime("2017-10-03");
		}
		if( $start_time  ==  strtotime("2017-09-01")  ) {
			$end_time = strtotime("2017-10-03");
		}

        $this->task->t_order_info->switch_tongji_database();
        $this->task->t_test_lesson_subject_require->switch_tongji_database();

        //
        $tongji_type= E\Etongji_type::V_SELLER_MONTH_REQUIRE_COUNT;
        $list= $this->task->t_test_lesson_subject_require->tongji_require_count($start_time,$end_time);
        $this->task->t_tongji_seller_top_info->update_list($tongji_type,$group_start_time,$list);

        //
        $tongji_type= E\Etongji_type::V_SELLER_MONTH_SUCC_TEST_LESSON_COUNT;
        $test_lesson_succ_list=$this->task->t_test_lesson_subject_require->tongji_test_lesson_succ_count($start_time,$end_time);
        $this->task->t_tongji_seller_top_info->update_list($tongji_type,$group_start_time,$test_lesson_succ_list);

        //
        $tongji_type= E\Etongji_type::V_SELLER_MONTH_ORDER_COUNT;
        $order_count_list= $this->task->t_order_info->tongji_seller_order_count($start_time,$end_time);
        $this->task->t_tongji_seller_top_info->update_list($tongji_type,$group_start_time,$order_count_list);

        //
        $tongji_type= E\Etongji_type::V_SELLER_MONTH_ORDER_PERCENT;

        $order_count_map=[];
        foreach ($order_count_list as $item) {
            $order_count_map[$item["adminid"]]=$item["value"];
        }


        $order_percent_list=[];
        foreach( $test_lesson_succ_list as $item ) {
            $adminid=$item["adminid"];
            if ($item["value"]  >=10 ) {
                $order_percent_list[]=[
                    "adminid" =>$adminid,
                    "value" => ((@$order_count_map[$adminid])/$item["value"]) *100,
                ];
            }
        }
        \App\Helper\Utils::order_list($order_percent_list,"value",false);
        $this->task->t_tongji_seller_top_info->update_list($tongji_type,$group_start_time,$order_percent_list);
        $tongji_type= E\Etongji_type::V_SELLER_MONTH_ORDER_MONEY;
        $order_money_list=[];
        foreach($order_count_list as $item) {
            $adminid=$item["adminid"];
            $order_money_list[]=[
                "adminid" =>$adminid,
                "value" => $item["money"] ,
            ];
        }
        \App\Helper\Utils::order_list($order_money_list,"value",false);
        $this->task->t_tongji_seller_top_info->update_list($tongji_type,$group_start_time,$order_money_list);
        /*$tongji_type= E\Etongji_type::V_SELLER_MONTH_ASSIGN_COUNT;
        $assign_count_list = $this->task->t_seller_student_new->tongji_assign_count_list($start_time,$end_time);
        $this->task->t_tongji_seller_top_info->update_list($tongji_type,$group_start_time,$assign_count_list);
        $tongji_type= E\Etongji_type::V_SELLER_MONTH_LESSON_PLAN;
        $lesson_count_list = $this->task->t_test_lesson_subject_sub_list->tongji_lesson_count_list($start_time,$end_time);
        $this->task->t_tongji_seller_top_info->update_list($tongji_type,$group_start_time,$lesson_count_list);*/
        $tongji_type= E\Etongji_type::V_SELLER_MONTH_ORDER_PERSON_COUNT;
        $order_person_count_list= $this->task->t_order_info->tongji_seller_order_person_count($start_time,$end_time);
        $this->task->t_tongji_seller_top_info->update_list($tongji_type,$group_start_time,$order_person_count_list);

        $tongji_type= E\Etongji_type::V_SELLER_MONTH_FAIL_LESSON_PERCENT;
        $test_lesson_list=$this->task->t_test_lesson_subject_require->tongji_test_lesson_group_by_admin_revisiterid($start_time,$end_time );
        $test_lesson_fail_per = $test_lesson_list["list"];
        $test_lesson_all_count= [] ;
        $test_lesson_fail_count= [] ;
        foreach($test_lesson_fail_per as &$item){
            $adminid=$item["admin_revisiterid"];
            $item["adminid"] = $adminid ;
            if($item['test_lesson_count'] != 0){
                $item['value'] = round($item['fail_all_count']/$item['test_lesson_count'],2)*100;
            }else{
                $item['value']=0;
            }
            $test_lesson_all_count[]= [ "adminid" =>$adminid , "value"=> $item['test_lesson_count']  ] ;
            $test_lesson_fail_count[]= [ "adminid" =>$adminid , "value"=> $item['fail_all_count']  ] ;
        }
        \App\Helper\Utils::order_list($test_lesson_fail_per,"value",1);
        \App\Helper\Utils::order_list($test_lesson_fail_count,"value",1);
        \App\Helper\Utils::order_list($test_lesson_all_count,"value",1);

        $this->task->t_tongji_seller_top_info->update_list($tongji_type,$group_start_time,$test_lesson_fail_per);
        $this->task->t_tongji_seller_top_info->update_list(
            E\Etongji_type::V_SELLER_MONTH_FAIL_LESSON_COUNT
            ,$group_start_time,$test_lesson_fail_count);
        $this->task->t_tongji_seller_top_info->update_list(
            E\Etongji_type::V_SELLER_MONTH_ALL_LESSON_COUNT,
            $group_start_time,$test_lesson_all_count);

        //dd($test_lesson_fail_per);
        $this->update_week_test_lesson_fail($group_start_time);
    }

    public function update_week_test_lesson_fail($group_start_time){
        $time = strtotime(date('Y-m-d',time()).'00:00:00');
        $week = date('w',$time);
        if($week == 0){
            $week = 7;
        }elseif($week == 1){
            $week = 8;
        }
        $end_time = $time-3600*24*($week-2);
        $start_time = $end_time-3600*24*7;
        $this->task->t_order_info->switch_tongji_database();
        $this->task->t_test_lesson_subject_require->switch_tongji_database();
        $tongji_type= E\Etongji_type::V_SELLER_WEEK_FAIL_LESSON_PERCENT;
        $test_lesson_list=$this->task->t_test_lesson_subject_require->tongji_test_lesson_group_by_admin_revisiterid($start_time,$end_time );
        $test_lesson_fail_per = $test_lesson_list["list"];
        $test_lesson_all_count= [] ;
        $test_lesson_fail_count= [] ;
        foreach($test_lesson_fail_per as &$item){
            $adminid=$item["admin_revisiterid"];
            $item["adminid"] = $adminid ;
            if($item['test_lesson_count'] != 0){
                $item['value'] = round($item['fail_all_count']/$item['test_lesson_count'],2)*100;
            }else{
                $item['value']=0;
            }
            $test_lesson_all_count[]= [ "adminid" =>$adminid , "value"=> $item['test_lesson_count']  ] ;
            $test_lesson_fail_count[]= [ "adminid" =>$adminid , "value"=> $item['fail_all_count']  ] ;
        }
        \App\Helper\Utils::order_list($test_lesson_fail_per,"value",1);
        \App\Helper\Utils::order_list($test_lesson_fail_count,"value",1);
        \App\Helper\Utils::order_list($test_lesson_all_count,"value",1);
        $this->task->t_tongji_seller_top_info->update_list($tongji_type,$group_start_time,$test_lesson_fail_per);
        $this->task->t_tongji_seller_top_info->update_list(
            E\Etongji_type::V_SELLER_WEEK_FAIL_LESSON_COUNT
            ,$group_start_time,$test_lesson_fail_count);
        $this->task->t_tongji_seller_top_info->update_list(
            E\Etongji_type::V_SELLER_WEEK_ALL_LESSON_COUNT,
            $group_start_time,$test_lesson_all_count);
    }
}
