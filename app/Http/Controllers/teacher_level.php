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
        $season = ceil((date('n'))/3)-1;//上季度是第几季度
        $start_time = strtotime(date('Y-m-d H:i:s', mktime(0, 0, 0,$season*3-3+1,1,date('Y'))));
        $end_time = strtotime(date('Y-m-d H:i:s', mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'))));
        
        $teacher_money_type       = $this->get_in_int_val("teacher_money_type",1);
        $list = $this->t_teacher_info->get_teacher_info_by_money_type($teacher_money_type,$start_time,$end_time);
        $tea_list=[];
        foreach($list as $val){
            $tea_list[] = $val["teacherid"];
        }
        $page_info = $this->get_in_page_info();
        $ret_info = $this->t_teacher_info->get_teacher_level_info($page_info,$tea_list);
        $tea_arr=[];
        foreach($ret_info["list"] as $val){
            $tea_arr[]=$val["teacherid"];
        }
        $test_person_num= $this->t_lesson_info->get_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);

        $kk_test_person_num= $this->t_lesson_info->get_kk_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);
        $change_test_person_num= $this->t_lesson_info->get_change_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);

        $teacher_record_score = $this->t_teacher_record_list->get_test_lesson_record_score($start_time,$end_time,$tea_arr);
        $tea_refund_info =$this->get_tea_refund_info($start_time,$end_time,$tea_arr);
        foreach($ret_info["list"] as &$item){
            E\Elevel::set_item_value_str($item,"level");
            $teacherid = $item["teacherid"];
            $item["lesson_count"] = round($list[$teacherid]["lesson_count"]/300,1);
            $item["lesson_count_score"] = $this->get_score_by_lesson_count($item["lesson_count"]);
            $item["cc_test_num"] = isset($test_person_num[$teacherid])?$test_person_num[$teacherid]["person_num"]:0;
            $item["cc_order_num"] = isset($test_person_num[$teacherid])?$test_person_num[$teacherid]["have_order"]:0;
            $item["cc_order_per"] = !empty($item["cc_test_num"])?round($item["cc_order_num"]/$item["cc_test_num"]*100,2):0;
            $item["cc_order_score"] = $this->get_cc_order_score($item["cc_order_num"],$item["cc_order_per"]);
            $item["other_test_num"] = (isset($kk_test_person_num[$teacherid])?$kk_test_person_num[$teacherid]["kk_num"]:0)+(isset($change_test_person_num[$teacherid])?$change_test_person_num[$teacherid]["change_num"]:0);
            $item["other_order_num"] = (isset($kk_test_person_num[$teacherid])?$kk_test_person_num[$teacherid]["kk_order"]:0)+(isset($change_test_person_num[$teacherid])?$change_test_person_num[$teacherid]["change_order"]:0);
            $item["other_order_per"] = !empty($item["other_test_num"])?round($item["other_order_num"]/$item["other_test_num"]*100,2):0;
            $item["other_order_score"] = $this->get_other_order_score($item["other_order_num"],$item["other_order_per"]);
            $item["record_num"] = isset($teacher_record_score[$teacherid])?$teacher_record_score[$teacherid]["num"]:0;
            $item["record_score"] = isset($teacher_record_score[$teacherid])?$teacher_record_score[$teacherid]["score"]:0;
            $item["record_score_avg"] = !empty($item["record_num"])?round($item["record_score"]/$item["record_num"],1):0;
            $item["record_final_score"] = !empty($item["record_num"])?ceil($item["record_score_avg"]*0.2):12;
            $item["is_refund"] = (isset($tea_refund_info[$teacherid]) && $tea_refund_info[$teacherid]>0)?1:0;
            $item["is_refund_str"] = $item["is_refund"]==1?"<font color='red'>有</font>":"无";
            $item["total_score"] = $item["lesson_count_score"]+$item["cc_order_score"]+ $item["other_order_score"]+$item["record_final_score"];
            
        }
        return $this->pageView(__METHOD__,$ret_info);

        //dd($ret_info);

    }

    public function get_other_order_score($num,$per){
        if($num<=0){
            return 5;
        }elseif($per <60){
            return 4;
        }elseif($per >=60 && $per <70){
            return 5;
        }elseif($per >=70 && $per <80){
            return 6;
        }elseif($per >=80 && $per <90){
            return 7;
        }elseif($per >=90 ){
            return 8;
        }


    }

    public function get_cc_order_score($num,$per){
        if($num<=0){
            return 7;
        }elseif($per <15){
            return 6;
        }elseif($per >=15 && $per <20){
            return 7;
        }elseif($per >=20 && $per <25){
            return 8;
        }elseif($per >=25 && $per <30){
            return 9;
        }elseif($per >=30 && $per <35){
            return 10;
        }elseif($per >=35 && $per <40){
            return 11;
        }elseif($per >=40){
            return 12;
        }


    }
    
    public function get_score_by_lesson_count($lesson_count){
        if($lesson_count >=60 && $lesson_count <70){
            return 51;
        }elseif($lesson_count >=70 && $lesson_count <80){
            return 52;
        }elseif($lesson_count >=80 && $lesson_count <90){
            return 53;
        }elseif($lesson_count >=90 && $lesson_count <100){
            return 54;
        }elseif($lesson_count >=100 && $lesson_count <110){
            return 55;
        }elseif($lesson_count >=110 && $lesson_count <120){
            return 56;
        }elseif($lesson_count >=120 && $lesson_count <130){
            return 57;
        }elseif($lesson_count >=130 && $lesson_count <140){
            return 58;
        }elseif($lesson_count >=140 && $lesson_count <150){
            return 59;
        }elseif($lesson_count>=150){
            return 60;
        }else{
            return 0;
        }


    }


}