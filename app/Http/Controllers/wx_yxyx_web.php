<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;
use Illuminate\Support\Facades\Redis ;
use Illuminate\Support\Facades\Session ;

class wx_yxyx_web extends Controller
{
    var $check_login_flag=false;
    public function __construct() {
        parent::__construct();
        \App\Helper\Utils::logger("sessionid:".session_id());
        \App\Helper\Utils::logger("web login_user_role:xueji".session("login_user_role"));
        \App\Helper\Utils::logger("web login_userid:".session("login_userid"));
        if(session("login_user_role") ==10 && session("login_userid")){
            $web_html_url="http://wx-yxyx-web.leo1v1.com";

            $to_url=$this->get_in_str_val("_url");
            $got_url_arr=preg_split("/\//", $to_url);
            $action=@$goto_url_arr[2];

            $url="$web_html_url/$action.html";

            header("Location: $url");
        }else{
            $wx_config=\App\Helper\Config::get_config("yxyx_wx");
            $to_url=bin2hex($this->get_in_str_val("_url"));
            $wx= new \App\Helper\Wx( $wx_config["appid"] , $wx_config["appsecret"] );

            $redirect_url=urlencode("http://wx-yxyx.leo1v1.com/wx_yxyx_common/wx_jump_page?goto_url=$to_url" );
            $wx->goto_wx_login( $redirect_url );
        }
    }

    public function bind(){}
    public function invite() {}
    public function index (){}

}
