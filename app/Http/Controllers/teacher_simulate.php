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
        foreach($tea_list as $val){
            $teacherid = $val['teacherid'];
            \App\Helper\Utils::check_isset_data($list[$teacherid],[],0);
            $tea_arr              = $list[$teacherid];
            $tea_arr["teacherid"] = $teacherid;
            E\Eteacher_money_type::set_item_value_str($val);
            E\Eteacher_money_type::set_item_value_str($val,"teacher_money_type_simulate");
            E\Elevel::set_item_value_str($val);
            E\Enew_level::set_item_value_str($val,"level_simulate");

            \App\Helper\Utils::check_isset_data($tea_arr['realname'],$val['realname'],0);
            \App\Helper\Utils::check_isset_data($tea_arr['teacher_money_type_str'],$val['teacher_money_type_str'],0);
            \App\Helper\Utils::check_isset_data($tea_arr['teacher_money_type_simulate_str'],$val['teacher_money_type_simulate_str'],0);
            \App\Helper\Utils::check_isset_data($tea_arr['level_str'],$val['level_str'],0);
            \App\Helper\Utils::check_isset_data($tea_arr['level_simulate_str'],$val['level_simulate_str'],0);

            $check_type = \App\Helper\Utils::check_teacher_money_type($val['teacher_money_type'],$val['teacher_type']);
            if($check_type==2){
                if(!isset($reward_list[$teacherid]['already_lesson_count'])){
                    $already_lesson_count = $this->get_already_lesson_count(
                        $start_time,$end_time,$teacherid
                    );
                }
                $reward_list[$teacherid]['already_lesson_count']          = $already_lesson_count;
                $reward_list[$teacherid]['already_lesson_count_simulate'] = $already_lesson_count;
            }else{
                $already_lesson_count=$val['already_lesson_count'];
            }

            if(!isset($reward_list[$teacherid]['already_lesson_count_simulate'])){
                $already_lesson_count_simulate = $this->get_already_lesson_count(
                    $start_time,$end_time,$teacherid,$val['teacher_money_type_simulate']
                );
                $reward_list[$teacherid]['already_lesson_count_simulate'] = $already_lesson_count_simulate;
            }else{
                $already_lesson_count_simulate=$reward_list[$teacherid]['already_lesson_count_simulate'];
            }

            $reward           = \App\Helper\Utils::get_teacher_lesson_money($val['type'],$already_lesson_count);
            $reward_simulate  = \App\Helper\Utils::get_teacher_lesson_money($val['type_simulate'],$already_lesson_count_simulate);
            $lesson_count     = $val['lesson_count']/100;
            $reward          *= $lesson_count;
            $reward_simulate *= $lesson_count;
            $money            = $val['money']*$lesson_count+$reward;
            $money_simulate   = $val['money_simulate']*$lesson_count+$reward_simulate;
            $lesson_price     = $val['lesson_price']/100;

            if(in_array($val['contract_type'],[0,3])){
                $lesson_price_simulate = $this->get_lesson_price_simulate($val);
            }

            \App\Helper\Utils::check_isset_data($tea_arr['money'],$money);
            \App\Helper\Utils::check_isset_data($tea_arr['money_simulate'],$money_simulate);
            \App\Helper\Utils::check_isset_data($tea_arr['reward'],$reward);
            \App\Helper\Utils::check_isset_data($tea_arr['reward_simulate'],$reward_simulate);
            \App\Helper\Utils::check_isset_data($tea_arr['lesson_price'],$lesson_price);
            \App\Helper\Utils::check_isset_data($tea_arr['lesson_count'],$lesson_count);
            \App\Helper\Utils::check_isset_data($tea_arr['lesson_price_simulate'],$lesson_price_simulate);

            $list[$teacherid] = $tea_arr;
        }

        $list = \App\Helper\Utils::list_to_page_info($list);
        return $this->pageView(__METHOD__,$list);
    }

    public function get_lesson_price_simulate($info){
        $lesson_total  = $info['lesson_total']*$info['default_lesson_count']/100;
        if($lesson_total>0){
            $has_promotion = 1;
            if($info['price'] < $info['discount_price']){
                $has_promotion = 2;
            }
            $price_arr_simulate = \App\OrderPrice\order_price_base::get_price_ex_cur(
                $info['competition_flag'],$has_promotion,$info['contract_type'],$info['grade'],$lesson_total,0
            );
            $per_price_simulate = $price_arr_simulate['discount_price']/$lesson_total;
            $lesson_price_simulate=$info['lesson_count']*$per_price_simulate/100;
        }else{
            $lesson_price_simulate=0;
        }
        return $lesson_price_simulate;
    }

    public function get_simulate_price(){
        $competition_flag = 0;
        $has_promotion    = 2;
        $contract_type    = 0;
        $grade            = 101;
        $lesson_total     = 450;

        $price = \App\OrderPrice\order_price_base::get_price_ex_cur(
            $competition_flag,$has_promotion,$contract_type,$grade,$lesson_total,0
        );
        var_dump($price);
    }

}
