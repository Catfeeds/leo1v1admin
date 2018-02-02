<?php
namespace App\Wx\Squirrel_tea;
/**
 * 处理请求
 * Created by Lane.
 * User: lane
 * Date: 13-12-19
 * Time: 下午11:04
 * Mail: lixuan868686@163.com
 * Website: http://www.lanecn.com
 */



use LaneWeChat\Core\TemplateMessage;

use LaneWeChat\Core\ResponsePassive;

use Squirrel_tea\Core\WeChatOAuth;
use Squirrel_tea\Core\UserManage;
use Squirrel_tea\Core\Media;
use Squirrel_tea\Core\AccessToken;


class WechatRequest  {
    /**
     * @descrpition 分发请求
     * @param $request
     * @return array|string
     */
    // var $check_login_flag=false;


    public static function switchType(&$request){

        $data = array();
        switch (@$request['msgtype']) {
            //事件
            case 'event':
                $request['event'] = strtolower($request['event']);
                switch ($request['event']) {
                    //关注
                    case 'subscribe':
                        //二维码关注
                        if(isset($request['eventkey']) && isset($request['ticket'])){
                            $data = self::eventQrsceneSubscribe($request);
                        //普通关注
                        }else{
                            $data = self::eventSubscribe($request);
                        }
                        break;
                    //扫描二维码
                    case 'scan':
                        $data = self::eventScan($request);
                        break;
                    //地理位置
                    case 'location':
                        $data = self::eventLocation($request);
                        break;
                    //自定义菜单 - 点击菜单拉取消息时的事件推送
                    case 'click':
                        $data = self::eventClick($request);
                        break;
                    //自定义菜单 - 点击菜单跳转链接时的事件推送
                    case 'view':
                        $data = self::eventView($request);
                        break;
                    //自定义菜单 - 扫码推事件的事件推送
                    case 'scancode_push':
                        $data = self::eventScancodePush($request);
                        break;
                    //自定义菜单 - 扫码推事件且弹出“消息接收中”提示框的事件推送
                    case 'scancode_waitmsg':
                        $data = self::eventScancodeWaitMsg($request);
                        break;
                    //自定义菜单 - 弹出系统拍照发图的事件推送
                    case 'pic_sysphoto':
                        $data = self::eventPicSysPhoto($request);
                        break;
                    //自定义菜单 - 弹出拍照或者相册发图的事件推送
                    case 'pic_photo_or_album':
                        $data = self::eventPicPhotoOrAlbum($request);
                        break;
                    //自定义菜单 - 弹出微信相册发图器的事件推送
                    case 'pic_weixin':
                        $data = self::eventPicWeixin($request);
                        break;
                    //自定义菜单 - 弹出地理位置选择器的事件推送
                    case 'location_select':
                        $data = self::eventLocationSelect($request);
                        break;
                    //取消关注
                    case 'unsubscribe':
                        $data = self::eventUnsubscribe($request);
                        break;
                    //群发接口完成后推送的结果
                    case 'masssendjobfinish':
                        $data = self::eventMassSendJobFinish($request);
                        break;
                    //模板消息完成后推送的结果
                    case 'templatesendjobfinish':
                        $data = self::eventTemplateSendJobFinish($request);
                        break;
                    default:
                        return Msg::returnErrMsg(MsgConstant::ERROR_UNKNOW_TYPE, '收到了未知类型的消息', $request);
                        break;
                }
                break;
            //文本
            case 'text':
                $data = self::text($request);
                break;
            //图像
            case 'image':
                $data = self::image($request);
                break;
            //语音
            case 'voice':
                $data = self::voice($request);
                break;
            //视频
            case 'video':
                $data = self::video($request);
                break;
            //小视频
            case 'shortvideo':
                $data = self::shortvideo($request);
                break;
            //位置
            case 'location':
                $data = self::location($request);
                break;
            //链接
            case 'link':
                $data = self::link($request);
                break;
            default:
                return ResponsePassive::text(@$request['fromusername'], @$request['tousername'], '收到未知的消息，我不知道怎么处理');
                break;
        }
        return $data;
    }


    /**
     * @descrpition 文本
     * @param $request
     * @return array
     */
    public static function text(&$request){

        if($request['content'] == '排名'){
            $tuwenList[] = array(
                'title' => "理优1对1致力于为初高中学生提供专业、专注、有效的教学.",
                'description' => "点击查看活动排名",
                'pic_url' => "http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%8F%8C%E6%97%A6%E8%8A%82%E6%8E%92%E5%90%8D.jpg",
                'url' => "http://wx-parent-web.leo1v1.com/teachris/rank.html?openid=".$request['fromusername'],
            );
            $item = array();
            foreach($tuwenList as $tuwen){
                $item[] = ResponsePassive::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
            }
            return  ResponsePassive::news($request['fromusername'], $request['tousername'], $item);
        }else{
            $content = "
老师您好，如果有什么疑问，您可以点击以下问题分类查看答案。
<a href='http://admin.leo1v1.com/article_wx/leo_teacher_wages'>薪资问题【点击查看】</a>
<a href='http://admin.leo1v1.com/article_wx/leo_teacher_train'>教师培训问题【点击查看】</a>
<a href='http://admin.leo1v1.com/article_wx/leo_teacher_lesson_software_download'>下载／登录问题【点击查看】</a>
<a href='http://admin.leo1v1.com/article_wx/leo_teacher_lesson_before'>课前问题【点击查看】</a>
<a href='http://admin.leo1v1.com/article_wx/leo_teacher_lessoning'>课堂问题【点击查看】</a>
<a href='http://admin.leo1v1.com/article_wx/leo_teacher_lesson_after'>课后问题【点击查看】</a>
<a href='http://admin.leo1v1.com/article_wx/leo_teacher_lesson_equipment'>设备问题【点击查看】</a>
如以上回答还不能解决您的问题，请添加理优问题答疑QQ群（请在入职邮件内查看群号）
";
            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);

        }


    }

    /**
     * @descrpition 图像
     * @param $request
     * @return array
     */
    public static function image(&$request){
        // $userInfo = UserManage::getUserInfo($request['fromusername']);
        // $name = $userInfo['nickname'];


        // // return ResponsePassive::text($request['fromusername'], $request['tousername'], $name);

        // $tuwenList[] = array(

        //     'title' => '[新师培训] 常见问题处理方法-for'.$name,

        //     'description' => '文章描述内容',

        //     'pic_url' => '',


        // );




        // $item = array();
        // foreach($tuwenList as $tuwen){
        //     $item[] = ResponsePassive::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
        // }



        // $fan_list_arr = UserManage::getFansList($next_openId='');
        // $fan_list_json = json_encode($fan_list);

        // if(is_array($fan_list)){
        //     $info = 1;
        // }else{
        //     $info = 0;
        // }

        $openid_user = $request['fromusername'];

        //使用客服接口发送消息
        $txt_arr = [
            'touser'   => $openid_user,
            'msgtype'  => 'news',
            "news"=>[
                "articles"=> [
                    [
                        "title"=>"TEST MSG",
                        "description"=>"Is Really A Happy Day",
                        "url"=>"https://mmbiz.qlogo.cn/mmbiz_jpg/cBWf565lml4NcGMWTiaeuDmWsUQpXz8TPJzfbsoUENe9dKqPKDXPZa7ITPCKvQiaVzmAvLBKPYmrhKNg2AkwwkVQ/0?wx_fmt=jpeg",
                        "picurl"=>"http://admin.leo1v1.com/article_wx/leo_teacher_new_teacher_deal_question"
                    ]
                ]
            ]
        ];


        $txt = self::ch_json_encode($txt_arr);
        $token = AccessToken::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token;
        $txt_ret = self::https_post($url,$txt);


        return  true;


        // \App\Helper\Utils::logger(" fan_list $fan_list_json");


        return  ResponsePassive::text($request['fromusername'], $request['tousername'], '');

        // return  ResponsePassive::news($request['fromusername'], $request['tousername'], $item);


    }

    /**
     * @descrpition 语音
     * @param $request
     * @return array
     */
    public static function voice(&$request){
        if(!isset($request['recognition'])){
            $content = '收到语音';
            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        }else{
            $content = '收到语音识别消息，语音识别结果为：'.$request['recognition'];
            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        }
    }

    /**
     * @descrpition 视频
     * @param $request
     * @return array
     */
    public static function video(&$request){
        $content = '收到视频';
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 视频
     * @param $request
     * @return array
     */
    public static function shortvideo(&$request){
        $content = '收到小视频';
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 地理
     * @param $request
     * @return array
     */
    public static function location(&$request){
        $content = '收到上报的地理位置';
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 链接
     * @param $request
     * @return array
     */
    public static function link(&$request){
        $content = '收到连接';
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 关注
     * @param $request
     * @return array
     */
    public static function eventSubscribe(&$request){

        $openid_user = $request['fromusername'];
        //使用客服接口发送消息
        $txt_arr = [
            'touser'   => $openid_user,
            'msgtype'  => 'text',
            'text'     => [
                'content' =>
self::unicode2utf8('\ue032')."欢迎加入理优1对1老师帮 ".self::unicode2utf8('\ue032')."

【绑定地址】
立刻绑定您上课时使用的账号，即可使用所有功能,
".self::unicode2utf8('\ue23A')."".self::unicode2utf8('\ue23A')." <a href='http://t.cn/RcQGnPX' >立即绑定</a> ".self::unicode2utf8('\ue23b')."".self::unicode2utf8('\ue23B')."

【菜单栏功能介绍】
① [讲师报名]可查看理优1对1简介，报名讲师，面试流程
② [个人中心]设置上课时间，评价学生，我的收入（可查看收入详情和申述），邀请有奖（点击可生成个人专属二维码，分享海报邀请好友报名，领取伯乐奖)".self::unicode2utf8('\ue12f')."".self::unicode2utf8('\ue12f')."".self::unicode2utf8('\ue12f')."
③ [帮助中心]使用手册，优秀视频，我要投诉，常见问题等相关帮助

"]
        ];

        $txt = self::ch_json_encode($txt_arr);
        $token = AccessToken::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token;
        $txt_ret = self::https_post($url,$txt);

        $tuwenList[] = array(

            'title' => '理优1对1招师代理攻略',

            'description' => '
如果你想成为招师代理却毫无头绪，
今天小编来介绍几种招师代理的推广方法，
帮你get技能，迅速上手！
快来加入理优招师代理的大家庭吧！',

            'pic_url' => 'https://mmbiz.qlogo.cn/mmbiz_jpg/cBWf565lml4NcGMWTiaeuDmWsUQpXz8TPJzfbsoUENe9dKqPKDXPZa7ITPCKvQiaVzmAvLBKPYmrhKNg2AkwwkVQ/0?wx_fmt=jpeg',

            'url' => 'http://admin.leo1v1.com/article_wx/leo_teacher_agent',

        );

        $item = array();
        foreach($tuwenList as $tuwen){
            $item[] = ResponsePassive::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
        }
        return  ResponsePassive::news($request['fromusername'], $request['tousername'], $item);

    }

    /**
     * @descrpition 取消关注
     * @param $request
     * @return array
     */
    public static function eventUnsubscribe(&$request){
        $content = '为什么不理我了？';
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 扫描二维码关注（未关注时）
     * @param $request
     * @return array
     */
    public static function eventQrsceneSubscribe(&$request){
        /*
        *用户扫描带参数二维码进行自动分组
        *此处添加此代码是大多数需求是在扫描完带参数二维码之后对用户自动分组
        */
        $sceneid = str_replace("qrscene_","",$request['eventkey']);
        //移动用户到相应分组中去,此处的$sceneid依赖于之前创建时带的参数
        if(!empty($sceneid)){

            $content = '欢迎您关注我们的微信，将为您竭诚服务。';
            $wx_openid=$request['fromusername'];
            $phone= $sceneid ;
            $t_parent_info= new \App\Models\t_parent_info();
            $content.=$t_parent_info->wx_binding_from_qrcode($wx_openid,$phone);

            #UserManage::editUserGroup($request['fromusername'], $sceneid);
            #$result=UserManage::getGroupByOpenId($request['fromusername']);
            //方便开发人员调试时查看参数正确性
            // $content = '欢迎您关注我们的微信，将为您竭诚服务。二维码Id:'.$result['groupid'];
        }else{
            $content = '欢迎您关注我们的微信，将为您竭诚服务。';
        }
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 扫描二维码（已关注时）
     * @param $request
     * @return array
     */
    public static function eventScan(&$request){

        \App\Helper\Utils::logger("WX request:" .json_encode($request) );


        $sceneid = str_replace("qrscene_","",$request['eventkey']);
        //移动用户到相应分组中去,此处的$sceneid依赖于之前创建时带的参数
        if(!empty($sceneid)){
            $content="您已经关注我们的微信，将为您竭诚服务。";

            $wx_openid=$request['fromusername'];
            $phone= $sceneid ;
            $t_parent_info= new \App\Models\t_parent_info();
            $content.=$t_parent_info->wx_binding_from_qrcode($wx_openid,$phone);
            #UserManage::editUserGroup($request['fromusername'], $sceneid);
            #$result=UserManage::getGroupByOpenId($request['fromusername']);
            //方便开发人员调试时查看参数正确性
            // $content = '欢迎您关注我们的微信，将为您竭诚服务。二维码Id:'.$result['groupid'];
        }else {
            $content = '您已经关注我们的微信，将为您竭诚服务。';
        }

        if(preg_match('/^1(3[0-9]|5[012356789]|8[0256789]|7[0678])\d{8}$/',$sceneid)){
            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);//old
        }else{

            $templateId = '9sMVYJCrHtNLdEiOoXqBtqcYkm2QBstaFKOWsrV8GnA';
            $url        = '';
            $touser     = $sceneid;
            $data = array(

                'first'=>array('value'=>'你已扫码la', 'color'=>'#0A0A0A'),

                'keyword1'=>array('value'=>'你已扫码', 'color'=>'#CCCCCC'),

                'keyword2'=>array('value'=>'', 'color'=>'#CCCCCC'),

            );

            TemplateMessage::sendTemplateMessage($data, $touser, $templateId, $url, $topcolor='#FF0000');

            $tuwenList[] = array(

                'title' => '家长端下载手册',

                'description' => '家长端下载手册',

                'pic_url' => 'https://mmbiz.qlogo.cn/mmbiz_jpg/DdBO9OC10ic8KLMK1GjXLLYGtbBTXfnBuPXBZzokyv3CpwL5o9kC5ercljmH6TiaKk2BfhbAm6r1KzmT8rctZ3LQ/0?wx_fmt=jpeg',

                'url' => 'http://admin.leo1v1.com/article_wx/parent_side_manual',

            );

            $item = array();
            foreach($tuwenList as $tuwen){
                $item[] = ResponsePassive::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
            }
            return  ResponsePassive::news($request['fromusername'], $request['tousername'], $item);

        }

    }

    /**
     * @descrpition 上报地理位置
     * @param $request
     * @return array
     */
    public static function eventLocation(&$request){
        $content = '收到上报的地理位置';
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 自定义菜单 - 点击菜单拉取消息时的事件推送
     * @param $request
     * @return array
     */
    public static function eventClick(&$request){
        //获取该分类的信息
        $eventKey = $request['eventkey'];
        $content = '收到点击菜单事件，您设置的key是' . $eventKey;

        $tuwenList = array();
        if ($eventKey == 'manual') {

            $tuwenList[] = array(

                'title' => '[软件]如何下载老师端、语音检测APP',

                'description' => '',

                'pic_url' => 'http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E8%BD%AF%E4%BB%B6%E4%B8%8B%E8%BD%BD/%E8%80%81%E5%B8%88%E7%AB%AF%E8%BD%AF%E4%BB%B6%E4%B8%8B%E8%BD%BD_%E5%B0%81%E9%9D%A2.png',

                'url' => 'http://wx-teacher-web.leo1v1.com/wx_teacher_share/leo_teacher_help/leo_teacher_software.html'

            );


            $tuwenList[] = array(

                'title' => '[iPad端] 软件使用教程',

                'description' => '',

                'pic_url' => 'http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-ipad%E7%AB%AF/%E5%B0%81%E9%9D%A2',

                'url' => "http://wx-teacher-web.leo1v1.com/wx_teacher_share/leo_teacher_help/leo_teacher_ipad.html",

            );

            $tuwenList[] = array(

                'title' => '[PC端] 软件使用教程',

                'description' => '',

                'pic_url' => 'http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-PC%E7%AB%AF/0.png',

                'url' => "http://wx-teacher-web.leo1v1.com/wx_teacher_share/leo_teacher_help/leo_teacher_pc.html"

            );


            $tuwenList[] = array(

                'title' => '[讲课白板] 功能介绍',

                'description' => '',

                'pic_url' => 'http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E7%99%BD%E6%9D%BF/%E7%99%BD%E6%9D%BF.png',

                'url' => 'http://wx-teacher-web.leo1v1.com/wx_teacher_share/leo_teacher_help/leo_teacher_whiteboard.html',
            );



        } elseif ($eventKey == 'video') {
            \App\Helper\Utils::logger("video_xj11");

            $tuwenList[] = array(

                'title' => '[数学] 试听课案例鉴赏',

                'description' => '',

                'pic_url' => 'https://mmbiz.qlogo.cn/mmbiz_png/cBWf565lml4sqb8Xr7LTXMyUia5bFziaqyaqfslQr5sWpTc3hqz3KF0QLpAmmLjeI6xNGlNic2PibPynJSIHrU903w/0?wx_fmt=png',

                'url' => "http://wx-teacher-web.leo1v1.com/wx_teacher_share/leo_teacher_help/leo_teacher_video_math.html"
            );

            $tuwenList[] = array(

                'title' => '[英语] 试听课案例鉴赏',

                'description' => '',

                'pic_url' => 'https://mmbiz.qlogo.cn/mmbiz_png/cBWf565lml4eLBb4Cluv6icfGh2Z5u8fO6LBGO3Q2EpBErwkFCXzKgLd5qeCibagCSo0SxM6enfzNhGYoicCcxwdw/0?wx_fmt=png',

                'url' => "http://wx-teacher-web.leo1v1.com/wx_teacher_share/leo_teacher_help/leo_teacher_video_english.html"
            );

            $tuwenList[] = array(

                'title' => '[语文] 试听课案例鉴赏',

                'description' => '',

                'pic_url' => 'https://mmbiz.qlogo.cn/mmbiz_png/cBWf565lml6MUooCw5Ylg8H04YNM6kv5keTjRg7iaibwzgMHDx9TWm7nAoAiab35nudc8WAFELeRLg3SqvPx5picsg/0?wx_fmt=png',

                'url' => "http://wx-teacher-web.leo1v1.com/wx_teacher_share/leo_teacher_help/leo_teacher_video_chinese.html"
            );

            $tuwenList[] = array(

                'title' => '[物理] 试听课案例鉴赏',

                'description' => '',

                'pic_url' => 'https://mmbiz.qlogo.cn/mmbiz_png/cBWf565lml5wfZguFN5Sib6H0sHGzE77PqBg0wvSLgwQmiaByySeb7W1OmwknKgH3VGFcOjoicHPsdlCZmPKv7D7Q/0?wx_fmt=png',

                'url' => "http://wx-teacher-web.leo1v1.com/wx_teacher_share/leo_teacher_help/leo_teacher_video_physics.html"
            );


        } elseif ($eventKey == 'friends') {
            $content = "理优老师报名链接：http://www.leo1v1.com/tea.html";
            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        } elseif ($eventKey == 'question') {
            $tuwenList[] = array(

                'title' => '[老师] 常见问题处理方法',

                'description' => '',

                'pic_url' => 'https://mmbiz.qlogo.cn/mmbiz_png/cBWf565lml5ticciaEDNHDsQ66rd1sibEhSVp4uUk6ZuzuwGByOLricbBloLr1qUOEIaIjOMBENrWdpqtGZpuoab7Q/0?wx_fmt=png',

                'url' => 'http://admin.leo1v1.com/article_wx/leo_teacher_deal_question',

            );

            $tuwenList[] = array(

                'title' => '[新师培训] 常见问题处理方法',

                'description' => '',

                'pic_url' => 'https://mmbiz.qlogo.cn/mmbiz_png/cBWf565lml5ticciaEDNHDsQ66rd1sibEhSU1QAFDC79vNel7s6NHPj0iaksAr7QibGic2JdAic6UDWWQHmfRx6HEdK2w/0?wx_fmt=png',

                'url' => 'http://admin.leo1v1.com/article_wx/leo_teacher_new_teacher_deal_question',

            );

        } elseif ($eventKey == 'invitation') {
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxa99d0de03f407627&redirect_uri=http%3A%2F%2Fwx-teacher.leo1v1.com%2Fcommon%2Fgoto_url%3Furl%3Dhttp%3A%2F%2Fadmin.leo1v1.com%2Farticle_wx%2Fget_openid%3F&response_type=code&scope=snsapi_base&state=1&connect_redirect=1#wechat_redirect';

            $openid = $request['fromusername'];

            $userInfo = UserManage::getUserInfo($openid);

            $t_teacher_info = new \App\Models\t_teacher_info();
            $phone = $t_teacher_info->get_phone_by_wx_openid($openid);

            if ( !$phone ){
                $content="
【绑定提醒】
您还未绑定手机，请绑定成功后重试
绑定地址：http://t.cn/RcQGnPX ";

                $_SESSION['wx_openid'] =   $request['fromusername'];
                session(['wx_openid'=> $request['fromusername']]);

                \App\Helper\Utils::logger("guanzhu1".session('wx_openid'));

                \App\Helper\Common::redis_set("teacher_wx_openid", $request['fromusername'] );


                return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
            }

            $url = "http://admin.leo1v1.com/common/get_teacher_qr?wx_openid=".$openid;

            $img_url = self::get_img_url($url);

            $type = 'image';

            $num = rand();
            $img_Long = file_get_contents($img_url);

            file_put_contents( public_path().'/wximg/'.$num.'.png',$img_Long);

            $img_url = public_path().'/wximg/'.$num.'.png';
            $img_url = realpath($img_url);

            $mediaId = Media::upload($img_url, $type);
            $mediaId = $mediaId['media_id'];

            unlink($img_url);

            $openid_user = $request['fromusername'];

            //使用客服接口发送消息
            $txt_arr = [
                'touser'   => $openid_user,
                'msgtype'  => 'text',
                'text'     => [
                    'content' =>
'【分享海报邀请好友报名】
'
.self::unicode2utf8('\ue12f').'面试及培训通过即可领取伯乐奖'.self::unicode2utf8('\ue12f').'
• 邀请高校生
第1～10人 '.self::unicode2utf8('\ue12f').'20元/人；
第11~20人 '.self::unicode2utf8('\ue12f').'30元/人；
第21~30人 '.self::unicode2utf8('\ue12f').'50元/人；
第31~50人 '.self::unicode2utf8('\ue12f').'60元/人；
• 邀请在职老师
第1～10人 '.self::unicode2utf8('\ue12f').'40元/人；
第11~20人 '.self::unicode2utf8('\ue12f').'50元/人；
第21~30人 '.self::unicode2utf8('\ue12f').'70元/人；
第31~50人 '.self::unicode2utf8('\ue12f').'80元/人；
'.self::unicode2utf8('\ue12f').'伯乐奖通过银行卡发放,请立刻绑定银行卡
'.self::unicode2utf8('\ue12f').'[我的收入]中可查看详情
'.self::unicode2utf8('\ue22f').' 分享邀请海报，领取伯乐奖！
']
            ];

            $txt = self::ch_json_encode($txt_arr);
            $token = AccessToken::getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token;
            $txt_ret = self::https_post($url,$txt);

            return ResponsePassive::image($request['fromusername'], $request['tousername'], $mediaId);

        }
        $item = array();
        foreach($tuwenList as $tuwen){
            $item[] = ResponsePassive::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
        }
        return  ResponsePassive::news($request['fromusername'], $request['tousername'], $item);
    }

    /**
     * @descrpition 自定义菜单 - 点击菜单跳转链接时的事件推送
     * @param $request
     * @return array
     */
    public static function eventView(&$request){
        //获取该分类的信息
        $eventKey = $request['eventkey'];
        $content = '收到跳转链接事件，您设置的key是' . $eventKey;
        if ($eventKey) {
        }

        \App\Helper\Utils::logger('tiaozhuan');


        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 自定义菜单 - 扫码推事件的事件推送
     * @param $request
     * @return array
     */
    public static function eventScancodePush(&$request){
        //获取该分类的信息
        $eventKey = $request['eventkey'];
        $content = '收到扫码推事件的事件，您设置的key是' . $eventKey;
        $content .= '。扫描信息：'.$request['scancodeinfo'];
        $content .= '。扫描类型(一般是qrcode)：'.$request['scantype'];
        $content .= '。扫描结果(二维码对应的字符串信息)：'.$request['scanresult'];
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 自定义菜单 - 扫码推事件且弹出“消息接收中”提示框的事件推送
     * @param $request
     * @return array
     */
    public static function eventScancodeWaitMsg(&$request){
        //获取该分类的信息
        $eventKey = $request['eventkey'];
        $content = '收到扫码推事件且弹出“消息接收中”提示框的事件，您设置的key是' . $eventKey;
        $content .= '。扫描信息：'.$request['scancodeinfo'];
        $content .= '。扫描类型(一般是qrcode)：'.$request['scantype'];
        $content .= '。扫描结果(二维码对应的字符串信息)：'.$request['scanresult'];
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 自定义菜单 - 弹出系统拍照发图的事件推送
     * @param $request
     * @return array
     */
    public static function eventPicSysPhoto(&$request){
        //获取该分类的信息
        $eventKey = $request['eventkey'];
        $content = '收到弹出系统拍照发图的事件，您设置的key是' . $eventKey;
        $content .= '。发送的图片信息：'.$request['sendpicsinfo'];
        $content .= '。发送的图片数量：'.$request['count'];
        $content .= '。图片列表：'.$request['piclist'];
        $content .= '。图片的MD5值，开发者若需要，可用于验证接收到图片：'.$request['picmd5sum'];
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 自定义菜单 - 弹出拍照或者相册发图的事件推送
     * @param $request
     * @return array
     */
    public static function eventPicPhotoOrAlbum(&$request){
        //获取该分类的信息
        $eventKey = $request['eventkey'];
        $content = '收到弹出拍照或者相册发图的事件，您设置的key是' . $eventKey;
        $content .= '。发送的图片信息：'.$request['sendpicsinfo'];
        $content .= '。发送的图片数量：'.$request['count'];
        $content .= '。图片列表：'.$request['piclist'];
        $content .= '。图片的MD5值，开发者若需要，可用于验证接收到图片：'.$request['picmd5sum'];
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 自定义菜单 - 弹出微信相册发图器的事件推送
     * @param $request
     * @return array
     */
    public static function eventPicWeixin(&$request){
        //获取该分类的信息
        $eventKey = $request['eventkey'];
        $content = '收到弹出微信相册发图器的事件，您设置的key是' . $eventKey;
        $content .= '。发送的图片信息：'.$request['sendpicsinfo'];
        $content .= '。发送的图片数量：'.$request['count'];
        $content .= '。图片列表：'.$request['piclist'];
        $content .= '。图片的MD5值，开发者若需要，可用于验证接收到图片：'.$request['picmd5sum'];
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 自定义菜单 - 弹出地理位置选择器的事件推送
     * @param $request
     * @return array
     */
    public static function eventLocationSelect(&$request){
        //获取该分类的信息
        $eventKey = $request['eventkey'];
        $content = '收到点击跳转事件，您设置的key是' . $eventKey;
        $content .= '。发送的位置信息：'.$request['sendlocationinfo'];
        $content .= '。X坐标信息：'.$request['location_x'];
        $content .= '。Y坐标信息：'.$request['location_y'];
        $content .= '。精度(可理解为精度或者比例尺、越精细的话 scale越高)：'.$request['scale'];
        $content .= '。地理位置的字符串信息：'.$request['label'];
        $content .= '。朋友圈POI的名字，可能为空：'.$request['poiname'];
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * 群发接口完成后推送的结果
     *
     * 本消息有公众号群发助手的微信号“mphelper”推送的消息
     * @param $request
     */
    public static function eventMassSendJobFinish(&$request){
        //发送状态，为“send success”或“send fail”或“err(num)”。但send success时，也有可能因用户拒收公众号的消息、系统错误等原因造成少量用户接收失败。err(num)是审核失败的具体原因，可能的情况如下：err(10001), //涉嫌广告 err(20001), //涉嫌政治 err(20004), //涉嫌社会 err(20002), //涉嫌色情 err(20006), //涉嫌违法犯罪 err(20008), //涉嫌欺诈 err(20013), //涉嫌版权 err(22000), //涉嫌互推(互相宣传) err(21000), //涉嫌其他
        $status = $request['status'];
        //计划发送的总粉丝数。group_id下粉丝数；或者openid_list中的粉丝数
        $totalCount = $request['totalcount'];
        //过滤（过滤是指特定地区、性别的过滤、用户设置拒收的过滤，用户接收已超4条的过滤）后，准备发送的粉丝数，原则上，FilterCount = SentCount + ErrorCount
        $filterCount = $request['filtercount'];
        //发送成功的粉丝数
        $sentCount = $request['sentcount'];
        //发送失败的粉丝数
        $errorCount = $request['errorcount'];
        $content = '发送完成，状态是'.$status.'。计划发送总粉丝数为'.$totalCount.'。发送成功'.$sentCount.'人，发送失败'.$errorCount.'人。';
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * 群发接口完成后推送的结果
     *
     * 本消息有公众号群发助手的微信号“mphelper”推送的消息
     * @param $request
     */
    public static function eventTemplateSendJobFinish(&$request){
        //发送状态，成功success，用户拒收failed:user block，其他原因发送失败failed: system failed
        $status = $request['status'];
        if($status == 'success'){
            //发送成功
        }else if($status == 'failed:user block'){
            //因为用户拒收而发送失败
        }else if($status == 'failed: system failed'){
            //其他原因发送失败
        }
        return true;
    }


    public static function test(){
        // 第三方发送消息给公众平台
        $encodingAesKey = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFG";
        $token = "pamtest";
        $timeStamp = "1409304348";
        $nonce = "xxxxxx";
        $appId = "wxb11529c136998cb6";
        $text = "<xml><ToUserName><![CDATA[oia2Tj我是中文jewbmiOUlr6X-1crbLOvLw]]></ToUserName><FromUserName><![CDATA[gh_7f083739789a]]></FromUserName><CreateTime>1407743423</CreateTime><MsgType><![CDATA[video]]></MsgType><Video><MediaId><![CDATA[eYJ1MbwPRJtOvIEabaxHs7TX2D-HV71s79GUxqdUkjm6Gs2Ed1KF3ulAOA9H1xG0]]></MediaId><Title><![CDATA[testCallBackReplyVideo]]></Title><Description><![CDATA[testCallBackReplyVideo]]></Description></Video></xml>";


        $pc = new Aes\WXBizMsgCrypt($token, $encodingAesKey, $appId);
        $encryptMsg = '';
        $errCode = $pc->encryptMsg($text, $timeStamp, $nonce, $encryptMsg);
        if ($errCode == 0) {
            print("加密后: " . $encryptMsg . "\n");
        } else {
            print($errCode . "\n");
        }

        $xml_tree = new \DOMDocument();
        $xml_tree->loadXML($encryptMsg);
        $array_e = $xml_tree->getElementsByTagName('Encrypt');
        $array_s = $xml_tree->getElementsByTagName('MsgSignature');
        $encrypt = $array_e->item(0)->nodeValue;
        $msg_sign = $array_s->item(0)->nodeValue;

        $format = "<xml><ToUserName><![CDATA[toUser]]></ToUserName><Encrypt><![CDATA[%s]]></Encrypt></xml>";
        $from_xml = sprintf($format, $encrypt);

// 第三方收到公众号平台发送的消息
        $msg = '';
        $errCode = $pc->decryptMsg($msg_sign, $timeStamp, $nonce, $from_xml, $msg);
        if ($errCode == 0) {
            print("解密后: " . $msg . "\n");
        } else {
            print($errCode . "\n");
        }
    }

    /*
     *负责处理emoji编码
     */

    public static function unicode2utf8($str)
    {
        $str = '{"result_str":"'.$str.'"}';    //组合成json格式
        $strarray = json_decode($str,true);    //json转换为数组，利用 JSON 对 \uXXXX 的支持来把转义符恢复为 Unicode 字符
        return $strarray['result_str'];
    }


    public static function deal_send_img(&$request,$openid){
        \App\Helper\Utils::logger('ddxueji'.json_encode($request));

        $content = 'ooo';
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    public static function get_img_url($url){
        \App\Helper\Utils::logger('curlimg1');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        \App\Helper\Utils::logger('curlimg2');
        return $output;
    }

    public static function https_post($url,$data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public static function ch_json_encode($data) {


        $ret = self::ch_urlencode($data);
        $ret = json_encode($ret);

        return urldecode($ret);
    }

    public static function ch_urlencode($data) {
        if (is_array($data) || is_object($data)) {
            foreach ($data as $k => $v) {
                if (is_scalar($v)) {
                    if (is_array($data)) {
                        $data[$k] = urlencode($v);
                    } else if (is_object($data)) {
                        $data->$k = urlencode($v);
                    }
                } else if (is_array($data)) {
                    $data[$k] = self::ch_urlencode($v); //递归调用该函数
                } else if (is_object($data)) {
                    $data->$k = self::ch_urlencode($v);
                }
            }
        }

        return $data;
    }






}
