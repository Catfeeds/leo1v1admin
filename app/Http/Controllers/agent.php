<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;


class agent extends Controller
{
    public function agent_list() {
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,null,1);
        $userid        = $this->get_in_userid(-1);
        $phone         = $this->get_in_phone();
        $grade         = $this->get_in_grade(-1);
        $parentid      = $this->get_in_parentid(-1);
        $wx_openid     = $this->get_in_wx_openid();
        $bankcard      = $this->get_in_str_val('bankcard');
        $idcard        = $this->get_in_str_val('idcard');
        $bank_address  = $this->get_in_str_val('bank_address');
        $bank_account  = $this->get_in_str_val('bank_account');
        $bank_phone    = $this->get_in_str_val('bank_phone');
        $bank_province = $this->get_in_str_val('bank_province');
        $bank_city     = $this->get_in_str_val('bank_city');
        $bank_type     = $this->get_in_str_val('bank_type');
        $zfb_name      = $this->get_in_str_val('zfb_name');
        $zfb_account   = $this->get_in_str_val('zfb_account');
        $type          = $this->get_in_int_val('agent_type');
        $page_num      = $this->get_in_page_num();
        $page_info     = $this->get_in_page_info();
        $ret_info = $this->t_agent->get_agent_info($page_info,$phone,$type,$start_time,$end_time);
        $userid_arr = [];
        foreach($ret_info['list'] as &$item){
            if($item['type'] == 1){
                if($item['userid']){
                    $userid_arr[] = $item['userid'];
                }
            }
            $item['agent_type'] = $item['type'];
            $item['create_time'] = date('Y-m-d H:i:s',$item['create_time']);
        }
        if(count($userid_arr)>0){
            $test_info = $this->t_lesson_info_b2->get_suc_test_by_userid($userid_arr);
            foreach($ret_info['list'] as &$item){
                foreach($test_info as $info){
                    if($item['userid'] == $info['userid']){
                        $item['success_flag'] = 1;
                    }
                }
            }
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function agent_list_new(){
        $type      = $this->get_in_int_val('type');
        $page_info = $this->get_in_page_info();
        $ret_info  = $this->t_agent->get_agent_info_new($page_info,$type);
        $userid_arr = [];
        foreach($ret_info['list'] as &$item){
            if($item['type'] == 1){
                $userid_arr[] = $item['s_userid'];
            }
            $item['agent_type'] = $item['type'];
            $item['create_time'] = date('Y-m-d H:i:s',$item['create_time']);
        }
        if(count($userid_arr)>0){
            $test_info = $this->t_lesson_info_b2->get_suc_test_by_userid($userid_arr);
            foreach($ret_info['list'] as &$item){
                foreach($test_info as $info){
                    if($item['s_userid'] == $info['userid']){
                        $item['success_flag'] = 1;
                    }
                }
            }
        }
        return $this->pageView(__METHOD__,$ret_info);
    }


    public function agent_order_list() {
        $orderid   = $this->get_in_int_val('orderid');
        $aid       = $this->get_in_int_val('aid');
        $pid       = $this->get_in_int_val('pid');
        $p_price   = $this->get_in_int_val('p_price');
        $ppid      = $this->get_in_int_val('ppid');
        $pp_price  = $this->get_in_int_val('pp_price');
        $page_num  = $this->get_in_page_num();
        $page_info = $this->get_in_page_info();
        $ret_info  = $this->t_agent_order->get_agent_order_info($page_info);
        foreach($ret_info['list'] as &$item){
            $item['create_time'] = date('Y-m-d H:i:s',$item['create_time']);
            $item['p_price'] = $item['p_price']/100;
            $item['pp_price'] = $item['pp_price']/100;
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
            if($item['create_time']){
                $item['create_time'] = date('Y-m-d H:i:s',$item['create_time']);
            }else{
                $item['create_time'] = '';
            }
            if($item['cash']){
                $item['cash'] = $item['cash']/100;
            }
        }

        return $this->pageView(__METHOD__,$ret_info);
    }

    public function check(){
        $ret = $this->t_agent->get_agent_list();
        foreach($ret as $item){
            $id = $item['id'];
            $userid = $item['userid'];
            $phone = $item['phone'];
            if($userid == null or $userid==0){
                $ret_info[] = $item;
                $userid = $this->t_phone_to_user->get_userid_by_phone($phone, E\Erole::V_STUDENT );
                $id_arr[] = $id;
                if($userid){
                    $this->t_agent->field_update_list($id,[
                        "userid" => $userid,
                    ]);
                }
            }
        }
        dd($id_arr);
        $userid = $this->t_phone_to_user->get_userid_by_phone($phone, E\Erole::V_STUDENT );

        $agent_id = 54;
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
        $userid = $this->t_phone_to_user->get_userid_by_phone($phone, E\Erole::V_STUDENT );
        // $student_info = $this->t_student_info->get_stu_row_by_phone($phone);
        $student_info = $this->t_student_info->field_get_list($userid,"*");
        $userid_new = $student_info['userid'];
        $type_new = $student_info['type'];
        $is_test_user = $student_info['is_test_user'];
        $level      = 0;
        $pay        = 0;
        $cash       = 0;
        $have_cash  = 0;
        $num        = 0;
        $my_num     = 0;
        if($userid_new && $type_new == 0 && $is_test_user == 0){
            $ret_list  = ['userid'=>0,'price'=>0];
            $level = 2;
            $nick      = $student_info['nick'];
            $ret       = $this->get_pp_pay_cash($phone);
            $pay       = $ret['pay'];
            $cash      = $ret['cash'];
            $num       = $ret['num'];
            $cash_item = $this->t_agent_cash->get_cash_by_phone($phone);
            if($cash_item['have_cash']){
                $have_cash = $cash_item['have_cash'];
            }
            $count_row = $this->t_agent->get_count_by_phone($phone);
            $my_num    = $count_row['count'];
            $test_count = 2;
        }else{
            $nick       = $phone;
            $agent_lsit = [];
            $agent_item = [];
            $agent_list = $this->t_agent->get_agent_list_by_phone($phone);
            foreach($agent_list as $item){
                if($phone == $item['phone']){
                    $agent_item = $item;
                }
                if($phone == $item['p_phone']){
                    $my_num++;
                }
            }
            if($agent_item){
                $test_lesson = [];
                $cash_item   = [];
                $count       = 0;
                $ret_list    = ['userid'=>0,'price'=>0];
                $nick        = $phone;
                $test_lesson = $this->t_agent->get_agent_test_lesson_count_by_id($agent_item['id']);
                $count       = count(array_unique(array_column($test_lesson,'id')));
                $cash_item   = $this->t_agent_cash->get_cash_by_phone($phone);
                if($cash_item['have_cash']){
                    $have_cash = $cash_item['have_cash'];
                }
                if(2<=$count){
                    $level = 2;
                    $ret = $this->get_pp_pay_cash($phone);
                    $test_count = 2;
                }else{
                    $level = 1;
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
        $cash_new     = (int)(($cash-$have_cash/100)*100)/100;
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

    }


    public function update_agent_order($orderid,$userid,$order_price){
        $agent_order = [];
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
                $level1_price    = $price/20;
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
                    'ppid'        => $ppid,
                    'pp_price'    => $pp_price,
                    'create_time' => time(null),
                ]);
            }
        }
    }

    public function check_agent_level($phone){//黄金1,水晶2,无资格0
        $student_info = [];
        $student_info = $this->t_student_info->get_stu_row_by_phone($phone);
        if(isset($student_info['userid'])){
            return 2;
        }else{
            $agent_item = [];
            $agent_item = $this->t_agent->get_agent_info_row_by_phone($phone);
            if(count($agent_item)>0){
                $test_lesson = [];
                $test_lesson = $this->t_agent->get_agent_test_lesson_count_by_id($agent_item['id']);
                $count       = count(array_unique(array_column($test_lesson,'id')));
                if(2<=$count){
                    return 2;
                }else{
                    return 1;
                }
            }else{
                return 0;
            }
        }
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
            $userid  = $item['userid'];
            $pay     = $item['price'];
            $ret_row = $this->t_lesson_info_b2->get_lesson_count_by_userid($userid);
            $count   = $ret_row['count'];
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


}
