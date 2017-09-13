<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class get_ass_stu_info_update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get_ass_stu_info_update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '助教学生信息每日更新';

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
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();

        //更新助教信息
        $start_time = strtotime(date("Y-m-01",time()-86400));
        $end_time = strtotime(date("Y-m-01",$start_time+40*86400));
        //$start_time = strtotime(date("2017-08-01"));
        // $end_time = strtotime(date("2017-09-01"));

        $last_month = strtotime(date('Y-m-01',$start_time-100));
        $ass_last_month = $task->t_month_ass_student_info->get_ass_month_info($last_month);
        $lesson_count_list_old=[];       

        foreach($ass_last_month as $ks=>&$vs){
            $userid_list = json_decode($vs["userid_list"],true);
            if(empty($userid_list)){
                $userid_list=[];
            }
            if(date("m",$start_time) == "06"){
                foreach($userid_list as $kq=>$qq){
                    $grade = $task->t_student_info->get_grade($qq);
                    if(in_array($grade,[203,303])){
                        unset($userid_list[$kq]);
                        $vs["read_student"]--;
                    }
                }
            }

            $lesson_count_list_old[$ks]=$task->t_manager_info->get_assistant_lesson_count_old($start_time,$end_time,$ks,$userid_list);
        }


        //dd($ass_last_month);
        //dd(date("Y-m-d",$last_month));
        $ass_list = $task->t_manager_info->get_adminid_list_by_account_role(1);
        $warning_list = $task->t_student_info->get_warning_stu_list();
        
        foreach($warning_list as $item){
            @$ass_list[$item["uid"]]["warning_student"]++;
        }

        $stu_info_all = $task->t_student_info->get_ass_stu_info_new();       
        $userid_list = $task->t_student_info->get_read_student_ass_info();
        $month_stop_all =  $task->t_student_info->get_ass_month_stop_info_new($start_time,$end_time);
        $lesson_count_list = $task->t_manager_info->get_assistant_lesson_count_info($start_time,$end_time); 

        // $lesson_count_list_old = $task->t_manager_info->get_assistant_lesson_count_info_old($start_time,$end_time);        
                   
        $assistant_renew_list = $task->t_manager_info->get_all_assistant_renew_list_new($start_time,$end_time);
        $kk_suc= $task->t_test_lesson_subject->get_ass_kk_tongji_info($start_time,$end_time);
        $refund_info = $task->t_order_refund->get_ass_refund_info($start_time,$end_time);

        $student_finish = $task->t_student_info->get_ass_first_revisit_info_finish($start_time,$end_time);//结课学生数
        $student_finish_detail = [];
        foreach ($student_finish as $key => $value) {  
            $student_finish_detail[$value['uid']] = $value['num']; 
        }

        $student_all = $task->t_student_info->get_ass_first_revisit_info();//在册学生数
        $student_all_detail = [];
        foreach ($student_all as $key => $value) {  
            $student_all_detail[$value['uid']] = $value['num']; 
        }


        $lesson_money_list = $task->t_manager_info->get_assistant_lesson_money_info($start_time,$end_time);
        $new_info          = $task->t_student_info->get_new_assign_stu_info($start_time,$end_time);
        $end_stu_info_new  = $task->t_student_info->get_end_class_stu_info($start_time,$end_time);
        $lesson_info       = $task->t_lesson_info_b2->get_ass_stu_lesson_list($start_time,$end_time);


        //主管2数据
        $month_middle = $start_time+15*86400;
        $lesson_list_first = $task->t_lesson_info_b2->get_all_ass_stu_lesson_info($start_time,$month_middle);
        $userid_list_first=[];
        $userid_list_first_all=[];
        foreach($lesson_list_first as $item1){
            $userid_list_first[$item1["uid"]][]=$item1["userid"];
            $userid_list_first_all[] = $item1["userid"];
        }
        $xq_revisit_first = $task->t_revisit_info->get_ass_xq_revisit_info_new($start_time,$month_middle,$userid_list_first_all,false);

        $lesson_list_second = $task->t_lesson_info_b2->get_all_ass_stu_lesson_info($month_middle,$end_time);
        $userid_list_second=[];
        $userid_list_second_all=[];
        foreach($lesson_list_second as $item2){
            $userid_list_second[$item2["uid"]][]=$item2["userid"];
            $userid_list_second_all[] = $item2["userid"];
        }

        $xq_revisit_second = $task->t_revisit_info->get_ass_xq_revisit_info_new($month_middle,$end_time,$userid_list_second_all,false);

        $new_info = $task->t_student_info->get_ass_new_stu_first_revisit_info($start_time,$end_time);
        $new_revisit=[];
        foreach($new_info as $vu){
            @$new_revisit[$vu["uid"]]["new_num"]++;
            if($vu["revisit_time"]>0){
                @$new_revisit[$vu["uid"]]["first_num"]++;
            }else{
                @$new_revisit[$vu["uid"]]["un_first_num"]++;
            }
        }



        $student_finish = $task->t_student_info->get_ass_first_revisit_info_finish($start_time,$end_time);//结课学生数
        $student_finish_detail = [];
        foreach ($student_finish as $key => $value) {  
            $student_finish_detail[$value['uid']] = $value['num']; 
        }
        $refund_score = $task->get_ass_refund_score($start_time,$end_time);

        $lesson_money_all = $task->t_manager_info->get_assistant_lesson_money_info_all($start_time,$end_time);
        $lesson_count_all = $task->t_manager_info->get_assistant_lesson_count_info_all($start_time,$end_time);
        $lesson_price_avg = !empty($lesson_count_all)?$lesson_money_all/$lesson_count_all:0;

        foreach($ass_list as $k=>&$item){
            if(!isset($item["warning_student"])){
                $item["warning_student"]=0;
            }
            $item["read_student"]          = @$stu_info_all[$k]["read_count"];
            $item["stop_student"]          = @$stu_info_all[$k]["stop_count"];
            $item["all_student"]           = @$stu_info_all[$k]["all_count"];
            $item["month_stop_student"]    = @$month_stop_student[$k]["num"];
            $item["lesson_total"]          = @$lesson_count_list[$k]["lesson_count"];
            $item["lesson_ratio"]          = !empty(@$ass_last_month[$k]["read_student"])?round(@$lesson_count_list_old[$k]/@$ass_last_month[$k]["read_student"]/100,2):0;

            $item["renw_price"]            = @$assistant_renew_list[$k]["renw_price"];
            $item["all_price"]             = @$assistant_renew_list[$k]["all_price"];
            $item["tran_price"]            = @$assistant_renew_list[$k]["tran_price"];
            $item["renw_student"]          = @$assistant_renew_list[$k]["all_student"];

            $item["kk_num"]                = @$kk_suc[$k]["lesson_count"];
            $item["userid_list"]           = @$userid_list[$k];
            $item["refund_student"]        = @$refund_info[$k]["num"];
            $item["new_refund_money"]      = @$refund_info[$k]["new_price"];
            $item["renw_refund_money"]     = @$refund_info[$k]["renw_price"];
            $item["lesson_total_old"]      = @$lesson_count_list_old[$k];
            $item["read_student_new"]      = @$lesson_count_list[$k]["user_count"]; //上课学生-new
            $item["all_student_new"]       = @$student_all_detail[$k] + @$student_finish_detail[$k]; //在册学员-new

            //new add
            $item["lesson_money"]          = @$lesson_money_list[$k]["lesson_price"];//课耗收入
            $item["new_student"]           = isset($new_info[$k]["num"])?$new_info[$k]["num"]:0;//新签人数
            $item["new_lesson_count"]      = isset($new_info[$k]["lesson_count"])?$new_info[$k]["lesson_count"]:0;//购买课时
            $item["end_stu_num"]           = isset($end_stu_info_new[$k]["num"])?$end_stu_info_new[$k]["num"]:0;//结课学生
            $item["lesson_student"]        = isset($lesson_info[$k]["user_count"])?$lesson_info[$k]["user_count"]:0;//在读学生

            //主管2.0数据
            $item["userid_list_first"] = isset($userid_list_first[$k])?$userid_list_first[$k]:[];
            $item["userid_list_first_target"] = count($item["userid_list_first"]);
            $item["userid_list_first_count"] = @$xq_revisit_first[$k]["num"];
            $item["userid_list_second"] = isset($userid_list_second[$k])?$userid_list_second[$k]:[];
            $item["userid_list_second_target"] = count($item["userid_list_second"]);
            $item["userid_list_second_count"] = @$xq_revisit_second[$k]["num"];
            $item["revisit_target"] = $item["userid_list_first_target"]+$item["userid_list_second_target"];
            $item["revisit_real"] = $item["userid_list_first_count"]+$item["userid_list_second_count"];
            $item["tran_num"] = isset($assistant_renew_list[$k])?$assistant_renew_list[$k]["tran_num"]:0;
            $item["new_num"] = isset($new_revisit[$k])?$new_revisit[$k]["new_num"]:0;
            $item["first_revisit_num"] = isset($new_revisit[$k]["first_num"])?$new_revisit[$k]["first_num"]:0;
            $item["un_first_revisit_num"] = isset($new_revisit[$k]["un_first_num"])?$new_revisit[$k]["un_first_num"]:0;
            // $item["refund_score"] = round((10-@$refund_score[$k])>=0?10-@$refund_score[$k]:0,2);
            $item["refund_score"] = round(@$refund_score[$k],2);
            $item["lesson_money"] = round(@$lesson_count_list[$k]["lesson_count"]*$lesson_price_avg/100,2);
            $item["kk_succ"] = isset($ass_month[$k])?$ass_month[$k]["kk_num"]:0;

            //$item["student_all"] = isset($student_all_detail[$k])?$student_all_detail[$k]:0;
            $item["student_finish"] = isset($student_finish_detail[$k])?$student_finish_detail[$k]:0;


            $adminid_exist = $task->t_month_ass_student_info->get_ass_month_info($start_time,$k,1);
            if($adminid_exist){

                $update_arr =  [
                    "read_student"          =>$item["read_student"],
                    "stop_student"          =>$item["stop_student"],
                    "all_student"           =>$item["all_student"],
                    "month_stop_student"    =>$item["month_stop_student"],
                    "warning_student"       =>$item["warning_student"],
                    "lesson_total"          =>$item["lesson_total"],
                    "lesson_ratio"          =>$item["lesson_ratio"],
                    "renw_price"            =>$item["renw_price"],
                    "renw_student"          =>$item["renw_student"],
                    "tran_price"            =>$item["tran_price"],
                    "kk_num"                =>$item["kk_num"],
                    "userid_list"           =>$item["userid_list"],
                    "refund_student"        =>$item["refund_student"],
                    "new_refund_money"      =>$item["new_refund_money"],
                    "renw_refund_money"     =>$item["renw_refund_money"],
                    "lesson_total_old"      =>$item["lesson_total_old"],
                    "read_student_new"      =>$item["read_student_new"],
                    "all_student_new"       =>$item["all_student_new"],

                    "lesson_money"          =>$item["lesson_money"],
                    "new_student"           =>$item["new_student"],
                    "new_lesson_count"      =>$item["new_lesson_count"],
                    "end_stu_num"           =>$item["end_stu_num"],
                    "lesson_student"        =>$item["lesson_student"]
                ];
                $task->t_month_ass_student_info->get_field_update_arr($k,$start_time,1,$update_arr);
            }else{
                $task->t_month_ass_student_info->row_insert([
                    "adminid"               =>$k,
                    "month"                 =>$start_time,
                    "read_student"          =>$item["read_student"],
                    "stop_student"          =>$item["stop_student"],
                    "all_student"           =>$item["all_student"],
                    "month_stop_student"    =>$item["month_stop_student"],
                    "warning_student"       =>$item["warning_student"],
                    "lesson_total"          =>$item["lesson_total"],
                    "lesson_ratio"          =>$item["lesson_ratio"],
                    "renw_price"            =>$item["renw_price"],
                    "renw_student"          =>$item["renw_student"],
                    "tran_price"            =>$item["tran_price"],
                    "kk_num"                =>$item["kk_num"],
                    "userid_list"           =>$item["userid_list"],
                    "refund_student"        =>$item["refund_student"],
                    "new_refund_money"      =>$item["new_refund_money"],
                    "renw_refund_money"     =>$item["renw_refund_money"],
                    "lesson_total_old"      =>$item["lesson_total_old"],
                    "kpi_type"              =>1,
                    "read_student_new"      =>$item["read_student_new"],
                    "all_student_new"       =>$item["all_student_new"],

                    "lesson_money"          =>$item["lesson_money"],
                    "new_student"           =>$item["new_student"],
                    "new_lesson_count"      =>$item["new_lesson_count"],
                    "end_stu_num"           =>$item["end_stu_num"],
                    "lesson_student"        =>$item["lesson_student"]
                ]);

            }

            if(date("d",time())=="01"){
                $month = strtotime(date("Y-m-01",time()));
                $userid_arr = @$userid_list[$k];
                $userid_list_last = json_decode($userid_arr,true);
                if(empty($userid_list_last)){
                    $userid_list_last=[];
                }

                $read_student_last = @$stu_info_all[$k]["read_count"];

                if(date("m",time()) == "06"){
                    foreach($userid_list_last as $kq=>$qq){
                        $grade = $task->t_student_info->get_grade($qq);
                        if(in_array($grade,[203,303])){
                            unset($userid_list_last[$kq]);
                            $read_student_last--;
                        }
                    }
                }
                $userid_list_last = json_encode($userid_list_last);
                $adminid_exist2 = $task->t_month_ass_student_info->get_ass_month_info($month,$k,1);
                if($adminid_exist2){                    
                    $month_arr = [
                        "read_student_last"     =>$read_student_last,
                        "userid_list_last"      =>$userid_list_last 
                    ];
                    $task->t_month_ass_student_info->get_field_update_arr($k,$month,1,$month_arr);                    
                }else{
                    $task->t_month_ass_student_info->row_insert([
                        "adminid"               =>$k,
                        "month"                 =>$month,
                        "read_student_last"     =>$read_student_last,
                        "userid_list_last"      =>$userid_list_last,
                        "kpi_type"              =>1
                    ]);

                }

                               
                
            }             
        }

        if(date("d",time())=="01"){
            $warning_stu_list=[];
            foreach($warning_list as $ss){                   
                @$warning_stu_list[$ss["uid"]]["warning_student"]++;
                @$warning_stu_list[$ss["uid"]]["userid_list"][]=$ss["userid"];
            }

            $ass_list = $task->t_manager_info->get_adminid_list_by_account_role(1);
            foreach($ass_list as $ki=>$val){
                if(isset($warning_stu_list[$ki])){
                    $warning_student = @$warning_stu_list[$ki]["warning_student"];
                    $userid_list = json_encode($warning_stu_list[$ki]["userid_list"]);
                }else{
                    $warning_student =0;
                    $userid_list=[];
                    $userid_list = json_encode($userid_list);
                }
                $id =$task->t_ass_weekly_info->get_id_by_unique_record($ki,$end_time,2);
                if($id >0){
                        
                }else{
                    $task->t_ass_weekly_info->row_insert([
                        "adminid"   =>$ki,
                        "week"      =>$end_time,
                        "warning_student" =>$warning_student,
                        "warning_student_list" =>$userid_list,
                        "time_type"    =>2
                    ]);
                }
                    
            }

        }
        
        //update
        $ass_list = $task->t_manager_info->get_adminid_list_by_account_role(1);
        /* $update_time = [
            4=>['start_time' => 1490976000,
                "end_time"   => 1493568000],
            5=>['start_time' => 1493568000,
                "end_time"   => 1496246400],
            6=>['start_time' => 1496246400,
                "end_time"   => 1498838400],
            7=>['start_time' => 1498838400,
                "end_time"   => 1501516800],
            8=>['start_time' => 1501516800,
                "end_time"   => 1504195200],
            9=>['start_time' => 1504195200,
                "end_time"   => 1506787200],
                ];*/
        // foreach ($update_time  as $key => $value) {
        //$start_time = $value['start_time'];
        // $end_time   = $value['end_time'];
        $end_time= strtotime(date("Y-m-01",time()-86400));
        $start_time= strtotime(date("Y-m-01",$end_time-86400));

        $lesson_money_list = $task->t_manager_info->get_assistant_lesson_money_info($start_time,$end_time);
        $new_info          = $task->t_student_info->get_new_assign_stu_info($start_time,$end_time);
        $end_stu_info_new  = $task->t_student_info->get_end_class_stu_info($start_time,$end_time);
        $lesson_info       = $task->t_lesson_info_b2->get_ass_stu_lesson_list($start_time,$end_time);
        $assistant_renew_list = $task->t_manager_info->get_all_assistant_renew_list_new($start_time,$end_time);
        foreach($ass_list as $k=>&$item){
            //new add
            $item["lesson_money"]          = @$lesson_money_list[$k]["lesson_price"];//课耗收入
            $item["new_student"]           = isset($new_info[$k]["num"])?$new_info[$k]["num"]:0;//新签人数
            $item["new_lesson_count"]      = isset($new_info[$k]["lesson_count"])?$new_info[$k]["lesson_count"]:0;//购买课时
            $item["end_stu_num"]           = isset($end_stu_info_new[$k]["num"])?$end_stu_info_new[$k]["num"]:0;//结课学生
            $item["lesson_student"]        = isset($lesson_info[$k]["user_count"])?$lesson_info[$k]["user_count"]:0;//在读学生

            $item["renw_price"]            = @$assistant_renew_list[$k]["renw_price"];
            $item["tran_price"]            = @$assistant_renew_list[$k]["tran_price"];
            $item["renw_student"]          = @$assistant_renew_list[$k]["all_student"];


            $adminid_exist = $task->t_month_ass_student_info->get_ass_month_info($start_time,$k,1);
            if($adminid_exist){
                $update_arr =  [
                    "lesson_money"          =>$item["lesson_money"],
                    "new_student"           =>$item["new_student"],
                    "new_lesson_count"      =>$item["new_lesson_count"],
                    "end_stu_num"           =>$item["end_stu_num"],
                    "lesson_student"        =>$item["lesson_student"],

                    "renw_price"            =>$item["renw_price"],
                    "tran_price"            =>$item["tran_price"],
                    "renw_student"          =>$item["renw_student"],
                ];
                $task->t_month_ass_student_info->get_field_update_arr($k,$start_time,1,$update_arr);
            }       
        }
            // }
        // dd($ass_list);
    }
}
