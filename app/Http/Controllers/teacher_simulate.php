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

    var $level_simulate_count_key   = "level_simulate_count";
    var $all_money_count_key        = "all_money_count";
    var $all_teacher_money_type_key = "all_teacher_money_type_count";
    var $has_month_key              = "has_month";
    var $teacher_money_type_key     = "teacher_money_type_count";

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
        $teacher_money_type_list   = json_decode(Redis::get($this->teacher_money_type_key),true);
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

            $reward          = \App\Helper\Utils::get_teacher_lesson_money($val['type'],$already_lesson_count);
            $reward_simulate = \App\Helper\Utils::get_teacher_lesson_money($val['type_simulate'],$already_lesson_count_simulate);
            $lesson_count    = round($val['lesson_count']/100,2);
            $reward          = round($reward*$lesson_count,2);
            $reward_simulate = round($reward_simulate*$lesson_count,2);
            $money           = round(($val['money']*$lesson_count+$reward),2);
            $money_simulate  = round(($val['money_simulate']*$lesson_count+$reward_simulate),2);
            $lesson_price    = round(($val['lesson_price']/100),2);
            if(in_array($val['contract_type'],[0,3])){
                $lesson_price_simulate = round($this->get_lesson_price_simulate($val),2);
            }else{
                $lesson_price_simulate = 0;
            }

            \App\Helper\Utils::check_isset_data($tea_arr['money'],$money);
            \App\Helper\Utils::check_isset_data($tea_arr['money_simulate'],$money_simulate);
            \App\Helper\Utils::check_isset_data($tea_arr['reward'],$reward);
            \App\Helper\Utils::check_isset_data($tea_arr['reward_simulate'],$reward_simulate);
            \App\Helper\Utils::check_isset_data($tea_arr['lesson_price'],$lesson_price);
            \App\Helper\Utils::check_isset_data($tea_arr['lesson_count'],$lesson_count);
            \App\Helper\Utils::check_isset_data($tea_arr['lesson_price_simulate'],$lesson_price_simulate);

            // 工资类型的统计
            \App\Helper\Utils::check_isset_data($teacher_money_type_list[$month_key][$val['teacher_money_type_str']],[],0);
            $money_type_arr = $teacher_money_type_list[$month_key][$val['teacher_money_type_str']];
            \App\Helper\Utils::check_isset_data($money_type_arr['money'],$money);
            \App\Helper\Utils::check_isset_data($money_type_arr['money_simulate'],$money_simulate);
            \App\Helper\Utils::check_isset_data($money_type_arr['reward'],$reward);
            \App\Helper\Utils::check_isset_data($money_type_arr['reward_simulate'],$reward_simulate);
            \App\Helper\Utils::check_isset_data($money_type_arr['lesson_price'],$lesson_price);
            \App\Helper\Utils::check_isset_data($money_type_arr['lesson_count'],$lesson_count);
            \App\Helper\Utils::check_isset_data($money_type_arr['lesson_price_simulate'],$lesson_price_simulate);
            $teacher_money_type_list[$month_key][$val['teacher_money_type_str']]=$money_type_arr;

            $all_money                 += $money;
            $all_lesson_price          += $lesson_price;
            $all_money_simulate        += $money_simulate;
            $all_lesson_price_simulate += $lesson_price_simulate;
            $list[$teacherid] = $tea_arr;
        }

        $all_teacher_money_type_list = $this->check_teacher_money_type_redis_key($teacher_money_type_list);
        Redis::set($this->teacher_money_type_key,json_encode($teacher_money_type_list));

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
            "acc"                        => $acc,
            "start_time"                 => $start_time,
        ];

        //刷新本月的总工资统计
        $this->check_month_redis_key($show_data);
        $final_money_list = json_decode(Redis::get($this->all_money_count_key),true);

        $show_data["final_money"]                 = $final_money_list;
        $show_data["all_teacher_money_type_list"] = $all_teacher_money_type_list;
        $show_key = date("Y-m",$start_time);
        $show_data["teacher_money_type_list"]     = $teacher_money_type_list[$show_key];
        $show_data['level_list'] = $level_list;

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
                $c_val['level_per'] = round($c_val['level_num']/$level_all,2);
            }
        }

        if($type==1){
            \App\Helper\Utils::debug_to_html( $level_count );
        }

        Redis::set($this->level_simulate_count_key,json_encode($level_count));
        return $this->output_succ();
    }

    /**
     * 更新redis中已结算工资月份
     */
    public function check_month_redis_key($data,$reset_month_info=0){
        $month_key     = date("Y-m",$data['start_time']);
        $now_month_key = date("Y-m",time());
        $check_time    = strtotime("2017-1-1");
        if($month_key==$now_month_key || $data['start_time']<$check_time){
            return true;
        }

        $has_month = json_decode(Redis::get($this->has_month_key),true);
        if(!isset($has_month)){
            $has_month[$month_key] = $data;
            $reset_month_info=1;
        }else{
            if(!array_key_exists($month_key,$all_money) || $reset_month_info==1){
                $has_month[$month_key]['all_money']                 = $data['all_money'];
                $has_month[$month_key]['all_money_simulate']        = $data['all_money_simulate'];
                $has_month[$month_key]['all_lesson_price']          = $data['all_lesson_price'];
                $has_month[$month_key]['all_lesson_price_simulate'] = $data['all_lesson_price_simulate'];
                $has_month[$month_key]['all_money_different']
                     = $has_month[$month_key]['all_money_simulate']-$has_month[$month_key]['all_money'];
                $has_month[$month_key]['all_lesson_price_different']
                     = $has_month[$month_key]['all_lesson_price_simulate']-$has_month[$month_key]['all_lesson_price'];
            }
        }

        if($reset_month_info==1){
            $this->check_all_money_redis_key($has_month);
        }

        Redis::set($this->has_month_key,json_encode($has_month));
    }

    public function check_all_money_redis_key($has_month){
        $all_money = [];
        foreach($has_month as $m_key => $m_val){
                \App\Helper\Utils::check_isset_data($all_money['money'],$m_val['money']);
                \App\Helper\Utils::check_isset_data($all_money['money_simulate'],$m_val['money_simulate']);
                \App\Helper\Utils::check_isset_data($all_money['reward'],$m_val['reward']);
                \App\Helper\Utils::check_isset_data($all_money['reward_simulate'],$m_val['reward_simulate']);
                \App\Helper\Utils::check_isset_data($all_money['lesson_price'],$m_val['lesson_price']);
                \App\Helper\Utils::check_isset_data($all_money['lesson_count'],$m_val['lesson_count']);
                \App\Helper\Utils::check_isset_data($all_money['lesson_price_simulate'],$m_val['lesson_price_simulate']);
        }
        Redis::set($this->all_money_count_key,json_encode($all_money));
    }

    public function check_teacher_money_type_redis_key($data){
        $all_money = [];
        foreach($data as $m_key => $m_val){
            foreach($m_val as $p_val){
                \App\Helper\Utils::check_isset_data($all_money['money'],$p_val['money']);
                \App\Helper\Utils::check_isset_data($all_money['money_simulate'],$p_val['money_simulate']);
                \App\Helper\Utils::check_isset_data($all_money['reward'],$p_val['reward']);
                \App\Helper\Utils::check_isset_data($all_money['reward_simulate'],$p_val['reward_simulate']);
                \App\Helper\Utils::check_isset_data($all_money['lesson_price'],$p_val['lesson_price']);
                \App\Helper\Utils::check_isset_data($all_money['lesson_count'],$p_val['lesson_count']);
                \App\Helper\Utils::check_isset_data($all_money['lesson_price_simulate'],$p_val['lesson_price_simulate']);
            }
        }
        // Redis::set($this->all_teacher_money_type_key,json_encode($all_money));
        return $all_money;
    }

    public function del_redis_simulate_money(){
        Redis::del($this->all_teacher_money_type_key);
        Redis::del($this->all_money_count_key);
        Redis::del($this->teacher_money_type_key);
        Redis::del($this->has_month_key);
        return $this->output_succ();
    }


}
