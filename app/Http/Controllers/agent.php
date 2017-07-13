<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;


class agent extends Controller
{
    var $check_login_flag=false;
    public function agent_list() {
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
        $zfb_name     = $this->get_in_str_val('zfb_name');
        $zfb_account     = $this->get_in_str_val('zfb_account');
        $page_num      = $this->get_in_page_num();
        $page_info     = $this->get_in_page_info();
        $ret_info = $this->t_agent->get_agent_info($page_info,$userid,$parentid,$phone,$wx_openid);
        foreach($ret_info['list'] as &$item){
            $item['create_time'] = date('Y-m-d H:i:s',$item['create_time']);
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
        }

        return $this->pageView(__METHOD__,$ret_info);
    }


    public function check(){
        $lessonid = 62815;
        $ret = $this->t_test_lesson_subject_sub_list->get_set_lesson_adminid_by_lessonid($lessonid);
        dd($ret['set_lesson_adminid']);
        // $openid = 'orwGAswh6yMByNDpPz8ToUPNhRpQ';
        // $template_id         = "kvkJPCc9t5LDc8sl0ll0imEWK7IGD1NrFKAiVSMwGwc";
        // $wx_data["first"]    = '1';
        // $wx_data["keyword1"] = '2';
        // $wx_data["keyword2"] = "\n 1、填写报名信息"
        //                      ."\n 2、录制试讲视频"
        //                      ."\n 3、进行入职培训"
        //                      ."\n 4、成功入职";
        // $wx_data["remark"] = "好友成功入职后，即可获得伯乐奖，"
        //                    ."伯乐奖将于每月10日结算（如遇节假日，会延后到之后的工作日），"
        //                    ."请及时绑定银行卡号，如未绑定将无法发放。";
        // \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$wx_data);
        // dd('a');

        // $teacher_openid = 'oJ_4fxFUMHpPlf-ibtKD2vuWTKp4';
        // $template_id_teacher = 'rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o';
        // $url = 'www.leo1v1.com';
        // $data = [
        //     'first'    => '1',
        //     'keyword1' => '2',
        //     'keyword2' => '3',
        //     'keyword3' => '4',
        //     'remark'   => '5',
        // ];
        // dd($template_id_teacher);
        // \App\Helper\Utils::send_teacher_msg_for_wx($teacher_openid,$template_id_teacher,$data,$url);

        // $time = strtotime(date('Y-m-d',time(null)).date('H:i',time(null)).':00');
        $time = strtotime('2017-7-12 18:00:00');
        $lesson_start = [$time+300,$time-60,$time-180,$time-300,$time-600,$time-1200,$time-2400];
        $lesson_info = $this->t_lesson_info_b2->get_check_lesson($lesson_start);
        $lesson_time = date('Y-m-d H:s',$lesson_info[0]['lesson_start']).'-'.date('H:s',$lesson_info[0]['lesson_end']);
        dd($lesson_start,$lesson_time);
        if(count($lesson_info)>0){
            foreach($lesson_info as $key=>$l_item){
                $ret = [
                    'lessonid'       => $l_item['lessonid'],
                    'lesson_type'    => $l_item['lesson_type'],
                    'tea_attend'     => $l_item['tea_attend'],
                    'stu_attend'     => $l_item['stu_attend'],
                    'teacher_openid' => $l_item['teacher_openid'],
                    'assistantid'    => $l_item['assistantid'],
                    'cc_id'          => $l_item['cc_id'],
                ];
                if($l_item['lesson_start'] == $time+300){//课前5分钟
                    $ret['work_type'] = 0;
                    dd($ret);
                    if(!isset($l_item['tea_attend'])){
                        $job=(new \App\Jobs\lesson_check($ret))->delay(60);
                        dispatch($job);
                    }
                }elseif($l_item['lesson_start'] == $time-60){//上课1分钟
                    $ret['work_type'] = 1;
                    dd($ret);
                    if(!isset($l_item['stu_attend'])){
                        $job=(new \App\Jobs\lesson_check($ret))->delay(60);
                        dispatch($job);
                    }
                }elseif($l_item['lesson_start'] == $time-180){//上课3分钟
                    $ret['work_type'] = 2;
                    dd($ret);
                }elseif($l_item['lesson_start'] == $time-300){//上课5分钟
                    $ret['work_type'] = 3;
                    dd($ret);
                }elseif($l_item['lesson_start'] == $time-600){//上课10分钟
                    $ret['work_type'] = 4;
                    dd($ret);
                    if(!isset($l_item['tea_attend'])){
                        $job=(new \App\Jobs\lesson_check($ret))->delay(60);
                        dispatch($job);
                    }
                    if(!isset($l_item['stu_attend'])){
                        $job=(new \App\Jobs\lesson_check($ret))->delay(60);
                        dispatch($job);
                    }
                }elseif($l_item['lesson_start'] == $time-1200){//上课20分钟
                    $ret['work_type'] = 5;
                    dd($ret);
                }elseif($l_item['lesson_start'] == $time-2400){//上课40分钟
                    $ret['work_type'] = 6;
                    if(isset($l_item['tea_attend'])){
                        $this->lessonid       = $ret['lessonid'];
                        $this->lesson_type    = $ret['lesson_type'];
                        $this->tea_attend     = $ret['tea_attend'];
                        $this->stu_attend     = $ret['stu_attend'];
                        $this->teacher_openid = $ret['teacher_openid'];
                        $this->assistantid    = $ret['assistantid'];
                        $this->cc_id          = $ret['cc_id'];
                        $this->work_type      = $ret['work_type'];

                        $lessonid       = $this->lessonid;
                        dd($lessonid);
                        $job=(new \App\Jobs\lesson_check($ret))->delay(60);
                        dispatch($job);
                    }
                    if(!isset($l_item['stu_attend'])){
                        $job=(new \App\Jobs\lesson_check($ret))->delay(60);
                        dispatch($job);
                    }
                }else{//学生中途退出超过5分钟
                    $ret['work_type'] = 7;
                    dd($ret);
                }
            }
            list($this->lessonid,$this->lesson_type,
                 $this->tea_attend,$this->stu_attend,$this->teacher_openid,
                 $this->$assistantid,$this->$cc_id,$this->work_type) = $ret;

        }


        // $phone = '13022221195';
        // $phone = '13902236712';   //1
        // $phone = '13818732888';      //2
        // $phone = '13162561667';
        // $pay = $this->check_agent_level($phone);
        // $pay = $this->add_agent_order();
        // $phone = trim($this->get_in_str_val('phone'));
        // $code_flag= $this->get_in_int_val("code_flag",0) ;
        // $phone = '15251318621';
        // $code_flag = 1;
        // if ( strlen($phone) != 11) {
        //     return $this->output_err("电话号码出错");
        // }
        // \App\Helper\Utils::logger("sessionid:".session_id());

        // $msg_num = \App\Helper\Common::redis_set_json_date_add("STU_PHONE_$phone",1000000);
        // $code    = rand(1000,9999);

        // \App\Helper\Common::redis_set("JOIN_USER_PHONE_$phone", $code );
        // $ret=\App\Helper\Utils::sms_common($phone, 10671029,[
        //     "code" => $code,
        //     "index" => $msg_num
        // ] );
        // $ret_arr= ["msg_num" =>$msg_num  ];
        // if ( $code_flag ) {
        //     $ret_arr["code"] =  $code;
        // }
        // return $this->output_succ($ret_arr);
    }

    public function check_agent_level($phone){//黄金1,水晶2,无资格0
        $phone = $this->get_in_str_val('phone',$phone,-1);
        $student_info = [];
        $student_info = $this->t_student_info->get_stu_row_by_phone($phone);
        if($student_info){
            return 2;
        }else{
            $agent_item = [];
            $agent_item = $this->t_agent->get_agent_info_row_by_phone($phone);
            if($agent_item){
                $test_lesson = [];
                $test_lesson = $this->t_agent->get_agent_test_lesson_count_by_id($agent_item['id']);
                if(2<=$test_lesson['count']){
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
            $ret_list[$key]['level2_cash'] = $pay;
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








    public function agent_bind(){
        $phone      = $this->get_in_str_val("phone");
        $code       = $this->get_in_str_val("code");
        $wx_openid  = $this->get_in_str_val("wx_openid");
        $check_code = \App\Helper\Common::redis_get("JOIN_USER_PHONE_$phone" );
        \App\Helper\Utils::logger("nange:".$wx_openid);

        if($phone==""){
            return $this->output_err("手机号不能为空！");
        }

        if($code==$check_code){
            $agent = $this->t_agent->get_agent_info_by_phone($phone);
            if(!isset($agent['id'])){
                $data = $this->t_agent->add_agent_row_new($phone,$wx_openid);
                if(!$data || !is_int($data)){
                    if($data===false){
                        $data="生成失败！请退出重试！";
                    }
                    return $this->output_err($data);
                }
                $agent_info['id']   = $data;
            }
            \App\Helper\Utils::logger("wx_openid189:$wx_openid,phone:$phone,id:".$id);


            if($wx_openid){
                $id = $this->t_agent->get_agent_info_by_openid($wx_openid);
                if($id>0 && $id!=$agent_info['id']){
                    $ret = $this->t_agent->field_update_list($id,[
                        "wx_openid" => null,
                    ]);
                }
                $re = $this->t_agent->field_update_list($agent_info['id'], [
                    "wx_openid" => $wx_openid
                ]);
            }else{
                return $this->output_err("微信绑定失败!请重新登录后绑定!");
            }

            session(["login_userid"=>$agent_info['id']]);
            session(["login_user_role"=>2]);
            session(["teacher_wx_use_flag"=>$agent_info['wx_use_flag']]);
            return $this->output_succ(["wx_use_flag"=>$agent_info['wx_use_flag']]);
        }else{
            return $this->output_err ("验证码不对");
        }
    }

    public function agent_add(){
        $p_phone = $this->get_in_str_val('p_phone');
        $phone   = $this->get_in_str_val('phone');
        if(!preg_match("/^1\d{10}$/",$p_phone) or !preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }
        if($p_phone == $phone){
            return $this->output_err("不能邀请自己!");
        }
        $phone_str = implode(',',[$phone,$p_phone]);
        $ret_list = $this->t_agent->get_id_by_phone($phone_str);
        foreach($ret_list as $item){
            if($phone == $item['phone']){
                return $this->output_err("您已被邀请过!");
            }
            if($p_phone = $item['phone']){
                $parentid = $item['id'];
            }
        }
        if(!isset($parentid)){
            $parentid = 0;
        }
        $ret = $this->t_agent->add_agent_row($parentid,$phone);
        if($ret){
            return $this->output_succ("邀请成功!");
        }else{
            return $this->output_err("数据请求异常!");
        }
    }

    public function get_user_info(){
        $phone = $this->get_in_str_val('phone');
        if(!preg_match("/^1\d{10}$/",$phone)){
            return outputJson(array('ret' => -1, 'info' => "请输入规范的手机号!"));
        }

        $student_info = [];
        $student_info = $this->t_student_info->get_stu_row_by_phone($phone);
        $level      = 0;
        $pay        = 0;
        $cash       = 0;
        $have_cash  = 0;
        $num        = 0;
        $my_num     = 0;
        if($student_info){
            $ret_list  = ['userid'=>0,'price'=>0];
            $level     = 2;
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
                }else{
                    $level = 1;
                    $ret = $this->get_p_pay_cash($phone);
                }
                $pay  = $ret['pay'];
                $cash = $ret['cash'];
                $num  = $ret['num'];
            }else{
                return $this->output_err("您暂无资格!");
            }
        }
        $data = [
            'level'     => $level,
            'nick'      => $nick,
            'pay'       => $pay,
            'cash'      => $cash,
            'have_cash' => $have_cash,
            'num'       => $num,
            'my_num'    => $my_num,
        ];
        return $this->output_succ(["user_info_list" =>$data]);
    }

    public function get_my_num(){
        $phone = $this->get_in_str_val('phone');
        // $phone = '13022221195';
        // $phone = '4445';
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
                    if($info['p_id'] == $item){
                        $count++;
                    }
                }
                $p_count[$item] = $count;
            }
            $p_ret = $this->t_agent->get_agent_order_by_phone($p_id);
            $id = array_column($ret,'id');
            $ret_new = $this->t_agent_order->get_order_by_id($id);
            foreach($p_ret as $key=>$item){
                $ret_list[$key]['name'] = $item['nick'];
                if($item['order_status']){
                    $ret_list[$key]['status'] = 2;
                }else{
                    $count_item = $this->t_lesson_info_b2->get_test_lesson_count_by_userid($item['userid']);
                    $count_test = $count_item['count'];
                    if(0<$count_test){
                        $ret_list[$key]['status'] = 1;
                    }else{
                        $ret_list[$key]['status'] = 0;
                    }
                }
                foreach($p_count as $k=>$i){
                    if($k == $item['p_id']){
                        $ret_list[$key]['count'] = $i;
                    }
                }
                if($item['p_create_time']){
                    $ret_list[$key]['time'] = date('Y.m.d',$item['p_create_time']);
                }else{
                    $ret_list[$key]['time'] = '';
                }
                foreach($ret_new as $info){
                    if($info['pid'] == $item['p_id']){
                        $ret_list[$key]['list'][]['name'] = $info['nick'];
                        $ret_list[$key]['list'][]['price'] = $info['price']/100;
                    }
                }
            }
        }else{
            $ret_list = [];
        }
        return $this->output_succ(["list" =>$ret_list]);
    }

    public function get_user_pay(){
        $phone = $this->get_in_str_val('phone');
        // $phone = '13022221195';
        // $phone = '4445';
        if(!preg_match("/^1\d{10}$/",$phone)){
            return outputJson(array('ret' => -1, 'info' => "请输入规范的手机号!"));
        }
        $student_info = [];
        $student_info = $this->t_student_info->get_stu_row_by_phone($phone);
        $pay          = 0;
        if($student_info){
            //orderid,pay,学生家长,order_time,order_price,count(lessonid),level1_cash,level2_cash,level1_flag,level2_flag
            $ret = $this->get_pp_pay_cash($phone);
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
                }else{
                    $ret = $this->get_p_pay_cash($phone);
                }
            }else{
                return $this->output_err("您暂无资格!");
            }
        }
        $ret_list      = $ret['list'];
        return $this->output_succ(["list" =>$ret_list]);
    }

    public function get_user_cash(){
        $phone = $this->get_in_str_val('phone');
        $type = $this->get_in_int_val('type');
        // $phone = '13022221195';
        // $phone = '4445';
        if(!preg_match("/^1\d{10}$/",$phone)){
            return outputJson(array('ret' => -1, 'info' => "请输入规范的手机号!"));
        }
        $student_info = [];
        $student_info = $this->t_student_info->get_stu_row_by_phone($phone);
        $pay          = 0;
        $cash         = 0;
        if($student_info){
            //orderid,cash,order_cash,parent_name,pay_time,count(lessonid),level1_cash,level2_cash
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
        $phone = $this->get_in_str_val('phone');
        // $phone = '13022221195';
        // $phone = '4445';
        if(!preg_match("/^1\d{10}$/",$phone)){
            return outputJson(array('ret' => -1, 'info' => "请输入规范的手机号!"));
        }
        $ret_list = [];
        $ret = $this->t_agent_cash->get_cash_list_by_phone($phone);
        foreach($ret as $key=>$item){
            $ret_list[$key]['cash'] = $item['cash'];
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
                $pay += $item['p_price'];
                $ret_list[$key]['price'] = $item['p_price'];
            }
            if($phone == $item['pp_phone']){
                $pay += $item['pp_price'];
                $ret_list[$key]['price'] = $item['pp_price'];
            }
            $ret_list[$key]['userid'] = $item['userid'];
            $ret_list[$key]['orderid'] = $item['orderid'];
            if($item['pay_price']){
                $ret_list[$key]['pay_price'] = $item['pay_price'];
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
                $pay += $item['p_price'];
                $ret_list[$key]['price'] = $item['p_price'];
            }
            $ret_list[$key]['userid'] = $item['userid'];
            $ret_list[$key]['orderid'] = $item['orderid'];
            if($item['pay_price']){
                $ret_list[$key]['pay_price'] = $item['pay_price'];
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
            'list'=>$ret_list,
        ];
        return $data;
    }

    public function update_agent_bank_info(){
        // $teacherid     = session("login_userid");
        $phone         = $this->get_in_str_val("phone");
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
        $cash   = $this->get_in_int_val("cash");
        if(!preg_match("/^1\d{10}$/",$phone)){
            return outputJson(array('ret' => -1, 'info' => "请输入规范的手机号!"));
        }
        // $phone = '889';
        // $bankcard = '6210984910004164835';
        // $idcard = '410185201111260509';
        // $bank_address = '中国邮政';
        // $bank_account = '张三';
        // $bank_phone = '889';
        // $bank_province = '上海';
        // $bank_city = '上海';
        // $bank_type = '邮政';
        // $zfb_name = '王五';
        // $zfb_account = 'wangwu@126.com';
        // $cash = 3000;

        $row = $this->t_agent->get_id_row_by_phone($phone);
        if(!$row){
            return $this->output_err("请先注册优学优享！");
        }
        $id = $row['id'];

        if($bankcard){
            if($phone=='' || $bankcard==0 || $bank_address=="" || $bank_account==""
               || $bank_phone=="" || $bank_type=="" || $idcard=="" || $bank_province==""
               || $bank_city==""
            ){
                return $this->output_err("请完善所有数据后重新提交！");
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
                if($ret){
                    $ret_new = $this->t_agent_cash->row_insert([
                        "aid"         => $id,
                        "cash"        => $cash,
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

            if($ret){
                $ret_new = $this->t_agent_cash->row_insert([
                    "aid"         => $id,
                    "cash"        => $cash,
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
        return $this->output_succ();
    }

}
