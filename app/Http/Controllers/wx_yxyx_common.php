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
        if(!$openid) {
            dd("请关闭 重进");
            exit;
        }
        session(["wx_yxyx_openid" => $openid]);

        \App\Helper\Utils::logger("HOST:".$_SERVER["HTTP_HOST"] );
        \App\Helper\Utils::logger("new_openid:$openid");
        \App\Helper\Utils::logger("sessionid:".session_id());

        $goto_url     = urldecode(hex2bin($this->get_in_str_val("goto_url")));
        $goto_url_arr = preg_split("/\//", $goto_url);
        $action       = @$goto_url_arr[2];
        $web_html_url = "http://wx-yxyx-web.leo1v1.com";
        if($action=="bind"){
            $url="$web_html_url/index.html#bind";
        }else{
            $agent_info = $this->t_agent->get_agent_info_by_openid($openid);
            $id = $agent_info['id'];
            if($id){
                session([
                    "login_user_role" => 10,
                    "agent_id"    => $id,
                ]);
                $url = "/wx_yxyx_web/$action";
            }else{
                $url = "$web_html_url/index.html#bind?".$action;
            }
        }
        \App\Helper\Utils::logger("JUMP URL:$url");
        header("Location: $url");
        return "succ";
    }

    public function logout(){
        session([
            "agent_id" => 0,
            "login_user_role" =>0,
        ]);
        return $this->output_succ('注销成功!');
    }

    public function send_phone_code() {
        $phone = trim($this->get_in_str_val('phone'));
        $wx_openid  = session("wx_yxyx_openid");
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }else{
            $openid = '';
            // $agent_info = $this->t_agent->get_agent_info_by_openid($wx_openid);
            $agent_info = $this->t_agent->get_agent_info_by_phone($phone);
            if(isset($agent_info['wx_openid'])){
                $openid = $agent_info['wx_openid'];
            }
            if($openid){
                $id = $agent_info['id'];
                return $this->output_err("您已绑定过！");
            }
        }

        $msg_num= \App\Helper\Common::redis_set_json_date_add("WX_P_PHONE_$phone",1000000);
        $code = rand(1000,9999);
        $ret=\App\Helper\Utils::sms_common($phone, 10671029,[
            "code" => $code,
            "index" => $msg_num
        ] );

        session([
            'wx_yxyx_code'=>$code,
            'wx_yxyx_phone'=>$phone
        ]);

        return $this->output_succ(["msg_num" =>$msg_num,"code" => $code ]);
    }

    public function bind(){
        $phone      = $this->get_in_str_val("phone");
        $code       = $this->get_in_str_val("code");
        $check_code = session("wx_yxyx_code");
        $wx_openid  = session("wx_yxyx_openid");
        \App\Helper\Utils::logger("bind_openid:".session('wx_yxyx_openid'));
        \App\Helper\Utils::logger("bind_sessionid:".session_id());
        \App\Helper\Utils::logger("bind_phone:".$phone);
        \App\Helper\Utils::logger("bind_code:".$code);
        \App\Helper\Utils::logger("bind_check_code:".$check_code);
        if(!$wx_openid){
            return $this->output_err('微信绑定失败!请重新登录后绑定!"');
        }
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }

        if($code==$check_code){
            $agent_info = [];
            $agent_info = $this->t_agent->get_agent_info_by_phone($phone);
            if(isset($agent_info['id'])){
                $id = $this->t_agent->update_field_list('t_agent',['wx_openid'=>$wx_openid],'id',$agent_info['id']);
            }else{
                $id = $this->t_agent->add_agent_row_new($phone,$wx_openid);
            }
            if(!$id){
                return $this->output_err("生成失败！请退出重试！");
            }

            session(["agent_id"=>$id]);
            session(["login_user_role"=>10]);
            \App\Helper\Utils::logger("bind_openid_add:$wx_openid,bind_phone_add:$phone,bind_id_add:".$id);
            return $this->output_succ("绑定成功!");
        }else{
            return $this->output_err ("验证码错误");
        }
    }

    public function agent_add(){
        $p_phone = $this->get_in_str_val('p_phone');
        $phone   = $this->get_in_str_val('phone');
        if(!preg_match("/^1\d{10}$/",$p_phone) or !preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }
        if($p_phone == $phone){
            return $this->output_err("不能邀请自己!");
        }
        $phone_str = implode(',',[$phone,$p_phone]);
        $ret_list = $this->t_agent->get_id_by_phone($phone_str);
        foreach($ret_list as $item){
            if($phone == $item['phone']){
                return $this->output_err("您已被邀请过!");
            }
            if($p_phone = $item['phone']){
                $parentid = $item['id'];
            }
        }
        if(!isset($parentid)){
            $parentid = 0;
        }
        $userid = $this->t_student_info->get_userid_by_phone($phone);
        $ret = $this->t_agent->add_agent_row($parentid,$phone,$userid);
        if($ret){
            return $this->output_succ("邀请成功!");
        }else{
            return $this->output_err("数据请求异常!");
        }
    }
}