<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use Illuminate\Support\Facades\Mail ;
use LaneWeChat\Core\AccessToken;
use LaneWeChat\Core\ResponsePassive;
use Illuminate\Http\Request;
use LaneWeChat\Core\WeChatOAuth;
use Squirrel\Core\UserManage;
use LaneWeChat\Core\TemplateMessage;

include(app_path("Wx/Squirrel/lanewechat_squirrel.php"));

class  squirrel_wx_server extends Controller
{
    var $check_login_flag =false;//是否需要验证
    public function index() {
        $wechat = new \App\Wx\Squirrel\wechat(WECHAT_TOKEN_SQU, TRUE);
        // $r = $wechat->checkSignature();


        $ret = $wechat->run();
        if (is_bool($ret)) {
            return "";
        }else{
            return $ret;
        }
    }

    public function sync_menu() {
        $menuList = array(
            array('id'=>'1', 'pid'=>'0', 'name'=>'我们是谁', 'type'=>'', 'code'=>''),
            array('id'=>'2', 'pid'=>'0', 'name'=>'课前须知', 'type'=>'', 'code'=>''),
            array('id'=>'3', 'pid'=>'0', 'name'=>'学员中心', 'type'=>'', 'code'=>''),
            array('id'=>'4', 'pid'=>'1', 'name'=>'免费试听课', 'type'=>'view', 'code'=>''),
            array('id'=>'6', 'pid'=>'1', 'name'=>'我们的特色', 'type'=>'view', 'code'=>''),
            array('id'=>'17', 'pid'=>'1', 'name'=>'关于我们', 'type'=>'view', 'code'=>''),
            array('id'=>'7', 'pid'=>'2', 'name'=>'我的邀请券', 'type'=>'view', 'code'=>''),
            array('id'=>'8', 'pid'=>'2', 'name'=>'在线客服', 'type'=>'view', 'code'=>""),
            array('id'=>'9', 'pid'=>'2', 'name'=>'下载教程', 'type'=>'view', 'code'=>''),
            array('id'=>'11', 'pid'=>'3', 'name'=>'积分商城', 'type'=>'click', 'code'=>'manual'),
            array('id'=>'12', 'pid'=>'3', 'name'=>'我的课程', 'type'=>'click', 'code'=>'video'),
            array('id'=>'13', 'pid'=>'3', 'name'=>'我的条款', 'type'=>'view', 'code'=>''),
            array('id'=>'16', 'pid'=>'3', 'name'=>'热门活动', 'type'=>'click', 'code'=>'invitation' ),
        );

        $menu =  new \Squirrel\Core\Menu();
        $ret = $menu::setMenu($menuList);
        $result =  $menu::getMenu($menuList);


        // $ret =  \Squirrel\Core\Menu::setMenu($menuList);
        // $result =  \Squirrel\Core\Menu::getMenu($menuList);
        dd($result);
    }

    public function wx_send_phone_code(){
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

    public function get_fan_list(){
        $user = new \Teacher\Core\UserManage();
        $ret = $user::getFansList();
        dd($ret);
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


    public function send_img_by_fansList_once_week () {
    }

}
