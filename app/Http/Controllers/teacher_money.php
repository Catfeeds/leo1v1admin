<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Artisan;

class teacher_money extends Controller
{
    use CacheNick;
    use TeaPower;

    var $check_login_flag = false;
    var $teacher_money;

    public function __construct(){
        parent::__construct();
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

        $simple_info  = $this->t_teacher_info->get_teacher_info($teacherid);
        $teacher_type = $simple_info['teacher_type'];

        //拉取上个月的课时信息
        $last_lesson_count = $this->get_last_lesson_count_info($start_time,$end_time,$teacherid);
        $time_list   = [];
        $lesson_list = [];
        $lesson_info = $this->t_lesson_info->get_lesson_list_for_wages($teacherid,$start_time,$end_time);
        $check_num   = [];
        if(!empty($lesson_info)){
            foreach($lesson_info as $key=>&$val){
                $base_list   = [];
                $reward_list = [];
                $full_list   = [];

                $val['lesson_base']        = "0";
                $val['lesson_reward']      = "0";
                $val['lesson_full_reward'] = "0";
                $lesson_count = $val['lesson_count']/100;
                if($val['confirm_flag'] != 2){
                    if($val['lesson_type'] != 2){
                        $val['money']       = $this->get_teacher_base_money($teacherid,$val);
                        $val['lesson_base'] = $val['money']*$lesson_count;

                        $lesson_reward = $this->get_lesson_reward_money(
                            $last_lesson_count,$val['already_lesson_count'],$val['teacher_money_type'],$teacher_type,$val['type']
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

                $this->get_lesson_cost_info($val,$check_num);
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
            if(in_array($r_val['type'],[E\Ereward_type::V_1,E\Ereward_type::V_2,E\Ereward_type::V_5])){
                \App\Helper\Utils::check_isset_data($reward_ex['price'],$reward['money']);

                if($r_val['type']==E\Ereward_type::V_2 && $r_val['userid']>0){
                    $stu_nick = $this->cache_get_student_nick($r_val['userid']);
                    $reward['money_info'] .= "|".$stu_nick;
                }

                $reward["type"] = 1;
                $reward_ex["reward_list"][] = $reward;
            }elseif(in_array($r_val['type'],[E\Ereward_type::V_3,E\Ereward_type::V_4])){
                \App\Helper\Utils::check_isset_data($reward_compensate['price'],$reward['money']);
                $reward["type"] = 2;
                $reward_compensate["reward_list"][] = $reward;
            }elseif(in_array($r_val['type'],[E\Ereward_type::V_6])){
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

    // /**
    //  * 获得老师扣款条目
    //  */
    // private function get_lesson_cost_info(&$val){
    //     $lesson_all_cost = 0;
    //     $deduct_type     = E\Elesson_deduct::$s2v_map;
    //     $deduct_info     = E\Elesson_deduct::$desc_map;
    //     $month           = 0;
    //     $lesson_month    = date("m",$val['lesson_start']);
    //     if($month!=$lesson_month){
    //         $month=$lesson_month;
    //         $this->change_num=0;
    //         $this->late_num=0;
    //     }

    //     if($val['confirm_flag']==2 &&  $val['deduct_change_class']>0){
    //         if($val['lesson_cancel_reason_type']==21){
    //             $lesson_all_cost = $this->teacher_money['lesson_miss_cost']/100;
    //             $info            = "上课旷课!";
    //         }elseif(($val['lesson_cancel_reason_type']==2 || $val['lesson_cancel_reason_type']==12)
    //                 && $val['lesson_cancel_time_type']==1){
    //             if($this->change_num>=3){
    //                 $lesson_all_cost = $this->teacher_money['lesson_cost']/100;
    //                 $info            = "课前４小时内取消上课！";
    //             }else{
    //                 $this->change_num++;
    //                 $lesson_all_cost = 0;
    //                 $info            = "本月第".$this->change_num."次换课";
    //             }
    //         }
    //         if(isset($info)){
    //             $cost_info['type']  = 3;
    //             $cost_info['money'] = $lesson_all_cost;
    //             $cost_info['info']  = $info;
    //             $val['list'][]      = $cost_info;
    //         }
    //     }else if($val['fail_greater_4_hour_flag']==0 && ($val['test_lesson_fail_flag']==101 || $val['test_lesson_fail_flag']==102)){
    //         $cost_info['type']  = 3;
    //         $cost_info['money'] = 0;
    //         $cost_info['info']  = "老师原因4小时内取消试听课";
    //         $val['list'][]      = $cost_info;
    //     }else{
    //         $lesson_cost = $this->teacher_money['lesson_cost']/100;
    //         $lesson_all_cost = 0;
    //         foreach($deduct_type as $key=>$item){
    //             if($val['deduct_change_class']==0){
    //                 if($val[$key]>0){
    //                     if($key=="deduct_come_late" && $this->late_num<3){
    //                         $this->late_num++;
    //                     }else{
    //                         $cost_info['type']  = 3;
    //                         $cost_info['money'] = $lesson_cost;
    //                         $cost_info['info']  = $deduct_info[$item];

    //                         $lesson_all_cost += $lesson_cost;
    //                         $val["list"][]    = $cost_info;
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     if($val['lesson_type']!=2){
    //         $val['lesson_cost_normal'] = (string)$lesson_all_cost;
    //     }else{
    //         $val['lesson_cost_normal'] = "0";
    //     }
    //     $val['lesson_cost'] = (string)$lesson_all_cost;
    // }

    /**
     * 老师工资汇总
     * teacherid 老师id
     * type wx 微信老师端 admin 后台计算老师工资明细
     */
    public function get_teacher_total_money(){
        $type       = $this->get_in_str_val("type","wx");
        $show_type  = $this->get_in_str_val("show_type","current");
        $teacherid  = $this->get_in_int_val("teacherid");

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
            $default_date = date("Y-m-d",time());
            $start_time   = strtotime($this->get_in_str_val("start_time",$default_date));
            $end_time     = strtotime($this->get_in_str_val("end_time",$default_date));
            $now_time     = strtotime("+1 day",strtotime($end_time));
            $teacher_type = $this->t_teacher_info->get_teacher_type($teacherid);
            $check_flag   = $this->check_full_time_teacher($teacherid,$teacher_type);
            if($check_flag){
                $now_time   = $start_time;
                $start_time = strtotime("-1 month",$start_time);
            }

            if($start_time=='' || $now_time==''){
                return $this->output_err("时间错误!");
            }
        }else{
            return $this->output_err("参数错误!");
        }

        $teacher_info = $this->get_teacher_info_for_total_money($teacherid);
        $list = $this->get_teacher_lesson_money_list($teacherid,$start_time,$now_time,$show_type);

        return $this->output_succ([
            "teacher_info" => $teacher_info,
            "data"         => $list,
        ]);
    }

    /**
     * 修改中，没有使用此方法
     */
    public function get_teacher_total_money_new(){
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
            $default_date = date("Y-m-d",time());
            $start_time   = strtotime($this->get_in_str_val("start_time",$default_date));
            $end_time     = strtotime($this->get_in_str_val("end_time",$default_date));
            $now_time     = strtotime("+1 day",strtotime($end_time));
            $teacher_type = $this->t_teacher_info->get_teacher_type($teacherid);
            $check_flag   = $this->check_full_time_teacher($teacherid,$teacher_type);
            if($check_flag){
                $now_time   = $start_time;
                $start_time = strtotime("-1 month",$start_time);
            }

            if($start_time=='' || $now_time==''){
                return $this->output_err("时间错误!");
            }
        }else{
            return $this->output_err("参数错误!");
        }

        $teacher_info = $this->get_teacher_info_for_total_money($teacherid);
        // $list = $this->get_teacher_lesson_money_list_test($teacherid,$start_time,$now_time,$show_type);
        $list = [];

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
     * 获取春晖奖
     */
    public function get_teacher_chunhui_reward(){
        $start_time = strtotime("2017-9-1");
        $chunhui_list = $this->t_teacher_money_list->get_teacher_chunhui_list($start_time);

        $ret_list   = [];
        $grade_list = [];
        $rank_lis   = [];
        $chunhui = array_flip(E\Echunhui_reward::$desc_map);
        foreach($chunhui_list as $val){
            $year      = date("Y",$val['add_time']);
            $month     = date("n月",$val['add_time']);
            $data_key  = $year."_".$month;
            $grade     = $val['grade'];
            $grade_str = E\Egrade::get_desc($grade);
            $money     = $val['money'];

            if(isset($chunhui[$money])){
                $rank = $chunhui[$money];
            }else{
                continue;
            }


            $grade_list[$data_key][$grade][] = [
                "rank" => $rank,
                "name" => $val['nick'],
            ];
            if(!isset($ret_list[$data_key])){
                $ret_list[$data_key] = [
                    "year"      => $year,
                    "month"     => $month,
                    "rank_info" => $grade_list[$data_key]
                ];
            }else{
                $ret_list[$data_key]["rank_info"] = $grade_list[$data_key];
            }
        }
        $ret_list = array_values($ret_list);

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
     * 添加老师额外奖金
     * @param type 额外奖金类型 枚举类 reward_type
     * @param teacherid 老师id
     * @param money_info 获奖信息 1 为课时数 2,3均为lessonid信息 4 为补偿原因
     * @param money 奖金金额
     */
    public function add_teacher_reward(){
        if(!\App\Helper\Utils::check_env_is_release()){
            $ret = $this->add_teacher_reward_2018_01_21();
            return $ret;
        }

        $type       = $this->get_in_int_val("type");
        $grade      = $this->get_in_int_val("grade");
        $teacherid  = $this->get_in_int_val("teacherid");
        $money_info = $this->get_in_str_val("money_info");
        $money      = $this->get_in_int_val("money");
        $add_date   = $this->get_in_str_val("add_time",date("Y-m-d",time()));
        $acc        = $this->get_account();

        if(in_array($type,[E\Ereward_type::V_1,E\Ereward_type::V_4])){
            if(!in_array($acc,["adrian","jim","sunny"])){
                return $this->output_err("此用户没有添加奖励权限！");
            }
        }

        $add_time   = strtotime($add_date);
        $check_flag = \App\Helper\Utils::check_teacher_salary_time($add_time);
        if(!$check_flag){
            return $this->output_err("无法添加奖金到<font color='red'>已经结算工资</font>的月份!");
        }

        if($type<100 && $money<0){
            return $this->output_err("该类型金额不能为负数！");
        }elseif($type>100 && $money>0){
            return $this->output_err("该类型金额不能为正数！");
        }

        $update_arr = [
            "teacherid"  => $teacherid,
            "type"       => $type,
            "add_time"   => $add_time,
            "money"      => $money,
            "money_info" => $money_info,
            "acc"        => $acc,
        ];

        if($type==E\Ereward_type::V_6){
            $ret = $this->add_reference_price($teacherid,$money_info,false);
            $this->t_user_log->add_teacher_reward_log($teacherid, $update_arr);
            return $this->output_ret($ret);
        }elseif($type == E\Ereward_type::V_7){
            $update_arr['grade'] = $grade;
        }elseif($type != E\Ereward_type::V_1){
            $check_flag = $this->t_teacher_money_list->check_is_exists($money_info,$type);
            if($check_flag){
                return $this->output_err("此类型奖励已存在!");
            }

            if($type==E\Ereward_type::V_2){
                $teacher_money_type = $this->t_teacher_info->get_teacher_money_type($teacherid);
                if(!in_array($teacher_money_type,[0,4,5,6,7])){
                    return $this->output_err("老师工资分类错误！");
                }
                $update_arr['lessonid'] = $money_info;
            }elseif($type==E\Ereward_type::V_3){
                $lesson_money_info = $this->t_lesson_info->get_lesson_money_info($money_info);
                $lesson_start_time = $lesson_money_info['lesson_start'];
                $lesson_end_time   = $lesson_money_info['lesson_end'];
                $diff_time         = $lesson_end_time-$lesson_start_time;
                $check_time        = 90*60;
                if($diff_time != $check_time){
                    return $this->output_err("本节课不是90分钟，无法添加90分钟的课程补偿。");
                }
                $check_lesson_count = \App\Helper\Utils::get_lesson_count($lesson_start_time, $lesson_end_time);
                if($lesson_money_info['lesson_count']==$check_lesson_count){
                    return $this->output_err("课程课时正确，不用补偿!");
                }

                $base_money = $lesson_money_info['money'];
                $start      = strtotime(date("Y-m-01",$lesson_money_info['lesson_start']));
                $end        = strtotime("+1 month",$start);
                $last_lesson_count = $this->get_last_lesson_count_info($start,$end,$lesson_money_info['teacherid']);
                $teacher_type      = $this->t_teacher_info->get_teacher_type($lesson_money_info['teacherid']);
                $reward_money      = $this->get_lesson_reward_money(
                    $last_lesson_count,$lesson_money_info['already_lesson_count'],$lesson_money_info['teacher_money_type'],
                    $teacher_type,$lesson_money_info['type']
                );

                $money = ($base_money+$reward_money)*25;
                $update_arr['money'] = $money;
            }elseif($type==E\Ereward_type::V_4 && $money_info==""){
                return $this->output_err("请填写补偿原因！");
            }
        }

        $ret = $this->t_teacher_money_list->row_insert($update_arr);
        if($ret){
            $this->t_user_log->add_teacher_reward_log($teacherid, $update_arr);
        }
        return $this->output_ret($ret);
    }

    /**
     * 添加老师额外奖金
     * @param int type 1 荣誉榜奖金 2 试听课奖金 3 90分钟课程补偿 4 工资补偿
     * @param int teacherid 老师id
     * @param string money_info 获奖信息 1 为课时数 2,3均为lessonid信息 4 为补偿原因
     * @param int money 奖金金额
     * @param int grade 年级  目前仅有春晖奖有效
     * @param string add_date 额外奖金的发放时间
     */
    public function add_teacher_reward_2018_01_21(){
        $type       = $this->get_in_int_val("type");
        $grade      = $this->get_in_int_val("grade");
        $teacherid  = $this->get_in_int_val("teacherid");
        $money_info = $this->get_in_str_val("money_info");
        $money      = $this->get_in_int_val("money");
        $add_date   = $this->get_in_str_val("add_time",date("Y-m-d",time()));
        $acc        = $this->get_account();

        if(in_array($type,[E\Ereward_type::V_1,E\Ereward_type::V_4])){
            if(!in_array($acc,["adrian","jim","sunny"])){
                return $this->output_err("此用户没有添加奖励权限！");
            }
        }

        $add_time   = strtotime($add_date);
        $check_flag = \App\Helper\Utils::check_teacher_salary_time($add_time);
        if(!$check_flag){
            return $this->output_err("无法添加奖金到<font color='red'>已经结算工资</font>的月份!");
        }

        if($type<100 && $money<0){
            return $this->output_err("该类型金额不能为负数！");
        }elseif($type>100 && $money>0){
            return $this->output_err("该类型金额不能为正数！");
        }

        $teacher_info = $this->t_teacher_info->get_teacher_info($teacherid);
        $teacher_money_type = $teacher_info['teacher_money_type'];
        $teacher_type = $teacher_info['teacher_type'];

        $update_arr = [
            "teacherid"  => $teacherid,
            "type"       => $type,
            "add_time"   => $add_time,
            "money"      => $money,
            "money_info" => $money_info,
            "acc"        => $acc,
        ];

        //需要检测 money_info 是否为课程标示
        $need_check_lesson_flag = false;
        if($type == E\Ereward_type::V_7){
            $update_arr['grade'] = $grade;
        }elseif($type != E\Ereward_type::V_1){
            $check_flag = $this->t_teacher_money_list->check_is_exists($money_info,$type);
            if($check_flag){
                return $this->output_err("此类型奖励已存在!");
            }
            if($type==E\Ereward_type::V_2){ //签单奖
                $need_check_lesson_flag = true;
                $check_full_teacher = \App\Helper\Utils::check_teacher_is_full($teacher_money_type, $teacher_type, $teacherid);
                if(in_array($teacher_money_type,[E\Eteacher_money_type::V_4,E\Eteacher_money_type::V_5,E\Eteacher_money_type::V_6])
                   && !$check_full_teacher){
                    return $this->output_err("老师工资分类错误！");
                }
            }elseif($type==E\Ereward_type::V_3){ //90分钟课程补偿
                $need_check_lesson_flag = true;
                $lesson_money_info = $this->t_lesson_info->get_lesson_money_info($money_info);
                $add_time    = $lesson_money_info['lesson_start'];
                $diff_time   = $lesson_money_info['lesson_end']-$add_time;
                $check_time  = 90*60;
                if($diff_time != $check_time){
                    return $this->output_err("本节课不是90分钟，无法添加90分钟的课程补偿。");
                }

                $base_money = $lesson_money_info['money'];
                $start      = strtotime(date("Y-m-01",$lesson_money_info['lesson_start']));
                $end        = strtotime("+1 month",$start);
                $last_lesson_count = $this->get_last_lesson_count_info($start,$end,$lesson_money_info['teacherid']);
                $reward_money      = $this->get_lesson_reward_money(
                    $last_lesson_count,$lesson_money_info['already_lesson_count'],$lesson_money_info['teacher_money_type'],
                    $lesson_money_info['teacher_type'],$lesson_money_info['type']
                );

                $money = ($base_money+$reward_money)*25;
                $update_arr['money'] = $money;
            }elseif($type==E\Ereward_type::V_4 && $money_info==""){ //工资补偿
                return $this->output_err("请填写补偿原因！");
            }
        }

        if($need_check_lesson_flag){
            $update_arr['lessonid'] = $money_info;
            $check_lesson_info=$this->t_lesson_info->get_lesson_info($money_info);
            if(empty($check_lesson_info)){
                return $this->output_err("没有该课程的信息，无法添加！");
            }
        }

        if($type == E\Ereward_type::V_6){
            $ret = $this->add_reference_price_2018_01_21($teacherid,$money_info,false);
            $update_arr['recommended_teacherid'] = $money_info;
        }else{
            $ret = $this->t_teacher_money_list->row_insert($update_arr);
        }
        if($ret){
            $log_arr = [
                "add_info" => $update_arr
            ];
            $msg = json_encode($log_arr);
            $this->t_user_log->add_user_log($teacherid,$msg,E\Euser_log_type::V_200);
        }

        return $this->output_ret($ret,"添加失败，请检查该老师是否符合所添加奖励类型的规则！");
    }

    public function get_teacher_info_for_total_money($teacherid){
        $info  = $this->t_teacher_info->get_teacher_info($teacherid);
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

        $is_test_user = $this->t_teacher_info->get_is_test_user($teacherid);
        if(!$is_test_user && $type!="admin"){
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
        }
        if(isset($error_info) && $error_info!=""){
            return $this->output_err($error_info);
        }

        $old_bankcard     = $this->t_teacher_info->get_bankcard($teacherid);
        $old_bank_account = $this->t_teacher_info->get_bank_account($teacherid);
        $old_bank_type    = $this->t_teacher_info->get_bank_type($teacherid);

        $ret = $this->t_teacher_info->field_update_list($teacherid,[
            "bankcard"      => $bankcard,
            "bank_address"  => $bank_address,
            "bank_account"  => $bank_account,
            "bank_phone"    => $bank_phone,
            "bank_type"     => $bank_type,
            "idcard"        => $idcard,
            "bank_city"     => $bank_city,
            "bank_province" => $bank_province,
            'bind_bankcard_time' => time()
        ]);

        if(!$ret && $bankcard!=$old_bankcard){
            return $this->output_err("更新失败！请重试！");
        }

        if(($old_bankcard != $bankcard) || ($old_bank_account != $bank_account) || ($old_bank_type != $bank_type) ){
            $tea_nick   = $this->t_teacher_info->get_realname($teacherid);
            $header_msg = $tea_nick."老师，修改了绑定的银行信息。";
            $msg  = "持卡人姓名：$tea_nick \n 银行卡类型： $bank_type \n 卡号：$bankcard";
            $url  = "/teacher_money/show_teacher_bank_info?teacherid=".$teacherid;
            $desc = "点击详情，查看修改后的银行卡号及详细信息";

            $this->t_manager_info->send_wx_todo_msg("sunny","银行卡信息变更",$header_msg,$msg,$url,$desc);

            if($type=="admin"){
                $acc = $this->get_account();
                $record_info = $acc."修改了".$tea_nick."的银行卡信息";
                $this->t_teacher_record_list->add_teacher_action_log($teacherid,$record_info,$acc);
            }
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

    /**
     * 获取老师工资
     */
    public function get_teacher_salary($teacherid,$start_time,$end_time){
        $salary_info = $this->get_teacher_lesson_money_list($teacherid,$start_time,$end_time);
        return $salary_info[0];
    }

    public function teacher_salary_list(){
        $acc = $this->get_account();
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,E\Eopt_date_type::V_3);
        $teacher = $this->get_in_int_val('teacher',-1);
        $teacher_type = $this->get_in_int_val("teacher_type",-1);
        $teacherid = $this->get_in_int_val('teacherid',-1);

        $ret_info = $this->t_teacher_salary_list->get_salary_list($start_time,$end_time,$teacher_type,$teacher);
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

        // $this->set_filed_for_js("g_adminid",$this->get_account_id());
        return $this->pageView(__METHOD__,$ret_info,[
            "all_money"     => $all_money,
            "all_money_tax" => $all_money_tax,
            'all_all_money' => $all_all_money,
            'all_not_money' => $all_not_money,
            "acc"           => $acc,
        ]);
    }

    public function update_pay_time() {
        $id = $this->get_in_int_val("id");
        $pay_time = strtotime($this->get_in_str_val("pay_time"));

        $this->t_teacher_salary_list->field_update_list($id, [
            "pay_time" => $pay_time
        ]);
        return $this->output_succ();
    }

    public function get_teacher_type() {
        $teacherid = $this->get_in_int_val("teacherid");
        //$type = $this->t_teacher_info->get_teacher_type($teacherid);
        $info = $this->t_teacher_info->field_get_list($teacherid, "teacher_money_type,teacher_type");
        if ($info['teacher_money_type'] == 7 || ($info['teacher_type'] == 3 && $info['teacher_money_type'] == 0)) {
            $type = '全职老师';
        } else {
            $type = '兼职老师';
        }
        // else {
        //     $type = E\Eteacher_type::get_desc($type);
        // }
        return $this->output_succ(["type"=>$type]);
    }

    public function show_teacher_bank_info_human() { // 人事绩效 - 老师银行卡信息
        $isbank = $this->get_in_int_val("is_bank", 1);
        $page_info = $this->get_in_page_info();
        $teacherid = $this->get_in_int_val("teacherid", -1);
        $ret_info = $this->t_teacher_info->get_teacher_bank_info($isbank, $teacherid, $page_info);
        $acc = $this->get_account();

        foreach($ret_info['list'] as $key => &$item) {
            $ret_info['list'][$key]['bind_bankcard_time_str'] = '';
            if ($item['bind_bankcard_time']) {
                $ret_info['list'][$key]['bind_bankcard_time_str'] = date('Y-m-d H:i:s', $item['bind_bankcard_time']);
            }
            E\Esubject::set_item_value_str($item);
            $item["phone"] = preg_replace('/(1[3456789]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$item['phone']);
            $item["bank_phone"] = preg_replace('/(1[3456789]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$item['bank_phone']);
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    /**
     * command:update_bole_reward  每天晚上23:59 更新伯乐奖
     */
    public function update_bole_reward($teacherid, $re_teacherid) {
        $this->add_reference_price($teacherid, $re_teacherid, false);
    }

    /**
     * Command:SetTeacherMoney  --type=5
     */
    public function set_lesson_all_money($teacherid,$start_time,$end_time){
        $this->set_teacher_all_lesson_money_list($teacherid, $start_time, $end_time);
    }

    /**
     * 设置老师的上个月的累计课时
     */
    public function set_teacher_last_lesson_count($start,$end,$teacherid){
        $last_lesson_count = $this->get_last_lesson_count_info($start,$end,$teacherid);
    }


}
