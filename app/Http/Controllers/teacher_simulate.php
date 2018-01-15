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
    var $teacher_ref_rate_key     = "teacher_ref_rate";

    var $lesson_total_key             = "lesson_total";
    var $already_lesson_count_key     = "already_lesson_count_month";
    var $already_lesson_count_simulate_key = "already_lesson_count_simulate_month";
    var $money_month_key              = "money_month";
    var $teacher_money_type_month_key = "teacher_money_type_month";

    public function new_teacher_money_list(){
        $this->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range("2017-7-1",0,0,null,3);
        $now_month          = date("m",time());
        $teacher_id         = $this->get_in_int_val("teacher_id",-1);
        $teacher_money_type = $this->get_in_enum_list(E\Eteacher_money_type::class);
        $teacher_money_type_simulate = $this->get_in_enum_list(E\Eteacher_money_type::class,-1,"teacher_money_type_simulate");
        $level              = $this->get_in_int_val("level",-1);
        $not_start          = $this->get_in_int_val("not_start",-1);
        $not_end            = $this->get_in_int_val("not_end",$now_month);
        $batch              = $this->get_in_int_val("batch",-1);
        $acc                = $this->get_account();

        $not_start = strtotime("2017-".$not_start."-01");
        $not_end   = strtotime("2017-".$not_end."-01");

        $list                      = [];
        $teacher_money_type_list   = [];
        $all_money                 = 0;
        $all_lesson_price          = 0;
        $all_money_simulate        = 0;
        $all_lesson_price_simulate = 0;
        $lesson_total              = 0;
        /**
         * 每个老师上个月的累积课时
         */
        $already_lesson_count_list = \App\Helper\Utils::redis(E\Eredis_type::V_GET,$this->already_lesson_count_key,[],true);
        /**
         * 每个老师上个月的模拟累积课时
         */
        $already_lesson_count_simulate_list = \App\Helper\Utils::redis(
            E\Eredis_type::V_GET,$this->already_lesson_count_simulate_key,[],true);

        $now_date  = date("Y-m",$start_time);
        $file_name = "/tmp/teacher_simulate_".$now_date."_".json_encode($teacher_money_type)."_".$level."_".$teacher_id."_".$not_start."_".$not_end.".txt";
        // 需要重新拉取  flag  0 不需要  1 需要
        $flag = 0;
        if(is_file($file_name)){
            $file_info = file_get_contents($file_name);
            if(empty($file_info) || $file_info==""){
                $flag = 1;
            }
        }else{
            $flag = 1;
        }

        if($flag){
            $tea_list = $this->t_teacher_info->get_teacher_simulate_list(
                $start_time,$end_time,$teacher_money_type,$level,$teacher_id,$not_start,$not_end,$teacher_money_type_simulate,$batch
            );
            file_put_contents($file_name,json_encode($tea_list));
        }else{
            $tea_list = json_decode($file_info,true);
        }

        foreach($tea_list as $val){
            $teacherid = $val['teacherid'];
            $teacher_ref_type_rate = 0;
            \App\Helper\Utils::check_isset_data($list[$teacherid],[],0);
            $tea_arr                   = $list[$teacherid];
            $tea_arr["teacherid"]      = $teacherid;
            $tea_arr["level_simulate"] = $val["level_simulate"];
            E\Eteacher_money_type::set_item_value_str($val,"now_money_type");
            E\Eteacher_money_type::set_item_value_str($val,"teacher_money_type_simulate");
            $val['level_str'] = \App\Helper\Utils::get_teacher_letter_level($val['now_money_type'],$val['now_level']);
            $val['level_simulate_str'] = \App\Helper\Utils::get_teacher_letter_level(
                $val['teacher_money_type_simulate'],$val['level_simulate']
            );
            \App\Helper\Utils::check_isset_data($tea_arr['realname'],$val['realname'],0);
            \App\Helper\Utils::check_isset_data($tea_arr['now_money_type_str'],$val['now_money_type_str'],0);
            \App\Helper\Utils::check_isset_data($tea_arr['teacher_money_type_simulate_str'],$val['teacher_money_type_simulate_str'],0);
            \App\Helper\Utils::check_isset_data($tea_arr['level_str'],$val['level_str'],0);
            \App\Helper\Utils::check_isset_data($tea_arr['level_simulate_str'],$val['level_simulate_str'],0);

            //上个月累计常规+试听课时
            $month_key = date("Y-m",$val['lesson_start']);
            if(!isset($already_lesson_count_list[$month_key][$teacherid])){
                $now_month_start = strtotime(date("Y-m-01",$val['lesson_start']));
                $now_month_end   = strtotime("+1 month",strtotime(date("Y-m-01",$val['lesson_start'])));
                $already_lesson_count_simulate = $this->get_already_lesson_count(
                    $now_month_start,$now_month_end,$teacherid,$teacher_money_type
                );
                $already_lesson_count_list[$month_key][$teacherid] = $already_lesson_count_simulate;
            }else{
                $already_lesson_count_simulate = $already_lesson_count_list[$month_key][$teacherid];
            }

            //上个月累计常规课时
            if(!isset($already_lesson_count_simulate_list[$month_key][$teacherid])){
                $now_month_start = strtotime(date("Y-m-01",$val['lesson_start']));
                $now_month_end   = strtotime("+1 month",strtotime(date("Y-m-01",$val['lesson_start'])));
                $already_lesson_count_simulate_2 = $this->get_already_lesson_count(
                    $now_month_start,$now_month_end,$teacherid,E\Eteacher_money_type::V_6
                );
                $already_lesson_count_simulate_list[$month_key][$teacherid] = $already_lesson_count_simulate_2;
            }else{
                $already_lesson_count_simulate_2 = $already_lesson_count_simulate_list[$month_key][$teacherid];
            }

            $check_type = \App\Helper\Utils::check_teacher_money_type($val['teacher_money_type'],$val['teacher_type']);
            if(in_array($check_type,[1,3])){
                $already_lesson_count = $val['already_lesson_count'];
            }elseif($check_type==2){
                $already_lesson_count = $already_lesson_count_simulate;
            }elseif($check_type==4){
                $already_lesson_count = $already_lesson_count_simulate_2;
            }else{
                $already_lesson_count = 0;
            }

            $check_type_simulate = \App\Helper\Utils::check_teacher_money_type(
                $val['teacher_money_type_simulate'],$val['teacher_type']);
            if(in_array($check_type_simulate,[1,3])){
                $already_lesson_count_si = $val['already_lesson_count'];
            }elseif($check_type_simulate==2){
                $already_lesson_count_si = $already_lesson_count_simulate;
            }elseif($check_type_simulate==4){
                $already_lesson_count_si = $already_lesson_count_simulate_2;
            }else{
                $already_lesson_count_si = 0;
            }

            //老师实际的课时奖励
            $reward = \App\Helper\Utils::get_teacher_lesson_money(
                $val['type'],$already_lesson_count);
            //老师模拟的课时奖励
            $reward_simulate  = \App\Helper\Utils::get_teacher_lesson_money(
                $val['type_simulate'],$already_lesson_count_si);

            $lesson_count     = $val['lesson_count']/100;
            $reward          *= $lesson_count;
            $reward_simulate *= $lesson_count;

            $money_base          = $val['money']*$lesson_count;
            $money_simulate_base = $val['money_simulate']*$lesson_count;
            $money               = $money_base+$reward;
            $money_simulate      = $money_simulate_base+$reward_simulate;

            if($val['teacher_money_type']==5){
                $teacher_ref_rate = $this->get_teacher_ref_rate($val['lesson_start'],$val['teacher_ref_type']);
                if($teacher_ref_rate>0){
                    $teacher_ref_money  = $money*$teacher_ref_rate;
                    $money             += $teacher_ref_money;
                }
            }

            $lesson_price = $val['lesson_price']/100;
            if(in_array($val['contract_type'],[0,3])){
                // $lesson_price_simulate = $this->get_lesson_price_simulate($val);
                $lesson_price_simulate = 0;
            }else{
                $lesson_price_simulate = 0;
            }

            \App\Helper\Utils::check_isset_data($tea_arr['money'],$money);
            \App\Helper\Utils::check_isset_data($tea_arr['money_base'],$money_base);
            \App\Helper\Utils::check_isset_data($tea_arr['money_simulate'],$money_simulate);
            \App\Helper\Utils::check_isset_data($tea_arr['money_simulate_base'],$money_simulate_base);
            \App\Helper\Utils::check_isset_data($tea_arr['reward'],$reward);
            \App\Helper\Utils::check_isset_data($tea_arr['reward_simulate'],$reward_simulate);
            \App\Helper\Utils::check_isset_data($tea_arr['lesson_price'],$lesson_price);
            \App\Helper\Utils::check_isset_data($tea_arr['lesson_count'],$lesson_count);
            \App\Helper\Utils::check_isset_data($tea_arr['lesson_price_simulate'],$lesson_price_simulate);

            $all_money                 += $money;
            $all_lesson_price          += $lesson_price;
            $all_money_simulate        += $money_simulate;
            $all_lesson_price_simulate += $lesson_price_simulate;
            $list[$teacherid] = $tea_arr;

            $lesson_total += $lesson_count;
        }
        \App\Helper\Utils::check_isset_data($all_count,0,0);
        \App\Helper\Utils::check_isset_data($down_count['base'],0,0);
        \App\Helper\Utils::check_isset_data($down_count['all'],0,0);
        \App\Helper\Utils::check_isset_data($up_count['base'],0,0);
        \App\Helper\Utils::check_isset_data($up_count['all'],0,0);

        /**
         * 统计变动数量
         */
        foreach($list as &$l_val){
            \App\Helper\Utils::check_isset_data($all_count,1);
            $l_val['money_different']        = round(($l_val['money_simulate']-$l_val['money']),2);
            $l_val['money_base_different']   = round(($l_val['money_simulate_base']-$l_val['money_base']),2);
            $l_val['lesson_price_different'] = round(($l_val['lesson_price_simulate']-$l_val['lesson_price']),2);

            if($l_val['money_base_different']<0){
                $down_count['base']++;
            }elseif($l_val['money_base_different']>0){
                $up_count['base']++;
            }
            if($l_val['money_different']<0){
                $down_count['all']++;
            }elseif($l_val['money_different']>0){
                $up_count['all']++;
            }
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
            "lesson_total"               => $lesson_total,
            "level_list"                 => $level_list,
            "acc"                        => $acc,
            "start_time"                 => $start_time,
            "all_count"                  => $all_count,
            "down_count"                 => $down_count,
            "up_count"                   => $up_count,
        ];

        $this->check_month_redis_key($show_data);
        \App\Helper\Utils::redis(E\Eredis_type::V_SET,$this->already_lesson_count_key,$already_lesson_count_list,true);
        \App\Helper\Utils::redis(
            E\Eredis_type::V_SET,$this->already_lesson_count_simulate_key,$already_lesson_count_simulate_list,true);

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
        return $this->output_err("无法修改");
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
        $this->switch_tongji_database();
        $type = $this->get_in_int_val("type");

        $level_list  = $this->t_teacher_info->get_level_simulate_list();
        $level_count = [];
        $level_all   = 0;
        $level_order = [];
        if(!empty($level_list)){
            foreach($level_list as $val){
                $level_all += $val['level_num'];
                E\Enew_level::set_item_value_str($val,"level_simulate");
                // \App\Helper\Utils::check_isset_data($level_count[$val['level_simulate_str']]['level_num']['level'],$val['level_simulate'],0);
                \App\Helper\Utils::check_isset_data($level_count[$val['level_simulate_str']]['level_num'],$val['level_num'],0);
                \App\Helper\Utils::check_isset_data($level_count["all"]['level_num'],$val['level_num']);
                // \App\Helper\Utils::check_isset_data($level_count["all"]['level_num']['level'],99,0);
                $level_order[]=$val['level_simulate'];
            }
            foreach($level_count as &$c_val){
                $c_val['level_per'] = round($c_val['level_num']/$level_all,4);
            }
        }

        // array_multisort($level_count,$level_order);
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
        $month_key     = date("Y-m",$data['start_time']);
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
                $all_money['all_money']                  += $data['all_money'];
                $all_money['all_money_simulate']         += $data['all_money_simulate'];
                $all_money['all_lesson_price']           += $data['all_lesson_price'];
                $all_money['all_lesson_price_simulate']  += $data['all_lesson_price_simulate'];
                $all_money['lesson_total']               += $data['lesson_total'];
                $all_money['all_money_different']         = $all_money['all_money_simulate']-$all_money['all_money'];
                $all_money['all_lesson_price_different']  = $all_money['all_lesson_price_simulate']-$all_money['all_lesson_price'];
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
        Redis::del($this->teacher_ref_rate_key);
        return $this->output_succ();
    }

    public function get_teacher_ref_rate($time,$teacher_ref_type){
        $start_date = date("Y-m-01",$time);
        $start_time = strtotime($start_date);

        $teacher_ref_rate_list = \App\Helper\Utils::redis(E\Eredis_type::V_GET,$this->teacher_ref_rate_key,[],true);
        if($teacher_ref_rate_list===null || !isset($teacher_ref_rate_list[$teacher_ref_type][$start_date])){
            $teacher_ref_num  = $this->t_teacher_info->get_teacher_ref_num($start_time,$teacher_ref_type);
            $teacher_ref_rate = \App\Helper\Utils::get_teacher_ref_rate($teacher_ref_num);
            $teacher_ref_rate_list[$teacher_ref_type][$start_date] = $teacher_ref_rate;
            \App\Helper\Utils::redis(E\Eredis_type::V_SET,$this->teacher_ref_rate_key,$teacher_ref_rate_list);
        }else{
            $teacher_ref_rate = $teacher_ref_rate_list[$teacher_ref_type][$start_date];
        }

        return $teacher_ref_rate;
    }

    public function teacher_simulate_money_total_list(){
        $account = $this->get_account();
        $level_list  = \App\Helper\Utils::redis(E\Eredis_type::V_GET,$this->level_simulate_count_key,[],true);
        $money_month = \App\Helper\Utils::redis(E\Eredis_type::V_GET,$this->money_month_key,[],true);
        $teacher_money_type_month = \App\Helper\Utils::redis(E\Eredis_type::V_GET,$this->teacher_money_type_month_key,[],true);

        $all_money = [];
        if(!empty($money_month)){
            foreach($money_month as $m_val){
                \App\Helper\Utils::check_isset_data($all_money['money'],$m_val['money']);
                \App\Helper\Utils::check_isset_data($all_money['money_simulate'],$m_val['money_simulate']);
                \App\Helper\Utils::check_isset_data($all_money['lesson_price'],$m_val['lesson_price']);
                \App\Helper\Utils::check_isset_data($all_money['lesson_price_simulate'],$m_val['lesson_price_simulate']);
                \App\Helper\Utils::check_isset_data($all_money['lesson_total'],$m_val['lesson_total']);
            }
        }

        if(is_array($teacher_money_type_month)){
            foreach($teacher_money_type_month as $month_key=>&$month_val){
                if(is_array($month_val)){
                    foreach($month_val as $t_key => &$t_val){
                        if(is_array($t_val)){
                            foreach($t_val as $l_key=>&$l_val){
                                $l_val['teacher_money_type_str'] = E\Eteacher_money_type::get_desc($t_key);
                                $l_val['level_str']              = E\Elevel::get_desc($l_key);
                            }
                        }
                    }
                }
            }
        }

        $has_power = 0;
        if(in_array($account,["ted","michelle"])){
            $has_power = 1;
        }

        return $this->view(__METHOD__,[
            "has_power"                => $has_power,
            "level_list"               => $level_list,
            "money_month"              => $money_month,
            "teacher_money_type_month" => $teacher_money_type_month,
            "all_money"                => $all_money,
        ]);
    }

    public function get_month_money_list(){
        \App\Helper\Utils::redis(E\Eredis_type::V_DEL,$this->already_lesson_count_key);
        \App\Helper\Utils::redis(E\Eredis_type::V_DEL,$this->money_month_key);
        \App\Helper\Utils::redis(E\Eredis_type::V_DEL,$this->teacher_money_type_month_key);

        $start_time = strtotime("2017-1-1");
        $end_time   = strtotime("2017-9-1");

        $job = new \App\Jobs\ResetTeacherMonthMoney($start_time,$end_time);
        dispatch($job);

        $this->get_level_simulate_list();
    }

    /**
     * 获取老师模拟工资
     */
    public function get_teacher_simulate_salary($teacherid,$start_time,$end_time){
        $salary_info = $this->get_teacher_lesson_simulate_money_list($teacherid,$start_time,$end_time);
        return $salary_info[0];
    }

    /**
     * 获取老师的模拟总工资明细
     * @param int teacherid 老师id
     * @param int start_time 拉取老师工资的开始时间
     * @param int end_time   拉取老师工资的结束时间
     * @param string show_type 拉取老师工资的结束时间
     * @return array list
     */
    public function get_teacher_lesson_simulate_money_list(
        $teacherid,$start_time,$end_time,$show_type="current"
    ){
        $start_date = strtotime(date("Y-m-01",$start_time));
        $now_date   = strtotime(date("Y-m-01",$end_time));

        $teacher_info = $this->t_teacher_info->get_teacher_info($teacherid);
        $teacher_money_type = $teacher_info['teacher_money_type'];
        $teacher_ref_type = $teacher_info['teacher_ref_type'];
        $teacher_type = $teacher_info['teacher_type'];
        //检测老师是否需要被渠道抽成
        $check_flag = $this->t_teacher_lecture_appointment_info->check_tea_ref($teacherid,$teacher_ref_type);
        if($check_flag){
            $teacher_ref_rate = $this->get_teacher_ref_rate(
                $start_time,$teacher_ref_type,$teacher_money_type
            );
        }

        $list = [];
        $check_num = [];
        for($i=0,$flag=true;$flag!=false;$i++){
            $j     = $i+1;
            $start = strtotime("+".$i."month",$start_date);
            $end   = strtotime("+".$j."month",$start_date);
            if($end==$now_date || $end>$now_date){
                $flag = false;
            }

            $start_list[] = $start;
            $list[$i]["date"]               = date("Y年m月",$start);
            $list[$i]["start_time"]         = $start;
            $list[$i]["end_time"]           = $end;
            $list[$i]["lesson_price"]       = "0";
            $list[$i]["lesson_normal"]      = "0";
            $list[$i]["lesson_trial"]       = "0";
            $list[$i]["lesson_reward"]      = "0";
            $list[$i]["lesson_full_reward"] = "0";
            $list[$i]["lesson_cost"]        = "0";
            //常规课扣款综合，本字段供后台统计使用
            $list[$i]["lesson_cost_normal"] = "0";
            $list[$i]["lesson_cost_tax"]    = "0";
            $list[$i]["lesson_total"]       = "0";
            $reward_list = $this->get_teacher_reward_money_list($teacherid,$start,$end);
            //荣誉榜奖励金额
            $list[$i]['lesson_reward_ex']   = $reward_list[E\Ereward_type::V_1]['money'];
            //试听课奖金
            $list[$i]['lesson_reward_trial'] = $reward_list[E\Ereward_type::V_2]['money'];
            //90分钟课程补偿
            $list[$i]['lesson_reward_compensate'] = $reward_list[E\Ereward_type::V_3]['money'];
            //工资补偿
            $list[$i]['lesson_reward_compensate_price'] = $reward_list[E\Ereward_type::V_4]['money'];
            //模拟试听奖金
            $list[$i]['lesson_reward_train'] = $reward_list[E\Ereward_type::V_5]['money'];
            //伯乐奖
            $list[$i]['lesson_reward_reference'] = $reward_list[E\Ereward_type::V_6]['money'];
            //春晖奖
            $list[$i]['lesson_reward_chunhui'] = $reward_list[E\Ereward_type::V_7]['money'];
            //微课工资
            $list[$i]['lesson_reward_weike'] = $reward_list[E\Ereward_type::V_8]['money'];
            //小班课工资
            $list[$i]['lesson_reward_small_class'] = $reward_list[E\Ereward_type::V_9]['money'];
            //公开课工资
            $list[$i]['lesson_reward_open_class'] = $reward_list[E\Ereward_type::V_10]['money'];

            $list[$i]["lesson_ref_money"]  = "0";
            $list[$i]["teacher_ref_money"] = "0";

            //拉取上个月的课时信息
            $last_lesson_count = $this->get_last_lesson_count_info($start,$end,$teacherid);
            $lesson_list = $this->t_lesson_info->get_lesson_list_for_simulate_wages($teacherid,$start,$end,-1,$show_type);
            if(!empty($lesson_list)){
                foreach($lesson_list as $key => &$val){
                    $lesson_count = $val['confirm_flag']!=2?($val['lesson_count']/100):0;
                    if($val['lesson_type'] != 2){
                        $val['money']       = $this->get_teacher_base_money($teacherid,$val);
                        $val['lesson_base'] = $val['money']*$lesson_count;
                        $list[$i]['lesson_normal'] += $val['lesson_base'];
                        $reward = $this->get_lesson_reward_money(
                            $last_lesson_count,$val['already_lesson_count'],$val['teacher_money_type'],$teacher_type,$val['type']
                        );
                    }else{
                        $val['lesson_base'] = \App\Helper\Utils::get_trial_base_price(
                            $val['teacher_money_type'],$val['teacher_type'],$val['lesson_start']
                        );
                        $list[$i]['lesson_trial'] += $val['lesson_base'];
                        $reward = "0";
                    }
                    $val['lesson_full_reward'] = '0';
                    $val['lesson_reward']      = $reward*$lesson_count+$val['lesson_full_reward'];
                    $val['lesson_cost_normal'] = '0';

                    $this->get_lesson_cost_info($val,$check_num);
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

        foreach($list as &$item){
            $item['teacher_lesson_price'] = $item['lesson_price'];
            $item['lesson_price'] = strval(
                $item['lesson_price']
                +$item['lesson_reward_ex']
                +$item['lesson_reward_trial']
                +$item['lesson_reward_compensate']
                +$item['lesson_reward_compensate_price']
                +$item['lesson_reward_reference']
                +$item['lesson_reward_train']
                +$item['lesson_reward_chunhui']
            );
            $item['lesson_normal']       = strval($item['lesson_normal']);
            $item['lesson_trial']        = strval($item['lesson_trial']);
            $item['lesson_reward']       = strval(
                $item['lesson_reward']
                +$item['lesson_reward_compensate']
                +$item['lesson_reward_compensate_price']
            );
            $item['lesson_reward_extra'] = strval($item['lesson_reward_trial']
                                                  +$item['lesson_reward_reference']
                                                  +$item['lesson_reward_chunhui']
                                                  +$item['lesson_reward_train']

            );
            $item['lesson_reward_ex']    = strval($item['lesson_reward_ex']);
            $item['lesson_reward_trial'] = strval($item['lesson_reward_trial']);
            $item['lesson_cost']         = strval($item['lesson_cost']);
            $item['lesson_cost_normal']  = strval($item['lesson_cost_normal']);
            $item['lesson_total']        = strval($item['lesson_total']);
            $item['lesson_price_tax']    = strval($item['lesson_price']);

            $item['lesson_reward_admin'] = $item['lesson_reward_chunhui']
                                         +$item['lesson_reward_weike']
                                         +$item['lesson_reward_small_class']
                                         +$item['lesson_reward_open_class'];

            //计算平台合作的抽成费用
            if(isset($teacher_ref_rate) && $teacher_ref_rate>0){
                $item['lesson_ref_money']  = strval($item['lesson_normal']+$item['lesson_reward']-$item['lesson_cost_normal']);
                $item['teacher_ref_money'] = strval($item['lesson_ref_money']*$teacher_ref_rate);
                $item['teacher_ref_rate']  = $teacher_ref_rate;
            }
            if($item['lesson_price']>0){
                $item['lesson_cost_tax'] = strval(round($item['lesson_price']*0.02,2));
                $item['lesson_price'] -= $item['lesson_cost_tax'];
            }
            // 老师帮 --- 我的收入页 12后显示选项
            if ($item['start_time'] < strtotime('2017-12-1')) {
                $item['list'] = [];
            } else {
                $item['list'] = [
                    ['name'=>'90分钟补偿','value'=> $item['lesson_reward_compensate'].''], 
                    ['name'=>'工资补偿','value'=> $item['lesson_reward_compensate_price'].''],
                    ['name'=>'小班课工资','value'=> $item['lesson_reward_small_class'].''],
                    ['name'=>'微课工资','value'=> $item['lesson_reward_weike'].''],
                    ['name'=>'公开课工资','value'=> $item['lesson_reward_open_class'].'']
                ];
            }
            $item['lesson_reward_chunhui'] = $item['lesson_reward_chunhui'].'';
            $item['lesson_reward_reference'] = $item['lesson_reward_reference'].'';
        }
        array_multisort($start_list,SORT_DESC,$list); 
        return $list;
    }

    /**
     * 从txt文件中获取内容
     */
    public function get_info_from_file($file_name="b"){
        $info = file_get_contents("/tmp/".$file_name.".txt");
        $arr  = explode("\n",$info);
        return $arr;
    }

    /**
     * 通过表单更新老师的模拟等级
     */
    public function reset_simulate_level(){
        $teacher_arr = $this->get_info_from_file("level_up");
        $level_map = array_flip(E\Enew_level::$simple_desc_map);
        $all_num = count($teacher_arr);
        foreach($teacher_arr as $val){
            if($val!=""){
                $teacher_info = explode("\t",$val);

                $teacherid     = $teacher_info[0];
                $nick          = $teacher_info[1];
                $old_level_str = $teacher_info[2];
                $new_level_str = $teacher_info[3];

                if(isset($level_map[$new_level_str])){
                    $level_simulate = $level_map[$new_level_str];
                }elseif($new_level_str=="T级"){
                    $level_simulate = 11;
                }else{
                    echo "teacherid:".$teacherid." 老师等级错误";
                    echo "<br>";
                    continue;
                }

                $check_flag = $this->t_teacher_info->get_teacher_info($teacherid);
                if(empty($check_flag)){
                    echo "teacherid: $teacherid 该老师 $nick 不存在";
                    echo "<br>";
                    continue;
                }

                echo "teacherid: $teacherid ,level_simulate: $level_simulate old_level_simulate: ".$check_flag['level_simulate'];
                if($level_simulate!=$check_flag['level_simulate']){
                    echo " update ";
                    $this->t_teacher_info->field_update_list($teacherid, [
                        "level_simulate" => $level_simulate
                    ]);
                }
                echo "<br>";

            }
        }
    }

}