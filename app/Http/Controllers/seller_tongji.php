<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;

class seller_tongji extends Controller
{
    use CacheNick;
    use TeaPower;
    var $change_num = 0;
    var $late_num   = 0;

    function __construct( $check_login_flag=true)  {
        $this->check_login_flag =$check_login_flag;
        parent::__construct();
        $this->teacher_money = \App\Helper\Config::get_config("teacher_money");
    }

    public function month_tongji_report(){
        \App\Helper\Utils::logger("START");
        $start = strtotime(date('Y-m-01',time()));
        $day = intval(ceil((time()-$start)/86400)-1);
        $day = $day-2*$day;
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],3);
        $end_time_new = $end_time;
        $start_time_new = $start_time;

        if($end_time >= time()){
            $end_time = time();
        }
        $common_new = new \App\Http\Controllers\common_ex;
        $month = $common_new->get_seller_month($start_time,$end_time)[0];
        $group_adminid_list = $common_new->get_month_group_adminid_list($month);
        $start_first = date('Y-m-01',$start_time);
        $res = [];
        $this->t_seller_month_money_target->switch_tongji_database();
        $ret_info = $this->t_seller_month_money_target->get_seller_month_time_info($start_first);
        $start_day = date('d',$start_time);
        $end_day = date('d',($end_time-10));
 
        foreach($ret_info as $k=>&$item){
            $month_time = json_decode($item['month_time'],true);
            $i = $j = $l=0;
            $now = time();
            $day = ceil(($end_time- $start_time)/86400);
            if(!empty($month_time)){
                foreach($month_time as $val){
                    if(substr($val[0],11,1) ==1){
                        $i++;
                    }
                    if(substr($val[0],11,1) ==1 && substr($val[0],8,2) <= $end_day && substr($val[0],8,2) >= $start_day ){
                        $j++;
                    }
                }
                $leave_and_overtime = json_decode($item['leave_and_overtime'],true);
                if(!empty($leave_and_overtime)){
                    foreach($leave_and_overtime as $v){
                        if(substr($v[0],11,1) ==2 && substr($v[0],8,2) <= $end_day && substr($v[0],8,2) >= $start_day ){
                            $l--;
                        }
                        if(substr($v[0],11,1) ==3 && substr($v[0],8,2) <= $end_day && substr($v[0],8,2) >= $start_day ){
                            $l++;
                        }

                    }
                }
            }
            $res[$k]['month_work_day'] = $i;
            $res[$k]['month_work_day_now'] = $j;
            $res[$k]['month_work_day_now_real'] = $j+$l;
            $res[$k]['target_personal_money'] = $item['personal_money'];
        }
   
        $this->check_and_switch_tongji_domain();
        // $this->t_admin_group_user->switch_tongji_database();
        $group_money_info = $this->t_admin_group_user->get_seller_month_money_info($start_first,$group_adminid_list);
        $num_info = $this->t_admin_group_user->get_group_num($start_time);
        foreach($group_money_info as &$item){
            $groupid = $item['groupid'];
            if($groupid >0 && isset($num_info[$groupid])){
                $res[$item['adminid']]['target_money'] =  $item['month_money']/$num_info[$groupid]['num'];
            }
        }
        //试听申请数
        // $this->t_test_lesson_subject_require->switch_tongji_database();
        $tr_info=$this->t_test_lesson_subject_require->tongji_require_test_lesson_group_by_admin_revisiterid_new($start_time,$end_time,$grade_list=[-1],$origin_ex="",$group_adminid_list);
        foreach($tr_info['list'] as $item){
            $adminid = $item['admin_revisiterid'];
            $res[$adminid]['require_test_count_for_month']=$item['require_test_count'];
            if(isset($res[$adminid]['month_work_day_now_real']) && $res[$adminid]['month_work_day_now_real'] != 0){
                $res[$adminid]['require_test_count_for_day'] = round($item['require_test_count']/$res[$adminid]['month_work_day_now_real']);
            }
        }
        //教务排课数
        $test_leeson_list=$this->t_test_lesson_subject_require->tongji_test_lesson_group_by_admin_revisiterid_new_two($start_time,$end_time,$grade_list=[-1],$origin_ex="",$group_adminid_list);
        foreach($test_leeson_list['list'] as $item){
            $adminid = $item['admin_revisiterid'];
            $res[$adminid]['test_lesson_count_for_month'] = $item['test_lesson_count'];
        }

        // $this->t_order_info->switch_tongji_database();
        $order_new = $this->t_order_info->get_1v1_order_list_by_adminid($start_time,$end_time,$stu_from_type=-1,$adminid=-1,$adminid_list=[],$group_adminid_list);
        foreach($order_new as $k=>$v){
            $res[$k]['all_new_contract_for_month'] = $v['all_new_contract'];
            if(isset($res[$k]['succ_all_count_for_month']) && $res[$k]['succ_all_count_for_month'] != 0){
                $res[$k]['order_per'] =round($v['all_new_contract']/$res[$k]['succ_all_count_for_month'],2);
            }
            $res[$k]['all_price_for_month'] = $v['all_price']/100;
            if(isset($res[$k]['target_money']) && $res[$k]['target_money'] != 0){
                $res[$k]['finish_per'] =  round($v['all_price']/100/$res[$k]['target_money'],2);
                $res[$k]['los_money'] = $res[$k]['target_money']-$v['all_price']/100;
            }
            if(isset($res[$k]['target_personal_money']) && $res[$k]['target_personal_money'] != 0){
                $res[$k]['finish_personal_per'] =  round($v['all_price']/100/$res[$k]['target_personal_money'],2);
                $res[$k]['los_personal_money'] = $res[$k]['target_personal_money']-$v['all_price']/100;
            }
        }
        foreach ($res as $ret_k=> &$res_item) {
            $res_item["adminid"] = $ret_k ;
        }
        list($member_new,$member_num_new,$member,$member_num,$become_member_num_l1,$leave_member_num_l1,$become_member_num_l2,$leave_member_num_l2,$become_member_num_l3,$leave_member_num_l3) = [[],[],[],[],0,0,0,0,0,0];
        $ret_info=\App\Helper\Common::gen_admin_member_data_new($res,[],0,strtotime(date("Y-m-01",$start_time)),$group_adminid_list);
        foreach( $ret_info as $key=>&$item ){
            $item["become_member_time"] = isset($item["create_time"])?$item["create_time"]:0;
            $item["leave_member_time"] = isset($item["leave_member_time"])?$item["leave_member_time"]:0;
            $item["del_flag"] = isset($item["del_flag"])?$item["del_flag"]:0;
            $item['suc_lesson_count_rate_all'] = isset($item["suc_lesson_count_rate_all"])?$item["suc_lesson_count_rate_all"]:0;
            E\Emain_type::set_item_value_str($item);
            E\Eseller_level::set_item_value_str($item);


            $item['finish_per'] =@$item['target_money']!=0?(round(@$item['all_price_for_month']/$item['target_money'],2)*100)."%":0;
            $item['finish_personal_per'] =@$item['target_personal_money']!=0?(round(@$item['all_price_for_month']/$item['target_personal_money'],2)*100)."%":0;
            $item['duration_count_for_day'] = \App\Helper\Common::get_time_format(@$item['duration_count_for_day']);
            $item['ave_price_for_month'] =@$item['all_new_contract_for_month']!=0?round(@$item['all_price_for_month']/@$item['all_new_contract_for_month']):0;
            $item['los_money'] = @$item['target_money']-@$item['all_price_for_month'];
            $item['los_personal_money'] = @$item['target_personal_money']-@$item['all_price_for_month'];

            if($item['level'] == "l-5" ){
                $item['target_money']="";
                $item['finish_per'] = "";
                $item['los_money'] = "";
                \App\Helper\Utils::unixtime2date_for_item($item,"become_member_time",'','Y-m-d');
                \App\Helper\Utils::unixtime2date_for_item($item,"leave_member_time",'','Y-m-d');
                $item["del_flag_str"] = \App\Helper\Common::get_boolean_color_str($item["del_flag"]);
                $item["del_flag"]?$leave_member_num_l3++:$become_member_num_l3++;
                $item["del_flag"]?$leave_member_num_l2++:$become_member_num_l2++;
                $item['become_member_num'] = $become_member_num_l3;
                $item['leave_member_num'] = $leave_member_num_l3;
            }else{
                $item["become_member_time"] = '';
                $item["leave_member_time"] = '';
                $item["del_flag_str"] = '';
                $item['become_member_num'] = '';
                $item['leave_member_num'] = '';
                $item['suc_lesson_count_rate'] = '';
                $item['kpi'] = '';
            }

            if($item['level'] == 'l-4'){
                $member[] = [
                    "first_group_name"  => $item['first_group_name'],
                    "up_group_name"     => $item['up_group_name'],
                    "group_name"        => $item['group_name'],
                ];
                $member_num[] = [
                    'become_member_num' => $become_member_num_l3,
                    'leave_member_num'  => $leave_member_num_l3,
                ];

                $become_member_num_l3 = 0;
                $leave_member_num_l3 = 0;
            }

            if($item['level'] == 'l-3'){
                $member_new[] = [
                    "first_group_name" => $item['first_group_name'],
                    "up_group_name" => $item['up_group_name'],
                    "group_name"    => $item['group_name'],
                ];
                $member_num_new[] = [
                    'become_member_num' => $become_member_num_l2,
                    'leave_member_num'  => $leave_member_num_l2,
                ];

                $become_member_num_l2 = 0;
                $leave_member_num_l2 = 0;
            }
            if(in_array($item['main_type_str'], ['助教','未定义','全部'])){
                unset($ret_info[$key]);
            }
            if(isset($item['target_money'])){
                $item['target_money'] = round($item['target_money']);
            }
            if(isset($item['los_money'])){
                $item['los_money'] = round($item['los_money']);
            }
            if(isset($item['all_price_for_month'])){
                $item['all_price_for_month'] = round($item['all_price_for_month']);
            }
            if(isset($item['ave_price_for_month'])){
                $item['ave_price_for_month'] = round($item['ave_price_for_month']);
            }
            if(isset($item['los_personal_money'])){
                $item['los_personal_money'] = round($item['los_personal_money']);
            }
        }
        foreach($member as $key=>&$item){
            foreach($member_num as $k=>$info){
                if(($key+1) == $k){
                    $item['become_member_num'] = $info['become_member_num'];
                    $item['leave_member_num'] = $info['leave_member_num'];
                }
            }
            $item['become_member_num'] = isset($item['become_member_num'])?$item['become_member_num']:'';
            $item['leave_member_num'] = isset($item['leave_member_num'])?$item['leave_member_num']:'';
        }
        foreach($member_new as $key=>&$item){
            foreach($member_num_new as $k=>$info){
                if(($key+1) == $k){
                    $item['become_member_num'] = $info['become_member_num'];
                    $item['leave_member_num'] = $info['leave_member_num'];
                }
            }
            $item['become_member_num'] = isset($item['become_member_num'])?$item['become_member_num']:'';
            $item['leave_member_num'] = isset($item['leave_member_num'])?$item['leave_member_num']:'';
        }
        foreach($ret_info as &$item){
            if(in_array($item['main_type_str'], ['助教','未定义','全部'])){
                unset($item);
            }else{
                if($item['level'] == 'l-3'){
                    foreach($member_new as $info){
                        if($item['up_group_name'] == $info['up_group_name']){
                            $item['become_member_num'] = $info['become_member_num'];
                            $item['leave_member_num'] = $info['leave_member_num'];
                        }
                    }
                }else{
                    if($item['level'] == 'l-4'){
                        foreach($member as $info){
                            if($item['group_name'] == $info['group_name']){
                                $item['become_member_num'] = $info['become_member_num'];
                                $item['leave_member_num'] = $info['leave_member_num'];
                            }
                        }
                    }else{
                        $item['become_member_num'] = '';
                        $item['leave_member_num'] = '';
                    }
                }
            }
        }


        \App\Helper\Utils::logger("OUTPUT");
       
        $test_lesson_succ_all = $this->t_test_lesson_subject_require->tongji_test_lesson_group_by_admin_revisiterid_new_succ_all($start_time_new,$end_time_new,$grade_list=[-1] , $origin_ex="");
        $this->set_filed_for_js("test_lesson_succ_all",@$test_lesson_succ_all["dis_succ_all_count"]);

        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info),
                               [
                                   '_publish_version' => 201712071914
                               ]
        );
    }

}  
?>