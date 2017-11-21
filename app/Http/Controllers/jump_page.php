<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
class jump_page extends Controller
{

    var $check_login_flag=false;

    function index()  {
        $url=$this->get_in_str_val("url");

        $wx= new \App\Helper\Wx( \App\Helper\Config::get_teacher_wx_appid()  , \App\Helper\Config::get_teacher_wx_appsecret()  );
        $redirect_uri="http://wx-teacher.leo1v1.com/jump_page/jump?url=$url";
        $code = $wx->goto_wx_login( $redirect_uri );
    }

    public function jump(){
        $url  = $this->get_in_str_val("url");
        $code = $this->get_in_str_val("code");

        \App\Helper\Utils::logger(" jump_page_tea: $url");

        $type = $this->get_in_int_val('type',-1);

        $wx= new \App\Helper\Wx( \App\Helper\Config::get_teacher_wx_appid()  , \App\Helper\Config::get_teacher_wx_appsecret()  );
        $token_info = $wx->get_token_from_code($code);
        $openid     = @$token_info["openid"];

        \App\Helper\Utils::logger("jumop77:".$code."openid88".$openid);

        if ($url=="login.html") {
            header("Location: http://wx-teacher-web.leo1v1.com/login.html?wx_openid=".$openid);
        }else{
            header("Location: http://wx-teacher-web.leo1v1.com/$url?type=$type");
        }



    }

}