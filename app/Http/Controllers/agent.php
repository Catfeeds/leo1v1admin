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
        $userid        = $this->get_in_userid(-1);
        $phone         = $this->get_in_phone();
        $p_phone       = $this->get_in_str_val('p_phone');
        $type          = $this->get_in_int_val('agent_type');
        $page_info     = $this->get_in_page_info();
        $test_lesson_flag= $this->get_in_e_boolean(-1, "test_lesson_flag" );
        $agent_type= $this->get_in_el_agent_type();
        $agent_level = $this->get_in_el_agent_level();
        $order_flag = $this->get_in_e_boolean(-1, "order_flag" );
        $l1_child_count= $this->get_in_intval_range("l1_child_count");

        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type)
            =$this->get_in_order_by_str([],"",["l1_child_count" => "a.l1_child_count" ,
                                               "l2_child_count" => "a.l2_child_count",
                                               "l1_agent_status_all_money" => "a.l1_agent_status_all_money",
                                               "l1_agent_status_all_open_money" => "a.l1_agent_status_all_open_money",
                                               "all_money" => "a.all_money",

                                               "all_yxyx_money" => "a.all_yxyx_money",
                                               "all_open_cush_money" => "a.all_open_cush_money",
                                               "order_open_all_money" => "a.order_open_all_money",
                                               "all_have_cush_money" => "a.all_have_cush_money",
                                               "child_order_count" => "a.child_order_count",
            ]);

        $ret_info = $this->t_agent->get_agent_info($page_info,$order_by_str ,$phone,$type,$start_time,$end_time,$p_phone, $test_lesson_flag , $agent_level ,$order_flag,$l1_child_count);
        $userid_arr = [];
        foreach($ret_info['list'] as &$item){
            $status = $item["lesson_user_online_status"];
            if($status == 2){
                $item["lesson_user_online_status"] = 0;
            }
            $item['lesson_start'] = $item['test_lessonid']?$item['lesson_start']:0;
            $item['agent_type'] = $item['type'];
            E\Eagent_type::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start");
            E\Eagent_level::set_item_value_str($item);
            E\Eagent_status::set_item_value_str($item);
            E\Estudent_stu_type::set_item_value_str($item);
            E\Eboolean::set_item_value_str($item,"agent_status_money_open_flag");

            E\Eagent_student_status::set_item_value_str($item);
            $item["cc_nick"]= $this->cache_get_account_nick( $item["admin_revisiterid"]);
            $item["test_lessonid_str"] = \App\Helper\Common::get_boolean_color_str( $item["test_lessonid"]);
            $item["lesson_user_online_status_str"] = \App\Helper\Common::get_boolean_color_str( $item["lesson_user_online_status"]);
            $item["price"]/= 100;
            $item["all_money"]/= 100;
            $item["l1_agent_status_all_money"]/= 100;
            $item["l1_agent_status_all_open_money"]/= 100;
            $item["all_yxyx_money"]/= 100;
            $item["all_open_cush_money"]/= 100;
            $item["all_have_cush_money"]/= 100;
            $item["order_open_all_money"]/= 100;

            $item["pp_off_info"] =  ($item["pp_price"]/100 ) ."/". E\Eagent_level::get_desc($item["pp_level"] )  ;
            $item["p_off_info"] =  ($item["p_price"]/100 ) ."/". E\Eagent_level::get_desc($item["p_level"] )  ;

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
        $page_num  = $this->get_in_page_num();
        $page_info = $this->get_in_page_info();
        $ret_info = $this->t_agent_cash->get_agent_cash_list($page_info);
        foreach($ret_info['list'] as &$item){
            $item['agent_check_money_flag'] = $item['check_money_flag'];
            $item['cash'] = $item['cash']?$item['cash']/100:0;
            $item["check_money_admin_nick"]= $this->cache_get_account_nick( $item["check_money_adminid"] );
            $item['check_money_desc'] = $item['check_money_desc']?$item['check_money_desc']:'';
            E\Eagent_check_money_flag::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"check_money_time");
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function check(){
        $phone = '12324589561';
        $phone = \App\Helper\Common::check_phone($phone);
        dd($phone);
        $time = strtotime(date('Y-m-d',time()).'00:00:00');
        $week = date('w',$time);
        if($week == 0){
            $week = 7;
        }elseif($week == 1){
            $week = 8;
        }
        $end_time = $time-3600*24*($week-2);
        $start_time = $end_time-3600*24*7;
        $tongji_type=E\Etongji_type::V_SELLER_WEEK_FAIL_LESSON_PERCENT;
        $self_top_info =$this->t_tongji_seller_top_info->get_admin_week_fail_percent($adminid=882,$start_time,$tongji_type);
        dd($self_top_info);
    }

    public function test_lesson_cancle_rate(){
        $adminid = 882;
        $userid = $this->get_in_int_val('userid');
        $time = strtotime(date('Y-m-d',time()).'00:00:00');
        $week = date('w',$time);
        if($week == 0){
            $week = 7;
        }elseif($week == 1){
            $week = 8;
        }
        $end_time = $time-3600*24*($week-2);
        $start_time = $end_time-3600*24*7;
        list($count,$count_del) = [0,0];
        $start_time_old = $start_time;
        $end_time_old = $end_time;
        $ret_info = $this->t_lesson_info_b2->get_seller_week_lesson_new($start_time,$end_time,$adminid);
        $lessonid_arr = [];
        $userid_arr = [];
        foreach($ret_info as $item){
            $lessonid_arr[] = $item['lessonid'];
            $userid_arr[] = $item['userid'];
        }
        foreach($ret_info as $item){
            if($item['lesson_del_flag']){
                $count_del++;
            }
            $count++;
        }
        $del_rate = $count?$count_del/$count:0;
        if($del_rate>0.25){//今日排课量
            $start_time = $time;
            $end_time = $time+3600*24;
            $ret_info = $this->t_lesson_info_b2->get_seller_week_lesson_new($start_time,$end_time,$adminid);
            $ret['ret'] = count($ret_info)?1:2;
            $review_suc = $this->t_test_lesson_subject_require_review->get_row_by_adminid_userid($adminid,$userid);
            if($review_suc){
                $ret['ret'] = 2;
            }
            $ret['rate'] = $del_rate*100;
        }else{//本周取消率
            $start_time = $time-3600*24*($week-2);
            $end_time = time();
            $ret_info = $this->t_lesson_info_b2->get_seller_week_lesson_new($start_time,$end_time,$adminid);
            foreach($ret_info as $item){
                if($item['lesson_del_flag']){
                    $count_del++;
                }
                $count++;
            }
            $del_rate = $count?$count_del/$count:0;
            if($del_rate>0.2){
                $ret['ret'] = 3;
            }else{
                $ret['ret'] = 4;
            }
        }
        dd($start_time_old,$end_time_old,$lessonid_arr,$userid_arr,$count_del,$count,$del_rate,$ret);
    }

    public function agent_add(){
        // $p_phone = '18616626799';
        // $phone   = '17701796622';
        $type   = E\Eagent_type::V_1;
        $userid = $this->t_phone_to_user->get_userid($phone);
        $student_info = $this->t_student_info->field_get_list($userid,'*');
        $orderid = 0;
        if($userid){
            $order_info = $this->t_order_info->get_nomal_order_by_userid($userid   );
            if($order_info['orderid']){
                $orderid = $order_info['orderid'];
            }
        }
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }
        if($p_phone == $phone){
            return $this->output_err("不能邀请自己!");
        }
        if(!$type){
            return $this->output_err("请选择报名类型!");
        }
        // if($userid
        //    && $student_info['type'] ==  E\Estudent_type::V_0
        //    && $student_info['is_test_user'] == 0
        //    && $orderid
        //    && $type == E\Eagent_type::V_1
        // ){//在读非测试
        //     dd($student_info);
        //     return $this->output_err("您已是在读学员!");
        // }
        // dd('b');
        if(!$p_phone){
            return $this->output_err("无推荐人!");
        }
        $phone_str = implode(',',[$phone,$p_phone]);
        $ret_list = $this->t_agent->get_id_by_phone($phone_str);
        foreach($ret_list as $item){
            if($phone == $item['phone']){
                $ret_info = $item;
            }else{
                $ret_info_p = $item;
            }
        }
        $parentid = $ret_info_p['id'];
        $p_wx_openid = $ret_info_p['wx_openid'];
        $p_agent_level = $ret_info_p['agent_level'];
        $pp_wx_openid = $ret_info_p['pp_wx_openid'];
        $pp_agent_level = $ret_info_p['pp_agent_level'];
        if(isset($ret_info['id'])){//已存在,则更新父级和类型
            if($type == $ret_info['type'] or $ret_info['type']==3){
                return $this->output_err("您已被邀请过!");
            }
            $type_new = $ret_info['type']=0?$type:3;
            $this->t_agent->field_update_list($ret_info['id'],[
                "parentid" => $parentid,
                "type"     => $type_new,
            ]);
            $this->send_agent_p_pp_msg_for_wx($phone,$p_phone,$type,$p_wx_openid,$p_agent_level,$pp_wx_openid,$pp_agent_level);
            return $this->output_succ("邀请成功!");
        }
        if($type == 1){//进例子
            $db_userid = $this->t_phone_to_user->get_userid_by_phone($phone, E\Erole::V_STUDENT );

            if ($db_userid)  {
                $add_time=$this->t_seller_student_new->get_add_time($userid);
                if ($add_time < time(NULL) -60*86400 ) { //60天前例子
                    $usreid= $this->t_seller_student_new->book_free_lesson_new($nick='',$phone,$grade=0,$origin='优学优享',$subject=0,$has_pad=0);
                    if ($userid) {
                        $this->t_student_info->field_update_list($userid, [
                            "origin_level" => E\Eorigin_level::V_99
                        ] );
                    }
                }
            }else{
                $this->t_seller_student_new->book_free_lesson_new($nick='',$phone,$grade=0,$origin='优学优享',$subject=0,$has_pad=0);
            }
        }
        $userid = null;
        $userid_new = $this->t_phone_to_user->get_userid_by_phone($phone, E\Erole::V_STUDENT );
        if($userid_new){
            $userid = $userid_new;
        }
        $ret = $this->t_agent->add_agent_row($parentid,$phone,$userid,$type);
        if($ret){
            $this->send_agent_p_pp_msg_for_wx($phone,$p_phone,$type,$p_wx_openid,$p_agent_level,$pp_wx_openid,$pp_agent_level);
            return $this->output_succ("邀请成功!");
        }else{
            return $this->output_err("数据请求异常!");
        }
    }

    public function send_agent_p_pp_msg_for_wx($phone,$p_phone,$type,$p_wx_openid,$p_agent_level,$pp_wx_openid,$pp_agent_level){
        $template_id = '70Yxa7g08OLcP8DQi4m-gSYsd3nFBO94CcJE7Oy6Xnk';
        $url = '';
        if($p_wx_openid){
            if($type == 1){//邀请学员
                $type_str = '邀请学员成功!';
                if($p_agent_level == 1){//黄金
                    $remark = '恭喜您成功邀请的学员'.$phone.'报名参加测评课，如学员成功购课则可获得最高500元的奖励哦。';
                }else{//水晶
                    $remark = '恭喜您成功邀请的学员'.$phone.'报名参加测评课，如学员成功购课则可获得最高1000元的奖励哦。';
                }
            }else{//邀请会员
                $type_str = '邀请会员成功!';
                $remark = '恭喜您成功邀请会员'.$phone;
            }
            $data = [
                'first'    => $type_str,
                'keyword1' => $phone,
                'keyword2' => $phone,
                'keyword3' => date('Y-m-d H:i:s',time()),
                'remark'   => $remark,
            ];
            \App\Helper\Utils::send_agent_msg_for_wx($p_wx_openid,$template_id,$data,$url);
        }
        if($pp_wx_openid){
            if($type == 1){//邀请学员
                $type_str = '邀请学员成功!';
                if($pp_agent_level == 1){//黄金
                    $remark = '恭喜您邀请的会员'.$p_phone."成功邀请了".$phone.'报名参加测评课。';
                }else{//水晶
                    $remark = '恭喜您邀请的会员'.$p_phone."成功邀请了".$phone.'报名参加测评课，如学员成功购课则可获得最高500元的奖励哦。';
                }
            }else{//邀请会员
                $type_str = '邀请会员成功!';
                $remark = '恭喜您邀请的会员'.$p_phone."成功邀请了".$phone;
            }
            $data_p = [
                'first'    => $type_str,
                'keyword1' => $phone,
                'keyword2' => $phone,
                'keyword3' => date('Y-m-d H:i:s',time()),
                'remark'   => $remark,
            ];
            \App\Helper\Utils::send_agent_msg_for_wx($pp_wx_openid,$template_id,$data_p,$url);
        }
    }



    public function get_agent_test_lesson($agent_id){
        $test_lesson = $this->t_agent->get_agent_test_lesson_count_by_id($agent_id);
        dd($test_lesson);
    }

    public function update_lesson_call_end_time(){
        $adminid = $this->get_in_int_val('adminid');
        $lesson_call_end = $this->t_lesson_info_b2->get_call_end_time_by_adminid_new($adminid);
        if(count($lesson_call_end)>0){
            foreach($lesson_call_end as $item){
                $ret = $this->t_lesson_info_b2->get_test_lesson_list(0,0,-1,$item['lessonid']);
            }
        }else{
            $ret = 1;
        }

        return $ret;
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

        $clink_args="?enterpriseId=3005131&userName=admin&pwd=".md5(md5("Aa123456" )."seed1")  . "&seed=seed1"  ;

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
        $dst_im = imagecreatefromjpeg($imgs['dst']);
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
        $r   = $w / 2; //圆半径
        $y_x = $r; //圆心X坐标
        $y_y = $r; //圆心Y坐标
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgbColor = imagecolorat($src_img, $x, $y);
                if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r))) {
                    imagesetpixel($img, $x, $y, $rgbColor);
                }
            }
        }
        return $img;
    }

}
