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

include(app_path("Wx/Teacher_test/lanewechat_teacher_test.php"));


class  wx_test_tea extends Controller
{
    var $check_login_flag =false;//是否需要验证
    public function index() {
        \App\Helper\Utils::logger("wx_test_tea1");

        $wechat = new \App\Wx\Teacher_test\wechat (WECHAT_TOKEN_TEC_TEST, TRUE);

        $r = $wechat->checkSignature();
        \App\Helper\Utils::logger("wx_test_tea_return $r");

        // $ret=$wechat->run();
        // if (is_bool($ret)) {
        //     return "";
        // }else{
        //     return $ret;
        // }
    }

    public function sync_menu() {
        \App\Helper\Utils::logger('caidan1');

        $menuList = array(
            array('id'=>'1', 'pid'=>'0', 'name'=>'排课评价', 'type'=>'', 'code'=>''),
            array('id'=>'2', 'pid'=>'0', 'name'=>'查工资', 'type'=>'', 'code'=>''),
            array('id'=>'3', 'pid'=>'0', 'name'=>'帮助中心', 'type'=>'', 'code'=>''),
            array('id'=>'4', 'pid'=>'1', 'name'=>'设置上课时间', 'type'=>'view', 'code'=>'http://wx-teacher.leo1v1.com/jump_page?url=course_arrange.html'),
            array('id'=>'5', 'pid'=>'1', 'name'=>'试听课评价', 'type'=>'view', 'code'=>'http://wx-teacher.leo1v1.com/jump_page?url=comment_list.html?type=0'),
            array('id'=>'6', 'pid'=>'1', 'name'=>'常规课评价', 'type'=>'view', 'code'=>'http://wx-teacher.leo1v1.com/jump_page?url=comment_list.html?type=1'),
            array('id'=>'7', 'pid'=>'2', 'name'=>'荣誉棒', 'type'=>'view', 'code'=>'http://wx-teacher.leo1v1.com/jump_page?url=honor_rank.html'),
            array('id'=>'8', 'pid'=>'2', 'name'=>'老师升级制度', 'type'=>'view', 'code'=>'http://wx-teacher.leo1v1.com/jump_page?url=wage_upgrade_rule.html'),
            array('id'=>'9', 'pid'=>'2', 'name'=>'薪资奖励规则', 'type'=>'view', 'code'=>'http://wx-teacher.leo1v1.com/jump_page?url=wage_award_rule.html'),
            array('id'=>'10', 'pid'=>'2', 'name'=>'工资汇总情况', 'type'=>'view', 'code'=>'http://wx-teacher.leo1v1.com/jump_page?url=wage_summary.html'),


            array('id'=>'11', 'pid'=>'3', 'name'=>'使用手册', 'type'=>'click', 'code'=>'manual'),
            array('id'=>'12', 'pid'=>'3', 'name'=>'优秀视频', 'type'=>'click', 'code'=>'video'),
            array('id'=>'13', 'pid'=>'3', 'name'=>'建议反馈', 'type'=>'view', 'code'=>'https://wj.qq.com/s/1193406/9d57'),
            array('id'=>'14', 'pid'=>'3', 'name'=>'推荐好友', 'type'=>'click', 'code'=>'friends'),
            array('id'=>'15', 'pid'=>'3', 'name'=>'常见问题', 'type'=>'click', 'code'=>'question'),






            // array('id'=>'7', 'pid'=>'1', 'name'=>'分享微信二维码', 'type'=>'view', 'code'=>'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx636f1058abca1bc1&redirect_uri=http%3A%2F%2Fwx-parent.leo1v1.com%2Farticle_wx%2Fget_openid&response_type=code&scope=snsapi_base&state=1#wechat_redirect' ),
        );

        // $w = new \App\Wx\Teacher\wechat(WECHAT_TOKEN_TEC, TRUE);

        // $result = \LaneWeChat\Core\Menu::setMenu($menuList);

        $result =  \Teacher\Core\Menu::setMenu($menuList);
        $result =  \Teacher\Core\Menu::getMenu($menuList);
        dd($result);
        // $result =  \App\Wx\Teacher\Core\Menu::setMenu($menuList);
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

}
