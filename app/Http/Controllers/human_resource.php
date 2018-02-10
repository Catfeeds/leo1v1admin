<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;
use Illuminate\Support\Facades\Cookie ;

class human_resource extends Controller
{
    use CacheNick;
    use TeaPower;

    public function regular_course()
    {
        $teacherid = $this->get_in_int_val('teacherid',-1);
        $userid    = $this->get_in_int_val('userid',-1);

        $date      = \App\Helper\Utils::get_week_range(time(NULL),1);
        $stat_time = $date["sdate"];

        $ret_info = \App\Helper\Utils::list_to_page_info([]);
        $this->set_filed_for_js("account_role_self",$this->get_account_role());
        $ass_master_flag = $this->check_ass_leader_flag($this->get_account_id());
        $this->set_filed_for_js("ass_master_flag",$ass_master_flag);
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function winter_regular_course()
    {
        $teacherid = $this->get_in_int_val('teacherid',-1);
        $userid = $this->get_in_int_val('userid',-1);

        $date=\App\Helper\Utils::get_week_range(time(NULL),1);
        $stat_time=$date["sdate"];

        $ret_info=\App\Helper\Utils::list_to_page_info([]);
        $this->set_filed_for_js("account_role_self",$this->get_account_role());
        return $this->pageView(__METHOD__, $ret_info);
    }

    public function summer_regular_course()
    {
        $teacherid = $this->get_in_int_val('teacherid',-1);
        $userid = $this->get_in_int_val('userid',-1);

        $date=\App\Helper\Utils::get_week_range(time(NULL),1);
        $stat_time=$date["sdate"];

        $ret_info=\App\Helper\Utils::list_to_page_info([]);
        $this->set_filed_for_js("account_role_self",$this->get_account_role());
        return $this->pageView(__METHOD__, $ret_info);
    }

    public function winter_teacher_lesson_list (){
        $teacherid = $this->get_in_int_val('teacherid',-1);
        $ret_info=\App\Helper\Utils::list_to_page_info([]);
        return $this->pageView(__METHOD__, $ret_info);
    }


    public function regular_course_all()
    {
        return $this->regular_course();
    }
    public function winter_regular_course_all()
    {
        return $this->winter_regular_course();
    }


    public function get_common_config()
    {
        $teacherid = $this->get_in_int_val('teacherid',-1);

        $common_lesson_config= $this->t_teacher_freetime_for_week->get_common_lesson_config($teacherid);
        $date=\App\Helper\Utils::get_week_range(time(NULL),1);

        $stime=$date["sdate"];
        foreach ( $common_lesson_config as &$item ) {
            $start_time=$item["start_time"];
            $end_time=$item["end_time"];

            $arr=explode("-",$start_time);
            $item["assistantid"] = $this->t_student_info->get_assistantid($item['userid']);
            $item['ass_nick'] = $this->t_assistant_info->get_nick($item['assistantid']);
            \App\Helper\Utils::logger("start_time:$start_time");
            $week=$arr[0];
            $start_time=@$arr[1];

            //得到周几的开始时间
            $day_start=$stime + ($week-1)*86400;
            $item["start_time_ex"] = strtotime(date("Y-m-d", $day_start)." $start_time")*1000;
            $item["end_time_ex"]   = strtotime(date("Y-m-d", $day_start)." $end_time")*1000;
            $item["nick"]          = $this->cache_get_student_nick($item["userid"]);
            $item["teacher"]       = $this->cache_get_teacher_nick($teacherid);
        }

        return  $this->output_succ( [ "common_lesson_config" => $common_lesson_config] );
    }
    public function get_common_config_new()
    {

        $userid = $this->get_in_int_val('userid',-1);
        $teacherid = $this->get_in_int_val('teacherid',-1);
        #$userid = 60016;
        $common_lesson_config= $this->t_week_regular_course->get_lesson_info($teacherid,$userid);

        //暑假课表显示在常规课表里
        /* $m = date("m",time());
        if($m>=6 && $m <9){
            $common_lesson_config_summer= $this->t_summer_week_regular_course->get_lesson_info($teacherid,$userid);
            foreach($common_lesson_config_summer as $k=>$v){
                if(!isset($common_lesson_config[$k])){
                    $common_lesson_config[$k]=$v;
                }
            }

            }*/
        $date=\App\Helper\Utils::get_week_range(time(NULL),1);
        $stime=$date["sdate"];
        foreach ( $common_lesson_config as &$item ) {
            $start_time=$item["start_time"];
            $end_time=$item["end_time"];

            $arr=explode("-",$start_time);
            $item["assistantid"] = $this->t_student_info->get_assistantid($item['userid']);
            $item['ass_nick'] = $this->t_assistant_info->get_nick($item['assistantid']);
            \App\Helper\Utils::logger("start_time:$start_time");
            $week=$arr[0];
            $start_time=@$arr[1];
            E\Ecompetition_flag::set_item_value_str($item,"competition_flag");

            //得到周几的开始时间
            $day_start=$stime + ($week-1)*86400;
            $item["start_time_ex"] = strtotime(date("Y-m-d", $day_start)." $start_time")*1000;
            $item["end_time_ex"]   = strtotime(date("Y-m-d", $day_start)." $end_time")*1000;
            $item["nick"]          = $this->cache_get_student_nick($item["userid"]);
            $item["teacher"]       = $this->cache_get_teacher_nick($item["teacherid"]);
        }

        return  outputjson_success( [ "common_lesson_config" => $common_lesson_config] );
    }

    public function get_summer_common_config_new()
    {
        $userid = $this->get_in_int_val('userid',-1);
        //$userid=-1;
        $teacherid = $this->get_in_int_val('teacherid',-1);
        #$userid = 60016;
        $common_lesson_config= $this->t_summer_week_regular_course->get_lesson_info($teacherid,$userid);
        $date=\App\Helper\Utils::get_week_range(time(NULL),1);
        $stime=$date["sdate"];
        foreach ( $common_lesson_config as &$item ) {
            $start_time=$item["start_time"];
            $end_time=$item["end_time"];

            $arr=explode("-",$start_time);
            $item["assistantid"] = $this->t_student_info->get_assistantid($item['userid']);
            $item['ass_nick'] = $this->t_assistant_info->get_nick($item['assistantid']);
            \App\Helper\Utils::logger("start_time:$start_time");
            $week=$arr[0];
            $start_time=@$arr[1];
            E\Ecompetition_flag::set_item_value_str($item,"competition_flag");

            //得到周几的开始时间
            $day_start=$stime + ($week-1)*86400;
            $item["start_time_ex"] = strtotime(date("Y-m-d", $day_start)." $start_time")*1000;
            $item["end_time_ex"]   = strtotime(date("Y-m-d", $day_start)." $end_time")*1000;
            $item["nick"]          = $this->cache_get_student_nick($item["userid"]);
            $item["teacher"]       = $this->cache_get_teacher_nick($item["teacherid"]);
        }

        return  outputjson_success( [ "common_lesson_config" => $common_lesson_config] );
    }

    public function get_winter_common_config_new()
    {
        $userid = $this->get_in_int_val('userid',-1);
        //$userid=-1;
        $teacherid = $this->get_in_int_val('teacherid',-1);
        #$userid = 60016;
        $common_lesson_config= $this->t_winter_week_regular_course->get_lesson_info($teacherid,$userid);
        $date=\App\Helper\Utils::get_week_range(time(NULL),1);
        $stime=$date["sdate"];
        foreach ( $common_lesson_config as &$item ) {
            $start_time=$item["start_time"];
            $end_time=$item["end_time"];

            $arr=explode("-",$start_time);
            $item["assistantid"] = $this->t_student_info->get_assistantid($item['userid']);
            $item['ass_nick'] = $this->t_assistant_info->get_nick($item['assistantid']);
            \App\Helper\Utils::logger("start_time:$start_time");
            $week=$arr[0];
            $start_time=@$arr[1];
            E\Ecompetition_flag::set_item_value_str($item,"competition_flag");

            //得到周几的开始时间
            $day_start=$stime + ($week-1)*86400;
            $item["start_time_ex"] = strtotime(date("Y-m-d", $day_start)." $start_time")*1000;
            $item["end_time_ex"]   = strtotime(date("Y-m-d", $day_start)." $end_time")*1000;
            $item["nick"]          = $this->cache_get_student_nick($item["userid"]);
            $item["teacher"]       = $this->cache_get_teacher_nick($item["teacherid"]);
        }

        return  outputjson_success( [ "common_lesson_config" => $common_lesson_config] );
    }


    public function get_eve_config()
    {
        $teacherid = $this->get_in_int_val('teacherid',-1);
        $old_start_time = $this->get_in_str_val("old_start_time");

        #$common_lesson_config= $this->t_teacher_freetime_for_week->get_common_lesson_config($teacherid);
        $common_lesson_config = $this->t_week_regular_course->get_lesson_info($teacherid,-1);
        $date=\App\Helper\Utils::get_week_range(time(NULL),1);
        $stime=$date["sdate"];

        foreach ( $common_lesson_config as &$item ) {
            $u_item="";
            if($old_start_time == $item["start_time"]){
                $start_time=$item["start_time"];
                $end_time=$item["end_time"];

                $arr=explode("-",$start_time);

                \App\Helper\Utils::logger("start_time:$start_time");
                $week=$arr[0];
                $start_time=@$arr[1];

                //得到周几的开始时间

                $item["start_time"]          = $start_time;
                $day_start=$stime + ($week-1)*86400;
                $item["week"]          = $week;
                $item["nick"]          = $this->cache_get_student_nick($item["userid"]);
                $item["teacher"]       = $this->cache_get_teacher_nick($teacherid);
                $u_item=$item;
            }

        }
        return outputjson_success(["data"=> $u_item]);
    }

    public function otp_common_config()
    {
        $opt_type       = $this->get_in_str_val("opt_type");
        $teacherid      = $this->get_in_teacherid();
        #$teacherid = $this->teacherid;

        $old_key = $this->get_in_str_val("old_key");
        $start_time          = $this->get_in_str_val("start_time");
        $end_time            = $this->get_in_str_val("end_time");
        $lesson_count           = $this->get_in_str_val("lesson_count");
        #$count_total            = $this->get_in_str_val("count_total");
        $tea_type       = $this->get_in_int_val("tea_type",0);
        $freetime       = $this->get_in_int_val("freetime",0);
        $userid         = $this->get_in_userid();
        $count_total = $this->t_course_order->get_count_total($teacherid,$userid);
        $teacher_in = $this->t_teacher_freetime_for_week->check_userid($teacherid);
        if($teacher_in!= 1){
            $this->t_teacher_freetime_for_week->add_regular_course($teacherid,$tea_type,$freetime);
        }
        $common_lesson_config     = $this->t_teacher_freetime_for_week->get_common_lesson_config($teacherid);
        $arr=explode("-",$start_time);
        $start = @$arr[1];
        $lesson_start = strtotime(date("Y-m-d", time(NULL))." $start");
        $lesson_end = strtotime(date("Y-m-d", time(NULL))." $end_time");
        $diff=($lesson_end-$lesson_start)/60;
        if(empty($lesson_count)){
            if ($diff<=40) {
                $lesson_count=100;
            } else if ( $diff <= 60) {
                $lesson_count=150;
            } else if ( $diff <=90 ) {
                $lesson_count=200;
            }else{
                $lesson_count= ceil($diff/40)*100 ;
            }
        }else{
            if ($diff<=40) {
                if($lesson_count !=100){
                    return $this->output_err("请设置正确的课时数") ;
                };
            } else if ( $diff <= 60) {
                if( $lesson_count !=150){
                    return $this->output_err("请设置正确的课时数") ;
                };
            } else if ( $diff <=90 ) {
                if($lesson_count !=200){
                    return $this->output_err("请设置正确的课时数") ;
                };
            }else{
                if($lesson_count != ceil($diff/40)*100){
                    return $this->output_err("请设置正确的课时数") ;
                };
            }
        }


        if($opt_type=="add"){
            if($userid<=0){
                return $this->output_err("请设置学生") ;
            }
            $item = [];
            $item["start_time"]     = $start_time;
            $item["end_time"]       = $end_time;
            $item["userid"]         = $userid;
            $item["lesson_count"]     = $lesson_count;
            $item["count_total"]     = $count_total;
            $common_lesson_config[]  = $item;
        }else if($opt_type=="update"){
            foreach ( $common_lesson_config as &$u_item) {
                if($u_item["start_time"]==$old_key) {
                    $u_item["start_time"] = $start_time;
                    $u_item["end_time"]   = $end_time;
                    $u_item["userid"]     = $userid;
                    $u_item["lesson_count"]     = $lesson_count;
                    $u_item["count_total"]     = $count_total;
                }
            }

        }else if($opt_type=="del"){
            $tmp_list=[];
            foreach ( $common_lesson_config as $d_item) {
                if($d_item["start_time"]==$old_key) {
                }else {
                    $tmp_list[]=$d_item;
                }
            }
            $common_lesson_config=$tmp_list;
        }
        $ret_db = $this->t_teacher_freetime_for_week->field_update_list($teacherid,[
            "common_lesson_config" => json_encode($common_lesson_config)
        ]);
        return $this->output_succ();
    }
    public function otp_common_config_new()
    {
        $opt_type   = $this->get_in_str_val("opt_type");
        $userid     = $this->get_in_userid();
        $old_userid = $this->get_in_int_val("old_userid");
        if(!$this->check_power(E\Epower::V_OTP_COMMON_CONFIG_NEW) ) {
            $assistantid=$this->t_assistant_info->get_assistantid($this->get_account());
            $ret_stu = $this->t_student_info->get_stu_info($assistantid);
            if($opt_type=="add" || $opt_type =="del"){
                if(!empty($userid) && !isset($ret_stu[$userid])){
                    return $this->output_err("请选择自己的学生!") ;
                }
            }elseif($opt_type == "update"){
                if(!isset($ret_stu[$userid]) || !isset($ret_stu[$old_userid])){
                    return $this->output_err("请选择自己的学生!") ;
                }
            }

        }

        $teacherid        = $this->get_in_teacherid();
        $old_key          = $this->get_in_str_val("old_key");
        $start_time       = $this->get_in_str_val("start_time");
        $old_start_time   = $this->get_in_str_val("old_start_time");
        $old_week         = $this->get_in_int_val("old_week");
        $end_time         = $this->get_in_str_val("end_time");
        $lesson_count     = $this->get_in_str_val("lesson_count");
        $competition_flag = $this->get_in_int_val("competition_flag");
        $arr              = explode("-",$start_time);
        $week = $arr[0];
        $start = @$arr[1];

        $lesson_start = strtotime(date("Y-m-d", time(NULL))." $start");
        $lesson_end = strtotime(date("Y-m-d", time(NULL))." $end_time");

        if($opt_type=="add" || $opt_type =="update"){            
            if($lesson_start >=  $lesson_end){
                return $this->output_err("开始时间不能大于结束时间!") ;
            }
        }
        $old_start_time = $old_week."-".$old_start_time;
        $diff=($lesson_end-$lesson_start)/60;
        if(empty($lesson_count)){
            // if ($diff<=40) {
            //     $lesson_count=100;
            // } else if ( $diff <= 60) {
            //     $lesson_count=150;
            // } else if ( $diff <=90 ) {
            //     $lesson_count=200;
            // }else{
            //     $lesson_count= ceil($diff/40)*100 ;
            // }
            $lesson_count = \App\Helper\Utils::get_lesson_count($lesson_start, $lesson_end);
        }


        if($opt_type=="add"){
            if($userid<=0){
                return $this->output_err("请设置学生") ;
            }

            //常规课表不能连排三节
            $rr = $this->regular_course_set_check($teacherid,$week,$start,$end_time,"");
            if($rr){
                return $rr;
            }

            $ret = $this->t_week_regular_course->check_is_clash($userid,$teacherid,$start_time,$end_time,"");
            if($ret['tea']){
                return $this->output_err("排课与老师课程冲突了(add)") ;
            }elseif($ret['stu']){
                return $this->output_err("排课与学生课程冲突了(add)") ;
            }
            $this->t_week_regular_course->add_regular_course($teacherid,$userid,$start_time,$end_time,$lesson_count,$competition_flag);
        }else if($opt_type=="update"){
            //常规课表不能连排三节
            $rr = $this->regular_course_set_check($teacherid,$week,$start,$end_time,$old_start_time);
            if($rr){
                return $rr;
            }

            $ret = $this->t_week_regular_course->check_is_clash($userid,$teacherid,$start_time,$end_time,$old_start_time);
            if($ret['tea']){
                return $this->output_err("排课与老师课程冲突了(update)") ;
            }elseif($ret['stu']){
                return $this->output_err("排课与学生课程冲突了(update)") ;
            }

            $this->t_week_regular_course->field_update_list_2(
                $teacherid,$old_start_time,
                ["start_time"=>$start_time,"end_time"=>$end_time,"userid"=>$userid,"lesson_count"=>$lesson_count,"competition_flag"=>$competition_flag]);
        }else if($opt_type=="del"){
            $this->t_week_regular_course->row_delete_2($teacherid,$start_time);
        }
        return $this->output_succ();
    }


    public function otp_winter_common_config_new()
    {
        $opt_type       = $this->get_in_str_val("opt_type");
        $userid         = $this->get_in_userid();
        $old_userid         = $this->get_in_int_val("old_userid");
        if(!$this->check_power(E\Epower::V_OTP_COMMON_CONFIG_NEW) ) {
            $assistantid=$this->t_assistant_info->get_assistantid($this->get_account());
            $ret_stu = $this->t_student_info->get_stu_info($assistantid);
            if($opt_type=="add" || $opt_type =="del"){
                if(!empty($userid) && !isset($ret_stu[$userid])){
                    return $this->output_err("请选择自己的学生!") ;
                }
            }elseif($opt_type == "update"){
                if(!isset($ret_stu[$userid]) || !isset($ret_stu[$old_userid])){
                    return $this->output_err("请选择自己的学生!") ;
                }
            }

        }

        $teacherid      = $this->get_in_teacherid();
        #$teacherid = $this->teacherid;
        $old_key = $this->get_in_str_val("old_key");
        $start_time          = $this->get_in_str_val("start_time");
        $old_start_time          = $this->get_in_str_val("old_start_time");
        $old_week         = $this->get_in_int_val("old_week");
        $end_time            = $this->get_in_str_val("end_time");
        $lesson_count           = $this->get_in_str_val("lesson_count");
        $competition_flag         = $this->get_in_int_val("competition_flag");
        $arr=explode("-",$start_time);
        $week = $arr[0];
        $start = @$arr[1];
        $old_start_time = $old_week."-".$old_start_time;
        $lesson_start = strtotime(date("Y-m-d", time(NULL))." $start");
        $lesson_end = strtotime(date("Y-m-d", time(NULL))." $end_time");

        if($opt_type=="add" || $opt_type =="update"){            
            if($lesson_start >=  $lesson_end){
                return $this->output_err("开始时间不能大于结束时间!") ;
            }
        }

        $lesson_count = \App\Helper\Utils::get_lesson_count($lesson_start, $lesson_end);

        // $diff=($lesson_end-$lesson_start)/60;
        // if(empty($lesson_count)){
        //     if ($diff<=40) {
        //         $lesson_count=100;
        //     } else if ( $diff <= 60) {
        //         $lesson_count=150;
        //     } else if ( $diff <=90 ) {
        //         $lesson_count=200;
        //     }else{
        //         $lesson_count= ceil($diff/40)*100 ;
        //     }
        // }


        if($opt_type=="add"){
            if($userid<=0){
                return $this->output_err("请设置学生") ;
            }
            //常规课表不能连排三节
            $rr = $this->regular_course_set_check($teacherid,$week,$start,$end_time,"");
            if($rr){
                return $rr;
            }


            $ret = $this->t_winter_week_regular_course->check_is_clash($userid,$teacherid,$start_time,$end_time,"");
            if($ret['tea']){
                return $this->output_err("排课与老师课程冲突了") ;
            }elseif($ret['stu']){
                return $this->output_err("排课与学生课程冲突了") ;
            }
            $this->t_winter_week_regular_course->add_regular_course($teacherid,$userid,$start_time,$end_time,$lesson_count,$competition_flag);
        }else if($opt_type=="update"){
            //常规课表不能连排三节
            $rr = $this->regular_course_set_check($teacherid,$week,$start,$end_time,$old_start_time);
            if($rr){
                return $rr;
            }

            $ret = $this->t_winter_week_regular_course->check_is_clash($userid,$teacherid,$start_time,$end_time,$old_start_time);
            if($ret['tea']){
                return $this->output_err("排课与老师课程冲突了") ;
            }elseif($ret['stu']){
                return $this->output_err("排课与学生课程冲突了") ;
            }

            $this->t_winter_week_regular_course->field_update_list_2(
                $teacherid,$old_start_time,
                ["start_time"=>$start_time,"end_time"=>$end_time,"userid"=>$userid,"lesson_count"=>$lesson_count,"competition_flag"=>$competition_flag]);
        }else if($opt_type=="del"){
            $this->t_winter_week_regular_course->row_delete_2($teacherid,$start_time);
        }
        return $this->output_succ();
    }

    public function otp_summer_common_config_new()
    {
        $opt_type       = $this->get_in_str_val("opt_type");
        $userid         = $this->get_in_userid();
        $old_userid         = $this->get_in_int_val("old_userid");
        if(!$this->check_power(E\Epower::V_OTP_COMMON_CONFIG_NEW) ) {
            $assistantid=$this->t_assistant_info->get_assistantid($this->get_account());
            $ret_stu = $this->t_student_info->get_stu_info($assistantid);
            if($opt_type=="add" || $opt_type =="del"){
                if(!empty($userid) && !isset($ret_stu[$userid])){
                    return $this->output_err("请选择自己的学生!") ;
                }
            }elseif($opt_type == "update"){
                if(!isset($ret_stu[$userid]) || !isset($ret_stu[$old_userid])){
                    return $this->output_err("请选择自己的学生!") ;
                }
            }

        }

        $teacherid      = $this->get_in_teacherid();
        #$teacherid = $this->teacherid;
        $old_key = $this->get_in_str_val("old_key");
        $start_time          = $this->get_in_str_val("start_time");
        $old_start_time          = $this->get_in_str_val("old_start_time");
        $old_week         = $this->get_in_int_val("old_week");
        $end_time            = $this->get_in_str_val("end_time");
        $lesson_count           = $this->get_in_str_val("lesson_count");
        $competition_flag         = $this->get_in_int_val("competition_flag");
        $arr=explode("-",$start_time);
        $week = $arr[0];
        $start = @$arr[1];
        $old_start_time = $old_week."-".$old_start_time;
        $lesson_start = strtotime(date("Y-m-d", time(NULL))." $start");
        $lesson_end = strtotime(date("Y-m-d", time(NULL))." $end_time");
        if($opt_type=="add" || $opt_type =="update"){            
            if($lesson_start >=  $lesson_end){
                return $this->output_err("开始时间不能大于结束时间!") ;
            }
        }


        $lesson_count = \App\Helper\Utils::get_lesson_count($lesson_start, $lesson_end);
        // $diff=($lesson_end-$lesson_start)/60;
        // if(empty($lesson_count)){
        //     if ($diff<=40) {
        //         $lesson_count=100;
        //     } else if ( $diff <= 60) {
        //         $lesson_count=150;
        //     } else if ( $diff <=90 ) {
        //         $lesson_count=200;
        //     }else{
        //         $lesson_count= ceil($diff/40)*100 ;
        //     }
        // }


        if($opt_type=="add"){
            if($userid<=0){
                return $this->output_err("请设置学生") ;
            }

            //常规课表不能连排三节
            $rr = $this->regular_course_set_check($teacherid,$week,$start,$end_time,"");
            if($rr){
                return $rr;
            }

            $ret = $this->t_summer_week_regular_course->check_is_clash($userid,$teacherid,$start_time,$end_time,"");
            if($ret['tea']){
                return $this->output_err("排课与老师课程冲突了") ;
            }elseif($ret['stu']){
                return $this->output_err("排课与学生课程冲突了") ;
            }
            $this->t_summer_week_regular_course->add_regular_course($teacherid,$userid,$start_time,$end_time,$lesson_count,$competition_flag);
        }else if($opt_type=="update"){
            //常规课表不能连排三节
            $rr = $this->regular_course_set_check($teacherid,$week,$start,$end_time,$old_start_time);
            if($rr){
                return $rr;
            }

            $ret = $this->t_summer_week_regular_course->check_is_clash($userid,$teacherid,$start_time,$end_time,$old_start_time);
            if($ret['tea']){
                return $this->output_err("排课与老师课程冲突了") ;
            }elseif($ret['stu']){
                return $this->output_err("排课与学生课程冲突了") ;
            }

            $this->t_summer_week_regular_course->field_update_list_2(
                $teacherid,$old_start_time,
                ["start_time"=>$start_time,"end_time"=>$end_time,"userid"=>$userid,"lesson_count"=>$lesson_count,"competition_flag"=>$competition_flag]);
        }else if($opt_type=="del"){
            $this->t_summer_week_regular_course->row_delete_2($teacherid,$start_time);
        }
        return $this->output_succ();
    }


    public function specialty()
    {
        $grade           = $this->get_in_int_val('grade',-1);
        $teacherid       = $this->get_in_int_val("teacherid",-1);
        $subject_default = Cookie::get("subject")==null?-1: Cookie::get("subject");
        $subject         = $this->get_in_int_val('subject', $subject_default);
        $page_num        = $this->get_in_int_val('page_num',-1);

        if($page_num < 1){
            $page_num = 1;
        }
        $ret_info = $this->t_teacher_closest->get_teacher_closest($grade,$subject,$teacherid,$page_num);
        $number   = ($page_num-1)*10+1;
        foreach($ret_info['list'] as &$item){
            $item['nick'] = $this->cache_get_teacher_nick($item['teacherid']);
            E\Egrade::set_item_value_str($item );
            E\Esubject::set_item_value_str($item );
            E\Edegree::set_item_value_str($item );
            $item['number'] = $number;
            $number++;
        }
        return $this->pageView(__METHOD__,$ret_info,[
            '_publish_version' =>'201711161039',
        ]);


    }

    public function preview()
    {
        $teacherid       = $this->get_in_int_val("teacherid",-1);

        $grade_map= $this->t_teacher_closest_grade->get_grade_info($teacherid);

        //for E\Egrade::$desc_map
        // [["grade", boolean]]

        //array (  array( "grade"=> 101 , "select_flag" => true ) )

        /*
          $desc_map= array(
          101 => "小一",
          102 => "小二",
          103 => "小三",
          104 => "小四",
          105 => "小五",
          106 => "小六",
          201 => "初一",
          202 => "初二",
          203 => "初三",
          301 => "高一",
          302 => "高二",
          303 => "高三",
          );
        */

        $subject_info = $this->t_teacher_closest_subject->get_subject_info($teacherid);
        $args=array(
            "teacherid"=>$teacherid,
        );
        $js_values_str=$this->get_js_g_args($args);

        return $this->view(__METHOD__,[
            "ret_info" => $ret_info,
            "js_values_str"   => $js_values_str,
        ]);
    }


    public function index_ass() {
        return $this->teacher_info_new () ;
    }

    public function index_jw(){
        return $this->teacher_info_new () ;
    }
    public function index_zs(){
        return $this->teacher_info_new() ;
    }

    public function index_new_jw(){
        $this->set_in_value("lesson_hold_flag",0);
        $this->set_in_value("is_test_user",0);
        return $this->index_new();
    }
    public function index_new_jw_hold(){
        $this->set_in_value("lesson_hold_flag",1);
        $this->set_in_value("is_test_user",0);
        return $this->index_new() ;
         // return $this->teacher_info_new () ;
    }

    public function index_new_seller_hold(){
        $this->set_in_value("seller_hold_flag",1);
        return $this->index_new_jw_hold() ;
        // return $this->teacher_info_new () ;
    }


    public function index_tea_qua_zj(){
        return $this->index_tea_qua();
    }

    public function index_tea_qua(){
        $this->set_in_value("is_test_user",0);
        $this->set_in_value("fulltime_flag",0);
        return $this->index_new() ;
    }

    public function index_fulltime(){
        $this->set_in_value("is_test_user",0);
        $this->set_in_value("fulltime_flag",1);
        return $this->index_new() ;
    }

    public function index_seller(){
        $this->set_in_value("seller_flag",1);
        return $this->teacher_info_new () ;
    }

    public function index()
    {
        $this->switch_tongji_database();
        $teacherid                = $this->get_in_int_val('teacherid',-1);
        $is_freeze                = $this->get_in_int_val('is_freeze',-1);
        $teacher_money_type       = $this->get_in_int_val("teacher_money_type",-1);
        $teacher_ref_type         = $this->get_in_int_val("teacher_ref_type",-1);
        $level                    = $this->get_in_int_val("level",-1);
        $page_num                 = $this->get_in_page_num();
        $need_test_lesson_flag    = $this->get_in_int_val("need_test_lesson_flag",1);
        $textbook_type            = $this->get_in_int_val("textbook_type",-1);
        $is_good_flag             = $this->get_in_int_val("is_good_flag",-1);
        $is_new_teacher           = $this->get_in_int_val("is_new_teacher",1);
        $gender                   = $this->get_in_int_val("gender",-1);
        $free_time                = $this->get_in_str_val("free_time","");
        $subject                  = $this->get_in_int_val("subject",-1);
        $second_subject           = $this->get_in_int_val("second_subject",-1);
        $trial_flag               = $this->get_in_int_val("trial_flag",0);
        $test_flag                = $this->get_in_int_val("test_flag",0);
        $seller_flag              = $this->get_in_int_val("seller_flag",0);
        $is_test_user             = $this->get_in_int_val("is_test_user",-1);
        $is_quit                  = $this->get_in_int_val("is_quit",0);
        $address                  = trim($this->get_in_str_val('address',''));
        $limit_plan_lesson_type   = $this->get_in_int_val("limit_plan_lesson_type",-1);
        $is_record_flag           = $this->get_in_int_val("is_record_flag",-1);
        $test_lesson_full_flag    = $this->get_in_int_val("test_lesson_full_flag",-1);
        $train_through_new        = $this->get_in_int_val("train_through_new",-1);
        $lesson_hold_flag         = $this->get_in_int_val("lesson_hold_flag",-1);
        $test_transfor_per        = $this->get_in_int_val("test_transfor_per",-1);
        $week_liveness            = $this->get_in_int_val("week_liveness",-1);
        $interview_score          = $this->get_in_int_val("interview_score",-1);
        $set_leave_flag           = $this->get_in_int_val("set_leave_flag",-1);
        $second_interview_score   = $this->get_in_int_val("second_interview_score",-1);
        $lesson_hold_flag_adminid = $this->get_in_int_val("lesson_hold_flag_adminid",-1);
        $fulltime_flag            = $this->get_in_int_val("fulltime_flag",-1);
        $teacher_type             = $this->get_in_int_val("teacher_type",-1);
        $seller_hold_flag         = $this->get_in_int_val("seller_hold_flag",-1);
        $have_wx                  = $this->get_in_int_val("have_wx",-1);
        $grade_plan               = $this->get_in_int_val("grade_plan",-1);
        $subject_plan             = $this->get_in_int_val("subject_plan",-1);
        $fulltime_teacher_type    = $this->get_in_int_val("fulltime_teacher_type", -1);
        $month_stu_num            = $this->get_in_int_val("month_stu_num", -1);
        $record_score_num         = $this->get_in_int_val("record_score_num", -1);
        $identity                 = $this->get_in_int_val("identity", -1);
        $plan_level               = $this->get_in_int_val("plan_level", -1);
        $teacher_textbook         = $this->get_in_int_val("teacher_textbook", -1);
        if($teacher_textbook != -1){
            $teacher_textbook = $teacher_textbook;
        }

        if(!empty($free_time)){
            $teacherid_arr = $this->get_free_teacherid_arr_new($free_time);
            $arr       = explode(",",$free_time);
            $time = strtotime($arr[0]);
        }else{
            $teacherid_arr=[];
            $time = time();
        }

        $adminid     = $this->get_account_id();
        $right_list  = $this->get_tea_subject_and_right_by_adminid($adminid);
        $tea_subject = $right_list["tea_subject"];
        $tea_right   = $right_list["tea_right"];
        $qz_flag     = $right_list["qz_flag"];
        if($adminid==486 || $adminid==478 ){
            $tea_subject= "";
        }
        // $jw_permission_list=[
        //     723=>3,
        //     1329=>3,
        //     1324=>2,
        //     1328=>2,
        //     1238=>1,
        //     513=>1,
        //     436=>"-1",
        //     478=>"-1"
        // ];
        // if(isset($jw_permission_list[$adminid])){
        //     $tea_subject="";
        //     $per_subject=$jw_permission_list[$adminid];
        // }else{
        //     $per_subject=-1;
        // }
       $per_subject=-1;



        $account_info = $this->t_manager_info->get_teacher_info_by_adminid($adminid);
        $date_week    = \App\Helper\Utils::get_week_range($time,1);
        $lstart       = $date_week["sdate"];
        $lend         = $date_week["edate"];

        $ret_info = $this->t_teacher_info->get_teacher_detail_info_new(
            $page_num,$teacherid,$teacher_money_type,$need_test_lesson_flag,$textbook_type,
            $is_good_flag,$is_new_teacher,$gender,$subject,
            $trial_flag,$address,$test_flag,$is_test_user,$second_subject,
            $level,$is_freeze,$tea_subject,$limit_plan_lesson_type,$is_record_flag,
            $test_lesson_full_flag,$lstart,$lend,$train_through_new,$lesson_hold_flag,$test_transfor_per,
            $week_liveness,$interview_score,$second_interview_score,$teacherid_arr,$seller_flag,
            $qz_flag,$teacher_type,$lesson_hold_flag_adminid,$is_quit,$set_leave_flag,$fulltime_flag,$seller_hold_flag,
            $teacher_ref_type,$have_wx,$grade_plan,$subject_plan,$fulltime_teacher_type,$month_stu_num,
            $record_score_num,$identity,$plan_level,$teacher_textbook,$per_subject
        );

        $tea_list = [];
        foreach($ret_info["list"] as $val){
            $tea_list[] = $val["teacherid"];
        }

        $lesson_tea_list = $this->t_lesson_info->get_teacher_lesson_list_by_week($tea_list);
        $arr_tea_list    = [];
        foreach($lesson_tea_list as &$item){
            $teacherid = $item['teacherid'];
            $start     = date('m-d H:i:s',$item['lesson_start']);
            $end       = date('H:i:s',$item['lesson_end']);
            E\Esubject::set_item_value_str($item,"subject");
            $subject = $item['subject_str'];
            @$arr_tea_list[$teacherid] .= $start."-".$end." ".$subject;
        }

        $test_lesson_num_list = $this->t_lesson_info->get_teacher_lesson_num_list($tea_list,$lstart,$lend);
        foreach($ret_info['list'] as  &$item){
            $revisit_info = $this->t_teacher_record_list->get_jw_revisit_info($item["teacherid"]);
            $item["class_will_type"]     = $revisit_info["class_will_type"];
            $item["class_will_sub_type"] = $revisit_info["class_will_sub_type"];
            $item["revisit_add_time"]    = $revisit_info["add_time"];
            $item["recover_class_time"]  = $revisit_info["recover_class_time"];
            $item["revisit_record_info"] = $revisit_info["record_info"];
            E\Eteacher_type::set_item_value_str($item,"teacher_type");
            E\Eboolean::set_item_value_str($item,"need_test_lesson_flag");
            E\Egender::set_item_value_str($item,"gender");

            E\Esubject::set_item_value_str($item,"subject");
            E\Egrade_range::set_item_value_str($item,"grade_start");
            E\Egrade_range::set_item_value_str($item,"grade_end");

            E\Esubject::set_item_value_str($item,"second_subject");
            E\Egrade_range::set_item_value_str($item,"second_grade_start");
            E\Egrade_range::set_item_value_str($item,"second_grade_end");

            E\Eidentity::set_item_value_str($item);
            $item['user_agent'] = \App\Helper\Utils::get_user_agent_info($item['user_agent']);
            $item['level_str'] = \App\Helper\Utils::get_teacher_level_str($item);
            E\Eteacher_money_type::set_item_value_str($item);
            E\Eteacher_ref_type::set_item_value_str($item); //是否全职
            E\Etextbook_type::set_item_value_str($item);
            E\Eteacher_is_good::set_item_value_str($item,"is_good_flag");
            E\Elimit_plan_lesson_type::set_item_value_str($item,"limit_plan_lesson_type");
            \App\Helper\Utils::unixtime2date_for_item($item,"freeze_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"limit_plan_lesson_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"train_through_new_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_hold_flag_time","_str");
            E\Eclass_will_type::set_item_value_str($item);
            E\Eclass_will_sub_type::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item, "revisit_add_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item, "recover_class_time","_str");

            if($item["train_through_new_time"] !=0){
                $lecture = $this->t_teacher_record_list->get_data_to_teacher_flow_id(E\Etrain_type::V_4, $item['teacherid']);
                if ($lecture) {
                    $item["work_day"] = \App\Helper\Utils::change_time_difference_to_day($lecture['add_time']);
                } else {
                    $item['work_day'] = "";
                }
            }else{
                $item["work_day"] ="";
            }
            $item["is_freeze_str"]         = $item["is_freeze"]==0?"非冻结":"已冻结";
            $item["lesson_info_week"]      = @$arr_tea_list[$item['teacherid']];
            $item["test_user_str"]         = $item['is_test_user']==0?"否":"是";
            $item["train_through_new_str"] = $item['train_through_new']==0?"否":"是";

            $item['phone_spare']=\App\Helper\Utils::get_teacher_contact_way($item);
            \App\Helper\Utils::hide_item_phone($item,"phone_spare");
            if(!empty($item["freeze_adminid"])){
                $item["freeze_adminid_str"] = $this->t_manager_info->get_account($item["freeze_adminid"]);
            }else{
                $item["freeze_adminid_str"]="";
            }

            $item["week_lesson_num"]=@$test_lesson_num_list[$item["teacherid"]]["num"];
            if($item["limit_plan_lesson_type"]>0){
                $item["week_left_num"]=$item["limit_plan_lesson_type"]-$item["week_lesson_num"];
            }else{
                $ret_num = $this->t_lesson_info->check_teacher_have_test_lesson_pre_week($item["teacherid"],time());
                if($ret_num==1){
                    $item["week_left_num"]=$item["limit_week_lesson_num"]-$item["week_lesson_num"];
                }else{
                    $item["week_left_num"]=6-$item["week_lesson_num"];
                }
            }
            if($item["week_left_num"]<0){
                $item["week_left_num"]=0;
            }

            if(empty( $item["interview_access"])){
                $item["interview_access"] = $this->t_teacher_info->get_interview_access($item["teacherid"]);
                if($item['interview_access']==""){
                    $item['interview_access'] = $this->t_teacher_record_list->get_teacher_interview_access($item['teacherid']);

                }
            }

            $not_grade_arr = explode(",",$item["not_grade"]);
            $not_grade_str = "";
            if(!empty($not_grade_arr)){
                foreach($not_grade_arr as $ss){
                    $not_grade_str  .= E\Egrade::get_desc($ss).",";
                }
            }
            $item["not_grade_str"] = trim($not_grade_str,",");
            $acc1 = $this->t_teacher_lecture_info->get_interview_acc($item["phone"]);
            $acc2 = $this->t_teacher_record_list->get_interview_acc($item["phone"]);
            if(!empty($acc1)){
                $item["interview_acc"]=$acc1;
            }elseif(!empty($acc2)){
                $item["interview_acc"]=$acc2;
            }else{
                $item["interview_acc"]="";
            }

            if(empty($item["address"])){
                $item["address"] = \App\Helper\Common::get_phone_location($item["phone"]);
            }

            $arr_text= explode(",",$item["teacher_textbook"]);
            foreach($arr_text as $vall){
                @$item["textbook"] .=  E\Eregion_version::get_desc ($vall).",";
            }
            $item["textbook"] = trim($item["textbook"],",");

            if(empty($item["teacher_tags"])){
                $item["teacher_tags"]="";
            }else{
                $tag= json_decode($item["teacher_tags"],true);
                if(is_array($tag)){
                    $str_tag="";
                    foreach($tag as $d=>$t){
                        $str_tag .= $d."  ".$t."<br>";
                    }
                    $item["teacher_tags"] = $str_tag;
                }
            }
            if($item["test_transfor_per"]>=20){
                $item["fine_dimension"]="维度A";
            }elseif($item["test_transfor_per"]>=10 && $item["test_transfor_per"]<20){
                $item["fine_dimension"]="维度B";
            }elseif($item["test_transfor_per"]<10 && in_array($item["identity"],[5,6]) && $item["month_stu_num"]>=4 && $item["record_score"]>=60 && $item["record_score"]<=90){
                $item["fine_dimension"]="维度C";
            }elseif($item["test_transfor_per"]<10 && !in_array($item["identity"],[5,6]) && $item["month_stu_num"]>=4 && $item["record_score"]<=90){
                $item["fine_dimension"]="维度C候选";
            }elseif($item["test_transfor_per"]<10 && in_array($item["identity"],[5,6]) && $item["month_stu_num"]>=1 && $item["month_stu_num"]<=3 && $item["record_score"]>=60 && $item["record_score"]<=90){
                $item["fine_dimension"]="维度D";
            }elseif($item["test_transfor_per"]<10 && !in_array($item["identity"],[5,6]) && $item["month_stu_num"]>=1 && $item["month_stu_num"]<=3 && $item["record_score"]<=90){
                $item["fine_dimension"]="维度D候选";
            }else{
                $item["fine_dimension"]="其他";
            }

            // 全职兼职 2017-12-19
            $item['full_flag'] = false;
            if ($item['teacher_money_type'] == 7 || ($item['teacher_type'] == 3 && $item['teacher_money_type'] == 0)) {
                $item['full_flag'] = true;
            }
        }

        $account_role    = $this->get_account_role();
        $week_num_person = $this->t_teacher_info->get_week_info_new();
        $jw_teacher_list = $this->t_manager_info->get_jw_teacher_list_new();
        return $this->pageView(__METHOD__,$ret_info,[
            "week_num_person" => $week_num_person,
            "acc"             => session("acc"),
            "tea_right"       => $tea_right,
            "account_role"    => $account_role,
            "jw_teacher_list" => $jw_teacher_list
        ]);
    }

    public function index_new()
    {
        $trial_flag = 1;
        $test_flag  = 1;

        $this->set_in_value("trial_flag",$trial_flag);
        $this->set_in_value("test_flag",$test_flag);

        return $this->index();
    }

    public function get_free_teacherid_arr_new($free_time){
        $arr       = explode(",",$free_time);
        $free_start = strtotime($arr[0]);
        $free_end = strtotime(@$arr[1]);

        $teacherid_arr = $this->t_lesson_info->get_test_lesson_num_by_free_time_new($free_start,$free_end);
        return $teacherid_arr;
    }

    public function get_free_teacherid_arr($free_time)
    {
        $free_start = strtotime($free_time);
        $free_end   = $free_start+3600;
        $ret_free   = $this->t_teacher_freetime_for_week->get_teacherid_and_freetime();

        $free_time_arr = $teacherid_arr = [];
        foreach($ret_free as $k=>$v){
            $free = json_decode($v['free_time_new'],true);
            if(!empty($free)){
                foreach($free as $item){
                    $rr = strtotime($item[0]);

                    $free_time_arr[$k][] = date("Y-m-d H:i",$rr);
                }
            }
        }
        foreach($free_time_arr as $k=>$s){
            if(in_array($free_time,$s)){
                $teacherid_arr[] = $k;
            }
        }

        $test_info = $this->t_lesson_info->get_test_lesson_num_by_free_time($free_start,$free_end,$teacherid_arr);
        foreach($teacherid_arr as $kk=>$vv){
            if(isset($test_info[$vv])){
                unset($teacherid_arr[$kk]);
            }
        }
        return $teacherid_arr;
    }

    public function assistant_info2()
    {
        $is_part_time = $this->get_in_int_val('is_part_time',-1);
        $ass_nick     = $this->get_in_str_val('ass_nick',"");
        $phone        = $this->get_in_str_val('phone',"");
        $score        = $this->get_in_int_val('score',-1);
        $page_num     = $this->get_in_int_val('page_num',-1);

        if($page_num < 1){
            $page_num = 1;
        }
        $ret_info = $this->t_assistant_info->get_ass_info_list_for_hr($is_part_time, $ass_nick, $phone, $score, $page_num);
        $number   = ($page_num-1)*10+1;
        foreach($ret_info['list'] as &$item){
            $birth_year = substr((string)$item['birth'], 0, 4);
            $age        = (int)date('Y', time()) - (int)$birth_year;

            $item['number']       = $number;
            $item['ass_nick']     = $item['nick'];
            $item['is_part_time'] = E\Eassistant_type::get_desc($item['assistant_type']);
            $item['gender']       = E\Egender::get_desc($item['gender']);
            $item['age']          = $age;

            $number++;
        }
        // $s_url = get_url('human_resource','assistant_info',"?ass_nick=$ass_nick&is_part_time=$is_part_time&phone=$phone&score=$score&page_num={Page}");
        // $this->setTplPageInfo($s_url, $ret_info['total_num'] , TEA_PER_PAGE , $page_num);
        return $this->pageView(__METHOD__,$ret_info);
    }


    public function assistant_info_new()
    {
        $is_part_time = $this->get_in_int_val('is_part_time',-1);
        $rate_score   = $this->get_in_int_val('rate_score',-1);
        $assistantid  = $this->get_in_int_val('assistantid',-1);
        $page_num     = $this->get_in_page_num();
        $ret_info     = $this->t_assistant_info->get_ass_info_new($is_part_time,$assistantid , $rate_score, $page_num);
        foreach($ret_info['list'] as &$item){
            E\Egender::set_item_value_str($item);
            $birth_year           = substr((string)$item['birth'], 0, 4);
            $age                  = (int)date('Y', time()) - (int)$birth_year;
            $item['ass_nick']     = $item['nick'];
            $item['is_part_time'] = E\Eassistant_type::get_desc($item['assistant_type']);
            // $item['age'] = $age;
            $item['age']    = $item['birth']?$age:'';
            $item['school'] = $item['school']?$item['school']:'';
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function add_ass_new()
    {
        $ass_nick       = $this->get_in_str_val('ass_nick',"");
        $gender         = $this->get_in_int_val('gender', -1);
        $birth          = $this->get_in_str_val('birth',"");
        $work_year      = $this->get_in_int_val('work_year', 0);
        $phone          = $this->get_in_str_val('phone',"");
        $email          = trim($this->get_in_str_val('email',""));
        $assistant_type = $this->get_in_int_val('assistant_type',-1);
        $school         = $this->get_in_str_val('school',"");


        if($ass_nick == "" || $birth == "" || $phone == "" || $assistant_type == -1 || $gender == -1){
            return outputJson(array('ret' => -1, 'info'  => '参数不完整，请检查后重新确认'));
        }
        $split_arr=preg_split("/@/",$email);

        $account=$split_arr[0];
        if (!$account ) {
            return $this->output_err("邮箱要填");
        }
        if($this->t_manager_info->get_id_by_account($account)) {
            return $this->output_err("用户已经存在");
        }


        $ret_num = $this->t_phone_to_user->is_phone_valid($phone, 3);
        if($ret_num['num'] > 0)   {
            return outputJson(array('ret' => -1, 'info'  => '111账号已经存在'));
        }

        $tmp = explode('-',$birth);
        $birth = "";
        foreach($tmp as $value){
            $birth .= $value;
        }
        srand(microtime(true) * 1000);
        $passwd = "142857";
        $md5_passwd = md5(md5($passwd)."#Aaron");
        $this->t_user_info->row_insert([
            "passwd"  => md5($passwd)
        ]);
        $assid =  $this->t_user_info->get_last_insertid();
        if($assid === false){
            return outputJson(array('ret' => -1, 'info'  => '插入失败'));
        }

        //加入ejabberd 账号让助教可以进入课堂
        //$this->users->add_ejabberd_account($assid,md5($passwd));
        //加入ejabberd监控账号 以ad开头
        //$this->users->add_ejabberd_account("ad_" . $assid,md5($passwd));

        $ret_db = $this->t_phone_to_user->add_phone_to_ass($assid, $phone);
        if($ret_db === false){
            return outputJson(array('ret' => -1, 'info'  => '插入失败'));
        }


        $ret_db = $this->t_assistant_info->add_new_ass($ass_nick, $gender, $birth, $work_year, $phone, $email,
                                                       $assistant_type, $assid, $school);
        if($ret_db === false) {
            return outputJson(array('ret' => -1, 'info'  => '插入失败'));
        }
        /*
        $message = "您的理优教育账号为:". $phone ."密码为:".$passwd;
        send_message($message, $phone);
        */



        //注册后台账号
        $passwd = $md5_passwd ;

        $adminid=$this->get_account_id();
        $this->t_admin_users->row_insert([
            "account" => $account,
            "password" => $passwd,
            "create_time" =>time(NULL) ,
        ]);
        $uid=$this->t_admin_users->get_last_insertid();
        $this->t_manager_info->row_insert([
            "uid" => $uid,
            "account" => $account,
            "name" => $ass_nick,
            "email" => $email,
            "phone" => $phone,
            "create_time" => time(NULL) ,
            "permission" => "59", //助教-一般
            "account_role" => E\Eaccount_role::V_1,
            "creater_adminid" => $this->get_account_id(),
        ]);


        return $this->output_succ();

    }

    public function delete_teacher()
    {
        $teacherid = $this->get_in_int_val('teacherid',-1);
        // teacher 0 assistant 1
        $teacher_type = $this->get_in_int_val('teacher_type',0);



        if($teacher_type == 0){
            $ret_auth = $this->t_manager_info->check_permission($this->get_account(), TEA_ARCHIVES);
            if(!$ret_auth)
                return outputJson(array('ret' => NOT_AUTH, 'info' => $this->err_string[NOT_AUTH]));

            $this->t_teacher_info->delete_teacher($teacherid);
            $this->t_user_info->delete_user($teacherid, 2);
        }elseif($teacher_type == 1){
            $ret_auth = $this->t_manager_info->check_permission($this->get_account(), ASS_ARCHIVES);
            if(!$ret_auth)
                return outputJson(array('ret' => NOT_AUTH, 'info' => $this->err_string[NOT_AUTH]));
            $this->t_assistant_info->delete_ass($teacherid);
            $ret_admin_id = $this->t_adid_to_adminid->get_admin_from_ad($teacherid);
            $this->t_manager_info->del_ass_manager($ret_admin_id);
            $this->t_user_info->delete_user($teacherid, 3);
        }
        return outputJson(array('ret' =>0 , 'info' => '删除成功'));
    }

    public function ass_detail_info()
    {
        $assistantid = $this->get_in_int_val('assistantid',-1);
        $ret_db      = $this->t_assistant_info>get_ass_page_info($assistantid);

        $year  = substr($ret_db['birth'], 0, 4);
        $month = substr($ret_db['birth'], 4, 2);
        $day   = substr($ret_db['birth'], 6, 2);

        $birth = $year."-".$month."-".$day;
        if($ret_db['face'] != "")
            $face = "<img width=\"200\" height=\"200\" src=\""  .$ret_db['face'] . "\"/>";
        else
            $face = "<img src='/images/header_img.jpg' />";
        $ass_info = array(
            'ass_nick'     => $ret_db['nick'],
            'birth'        => $birth,
            'gender'       => $this->g_config['gender'][$ret_db['gender']],
            'gender_num'   => $ret_db['gender'],
            'phone'        => $ret_db['phone'],
            'email'        => $ret_db['email'],
            'face'         => $face,
            'school'       => $ret_db['school'],
            'is_part_time' => $ret_db['assistant_type']?'兼职':'全职',
            'rate_score'   => $ret_db['rate_score'],
            'ass_style'    => $ret_db['ass_style'],
            'achievement'  => $ret_db['achievement'],
            'base_intro'   => $ret_db['base_intro'],
            'work_year'    => $ret_db['work_year'],
            'prize'        => $ret_db['prize'],
        );

        outputJson(array('ret' => 0, 'info' => $ass_info));
    }

    public function update_assistant_info()
    {
        $assistantid    = $this->get_in_int_val('assistantid', -1);
        $ass_nick       = $this->get_in_str_val('ass_nick', "");
        $gender         = $this->get_in_int_val('gender', 0);
        $work_year      = $this->get_in_int_val('work_year',0);
        $assistant_type = $this->get_in_int_val('assistant_type',0);
        $email          = $this->get_in_str_val('email',"");
        $ass_style      = $this->get_in_str_val('ass_style',"");
        $achievement    = $this->get_in_str_val('achievement',"");
        $birth          = $this->get_in_str_val('birth', "");
        $school         = $this->get_in_str_val('school',"");
        $base_intro     = $this->get_in_str_val('base_intro',"");
        $prize          = $this->get_in_str_val('prize',"");

        $ret_auth = $this->t_manager_info->check_permission($this->get_account(), ASS_ARCHIVES);
        if(!$ret_auth)
            return outputJson(array('ret' => NOT_AUTH, 'info' => $this->err_string[NOT_AUTH]));

        $tmp = explode("-",$birth);
        $birth = $tmp[0] . $tmp[1] . $tmp[2];
        $this->t_assistant_info->modify_ass_detail_info($assistantid, $ass_nick, $gender, $work_year, $school,
                                                        $email, $ass_style, $achievement, $birth, $base_intro, $assistant_type, $prize);

        return outputJson(array('ret' => 0));
    }

    public function set_assistant_face()
    {
        $key         = $this->get_in_str_val('key',"");
        $assistantid = $this->get_in_int_val('assistantid', -1);
        if($key == "")
            return outputJson(array('ret' => -1, 'info' => 'key不能为空'));

        $domain = config('admin')['qiniu']['public']['url'];
        $url = "http://" . $this->g_config['qiniu']['download_pub'] . "/" . $key;
        //$url = $domain."/". $key;
        //return $this->output_err("hahaha!");
        $ret_db = $this->t_assistant_info->change_ass_face($url, $assistantid);
        if($ret_db > 0)
            return outputJson(array('ret' => 0, 'info' => '成功'));
        else
            return outputJson(array('ret' => -1, 'info' => '失败'));
    }

    public function get_assistant_info()
    {
        $assistantid = $this->get_in_int_val('assistantid',-1);
        $row=$this->t_assistant_info->field_get_list($assistantid,"*");
        return outputjson_success(["data"=> $row  ]);

    }
    public function get_assistant_info2()
    {
        $assistantid = $this->get_in_int_val('assistantid',-1);

        $ret_info=$this->t_assistant_info->get_assistant_list($assistantid);

        return outputJson(array('ret'=>0,'ret_info'=>$ret_info));

    }

    public function update_assistant_info2()
    {
        $assistantid = $this->get_in_int_val('assistantid',-1);
        $nick        = $this->get_in_str_val('name');
        $birth       = str_replace('-','',$this->get_in_str_val('birth'));
        $gender      = $this->get_in_int_val('sex');
        $work_year   = $this->get_in_int_val('years');
        $school      = $this->get_in_str_val('school');
        $phone       = $this->get_in_str_val('phone');
        $email       = $this->get_in_str_val('email');
        $assistant_type= $this->get_in_int_val('job');
        $assistant_type = $assistant_type<0?0:$assistant_type;

        $ret_info = $this->t_assistant_info->update_assistant_info3($assistantid,$nick,$birth,$gender,$work_year,$school,$phone,$email,$assistant_type);

        if ($ret_info === false) {
           return outputJson(array('ret' => -1, 'info' => '错误'));
           }
        return outputJson(array('ret' => 0, 'info' => '成功'));
    }

    public function add_teacher()
    {
        return $this->output_err("接口停用");
        $grade        = $this->get_in_int_val('grade',-1);
        $grade_ex     = $this->get_in_int_val('grade_ex',-1);
        $subject      = $this->get_in_int_val('subject',0);
        $degree       = $this->get_in_int_val('degree',-1);
        $teacher      = $this->get_in_int_val('teacher',-1);
        $introduction = $this->get_in_str_val('introduction','');

        if($grade_ex>$grade){
            $grade_arr=E\Egrade::$simple_desc_map;
            foreach($grade_arr as $key=>$val){
                if($key>=$grade && $key<=$grade_ex){
                    $ret_info=$this->t_teacher_closest->add_teacher_info($key,$subject,$degree,$teacher,$introduction);
                }
            }
        }else{
            $ret_info = $this->t_teacher_closest->add_teacher_info($grade,$subject,$degree,$teacher,$introduction);
        }

        if ($ret_info === false) {
           return outputJson(array('ret' => -1, 'info' => '错误'));
        }
        return outputJson(array('ret' => 0, 'info' => '成功'));
    }


    public function get_simple_teacher_info()
    {
        $teacherid = $this->get_in_int_val('teacherid',-1);
        $row       = $this->t_teacher_closest->field_get_list($teacherid,"*");

        return outputjson_success(["data"=> $row  ]);

    }
    public function edit_teacher()
    {
        $teacherid    = $this->get_in_int_val('teacherid',-1);
        $grade        = $this->get_in_int_val('grade',-1);
        $subject      = $this->get_in_int_val('subject',0);
        $degree       = $this->get_in_int_val('degree',-1);
        $introduction = $this->get_in_str_val('introduction','');

        $ret_info     = $this->t_teacher_closest->update_teacher_list($teacherid,$grade,$subject,$degree,$introduction);
        if ($ret_info === false) {
           return outputJson(array('ret' => -1, 'info' => '错误'));
           }
        return outputJson(array('ret' => 0, 'info' => '成功'));

    }

    public function delete_tea_closest()
    {
        $teacherid = $this->get_in_teacherid();
        $subject   = $this->get_in_subject();
        $grade     = $this->get_in_grade();

        $ret_info = $this->t_teacher_closest->delete_tea_info($teacherid,$subject,$grade);
        if ($ret_info === false) {
            return outputJson(array('ret' => -1, 'info' => '错误'));
        }
        return outputJson(array('ret' => 0, 'info' => '成功'));
    }

    public function get_apply_info(){
        list($start_time,$end_time) =        $this->get_in_date_range(-30,0);
        $user_name = $this->get_in_str_val('user_name',"");
        $page_num           = $this->get_in_page_num();
        $ret_info = $this->t_apply_reg->get_apply_info($page_num,$start_time,$end_time,$user_name);
        foreach($ret_info['list'] as &$item){
            $item['add_time'] = date('Y-m-d',$item['add_time']);
            E\Egender::set_item_value_str($item );
            if(!empty($item['trial_start_time'])){
                $item['trial_start_time_str'] = date('Y-m-d',$item['trial_start_time']);
            }else{
                $item['trial_start_time_str']="";
            }
            if(!empty($item['trial_end_time'])){
                $item['trial_end_time_str'] = date('Y-m-d',$item['trial_end_time']);
            }else{
                $item['trial_end_time_str']="";
            }


        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function teacher_meeting_info(){
        $page_num    = $this->get_in_page_num();
        list($start_time,$end_time)=$this->get_in_date_range( -30,0);
        $ret_info = $this->t_teacher_meeting_info->get_all_info($start_time,$end_time,$page_num);
        foreach($ret_info['list'] as &$item){
            $item['create_time_str'] = date('Y-m-d H:i:s',$item['create_time']);
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function teacher_meeting_join_info(){
        $create_time = $this->get_in_str_val('create_time');
        if (!$create_time) {
            return $this->error_view(
                [
                    " 没有会议信息 ",
                    " 请从[会议记录] 点击\"进入与会老师信息\"进来 ",
                ]
            );
        } else {
            $create_time_str= date('Y-m-d H:i:s',$create_time);
            $teacherid = $this->get_in_int_val('teacherid',-1);
            $subject = $this->get_in_int_val('subject',-1);
            $page_count        = $this->get_in_page_count();
            $page_num    = $this->get_in_page_num();
            $ret_info = $this->t_teacher_info->get_teacher_info_all_by_page($teacherid,$create_time,$page_num,$subject);
            $xishu=[0=>1,1=>0.7,2=>0];
            $real_page_num=$ret_info["page_info"]["page_num"]-1;
            foreach( $ret_info["list"] as $index=> &$item ) {
                $item["index"] = $real_page_num*$page_count+ $index +1;
                E\Eteacher_join_info::set_item_value_str($item,"join_info" );
                $item["xishu"] = $xishu[$item['join_info']];
            }
            return $this->pageView(__METHOD__,$ret_info,["create_time_str"=>$create_time_str,"create_time"=>$create_time]);

        }

    }

    public function teacher_assess(){
        list($start_time,$end_time)=$this->get_in_date_range( -30,0);
        $teacherid      = $this->get_in_int_val('teacherid',-1);
        $page_num    = $this->get_in_page_num();
        $ret_info = $this->t_teacher_assess->get_assess_info($teacherid,$page_num,$start_time,$end_time);
        foreach($ret_info["list"] as &$item){
            $item['assess_nick'] = $this->cache_get_account_nick($item['assess_adminid']);
            $item['assess_res_str'] = E\Eassess_res::get_desc($item["assess_res"]) ;
            $item['assess_time_str'] = date("Y-m-d H:i:s",$item["assess_time"]) ;
            $item['nick'] = $this->t_teacher_info->get_nick($item['teacherid']);
            $item['assess_num'] = $this->t_teacher_info->field_get_value($item['teacherid'],"assess_num");

        }
        return $this->pageView(__METHOD__,$ret_info);

    }

    public function teacher_lecture_list_fulltime(){
        $this->set_in_value("fulltime_flag",1);
        return $this->teacher_lecture_list();
    }

    public function teacher_lecture_list_zs(){
        $this->set_in_value("zs_flag",1);
        return $this->teacher_lecture_list();
    }

    public function teacher_lecture_list_zj(){
        return $this->teacher_lecture_list();
    }

    public function teacher_lecture_list_research(){
        return $this->teacher_lecture_list();
    }

    public function teacher_lecture_list(){
        $this->switch_tongji_database();
        list($start_time,$end_time,$opt_date_type) = $this->get_in_date_range(date("Y-m-01",time(NULL)),0,1,[
            1 => array("add_time","添加时间"),
            2 => array("confirm_time", "审核时间"),
        ]);
        $adminid     = $this->get_account_id();
        $right_list  = $this->get_tea_subject_and_right_by_adminid($adminid);
        $tea_subject = $right_list["tea_subject"];
        $tea_right   = $right_list["tea_right"];
        $qz_flag     = $right_list["qz_flag"];

        if($adminid==486){
            $tea_subject="";
        }elseif($adminid==952){
            $tea_subject="(6)";
        }elseif($adminid==770){
            $tea_subject="(4,6)";
        }elseif($adminid==895){
            $tea_subject="(7,8,9)";
        }elseif($adminid==793){
            $tea_subject="(5,10)";
        }elseif($adminid==790){
            $tea_subject="(1,3,7,8,9)";
        }

        $grade         = $this->get_in_int_val("grade",-1);
        $trans_grade   = $this->get_in_int_val("trans_grade",-1);
        $subject       = $this->get_in_int_val("subject",-1);
        $identity      = $this->get_in_int_val('identity',-1);
        $status        = $this->get_in_int_val("status",0);
        $page_num      = $this->get_in_page_num();
        $phone         = trim($this->get_in_str_val('phone',''));
        $teacherid     = $this->get_in_int_val('teacherid',-1);
        $is_test_flag  = $this->get_in_int_val('is_test_flag',0);
        $have_wx       = $this->get_in_int_val('have_wx',-1);
        $full_time     = $this->get_in_int_val('full_time',-1);
        $fulltime_flag = $this->get_in_int_val('fulltime_flag');
        $id_train_through_new_time = $this->get_in_int_val("id_train_through_new_time",-1);
        //如果用电话号码检索不区分库
        if($phone)
            $tea_subject = '';

        //判断招师主管
        $is_master_flag = $this->t_admin_group_name->check_is_master(8,$adminid);
        //判断是否是招师
        $is_zs_flag = (($this->t_admin_group_user->get_main_type($adminid))==8)?1:0;
        if($is_zs_flag==1 && $is_master_flag !=1 && $adminid!=790){
            $accept_adminid = $adminid;
            $id_train_through_new=0;
        }else{
            $accept_adminid = -1;
            $id_train_through_new=-1;
        }

        $id_train_through_new      = $this->get_in_int_val("id_train_through_new", $id_train_through_new);
        $zs_flag = $this->get_in_int_val('zs_flag',0);

        if($fulltime_flag==1){
            $full_time=1;
        }

        $this->t_teacher_lecture_info->switch_tongji_database();
        $ret_info = $this->t_teacher_lecture_info->get_teacher_lecture_list(
            $page_num,$opt_date_type,$start_time,$end_time,$grade,$subject,$status,$phone,$teacherid,$tea_subject,$is_test_flag,
            $trans_grade,$have_wx,$full_time,$id_train_through_new_time,$id_train_through_new,$accept_adminid,$identity
        );

        $num = 0;
        if(is_array($ret_info['list'])){
            foreach($ret_info['list'] as &$val){
                if($val['t_teacherid']>0 && $val['add_time']>$val['t_create_time'] && $val['t_subject']==$val['subject'] ){
                    $val['trans_grade'] = 1;
                }else{
                    $val['trans_grade'] = 0;
                }
                E\Eboolean::set_item_value_str($val,"trans_grade");
                E\Eboolean::set_item_value_str($val,"full_time");
                $num++;
                $val['num'] = $num;
                \App\Helper\Utils::unixtime2date_for_item($val,"add_time","_str");
                \App\Helper\Utils::unixtime2date_for_item($val,"answer_begin_time","_str");
                \App\Helper\Utils::unixtime2date_for_item($val,"confirm_time","_str");
                E\Eidentity::set_item_value_str($val);
                E\Esubject::set_item_value_str($val);
                if(empty($val["grade"])){
                    $val["grade"] = intval($val["grade_ex"]);
                }
                E\Egrade::set_item_value_str($val);
                E\Eis_test::set_item_value_str($val,"is_test_flag");
                E\Echeck_status::set_item_value_str($val,"status");
                $val['t_subject_str']="";
                if($val["t_subject"]>0 && $val["t_subject"] != $val["subject"]){
                    E\Esubject::set_item_value_str($val,"t_subject");
                }
                $val['textbook'] = $this->get_teacher_textbook($val['subject'],$val['textbook']);
                $this->change_https_to_http($val['audio']);
                $this->change_https_to_http($val['draw']);
                if($val["wx_openid"]){
                    $val["have_wx_flag"]="是";
                }else{
                    $val["have_wx_flag"]="否";
                }
                if($val['train_through_new_time'] >0){
                    $val['train_status_str'] = "已通过";
                }else{
                    $val['train_status_str'] = "未通过";
                }
                if($val['train_through_new'] == 1){
                    $val['train_through_str'] = "已通过";
                }else{
                    $val['train_through_str'] = "未通过";
                }
                $val["phone_ex"] = preg_replace('/(1[356789]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$val["phone"]);

            }
        }

        return $this->pageView(__METHOD__, $ret_info ,[
            "acc"         => session("acc"),
            "tea_subject" => $tea_subject,
            "adminid"     => $adminid,
            "account_role" => session("account_role"),
            "zs_flag"      =>$zs_flag
        ]);
    }

    public function change_https_to_http(&$url){
        $length = strlen($url);
        if(substr($url,0,5)=="https"){
            $url = "http".substr($url,5,$length);
        }
    }

    public function get_week_confirm_num(){
        $adminid  = $this->get_in_int_val("adminid");
        if($adminid !=499 && $adminid !=505){
            $num=0;
        }else{
            //$adminid=499;
            $date_week = \App\Helper\Utils::get_week_range(time(),1);
            $date = strtotime(date("Y-m-d",$date_week["sdate"] + 86400)." 10:00:00");
            if($date >= time()){
                $date_week_pre = \App\Helper\Utils::get_week_range(time()-7*86400,1);
                $date_pre = strtotime(date("Y-m-d",$date_week_pre["sdate"] + 86400)." 10:00:00");
                $num = $this->t_teacher_lecture_info->get_week_confirm_num($adminid,$date_pre,$date);
            }else{
                $date_week_next = \App\Helper\Utils::get_week_range(time()+7*86400,1);
                $date_next = strtotime(date("Y-m-d",$date_week_next["sdate"] + 86400)." 10:00:00");
                $num = $this->t_teacher_lecture_info->get_week_confirm_num($adminid,$date,$date_next);
            }

        }
        return $this->output_succ(["data"=>$num]);

    }

    public function set_teacher_lecture_account(){
        $id  = $this->get_in_int_val("id");
        $acc = $this->get_in_str_val("acc");

        $account = $this->t_teacher_lecture_info->get_account($id);
        if($account != "" && $acc!=$account){
            return $this->output_err("此试讲视频已有人审核!请审核其他视频!");
        }elseif($account==""){
            $this->t_teacher_lecture_info->field_update_list($id,[
                "account" => $acc
            ]);
        }

        return $this->output_succ();
    }

    /**
     * status 0 未审核 1 已通过 2 未通过 3 可重申
     */
    public function set_teacher_lecture_status_new(){
        $id                                 = $this->get_in_int_val("id");
        $appointment_id                     = $this->get_in_int_val("appointment_id");
        $subject                            = $this->get_in_int_val("subject");
        $grade                              = $this->get_in_int_val("grade");
        $reason                             = $this->get_in_str_val("reason");
        $lecture_content_design_score       = $this->get_in_int_val("lecture_content_design_score");
        $lecture_combined_score             = $this->get_in_int_val("lecture_combined_score");
        $course_review_score                = $this->get_in_str_val("course_review_score");
        $teacher_mental_aura_score          = $this->get_in_int_val("teacher_mental_aura_score");
        $teacher_point_explanation_score    = $this->get_in_int_val("teacher_point_explanation_score");
        $teacher_class_atm_score            = $this->get_in_int_val("teacher_class_atm_score");
        $teacher_dif_point_score            = $this->get_in_int_val("teacher_dif_point_score");
        $teacher_blackboard_writing_score   = $this->get_in_int_val("teacher_blackboard_writing_score");
        $teacher_explain_rhythm_score       = $this->get_in_int_val("teacher_explain_rhythm_score");
        $teacher_language_performance_score = $this->get_in_int_val("teacher_language_performance_score");
        $teacher_lecture_score              = $this->get_in_int_val("total_score");
        $identity                           = $this->get_in_int_val("identity");
        $work_year                          = $this->get_in_int_val("work_year");
        $sshd_good                          = $this->get_in_str_val("sshd_good");
        $not_grade                          = $this->get_in_str_val("not_grade");
        $acc                                = $this->get_account();
        $new_tag_flag                     = $this->get_in_int_val("new_tag_flag",0);
        $style_character                  = $this->get_in_str_val("style_character");
        $professional_ability             = $this->get_in_str_val("professional_ability");
        $classroom_atmosphere             = $this->get_in_str_val("classroom_atmosphere");
        $courseware_requirements          = $this->get_in_str_val("courseware_requirements");
        $diathesis_cultivation            = $this->get_in_str_val("diathesis_cultivation");

        if($identity<=0){
            return $this->output_err("请选择老师身份！");
        }

        $this->t_teacher_lecture_appointment_info->field_update_list($appointment_id,["teacher_type"=>$identity]);

        if($teacher_lecture_score<55){
            $status=2;
        }elseif($teacher_lecture_score<65){
            $status=3;
        }else{
            $status=1;
        }
        $lecture_info           = $this->t_teacher_lecture_info->get_lecture_info($id);
        $lecture_info['id']     = $id;
        $lecture_info['reason'] = $reason;

        $full_time = $this->t_teacher_lecture_appointment_info->get_full_time($appointment_id);
        if($full_time==0){
            $this->send_lecture_sms($lecture_info,$status);
        }elseif($full_time==1){
            $teacherid_ex = $this->t_teacher_info->get_teacherid_by_phone($lecture_info["phone"]);
            $wx_openid = $this->t_teacher_info->get_wx_openid($teacherid_ex);
            if($wx_openid){
                /**
                   9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ
                   {{first.DATA}}
                   评估内容：{{keyword1.DATA}}
                   评估结果：{{keyword2.DATA}}
                   时间：{{keyword3.DATA}}
                   {{remark.DATA}}
                */
                $template_id = "9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ";
                $data=[];
                if($status==1){
                    $data['first']="老师您好，恭喜您已经成功通过初试。";
                    $data['keyword1']="初试结果";
                    $data['keyword2']="通过";
                    $data['keyword3']=date("Y年m月d日 H:i:s");
                    $data['remark']="后续将有HR和您联系，请保持电话畅通。";
                }elseif($status==2){
                    $data['first']="老师您好，很抱歉您没有通过试讲审核。";
                    $data['keyword1']="初试结果";
                    $data['keyword2']="未通过";
                    $data['keyword3']=date("Y年m月d日 H:i:s");
                    $data['remark']="感谢您的投递，您的简历已进入我公司的简历库，如有需要我们会与您取得联系。";
                }elseif($status==3){
                    $data['first']="老师您好，您的试讲审核结果未可重审";
                    $data['keyword1']="初试结果";
                    $data['keyword2']="可重审,您可以再次提交试讲视频";
                    $data['keyword3']=date("Y年m月d日 H:i:s");
                    $data['remark']="感谢您的投递，您的简历已进入我公司的简历库，如有需要我们会与您取得联系。";
                }

                $url="";
                \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
            }
        }


        $this->t_teacher_lecture_info->field_update_list($id,[
            "status"                             => $status,
            "reason"                             => $reason,
            "confirm_time"                       => time(),
            "teacher_mental_aura_score"          => $teacher_mental_aura_score,
            "teacher_point_explanation_score"    => $teacher_point_explanation_score,
            "teacher_class_atm_score"            => $teacher_class_atm_score,
            "teacher_dif_point_score"            => $teacher_dif_point_score,
            "teacher_blackboard_writing_score"   => $teacher_blackboard_writing_score,
            "teacher_explain_rhythm_score"       => $teacher_explain_rhythm_score,
            "teacher_language_performance_score" => $teacher_language_performance_score,
            "lecture_content_design_score"       => $lecture_content_design_score,
            "lecture_combined_score"             => $lecture_combined_score,
            "course_review_score"                => $course_review_score,
            "teacher_lecture_score"              => $teacher_lecture_score,
        ]);

        $check_info = [
            "subject"   => $subject,
            "grade"     => $grade,
            "not_grade" => $not_grade,
        ];

        if($status==E\Echeck_status::V_1){
            $teacher_info     = $this->t_teacher_info->get_teacher_info_by_phone($lecture_info['phone']);

            $appointment_info = $this->t_teacher_lecture_appointment_info->get_appointment_info_by_id($appointment_id);
            $nick = $appointment_info['name'];
            if($full_time==1){
                $this->t_manager_info->send_wx_todo_msg_by_adminid (986,"全职老师一面通过","全职老师一面通过",$nick."老师一面通过","");
                $this->t_manager_info->send_wx_todo_msg_by_adminid (1043,"全职老师一面通过","全职老师一面通过",$nick."老师一面通过","");
                $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"全职老师一面通过","全职老师一面通过",$nick."老师一面通过","");
            }

            $notice_reference_flag = false;
            if(!empty($teacher_info)){
                if($teacher_info['trial_lecture_is_pass']==1){
                    $notice_reference_flag = true;
                }

                if($new_tag_flag==0){
                    $this->set_teacher_label($teacher_info["teacherid"],0,"",$sshd_good,3);
                }elseif($new_tag_flag==1){
                    $tea_tag_arr=[
                        "style_character"=>$style_character,
                        "professional_ability"=>$professional_ability,
                        "classroom_atmosphere"=>$classroom_atmosphere,
                        "courseware_requirements"=>$courseware_requirements,
                        "diathesis_cultivation"=>$diathesis_cultivation,
                    ];
                    $this->set_teacher_label_new($teacher_info["teacherid"],0,"",$tea_tag_arr,3); 
                }

                $this->set_teacher_label($teacher_info["teacherid"],0,"",$sshd_good,3);
                \App\Helper\Utils::logger("set teacher label_list");
                $this->check_teacher_lecture_is_pass($teacher_info);
                $ret = $this->set_teacher_grade($teacher_info,$check_info);
                if(!$ret){
                    return $this->output_err("更新老师年级出错！请重试！");
                }
            }else{
                $notice_reference_flag = true;
                \App\Helper\Utils::logger("add teacher info");
                $grade_range = \App\Helper\Utils::change_grade_to_grade_range($grade);
                $add_info    = [
                    "phone"                 => $lecture_info['phone'],
                    "identity"              => $identity,
                    "tea_nick"              => $nick,
                    "subject"               => $subject,
                    "grade"                 => $grade,
                    "grade_start"           => $grade_range['grade_start'],
                    "grade_end"             => $grade_range['grade_end'],
                    "not_grade"             => $not_grade,
                    "level"                 => 0,
                    "wx_use_flag"           => 1,
                    "trial_lecture_is_pass" => 1,
                ];
                $teacherid = $this->add_teacher_common($add_info);
                if($teacherid===false){
                    \App\Helper\Utils::logger("teacher lecture is pass,phone:".$lecture_info['phone']);
                    return $this->output_err("生成老师失败！");
                }
                //老师标签
                if($new_tag_flag==0){
                    $this->set_teacher_label($teacherid,0,"",$sshd_good,3);
                }elseif($new_tag_flag==1){
                    $tea_tag_arr=[
                        "style_character"=>$style_character,
                        "professional_ability"=>$professional_ability,
                        "classroom_atmosphere"=>$classroom_atmosphere,
                        "courseware_requirements"=>$courseware_requirements,
                        "diathesis_cultivation"=>$diathesis_cultivation,
                    ];
                    $this->set_teacher_label_new($teacherid,0,"",$tea_tag_arr,3); 
                }

                \App\Helper\Utils::logger("add teacher info, teacherid is:".$teacherid);
            }

            //老师通过试讲通知推荐人
            if($notice_reference_flag){
                $reference_info = $this->t_teacher_info->get_reference_info_by_phone($lecture_info['phone']);
                if(!empty($reference_info)){
                    $wx_openid    = $reference_info['wx_openid'];
                    $teacher_type = $reference_info['teacher_type'];
                    if($wx_openid!="" && !in_array($teacher_type,[21,22,31])){
                        $record_info = $appointment_info['name']."已通过试讲，即将进行入职培训";
                        $status_str  = "试讲通过";
                        \App\Helper\Utils::send_reference_msg_for_wx($wx_openid,$record_info,$status_str);
                    }
                }
            }

        }

        return $this->output_succ();
    }

    private function send_lecture_sms($teacher_info,$status){
        $teacher_re_submit_num = $this->t_teacher_lecture_info->get_teacher_re_submit_num($teacher_info['id']);
        if(!isset($teacher_info['phone']) || $teacher_re_submit_num>0){
            return false;
        }

        if($status==1){
            /**
             * 老师试讲通过2-14
             * SMS_46865086
             * 面试结果通知：${name}老师您好，恭喜您已经成功通过试讲，试讲反馈情况是：${reason}。
             每周我们都会组织新入职老师的在线培训，帮助各位老师熟悉软件使用，提高教学技能。
             请您准时参加培训，培训通过后我们会及时给您安排试听课。
            */
            $sms_id = 46865086;
            $arr    = [
                "name"   => $teacher_info['nick'],
                "reason" => $teacher_info['reason'],
            ];
            $info_str = "面试通过";
         }elseif($status==2){
            /**
             * 模板名称 : 老师试讲未通过2-14
             * 模板ID   : SMS_46745131
             * 模板内容 : 面试结果通知：${name}老师您好，我们详细回看了试讲视频，很抱歉您没有通过面试审核。
             理优教育致力于打造高水平的教学服务团队，期待将来您能加入理优教学团队，如对面试结果有疑问请联系招聘老师。
             */
            $sms_id = 46745131;
            $arr    = [
                "name"   => $teacher_info['nick'],
                "reason" => $teacher_info['reason'],
            ];
            $info_str = "面试淘汰";
        }elseif($status==3){
            /**
             * 模板名称 : 老师试讲可重申2-14
             * 模板ID   : SMS_46670149
             * 模板内容 : 面试结果通知：${name}老师您好，我们详细回看了试讲视频，很抱歉您没有通过面试审核。
             但您的潜力很大，我们给予您二次试讲机会。您的试讲反馈情况是：${reason}。
             理优教育致力于打造高水平的教学服务团队，期待您能通过下次面试，加油！如对面试结果有疑问请联系招聘老师。
             */
            $sms_id = 46670149;
            $arr    = [
                "name"   => $teacher_info['nick'],
                "reason" => $teacher_info['reason'],
            ];
            $info_str = "面试重审";
        }
        \App\Helper\Utils::sms_common($teacher_info['phone'],$sms_id,$arr);

        $admin_arr = [
            492 => "zoe",
            513 => "abby",
            790 => "ivy",
        ];
        $header_msg  = "老师".$info_str."通知";
        $from_user   = "理优面试组";
        $admin_url   = "http://admin.leo1v1.com/human_resource/teacher_lecture_list/?phone=".$teacher_info["phone"];
        $subject_str = E\Esubject::get_desc($teacher_info['subject']);

        foreach($admin_arr as $id => $name){
            $msg_info = $name."老师你好,".$subject_str."学科老师".$teacher_info['nick'].$info_str
                                  .",建议如下:".$teacher_info['reason'];
            $this->t_manager_info->send_wx_todo_msg_by_adminid($id,$from_user,$header_msg,$msg_info,$admin_url);
        }
    }

    public function get_teacher_simple_info(){
        $phone = $this->get_in_int_val("phone");
        $id    = $this->get_in_int_val("id");

        $ret = $this->t_teacher_info->check_teacher_phone($phone);
        if(!$ret){
            $teacherid = $this->t_phone_to_user->get_userid_by_phone($phone,E\Erole::V_TEACHER);
            if($teacherid>0){
                $this->t_phone_to_user->delete_user($teacherid,E\Erole::V_TEACHER);
            }
            $ret_info = $this->t_teacher_lecture_info->get_simple_info_new($id);
            return $this->output_succ(["data"=>$ret_info]);
        }else{
            return $this->output_err("老师已存在!");
        }
    }

    public function set_teacher_is_test(){
        $teacherid    = $this->get_in_int_val("teacherid");
        $is_test_user = $this->get_in_int_val("is_test_user");

        $ret = $this->t_teacher_info->field_update_list($teacherid,[
            "is_test_user" => $is_test_user
        ]);

        if($ret>0){
            return $this->output_succ();
        }else{
            return $this->output_err("更换失败!请重试!");
        }
    }

    public function add_lecture_revisit_record(){
        $phone          = $this->get_in_str_val('phone');
        $revisit_note   = $this->get_in_str_val('revisit_note');
        $revisit_origin = $this->get_in_int_val('revisit_origin');
        $sys_operator   = $this->get_account();
        $revisit_time   = time();

        $this->t_lecture_revisit_info->row_insert([
            "phone"             => $phone,
            "revisit_time"      => $revisit_time,
            "revisit_origin"    => $revisit_origin,
            "sys_operator"      => $sys_operator,
            "revisit_note"      => $revisit_note
        ]);
        return $this->output_succ();
    }

    public function add_new_teacher_revisit_record(){
        $teacherid      = $this->get_in_int_val('teacherid');
        $revisit_note   = $this->get_in_str_val('revisit_note');
        $class_will_type   = $this->get_in_int_val('class_will_type');
        $class_will_sub_type   = $this->get_in_int_val('class_will_sub_type');
        $recover_class_time   = $this->get_in_str_val('recover_class_time');
        $free_time   = $this->get_in_str_val('free_time');
        $teacher_textbook   = $this->get_in_str_val('teacher_textbook');
        $region   = $this->get_in_str_val('region');
        $work_year   = $this->get_in_int_val('work_year');
        $gender   = $this->get_in_int_val('gender');
        if(!empty($recover_class_time)){
            $recover_class_time = strtotime($recover_class_time);
        }else{
            $recover_class_time=0;
        }
        $acc            = $this->get_account();
        $add_time   = time();
        $type=5;
        $this->t_teacher_record_list->row_insert([
            "teacherid"               => $teacherid,
            "add_time"                => $add_time,
            "acc"                     => $acc,
            "record_info"             => $revisit_note,
            "type"                    => $type,
            "class_will_sub_type"     =>$class_will_sub_type,
            "class_will_type"         =>$class_will_type,
            "recover_class_time"      =>$recover_class_time,
            "free_time"               =>$free_time,
            "teacher_textbook"        =>$teacher_textbook,
            "region"                  =>$region,
            "work_year"               =>$work_year,
            "gender"                  =>$gender
        ]);
        // $this->t_teacher_info->field_update_list($teacherid,[
        //     "gender"  =>$gender,
        //     "teacher_textbook" =>$teacher_textbook,
        //     "work_year"        =>$work_year,
        //     "address"          =>$region,
        //     "free_time"        =>$free_time
        // ]);
        return $this->output_succ();
    }


    public function get_lecture_revisit_info(){
        $phone  = $this->get_in_str_val('phone');

        $ret = $this->t_lecture_revisit_info->get_lecture_revisit_info_by_phone($phone);
        foreach($ret as &$item){
            $item["revisit_time_str"] = date("Y-m-d H:i:s",$item["revisit_time"]);
            E\Electure_revisit_type::set_item_value_str($item,"revisit_origin");
        }
        return $this->output_succ(["revisit_list"=>$ret]);
    }

    public function get_new_teacher_revisit_info(){
        $teacherid  = $this->get_in_int_val('teacherid');

        $ret = $this->t_teacher_record_list->get_record_info_new($teacherid,5);
        foreach($ret as &$item){
            $item["add_time_str"] = date("Y-m-d H:i:s",$item["add_time"]);
        }

        return $this->output_succ(["revisit_list"=>$ret]);
    }

    public function teacher_lecture_appointment_info_full_time(){
        $this->set_in_value("show_full_time",1);
        $this->set_in_value("full_time",1);
        return $this->teacher_lecture_appointment_info();
    }

    public function teacher_lecture_appointment_info_zs(){
        return $this->teacher_lecture_appointment_info();
    }

    public function teacher_lecture_appointment_info(){
        $this->switch_tongji_database();

        list($start_time,$end_time,$opt_date_str) = $this->get_in_date_range(-7,0,1,[
            1 => array("la.answer_begin_time","入库时间"),
            2 => array("ta.lesson_start", "面试时间"),
        ]);

        $lecture_appointment_status = $this->get_in_int_val('lecture_appointment_status',-1);
        $teacherid                  = $this->get_in_int_val('teacherid',"-1");
        $status                     = $this->get_in_int_val("status",-1);
        $user_name                  = trim($this->get_in_str_val('user_name',''));
        $record_status              = $this->get_in_int_val("record_status",-1);
        $page_num                   = $this->get_in_page_num();
        $grade                      = $this->get_in_int_val('grade',-1);
        $subject                    = $this->get_in_int_val('subject',-1);
        $have_wx                    = $this->get_in_int_val("have_wx",-1);
        $lecture_revisit_type       = $this->get_in_int_val("lecture_revisit_type",-1);
        $lecture_revisit_type_new   = $this->get_in_int_val("lecture_revisit_type_new",-1);
        $full_time                  = $this->get_in_int_val("full_time",-1);
        $show_full_time             = $this->get_in_int_val("show_full_time",0);
        $teacher_ref_type           = $this->get_in_enum_list(E\Eteacher_ref_type::class);
        $fulltime_teacher_type      = $this->get_in_int_val("fulltime_teacher_type", -1);
        $accept_adminid             = $this->get_in_int_val("accept_adminid", -1);
        $second_train_status        = $this->get_in_int_val("second_train_status", -1);
        $teacher_pass_type          = $this->get_in_int_val("teacher_pass_type", -1);
        $gender                     = $this->get_in_int_val("gender", -1);
        $is_test_user               = $this->get_in_int_val("is_test_user", 0);

        if($show_full_time ==1){
            $interview_type = $this->get_in_int_val("interview_type",-1);
        }else{
            $interview_type = $this->get_in_int_val("interview_type",0);
        }

        $adminid = $this->get_account_id();
        $acc     = $this->get_account();
        $account_role = $this->get_account_role();

        //检查是否招师组长
        $is_master_flag = $this->t_admin_group_name->check_is_master(8,$adminid);
        if(in_array($adminid,[349,72,186,68,500,897,967,480,944,974,985,994,986,1043])
           || in_array($acc,['jim','adrian',"alan","ted","夏宏东","low-key"])
           || $account_role==12
        ){
            $adminid = -1;
        }

        $ret_info = $this->t_teacher_lecture_appointment_info->get_all_info(
            $page_num,$start_time,$end_time,$teacherid,$lecture_appointment_status,
            $user_name,$status,$adminid,$record_status,$grade,$subject,$teacher_ref_type,
            $interview_type,$have_wx, $lecture_revisit_type,$full_time,
            $lecture_revisit_type_new,$fulltime_teacher_type,$accept_adminid,
            $second_train_status,$teacher_pass_type,$opt_date_str,$gender,$is_test_user
        );
        foreach($ret_info["list"] as &$item){
            $item["begin"] = date("Y-m-d H:i:s",$item["answer_begin_time"]);
            $item["end"] = date("Y-m-d H:i:s",$item["answer_end_time"]);
            $item["answer_time"] = date("Y-m-d H:i:s",$item["answer_begin_time"])."-".date("H:i:s",$item["answer_end_time"]);
            if($item['full_time']==1){
                $item['teacher_type_str']="全职老师";
            }else{
                E\Eidentity::set_item_value_str($item,"teacher_type");
            }
            $item['interviewer_teacher_str'] = $this->cache_get_teacher_nick($item["interviewer_teacherid"]);
            E\Electure_appointment_status::set_item_value_str($item,"lecture_appointment_status");
            E\Electure_revisit_type::set_item_value_str($item,"lecture_revisit_type");
            E\Eboolean::set_item_value_str($item,"full_time");
            if($item['subject_ex']==""){
                $item['subject_ex'] = 0;
            }
            E\Esubject::set_item_value_str($item,"subject_ex");
            E\Esubject::set_item_value_str($item,"trans_subject_ex");
            E\Egender::set_item_value_str($item);
            E\Eboolean::set_item_value_str($item,"is_test_user");

            if(($item['status']=="-2" && empty($item["lesson_start"])) || ($item['add_time'] <= 0 && $item['status'] <= 0 && $item['trial_train_status'] == -2)){
                $item['status_str'] = "无试讲";
            }elseif(($item['status']==0 && (($item["trial_train_status"] ==-2 && $item["lesson_start"]>0) || empty($item["lesson_start"]))) || (($item['status']==0 || $item['status']=="-2") && ($item["trial_train_status"] ==-2 && $item["lesson_start"]>0))){
                $item['status_str'] = "未审核";
            }elseif($item['status']==1 || $item["trial_train_status"]==1){
                $item['status_str'] = "已通过";
            }else if(($item['status']==2 && ($item["trial_train_status"]==0 || empty($item["lesson_start"]) )) || (($item['status']==2 || $item['status']=="-2") && $item["trial_train_status"]==0 )) {
                $item['status_str'] = "未通过";
            }elseif($item["trial_train_status"]==2){
                $item['status_str'] ="老师未到";
            }else{
                 E\Echeck_status::set_item_value_str($item, "status");
            }

            $full_status = $item['full_status'];
            if($full_status==="1"){
                $item['full_status_str']="通过";
            }elseif($full_status==="0"){
                $item['full_status_str']="不通过";
            }elseif($item["full_status"]=="3"){
                $item['full_status_str'] ="待定";
            }else{
                $item['full_status_str']="未审核";
            }

            if(!isset($item['reference_name']) || $item['reference_name']==""){
                if($item['reference']==''){
                    $item['reference_name'] = "无";
                }else{
                    $item['reference_name'] = "自由渠道";
                }
            }

            $item["phone_ex"] = preg_replace('/(1[356789]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$item["phone"]);
            $count = strlen($item["qq"]);
            $item["qq_ex"] = substr($item["qq"],0,3)."***".substr($item["qq"],6,$count-1);
            $num = strlen($item["email"]);
            $item["email_ex"] = substr($item["email"],0,3)."****".substr($item["email"],7,$num-1);
            if(!empty($item["record_info"])){
                $item["reason"]=$item["record_info"];
            }

            if($item["wx_openid"]){
                $item["have_wx_flag"]="是";
            }else{
                $item["have_wx_flag"]="否";
            }

            if($item["lesson_start"]>0){
                $item["lecture_revisit_type_new_str"] = date("Y-m-d H:i:s",$item["lesson_start"]);
            }else{
                $item["lecture_revisit_type_new_str"] = E\Electure_revisit_type::get_desc($item['lecture_revisit_type']);
            }

            \App\Helper\Utils::unixtime2date_for_item($item, "train_through_new_time","_str");
            if(empty($item["grade_ex"])){
                $item["grade_ex"]=0;
            }
            $not_grade_arr=explode(",",$item["grade_ex"]);
            $item["grade_ex_str"]="";
            foreach($not_grade_arr as $ty){
                $item["grade_ex_str"] .=E\Egrade::get_desc($ty).",";
            }
            $item["grade_ex_str"] = trim($item["grade_ex_str"],",");
        }

        $account_id = $this->get_account_id();
        $this->set_in_value("tea_adminid",$account_id);
        $tea_adminid = $this->get_in_int_val("tea_adminid");
        $this->set_in_value("fulltime_flag",$show_full_time);
        $fulltime_flag= $this->get_in_int_val("fulltime_flag");
        $next_day = strtotime(date("Y-m-d",time()+86400));
        $this->set_in_value("next_day",$next_day);
        $next_day = $this->get_in_int_val("next_day");
        $acc= $this->get_account();
        return $this->pageView(__METHOD__,$ret_info,[
            "account_id"     => $account_id,
            "show_full_time" => $show_full_time,
            "acc"            => $acc
        ]);
    }

    public function set_teacher_grade_range(){
        $teacherid   = $this->get_in_int_val("teacherid");
        $grade_start = $this->get_in_int_val("grade_start");
        $grade_end   = $this->get_in_int_val("grade_end");

        $ret = $this->t_teacher_info->field_update_list($teacherid,[
            "grade_start" => $grade_start,
            "grade_end"   => $grade_end,
        ]);
        if(!$ret){
            return $this->output_err("更新失败！");
        }

        return $this->output_succ();
    }

    /**
     * 更新老师例子库中的年级范围等信息
     */
    public function set_teacher_appointment_info(){
        $id          = $this->get_in_int_val("id");
        $subject     = $this->get_in_int_val("subject");
        $grade_start = $this->get_in_int_val("grade_start");
        $grade_end   = $this->get_in_int_val("grade_end");
        $not_grade   = $this->get_in_str_val("not_grade");
        $textbook    = $this->get_in_str_val("textbook");

        if($subject<1 || $subject==""){
            return $this->output_err("请选择科目！");
        }
        $update_data = [
            "subject_ex"  => $subject,
            "grade_start" => $grade_start,
            "grade_end"   => $grade_end,
            "not_grade"   => $not_grade,
            "textbook"    => $textbook,
        ];

        $ret = $this->t_teacher_lecture_appointment_info->field_update_list($id,$update_data);
        if(!$ret){
            return $this->output_err("更新失败或没有变更内容！");
        }

        $file = \App\Helper\Utils::get_teacher_lecture_file($subject,$grade_start,$grade_end,$not_grade);

        return $this->output_succ();
    }

    public function get_teacher_textbook($subject,$textbook_str){
        $textbook_arr = explode(",",$textbook_str);
        $textbook     = "";
        foreach($textbook_arr as $val){
            $textbook .= E\Eregion_version::get_desc($val).",";
        }
        return $textbook;
    }

    public function get_question_tongji(){
        /*
          $grade = $this->get_in_int_val('grade',200);
          $subject = $this->get_in_int_val('subject',2);
          $note_name              = trim($this->get_in_str_val('note_name',''));
          $page_num              = $this->get_in_page_num();
        */
        $ret_info = $this->t_lesson_note->get_all_info();
        /*
          foreach($ret["list"] as &$item){
          $note_id = $item["noteid"];
          $num = strlen(18010000);
          #$del_value=sprintf( "%06d", $note_id%1000000);
          $item["main_note_id"] = substr($note_id,0,$num-4)."0000";
          $item["main_note_name"] = $this->t_lesson_note->get_note_name($item["main_note_id"]);
          $item["second_note_id"] = substr($note_id,0,$num-2)."00";
          $item["second_note_name"] = $this->t_lesson_note->get_note_name($item["second_note_id"]);
          E\Esubject::set_item_value_str($item,"subject");
          E\Egrade_part::set_item_value_str($item,"grade");
          }
        */
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function set_class_abnormal_record(){
        $teacherid          = $this->get_in_int_val("teacherid",0);
        $record_lesson_list = $this->get_in_str_val("lessonid_list","");
        if(empty($record_lesson_list)){
            $str = "近期学生教学质量反馈报告";
        }else{
            $lessonid=json_decode($record_lesson_list,true);
            $str = "";
            foreach($lessonid as $item){
                $ret = $this->t_lesson_info->get_lesson_info_stu($item);
                $lesson_start = date("Y.m.d H:i:s",$ret["lesson_start"]);
                $str .= $lesson_start." ".$ret["nick"].";";
            }
            $str = trim($str,";");
            $str = $str."的教学质量反馈报告";
        }
        $ret = $this->t_teacher_record_list->row_insert([
            "teacherid"                        => $teacherid,
            "type"                             => 1,
            "record_info"                      => "课程异常,已反馈老师",
            "add_time"                         => time(),
            "acc"                              => $this->get_account(),
            "record_lesson_list"               => $record_lesson_list
        ]);
        if($ret){
            $openid = $this->t_teacher_info->get_wx_openid($teacherid);
            if($openid!=''){
                /**
                 * 模板ID : 9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ
                 * 标题   : 评估结果通知
                 * {{first.DATA}}
                 * 评估内容：{{keyword1.DATA}}
                 * 评估结果：{{keyword2.DATA}}
                 * 时间：{{keyword3.DATA}}
                 * {{remark.DATA}}
                 */
                $template_id      = "9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ";
                $data['first']    = "老师你好，近期我们对您的课程进行了听课抽查，课程的质量反馈报告如下：";
                $data['keyword1'] = $str;
                $data['keyword2'] = "暂无";
                $data['keyword3'] = date("Y-m-d H:i:s",time())."(教研评价时间)";
                $data['remark'] = "监课情况:因课程异常，本堂课无法正常反馈，后续课堂中遇到问题可尝试如下方式："
                                ."\n1、和学生一起退出重进"
                                ."\n2、下载“手机语音监测APP”自我监测"
                                ."\n3、联系助教/咨询老师进行协调处理"
                                ."\n希望老师之后碰到相关问题切莫惊慌，镇定处理。理优期待与你一起共同进步，提供高品质教学服务。";

                //$data['remark']   = "如有疑问请联系各学科教研老师，理优期待与你一起共同进步，提供高品质教学服务。";
                \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data);
            }

        }
        return $this->output_succ();

    }

    public function set_teacher_record_info(){
        $teacherid                        = $this->get_in_int_val("teacherid",0);
        $type                             = $this->get_in_int_val("type",0);
        $courseware_flag                  = $this->get_in_str_val('courseware_flag');
        $courseware_flag_score            = $this->get_in_int_val('courseware_flag_score');
        $lesson_preparation_content       = $this->get_in_str_val('lesson_preparation_content');
        $lesson_preparation_content_score = $this->get_in_int_val('lesson_preparation_content_score');
        $courseware_quality               = $this->get_in_str_val('courseware_quality');
        $courseware_quality_score         = $this->get_in_int_val('courseware_quality_score');
        $tea_process_design               = $this->get_in_str_val('tea_process_design');
        $tea_process_design_score         = $this->get_in_int_val('tea_process_design_score');
        $class_atm                        = $this->get_in_str_val('class_atm');
        $class_atm_score                  = $this->get_in_int_val('class_atm_score');
        $knw_point                        = $this->get_in_str_val('knw_point');
        $knw_point_score                  = $this->get_in_int_val('knw_point_score');
        $dif_point                        = $this->get_in_str_val('dif_point');
        $dif_point_score                  = $this->get_in_int_val('dif_point_score');
        $teacher_blackboard_writing       = $this->get_in_str_val('teacher_blackboard_writing');
        $teacher_blackboard_writing_score = $this->get_in_int_val('teacher_blackboard_writing_score');
        $tea_rhythm                       = $this->get_in_str_val('tea_rhythm');
        $tea_rhythm_score                 = $this->get_in_int_val('tea_rhythm_score');
        $language_performance             = $this->get_in_str_val('language_performance');
        $language_performance_score       = $this->get_in_int_val('language_performance_score');
        $content_fam_degree               = $this->get_in_str_val('content_fam_degree');
        $content_fam_degree_score         = $this->get_in_int_val('content_fam_degree_score');
        $answer_question_cre              = $this->get_in_str_val('answer_question_cre');
        $answer_question_cre_score        = $this->get_in_int_val('answer_question_cre_score');
        $tea_attitude                     = $this->get_in_str_val('tea_attitude');
        $tea_attitude_score               = $this->get_in_int_val('tea_attitude_score');
        $tea_method                       = $this->get_in_str_val('tea_method');
        $tea_method_score                 = $this->get_in_int_val('tea_method_score');
        $tea_concentration                = $this->get_in_str_val('tea_concentration');
        $tea_concentration_score          = $this->get_in_int_val('tea_concentration_score');
        $tea_accident                     = $this->get_in_str_val('tea_accident');
        $tea_accident_score               = $this->get_in_int_val('tea_accident_score');
        $tea_operation                    = $this->get_in_str_val('tea_operation');
        $tea_operation_score              = $this->get_in_int_val('tea_operation_score');
        $tea_environment                  = $this->get_in_str_val('tea_environment');
        $tea_environment_score            = $this->get_in_int_val('tea_environment_score');
        $class_abnormality                = $this->get_in_str_val('class_abnormality');
        $class_abnormality_score          = $this->get_in_int_val('class_abnormality_score');
        $record_info                      = $this->get_in_str_val("record_info","");
        $record_score                     = $this->get_in_int_val("record_score",0);
        $record_monitor_class             = $this->get_in_str_val("record_monitor_class","");
        $record_rank                      = $this->get_in_str_val("record_rank","");
        $record_lesson_list               = $this->get_in_str_val("record_lesson_list","");
        $sshd_good                          = $this->get_in_str_val("sshd_good");
        $sshd_bad                           = $this->get_in_str_val("sshd_bad");
        $ktfw_good                          = $this->get_in_str_val("ktfw_good");
        $ktfw_bad                           = $this->get_in_str_val("ktfw_bad");
        $skgf_good                          = $this->get_in_str_val("skgf_good");
        $skgf_bad                           = $this->get_in_str_val("skgf_bad");
        $jsfg_good                          = $this->get_in_str_val("jsfg_good");
        $jsfg_bad                           = $this->get_in_str_val("jsfg_bad");

        $add_time = time();
        if($teacherid==0 || $type==0 || $record_info=="" || $record_score>100){
            return $this->output_err("参数出错!请重新评价!");
        }

        if(mb_strlen($record_info)>150){
            return $this->output_err("评价内容超过了150字!");
        }

        $tea_nick = $this->cache_get_teacher_nick($teacherid);
        $ret = $this->t_teacher_record_list->row_insert([
            "courseware_flag"                  => $courseware_flag,
            "courseware_flag_score"            => $courseware_flag_score,
            "lesson_preparation_content"       => $lesson_preparation_content,
            "lesson_preparation_content_score" => $lesson_preparation_content_score,
            "courseware_quality"               => $courseware_quality,
            "courseware_quality_score"         => $courseware_quality_score,
            "tea_process_design"               => $tea_process_design,
            "tea_process_design_score"         => $tea_process_design_score,
            "class_atm"                        => $class_atm,
            "class_atm_score"                  => $class_atm_score,
            "knw_point"                        => $knw_point,
            "knw_point_score"                  => $knw_point_score,
            "dif_point"                        => $dif_point,
            "dif_point_score"                  => $dif_point_score,
            "teacher_blackboard_writing"       => $teacher_blackboard_writing,
            "teacher_blackboard_writing_score" => $teacher_blackboard_writing_score,
            "tea_rhythm"                       => $tea_rhythm,
            "tea_rhythm_score"                 => $tea_rhythm_score,
            "language_performance"             => $language_performance,
            "language_performance_score"       => $language_performance_score,
            "content_fam_degree"               => $content_fam_degree,
            "content_fam_degree_score"         => $content_fam_degree_score,
            "answer_question_cre"              => $answer_question_cre,
            "answer_question_cre_score"        => $answer_question_cre_score,
            "tea_attitude"                     => $tea_attitude,
            "tea_attitude_score"               => $tea_attitude_score,
            "tea_method"                       => $tea_method,
            "tea_method_score"                 => $tea_method_score,
            "tea_concentration"                => $tea_concentration,
            "tea_concentration_score"          => $tea_concentration_score,
            "tea_accident"                     => $tea_accident,
            "tea_accident_score"               => $tea_accident_score,
            "tea_operation"                    => $tea_operation,
            "tea_operation_score"              => $tea_operation_score,
            "tea_environment"                  => $tea_environment,
            "tea_environment_score"            => $tea_environment_score,
            "class_abnormality"                => $class_abnormality,
            "class_abnormality_score"          => $class_abnormality_score,
            "teacherid"                        => $teacherid,
            "type"                             => $type,
            "record_info"                      => $record_info,
            "record_score"                     => $record_score,
            "add_time"                         => $add_time,
            "acc"                              => $this->get_account(),
            "record_monitor_class"             => $record_monitor_class,
            "record_rank"                      => $record_rank,
            "record_lesson_list"               => $record_lesson_list
        ]);

        if(empty($record_lesson_list)){
            $str = "近期学生教学质量反馈报告";
        }else{
            $lessonid=json_decode($record_lesson_list,true);
            $str = "";
            foreach($lessonid as $item){
                $ret = $this->t_lesson_info->get_lesson_info_stu($item);
                $lesson_start = date("Y.m.d H:i:s",$ret["lesson_start"]);
                $str .= $lesson_start." ".$ret["nick"].";";
            }
            $str = trim($str,";");
            $str = $str."的教学质量反馈报告";
        }

        if($ret){
            $this->t_teacher_info->field_update_list($teacherid,["is_record_flag"=>1]);
            $this->add_teacher_label($sshd_good,$sshd_bad,$ktfw_good,$ktfw_bad,$skgf_good,$skgf_bad,$jsfg_good,$jsfg_bad,$teacherid,2,0,0,$record_lesson_list);

            $openid = $this->t_teacher_info->get_wx_openid($teacherid);
            if($openid!=''){
                /**
                 * 模板ID : 9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ
                 * 标题   : 评估结果通知
                 * {{first.DATA}}
                 * 评估内容：{{keyword1.DATA}}
                 * 评估结果：{{keyword2.DATA}}
                 * 时间：{{keyword3.DATA}}
                 * {{remark.DATA}}
                 */
                $template_id      = "9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ";
                $data['first']    = "老师你好，近期我们对您的课程进行了听课抽查，课程的质量反馈报告如下：";
                $data['keyword1'] = $str;
                $data['keyword2'] = $record_score."分    等级:".$record_rank."(S>A>B>C)";
                $data['keyword3'] = date("Y-m-d H:i:s",time())."(教研评价时间)";
                $data['remark'] = "监课情况:".$record_monitor_class
                                ."\n建       议:".$record_info
                                ."\n如有疑问请联系各学科教研老师，理优期待与你一起共同进步，提供高品质教学服务。";

                $url = "http://admin.leo1v1.com/common/teacher_record_detail_info?teacherid=".$teacherid
                     ."&type=1&add_time=".$add_time;
                //$data['remark']   = "如有疑问请联系各学科教研老师，理优期待与你一起共同进步，提供高品质教学服务。";
                \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data,$url);
            }else{
                /**
                 * 模板类型 : 短信通知
                 * 模板名称 : 老师反馈通知2-14
                 * 模板ID   : SMS_46750146
                 * 模板内容 : 课程反馈通知：${name}老师您好，近期我们进行教学质量的抽查，您的课程反馈情况是：${reason}，
                 教学质量评分为：${score}分。如有疑问请联系学科教研老师，理优期待与你共同进步，提高教学服务质量。
                 */
                $phone    = $this->t_teacher_info->get_phone($teacherid);
                $sms_id   = 46750146;
                $sms_data = [
                    "name"   => $tea_nick,
                    "reason" => $record_info,
                    "score"  => $record_score,
                ];
                $sign_name=\App\Helper\Utils::get_sms_sign_name();

                \App\Helper\Utils::sms_common($phone,$sms_id,$sms_data,0,$sign_name);
            }
            return $this->output_succ();
        }else{
            return $this->output_err("评价失败!请重新评价");
        }
    }

    public function set_teacher_record_info_new(){
        $teacherid                        = $this->get_in_int_val("teacherid",0);
        $type                             = $this->get_in_int_val("type",0);
        $tea_process_design_score         = $this->get_in_int_val('tea_process_design_score');
        $knw_point_score                  = $this->get_in_int_val('knw_point_score');
        $teacher_blackboard_writing_score = $this->get_in_int_val('teacher_blackboard_writing_score');
        $tea_rhythm_score                 = $this->get_in_int_val('tea_rhythm_score');
        $language_performance_score       = $this->get_in_int_val('language_performance_score');
        $answer_question_cre_score        = $this->get_in_int_val('answer_question_cre_score');
        $tea_concentration_score          = $this->get_in_int_val('tea_concentration_score');
        $tea_operation_score              = $this->get_in_int_val('tea_operation_score');
        $tea_environment_score            = $this->get_in_int_val('tea_environment_score');
        $class_abnormality_score          = $this->get_in_int_val('class_abnormality_score');
        $record_info                      = $this->get_in_str_val("record_info","");
        $record_score                     = $this->get_in_int_val("score",0);
        $no_tea_related_score             = $this->get_in_int_val("no_tea_related_score",0);
        $record_monitor_class             = $this->get_in_str_val("record_monitor_class","");
        $record_lesson_list               = $this->get_in_str_val("record_lesson_list","");
        $sshd_good                        = $this->get_in_str_val("sshd_good");
        $sshd_bad                         = $this->get_in_str_val("sshd_bad");
        $ktfw_good                        = $this->get_in_str_val("ktfw_good");
        $ktfw_bad                         = $this->get_in_str_val("ktfw_bad");
        $skgf_good                        = $this->get_in_str_val("skgf_good");
        $skgf_bad                         = $this->get_in_str_val("skgf_bad");
        $jsfg_good                        = $this->get_in_str_val("jsfg_good");
        $jsfg_bad                         = $this->get_in_str_val("jsfg_bad");
        $trial_train_status               = $this->get_in_int_val("trial_train_status");
        $lessonid                         = $this->get_in_int_val("lessonid");

        $add_time = time();
        if($teacherid==0 || $type==0 || $record_info=="" || $record_score>100){
            return $this->output_err("参数出错!请重新评价!");
        }

        if(mb_strlen($record_info)>150){
            return $this->output_err("评价内容超过了150字!");
        }

        $tea_nick = $this->cache_get_teacher_nick($teacherid);
        $ret = $this->t_teacher_record_list->row_insert([
            "tea_process_design_score"         => $tea_process_design_score,
            "knw_point_score"                  => $knw_point_score,
            "teacher_blackboard_writing_score" => $teacher_blackboard_writing_score,
            "tea_rhythm_score"                 => $tea_rhythm_score,
            "language_performance_score"       => $language_performance_score,
            "answer_question_cre_score"        => $answer_question_cre_score,
            "tea_concentration_score"          => $tea_concentration_score,
            "tea_operation_score"              => $tea_operation_score,
            "tea_environment_score"            => $tea_environment_score,
            "class_abnormality_score"          => $class_abnormality_score,
            "teacherid"                        => $teacherid,
            "type"                             => $type,
            "record_info"                      => $record_info,
            "record_score"                     => $record_score,
            "no_tea_related_score"             => $no_tea_related_score,
            "add_time"                         => $add_time,
            "acc"                              => $this->get_account(),
            "record_monitor_class"             => $record_monitor_class,
            "record_lesson_list"               => $record_lesson_list,
            "train_lessonid"                   => $lessonid,
            "trial_train_status"               => $trial_train_status
        ]);

        if(empty($record_lesson_list)){
            $str = "近期学生教学质量反馈报告";
        }else{
            $lessonid = json_decode($record_lesson_list,true);
            $str = "";
            foreach($lessonid as $item){
                $ret = $this->t_lesson_info->get_lesson_info_stu($item);
                $lesson_start = date("Y.m.d H:i:s",$ret["lesson_start"]);
                $str .= $lesson_start." ".$ret["nick"].";";
            }
            $str = trim($str,";");
            $str = $str."的教学质量反馈报告";
        }

        if($ret){
            $this->t_teacher_info->field_update_list($teacherid,["is_record_flag"=>1]);
            // $this->add_teacher_label($sshd_good,$sshd_bad,$ktfw_good,$ktfw_bad,$skgf_good,$skgf_bad,$jsfg_good,$jsfg_bad,$teacherid,2,0,0,$record_lesson_list);
            $this->set_teacher_label($teacherid,$lessonid,$record_lesson_list,$sshd_good,2);


            $openid = $this->t_teacher_info->get_wx_openid($teacherid);
            if($openid!=''){
                /**
                 * 模板ID : 9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ
                 * 标题   : 评估结果通知
                 * {{first.DATA}}
                 * 评估内容：{{keyword1.DATA}}
                 * 评估结果：{{keyword2.DATA}}
                 * 时间：{{keyword3.DATA}}
                 * {{remark.DATA}}
                 */
                $template_id      = "9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ";
                $data['first']    = "老师你好，近期我们对您的课程进行了听课抽查，课程的质量反馈报告如下：";
                $data['keyword1'] = $str;
                $data['keyword2'] = $record_score."分";
                $data['keyword3'] = date("Y-m-d H:i:s",time())."(教研评价时间)";
                $data['remark'] = "监课情况:".$record_monitor_class
                                ."\n建       议:".$record_info
                                ."\n如有疑问请联系各学科教研老师，理优期待与你一起共同进步，提供高品质教学服务。";

                $url = "http://admin.leo1v1.com/common/teacher_record_detail_info?teacherid=".$teacherid
                     ."&type=".$type."&add_time=".$add_time;
                \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data,$url);
            }else{
                /**
                 * 模板类型 : 短信通知
                 * 模板名称 : 老师反馈通知2-14
                 * 模板ID   : SMS_46750146
                 * 模板内容 : 课程反馈通知：${name}老师您好，近期我们进行教学质量的抽查，您的课程反馈情况是：${reason}，
                 教学质量评分为：${score}分。如有疑问请联系学科教研老师，理优期待与你共同进步，提高教学服务质量。
                */
                $phone    = $this->t_teacher_info->get_phone($teacherid);
                $sms_id   = 46750146;
                $sms_data = [
                    "name"   => $tea_nick,
                    "reason" => $record_info,
                    "score"  => $record_score,
                ];
                $sign_name = \App\Helper\Utils::get_sms_sign_name();
                \App\Helper\Utils::sms_common($phone,$sms_id,$sms_data,0,$sign_name);
            }
            return $this->output_succ();
        }else{
            return $this->output_err("评价失败!请重新评价");
        }
    }

    public function set_trial_train_lesson(){
        $id                               = $this->get_in_int_val("id",0);
        $teacherid                        = $this->get_in_int_val("teacherid",0);
        $lessonid                         = $this->get_in_int_val("lessonid",0);
        $record_lesson_list               = $this->get_in_str_val("record_lesson_list","");
        $status                           = $this->get_in_int_val("status");
        $tea_process_design_score         = $this->get_in_int_val('tea_process_design_score');
        $knw_point_score                  = $this->get_in_int_val('knw_point_score');
        $teacher_blackboard_writing_score = $this->get_in_int_val('teacher_blackboard_writing_score');
        $tea_rhythm_score                 = $this->get_in_int_val('tea_rhythm_score');
        $language_performance_score       = $this->get_in_int_val('language_performance_score');
        $answer_question_cre_score        = $this->get_in_int_val('answer_question_cre_score');
        $tea_concentration_score          = $this->get_in_int_val('tea_concentration_score');
        $tea_operation_score              = $this->get_in_int_val('tea_operation_score');
        $tea_environment_score            = $this->get_in_int_val('tea_environment_score');
        $class_abnormality_score          = $this->get_in_int_val('class_abnormality_score');
        $record_info                      = $this->get_in_str_val("record_info","");
        $record_score                     = $this->get_in_int_val("score",0);
        $no_tea_related_score             = $this->get_in_int_val("no_tea_related_score",0);
        $record_monitor_class             = $this->get_in_str_val("record_monitor_class","");
        $sshd_good                        = $this->get_in_str_val("sshd_good");
        $new_tag_flag                     = $this->get_in_int_val("new_tag_flag",0);
        $style_character                  = $this->get_in_str_val("style_character");
        $professional_ability             = $this->get_in_str_val("professional_ability");
        $classroom_atmosphere             = $this->get_in_str_val("classroom_atmosphere");
        $courseware_requirements          = $this->get_in_str_val("courseware_requirements");
        $diathesis_cultivation            = $this->get_in_str_val("diathesis_cultivation");


        $acc= $this->t_teacher_record_list->get_acc($id);
        $account = $this->get_account();
        if($acc != $account && $acc !=""){
            return $this->output_err("您没有权限审核,审核人为".$acc);
        }

        $info = $this->t_teacher_info->get_teacher_info($teacherid);
        $ret  = $this->t_teacher_record_list->field_update_list($id,[
            "tea_process_design_score"         => $tea_process_design_score,
            "knw_point_score"                  => $knw_point_score,
            "teacher_blackboard_writing_score" => $teacher_blackboard_writing_score,
            "tea_rhythm_score"                 => $tea_rhythm_score,
            "language_performance_score"       => $language_performance_score,
            "answer_question_cre_score"        => $answer_question_cre_score,
            "tea_concentration_score"          => $tea_concentration_score,
            "tea_operation_score"              => $tea_operation_score,
            "tea_environment_score"            => $tea_environment_score,
            "class_abnormality_score"          => $class_abnormality_score,
            "record_info"                      => $record_info,
            "record_score"                     => $record_score,
            "no_tea_related_score"             => $no_tea_related_score,
            "record_monitor_class"             => $record_monitor_class,
            "trial_train_status"               => $status,
            "add_time"                         => time(),
            "acc"                              => $account
        ]);

        if(!$ret){
            return $this->output_err("更新出错！请重新提交！");
        }
        if($new_tag_flag==0){
            $this->set_teacher_label($teacherid,$lessonid,$record_lesson_list,$sshd_good,2); 
        }elseif($new_tag_flag==1){
            $tea_tag_arr=[
                "style_character"=>$style_character,
                "professional_ability"=>$professional_ability,
                "classroom_atmosphere"=>$classroom_atmosphere,
                "courseware_requirements"=>$courseware_requirements,
                "diathesis_cultivation"=>$diathesis_cultivation,
            ];
            $this->set_teacher_label_new($teacherid,$lessonid,$record_lesson_list,$tea_tag_arr,2); 
        }

        $teacher_info  = $this->t_teacher_info->get_teacher_info($teacherid);
        $lesson_info   = $this->t_lesson_info->get_lesson_info($lessonid);
        if($status==1){
            if($teacherid==240314 ){
                //新版,发送入职前在线签订入职协议
                /**
                 * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
                 * 标题课程 : 待办事项提醒
                 * {{first.DATA}}
                 * 待办主题：{{keyword1.DATA}}
                 * 待办内容：{{keyword2.DATA}}
                 * 日期：{{keyword3.DATA}}
                 * {{remark.DATA}}
                 */

                $data=[];
                $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
                $data['first']    = $teacher_info["nick"]."老师您好，恭喜您通过模拟试听课考核，为了保护您的利益，请您签订《理优平台兼职老师入职协议》，之后您将正式成为理优平台兼职老师。";
                $data['keyword1'] = "签订入职协议";
                $data['keyword2'] = "签订《理优平台老师兼职协议》 ";
                $data['keyword3'] = date("Y-m-d",time());
                $data['remark']   = "点击此链接，签订入职协议";
                $url = "http://wx-teacher.leo1v1.com/wx_teacher_web/agreement";
                $wx_openid = $this->t_teacher_info->get_wx_openid($teacherid);
                if($wx_openid){
                    \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
                }


            }else{
              
                $ret = $this->t_teacher_info->field_update_list($teacherid,[
                    "trial_train_flag"  => 1,
                    "train_through_new" => 1,
                    "level"             => 1
                ]);
                $keyword2   = "已通过";
                $teacher_info  = $this->t_teacher_info->get_teacher_info($teacherid);

                /**
                 * 模板ID   : E9JWlTQUKVWXmUUJq_hvXrGT3gUvFLN6CjYE1gzlSY0
                 * 标题课程 : 等级升级通知
                 * {{first.DATA}}
                 * 用户昵称：{{keyword1.DATA}}
                 * 最新等级：{{keyword2.DATA}}
                 * 生效时间：{{keyword3.DATA}}
                 * {{remark.DATA}}
                 */
                $wx_openid = $this->t_teacher_info->get_wx_openid($teacherid);
                if($wx_openid){
                    $data=[];
                    $template_id      = "E9JWlTQUKVWXmUUJq_hvXrGT3gUvFLN6CjYE1gzlSY0";
                    $data['first']    = "恭喜您通过模拟试听课审核，加入排课群，和排课老师沟通可上课时间。点详情，看群号。";
                    $data['keyword1'] = $teacher_info["nick"];
                    $data['keyword2'] = "二星级";
                    $data['keyword3'] = date("Y-m-d H:i",time());
                    $data['remark']   = "\n升级原因:具备线上教学能力。".$record_info."\n您将获得20元奖励，请在微信老师帮-个人中心-我的收入-绑定银行卡，每月10日发放上月薪资到绑定的银行卡。";
                    $url = "http://admin.leo1v1.com/common/show_level_up_html?teacherid=".$teacherid;
                    \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
                }

                //邮件推送
                $html = $this->teacher_level_up_html($teacher_info);
                $email = $teacher_info["email"];
                if($email){
                    dispatch( new \App\Jobs\SendEmailNew(
                        $email,"【理优1对1】老师晋升通知",$html
                    ));
                }

                //添加模拟试听奖金
                $check_flag = $this->t_teacher_money_list->check_is_exists($lessonid,E\Ereward_type::V_5);
                if(!$check_flag){
                    $train_reward = \App\Helper\Config::get_config_2("teacher_money","trial_train_reward");
                    $this->t_teacher_money_list->row_insert([
                        "teacherid"  => $teacherid,
                        "type"       => E\Ereward_type::V_5,
                        "add_time"   => time(),
                        "money"      => $train_reward,
                        "money_info" => $lessonid,
                        "acc"        => $this->get_account(),
                    ]);
                }
            }

        }elseif($status=2){
            $keyword2 = "未通过";
            if($teacher_info['wx_openid']!=""){
                /**
                 * 模板ID : 9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ
                 * 标题   : 评估结果通知
                 * {{first.DATA}}
                 * 评估内容：{{keyword1.DATA}}
                 * 评估结果：{{keyword2.DATA}}
                 * 时间：{{keyword3.DATA}}
                 * {{remark.DATA}}
                 */
                $template_id      = "9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ";
                $data['first']    = "老师您好，很抱歉您没有通过模拟试听，希望您再接再厉。";
                $data['keyword1'] = $record_info;
                $data['keyword2'] = $keyword2;
                $data['keyword3'] = date("Y-m-d H:i:s");
                $data['remark'] = "请重新提交模拟试听时间，理优教育致力于打造高水平的教学服务团队，期待您能通过下次模拟试听，加油！";
                $url = "";
                \App\Helper\Utils::send_teacher_msg_for_wx($teacher_info['wx_openid'],$template_id,$data,$url);
            }
            $ret = $this->add_trial_train_lesson($teacher_info,1,2);
        }else{
            return $this->output_err("审核状态出错！");
        }

        return $this->output_succ();
    }


    public function teacher_record_detail_info(){
        $this->t_teacher_record_list->switch_tongji_database();
        $teacherid = $this->get_in_int_val("teacherid",53289);
        $type      = $this->get_in_int_val("type",1);
        $add_time  = $this->get_in_int_val("add_time",1496368310);
        $ret_info  = $this->t_teacher_record_list->get_all_info($teacherid,$type,$add_time);

        return $this->pageView(__METHOD__,null,["ret_info"=>$ret_info]);
    }

    public function teacher_record_detail_info_new(){
        $this->t_teacher_record_list->switch_tongji_database();
        $teacherid = $this->get_in_int_val("teacherid",53289);
        $type      = $this->get_in_int_val("type",1);
        $add_time  = $this->get_in_int_val("add_time",1496370325);
        $ret_info  = $this->t_teacher_record_list->get_all_info($teacherid,$type,$add_time);

        return $this->pageView(__METHOD__,null,["ret_info"=>$ret_info]);
    }
    public function teacher_record_detail_list_new_zj(){
        return $this->teacher_record_detail_list_new();
    }
    public function teacher_record_detail_list_zj(){
        return $this->teacher_record_detail_list();
    }




    public function teacher_record_detail_list(){
        $this->t_teacher_record_list->switch_tongji_database();
        $teacherid = $this->get_in_int_val("teacherid",-1);
        $subject   = $this->get_in_int_val("subject",-1);
        $page_num  = $this->get_in_page_info();
        list($start_time,$end_time) = $this->get_in_date_range(date("2017-05-01"),date("2017-05-31"));
        $ret_info = $this->t_teacher_record_list->get_all_record_info_time($teacherid,1,$start_time,$end_time,$page_num,$subject);

        $time = time()-7*86400;
        foreach($ret_info["list"] as &$item){
            E\Esubject::set_item_value_str($item,"subject");
            $item["create_time_str"] = date("Y-m-d",$item["create_time"]);
            $record_lesson_list = $item["record_lesson_list"];
            if($item["create_time"] >= $time){
                $item["fkqk"] = "新入职老师";
            }else{
                $item["fkqk"] = "非新入职老师";
            }

            if(empty($record_lesson_list)){
                $str = "";
            }else{
                $lessonid=json_decode($record_lesson_list,true);
                $str = "";
                foreach($lessonid as $item){
                    $ret = $this->t_lesson_info->get_lesson_info_stu($item);
                    $lesson_start = date("Y.m.d H:i:s",$ret["lesson_start"]);
                    $str .= $lesson_start." ".$ret["nick"].";";
                }
                $str = trim($str,";")."\n";
                $item["record_monitor_class"] = $str." ".$item["record_monitor_class"];
            }
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function teacher_record_detail_list_new(){
        $this->t_teacher_record_list->switch_tongji_database();
        $teacherid = $this->get_in_int_val("teacherid",-1);
        $subject   = $this->get_in_int_val("subject",-1);
        $page_num  = $this->get_in_page_num();
        list($start_time,$end_time) = $this->get_in_date_range(-7,0);
        $ret_info = $this->t_teacher_record_list->get_all_record_info_time($teacherid,1,$start_time,$end_time,$page_num,$subject,1);

        $time = time()-7*86400;
        foreach($ret_info["list"] as &$item){
            E\Esubject::set_item_value_str($item,"subject");
            $item["create_time_str"] = date("Y-m-d",$item["create_time"]);
            $record_lesson_list = $item["record_lesson_list"];
            if($item["create_time"] >= $time){
                $item["fkqk"] = "新入职老师";
            }else{
                $item["fkqk"] = "非新入职老师";
            }

            if(empty($record_lesson_list)){
                $str = "";
            }else{
                $lessonid=json_decode($record_lesson_list,true);
                if(empty($lessonid)){
                    $lessonid=[];
                }
                $str = "";
                if(!empty($lessonid)){
                    foreach($lessonid as $item){
                        $ret = $this->t_lesson_info->get_lesson_info_stu($item);
                        $lesson_start = date("Y.m.d H:i:s",$ret["lesson_start"]);
                        $str .= $lesson_start." ".$ret["nick"].";";
                    }
                    $str = trim($str,";")."\n";

                }
            }
            // $item["record_monitor_class"] = $str." ".$item["record_monitor_class"];

        }
        return $this->pageView(__METHOD__,$ret_info);
    }


    public function get_teacher_record_list(){
        $teacherid = $this->get_in_int_val("teacherid",0);
        $type      = $this->get_in_int_val("type",1);
        if($teacherid==0){
            return $this->output_err("老师id出错!");
        }

        $list = $this->t_teacher_record_list->get_teacher_record_list($teacherid,$type);
        foreach($list as &$val){
            $val['add_time_str']=date("Y-m-d H:i:s",$val['add_time']);
        }

        if(empty($list)){
            return $this->output_err("该老师没有反馈记录!");
        }else{
            return $this->output_succ(["data"=>$list]);
        }
    }

    public function update_lecture_status(){
        $id     = $this->get_in_int_val("id");
        $status = $this->get_in_int_val("status");

        $ret = $this->t_teacher_lecture_info->field_update_list($id,[
            "status" => $status
        ]);

        if($ret){
            return $this->output_succ();
        }else{
            return $this->output_err("更改失败,请刷新重试!");
        }
    }

    /**
     * @param name 姓名
     * @param phone 电话
     * @param email 邮箱
     * @param grade 年级
     * @param subject 科目
     * @param school 学校
     * @param textbook教材
     * @param teacher_type师资
     * @param reference 推荐人
     */
    public function add_teacher_lecture_appointment_info(){
        $name         = $this->get_in_str_val("name");
        $phone        = $this->get_in_str_val("phone");
        $email        = $this->get_in_str_val("email");
        $grade_ex     = $this->get_in_str_val("grade");
        $subject_ex   = $this->get_in_str_val("subject");
        $school       = $this->get_in_str_val("school");
        $textbook     = $this->get_in_str_val("textbook");
        $teacher_type = $this->get_in_str_val("teacher_type");
        $reference    = $this->get_in_str_val("reference");

        $check_flag=$this->t_teacher_lecture_appointment_info->check_is_exist(0,$phone);
        if($check_flag){
            return $this->output_err("手机号已存在!");
        }
        if($teacher_type=="" || $teacher_type==0){
            return $this->output_err("请选择老师身份!");
        }

        $ret = $this->t_teacher_lecture_appointment_info->row_insert([
            "name"         => $name,
            "phone"        => $phone,
            "email"        => $email,
            "grade_ex"     => $grade_ex,
            "subject_ex"   => $subject_ex,
            "school"       => $school,
            "textbook"     => $textbook,
            "teacher_type" => $teacher_type,
            "reference"    => $reference,
        ]);

        if($ret){
            return $this->output_succ();
        }else{
            return $this->output_err("上传失败!请重试!");
        }
    }

    public function get_stu_count_list(){
        $this->t_lesson_info->switch_tongji_database();

        $start_time = strtotime("2015-7-1");
        $end        = strtotime("2017-1-1");
        for($i=0;$start_time<$end;$i++){
            $end_time = strtotime("+1 month",$start_time);
            $ret[$i]["date"]=date("Y-m-d",$start_time);
            $ret[$i]['stu_new_num'] = $this->t_lesson_info->get_stu_total_by_type($start_time,$end_time,1);
            $ret[$i]['stu_old_num'] = $this->t_lesson_info->get_stu_total_by_type($start_time,$end_time,2);
            $ret[$i]['stu_all_num'] = $this->t_lesson_info->get_stu_total($start_time,$end_time);

            $subject_new = $this->t_lesson_info->get_stu_subject_list($start_time,$end_time,1);
            $subject_old = $this->t_lesson_info->get_stu_subject_list($start_time,$end_time,2);

            $subject_new_num=0;
            foreach($subject_new as $val){
                $subject_new_num += $val['subject_num'];
            }

            $subject_old_num=0;
            foreach($subject_old as $val){
                $subject_old_num += $val['subject_num'];
            }
            $subject_num = $subject_new_num+$subject_old_num;

            $ret[$i]['subject_new_per'] = $subject_new_num/count($subject_new);
            $ret[$i]['subject_old_per'] = $subject_old_num/count($subject_old);
            $ret[$i]['subject_num_per'] = $subject_num/(count($subject_new)+count($subject_old));

            $ret[$i]['lesson_total_new']   = $this->t_lesson_info->get_lesson_total_by_stuid($start_time,$end_time,1);
            $ret[$i]['lesson_total_old']   = $this->t_lesson_info->get_lesson_total_by_stuid($start_time,$end_time,2);
            $ret[$i]['lesson_trial_total'] = $this->t_lesson_info->get_trial_lesson_total($start_time,$end_time);

            $start_time = $end_time;
        }

        $ret_info = \App\Helper\Utils::list_to_page_info($ret);

        return $this->pageView(__METHOD__,$ret_info);
    }

    public function set_teacher_limit_plan_lesson(){
        $teacherid                = $this->get_in_int_val("teacherid");
        $limit_plan_lesson_type   = $this->get_in_int_val("limit_plan_lesson_type");
        $seller_require_flag      = $this->get_in_int_val("seller_require_flag",0);
        $limit_plan_lesson_reason = trim($this->get_in_str_val("limit_plan_lesson_reason"));
        $account                  = $this->get_account();
        $account_id = $this->get_account_id();
        $time     = time();
        $tea_nick = $this->cache_get_teacher_nick($teacherid);
        $limit_account = $this->t_teacher_info->field_get_value($teacherid,"limit_plan_lesson_account");
        $old_type      = $this->t_teacher_info->field_get_value($teacherid,"limit_plan_lesson_type");
        $del_flag = $this->t_manager_info->get_del_flag_new($limit_account);
        if($old_type !=0 && !empty($limit_account) && $account!=$limit_account && $account_id !=72 && $account_id !=349 && $del_flag==0){
             return $this->output_err("没有权限!");
        }

        $ret = $this->t_teacher_info->field_update_list($teacherid,[
            "limit_plan_lesson_type"    => $limit_plan_lesson_type,
            "limit_plan_lesson_reason"  => $limit_plan_lesson_reason,
            "limit_plan_lesson_time"    => $time,
        ]);
        if($limit_plan_lesson_type >0 && $account_id !=72){
            $this->t_teacher_info->field_update_list($teacherid,[
                "limit_plan_lesson_account" => $account
            ]);
        }else if($seller_require_flag != 1){
            $this->t_teacher_info->field_update_list($teacherid,[
                "limit_plan_lesson_account" => ""
            ]);

        }

        if($ret){
            $this->t_teacher_record_list->row_insert([
                "teacherid"          =>$teacherid,
                "type"               =>3,
                "record_info"        =>$limit_plan_lesson_reason,
                "add_time"           =>time(),
                "limit_plan_lesson_type"    => $limit_plan_lesson_type,
                "acc"                =>$account,
                "seller_require_flag"=>$seller_require_flag,
                "limit_plan_lesson_type_old" =>$old_type
            ]);

            if($limit_plan_lesson_type != $old_type && ($limit_plan_lesson_type==0 || ($limit_plan_lesson_type !=0 && $limit_plan_lesson_type > $old_type && $old_type !=0))){
                if($limit_plan_lesson_type==0){
                    $rr="不限课";
                }else{
                    $rr="一周限排".$limit_plan_lesson_type."节";
                }
                $admin_arr=[72,448];
                foreach($admin_arr as $ss){
                    $this->t_manager_info->send_wx_todo_msg_by_adminid ($ss,"理优监课组","老师限制排课更改",$tea_nick."老师,限制排课由一周限排".$old_type."节,改为".$rr.",操作人:".$account,"");
                }

            }

            if($limit_plan_lesson_type >0 && $limit_plan_lesson_type != $old_type){
                /**
                 * 模板ID : n8m_rkyP_hhDxPGxG1yzOfcoRX1t017RQtiyY9_8TDc
                 * 标题   :课程冻结通知
                 * {{first.DATA}}
                 * 课程名称：{{keyword1.DATA}}
                 * 操作时间：{{keyword2.DATA}}
                 * {{remark.DATA}}
                 */
                $template_id      = "n8m_rkyP_hhDxPGxG1yzOfcoRX1t017RQtiyY9_8TDc";//old

                $data['first']    = "老师您好，近期我们进行教学质量抽查，你的课程被限制排课,一周试听课排课数量不超过".$limit_plan_lesson_type."次。\n您的课程反馈情况是：".$limit_plan_lesson_reason;
                $data['keyword1'] = "试听课";
                $data['keyword2'] = date("Y-m-d H:i",time());
                $data['remark']   = "参加相关培训达标后，系统会放开排课限制，"
                                  ."如有疑问请联系各学科教研老师，理优期待与你一起共同进步，提高教学服务质量。";

                /**
                 * 模板类型 : 短信通知
                 * 模板名称 : 限制老师排课通知2-14
                 * 模板ID   : SMS_46835164
                 * 模板内容 : 限制排课通知：${name}老师您好，近期我们进行教学质量抽查，您的课程反馈情况是：${reason}。
                 您已被限制排课，一周试听课排课数量不超过${num}节。参加相关培训达标后，系统会放开排课限制，
                 如有疑问请联系各学科教研老师。理优期待与你共同进步，提高教学服务质量。
                */
                $sms_id   = 46835164;
                $sms_data = [
                    "name"   => $tea_nick,
                    "num"    => $limit_plan_lesson_type,
                    "reason" => $limit_plan_lesson_reason,
                ];
            }elseif($limit_plan_lesson_type ==0 && $limit_plan_lesson_type != $old_type){
                /**
                 * 模板ID   : gFBAryMgP7IAeLEIKJgui4JaMCHrBSGHm2wrvQP2EcA
                 * 标题课程 : 解冻通知
                 * {{first.DATA}}
                 * 课程名称：{{keyword1.DATA}}
                 * 操作时间：{{keyword2.DATA}}
                 * {{remark.DATA}}
                 */
                $template_id      = "gFBAryMgP7IAeLEIKJgui4JaMCHrBSGHm2wrvQP2EcA";//old
                $data['first']    = "老师您好，您的课程已解除排课限制。";
                $data['keyword1'] = "试听课";
                $data['keyword2'] = date("Y-m-d H:i",time());
                $data['remark']   = "请继续关注理优的培训活动，理优期待与你一起共同进步，提高教学服务质量。";

                /**
                 * 模板名称 : 解除排课限制通知2-14
                 * 模板ID   : SMS_46725145
                 * 模板内容 : 解除排课限制通知：${name}老师您好，您的课程已经解除排课限制。
                 请继续关注理优的培训活动，理优期待与你共同进步，提高教学服务质量。
                */
                $sms_id   = 46725145;
                $sms_data = [
                    "name" => $tea_nick,
                ];
            }

            $openid = $this->t_teacher_info->get_wx_openid($teacherid);
            if(isset($openid) && isset($template_id)){
                \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data);
            }elseif(isset($sms_id)){
                $phone = $this->t_teacher_info->get_phone($teacherid);
                if(isset($phone)){
                    $sign_name = \App\Helper\Utils::get_sms_sign_name();
                    \App\Helper\Utils::sms_common($phone,$sms_id,$sms_data,0,$sign_name);
                }
            }
        }

        return $this->output_succ();
    }

    public function get_lesson_full_list(){
        list($start_time, $end_time) = $this->get_in_date_range(date("Y-m-01",strtotime("-1 month",time())),0, 0,[],3 );

        $trial_money  = $this->get_in_int_val("trial_money",60);
        $normal_money = $this->get_in_int_val("normal_money",80);
        $order_str    = $this->get_in_int_val("order_str",0);
        $order_type   = $this->get_in_int_val("order_type",0);
        $lesson_num   = $this->get_in_int_val("lesson_num",15);
        $full_type    = $this->get_in_int_val("full_type",0);

        $lesson_list = $this->t_lesson_info->get_lesson_list_for_lesson_full_num($start_time,$end_time);

        $list      = [];
        $ret_list  = [];
        $teacherid = [];

        foreach($lesson_list as $val){
            if(!isset($teacherid[$val['teacherid']]['late'])){
                $teacherid[$val['teacherid']]['late']     = 0;
                $teacherid[$val['teacherid']]['has_full'] = 0;
            }

            if(!isset($list[$val['teacherid']]['trial_num'])){
                $list[$val['teacherid']]['trial_num']       = 0;
                $ret_list[$val['teacherid']]['trial_money'] = 0;
            }
            if(!isset($list[$val['teacherid']]['normal_num'])){
                $list[$val['teacherid']]['normal_num']       = 0;
                $ret_list[$val['teacherid']]['normal_money'] = 0;
            }
            if(!isset($list[$val['teacherid']]['all_num'])){
                $list[$val['teacherid']]['all_num']         = 0;
                $ret_list[$val['teacherid']]['count_money'] = 0;
            }
            if(!isset($list[$val['teacherid']]["nick"])){
                $list[$val['teacherid']]['nick']     = $val['realname'];
                $list[$val['teacherid']]['deduct_come_late'] = 0;
                $ret_list[$val['teacherid']]['nick'] = $val['realname'];
            }

            if($val['lesson_type']==2){
                if(($val['success_flag']<2 || $val['test_lesson_fail_flag']<100 )){
                    $list[$val['teacherid']]['trial_num']++;
                    if($list[$val['teacherid']]['trial_num']%$lesson_num==0){
                        $ret_list[$val['teacherid']]['trial_money']+=$trial_money;
                    }

                    $this->get_all_money(
                        $list[$val['teacherid']]['all_num'],$ret_list[$val['teacherid']]['count_money'],$lesson_num,$full_type,
                        $normal_money,$val['lesson_count']
                    );
                    if($ret_list[$val['teacherid']]['count_money']>0 && $teacherid[$val['teacherid']]['has_full']==0){
                        $teacherid[$val['teacherid']]['has_full']=1;
                    }
                }elseif(($val['fail_greater_4_hour_flag']==1
                        && ($val['test_lesson_fail_flag']==101 || $val['test_lesson_fail_flag']==102))
                ){
                    $teacherid[$val['teacherid']]['late'] = 1;
                    $list[$val['teacherid']]['trial_num'] = 0;
                    $list[$val['teacherid']]['all_num']   = 0;
                }elseif($val['deduct_come_late']>0){
                    $list[$val['teacherid']]['all_num']   = 0;
                }
            }else{
                if($val['confirm_flag']!=2 && $val['deduct_change_class']==0 && $val['deduct_come_late']==0){
                    $list[$val['teacherid']]['normal_num']++;
                    if($list[$val['teacherid']]['normal_num']%$lesson_num==0){
                        $ret_list[$val['teacherid']]['normal_money'] += $normal_money;
                    }

                    $this->get_all_money(
                        $list[$val['teacherid']]['all_num'],$ret_list[$val['teacherid']]['count_money'],$lesson_num,$full_type,
                        $normal_money,$val['lesson_count']
                    );

                    if($ret_list[$val['teacherid']]['count_money']>0 && $teacherid[$val['teacherid']]['has_full']==0){
                        $teacherid[$val['teacherid']]['has_full']=1;
                    }
                }elseif($val['deduct_change_class']>0){
                    $teacherid[$val['teacherid']]['late'] = 1;
                    $list[$val['teacherid']]['normal_num'] = 0;
                    $list[$val['teacherid']]['all_num']    = 0;
                }elseif($val['deduct_come_late']>0){
                    $list[$val['teacherid']]['all_num']    = 0;
                }
            }
        }


        $all_money    = 0;
        $count_money  = 0;
        $trial_money  = 0;
        $normal_money = 0;
        $all          = [];

        if($order_str==0){
            $order_str="all_money";
        }elseif($order_str==1){
            $order_str="trial_money";
        }elseif($order_str==2){
            $order_str="normal_money";
        }elseif($order_str==3){
            $order_str="count_money";
        }

        if($order_type==0){
            $order_type = SORT_DESC;
        }else{
            $order_type = SORT_ASC;
        }

        $count_teacher=0;
        if(is_array($ret_list) && !empty($ret_list)){
            foreach($ret_list as $key=>&$val){
                $val["all_money"]  = $val['normal_money']+$val['trial_money'];
                $all_money        += $val['all_money'];
                $trial_money      += $val['trial_money'];
                $normal_money     += $val['normal_money'];
                $count_money      += $val['count_money'];
                $list[$key]        = $val[$order_str];
                if($val['count_money']>0){
                    $count_teacher++;
                }
            }
            array_multisort($list,$order_type,$ret_list);
        }

        $late_teacher     = 0;
        $has_full_teacher = 0;
        foreach($teacherid as $tea_val){
            if($tea_val['late']==0){
                $late_teacher++;
                if($tea_val['has_full']){
                    $has_full_teacher++;
                }
            }
        }

        $all_teacher = $this->t_lesson_info->tongji_get_teacher_count($start_time,$end_time);

        $ret_list = \App\Helper\Utils::list_to_page_info($ret_list);
        return $this->pageView(__METHOD__,$ret_list,[
            "all_money"     => $all_money,
            "trial_money"   => $trial_money,
            "normal_money"  => $normal_money,
            "count_money"   => $count_money,

            "all_teacher"   => $all_teacher,
            "count_teacher" => $count_teacher,
            "late_teacher"  => $late_teacher,
            "has_full_teacher"  => $has_full_teacher,
        ]);
    }

    private function get_all_money(&$all_num,&$all_money,$lesson_num,$full_type,$money,$lesson_count){
        if($full_type==0){
            $all_num++;
            if($all_num%$lesson_num==0){
                $all_money += $money;
            }
        }elseif($full_type==1){
            $all_num += ($lesson_count/100);

            if($all_num/$lesson_num>1){
                $all_money += $money;
                $all_num    = $all_num%$lesson_num;
            }
        }
    }

    public function get_lesson_full_wage_old(){
        list($start_time, $end_time) = $this->get_in_date_range(date("Y-m-01",strtotime("-1 month",time())),0, 0,[],3 );

        $full_money = $this->get_in_int_val("normal_money",100);
        $lesson_num = $this->get_in_int_val("lesson_num",20);
        $order_type = $this->get_in_int_val("order_type",0);

        $all_teacher = $this->t_lesson_info->tongji_get_teacher_count($start_time,$end_time);
        $lesson_list = $this->t_lesson_info->get_lesson_list_for_lesson_full_num($start_time,$end_time);

        $list      = [];
        $ret_list  = [];
        $all_money = 0;
        foreach($lesson_list as $val){
            if(!isset($ret_list[$val['teacherid']]['count_money'])){
                $ret_list[$val['teacherid']]['count_money'] = 0;
            }
            if(!isset($ret_list[$val['teacherid']]["nick"])){
                $ret_list[$val['teacherid']]['nick']      = $val['realname'];
                $ret_list[$val['teacherid']]['teacherid'] = $val['teacherid'];
            }

            if($val['lesson_type']==2){
                if($val['success_flag']<2 || $val['test_lesson_fail_flag']<100){
                    if($val['lesson_full_num']%$lesson_num==0){
                        $ret_list[$val['teacherid']]['count_money'] += $full_money;
                        $all_money += $full_money;
                    }
                }
            }else{
                if($val['confirm_flag']!=2 && $val['deduct_change_class']==0 && $val['deduct_come_late']==0){
                    if($val['lesson_full_num']%$lesson_num==0){
                        $ret_list[$val['teacherid']]['count_money'] += $full_money;
                        $all_money+=$full_money;
                    }
                }
            }
        }

        $full_teacher = 0;
        foreach($ret_list as $val){
            if($val['count_money']>0){
                $full_teacher++;
            }
        }

        /**
         $list = $this->t_lesson_info->get_lesson_full_wage_old($start_time,$end_time,$lesson_num,$order_type);
         $num       = 1;
         $all_money = 0;
         foreach($list as &$val){
         $all_money += $val['lesson_full_price'];
         $val['num'] = $num;
         $num++;
         }
        */

        $ret_list = \App\Helper\Utils::list_to_page_info($ret_list);
        return $this->pageView(__METHOD__,$ret_list,[
            "all_money"    => $all_money,
            "all_teacher"  => $all_teacher,
            "full_teacher" => $full_teacher,
        ]);
    }

    public function send_sms_by_video_error(){
        $id                    = $this->get_in_int_val("id");
        $phone                 = $this->get_in_str_val("phone");
        $nick                  = $this->get_in_str_val("nick");
        $teacher_re_submit_num = $this->get_in_int_val("teacher_re_submit_num");

        if($teacher_re_submit_num==0){
            /**
             * 模板名称 : 老师视频不完整
             * 模板ID   : SMS_46705122
             * 模板内容 : ${name}老师您好，理优面试老师详细回看了您的试讲视频，你的试讲视频录制不成功请重新录制，
             理优教育致力于打造高水平的教学服务团队，期待将来有机会您能加入理优教学团队。
             如有对面试结果有疑惑请联系招聘老师。
            */
            $sms_id = 46705122;
            $arr    = [
                "name" => $nick
            ];
            $ret = \App\Helper\Utils::sms_common($phone,$sms_id,$arr);

            $teacher_re_submit_num++;
            $this->t_teacher_lecture_info->field_update_list($id,[
                "teacher_re_submit_num" => $teacher_re_submit_num,
                "status"                => 4,
            ]);
            $teacher_info = $this->t_teacher_lecture_info->field_get_list($id,"phone,subject,nick");
            $admin_arr = [
                492 => "zoe",
                513 => "abby",
                790 => "ivy",
            ];
            $header_msg  = "老师视频录制失败,重审通知";
            $from_user   = "理优面试组";
            $admin_url   = "http://admin.leo1v1.com/human_resource/teacher_lecture_list/?phone=".$teacher_info["phone"];
            $subject_str = E\Esubject::get_desc($teacher_info['subject']);

            foreach($admin_arr as $id=>$name){
                $msg_info = $name."老师你好,".$subject_str."学科老师".$teacher_info['nick'].",因视频录制失败,需要重审";
                $this->t_manager_info->send_wx_todo_msg_by_adminid($id,$from_user,$header_msg,$msg_info,$admin_url);
            }
            return $this->output_succ();


            // if($ret){
               
            //     return $this->output_succ();
            // }else{
            //     return $this->output_err("短信发送失败！");
            // }
        }else{
            return $this->output_err("已经通知过老师，请勿重复点击！");
        }
    }

    /**
     * 一键把老师设置为999开头的测试老师
     */
    public function switch_teacher_to_test(){
        $teacherid = $this->get_in_int_val("teacherid");
        $phone     = $this->get_in_int_val("phone");

        $max_phone = $this->t_teacher_info->get_max_test_phone();
        $new_phone = $max_phone+1;
        $this->set_in_value("userid",$teacherid);
        $this->set_in_value("phone",$phone);
        $this->set_in_value("new_phone",$new_phone);
        $this->set_in_value("role",E\Erole::V_TEACHER);
        $ret = $this->change_phone();
        return $ret;
    }

    /**
     * 更改老师的手机号
     */
    public function change_phone(){
        $userid    = $this->get_in_int_val("userid");
        $phone     = $this->get_in_int_val("phone");
        $new_phone = $this->get_in_int_val("new_phone");
        $role      = $this->get_in_int_val("role",E\Erole::V_STUDENT);

        $ret = \App\Helper\Utils::check_phone($new_phone);
        if(!$ret){
            return $this->output_err("手机号不是11位!");
        }
        $ret = $this->t_phone_to_user->check_is_exist_by_phone_and_userid($userid,$phone,$role);
        if(empty($ret)){
            return $this->output_err("电话与用户不匹配，无法修改手机号！");
        }
        $new_ret = $this->t_phone_to_user->check_is_exist_by_phone_and_userid(-1,$new_phone,$role);
        if(!empty($new_ret)){
            return $this->output_err("该账号已存在！");
        }
        $update_tea_arr = [
            "phone"       => $new_phone,
            "phone_spare" => $new_phone,
        ];
        if(substr($new_phone,0,3)=="999"){
            $update_tea_arr["is_test_user"] = 1;
        }

        $this->t_phone_to_user->start_transaction();
        $update_ret = $this->t_phone_to_user->set_phone($new_phone,$role,$userid);
        $tea_ret    = $this->t_teacher_info->field_update_list($userid,$update_tea_arr);

        if($update_ret && $tea_ret){
            $this->t_phone_to_user->commit();
            \App\Helper\Utils::logger("update teacher phone success!teacherid:".$userid
                                      ." old phone:".$phone." new phone:".$new_phone);

            if($role==E\Erole::V_TEACHER){
                $record_info="手机变更,由".$phone."变更为".$new_phone;
                $this->t_teacher_record_list->row_insert([
                    'teacherid'   => $userid,
                    'type'        => 6,
                    'record_info' => $record_info,
                    'add_time'    => time(),
                    'acc'         => $this->get_account(),
                ]);
            }
            return $this->output_succ();
        }else{
            \App\Helper\Utils::logger("update teacher phone fail! update ret is:".$update_ret." tea ret is ".$tea_ret);
            $this->t_phone_to_user->rollback();
            return $this->output_err("手机号更新失败!请重试!");
        }
    }

    public function set_teacher_lecture_is_test(){
        $id = $this->get_in_int_val("id");

        $old_is_test_flag=$this->t_teacher_lecture_info->get_is_test_flag($id);
        if($old_is_test_flag){
            $is_test_flag=0;
        }else{
            $is_test_flag=1;
        }

        $ret=$this->t_teacher_lecture_info->field_update_list($id,[
            "is_test_flag"=>$is_test_flag
        ]);

        if($ret){
            return $this->output_succ();
        }else{
            return $this->output_err("更改失败，请重试！");
        }
    }

    public function set_teacher_level(){
        $teacherid = $this->get_in_int_val("teacherid");
        $old_level = $this->get_in_int_val("old_level");
        $level     = $this->get_in_int_val("level");
        $acc       = $this->get_account();
        $nick      = $this->cache_get_teacher_nick($teacherid);

        $teacher_info = $this->t_teacher_info->get_teacher_info($teacherid);
        $teacher_money_type = $teacher_info['teacher_money_type'];
        if(in_array($teacher_money_type,[2,3])){
            return $this->output_err("固定工资和外聘不能修改等级！");
        }

        $this->t_teacher_info->start_transaction();
        $ret = $this->t_teacher_info->field_update_list($teacherid,[
            "level" => $level
        ]);
        if(!$ret){
            $this->t_teacher_info->rollback();
            return $this->output_err("更改老师等级失败！");
        }
        \App\Helper\Utils::logger("set teacher level teacherid:".$teacherid."level:".$level."the account is ".$acc);
        $lesson_num = $this->t_lesson_info->get_teacher_level_num($teacherid,$old_level);
        if($lesson_num>0){
            $ret = $this->t_lesson_info->reset_lesson_teacher_level($teacherid,$level);
            if(!$ret){
                $this->t_teacher_info->rollback();
                return $this->output_err("更改课程的老师等级失败！");
            }
        }

        $level_str     = E\Elevel::get_desc($level);
        $old_level_str = E\Elevel::get_desc($old_level);
        if(!in_array($teacher_money_type,[2,3]) && $old_level<$level){
            $old_money   = $this->t_teacher_money_type->get_teacher_money_type($teacher_money_type,$old_level);
            $new_money   = $this->t_teacher_money_type->get_teacher_money_type($teacher_money_type,$level);
            $diff_money  = $new_money[101]['money']-$old_money[101]['money'];
            \App\Helper\Utils::logger("set teacher level diff money".$diff_money
                                      ." old_money :".json_encode($old_money)."new money".json_encode($new_money));

            if($teacher_info['wx_openid']!=""){
                /**
                 * 标题        等级升级通知
                 * template_id E9JWlTQUKVWXmUUJq_hvXrGT3gUvFLN6CjYE1gzlSY0
                 * {{first.DATA}}
                 * 用户昵称：{{keyword1.DATA}}
                 * 最新等级：{{keyword2.DATA}}
                 * 生效时间：{{keyword3.DATA}}
                 * {{remark.DATA}}
                 */
                $template_id = "E9JWlTQUKVWXmUUJq_hvXrGT3gUvFLN6CjYE1gzlSY0";
                $data['first']    = "老师你好，你的表现达到理优平台升级标准，因此会提高你的薪资等级。";
                $data['keyword1'] = $nick;
                $data['keyword2'] = $level_str;
                $data['keyword3'] = date("Y-m-d H:i");
                $data['remark']   = "升级后你的课时费每课时增加".$diff_money."元，期待你在理优平台进一步成长进步，加油！";
                \App\Helper\Utils::send_teacher_msg_for_wx($teacher_info['wx_openid'],$template_id,$data);
            }elseif($teacher_info['phone']){
                /**
                 * 模板名称 : 老师等级升级通知
                 * 模板ID   : SMS_50270017
                 * 模板内容 : ${name}老师你好，你的表现达到理优平台升级标准，因此会提高你的薪资等级。
                 升级后你的课时费每课时增加${price}元，期待你在理优平台进一步成长进步，加油！
                 */
                $sms_id    = 50270017;
                $sign_name = \App\Helper\Utils::get_sms_sign_name();
                $arr       = [
                    "name"  => $nick,
                    "price" => $diff_money,
                ];

                \App\Helper\Utils::sms_common($teacher_info['phone'],$sms_id,$arr,0,$sign_name);
            }
        }

        $this->t_teacher_info->commit();
        $record_info = "老师等级变更,由".$old_level_str."变更为".$level_str;
        $this->t_teacher_record_list->row_insert([
            'teacherid'   => $teacherid,
            'type'        => 6,
            'record_info' => $record_info,
            'add_time'    => time(),
            'acc'         => $this->get_account(),
        ]);
        return $this->output_succ();
    }

    public function add_teacher_record(){
        $record_info = $this->get_in_str_val("record_info");
        $type        = $this->get_in_int_val("type");
        $teacherid   = $this->get_in_int_val("teacherid");

        $ret = $this->t_teacher_record_list->row_insert([
            "teacherid"   => $teacherid,
            "type"        => $type,
            "record_info" => $record_info,
            "add_time"    => time(),
            "acc"         => $this->get_account(),
        ]);

        if($ret){
            return $this->output_succ();
        }else{
            return $this->output_err("添加出错！");
        }
    }

    public function research_qz_teacher_stu_info(){
        list($start_time,$end_time, $opt_date_str)=$this->get_in_date_range(
            0,150, 1, [
                1 => array("lesson_end","结课时间"),
            ]
        );

        $teacherid   = $this->get_in_int_val("teacherid",-1);
        $account_role   = $this->get_in_int_val("account_role",-1);
        // $teacherid = 53289;
        $ret_info = $this->t_course_order->get_tea_stu_info($teacherid,$account_role);
        $end = time();
        $start = time()-60*86400;
        foreach($ret_info["list"] as $k=>&$item){
            $teacherid = $item["teacherid"];
            $userid= $item["userid"];
            $item["lesson_total"] = $this->t_lesson_info->get_month_stu_lesson_count($start,$end,$teacherid,$userid);
            $item["lesson_left"] = $item["assigned_lesson_count"]-$item["finish_lesson_count"];
            if($item["lesson_total"]>0){
                $day = intval(time()+($item["lesson_left"]*60/$item["lesson_total"])*86400);
                $item["end_day"]= date("Y-m-d",$day);
            }else{
                $day=0;
                $item["end_day"]="";
            }

            E\Egrade::set_item_value_str($item,"grade");
            if($day>$end_time || $day <$start_time){
                unset($ret_info["list"][$k]);
            }
        }


        return $this->pageView(__METHOD__,$ret_info);
    }

    public function teacher_test_lesson_info_fulltime(){
        $this->set_in_value("fulltime_flag",1);
        return $this->teacher_test_lesson_info();
    }

    public function teacher_test_lesson_info_zj(){
        return $this->teacher_test_lesson_info();
    }

    public function teacher_test_lesson_info(){
        $this->check_and_switch_tongji_domain();
        $sum_field_list = [
            "all_lesson",
            "success_lesson",
            "lesson_num_old",
            "lesson_num",
            "test_person_num",
            "lesson_num_other",
            "kk_num",
            "change_num",
            "success_per",
            "have_order",
            "order_number",
            "have_order_other",
            "kk_order",
            "change_order",
            "order_num_per",
            "order_per",
            "order_num_per_other",
            "kk_per",
            "change_per"
        ];
        $order_field_arr = array_merge(["nick"],$sum_field_list);
        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str($order_field_arr,"nick desc");

        $page_num    = $this->get_in_page_num();
        list($start_time,$end_time)=$this->get_in_date_range( 0,0,0,[],3);

        $teacherid       = $this->get_in_int_val('teacherid', -1);
        $subject         = $this->get_in_int_val('subject', -1);
        $teacher_subject = $this->get_in_int_val('teacher_subject', -1);
        $identity        = $this->get_in_int_val('identity', -1);
        $grade_part_ex   = $this->get_in_int_val('grade_part_ex',-1);
        $tea_status      = $this->get_in_int_val('tea_status',-1);
        $teacher_account = $this->get_in_int_val('teacher_account', -1);
        $qzls_flag       = $this->get_in_int_val('qzls_flag', -1);
        $fulltime_flag   = $this->get_in_int_val('fulltime_flag', -1);
        $create_now      = $this->get_in_int_val('create_now', -1);
        $fulltime_teacher_type = $this->get_in_int_val("fulltime_teacher_type", -1);
        $adminid         = $this->get_account_id();
        $right_list      = $this->get_tea_subject_and_right_by_adminid($adminid);

        $tea_subject = $right_list["tea_subject"];
        $tea_right   = $right_list["tea_right"];
        $qz_flag     = $right_list["qz_flag"];
        if($adminid==478){
            $tea_subject="";
        }


        $this->t_teacher_info->switch_tongji_database();
        $this->t_lesson_info->switch_tongji_database();

        $lesson_end_time = $this->get_test_lesson_end_time($end_time);

        $ret_info = $this->t_teacher_info->get_teacher_test_lesson_info_by_time($page_num,$teacherid,$teacher_subject,$identity,$tea_subject,$qz_flag,$tea_status,$teacher_account,$qzls_flag,$fulltime_flag,$create_now,$start_time,$end_time,$fulltime_teacher_type);

        $teacherid_list=[];
        foreach($ret_info['list'] as $t_item) {
            $teacherid_list[]=$t_item["teacherid"];
        }
        $all_lesson_list = $this->t_lesson_info->get_all_lesson_num_info( $start_time,$lesson_end_time,$subject,$grade_part_ex,$teacherid_list);
        $test_person_num= $this->t_lesson_info->get_teacher_test_person_num_list( $start_time,$lesson_end_time,$subject,$grade_part_ex,$teacherid_list);
        $test_person_num_old= $this->t_lesson_info->get_teacher_test_person_num_list_old( $start_time,$lesson_end_time,$subject,$grade_part_ex,$teacherid_list);

        $test_person_num_other= $this->t_lesson_info->get_teacher_test_person_num_list_other( $start_time,$lesson_end_time,$subject,$grade_part_ex,$teacherid_list);

        $kk_test_person_num= $this->t_lesson_info->get_kk_teacher_test_person_num_list( $start_time,$lesson_end_time,$subject,$grade_part_ex,$teacherid_list);
        $change_test_person_num= $this->t_lesson_info->get_change_teacher_test_person_num_list( $start_time,$lesson_end_time,$subject,$grade_part_ex,$teacherid_list);
        $success_test_lesson_list = $this->t_lesson_info->get_success_test_lesson_list_new($start_time,$lesson_end_time,$subject,$grade_part_ex,$teacherid_list);
        $success_not_in_lesson_list =  $this->t_lesson_info->get_success_not_test_lesson_list_new($start_time,$lesson_end_time,$subject,$grade_part_ex,$teacherid_list);
        $have_order_list =$this->t_lesson_info->get_have_order_list_new($start_time,$lesson_end_time,$subject,$grade_part_ex,$teacherid_list);
        $subject_num_list = $this->t_lesson_info->get_teacher_test_subject_num_list( $start_time,$lesson_end_time,$subject,$grade_part_ex,$teacherid_list);

        $date                              = \App\Helper\Utils::get_month_range(time(),1);
        $teacher_regular_stu_list          = $this->t_lesson_info->get_regular_stu_num($date["sdate"],$date["edate"],$teacherid_list);
        $teacher_test_lesson_num_list      = $this->t_lesson_info->get_test_lesson_num_list(time(),time()+21*86400,$teacherid_list);
        $teacher_lesson_count_total        = $this->t_lesson_info->get_teacher_lesson_count_total(time(),$date["edate"],$teacherid_list);
        $date_week                         = \App\Helper\Utils::get_week_range(time(),1);
        $teacher_test_lesson_num_list_week = $this->t_lesson_info->get_test_lesson_num_list(time(),$date_week["edate"],$teacherid_list);

        foreach($ret_info["list"] as &$item){
            if($item["train_through_new_time"] !=0){
                $item["work_day"] = ceil((time()-$item["train_through_new_time"])/86400)."天";
            }else{
                $item["work_day"] ="";
            }

            E\Eidentity::set_item_value_str($item);
            E\Egrade_part_ex::set_item_value_str($item,"grade_part_ex");
            E\Egrade_part_ex::set_item_value_str($item,"second_grade");
            E\Elimit_plan_lesson_type::set_item_value_str($item,"limit_plan_lesson_type");
            $item["test_person_num"] = isset($test_person_num[$item["teacherid"]])?$test_person_num[$item["teacherid"]]["person_num"]:0;
            $item["all_lesson"] = isset($all_lesson_list[$item["teacherid"]])?$all_lesson_list[$item["teacherid"]]["num"]:0;
            $item["lesson_num"] = isset($test_person_num[$item["teacherid"]])?$test_person_num[$item["teacherid"]]["lesson_num"]:0;
            $item["lesson_num_old"] = isset($test_person_num_old[$item["teacherid"]])?$test_person_num_old[$item["teacherid"]]["lesson_num"]:0;
            $item["have_order"] = isset($test_person_num[$item["teacherid"]])?$test_person_num[$item["teacherid"]]["have_order"]:0;
            $item["lesson_num_other"] = isset($test_person_num_other[$item["teacherid"]])?$test_person_num_other[$item["teacherid"]]["lesson_num"]:0;
            $item["have_order_other"] = isset($test_person_num_other[$item["teacherid"]])?$test_person_num_other[$item["teacherid"]]["have_order"]:0;

            $item["order_number"] = isset($success_test_lesson_list[$item["teacherid"]])?$success_test_lesson_list[$item["teacherid"]]["order_number"]:0;
            $item["success_lesson"] = isset($success_test_lesson_list[$item["teacherid"]])?$success_test_lesson_list[$item["teacherid"]]["success_lesson"]:0;
            $item["success_not_in_lesson"] = isset($success_not_in_lesson_list[$item["teacherid"]])?$success_not_in_lesson_list[$item["teacherid"]]["success_not_in_lesson"]:0;
            $item["subject_num"] = isset($subject_num_list[$item["teacherid"]])?$subject_num_list[$item["teacherid"]]["subject_num"]:0;

            $item["kk_num"] = isset($kk_test_person_num[$item["teacherid"]])?$kk_test_person_num[$item["teacherid"]]["kk_num"]:0;
            $item["kk_order"] = isset($kk_test_person_num[$item["teacherid"]])?$kk_test_person_num[$item["teacherid"]]["kk_order"]:0;
            $item["kk_per"] = !empty($item["kk_num"])?round($item["kk_order"]/$item["kk_num"],4)*100:0;
            $item["change_num"] = isset($change_test_person_num[$item["teacherid"]])?$change_test_person_num[$item["teacherid"]]["change_num"]:0;
            $item["change_order"] = isset($change_test_person_num[$item["teacherid"]])?$change_test_person_num[$item["teacherid"]]["change_order"]:0;
            $item["change_per"] = !empty($item["change_num"])?round($item["change_order"]/$item["change_num"],4)*100:0;

            if($item['is_freeze']==1){
                $item['status_str'] = "已冻结";
            }elseif($item['limit_plan_lesson_type']>0){
                $item['status_str'] = E\Elimit_plan_lesson_type::get_desc($item['limit_plan_lesson_type']);
            }else{
                $item['status_str'] = "正常";
            }

            $item["success_per"] = !empty($item["all_lesson"])?sprintf("%.2f",$item["success_lesson"]/$item["all_lesson"])*100:0;
            $item["order_per"]   = !empty($item["success_lesson"])?round($item["order_number"]/$item["success_lesson"],4)*100:0;
            $item["regular_stu_num"]=isset($teacher_regular_stu_list[$item["teacherid"]])?$teacher_regular_stu_list[$item["teacherid"]]["regular_count"]:0;
            $item["test_lesson_num"]=isset($teacher_test_lesson_num_list[$item["teacherid"]])?$teacher_test_lesson_num_list[$item["teacherid"]]["test_lesson_count"]:0;
            $item["test_lesson_num_week"]=isset($teacher_test_lesson_num_list_week[$item["teacherid"]])?$teacher_test_lesson_num_list_week[$item["teacherid"]]["test_lesson_count"]:0;
            $item["teacher_lesson_count_total"]=isset($teacher_lesson_count_total[$item["teacherid"]])?$teacher_lesson_count_total[$item["teacherid"]]["lesson_total"]/100:0;
            $item["order_num_per"] = !empty($item["test_person_num"])?round($item["have_order"]/$item["test_person_num"],4)*100:0;
            $item["order_num_per_other"] = !empty($item["lesson_num_other"])?round($item["have_order_other"]/$item["lesson_num_other"],4)*100:0;
            $item["kk_lesson_per"] = !empty($item["success_lesson"]-$item["test_person_num"])?round(($item["order_number"]-$item["have_order"])/($item["success_lesson"]-$item["test_person_num"]),4)*100:0;
            $item["freeze_time_str"] = date('Y-m-d',$item["freeze_time"]);
            $item["limit_plan_lesson_time_str"] = date("Y-m-d",$item["limit_plan_lesson_time"]);
            $not_grade_arr=explode(",",$item["not_grade"]);
            $not_grade_str="";
            if(!empty($not_grade_arr)){
                foreach($not_grade_arr as $ss){
                    $not_grade_str  .= E\Egrade::get_desc($ss).",";
                }
            }
            $item["not_grade_str"] = trim($not_grade_str,",");
            if(!empty($item["freeze_adminid"])){
                $item["freeze_adminid_str"] = $this->t_manager_info->get_account($item["freeze_adminid"]);
            }else{
                $item["freeze_adminid_str"]="";
            }

        }
        $day_time = $this->get_avg_conversion_time(time(),2);
        $rr = $this->t_lesson_info->get_order_add_time();
        $order_lesson_day = !empty($rr["all_count"])?round(($rr["lesson_time"]-$rr["order_time"])/$rr["all_count"]/86400,1):0;
        if (!$order_in_db_flag) {

            \App\Helper\Utils::order_list( $ret_info["list"], $order_field_name, $order_type );
        }

        return $this->pageView(__METHOD__,$ret_info,[
            "day_time"   =>$day_time,
            "order_lesson_day" => $order_lesson_day,
            "start_time"  => $start_time,
            "end_time"    => $end_time,
            "subject"    => $subject,
            "tea_status"  => $tea_status,
            "tea_subject" => @$tea_subject,
            "tea_right"   => @$tea_right,
            "adminid"     => $this->get_account_id(),
            "total"       => @$total_arr
        ]);

    }

    public function teacher_test_lesson_info_total_fulltime(){
        $this->set_in_value("fulltime_flag",1);
        return $this->teacher_test_lesson_info_total();
    }

    public function teacher_test_lesson_info_total(){
        list($start_time,$end_time)=$this->get_in_date_range(-10,0);

        $teacherid                  = $this->get_in_int_val('teacherid', -1);
        $subject                    = $this->get_in_int_val('subject', -1);
        $teacher_subject            = $this->get_in_int_val('teacher_subject', -1);
        $identity                   = $this->get_in_int_val('identity', -1);
        $grade_part_ex              =$this->get_in_int_val('grade_part_ex',-1);
        $tea_status                 =$this->get_in_int_val('tea_status',-1);
        $teacher_account            = $this->get_in_int_val('teacher_account', -1);
        $fulltime_flag            = $this->get_in_int_val('fulltime_flag', -1);
        $fulltime_teacher_type = $this->get_in_int_val("fulltime_teacher_type", -1);

        $adminid      = $this->get_account_id();
        $right_list = $this->get_tea_subject_and_right_by_adminid($adminid);
        $tea_subject = $right_list["tea_subject"];
        $tea_right  = $right_list["tea_right"];
        $qz_flag = $right_list["qz_flag"];


        $this->t_lesson_info->switch_tongji_database();

        $day_time = $this->get_avg_conversion_time(time(),2);
        $rr = $this->t_lesson_info->get_order_add_time();
        $order_lesson_day = !empty($rr["all_count"])?round(($rr["lesson_time"]-$rr["order_time"])/$rr["all_count"]/86400,1):0;
        $lesson_end_time = $this->get_test_lesson_end_time($end_time);

        $all_lesson_total = $this->t_lesson_info->get_all_lesson_num_info_total( $start_time,$lesson_end_time,$subject,$grade_part_ex,$teacherid,$teacher_subject,$identity,$tea_subject,$qz_flag,$tea_status,$teacher_account,$fulltime_flag,$fulltime_teacher_type);
        $test_person_num_total_old= $this->t_lesson_info->get_teacher_test_person_num_list_total_old( $start_time,$lesson_end_time,$subject,$grade_part_ex,$teacherid,$teacher_subject,$identity,$tea_subject,$qz_flag,$tea_status,$teacher_account,$fulltime_flag,$fulltime_teacher_type);
        $test_person_num_total= $this->t_lesson_info->get_teacher_test_person_num_list_total( $start_time,$lesson_end_time,$subject,$grade_part_ex,$teacherid,$teacher_subject,$identity,$tea_subject,$qz_flag,$tea_status,$teacher_account,$fulltime_flag,$fulltime_teacher_type);
        //
        $test_person_num_total_other= $this->t_lesson_info->get_teacher_test_person_num_list_total_other( $start_time,$lesson_end_time,$subject,$grade_part_ex,$teacherid,$teacher_subject,$identity,$tea_subject,$qz_flag,$tea_status,$teacher_account,$fulltime_flag,$fulltime_teacher_type);

        $kk_test_person_num_total= $this->t_lesson_info->get_kk_teacher_test_person_num_list_total( $start_time,$lesson_end_time,$subject,$grade_part_ex,$teacherid,$teacher_subject,$identity,$tea_subject,$qz_flag,$tea_status,$teacher_account,$fulltime_flag,$fulltime_teacher_type);
        $change_test_person_num_total= $this->t_lesson_info->get_change_teacher_test_person_num_list_total( $start_time,$lesson_end_time,$subject,$grade_part_ex,$teacherid,$teacher_subject,$identity,$tea_subject,$qz_flag,$tea_status,$teacher_account,$fulltime_flag,$fulltime_teacher_type);

        $success_test_lesson_list_total = $this->t_lesson_info->get_success_test_lesson_list_new_total($start_time,$lesson_end_time,$subject,$grade_part_ex,$teacherid,$teacher_subject,$identity,$tea_subject,$qz_flag,$tea_status,$teacher_account,$fulltime_flag,$fulltime_teacher_type);
        $total_arr=[];
        $total_arr["all_lesson"] = $all_lesson_total;
        $total_arr["success_lesson"] =  $success_test_lesson_list_total["success_lesson"];
        $total_arr["lesson_num"] =  $test_person_num_total["lesson_num"];//
        $total_arr["lesson_num_old"] =  $test_person_num_total_old["lesson_num"];
        //
        $total_arr["lesson_num_other"] =  $test_person_num_total_other["lesson_num"];
        $total_arr["test_person_num"] =  $test_person_num_total["person_num"];//
        $total_arr["kk_num"] =  $kk_test_person_num_total["kk_num"];
        $total_arr["kk_order"] =  $kk_test_person_num_total["kk_order"];
        $total_arr["change_num"] =  $change_test_person_num_total["change_num"];
        $total_arr["change_order"] =  $change_test_person_num_total["change_order"];
        $total_arr["have_order"] = $test_person_num_total["have_order"];//
        //
        $total_arr["have_order_other"] = $test_person_num_total_other["have_order"];
        $total_arr["order_number"] = $success_test_lesson_list_total["order_number"];

        $total_arr["success_per"] = !empty($total_arr["all_lesson"])?sprintf("%.2f",$total_arr["success_lesson"]/$total_arr["all_lesson"])*100:0;
        $total_arr["kk_per"] = !empty($total_arr["kk_num"])?round($total_arr["kk_order"]/$total_arr["kk_num"],4)*100:0;
        $total_arr["change_per"] = !empty($total_arr["change_num"])?round($total_arr["change_order"]/$total_arr["change_num"],4)*100:0;

        $total_arr["order_num_per"] = !empty($total_arr["test_person_num"])?round($total_arr["have_order"]/$total_arr["test_person_num"],4)*100:0;
        //
        $total_arr["order_num_per_other"] = !empty($total_arr["lesson_num_other"])?round($total_arr["have_order_other"]/$total_arr["lesson_num_other"],4)*100:0;

        $total_arr["order_per"]   = !empty($total_arr["success_lesson"])?round($total_arr["order_number"]/$total_arr["success_lesson"],4)*100:0;
        return $this->pageView(__METHOD__,null,[
            "day_time"   =>$day_time,
            "order_lesson_day" => $order_lesson_day,
            "tea_status"  => $tea_status,
            "tea_subject" => $subject,
            "tea_right"   => $tea_right,
            "adminid"     => $this->get_account_id(),
            "total"       => @$total_arr
        ]);

    }

    public function get_broken_line_order_rate(){ //折线
        list($start_time,$end_time)=$this->get_in_date_range(-10,0);

        $teacherid                  = $this->get_in_int_val('teacherid', -1);
        $subject                    = $this->get_in_int_val('subject', -1);
        $teacher_subject            = $this->get_in_int_val('teacher_subject', -1);
        $identity                   = $this->get_in_int_val('identity', -1);
        $grade_part_ex              =$this->get_in_int_val('grade_part_ex',-1);
        $tea_status                 =$this->get_in_int_val('tea_status',-1);
        $teacher_account            = $this->get_in_int_val('teacher_account', -1);
        $fulltime_flag            = 1; //全职老师
        $fulltime_teacher_type = $this->get_in_int_val("fulltime_teacher_type", -1);

        $adminid      = $this->get_account_id();
        $right_list = $this->get_tea_subject_and_right_by_adminid($adminid);
        $tea_subject = $right_list["tea_subject"];
        $tea_right  = $right_list["tea_right"];
        $qz_flag = $right_list["qz_flag"];

        $this->t_lesson_info->switch_tongji_database();

        $begin_month = 3;
        $now = date('n');

        $list = [];
        $start_time  = strtotime(date('Y-m-d'));

        $end_time = strtotime(date('Y-m-d', strtotime(" 1510156800 +1 month -1 day")));

        dd($end_time);

        $month_list = [
            "2017-3-1",
            "2017-4-1",
            "2017-5-1",
            "2017-6-1",
            "2017-7-1",
            "2017-8-1",
            "2017-9-1",
            "2017-10-1",
            "2017-11-1",
        ];

        foreach($month_list as $item){
            $start_time  = strtotime($item);
            $end_time = strtotime(date('Y-m-d', strtotime("$start_time +1 month -1 day")));

            $list['math_order'][$begin_month] = $this->t_lesson_info_b3->get_teacher_test_person_num_list_total( $start_time,$end_time,2,$grade_part_ex,$teacherid,$teacher_subject,$identity,$tea_subject,$qz_flag,$tea_status,$teacher_account,$fulltime_flag,$fulltime_teacher_type);
            $list['math_success'][$begin_month] = $this->t_lesson_info_b3->get_success_test_lesson_list_broken($start_time,$end_time,2,$grade_part_ex,$teacherid,$teacher_subject,$identity,$tea_subject,$qz_flag,$tea_status,$teacher_account,$fulltime_flag,$fulltime_teacher_type);

            $list['china_order'][$begin_month] = $this->t_lesson_info_b3->get_teacher_test_person_num_list_total( $start_time,$end_time,1,$grade_part_ex,$teacherid,$teacher_subject,$identity,$tea_subject,$qz_flag,$tea_status,$teacher_account,$fulltime_flag,$fulltime_teacher_type);
            $list['china_success'][$begin_month] = $this->t_lesson_info_b3->get_success_test_lesson_list_broken($start_time,$end_time,1,$grade_part_ex,$teacherid,$teacher_subject,$identity,$tea_subject,$qz_flag,$tea_status,$teacher_account,$fulltime_flag,$fulltime_teacher_type);

            $list['english_order'][$begin_month] = $this->t_lesson_info_b3->get_teacher_test_person_num_list_total( $start_time,$end_time,3,$grade_part_ex,$teacherid,$teacher_subject,$identity,$tea_subject,$qz_flag,$tea_status,$teacher_account,$fulltime_flag,$fulltime_teacher_type);
            $list['english_success'][$begin_month] = $this->t_lesson_info_b3->get_success_test_lesson_list_broken($start_time,$end_time,3,$grade_part_ex,$teacherid,$teacher_subject,$identity,$tea_subject,$qz_flag,$tea_status,$teacher_account,$fulltime_flag,$fulltime_teacher_type);
        }



        $list = [
            "china_rate" => ''
        ];



        $test_list = $this->t_lesson_info_b3->get_test_list_for_month($start_time,$end_time);

        return $this->pageView(__METHOD__,[],[
            'data_ex_list' => $list
        ]);


    }


    public function get_assign_jw_adminid_list(){
        $this->check_and_switch_tongji_domain();
        $page_num = $this->get_in_page_num();
        $teacherid              = $this->get_in_int_val('teacherid',-1);
        $jw_adminid             = $this->get_in_int_val('jw_adminid',-1);
        $grade_part_ex          = $this->get_in_int_val("grade_part_ex",-1);
        $subject                = $this->get_in_int_val("subject",-1);
        $second_subject         = $this->get_in_int_val("second_subject",-1);
        $identity               = $this->get_in_int_val("identity",-1);
        $class_will_type        = $this->get_in_int_val("class_will_type",-1);
        $have_lesson            = $this->get_in_int_val("have_lesson",0);
        $revisit_flag           = $this->get_in_int_val("revisit_flag",0);
        $textbook_flag          = $this->get_in_int_val("textbook_flag",0);
        $have_test_lesson_flag          = $this->get_in_int_val("have_test_lesson_flag",-1);

        $adminid = $this->get_account_id();
        $account = $this->get_account();
        // $adminid=343;
        if($adminid==74 || $adminid==349 || $adminid==99 || $account=="adrian"){
            $adminid=-1;
        }
        $ret_info = $this->t_teacher_info->get_assign_jw_adminid_info($page_num,$adminid,$teacherid,$grade_part_ex,$subject,$second_subject,$identity,$jw_adminid,$class_will_type,$have_lesson,$revisit_flag,$textbook_flag,$have_test_lesson_flag);
        foreach($ret_info["list"] as &$item){
            if($item["train_through_new_time"] !=0){
                $item["work_day"] = ceil((time()-$item["train_through_new_time"])/86400)."天";
            }else{
                $item["work_day"] ="";
            }

            E\Eidentity::set_item_value_str($item);
            E\Esubject::set_item_value_str($item,"subject");
            E\Esubject::set_item_value_str($item,"l_subject");
            E\Esubject::set_item_value_str($item,"second_subject");
            E\Egrade_range::set_item_value_str($item,"grade_start");
            E\Egrade_range::set_item_value_str($item,"grade_end");

            E\Egrade_part_ex::set_item_value_str($item);
            E\Eclass_will_type::set_item_value_str($item);
            E\Eclass_will_sub_type::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item, "assign_jw_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item, "add_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item, "recover_class_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item, "lesson_start","_str");
            $arr= explode(",",$item["teacher_textbook"]);
            foreach($arr as $val){
                @$item["textbook"] .=  E\Eregion_version::get_desc ($val).",";
            }
            $item["textbook"] = trim($item["textbook"],",");
            $item["phone_ex"] = preg_replace('/(1[3456789]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$item['phone']);


        }
        // dd($ret_info);
        $jw_list = $this->t_manager_info->get_adminid_list_by_account_role(3);
        foreach($jw_list as $k=>$v){
            if(in_array($v["uid"],[454,492,513])){
                unset($jw_list[$k]);
            }
        }
        return $this->pageView(__METHOD__,$ret_info,[
            "adminid" => $adminid,
            "jw_list" => $jw_list
        ]);
    }

    public function get_check_textbook_tea_list(){
        $adminid             = $this->get_account_id();
        $textbook_check_flag = $this->get_in_int_val("textbook_check_flag",-1);
        $user_name           = $this->get_in_str_val("user_name");
        $page_num            = $this->get_in_page_num();

        if($adminid==74 || $adminid==349 || $adminid==99 || $adminid==186 || $adminid==486){
            $adminid=-1;
        }

        $check_time = strtotime("2017-9-2");
        $ret_info   = $this->t_teacher_info->get_check_textbook_tea_list($page_num,$adminid,$textbook_check_flag,$user_name);

        return $this->pageView(__METHOD__,$ret_info);
    }

    public function get_avg_conversion_time($time=0,$decimal_point=0){
        if($time==0){
            $time = time();
        }
        $this->t_lesson_info->switch_tongji_database();

        $start_time_ave = $time-30*86400;
        $res = $this->t_lesson_info->get_all_test_order_info_by_time($start_time_ave);
        $num = 0;
        $arr = 0;
        foreach($res as $item){
            if($item["orderid"]>0 && $item["order_time"]>0 && $item["lesson_start"]>0){
                $num++;
                $arr += ($item["order_time"]-$item["lesson_start"]);
            }
        }

        if($num!=0){
            $day_num = round($arr/$num/86400,$decimal_point);
        }else{
            $day_num = 0;
        }
        return $day_num;
    }


    //精品老师页面
    public function get_elite_teacher_list(){
        $this->set_in_value("elite_flag",1);
        $this->set_in_value("is_test_user",0);
        return $this->teacher_info_new();
    }

    public function teacher_info_new(){
        $this->switch_tongji_database();
        list($through_start,$through_end) = $this->get_in_date_range(0,0,0,null,3);
        $teacherid              = $this->get_in_int_val('teacherid',-1);
        $is_freeze              = $this->get_in_int_val('is_freeze',-1);
        $free_time              = $this->get_in_str_val("free_time","");
        $page_num               = $this->get_in_page_num();
        $is_test_user           = $this->get_in_int_val("is_test_user",-1);
        $gender                 = $this->get_in_int_val("gender",-1);
        $grade_part_ex          = $this->get_in_int_val("grade_part_ex",-1);
        $subject                = $this->get_in_int_val("subject",-1);
        $second_subject         = $this->get_in_int_val("second_subject",-1);
        $address                = trim($this->get_in_str_val('address',''));
        $limit_plan_lesson_type = $this->get_in_int_val("limit_plan_lesson_type",-1);
        $lesson_hold_flag       = $this->get_in_int_val("lesson_hold_flag",-1);
        $train_through_new      = $this->get_in_int_val("train_through_new",-1);
        $seller_flag            = $this->get_in_int_val("seller_flag",0);
        $sleep_teacher_flag     = $this->get_in_int_val("sleep_teacher_flag",-1);
        $elite_flag             = $this->get_in_int_val("elite_flag",-1);
        $adminid                = $this->get_account_id();

        $right_list  = $this->get_tea_subject_and_right_by_adminid($adminid);
        $tea_subject = $right_list["tea_subject"];
        $tea_right   = $right_list["tea_right"];
        $qz_flag     = $right_list["qz_flag"];
       
        if($adminid==486 || $adminid==478){
            $tea_subject = "";
        }

        // $jw_permission_list=[
        //     723=>3,
        //     1329=>3,
        //     1324=>2,
        //     1328=>2,
        //     1238=>1,
        //     513=>1,
        //     436=>"-1",
        //     478=>"-1"
        // ];
        // if(isset($jw_permission_list[$adminid])){
        //     $tea_subject="";
        //     $per_subject=$jw_permission_list[$adminid];
        // }else{
        //     $per_subject=-1;
        // }
        $per_subject=-1;
        if(!empty($free_time)){
            $teacherid_arr = $this->get_free_teacherid_arr_new($free_time);
            $arr       = explode(",",$free_time);
            $time = strtotime($arr[0]);
        }else{
            $teacherid_arr=[];
            $time = time();
        }

        $date_week = \App\Helper\Utils::get_week_range($time,1);
        $lstart    = $date_week["sdate"];
        $lend      = $date_week["edate"];

        //晋升老师名单
        // $advance_list = $this->t_teacher_advance_list->get_all_advance_teacher();
        $advance_list=[];
        
        $ret_info  = $this->t_teacher_info->get_teacher_detail_list_new(
            $teacherid,$is_freeze,$page_num,$is_test_user,$gender,
            $grade_part_ex,$subject,$second_subject,$address,$limit_plan_lesson_type,
            $lesson_hold_flag,$train_through_new,$seller_flag,$tea_subject,$lstart,
            $lend,$teacherid_arr,$through_start,$through_end,$sleep_teacher_flag,
            $advance_list, $per_subject,$elite_flag
        );

        foreach($ret_info['list'] as  &$item){
            $birth_year = substr((string)$item['birth'], 0, 4);
            $age        = (int)date('Y', time()) - (int)$birth_year;
            E\Eteacher_type::set_item_value_str($item,"teacher_type");
            E\Egender::set_item_value_str($item,"gender");
            E\Esubject::set_item_value_str($item,"subject");
            E\Esubject::set_item_value_str($item,"second_subject");
            E\Esubject::set_item_value_str($item,"third_subject");
            E\Egrade_part_ex::set_item_value_str($item,"grade_part_ex");
            E\Egrade_range::set_item_value_str($item,"grade_start");
            E\Egrade_range::set_item_value_str($item,"grade_end");
            E\Eidentity::set_item_value_str($item);
            $item['user_agent'] = \App\Helper\Utils::get_user_agent_info($item['user_agent']);
            $item['age']        = $age;
            $item['level_str'] = \App\Helper\Utils::get_teacher_letter_level($item['teacher_money_type'],$item['level']);
            E\Eteacher_money_type::set_item_value_str($item);
            E\Etextbook_type::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,"train_through_new_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time","_str");
            if($item["train_through_new_time"] !=0){
                $item["work_day"] = ceil((time()-$item["train_through_new_time"])/86400)."天";
            }else{
                $item["work_day"] ="";
            }
            E\Elimit_plan_lesson_type::set_item_value_str($item,"limit_plan_lesson_type");

            $item["limit_plan_lesson_time_str"] = date("Y-m-d",$item["limit_plan_lesson_time"]);
            $not_grade_arr=explode(",",$item["not_grade"]);
            $not_grade_str="";
            if(!empty($not_grade_arr)){
                foreach($not_grade_arr as $ss){
                    $not_grade_str  .= E\Egrade::get_desc($ss).",";
                }
            }
            $item["not_grade_str"] = trim($not_grade_str,",");
            if(!empty($item["freeze_adminid"])){
                $item["freeze_adminid_str"] = $this->t_manager_info->get_account($item["freeze_adminid"]);
            }else{
                $item["freeze_adminid_str"]="";
            }

            if(!in_array($item['teacher_type'],["21","22"])){
                $item["phone_ex"] = preg_replace('/(1[358]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$item["phone"]);
            }else{
                $item['phone_ex'] = $item['phone'];
            }
            if(empty($item["address"])){
                $item["address"] = \App\Helper\Common::get_phone_location($item["phone"]);
                $item["address"]   = substr($item["address"], 0, -6);
            }
            if(empty($item["teacher_tags"])){
                $item["teacher_tags"]="";
            }else{
                $tag= json_decode($item["teacher_tags"],true);
                if(is_array($tag)){
                    $str_tag="";
                    foreach($tag as $d=>$t){
                        $str_tag .= $d."  ".$t."<br>";
                    }
                    $item["teacher_tags"] = $str_tag;
                }
            }



        }

        $acc = $this->get_account();

        //检查是否教务组长
        $is_master_flag_jw = $this->t_admin_group_name->check_is_master(3,$this->get_account_id());
        if($is_master_flag_jw==1 || in_array($acc,["jack","jim","CoCo老师","孙瞿"])){
            $is_master_flag_jw=1;
        }

        return $this->pageView(__METHOD__,$ret_info,[
            "acc"          => $acc,
            "account_role" => $this->get_account_role(),
            "is_master_flag_jw" =>$is_master_flag_jw,
            "elite_flag"     =>$elite_flag
        ]);
    }

    public function teacher_info_for_seller(){
        $address                = trim($this->get_in_str_val('address',''));
        $page_num               = $this->get_in_page_num();
        $ret_info = $this->t_teacher_info->get_seller_teacher_detail_list_new($page_num,$address);
        foreach($ret_info["list"] as &$item){
            E\Elimit_plan_lesson_type::set_item_value_str($item,"limit_plan_lesson_type");
            \App\Helper\Utils::unixtime2date_for_item($item,"freeze_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_hold_flag_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"limit_plan_lesson_time","_str");
            $item["is_freeze_str"]             = $item["is_freeze"]==0?"非冻结":"已冻结";
            if(!empty($item["freeze_adminid"])){
                $item["freeze_adminid_str"] = $this->t_manager_info->get_account($item["freeze_adminid"]);
            }else{

                $item["freeze_adminid_str"]="";
            }

        }
        return $this->pageView(__METHOD__,$ret_info);

    }

    public function get_teacher_lecture_fail_score_info_zs(){
       return $this->get_teacher_lecture_fail_score_info();
    }

    public function get_teacher_lecture_fail_score_info(){
        list($start_time,$end_time)=$this->get_in_date_range(-10,0);
        $ret_info = $this->t_teacher_lecture_info->get_fail_ave_score($start_time,$end_time);
        foreach($ret_info as &$item){
            $item = sprintf("%.2f",$item*100);
        }
        return $this->pageView(__METHOD__,null,[
            "list"              =>$ret_info
        ]);
    }

    public function set_teacher_ref_type(){
        $teacherid        = $this->get_in_int_val("teacherid");
        $teacher_ref_type = $this->get_in_int_val("teacher_ref_type");
        $teacher_type     = $this->get_in_int_val("teacher_type");

        $ret_info = $this->t_teacher_info->field_update_list($teacherid,[
            "teacher_ref_type" => $teacher_ref_type,
            "teacher_type"     => $teacher_type,
        ]);

        if(!$ret_info){
            return $this->output_err("系统错误！请重试！");
        }
        return $this->output_succ();
    }

    public function reset_lecture_account(){
        $id = $this->get_in_int_val("id");

        $ret = $this->t_teacher_lecture_info->field_update_list($id,[
            "account" => null
        ]);

        if(!$ret){
            return $this->output_err("更新出错！");
        }
        return $this->output_succ();
    }

    public function regular_course_seller()
    {
        $teacherid = $this->get_in_int_val('teacherid',-1);
        $userid = $this->get_in_int_val('userid',-1);

        $date=\App\Helper\Utils::get_week_range(time(NULL),1);
        $stat_time=$date["sdate"];

        $ret_info=\App\Helper\Utils::list_to_page_info([]);
        return $this->pageView(__METHOD__, $ret_info);
    }


    public function get_common_config_new_seller()
    {
        $userid    = $this->get_in_int_val('userid',-1);
        $type      = $this->get_in_int_val('type');
        $timestamp = $this->get_in_int_val('timestamp');
        $teacherid = $this->get_in_int_val('teacherid',-1);
        $adminid   = $this->get_account_id();

        if ($type == 1) {
            $ret_db = $this->t_lesson_info->get_lesson_info_by_adminid($adminid, $timestamp, $teacherid);

            $ret_db_except = $this->t_test_lesson_subject_require->get_expect_lesson_info_by_adminid($adminid, $timestamp);

            // dd($ret_db);

        } else {
            return '';
        }

        if ($ret_db === false) {
            return outputjson_error();
        }
        if (count($ret_db) == 0) {
            $this->output_succ( array('lesson_list' => $ret_db));
        }

        $ret_info    = array();

        foreach($ret_db_except as $item_except){
            array_push($ret_db,$item_except);
        }

        foreach($ret_db as $key => &$item){
            $item['lesson_type']      = '试听';
            $item['lesson_count']     = $item['lesson_count']/100 ;
            $item['teacher']          = $this->cache_get_teacher_nick($item["teacherid"]);
            $item['nick']             = $this->cache_get_student_nick($item["userid"]);
            $item["lesson_start_str"] = \App\Helper\Utils::unixtime2date( $item["lesson_start"] ) ;
            $item["lesson_end_str"]   = \App\Helper\Utils::unixtime2date( $item["lesson_end"] ) ;

            if ( !$item['lesson_start']) {
                $item["title"] = '-申请时间-未排课';
                $item['lesson_type']      = '未排课 ';
                $item['lesson_start_str'] ='期望上课时间:'.\App\Helper\Utils::unixtime2date($item['stu_request_test_lesson_time']);
                $item['teacher']    = '无';
                $item['lesson_end'] = $item['require_time']+3600;
                $item['lesson_end_str'] = \App\Helper\Utils::unixtime2date($item['stu_request_test_lesson_time']);
                $item['lesson_start'] = $item['require_time'];

            } else {
                $item["title"]            =  $item['teacher'] ."-"  . $item['nick'] ;
            }


        }


        return  outputjson_success( [ "lesson_list" => $ret_db] );

    }

    public function quit_teacher_info(){
        $teacherid = $this->get_in_int_val('teacherid',-1);
        $subject = $this->get_in_int_val('subject',-1);
        list($start_time, $end_time) = $this->get_in_date_range(0,0,0,[],3 );
        $page_num               = $this->get_in_page_num();
        $ret_info = $this->t_teacher_info->get_teacher_quit_info($page_num,$start_time,$end_time,$teacherid,$subject);
        foreach($ret_info["list"] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"quit_time","_str");
            E\Egrade_part_ex::set_item_value_str($item,"grade_part_ex");
            E\Egrade_range::set_item_value_str($item,"grade_start");
            E\Egrade_range::set_item_value_str($item,"grade_end");
            E\Esubject::set_item_value_str($item,"subject");

        }
        return $this->pageView(__METHOD__, $ret_info);
    }

    /**
     * @param phone     待转移的原始phone账号
     * @param new_phone 转移的新的phone账号
     */
    public function change_teacher_to_new(){
        $phone     = $this->get_in_str_val("phone");
        $new_phone = $this->get_in_str_val("new_phone");
        $acc       = $this->get_account();

        if(!in_array($acc,["adrian","jim","zoe","amyshen"])){
            return $this->output_err("权限不足！");
        }
        if($new_phone==""){
            return $this->output_err("请填写手机号！");
        }

        $new_teacherid = $this->t_teacher_info->get_teacherid_by_phone($new_phone);
        if(!$new_teacherid){
            $teacher_info = $this->t_teacher_info->get_teacher_info_by_phone($phone);
            if(empty($teacher_info)){
                return $this->output_err("老师信息为空!请确定老师手机是否正确!".$phone);
            }

            $this->t_teacher_info->field_update_list($teacher_info['teacherid'],[
                "trial_lecture_is_pass" => 0,
                "train_through_new"     => 0,
                "wx_openid"             => null
            ]);

            $add_info = [
                "acc"                   => $acc,
                "wx_use_flag"           => $teacher_info['wx_use_flag'],
                "trial_lecture_is_pass" => $teacher_info['trial_lecture_is_pass'],
                "train_through_new"     => $teacher_info['train_through_new'],
                "level"                 => 1,
                "subject"               => $teacher_info['subject'],
                "tea_nick"              => $teacher_info['nick'],
                "realname"              => $teacher_info['realname'],
                // "phone_spare"           => $phone,
                "phone"                 => $new_phone,
                "identity"              => $teacher_info['identity'],
                "grade_start"           => $teacher_info['grade_start'],
                "grade_end"             => $teacher_info['grade_end'],
                "grade"                 => $teacher_info['grade_part_ex'],
                "email"                 => $teacher_info['email'],
                "school"                => $teacher_info['school'],
                "send_sms_flag"         => 0,
                "transfer_teacherid"    => $teacher_info['teacherid'],
                "transfer_time"         => time(),
                "wx_openid"             => $teacher_info['wx_openid'],
            ];
            $new_teacherid = $this->add_teacher_common($add_info);
        }

        return $this->output_succ(["new_teacherid" => $new_teacherid]);
    }

    public function check_phone_exists($phone){
        if(!$phone){
            $phone = $this->get_in_str_val("new_phone");
        }
        $check_flag = $this->t_teacher_info->check_teacher_phone($phone);
        if($check_flag){
            return $this->output_err("此手机号已存在！");
        }
        return $this->output_succ();
    }

    public function transfer_teacher_info(){
        $old_teacherid = $this->get_in_int_val("old_teacherid");
        $new_teacherid = $this->get_in_int_val("new_teacherid");
        $lesson_date   = $this->get_in_str_val("lesson_start",date("Y-m-d",time()));
        $acc           = $this->get_account();
        $lesson_start  = strtotime($lesson_date);

        \App\Helper\Utils::logger("user:".$acc."transfer_teacher old teacherid:".$old_teacherid."new teacherid:".$new_teacherid);
        if(!in_array($acc,["adrian","jim","alan","zoe","amyshen"])){
            return $this->output_err("权限不足！");
        }

        $teacher_info = $this->t_teacher_info->get_teacher_info($new_teacherid);
        $this->t_course_order->start_transaction();
        $course_count = $this->t_course_order->get_course_count($old_teacherid);
        if($course_count>0){
            $ret = $this->t_course_order->transfer_teacher_course($old_teacherid,$new_teacherid);
            if(!$ret){
                $this->t_course_order->rollback();
                return $this->output_err("更新课程包(course)出错！请重试！");
            }
        }

        $lesson_count = $this->t_lesson_info_b2->get_teacher_lesson_num($old_teacherid,$lesson_start,-1);
        if($lesson_count>0){
            $ret = $this->t_lesson_info_b2->transfer_teacher_lesson(
                $old_teacherid,$new_teacherid,$teacher_info['teacher_money_type'],$teacher_info['level'],$lesson_start
            );
            if(!$ret){
                $this->t_course_order->rollback();
                return $this->output_err("更新课程(lesson)出错！请重试！");
            }
        }

        $regular_count = $this->t_week_regular_course->get_regular_count($old_teacherid);
        if($regular_count>0){
            $ret = $this->t_week_regular_course->transfer_teacher_week_regular($old_teacherid,$new_teacherid);
            if(!$ret){
                $this->t_course_order->rollback();
                return $this->output_err("更新常规课表(regular)出错！请重试！");
            }
        }

        $old_wx_use_flag = $this->t_teacher_info->get_wx_openid($old_teacherid);
        if($old_wx_use_flag!=0){
            $ret = $this->t_teacher_info->field_update_list($old_teacherid,[
                "wx_use_flag"  => 0,
            ]);
            if(!$ret){
                return $this->output_err("更新老师信息失败！");
            }
        }
        $this->t_course_order->commit();

        $from_user  = "转移老师信息";
        $header_msg = "$acc 将 $old_teacherid 的信息转移至 $new_teacherid 上";
        $this->t_manager_info->send_wx_todo_msg("adrian",$from_user,$header_msg);

        return $this->output_succ();
    }

    public function origin_list(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,3);

        $appoint_list = $this->t_teacher_lecture_appointment_info->get_teacher_lecture_appointment_origin_list(
            $start_time,$end_time
        );
        $lecture_list = $this->t_teacher_lecture_info->get_lecture_origin_list($start_time,$end_time);
        $teacher_list = $this->t_lesson_info_b2->get_teacher_origin_list($start_time,$end_time);

        foreach($appoint_list as $a_key => $a_val){
            \App\Helper\Utils::check_isset_data($ret_list["all"]["num"],(int)$a_val["num"]);
            \App\Helper\Utils::check_isset_data($ret_list[$a_key]["num"],$a_val["num"]);
        }
        foreach($lecture_list as $l_key => $l_val){
            \App\Helper\Utils::check_isset_data($ret_list["all"]["lecture_total"],(int)$l_val["lecture_total"]);
            \App\Helper\Utils::check_isset_data($ret_list[$l_key]["lecture_total"],$l_val["lecture_total"]);
            \App\Helper\Utils::check_isset_data($ret_list["all"]["pass_total"],(int)$l_val["pass_total"]);
            \App\Helper\Utils::check_isset_data($ret_list[$l_key]["pass_total"],$l_val["pass_total"]);
        }
        foreach($teacher_list as $t_key => $t_val){
            \App\Helper\Utils::check_isset_data($ret_list["all"]["through_num"],(int)$t_val["through_num"]);
            \App\Helper\Utils::check_isset_data($ret_list[$t_key]["through_num"],$t_val["through_num"]);
            \App\Helper\Utils::check_isset_data($ret_list["all"]["train_num"],(int)$t_val["train_num"]);
            \App\Helper\Utils::check_isset_data($ret_list[$t_key]["train_num"],$t_val["train_num"]);
        }

        foreach($ret_list as $r_key => &$r_val){
            if($r_key===""){
                $r_val['teacher_ref_type'] = "自由渠道";
            }elseif($r_key!=="all"){
                $r_val['teacher_ref_type'] = E\Eteacher_ref_type::get_desc($r_key);
            }else{
                $r_val['teacher_ref_type'] = $r_key;
            }

            \App\Helper\Utils::check_isset_data($r_val["num"],0);
            \App\Helper\Utils::check_isset_data($r_val["lecture_total"],0);
            \App\Helper\Utils::check_isset_data($r_val["pass_total"],0);
            \App\Helper\Utils::check_isset_data($r_val["through_num"],0);
            \App\Helper\Utils::check_isset_data($r_val["train_num"],0);

            $r_val['lecture_rate'] = \App\Helper\Utils::get_rate($r_val["lecture_total"],$r_val['num']);
            $r_val['pass_rate']    = \App\Helper\Utils::get_rate($r_val["pass_total"],$r_val['lecture_total']);
            $r_val['through_rate'] = \App\Helper\Utils::get_rate($r_val["through_num"],$r_val['train_num']);
        }

        $ret_list = \App\Helper\Utils::list_to_page_info($ret_list);
        return $this->pageView(__METHOD__,$ret_list);
    }

    public function reaearch_teacher_lesson_list_fulltime(){
        return $this->reaearch_teacher_lesson_list();
    }

    public function reaearch_teacher_lesson_list_research(){
        $this->set_in_value("research_flag",1);
        return $this->reaearch_teacher_lesson_list();
    }

    public function reaearch_teacher_lesson_list(){
        $teacherid = $this->get_in_int_val("teacherid",-1);
        $page_info = $this->get_in_page_info();
        $research_flag = $this->get_in_int_val("research_flag",-1);
        $ret_info = $this->t_teacher_info->get_research_teacher_list_lesson($page_info,$teacherid,$research_flag);

        foreach($ret_info["list"] as &$item){
            E\Esubject::set_item_value_str($item,"subject");
            //E\Egrade_part_ex::set_item_value_str($item,"grade_part_ex");
            E\Egrade_range::set_item_value_str($item,"grade_start");
            E\Egrade_range::set_item_value_str($item,"grade_end");
            E\Esubject::set_item_value_str($item,"second_subject");
            //E\Egrade_part_ex::set_item_value_str($item,"grade_part_ex");
            E\Egrade_range::set_item_value_str($item,"second_grade_start");
            E\Egrade_range::set_item_value_str($item,"second_grade_end");
            E\Eboolean::set_item_value_str($item,"limit_seller_require_flag");
            $time_info = json_decode($item["week_limit_time_info"],true);
            $str="";
            if($time_info){
                foreach($time_info as $val){
                    $str .=$val["week_name"]." ".$val["start"]."~".$val["end"]."<br>";
                }
            }
            $item["week_limit_time_info_str"] =  $str;

        }
        // dd($ret_info);
        return $this->pageView(__METHOD__,$ret_info,[
            '_publish_version' =>'201712161131',
            'research_flag'=>$research_flag
        ]);
    }

    public function add_research_teacher(){ 
        $teacher_info = array();
        $teacher_info['phone'] = trim($this->get_in_str_val('phone',''));
        $teacher_info['realname'] = trim($this->get_in_str_val('realname',''));
        $teacher_info['subject'] = $this->get_in_int_val("subject",-1);;
        $teacher_info['grade_start'] = $this->get_in_int_val("grade_start",-1);
        $teacher_info['grade_end'] = $this->get_in_int_val("grade_end",-1);;
        $teacher_info['teacher_money_type'] = trim($this->get_in_int_val('teacher_money_type',''));
        $teacher_info['teacher_type'] = trim($this->get_in_int_val('teacher_type',''));
        // \App\Helper\Utils::logger("add resarach teacher: ".json_encode($teacher_info));
        $teacherid = $this->add_teacher_common($teacher_info);
        if(!$teacherid){
            return $this->output_err("生成老师失败！");
        }else{
            return $this->output_succ(["teacherid" => $teacherid]);
        } 
    }

    public function zs_origin_list(){
        $this->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,3);
        $ret_info = $this->t_teacher_lecture_appointment_info->get_app_lecture_sum_by_reference($start_time,$end_time);
        $lecture_info = $this->t_teacher_lecture_appointment_info->get_lecture_sum_by_reference($start_time,$end_time);
        $trial_info = $this->t_teacher_lecture_appointment_info->get_trial_sum_by_reference($start_time,$end_time);
        $through_info = $this->t_teacher_lecture_appointment_info->get_trial_sum_by_reference($start_time,$end_time,1);
        $train_info = $this->t_teacher_lecture_appointment_info->get_train_sum_by_reference($start_time,$end_time);
        // $train_trial_info = $this->t_teacher_lecture_appointment_info->get_train_trial_sum_by_reference($start_time,$end_time);
        // $train_through_info = $this->t_teacher_lecture_appointment_info->get_train_trial_sum_by_reference($start_time,$end_time,1);
        $all=[];$list=[];
        foreach($ret_info as $k=>&$item){
            $item["lecture_num"]              = @$lecture_info[$k]["lecture_num"];
            $item["lecture_pass_num"]         = @$lecture_info[$k]["lecture_pass_num"];
            $item["train_num"]                = @$train_info[$k]["train_num"];
            $item["train_pass_num"]           = @$train_info[$k]["train_pass_num"];
            $item["interview_num"]            = $item["lecture_num"]+$item["train_num"] ;
            $item["interview_pass_num"]       = $item["lecture_pass_num"]+ $item["train_pass_num"];
            $item["interview_trial_num"]      = @$trial_info[$k]["trial_num"];
            $item["interview_trial_pass_num"] = @$through_info[$k]["trial_num"];
            if($item["teacher_ref_type"]==""){
                $item["teacher_ref_type"]=-1;
            }
            @$all["app_num"]+=$item["app_num"];
            @$list[$item["teacher_ref_type"]]["app_num"] +=$item["app_num"];
            @$all["interview_num"] +=$item["interview_num"];
            @$list[$item["teacher_ref_type"]]["interview_num"] +=$item["interview_num"];
            @$all["interview_pass_num"] +=$item["interview_pass_num"];
            @$list[$item["teacher_ref_type"]]["interview_pass_num"] +=$item["interview_pass_num"];
            @$all["interview_trial_num"] +=$item["interview_trial_num"];
            @$list[$item["teacher_ref_type"]]["interview_trial_num"] +=$item["interview_trial_num"];
            @$all["interview_trial_pass_num"] +=$item["interview_trial_pass_num"];
            @$list[$item["teacher_ref_type"]]["interview_trial_pass_num"] +=$item["interview_trial_pass_num"];
        }

        $index=0;
        $all["teacher_ref_type"]="全部";
        $all["reference"] ="";
        $all["level"]="l-0";
        $all["teacher_ref_type_class"]="";
        $all["realname"]="";
        $data=[];
        $data[]=$all;
        $index++;
        foreach($list as $key=>&$val){
            $val["teacher_ref_type"]=$key;
            $val["reference"] ="";
            $val["level"]="l-1";
            $val["teacher_ref_type_class"]="teacher_ref_type".$index;
            $val["realname"]="";
            $data[]=$val;
            foreach($ret_info as &$v){
                if($v["teacher_ref_type"]==$key){
                    if($v["teacher_ref_type"]==-1){
                        $v["realname"]="无推荐人";
                    }
                    $v["level"]="l-2";
                    $v["teacher_ref_type_class"]="teacher_ref_type".$index;
                    $data[]=$v;
                }
            }
            $index++;
        }

        foreach($data as &$i){
            if($i["teacher_ref_type"]==-1){
                $i['teacher_ref_type_str'] = "无推荐人";
            }elseif($i["teacher_ref_type"]!=="全部"){
                $i['teacher_ref_type_str'] = E\Eteacher_ref_type::get_desc($i["teacher_ref_type"]);
            }else{
                $i['teacher_ref_type_str'] = $i["teacher_ref_type"];
            }
            $i["interview_per"] = !empty($i["app_num"])?round($i["interview_num"]/$i["app_num"]*100,2):0;
            $i["interview_pass_per"] = !empty($i["interview_num"])?round($i["interview_pass_num"]/$i["interview_num"]*100,2):0;
            $i["interview_trial_pass_per"] = !empty($i["interview_trial_num"])?round($i["interview_trial_pass_num"]/$i["interview_trial_num"]*100,2):0;
        }

        $ret_list = \App\Helper\Utils::list_to_page_info($data);
        return $this->pageView(__METHOD__,$ret_list);
    }

    public function teacher_total_list(){
        $this->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,3);
        $teacherid          = $this->get_in_int_val("teacherid",-1);
        $teacher_money_type = $this->get_in_int_val("teacher_money_type",0);
        $level              = $this->get_in_int_val("level",-1);
        $is_test_user       = $this->get_in_int_val("is_test_user",0);
        $page_num           = $this->get_in_page_num();

        $tea_list = $this->t_teacher_info->get_teacher_total_list(
            $page_num,$start_time,$end_time,$teacherid,$teacher_money_type,$level,$is_test_user
        );

        foreach($tea_list['list'] as &$val){
            E\Eteacher_money_type::set_item_value_str($val);
            E\Elevel::set_item_value_str($val);
            $val['create_time_str']=\App\Helper\Utils::unixtime2date($val['create_time']);
            $val['trial_lesson_count'] /= 100;
            $val['normal_lesson_count'] /= 100;
            $val['all_lesson_count']=$val['trial_lesson_count']+$val['normal_lesson_count'];
            $grade_str="";
            if($val['all_grade']!=""){
                $grade_arr = explode(",",$val['all_grade']);
                if(!empty($grade_arr)){
                    foreach($grade_arr as $grade_val){
                        $grade_str .= E\Egrade::get_desc($grade_val).",";
                    }
                }
            }
            $subject_str="";
            if($val['all_subject']!=""){
                $subject_arr = explode(",",$val['all_subject']);
                if(!empty($subject_arr)){
                    foreach($subject_arr as $subject_val){
                        $subject_str .= E\Esubject::get_desc($subject_val).",";
                    }
                }
            }
            $val['grade_str']   = $grade_str;
            $val['subject_str'] = $subject_str;
        }

        return $this->pageView(__METHOD__,$tea_list);
    }

    public function new_teacher_money_list(){
        $this->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,3);
        $teacherid          = $this->get_in_int_val("teacherid",-1);
        $teacher_money_type = $this->get_in_int_val("teacher_money_type",0);
        $level              = $this->get_in_int_val("level",-1);
        $is_test_user       = $this->get_in_int_val("is_test_user",0);
        $page_num           = $this->get_in_page_num();

        $tea_list = $this->t_teacher_info->get_teacher_total_list_new(
            $page_num,$start_time,$end_time,$teacherid,$teacher_money_type,$level,$is_test_user
        );

        foreach($tea_list['list'] as &$val){
            E\Eteacher_money_type::set_item_value_str($val);
            E\Elevel::set_item_value_str($val);
            E\Enew_level::set_item_value_str($val,"level_simulate");
            $val['create_time_str']=\App\Helper\Utils::unixtime2date($val['create_time']);
            $val['trial_lesson_count'] /= 100;
            $val['normal_lesson_count'] /= 100;
            $val['all_lesson_count']=$val['trial_lesson_count']+$val['normal_lesson_count'];
            $grade_str="";
            if($val['all_grade']!=""){
                $grade_arr = explode(",",$val['all_grade']);
                if(!empty($grade_arr)){
                    foreach($grade_arr as $grade_val){
                        $grade_str .= E\Egrade::get_desc($grade_val).",";
                    }
                }
            }
            $subject_str="";
            if($val['all_subject']!=""){
                $subject_arr = explode(",",$val['all_subject']);
                if(!empty($subject_arr)){
                    foreach($subject_arr as $subject_val){
                        $subject_str .= E\Esubject::get_desc($subject_val).",";
                    }
                }
            }
            $val['grade_str']   = $grade_str;
            $val['subject_str'] = $subject_str;
        }
        return $this->pageView(__METHOD__,$tea_list);
    }



    public function interview_remind(){ // 面试提醒

        list($start_time,$end_time,$opt_date_str) = $this->get_in_date_range(0,0,1,[
            1 => array("interview_time","面试时间"),
        ],1);

        $user_name = $this->get_in_str_val('user_name');
        $page_num  = $this->get_in_int_val('page_num');


        $ret_info = $this->t_interview_remind->get_interview_remind_list($page_num,$start_time, $end_time,$user_name);

        foreach($ret_info['list'] as &$v){
            $v['interviewer_name'] = $this->t_manager_info->get_account($v['interviewer_id']);
            $v['interview_time'] = date('Y-m-d H:i:s',$v['interview_time']);
            if($v['send_msg_time']>0){
                $v['send_msg_time'] = date('Y-m-d H:i:s',$v['send_msg_time']);
            }else{
                $v['send_msg_time'] = '无';
            }
            if($v['is_send_flag'] == 1){
                $v['is_send_flag_str'] = "<font color=\"green\">已发送</font>";
            }else{
                $v['is_send_flag_str'] = "<font color=\"blue\">未发送</font>";
            }
        }
        return $this->pageView(__METHOD__,$ret_info);
    }


    public function get_input_score_list(){
        list($start_time, $end_time) = $this->get_in_date_range(0,0,0,[],3,0,true);
        $admin_type = $this->get_in_int_val('admin_type',1);
        $page_num   = $this->get_in_page_num();

        $ret_info = $this->t_student_score_info->get_input_score_list($start_time, $end_time, $admin_type, $page_num);


        foreach( $ret_info['list'] as &$item){
            if($item['admin_type'] == 1){ // 家长
                $item['create_nick'] = $this->t_parent_info->get_nick($item['create_adminid']);
                $item['account_type'] = '家长';
                $item['admin_type_str'] = '微信端';
            }else{ // 助教
                $item['create_nick'] = $this->t_manager_info->get_account($item['create_adminid']);
                $item['account_type'] = '助教';
                $item['admin_type_str'] = '后台';
            }
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time","","Y-m-d H:i");
        }

        return $this->pageView(__METHOD__, $ret_info);

    }


    public function get_lesson_modify_list(){
        list($start_time, $end_time) = $this->get_in_date_range(0,0,0,[],3,0,true);
        $page_num = $this->get_in_page_num();
        $is_done  = $this->get_in_int_val('is_modify_time_flag',-1);
        $ret_info = $this->t_lesson_time_modify->get_modify_list($start_time, $end_time, $page_num, $is_done);

        foreach($ret_info['list'] as &$item){
            $item[''] = '';

            if($item['is_modify_time_flag'] == 2){
                $item['is_modify_time_flag_str'] = "<font color=\"red\">已拒绝</font>";
            }elseif($item['is_modify_time_flag'] == 1){
                $item['is_modify_time_flag_str'] = "<font color=\"green\">已完成</font>";
            }elseif($item['is_modify_time_flag'] == 0){
                $item['is_modify_time_flag_str'] = "<font color=\blue\">老师未回应</font>";
            }

            \App\Helper\Utils::unixtime2date_for_item($item,"parent_deal_time","","Y-m-d H:i");

        }

        return $this->pageView(__METHOD__, $ret_info);
    }

    public function update_tea_realname(){
        $teacherid = $this->get_in_int_val("teacherid");
        $nick      = $this->get_in_str_val("nick");
        $realname  = $this->get_in_str_val("realname");

        if($nick=="" || $realname==""){
            return $this->output_err("姓名不能为空!");
        }

        $ret = $this->t_teacher_info->field_update_list($teacherid, [
            "nick"     => $nick,
            "realname" => $realname,
        ]);
        return $this->output_ret($ret);
    }

    /**
     * 重置老师试讲预约的扩课信息
     */
    public function reset_teacher_trans_info(){
        $id = $this->get_in_int_val("id");

        $trans_info = $this->t_teacher_lecture_appointment_info->get_teacher_trans_info($id);
        if(empty($trans_info)){
            return $this->output_err("老师试讲信息不存在，请刷新重试！");
        }

        $grade         = $trans_info["grade_ex"];
        $subject       = $trans_info["subject_ex"];
        $trans_grade   = $trans_info["trans_grade_ex"];
        $trans_subject = $trans_info["trans_subject_ex"];
        if($grade=="" && $subject=="" && $trans_grade!="" && $trans_subject!=0){
            $update_arr = [
                "grade_ex"         => $trans_grade,
                "subject_ex"       => $trans_subject,
                "trans_grade_ex"   => "",
                "trans_subject_ex" => 0,
            ];
            $ret = $this->t_teacher_lecture_appointment_info->field_update_list($id, $update_arr);
            return $this->output_ret($ret);
        }else{
            return $this->output_err("老师扩课的试讲信息无法更换！");
        }
    }

}
