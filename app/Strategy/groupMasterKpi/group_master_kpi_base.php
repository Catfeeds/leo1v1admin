<?php
namespace App\Strategy\groupMasterKpi  ;

use \App\Enums as E;
class group_master_kpi_base {

    static $cur_type = E\Egroup_master_kpi::V_201711;

    static $type_config=[
        E\Egroup_master_kpi::V_201711=>  group_master_kpi_base::class,
    ];

    static $percent_config = [
        0     => 0,
        20000 => 3,
        50000 => 5,
        80000 => 8,
        130000 => 10,
        180000 => 12,
        230000 => 15,
    ];


    static function get_value_from_config($config,$check_key,$def_value=0) {
        $last_value=$def_value;
        foreach ($config as  $k =>$v ) {
            if ($k > $check_key )  {
                return $last_value;
            }
            $last_value= $v;
        }
        return $last_value;
    }
    static function get_pecent_config( $money ) {
        return static::get_value_from_config(static::$percent_config  ,$money);
    }

    static function  get_info( $adminid, $start_time, $end_time  )  {
        /** @var $tt \App\Console\Tasks\TaskController */
        $tt= new \App\Console\Tasks\TaskController();
        //试听成功数
        list($res[$adminid][E\Eweek_order::V_1],$res[$adminid][E\Eweek_order::V_2],$res[$adminid][E\Eweek_order::V_3],$res[$adminid][E\Eweek_order::V_4],$res[$adminid]['lesson_per'],$res[$adminid]['kpi'],$res[$adminid]['fail_all_count'],$res[$adminid]['test_lesson_count']) = [[],[],[],[],0,0,0,0];
        list($start_time_new,$end_time_new)= $this->get_in_date_range_month(date("Y-m-01"));
        if($end_time_new >= time()){
            $end_time_new = time();
        }
        dd($start_time_new);
        $ret_new = $this->t_month_def_type->get_month_week_time($start_time_new);
        $test_leeson_list_new = $this->t_test_lesson_subject_require->tongji_test_lesson_group_by_admin_revisiterid_new_three($start_time_new,$end_time_new,$grade_list=[-1] , $origin_ex="",$adminid);
        foreach($test_leeson_list_new['list'] as $item){
            $adminid = $item['admin_revisiterid'];
            $lesson_start = $item['lesson_start'];
            foreach($ret_new as $info){
                $start = $info['start_time'];
                $end = $info['end_time'];
                $week_order = $info['week_order'];
               if($lesson_start>=$start && $lesson_start<$end && $week_order==E\Eweek_order::V_1){
                    $res[$adminid][$week_order][] = $item;
                }elseif($lesson_start>=$start && $lesson_start<$end && $week_order==E\Eweek_order::V_2){
                    $res[$adminid][$week_order][] = $item;
                }elseif($lesson_start>=$start && $lesson_start<$end && $week_order==E\Eweek_order::V_3){
                    $res[$adminid][$week_order][] = $item;
                }elseif($lesson_start>=$start && $lesson_start<$end && $week_order==E\Eweek_order::V_4){
                    $res[$adminid][$week_order][] = $item;
                }
            }
        }
        foreach($res as $key=>$item){
            $res[$key]['suc_lesson_count_one'] = isset($item[E\Eweek_order::V_1])?count($item[E\Eweek_order::V_1]):0;
            $res[$key]['suc_lesson_count_two'] = isset($item[E\Eweek_order::V_2])?count($item[E\Eweek_order::V_2]):0;
            $res[$key]['suc_lesson_count_three'] = isset($item[E\Eweek_order::V_3])?count($item[E\Eweek_order::V_3]):0;
            $res[$key]['suc_lesson_count_four'] = isset($item[E\Eweek_order::V_4])?count($item[E\Eweek_order::V_4]):0;
            $res[$key]['suc_lesson_count_one_rate'] = $res[$key]['suc_lesson_count_one']<12?0:15;
            $res[$key]['suc_lesson_count_two_rate'] = $res[$key]['suc_lesson_count_two']<12?0:15;
            $res[$key]['suc_lesson_count_three_rate'] = $res[$key]['suc_lesson_count_three']<12?0:15;
            $res[$key]['suc_lesson_count_four_rate'] = $res[$key]['suc_lesson_count_four']<12?0:15;
            $suc_lesson_count_rate = $res[$key]['suc_lesson_count_one_rate']+$res[$key]['suc_lesson_count_two_rate']+$res[$key]['suc_lesson_count_three_rate']+$res[$key]['suc_lesson_count_four_rate'];
            $res[$key]['suc_lesson_count_rate'] = $suc_lesson_count_rate.'%';
            $res[$key]['suc_lesson_count_rate_all'] = $suc_lesson_count_rate;
        }
        if($end_time >= time()){
            $end_time = time();
        }
        $test_leeson_list=$this->t_test_lesson_subject_require->tongji_test_lesson_group_by_admin_revisiterid_new($start_time,$end_time,$grade_list=[-1] , $origin_ex="",$adminid);
        foreach($test_leeson_list['list'] as $item){
            $adminid = $item['admin_revisiterid'];
            $res[$adminid]['succ_all_count']=$item['succ_all_count'];
            $res[$adminid]['fail_all_count'] = $item['fail_all_count'];
            $res[$adminid]['test_lesson_count'] = $item['test_lesson_count'];
        }
        $lesson_per = $res[$adminid]['test_lesson_count']!=0?(round($res[$adminid]['fail_all_count']/$res[$adminid]['test_lesson_count'],2)*100):0;
        $res[$adminid]['lesson_per'] = $lesson_per>0?$lesson_per."%":0;
        $res[$adminid]['lesson_kpi'] = $lesson_per<18?40:0;
        $kpi = $res[$adminid]['lesson_kpi']+$res[$adminid]['suc_lesson_count_rate_all'];
        $res[$adminid]['kpi'] = ($kpi && $res[$adminid]['test_lesson_count']>0)>0?$kpi."%":0;
        $manager_info = $this->t_manager_info->field_get_list($adminid,'become_member_time,del_flag');
        if($manager_info["become_member_time"]>0 && ($end_time-$manager_info["become_member_time"])<3600*24*60 && $manager_info["del_flag"]==0){
            $item['kpi'] = "100%";
        }
        $arr['suc_first_week'] = $res[$adminid]['suc_lesson_count_one'];
        $arr['suc_second_week'] = $res[$adminid]['suc_lesson_count_two'];
        $arr['suc_third_week'] = $res[$adminid]['suc_lesson_count_three'];
        $arr['suc_fourth_week'] = $res[$adminid]['suc_lesson_count_four'];
        $arr['lesson_per'] = $res[$adminid]['lesson_per'];
        $arr['kpi'] = $res[$adminid]['kpi'];
        return $arr;
    }

    static function  get_info_by_type( $type, $adminid, $start_time, $end_time  )  {
        $class_name=static::$type_config[$type];
        return $class_name::get_info(  $adminid, $start_time, $end_time );
    }

    static public function get_cur_info( $adminid, $start_time, $end_time) {
        return  static::get_info_by_type(static::$cur_type , $adminid, $start_time, $end_time) ;
    }

}