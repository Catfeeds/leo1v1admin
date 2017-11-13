<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;
use Illuminate\Support\Facades\Redis ;


class wx_token extends Controller
{
    var $appid            = "wx636f1058abca1bc1";
    var $appsecret        = "756ca8483d61fa9582d9cdedf202e73e";
    var $check_login_flag = false;

    public function get_token() {
        $appid     = $this->appid;
        $appsecret = $this->appsecret;

        $json_data=file_get_contents( "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret"  );
        $ret_arr=\App\Helper\Utils::json_decode_as_array($json_data);
        return $ret_arr["access_token"] ;
    }

    public function get_token_from_code($code) {
        $appid=$this->appid;
        $appsecret=$this->appsecret;
        //https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code
        $json_data=file_get_contents( "https://api.weixin.qq.com/sns/oauth2/access_token?grant_type=authorization_code&code=$code&appid=$appid&secret=$appsecret"  );
        $ret_arr=\App\Helper\Utils::json_decode_as_array($json_data);

        return $ret_arr;
    }
    public function get_user_info_from_token($openid,$token) {
        $appid=$this->appid;
        $appsecret=$this->appsecret;

        $json_data=file_get_contents( "https://api.weixin.qq.com/sns/userinfo?appid=$appid&secret=$appsecret&access_token=$token&openid=$openid"  );
        $ret_arr=\App\Helper\Utils::json_decode_as_array($json_data);
        if (is_array($ret_arr)) {
            $this->t_wx_user_info->add_or_udpate($ret_arr); 
        }
        return $ret_arr;
        //https://api.weixin.qq.com/sns/userinfo?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN
    }

    public function page() {
        global $_GET;
        $code=$_GET["code"];
        $token_info=$this->get_token_from_code($code);
        $code_key="code_$code";
        if (isset( $token_info["access_token"]  )) {
            Redis::set($code_key,json_encode($token_info));
        }else{
            $token_info=\App\Helper\Utils::json_decode_as_array(  Redis::get($code_key));
        }

        $token= $token_info["access_token"] ;
        $openid= $token_info["openid"] ;

        $user_info=$this->get_user_info_from_token($openid,$token);
        //Redis::set("openid_$openid",json_encode($user_info));
        //header("location: http://www.leo1v1.com");
        dd($user_info);
    }

    public function index(){
        global $_GET;
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce     = $_GET["nonce"];

        $token  = "123456";
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
            return $_GET["echostr"];
            exit;
        }else{
            exit;
        }
    }

    public function get_jssdk_config() {
        $url=$this->get_in_str_val("url");
        $jssdk=new \App\Helper\Wxjssdk(  $this->appid, $this->appsecret);
        $signPackage= $jssdk->GetSignPackage($url);
        return $this->output_succ(["signPackage"=>$signPackage]);
    }
    public function get_user_info_by_openid() {
        $openid=$this->get_in_str_val("openid");
        $row=$this->t_wx_user_info->field_get_list($openid,"*");
        return $this->output_succ(["data"=>$row ]);
    }

}