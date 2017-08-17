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

        $teacher_money_type = $this->get_in_int_val("teacher_money_type",0);
        $level              = $this->get_in_int_val("level",-1);

        $tea_list = $this->t_teacher_info->get_teacher_simulate_list(
            $start_time,$end_time,$teacher_money_type,$level
        );

        $list        = [];
        $reward_list = [];
        foreach($tea_list['list'] as $val){
            $teacherid = $val['teacherid'];
            \App\Helper\Utils::check_isset_data($list[$teacherid],[],0);
            $tea_arr              = $list[$teacherid];
            $tea_arr["teacherid"] = $teacherid;

            $check_type = \App\Helper\Utils::check_teacher_money_type($val['teacher_money_type'],$val['teacher_type']);
            if($check_type==2){
                if(!isset($reward_list[$teacherid]['already_lesson_count'])){
                    $already_lesson_count=$this->get_already_lesson_count(
                        $start_time,$end_time,$val['teacher_money_type'],$val['teacher_type']
                    );
                }
                $reward_list[$teacherid]['already_lesson_count']          = $already_lesson_count;
                $reward_list[$teacherid]['already_lesson_count_simulate'] = $already_lesson_count;
            }else{
                $already_lesson_count=$val['already_lesson_count'];
            }

            if(!isset($reward_list[$teacherid]['already_lesson_count_simulate'])){
                $already_lesson_count_simulate = $this->get_already_lesson_count(
                    $start_time,$end_time,E\Eteacher_money_type::V_7,$val['teacher_type']
                );
                $reward_list[$teacherid]['already_lesson_count_simulate']=$already_lesson_count_simulate;
            }

            $reward = \App\Helper\Utils::get_teacher_lesson_money($val['type'],$already_lesson_count);
            $reward_simulate = \App\Helper\Utils::get_teacher_lesson_money($val['type_simulate'],$already_lesson_count_simulate);

            \App\Helper\Utils::check_isset_data($tea_arr['money'],$val['money']);
            \App\Helper\Utils::check_isset_data($tea_arr['money_simulate'],$val['money_simulate']);
            \App\Helper\Utils::check_isset_data($tea_arr['reward'],$reward);
            \App\Helper\Utils::check_isset_data($tea_arr['reward_simulate'],$reward_simulate);
            \App\Helper\Utils::check_isset_data($tea_arr['lesson_price'],$val['lesson_price']);
            \App\Helper\Utils::check_isset_data($tea_arr['lesson_count'],$val['lesson_count']);

        }

        return $this->pageView(__METHOD__,$tea_list);
    }


}