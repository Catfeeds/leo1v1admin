<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;
use Illuminate\Support\Facades\Cookie ;
use Illuminate\Support\Facades\Redis ;

class teacher_simulate extends Controller
{
    use CacheNick;
    use TeaPower;

    public function new_teacher_money_list(){
        $this->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,3);

        $teacher_id         = $this->get_in_int_val("teacher_id",-1);
        $teacher_money_type = $this->get_in_int_val("teacher_money_type",0);
        $level              = $this->get_in_int_val("level",-1);
        $acc                = $this->get_account();

        $tea_list = $this->t_teacher_info->get_teacher_simulate_list(
            $start_time,$end_time,$teacher_money_type,$level,$teacher_id
        );

        $list                      = [];
        $reward_list               = [];
        $all_money                 = 0;
        $all_lesson_price          = 0;
        $all_money_simulate        = 0;
        $all_lesson_price_simulate = 0;
        foreach($tea_list as $val){
            $teacherid = $val['teacherid'];
            \App\Helper\Utils::check_isset_data($list[$teacherid],[],0);
            $tea_arr                   = $list[$teacherid];
            $tea_arr["teacherid"]      = $teacherid;
            $tea_arr["level_simulate"] = $val["level_simulate"];

            E\Eteacher_money_type::set_item_value_str($val);
            E\Eteacher_money_type::set_item_value_str($val,"teacher_money_type_simulate");
            E\Elevel::set_item_value_str($val);
            E\Enew_level::set_item_value_str($val,"level_simulate");

            \App\Helper\Utils::check_isset_data($tea_arr['realname'],$val['realname'],0);
            \App\Helper\Utils::check_isset_data($tea_arr['teacher_money_type_str'],$val['teacher_money_type_str'],0);
            \App\Helper\Utils::check_isset_data($tea_arr['teacher_money_type_simulate_str'],$val['teacher_money_type_simulate_str'],0);
            \App\Helper\Utils::check_isset_data($tea_arr['level_str'],$val['level_str'],0);
            \App\Helper\Utils::check_isset_data($tea_arr['level_simulate_str'],$val['level_simulate_str'],0);


            $month_key  = date("Y-m",$val['lesson_start']);
            $key = "already_lesson_count_".$month_key."_".$teacherid;
            $already_lesson_count_simulate = Redis::get($key);
            if($already_lesson_count_simulate === null){
                $last_end_time   = strtotime(date("Y-m-01",$val['lesson_start']));
                $last_start_time = strtotime("-1 month",$last_end_time);
                $already_lesson_count_simulate = $this->get_already_lesson_count(
                    $start_time,$end_time,$teacherid,$val['teacher_money_type']
                );
                Redis::set($key,$already_lesson_count_simulate);
            }

            $check_type = \App\Helper\Utils::check_teacher_money_type($val['teacher_money_type'],$val['teacher_type']);
            if($check_type==2){
                $already_lesson_count = $already_lesson_count_simulate;
            }else{
                $already_lesson_count = $val['already_lesson_count'];
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
            }else{
                $lesson_price_simulate = 0;
            }

            \App\Helper\Utils::check_isset_data($tea_arr['money'],round($money,2));
            \App\Helper\Utils::check_isset_data($tea_arr['money_simulate'],round($money_simulate,2));
            \App\Helper\Utils::check_isset_data($tea_arr['reward'],round($reward,2));
            \App\Helper\Utils::check_isset_data($tea_arr['reward_simulate'],round($reward_simulate,2));
            \App\Helper\Utils::check_isset_data($tea_arr['lesson_price'],round($lesson_price,2));
            \App\Helper\Utils::check_isset_data($tea_arr['lesson_count'],round($lesson_count,2));
            \App\Helper\Utils::check_isset_data($tea_arr['lesson_price_simulate'],round($lesson_price_simulate,2));

            $all_money                 += $money;
            $all_lesson_price          += $lesson_price;
            $all_money_simulate        += $money_simulate;
            $all_lesson_price_simulate += $lesson_price_simulate;

            $list[$teacherid] = $tea_arr;
        }

        foreach($list as &$l_val){
            $l_val['money_different']        = round(($l_val['money_simulate']-$l_val['money']),2);
            $l_val['lesson_price_different'] = round(($l_val['lesson_price_simulate']-$l_val['lesson_price']),2);
        }

        $level_list = json_decode(Redis::get("level_simulate_count"),true);

        $all_money_different        = $all_money_simulate-$all_money;
        $all_lesson_price_different = $all_lesson_price_simulate-$all_lesson_price;
        $list = \App\Helper\Utils::list_to_page_info($list);
        return $this->pageView(__METHOD__,$list,[
            "all_money"                  => round($all_money,2),
            "all_money_simulate"         => round($all_money_simulate,2),
            "all_lesson_price"           => round($all_lesson_price,2),
            "all_lesson_price_simulate"  => round($all_lesson_price_simulate,2),
            "all_money_different"        => round($all_money_different,2),
            "all_lesson_price_different" => round($all_lesson_price_different,2),
            "level_list"                 => $level_list,
            "acc"                        => $acc,
        ]);
    }

    public function get_lesson_price_simulate($info){
        $lesson_total  = $info['lesson_total']*$info['default_lesson_count']/100;
        if($lesson_total>0){
            $has_promotion = 1;
            if($info['price'] < $info['discount_price']){
                $has_promotion = 2;
            }
            switch($info['grade']){
            case 100:
                $info['grade']=101;break;
            case 200:
                $info['grade']=201;break;
            case 300:
                $info['grade']=301;break;
            default:
                $info['grade']=$info['grade'];break;
            }
            $price_arr_simulate = \App\OrderPrice\order_price_base::get_price_ex_cur(
                $info['competition_flag'],$has_promotion,$info['contract_type'],$info['grade'],$lesson_total,0
            );
            $per_price_simulate = $price_arr_simulate['discount_price']/$lesson_total;
            $lesson_price_simulate=$info['lesson_count']*$per_price_simulate/100;
        }else{
            $lesson_price_simulate=0;
        }
        return round($lesson_price_simulate,2);
    }

    public function get_teacher_already_lesson_count($val){
        $month = date("Y-m",$val['lesson_start']);
        $key = $teacherid."_".$month;

    }


    public function update_teacher_simulate_info(){
        $teacherid      = $this->get_in_int_val("teacherid");
        $level_simulate = $this->get_in_int_val("level_simulate");
        if(!$teacherid){
            return $this->output_err("老师id出错！");
        }

        $ret= $this->t_teacher_info->field_update_list($teacherid,[
            "level_simulate"=>$level_simulate
        ]);

        if(!$ret){
            return $this->output_err("更新失败！");
        }
        return $this->output_succ();
    }

    public function get_level_simulate_list(){
        $type = $this->get_in_int_val("type");

        $level_list = $this->t_teacher_info->get_level_simulate_list();
        $level_count = [];
        $level_all = 0;
        if(!empty($level_list)){
            foreach($level_list as $val){
                $level_all += $val['level_num'];
                E\Enew_level::set_item_value_str($val,"level_simulate");
                \App\Helper\Utils::check_isset_data($level_count[$val['level_simulate_str']]['level_num'],$val['level_num'],0);
            }
            foreach($level_count as &$c_val){
                $c_val['level_per'] = round($c_val['level_num']/$level_all,2);
            }
        }

        if($type==1){
            \App\Helper\Utils::debug_to_html( $level_count );
        }

        $key = "level_simulate_count";
        Redis::set($key,json_encode($level_count));

        return $this->output_succ();
    }

}
