<?php
namespace App\Helper;

class Wx{
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

    public function get_wx_login_url($redirect_url) {
        \App\Helper\Utils::logger(" goto_wx_login redirect_url: $redirect_url");
        $appid = $this->appid;
        $no    = rand(1,10000);
        $url   = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_url&response_type=code&no=$no&scope=snsapi_userinfo&state=STATE_$no&connect_redirect=1#wechat_redirect";
        return $url;
    }

    public function goto_wx_login($redirect_url) {
        $url = $this->get_wx_login_url($redirect_url);
        header("location: $url");
        exit;
    }

    public function goto_wx_login_for_openid($redirect_url) {// 教师节测试[静默授权]
        $url=$this->get_wx_login_url_for_openid($redirect_url);
        header("location: $url");
        exit;
    }

    public function get_wx_login_url_for_openid($redirect_url) { //教师节测试[静默授权]
        \App\Helper\Utils::logger(" goto_wx_login redirect_url: $redirect_url");
        $appid = $this->appid;
        $no    = rand(1,10000);
        $url   = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_url&response_type=code&no=$no&scope=snsapi_base&state=STATE_$no&connect_redirect=1#wechat_redirect";
        return $url;
    }

    public function get_token_from_code($code) {
        $appid     = $this->appid;
        $appsecret = $this->appsecret;
        $json_data=file_get_contents( "https://api.weixin.qq.com/sns/oauth2/access_token?grant_type=authorization_code&code=$code&appid=$appid&secret=$appsecret"  );
        $ret_arr=\App\Helper\Utils::json_decode_as_array($json_data);

        return $ret_arr;
    }

    public function get_openid_from_code($code) { // 测试
        $appid     = $this->appid;
        $appsecret = $this->appsecret;
        //https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code
        $json_data=file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?grant_type=authorization_code&code=$code&appid=$appid&secret=$appsecret"  );
        $ret_arr=\App\Helper\Utils::json_decode_as_array($json_data);

        return $ret_arr;
    }

    static public function gen_temp_data( $openid,$template_id,$url,$data, $topcolor="#FF0000" ) {
        $data = [
            "touser"      => $openid,
            "template_id" => $template_id,
            "url"         => $url,
            "topcolor"    => $topcolor,
            "data"        => $data,
        ];
        return \App\Helper\Common::json_encode_zh($data);
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

    function get_new_wx_token($appid,$appsecret,$reset_flag=false) {//强制,及时刷新token
        \App\Helper\Utils::logger("XX :$appid,$appsecret, ");

        $key     = "wx_token_$appid";
        $ret_arr = \App\Helper\Common::redis_get_json($key);
        $now     = time(NULL);
        \App\Helper\Utils::logger('gettoken1');

        \App\Helper\Utils::logger('gettoken2');

        $json_data=file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret"  );
        $ret_arr=\App\Helper\Utils::json_decode_as_array($json_data);
        $ret_arr["get_time"]=time(NULL);
        \App\Helper\Common::redis_set_json($key,$ret_arr );

        \App\Helper\Utils::logger('gettoken4:' .json_encode( $ret_arr) );

        return @$ret_arr["access_token"];
    }

    function wx_get_token($reset_flag=false) {
        $appid     = $this->appid;
        $appsecret = $this->appsecret;
        return $this->get_wx_token($appid,$appsecret,$reset_flag);
    }

    function send_template_msg( $openid, $template_id, $data ,$url="" ) {
        if(!\App\Helper\Utils::check_env_is_release()){
            return false;
        }

        foreach ($data as &$item) {
            if (!is_array($item)) {
                $item = [
                    "value" => $item,
                    "color" => "#173177",
                    // "color" => "#e22870", //test
                ];
            }
        }

        $str = $this->gen_temp_data($openid,$template_id,$url,$data);
        $token = $this->wx_get_token();
        \App\Helper\Utils::logger("token:$token");
        \App\Helper\Utils::logger('xjstr'.$str);

        $qq_url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$token";
        $ret    = \App\Helper\Utils::json_decode_as_array( \App\Helper\Common::http_post_json_str($qq_url, $str ));

        if ($ret["errcode"]===0) {
            \App\Helper\Utils::logger("WX MSG SUCC:" .json_encode($ret) );
            return true;
        }else{
            \App\Helper\Utils::logger("RESET WX MSG ONE ERROR:".json_encode($ret) );
            //send next..
            $token=$this->wx_get_token(true);
            \App\Helper\Utils::logger("2 token:$token");
            $qq_url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$token";
            $ret=\App\Helper\Utils::json_decode_as_array(\App\Helper\Common::http_post_json_str($qq_url, $str ));


            if(@$ret["errcode"]===0){
                \App\Helper\Utils::logger("WX MSG SUCC:".json_encode($ret) );
                return true;
            }else{
                \App\Helper\Utils::logger("WX MSG TWO ERROR:".json_encode($ret) );
                if (\App\Helper\Utils::check_env_is_release() ) {
                    if (!in_array(  $ret["errcode"], [43004  ,40003])) {
                        /*
                        dispatch( new \App\Jobs\send_error_mail(
                            "","KK WX ERR: $template_id ", json_encode($ret)  ));
                        */
                    }
                }
                return false;
            }
        }
    }

    function send_template_msg_color( $openid, $template_id, $data ,$url="", $color='' ) {
        foreach ($data as &$item) {
            if (!is_array($item)) {
                $item = [
                    "value" => $item,
                    // "color" => $color,
                    "color" => "#e22870", //test
                ];
            }
        }

        $str = $this->gen_temp_data($openid,$template_id,$url,$data);
        $token = $this->wx_get_token();
        \App\Helper\Utils::logger("token:$token");
        \App\Helper\Utils::logger('xjstr'.$str);

        $qq_url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$token";
        $ret    = \App\Helper\Utils::json_decode_as_array( \App\Helper\Common::http_post_json_str($qq_url, $str ));

        if ($ret["errcode"]===0) {
            \App\Helper\Utils::logger("WX MSG SUCC:" .json_encode($ret) );
            return true;
        }else{
            \App\Helper\Utils::logger("RESET WX MSG ONE ERROR:".json_encode($ret) );
            //send next..
            $token=$this->wx_get_token(true);
            \App\Helper\Utils::logger("2 token:$token");
            $qq_url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$token";
            $ret=\App\Helper\Utils::json_decode_as_array(\App\Helper\Common::http_post_json_str($qq_url, $str ));


            if(@$ret["errcode"]===0){
                \App\Helper\Utils::logger("WX MSG SUCC:".json_encode($ret) );
                return true;
            }else{
                \App\Helper\Utils::logger("WX MSG TWO ERROR:".json_encode($ret) );
                if (!in_array(  $ret["errcode"], [43004  ,40003])) {
                    dispatch( new \App\Jobs\send_error_mail(
                        "xcwenn@qq.com","WX ERR: $template_id ", json_encode($ret)  ));
                    dispatch( new \App\Jobs\send_error_mail(
                        "wg392567893@163.com","WX ERR: $template_id ", json_encode($ret)  ));
                }
                return false;
            }
        }
    }

    public function get_user_info_from_token($openid,$token) {
        $appid=$this->appid;
        $appsecret=$this->appsecret;

        //https://api.weixin.qq.com/cgi-bin/user/info?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN 

        $cmd="https://api.weixin.qq.com/sns/userinfo?appid=$appid&secret=$appsecret&access_token=$token&openid=$openid";
        $json_data=file_get_contents( $cmd  );

        $ret_arr=\App\Helper\Utils::json_decode_as_array($json_data);
        if (is_array($ret_arr) && isset($ret_arr["openid"])) {
            $t= new  \App\Models\t_wx_user_info();
            $t->add_or_udpate($ret_arr);
        }
        return $ret_arr;
        //https://api.weixin.qq.com/sns/userinfo?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN
    }


    public function get_user_info($openid,$token) {
        $appid=$this->appid;
        $appsecret=$this->appsecret;

        $cmd = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$token&openid=$openid&lang=zh_CN"; 

        // $cmd="https://api.weixin.qq.com/sns/userinfo?appid=$appid&secret=$appsecret&access_token=$token&openid=$openid";
        $json_data=file_get_contents($cmd);

        $ret_arr=\App\Helper\Utils::json_decode_as_array($json_data);
        return $ret_arr;
    }
};
