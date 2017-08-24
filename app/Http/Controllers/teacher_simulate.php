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

    var $level_simulate_count_key = "level_simulate_count";
    var $all_money_count_key      = "all_money_count";
    var $has_month_key            = "has_month";
    var $already_lesson_count_key = "already_lesson_count";
    var $teacher_ref_rate_key     = "teacher_ref_rate";

    public function new_teacher_money_list(){
        $this->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range("2017-7-1",0,0,null,3);

        $teacher_id         = $this->get_in_int_val("teacher_id",-1);
        $teacher_money_type = $this->get_in_int_val("teacher_money_type",0);
        $level              = $this->get_in_int_val("level",-1);
        $acc                = $this->get_account();

        $tea_list = $this->t_teacher_info->get_teacher_simulate_list(
            $start_time,$end_time,$teacher_money_type,$level,$teacher_id
        );
        $list                      = [];
        $teacher_money_type_list   = [];
        $all_money                 = 0;
        $all_lesson_price          = 0;
        $all_money_simulate        = 0;
        $all_lesson_price_simulate = 0;
        $already_lesson_count_list = [];
        $teacher_ref_rate_list     = \App\Helper\Utils::redis(E\Eredis::V_GET,$this->teacher_ref_rate_key,[],true);
        if($teacher_ref_rate_list===null){
            
        }
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

            $month_key = date("Y-m",$val['lesson_start']);
            $key = "already_lesson_count_".$month_key."_".$teacherid;
            if(!isset($already_lesson_count_list[$key])){
                $already_lesson_count_simulate = Redis::get($key);
                if($already_lesson_count_simulate === null){
                    $last_end_time   = strtotime(date("Y-m-01",$val['lesson_start']));
                    $last_start_time = strtotime("-1 month",$last_end_time);
                    $already_lesson_count_simulate = $this->get_already_lesson_count(
                        $start_time,$end_time,$teacherid,$val['teacher_money_type']
                    );
                    Redis::set($key,$already_lesson_count_simulate);
                }
                $already_lesson_count_list[$key] = $already_lesson_count_simulate;
            }else{
                $already_lesson_count_simulate = $already_lesson_count_list[$key];
            }

            $check_type = \App\Helper\Utils::check_teacher_money_type($val['teacher_money_type'],$val['teacher_type']);
            if($check_type==2){
                $already_lesson_count = $already_lesson_count_simulate;
            }else{
                $already_lesson_count = $val['already_lesson_count'];
            }

            $reward           = \App\Helper\Utils::get_teacher_lesson_money_simulate($val['type'],$already_lesson_count);
            $reward_simulate  = \App\Helper\Utils::get_teacher_lesson_money_simulate($val['type_simulate'],$already_lesson_count_simulate);
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

        $level_list = json_decode(Redis::get($this->level_simulate_count_key),true);

        $all_money_different        = $all_money_simulate-$all_money;
        $all_lesson_price_different = $all_lesson_price_simulate-$all_lesson_price;

        $show_data = [
            "all_money"                  => round($all_money,2),
            "all_money_simulate"         => round($all_money_simulate,2),
            "all_lesson_price"           => round($all_lesson_price,2),
            "all_lesson_price_simulate"  => round($all_lesson_price_simulate,2),
            "all_money_different"        => round($all_money_different,2),
            "all_lesson_price_different" => round($all_lesson_price_different,2),
            "level_list"                 => $level_list,
            "acc"                        => $acc,
            "start_time"                 => $start_time,
        ];

        $this->check_month_redis_key($show_data);
        $final_money_list = json_decode(Redis::get($this->all_money_count_key),true);
        $show_data["final_money"] = $final_money_list;
        $list = \App\Helper\Utils::list_to_page_info($list);
        return $this->pageView(__METHOD__,$list,$show_data);
    }

    /**
     * 获取课程的模拟收入
     */
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
            $args=[
                "from_test_lesson_id"=>0
            ];
            $price_arr_simulate = \App\OrderPrice\order_price_base::get_price_ex_cur(
                $info['competition_flag'],$has_promotion,$info['contract_type'],$info['grade'],$lesson_total,0,$args
            );
            $per_price_simulate = $price_arr_simulate['discount_price']/$lesson_total;
            $lesson_price_simulate=$info['lesson_count']*$per_price_simulate/100;
        }else{
            $lesson_price_simulate=0;
        }
        return round($lesson_price_simulate,2);
    }

    /**
     * 更新老师的模拟信息 
     */
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

    /**
     * 更新redis中模拟等级的分布列表
     */
    public function get_level_simulate_list(){
        $type = $this->get_in_int_val("type");

        $level_list  = $this->t_teacher_info->get_level_simulate_list();
        $level_count = [];
        $level_all   = 0;
        if(!empty($level_list)){
            foreach($level_list as $val){
                $level_all += $val['level_num'];
                E\Enew_level::set_item_value_str($val,"level_simulate");
                \App\Helper\Utils::check_isset_data($level_count[$val['level_simulate_str']]['level_num'],$val['level_num'],0);
                \App\Helper\Utils::check_isset_data($level_count["all"]['level_num'],$val['level_num']);
            }
            foreach($level_count as &$c_val){
                $c_val['level_per'] = round($c_val['level_num']/$level_all,4);
            }
        }

        Redis::set($this->level_simulate_count_key,json_encode($level_count));
        if($type==1){
            \App\Helper\Utils::debug_to_html( $level_count );
        }

        return $this->output_succ();
    }

    /**
     * 更新redis中已结算工资月份
     */
    public function check_month_redis_key($data){
        $month_key = date("Y-m",$data['start_time']);
        $now_month_key = date("Y-m",time());
        $check_time    = strtotime("2017-1-1");
        if($month_key==$now_month_key || $data['start_time']<$check_time){
            return true;
        }

        $has_month = json_decode(Redis::get($this->has_month_key),true);
        if(!isset($has_month)){
            $has_month   = [];
            $has_month[] = $month_key;
            $all_money   = $data;
        }else{
            $has_month_flip = array_flip($has_month);
            $all_money = json_decode(Redis::get($this->all_money_count_key),true);

            if(!array_key_exists($month_key,$has_month_flip)){
                $has_month[] = $month_key;
                $all_money['all_money']                 += $data['all_money'];
                $all_money['all_money_simulate']        += $data['all_money_simulate'];
                $all_money['all_lesson_price']          += $data['all_lesson_price'];
                $all_money['all_lesson_price_simulate'] += $data['all_lesson_price_simulate'];
                $all_money['all_money_different']        = $all_money['all_money_simulate']-$all_money['all_money'];
                $all_money['all_lesson_price_different'] = $all_money['all_lesson_price_simulate']-$all_money['all_lesson_price'];
                unset($all_money['start_time']);
                unset($all_money['acc']);
                unset($all_money['level_list']);
            }
        }

        Redis::set($this->has_month_key,json_encode($has_month));
        Redis::set($this->all_money_count_key,json_encode($all_money));
    }

    public function del_redis_simulate_money(){
        Redis::del($this->has_month_key);
        Redis::del($this->all_money_count_key);
        return $this->output_succ();
    }

    public function set_teacher_ref_rate(){
        $start_time = strtotime("2017-1-1");
        $end_date   = strtotime("2017-8-1");
        if($teacher_ref_type==1){
            $teacher_ref_rate = \App\Helper\Config::get_config_2("teacher_ref_rate",$teacher_ref_type);
        }elseif($teacher_ref_type!=0){
            $teacher_ref_num  = $this->t_teacher_info->get_teacher_ref_num($start_time,$teacher_ref_type);
            $teacher_ref_rate = \App\Helper\Utils::get_teacher_ref_rate($teacher_ref_num);
        }
    }
}
