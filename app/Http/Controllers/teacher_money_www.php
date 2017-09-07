<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie;

class teacher_money_www extends Controller
{
    use CacheNick;
    var $teacher_money;
    var $late_num   = 0;
    var $change_num = 0;

    public function __construct(){
        $this->teacher_money = \App\Helper\Config::get_config("teacher_money");
    }

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

            \App\Helper\Utils::check_isset_data($list[$month_key]['试听课程'],[],0);

            if($val['lesson_type'] != 2){
                $val['money']       = \App\Helper\Utils::get_teacher_base_money($teacherid,$val);
                $val['lesson_base'] = $val['money']*$lesson_count;
                $list[$i]['lesson_normal'] += $val['lesson_base'];
                $reward = \App\Helper\Utils::get_teacher_lesson_money($val['type'],$already_lesson_count);
            }else{
                $val['lesson_base'] = \App\Helper\Utils::get_trial_base_price(
                    $teacher_money_type,$val['teacher_type'],$val['lesson_start']
                );
                $list[$i]['lesson_trial'] += $val['lesson_base'];
                $reward = "0";
            }
            $val['lesson_full_reward'] = 0;
            $val['lesson_reward']      = $reward*$lesson_count+$val['lesson_full_reward'];

            $this->get_lesson_cost_info($val);
            $lesson_price = $val['lesson_base']+$val['lesson_reward']-$val['lesson_cost'];

            $list[$i]['lesson_price']       += $lesson_price;
            $list[$i]['lesson_reward']      += $val['lesson_reward'];
            $list[$i]['lesson_cost']        += $val['lesson_cost'];
            $list[$i]['lesson_cost_normal'] += $val['lesson_cost_normal'];
            $list[$i]['lesson_total']       += $lesson_count;
            $list[$i]['lesson_full_reward'] += $val['lesson_full_reward'];
        }

    }




}