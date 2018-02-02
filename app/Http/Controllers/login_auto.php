<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use Illuminate\Support\Facades\Redis ;


use Illuminate\Support\Facades\Session;

class login_auto extends Controller
{
    var $check_login_flag = false;

    /**
     * 老师pc自动登录接口
     */
    public function teacher(){
        $teacherid   = $this->get_in_teacherid();
        $login_token = $this->get_in_str_val("login_token");
        $login_token = hex2bin($login_token);
        $check_key   = \App\Helper\Config::get_config("login_auto_key");
        $json_str    = \App\Helper\Common::decrypt($login_token,$check_key);
        $token_data  = \App\Helper\Utils::json_decode_as_array($json_str);
        $goto_url   =  @hex2bin( $this->get_in_str_val("goto_url"));
        if(!is_array($token_data)){
            return $this->output_err("账号出错，请重试！");
        }

        $gen_time        = $token_data["gen_time"];
        $token_teacherid = $token_data["uid"];
        $md5value        = $token_data["md5"];
        if($token_teacherid != $teacherid){
            return $this->output_err("老师id出错！请重试！");
        }
        $check_str = md5($gen_time);
        if( $check_str != $token_data["md5"] ) {
            return $this->output_err("账号检测出错！请重试！");
        }

        $teacher_info = $this->t_teacher_info->get_teacher_info_all($teacherid);
        // $_SESSION['phone']   = $teacher_info['phone'];
        // $_SESSION['account'] = $teacher_info['phone'];
        // $_SESSION['tid']     = $teacherid;
        // $_SESSION['role']    = E\Erole::V_TEACHER;
        // session($_SESSION);

        $sess['tid']  = $teacherid;
        $sess["acc"]  = $teacherid;
        $sess['nick'] = $teacher_info["nick"] ;
        $sess['face'] = $teacher_info["face"] ;
        $sess['role'] = E\Erole::V_TEACHER;
        session($sess);

        if (!$goto_url) {
            $goto_url="/teacher_info";
        }
        header("Location: $goto_url");
    }
}