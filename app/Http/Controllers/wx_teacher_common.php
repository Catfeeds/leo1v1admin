<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;
use Illuminate\Support\Facades\Redis ;
use Illuminate\Support\Facades\Session ;

class wx_teacher_common extends Controller
{
    var $check_login_flag=false;

    public function wx_jump_page () {


        $code       = $this->get_in_str_val("code");
        /**  @var  \App\Helper\Wx  $wx */
        $wx_config=\App\Helper\Config::get_config("teacher_wx");
        $wx= new \App\Helper\Wx( $wx_config["appid"] , $wx_config["appsecret"] );

        global $_SERVER;
        $token_info = $wx->get_token_from_code($code);
        $openid     = @$token_info["openid"];
        if (!$openid) {
            dd( "请关闭 重进");
            exit;
        }
        session(["wx_parent_openid" => $openid ] );

        $goto_url     = urldecode(hex2bin($this->get_in_str_val("goto_url")));
        $goto_url_arr=preg_split("/\//", $goto_url);
        $action=@$goto_url_arr[2];

        $web_html_url="http://wx-teacher-web.leo1v1.com";
        if ($action=="binding" ){
            $url="$web_html_url/login.html?goto_url=/&wx_openid=".$openid;
        }else{
            $teacherid   = $this->t_teacher_info->get_teacherid_by_openid($openid);
            $wx_use_flag = $this->t_teacher_info->get_wx_use_flag($teacherid);
            if ($teacherid) {
                session([
                    "login_user_role" => 2,
                    "login_userid" => $teacherid,
                    "teacher_wx_use_flag" =>  $wx_use_flag,
                ]);
                $url="/wx_teacher_web/$action";
            }else{
                $url="$web_html_url/login.html?goto_url=/$action&wx_openid=".$openid;
            }
        }
        \App\Helper\Utils::logger("JUMP URL:$url");

        header("Location: $url");
        return "succ";
    }


    public function logout() {
        session([
            "login_userid" => 0,
            "login_user_role" =>0,
        ]);
        return $this->output_succ();
    }


}