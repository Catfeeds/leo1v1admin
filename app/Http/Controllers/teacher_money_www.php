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

        $lesson_list = $this->t_lesson_info->get_lesson_list_for_wages($teacherid,$begin_time,$end_time);
        $list      = [];
        $check_num = [];
        foreach($lesson_list as $val){
            $check_type           = \App\Helper\Utils::check_teacher_money_type($val['teacher_money_type'],$teacher_type);
            $already_lesson_count = $check_type!=2?$val['already_lesson_count']:$last_lesson_count;
            $lesson_count         = $val['confirm_flag']!=2?($val['lesson_count']/100):0;
            $month_key            = date("Y-m",$l_val['lesson_start']);

            if($val['lesson_type'] != 2){
                $val['money']       = \App\Helper\Utils::get_teacher_base_money($teacherid,$val);
                $val['lesson_base'] = $val['money']*$lesson_count;
                $lesson_reward = \App\Helper\Utils::get_teacher_lesson_money($val['type'],$already_lesson_count);
                $list_lesson_key = "normal_lesson";
            }else{
                $val['lesson_base'] = \App\Helper\Utils::get_trial_base_price(
                    $teacher_money_type,$val['teacher_type'],$val['lesson_start']
                );
                $lesson_reward   = "0";
                $list_lesson_key = "trial_lesson";
            }
            $lesson_money = $val['lesson_base']+$lesson_reward;

            $this->get_lesson_cost_info($val,$check_num);
            $lesson_time = \App\Helper\Utils::get_lesson_time($val['lesson_start'],$val['lesson_end']);
            $lesson_arr = [
                "type"       => "--",
                "name"       => $val['stu_nick'],
                "time"       => $lesson_time,
                "state_info" => $val['lesson_cost_info'],
                "cost"       => $val['lesson_cost'],
                "money"      => $lesson_money,
            ];
            $list[$month_key][$list_lesson_key][]=$lesson_arr;
        }

        $reward_list = $this->t_teacher_money_list->get_teacher_honor_money_list($teacherid,$begin_time,$end_time);
        foreach($reward_list as $r_val){
            $month_key = date("Y-m",$r_val['add_time']);
            $reward_arr = [
            ];
            switch($r_val['type']){
            case E\Ereward_type::V_6:

            case E\Ereward_type::V_6:
            }
        }




    }

    private function get_lesson_cost_info(&$val,&$check_num){
        $lesson_all_cost = 0;
        $lesson_info     = "";
        $deduct_type = E\Elesson_deduct::$s2v_map;
        $deduct_info = E\Elesson_deduct::$desc_map;
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
                    $lesson_info     = "课前４小时内取消上课！";
                }else{
                    $change_num++;
                    $lesson_info     = "本月第".$change_num."次换课";
                    $lesson_all_cost = 0;
                }
            }
        }else{
            $lesson_cost = $teacher_money['lesson_cost']/100;
            foreach($deduct_type as $key=>$item){
                if($val['deduct_change_class']==0){
                    if($val[$key]>0){
                        if($key=="deduct_come_late" && $late_num<3){
                            $late_num++;
                        }else{
                            $lesson_all_cost += $lesson_cost;
                            $lesson_info.=$deduct_info[$item]."/";
                        }
                    }
                }
            }
        }

        $val['lesson_cost']      = $lesson_all_cost;
        $val['lesson_cost_info'] = $lesson_info;
        $check_num[$month_key]['change_num'] = $change_num;
        $check_num[$month_key]['late_num']   = $late_num;
    }

}