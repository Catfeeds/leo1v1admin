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
        //cc自定义月时间
        $def_info = $tt->t_month_def_type->get_time_by_def_time(strtotime(date('Y-m-1',$start_time)));
        $start_time_new = $def_info['start_time'];
        $end_time_new = $def_info['end_time'];
        //全月在职组员
        // $adminid_list = $tt->t_admin_group_name->get_group_admin_list($adminid);
        $adminid_list = $tt->t_group_name_month->get_group_admin_list($adminid,strtotime(date('Y-m-1',$start_time)));
        if(!$adminid_list){
            $arr['kpi'] = '';
            $arr['kpi_desc'] = '';
            return $arr;
        }
        $adminid_list = array_unique(array_column($adminid_list,'adminid'));
        $person_count = count($adminid_list);
        $leave_count  = 0;
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
                if($leave_member_time>$start_time_new && $leave_member_time<$end_time_new){
                    $leave_count += 1;
                }
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
        $full_count = count($adminid_list);
        //试听
        $ret_new = $tt->t_month_def_type->get_month_week_time($start_time_new);
        $tt->t_test_lesson_subject_require->switch_tongji_database();
        $test_lesson_list = $tt->t_test_lesson_subject_require->tongji_test_lesson_group_by_admin_revisiterid_new($start_time_new,$end_time_new,$grade_list=[-1] , $origin_ex="",$adminid,$adminid_list);
        $res = [];
        foreach($test_lesson_list['list'] as $item){
            $adminid = $item['admin_revisiterid'];
            $res[$adminid]['succ_all_count']=$item['succ_all_count'];
            $res[$adminid]['dis_succ_all_count']=$item['dis_succ_all_count'];
            $res[$adminid]['fail_all_count'] = $item['fail_all_count'];
            $res[$adminid]['test_lesson_count'] = $item['test_lesson_count'];
            $res[$adminid]['all_new_contract'] = 0;
        }
        //签单
        $tt->t_order_info->switch_tongji_database();
        $order_list = $tt->t_order_info->get_1v1_order_list_by_adminid($start_time_new,$end_time_new,-1,-1,$adminid_list);
        foreach($order_list as $item){
            $adminid = $item['adminid'];
            $all_new_contract = $item['all_new_contract'];
            $res[$adminid]['all_new_contract'] = $all_new_contract;
        }
        $test_lesson_count = array_sum(array_column($res,'test_lesson_count'));//学生上课数
        $fail_all_count = array_sum(array_column($res,'fail_all_count'));//取消数
        $succ_all_count = array_sum(array_column($res,'succ_all_count'));//试听成功数
        $dis_succ_all_count = array_sum(array_column($res,'dis_succ_all_count'));//试听成功数
        $all_new_contract = array_sum(array_column($res,'all_new_contract'));//签约数

        $test_per = ($full_count>0 && round($test_lesson_count/$full_count,2)>=50.00)?10:0;//平均课数
        $fail_per = ($test_lesson_count>0 && round($fail_all_count/$test_lesson_count,4)*100<=18.00)?10:0;//取消率
        $order_per = ($succ_all_count>0 && round($all_new_contract/$succ_all_count,4)*100>=10.00)?40:0;//转化率
        $leave_per = ($person_count>0 && round($leave_count/$person_count,4)*100<=20.00)?40:0;//离职率

        $test_per_desc = ($full_count>0)?$test_lesson_count.'÷'.$full_count:0;
        $fail_per_desc = ($test_lesson_count>0)?$fail_all_count.'÷'.$test_lesson_count:0;
        $order_per_desc = ($succ_all_count>0)?$all_new_contract.'÷'.$succ_all_count:0;
        $leave_per_desc = ($person_count>0)?$leave_count.'÷'.$person_count:0;

        $kpi = $test_per+$fail_per+$order_per+$leave_per;
        $kpi_desc = $test_per_desc.'+'.$fail_per_desc.'+'.$order_per_desc.'+'.$leave_per_desc;
        $arr['group_kpi'] = $kpi.'%';
        $arr['group_kpi_desc'] = $kpi_desc;

        $group_month_avg_lesson = $full_count>0?round($test_lesson_count/$full_count,2):0;
        $group_month_avg_lesson_per = $test_lesson_count>0?round($fail_all_count/$test_lesson_count,4):0;
        $group_month_avg_order_per = $dis_succ_all_count>0?round($all_new_contract/$dis_succ_all_count,4):0;
        $group_month_avg_leave_per = $person_count>0?round($leave_count/$person_count,4):0;
        $arr['group_month_avg_lesson'] = $group_month_avg_lesson;
        $arr['group_month_avg_lesson_per'] = $group_month_avg_lesson_per;
        $arr['group_month_avg_order_per'] = $group_month_avg_order_per;
        $arr['group_month_avg_leave_per'] = $group_month_avg_leave_per;
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