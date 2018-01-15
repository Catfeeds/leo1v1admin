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

    function __construct( )  {
        parent::__construct();
        $this->teacher_money = \App\Helper\Config::get_config("teacher_money");
    }

    //模拟总体工资
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

    /**
     * 老师模拟工资明细
     */
    public function tea_simulate_wages_info(){
        list($start_time, $end_time) = $this->get_in_date_range(date("Y-m-01",strtotime("-1 month",time())),0, 0,[],3 );
        $teacherid = $this->get_in_teacherid(0);
        $studentid = $this->get_in_int_val("studentid",-1);
        $show_type = $this->get_in_str_val("show_type","current");

        if($teacherid == 0){
            $ret_list = \App\Helper\Utils::list_to_page_info([]);
            return $this->Pageview(__METHOD__,$ret_list);
        }

        $teacher_type             = $this->t_teacher_info->get_teacher_type($teacherid);
        $old_list                 = $this->t_lesson_info_b3->get_lesson_list_for_simulate_wages(
            $teacherid,$start_time,$end_time,$studentid,$show_type
        );

        //拉取上个月的课时信息
        $last_month_info          = $this->get_last_lesson_count_info($start_time,$end_time,$teacherid);
        $last_all_lesson_count    = $last_month_info['all_lesson_count'];
        $last_normal_lesson_count = $last_month_info['all_normal_count'];

        global $cur_key_index;
        $check_init_map_item = function(&$item,$key,$key_class,$value="") {
            global $cur_key_index;
            if (!isset($item[$key])) {
                $item[$key] = [
                    "value"     => $value,
                    "key_class" => $key_class."-".$cur_key_index,
                    "sub_list"  => [],
                    "data"      => [],
                ];
                $cur_key_index++;
            }
        };

        $add_data = function(&$item,$add_item){
            $arr  = &$item["data"];
            foreach($add_item as $k => $v){
                if (!isset($arr[$k])) {
                    $arr[$k]="";
                }
                if ($k=="price" || $k=="lesson_count" || $k=="lesson_reward" || $k=="lesson_full_reward") {
                    $arr[$k] += $v;
                }
                if($k=="lesson_cost"){
                    $arr[$k] -= $v;
                }
            }
        };

        $data_map     = [];
        $lesson_total_arr = [
            "trial_total"  => 0,
            "normal_total" => 0,
        ];
        $all_price = 0;
        $check_init_map_item($data_map,"","");
        foreach ($old_list as $row_id => &$item) {
            $studentid    = $item["userid"];
            $grade        = $item["grade"];
            $pre_price    = $this->get_teacher_base_money($teacherid,$item);
            $lesson_count = $item["lesson_count"];

            //判断课程的老师类型来设置累计课时的数值
            $check_type = \App\Helper\Utils::check_teacher_money_type($item['teacher_money_type'],$teacher_type);
            switch($check_type){
            case 1: case 3:
                $already_lesson_count = $item['already_lesson_count'];
                break;
            case 2:
                $already_lesson_count = $last_all_lesson_count;
                break;
            case 4:
                $already_lesson_count = $last_normal_lesson_count;
                break;
            default:
                $already_lesson_count = 0;
                break;
            }

            if($item['type']!=0){
                $rule_type = \App\Config\teacher_rule::get_teacher_rule($item['type']);
            }else{
                $rule_type = [];
            }

            if(!empty($rule_type)){
                $i=0;
                $lesson_count_level = count($rule_type);
                foreach($rule_type as $key=>$val){
                    $i++;
                    if($already_lesson_count<$key){
                        $lesson_count_level=$key==0?$i:($i-1);
                        break;
                    }
                }
            }else{
                $lesson_count_level = 1;
            }

            $def_lesson_count = \App\Helper\Utils::get_lesson_count($item['lesson_start'],$item['lesson_end']);
            if ($lesson_count != $def_lesson_count ) {
                $item["lesson_count_err"] = "background-color:red;";
            }

            $item['lesson_full_reward'] = \App\Helper\Utils::get_lesson_full_reward($item['lesson_full_num']);
            $this->get_lesson_cost_info($item);

            if($item['confirm_flag']==2){
                $item['lesson_price'] = 0;
                $item['pre_reward']   = 0;
                $item['price']        = 0;
            }else{
                $item['lesson_price']  /= 100;
                if($item["lesson_type"]!=2){
                    \App\Helper\Utils::check_isset_data($lesson_total_arr['normal_total'],$item['lesson_count']);
                    $item['pre_reward'] = \App\Helper\Utils::get_teacher_lesson_money($item['type'],$already_lesson_count);
                    $item["price"]      = ($pre_price+$item['pre_reward'])*$lesson_count/100
                                        +$item['lesson_full_reward']
                                        -$item['lesson_cost'];
                    $item["pre_price"] = $pre_price;
                }else{
                    \App\Helper\Utils::check_isset_data($lesson_total_arr['trial_total'],$item['lesson_count']);
                    $item['pre_reward'] = 0;
                    if($lesson_count>0) {
                        $trial_base = \App\Helper\Utils::get_trial_base_price(
                            $item['teacher_money_type'],$item['teacher_type'],$item['lesson_start']
                        );

                        $item["price"]        = $trial_base+$item['lesson_full_reward']-$item['lesson_cost'];
                        $item["pre_price"]    = $trial_base;
                        $item["lesson_count"] = 100;
                    }else{
                        $item["price"]        = 0;
                        $item["pre_price"]    = 0;
                        $item["lesson_count"] = 0;
                    }
                }
            }
            $all_price += $item['price'];

            $item['lesson_reward'] = $item['pre_reward']*$lesson_count/100;
            $item['tea_level'] = \App\Helper\Utils::get_teacher_letter_level($item['teacher_money_type'],$item['level']);
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            E\Econfirm_flag::set_item_value_str($item);
            E\Econtract_type::set_item_value_str($item,"lesson_type");
            if ($item['lesson_type'] != 2) {
                $item['lesson_type_str'] = '常规';
            }
            E\Eteacher_money_type::set_item_value_str($item);

            $item["lesson_time"] = \App\Helper\Utils::fmt_lesson_time($item["lesson_start"], $item["lesson_end"]);

            $key0_map = &$data_map[""];
            $check_init_map_item($key0_map["sub_list"] , $studentid,"key1");
            $add_data($key0_map,$item);

            $key1_map=&$key0_map["sub_list"][$studentid];
            $check_init_map_item($key1_map["sub_list"] ,$lesson_count_level,"key2",$item['type'] );
            $add_data($key1_map, $item );

            $key2_map=&$key1_map["sub_list"][$lesson_count_level];
            $check_init_map_item($key2_map["sub_list"] ,$row_id,"key3");
            $add_data($key2_map, $item );

            $key3_map=&$key2_map["sub_list"][$row_id];
            $key3_map["data"]=$item;
        }

        $list=[];
        if (count($old_list)>0) {
            foreach ($data_map as  $studentid=> $item0 ) {
                $data=$item0["data"];
                $data["key1"]="全部";
                $data["key2"]="";
                $data["key3"]="";
                $data["key1_class"]="";
                $data["key2_class"]="";
                $data["key3_class"]="";
                $data["level"]="l-0";
                $list[]=$data;

                foreach ( $item0["sub_list"] as $key1=> $item1  ) { // student
                    $data=$item1["data"];
                    $data["stu_nick"]=$this->cache_get_student_nick($key1);
                    $data["key1"]=$key1;
                    $data["key2"]="";
                    $data["key3"]="";
                    $data["key1_class"]=$item1["key_class"];
                    $data["key2_class"]="";
                    $data["key3_class"]="";
                    $data["level"]="l-1";
                    $list[]=$data;

                    foreach ( $item1["sub_list"] as $key2 => $item2 ) { //lesson_count_level
                        $data=$item2["data"];
                        $data["key1"]=$key1;
                        $data["key2"]=$key2;

                        if($item2['value']>0){
                            $lesson_count_range = \App\Config\teacher_rule::get_teacher_lesson_count_range($item2['value']);
                            $data["lesson_count_level_str"] = $lesson_count_range[$key2];
                        }else{
                            $data["lesson_count_level_str"] = "未确认";
                        }

                        $data["key3"]       = "";
                        $data["key1_class"] = $item1["key_class"];
                        $data["key2_class"] = $item2["key_class"];
                        $data["key3_class"] = "";
                        $data["level"]      = "l-2";

                        $list[]=$data;
                        foreach ( $item2["sub_list"] as $key3=> $item3  ) {
                            $data=$item3["data"];
                            $data["key1"]=$key1;
                            $data["key2"]=$key2;
                            $data["key3"]=$key3;
                            $data["key1_class"]=$item1["key_class"];
                            $data["key2_class"]=$item2["key_class"];
                            $data["key3_class"]=$item3["key_class"];
                            $data["level"]="l-3";
                            $data["lesson_count_level_str"]="-";

                            $list[] = $data;
                        }
                    }
                }
            }
        }

        $teacher_reward = $this->t_teacher_money_list->get_teacher_honor_money($teacherid,$start_time,$end_time,0);

        $ret_list = \App\Helper\Utils::list_to_page_info($list);
        return $this->Pageview(__METHOD__,$ret_list,[
            "teacherid"      => $teacherid,
            "lesson_count"   => $lesson_total_arr,
            "teacher_reward" => $teacher_reward,
            "all_price"      => $all_price,
        ]);
    }

    private function get_lesson_cost_info(&$val){
        $lesson_all_cost = 0;
        $lesson_info     = "";
        $deduct_type = E\Elesson_deduct::$s2v_map;
        $deduct_info = E\Elesson_deduct::$desc_map;
        $month = 0;
        $lesson_month = date("m",$val['lesson_start']);
        if($month!=$lesson_month){
            $month=$lesson_month;
            $this->change_num=0;
            $this->late_num=0;
        }

        if($val['confirm_flag']==2 && $val['deduct_change_class']>0){
            if($val['lesson_cancel_reason_type']==21){
                $lesson_all_cost = $this->teacher_money['lesson_miss_cost']/100;
                $info            = "上课旷课!";
            }elseif(($val['lesson_cancel_reason_type']==2 || $val['lesson_cancel_reason_type']==12)
            && $val['lesson_cancel_time_type']==1){
                if($this->change_num>=3){
                    $lesson_all_cost = $this->teacher_money['lesson_cost']/100;
                    $lesson_info     = "课前４小时内取消上课！";
                }else{
                    $this->change_num++;
                    $lesson_info     = "本月第".$this->change_num."次换课";
                    $lesson_all_cost = 0;
                }
            }
        }else{
            $lesson_cost = $this->teacher_money['lesson_cost']/100;
            foreach($deduct_type as $key=>$item){
                if($val['deduct_change_class']==0){
                    if($val[$key]>0){
                        if($key=="deduct_come_late" && $this->late_num<3){
                            $this->late_num++;
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
    }



}
