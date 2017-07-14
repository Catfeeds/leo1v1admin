<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;
use Illuminate\Support\Facades\Redis ;
use Illuminate\Support\Facades\Session ;

class wx_yxyx_common extends Controller
{
    var $check_login_flag=false;

    public function wx_jump_page () {
        $code       = $this->get_in_str_val("code");
        /**  @var  \App\Helper\Wx  $wx */
        $wx_config=\App\Helper\Config::get_config("yxyx_wx");
        $wx= new \App\Helper\Wx( $wx_config["appid"] , $wx_config["appsecret"] );

        global $_SERVER;
        $token_info = $wx->get_token_from_code($code);
        $openid     = @$token_info["openid"];
        if (!$openid) {
            dd( "请关闭 重进");
            exit;
        }
        session(["wx_agent_openid" => $openid ] );

        \App\Helper\Utils::logger("HOST:".$_SERVER["HTTP_HOST"] );
        \App\Helper\Utils::logger("wx_parent_openid:$openid ");
        \App\Helper\Utils::logger("wx_parent_openid:".session("wx_parent_openid"));

        $goto_url     = urldecode(hex2bin($this->get_in_str_val("goto_url")));
        $goto_url_arr = preg_split("/\//", $goto_url);
        $action       = @$goto_url_arr[2];

        $web_html_url = "http://wx-yxyx-web.leo1v1.com";
        if($action=="bind"){
            $url="$web_html_url/#bind?goto_url=/&wx_openid=".$openid;
        }else{
            $agent_info = $this->t_agent->get_agent_info_by_openid($openid);
            $id = $agent_info['id'];
            // $wx_use_flag = $this->t_yxyx_info->get_wx_use_flag($yxyxid);
            if($id){
                session([
                    "login_user_role" => 10,
                    "login_userid"    => $id,
                ]);
                $url = "/wx_yxyx_web/$action";
            }else{
                $url = "$web_html_url/#bind?goto_url=/$action&wx_openid=".$openid;
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


    public function bind(){
        $phone     = $this->get_in_str_val("phone");
        $code      = $this->get_in_str_val("code");
        $phone = '15251318621';
        $code = '5154';
        $wx_openid = session("wx_agent_openid" );
        $check_code = \App\Helper\Common::redis_get("JOIN_USER_PHONE_$phone" );
        \App\Helper\Utils::logger("nange:".$wx_openid);
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }
        if($code==$check_code){
            $agent_info = $this->t_agent->get_agent_info_by_phone($phone);
            if(!isset($agent['id'])){
                $data = $this->t_agent->add_agent_row_new($phone,$wx_openid);
                if(!$data || !is_int($data)){
                    if($data === false){
                        $data="生成失败！请退出重试！";
                    }
                    return $this->output_err($data);
                }
                $agent_info['id'] = $data;
            }
            \App\Helper\Utils::logger("wx_openid189:$wx_openid,phone:$phone,id:".$agent_info['id']);

            if($wx_openid){
                $id = $this->t_agent->get_agent_info_by_openid($wx_openid);
                if($id>0 && $id != $agent_info['id']){
                    $ret = $this->t_agent->field_update_list($id,[
                        "wx_openid" => null,
                    ]);
                }
                $re = $this->t_agent->field_update_list($agent_info['id'], [
                    "wx_openid" => $wx_openid
                ]);
            }else{
                return $this->output_err("微信绑定失败!请重新登录后绑定!");
            }

            session(["login_userid"=>$agent_info['aid']]);
            session(["login_user_role"=>10]);
            return $this->output_succ();
        }else{
            return $this->output_err ("验证码不对");
        }
    }

}