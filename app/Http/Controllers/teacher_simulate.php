<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;
use Illuminate\Support\Facades\Cookie ;

class teacher_simulate extends Controller
{
    use CacheNick;
    use TeaPower;

    public function new_teacher_money_list(){
        $this->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,3);

        $teacherid          = $this->get_in_int_val("teacherid",-1);
        $teacher_money_type = $this->get_in_int_val("teacher_money_type",0);
        $level              = $this->get_in_int_val("level",-1);

        $tea_list = $this->t_teacher_info->get_teacher_simulate_list(
            $start_time,$end_time,$teacherid,$teacher_money_type,$level
        );

        $list        = [];
        $reward_list = [];
        foreach($tea_list['list'] as $val){
            \App\Helper\Utils::check_isset_data($list[$val['teacherid']],[],0);
            $tea_arr              = $list[$val['teacherid']];
            $tea_arr["teacherid"] = $val['teacherid'];

            $check_type = \App\Helper\Utils::check_teacher_money_type($val['teacher_money_type'],$val['teacher_type']);
            if($check_type==2 ){
                if(!isset($reward_list[$val['teacherid']]['already_lesson_count'])){
                    $last_start_time = strtotime("-1 month",$start_time);
                    $last_end_time   = strtotime("-1 month",$end_time);
                    $already_lesson_count = $this->t_lesson_info->get_teacher_last_month_lesson_count(
                        $val['teacherid'],$last_start_time,$last_end_time
                    );
                    $reward_list[$val["teacherid"]]["already_lesson_count"]=$already_lesson_count;
                }else{
                    
                }
            }
            if(!in_array($val['teacher_money_type'],[0,1,2,3]) && !isset($reward_list[$val['teacherid']]['already_lesson_count'])){
                $last_start_time = strtotime("-1 month",$start_time);
                $last_end_time   = strtotime("-1 month",$end_time);
                $already_lesson_count = $this->t_lesson_info->get_teacher_last_month_lesson_count($teacherid,$start_time,$end_time);
                $reward_list[$val["teacherid"]]["already_lesson_count"]=$already_lesson_count;
            }
            \App\Helper\Utils::check_isset_data($tea_arr['money'],$val['money']);
            \App\Helper\Utils::check_isset_data($tea_arr['money_simulate'],$val['money_simulate']);
            \App\Helper\Utils::check_isset_data($tea_arr['lesson_price'],$val['lesson_price']);
            \App\Helper\Utils::check_isset_data($tea_arr['lesson_count'],$val['lesson_count']);

        }

        return $this->pageView(__METHOD__,$tea_list);
    }

}