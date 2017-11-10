<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;

class assistant_performance extends Controller
{
    use CacheNick;
    use TeaPower;
    public function ass_revisit_info_month() {
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],3);

        $account = $this->get_account();
        if($account=="jack"){
            $account="eros";
        }
        $assistantid = $this->t_assistant_info->get_assistantid( $account);
        $assistantid  = $this->get_in_int_val("assistantid",$assistantid);
        $ret_info = $this->t_student_info->get_assistant_read_stu_info($assistantid);
        $month_start = strtotime(date("Y-m-01",$start_time));
        $month_end = strtotime(date("Y-m-01",$month_start+40*86400));
        $cur_start = $month_start+15*86400;
        $cur_end =  $month_end;
        $last_start = $month_start;
        $last_end =  $month_start+15*86400;
        $cur_time_str  = date("m.d",$cur_start)."-".date("m.d",$cur_end-300);
        $last_time_str = date("m.d",$last_start)."-".date("m.d",$last_end-300);

        return $this->pageView(__METHOD__,$ret_info,[
            "last_time_str"=>$last_time_str,
            "cur_time_str" =>$cur_time_str
        ]);
    }

    public function get_ass_stu_lesson_month(){
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],3);
        $account = $this->get_account();
        if($account=="jack"){
            $account="eros";
        }
        $assistantid = $this->t_assistant_info->get_assistantid( $account);
        $assistantid  = $this->get_in_int_val("assistantid",$assistantid);
        $adminid = $this->t_manager_info->get_ass_adminid($assistantid);

        $start_info       = \App\Helper\Utils::get_week_range($start_time,1 );
        $first_week = $start_info["sdate"];
        $end_info = \App\Helper\Utils::get_week_range($end_time,1 );
        if($end_info["edate"] <= $end_time){
            $last_week =  $end_info["sdate"];
        }else{
            $last_week =  $end_info["sdate"]-7*86400;
        }
        $n = ($last_week-$first_week)/(7*86400)+1;
        $userid_list = $this->t_student_info->get_read_student_ass_info();
        $time_list=[];
        $time_arr=[];
        for($i=0;$i<$n;$i++){
           $week = $first_week+$i*7*86400;
            $id =$this->t_ass_weekly_info->get_id_by_unique_record($adminid,$week,1);
            if($id>0){
                $read_student_list = $this->t_ass_weekly_info->field_get_list($id,"read_student_list");
                $read_student_list= @$read_student_list["read_student_list"];
            }else{
                $read_student_list = @$userid_list[$adminid];
            }
            if($read_student_list){
                $read_student_list = json_decode($read_student_list,true);
            }else{
                $read_student_list=[];
            }
            $time_list[$week]=$read_student_list;
            $time_arr[$week]=date("Y.m.d",$week)."-".date("Y.m.d",$week+7*86400-100);

        }
        $list=[];
        foreach($time_list as $k=>$val){
            foreach($val as $v){
                $list[$v][$k]=1; 
            }
        }
        foreach($list as $key=>&$item){
            foreach($time_list as $k=>$v){
                if(!isset($item[$k])){
                    $item[$k]="否";
                }else{
                     $item[$k]="是";
                }
            }
            $item["nick"] = $this->cache_get_student_nick($key);

        }

        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($list),[
            "time_arr" =>$time_arr
        ]);

    }

    public function performance_info(){
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],3);
        $last_month = strtotime("-1 month",$start_time);
        $ass_month= $this->t_month_ass_student_info->get_ass_month_info_payroll($start_time);
        $last_ass_month= $this->t_month_ass_student_info->get_ass_month_info_payroll($last_month);
        foreach($ass_month as $k=>&$item){
            //回访

            //课时消耗达成率
            $registered_student_list = @$last_ass_month[$k]["registered_student_list"];
            if($registered_student_list){
                $registered_student_arr = json_decode($registered_student_list,true);
                $last_stu_num = count($registered_student_arr);
                $last_lesson_total = $this->t_week_regular_course->get_lesson_count_all($registered_student_arr);
                dd($last_lesson_total."-".$last_stu_num);
            }else{
                $registered_student_arr=[];      
                $estimate_month_lesson_count =100;
            }
        }
        
               
        
        
    }

}
