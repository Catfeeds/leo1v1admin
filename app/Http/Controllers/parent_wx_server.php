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


include(app_path("Libs/LaneWeChat/lanewechat.php"));


class  parent_wx_server extends Controller
{
    var $check_login_flag =false;
    public function index() {

        // $wechat = new \LaneWeChat\Core\Wechat (WECHAT_TOKEN, TRUE);
        $wechat = new \App\Wx\Parent\wechat (WECHAT_TOKEN, TRUE);
        //$wechat->checkSignature();
        $ret=$wechat->run();
        if (is_bool($ret)) {
            return "";
        }else{
            return $ret;
        }
    }

    public function sync_menu() {
        if (!\App\Helper\Utils::check_env_is_release()  ) {
            dd( " no release!" );
        }
        $menuList = array(
            array('id'=>'1', 'pid'=>'0', 'name'=>'0元试听', 'type'=>'view', 'code'=>'http://www.leo1v1.com/market/index.html?服务号—菜单栏'),

            array('id'=>'2', 'pid'=>'0', 'name'=>'活动专区', 'type'=>'', 'code'=>''),
            array('id'=>'4', 'pid'=>'2', 'name'=>'活动反馈', 'type'=>'click', 'code'=>'activity'),
            array('id'=>'5', 'pid'=>'2', 'name'=>'精品讲座', 'type'=>'view', 'code'=>'https://m.qlchat.com/live/840000124177916.htm'),
            array('id'=>'6', 'pid'=>'2', 'name'=>'精品内容', 'type'=>'click', 'code'=>'content'),

            array('id'=>'3', 'pid'=>'0', 'name'=>'个人中心', 'type'=>'', 'code'=>''),
            array('id'=>'11', 'pid'=>'3', 'name'=>'奖品区', 'type'=>'view', 'code'=>"http://wx-parent.leo1v1.com/wx_parent/prizes"),
            array('id'=>'7', 'pid'=>'3', 'name'=>'使用手册', 'type'=>'click', 'code'=>'manual'),
            array('id'=>'8', 'pid'=>'3', 'name'=>'课程详情', 'type'=>'view', 'code'=>'http://wx-parent.leo1v1.com/wx_parent/index'),
            array('id'=>'10', 'pid'=>'3', 'name'=>'成绩记录', 'type'=>'view', 'code'=>"http://wx-parent.leo1v1.com/wx_parent/scores?_url=/wx_parent/scores"),
            array('id'=>'9', 'pid'=>'3', 'name'=>'我要投诉', 'type'=>'view', 'code'=>'http://wx-parent.leo1v1.com/wx_parent/complain' ),

        );

        // dd(\LaneWeChat\Core\Menu::getMenu($menuList));
        $result = \LaneWeChat\Core\Menu::setMenu($menuList);
        // dd($result);
        dd(\LaneWeChat\Core\Menu::getMenu());

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

    public function wx_bind( Request $request){
        /*
          $wx= new \App\Helper\Wx("","");
          $to_url=bin2hex($this->get_in_str_val("to_url"));
          $redirect_url=urlencode("http://wx-parent.leo1v1.com?=$to_url" );
          $wx->goto_wx_login( $redirect_url );
        */

        /*
          $wx= new \App\Helper\Wx();
          global $_SERVER;
          if (!$code) {
          $to_url=bin2hex($this->get_in_str_val("to_url"));
          $redirect_url=urlencode("http://admin.leo1v1.com?to_url=$to_url" );
          $wx->goto_wx_login( $redirect_url );
        */

    }

}
