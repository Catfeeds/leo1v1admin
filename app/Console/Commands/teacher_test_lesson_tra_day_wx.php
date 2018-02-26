<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class teacher_test_lesson_tra_day_wx extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:teacher_test_lesson_tra_day_wx';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '各学科试听转化率推送';

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

        $start_time_ave = time()-30*86400;
        $res = $task->t_lesson_info->get_all_test_order_info_by_time($start_time_ave);
        $num = 0;
        $arr = 0;
        foreach($res as $item){
            if($item["orderid"]>0 && $item["order_time"]>0 && $item["lesson_start"]>0){
                $num++;
                $arr += ($item["order_time"]-$item["lesson_start"]);
            }
        }

        if($num!=0){
            $day_num = round($arr/$num/86400,0);
        }else{
            $day_num = 0;
        }

        $start_time = strtotime(date("Y-m-01",time()));
        $end_time = strtotime(date("Y-m-d",time()-$day_num*86400));
        //$ret_info = $task->t_lesson_info->tongji_teacher_test_lesson_info_list($start_time,$end_time);


        //整体转化率
        $all = $zh= [];
        $test_person_num= $task->t_lesson_info->get_teacher_test_person_num_list_subject( $start_time,$end_time);
        foreach($test_person_num as $item){
            @$all["person_num"] +=$item["person_num"];
            @$all["have_order"] +=$item["have_order"];
            if($item["subject"]==4 && $item["subject"] >5){
                 @$zh["person_num"] +=$item["person_num"];
                 @$zh["have_order"] +=$item["have_order"];
            }
        }
        $all["order_per"] = (!empty($all["person_num"])?round($all["have_order"]/$all["person_num"],3)*100:0)."%";
        $zh["order_per"] = (!empty($zh["person_num"])?round($zh["have_order"]/$zh["person_num"],3)*100:0)."%";
        $yw["order_per"] = (!empty($test_person_num[1]["person_num"])?round($test_person_num[1]["have_order"]/$test_person_num[1]["person_num"],3)*100:0)."%";
        $sx["order_per"] = (!empty($test_person_num[2]["person_num"])?round($test_person_num[2]["have_order"]/$test_person_num[2]["person_num"],3)*100:0)."%";
        $yy["order_per"] = (!empty($test_person_num[3]["person_num"])?round($test_person_num[3]["have_order"]/$test_person_num[3]["person_num"],3)*100:0)."%";
        $wl["order_per"] = (!empty($test_person_num[5]["person_num"])?round($test_person_num[5]["have_order"]/$test_person_num[5]["person_num"],3)*100:0)."%";

        $kk_test_person_num= $task->t_lesson_info->get_kk_teacher_test_person_num_list_subject( $start_time,$end_time);
        //dd($kk_test_person_num);
        foreach($kk_test_person_num as $item){
            @$all["kk_num"] +=$item["kk_num"];
            @$all["kk_order"] +=$item["kk_order"];
            if($item["subject"]==4 && $item["subject"] >5){
                @$zh["kk_num"] +=$item["kk_num"];
                @$zh["kk_order"] +=$item["kk_order"];
            }
        }
        $all["kk_per"] = (!empty($all["kk_num"])?round($all["kk_order"]/$all["kk_num"],3)*100:0)."%";
        $zh["kk_per"] = (!empty($zh["kk_num"])?round($zh["kk_order"]/$zh["kk_num"],3)*100:0)."%";
        $yw["kk_per"] = (!empty($kk_test_person_num[1]["kk_num"])?round($kk_test_person_num[1]["kk_order"]/$kk_test_person_num[1]["kk_num"],3)*100:0)."%";
        $sx["kk_per"] = (!empty($kk_test_person_num[2]["kk_num"])?round($kk_test_person_num[2]["kk_order"]/$kk_test_person_num[2]["kk_num"],3)*100:0)."%";
        $yy["kk_per"] = (!empty($kk_test_person_num[3]["kk_num"])?round($kk_test_person_num[3]["kk_order"]/$kk_test_person_num[3]["kk_num"],3)*100:0)."%";
        $wl["kk_per"] = (!empty($kk_test_person_num[5]["kk_num"])?round($kk_test_person_num[5]["kk_order"]/$kk_test_person_num[5]["kk_num"],3)*100:0)."%";


        $change_test_person_num= $task->t_lesson_info->get_change_teacher_test_person_num_list_subject( $start_time,$end_time);
        foreach($change_test_person_num as $item){
            @$all["change_num"] +=$item["change_num"];
            @$all["change_order"] +=$item["change_order"];
            if($item["subject"]==4 && $item["subject"] >5){
                @$zh["change_num"] +=$item["change_num"];
                @$zh["change_order"] +=$item["change_order"];
            }
        }
        $all["change_per"] = (!empty($all["change_num"])?round($all["change_order"]/$all["change_num"],3)*100:0)."%";
        $zh["change_per"] = (!empty($zh["change_num"])?round($zh["change_order"]/$zh["change_num"],3)*100:0)."%";
        $yw["change_per"] = (!empty($change_test_person_num[1]["change_num"])?round($change_test_person_num[1]["change_order"]/$change_test_person_num[1]["change_num"],3)*100:0)."%";
        $sx["change_per"] = (!empty($change_test_person_num[2]["change_num"])?round($change_test_person_num[2]["change_order"]/$change_test_person_num[2]["change_num"],3)*100:0)."%";
        $yy["change_per"] = (!empty($change_test_person_num[3]["change_num"])?round($change_test_person_num[3]["change_order"]/$change_test_person_num[3]["change_num"],3)*100:0)."%";
        $wl["change_per"] = (!empty($change_test_person_num[5]["change_num"])?round($change_test_person_num[5]["change_order"]/$change_test_person_num[5]["change_num"],3)*100:0)."%";



        //$success_test_lesson_list = $task->t_lesson_info->get_success_test_lesson_list_new_subject($start_time,$end_time);
        //349 江惠朋，72 刘亚辉
        $admin_arr2=[349,72];
        foreach($admin_arr2 as $vv){
            $task->t_manager_info->send_wx_todo_msg_by_adminid ($vv,"理优监课组","每日各学科试听转化率","本月签单率:".$all["order_per"].",扩课签单率:".$all["kk_per"].",换老师签单率:".$all["change_per"].";其中语文签单率:".$yw["order_per"].",扩课签单率:".$yw["kk_per"].",换老师签单率:".$yw["change_per"].";数学签单率:".$sx["order_per"].",扩课签单率:".$sx["kk_per"].",换老师签单率:".$sx["change_per"].";英语签单率:".$yy["order_per"].",扩课签单率:".$yy["kk_per"].",换老师签单率:".$yy["change_per"].";物理签单率:".$wl["order_per"].",扩课签单率:".$wl["kk_per"].",换老师签单率:".$wl["change_per"].";小学科签单率:".$zh["order_per"].",扩课签单率:".$zh["kk_per"].",换老师签单率:".$zh["change_per"],"");
        }
        $list = $task->t_admin_main_group_name->get_group_list (4);

        $task->t_manager_info->send_wx_todo_msg_by_adminid (793,"理优监课组","物理每日试听转化率","签单率:".$wl["order_per"].",扩课签单率:".$wl["kk_per"].",换老师签单率:".$wl["change_per"],"");
        foreach($list as $item){
            if($item["group_name"]=="文综组"){
                $task->t_manager_info->send_wx_todo_msg_by_adminid ($item["master_adminid"],"理优监课组","语文每日试听转化率","签单率:".$yw["order_per"].",扩课签单率:".$yw["kk_per"].",换老师签单率:".$yw["change_per"],"");
                $task->t_manager_info->send_wx_todo_msg_by_adminid ($item["master_adminid"],"理优监课组","英语每日试听转化率","签单率:".$yy["order_per"].",扩课签单率:".$yy["kk_per"].",换老师签单率:".$yy["change_per"],"");

            }elseif($item["group_name"]=="数学组"){
                $task->t_manager_info->send_wx_todo_msg_by_adminid ($item["master_adminid"],"理优监课组","数学每日试听转化率","签单率:".$sx["order_per"].",扩课签单率:".$sx["kk_per"].",换老师签单率:".$sx["change_per"],"");
                $task->t_manager_info->send_wx_todo_msg_by_adminid (754,"理优监课组","数学每日试听转化率","签单率:".$sx["order_per"].",扩课签单率:".$sx["kk_per"].",换老师签单率:".$sx["change_per"],"");

            }elseif($item["group_name"]=="小学科"){
                $task->t_manager_info->send_wx_todo_msg_by_adminid ($item["master_adminid"],"理优监课组","小学科每日试听转化率","签单率:".$zh["order_per"].",扩课签单率:".$zh["kk_per"].",换老师签单率:".$zh["change_per"],""); 
            }

        }



        //整体转化率(兼职老师)
        $all = $zh= [];
        $test_person_num= $task->t_lesson_info->get_teacher_test_person_num_list_subject( $start_time,$end_time,0);
        foreach($test_person_num as $item){
            @$all["person_num"] +=$item["person_num"];
            @$all["have_order"] +=$item["have_order"];
            if($item["subject"]==4 && $item["subject"] >5){
                @$zh["person_num"] +=$item["person_num"];
                @$zh["have_order"] +=$item["have_order"];
            }
        }
        $all["order_per"] = (!empty($all["person_num"])?round($all["have_order"]/$all["person_num"],3)*100:0)."%";
        $zh["order_per"] = (!empty($zh["person_num"])?round($zh["have_order"]/$zh["person_num"],3)*100:0)."%";
        $yw["order_per"] = (!empty($test_person_num[1]["person_num"])?round($test_person_num[1]["have_order"]/$test_person_num[1]["person_num"],3)*100:0)."%";
        $sx["order_per"] = (!empty($test_person_num[2]["person_num"])?round($test_person_num[2]["have_order"]/$test_person_num[2]["person_num"],3)*100:0)."%";
        $yy["order_per"] = (!empty($test_person_num[3]["person_num"])?round($test_person_num[3]["have_order"]/$test_person_num[3]["person_num"],3)*100:0)."%";
        $wl["order_per"] = (!empty($test_person_num[5]["person_num"])?round($test_person_num[5]["have_order"]/$test_person_num[5]["person_num"],3)*100:0)."%";

        $kk_test_person_num= $task->t_lesson_info->get_kk_teacher_test_person_num_list_subject( $start_time,$end_time,0);
        //dd($kk_test_person_num);
        foreach($kk_test_person_num as $item){
            @$all["kk_num"] +=$item["kk_num"];
            @$all["kk_order"] +=$item["kk_order"];
            if($item["subject"]==4 && $item["subject"] >5){
                @$zh["kk_num"] +=$item["kk_num"];
                @$zh["kk_order"] +=$item["kk_order"];
            }
        }
        $all["kk_per"] = (!empty($all["kk_num"])?round($all["kk_order"]/$all["kk_num"],3)*100:0)."%";
        $zh["kk_per"] = (!empty($zh["kk_num"])?round($zh["kk_order"]/$zh["kk_num"],3)*100:0)."%";
        $yw["kk_per"] = (!empty($kk_test_person_num[1]["kk_num"])?round($kk_test_person_num[1]["kk_order"]/$kk_test_person_num[1]["kk_num"],3)*100:0)."%";
        $sx["kk_per"] = (!empty($kk_test_person_num[2]["kk_num"])?round($kk_test_person_num[2]["kk_order"]/$kk_test_person_num[2]["kk_num"],3)*100:0)."%";
        $yy["kk_per"] = (!empty($kk_test_person_num[3]["kk_num"])?round($kk_test_person_num[3]["kk_order"]/$kk_test_person_num[3]["kk_num"],3)*100:0)."%";
        $wl["kk_per"] = (!empty($kk_test_person_num[5]["kk_num"])?round($kk_test_person_num[5]["kk_order"]/$kk_test_person_num[5]["kk_num"],3)*100:0)."%";


        $change_test_person_num= $task->t_lesson_info->get_change_teacher_test_person_num_list_subject( $start_time,$end_time,0);
        //dd($change_test_person_num);
        foreach($change_test_person_num as $item){
            @$all["change_num"] +=$item["change_num"];
            @$all["change_order"] +=$item["change_order"];
            if($item["subject"]==4 && $item["subject"] >5){
                @$zh["change_num"] +=$item["change_num"];
                @$zh["change_order"] +=$item["change_order"];
            }
        }
        $all["change_per"] = (!empty($all["change_num"])?round($all["change_order"]/$all["change_num"],3)*100:0)."%";
        $zh["change_per"] = (!empty($zh["change_num"])?round($zh["change_order"]/$zh["change_num"],3)*100:0)."%";
        $yw["change_per"] = (!empty($change_test_person_num[1]["change_num"])?round($change_test_person_num[1]["change_order"]/$change_test_person_num[1]["change_num"],3)*100:0)."%";
        $sx["change_per"] = (!empty($change_test_person_num[2]["change_num"])?round($change_test_person_num[2]["change_order"]/$change_test_person_num[2]["change_num"],3)*100:0)."%";
        $yy["change_per"] = (!empty($change_test_person_num[3]["change_num"])?round($change_test_person_num[3]["change_order"]/$change_test_person_num[3]["change_num"],3)*100:0)."%";
        $wl["change_per"] = (!empty($change_test_person_num[5]["change_num"])?round($change_test_person_num[5]["change_order"]/$change_test_person_num[5]["change_num"],3)*100:0)."%";



        //$success_test_lesson_list = $task->t_lesson_info->get_success_test_lesson_list_new_subject($start_time,$end_time);

        $admin_arr2=[349,72,1171];
        foreach($admin_arr2 as $vv){
            $task->t_manager_info->send_wx_todo_msg_by_adminid ($vv,"理优监课组","每日兼职试听转化率","本月签单率:".$all["order_per"].",扩课签单率:".$all["kk_per"].",换老师签单率:".$all["change_per"].";其中语文签单率:".$yw["order_per"].",扩课签单率:".$yw["kk_per"].",换老师签单率:".$yw["change_per"].";数学签单率:".$sx["order_per"].",扩课签单率:".$sx["kk_per"].",换老师签单率:".$sx["change_per"].";英语签单率:".$yy["order_per"].",扩课签单率:".$yy["kk_per"].",换老师签单率:".$yy["change_per"].";物理签单率:".$wl["order_per"].",扩课签单率:".$wl["kk_per"].",换老师签单率:".$wl["change_per"].";小学科签单率:".$zh["order_per"].",扩课签单率:".$zh["kk_per"].",换老师签单率:".$zh["change_per"],"");
        }



        //整体转化率(全职老师)
        $all = $zh= [];
        $test_person_num= $task->t_lesson_info->get_teacher_test_person_num_list_subject( $start_time,$end_time,1);
        foreach($test_person_num as $item){
            @$all["person_num"] +=$item["person_num"];
            @$all["have_order"] +=$item["have_order"];
            if($item["subject"]==4 && $item["subject"] >5){
                @$zh["person_num"] +=$item["person_num"];
                @$zh["have_order"] +=$item["have_order"];
            }
        }
        $all["order_per"] = (!empty($all["person_num"])?round($all["have_order"]/$all["person_num"],3)*100:0)."%";
        $zh["order_per"] = (!empty($zh["person_num"])?round($zh["have_order"]/$zh["person_num"],3)*100:0)."%";
        $yw["order_per"] = (!empty($test_person_num[1]["person_num"])?round($test_person_num[1]["have_order"]/$test_person_num[1]["person_num"],3)*100:0)."%";
        $sx["order_per"] = (!empty($test_person_num[2]["person_num"])?round($test_person_num[2]["have_order"]/$test_person_num[2]["person_num"],3)*100:0)."%";
        $yy["order_per"] = (!empty($test_person_num[3]["person_num"])?round($test_person_num[3]["have_order"]/$test_person_num[3]["person_num"],3)*100:0)."%";
        $wl["order_per"] = (!empty($test_person_num[5]["person_num"])?round($test_person_num[5]["have_order"]/$test_person_num[5]["person_num"],3)*100:0)."%";

        $kk_test_person_num= $task->t_lesson_info->get_kk_teacher_test_person_num_list_subject( $start_time,$end_time,1);
        //dd($kk_test_person_num);
        foreach($kk_test_person_num as $item){
            @$all["kk_num"] +=$item["kk_num"];
            @$all["kk_order"] +=$item["kk_order"];
            if($item["subject"]==4 && $item["subject"] >5){
                @$zh["kk_num"] +=$item["kk_num"];
                @$zh["kk_order"] +=$item["kk_order"];
            }
        }
        $all["kk_per"] = (!empty($all["kk_num"])?round($all["kk_order"]/$all["kk_num"],3)*100:0)."%";
        $zh["kk_per"] = (!empty($zh["kk_num"])?round($zh["kk_order"]/$zh["kk_num"],3)*100:0)."%";
        $yw["kk_per"] = (!empty($kk_test_person_num[1]["kk_num"])?round($kk_test_person_num[1]["kk_order"]/$kk_test_person_num[1]["kk_num"],3)*100:0)."%";
        $sx["kk_per"] = (!empty($kk_test_person_num[2]["kk_num"])?round($kk_test_person_num[2]["kk_order"]/$kk_test_person_num[2]["kk_num"],3)*100:0)."%";
        $yy["kk_per"] = (!empty($kk_test_person_num[3]["kk_num"])?round($kk_test_person_num[3]["kk_order"]/$kk_test_person_num[3]["kk_num"],3)*100:0)."%";
        $wl["kk_per"] = (!empty($kk_test_person_num[5]["kk_num"])?round($kk_test_person_num[5]["kk_order"]/$kk_test_person_num[5]["kk_num"],3)*100:0)."%";


        $change_test_person_num= $task->t_lesson_info->get_change_teacher_test_person_num_list_subject( $start_time,$end_time,1);
        //dd($change_test_person_num);
        foreach($change_test_person_num as $item){
            @$all["change_num"] +=$item["change_num"];
            @$all["change_order"] +=$item["change_order"];
            if($item["subject"]==4 && $item["subject"] >5){
                @$zh["change_num"] +=$item["change_num"];
                @$zh["change_order"] +=$item["change_order"];
            }
        }
        $all["change_per"] = (!empty($all["change_num"])?round($all["change_order"]/$all["change_num"],3)*100:0)."%";
        $zh["change_per"] = (!empty($zh["change_num"])?round($zh["change_order"]/$zh["change_num"],3)*100:0)."%";
        $yw["change_per"] = (!empty($change_test_person_num[1]["change_num"])?round($change_test_person_num[1]["change_order"]/$change_test_person_num[1]["change_num"],3)*100:0)."%";
        $sx["change_per"] = (!empty($change_test_person_num[2]["change_num"])?round($change_test_person_num[2]["change_order"]/$change_test_person_num[2]["change_num"],3)*100:0)."%";
        $yy["change_per"] = (!empty($change_test_person_num[3]["change_num"])?round($change_test_person_num[3]["change_order"]/$change_test_person_num[3]["change_num"],3)*100:0)."%";
        $wl["change_per"] = (!empty($change_test_person_num[5]["change_num"])?round($change_test_person_num[5]["change_order"]/$change_test_person_num[5]["change_num"],3)*100:0)."%";

        //$success_test_lesson_list = $task->t_lesson_info->get_success_test_lesson_list_new_subject($start_time,$end_time);

        $admin_arr2 = [349,72,480,1171,1453,1446];
        foreach($admin_arr2 as $vv){
            $task->t_manager_info->send_wx_todo_msg_by_adminid ($vv,"理优监课组","每日全职试听转化率","本月签单率:".$all["order_per"].",扩课签单率:".$all["kk_per"].",换老师签单率:".$all["change_per"].";其中语文签单率:".$yw["order_per"].",扩课签单率:".$yw["kk_per"].",换老师签单率:".$yw["change_per"].";数学签单率:".$sx["order_per"].",扩课签单率:".$sx["kk_per"].",换老师签单率:".$sx["change_per"].";英语签单率:".$yy["order_per"].",扩课签单率:".$yy["kk_per"].",换老师签单率:".$yy["change_per"].";物理签单率:".$wl["order_per"].",扩课签单率:".$wl["kk_per"].",换老师签单率:".$wl["change_per"].";小学科签单率:".$zh["order_per"].",扩课签单率:".$zh["kk_per"].",换老师签单率:".$zh["change_per"],"");
        }

        //每日各模式试听转化率
        $top_seller_total = $task->t_lesson_info_b3->get_seller_test_lesson_tran_info( $start_time,$end_time,1,1); //咨询/老师1000精排总体
        $top_seller_total["per"] = !empty($top_seller_total["person_num"])?round($top_seller_total["have_order"]/$top_seller_total["person_num"]*100,2):0;

        $green_seller_total = $task->t_lesson_info_b3->get_seller_test_lesson_tran_info( $start_time,$end_time,2,1); //咨询/老师绿色通道总体
        $green_seller_total["per"] = !empty($green_seller_total["person_num"])?round($green_seller_total["have_order"]/$green_seller_total["person_num"]*100,2):0;

        $normal_seller_total_grab = $task->t_lesson_info_b3->get_seller_test_lesson_tran_info( $start_time,$end_time,3,1,1); //咨询/老师普通排课总体(抢课)
        $normal_seller_total_grab["per"] = !empty($normal_seller_total_grab["person_num"])?round($normal_seller_total_grab["have_order"]/$normal_seller_total_grab["person_num"]*100,2):0;

        $normal_seller_total = $task->t_lesson_info_b3->get_seller_test_lesson_tran_info( $start_time,$end_time,3,1,0); //咨询/老师普通排课总体(非抢课)
        $normal_seller_total["per"] = !empty($normal_seller_total["person_num"])?round($normal_seller_total["have_order"]/$normal_seller_total["person_num"]*100,2):0;

        $admin_arr2=[349,72,967];
        $start = date("Y-m-d H:i:s",$start_time);
        $end   = date("Y-m-d H:i:s",$end_time);
        foreach($admin_arr2 as $vv){
            $task->t_manager_info->send_wx_todo_msg_by_adminid ($vv,"理优监课组","每日各模式试听转化率","\n抢单模式:".$normal_seller_total_grab["per"]."%\n普排模式:".$normal_seller_total["per"]."%\n精排模式:".$top_seller_total["per"]."%\n绿色通道:".$green_seller_total["per"]."%","http://admin.leo1v1.com/main_page/teacher_management_info?date_type_config=undefined&date_type=null&opt_date_type=0&start_time=".$start."&end_time=".$end);
        }
    }


}
