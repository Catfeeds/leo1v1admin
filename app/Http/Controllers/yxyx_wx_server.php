<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;

use LaneWeChat\Core\AccessToken;

use LaneWeChat\Core\ResponsePassive;

use Illuminate\Http\Request;

use LaneWeChat\Core\WeChatOAuth;

use LaneWeChat\Core\UserManage;

use LaneWeChat\Core\TemplateMessage;

include(app_path("Wx/Yxyx/lanewechat_yxyx.php"));


class  yxyx_wx_server extends Controller
{
    var $check_login_flag =false;//是否需要验证
    public function index() {
        $wechat = new \App\Wx\Yxyx\wechat (WECHAT_TOKEN_YXYX, TRUE);
        if (  false && !\App\Helper\Utils::check_env_is_release() ) {
            $r = $wechat->checkSignature();
            exit;
        }else{
            $ret=$wechat->run();
            if (is_bool($ret)) {
                return "";
            }else{
                return $ret;
            }
        }
    }

    public function sync_menu() {
        $wx_config =\App\Helper\Config::get_config("yxyx_wx") ;
        $base_url= $wx_config["url"] ;

        $menuList = array(
            array('id'=>'1', 'pid'=>'0', 'name'=>'我要邀请', 'type'=>'', 'code'=>''),
            array('id'=>'2', 'pid'=>'0', 'name'=>'理优教育', 'type'=>'', 'code'=>''),
            array('id'=>'3', 'pid'=>'0', 'name'=>'账号管理', 'type'=>'', 'code'=>''),
            array('id'=>'4', 'pid'=>'1', 'name'=>'邀请学员', 'type'=>'click', 'code'=>'invitation'),
            array('id'=>'5', 'pid'=>'1', 'name'=>'邀请会员', 'type'=>'click', 'code'=>'invitation_member'),
            array('id'=>'7', 'pid'=>'2', 'name'=>'理优简介', 'type'=>'view','code'=>'https://mp.weixin.qq.com/s?__biz=MzIyOTg0OTYwMA==&mid=2247483744&idx=1&sn=30368193100437dac60b2819592a899a&pass_ticket=AsSp67zKHcmO1xdBmlLZtjvArjS4d%2FPUll3bP1hzxZvsBCVWXZJMUjx%2BTMnzlUVw'),
            // array('id'=>'8', 'pid'=>'2', 'name'=>'精品内容', 'type'=>'view', 'code'=>'http://www.xmypage.com/model2_28992.html'),
            array('id'=>'8', 'pid'=>'2', 'name'=>'精品内容', 'type'=>'click', 'code'=>'content'),
            // array('id'=>'8', 'pid'=>'2', 'name'=>'精品内容', 'type'=>'view', 'code'=>'http://www.leo1v1.com/wx-invite-article/index.html'),
            array('id'=>'9', 'pid'=>'2', 'name'=>'学员反馈', 'type'=>'click', 'code'=>'feedback'),
            // array('id'=>'10', 'pid'=>'2', 'name'=>'每日卡片', 'type'=>'click', 'code'=>'card'),
            array('id'=>'11', 'pid'=>'2', 'name'=>'预约试听', 'type'=>'view', 'code'=>'http://www.leo1v1.com/market-l/index.html'),
            array('id'=>'15', 'pid'=>'3', 'name'=>'个人中心', 'type'=>'view', 'code'=> "$base_url/wx_yxyx_web/index" ),
            array('id'=>'16', 'pid'=>'3', 'name'=>'绑定账号', 'type'=>'view', 'code'=>"$base_url/wx_yxyx_web/bind"),
            array('id'=>'18', 'pid'=>'3', 'name'=>'常见问题', 'type'=>'click', 'code'=>'question'),
        );

        $ret =  \Yxyx\core\Menu::setMenu($menuList);
        $result =  \Yxyx\core\Menu::getMenu($menuList);
        dd($result);
    }


    public function wx_send_phone_code () {
        $phone = $_GET['phone'];
        $code = rand(1000,9999);
        $ret=\App\Helper\Utils::sms_common($phone, 10671029,[
            "code" => $code,
            "index" => "2"
        ] );

        session([
            'wx_parent_code'=>$code
        ]);
        return $this->output_succ(["index" =>$phone_index ]);
    }


    public function ceshi () {
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx636f1058abca1bc1&redirect_uri=http%3A%2F%2Fwx-parent.leo1v1.com%2Farticle_wx%2Fget_openid&response_type=code&scope=snsapi_base&state=1#wechat_redirect";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        // dd($output);
        // $content = json_encode( $output);
        $content = $output;
        // dd($output);
        var_dump( $output );

    }

}
