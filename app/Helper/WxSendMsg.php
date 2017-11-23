<?php
namespace App\Helper;

class WxSendMsg{
    var  $appid="";
    var  $appsecret="";

    public function __construct($appid='',$appsecret='') {
        if($appid==''){
            $this->appid     = \App\Helper\Config::get_wx_appid();
            $this->appsecret = \App\Helper\Config::get_wx_appsecret();
        }else{
            $this->appid     = $appid;
            $this->appsecret = $appsecret;
        }
    }

    function get_wx_token($appid,$appsecret,$reset_flag=false) {
        \App\Helper\Utils::logger("XX :$appid,$appsecret, ");

        $key     = "wx_token_$appid";
        $ret_arr = \App\Helper\Common::redis_get_json($key);
        $now     = time(NULL);
        \App\Helper\Utils::logger('gettoken1');

        if (!$ret_arr || !isset($ret_arr["access_token"])  ||   $ret_arr["get_time"]+7000 <  $now  || $reset_flag ) {
            \App\Helper\Utils::logger('gettoken2');

            $json_data=file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret"  );
            $ret_arr=\App\Helper\Utils::json_decode_as_array($json_data);
            $ret_arr["get_time"]=time(NULL);
            \App\Helper\Common::redis_set_json($key,$ret_arr );
        }
        \App\Helper\Utils::logger('gettoken4:' .json_encode( $ret_arr) );

        return @$ret_arr["access_token"];
    }

};
