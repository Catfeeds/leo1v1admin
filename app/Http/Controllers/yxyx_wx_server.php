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
        /*
        $r = $wechat->checkSignature();
        exit;
        */


        $ret=$wechat->run();
        if (is_bool($ret)) {
            return "";
        }else{
            return $ret;
        }
    }

    public function sync_menu() {
        $menuList = array(
            array('id'=>'1', 'pid'=>'0', 'name'=>'我要邀请', 'type'=>'', 'code'=>''),
            array('id'=>'2', 'pid'=>'0', 'name'=>'理优教育', 'type'=>'', 'code'=>''),
            array('id'=>'3', 'pid'=>'0', 'name'=>'账号管理', 'type'=>'', 'code'=>''),

            array('id'=>'4', 'pid'=>'1', 'name'=>'我要邀请', 'type'=>'view', 'code'=>'http://wx-yxyx.leo1v1.com/wx_yxyx_web/invite'),
            array('id'=>'7', 'pid'=>'2', 'name'=>'理优教育', 'type'=>'view', 'code'=>'http://www.xmypage.com/model2_28992.html'),

            array('id'=>'13', 'pid'=>'3', 'name'=>'绑定账号', 'type'=>'view', 'code'=>'http://wx-yxyx.leo1v1.com/wx_yxyx_web/bind'),
            array('id'=>'13', 'pid'=>'3', 'name'=>'注销账号', 'type'=>'view', 'code'=>'http://wx-yxyx.leo1v1.com/wx_yxyx_common/logout'),
            array('id'=>'16', 'pid'=>'3', 'name'=>'个人中心', 'type'=>'view', 'code'=>'http://wx-yxyx.leo1v1.com/wx_yxyx_web/index' ),

            array('id'=>'15', 'pid'=>'3', 'name'=>'常见问题', 'type'=>'click', 'code'=>'question'),
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
