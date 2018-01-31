<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;

class teacher_level extends Controller
{
    use CacheNick;
    use TeaPower;

    public function get_teacher_level_quarter_info(){
        $this->switch_tongji_database();
        $sum_field_list = [
            "total_score"
        ];
        $order_field_arr = array_merge(["realname"],$sum_field_list);
        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str($order_field_arr,"realname desc ");

        $season     = ceil((date('n'))/3)-1;//上季度是第几季度
        $start_time = strtotime(date('Y-m-d H:i:s',mktime(0, 0, 0,$season*3-3+1,1,date('Y'))));
        $end_time   = strtotime(date('Y-m-d H:i:s',mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'))));
        $this->set_in_value("quarter_start",$start_time);
        $quarter_start = $this->get_in_int_val("quarter_start");
        $teacher_money_type = $this->get_in_int_val("teacher_money_type",6);
        $teacherid = $this->get_in_int_val("teacherid",-1);
        $page_info = $this->get_in_page_info();

        $start_time = strtotime("2017-10-01");
        $ret_info = $this->t_teacher_advance_list->get_info_by_time($page_info,$start_time,$teacher_money_type,$teacherid,-1,-1,-1,0);
        foreach($ret_info["list"] as &$item){
            //$item["level"]=$item["level_before"];
            $item["level"]=$item["real_level"];
            if($teacher_money_type==6){
                //  E\Enew_level::set_item_value_str($item,"level_before");
                // E\Enew_level::set_item_value_str($item,"level_after");
                $item["level_str"] = E\Enew_level::get_simple_desc($item["level"]);
                $item["level_after_str"] = E\Enew_level::get_simple_desc($item["level_after"]);
            }else{
                //  E\Elevel::set_item_value_str($item,"level_before");
                // E\Elevel::set_item_value_str($item,"level_after");
                $item["level_str"] = E\Elevel::get_simple_desc($item["level"]);
                $item["level_after_str"] = E\Elevel::get_simple_desc($item["level_after"]);
            }
            \App\Helper\Utils::unixtime2date_for_item($item,"accept_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"require_time","_str");

            E\Eaccept_flag::set_item_value_str($item);
            $item["lesson_count"] = $item["lesson_count"]/100;
            $item["is_refund_str"] = $item["is_refund"]==1?"<font color='red'>有</font>":"无";
            $record_final_score = !empty($item["record_num"])?round($item["record_score_avg"]*0.3,1):18;
            $item["total_score"] =$item["total_score"]-$item["record_final_score"]+$record_final_score;
            $item["record_final_score"]=$record_final_score;
        }


        /*$list     = $this->t_teacher_info->get_teacher_info_by_money_type($teacher_money_type,$start_time,$end_time);
        $tea_list = [];
        foreach($list as $val){
            $tea_list[] = $val["teacherid"];
        }

        $ret_info = $this->t_teacher_info->get_teacher_level_info($page_info,$tea_list,$start_time);
        $tea_arr=[];
        foreach($ret_info["list"] as $val){
            $tea_arr[]=$val["teacherid"];
        }

        $test_person_num        = $this->t_lesson_info->get_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);
        $kk_test_person_num     = $this->t_lesson_info->get_kk_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);
        $change_test_person_num = $this->t_lesson_info->get_change_teacher_test_person_num_list(
            $start_time,$end_time,-1,-1,$tea_arr);
        $teacher_record_score = $this->t_teacher_record_list->get_test_lesson_record_score($start_time,$end_time,$tea_arr,1);
        $tea_refund_info      = $this->get_tea_refund_info($start_time,$end_time,$tea_arr);
        foreach($ret_info["list"] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"accept_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"require_time","_str");
            E\Eaccept_flag::set_item_value_str($item);

            $teacherid = $item["teacherid"];
            $item["lesson_count"] = round($list[$teacherid]["lesson_count"]/300,1);
            $item["lesson_count_score"] = $this->get_score_by_lesson_count($item["lesson_count"]);
            $item["stu_num"] = $list[$teacherid]["stu_num"];
            $item["stu_num_score"] = $this->get_stu_num_score($item["stu_num"]);

            $item["cc_test_num"]    = isset($test_person_num[$teacherid])?$test_person_num[$teacherid]["person_num"]:0;
            $item["cc_order_num"]   = isset($test_person_num[$teacherid])?$test_person_num[$teacherid]["have_order"]:0;
            $item["cc_order_per"]   = !empty($item["cc_test_num"])?round($item["cc_order_num"]/$item["cc_test_num"]*100,2):0;
            $item["cc_order_score"] = $this->get_cc_order_score($item["cc_order_num"],$item["cc_order_per"]);
            $item["other_test_num"] = (isset($kk_test_person_num[$teacherid])?$kk_test_person_num[$teacherid]["kk_num"]:0)+(isset($change_test_person_num[$teacherid])?$change_test_person_num[$teacherid]["change_num"]:0);
            $item["other_order_num"] = (isset($kk_test_person_num[$teacherid])?$kk_test_person_num[$teacherid]["kk_order"]:0)+(isset($change_test_person_num[$teacherid])?$change_test_person_num[$teacherid]["change_order"]:0);
            $item["other_order_per"] = !empty($item["other_test_num"])?round($item["other_order_num"]/$item["other_test_num"]*100,2):0;
            $item["other_order_score"] = $this->get_other_order_score($item["other_order_num"],$item["other_order_per"]);
            $item["record_num"] = isset($teacher_record_score[$teacherid])?$teacher_record_score[$teacherid]["num"]:0;
            $item["record_score"] = isset($teacher_record_score[$teacherid])?$teacher_record_score[$teacherid]["score"]:0;
            $item["record_score_avg"] = !empty($item["record_num"])?round($item["record_score"]/$item["record_num"],1):0;
            $item["record_final_score"] = !empty($item["record_num"])?ceil($item["record_score_avg"]*0.3):18;
            $item["is_refund"] = (isset($tea_refund_info[$teacherid]) && $tea_refund_info[$teacherid]>0)?1:0;
            $item["is_refund_str"] = $item["is_refund"]==1?"<font color='red'>有</font>":"无";
            $item["total_score"] = $item["lesson_count_score"]+$item["cc_order_score"]+ $item["other_order_score"]+$item["record_final_score"]+$item["stu_num_score"];
            $item["hand_flag"]=0;
            if($item["teacher_money_type"]==6){
                E\Enew_level::set_item_value_str($item,"level");
                E\Enew_level::set_item_value_str($item,"level_after");
            }else{
                E\Elevel::set_item_value_str($item,"level");
                E\Elevel::set_item_value_str($item,"level_after");
            }
        }
        $hand_info = $this->t_teacher_advance_list->get_hand_add_list($start_time,1,0);
        foreach($hand_info as &$h){
            $h["realname"] = $this->t_teacher_info->get_realname($h["teacherid"]);
            $h["level"]  = $h["level_before"];
            $h["lesson_count"] =  $h["lesson_count"]/100;
            $h["is_refund_str"] = $h["is_refund"]==1?"<font color='red'>有</font>":"无";
            \App\Helper\Utils::unixtime2date_for_item($h,"accept_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($h,"require_time","_str");
            E\Eaccept_flag::set_item_value_str($h);
            if($h["teacher_money_type"]==6){
                $h["level_str"] =E\Enew_level::get_desc($h["level"]);
                $h["level_after_str"] =E\Enew_level::get_desc($h["level_after"]);
            }else{
                $h["level_str"] =E\Elevel::get_desc($h["level"]);
                $h["level_after_str"] =E\Elevel::get_desc($h["level_after"]);

            }
            array_unshift($ret_info["list"],$h);

        }
        if (!$order_in_db_flag) {
            \App\Helper\Utils::order_list( $ret_info["list"], $order_field_name, $order_type );
        }

        /* $erick =[];
        $erick["teacherid"]=50158;
        $erick["realname"]="刘辉";
        $erick["level"]  = $this->t_teacher_info->get_level($erick["teacherid"]);
        $erick["level_after"] = $this->t_teacher_advance_list->get_level_after($start_time,$erick["teacherid"]);
        $erick["level_str"] =E\Elevel::get_desc($erick["level"]);
        $erick["level_after_str"] =E\Elevel::get_desc($erick["level_after"]);
        $erick["lesson_count"] = $erick["lesson_count_score"]=$erick["cc_test_num"]=$erick["cc_order_num"]= $erick["cc_order_per"]= $erick["cc_order_score"]=$erick["other_test_num"] =$erick["other_order_num"]= $erick["other_order_per"]=$erick["other_order_score"]= $erick["record_num"]= $erick["record_score"]= $erick["record_score_avg"]= $erick["record_final_score"]= $erick["total_score"]=0;
        $erick["is_refund_str"]="无";
        $erick["is_refund"]=0;
        $erick["hand_flag"]=0;
        $erick["teacher_money_type"]=5;

        array_unshift($ret_info["list"],$erick);*/
        $acc= $this->get_account();
        return $this->pageView(__METHOD__,$ret_info,[
            "acc"             =>$acc
        ]);
    }
    public function update_teacher_advance_info_new(){
        $season     = ceil((date('n'))/3)-1;//上季度是第几季度
        $start_time = strtotime(date('Y-m-d H:i:s',mktime(0, 0, 0,$season*3-3+1,1,date('Y'))));
        $end_time   = strtotime(date('Y-m-d H:i:s',mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'))));
        $this->set_in_value("quarter_start",$start_time);
        $quarter_start = $this->get_in_int_val("quarter_start");
        $teacher_money_type = $this->get_in_int_val("teacher_money_type",6);
        $page_info = $this->get_in_page_info();

        $start_time = strtotime("2017-10-01");
        $end_time = strtotime("2018-01-01");
        $teacher_money_type=6;
        $list     = $this->t_teacher_info->get_teacher_info_by_money_type($teacher_money_type,$start_time,$end_time);
        $tea_list = [];
        foreach($list as $val){
            $tea_list[] = $val["teacherid"];
        }

        $ret_info = $this->t_teacher_info->get_teacher_level_info($page_info,$tea_list,$start_time);
        $tea_arr=[];
        foreach($ret_info["list"] as $val){
            $tea_arr[]=$val["teacherid"];
        }

        $test_person_num        = $this->t_lesson_info->get_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);
        $kk_test_person_num     = $this->t_lesson_info->get_kk_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);
        $change_test_person_num = $this->t_lesson_info->get_change_teacher_test_person_num_list(
            $start_time,$end_time,-1,-1,$tea_arr);
        $teacher_record_score = $this->t_teacher_record_list->get_test_lesson_record_score($start_time,$end_time,$tea_arr);
        $tea_refund_info      = $this->get_tea_refund_info($start_time,$end_time,$tea_arr);
        foreach($ret_info["list"] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"accept_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"require_time","_str");
            E\Eaccept_flag::set_item_value_str($item);

            $teacherid = $item["teacherid"];
            $item["lesson_count"] = round($list[$teacherid]["lesson_count"]/300,1);
            $item["lesson_count_score"] = $this->get_score_by_lesson_count($item["lesson_count"]);
            $item["stu_num"] = $list[$teacherid]["stu_num"];
            $item["stu_num_score"] = $this->get_stu_num_score($item["stu_num"]);

            $item["cc_test_num"]    = isset($test_person_num[$teacherid])?$test_person_num[$teacherid]["person_num"]:0;
            $item["cc_order_num"]   = isset($test_person_num[$teacherid])?$test_person_num[$teacherid]["have_order"]:0;
            $item["cc_order_per"]   = !empty($item["cc_test_num"])?round($item["cc_order_num"]/$item["cc_test_num"]*100,2):0;
            $item["cc_order_score"] = $this->get_cc_order_score($item["cc_order_num"],$item["cc_order_per"]);
            $item["other_test_num"] = (isset($kk_test_person_num[$teacherid])?$kk_test_person_num[$teacherid]["kk_num"]:0)+(isset($change_test_person_num[$teacherid])?$change_test_person_num[$teacherid]["change_num"]:0);
            $item["other_order_num"] = (isset($kk_test_person_num[$teacherid])?$kk_test_person_num[$teacherid]["kk_order"]:0)+(isset($change_test_person_num[$teacherid])?$change_test_person_num[$teacherid]["change_order"]:0);
            $item["other_order_per"] = !empty($item["other_test_num"])?round($item["other_order_num"]/$item["other_test_num"]*100,2):0;
            $item["other_order_score"] = $this->get_other_order_score($item["other_order_num"],$item["other_order_per"]);
            $item["record_num"] = isset($teacher_record_score[$teacherid])?$teacher_record_score[$teacherid]["num"]:0;
            $item["record_score"] = isset($teacher_record_score[$teacherid])?$teacher_record_score[$teacherid]["score"]:0;
            $item["record_score_avg"] = !empty($item["record_num"])?round($item["record_score"]/$item["record_num"],1):0;
            $item["record_final_score"] = !empty($item["record_num"])?ceil($item["record_score_avg"]*0.3):18;
            $item["is_refund"] = (isset($tea_refund_info[$teacherid]) && $tea_refund_info[$teacherid]>0)?1:0;
            $item["is_refund_str"] = $item["is_refund"]==1?"<font color='red'>有</font>":"无";
            $item["total_score"] = $item["lesson_count_score"]+$item["cc_order_score"]+ $item["other_order_score"]+$item["record_final_score"]+$item["stu_num_score"];
            $item["hand_flag"]=0;
            if($item["teacher_money_type"]==6){
                E\Enew_level::set_item_value_str($item,"level");
                E\Enew_level::set_item_value_str($item,"level_after");
            }else{
                E\Elevel::set_item_value_str($item,"level");
                E\Elevel::set_item_value_str($item,"level_after");
            }
            $exists = $this->t_teacher_advance_list->field_get_list_2($start_time,$teacherid,"teacherid");
            if(!$exists){
                $this->t_teacher_advance_list->row_insert([
                    "start_time" =>$start_time,
                    "teacherid"  =>$teacherid,
                    "level_before"=>$item["level"],
                    "lesson_count"=>$item["lesson_count"]*100,
                    "lesson_count_score"=>$item["lesson_count_score"],
                    "cc_test_num"=>$item["cc_test_num"],
                    "cc_order_num" =>$item["cc_order_num"],
                    "cc_order_per" =>$item["cc_order_per"],
                    "cc_order_score" =>$item["cc_order_score"],
                    "other_test_num"=>$item["other_test_num"],
                    "other_order_num" =>$item["other_order_num"],
                    "other_order_per" =>$item["other_order_per"],
                    "other_order_score" =>$item["other_order_score"],
                    "record_final_score"=>$item["record_final_score"],
                    "record_score_avg" =>$item["record_score_avg"],
                    "record_num"     =>$item["record_num"],
                    "is_refund"      =>$item["is_refund"],
                    "total_score"    =>$item["total_score"],
                    "teacher_money_type"=>$item["teacher_money_type"],
                    "stu_num"        =>$item["stu_num"],
                    "stu_num_score"  =>$item["stu_num_score"]
                ]);

            }else{
                $this->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[
                    "level_before"=>$item["level"],
                    "lesson_count"=>$item["lesson_count"]*100,
                    "lesson_count_score"=>$item["lesson_count_score"],
                    "cc_test_num"=>$item["cc_test_num"],
                    "cc_order_num" =>$item["cc_order_num"],
                    "cc_order_per" =>$item["cc_order_per"],
                    "cc_order_score" =>$item["cc_order_score"],
                    "other_test_num"=>$item["other_test_num"],
                    "other_order_num" =>$item["other_order_num"],
                    "other_order_per" =>$item["other_order_per"],
                    "other_order_score" =>$item["other_order_score"],
                    "record_final_score"=>$item["record_final_score"],
                    "record_score_avg" =>$item["record_score_avg"],
                    "record_num"     =>$item["record_num"],
                    "is_refund"      =>$item["is_refund"],
                    "total_score"    =>$item["total_score"],
                    "teacher_money_type"=>$item["teacher_money_type"],
                    "stu_num"        =>$item["stu_num"],
                    "stu_num_score"  =>$item["stu_num_score"]
                ]);

            }

        }
        return $this->output_succ();

    }

    public function del_advance_info(){
        $teacherid = $this->get_in_int_val("teacherid");
        $start_time = $this->get_in_int_val("start_time");
        $this->t_teacher_advance_list->row_delete_2($start_time,$teacherid);

        return $this->output_succ();
    }

    public function update_level_record_info(){
        $teacherid = $this->get_in_int_val("teacherid");
        $start_time = $this->get_in_int_val("start_time");
        $record_num = $this->get_in_int_val("record_num");
        $record_score_avg  = $this->get_in_str_val("record_score_avg");
        $record_final_score = !empty($record_num)?ceil($record_score_avg*0.3):18;
        $total_score = $this->t_teacher_advance_list->get_total_score($start_time,$teacherid);
        $record_final_score_old = $this->t_teacher_advance_list->get_record_final_score($start_time,$teacherid);
        $total_score =$total_score-$record_final_score_old+$record_final_score;
        $this->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[
            "record_final_score" =>$record_final_score,
            "record_num"         =>$record_num,
            "record_score_avg"   =>$record_score_avg,
            "total_score"        =>$total_score
        ]);
        return $this->output_succ();

    }

    public function get_teacher_level_quarter_info_new(){
        // $season     = ceil((date('n'))/3)-1;//上季度是第几季度
        // $start_time = strtotime(date('Y-m-d H:i:s',mktime(0, 0, 0,$season*3-3+1,1,date('Y'))));
        $start_time = strtotime("2017-07-01");
        $page_info = $this->get_in_page_info();
        $ret_info = $this->t_teacher_info->get_teacher_level_info_new($page_info,$start_time);
        foreach($ret_info["list"] as &$item){
            E\Elevel::set_item_value_str($item,"level");
            $item["level_after"] = $item["level"]+1;
            if($item["level"]==4){
                $item["level_after"]=4;
            }
            E\Elevel::set_item_value_str($item,"level_after");
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"accept_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"require_time","_str");
            E\Eaccept_flag::set_item_value_str($item);
            $item["hand_flag"]=0;
        }

        $acc= $this->get_account();
        return $this->pageView(__METHOD__,$ret_info,[
            "acc"             =>$acc
        ]);
    }

    public function get_teacher_level_quarter_info_fulltime(){
        $this->switch_tongji_database();
        $sum_field_list = [
            "total_score"
        ];
        $order_field_arr = array_merge(["realname"],$sum_field_list);
        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str($order_field_arr,"realname desc ");

        $season = ceil((date('n'))/3)-1;//上季度是第几季度
        $start_time = strtotime(date('Y-m-d H:i:s', mktime(0, 0, 0,$season*3-3+1,1,date('Y'))));
        $end_time = strtotime(date('Y-m-d H:i:s', mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'))));
        $this->set_in_value("quarter_start",$start_time);
        $quarter_start = $this->get_in_int_val("quarter_start");
        $fulltime_teacher_type = $this->get_in_int_val("fulltime_teacher_type",-1);

        $hand_info = $this->t_teacher_advance_list->get_hand_add_list($start_time,1,1,$fulltime_teacher_type);
        foreach($hand_info as &$h){
            $h["realname"] = $this->t_teacher_info->get_realname($h["teacherid"]);
            $h["level"]  = $this->t_teacher_info->get_level($h["teacherid"]);
            $h["level_str"] =E\Elevel::get_desc($h["level"]);
            $h["lesson_count"] =  $h["lesson_count"]/100;
            $h["level_after_str"] =E\Elevel::get_desc($h["level_after"]);
            $h["is_refund_str"] = $h["is_refund"]==1?"<font color='red'>有</font>":"无";
            \App\Helper\Utils::unixtime2date_for_item($h,"accept_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($h,"require_time","_str");

            E\Eaccept_flag::set_item_value_str($h);

        }
        $ret_info = \App\Helper\Utils::list_to_page_info($hand_info);
        if (!$order_in_db_flag) {
            \App\Helper\Utils::order_list( $ret_info["list"], $order_field_name, $order_type );
        }

        return $this->pageView(__METHOD__,$ret_info);

        //dd($ret_info);

    }

    public function add_teacher_advance_info(){
        $season = ceil((date('n'))/3)-1;//上季度是第几季度
        $start_time = strtotime(date('Y-m-d H:i:s', mktime(0, 0, 0,$season*3-3+1,1,date('Y'))));
        $teacherid = $this->get_in_int_val("teacherid");
        $total_score = $this->get_in_int_val("total_score");
        $level = $this->t_teacher_info->get_level($teacherid);
        $teacher_money_type = $this->t_teacher_info->get_teacher_money_type($teacherid);
        /*$level_after = $level+1;
        if($level==4){
            $level_after=4;
            }*/
        $this->t_teacher_advance_list->row_insert([
            "start_time" =>$start_time,
            "teacherid"  =>$teacherid,
            "level_before" =>$level,
            // "level_after"  =>$level_after,
            "total_score"  =>$total_score,
            "hand_flag"    =>1,
            "teacher_money_type"=>$teacher_money_type
        ]);
        return $this->output_succ();

    }
    public function get_other_order_score($num,$per){
        if($num==1 && $per==100){
            return 2;
        }elseif($num==2 && $per==100){
            return 3;
        }elseif($num==3 && $per==100){
            return 4;
        }elseif($num>=4 && $per==100){
            return 5;
        }elseif($per <20){
            return 1;
        }elseif($per >=20 && $per <40){
            return 2;
        }elseif($per >=40 && $per <60){
            return 3;
        }elseif($per >=60 && $per <100){
            return 4;
        }


    }

    public function get_cc_order_score($num,$per){
        if($num==1 && $per==100){
            return 12;
        }elseif($num==2 && $per==100){
            return 13;
        }elseif($num==3 && $per==100){
            return 14;
        }elseif($num==4 && $per==100){
             return 15;
        }elseif($per==0){
            return 10;
        }elseif($per <10 && $per>0){
            return 9;
        }elseif($per >=10 && $per <20){
            return 11;
        }elseif($per >=20 && $per <40){
            return 12;
        }elseif($per >=40 && $per <60){
            return 13;
        }elseif($per >=60 && $per <80){
            return 14;
        }elseif($per >=80){
            return 15;
        }


    }

    public function get_stu_num_score($stu_num){
        if($stu_num<8){
            return 6;
        }elseif($stu_num<12){
            return 7;
        }elseif($stu_num<16){
            return 8;
        }elseif($stu_num<20){
            return 9;
        }else{
            return 10;
        }
    }
    public function get_score_by_lesson_count($lesson_count){
        if($lesson_count >=60 && $lesson_count <80){
            return 26;
        }elseif($lesson_count >=60 && $lesson_count <100){
            return 28;
        }elseif($lesson_count >=100 && $lesson_count <120){
            return 30;
        }elseif($lesson_count >=120 && $lesson_count <140){
            return 32;
        }elseif($lesson_count >=140 && $lesson_count <160){
            return 34;
        }elseif($lesson_count >=160 && $lesson_count <180){
            return 36;
        }elseif($lesson_count >=180 && $lesson_count <200){
            return 38;
        }elseif($lesson_count>=200){
            return 40;
        }else{
            return 0;
        }


    }

    public function update_teacher_advance_info_hand(){
        $season = ceil((date('n'))/3)-1;//上季度是第几季度
        $teacherid = $this->get_in_int_val("teacherid");
        $start_time = $this->get_in_int_val("start_time");
        $realname = $this->get_in_str_val("realname");
        $end_time = strtotime(date('Y-m-d H:i:s', mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'))));
        $transfer_teacherid = $this->t_teacher_info->get_transfer_teacherid($teacherid);
        // $realname ="胡玉梅";
        $tea_arr=[];
        $tea_arr[] = $teacherid;
        if($transfer_teacherid>0){
            $tea_arr[]= $transfer_teacherid;
        }
        $teacher_money_type = $this->t_teacher_info->get_teacher_money_type($teacherid);
        $lesson_total = $this->t_teacher_info->get_teacher_lesson_total_realname($teacher_money_type,$start_time,$end_time,"",$tea_arr);
        $lesson_count=0;
        foreach($lesson_total as $val){
            $lesson_count +=$val["lesson_count"];
        }
        $lesson_count = round($lesson_count/3,1);
        $lesson_count_score = $this->get_score_by_lesson_count($lesson_count/100);

        $test_person_num= $this->t_lesson_info->get_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);


        $kk_test_person_num= $this->t_lesson_info->get_kk_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);
        $change_test_person_num= $this->t_lesson_info->get_change_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);

        $teacher_record_score = $this->t_teacher_record_list->get_test_lesson_record_score($start_time,$end_time,$tea_arr,1);
        $tea_refund_info =$this->get_tea_refund_info($start_time,$end_time,$tea_arr);
        $cc_test_num=$cc_order_num=0;
        foreach($test_person_num as $val){
            $cc_test_num +=$val["person_num"];
            $cc_order_num +=$val["have_order"];
        }

        $cc_order_per= !empty($cc_test_num)?round($cc_order_num/$cc_test_num*100,2):0;
        $cc_order_score = $this->get_cc_order_score($cc_order_num,$cc_order_per);

        $other_test_num = $other_order_num=0;
        foreach($kk_test_person_num as $val){
            $other_test_num +=$val["kk_num"];
            $other_order_num +=$val["kk_order"];

        }
        foreach($change_test_person_num as $val){
            $other_test_num +=$val["change_num"];
            $other_order_num +=$val["change_order"];
        }

        $other_order_per = !empty($other_test_num)?round($other_order_num/$other_test_num*100,2):0;
        $other_order_score = $this->get_other_order_score($other_order_num,$other_order_per);

        $record_num = $record_score=0;
        foreach($teacher_record_score as $val){
            $record_num +=$val["num"];
            $record_score +=$val["score"];
        }
        $record_score_avg = !empty($record_num)?round($record_score/$record_num,1):0;
        $record_final_score = !empty($record_num)?ceil($record_score_avg*0.3):18;
        $is_refund = 0;
        if(!empty($tea_refund_info)){
            $is_refund=1;
        }

        //常规学生数
        $stu_num = $this->t_teacher_info->get_teacher_lesson_stu_num($teacher_money_type,$start_time,$end_time,$tea_arr);
        $stu_num_score = $this->get_stu_num_score($stu_num);

        $total_score = $lesson_count_score+$cc_order_score+ $other_order_score+$record_final_score+$stu_num_score;
        $this->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[
            "lesson_count"=>$lesson_count,
            "lesson_count_score"=>$lesson_count_score,
            "cc_test_num"=>$cc_test_num,
            "cc_order_num" =>$cc_order_num,
            "cc_order_per" =>$cc_order_per,
            "cc_order_score" =>$cc_order_score,
            "other_test_num"=>$other_test_num,
            "other_order_num" =>$other_order_num,
            "other_order_per" =>$other_order_per,
            "other_order_score" =>$other_order_score,
            "record_final_score"=>$record_final_score,
            "record_score_avg" =>$record_score_avg,
            "record_num"     =>$record_num,
            "is_refund"      =>$is_refund,
            "total_score"    =>$total_score,
            "stu_num"        =>$stu_num,
            "stu_num_score"  =>$stu_num_score
        ]);
        return $this->output_succ();
    }

    public function update_level_after(){
        $teacherid = $this->get_in_int_val("teacherid");
        $start_time = $this->get_in_int_val("start_time");
        $level_after = $this->get_in_int_val("level_after");
        $this->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[
            "level_after" =>$level_after
        ]);
        return $this->output_succ();

    }
    public function set_teacher_advance_require(){
        $teacherid = $this->get_in_int_val("teacherid");
        $start_time = $this->get_in_int_val("start_time");
        $level_before = $this->get_in_int_val("level_before");
        $level_after = $this->get_in_int_val("level_after");
        $lesson_count = $this->get_in_int_val("lesson_count");
        $lesson_count_score  = $this->get_in_int_val("lesson_count_score");
        $cc_test_num = $this->get_in_int_val("cc_test_num");
        $cc_order_num = $this->get_in_int_val("cc_order_num");
        $cc_order_per = $this->get_in_str_val("cc_order_per");
        $cc_order_score = $this->get_in_int_val("cc_order_score");
        $other_test_num = $this->get_in_int_val("other_test_num");
        $other_order_num = $this->get_in_int_val("other_order_num");
        $other_order_per = $this->get_in_str_val("other_order_per");
        $other_order_score = $this->get_in_int_val("other_order_score");
        $record_num = $this->get_in_int_val("record_num");
        $record_score_avg = $this->get_in_str_val("record_score_avg");
        $record_final_score  = $this->get_in_int_val("record_final_score");
        $is_refund  = $this->get_in_int_val("is_refund");
        $total_score = $this->get_in_int_val("total_score");
        $hand_flag = $this->get_in_int_val("hand_flag");
        $teacher_money_type = $this->get_in_int_val("teacher_money_type");
        $golden_flag = $this->get_in_int_val("golden_flag",0);
        $exists = $this->t_teacher_advance_list->field_get_list_2($start_time,$teacherid,"teacherid");

        if(!$exists){
            $this->t_teacher_advance_list->row_insert([
                "start_time" =>$start_time,
                "teacherid"  =>$teacherid,
                "level_before"=>$level_before,
                "level_after" =>$level_after,
                "lesson_count"=>$lesson_count,
                "lesson_count_score"=>$lesson_count_score,
                "cc_test_num"=>$cc_test_num,
                "cc_order_num" =>$cc_order_num,
                "cc_order_per" =>$cc_order_per,
                "cc_order_score" =>$cc_order_score,
                "other_test_num"=>$other_test_num,
                "other_order_num" =>$other_order_num,
                "other_order_per" =>$other_order_per,
                "other_order_score" =>$other_order_score,
                "record_final_score"=>$record_final_score,
                "record_score_avg" =>$record_score_avg,
                "record_num"     =>$record_num,
                "is_refund"      =>$is_refund,
                "total_score"    =>$total_score,
                "require_time"   =>time(),
                "require_adminid"=>$this->get_account_id(),
                "teacher_money_type"=>$teacher_money_type
            ]);
        }else{
            $this->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[
                "require_time"   =>time(),
                "require_adminid"=>$this->get_account_id(),
                "level_after" =>$level_after,
                "teacher_money_type"=>$teacher_money_type
            ]);
        }
        $realname  = $this->t_teacher_info->get_realname($teacherid);
        if($teacherid==392077){
             $this->t_manager_info->send_wx_todo_msg_by_adminid (1122,"兼职老师晋升申请","兼职老师晋升申请待处理",$realname."老师的晋升申请已提交,请尽快审核","http://admin.leo1v1.com/teacher_level/get_teacher_advance_info?start_time=".$start_time."&teacherid=".$teacherid."&is_test_user=-1");
        }else{
            $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"兼职老师晋升申请","兼职老师晋升申请待处理",$realname."老师的晋升申请已提交,请尽快审核","http://admin.leo1v1.com/teacher_level/get_teacher_advance_info?start_time=".$start_time."&teacherid=".$teacherid);
            $this->t_manager_info->send_wx_todo_msg_by_adminid (72,"兼职老师晋升申请","兼职老师晋升申请待处理",$realname."老师的晋升申请已提交,请尽快审核","http://admin.leo1v1.com/teacher_level/get_teacher_advance_info?start_time=".$start_time."&teacherid=".$teacherid);

        }


        return $this->output_succ();
    }

    public function send_teacher_advince_wx(){
        $start_time = 1498838400;
        $start_time_str = '2017-07-01';
        $teacherid = $this->get_in_int_val("teacherid");
        $realname  = $this->t_teacher_info->get_realname($teacherid);
        $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"教研质监老师晋升申请","教研质监老师晋升申请待处理",$realname."老师的晋升申请已提交,请尽快审核","http://admin.leo1v1.com/teacher_level/teacher_advance_info_list?start_time=".$start_time_str."&teacherid=".$teacherid);
        $this->t_manager_info->send_wx_todo_msg_by_adminid (72,"教研质监老师晋升申请","教研质监老师晋升申请待处理",$realname."老师的晋升申请已提交,请尽快审核","http://admin.leo1v1.com/teacher_level/teacher_advance_info_list?start_time=".$start_time_str."&teacherid=".$teacherid);

        return $this->output_succ();

    }

    public function set_teacher_advance_require_new(){
        $start_time = 1498838400;
        $start_time_str = '2017-07-01';
        $teacherid = $this->get_in_int_val("teacherid");
        $level_before = $this->get_in_int_val("level_before");
        $level_after = $this->get_in_int_val("level_after");
        $this->t_teacher_advance_list->row_insert([
            "start_time" =>$start_time,
            "teacherid"  =>$teacherid,
            "level_before"=>$level_before,
            "level_after" =>$level_after,
            "require_adminid"=>$this->get_account_id(),
            "require_time"   =>time(),
        ]);
        $realname  = $this->t_teacher_info->get_realname($teacherid);
        $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"教研质监老师晋升申请","教研质监老师晋升申请待处理",$realname."老师的晋升申请已提交,请尽快审核","http://admin.leo1v1.com/teacher_level/teacher_advance_info_list?start_time=".$start_time_str."&teacherid=".$teacherid);
        $this->t_manager_info->send_wx_todo_msg_by_adminid (72,"教研质监老师晋升申请","教研质监老师晋升申请待处理",$realname."老师的晋升申请已提交,请尽快审核","http://admin.leo1v1.com/teacher_level/teacher_advance_info_list?start_time=".$start_time_str."&teacherid=".$teacherid);

        return $this->output_succ();
    }

    public function set_teacher_advance_require_master(){
        $teacherid = $this->get_in_int_val("teacherid");
        $start_time =$this->get_in_int_val("start_time");
        $level_after = $this->get_in_int_val("level_after");
        $accept_flag = $this->get_in_int_val("accept_flag");
        $accept_info = trim($this->get_in_str_val("accept_info"));
        $this->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[
            "accept_flag"  =>$accept_flag,
            "accept_time"  =>time(),
            "accept_adminid" =>$this->get_account_id(),
            "accept_info"    =>$accept_info
        ]);
        $realname  = $this->t_teacher_info->get_realname($teacherid);
        if($accept_flag==1 ){
            $old_level = $this->t_teacher_info->get_level($teacherid);
            $this->t_teacher_info->field_update_list($teacherid,["level"=>$level_after]);
            // $level_degree = E\Elevel::v2s($level_after);
            $info = $this->t_teacher_info->field_get_list($teacherid,"teacher_money_type,teacher_type,nick");
            $info["level"] = $level_after;
            $info["old_level"] = $old_level;

            $level_degree    = \App\Helper\Utils::get_teacher_level_str($info);

            $score = $this->t_teacher_advance_list->get_total_score($start_time,$teacherid);

            //已排課程工資等級更改
            $level_start = strtotime(date("Y-m-d",time()));
            $teacher_money_type = $this->t_teacher_info->get_teacher_money_type($teacherid);
            $this->t_lesson_info->set_teacher_level_info_from_now($teacherid,$teacher_money_type,$level_after,$level_start);

            //微信通知老师
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
            // $wx_openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";
            if($wx_openid){
                $data=[];
                $template_id      = "E9JWlTQUKVWXmUUJq_hvXrGT3gUvFLN6CjYE1gzlSY0";
                $data['first']    = "恭喜".$realname."老师,您已经成功晋级到了".$level_degree;
                $data['keyword1'] = $realname;
                $data['keyword2'] = $level_degree;
                $data['keyword3'] = date("Y-m-d 00:00",time());
                /* $data['remark']   = "晋升分数:".$score
                   ."\n请您继续加油,理优期待与你一起共同进步,提供高品质教学服务";*/
                $data['remark']   = "希望老师在今后的教学中继续努力,再创佳绩";

                $url = "http://admin.leo1v1.com/common/show_level_up_html?teacherid=".$teacherid;
                \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
            }

            //邮件推送 暂时不推
            /* $html  = $this->teacher_level_up_html($info);
            $email = $this->t_teacher_info->get_email($teacherid);
            // $email = "jack@leoedu.com";
            if($email){
                dispatch( new \App\Jobs\SendEmailNew(
                    $email,"【理优1对1】老师晋升通知",$html
                ));
                }*/

            //微信通知教研
            $subject = $this->t_teacher_info->get_subject($teacherid);
            $master_adminid = $this->get_tea_adminid_by_subject($subject);
            $teacher_info = $this->t_manager_info->get_teacher_info_by_adminid($master_adminid);
            $jy_teacherid = $teacher_info["teacherid"];
            $wx_openid = $this->t_teacher_info->get_wx_openid($jy_teacherid);
            //  $wx_openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";
            if($wx_openid){
                $data=[];
                $template_id      = "E9JWlTQUKVWXmUUJq_hvXrGT3gUvFLN6CjYE1gzlSY0";
                $data['first']    = "恭喜".$realname."获得了晋升";
                $data['keyword1'] = $realname;
                $data['keyword2'] = $level_degree;
                $data['keyword3'] = date("Y-m-d H:i",time());
                $data['remark']   = "";
                // $url = "";
                $url = "http://admin.leo1v1.com/common/show_level_up_html?teacherid=".$teacherid;
                \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
            }


        }elseif($accept_flag==2){
            //微信通知師資管理
            /**
             * 模板ID   : 9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ
             * 标题课程 : 评估结果通知
             * {{first.DATA}}
             * 评估内容：{{keyword1.DATA}}
             * 评估结果：{{keyword2.DATA}}
             * 时间：{{keyword3.DATA}}
             * {{remark.DATA}}
             */


            $wx_openid = $this->t_teacher_info->get_wx_openid(130462);
            $wx_openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";
            if($wx_openid){
                $data=[];
                $template_id      = "9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ";
                $data['first']    = "晋升申请驳回";
                $data['keyword1'] = $realname."未能通过晋升申请";
                $data['keyword2'] = $accept_info;
                $data['keyword3'] = date("Y-m-d H:i",time());
                $data['remark']   = "";
                $url = "";
                \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
            }




        }
        return $this->output_succ();

    }

    public function get_teacher_advance_info_fulltime(){
        $this->set_in_value( "fulltime_flag_new", 1);
        return $this->get_teacher_advance_info();
    }
    public function get_teacher_advance_info(){
        $season = ceil((date('n'))/3)-1;//上季度是第几季度
        $start_time = strtotime(date('Y-m-d H:i:s', mktime(0, 0, 0,$season*3-3+1,1,date('Y'))));
        $end_time = strtotime(date('Y-m-d H:i:s', mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'))));
        // $start_time = strtotime("2017-04-01");
        $start_time = $this->get_in_int_val("start_time",$start_time);
        $this->set_filed_for_js("quarter_start",$start_time);
        $teacher_money_type       = $this->get_in_int_val("teacher_money_type",-1);
        $teacherid       = $this->get_in_int_val("teacherid",-1);
        $accept_flag       = $this->get_in_int_val("accept_flag",-1);

        $fulltime_flag_new       = $this->get_in_int_val("fulltime_flag_new",0);
        $is_test_user       = $this->get_in_int_val("is_test_user",0);

        $page_info = $this->get_in_page_info();
        $ret_info = $this->t_teacher_advance_list->get_info_by_time($page_info,$start_time,$teacher_money_type,$teacherid,$accept_flag,$fulltime_flag_new,$is_test_user);
        foreach($ret_info["list"] as &$item){
            if($item["teacher_money_type"]==6){
                E\Enew_level::set_item_value_str($item,"level_before");
                E\Enew_level::set_item_value_str($item,"level_after");
                $item["level_before_str"] = E\Enew_level::v2s($item["level_before"]);
                $item["level_after_str"] = E\Enew_level::v2s($item["level_after"]);


            }else{
                E\Elevel::set_item_value_str($item,"level_before");
                E\Elevel::set_item_value_str($item,"level_after");
                $item["level_before_str"] = E\Elevel::v2s($item["level_before"]);
                $item["level_after_str"] = E\Elevel::v2s($item["level_after"]);

            }
            \App\Helper\Utils::unixtime2date_for_item($item,"accept_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"require_time","_str");

            E\Eaccept_flag::set_item_value_str($item);
            $item["is_refund_str"] = $item["is_refund"]==1?"<font color='red'>有</font>":"无";
            $item["test_total_score"] = $item["cc_order_score"]+$item["other_order_score"];
            $record_final_score = !empty($item["record_num"])?round($item["record_score_avg"]*0.3,1):18;
            $item["total_score"] =$item["total_score"]-$item["record_final_score"]+$record_final_score;;
            $item["record_final_score"]=$record_final_score;


        }

        //季度时间列表
        $season_list = $this->get_four_season_list();

        return $this->pageView(__METHOD__,$ret_info,[
            "season_list"  =>$season_list
        ]);
    }

    public function teacher_advance_info_list(){
        $season = ceil((date('n'))/3)-1;//上季度是第几季度
        $start_time = strtotime(date('Y-m-d H:i:s', mktime(0, 0, 0,$season*3-3+1,1,date('Y'))));
        $end_time = strtotime(date('Y-m-d H:i:s', mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'))));

        $start_time = $this->get_in_int_val("start_time",$start_time);
        $this->set_filed_for_js("quarter_start",$start_time);
        $teacher_money_type = $this->get_in_int_val("teacher_money_type",-1);
        $teacherid          = $this->get_in_int_val("teacherid",-1);
        $accept_flag        = $this->get_in_int_val("accept_flag",-1);
        $fulltime_flag      = $this->get_in_int_val("fulltime_flag",-1);
        $page_info          = $this->get_in_page_info();

        $ret_info = $this->t_teacher_advance_list->get_info_by_time_new($page_info,$teacher_money_type,$teacherid,$accept_flag,$fulltime_flag,$start_time);
        foreach($ret_info["list"] as &$item){
            E\Eaccept_flag::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,"accept_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"require_time","_str");
            E\Elevel::set_item_value_str($item,"level_before");
            E\Elevel::set_item_value_str($item,"level_after");
            \App\Helper\Utils::unixtime2date_for_item($item,"become_member_time");
        }
        //季度时间列表
        $season_list = $this->get_four_season_list();

        return $this->pageView(__METHOD__,$ret_info,[
            "season_list"  =>$season_list
        ]);

    }

    public function get_teacher_refund_detail_info(){
        $teacherid = $this->get_in_int_val("teacherid");
        $start_time = $this->get_in_int_val("start_time");
        // $teacherid = 109666;
        $tea_arr = [$teacherid];
        // $start_time = strtotime("2017-04-01");
        $end_time = strtotime(date("Y-m-01",$start_time+110*86400));
        $list = $this->t_order_refund->get_tea_refund_info_new($start_time,$end_time,$tea_arr);
        $arr =[];
        foreach($list as $val){
            $ss = $val["nick"]."-".$val["apply_time"];
            @$arr[$ss][$val["value"]]=$val["score"];
        }
        $data=[];
        foreach($arr as $k=>$item){
            $all=0;$ass=0;
            foreach($item as $kk=>$v){
                if($kk=="教学部"){
                    $ass = $v;
                }
                $all +=$v;

            }
            if($all>0 && $ass >0){
                @$data[$k]["per"]=round(100*$ass/$all,2);
                $info = explode("-",$k);
                $data[$k]["nick"] = @$info[0];
                $data[$k]["apply_time"] = @$info[1];
                $data[$k]["apply_time_str"]=!empty($data[$k]["apply_time"])?date("Y-m-d H:i:s",$data[$k]["apply_time"]):"";
            }


        }
        return $this->output_succ(["data"=>$data]);



    }


    public function get_teacher_test_lesson_info(){
        $teacherid = $this->get_in_int_val("teacherid");
        $num = $this->get_in_int_val("num");
        $userid       = $this->get_in_int_val("userid",-1);
        $lesson_type       = $this->get_in_int_val("lesson_type");
        $data= $this->t_lesson_info_b2->get_lesson_row_info($teacherid,$lesson_type,$num,$userid);
        /*  $data["first"]["num"] = "第一次课";
        $data["five"] = $this->t_lesson_info_b2->get_lesson_row_info($teacherid,$lesson_type,4);
        $data["five"]["num"] = "第五次课";*/
        /* foreach($data as &$item){
             E\Esubject::set_item_value_str($item,"subject");
             $item["lesson_start_str"] = date("Y-m-d H:i:s",$item["lesson_start"]);
             $item["nick"] = $this->t_student_info->get_nick($item["userid"]);
             }*/
        if(empty($data)){
            return $this->output_err("没有视频!");
        }else{
            return $this->output_succ(["data"=>$data["lessonid"]]);
        }
    }

    public function teacher_lesson_record_info(){
        $this->switch_tongji_database();
        $page_info = $this->get_in_page_info();
        $teacherid       = $this->get_in_int_val("teacherid",-1);
        $subject         = $this->get_in_int_val("subject",-1);
        $ret_info = $this->t_teacher_info->get_tea_have_test_lesson($page_info,$teacherid,$subject);
        foreach($ret_info["list"] as &$item){
            E\Esubject::set_item_value_str($item,"subject");
            E\Egrade_part_ex::set_item_value_str($item,"grade_part_ex");
            E\Egrade_range::set_item_value_str($item,"grade_start");
            E\Egrade_range::set_item_value_str($item,"grade_end");

        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function teacher_regular_lesson_record_info(){
        $this->switch_tongji_database();
        $page_info = $this->get_in_page_info();
        $teacherid       = $this->get_in_int_val("teacherid",-1);
        $userid       = $this->get_in_int_val("userid",-1);
        $subject         = $this->get_in_int_val("subject",-1);
        $arr = $this->t_teacher_info->get_tea_regular_test_lesson_list($subject);
        $tea_list=[];
        foreach($arr as $val){
            $tea_list[]=$val["teacherid"];
        }
        $ret_info = $this->t_teacher_info->get_tea_regular_test_lesson($page_info,$teacherid,$userid,$subject,$tea_list);
        foreach($ret_info["list"] as &$item){
            E\Esubject::set_item_value_str($item,"subject");
            E\Egrade_part_ex::set_item_value_str($item,"grade_part_ex");
            E\Egrade_range::set_item_value_str($item,"grade_start");
            E\Egrade_range::set_item_value_str($item,"grade_end");
        }
        return $this->pageView(__METHOD__,$ret_info);
    }


    public function get_first_test_lesson_info_jy(){
        return $this->get_first_test_lesson_info();
    }

    //第一次试听课列表
    public function get_first_test_lesson_info(){
        $this->switch_tongji_database();
        $page_info = $this->get_in_page_info();
        list($start_time, $end_time)=$this->get_in_date_range(0,0,0,[],3);
        $subject         = $this->get_in_int_val("subject",-1);
        $teacherid       = $this->get_in_int_val("teacherid",-1);
        $record_flag       = $this->get_in_int_val("record_flag",0);
        $tea_subject = $this->get_admin_subject($this->get_account_id(),2);
        $ret_info = $this->t_lesson_info_b2->get_teacher_first_test_lesson($page_info,$start_time,$end_time,$subject,$teacherid,$record_flag,$tea_subject);
        // dd($ret_info);
        foreach($ret_info["list"] as &$item){
            E\Esubject::set_item_value_str($item,"subject");
            E\Egrade_part_ex::set_item_value_str($item,"grade_part_ex");
            E\Egrade_range::set_item_value_str($item,"grade_start");
            E\Egrade_range::set_item_value_str($item,"grade_end");
            E\Egrade::set_item_value_str($item);
            if(!empty($item["record_info"])){
                $item["record_flag_str"]="已反馈";
            }else{
                $item["record_flag_str"]="未反馈";
            }
            if(empty($item["test_stu_request_test_lesson_demand"])){
                $item["test_stu_request_test_lesson_demand"] = $item["stu_request_test_lesson_demand"];
            }
            $item["add_time_str"] = date("Y-m-d H:i",$item["add_time"]);

        }

        $this->set_in_value("acc",$this->get_account());
        $acc = $this->get_in_str_val("acc");
        return $this->pageView(__METHOD__,$ret_info,[
            "acc" =>$acc
        ]);

    }


    public function get_fifth_test_lesson_info_jy(){
        return $this->get_fifth_test_lesson_info();
    }


    //第五次试听课列表
    public function get_fifth_test_lesson_info(){
        $this->switch_tongji_database();
        $page_info = $this->get_in_page_info();
        list($start_time, $end_time)=$this->get_in_date_range(0,0,0,[],3);
        $subject         = $this->get_in_int_val("subject",-1);
        $teacherid       = $this->get_in_int_val("teacherid",-1);
        $record_flag       = $this->get_in_int_val("record_flag",0);
        $tea_subject = $this->get_admin_subject($this->get_account_id(),2);
        $ret_info = $this->t_lesson_info_b2->get_teacher_fifth_test_lesson($page_info,$start_time,$end_time,$subject,$teacherid,$record_flag,$tea_subject);
        foreach($ret_info["list"] as &$item){
            E\Esubject::set_item_value_str($item,"subject");
            E\Egrade_part_ex::set_item_value_str($item,"grade_part_ex");
            E\Egrade_range::set_item_value_str($item,"grade_start");
            E\Egrade_range::set_item_value_str($item,"grade_end");
            E\Egrade::set_item_value_str($item);
            if(!empty($item["record_info"])){
                $item["record_flag_str"]="已反馈";
            }else{
                $item["record_flag_str"]="未反馈";
            }
            if(empty($item["test_stu_request_test_lesson_demand"])){
                $item["test_stu_request_test_lesson_demand"] = $item["stu_request_test_lesson_demand"];
            }
            $item["add_time_str"] = date("Y-m-d H:i",$item["add_time"]);

        }
        $this->set_in_value("acc",$this->get_account());
        $acc = $this->get_in_str_val("acc");
        return $this->pageView(__METHOD__,$ret_info,[
            "acc" =>$acc
        ]);

    }

    public function get_first_regular_lesson_info_jy(){
        return $this->get_first_regular_lesson_info();
    }


    //第一次常规课课列表
    public function get_first_regular_lesson_info(){
        $this->switch_tongji_database();

        $page_info = $this->get_in_page_info();
        list($start_time, $end_time)=$this->get_in_date_range(0,0,0,[],3);
        $subject         = $this->get_in_int_val("subject",-1);
        $teacherid       = $this->get_in_int_val("teacherid",-1);
        $userid       = $this->get_in_int_val("userid",-1);
        $record_flag       = $this->get_in_int_val("record_flag",0);
        $tea_subject = $this->get_admin_subject($this->get_account_id(),2);
        $ret_info = $this->t_lesson_info_b2->get_teacher_first_regular_lesson($page_info,$start_time,$end_time,$subject,$teacherid,$record_flag,$userid,$tea_subject);
        foreach($ret_info["list"] as &$item){
            E\Esubject::set_item_value_str($item,"subject");
            E\Egrade_part_ex::set_item_value_str($item,"grade_part_ex");
            E\Egrade_range::set_item_value_str($item,"grade_start");
            E\Egrade_range::set_item_value_str($item,"grade_end");
            E\Egrade::set_item_value_str($item);
            if(!empty($item["record_info"])){
                $item["record_flag_str"]="已反馈";
            }else{
                $item["record_flag_str"]="未反馈";
            }
            $item["add_time_str"] = date("Y-m-d H:i",$item["add_time"]);

        }

        $this->set_in_value("acc",$this->get_account());
        $acc = $this->get_in_str_val("acc");
        return $this->pageView(__METHOD__,$ret_info,[
            "acc" =>$acc
        ]);

    }

    public function get_fifth_regular_lesson_info_jy(){
        return $this->get_fifth_regular_lesson_info();
    }


    //第五次常规课课列表
    public function get_fifth_regular_lesson_info(){
        $this->switch_tongji_database();
        $page_info = $this->get_in_page_info();
        list($start_time, $end_time)=$this->get_in_date_range(0,0,0,[],3);
        $subject         = $this->get_in_int_val("subject",-1);
        $teacherid       = $this->get_in_int_val("teacherid",-1);
        $userid       = $this->get_in_int_val("userid",-1);
        $record_flag       = $this->get_in_int_val("record_flag",0);
        $tea_subject = $this->get_admin_subject($this->get_account_id(),2);
        $ret_info = $this->t_lesson_info_b2->get_teacher_fifth_regular_lesson($page_info,$start_time,$end_time,$subject,$teacherid,$record_flag,$userid,$tea_subject);
        foreach($ret_info["list"] as &$item){
            E\Esubject::set_item_value_str($item,"subject");
            E\Egrade_part_ex::set_item_value_str($item,"grade_part_ex");
            E\Egrade_range::set_item_value_str($item,"grade_start");
            E\Egrade_range::set_item_value_str($item,"grade_end");
            E\Egrade::set_item_value_str($item);
            if(!empty($item["record_info"])){
                $item["record_flag_str"]="已反馈";
            }else{
                $item["record_flag_str"]="未反馈";
            }
            $item["add_time_str"] = date("Y-m-d H:i",$item["add_time"]);

        }
        $this->set_in_value("acc",$this->get_account());
        $acc = $this->get_in_str_val("acc");

        return $this->pageView(__METHOD__,$ret_info,[
            "acc" =>$acc
        ]);
    }

    public function set_teacher_record_acc(){
        $teacherid                        = $this->get_in_int_val("teacherid",0);
        $lessonid                         = $this->get_in_int_val("lessonid",0);
        $record_lesson_list               = $this->get_in_str_val("lesson_list","");
        $record_type                     = $this->get_in_int_val("type");
        $lesson_style                    = $this->get_in_int_val("lesson_style");
        // $acc                        = $this->get_in_str_val("acc");
        $id = $this->t_teacher_record_list->check_lesson_record_exist($lessonid,$record_type,$lesson_style);
        if($id>0){
            $acc= $this->t_teacher_record_list->get_acc($id);
            if(empty($acc)){
                $acc= $this->get_account();
                $ret = $this->t_teacher_record_list->field_update_list($id,[
                    "acc"            => $acc,
                    "click_time"     => time(),
                ]);
            }
        }else{
            $acc= $this->get_account();
            $ret = $this->t_teacher_record_list->row_insert([
                "teacherid"      => $teacherid,
                "type"           => $record_type,
                "train_lessonid" => $lessonid,
                "lesson_style"   => $lesson_style,
                "acc"            => $acc,
                "add_time"       => time(),
                "click_time"     => time(),
            ]);

        }

        //获取老师标签列表
        $list = $this->get_teacher_tag_list();


        return $this->output_succ(["acc"=>$acc,"data"=>$list]);


    }

    public function check_is_have_record(){
        $lessonid                         = $this->get_in_int_val("lessonid",0);
        $id = $this->t_teacher_record_list->check_lesson_record_exist($lessonid,1,-1);

        //获取老师标签列表
        $list = $this->get_teacher_tag_list();




        return $this->output_succ(["id"=>$id,"tag"=>$list]);

    }

    public function set_teacher_record_info(){
        $teacherid                        = $this->get_in_int_val("teacherid",0);
        $userid                           = $this->get_in_int_val("userid",0);
        $lessonid                         = $this->get_in_int_val("lessonid",0);
        $record_lesson_list               = $this->get_in_str_val("lesson_list","");
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
        $record_info                      = trim($this->get_in_str_val("record_info",""));
        $record_score                     = $this->get_in_int_val("score",0);
        $no_tea_related_score             = $this->get_in_int_val("no_tea_related_score",0);
        $record_monitor_class             = $this->get_in_str_val("record_monitor_class","");
        $sshd_good                        = $this->get_in_str_val("sshd_good");
        $record_type                      = $this->get_in_int_val("type");
        $lesson_style                     = $this->get_in_int_val("lesson_style");
        $id                               = $this->get_in_int_val("id");
        $lesson_invalid_flag              = $this->get_in_int_val("lesson_invalid_flag");
        $train_type                       = $this->get_in_str_val("train_type");
        $subject                          = $this->get_in_int_val("subject");
        $new_tag_flag                     = $this->get_in_int_val("new_tag_flag",0);
        $style_character                  = $this->get_in_str_val("style_character");
        $professional_ability             = $this->get_in_str_val("professional_ability");
        $classroom_atmosphere             = $this->get_in_str_val("classroom_atmosphere");
        $courseware_requirements          = $this->get_in_str_val("courseware_requirements");
        $diathesis_cultivation            = $this->get_in_str_val("diathesis_cultivation");

        if(empty($record_info)){
            return $this->output_err("请输入反馈内容!");
        }
        //insert t_teacher_train_info
        $te = $train_type;
        $te  = trim($te,'[]');
        if($te){
            $te  = explode(",", $te);
            foreach ($te as $key => $value) {
                $type = intval(trim($value,'"'));
                $ret = $this->t_teacher_train_info->row_insert([
                    "create_time"    => time(),
                    "create_adminid" => $this->get_account_id(),
                    "train_type"     => $type,
                    "teacherid"      => $teacherid,
                    "subject"        => $subject,
                    "lessonid"       => $lessonid,
                    "status"         => 1,//no
                ]);
            }
        }

        //更新teacher_info里面train_type字段值
        $ret_train_type = $this->t_teacher_info->get_train_type($teacherid);
        $ret_train_type = trim($ret_train_type,'[]');

        if($ret_train_type){
            $ret_train_type = explode(",",$ret_train_type);
        }else{
            $ret_train_type = [];
        }

        $te = $train_type;
        $te  = trim($te,'[]');
        if($te){
            $te  = explode(",", $te);
        }else{
            $te = [];
        }



        foreach($te as $k =>&$v) {
            if(!in_array($v, $ret_train_type))
                array_push($ret_train_type, $v);
        }
        $ret_train_type = implode(',', $ret_train_type);
        $ret_train_type = '['.$ret_train_type.']';
        $this->t_teacher_info->field_update_list($teacherid,['train_type' => $ret_train_type]);





        //
        $id = $this->t_teacher_record_list->check_lesson_record_exist($lessonid,$record_type,$lesson_style);
        $lesson_invalid_flag_old = $this->t_teacher_record_list->get_lesson_invalid_flag($id);
        $add_time = time();
        if($id>0){
            $ret = $this->t_teacher_record_list->field_update_list($id,[
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
                "userid"                           => $userid,
                "add_time"                         => $add_time,
                "lesson_invalid_flag"              =>$lesson_invalid_flag,
                "train_type"                       => $train_type,
            ]);

        }else{
            $ret = $this->t_teacher_record_list->row_insert([
                "teacherid"      => $teacherid,
                "type"           => $record_type,
                "add_time"       => $add_time,
                "train_lessonid" => $lessonid,
                "lesson_style"   => $lesson_style,
                "acc"            => $this->get_account(),
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
                "lesson_invalid_flag"              => $lesson_invalid_flag,
                "userid"                           => $userid,
                "train_type"                       => $train_type,
            ]);

           

        }

        //设置标签
        if((empty($lesson_invalid_flag_old) || $lesson_invalid_flag_old==2) && $lesson_invalid_flag==1){
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
        }


        $this->t_teacher_info->field_update_list($teacherid,["is_record_flag"=>1]);

        $openid = $this->t_teacher_info->get_wx_openid($teacherid);
        if($openid!=''){
            $str="";
            if($lesson_style==1){
                $str = "第一次试听课教学反馈";
            }elseif($lesson_style==2){
                $str = "第五次试听课教学反馈";
            }elseif($lesson_style==3){
                $nick = $this->t_student_info->get_nick($userid);
                $str = "学生:".$nick."的第一次常规课教学反馈";
            }elseif($lesson_style==4){
                $nick = $this->t_student_info->get_nick($userid);
                $str = "学生:".$nick."的第五次常规课教学反馈";
            }elseif($lesson_style=6){
                // $openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";
                $str = "教学反馈";
            }elseif($lesson_style==7){
                $str = "试听课教学反馈";
            }elseif($lesson_style==8){
                $str = "常规课教学反馈";
            }

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
                 ."&type=".$record_type."&add_time=".$add_time;
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
            $tea_nick = $this->t_teacher_info->get_realname($teacherid);
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

    }

    public function reset_record_acc(){
        $id = $this->get_in_int_val("id");
        $this->t_teacher_record_list->field_update_list($id,[
            "acc"  =>"",
            "click_time"=>0
        ]);
        return $this->output_succ();
    }

    public function delete_record_acc(){
        $id  = $this->get_in_int_val("id");
        $this->t_teacher_record_list->row_delete($id);
        return $this->output_succ();
    }



    public function get_seller_top_test_lesson_info(){
        $this->switch_tongji_database();
        $page_info = $this->get_in_page_info();
        list($start_time, $end_time)=$this->get_in_date_range(0,0,0,[],3);
        $subject         = $this->get_in_int_val("subject",-1);
        $teacherid       = $this->get_in_int_val("teacherid",-1);
        $userid       = $this->get_in_int_val("userid",-1);
        $record_flag       = $this->get_in_int_val("record_flag",0);
        $tea_subject = $this->get_admin_subject($this->get_account_id(),2);
        $ret_info = $this->t_lesson_info_b3->get_seller_top_test_lesson($page_info,$start_time,$end_time,$subject,$teacherid,$record_flag,$userid,$tea_subject);
        foreach($ret_info["list"] as &$item){
            E\Esubject::set_item_value_str($item,"subject");
            E\Egrade_part_ex::set_item_value_str($item,"grade_part_ex");
            E\Egrade_range::set_item_value_str($item,"grade_start");
            E\Egrade_range::set_item_value_str($item,"grade_end");
            E\Egrade::set_item_value_str($item);
            if(!empty($item["record_info"])){
                $item["record_flag_str"]="已反馈";
            }else{
                $item["record_flag_str"]="未反馈";
            }
            if(empty($item["test_stu_request_test_lesson_demand"])){
                $item["test_stu_request_test_lesson_demand"] = $item["stu_request_test_lesson_demand"];
            }
            $item["add_time_str"] = date("Y-m-d H:i",$item["add_time"]);
        }

        $this->set_in_value("acc",$this->get_account());
        $acc = $this->get_in_str_val("acc");
        return $this->pageView(__METHOD__,$ret_info,[
            "acc" =>$acc
        ]);
    }

    public function teacher_switch_list(){
        $now_month          = date("m",time());
        $teacher_money_type = $this->get_in_int_val("teacher_money_type",-1);
        $teacherid          = $this->get_in_int_val("teacherid",-1);
        $batch              = $this->get_in_int_val("batch",-1);
        $not_start          = $this->get_in_int_val("not_start",-1);
        $not_end            = $this->get_in_int_val("not_end",$now_month);
        $acc = $this->get_account();
        if($acc=="傅文莉"){
            $status=1;
        }elseif($acc=="ted"){
            $status=2;
        }else{
            $status=-1;
        }
        $status = $this->get_in_int_val("status",$status);

        $not_start = strtotime("2017-".$not_start."-01");
        $not_end   = strtotime("2017-".$not_end."-01");

        $ret_info = $this->t_teacher_switch_money_type_list->get_teacher_switch_list(
            $teacherid,$teacher_money_type,$batch,$status,-1,$not_start,$not_end
        );
        $num=0;
        foreach($ret_info as &$val){
            $num++;
            $val['num']=$num;
            E\Eteacher_money_type::set_item_value_str($val);
            E\Elevel::set_item_value_str($val);
            E\Enew_level::set_item_value_str($val);
            $val['batch_str'] = "第".$val['batch']."批次";
            E\Eswitch_status::set_item_value_str($val,"status");
            $val['lesson_total'] /= 100;
            if($val['confirm_time']>0){
                $val['time_str'] = \App\Helper\Utils::unixtime2date($val['confirm_time']);
            }else{
                $val['time_str'] = \App\Helper\Utils::unixtime2date($val['put_time']);
            }
            if($val['lesson_total']>0){
                $val['per_money_different'] = $val['base_money_different']/$val['lesson_total'];
            }else{
                $val['per_money_different'] = 0;
            }
        }
        $ret_info = \App\Helper\Utils::list_to_page_info($ret_info);

        return $this->pageView(__METHOD__,$ret_info,[
            "acc" => $acc
        ]);
    }

    public function teacher_switch_list_finally(){
        return $this->teacher_switch_list();
    }

    public function switch_upload(){
        $id = $this->get_in_int_val("id");

        $ret = $this->t_teacher_switch_money_type_list->field_update_list($id,[
            "status"   => E\Eswitch_status::V_1,
            "put_time" => time()
        ]);
        if(!$ret){
            return $this->output_err("更新失败!请重试!");
        }
        return $this->output_succ();
    }

    /**
     * 审核切换老师工资类型申请
     * @param id
     * @param type 1 一审审核 2 二审审核
     * @param status 0 不通过 1 通过
     */
    public function check_switch_info(){
        $id                     = $this->get_in_int_val("id");
        $type                   = $this->get_in_int_val("type");
        $status                 = $this->get_in_int_val("status");
        $acc                    = $this->get_account();

        if(!in_array($acc,["傅文莉","ted"])){
            return $this->output_err("你没有审核权限!");
        }
        if($type==1){
            $check_status = $status==1?E\Eswitch_status::V_2:E\Eswitch_status::V_3;
        }elseif($type==2){
            $check_status = $status==1?E\Eswitch_status::V_4:E\Eswitch_status::V_5;
        }else{
            return $this->output_err("类型出错!");
        }

        $this->t_teacher_switch_money_type_list->start_transaction();
        $ret = $this->t_teacher_switch_money_type_list->field_update_list($id,[
            "confirm_time" => time(),
            "status"       => $check_status,
        ]);
        if(!$ret){
            $this->t_teacher_switch_money_type_list->rollback();
            return $this->output_err("更新出错!请重试!");
        }

        if($type==2 && $status==1){
            $teacher_info = $this->t_teacher_switch_money_type_list->get_teacher_info_by_id($id);
            $this->t_teacher_info->field_update_list($teacher_info['teacherid'],[
                "teacher_money_type" => $teacher_info['new_teacher_money_type'],
                "level"              => $teacher_info['new_level'],
            ]);
            $ret = $this->reset_teacher_money_info($teacher_info['teacherid']);
            if(!$ret){
                $this->t_teacher_switch_money_type_list->rollback();
                return $this->output_err("更新老师课程信息出错!请重试!");
            }
        }
        $this->t_teacher_switch_money_type_list->commit();

        return $this->output_succ();
    }

    public function get_teacher_level_quarter_info_show(){
        $sum_field_list = [
            "total_score"
        ];
        $order_field_arr = array_merge(["realname"],$sum_field_list);
        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str($order_field_arr,"realname desc ");

        $season     = ceil((date('n'))/3)-1;//上季度是第几季度
        $start_time = strtotime(date('Y-m-d H:i:s',mktime(0, 0, 0,$season*3-3+1,1,date('Y'))));
        $end_time   = strtotime(date('Y-m-d H:i:s',mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'))));
        $this->set_in_value("quarter_start",$start_time);
        $quarter_start = $this->get_in_int_val("quarter_start");
        $teacher_money_type = $this->get_in_int_val("teacher_money_type",6);
        $teacherid = $this->get_in_int_val("teacherid",-1);
        $is_test_user = $this->get_in_int_val("is_test_user",0);
        $show_all = $this->get_in_int_val("show_all",0);
        $advance_require_flag = $this->get_in_int_val("advance_require_flag",-1);
        $withhold_require_flag = $this->get_in_int_val("withhold_require_flag",-1);
        $page_info = $this->get_in_page_info();


        $teacher_money_type=6;
        $start_time = strtotime("2017-10-01");
        $ret_info = $this->t_teacher_advance_list->get_info_by_time($page_info,$start_time,$teacher_money_type,$teacherid,-1,-1,$is_test_user,$advance_require_flag,$show_all,$withhold_require_flag);
        foreach($ret_info["list"] as &$item){
            //$item["level"]=$item["level_before"];
            $item["level"]=$item["real_level"];
            if($teacher_money_type==6){
                //  E\Enew_level::set_item_value_str($item,"level_before");
                // E\Enew_level::set_item_value_str($item,"level_after");
                $item["level_str"] = E\Enew_level::get_simple_desc($item["level"]);
                $item["level_after_str"] = E\Enew_level::get_simple_desc($item["level_after"]);
                $item["level_before_str"] = E\Enew_level::get_simple_desc($item["level_before"]);


            }else{
                //  E\Elevel::set_item_value_str($item,"level_before");
                // E\Elevel::set_item_value_str($item,"level_after");
                $item["level_str"] = E\Elevel::get_simple_desc($item["level"]);
                $item["level_after_str"] = E\Elevel::get_simple_desc($item["level_after"]);
                $item["level_before_str"] = E\Elevel::get_simple_desc($item["level_before"]);

            }
            \App\Helper\Utils::unixtime2date_for_item($item,"accept_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"require_time","_str");

            E\Eaccept_flag::set_item_value_str($item);
            E\Eaccept_flag::set_item_value_str($item,"withhold_final_trial_flag");
            E\Eaccept_flag::set_item_value_str($item,"advance_first_trial_flag");
            E\Eaccept_flag::set_item_value_str($item,"withhold_first_trial_flag");
            $item["lesson_count"] = $item["lesson_count"]/100;
            // $item["lesson_count_score"] = $this->get_advance_score_by_num( $item["lesson_count"],1);//课耗得分
            $item["lesson_count_score"] = $item["lesson_count_score"]/100;
            // $item["record_final_score"]= $this->get_advance_score_by_num( $item["record_score_avg"],5);//教学质量得分
            $item["record_final_score"] =  $item["record_final_score"]/100;
            //$item["cc_order_score"]= $this->get_advance_score_by_num( $item["cc_order_num"],2);//cc签单数得分
            $item["cc_order_score"]= $item["cc_order_score"]/100;
            // $item["other_order_score"]= $this->get_advance_score_by_num( $item["other_order_num"],3);//cr签单得分
            $item["other_order_score"] =  $item["other_order_score"]/100;
            // $item["stu_num_score"]= $this->get_advance_score_by_num( $item["stu_num"],4);//常规学生签单得分
            $item["stu_num_score"] = $item["stu_num_score"]/100;
            $order_score = $item["cc_order_score"]+ $item["other_order_score"];//签单总分
            if($order_score>=10){
                $order_score=10;
            }
            $item["total_score"] =$item["lesson_count_score"]+$item["record_final_score"]+$order_score+ $item["stu_num_score"];//总得分
            // list($item["reach_flag"],$item["withhold_money"])=$this->get_tea_reach_withhold_list($item["level_before"],$item["total_score"]);
            $item["withhold_money"]=$item["withhold_money"]/100;
            E\Eboolean::set_item_value_str($item,"reach_flag");

        }

        $this->set_filed_for_js("start_time",$start_time);
        return $this->pageView(__METHOD__,$ret_info);
    }

    //晋升申请(2018年1月新版)
    public function set_teacher_advance_require_2018(){
        $start_time   = $this->get_in_int_val("start_time");
        $teacherid    = $this->get_in_int_val("teacherid");
        $level_after  = $this->get_in_int_val("level_after");
        $this->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[
            "level_after"     => $level_after,
            "require_time"    => time(),
            "require_adminid" => $this->get_account_id()
        ]);
        return $this->output_succ();

    }

    //扣款申请
    public function set_teacher_advance_withhold_require_2018(){
        $start_time   = $this->get_in_int_val("start_time");
        $teacherid    = $this->get_in_int_val("teacherid");
        $withhold_money    = $this->get_in_str_val("withhold_money");
        $this->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[
            "withhold_require_time"    => time(),
            "withhold_require_adminid" => $this->get_account_id(),
            "withhold_money"           => $withhold_money*100
        ]);
        return $this->output_succ();

    }

    //晋升审批处理(邮件/微信推送)
    public function set_teacher_advance_require_master_2018(){
        $start_time   = $this->get_in_int_val("start_time");
        $teacherid    = $this->get_in_int_val("teacherid");
        $accept_flag  = $this->get_in_int_val("accept_flag");
        $advance_first_trial_flag   = $this->get_in_int_val("advance_first_trial_flag");
        $old_level  = $this->get_in_int_val("old_level");
        $level_after  = $this->get_in_int_val("level_after");
        if($advance_first_trial_flag>0){
            $this->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[
                "advance_first_trial_flag"     => $advance_first_trial_flag ,
                "advance_first_trial_time"    => time(),
                "advance_first_trial_adminid" => $this->get_account_id()
            ]);
            if($advance_first_trial_flag==2){
                $this->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[
                    "accept_flag"     => 2,
                    "accept_time"    => time(),
                    // "accept_adminid" => $this->get_account_id()
                ]);
            }

        }elseif( $accept_flag>0){
            $check_accept_flag = $this->t_teacher_advance_list->get_advance_first_trial_flag($start_time,$teacherid);
            if($check_accept_flag != 1){
                return $this->output_err("教研总监还未审批!");
            }
            $this->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[
                "accept_flag"     => $accept_flag,
                "accept_time"    => time(),
                "accept_adminid" => $this->get_account_id()
            ]);

            if($accept_flag==1){
                $this->t_teacher_info->field_update_list($teacherid,["level"=>$level_after]);
                $info = $this->t_teacher_info->field_get_list($teacherid,"teacher_money_type,teacher_type,nick,realname,wx_openid");
                $info["level"] = $level_after;
                $info["old_level"] = $old_level;

                $level_degree    = \App\Helper\Utils::get_teacher_level_str($info);

                $score = $this->t_teacher_advance_list->get_total_score($start_time,$teacherid);

                //已排課程工資等級更改
                // $level_start = strtotime(date("Y-m-01",time()));
                $level_start = strtotime("+3 months",$start_time);
                $teacher_money_type = $info["teacher_money_type"];
                $this->t_lesson_info->set_teacher_level_info_from_now($teacherid,$teacher_money_type,$level_after,$level_start);

                //微信通知老师
                /**
                 * 模板ID   : E9JWlTQUKVWXmUUJq_hvXrGT3gUvFLN6CjYE1gzlSY0
                 * 标题课程 : 等级升级通知
                 * {{first.DATA}}
                 * 用户昵称：{{keyword1.DATA}}
                 * 最新等级：{{keyword2.DATA}}
                 * 生效时间：{{keyword3.DATA}}
                 * {{remark.DATA}}
                 */
                $wx_openid = $info["wx_openid"];
                $realname = $info["realname"];
                $wx_openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";
                if($wx_openid){
                    $data=[];
                    $template_id      = "E9JWlTQUKVWXmUUJq_hvXrGT3gUvFLN6CjYE1gzlSY0";
                    $data['first']    = "恭喜".$realname."老师,您已经成功晋级到了".$level_degree;
                    $data['keyword1'] = $realname;
                    $data['keyword2'] = $level_degree;
                    $data['keyword3'] = date("Y-m-01 00:00",$level_start);
                    /* $data['remark']   = "晋升分数:".$score
                       ."\n请您继续加油,理优期待与你一起共同进步,提供高品质教学服务";*/
                    $data['remark']   = "希望老师在今后的教学中继续努力,再创佳绩";

                    $url = "http://admin.leo1v1.com/common/show_level_up_html?teacherid=".$teacherid;
                    \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);                   
                
                }

                // 邮件推送
                $html  = $this->teacher_level_up_html($info);
                $email = $this->t_teacher_info->get_email($teacherid);
                $email = "jack@leoedu.com";
                if($email){
                    dispatch( new \App\Jobs\SendEmailNew(
                        $email,"【理优1对1】老师晋升通知",$html
                    ));
                }

                $this->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[              
                    "advance_wx_flag"=> 1
                ]);

 
            }

        }
       
        return $this->output_succ();
    }

    //扣款审批
    public function set_teacher_withhold_require_master_2018(){
        $start_time   = $this->get_in_int_val("start_time");
        $teacherid    = $this->get_in_int_val("teacherid");
        $withhold_final_trial_flag  = $this->get_in_int_val("withhold_final_trial_flag");
        $withhold_first_trial_flag  = $this->get_in_int_val("withhold_first_trial_flag");
        if($withhold_first_trial_flag>0){
            $this->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[
                "withhold_first_trial_flag"     => $withhold_first_trial_flag,
                "withhold_first_trial_time"    => time(),
                "withhold_first_trial_adminid" => $this->get_account_id()
            ]);
            if($withhold_first_trial_flag==2){
                $this->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[
                    "withhold_final_trial_flag"     => 2,
                    "withhold_final_trial_time"    => time(),
                    // "withhold_final_trial_adminid" => $this->get_account_id()
                ]);

            }

        }elseif($withhold_final_trial_flag>0){
            $check_accept_flag = $this->t_teacher_advance_list->get_withhold_first_trial_flag($start_time,$teacherid);
            if($check_accept_flag != 1){
                return $this->output_err("教研总监还未审批!");
            }

            $this->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[
                "withhold_final_trial_flag"     => $withhold_final_trial_flag,
                "withhold_final_trial_time"    => time(),
                "withhold_final_trial_adminid" => $this->get_account_id()
            ]);

            if($withhold_final_trial_flag==1){
                $withhold_money = $this->t_teacher_advance_list->get_withhold_money($start_time,$teacherid);
                if ( \App\Helper\Utils::check_env_is_local() || \App\Helper\Utils::check_env_is_test() ){
                    $month_start = strtotime(date("Y-m-d",time()));
                    for($i=4;$i<7;$i++){
                        $month = strtotime(date("Y-m-d",strtotime("+$i months",$start_time)-86400)." 10:00");
                        $st = strtotime("+$i months",$start_time);
                        if($st>=$month_start){                        
                            $this->t_teacher_money_list->row_insert([
                                "teacherid" =>$teacherid,
                                "type"      =>101,
                                "add_time"  =>$month,
                                "money"     => "-$withhold_money",
                                "money_info"=> date("Y-m-d",$month)." 等级不达标扣款"
                            ]);
                        }
                    }
                    $this->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[
                        "withhold_wx_flag"     => 1,
                    ]);

                   
                }

            }
 
        }
        
        return $this->output_succ();
    }

    //一键晋升审批
    public function set_teacher_advance_require_all_2018(){
        $start_time   = $this->get_in_int_val("start_time");
        $agree_flag   = $this->get_in_int_val("agree_flag");
        $acc          = $this->get_account();
        $jim_flag = $this->get_in_int_val("jim_flag",2);       
        $teacher_money_type=6;
        if($agree_flag==1){
            if($acc=="江敏" || $jim_flag==1){
                //更新所有未处理的申请
                $this->t_teacher_advance_list->update_first_advance_deal_info_all(1,$this->get_account_id(),time(),$start_time,$teacher_money_type);
                
            }elseif($acc=="ted" || $jim_flag==2){
                $this->t_teacher_advance_list->update_second_advance_deal_info_all(1,$this->get_account_id(),time(),$start_time,$teacher_money_type);
                //发送微信/邮件推送
                $job = new \App\Jobs\SendAdvanceTeacherWxEmail($start_time,$teacher_money_type,1);
                dispatch($job);
            }
        }elseif($agree_flag==2){
            if($acc=="江敏" || $jim_flag==1){
                $this->t_teacher_advance_list->update_first_advance_deal_info_all(2,$this->get_account_id(),time(),$start_time,$teacher_money_type); 

            }elseif($acc=="ted" || $jim_flag==2){
                $this->t_teacher_advance_list->update_second_advance_deal_info_all(2,$this->get_account_id(),time(),$start_time,$teacher_money_type);
            }

        }
        return $this->output_succ();

    }

    //一键扣款审批
    public function set_teacher_withhold_require_all_2018(){
        $start_time   = $this->get_in_int_val("start_time");
        $agree_flag   = $this->get_in_int_val("agree_flag");
        $acc          = $this->get_account();
        $jim_flag = $this->get_in_int_val("jim_flag",2);
        $teacher_money_type=6;
        if($agree_flag==1){
            if($acc=="江敏" || $jim_flag==1){
                //更新所有未处理的申请
                $this->t_teacher_advance_list->update_first_withhold_deal_info_all(1,$this->get_account_id(),time(),$start_time,$teacher_money_type);
                
            }elseif($acc=="ted" || $jim_flag==2){
                $this->t_teacher_advance_list->update_second_withhold_deal_info_all(1,$this->get_account_id(),time(),$start_time,$teacher_money_type); 
                //老师工资扣款处理
                $job = new \App\Jobs\SendAdvanceTeacherWxEmail($start_time,$teacher_money_type,2);
                dispatch($job);
            }
        }elseif($agree_flag==2){
            if($acc=="江敏" || $jim_flag==1){
                $this->t_teacher_advance_list->update_first_withhold_deal_info_all(2,$this->get_account_id(),time(),$start_time,$teacher_money_type); 
            }elseif($acc=="ted" || $jim_flag==2){
                $this->t_teacher_advance_list->update_second_withhold_deal_info_all(2,$this->get_account_id(),time(),$start_time,$teacher_money_type);
            }

        }
        return $this->output_succ();

    }



    //新版刷新数据
    public function update_teacher_advance_info_all(){
        $start_time = $this->get_in_int_val("start_time");
        $teacher_money_type = $this->get_in_int_val("teacher_money_type");
        $start_time =strtotime("2017-10-01");
        $end_time = strtotime("+3 months",$start_time);
        $ret_info = $this->t_teacher_advance_list->get_info_by_teacher_money_type($start_time,$teacher_money_type);
        dd($ret_info);

        $tea_arr=[];
        foreach($ret_info as $val){
            $tea_arr[]=$val["teacherid"];
        }

        // $test_person_num        = $this->t_lesson_info->get_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);
        // $kk_test_person_num     = $this->t_lesson_info->get_kk_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);
        // $change_test_person_num = $this->t_lesson_info->get_change_teacher_test_person_num_list(
        //     $start_time,$end_time,-1,-1,$tea_arr);
        // $teacher_record_score = $this->t_teacher_record_list->get_test_lesson_record_score($start_time,$end_time,$tea_arr);
        foreach($ret_info as &$item){
            $teacherid = $item["teacherid"];
            $item["level"]=$item["real_level"];
            $item["lesson_count"] = $item["lesson_count"]/100;
            $item["lesson_count_score"] = $this->get_advance_score_by_num( $item["lesson_count"],1);//课耗得分
            $item["stu_num_score"]= $this->get_advance_score_by_num( $item["stu_num"],4);//常规学生签单得分
           
            // $item["cc_test_num"]    = isset($test_person_num[$teacherid])?$test_person_num[$teacherid]["person_num"]:0;
            // $item["cc_order_num"]   = isset($test_person_num[$teacherid])?$test_person_num[$teacherid]["have_order"]:0;
            $item["cc_order_score"]= $this->get_advance_score_by_num( $item["cc_order_num"],2);//cc签单数得分

            // $item["other_test_num"] = (isset($kk_test_person_num[$teacherid])?$kk_test_person_num[$teacherid]["kk_num"]:0)+(isset($change_test_person_num[$teacherid])?$change_test_person_num[$teacherid]["change_num"]:0);
            // $item["other_order_num"] = (isset($kk_test_person_num[$teacherid])?$kk_test_person_num[$teacherid]["kk_order"]:0)+(isset($change_test_person_num[$teacherid])?$change_test_person_num[$teacherid]["change_order"]:0);
            $item["other_order_score"]= $this->get_advance_score_by_num( $item["other_order_num"],3);//cr签单得分

          
            // $item["record_num"] = isset($teacher_record_score[$teacherid])?$teacher_record_score[$teacherid]["num"]:0;
            // $item["record_score"] = isset($teacher_record_score[$teacherid])?$teacher_record_score[$teacherid]["score"]:0;
            // $item["record_score_avg"] = !empty($item["record_num"])?round($item["record_score"]/$item["record_num"],1):0;
            $item["record_final_score"]= $this->get_advance_score_by_num( $item["record_score_avg"],5);//教学质量得分

            $order_score = $item["cc_order_score"]+ $item["other_order_score"];//签单总分
            if($order_score>=10){
                $order_score=10;
            }
            $item["total_score"] =$item["lesson_count_score"]+$item["record_final_score"]+$order_score+ $item["stu_num_score"];//总得分
          
            $item["hand_flag"]=0;          
            $exists = $this->t_teacher_advance_list->field_get_list_2($start_time,$teacherid,"teacherid");
            if(!$exists){
                $this->t_teacher_advance_list->row_insert([
                    "start_time" =>$start_time,
                    "teacherid"  =>$teacherid,
                    "level_before"=>$item["level"],
                    // "lesson_count"=>$item["lesson_count"]*100,
                    "lesson_count_score"=>$item["lesson_count_score"],
                    // "cc_test_num"=>$item["cc_test_num"],
                    // "cc_order_num" =>$item["cc_order_num"],
                    // "cc_order_per" =>$item["cc_order_per"],
                    "cc_order_score" =>$item["cc_order_score"],
                    // "other_test_num"=>$item["other_test_num"],
                    // "other_order_num" =>$item["other_order_num"],
                    // "other_order_per" =>$item["other_order_per"],
                    "other_order_score" =>$item["other_order_score"],
                    "record_final_score"=>$item["record_final_score"],
                    // "record_score_avg" =>$item["record_score_avg"],
                    // "record_num"     =>$item["record_num"],
                    // "is_refund"      =>$item["is_refund"],
                    "total_score"    =>$item["total_score"],
                    // "teacher_money_type"=>$item["teacher_money_type"],
                    // "stu_num"        =>$item["stu_num"],
                    "stu_num_score"  =>$item["stu_num_score"]
                ]);

            }else{
                // $this->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[
                //     "level_before"=>$item["level"],
                //     "lesson_count"=>$item["lesson_count"]*100,
                //     "lesson_count_score"=>$item["lesson_count_score"],
                //     "cc_test_num"=>$item["cc_test_num"],
                //     "cc_order_num" =>$item["cc_order_num"],
                //     "cc_order_per" =>$item["cc_order_per"],
                //     "cc_order_score" =>$item["cc_order_score"],
                //     "other_test_num"=>$item["other_test_num"],
                //     "other_order_num" =>$item["other_order_num"],
                //     "other_order_per" =>$item["other_order_per"],
                //     "other_order_score" =>$item["other_order_score"],
                //     "record_final_score"=>$item["record_final_score"],
                //     "record_score_avg" =>$item["record_score_avg"],
                //     "record_num"     =>$item["record_num"],
                //     "is_refund"      =>$item["is_refund"],
                //     "total_score"    =>$item["total_score"],
                //     "teacher_money_type"=>$item["teacher_money_type"],
                //     "stu_num"        =>$item["stu_num"],
                //     "stu_num_score"  =>$item["stu_num_score"]
                // ]);

            }

        }


    }






}
