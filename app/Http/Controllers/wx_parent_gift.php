<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;

class wx_parent_gift extends Controller
{
    public function test () {
        /**
           获取code
           https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect
           define("WECHAT_APPID", 'wx636f1058abca1bc1'); //理优公众号
           define("WECHAT_APPSECRET",'756ca8483d61fa9582d9cdedf202e73e');//理优

        ***/
        $parent_appid = "wx636f1058abca1bc1";
        $url = "http://admin.yb1v1.com/wx_parent_gift/check_parent_info";

        $redirect_url = urlencode($url);

        $url = " https://open.weixin.qq.com/connect/oauth2/authorize?appid=$parent_appid&redirect_uri=$redirect_url&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";


        dd($url);

    }

    public function check_parent_info(){
        echo 1;
    }








}