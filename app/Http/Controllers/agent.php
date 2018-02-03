<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;


class agent extends Controller
{
    use CacheNick;
    public function agent_list() {
        list($start_time,$end_time)=$this->get_in_date_range_month(0);
        $userid           = $this->get_in_userid(-1);
        $phone            = $this->get_in_phone();
        $p_phone          = $this->get_in_str_val('p_phone');
        $type             = $this->get_in_int_val('agent_type');
        $page_info        = $this->get_in_page_info();
        $test_lesson_flag = $this->get_in_e_boolean(-1, "test_lesson_flag" );
        $agent_type       = $this->get_in_el_agent_type();
        $agent_level      = $this->get_in_el_agent_level();
        $order_flag       = $this->get_in_e_boolean(-1, "order_flag" );
        $l1_child_count   = $this->get_in_intval_range("l1_child_count");

        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type)
            =$this->get_in_order_by_str([],"",["all_yxyx_money" => "a.all_yxyx_money",
                                               "all_open_cush_money" => "a.all_open_cush_money",
                                               "all_have_cush_money" => "a.all_have_cush_money",
            ]);
        \App\Helper\Utils::logger("orderby_str: $order_by_str ");

        $ret_info = $this->t_agent->get_agent_info($page_info,$order_by_str ,$phone,$type,$start_time,$end_time,$p_phone, $test_lesson_flag , $agent_level ,$order_flag,$l1_child_count);
        foreach($ret_info['list'] as &$item){
            //获取用户签单量及签单金额
            $agent_order_sum = $this->t_order_info->get_agent_order_sum($item['userid']);
            $item['self_order_count'] = $agent_order_sum['self_order_count'];
            $item['self_order_price'] = $agent_order_sum['self_order_price']/100;
            //获取用户推荐学员、会员、会员+学员量
            $agent_invite_sort_info = $this->t_agent->get_invite_sort_num($item['id']);
            $item['child_student_count'] = $agent_invite_sort_info['child_student_count'];
            $item['child_member_count'] = $agent_invite_sort_info['child_member_count'];
            $item['child_student_member_count'] = $agent_invite_sort_info['child_student_member_count'];
            $status = $item["lesson_user_online_status"];
            if($status == 2){
                $item["lesson_user_online_status"] = 0;
            }
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start");
            E\Eagent_level::set_item_value_str($item);
            E\Eagent_student_status::set_item_value_str($item);
            $item["all_yxyx_money"]/= 100;
            $item["all_open_cush_money"]/= 100;
            $item["all_have_cush_money"]/= 100;

            $item['agent_type_str'] = empty($item['parentid']) ? '注册':'邀请';
            $item['is_test_lesson_str'] = empty($item['test_lessonid']) ? '未试听':'已试听';
            if($item['account_role'] == 1)
                $item['teach_assistantant'] = $item['account'].'/'.$item['name'];
            $item['agent_info'] = 1;
        }
        $agent_total_num = $ret_info['total_num'];
        return $this->pageView(__METHOD__,$ret_info,['agent_total_num'=>$agent_total_num]);
    }

    //@desn:学员列表
    public function student_list() {
        $this->check_and_switch_tongji_domain();
        list($start_time,$end_time)=$this->get_in_date_range_month(0);
        $userid           = $this->get_in_userid(-1);
        $phone            = $this->get_in_phone();
        $p_phone          = $this->get_in_str_val('p_phone');
        $type             = $this->get_in_int_val('agent_type');
        $page_info        = $this->get_in_page_info();
        $test_lesson_flag = $this->get_in_e_boolean(-1, "test_lesson_flag" );
        $agent_type       = $this->get_in_el_agent_type();
        $agent_level      = $this->get_in_el_agent_level();
        $order_flag       = $this->get_in_e_boolean(-1, "order_flag" );
        $l1_child_count   = $this->get_in_intval_range("l1_child_count");

        $ret_info = $this->t_agent->get_student_info($page_info,$phone,$type,$start_time,$end_time,$p_phone, $test_lesson_flag , $agent_level ,$order_flag,$l1_child_count);
        foreach($ret_info['list'] as &$item){
            //获取用户签单量及签单金额
            $agent_order_sum = $this->t_order_info->get_agent_order_sum($item['userid']);
            $item['self_order_count'] = $agent_order_sum['self_order_count'];
            $item['self_order_price'] = $agent_order_sum['self_order_price']/100;

            $item['is_test_lesson_str'] = empty($item['test_lessonid']) ? '未试听':'已试听';
            if($item['account_role'] == 1)
                $item['teach_assistantant'] = $item['account'].'/'.$item['name'];
            $item['agent_info'] = 1;

        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function agent_list_new(){
        list($all_count,$assigned_count,$tmk_assigned_count,$tq_no_call_count,$tq_called_count,$tq_call_fail_count,
             $tq_call_succ_valid_count,$tq_call_succ_invalid_count,$tq_call_fail_invalid_count,$have_intention_a_count,
             $have_intention_b_count,$have_intention_c_count,$require_count,$test_lesson_count,$succ_test_lesson_count,
             $order_count,$user_count,$order_all_money,$start_time,$end_time) = [[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],$this->get_in_int_val('start_time'),$this->get_in_int_val('end_time')];
        $type         = $this->get_in_int_val('type');
        $ret          = $this->t_agent->get_agent_info_new(null);
        $userid_arr   = [];
        $ret_new      = [];
        $ret_info_new = [];
        $id_arr       = array_unique(array_column($ret,'id'));
        foreach($ret as &$item){
            $item['p_nickname'] = $item['p_nickname'].'/'.$item['p_phone'];
            $item['pp_nickname'] = $item['pp_nickname'].'/'.$item['pp_phone'];
            $item["lesson_user_online_status_str"] = \App\Helper\Common::get_set_boolean_color_str($item["lesson_user_online_status"]);
            if($item['type'] == 1){
                $userid_arr[] = $item['userid'];
            }
            $item['agent_type'] = $item['type'];
            $item['a_create_time'] = $item['create_time'];
            $item['a_lesson_start'] = $item['lesson_start'];
            $item['create_time'] = \App\Helper\Utils::unixtime2date($item['create_time']);
            $item['lesson_start'] = $item['lesson_start']?\App\Helper\Utils::unixtime2date($item['lesson_start']):'';

            $id = $item['id'];
            $id_arr_new = array_unique(array_column($ret_new,'id'));
            if(in_array($id,$id_arr_new)){
            }else{
                if($item['lesson_start']){
                    if($item['lesson_start']>$item['create_time']){
                        $ret_new[] = $item;
                    }
                }else{
                    $ret_new[] = $item;
                }
            }
            //例子总数
            $id_arr_new_two = array_unique(array_column($ret_info_new,'id'));
            if(in_array($id,$id_arr_new_two)){
            }else{
                if($start_time && $end_time){
                    if($item['a_create_time']>=$start_time && $item['a_create_time']<$end_time){
                        $ret_info_new[] = $item;
                    }
                }else{
                    $ret_info_new[] = $item;
                }
            }
        }
        if(count($userid_arr)>0){
            foreach($ret_new as &$item){
                if($start_time && $end_time){
                    if($item['a_create_time']>=$start_time && $item['a_create_time']<$end_time){
                        //已分配销售
                        if($item['admin_revisiterid']>0){
                            $assigned_count[] = $item;
                        }
                        //TMK有效
                        if($item['tmk_student_status'] == 3){
                            $tmk_assigned_count[] = $item;
                        }
                        //未拨打
                        if($item['global_tq_called_flag'] == 0){
                            $tq_no_call_count[] = $item;
                        }
                        //已拨打
                        if($item['global_tq_called_flag'] != 0){
                            $tq_called_count[] = $item;
                        }
                        //未接通
                        if($item['global_tq_called_flag'] == 1){
                            $tq_call_fail_count[] = $item;
                        }
                        //已拨通-有效
                        if($item['global_tq_called_flag'] == 2 && $item['sys_invaild_flag'] == 0){
                            $tq_call_succ_valid_count[] = $item;
                        }
                        //已拨通-无效
                        if($item['global_tq_called_flag'] == 2 && $item['sys_invaild_flag'] == 1){
                            $tq_call_succ_invalid_count[] = $item;
                        }
                        //未拨通-无效
                        if($item['global_tq_called_flag'] == 1 && $item['sys_invaild_flag'] == 1){
                            $tq_call_fail_invalid_count[] = $item;
                        }
                        //有效意向(A)
                        if($item['global_tq_called_flag'] == 2 && $item['seller_student_status'] == 100){
                            $have_intention_a_count[] = $item;
                        }
                        //有效意向(B)
                        if($item['global_tq_called_flag'] == 2 && $item['seller_student_status'] == 101){
                            $have_intention_b_count[] = $item;
                        }
                        //有效意向(C)
                        if($item['global_tq_called_flag'] == 2 && $item['seller_student_status'] == 102){
                            $have_intention_c_count[] = $item;
                        }
                    }
                    if($item['a_lesson_start']>=$start_time && $item['a_lesson_start']<$end_time){
                        //预约数&&上课数
                        // if($item['accept_flag'] == 1 && $item['is_test_user'] == 0 && $item['require_admin_type'] == 2 ){
                        if($item['test_lessonid']){
                            $require_count[] = $item;
                            $test_lesson_count[] = $item;
                        }
                        //试听成功数
                        if($item['lesson_user_online_status'] == 1 ){
                            $succ_test_lesson_count[] = $item;
                        }
                    }
                }else{
                    //已分配销售
                    if($item['admin_revisiterid']>0){
                        $assigned_count[] = $item;
                    }
                    //TMK有效
                    if($item['tmk_student_status'] == 3){
                        $tmk_assigned_count[] = $item;
                    }
                    //未拨打
                    if($item['global_tq_called_flag'] == 0){
                        $tq_no_call_count[] = $item;
                    }
                    //已拨打
                    if($item['global_tq_called_flag'] != 0){
                        $tq_called_count[] = $item;
                    }
                    //未接通
                    if($item['global_tq_called_flag'] == 1){
                        $tq_call_fail_count[] = $item;
                    }
                    //已拨通-有效
                    if($item['global_tq_called_flag'] == 2 && $item['sys_invaild_flag'] == 0){
                        $tq_call_succ_valid_count[] = $item;
                    }
                    //已拨通-无效
                    if($item['global_tq_called_flag'] == 2 && $item['sys_invaild_flag'] == 1){
                        $tq_call_succ_invalid_count[] = $item;
                    }
                    //未拨通-无效
                    if($item['global_tq_called_flag'] == 1 && $item['sys_invaild_flag'] == 1){
                        $tq_call_fail_invalid_count[] = $item;
                    }
                    //有效意向(A)
                    if($item['global_tq_called_flag'] == 2 && $item['seller_student_status'] == 100){
                        $have_intention_a_count[] = $item;
                    }
                    //有效意向(B)
                    if($item['global_tq_called_flag'] == 2 && $item['seller_student_status'] == 101){
                        $have_intention_b_count[] = $item;
                    }
                    //有效意向(C)
                    if($item['global_tq_called_flag'] == 2 && $item['seller_student_status'] == 102){
                        $have_intention_c_count[] = $item;
                    }
                    //预约数&&上课数
                    if($item['test_lessonid']){
                        $require_count[] = $item;
                        $test_lesson_count[] = $item;
                    }
                    //试听成功数
                    if($item['lesson_user_online_status'] == 1 ){
                        $succ_test_lesson_count[] = $item;
                    }
                }
            }
        }
        if($type==2){ //已分配销售
            $ret_info_new = $assigned_count;
        }elseif($type == 3){ //TMK有效
            $ret_info_new = $tmk_assigned_count;
        }elseif($type == 5){ //未拨打
            $ret_info_new = $tq_no_call_count;
        }elseif($type == 6){ //已拨打
            $ret_info_new = $tq_called_count;
        }elseif($type == 7){ //未接通
            $ret_info_new = $tq_call_fail_count;
        }elseif($type == 8){ //已拨通-有效
            $ret_info_new = $tq_call_succ_valid_count;
        }elseif($type == 9){ //已拨通-无效
            $ret_info_new = $tq_call_succ_invalid_count;
        }elseif($type == 10){ //未拨通-无效
            $ret_info_new = $tq_call_fail_invalid_count;
        }elseif($type == 11){ //有效意向(A)
            $ret_info_new = $have_intention_a_count;
        }elseif($type == 12){ //有效意向(B)
            $ret_info_new = $have_intention_b_count;
        }elseif($type == 13){ //有效意向(C)
            $ret_info_new = $have_intention_c_count;
        }elseif($type == 14){ //预约数
            $ret_info_new = $require_count;
        }elseif($type == 15){ //上课数
            $ret_info_new = $test_lesson_count;
        }elseif($type == 16){ //试听成功数
            $ret_info_new = $succ_test_lesson_count;
        }
        foreach($ret_info_new as $key=>&$item){
            $item['num'] = $key+1;
        }
        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($ret_info_new));
    }

    public function agent_order_list() {
        $orderid    = $this->get_in_int_val('orderid');
        $start_time = $this->get_in_int_val('start_time');
        $end_time   = $this->get_in_int_val('end_time');
        $aid        = $this->get_in_int_val('aid');
        $pid        = $this->get_in_int_val('pid');
        $p_price    = $this->get_in_int_val('p_price');
        $ppid       = $this->get_in_int_val('ppid');
        $pp_price   = $this->get_in_int_val('pp_price');
        $userid     = $this->get_in_int_val('userid');
        $page_num   = $this->get_in_page_num();
        $page_info  = $this->get_in_page_info();
        $ret_info  = $this->t_agent_order->get_agent_order_info($page_info,$start_time,$end_time);
        foreach($ret_info['list'] as &$item){
            $item['p_price'] = $item['p_price']/100;
            $item['pp_price'] = $item['pp_price']/100;
            $item['price'] = $item['price']/100;
            E\Ep_level::set_item_value_str($item);
            E\Epp_level::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,'create_time');
            \App\Helper\Utils::unixtime2date_for_item($item,'a_create_time');
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function agent_cash_list() {
        $cash     = $this->get_in_int_val('cash');
        $cash     = $this->get_in_int_val('type');
        $nickname = $this->get_in_str_val('nickname');
        $page_num  = $this->get_in_page_num();
        $page_info = $this->get_in_page_info();
        $origin_count = $this->get_in_intval_range("origin_count");
        list($start_time,$end_time,$opt_date_str)= $this->get_in_date_range(
            -30*5, 1, 0, [
                0 => array( "ac.create_time", "申请提交时间"),
                1 => array("ac.check_money_time","财务审核时间"),
            ], 0,0, true
        );
        $cash_range = $this->get_in_intval_range('cash_range','',$is_money=1);
        $agent_check_money_flag    = $this->get_in_int_val("agent_check_money_flag", -1,E\Eagent_check_money_flag::class);
        $phone = $this->get_in_phone();
        $check_money_admin_nick = $this->get_in_str_val('check_money_admin_nick');
        $check_money_admin_id = '';
        if($check_money_admin_nick){
            if($check_money_admin_nick == -1)
                $check_money_admin_id = -1;
            else
                $check_money_admin_id = $this->t_manager_info->get_id_by_account($check_money_admin_nick);
        }
        $ret_info = $this->t_agent_cash->get_agent_cash_list($page_info,$agent_check_money_flag,$phone,$nickname,$start_time,$end_time,$opt_date_str,$cash_range,$check_money_admin_id);
        //获取统计信息
        $statistic_info = $this->t_agent_cash->get_agent_cash_person($agent_check_money_flag,$phone,$nickname,$start_time,$end_time,$opt_date_str,$cash_range,$check_money_admin_id);

        foreach($ret_info['list'] as &$item){
            //获取冻结金额
            $item['agent_cash_money_freeze'] = $this->t_agent_cash_money_freeze->get_agent_cash_money_freeze($item['id']);
            $item['cash'] = $item['cash'] - $item['agent_cash_money_freeze'];
            $item['agent_cash_money_freeze'] /= 100;
            $item['agent_check_money_flag'] = $item['check_money_flag'];
            $item['cash'] /=100 ;
            $item['all_open_cush_money'] /=100;
            $item['all_have_cush_money'] /=100;
            $item["check_money_admin_nick"]= $this->cache_get_account_nick( $item["check_money_adminid"] );
            $item['check_money_desc'] = $item['check_money_desc']?$item['check_money_desc']:'';
            E\Eagent_check_money_flag::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"check_money_time");
        }
        return $this->pageView(__METHOD__,$ret_info,[
            'cash_person_count' => $statistic_info['person_count'],
            'cash_count' => $statistic_info['cash_count'],
            'cash_refuse_money' => $statistic_info['refuse_money']/100,
            'cash_freeze_money' => $statistic_info['freeze_money']/100,
        ]);
    }
    //@desn:冻结优学优享申请金额
    public function agent_money_freeze(){
        $id = $this->get_in_id();
        $adminid = $this->get_account_id();
        $freeze_money = $this->get_in_int_val('freeze_money');
        $agent_freeze_type = $this->get_in_int_val('agent_freeze_type');
        $phone = $this->get_in_str_val('phone');
        if($agent_freeze_type == 3){
            $agent_money_ex_type = $this->get_in_int_val('agent_money_ex_type');
            $agent_activity_time = strtotime($this->get_in_str_val('agent_activity_time'));
        }else{
            $agent_money_ex_type = '';
            $agent_activity_time = '';
        }
        $cash = $this->get_in_int_val('cash');
        $to_agentid = $this->get_in_int_val('agentid');
        \App\Helper\Utils::logger(" phone $phone");
        if($freeze_money <= 0 || $freeze_money > $cash)
            return $this->output_err('冻结金额错误');
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }
        $freeze_money *=100;
        //插入冻结记录
        $insert_status = $this->t_agent_cash_money_freeze->row_insert([
            'freeze_money' => $freeze_money,
            'adminid' => $adminid,
            'create_time' => time(NULL),
            'agent_freeze_type' => $agent_freeze_type,
            'phone' => $phone,
            'agent_money_ex_type' => $agent_money_ex_type,
            'agent_activity_time' => $agent_activity_time,
            'agent_cash_id' => $id
        ]);

        // 发送推送
        if($insert_status){
            $from_agentid = '';
            $agent_money_ex_type_str = E\Eagent_money_ex_type::get_desc($agent_money_ex_type);
            $this->t_agent->send_wx_msg_freeze_cash_money($from_agentid,$to_agentid,$agent_freeze_type,$phone,$agent_money_ex_type_str,$url='',$agent_activity_time);
        }

        return $this->output_succ();
    }

    public function check(){
        $this->check_and_switch_tongji_domain();
        $ret = $this->t_seller_student_new->get_item_list();
        echo '<table border="1" width="600" align="center">';
        echo '<caption><h1>tmk标记待定状态例子</h1></caption>';
        echo '<tr bgcolor="#dddddd">';
        echo '<th>号码</th><th>TMK状态</th><th>来源</th><th>例子首次进入时间</th><th>拨打人数</th><th>最后拨打人</th><th>最后一次回访时间</th><th>当前cc</th><th>是否出现在公海</th>';
        echo '</tr>';
        foreach($ret as $item){
            echo '<tr>';
            echo '<td>'.$item['phone'].'</td>';
            echo '<td>'.E\Etmk_student_status::get_desc($item['tmk_student_status']).'</td>';
            echo '<td>'.$item['origin'].'</td>';
            echo '<td>'.date('Y-m-d H:i:s',$item['add_time']).'</td>';
            echo '<td>'.$item['call_admin_count'].'</td>';
            echo '<td>'.$this->cache_get_account_nick($item['last_contact_cc']).'</td>';
            echo '<td>'.date('Y-m-d H:i:s',$item['last_revisit_time']).'</td>';
            echo '<td>'.$this->cache_get_account_nick($item['admin_revisiterid']).'</td>';
            echo '<td>'.(($item['seller_resource_type']==1 && $item['admin_revisiterid']==0 && $item['global_seller_student_status']!=50 && $item['lesson_count_all']==0 && $item['sys_invaild_flag']==0 && ($item['hand_free_count']+$item['auto_free_count'])<5)?'是':'否').'</td>';
            echo '</tr>';
        }
        echo '</table>';
    }

    public function test_new(){
        list($start_time,$end_time,$time,$ret,$ret_info) = [0,0,1517500800,[],[]];
        $ret_threshold = $this->t_seller_edit_log->get_threshold($time);
        if(date('w')!=2){
            for($i=1;$i<=12;$i++){
                $start_time = $time-3600*24*$i;
                $end_time = $start_time+3600*24;
                if(date('w',$start_time) != 2){
                    $ret_info[$i]['start_time'] = $start_time;
                    $ret_info[$i]['end_time'] = $end_time;
                    if(count($ret_info)==10){
                        break;
                    }
                }
            }
            foreach($ret_info as $item){
                $start_time = $item['start_time'];
                $end_time = $item['end_time'];
                $ret_call = $this->t_seller_get_new_log->get_list_by_time($start_time,$end_time,$call_flag=1);
                $count_call = count(array_unique(array_column($ret_call, 'userid')));
                $ret_called = $this->t_seller_get_new_log->get_list_by_time($start_time,$end_time,$call_flag=2);
                $count_called = count(array_unique(array_column($ret_called, 'userid')));
                $ret[$start_time]['call_count'] = $count_call;
                $ret[$start_time]['called_count'] = $count_called;
                $ret[$start_time]['rate'] = $count_call>0?(round($count_called/$count_call, 4)*100):0;
                echo date('Y-m-d H:i:s',$start_time).'~'.date('Y-m-d H:i:s',$end_time)."=>拨打个数:".$count_call.',拨通个数:'.$count_called.',拨通率:'.$ret[$start_time]['rate']."%<br/>";
            }
            $rate_arr = array_column($ret, 'rate');
            $rate_avg = round(array_sum($rate_arr)/count($rate_arr),4);
            foreach($ret as $start_time=>$item){
                $ret[$start_time]['dif_square'] = round(pow($item['rate']-$rate_avg,2),2);
            }
            $pow_sqrt = round(sqrt(array_sum(array_column($ret, 'dif_square'))/(count($ret)-1)),2);

            $count_call_all = array_sum(array_column($ret, 'call_count'));
            $count_called_all = array_sum(array_column($ret, 'called_count'));
            $threshold_max = $count_call_all>0?(round($count_called_all/$count_call_all,4)*100):0;
            $threshold_min = $threshold_max-$pow_sqrt;
            echo "黄色预警线:".$threshold_max."%";
            echo "红色警戒线:".$threshold_min."%";
            // $this->t_seller_edit_log->row_insert([
            //     'type'=>E\Eseller_edit_log_type::V_4,
            //     'new'=>$threshold_max,
            //     'create_time'=>$time,
            // ]);
            // $this->t_seller_edit_log->row_insert([
            //     'type'=>E\Eseller_edit_log_type::V_5,
            //     'new'=>$threshold_min,
            //     'create_time'=>$time,
            // ]);
        }
    }

    public function del_detailid(){
        $id = $this->get_in_int_val('id',174055);
        $ret = $this->t_seller_new_count_get_detail->rwo_del_by_detail_id($id);
        dd($ret);
    }
    //处理等级头像
    public function get_top_img($adminid,$face_pic,$level_face,$ex_str){
        $datapath = $face_pic;
        $datapath_new = $level_face;
        $datapath_type = @end(explode(".",$datapath));
        $datapath_type_new = @end(explode(".",$datapath_new));
        $image_1 = $this->yuan_img($datapath);
        if($datapath_type_new == 'jpg' || $datapath_type_new == 'jpeg'){
            $image_2 = imagecreatefromjpeg($datapath_new);
        }elseif($datapath_type_new == 'png'){
            $image_2 = imagecreatefrompng($datapath_new);
        }elseif($datapath_type_new == 'gif'){
            $image_2 = imagecreatefromgif($datapath_new);
        }elseif($datapath_type_new == 'wbmp'){
            $image_2 = imagecreatefromwbmp($datapath_new);
        }else{
            $image_2 = imagecreatefromstring($datapath_new);
        }
        $image_3 = imageCreatetruecolor(imagesx($image_1),imagesy($image_1));
        $color = imagecolorallocatealpha($image_3,255,255,255,1);
        imagefill($image_3, 0, 0, $color);
        imageColorTransparent($image_3, $color);

        imagecopyresampled($image_3,$image_2,0,0,0,0,imagesx($image_3),imagesy($image_3),imagesx($image_2),imagesy($image_2));
        imagecopymerge($image_1,$image_3,0,0,0,0,imagesx($image_3),imagesx($image_3),100);
        // header('Content-type: image/jpg');
        // dd(imagepng($image_1));

        $tmp_url = "/tmp/".$adminid."_".$ex_str."_gd.png";
        imagepng($image_1,$tmp_url);
        $file_name = \App\Helper\Utils::qiniu_upload($tmp_url);
        $level_face_url = '';
        if($file_name!=''){
            $cmd_rm = "rm /tmp/".$adminid."*.png";
            \App\Helper\Utils::exec_cmd($cmd_rm);
            $domain = config('admin')['qiniu']['public']['url'];
            $level_face_url = $domain.'/'.$file_name;
        }
        return $level_face_url;
    }

    //设备版本信息
    public function get_user_agent_version(){
        list($num_pad,$num_mac,$num_win,$num_android_4,$num_android_5,$num_android_6,$num_android_7,$num_android_x,$num_no) = [0,0,0,0,0,0,0,0,0];
        $ret_info = $this->t_lesson_info_b3->get_month_list();
        foreach($ret_info as $ke=>&$item){
            $user_agent = $item['user_agent'];
            if($user_agent){
                $user_agent = json_decode($user_agent);
                foreach($user_agent as $key=>$info){
                    if($key == 'device_model'){
                        $ret_info[$ke]['device_model'] = $info;
                    }
                    if($key == 'system_version'){
                        $ret_info[$ke]['system_version'] = $info;
                    }
                }
                $device_model = $item['device_model'];
                $system_version = explode('.',$item['system_version'])[0];
                if(strpos($device_model,'iPad')!==false){
                    $num_pad++;
                }elseif(strpos($device_model,'Mac')!==false){
                    $num_mac++;
                }elseif(strpos($device_model,'Windows')!==false){
                    $num_win++;
                }else{
                    if($system_version==4){
                        $num_android_4++;
                    }elseif($system_version==5){
                        $num_android_5++;
                    }elseif($system_version==6){
                        $num_android_6++;
                    }elseif($system_version==7){
                        $num_android_7++;
                    }else{
                        $num_android_x++;
                    }
                }
            }else{
                $ret_info[$ke]['device_model'] = '';
                $ret_info[$ke]['system_version'] = '';
                $num_no++;
            }
        }
        $ret = [
            '上课数'=>count($ret_info),
            'iPad'=>$num_pad,
            'Mac'=>$num_mac,
            'Windows'=>$num_win,
            'android_4'=>$num_android_4,
            'android_5'=>$num_android_5,
            'android_6'=>$num_android_6,
            'android_7'=>$num_android_7,
            'android_x'=>$num_android_x,
            '无设备信息'=>$num_no,
        ];
        dd($ret);
    }

    public function get_my_pay($phone){
        $pay = 0;
        $phone = $this->get_in_str_val('phone',$phone,-1);
        $level = $this->check_agent_level($phone);
        if($level == 2){
            // $p_pay  = $this->t_agent->get_agent_level2_p_price_by_phone($phone);
            // $pp_pay = $this->t_agent->get_agent_level2_pp_price_by_phone($phone);
            $p_pay  = $this->t_agent_order->get_p_price_by_phone($phone);
            $pp_pay = $this->t_agent_order->get_pp_price_by_phone($phone);
            $pay    = $p_pay['price'] + $pp_pay['price'];
        }elseif($level == 1){
            // $pay = $this->t_agent->get_agent_level1_p_price_by_phone($phone);
            $pay_row = $this->t_agent_order->get_p_price_by_phone($phone);
            $pay = $pay_row['price'];
        }else{
            $pay = 0;
        }

        // return $this->output_succ(["data"=>$lesson_list]);
        return $pay;
    }

    public function get_my_cash($phone){
        $phone = $this->get_in_str_val('phone',$phone,-1);
        $level = $this->check_agent_level($phone);
        if($level == 2){
            $p_ret_list  = [];
            $pp_ret_list = [];
            // $p_ret_list  = $this->t_agent->get_agent_level2_p_order_by_phone($phone);
            // $pp_ret_list = $this->t_agent->get_agent_level2_pp_order_by_phone($phone);
            $p_ret_list  = $this->t_agent_order->get_p_order_by_phone($phone);
            $pp_ret_list  = $this->t_agent_order->get_pp_order_by_phone($phone);
            $ret_list    = array_merge($p_ret_list,$pp_ret_list);
            $cash        = $this->get_cash($ret_list);
        }elseif($level == 1){
            $ret_list = [];
            // $ret_list = $this->t_agent->get_agent_level1_order_by_phone($phone);
            $ret_list  = $this->t_agent_order->get_p_order_by_phone($phone);
            $cash     = $this->get_cash($ret_list);
        }else{
            $cash = 0;
        }

        return $cash;
    }

    public function get_cash($ret_list){
        $cash = 0;
        foreach($ret_list as $key=>$item){
            $userid     = $item['userid'];
            $pay        = $item['price'];
            $order_time = $item['order_time'];
            $ret_row    = $this->t_lesson_info_b2->get_lesson_count_by_userid($userid,$order_time);
            $count      = $ret_row['count'];
            $ret_list[$key]['count'] = $count;
            $ret_list[$key]['level1_cash'] = $pay/5;
            $ret_list[$key]['level2_cash'] = $pay-$ret_list[$key]['level1_cash'];
            if(8<=$count){
                $cash += $pay;
                $ret_list[$key]['order_cash'] = $pay;
            }elseif(2<=$count && $count<8){
                $cash += $pay/5;
                $ret_list[$key]['order_cash'] = $pay/5;
            }else{
                $cash += 0;
                $ret_list[$key]['order_cash'] = 0;
            }
        }
        $data = ['cash'=>$cash,'list'=>$ret_list];
        return $data;
    }

    public function add_agent_order(){
        $userid_array      = [];
        $orderid_array     = [];
        $userid_array_str  = '';
        $orderid_array_str = '';
        $userid_list       = $this->t_agent->get_all_userid();
        $orderid_list      = $this->t_agent_order->get_all_orderid();
        $userid_array      = array_filter(array_column($userid_list,'userid'));
        $orderid_array     = array_filter(array_column($orderid_list,'orderid'));
        $userid_array_str  = implode(',',$userid_array);
        $orderid_array_str = implode(',',$orderid_array);
        $agent_order = $this->t_order_info->get_agent_order($orderid_array_str,$userid_array_str);
        if(0<count($agent_order)){
            $this->insert_agent_order($agent_order);
        }
    }

    public function insert_agent_order($agent_order){
        foreach($agent_order as $item){
            $orderid = $item['orderid'];
            $pid = $item['pid'];
            $ppid = $item['ppid'];
            $p_phone = $item['p_phone'];
            $pp_phone = $item['pp_phone'];
            $price = $item['price'];
            $p_level = $this->check_agent_level($p_phone);
            $pp_level = $this->check_agent_level($pp_phone);
            if($p_level == 2){
                $p_price = $price/10>1000?1000:$price/10;
            }elseif($p_level == 1){
                $p_price = $price/20;
            }else{
                $p_price = 0;
            }
            if($pp_level == 2){
                $pp_price = $price/20>500?500:$price/20;
            }else{
                $pp_price = 0;
            }
            $this->t_agent_order->add_agent_order_row($orderid,$pid,$p_price,$ppid,$pp_price);
        }
    }






    public function get_user_info(){
        // $agent_id = 60;//月月
        // $agent_id = 54;//陈
        // $agent_id = 211;//Amanda
        // $agent_id = 1571;//三千笔墨绘你一世倾
        // $agent_id = 427;//周圣杰 Eros
        // $agent_id = 1509;//王朝刚
        // $agent_id = 443;//九月
        $agent_id = 435;//助教2组-戈叶伟-Amy
        $agent_info = $this->t_agent->get_agent_info_by_id($agent_id);
        if(isset($agent_info['phone'])){
            $phone = $agent_info['phone'];
        }else{
            return $this->output_err("请先绑定优学优享账号!");
        }
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }
        $student_info = [];
        $userid = $agent_info['userid'];

        $level        = (int)$agent_info['agent_level'];
        $nick         = $agent_info['nickname']?$agent_info['nickname']:$phone;
        $headimgurl   = $agent_info['headimgurl']?$agent_info['headimgurl']:'';
        $nickname     = $agent_info['nickname']?$agent_info['nickname']:'';
        $pay          = 0;
        $cash         = 0;
        $have_cash    = 0;
        $num          = 0;
        $my_num_count = $this->t_agent->get_count_by_phone($phone);
        $my_num       = $my_num_count['count']?$my_num_count['count']:0;
        $cash_item    = $this->t_agent_cash->get_cash_by_phone($phone);
        $have_cash    = $cash_item['have_cash']?$cash_item['have_cash']:0;
        if($level == 2){
            $ret       = $this->get_pp_pay_cash($phone);
            $pay       = $ret['pay'];
            $cash      = $ret['cash'];
            $num       = $ret['num'];
            $test_count = 2;
        }else{
            $agent_lsit = [];
            $agent_item = [];
            $agent_list = $this->t_agent->get_agent_list_by_phone($phone);
            foreach($agent_list as $item){
                if($phone == $item['phone']){
                    $agent_item = $item;
                }
            }
            if($agent_item){
                $test_lesson = [];
                $cash_item   = [];
                $ret_list    = ['userid'=>0,'price'=>0];
                $test_lesson = $this->t_agent->get_agent_test_lesson_count_by_id($agent_item['id']);
                $count       = count(array_unique(array_column($test_lesson,'id')));
                if(2<=$count){
                    $ret = $this->get_pp_pay_cash($phone);
                    $test_count = 2;
                }else{
                    $ret = $this->get_p_pay_cash($phone);
                    $test_count = $count;
                }
                $pay  = $ret['pay'];
                $cash = $ret['cash'];
                $num  = $ret['num'];
            }else{
                return $this->output_err("您暂无资格!");
            }
        }
        $cash_new = (int)(($cash-$have_cash/100)*100)/100;
        $cash_new = $cash_new>0?$cash_new:0;
        $data = [
            'level'      => $level,
            'nick'       => $nick,
            'pay'        => $pay,
            'cash'       => $cash_new,
            'have_cash'  => $have_cash/100,
            'num'        => $num,
            'my_num'     => $my_num,
            'count'      => $test_count,
            'headimgurl' => $agent_info['headimgurl'],
            'nickname'   => $agent_info['nickname'],
        ];
        dd($data);
    }
    public function agent_user_link () {

        $phone=$this->get_in_phone();
        $id=$this->get_in_id();
        if ($phone) {
            $agent_info= $this->t_agent->get_agent_info_by_phone($phone);
            $id=$agent_info["id"];
        }
        if ($id) {
            $phone=$this->t_agent->get_phone($id);
        }
        $this->set_filed_for_js("phone",$phone);
        $this->set_filed_for_js("id",$id);

        $list=$this->t_agent->get_link_map_list_by_ppid($id );


        $ret_info=\App\Helper\Utils::list_to_page_info($list);
        //dd($list);
        return $this->pageView(__METHOD__, $ret_info);

    }



    public function agent_user_wechat () {
        $phone=$this->get_in_phone();
        $id=$this->get_in_id();
        if ($phone) {
            $agent_info= $this->t_agent->get_agent_info_by_phone($phone);
            $id=$agent_info["id"];
        }
        if ($id) {
            $phone=$this->t_agent->get_phone($id);
        }

        $this->set_filed_for_js("phone",$phone);
        $this->set_filed_for_js("id",$id);
        return $this->pageView(__METHOD__,NULL);

    }

    //@desn:新版微信信息
    public function user_center_info(){
        $nickname=$this->get_in_str_val('nickname');
        \App\Helper\Utils::logger("nickname:$nickname");
        $phone=$this->get_in_phone();
        $id=$this->get_in_id();
        if ($phone) {
            $agent_info= $this->t_agent->get_agent_info_by_phone($phone);
            $id=$agent_info["id"];
        }
        if($nickname){
            $agent_info=$this->t_agent->get_agent_info_by_nickname($nickname);
            $id = $agent_info['id'];
            $nickname = $agent_info['nickname'];
        }
        if ($id) {
            $phone=$this->t_agent->get_phone($id);
        }

        $this->set_filed_for_js("phone",$phone);
        $this->set_filed_for_js("nickname",$nickname);
        $this->set_filed_for_js("id",$id);
        return $this->pageView(__METHOD__,NULL);
    }

    public function get_my_num(){
        // $agent_id = 60;//月月
        // $agent_id = 54;//陈
        // $agent_id = 211;//Amanda
        // $agent_id = 1571;//三千笔墨绘你一世倾
        // $agent_id = 427;//周圣杰 Eros
        // $agent_id = 1509;//王朝刚
        // $agent_id = 443;//九月
        $agent_id = 435;//助教2组-戈叶伟-Amy
        $agent_info = $this->t_agent->get_agent_info_by_id($agent_id);
        if(isset($agent_info['phone'])){
            $phone = $agent_info['phone'];
        }else{
            return $this->output_err("请先绑定优学优享账号!");
        }
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }
        $ret = [];
        $ret = $this->t_agent->get_p_list_by_phone($phone);
        $p_count = [];
        $p_id = array_column($ret,'p_id');
        if($ret[0]['p_id']){
            foreach($p_id as $item){
                $count = 0;
                foreach($ret as $info){
                    if($info['p_id'] == $item && $info['id']){
                        $count++;
                    }
                }
                $p_count[$item] = $count;
            }
            $p_ret = $this->t_agent->get_agent_order_by_phone($p_id);
            $id = array_column($ret,'id');
            $ret_new = $this->t_agent_order->get_order_by_id($id);
            foreach($p_ret as $key=>&$item){
                $ret_list[$key]['phone'] = $item['phone'];
                $ret_list[$key]['name'] = $item['nickname']?$item['nickname']:$item['phone'];
                $ret_list[$key]['status'] = 0;
                if($item['order_status']){//购课
                    $ret_list[$key]['status'] = 2;
                }else{
                    if($item['userid']){//试听成功
                        $count_item = $this->t_lesson_info_b2->get_test_lesson_count_by_userid($item['userid'],$item['p_create_time']);
                        $ret_list[$key]['status'] = $count_item['lessonid']?1:0;
                    }
                }
                foreach($p_count as $k=>$i){
                    if($k == $item['p_id']){
                        $ret_list[$key]['count'] = $i;
                    }
                }
                \App\Helper\Utils::unixtime2date_for_item($item,"p_create_time");
                foreach($ret_new as $k=>$info){
                    if($info['pid'] == $item['p_id']){
                        $ret_list[$key]['list'][$k]['name'] = $info['nick'];
                        $ret_list[$key]['list'][$k]['price'] = $info['price']/100;
                    }
                }
            }
        }else{
            $ret_list = [];
        }
        dd($ret_list);
    }

    public function get_user_cash(){
        $agent_id = $this->get_agent_id();
        $type = $this->get_in_int_val('type');
        $agent_info = $this->t_agent->get_agent_info_by_id($agent_id);
        if(isset($agent_info['phone'])){
            $phone = $agent_info['phone'];
        }else{
            return $this->output_err("请先绑定优学优享账号!");
        }
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }

        $student_info = [];
        $student_info = $this->t_student_info->get_stu_row_by_phone($phone);
        $pay          = 0;
        $cash         = 0;
        if($student_info){
            $ret = $this->get_pp_pay_cash($phone);
            $cash = $ret['cash'];
        }else{
            $agent_lsit = [];
            $agent_item = [];
            $agent_list = $this->t_agent->get_agent_list_by_phone($phone);
            foreach($agent_list as $item){
                if($phone == $item['phone']){
                    $agent_item = $item;
                }
            }
            if($agent_item){
                $test_lesson = [];
                $test_lesson = $this->t_agent->get_agent_test_lesson_count_by_id($agent_item['id']);
                $count       = count(array_unique(array_column($test_lesson,'id')));
                if(2<=$count){
                    $ret = $this->get_pp_pay_cash($phone);
                    $cash      = $ret['cash'];
                }else{
                    $ret = $this->get_p_pay_cash($phone);
                    $cash      = $ret['cash'];
                }
            }else{
                return $this->output_err("您暂无资格!");
            }
        }
        $ret_list      = $ret['list'];
        if($type==1){
            return $this->output_succ(["list" =>$ret_list]);
        }
        return $this->output_succ(["cash"=>$cash,"list" =>$ret_list]);
    }

    public function get_have_cash(){
        $agent_id = $this->get_agent_id();
        $agent_info = $this->t_agent->get_agent_info_by_id($agent_id);
        if(isset($agent_info['phone'])){
            $phone = $agent_info['phone'];
        }else{
            return $this->output_err("请先绑定优学优享账号!");
        }
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }
        $ret_list = [];
        $ret = $this->t_agent_cash->get_cash_list_by_phone($phone);
        foreach($ret as $key=>$item){
            $ret_list[$key]['cash'] = $item['cash']/100;
            $ret_list[$key]['is_suc_flag'] = $item['is_suc_flag'];
            $ret_list[$key]['create_time'] = date('Y-m-d',$item['create_time']);
        }
        return $this->output_succ(["list" =>$ret_list]);
    }

    public function get_pp_pay_cash($phone){
        $pay      = 0;
        $ret      = [];
        $ret_list = [];
        $pay_list = $this->t_agent_order->get_price_by_phone($phone);
        foreach($pay_list as $key=>$item){
            if($phone == $item['p_phone']){
                $pay += $item['p_price']/100;
                $ret_list[$key]['price'] = $item['p_price']/100;
            }
            if($phone == $item['pp_phone']){
                $pay += $item['pp_price']/100;
                $ret_list[$key]['price'] = $item['pp_price']/100;
            }
            $ret_list[$key]['userid'] = $item['userid'];
            $ret_list[$key]['orderid'] = $item['orderid'];
            if($item['pay_price']){
                $ret_list[$key]['pay_price'] = $item['pay_price']/100;
            }else{
                $ret_list[$key]['pay_price'] = 0;
            }
            if($item['pay_time']){
                $ret_list[$key]['pay_time'] = date('Y-m-d H:i:s',$item['pay_time']);
            }else{
                $ret_list[$key]['pay_time'] = '';
            }
            if($item['parent_name']){
                $ret_list[$key]['parent_name'] = $item['parent_name'];
            }else{
                $ret_list[$key]['parent_name'] = '';
            }
            $ret_list[$key]['order_time'] = $item['order_time'];
        }
        $ret = $this->get_cash($ret_list);
        foreach($ret['list'] as $key=>$item){
            $ret_list[$key]['count'] = $item['count'];
            $ret_list[$key]['order_cash'] = $item['order_cash'];
            $ret_list[$key]['level1_cash'] = $item['level1_cash'];
            $ret_list[$key]['level2_cash'] = $item['level2_cash'];
        }
        $data = [
            'pay'=>$pay,
            'cash'=>$ret['cash'],
            'num'=>count($pay_list),
            'list' => $ret_list,
        ];
        return $data;
    }

    public function get_p_pay_cash($phone){
        $pay = 0;
        $ret_list = [];
        $pay_list  = $this->t_agent_order->get_p_price_by_phone($phone);
        foreach($pay_list as $key=>$item){
            if($phone == $item['p_phone']){
                $pay += $item['p_price']/100;
                $ret_list[$key]['price'] = $item['p_price']/100;
            }
            $ret_list[$key]['userid'] = $item['userid'];
            $ret_list[$key]['orderid'] = $item['orderid'];
            if($item['pay_price']){
                $ret_list[$key]['pay_price'] = $item['pay_price']/100;
            }else{
                $ret_list[$key]['pay_price'] = 0;
            }
            if($item['pay_time']){
                $ret_list[$key]['pay_time'] = date('Y-m-d H:i:s',$item['pay_time']);
            }else{
                $ret_list[$key]['pay_time'] = '';
            }
            if($item['parent_name']){
                $ret_list[$key]['parent_name'] = $item['parent_name'];
            }else{
                $ret_list[$key]['parent_name'] = '';
            }
            $ret_list[$key]['order_time'] = $item['order_time'];
        }
        $ret = $this->get_cash($ret_list);
        foreach($ret['list'] as $key=>$item){
            $ret_list[$key]['count'] = $item['count'];
            $ret_list[$key]['order_cash'] = $item['order_cash']/100;
            $ret_list[$key]['level1_cash'] = $item['level1_cash'];
            $ret_list[$key]['level2_cash'] = $item['level2_cash'];
        }
        $data = [
            'pay'=>$pay,
            'cash'=>$ret['cash'],
            'num'=>count($pay_list),
            'list'=>$ret_list,
        ];
        return $data;
    }

    // public function get_cash($ret_list){
    //     $cash = 0;
    //     foreach($ret_list as $key=>$item){
    //         $userid  = $item['userid'];
    //         $pay     = $item['price'];
    //         $ret_row = $this->t_lesson_info_b2->get_lesson_count_by_userid($userid);
    //         $count   = $ret_row['count'];
    //         $ret_list[$key]['count'] = $count;
    //         $ret_list[$key]['level1_cash'] = $pay/5;
    //         $ret_list[$key]['level2_cash'] = $pay-$ret_list[$key]['level1_cash'];
    //         if(8<=$count){
    //             $cash += $pay;
    //             $ret_list[$key]['order_cash'] = $pay;
    //         }elseif(2<=$count && $count<8){
    //             $cash += $pay/5;
    //             $ret_list[$key]['order_cash'] = $pay/5;
    //         }else{
    //             $cash += 0;
    //             $ret_list[$key]['order_cash'] = 0;
    //         }
    //     }
    //     $data = ['cash'=>$cash,'list'=>$ret_list];
    //     return $data;
    // }

    public function get_bank_info(){
        $agent_id = $this->get_agent_id();
        $agent_info = $this->t_agent->get_agent_info_by_id($agent_id);
        if(isset($agent_info['phone'])){
            $phone = $agent_info['phone'];
        }else{
            return $this->output_err("请先绑定优学优享账号!");
        }
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }
        $ret = [];
        $ret = $this->t_agent->get_agent_info_by_phone($phone);
        if(!$ret){
            return $this->output_err('请先绑定优学优享账号!');
        }
        $data = [
            "bankcard"      => $ret['bankcard'],
            "bank_address"  => $ret['bank_address'],
            "bank_account"  => $ret['bank_account'],
            "bank_phone"    => $ret['bank_phone'],
            "bank_type"     => $ret['bank_type'],
            "idcard"        => $ret['idcard'],
            "bank_city"     => $ret['bank_city'],
            "bank_province" => $ret['bank_province'],
            "zfb_name"      => $ret['zfb_name'],
            "zfb_account"   => $ret['zfb_account'],
        ];

        return $this->output_succ(["data" =>$data]);
    }

    public function update_agent_bank_info(){
        $agent_id = $this->get_agent_id();
        $agent_info = $this->t_agent->get_agent_info_by_id($agent_id);
        if(isset($agent_info['phone'])){
            $phone = $agent_info['phone'];
        }else{
            return $this->output_err("请先绑定优学优享账号!");
        }
        $bankcard      = $this->get_in_str_val("bankcard");
        $idcard        = $this->get_in_str_val("idcard");
        $bank_address  = $this->get_in_str_val("bank_address");
        $bank_account  = $this->get_in_str_val("bank_account");
        $bank_phone    = $this->get_in_str_val("bank_phone");
        $bank_province = $this->get_in_str_val("bank_province");
        $bank_city     = $this->get_in_str_val("bank_city");
        $bank_type     = $this->get_in_str_val("bank_type");
        $zfb_name      = $this->get_in_str_val("zfb_name");
        $zfb_account   = $this->get_in_str_val("zfb_account");
        $cash          = $this->get_in_str_val("cash"); //要提现
        $id            = $agent_id;
        if(!isset($cash)){
            return $this->output_err("请输入提现金额!");
        }
        $check_cash = $this->check_user_cash($phone);
        $total_cash = $check_cash['cash'];      //可提现
        $have_cash = $check_cash['have_cash'];  //已提现
        $cash_new = $cash + $have_cash;
        if($cash_new > $total_cash){
            return $this->output_err("超出可提现金额!");
        }
        if($bankcard){
            if($phone=='' || $bankcard==0 || $bank_address=="" || $bank_account==""
               || $bank_phone=="" || $bank_type=="" || $idcard=="" || $bank_province==""
               || $bank_city==""
            ){
                return $this->output_err("请完善所有数据后重新提交！");
            }
            if(!preg_match("/^1\d{10}$/",$bank_phone)){
                return $this->output_err("请输入规范的手机号!");
            }
            if($bank_account){
                $ret = $this->t_agent->field_update_list($id,[
                    "bankcard"      => $bankcard,
                    "bank_address"  => $bank_address,
                    "bank_account"  => $bank_account,
                    "bank_phone"    => $bank_phone,
                    "bank_type"     => $bank_type,
                    "idcard"        => $idcard,
                    "bank_city"     => $bank_city,
                    "bank_province" => $bank_province,
                ]);
                if(($bankcard == $agent_info['bankcard']) && ($bank_address == $agent_info['bank_address'])
                   && ($bank_account == $agent_info['bank_account']) && ($bank_phone == $agent_info['bank_phone'])
                   && ($bank_type == $agent_info['bank_type']) && ($idcard == $agent_info['idcard'])
                   && ($bank_city == $agent_info['bank_city']) && ($bank_province == $agent_info['bank_province'])){
                    $ret = 1;
                }
                if($ret){
                    \App\Helper\Utils::logger('yxyx_cash:'.$cash*100);
                    $ret_new = $this->t_agent_cash->row_insert([
                        "aid"         => $id,
                        "cash"        => $cash*100,
                        "is_suc_flag" => 0,
                        "type"        => 1,
                        "create_time" => time(null),
                    ]);
                    if(!$ret_new){
                        return $this->output_err('更新失败！请重试！');
                    }
                }else{
                    return $this->output_err("更新失败！请重试！");
                }
            }
        }elseif($zfb_account){
            if($zfb_name=='' || $zfb_account==''){
                return $this->output_err("请完善所有数据后重新提交！");
            }
            $ret = $this->t_agent->field_update_list($id,[
                "zfb_name"     => $zfb_name,
                "zfb_account"     => $zfb_account,
            ]);
            if(($zfb_account == $agent_info['zfb_account']) && ($zfb_name == $agent_info['zfb_name'])){
                $ret = 1;
            }
            if($ret){
                $ret_new = $this->t_agent_cash->row_insert([
                    "aid"         => $id,
                    "cash"        => $cash*100,
                    "is_suc_flag" => 0,
                    "type"        => 2,
                    "create_time" => time(null),
                ]);
                if(!$ret_new){
                    return $this->output_err('更新失败！请重试！');
                }
            }else{
                return $this->output_err("更新失败！请重试！");
            }
        }
        return $this->output_succ('成功');
    }

    public function check_user_cash($phone){
        $student_info = [];
        $student_info = $this->t_student_info->get_stu_row_by_phone($phone);
        $cash       = 0;
        $have_cash  = 0;
        if($student_info){
            $ret       = $this->get_pp_pay_cash($phone);
            $cash      = $ret['cash'];
            $cash_item = $this->t_agent_cash->get_cash_by_phone($phone);
            if($cash_item['have_cash']){
                $have_cash = $cash_item['have_cash'];
            }
        }else{
            $agent_lsit = [];
            $agent_item = [];
            $agent_list = $this->t_agent->get_agent_list_by_phone($phone);
            foreach($agent_list as $item){
                if($phone == $item['phone']){
                    $agent_item = $item;
                }
            }
            if($agent_item){
                $test_lesson = [];
                $cash_item   = [];
                $count       = 0;
                $test_lesson = $this->t_agent->get_agent_test_lesson_count_by_id($agent_item['id']);
                $count       = count(array_unique(array_column($test_lesson,'id')));
                $cash_item   = $this->t_agent_cash->get_cash_by_phone($phone);
                if($cash_item['have_cash']){
                    $have_cash = $cash_item['have_cash'];
                }
                if(2<=$count){
                    $level = 2;
                    $ret = $this->get_pp_pay_cash($phone);
                }else{
                    $level = 1;
                    $ret = $this->get_p_pay_cash($phone);
                }
                $cash = $ret['cash'];
            }else{
                return $this->output_err("您暂无资格!");
            }
        }
        $data = [
            'cash'      => $cash,
            'have_cash' => $have_cash/100,
        ];
        return $data;
    }

    public function get_all_test_pic(){
        //title,date,用户未读取标志（14天内），十张海报（当天之前的，可跳转）
        $grade     = $this->get_in_int_val('grade',-1);
        $subject   = $this->get_in_int_val('subject',-1);
        $test_type = $this->get_in_int_val('test_type',-1);
        $page_info = $this->get_in_page_info();
        // $parentid  = $this->get_parentid();
        $parentid  = 44;
        $ret_info  = $this->t_yxyx_test_pic_info->get_all_for_wx($grade, $subject, $test_type, $page_info, $parentid);
        foreach ($ret_info['list'] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
        }
        //获取十张海报
        $all_id     = $this->t_yxyx_test_pic_info->get_all_id_poster();
        $count_num  = count($all_id)-1;
        $poster_arr = [];
        $num_arr    = [];
        $loop_num   = 0;
        while ( $loop_num < 10) {
            $key = mt_rand(0, $count_num);
            if( !in_array($key, $num_arr)) {
                $num_arr[]    = $key;
                $poster_arr[] = $all_id[$key];
                $loop_num++;
            }
        }
        // dd($ret_info);
        return $this->output_succ([
            ['list'=>$ret_info],
            ['poster'=>$poster_arr],
        ]);
    }

    public function get_one_test_and_other() {
        //title,poster(当天之前的)
        $id = $this->get_in_int_val('id',-1);
        if ($id < 0){
            return $this->output_err('信息有误！');
        }
        $ret_info = $this->t_yxyx_test_pic_info->get_one_info($id);
        \App\Helper\Utils::unixtime2date_for_item($ret_info,"create_time");
        E\Egrade::set_item_value_str($ret_info,"grade");
        E\Esubject::set_item_value_str($ret_info,"subject");
        E\Etest_type::set_item_value_str($ret_info,"test_type");
        $ret_info['pic_arr'] = explode( '|',$ret_info['pic']);
        unset($ret_info['pic']);
        //获取所有id，随机选取三个
        $all_id    = $this->t_yxyx_test_pic_info->get_all_id_poster($id);
        $count_num = count($all_id)-1;
        $id_arr    = [];
        $num_arr   = [];
        $loop_num  = 0;
        while ( $loop_num < 3) {
            $key = mt_rand(0, $count_num);
            if( !in_array($key, $num_arr)) {
                $num_arr[] = $key;
                $id_arr[]  = $all_id[$key]['id'];
                $loop_num++;
            }
        }
        $id_str = '('.join($id_arr,',').')';
        $create_time = strtotime('today');
        $other_info = $this->t_yxyx_test_pic_info->get_other_info($id_str, $create_time);
        return $this->output_succ([
            ['list' => $ret_info],
            ['other'=>$other_info],
        ]);
    }
    public function get_phone_count(){
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],1);
        $phone           = $this->get_in_phone();
        $is_called_phone = $this->get_in_int_val("is_called_phone",-1, E\Eboolean::class );
        $uid             = $this->get_in_int_val("uid",-1);
        $page_num        = $this->get_in_page_num();
        $seller_student_status  = $this->get_in_el_seller_student_status();
        $type            = $this->get_in_int_val('agent_type');

        $clink_args="?enterpriseId=3005131&userName=admin&pwd=".md5(md5("leoAa123456" )."seed1")  . "&seed=seed1"  ;

        $ret_info=$this->t_tq_call_info->get_agent_call_phone_list($page_num,$start_time,$end_time,$uid,$is_called_phone,$phone, $seller_student_status,$type );
        $now=time(NULL);
        foreach($ret_info["list"] as &$item) {
            $record_url= $item["record_url"] ;
            if ($now-$item["start_time"] >1*86400 && (preg_match("/saas.yxjcloud.com/", $record_url  )|| preg_match("/121.196.236.95/", $record_url  ) ) ){
                $item["load_wav_self_flag"]=1;
            }else{
                $item["load_wav_self_flag"]=0;
            }
            if (preg_match("/api.clink.cn/", $record_url ) ) {
                $item["record_url"].=$clink_args;
            }

            \App\Helper\Utils::unixtime2date_for_item($item,"start_time");
            E\Eboolean::set_item_value_str($item,"is_called_phone");
            E\Eseller_student_status::set_item_value_str($item);
            E\Eaccount_role::set_item_value_str($item);
            $item["duration"]= \App\Helper\Common::get_time_format($item["duration"]);
        }
        return $this->pageView(__METHOD__,$ret_info);

    }



    public function update_agent_userid(){
        $ret_info = $this->t_agent->get_agent_list();
        $ret = [];
        foreach($ret_info as $item){
            $id = $item['id'];
            $phone = $item['phone'];
            $userid = $item['userid'];
            $userid_new = $this->t_phone_to_user->get_userid_by_phone($phone, E\Erole::V_STUDENT );
            if(!$userid){
                if($userid_new){
                    $ret[] = $this->t_agent->field_update_list($id,[
                        "userid" => $userid_new,
                    ]);
                }
            }
        }
        dd($ret);
    }

    public function update_agent_level(){
        $ret_info = $this->t_agent->get_agent_list();
        foreach($ret_info as $item){
            $id = $item['id'];
            $phone = $item['phone'];
            $create_time = $item['create_time'];
            $userid = $item['userid'];
            $wx_openid = $item['wx_openid'];
            $student_info = $this->t_student_info->field_get_list($userid,"*");
            $orderid = 0;
            if($userid){
                $order_info = $this->t_order_info->get_nomal_order_by_userid($userid,time());
                if($order_info['orderid']){
                    $orderid = $order_info['orderid'];
                }
            }
            $userid_new   = $student_info['userid'];
            $type_new     = $student_info['type'];
            $is_test_user = $student_info['is_test_user'];
            $level        = 0;
            if($userid
               && $type_new ==  E\Estudent_type::V_0
               && $is_test_user == 0
               && $orderid){//在读非测试
                $level     =  E\Eagent_level::V_2 ;
            }elseif($wx_openid){//有wx绑定
                $test_lesson = $this->t_agent->get_son_test_lesson_count_by_id($id,time());
                $count       = count($test_lesson);
                if($count>=2){
                    $level     =  E\Eagent_level::V_2 ;
                }else{
                    $level     =  E\Eagent_level::V_1 ;
                }
            }else{//非绑定
                $level =  E\Eagent_level::V_0;
            }
            $this->t_agent->field_update_list($id,[
                "agent_level" => $level
            ]);
        }
    }

    public function update_agent_test_lessonid(){
        $ret_info = $this->t_agent->get_agent_list();
        foreach($ret_info as $item){
            $id = $item['id'];
            $userid = $item['userid'];
            $student_info = $this->t_student_info->field_get_list($userid,"*");
            $is_test_user = $student_info['is_test_user'];
            $create_time = $item['create_time'];
            $lessonid_new = 0;
            if($userid && $is_test_user == 0 && $student_info){
                $ret = $this->t_lesson_info_b2->get_succ_test_lesson($userid,$create_time);
                if ($ret) {
                    $lessonid = $ret['lessonid'];
                    if($lessonid){
                        $lessonid_new = $lessonid;
                    }
                }
            }
            $this->t_agent->field_update_list($id,[
                "test_lessonid" => $lessonid_new
            ]);
        }
    }

    public function update_agent_order_new(){
        $orderid = 21765;
        $userid = 303874;
        $price = 535200;
        $this->update_agent_order($orderid,$userid,$price);
    }

    public function update_agent_order($orderid,$userid,$order_price){
        $agent_order = [];
        $ret_info = [];
        $agent_order = $this->t_agent_order->get_row_by_orderid($orderid);
        if(!isset($agent_order['orderid'])){
            $phone    = $this->t_student_info->get_phone($userid);
            $ret_info = $this->t_agent->get_p_pp_id_by_phone($phone);
            if(isset($ret_info['id'])){
                $level1 = 0;
                $level2 = 0;
                if($ret_info['p_phone']){
                    $level1 = $this->check_agent_level($ret_info['p_phone']);
                }
                if($ret_info['pp_phone']){
                    $level2 = $this->check_agent_level($ret_info['pp_phone']);
                }
                $price           = $order_price/100;
                $level1_price    = $price/20>500?500:$price/20;
                $level2_p_price  = $price/10>1000?1000:$price/10;
                $level2_pp_price = $price/20>500?500:$price/20;
                $pid = $ret_info['pid'];
                $ppid = $ret_info['ppid'];
                $p_price = 0;
                $pp_price = 0;
                if($level1 == 1){//黄金
                    $p_price = $level1_price*100;
                }elseif($level1 == 2){//水晶
                    $p_price = $level2_p_price*100;
                }
                if($level2 == 2){//水晶
                    $pp_price = $level2_pp_price*100;
                }
                $this->t_agent_order->row_insert([
                    'orderid'     => $orderid,
                    'aid'         => $ret_info['id'],
                    'pid'         => $pid,
                    'p_price'     => $p_price,
                    'p_level'     => $level1,
                    'ppid'        => $ppid,
                    'pp_price'    => $pp_price,
                    'pp_level'    => $level2,
                    'create_time' => time(null),
                ]);
            }
        }
    }

    public function check_agent_level($phone){//黄金1,水晶2,无资格0
        $agent = $this->t_agent->get_agent_info_row_by_phone($phone);
        if($agent['agent_level']){
            $level = $agent['agent_level'];
        }else{
            $level = 0;
        }
        return $level;
    }


        /**
     * @todo : 本函数用于 将方形的图片压缩后
     *         再裁减成圆形 做成logo
     *         与背景图合并
     * @return 返回url
     */
    public function index(){
        $headimgurl = 'http://wx.qlogo.cn/mmopen/ajNVdqHZLLAEbfWjOqjPWTPiaSg6wBVuE1D986YvpLF9CNuVUz0ce0rmP0eQNz345KeSK0RWsG5B3ibv3oIXZLOQ/0';
        $datapath = "/tmp/178_headimg.jpeg";
        $wgetshell = 'wget -O '.$datapath.' "'.$headimgurl.'" ';
        shell_exec($wgetshell);

        // $imgg = $this->yuan_img($datapath);
        // $datapath_new ="/tmp/189_headimg_new.jpeg";
        // imagejpeg($imgg,$datapath_new);
        // $image_4 = imagecreatefromjpeg($datapath_new);
        // dd($image_4);
        //头像
        $headimgurl = $datapath;
        //背景图
        $bgurl = 'http://7u2f5q.com2.z0.glb.qiniucdn.com/d8563e7ad928cf9535fc5c90e17bb2521503108001175.jpg';
        $imgs['dst'] = $bgurl;
        //第一步 压缩图片
        $imggzip = $this->resize_img($headimgurl);
        //第二步 裁减成圆角图片
        $imgs['src'] = $this->test($imggzip);
        dd($imgs['src']);
        //第三步 合并图片
        $dest = $this->mergerImg($imgs);
        dd($dest);
    }

    public function resize_img($url,$path='/tmp/'){
        $imgname = $path.uniqid().'.jpg';
        $file = $url;
        list($width, $height) = getimagesize($file); //获取原图尺寸
        $percent = (110/$width);
        //缩放尺寸
        $newwidth = 190;
        $newheight = 190;
        $src_im = imagecreatefromjpeg($file);
        $dst_im = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresized($dst_im, $src_im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        imagejpeg($dst_im, $imgname); //输出压缩后的图片
        // header('Content-type: image/jpg');
        // dd(imagejpeg($dst_im));

        imagedestroy($dst_im);
        imagedestroy($src_im);

        return $imgname;
    }

    //第一步生成圆角图片
    public function test($url,$path='/tmp/'){
        $w = 190;  $h=190; // original size
        $original_path= $url;
        $dest_path = $path.uniqid().'.png';
        $src = imagecreatefromjpeg($original_path);
        $newpic = imagecreatetruecolor($w,$h);
        imagealphablending($newpic,false);
        $transparent = imagecolorallocatealpha($newpic, 0, 0, 0, 127);
        $r=$w/2;
        for($x=0;$x<$w;$x++)
            for($y=0;$y<$h;$y++){
                $c = imagecolorat($src,$x,$y);
                $_x = $x - $w/2;
                $_y = $y - $h/2;
                if((($_x*$_x) + ($_y*$_y)) < ($r*$r)){
                    imagesetpixel($newpic,$x,$y,$c);
                }else{
                    imagesetpixel($newpic,$x,$y,$transparent);
                }
            }
        imagesavealpha($newpic, true);
        imagepng($newpic, $dest_path);
        // header('Content-type: image/jpg');
        // dd(imagepng($newpic));

        imagedestroy($newpic);
        imagedestroy($src);
        unlink($url);

        return $dest_path;
    }

    //php 合并图片
    public function mergerImg($imgs,$path='/tmp/') {
        $imgname = $path.rand(1000,9999).uniqid().'.jpg';

        list($max_width, $max_height) = getimagesize($imgs['dst']);
        $dests = imagecreatetruecolor($max_width, $max_height);
        // $dst_im = imagecreatefromjpeg($imgs['dst']);
        $dst_im = imagecreatefrompng($imgs['dst']);
        imagecopy($dests,$dst_im,0,0,0,0,$max_width,$max_height);
        imagedestroy($dst_im);
        $src_im = imagecreatefrompng($imgs['src']);
        $src_info = getimagesize($imgs['src']);
        imagecopy($dests,$src_im,354,35,0,0,190,190);

        imagedestroy($src_im);
        imagejpeg($dests,$imgname);

        unlink($imgs['src']);
        return $imgname;
    }

    /**
     *  blog:http://www.zhaokeli.com
     * 处理成圆图片,如果图片不是正方形就取最小边的圆半径,从左边开始剪切成圆形
     * @param  string $imgpath [description]
     * @return [type]          [description]
     */
    function yuan_img($imgpath = './tx.jpg') {
        $ext     = pathinfo($imgpath);
        $src_img = null;
        switch ($ext['extension']) {
        case 'jpg':
            $src_img = imagecreatefromjpeg($imgpath);
            break;
        case 'jpeg':
            $src_img = imagecreatefromjpeg($imgpath);
            break;
        case 'png':
            $src_img = imagecreatefrompng($imgpath);
            break;
        }
        $wh  = getimagesize($imgpath);
        $w   = $wh[0];
        $h   = $wh[1];
        $w   = min($w, $h);
        $h   = $w;
        $img = imagecreatetruecolor($w, $h);
        //这一句一定要有
        imagesavealpha($img, true);
        //拾取一个完全透明的颜色,最后一个参数127为全透明
        $bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
        imagefill($img, 0, 0, $bg);
        $r   = $w / 2-20; //圆半径
        $y_x = $r; //圆心X坐标
        $y_y = $r; //圆心Y坐标
        // dd($r,$y_x,$y_y);
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgbColor = imagecolorat($src_img, $x, $y);
                if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r))) {
                    imagesetpixel($img, $x+14, $y+14, $rgbColor);
                }
            }
        }
        return $img;
    }

    //@desn:优学优享团队信息
    public function agent_group(){
        $group_colconel = $this->get_in_str_val('group_colconel');
        $agentid = $this->get_in_int_val("colconel_agent_id");
        $page_info=$this->get_in_page_info();
        $ret_info = $this->t_agent_group->get_agent_group_list($agentid,$page_info,$group_colconel);
        foreach ($ret_info["list"] as &$item ) {
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            //获取每个团的成员个数
            $item['member_num'] = $this->t_agent_group_members->get_member_num($item['group_id']);
        }
        return $this->pageView(__METHOD__,$ret_info);
    }
    //优学优享团队统计
    public function agent_group_statistics(){
        list($start_time, $end_time  ) =$this->get_in_date_range_month(0);
        //获取一级数据  --begin---
        $colconel_list = $this->t_agent_group->get_colconel_list();
        for($i=0;$i<count($colconel_list);$i++){
            //获取团长的一级推荐人信息   ---begin--
            $colconel_child_info = $this->t_agent->get_colconel_child_info($colconel_list[$i]['colconel_id']);
            //团长业绩[一级]
            $colconel_student_count = 0;
            $colconel_member_count = 0;
            $colconel_child_arr = [];
            foreach($colconel_child_info as &$item){
                if(($item['type'] == 1||$item['type']==3) && $item['create_time'] >= $start_time && $item['create_time']<$end_time)
                    $colconel_student_count ++;
                if(($item['type'] == 2||$item['type']==3) && $item['create_time'] >= $start_time && $item['create_time']<$end_time)
                    $colconel_member_count ++;
                $colconel_child_arr[] = $item['id'];
            }
            $colconel_test_lesson_count = 0;
            if($colconel_child_arr){
                $colconel_child_str = '('.implode(',',$colconel_child_arr).')';
                //获取一级试听信息
                $test_lesson_info = $this->t_agent->get_child_test_lesson_info($colconel_child_str);
                if($test_lesson_info){
                    foreach( $test_lesson_info as $item ) {
                        if ($item["lesson_user_online_status"] ==1 && $item['l_time'] >= $start_time && $item['l_time'] < $end_time)
                            $colconel_test_lesson_count += 1;
                    }
                }


                //计算签单金额、签单量
                $child_order_info = $this->t_agent_order->get_cycle_child_order_info($colconel_child_str,$start_time,$end_time);
                $colconel_order_count = $child_order_info['child_order_count'];
                $colconel_order_money = $child_order_info['child_order_money'];
            }

            //获取团长的一级推荐人信息   ---end--
            //团长信息
            $colconel_info = $this->t_agent->get_agent_info_by_id($colconel_list[$i]['colconel_id']);
            $colconel_result[] = [
                'colconel_id' => $colconel_info['id'],
                'colconel_name' => $colconel_info['phone'].'/'.$colconel_info['nickname'],
                'test_lesson_count' => $colconel_test_lesson_count,
                'member_count' => $colconel_member_count,
                'student_count' => $colconel_student_count,
                'order_count' => $colconel_order_count,
                'order_money' => $colconel_order_money/100,
                'is_colconel' => 1,
                'level' => 'l-1',
            ];
            //$colconel_result[] = $this->t_agent->get_colconel_statistics($colconel_list[$i]['colconel_id']);

        }
        // dd($colconel_result);
        //获取一级数据  --end---

        //获取所有团长的数据之和--begin--
        $colconel_test_lesson_count = 0;
        $colconel_member_count = 0;
        $colconel_student_count = 0;
        $colconel_order_count = 0;
        $colconel_order_money = 0;
        if(@$colconel_result){
            foreach($colconel_result as &$item){
                $colconel_test_lesson_count += $item['test_lesson_count'];
                $colconel_member_count += $item['member_count'];
                $colconel_student_count += $item['student_count'];
                $colconel_order_count += $item['order_count'];
                $colconel_order_money += $item['order_money'];
            }
        }
        //获取所有团长的数据之和--end--


        //获取二级数据  ---begin---
        if(@$colconel_result){
            for($i=0;$i<count($colconel_result);$i++){
                $group_list[] = $colconel_result[$i];
                $group_result = $this->t_agent_group_members->get_group_info($colconel_result[$i]['colconel_id'],$start_time,$end_time);
                foreach($group_result as &$item){
                    $item['colconel_name'] = $colconel_result[$i]['colconel_name'];
                    $item['order_money'] /= 100;
                    $group_list[] = $item;
                }
            }
        }
        // dd($group_list);
        //获取二级数据  ---end---

        //获取三级数据  ---begin---
        if(@$group_list){
            for($i=0;$i<count($group_list);$i++){
                $member_list[] = $group_list[$i];
                if(@$group_list[$i]['is_group'] == 1){
                    $member_result = $this->t_agent_group_members->get_member_result($group_list[$i]['group_id'],$start_time,$end_time);
                    foreach($member_result as &$item){
                        $item['colconel_name'] = $group_list[$i]['colconel_name'];
                        $item['group_name'] = $group_list[$i]['group_name'];
                        $item['order_money'] /= 100;
                        $member_list[] = $item;
                    }
                }
            }
        }
        // dd($member_list);
        //获取三级数据  ---end---

        //分配层级类标识 ---begin--
        $colconel_num = 1;
        if(@$member_list){
            foreach($member_list as &$item){
                if(@$item['is_colconel'] == 1){
                    $item['main_type_class'] = 'acmpus_id-'.$colconel_num;
                    $item['up_group_name_class'] = '';
                    $item['group_name_class'] = '';
                }
                if(@$item['is_group'] == 1){
                    $item['up_group_name_class'] = 'up_group_name-'.++$colconel_num;
                    $item['group_name_class'] ='';
                }
                if(@$item['is_member'] == 1)
                    $item['group_name_class'] = 'group_name-'.++$colconel_num;
            }

            $acmpus='';
            foreach($member_list as &$item){
                if(@$item['main_type_class'])
                    $acmpus = $item['main_type_class'];
                if(@$item['is_colconel'] != 1)
                    $item['main_type_class'] = $acmpus;
            }
            $up_group_name='';
            foreach($member_list as &$item){
                if(@$item['up_group_name_class'])
                    $up_group_name = $item['up_group_name_class'];
                if(@$item['is_member'] ==1)
                    $item['up_group_name_class'] = $up_group_name;
            }
        }
        // dd($member_list);
        //分配层级类标识 ---end--

        //获取全部团员业绩[不包括团长]
        $agent_member_result = $this->t_agent_group_members->get_agent_member_result($start_time,$end_time);

        $agent_all_group_result['test_lesson_count'] = $agent_member_result['test_lesson_count']+$colconel_test_lesson_count;
        $agent_all_group_result['member_count'] = $agent_member_result['member_count']+$colconel_member_count;
        $agent_all_group_result['student_count'] = $agent_member_result['student_count']+$colconel_student_count;

        $agent_all_group_result['order_count'] = $agent_member_result['order_count']+$colconel_order_count;

        $agent_all_group_result['order_money'] = ($agent_member_result['order_money']+$colconel_order_money)/100;
        $agent_all_group_result['name'] = '总计';
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info(@$member_list),[
            'agent_all_group_result' => $agent_all_group_result,
        ]);
    }
    public function get_wx_login_list() {
        $to_agentid=$this->get_in_int_val("to_agentid", -1);

        list( $start_time,$end_time )=$this->get_in_date_range_day(0);
        $agent_wx_msg_type=$this->get_in_el_agent_wx_msg_type();
        $page_info= $this->get_in_page_info();
        $ret_info=$this->t_agent_wx_msg_log->get_list($page_info, $start_time,$end_time, $to_agentid ,$agent_wx_msg_type );
        foreach ($ret_info["list"] as &$item) {
            E\Eagent_wx_msg_type::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,"log_time");
            E\Eboolean::set_item_value_str($item,"succ_flag");
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function get_yxyx_member(){

        list($start_time,$end_time)=$this->get_in_date_range_month(0);
        $phone = trim($this->get_in_str_val('phone',''));
        if($phone > 100000) {
            $nickname = '';
        } else {
            $nickname = $phone;
            $phone = '';
        }
        $page_info = $this->get_in_page_info();


        $db_arr = ["no_phone_count", "ok_phone_no_lesson", "ok_lesson_rate", "ok_lesson_no_order", "order_rate"];
        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type) = $this->get_in_order_by_str($db_arr,"",[
                "user_count"         => "user_count" ,
                "no_revisit_count"   => "no_revisit_count",
                "no_phone_count"     => "no_phone_count",
                "ok_phone_count"     => "ok_phone_count",
                "ok_phone_no_lesson" => "ok_phone_no_lesson",
                "rank_count"         => "rank_count",
                "del_lesson_count"   => "del_lesson_count",
                "ok_lesson_count"    => "ok_lesson_count",
                "ok_lesson_rate"     => "ok_lesson_rate",
                "ok_lesson_no_order" => "ok_lesson_no_order",
                "order_user_count"   => "order_user_count",
                "order_rate"         => "order_rate",
                "price"              => "price",
            ]);



        if( in_array($order_field_name, $db_arr) ){
            $page_flag = false;
        } else {
            $page_flag = true;
        }
        $ret_info = $this->t_agent->get_yxyx_member($start_time, $end_time,$nickname,$phone,$page_info,$order_by_str,$page_flag);

        $all_user = 0;
        $order_user = 0;
        $price = 0;
        if ( $page_flag ) {

            foreach ($ret_info['list'] as &$item){
                $item['no_revisit_count']--;
                $item['ok_phone_count']--;
                $item['rank_count']--;
                $item['ok_lesson_count']--;
                $item['del_lesson_count']--;
                $item['price'] = $item['price']/100;
                // $item['no_revisit_count'] = $item['user_count'] - $item['revisit_count'];
                if($item['rank_count']) {
                    $item['ok_lesson_rate'] = round( $item['ok_lesson_count']*100/$item['rank_count'],2);
                } else {
                    $item['ok_lesson_rate'] = 0;
                }
                if($item['user_count']) {
                    $item['order_rate'] = round( $item['order_user_count']*100/$item['user_count'],2);
                } else {
                    $item['order_rate'] = 0;
                }
                $item['no_phone_count'] = $item['user_count'] -$item['no_revisit_count']-$item['ok_phone_count'];
                $item['ok_phone_no_lesson'] = $item['ok_phone_count'] - $item['rank_count'];
                $item['ok_lesson_no_order'] = $item['ok_lesson_count'] - $item['order_user_count'];
                $all_user = $all_user+$item['user_count'];
                $order_user = $order_user+$item['order_user_count'];
                $price = $price+$item['price'];
            }

        } else {

            foreach ($ret_info as &$item){
                $item['no_revisit_count']--;
                $item['ok_phone_count']--;
                $item['rank_count']--;
                $item['ok_lesson_count']--;
                $item['del_lesson_count']--;
                $item['price'] = $item['price']/100;

                if($item['rank_count']) {
                    $item['ok_lesson_rate'] = round( $item['ok_lesson_count']*100/$item['rank_count'],2);
                } else {
                    $item['ok_lesson_rate'] = 0;
                }
                if($item['user_count']) {
                    $item['order_rate'] = round( $item['order_user_count']*100/$item['user_count'],2);
                } else {
                    $item['order_rate'] = 0;
                }
                $item['no_phone_count'] = $item['user_count'] -$item['no_revisit_count']-$item['ok_phone_count'];
                $item['ok_phone_no_lesson'] = $item['ok_phone_count'] - $item['rank_count'];
                $item['ok_lesson_no_order'] = $item['ok_lesson_count'] - $item['order_user_count'];
            }

            $ret_info = \App\Helper\Utils::order_list_new( $ret_info, $order_field_name, $order_type ,$page_info);

            foreach($ret_info['list'] as $item){
                $all_user   = $all_user+$item['user_count'];
                $order_user = $order_user+$item['order_user_count'];
                $price      = $price+$item['price'];
            }
        }


        return $this->pageView(__METHOD__,$ret_info,[
            'all_user' => $all_user,
            'order_user' => $order_user,
            'price' => $price,
        ]);
    }

    public function get_yxyx_member_detail(){

        list($start_time,$end_time)=$this->get_in_date_range_month(0);
        $id = $this->get_in_int_val('id',-1);
        $page_info = $this->get_in_page_info();
        $opt_type = $this->get_in_str_val('opt_type','');
        $ret_info = $this->t_agent->get_yxyx_member_detail($id,$start_time, $end_time,$opt_type,$page_info);
        foreach ($ret_info['list'] as &$item){
            E\Egrade::set_item_value_str($item,'grade');
            E\Esubject::set_item_value_str($item,'subject');
            $item['test_lesson'] = $item['test_lessonid'] ? '是': '否';
            \App\Helper\Utils::unixtime2date_for_item($item,'revisit_time');
            \App\Helper\Utils::unixtime2date_for_item($item,'lesson_start');
            \App\Helper\Utils::unixtime2date_for_item($item,'create_time');
            // $item['account'] = $this->cache_get_account_nick($item['admin_revisiterid']);
            $lass_call_time_space = $item['last_revisit_time']?(time()-$item['last_revisit_time']):(time()-$item['add_time']);
            $item['last_call_time_space'] = (int)($lass_call_time_space/86400);
            E\Etest_lesson_order_fail_flag::set_item_value_str($item);

            if ($item['no_tq'] > 0) {
                $item['phone_count'] = $item['phone_count'] - 1;
            }
            if ($item['no_tq'] == 0 && $item['ok_phone'] == 0) {
                $item['phone_count'] = $item['phone_count'] - 1;
            }

        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function update_add_reason(){
        $id = $this->get_in_int_val('id','');
        $add_reason = $this->get_in_str_val('add_reason','');
        if ($id <= 0) {
            return $this->output_err('信息有误!提交失败!');
        }

        $res = $this->t_agent->field_update_list($id,['add_reason' => $add_reason]);

        return $this->output_succ();
    }

    public function get_agent_phone_by_wx_openid(){
        $wx_openid = $this->get_in_str_val($wx_openid,'');
        if ($wx_openid !== '') {
            $ret = $this->t_agent->get_agent_info_by_openid($wx_openid);
            return $ret['phone'];
        }
        return false;
    }
    //@desn:用户推荐人详情
    //@param type 1:学员 2：会员 3：学员+会员
    //@param parentid 推荐人id
    public function agent_child_info(){
        $phone=$this->get_in_phone();
        $id=$this->get_in_id();
        if ($phone) {
            $agent_info= $this->t_agent->get_agent_info_by_phone($phone);
            $id=$agent_info["id"];
        }
        if ($id) {
            $phone=$this->t_agent->get_phone($id);
        }
        $this->set_filed_for_js("phone",$phone);
        $this->set_filed_for_js("id",$id);
        $page_info = $this->get_in_page_info();
        $type = $this->get_in_int_val('type',-1);
        $parentid = $this->get_in_int_val('id');
        if($type <= 0)
            $this->output_err('传入学员类型有误!');
        $ret_info = $this->t_agent->get_child_info($parentid,$type,$page_info);
        if($ret_info['total_num']<1)
            $ret_info['list'] = [];
        foreach($ret_info['list'] as &$item){
            //获取用户签单量及签单金额
            $agent_order_sum = $this->t_order_info->get_agent_order_sum($item['userid']);
            $item['self_order_count'] = $agent_order_sum['self_order_count'];
            $item['self_order_price'] = $agent_order_sum['self_order_price']/100;
            $item['is_test_lesson_str'] = empty($item['test_lessonid']) ? '未试听':'已试听';
            if($item['account_role'] == 1)
                $item['teach_assistantant'] = $item['account'].'/'.$item['name'];
            $item['agent_info'] = 1;
        }
        return $this->pageView(__METHOD__,$ret_info,['type'=>$type]);
    }
    //@desn:获取该订单冻结原因
    public function get_freeze_reason(){
        $id = $this->get_in_id();
        $item = $this->t_agent_cash_money_freeze->get_freeze_reason($id);
        if($item){
            $item['freeze_money'] /= 100;
            $item['admin_account'] = $this->t_manager_info->get_account_by_uid($item['adminid']);
            \App\Helper\Utils::unixtime2date_for_item($item,'create_time');
            E\Eagent_freeze_type::set_item_value_str($item,'agent_freeze_type');
            E\Eagent_money_ex_type::set_item_value_str($item,'agent_money_ex_type');
            \App\Helper\Utils::unixtime2date_for_item($item,'agent_activity_time');

            return $this->output_succ([
                'freeze_reason' => $item,
            ]);
        }else{
            return $this->output_succ([
                'freeze_reason' => -1,
            ]);
        }
    }
    //@desn:获取用户该笔体现的来源
    public function get_agent_cash_log(){
        $agent_id = $this->get_in_int_val('agentid');
        $this_cash_time = $this->get_in_int_val('this_cash_time');
        $last_cash_time = $this->t_agent_cash->get_last_cash_time($agent_id,$this_cash_time);
        $agent_cash_log = $this->t_agent_income_log->get_this_cash_log($agent_id,$this_cash_time,$last_cash_time);
        foreach($agent_cash_log as &$item){
            E\Eagent_income_type::set_item_value_str($item,'agent_income_type');
            $item['agent_name'] = $item['a_phone'].'/'.$item['a_nickname'];
            $item['agent_child_name'] = $item['ca_phone'].'/'.$item['ca_nickname'];
            \App\Helper\Utils::unixtime2date_for_item($item,'create_time');
            $item['money'] /= 100;
        };
        return $this->output_succ(['agent_cash_log' => $agent_cash_log]);
    }
}
