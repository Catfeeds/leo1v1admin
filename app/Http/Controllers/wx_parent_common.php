<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;
use Illuminate\Support\Facades\Redis ;
use Illuminate\Support\Facades\Session ;

class wx_parent_common extends Controller
{
    var $check_login_flag=false;

    public function wx_parent_jump_page () {

        $code       = $this->get_in_str_val("code");
        /**  @var  wx \App\Helper\Wx */
        $wx= new \App\Helper\Wx( );
        global $_SERVER;
        $token_info = $wx->get_token_from_code($code);
        $openid     = @$token_info["openid"];
        if (!$openid) {
            dd( "请关闭 重进");
            exit;
        }
        session(["wx_parent_openid" => $openid ] );

        \App\Helper\Utils::logger("HOST:" . $_SERVER["HTTP_HOST"] );
        \App\Helper\Utils::logger("wx_parent_openid:$openid ");
        \App\Helper\Utils::logger("wx_parent_openid:".session("wx_parent_openid"));

        $goto_url     = urldecode(hex2bin($this->get_in_str_val("goto_url")));
        $goto_url_arr=preg_split("/\//", $goto_url);
        $action=@$goto_url_arr[2];
        $web_html_url="http://wx-parent-web.leo1v1.com";
        if ($action=="binding" ){
            $url="$web_html_url/binding?goto_url=";
        }else{
            $parentid= $this->t_parent_info->get_parentid_by_wx_openid($openid);
            if ($parentid) {
                session([
                    "parentid" => $parentid,
                ]);
                $url="$web_html_url/$action";
            }else{
                $url="$web_html_url/binding?goto_url=/$action";
            }
        }
        \App\Helper\Utils::logger("JUMP URL:$url");

        header("Location: $url");
        return "succ";
    }

    public function wx_send_phone_code () {

        $phone = trim($this->get_in_str_val('phone'));
        $market_activity_type  = $this->get_in_int_val('type',-1); // 区分是否是市场的活动

        if (session("")) {
        }


        if ( strlen($phone) != 11) {
            return $this->output_err("电话号码出错");
        }
        $parentid=$this->t_phone_to_user->get_userid_by_phone($phone,E\Erole::V_PARENT );

        if(!$parentid && ($market_activity_type<0)) {
            return $this->output_err("你的孩子还没有注册理优1对1,不能绑定!");
        }

        $msg_num= \App\Helper\Common::redis_set_json_date_add("WX_P_PHONE_$phone",1000000);
        $code = rand(1000,9999);
        $ret=\App\Helper\Utils::sms_common($phone, 10671029,[
            "code" => $code,
            "index" => $msg_num
        ] );

        session([
            'wx_parent_code'=>$code,
            'wx_parent_phone'=>$phone,
            'market_activity_type' => $market_activity_type
        ]);

        return $this->output_succ(["msg_num" =>$msg_num,"code" => $code ]);

    }
    public function do_wx_bind( ){
        $code      = $this->get_in_str_val('code');
        $wx_openid = session("wx_parent_openid");

        \App\Helper\Utils::logger("HOST:" . $_SERVER["HTTP_HOST"] );
        \App\Helper\Utils::logger("do_wx_bind: wx_parent_openid: $wx_openid " );
        if (!$wx_openid){
            return $this->output_err("请重新绑定");
        }

        $phone = session("wx_parent_phone");
        if (!$phone){
            return $this->output_err("请重新绑定");
        }

        $parentid = $this->t_phone_to_user->get_userid_by_phone($phone,E\Erole::V_PARENT );
        if(!$parentid) {
            return $this->output_err("你的孩子还没有注册理优1对1,不能绑定!");
        }

        $db_parentid = $this->t_parent_info->get_parentid_by_wx_openid($wx_openid );
        if ($db_parentid) {
            $this->t_parent_info->field_update_list($db_parentid,[
                "wx_openid" => NULL,
            ]);
        }

        $this->t_parent_info->field_update_list($parentid,[
            "wx_openid" => $wx_openid,
        ]);
        session(["parentid" => $parentid ]);

        return $this->output_succ();
    }

    public function get_lesson_evaluate () {
        $lessonid     = $this->get_in_lessonid();
        $userid       = $this->get_in_userid();
        $label_origin = 1;
        $item = $this->t_teacher_label->get_info_by_lessonid($lessonid,$userid,$label_origin);
        if ($item)  {
            return $this->output_succ($item);
        }else{
            return $this->output_err("没有数据");
        }

    }


    public function check_parent_info(){

    }

    public function get_user_info_for_market(){
        /**
           获取code
           https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect
           define("WECHAT_APPID", 'wx636f1058abca1bc1'); //理优公众号
           define("WECHAT_APPSECRET",'756ca8483d61fa9582d9cdedf202e73e');//理优

        ***/
        $parent_appid = "wx636f1058abca1bc1";
        $url = "http://admin.yb1v1.com/wx_parent_common/check_parent_info";

        $redirect_url = urlencode($url);

        $url = " https://open.weixin.qq.com/connect/oauth2/authorize?appid=$parent_appid&redirect_uri=$redirect_url&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";


        dd($url);
    }


    public function logout() {
        session([
            "parentid" => 0,
            "wx_parent_openid" =>"",
            "wx_parent_phone" => "" ,
        ]);
        return $this->output_succ();
    }

}