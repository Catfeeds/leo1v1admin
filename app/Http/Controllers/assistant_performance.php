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
        $start_info       = \App\Helper\Utils::get_week_range($start_time,1 );
        $first_week = $start_info["sdate"];
        $end_info = \App\Helper\Utils::get_week_range($end_time,1 );
        if($end_info["edate"] <= $end_time){
            $last_week =  $end_info["sdate"];
        }else{
            $last_week =  $end_info["sdate"]-7*86400;
        }
        $n = ($last_week-$first_week)/(7*86400)+1;
        for($i=0;$i<$n;$i++){
           $week = $first_week+$i*7*86400;
        }


    }

}
