<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie;

class teacher_money_www extends Controller
{
    use CacheNick;

    public function get_teacher_money_total_list(){
        $teacherid = $this->get_login_teacher();

        $now_date   = date("Y-m-01",time());
        $begin_time = strtotime("-1 year ",$now_date);
        $end_time   = strtotime("+1 month",strtotime($now_date));
        $first_lesson_time = $this->t_lesson_info_b3->get_first_lesson_time($teacherid);
        if($begin_time<$first_lesson_time){
            $begin_time = $first_lesson_time;
        }

        $reward_list = $this->t_teacher_money_list->get_teacher_honor_money_list($teacherid,$begin_time,$end_time);
        $lesson_list = $this->t_lesson_info->get_lesson_list_for_wages($teacherid,$begin_time,$end_time);
        $list   = [];
        foreach($lesson_list as $val){
            $check_type           = \App\Helper\Utils::check_teacher_money_type($val['teacher_money_type'],$teacher_type);
            $already_lesson_count = $check_type!=2?$val['already_lesson_count']:$last_lesson_count;
            $lesson_count         = $val['confirm_flag']!=2?($val['lesson_count']/100):0;
            $month_key            = date("Y-m",$l_val['lesson_start']);

            if($val['lesson_type'] != 2){
                $val['money']       = \App\Helper\Utils::get_teacher_base_money($teacherid,$val);
                $val['lesson_base'] = $val['money']*$lesson_count;
                $reward = \App\Helper\Utils::get_teacher_lesson_money($val['type'],$already_lesson_count);
                $list[$month_key]['lesson_normal'] = $val['lesson_base'];
            }else{
                $val['lesson_base'] = \App\Helper\Utils::get_trial_base_price(
                    $teacher_money_type,$val['teacher_type'],$val['lesson_start']
                );
                $list[$i]['lesson_trial'] += $val['lesson_base'];
                $reward = "0";
            }

            $this->get_lesson_cost_info($val);
            $lesson_time = \App\Helper\Utils::get_lesson_time($val['lesson_start'],$val['lesson_end']);
            $lesson_arr = [
                "name" => $val['stu_nick'],
                "time" => $lesson_time,
                "state" => $lesson_time,
            ];

        }
    }

    private function get_lesson_cost_info(&$val,&$check_num){
        $lesson_all_cost = 0;
        $deduct_type   = E\Elesson_deduct::$s2v_map;
        $deduct_info   = E\Elesson_deduct::$desc_map;
        $teacher_money = \App\Helper\Config::get_config("teacher_money");
        $month_key     = date("Y-m",$val['lesson_start']);
        \App\Helper\Utils::check_isset_data($check_num[$month_key]['change_num'],0,0);
        \App\Helper\Utils::check_isset_data($check_num[$month_key]['late_num'],0,0);
        $change_num = $check_num[$month_key]['change_num'];
        $late_num   = $check_num[$month_key]['late_num'];

        if($val['confirm_flag']==2 && $val['deduct_change_class']>0){
            if($val['lesson_cancel_reason_type']==21){
                $lesson_all_cost = $teacher_money['lesson_miss_cost']/100;
                $info            = "上课旷课!";
            }elseif(($val['lesson_cancel_reason_type']==2 || $val['lesson_cancel_reason_type']==12)
                    && $val['lesson_cancel_time_type']==1){
                if($change_num>=3){
                    $lesson_all_cost = $teacher_money['lesson_cost']/100;
                    $info            = "课前４小时内取消上课！";
                }else{
                    $change_num++;
                    $info            = "本月第".$change_num."次换课";
                    $lesson_all_cost = 0;
                }
            }
            if(isset($info)){
                $cost_info['type']  = 3;
                $cost_info['money'] = $lesson_all_cost;
                $cost_info['info']  = $info;
                $val['list'][]      = $cost_info;
            }
        }else if($val['fail_greater_4_hour_flag']==0 && ($val['test_lesson_fail_flag']==101 || $val['test_lesson_fail_flag']==102)){
            $cost_info['type']  = 3;
            $cost_info['money'] = 0;
            $cost_info['info']  = "老师原因4小时内取消试听课";
            $val['list'][]      = $cost_info;
        }else{
            $lesson_cost = $teacher_money['lesson_cost']/100;
            $lesson_all_cost = 0;
            foreach($deduct_type as $key=>$item){
                if($val['deduct_change_class']==0){
                    if($val[$key]>0){
                        if($key=="deduct_come_late" && $late_num<3){
                            $late_num++;
                        }else{
                            $cost_info['type']  = 3;
                            $cost_info['money'] = $lesson_cost;
                            $cost_info['info']  = $deduct_info[$item];

                            $lesson_all_cost += $lesson_cost;
                            $val["list"][]    = $cost_info;
                        }
                    }
                }
            }
        }

        if($val['lesson_type']!=2){
            $val['lesson_cost_normal'] = (string)$lesson_all_cost;
        }else{
            $val['lesson_cost_normal'] = "0";
        }
        $val['lesson_cost'] = (string)$lesson_all_cost;
    }


}