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
    var $check_login_flag = false;

    public function teacher_simulate_salary_list(){
        $acc = $this->get_account();
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,E\Eopt_date_type::V_3);
        $teacher      = $this->get_in_int_val('teacher',-1);
        $teacher_type = $this->get_in_int_val("teacher_type",-1);
        $teacherid    = $this->get_in_int_val('teacherid',-1);

        $ret_info  = $this->t_teacher_simulate_salary_list->get_simulate_salary_list($start_time,$end_time,$teacher_type,$teacher);
        $all_money = 0;
        $all_all_money = 0;//全职老师
        $all_not_money = 0;//兼职老师
        foreach($ret_info['list'] as &$t_val){
            $t_val['pay_time'] = \App\Helper\Utils::unixtime2date($t_val['pay_time'],"Y-m-d");
            $t_val['add_time'] = \App\Helper\Utils::unixtime2date($t_val['add_time'],"Y-m-d");
            $t_val['money']   /= 100;
            if($t_val['is_negative']==1){
                $t_val['money'] = 0-$t_val['money'];
            }
            E\Esubject::set_item_value_str($t_val);
            $all_money += $t_val['money'];
            if ($t_val['teacher_money_type'] == 7 || ($t_val['teacher_type'] == 3 && $t_val["teacher_money_type"] == 0)) {
                $all_all_money += $t_val['money'];
            } else {
                $all_not_money += $t_val['money'];
            }
            E\Eteacher_type::set_item_value_str($t_val);
            E\Eteacher_money_type::set_item_value_str($t_val);
        }
        $all_money_tax = $all_money*0.98;

        return $this->pageView(__METHOD__,$ret_info,[
            "all_money"     => $all_money,
            "all_money_tax" => $all_money_tax,
            'all_all_money' => $all_all_money,
            'all_not_money' => $all_not_money,
        ]);
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
            //拉取课程的模拟工资
            $lesson_list = $this->t_lesson_info_b3->get_lesson_list_for_simulate_wages($teacherid,$start,$end,-1,$show_type);
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