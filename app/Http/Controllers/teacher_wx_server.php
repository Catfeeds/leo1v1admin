<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;

use LaneWeChat\Core\AccessToken;

use LaneWeChat\Core\ResponsePassive;

use Illuminate\Http\Request;

use LaneWeChat\Core\WeChatOAuth;

// use LaneWeChat\Core\UserManage;
use Teacher\Core\UserManagre;

use LaneWeChat\Core\TemplateMessage;

include(app_path("Wx/Teacher/lanewechat_teacher.php"));


class  teacher_wx_server extends Controller
{
    var $check_login_flag =false;//是否需要验证
    public function index() {
        $wechat = new \App\Wx\Teacher\wechat (WECHAT_TOKEN_TEC, TRUE);
        // $r = $wechat->checkSignature();


        $ret=$wechat->run();
        if (is_bool($ret)) {
            return "";
        }else{
            return $ret;
        }
    }

    public function sync_menu() {
        $menuList = array(
            array('id'=>'1', 'pid'=>'0', 'name'=>'讲师报名', 'type'=>'', 'code'=>''),
            array('id'=>'2', 'pid'=>'0', 'name'=>'个人中心', 'type'=>'', 'code'=>''),
            array('id'=>'3', 'pid'=>'0', 'name'=>'帮助中心', 'type'=>'', 'code'=>''),

            array('id'=>'4', 'pid'=>'1', 'name'=>'关于理优', 'type'=>'view', 'code'=>'http://admin.yb1v1.com/article_wx/leo_teacher_about_me'),
            array('id'=>'6', 'pid'=>'1', 'name'=>'面试流程', 'type'=>'view', 'code'=>'http://admin.yb1v1.com/article_wx/leo_teacher_interview'),

            array('id'=>'17', 'pid'=>'1', 'name'=>'代理须知', 'type'=>'view', 'code'=>'http://admin.yb1v1.com/article_wx/leo_teacher_agent'),

            array('id'=>'18', 'pid'=>'1', 'name'=>'招师大奖', 'type'=>'view', 'code'=>'http://admin.yb1v1.com/article_wx/leo_teacher_recruit'),

            array('id'=>'5', 'pid'=>'1', 'name'=>'立即报名', 'type'=>'view', 'code'=>'http://wx-teacher.leo1v1.com/wx_teacher_web/tea?reference'),

            array('id'=>'7', 'pid'=>'2', 'name'=>'荣誉榜', 'type'=>'view', 'code'=>'http://wx-teacher.leo1v1.com/wx_teacher_web/honor_rank'),
            array('id'=>'8', 'pid'=>'2', 'name'=>'上课时间', 'type'=>'view', 'code'=>"http://wx-teacher.leo1v1.com/wx_teacher_web/course_arrange"),
            array('id'=>'9', 'pid'=>'2', 'name'=>'课程评价', 'type'=>'view', 'code'=>'http://wx-teacher.leo1v1.com/wx_teacher_web/comment_list'),
            array('id'=>'10', 'pid'=>'2', 'name'=>'我的收入', 'type'=>'view', 'code'=>'http://wx-teacher.leo1v1.com/wx_teacher_web/wage_summary'),

            array('id'=>'11', 'pid'=>'3', 'name'=>'使用手册', 'type'=>'click', 'code'=>'manual'),
            array('id'=>'12', 'pid'=>'3', 'name'=>'优秀视频', 'type'=>'click', 'code'=>'video'),
            array('id'=>'13', 'pid'=>'3', 'name'=>'我要投诉', 'type'=>'view', 'code'=>'http://wx-teacher.leo1v1.com/wx_teacher_web/complaint'),
            array('id'=>'16', 'pid'=>'2', 'name'=>'邀请有奖', 'type'=>'click', 'code'=>'invitation' ),

            array('id'=>'15', 'pid'=>'3', 'name'=>'常见问题', 'type'=>'view', 'code'=>'http://www.jiuqitian.com/help3'),
        );


        $ret =  \Teacher\Core\Menu::setMenu($menuList);
        $result =  \Teacher\Core\Menu::getMenu($menuList);
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


    public function get_fan_list(){
        // getFansList
        \App\Teacher\Core\UserManagre
        // usermanage.php
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
