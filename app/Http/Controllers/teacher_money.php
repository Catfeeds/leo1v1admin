<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie;

class teacher_money extends Controller
{
    use CacheNick;
    var $check_login_flag = false;
    var $teacher_money;
    var $late_num   = 0;
    var $change_num = 0;

    public function __construct(){
        $this->teacher_money = \App\Helper\Config::get_config("teacher_money");
    }

    /**
     * 老师工资明细
     */
    public function get_teacher_money_list(){
        $teacherid = $this->get_in_int_val("teacherid");
        if(!$teacherid){
            return $this->output_err("老师id错误!");
        }

        $start_time = $this->get_in_int_val("start_time",strtotime(date("Y-m-01",time())));
        $end_time   = $this->get_in_int_val("end_time",strtotime("+1 month",$start_time));

        $simple_info = $this->t_teacher_info->get_teacher_info($teacherid);
        $teacher_money_type = $simple_info['teacher_money_type'];
        $teacher_type       = $simple_info['teacher_type'];
        $transfer_teacherid = $simple_info['transfer_teacherid'];

        $last_month_start = strtotime("-1 month",$start_time);
        $last_month_end   = strtotime("-1 month",$end_time);
        //上个月累计常规+试听课时
        $last_all_lesson_count = $this->t_lesson_info->get_teacher_last_month_lesson_count(
            $teacherid,$last_month_start,$last_month_end);
        //上个月累计常规课时
        $last_normal_lesson_count = $this->t_lesson_info->get_teacher_last_month_lesson_count(
            $teacherid,$last_month_start,$last_month_end,E\Eteacher_money_type::V_6);
        //检测是否存在转移记录
        if($transfer_teacherid>0){
            $old_all_lesson_count = $this->t_lesson_info->get_teacher_last_month_lesson_count(
                $transfer_teacherid,$last_month_start,$last_month_end);
            $old_normal_lesson_count = $this->t_lesson_info->get_teacher_last_month_lesson_count(
                $transfer_teacherid,$last_month_start,$last_month_end,E\Eteacher_money_type::V_6);
            $last_all_lesson_count    += $old_all_lesson_count;
            $last_normal_lesson_count += $old_normal_lesson_count;
        }

        $time_list   = [];
        $lesson_list = [];
        $lesson_info = $this->t_lesson_info->get_lesson_list_for_wages($teacherid,$start_time,$end_time);
        if(!empty($lesson_info)){
            foreach($lesson_info as $key=>&$val){
                $base_list   = [];
                $reward_list = [];
                $full_list   = [];
                //判断课程的老师类型来设置累计课时的数值
                $check_type = \App\Helper\Utils::check_teacher_money_type($val['teacher_money_type'],$teacher_type);
                switch($check_type){
                case 1: case 3:
                    $already_lesson_count = $val['already_lesson_count'];
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

                $val['lesson_base']        = "0";
                $val['lesson_reward']      = "0";
                $val['lesson_full_reward'] = "0";
                $lesson_count = $val['lesson_count']/100;
                if($val['confirm_flag'] != 2){
                    if($val['lesson_type'] != 2){
                        $val['money']       = \App\Helper\Utils::get_teacher_base_money($teacherid,$val);
                        $val['lesson_base'] = $val['money']*$lesson_count;
                        $lesson_reward      = \App\Helper\Utils::get_teacher_lesson_money(
                            $val['type'],$already_lesson_count
                        );
                        $val['lesson_reward'] = $lesson_reward*$lesson_count;
                        $reward_list['type']  = 2;
                        $reward_list['info']  = "累计课时奖励";
                        $reward_list['money'] = strval($val['lesson_reward']);
                    }else{
                        if($val['fail_greater_4_hour_flag']==0 &&
                           $val['test_lesson_fail_flag']==101 || $val['test_lesson_fail_flag']==102){
                            $val['lesson_base'] = "0";
                        }else{
                            $val['lesson_base'] = \App\Helper\Utils::get_trial_base_price(
                                $val['teacher_money_type'],$val['teacher_type'],$val['lesson_start']
                            );
                        }
                        $val['lesson_reward'] = "0";
                    }

                    if($val['lesson_base']!=0){
                        $base_list['type']  = 1;
                        $base_list['info']  = "老师基本工资";
                        $base_list['money'] = strval($val['lesson_base']);
                    }

                    $val['lesson_full_reward'] = 0;
                    if($val['lesson_full_reward']>0){
                        $full_list['type']  = 2;
                        $full_list['info']  = "全勤奖";
                        $full_list['money'] = $val['lesson_full_reward'];
                    }

                    if(!empty($base_list)){
                        $val['list'][] = $base_list;
                    }
                    if(!empty($reward_list)){
                        $val['list'][] = $reward_list;
                    }
                    if(!empty($full_list)){
                        $val['list'][] = $full_list;
                    }
                }

                $this->get_lesson_cost_info($val);
                $lesson_price = $val['lesson_base']+$val['lesson_reward']+$val['lesson_full_reward']-$val['lesson_cost'];
                $lesson_list[$key]['lesson_base']   = strval($val['lesson_base']);
                $lesson_list[$key]['lesson_reward'] = strval($val['lesson_reward']+$val['lesson_full_reward']);
                $lesson_list[$key]['lesson_cost']   = $val['lesson_cost'];
                $lesson_list[$key]['lesson_price']  = strval($lesson_price);
                $lesson_list[$key]['stu_nick']      = $val['stu_nick'];
                $lesson_list[$key]['lesson_time']   = date("m.d H:i",$val['lesson_start'])."-".date("H:i",$val['lesson_end']);
                $lesson_list[$key]['late_status']   = $val['deduct_come_late'];
                $lesson_list[$key]['lesson_type']   = $val['lesson_type'];
                $lesson_list[$key]['lessonid']      = $val['lessonid'];
                if(isset($val['list'])){
                    $lesson_list[$key]['list'] = $val['list'];
                }
                $time_list[$key]['time'] = $val['lesson_start'];
            }
            array_multisort($time_list,SORT_DESC,$lesson_list);
        }

        $teacher_reward_list = $this->t_teacher_money_list->get_teacher_honor_money_list($teacherid,$start_time,$end_time);
        $reward_ex['name']         = "奖金";
        $reward_compensate['name'] = "补偿";
        $reward_reference['name']  = "推荐";
        foreach($teacher_reward_list as $r_val){
            $reward['add_time_str'] = \App\Helper\Utils::unixtime2date($r_val["add_time"],"Y-m-d");
            $reward['money']        = (float)$r_val['money']/100;
            $reward['money_info']   = E\Ereward_type::get_desc($r_val['type']);
            if(in_array($r_val['type'],[1,2,5])){
                \App\Helper\Utils::check_isset_data($reward_ex['price'],$reward['money']);
                $reward["type"] = 1;
                $reward_ex["reward_list"][] = $reward;
            }elseif(in_array($r_val['type'],[3,4])){
                \App\Helper\Utils::check_isset_data($reward_compensate['price'],$reward['money']);
                $reward["type"] = 2;
                $reward_compensate["reward_list"][] = $reward;
            }elseif(in_array($r_val['type'],[6])){
                \App\Helper\Utils::check_isset_data($reward_reference['price'],$reward['money']);
                $reward['money_info'] = $this->t_teacher_info->get_nick($r_val['money_info']);
                $reward["type"] = 1;
                $reward_reference["reward_list"][] = $reward;
            }
        }
        $this->get_array_data_by_count($all_reward_list,$reward_ex);
        $this->get_array_data_by_count($all_reward_list,$reward_compensate);
        $this->get_array_data_by_count($all_reward_list,$reward_reference);

        return $this->output_succ(["data"=>$lesson_list,"all_reward_list"=>$all_reward_list]);
    }

    public function get_array_data_by_count(&$array,$check_array,$num=1){
        if(count($check_array)>$num){
            $array[]=$check_array;
        }
    }

    /**
     * 获得老师扣款条目
     */
    private function get_lesson_cost_info(&$val){
        $lesson_all_cost = 0;
        $deduct_type     = E\Elesson_deduct::$s2v_map;
        $deduct_info     = E\Elesson_deduct::$desc_map;
        $month           = 0;
        $lesson_month    = date("m",$val['lesson_start']);
        if($month!=$lesson_month){
            $month=$lesson_month;
            $this->change_num=0;
            $this->late_num=0;
        }

        if($val['confirm_flag']==2 &&  $val['deduct_change_class']>0){
            if($val['lesson_cancel_reason_type']==21){
                $lesson_all_cost = $this->teacher_money['lesson_miss_cost']/100;
                $info            = "上课旷课!";
            }elseif(($val['lesson_cancel_reason_type']==2 || $val['lesson_cancel_reason_type']==12)
                    && $val['lesson_cancel_time_type']==1){
                if($this->change_num>=3){
                    $lesson_all_cost = $this->teacher_money['lesson_cost']/100;
                    $info            = "课前４小时内取消上课！";
                }else{
                    $this->change_num++;
                    $info            = "本月第".$this->change_num."次换课";
                    $lesson_all_cost = 0;
                }
            }
            if(isset($info)){
                $cost_info['type']  = 3;
                $cost_info['money'] = $lesson_all_cost;
                $cost_info['info']  = $info;
                $val['list'][]      = $cost_info;
            }
        }else if($val['fail_greater_4_hour_flag']==0 && ($val['test_lesson_fail_flag']==101 || $val['test_lesson_fail_flag']==102)){
            $cost_info['type']  = 3;
            $cost_info['money'] = 0;
            $cost_info['info']  = "老师原因4小时内取消试听课";
            $val['list'][]      = $cost_info;
        }else{
            $lesson_cost = $this->teacher_money['lesson_cost']/100;
            $lesson_all_cost = 0;
            foreach($deduct_type as $key=>$item){
                if($val['deduct_change_class']==0){
                    if($val[$key]>0){
                        if($key=="deduct_come_late" && $this->late_num<3){
                            $this->late_num++;
                        }else{
                            $cost_info['type']  = 3;
                            $cost_info['money'] = $lesson_cost;
                            $cost_info['info']  = $deduct_info[$item];

                            $lesson_all_cost += $lesson_cost;
                            $val["list"][]    = $cost_info;
                        }
                    }
                }
            }
        }

        if($val['lesson_type']!=2){
            $val['lesson_cost_normal'] = (string)$lesson_all_cost;
        }else{
            $val['lesson_cost_normal'] = "0";
        }
        $val['lesson_cost'] = (string)$lesson_all_cost;
    }

    /**
     * 老师工资汇总
     * teacherid 老师id
     * type wx 微信老师端 admin 后台计算老师工资明细
     */
    public function get_teacher_total_money(){
        $type      = $this->get_in_str_val("type","wx");
        $show_type = $this->get_in_str_val("show_type","current");
        $teacherid = $this->get_in_int_val("teacherid");
        if(!$teacherid){
            return $this->output_err("老师id错误!");
        }

        $this->t_lesson_info->switch_tongji_database();
        if($type=="wx"){
            $start_time = $this->t_lesson_info->get_first_lesson_start($teacherid);
            $node_time  = strtotime("2016-12-1");
            if($start_time<$node_time){
                $start_time = $node_time;
            }
            $now_time = strtotime("+1 month",strtotime(date("Y-m-01",time())));
        }elseif($type=="admin"){
            $start_time   = strtotime($this->get_in_str_val("start_time",date("Y-m-d",time())));
            $now_time     = strtotime("+1 day",strtotime($this->get_in_str_val("end_time",date("Y-m-d",time()))));
            $teacher_info = $this->t_teacher_info->get_teacher_info($teacherid);
            // 后台拉取平台代理的老师工资
            $teacher_ref_type = $teacher_info['teacher_ref_type'];
            if($teacher_info['teacher_money_type']==5){
                if($teacher_ref_type==1){
                    $teacher_ref_rate = \App\Helper\Config::get_config_2("teacher_ref_rate",$teacher_ref_type);
                }elseif($teacher_ref_type!=0){
                    $teacher_ref_num  = $this->t_teacher_info->get_teacher_ref_num($start_time,$teacher_ref_type);
                    $teacher_ref_rate = \App\Helper\Utils::get_teacher_ref_rate($teacher_ref_num);
                }
            }

            /**
             * 公司全职老师除以下三位，其他按隔月发放。
             * 叶，时，刁
             */
            if(!in_array($teacherid,[51094,99504,97313]) && $teacher_info['teacher_type']==3){
                $now_time   = $start_time;
                $start_time = strtotime("-1 month",$start_time);
            }

            if($start_time=='' || $now_time==''){
                return $this->output_err("时间错误!");
            }
        }else{
            return $this->output_err("参数错误!");
        }

        $start_date         = strtotime(date("Y-m-01",$start_time));
        $now_date           = strtotime(date("Y-m-01",$now_time));

        $simple_info        = $this->t_teacher_info->get_teacher_info($teacherid);
        $teacher_money_flag = $simple_info['teacher_money_flag'];
        $teacher_money_type = $simple_info['teacher_money_type'];
        $teacher_type       = $simple_info['teacher_type'];
        $transfer_teacherid = $simple_info['transfer_teacherid'];
        $transfer_time      = $simple_info['transfer_time'];
        $teacher_info       = $this->get_teacher_info_for_total_money($simple_info);

        $list = [];
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
            //荣誉榜奖励金额
            $list[$i]['lesson_reward_ex']   = ($this->t_teacher_money_list->get_teacher_honor_money(
                $teacherid,$start,$end,E\Ereward_type::V_1))/100;
            //试听课奖金
            $list[$i]['lesson_reward_trial'] = ($this->t_teacher_money_list->get_teacher_honor_money(
                $teacherid,$start,$end,E\Ereward_type::V_2))/100;
            //90分钟课程补偿
            $list[$i]['lesson_reward_compensate'] = ($this->t_teacher_money_list->get_teacher_honor_money(
                $teacherid,$start,$end,E\Ereward_type::V_3))/100;
            //工资补偿
            $list[$i]['lesson_reward_compensate_price'] = ($this->t_teacher_money_list->get_teacher_honor_money(
                $teacherid,$start,$end,E\Ereward_type::V_4))/100;
            //模拟试听奖金
            $list[$i]['lesson_reward_train'] = ($this->t_teacher_money_list->get_teacher_honor_money(
                $teacherid,$start,$end,E\Ereward_type::V_5))/100;
            //伯乐奖
            $list[$i]['lesson_reward_reference'] = ($this->t_teacher_money_list->get_teacher_honor_money(
                $teacherid,$start,$end,E\Ereward_type::V_6))/100;
            $list[$i]["lesson_ref_money"]  = "0";
            $list[$i]["teacher_ref_money"] = "0";

            $last_month_start = strtotime("-1 month",$start);
            $last_month_end   = strtotime("-1 month",$end);
            //上个月累计常规+试听课时
            $last_all_lesson_count = $this->t_lesson_info->get_teacher_last_month_lesson_count(
                $teacherid,$last_month_start,$last_month_end);
            //上个月累计常规课时
            $last_normal_lesson_count = $this->t_lesson_info->get_teacher_last_month_lesson_count(
                $teacherid,$last_month_start,$last_month_end,E\Eteacher_money_type::V_6);
            //检测是否存在转移记录
            if($transfer_teacherid>0){
                $old_all_lesson_count = $this->t_lesson_info->get_teacher_last_month_lesson_count(
                    $transfer_teacherid,$last_month_start,$last_month_end);
                $old_normal_lesson_count = $this->t_lesson_info->get_teacher_last_month_lesson_count(
                    $transfer_teacherid,$last_month_start,$last_month_end,E\Eteacher_money_type::V_6);
                $last_all_lesson_count   += $old_all_lesson_count;
                $last_normal_lesson_count += $old_normal_lesson_count;
            }

            $lesson_list = $this->t_lesson_info->get_lesson_list_for_wages($teacherid,$start,$end,-1,$show_type);
            if(!empty($lesson_list)){
                foreach($lesson_list as $key => &$val){
                    //判断课程的老师类型来设置累计课时的数值
                    $check_type = \App\Helper\Utils::check_teacher_money_type($val['teacher_money_type'],$teacher_type);
                    switch($check_type){
                    case 1: case 3:
                        $already_lesson_count = $val['already_lesson_count'];
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
                    $lesson_count = $val['confirm_flag']!=2?($val['lesson_count']/100):0;

                    if($val['lesson_type'] != 2){
                        $val['money']       = \App\Helper\Utils::get_teacher_base_money($teacherid,$val);
                        $val['lesson_base'] = $val['money']*$lesson_count;
                        $list[$i]['lesson_normal'] += $val['lesson_base'];
                        $reward = \App\Helper\Utils::get_teacher_lesson_money($val['type'],$already_lesson_count);
                    }else{
                        $val['lesson_base'] = \App\Helper\Utils::get_trial_base_price(
                            $val['teacher_money_type'],$val['teacher_type'],$val['lesson_start']
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

        foreach($list as &$item){
            $item['lesson_price'] = strval(
                $item['lesson_price']
                +$item['lesson_reward_ex']
                +$item['lesson_reward_trial']
                +$item['lesson_reward_compensate']
                +$item['lesson_reward_compensate_price']
                +$item['lesson_reward_reference']
                +$item['lesson_reward_train']
            );
            $item['lesson_normal']       = strval($item['lesson_normal']);
            $item['lesson_trial']        = strval($item['lesson_trial']);
            $item['lesson_reward']       = strval(
                $item['lesson_reward']
                +$item['lesson_reward_compensate']
                +$item['lesson_reward_compensate_price']
            );
            $item['lesson_reward_ex']    = strval($item['lesson_reward_ex']);
            $item['lesson_reward_trial'] = strval($item['lesson_reward_trial']);
            $item['lesson_cost']         = strval($item['lesson_cost']);
            $item['lesson_cost_normal']  = strval($item['lesson_cost_normal']);
            $item['lesson_total']        = strval($item['lesson_total']);
            $item['lesson_price_tax']    = strval($item['lesson_price']);
            //计算平台合作的抽成费用
            if(isset($teacher_ref_rate) && $teacher_ref_rate>0){
                $item['lesson_ref_money']  = strval($item['lesson_normal']+$item['lesson_reward']-$item['lesson_cost_normal']);
                $item['teacher_ref_money'] = strval($item['lesson_ref_money']*$teacher_ref_rate);
                $item['teacher_ref_rate']  = $teacher_ref_rate;
            }

            //teacher_money_flag=1 多卡用户,不扣管理费
            if($teacher_money_flag!=1){
                //旧版工资体系800以外部分扣管理费,新版工资体系全部扣管理费
                if(in_array($teacher_money_type,[0,1,2,3])){
                    if($item['lesson_price']>800){
                        $tax_price = $item['lesson_price']-800;
                        $item['lesson_cost_tax'] = strval(round($tax_price*0.02,2));
                        $item['lesson_price'] -= $item['lesson_cost_tax'];
                    }
                }else{
                    $item['lesson_cost_tax'] = strval(round($item['lesson_price']*0.02,2));
                    $item['lesson_price'] -= $item['lesson_cost_tax'];
                }
            }
        }
        array_multisort($start_list,SORT_DESC,$list);

        return $this->output_succ([
            "teacher_info" => $teacher_info,
            "data"         => $list,
        ]);
    }

    /**
     * 老师荣誉榜 每周二结算
     * 通过命令 Command:SetTeacherMoney --type=1 执行
     */
    public function get_teacher_lesson_total_list(){
        $start_time = strtotime("2016-10-4");
        $tea_list   = $this->t_teacher_money_list->get_teacher_lesson_total_list($start_time);

        $list     = [];
        $ret_list = [];
        $add_time = 0;
        if(is_array($tea_list)){
            foreach($tea_list as $key=>$val){
                if($add_time!=$val['add_time']){
                    if(!empty($list)){
                        $ret_list[]=$list;
                        $list = [];
                    }
                    $add_time     = $val['add_time'];
                    $list["year"] = date("Y",$val['add_time']);
                    $end_time     = strtotime("-1 week",$val['add_time']);
                    $list["time"] = date("m.d",$end_time)."-".date("m.d",($val['add_time']-86400));
                }

                $teacher["tea_nick"]     = $val['nick']==""?$val['realname']:$val['nick'];
                $teacher["lesson_total"] = strval($val['lesson_total']/100);
                $teacher["money"]        = strval($val['money']/100);
                $list["list"][]          = $teacher;

                if(($key+1)==count($tea_list)){
                    $ret_list[]=$list;
                    $list = [];
                }
            }
        }

        return $this->output_succ(["data"=>$ret_list]);
    }

    /**
     * 获取老师指定月份各个扣款免责次数
     */
    private function get_cost_num($teacherid,$start_time){
        $time = \App\Helper\Utils::get_month_date($start_time);
        $data['come_late']  = $this->t_lesson_info->get_cost_num($time['start'],$time['end'],$teacherid,1);
        $data['change_num'] = $this->t_lesson_info->get_cost_num($time['start'],$time['end'],$teacherid,2);

        return $data;
    }

    public function get_teacher_record_detail_info(){
        $this->t_teacher_record_list->switch_tongji_database();
        $teacherid = $this->get_in_int_val("teacherid",50158);
        $type      = $this->get_in_int_val("type",1);
        $add_time  = $this->get_in_int_val("add_time",1484116899);

        $ret_info  = $this->t_teacher_record_list->get_all_info($teacherid,$type,$add_time);

        return $this->output_succ(["ret_info"=>$ret_info]);
    }

    /**
     * 检测老师微信可使用的权限
     * @param type 0 是否可以看到工资相关的权限
     * @param teacherid 老师id
     * @return int 0 否 1 是
     */
    public function check_teacher_wx_permission(){
        $type      = $this->get_in_int_val("type",0);
        $teacherid = $this->get_in_int_val("teacherid");

        if($type==0){
            $wx_use_flag = $this->t_teacher_info->get_wx_use_flag($teacherid);
        }else{
            return $this->output_err("权限类型出错！");
        }

        return $this->output_succ(["wx_use_flag"=>$wx_use_flag]);
    }

    /**
     * @param type 1 荣誉榜奖金 2 试听课奖金 3 90分钟课程补偿 4 工资补偿
     * @param teacherid 老师id
     * @param money_info 获奖信息 1 为课时数 2,3均为lessonid信息 4 为补偿原因
     * @param money 奖金金额
     */
    public function add_teacher_reward(){
        $type       = $this->get_in_int_val("type");
        $teacherid  = $this->get_in_int_val("teacherid");
        $money_info = $this->get_in_str_val("money_info");
        $money      = $this->get_in_int_val("money");
        $add_date   = $this->get_in_str_val("add_time",date("Y-m-d",time()));
        $add_time   = strtotime($add_date);
        $acc        = $this->get_account();
        if(in_array($type,[E\Ereward_type::V_1,E\Ereward_type::V_4])){
            if(!in_array($acc,["adrian","jim","sunny"])){
                return $this->output_err("此用户没有添加奖励权限！");
            }
        }

        if($type != E\Ereward_type::V_1){
            $check_flag = $this->t_teacher_money_list->check_is_exists($money_info,$type);
            if($check_flag){
                return $this->output_err("此类型奖励已存在!");
            }

            if($type==E\Ereward_type::V_2){
                $teacher_money_type = $this->t_teacher_info->get_teacher_money_type($teacherid);
                if(!in_array($teacher_money_type,[0,4,5,6])){
                    return $this->output_err("老师工资分类错误！");
                }
            }elseif($type==E\Ereward_type::V_3){
                $lesson_info = $this->t_lesson_info->get_lesson_info($money_info);
                $add_time    = $lesson_info['lesson_start'];
                $diff_time   = $lesson_info['lesson_end']-$add_time;
                $check_time  = 90*60;
                if($diff_time != $check_time){
                    return $this->output_err("本节课不是90分钟，无法添加90分钟的课程补偿。");
                }

                $base_money = $this->t_lesson_info->get_lesson_money($money_info);
                $money      = $base_money*25;
            }elseif($type==E\Ereward_type::V_4 && $money_info==""){
                return $this->output_err("请填写补偿原因！");
            }
        }

        $ret = $this->t_teacher_money_list->row_insert([
            "teacherid"  => $teacherid,
            "type"       => $type,
            "add_time"   => $add_time,
            "money"      => $money,
            "money_info" => $money_info,
            "acc"        => $acc,
        ]);
        if(!$ret){
            return $this->output_err("添加失败！请重试！");
        }
        return $this->output_succ();
    }

    public function get_teacher_info_for_total_money($info){
        $level_str = \App\Helper\Utils::get_teacher_level_str($info);

        $teacher_type = $info['teacher_type']==32?32:0;
        $bank_status  = $info['bankcard']==""?"未绑定":"已绑定";
        $teacher_info = [
            "nick"         => $info['nick'],
            "face"         => $info['face'],
            "level"        => $level_str,
            "teacher_type" => $teacher_type,
            "bank_status"  => $bank_status,
            "train_through_new_time" => $info['train_through_new_time']
        ];
        return $teacher_info;
    }

    public function update_teacher_bank_info(){
        $type = $this->get_in_str_val("type","wx");
        if($type=="wx"){
            $teacherid = session("login_userid");
        }elseif($type=="admin"){
            $teacherid = $this->get_in_int_val("teacherid");
        }
        $bankcard      = $this->get_in_str_val("bankcard");
        $bank_address  = $this->get_in_str_val("bank_address");
        $bank_account  = $this->get_in_str_val("bank_account");
        $bank_phone    = $this->get_in_str_val("bank_phone");
        $bank_province = $this->get_in_str_val("bank_province");
        $bank_city     = $this->get_in_str_val("bank_city");
        $bank_type     = $this->get_in_str_val("bank_type");
        $idcard        = $this->get_in_str_val("idcard");

        if($teacherid==0){
            $error_info="老师未登录!";
        }elseif($bank_account==""){
            $error_info="请填写持卡人!";
        }elseif($idcard==""){
            $error_info="请填写身份证号!";
        }elseif($bank_type==""){
            $error_info="请选择银行卡类型!";
        }elseif($bank_address==""){
            $error_info="请填写银行支行名称!";
        }elseif($bank_province==""){
            $error_info="请填写开户省!";
        }elseif($bank_city==""){
            $error_info="请填写开户市!";
        }elseif($bankcard==""){
            $error_info="请填写银行卡号!";
        }elseif($bank_phone==""){
            $error_info="请填写银行预留手机号!";
        }
        if(isset($error_info) && $error_info!=""){
            return $this->output_err($error_info);
        }

        $old_bankcard = $this->t_teacher_info->get_bankcard($teacherid);

        $ret = $this->t_teacher_info->field_update_list($teacherid,[
            "bankcard"      => $bankcard,
            "bank_address"  => $bank_address,
            "bank_account"  => $bank_account,
            "bank_phone"    => $bank_phone,
            "bank_type"     => $bank_type,
            "idcard"        => $idcard,
            "bank_city"     => $bank_city,
            "bank_province" => $bank_province,
        ]);

        if(!$ret && $bankcard!=$old_bankcard){
            return $this->output_err("更新失败！请重试！");
        }
        if($old_bankcard!=""){
            $tea_nick = $this->t_teacher_info->get_realname($teacherid);
            $header_msg = $tea_nick."老师，修改了绑定的银行卡号。";
            $msg  = "持卡人姓名：$tea_nick \n 银行卡类型： $bank_type \n 卡号：$bankcard";
            $url  = "/teacher_money/show_teacher_bank_info?teacherid=".$teacherid;
            $desc = "点击详情，查看修改后的银行卡号及详细信息";

            $this->t_manager_info->send_wx_todo_msg("sunny","银行卡号变更",$header_msg,$msg,$url,$desc);
        }

        return $this->output_succ();
    }

    public function grade_wages_list(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,3);

        $this->switch_tongji_database();
        $list        = $this->t_lesson_info_b2->get_grade_wages_list($start_time,$end_time,0);
        $full_start  = strtotime("-1 month",$start_time);
        $full_end    = strtotime("-1 month",$end_time);
        $full_list   = $this->t_lesson_info_b2->get_grade_wages_list($full_start,$full_end,3);
        $lesson_list = array_merge($list,$full_list);

        $count[0]  = [];
        $all_count = &$count[0];
        $all_count['grade_str'] = "总计";
        $all_count['trial_money'] = 0;
        $all_count['trial_count'] = 0;
        $all_count['normal_money'] = 0;
        $all_count['normal_count'] = 0;
        $all_count['lesson_price'] = 0;
        foreach($list as $val){
            $grade = $val['grade'];
            if(!isset($count[$grade]["grade"])){
                $count[$grade]["grade_str"]=E\Egrade::get_desc($grade);
            }
            $val['lesson_count'] /= 100;
            $val['lesson_price'] /= 100;
            \App\Helper\Utils::check_isset_data($all_count['all_count'],$val['lesson_count']);
            \App\Helper\Utils::check_isset_data($count[$grade]['trial_money'],0);
            \App\Helper\Utils::check_isset_data($count[$grade]['trial_count'],0);
            \App\Helper\Utils::check_isset_data($count[$grade]['normal_money'],0);
            \App\Helper\Utils::check_isset_data($count[$grade]['normal_count'],0);
            \App\Helper\Utils::check_isset_data($count[$grade]['lesson_price'],$val['lesson_price']);
            \App\Helper\Utils::check_isset_data($all_count['lesson_price'],$val['lesson_price']);
            if($val['lesson_type']==2){
                $trial_base = \App\Helper\Utils::get_trial_base_price(
                    $val['teacher_money_type'],$val['teacher_type'],$val['lesson_start']
                );

                \App\Helper\Utils::check_isset_data($count[$grade]['trial_money'],$trial_base);
                \App\Helper\Utils::check_isset_data($count[$grade]['trial_count'],$val['lesson_count']);
                \App\Helper\Utils::check_isset_data($all_count['trial_money'],$trial_base);
                \App\Helper\Utils::check_isset_data($all_count['trial_count'],$val['lesson_count']);
            }else{
                $normal_money = $val['lesson_count']*$val['money'];
                \App\Helper\Utils::check_isset_data($count[$grade]['normal_money'],$normal_money);
                \App\Helper\Utils::check_isset_data($count[$grade]['normal_count'],$val['lesson_count']);
                \App\Helper\Utils::check_isset_data($all_count['normal_money'],$normal_money);
                \App\Helper\Utils::check_isset_data($all_count['normal_count'],$val['lesson_count']);
            }
        }
        ksort($count);
        $ret_info = \App\Helper\Utils::list_to_page_info($count);

        return $this->PageView(__METHOD__,$ret_info);
    }

    public function get_teacher_bank_info(){
        $teacherid = $this->get_wx_teacherid();

        $bank_info = $this->t_teacher_info->field_get_list($teacherid,"bank_account,idcard,bank_type,bank_address,bank_province,bank_city,bankcard,bank_phone");

        return $this->output_succ(["data"=>$bank_info]);
    }

    public function check_teacher_trial_lesson(){
        $teacherid = $this->get_in_int_val("teacherid");
        $lessonid  = $this->get_in_int_val("lessonid");

        if($teacherid==0 || $lessonid==0){
            return $this->output_err("老师和课程id都不能为0！");
        }
        $lesson_info = $this->t_lesson_info->get_lesson_info($lessonid);
        $lesson_type = $lesson_info['lesson_type'];
        $userid      = $lesson_info['userid'];
        if($lesson_type<1000){
            $check_flag = $this->t_lesson_info_b2->check_teacher_lesson($teacherid,$userid,$subject,$lesson_type);
        }else{
            return $this->output_err("课程类型出错！试听签单奖只能是由试听或常规来申诉！");
        }
        if(isset($check_flag) && $check_flag==1){
            return $this->output_err("符合签单奖！");
            if($lesson_type==2){
                $this->set_in_value("type",2);
                $this->set_in_value("teacherid",$teacherid);
                $this->set_in_value("money_info",$teacherid);
            }
        }
    }

    public function show_teacher_bank_info(){
        $teacherid = $this->get_in_int_val("teacherid");
        if($teacherid==0){
            return $this->output_err("老师id出错！");
        }

        $bank_info = $this->t_teacher_info->field_get_list($teacherid,"realname,bank_account,idcard,bank_type,bank_address,bank_province,bank_city,bankcard,bank_phone");

        return $this->pageView(__METHOD__,[],[
            "bank_info"=>$bank_info
        ]);
    }

    public function reset_lesson_reward(){
        $lessonid = $this->get_in_int_val("lessonid");
        $account  = $this->get_account();

        $teacherid    = $this->t_lesson_info->get_teacherid($lessonid);
        $lesson_info  = $this->t_lesson_info->get_lesson_info($lessonid);
        $teacher_info = $this->t_teacher_info->get_teacher_info($teacherid);

        if($lesson_info['teacher_money_type']==$teacher_info['teacher_money_type'] && $lesson_info['level']==$teacher_info['level']){
            return $this->output_err("该课程信息正确！不用修改！");
        }
        $lesson_month = date("Y-m",$lesson_info['lesson_start']);
        $now_month = date("Y-m",time());
        if($lesson_month!=$now_month && $account!="adrian"){
            return $this->output_err("不是本月课程！无法更改！");
        }

        $ret = $this->t_lesson_info->field_update_list($lessonid,[
            "teacher_money_type" => $teacher_info['teacher_money_type'],
            "level"              => $teacher_info['level'],
        ]);
        if(!$ret){
            return $this->output_err("更新失败！请重试！");
        }
        return $this->output_succ();
    }


}