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
        //$end_time = strtotime(date("Y-m-01",time()));
        $start_time = strtotime(date("Y-m-01",time()-86400));
        $end_time = strtotime(date("Y-m-01",$start_time+40*86400));
        // $start_time = strtotime(date("2017-07-01"));
        // $end_time = strtotime(date("2017-08-01"));

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
            $item["all_student_new"]      = @$student_all_detail[$k] + @$student_finish_detail[$k]; //在册学员-new
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
                    "all_student_new"       =>$item["all_student_new"]
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
                    "all_student_new"       =>$item["all_student_new"]
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

                $warning_stu_list=[];
                foreach($warning_list as $ss){                   
                    @$warning_stu_list[$ss["uid"]]["warning_student"]++;
                    @$warning_stu_list[$ss["uid"]]["userid_list"][]=$ss["userid"];
                }

                $ass_list = $task->t_manager_info->get_adminid_list_by_account_role(1);
                foreach($ass_list as $k=>$val){
                    if(isset($warning_stu_list[$k])){
                        $warning_student = @$warning_stu_list[$k]["warning_student"];
                        $userid_list = json_encode($warning_stu_list[$k]["userid_list"]);
                    }else{
                        $warning_student =0;
                        $userid_list=[];
                        $userid_list = json_encode($userid_list);
                    }
                    $task->t_ass_weekly_info->row_insert([
                        "adminid"   =>$k,
                        "week"      =>$end_time,
                        "warning_student" =>$warning_student,
                        "warning_student_list" =>$userid_list,
                        "time_type"    =>2
                    ]);
                }
                
            }             
        }
       
        // dd($ass_list);
    }
}
