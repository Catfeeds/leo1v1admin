<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie;

class teacher_money_www extends Controller
{
    use CacheNick;
    use TeaPower;

    public function get_teacher_money_total_list(){
        $teacherid = $this->get_login_teacher();
        // $teacherid = $this->get_in_int_val("teacherid");;
        if(!$teacherid){
            return $this->output_err("老师id出错！");
        }

        $now_date   = date("Y-m-01",time());
        $begin_time = strtotime("-1 year",strtotime($now_date));
        $end_time   = strtotime("+1 month",strtotime($now_date));
        $first_lesson_time = $this->t_lesson_info_b3->get_first_lesson_time($teacherid);
        if($begin_time<$first_lesson_time){
            $begin_time = $first_lesson_time;
        }
        $simple_info = $this->t_teacher_info->get_teacher_info($teacherid);
        $teacher_money_flag = $simple_info['teacher_money_flag'];
        $teacher_type       = $simple_info['teacher_type'];
        $transfer_teacherid = $simple_info['transfer_teacherid'];

        $lesson_list = $this->t_lesson_info->get_lesson_list_for_wages($teacherid,$begin_time,$end_time);
        $list      = [];
        $check_num = [];
        $already_lesson_count_list = [];
        foreach($lesson_list as $val){
            $check_type   = \App\Helper\Utils::check_teacher_money_type($val['teacher_money_type'],$teacher_type);
            $lesson_count = $val['confirm_flag']!=2?($val['lesson_count']/100):0;
            $month_key    = date("Y-m",$val['lesson_start']);
            \App\Helper\Utils::check_isset_data($list[$month_key]["all_money"],0,0);
            \App\Helper\Utils::check_isset_data($list[$month_key]["date"],$month_key,0);

            $key = "already_lesson_count_".$month_key."_".$teacherid;
            if(!isset($already_lesson_count_list[$key])){
                $last_lesson_count = \App\Helper\Common::redis_get($key);
                if($last_lesson_count === null){
                    $last_end_time = strtotime(date("Y-m-01",$val['lesson_start']));
                    $last_start_time = strtotime("-1 month",$last_end_time);
                    $last_lesson_count = $this->get_already_lesson_count(
                        $last_start_time,$last_end_time,$teacherid,$val['teacher_money_type']
                    );
                    \App\Helper\Common::redis_set($key,$last_lesson_count);
                }
                $already_lesson_count_list[$key] = $last_lesson_count;
            }else{
                $last_lesson_count = $already_lesson_count_list[$key];
            }

            $already_lesson_count = $check_type!=2?$val['already_lesson_count']:$last_lesson_count;
            if($val['lesson_type'] != 2){
                $val['money']       = \App\Helper\Utils::get_teacher_base_money($teacherid,$val);
                $val['lesson_base'] = $val['money']*$lesson_count;
                $lesson_reward = \App\Helper\Utils::get_teacher_lesson_money($val['type'],$already_lesson_count);
                $list_lesson_key = "normal_lesson";
            }else{
                $val['lesson_base'] = \App\Helper\Utils::get_trial_base_price(
                    $val['teacher_money_type'],$val['teacher_type'],$val['lesson_start']
                );
                $lesson_reward   = "0";
                $list_lesson_key = "trial_lesson";
            }
            $lesson_money = $val['lesson_base']+$lesson_reward;

            $this->get_lesson_cost_info($val,$check_num);
            $lesson_time = \App\Helper\Utils::get_lesson_time($val['lesson_start'],$val['lesson_end']);
            $lesson_arr = [
                "name"       => $val['stu_nick'],
                "time"       => $lesson_time,
                "state_info" => $val['lesson_cost_info'],
                "cost"       => $val['lesson_cost'],
                "money"      => $lesson_money,
            ];
            $list[$month_key][$list_lesson_key][]=$lesson_arr;
            $list[$month_key]["all_money"] += $lesson_money;
        }

        $reward_list = $this->t_teacher_money_list->get_teacher_honor_money_list($teacherid,$begin_time,$end_time);
        foreach($reward_list as $r_val){
            $month_key = date("Y-m",$r_val['add_time']);
            $add_time  = date("Y-m-d H:i",$r_val['add_time']);
            \App\Helper\Utils::check_isset_data($list[$month_key]["all_money"],0,0);

            $reward_money = $r_val['money']/100;
            $reward_arr = [
                "name"       => E\Ereward_type::get_desc($r_val['type']),
                "time"       => $add_time,
                "state_info" => "",
                "cost"       => "",
                "money"      => $r_val['money']/100,
            ];
            switch($r_val['type']){
            case E\Ereward_type::V_6:
                $reward_arr["name"]=$this->cache_get_teacher_nick($r_val['money_info']);
                $list_reward_key = "reference";
                break;
            case E\Ereward_type::V_1: case E\Ereward_type::V_2: case E\Ereward_type::V_5:
                $list_reward_key = "reward";
                break;
            default:
                $list_reward_key = "compensate";
                break;
            }
            $list[$month_key][$list_reward_key][]  = $reward_arr;
            $list[$month_key]["all_money"]        += $reward_money;
        }

        return $this->output_succ(["list"=>$list]);
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