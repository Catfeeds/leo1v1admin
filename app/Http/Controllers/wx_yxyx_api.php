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
        $this->middleware(function ($request, $next) {
            if (! $this->get_agent_id()){
                echo $this->output_err("未登录");
                exit;
            }
            return $next($request);
        });
    }

    public function get_agent_id(){
        $agent_id= $this->get_in_int_val("_agent_id")?$this->get_in_int_val("_agent_id"):session("agent_id");
        return $agent_id;
    }
    public function get_user_info_new(){
        $agent_id   = $this->get_agent_id();
        $agent_info = $this->t_agent->get_agent_info_by_id($agent_id);
        $userid = $agent_info['userid'];

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

        if($userid){
            $ruffian_money = $this->t_luck_draw_yxyx_for_ruffian->get_ruffian_money($userid);
            if(!$ruffian_money){
                $ruffian_money = 0;
            }
        }else{
            $ruffian_money = 0;
        }

        $data["ruffian_money_info"] =[
            "all_money" => $ruffian_money,
            "open_moeny" => $ruffian_money,
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
        $check_money_flag = $this->get_in_int_val('check_money_flag');
        if(isset($agent_info['phone'])){
            $phone = $agent_info['phone'];
        }else{
            return $this->output_err("请先绑定优学优享账号!");
        }
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }
        $ret_list = [];
        $ret = $this->t_agent_cash->get_cash_list_by_phone($phone,$check_money_flag);
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
        $have_cash = $this->t_agent_cash->get_have_cash($agent_id,[0,1]);
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
        $url = 'http://admin.leo1v1.com/user_manage/qc_complaint/';
        $wx=new \App\Helper\Wx();
        $wx->send_template_msg($qc_item,$template_id,$data_msg ,$url);
    }


    public function get_user_center_info(){
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
            "all_money" => $agent_info["all_yxyx_money"]/100,
            'phone' => $agent_info['phone'],
            'wx_openid' => $agent_info['wx_openid']
        ];

        E\Eagent_level::set_item_value_str($data);

        $activity_money=$this->t_agent_money_ex->get_all_money($agent_id)/100;

        $data["child_all_count"]= $agent_info["l1_child_count"];

        //获取用户邀请人试听情况
        $child_test_lesson_info = $this->t_agent->get_child_test_lesson_info_by_parentid($agent_id);
        $test_lesson_succ_flog = 0;
        foreach($child_test_lesson_info as &$item){
            if($item['lesson_user_online_status'] == 1){
                $test_lesson_succ_flog = 1;
                break;
            }
                
        }
        $data['test_lesson_succ_flog'] = $test_lesson_succ_flog;

        return $this->output_succ(["user_info_list" =>$data]);

    }
    //@desn:我的收入页面
    public function my_income_info(){
        $agent_id   = $this->get_agent_id();
        $agent_info = $this->t_agent->get_agent_info_by_id($agent_id);
        if(isset($agent_info['phone'])){
            $phone = $agent_info['phone'];
        }else{
            return $this->output_err("请先绑定优学优享账号!");
        }

        $list = [
            "all_money" => $agent_info["all_yxyx_money"]/100,
            "open_moeny" => ($agent_info["all_open_cush_money"]-$agent_info['all_have_cush_money'])/100,
            "all_have_cush_money" => $agent_info["all_have_cush_money"]/100,
        ];

        //获取提现中金额
        $list['is_cash_money'] = $this->t_agent_cash->get_is_cashing_money($agent_id)/100;
        return $this->output_succ(['income_info'=>$list]);
    }
    //@desn:获取我的邀请、会员邀请奖励列表
    public function get_invite_list(){
        $agent_id   = $this->get_agent_id();
        $table_type   = $this->get_in_int_val('table_type',1);
        $page_count = empty($this->get_in_int_val('page_count'))?5:$this->get_in_int_val('page_count');
        $page_info = $this->get_in_page_info();
        if (!$agent_id){
            return $this->output_err("没有信息");
        }
        if($table_type == 1){
            $list = $this->t_agent->my_invite($agent_id,$page_info,$page_count);
            foreach($list['list'] as &$item){
                \App\Helper\Utils::unixtime2date_for_item($item,"create_time",'',"Y-m-d");
                if($item['agent_status'] > 0 && $item['agent_status'] < 2)
                    $item['agent_status'] = "0";
                $item['agent_status_money'] /=100;
                if($item['nickname'])
                    $item['nickname'] = $item['nickname'];
                else
                    $item['nickname'] = $item['phone'];
                E\Eagent_student_status::set_item_value_str($item);
                E\Eagent_status::set_item_value_str($item);
            }
            return $this->output_succ([
                "my_invite"=>$list,
            ]);

        }elseif($table_type == 2){
            $data = $this->t_agent->member_invite($agent_id,$page_info,$page_count);
            foreach($data['list'] as &$item){
                \App\Helper\Utils::unixtime2date_for_item($item,"create_time",'',"Y-m-d");
                if($item['agent_status'] > 0 && $item['agent_status'] < 2)
                    $item['agent_status'] = "0";
                if($item['agent_status'] >30)
                    $item['agent_status'] = "30";
                $item['agent_status_money'] /=100;
                if($item['nickname'])
                    $item['nickname'] = $item['nickname'];
                else
                    $item['nickname'] = $item['phone'];
                E\Eagent_student_status::set_item_value_str($item);
                E\Eagent_status::set_item_value_str($item);
            }
            return $this->output_succ([
                "member_invite"=>$data,
            ]);
        }else{
            return $this->output_err('传入类型错误！');
        }

    }

    //@desn:获取银行卡信息
    public function get_agent_bank_info(){
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
            "bank_account"  => $ret['bank_account'],
            "idcard"        => $ret['idcard'],
            "bank_type"     => $ret['bank_type'],
            "bank_address"  => $ret['bank_address'],
            "bank_province" => $ret['bank_province'],
            "bank_city"     => $ret['bank_city'],
            "bankcard"      => $ret['bankcard'],
            "bank_phone"    => $ret['bank_phone'],
        ];

        return $this->output_succ(["data" =>$data]);
    }

    //@desn:获取用户支付包信息
    public function get_agent_alipay_info(){
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
            "zfb_name"      => $ret['zfb_name'],
            "zfb_account"   => $ret['zfb_account'],
        ];

        return $this->output_succ(["data" =>$data]);
    }
    //获取用户佣金奖励
    public function get_commission_reward(){
        $agent_id = $this->get_agent_id();
        $table_type   = $this->get_in_int_val('table_type',1);
        $agent_info = $this->t_agent->get_agent_info_by_id($agent_id);
        $page_info = $this->get_in_page_info();
        $page_count = empty($this->get_in_int_val('page_count'))?5:$this->get_in_int_val('page_count');
        if(isset($agent_info['phone'])){
            $phone = $agent_info['phone'];
        }else{
            return $this->output_err("请先绑定优学优享账号!");
        }
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }

        if($table_type == 1){
            //获取用户邀请人佣金奖励
            $invite_child_reward = $this->t_agent_order->get_invite_child_reward($agent_id,$type=1,$page_info,$page_count);

            foreach($invite_child_reward['list'] as &$item){
                if($item['nickname'])
                    $item['nickname'] = $item['nickname'];
                else
                    $item['nickname'] = $item['phone'];
                \App\Helper\Utils::unixtime2date_for_item($item,"create_time",'',"Y-m-d");
                $item['price'] /= 100;
                $item['p_price'] /= 100;
                $lesson_info= $this->t_lesson_info_b2->get_lesson_count_by_userid($item['userid'],$item["pay_time"]);
                $item['count']=$lesson_info["count"] ;
            }
            return $this->output_succ([
                'child_reward' => $invite_child_reward,
            ]);

        }elseif($table_type ==2){
            //获取会员邀请人佣金
            $member_child_reward = $this->t_agent_order->get_invite_child_reward($agent_id,$type=2,$page_info,$page_count);
            foreach($member_child_reward['list'] as &$item){
                if($item['nickname'])
                    $item['nickname'] = $item['nickname'];
                else
                    $item['nickname'] = $item['phone'];
                \App\Helper\Utils::unixtime2date_for_item($item,"create_time",'',"Y-m-d");
                $item['price'] /= 100;
                $item['p_price'] = $item['pp_price']/100;
                $lesson_info= $this->t_lesson_info_b2->get_lesson_count_by_userid($item['userid'],$item["pay_time"]);
                $item['count']=$lesson_info["count"] ;
            }

            return $this->output_succ([
                'member_reward' => $member_child_reward,
            ]);
        }else{
            return $this->output_err('传入类型错误！');
        }


    }

    //@desn:更新银行卡信息
    public function update_bank_info(){
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
        $id            = $agent_id;
        $cash_type = 1;

        $agent_info=$this->t_agent->field_get_list($agent_id ,"*");
        $total_cash = $agent_info["all_open_cush_money"];
        $have_cash = $this->t_agent_cash->get_have_cash($agent_id,[0,1]);
        if (!($total_cash - $have_cash>0)) {
            return $this->output_err("无可提现金额!");
        }
        if($total_cash - $have_cash < 2500){
            return $this->output_err("可提现金额最低为25元!");
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

            $cash_type = 1;
        }elseif($zfb_account){
            if($zfb_name=='' || $zfb_account==''){
                return $this->output_err("请完善所有数据后重新提交！");
            }
            $ret = $this->t_agent->field_update_list($id,[
                "zfb_name"     => $zfb_name,
                "zfb_account"     => $zfb_account,
            ]);

            $cash_type = 2;
        }
        $ret_new = $this->t_agent_cash->row_insert([
            "aid"         => $id,
            "cash"        => $total_cash - $have_cash,
            "is_suc_flag" => 0,
            "type"        => $cash_type,
            "create_time" => time(null),
        ]);

        return $this->output_succ();

    }
    //@desn:获取邀请奖励[我邀请的、会员邀请]
    public function get_had_invite_rewards(){
        $agent_id = $this->get_agent_id();
        $table_type   = $this->get_in_int_val('table_type',1);
        $agent_info = $this->t_agent->get_agent_info_by_id($agent_id);
        $page_info = $this->get_in_page_info();
        $page_count = empty($this->get_in_int_val('page_count'))?5:$this->get_in_int_val('page_count');
        if(isset($agent_info['phone'])){
            $phone = $agent_info['phone'];
        }else{
            return $this->output_err("请先绑定优学优享账号!");
        }
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }
        //获取上次提现成功的申请体现时间
        $last_succ_cash_time = $this->t_agent_cash->get_last_succ_cash_time($agent_id);
        if($table_type == 1){
            //获取自己邀请的奖励列表
            $list = $this->t_agent->my_had_invite($agent_id,$page_info,$page_count,$last_succ_cash_time);
            foreach($list['list'] as $key => &$item){
                if($item['nickname'])
                    $item['nickname'] = $item['nickname'];
                else
                    $item['nickname'] = $item['phone'];
                $item['agent_status_money'] /= 100;
            }
            return $this->output_succ([
                "my_invite"=>$list,
            ]);
        }elseif($table_type == 2){
            //获取会员邀请的奖励列表
            $data = $this->t_agent->member_had_invite($agent_id,$page_info,$page_count,$last_succ_cash_time);
            foreach($data['list'] as $key => &$item){
                if($item['nickname'])
                    $item['nickname'] = $item['nickname'];
                else
                    $item['nickname'] = $item['phone'];
                $item['agent_status_money'] /= 100;
            }

            return $this->output_succ([
                "member_invite"=>$data,
            ]);
        }else{
            return $this->output_err('传入类型错误！');
        }

    }
    //@desn:获取可提现佣金奖励
    public function get_can_cash_commission(){
        $agent_id = $this->get_agent_id();
        $table_type   = $this->get_in_int_val('table_type',1);
        $agent_info = $this->t_agent->get_agent_info_by_id($agent_id);
        $page_info = $this->get_in_page_info();
        $page_count = empty($this->get_in_int_val('page_count'))?5:$this->get_in_int_val('page_count');
        if(isset($agent_info['phone'])){
            $phone = $agent_info['phone'];
        }else{
            return $this->output_err("请先绑定优学优享账号!");
        }
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }
        //获取上次提现成功的申请体现时间
        $last_succ_cash_time = $this->t_agent_cash->get_last_succ_cash_time($agent_id);

        if($table_type ==1){
            //获取用户邀请人佣金奖励
            $invite_child_reward = $this->t_agent_order->get_can_cash_commission_reward($agent_id,$type=1,$page_info,$page_count,$last_succ_cash_time);

            foreach($invite_child_reward['list'] as &$item){
                if($item['nickname'])
                    $item['nickname'] = $item['nickname'];
                else
                    $item['nickname'] = $item['phone'];
                \App\Helper\Utils::unixtime2date_for_item($item,"create_time",'',"Y-m-d");
                $item['price'] /= 100;
                //获取已经提现的金额
                $has_cash_commission = $this->t_agent_income_log->get_has_cash_commission($agent_id,$item['aid'],$last_succ_cash_time,1);
                $item['p_open_price'] -= $has_cash_commission;
                $item['p_open_price'] /= 100;
                $lesson_info= $this->t_lesson_info_b2->get_lesson_count_by_userid($item['userid'],$item["pay_time"]);
                $item['count']=$lesson_info["count"] ;
            }
            return $this->output_succ([
                'child_reward' => $invite_child_reward,
            ]);
        }elseif($table_type ==2 ){
            

            //获取会员邀请人佣金
            $member_child_reward = $this->t_agent_order->get_can_cash_commission_reward($agent_id,$type=2,$page_info,$page_count,$last_succ_cash_time);
            foreach($member_child_reward['list'] as &$item){
                if($item['nickname'])
                    $item['nickname'] = $item['nickname'];
                else
                    $item['nickname'] = $item['phone'];
                \App\Helper\Utils::unixtime2date_for_item($item,"create_time",'',"Y-m-d");
                $item['price'] /= 100;
                //获取已经提现的金额
                $has_cash_commission = $this->t_agent_income_log->get_has_cash_commission($agent_id,$item['aid'],$last_succ_cash_time,2);
                $item['pp_open_price'] -= $has_cash_commission;
                $item['p_open_price'] = $item['pp_open_price']/100;
                $lesson_info= $this->t_lesson_info_b2->get_lesson_count_by_userid($item['userid'],$item["pay_time"]);
                $item['count']=$lesson_info["count"] ;
            }

            return $this->output_succ([
                'member_reward' => $member_child_reward,
            ]);
        }else{
            return $this->output_err('传入类型错误！');
        }

    }
    //@desn:获取邀请人列表
    public function get_invite_type_list(){
        $agent_id = $this->get_agent_id();
        $table_type = $this->get_in_int_val('table_type',1);
        $agent_info = $this->t_agent->get_agent_info_by_id($agent_id);
        $page_info = $this->get_in_page_info();
        $page_count = empty($this->get_in_int_val('page_count'))?5:$this->get_in_int_val('page_count');
        if(isset($agent_info['phone'])){
            $phone = $agent_info['phone'];
        }else{
            return $this->output_err("请先绑定优学优享账号!");
        }
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }

        if($table_type == 1){
            //获取一级用户为学员的列表
            $student_list = $this->t_agent->get_invite_type_list($agent_id,$type=1,$page_info,$page_count);
            foreach($student_list['list'] as &$item){
                if($item['agent_status'] > 0 && $item['agent_status'] < 2)
                    $item['agent_status'] = "0";
                \App\Helper\Utils::unixtime2date_for_item($item,"create_time",'',"Y-m-d");
                if($item['nickname'])
                    $item['nickname'] = $item['nickname'];
                else
                    $item['nickname'] = $item['phone'];
            }
            return $this->output_succ([
                'student_list' => $student_list,
                'steudent_first_num' => $student_list['total_num'],
            ]);
        }elseif($table_type == 2){
            //获取一级用户为会员的列表
            $member_list = $this->t_agent->get_invite_type_list($agent_id,$type=2,$page_info,$page_count);
            foreach($member_list['list'] as &$item){
                if($item['agent_status'] > 0 && $item['agent_status'] < 2)
                    $item['agent_status'] = "0";
                \App\Helper\Utils::unixtime2date_for_item($item,"create_time",'',"Y-m-d");
                if($item['nickname'])
                    $item['nickname'] = $item['nickname'];
                else
                    $item['nickname'] = $item['phone'];
                $item['child'] = $this->t_agent->get_second_invite_list($item['id']);
                foreach($item['child'] as &$val){
                    \App\Helper\Utils::unixtime2date_for_item($val,"create_time",'',"Y-m-d");
                    if(empty($val['nickname']))
                        $val['nickname'] = $val['phone'];
                    if($val['agent_status'] < 2)
                        $val['agent_status'] = "0";
                    $val['price'] /= 100;
                }
                $item['second_num'] = count($item['child']);
            }
            return $this->output_succ([
                'member_list' => $member_list,
                'member_first_num' => $member_list['total_num'],
            ]);
        }elseif($table_type == 3){
            //获取一级用户为学员&会员的列表
            $student_and_member_list = $this->t_agent->get_invite_type_list($agent_id,$type=3,$page_info,$page_count);
            foreach($student_and_member_list['list'] as &$item){
                if($item['agent_status'] > 0 && $item['agent_status'] < 2)
                    $item['agent_status'] = "0";
                \App\Helper\Utils::unixtime2date_for_item($item,"create_time",'',"Y-m-d");
                if($item['nickname'])
                    $item['nickname'] = $item['nickname'];
                else
                    $item['nickname'] = $item['phone'];
                $item['child'] = $this->t_agent->get_second_invite_list($item['id']);
                foreach($item['child'] as &$val){
                    \App\Helper\Utils::unixtime2date_for_item($val,"create_time",'',"Y-m-d");
                    if(empty($val['nickname']))
                        $val['nickname'] = $val['phone'];
                    if($val['agent_status'] < 2)
                        $val['agent_status'] = "0";
                    $val['price'] /= 100;
                }
                $item['second_num'] = count($item['child']);
            }


            return $this->output_succ([
                'student_and_member_list' => $student_and_member_list,
                'student_and_member_first_num' => $student_and_member_list['total_num']
            ]);
        }else{
            return $this->output_err('传入类型错误！');
        }

    }
    //@desn:获取全部活动奖励
    //@param:is_cash 是否可提现标识  0 全部 2：可提现
    public function get_activity_rewards(){
        $is_cash = $this->get_in_int_val('is_cash',0);
        $agent_id = $this->get_agent_id();
        $agent_info = $this->t_agent->get_agent_info_by_id($agent_id);
        $page_info = $this->get_in_page_info();
        $page_count = empty($this->get_in_int_val('page_count'))?5:$this->get_in_int_val('page_count');
        if(isset($agent_info['phone'])){
            $phone = $agent_info['phone'];
        }else{
            return $this->output_err("请先绑定优学优享账号!");
        }
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }
        //获取上次提现成功的申请体现时间
        $last_succ_cash_time = $this->t_agent_cash->get_last_succ_cash_time($agent_id);
        //获取未体现的转盘奖励id_str  
        $daily_lottery_id_str = $this->t_agent_income_log->get_daily_lottery_id_str($agent_id,$last_succ_cash_time);
        $lid_str = '';
        $lid_arr = [];
        foreach($daily_lottery_id_str as $val){
            $lid_arr[]=$val['activity_id_str'];
        }
        if($lid_arr)
            $lid_str = join(',',$lid_arr);
        //获取未体现赠送现金奖励id_str
        $agent_money_ex_arr = $this->t_agent_income_log->get_agent_money_ex_arr($agent_id,$last_succ_cash_time);
        $agent_money_id_arr = [];
        $agent_money_id_str = '';
        foreach($agent_money_ex_arr as $val){
            $agent_money_id_arr[] = $val['agent_money_ex_id'];
        }
        if($agent_money_id_arr)
            $agent_money_id_str = join(',',$agent_money_id_arr);
        $reward_list = $this->t_agent_money_ex->get_reward_list($agent_id,$page_info,$page_count,$is_cash,$lid_str,$agent_money_id_str);
        foreach($reward_list['list'] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time",'',"Y-m-d");
            if($item['activity_type'] ==1)
                E\Eagent_money_ex_type::set_item_value_str($item);
            else{
                $item['l_type'] = $item['agent_money_ex_type'];
                E\El_type::set_item_value_str($item);
                $item['agent_money_ex_type_str'] = $item['l_type_str'];
            }
                
            $item['money'] /= 100;
        }
        if(!$is_cash){
            //获取活动奖励总金额
            $activity_total_money = $this->t_agent_money_ex->get_activity_total_money($agent_id,$is_cash);
            $activity_daily_lottery = $this->t_agent_daily_lottery->get_sum_daily_lottery($agent_id,$is_cash);
        }else{
            //获取活动奖励
            $activity_total_money = $this->t_agent_money_ex->get_can_cash_activity_money($agent_id,$is_cash,$last_succ_cash_time);
            //获取未体现的转盘奖励id_str
            $daily_lottery_id_str = $this->t_agent_income_log->get_daily_lottery_id_str($agent_id,$last_succ_cash_time);
            $lid_str = '';
            foreach($daily_lottery_id_str as $val){
                $lid_arr[]=$val['activity_id_str'];
            }
            if($lid_arr)
                $lid_str = join(',',$lid_arr);
            $check_flag = 1;
            $activity_daily_lottery = $this->t_agent_daily_lottery->get_can_cash_daily_lottery($agent_id,$check_flag,$lid_str);
        }
        $activity_total_money =($activity_total_money+$activity_daily_lottery)/100;
        return $this->output_succ([
            'reward_list' => $reward_list,
            'activity_total_money' => $activity_total_money
        ]);
    }
    //@desn:获取用户邀请奖励、佣金奖励、活动奖励之和
    public function agent_reward_sort_sum(){
        $agent_id = $this->get_agent_id();
        $check_flag = $this->get_in_int_val('check_flag');
        $agent_info = $this->t_agent->get_agent_info_by_id($agent_id);
        if(isset($agent_info['phone'])){
            $phone = $agent_info['phone'];
        }else{
            return $this->output_err("请先绑定优学优享账号!");
        }
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }
        //获取上次提现成功的申请体现时间
        $last_succ_cash_time = $this->t_agent_cash->get_last_succ_cash_time($agent_id);

        if(!$check_flag){
            //获取用户邀请奖励
            $l1_child_invite_reward = $this->t_agent->get_l1_agent_status_all_money($agent_id);
            $l2_child_invite_reward = $this->t_agent->get_l2_agent_status_all_money($agent_id);
            $invite_reward = ($l1_child_invite_reward+$l2_child_invite_reward)/100;
            //获取佣金奖励
            $l1_child_commission_reward = $this->t_agent_order->get_l1_child_commission_reward($agent_id);
            $l2_child_commission_reward = $this->t_agent_order->get_l2_child_commission_reward($agent_id);
            $commission_reward = ($l1_child_commission_reward+$l2_child_commission_reward)/100;
            //获取活动奖励
            $activity_money_ex_all = $this->t_agent_money_ex->get_agent_sum_activity_money($agent_id,$check_flag);
            $activity_daily_lottery = $this->t_agent_daily_lottery->get_sum_daily_lottery($agent_id,$check_flag);
        }else{
            //获取可体现用户邀请奖励
            $l1_child_can_cash_invite_reward = $this->t_agent->get_now_l1_all_open_money($agent_id,$last_succ_cash_time);
            $l2_child_can_cash_invite_reward = $this->t_agent->get_now_l2_all_open_money($agent_id,$last_succ_cash_time);
            $invite_reward = ($l1_child_can_cash_invite_reward+$l2_child_can_cash_invite_reward)/100;
            //获取一级可提现佣金奖励
            $l1_child_can_cash_commission_reward = $this->t_agent_order->get_now_l1_commission_money($agent_id,$last_succ_cash_time);
            //获取一级部分之前已经提现的佣金
            $l1_child_has_cash = $this->t_agent_income_log->get_l1_child_has_cash($agent_id,$last_succ_cash_time);
            //获取二级可提现佣金奖励
            $l2_child_can_cash_commission_reward = $this->t_agent_order->get_now_l2_commission_money($agent_id,$last_succ_cash_time);
            //获取二级部分之前已经提现的佣金
            $l2_child_has_cash = $this->t_agent_income_log->get_l2_child_has_cash($agent_id,$last_succ_cash_time);


            $commission_reward =$l1_child_can_cash_commission_reward-$l1_child_has_cash+$l2_child_can_cash_commission_reward-$l2_child_has_cash ;
            $commission_reward /= 100;
            //获取活动奖励
            $activity_money_ex_all = $this->t_agent_money_ex->get_can_cash_activity_money($agent_id,$check_flag,$last_succ_cash_time);
            //获取未体现的转盘奖励id_str
            $daily_lottery_id_str = $this->t_agent_income_log->get_daily_lottery_id_str($agent_id,$last_succ_cash_time);
            $lid_str = '';
            $lid_arr = [];
            foreach($daily_lottery_id_str as $val){
                $lid_arr[]=$val['activity_id_str'];
            }
            if($lid_arr)
                $lid_str = join(',',$lid_arr);
            $check_flag = 1;
            $activity_daily_lottery = $this->t_agent_daily_lottery->get_can_cash_daily_lottery($agent_id,$check_flag,$lid_str);
        }

        $activity_money = @$activity_money_ex_all + @$activity_daily_lottery;
        return $this->output_succ([
            'invite_reward' => $invite_reward,
            'commission_reward' => $commission_reward,
            'activity_money' => $activity_money/100
        ]);
    }
    //@desn:制作推荐的图片
    public function get_agent_invite_img(){
        $agent_id = $this->get_agent_id();
        $check_flag = $this->get_in_int_val('check_flag');
        $agent_info = $this->t_agent->get_agent_info_by_id($agent_id);
        $img_type = $this->get_in_int_val('img_type');
        $phone = '';
        if(isset($agent_info['phone'])){
            $phone = $agent_info['phone'];
        }else{
            return $this->output_err("请先绑定优学优享账号!");
        }
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }
        //生成图片  --begin--
        $request = '';
        if($img_type == 1){
            $bg_url      = "http://7u2f5q.com2.z0.glb.qiniucdn.com/0404fa8aeb8160820d2709baee4909871510113929932.jpg";
            $qr_code_url = "http://www.leo1v1.com/market-invite/index.html?p_phone=$phone&type=1";
            if(\App\Helper\Utils::check_env_is_test())
                $qr_code_url = "http://test.www.leo1v1.com/market-invite/index.html?p_phone=$phone&type=1";
        }elseif($img_type == 2){
            $bg_url = "http://7u2f5q.com2.z0.glb.qiniucdn.com/4fa4f2970f6df4cf69bc37f0391b14751506672309999.png";
            $qr_code_url = "http://www.leo1v1.com/market-invite/index.html?p_phone=$phone&type=2";
            if(\App\Helper\Utils::check_env_is_test())
                $qr_code_url = "http://test.www.leo1v1.com/market-invite/index.html?p_phone=$phone&type=2";
        }
        $invite_img = \App\Helper\Utils::make_invite_img_new($bg_url,$qr_code_url,$agent_info,$img_type);
        $relative_path = 'http://admin.leo1v1.com'.$invite_img;
        if(\App\Helper\Utils::check_env_is_test())
            $relative_path = 'http://test.admin.leo1v1.com/'.$invite_img;

        \App\Helper\Utils::logger("qr_code_url $qr_code_url "); 

        //生成图片  --end--
        return $this->output_succ(['invite_img' => $relative_path]);
    }
    //@desn:获取用户抽奖次数
    public function get_daily_lottery_count(){
        $agent_id = $this->get_agent_id();
        $agent_info = $this->t_agent->get_agent_info_by_id($agent_id);
        $img_type = $this->get_in_int_val('img_type');
        $phone = '';
        if(isset($agent_info['phone'])){
            $phone = $agent_info['phone'];
        }else{
            return $this->output_err("请先绑定优学优享账号!");
        }
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }
        //获取该用户今日可用抽奖次数
        $daily_lottery_count = $this->agent_daily_lottery_count($agent_id);

        return $this->output_succ([
            'left_daily_lottery_count' => $daily_lottery_count,
        ]);
    }
    //@desn:获取用户每日抽奖次数
    //@param:用户优学优享id
    private function agent_daily_lottery_count($agent_id){
        //获取用户今日可用抽奖次数  --begin--
        $daily_lottery_count = 1;
        $begin_time = strtotime(date('Y-m-d'));
        $end_time = strtotime(date('Y-m-d 23:59:59'));
        $agent_today_invite_list = $this->t_agent->get_today_invite_list($agent_id,$begin_time,$end_time);
        $student_flag = false;
        $member_flag = false;
        foreach($agent_today_invite_list as &$item){
            if($item['type'] == 1 && !$student_flag){
                $daily_lottery_count++;
                $student_flag = true;
            }elseif($item['type'] ==2 && !$member_flag){
                $daily_lottery_count++;
                $member_flag = true;
            }elseif($item['type'] == 3){
                $daily_lottery_count++;
            }
            if($daily_lottery_count >= 3)
                break;
        }
        //获取用户今日可用抽奖次数  --end--

        //获取用户已消耗抽奖次数
        $has_used_count = $this->t_agent_daily_lottery->get_has_used_count($agent_id,$begin_time,$end_time);

        $daily_lottery_count = $daily_lottery_count - $has_used_count;
        if(\App\Helper\Utils::check_env_is_local())
            $daily_lottery_count  = 1;
        return $daily_lottery_count;
    }
    //@desn:优学优享每日抽奖
    public function do_daily_lottery(){
        $agent_id = $this->get_agent_id();
        $agent_info = $this->t_agent->get_agent_info_by_id($agent_id);
        $img_type = $this->get_in_int_val('img_type');
        $phone = '';
        if(isset($agent_info['phone'])){
            $phone = $agent_info['phone'];
        }else{
            return $this->output_err("请先绑定优学优享账号!");
        }
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }

        //用户可抽奖次数校验
        $daily_lottery_count = $this->agent_daily_lottery_count($agent_id);
        if(\App\Helper\Utils::check_env_is_local())
            $daily_lottery_count = 1;

        if($daily_lottery_count > 0){
            $the_prize = 1;
            $rand  = mt_rand(1,100);
            $money = 0;
            
            switch($rand){
            case $rand>=1 && $rand<=15:
                $the_prize = 1;//再接再厉
                break;
            case $rand>=16 && $rand<=30:
                $the_prize = 2;//再接再厉
                break;
            case $rand>=31 && $rand<=50:
                $the_prize = 3;//0.01
                $money = 1;
                break;
            case $rand>=51 && $rand<=70:
                $the_prize = 4;//0.05
                $money = 5;
                break;
            case $rand>=71 && $rand<=85:
                $the_prize = 5;//0.1
                $money = 10;
                break;
            case $rand>=86 && $rand<=90:
                $the_prize = 6;//0.5
                $money = 50;
                break;
            case $rand>=91 && $rand<=95:
                $the_prize = 7;//0.8
                $money = 80;
                break;
            case $rand>=96 && $rand<=100:
                $the_prize = 8;//1.00
                $money = 100;
                break;
            default:
                $the_prize = 1;
            };
            
            //插入获奖记录
            $insert_status = $this->t_agent_daily_lottery->row_insert([
                'l_type' => E\El_type::V_DAILY_LOTTERY,
                'money' => $money,
                'agent_id' => $agent_id,
                'create_time' => time(NULL),
            ]);

            if($insert_status){
                if($money > 0){
                    $this_field = 'all_yxyx_money';
                    //添加到用户总金额
                    $update_status = $this->t_agent->since_the_add($agent_id,$this_field,$money);
                    if($update_status){
                        return $this->output_succ([
                            'the_prize' => $the_prize,
                            'money' => $money/100
                        ]);
                    }else{
                        return $this->output_err('添加抽奖金额失败!');
                    }
                }else{
                    return $this->output_succ([
                        'the_prize' => $the_prize,
                        'money' => $money
                    ]);
                }

            }else{
                return $this->output_err('插入抽奖记录失败!');
            }


        }else{
            return $this->output_err('您今日的抽奖次数已用完!');
        }
    }
} 