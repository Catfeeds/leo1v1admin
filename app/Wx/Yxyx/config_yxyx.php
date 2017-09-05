<?php
namespace LaneWeChat;
/**
 * 系统主配置文件.
 * @Created by Lane.
 * @Author: lane
 * @Mail lixuan868686@163.com
 * @Date: 14-8-1
 * @Time: 下午1:00
 * @Blog: Http://www.lanecn.com
 */
//版本号
// define('LANEWECHAT_VERSION', '1.4');
// define('LANEWECHAT_VERSION_DATE', '2014-11-05');

/*
 * 服务器配置，详情请参考@link http://mp.weixin.qq.com/wiki/index.php?title=接入指南
 */
define("WECHAT_URL_YXYX", 'http://yxyx.leo1v1.com/yxyx_wx_server/');//理优优学优享

define('WECHAT_TOKEN_YXYX', 'yxyx14285714');
// define('ENCODING_AES_KEY_YXYX', "rSOE0Qp7BhrVl4T2GPecyTKrVOKklfR1nulOCW71X5G");//理优优学优享
define('ENCODING_AES_KEY_YXYX', "rFUbMgUBv7wJNrnW1YqlfCaQDMSvAccVZgkMPxASZeP");//理优优学优享
// define('ENCODING_AES_KEY_YXYX', "kP9eZAagRdgfPsYxR0KqMvRaORCYfPmjU6sHiKfiNdA");//微信测试

//8Xc9cpq8pFNgUC7kluftBPTRhrp50bTIxbAdfawLWXD

// /*
//  * 开发者配置
//  */
define("WECHAT_APPID_YXYX", 'wxb4f28794ec117af0'); //理优优学优享公众号
//4a4bc7c543698b8ac499e5c72c22f242
define("WECHAT_APPSECRET_YXYX",'4a4bc7c543698b8ac499e5c72c22f242');//理优优学优享appsecret
//"appid": "wxa99d0de03f407627",
//    "appsecret": "61bbf741a09300f7f2fd0a861803f920",

//61bbf741a09300f7f2fd0a861803f920

// define("WECHAT_APPID_YXYX", 'wx3cdac6efe27e7458'); //测试微信号
// define("WECHAT_APPSECRET_YXYX",'d6da9400e1c9e2feca7427f8da47d3bd');//测试微信号





// /*
//  * SAE平台配置
//  */
define("HTTP_ACCESSKEY_YXYX", '04xmzo3zm5');
define("HTTP_APPNAME_YXYX", 'imcustom4test');

////-----引入系统所需类库-------------------
////引入错误消息类
//include_once 'core/msg.lib.php';
////引入错误码类
//include_once 'core/msgconstant.lib.php';
////引入CURL类
//include_once 'core/curl.lib.php';
//
////-----------引入微信所需的基本类库----------------
////引入微信处理中心类
//include_once 'core/wechat.lib.php';
////引入微信请求处理类
//include_once 'core/wechatrequest.lib.php';
////引入微信被动响应处理类
//include_once 'core/responsepassive.lib.php';
////引入微信access_token类
//include 'core/accesstoken.lib.php';
//
////-----如果是认证服务号，需要引入以下类--------------
////引入微信权限管理类
//include_once 'core/wechatoauth.lib.php';
////引入微信用户/用户组管理类
//include_once 'core/usermanage.lib.php';
////引入微信主动相应处理类
//include_once 'core/responseinitiative.lib.php';
////引入多媒体管理类
//include_once 'core/media.lib.php';
////引入自定义菜单类
//include_once 'core/menu.lib.php';
?>