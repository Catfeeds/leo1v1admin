<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use \App\Enums as E;
use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;
use Illuminate\Support\Facades\Redis ;
use Illuminate\Support\Facades\Session ;
use App\Jobs\deal_wx_pic;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
require_once  app_path("/Libs/Qiniu/functions.php");
//require(app_path("/Libs/OSS/autoload.php"));
//use OSS\OssClient;
//use OSS\Core\OssException;
require_once(app_path("/Libs/OSS/autoload.php"));
use OSS\OssClient;
use OSS\Core\OssException;
class wx_yxyx_api extends Controller
{
    use CacheNick;
    var $check_login_flag=false;
    public function __construct() {
        parent::__construct();
        if (! $this->get_agent_id()){
            echo $this->output_err("未登录");
            exit;
        }
    }

    public function get_agent_id(){
        $agent_id= $this->get_in_int_val("_agent_id")?$this->get_in_int_val("_agent_id"):session("agent_id");
        return $agent_id;
    }
    public function get_user_info_new(){
        $agent_id   = $this->get_agent_id();
        $agent_info = $this->t_agent->get_agent_info_by_id($agent_id);
        if(isset($agent_info['phone'])){
            $phone = $agent_info['phone'];
        }else{
            return $this->output_err("请先绑定优学优享账号!");
        }

        $agent_level = (int)$agent_info['agent_level'];
        $nick         = $agent_info['nickname'];
        if (!$nick) {
            $nick=$phone;
        }
        $headimgurl   = $agent_info['headimgurl'];
        $nickname     = $agent_info['nickname'];

        $data = [
            'agent_level'         => $agent_level ,
            'usernick'            => $nick,
            'wx_headimgurl'       => $agent_info['headimgurl'],
            'wx_nick'             => $agent_info['nickname'],
            "star_count"          => $agent_info["star_count"],//星星个数
            "all_have_cush_money" => $agent_info["all_have_cush_money"]/100,
        ];

        E\Eagent_level::set_item_value_str($data);
        $data["all_money_info"] =[
            "all_money" => $agent_info["all_yxyx_money"]/100,
            "open_moeny" => $agent_info["all_open_cush_money"]/100,
        ];

        $data["order_money_info"] =[
            "all_money" => $agent_info["all_money"]/100,
            "open_moeny" => $agent_info["order_open_all_money"]/100,
        ];

        $data["invite_money_info"] =[
            "all_money" => $agent_info["l1_agent_status_all_money"]/100,
            "open_moeny" => $agent_info["l1_agent_status_all_open_money"]/100,
        ];

        $data["l2_invite_money_info"] =[
            "all_money" => $agent_info["l2_agent_status_all_money"]/100,
            "open_moeny" => $agent_info["l2_agent_status_all_open_money"]/100,
        ];

        $activity_money=$this->t_agent_money_ex->get_all_money($agent_id)/100;

        $data["activity_money_info"] =[
            "all_money" => $activity_money,
            "open_moeny" => $activity_money ,
        ];


        $data["child_all_count"]= $agent_info["l1_child_count"] + $agent_info["l2_child_count"] ;
        $data["order_user_count"]= $agent_info["child_order_count"] ;

        //$data["order_user_count"]= //$this->ag
        //$data["invite_money_not_open_lesson_succ"]=$this->t_agent->get_invite_money( $agent_id  ,1,0)/100;
        //$data["invite_money_not_open_not_lesson_succ"]=$this->t_agent->get_invite_money($agent_id,0,0)/100;


        return $this->output_succ(["user_info_list" =>$data]);

    }
    public function get_l1_invite_money_list() {
        $agent_id= $this->get_agent_id();
        $agent_status_money_open_flag = $this-> get_in_int_val("agent_status_money_open_flag",-1);
        $test_lesson_succ_flag        = $this-> get_in_int_val("test_lesson_succ_flag",-1);
        $list=$this->t_agent-> get_invite_money_list($agent_id, $test_lesson_succ_flag , $agent_status_money_open_flag );
        foreach ($list  as &$item) {
            E\Eagent_status::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            $item["agent_status_money"]/=100;
            $item["nick"]= $item["nickname"]."/". $item["phone"];
            E\Eboolean::set_item_value_str($item,"agent_status_money_open_flag");
        }

        return $this->output_succ(["list" => $list]);
    }

    public function get_l2_invite_money_list() {
        $agent_id= $this->get_agent_id();
        $agent_status_money_open_flag = $this-> get_in_int_val("agent_status_money_open_flag",-1);
        $test_lesson_succ_flag        = $this-> get_in_int_val("test_lesson_succ_flag",-1);
        $list=$this->t_agent-> get_l2_invite_money_list($agent_id, $test_lesson_succ_flag , $agent_status_money_open_flag );
        foreach ($list  as &$item) {
            E\Eagent_status::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            $item["agent_status_money"]/=100;
            $item["nick"]= $item["nickname"]."/". $item["phone"];
            E\Eboolean::set_item_value_str($item,"agent_status_money_open_flag");
        }

        return $this->output_succ(["list" => $list]);
    }



    public function get_user_info(){
        $agent_id   = $this->get_agent_id();
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
        $test_count   = 0;
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
        $cash_new = $cash_new>=0?$cash_new:0;
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

        return $this->output_succ(["user_info_list" =>$data]);
    }

    public function get_level_1_user_list(){
        $agent_id   = $this->get_agent_id();
        if (!$agent_id){
            return $this->output_err("没有信息");
        }
        $list= $this->t_agent->get_level_list( $agent_id );
        foreach ($list as &$item) {
            if ($item["nickname"] ) {
                $item["name"]= $item["nickname"]. "/". $item["phone"];
            }else{
                $item["name"]=  $item["phone"];
            }

            E\Eagent_type::set_item_value_str($item);
            E\Eagent_student_status::set_item_value_str($item);
            E\Eagent_status::set_item_value_str($item);
            $item["invaild_flag"]= $item["agent_student_status"]== E\Eagent_student_status::V_100?1:0;

            \App\Helper\Utils::unixtime2date_for_item($item,"create_time",'',"Y-m-d");
            $item["child_count"]*=1;
        }
        return $this->output_succ(["list"=>$list]);
    }

    public function get_level_2_user_list(){
        $agent_id   = $this->get_agent_id();
        if (!$agent_id){
            return $this->output_err("没有信息");
        }
        $sub_agent_id = $this->get_in_int_val("sub_agent_id");
        if ($this->t_agent->get_parentid($sub_agent_id)!= $agent_id   ) {
            return $this->output_err("出错,不是你的下级");
        }

        $list= $this->t_agent->get_level_list( $sub_agent_id );
        $ret_list=[];
        foreach ($list as $item) {
            if (in_array( $item["agent_type"] ,[1,3] ))  {//会员
                if ($item["nickname"] ) {
                    $item["name"]= $item["nickname"]. "/". $item["phone"];
                }else{
                    $item["name"]=  $item["phone"];
                }
                $item["agent_type"]=1; //设置为学员
                unset($item["child_count"]); //设置为学员
                E\Eagent_type::set_item_value_str($item);
                E\Eagent_student_status::set_item_value_str($item);
                \App\Helper\Utils::unixtime2date_for_item($item,"create_time",'',"Y-m-d");
                E\Eagent_status::set_item_value_str($item);
                $item["invaild_flag"]= $item["agent_student_status"]== E\Eagent_student_status::V_100?1:0;
                $ret_list[]=$item;
            }
        }

        return $this->output_succ(["list"=>$ret_list]);
    }


    public function get_user_money_info() {
        $agent_id = $this->get_agent_id();

    }

    public function get_user_cash(){
        $agent_id = $this->get_agent_id();
        $type=$this->get_in_int_val("type");

        $list=$this->t_agent->get_link_list_by_ppid($agent_id);

        $ret_list=[];
        /*
        " a1.userid as p_userid,a1.id as pid,  a1.nickname p_nick, a1.phone p_phone,  "
            . " a1.agent_level p_agent_level , a1.test_lessonid p_test_lessonid,    "
            . " a1.type p_agent_type, "
            . " a1.test_lessonid  p_test_lessonid, "

            . " a.userid as userid, a.id as id,  a.nickname nick, a.phone phone, "
            . " a.agent_level agent_level , a.test_lessonid test_lessonid , "
            . " a.type agent_type, "
            . " a.test_lessonid  test_lessonid ,"


            . " ao1.p_level o_p_agent_level, ao1.p_price o_p_price,  o1.price o_p_from_price, o1.pay_time o_p_from_pay_time,  o1.orderid  o_p_from_orderid "
            . " ao.pp_level o_agent_level , ao.pp_price o_price ,  o1.price o_from_price , o.pay_time o_from_pay_time  ,  o.orderid  o_from_orderid "
        */
        //{"price":490,"userid":"214727","orderid":"20854","pay_price":4900,"pay_time":"2017-08-13 16:30:43","parent_name":"15296031880","order_time":"1503558534","count":"0","order_cash":0,"level1_cash":98,"level2_cash":392}


        $id_map=[];
        foreach ( $list as &$item ) {

            $userid=$item["userid"];
            $price=$item["o_price"]/100; //提成
            $pay_price=$item["o_from_price"]/100; //订单定额
            $orderid=$item["o_from_orderid"];
            $pay_time=$item["o_from_pay_time"];
            $nick=$item["nick"];
            $phone=$item["phone"];


            $p_userid=$item["p_userid"];
            $p_price=$item["o_p_price"]/100;
            $p_pay_price=$item["o_p_from_price"]/100; //订单定额
            $p_orderid=$item["o_p_from_orderid"];
            $p_pay_time=$item["o_p_from_pay_time"];
            $p_nick=$item["p_nick"];
            $p_phone=$item["p_phone"];
            $item=[];

            if ($p_price) { //第一级有金额
                if (isset($id_map[$p_userid ]) ) {
                    continue;
                }
                $id_map[$p_userid]=true;
                $item["userid"]=$p_userid;
                $item["price"]=$p_price;
                $item["pay_price"]=$p_pay_price;
                $item["orderid"]=$p_orderid;
                $item["pay_time"]=$p_pay_time;

                $item["nick"]=$p_nick;
                $item["phone"]=$p_phone;

                $ret_list[]= $item;
            }
            if ($price)  { //第二级有金额
                if (isset($id_map[$userid ]) ) {
                    continue;
                }
                $id_map[$userid]=true;
                $item["userid"]=$userid;
                $item["price"]=$price;
                $item["pay_price"]=$pay_price;
                $item["orderid"]=$orderid;
                $item["pay_time"]=$pay_time;

                $item["nick"]=$nick;
                $item["phone"]=$phone;
                $ret_list[]= $item;
            }
        }
        unset( $item);

        //{"price":490,"userid":"214727","orderid":"20854","pay_price":4900,"pay_time":"2017-08-13 16:30:43","parent_name":"15296031880","order_time":"1503558534","count":"0","order_cash":0,"level1_cash":98,"level2_cash":392}
        $cash=0;
        $a_list=[];

        foreach ( $ret_list as $item ) {

            $userid=$item["userid"];
            $item["level1_cash"] = $item["price"]*0.2;
            $item["level2_cash"] = $item["price"]*0.8;
            $lesson_info= $this->t_lesson_info_b2->get_lesson_count_by_userid($userid,$item["pay_time"]);
            $lesson_count=$lesson_info["count"] ;;
            $item["count"] = $lesson_count ;
            $item["parent_name"] = $item["nick"]."/".$item["phone"];
            \App\Helper\Utils::unixtime2date_for_item($item,"pay_time","" ,"Y-m-d");
            $order_cash=0;
            if ($lesson_count >=2) {
                $order_cash+=  $item["level1_cash"];
            }
            if ($lesson_count >=8) {
                $order_cash+=  $item["level2_cash"];
            }
            $item["order_cash"] = $order_cash;
            $cash+= $order_cash;
            if ($type==0) {
                $a_list[]=$item;
            }else {
                if ($order_cash >0 ) {
                    $a_list[]=$item;
                }
            }

        }
        //type=0: array(array( "pay_time" => 购课时间 "parent_name" => 家长姓名 "count" => 上课次数 "order_cash" => 单笔提现金额 "level1_cash" => 上满2次课可提现金额 "level2_cash" => 上满8次课可提现金额 )) type=1: array(array( "price" => 单笔收入 "pay_price" => 购买课程金额 "pay_time" => 购课时间 "parent_name" => 家长姓名 "count" => 学生上课次数 "level1_cash" => 上2~8次课提现金额 "level2_cash" => 上8次课提现金额 ))

        return $this->output_succ([
            "list" => $a_list,
            "cash" => $cash,
        ]);
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
            $ret_list[$key]['is_suc_flag'] = $item['check_money_flag'];
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
            if(4<=$count){
                $cash += $pay;
                $ret_list[$key]['order_cash'] = $pay;
            }elseif(2<=$count && $count<4){
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
        $cash*=100;
        $id            = $agent_id;
        if (!($cash>0)) {
            return $this->output_err("提现金额不对!");
        }


        $agent_info=$this->t_agent->field_get_list($agent_id ,"*");
        $total_cash = $agent_info["all_open_cush_money"];
        $have_cash = $this->t_agent_cash->get_have_cash($agent_id);
        $cash_new = $cash + $have_cash;
        if($cash_new > $total_cash){
            return $this->output_err("超出可提现金额!");
        }

        if($bankcard){
            if($bankcard==0 || $bank_address=="" || $bank_account==""
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
            }
        }elseif($zfb_account){
            if($zfb_name=='' || $zfb_account==''){
                return $this->output_err("请完善所有数据后重新提交！");
            }
            $ret = $this->t_agent->field_update_list($id,[
                "zfb_name"     => $zfb_name,
                "zfb_account"     => $zfb_account,
            ]);

        }
        $ret_new = $this->t_agent_cash->row_insert([
            "aid"         => $id,
            "cash"        => $cash,
            "is_suc_flag" => 0,
            "type"        => 1,
            "create_time" => time(null),
        ]);

        return $this->output_succ();
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

    public function sendWxMsg($openid){
        $template_id = "";
        $data_msg = [
            "first"     => "$opt_nick 老师发布了一条投诉",
            "keyword1"  => "常规投诉",
            "keyword2"  => "老师投诉内容:$report_msg",
            "keyword3"  => "投诉时间 $log_time_date ",
        ];
        $url = 'http://admin.yb1v1.com/user_manage/qc_complaint/';
        $wx=new \App\Helper\Wx();
        $wx->send_template_msg($qc_item,$template_id,$data_msg ,$url);
    }


}
