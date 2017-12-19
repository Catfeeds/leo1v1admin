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
        list($res[$adminid][E\Eweek_order::V_1],$res[$adminid][E\Eweek_order::V_2],$res[$adminid][E\Eweek_order::V_3],$res[$adminid][E\Eweek_order::V_4],$res[$adminid]['lesson_per'],$res[$adminid]['kpi'],$res[$adminid]['succ_all_count'],$res[$adminid]['fail_all_count'],$res[$adminid]['test_lesson_count']) = [[],[],[],[],0,0,0,0,0];
        //cc自定义月时间
        $def_info = $tt->t_month_def_type->get_time_by_def_time(strtotime(date('Y-m-1',$start_time)));
        $start_time_new = $def_info['start_time'];
        $end_time_new = $def_info['end_time'];
        //全月在职组员
        $adminid_list = $tt->t_admin_group_name->get_group_admin_list($adminid);
        $adminid_list = array_unique(array_column($adminid_list,'adminid'));
        $adminid_info = $tt->t_manager_info->get_group_admin_list($adminid_list);
        foreach($adminid_info as $key=>$item){
            $adminid = $item['adminid'];
            $full_month_flag = 1;
            $del_flag = $item['del_flag'];
            $create_time = $item['create_time'];
            $leave_member_time = $item['leave_member_time'];
            if($del_flag == 0){
                if($create_time>$start_time_new){
                    $full_month_flag = 0;
                }
            }else{
                if($leave_member_time<$end_time_new){
                    $full_month_flag = 0;
                }
            }
            $adminid_info[$key]['full_month_flag'] = $full_month_flag;
            if($full_month_flag == 0){
                foreach($adminid_list as $key_i=>$info){
                    if($adminid == $info){
                        unset($adminid_list[$key_i]);
                        break;
                    }
                }
            }
            $adminid_info[$key]['create_time_str'] = $create_time?date('Y-m-d H:i:s',$create_time):'';
            $adminid_info[$key]['leave_member_time_str'] = $leave_member_time?date('Y-m-d H:i:s',$leave_member_time):'';
        }
        //cc自定义试听成功周
        $ret_new = $tt->t_month_def_type->get_month_week_time($start_time_new);
        $test_lesson_list = $tt->t_test_lesson_subject_require->tongji_test_lesson_group_by_admin_revisiterid_new($start_time_new,$end_time_new,$grade_list=[-1] , $origin_ex="",$adminid,$adminid_list);
        foreach($test_lesson_list['list'] as $item){
            $adminid = $item['admin_revisiterid'];
            $res[$adminid]['succ_all_count']=$item['succ_all_count'];
            $res[$adminid]['fail_all_count'] = $item['fail_all_count'];
            $res[$adminid]['test_lesson_count'] = $item['test_lesson_count'];
        }
        dd($res);
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