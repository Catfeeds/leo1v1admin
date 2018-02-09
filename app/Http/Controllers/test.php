<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use App\Enums as f;

use Illuminate\Support\Facades\Mail ;

require_once  app_path("Libs/Pingpp/init.php");

class test extends Controller
{
    var $check_login_flag =true;

    public function tt() {
        $flow_type = 1;
        $config=\App\Helper\Utils::json_decode_as_array($this->t_flow_config->get_json_data($flow_type));
        dd($config);
        $gen_node_type= function( $type ) {
            $arr=preg_split("/ /", $type);
            return $arr[0];
        };

        /*
        $this->check_and_switch_tongji_domain();
        dispatch( new \App\Jobs\send_error_mail('', "SQL XXX", "title asdfa adfagf  "));
        phpinfo();
        */
    }

    public function test1() {
       $f= new \App\Jobs\new_seller_student(10001);
        return $this->output_succ();
    }
    public function tree() {
        return $this->pageOutJson(__METHOD__);
    }

    public function get_user_list(){
        #分页信息
        $page_info= $this->get_in_page_info();
        #排序信息
        list($order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str([],"userid desc");
        $origin_ex=$this->get_in_str_val("origin_ex");
        $group_admin_ex= $this->get_in_str_val("group_admin_ex");

        $xmpp_server_id=$this->get_in_str_val("xmpp_server_id");
        #输入参数
        list($start_time, $end_time)=$this->get_in_date_range_day(0);
        $userid=$this->get_in_userid(-1);
        $grade=$this->get_in_el_grade();
        $gender=$this->get_in_el_gender();
        $query_text=$this->get_in_query_text();
        $this->get_in_int_val("test_select",-1);

        $ret_info=$this->t_student_info->get_test_list($page_info, $order_by_str,  $grade );

        foreach($ret_info["list"] as &$item) {
            E\Egrade::set_item_value_str($item);
            $item["testv"]="xxx";
        }
        //sleep(3);
        //dd($this->html_power_list);

        return $this->pageView(__METHOD__, $ret_info,[
            "message" =>  "cur usrid:".$userid,
        ]);
    }
    public function get_user_list1(){
        $this->set_in_value("grade", 101);
        //$sys_operator_uid= $this->get_account_id();
        //$this->get_account_role();
        $this->set_in_value("adminid", $this->get_account_id())  ;

        $this->html_power_list_add([ "grade","opt_grade", "input_grade" ]);
        return $this->get_user_list();
    }

    public function test_user_list() {
        $grade = $this->get_in_int_val("grade");
        $this->set_in_value("grade", $grade);
        //$sys_operator_uid= $this->get_account_id();
        //$this->get_account_role();
        $this->set_in_value("adminid", $this->get_account_id())  ;

        $this->html_power_list_add([ "grade","opt_grade", "input_grade" ]);
        return $this->get_user_list();
    }

    public function set_student_nick() {
        $userid=$this->get_in_userid();
        $nick=$this->get_in_str_val("nick");
        $this->t_student_info->field_update_list($userid,[
            "nick"  => $nick
        ]);
        return $this->output_succ();
    }


    public function t() {
        //app_path("/Libs/xx/init.php");


        $objReader = \PHPExcel_IOFactory::createReader('Excel2003XML');
        $objPHPExcel = $objReader->load("/tmp/001.xls");
        $objPHPExcel->setActiveSheetIndex(0);
        $arr=$objPHPExcel->getActiveSheet()->toArray();
        foreach ( $arr as $index=> $item) {
            if ($index== 0 ) { //标题
                //验证字段名
                if ( trim($item[0]) != "手机号"
                     ||trim($item[1]) != "归属地"
                     ||trim($item[3]) != "来源"
                )  {
                    return "xxx" ;

                }
            }else{
                //导入数据
                /*
                  0 => "手机号"
                  1 => "归属地"
                  2 => "时间"
                  3 => "来源"
                  4 => "姓名"
                  5 => "用户备注"
                  6 => "年级"
                  7 => "科目"
                  8 => "是否有pad"
                */
                $phone          = $item[0];
                $phone_location = $item[1];
                $origin         = $item[3];
                $nick           = $item[4];
                $user_desc      = $item[5];
                $grade          = $item[6];
                $subject        = $item[7];
                $has_pad        = $item[8];

                if ( !$this->t_seller_student_info->check_phone_existed($phone) ) {
                    $this->t_seller_student_info->add($phone,$origin,$nick,$user_desc,$grade,$subject,$has_pad);
                }
            }
        }

        return "x" ;
    }

    public function get_pingpp_info(){
        $channel = "alipay_wap";
        $amount = 100;
        $orderNo = substr(md5(time()), 0, 12);

        /**
         * 设置请求签名密钥，密钥对需要你自己用 openssl 工具生成，如何生成可以参考帮助中心：https://help.pingxx.com/article/123161；
         * 生成密钥后，需要在代码中设置请求签名的私钥(rsa_private_key.pem)；
         * 然后登录 [Dashboard](https://dashboard.pingxx.com)->点击右上角公司名称->开发信息->商户公钥（用于商户身份验证）
         * 将你的公钥复制粘贴进去并且保存->先启用 Test 模式进行测试->测试通过后启用 Live 模式
         */

        // dd(app_path("Libs/Pingpp/init.php"));
        \Pingpp\Pingpp::setApiKey(APP_KEY);  // 设置 API Key
        \Pingpp\Pingpp::setPrivateKeyPath(app_path("Libs/Pingpp/your_rsa_private_key.pem"));   // 设置私钥
        //\Pingpp\Pingpp::setPrivateKey(file_get_contents(app_path("Libs/Pingpp/your_rsa_private_key.pem")));
        $r = \Pingpp\Pingpp::getApiKey();
        $t = \Pingpp\Pingpp::getPrivateKeyPath();
        //dd($r);
        // dd(\Pingpp\Pingpp::getPrivateKey());


        // 设置私钥内容方式2
        // \Pingpp\Pingpp::setPrivateKey(file_get_contents(__DIR__ . '/your_rsa_private_key.pem'));

        /**
         * $extra 在使用某些渠道的时候，需要填入相应的参数，其它渠道则是 array()。
         * 以下 channel 仅为部分示例，未列出的 channel 请查看文档 https://pingxx.com/document/api#api-c-new；
         * 或直接查看开发者中心：https://www.pingxx.com/docs/server；包含了所有渠道的 extra 参数的示例；
         */
        $extra = array();
        /*  switch ($channel) {
        case 'alipay_wap':
            $extra = array(
                // success_url 和 cancel_url 在本地测试不要写 localhost ，请写 127.0.0.1。URL 后面不要加自定义参数
                'success_url' => 'http://example.com/success',
                'cancel_url' => 'http://example.com/cancel'
            );
            break;
        case 'bfb_wap':
            $extra = array(
                'result_url' => 'http://example.com/result',// 百度钱包同步回调地址
                'bfb_login' => true// 是否需要登录百度钱包来进行支付
            );
            break;
        case 'upacp_wap':
            $extra = array(
                'result_url' => 'http://example.com/result'// 银联同步回调地址
            );
            break;
        case 'wx_pub':
            $extra = array(
                'open_id' => 'openidxxxxxxxxxxxx'// 用户在商户微信公众号下的唯一标识，获取方式可参考 pingpp-php/lib/WxpubOAuth.php
            );
            break;
        case 'wx_pub_qr':
            $extra = array(
                'product_id' => 'Productid'// 为二维码中包含的商品 ID，1-32 位字符串，商户可自定义
            );
            break;
        case 'yeepay_wap':
            $extra = array(
                'product_category' => '1',// 商品类别码参考链接 ：https://www.pingxx.com/api#api-appendix-2
                'identity_id'=> 'your identity_id',// 商户生成的用户账号唯一标识，最长 50 位字符串
                'identity_type' => 1,// 用户标识类型参考链接：https://www.pingxx.com/api#yeepay_identity_type
                'terminal_type' => 1,// 终端类型，对应取值 0:IMEI, 1:MAC, 2:UUID, 3:other
                'terminal_id'=>'your terminal_id',// 终端 ID
                'user_ua'=>'your user_ua',// 用户使用的移动终端的 UserAgent 信息
                'result_url'=>'http://example.com/result'// 前台通知地址
            );
            break;
        case 'jdpay_wap':
            $extra = array(
                'success_url' => 'http://example.com/success',// 支付成功页面跳转路径
                'fail_url'=> 'http://example.com/fail',// 支付失败页面跳转路径
                /**
                 *token 为用户交易令牌，用于识别用户信息，支付成功后会调用 success_url 返回给商户。
                 *商户可以记录这个 token 值，当用户再次支付的时候传入该 token，用户无需再次输入银行卡信息
                 */
            /*        'token' => 'dsafadsfasdfadsjuyhfnhujkijunhaf' // 选填
            );
            break;
            }*/


        try {
            $ch = \Pingpp\Charge::create(
                array(
                    //请求参数字段规则，请参考 API 文档：https://www.pingxx.com/api#api-c-new
                    'subject'   => 'Your Subject',
                    'body'      => 'Your Body',
                    'amount'    => $amount,//订单总金额, 人民币单位：分（如订单总金额为 1 元，此处请填 100）
                    'order_no'  => $orderNo,// 推荐使用 8-20 位，要求数字或字母，不允许其他字符
                    'currency'  => 'cny',
                    'extra'     => $extra,
                    'channel'   => $channel,// 支付使用的第三方支付渠道取值，请参考：https://www.pingxx.com/api#api-c-new
                    'client_ip' => $_SERVER['REMOTE_ADDR'],// 发起支付请求客户端的 IP 地址，格式为 IPV4，如: 127.0.0.1
                    'app'       => array('id' => APP_ID)
                )
            );
            echo $ch;// 输出 Ping++ 返回的支付凭据 Charge
        } catch (\Pingpp\Error\Base $e) {
            // 捕获报错信息
            if ($e->getHttpStatus() != null) {
                header('Status: ' . $e->getHttpStatus());
                echo $e->getHttpBody();
            } else {
                echo $e->getMessage();
            }
        }

    }

    public function get_charge_info(){
        \Pingpp\Pingpp::setApiKey(APP_KEY);  // 设置 API Key
        \Pingpp\Pingpp::setPrivateKeyPath(app_path("Libs/Pingpp/your_rsa_private_key.pem"));   // 设置私钥

        // 查询 charge 对象
        $charge_id = "ch_DKmTmHvPaffPS4Wv5C4ynfXD";
        try {
            $charge = \Pingpp\Charge::retrieve($charge_id);
            echo $charge;
        } catch (\Pingpp\Error\Base $e) {
            if ($e->getHttpStatus() != null) {
                header('Status: ' . $e->getHttpStatus());
                echo $e->getHttpBody();
            } else {
                echo $e->getMessage();
            }
        }
        // exit;


        // 查询 charge 对象列表
        $search_params = [
            'app'   => array('id' => APP_ID)            // 此参数必填
        ];
        //  $charge_all = \Pingpp\Charge::all($search_params);
        // dd($charge_all);
        try {
            $charge_all = \Pingpp\Charge::all($search_params);
            echo $charge_all;                                                     // 输出 Ping++ 返回的 charge 对象列表
        } catch (\Pingpp\Error\Base $e) {
            if ($e->getHttpStatus() != null) {
                header('Status: ' . $e->getHttpStatus());
                echo $e->getHttpBody();
            } else {
                echo $e->getMessage();
            }
        }

    }

    public function tea_lesson_count_detail_list() {
        $teacherid = $this->get_in_teacherid(0);
        $start_time      = $this->get_in_start_time_from_str(date("Y-m-01",time(NULL)) );
        $end_time        = $this->get_in_end_time_from_str_next_day(date("Y-m-d",(time(NULL)+86400)) );
        $old_list=$this->t_lesson_info->get_1v1_lesson_list_by_teacher($teacherid,$start_time,$end_time);


        global $cur_key_index;
        $check_init_map_item=function (&$item,$key,$key_class,$value="") {
            global $cur_key_index;
            if (!isset($item [$key]) ) {
                $item[$key] = [
                    "value" => $value,
                    "key_class" => $key_class."-".$cur_key_index,
                    "sub_list"=>[] ,
                    "data" => array(),
                ];
                $cur_key_index++;
            }
        };
        $add_data=function ( &$item, $add_item ) {
            $arr=&$item["data"];
            foreach  ($add_item as $k => $v ) {
                if ( !is_int($k) &&  ($k=="price" || $k=="lesson_count") ) {
                    if (!isset($arr[$k]))  {
                        $arr[$k]=0;
                    }
                    $arr[$k]+=$v;
                }
            }

        };

        $data_map=[]; //studentid -> lesson_count_level -> row
        $check_init_map_item($data_map,"","");
        foreach ($old_list as $row_id=> &$item) {

            $already_lesson_count=$item["already_lesson_count"];
            $lesson_count_level=\App\Config\teacher_price::get_lesson_count_level($already_lesson_count);
            $studentid=$item["userid"];
            //teacher level
            $level=$item["level"];
            $grade=$item["grade"];

            $item["price"] =  \App\Config\teacher_price::get_price($level,$grade,$lesson_count_level);
            $key0_map=&$data_map[""];
            $check_init_map_item($key0_map["sub_list"] , $studentid,"key1" );
            $add_data($key0_map, $item );

            $key1_map=&$key0_map["sub_list"][$studentid];
            $check_init_map_item($key1_map["sub_list"] , $lesson_count_level,"key2" );
            $add_data($key1_map, $item );

            $key2_map=&$key1_map["sub_list"][$lesson_count_level];
            $check_init_map_item($key2_map["sub_list"] ,$row_id,"key3" );
            $add_data($key2_map, $item );

            $key3_map=&$key2_map["sub_list"][$row_id];
            $key3_map["data"]=$item;


        }

        //to_list
        $list=[];

        foreach ($data_map as  $studentid=> $item0 ) {
            $data=$item0["data"];
            $data["key1"]="全部";
            $data["key2"]="";
            $data["key3"]="";
            $data["key1_class"]="";
            $data["key2_class"]="";
            $data["key3_class"]="";
            $data["level"]="l-0";
            $list[]=$data;

            foreach ( $item0["sub_list"] as $key1=> $item1  ) { // student
                $data=$item1["data"];
                $data["key1"]=$key1;
                $data["key2"]="";
                $data["key3"]="";
                $data["key1_class"]=$item1["key_class"];
                $data["key2_class"]="";
                $data["key3_class"]="";
                $data["level"]="l-1";

                $list[]=$data;

                foreach ( $item1["sub_list"] as $key2=> $item2  ) { //lesson_count_level
                    $data=$item2["data"];
                    $data["key1"]=$key1;
                    $data["key2"]=$key2;
                    $data["key3"]="";
                    $data["key1_class"]=$item1["key_class"];
                    $data["key2_class"]=$item2["key_class"];
                    $data["key3_class"]="";
                    $data["level"]="l-2";

                    $list[]=$data;
                    foreach ( $item2["sub_list"] as $key3=> $item3  ) {
                        $data=$item3["data"];
                    $data["key1"]=$key1;
                    $data["key2"]=$key2;
                    $data["key3"]=$key3;
                    $data["key1_class"]=$item1["key_class"];
                    $data["key2_class"]=$item2["key_class"];
                    $data["key3_class"]=$item3["key_class"];
                    $data["level"]="l-3";

                        $list[]=$data;
                    }
                }
            }
        }
        $ret_list=\App\Helper\Utils::list_to_page_info($list);

        return $this->Pageview(__METHOD__,$ret_list );
    }


    public function cc(){
        $item = '1234';
        // $t = '{
        //                     "time":'.$item.',
        //                     "can_edit":0
        //                 }';

        // $ret_info = [
        //     '{
        //                     "time":'.$item.',
        //                     "can_edit":1
        //                 }'
        // ];

        // $cc =[
        //     "time"=>$item,
        //     "can_edit" =>1
        // ];
        // $cc_str = json_encode($cc);
        // $ret_info = [
        //     $cc_str

        $t = [
            "time"=>$item,
            "can_edit"=>1
        ];
        $ret_info = [];
        array_push($ret_info,$t);


        // array_push($ret_info,$t);
        // $ret_info[]='{ "time":["2016-08-23 21:00","21:59"], "can_edit":1 }';
        dd($ret_info);
        $today_time = strtotime(date('Y-m-d',time(NULL)));

        dd($today_time);
        $day = date('G',time());
        dd($day);
    }
    public function p_list() {
        $page_info= $this->get_in_page_info();
        $nick_phone= $this->get_in_str_val("nick_phone");
        $account_role= $this->get_in_el_account_role();
        $ret_info=$this->t_manager_info->get_list_test($page_info,$nick_phone);
        return $this->pageView( __METHOD__,$ret_info);

    }
    public function ff() {
        dd($_SERVER);
    }


}
