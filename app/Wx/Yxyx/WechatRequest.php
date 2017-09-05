<?php
namespace App\Wx\Yxyx;
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

use Yxyx\Core\WeChatOAuth;
use Yxyx\Core\UserManage;
use Yxyx\Core\Media;
use Yxyx\Core\AccessToken;


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
                        \App\Helper\Utils::logger('yxyx_click');
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
        $content = $request['content'];
        if($content == '优学优享'){
            $tuwenList[] = array(
                'title' => '优学优享简介',
                'description' => '优学优享是理优教育旗下的分享社区。分享科学的教育理念。如邀请身边的好友进入理优学习，可获得相应的奖励。',
                'pic_url' => 'http://7u2f5q.com2.z0.glb.qiniucdn.com/83cfe76ea4e8ef816e9ac09ce0f830701501310436597.jpg',
                'url' => 'http://admin.yb1v1.com/article_wx/leo_yxyx_int',
            );
            $item = array();
            foreach($tuwenList as $tuwen){
                $item[] = ResponsePassive::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
            }
            return  ResponsePassive::news($request['fromusername'], $request['tousername'], $item);
        }elseif($content == '测试一'){
            $tuwenList[] = array(
                'title' => '测试文章一',
                'description' => '优学优享是理优教育旗下的分享社区。分享科学的教育理念。如邀请身边的好友进入理优学习，可获得相应的奖励。',
                'pic_url' => '',
                'url' => 'http://admin.yb1v1.com/article_wx/leo_yxyx_int',
            );
            $item = array();
            foreach($tuwenList as $tuwen){
                $item[] = ResponsePassive::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
            }
            return  ResponsePassive::news($request['fromusername'], $request['tousername'], $item);
        }else{
            $content = self::unicode2utf8('\ue032')."你来啦，真好。".self::unicode2utf8('\ue032')."

【分享】它使快乐增大，它使悲伤减小。

【菜单栏功能介绍】
-[我要邀请]-:生成邀请海报。
-[理优教育]-:内有理优简介、精品内容、学员反馈、每日卡片。
-[账号管理]-:
(1)绑定账号：绑定手机号即可开启优学优享功能。
(2)个人中心：查询用户等级、邀请人数、奖励情况等。
(3)常见问题：自助解决问题。
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
        $content = '收到图片';


        \App\Helper\Utils::logger('img_openid777:'.$request['fromusername']);


        return ResponsePassive::image($request['fromusername'], $request['tousername'], $mediaId);

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


        $wx_config= \App\Helper\Config::get_config("yxyx_wx");
        $base_url=$wx_config["url"];
        $content =
            self::unicode2utf8('\ue032')."你来啦，真好。".self::unicode2utf8('\ue032')."

诚挚地邀请您，推荐身边朋友的孩子来理优学习。
每月轻松获得奖金过万。

理优1对1是专注于中小学1对1辅导的在线教育机构。
现有6000多名全国公校老师，以及40万注册用户。
每年帮助数以万计的学生提升成绩。学员成绩平均有30%的提高。

如何邀请：
【第一步】<a href='$base_url/wx_yxyx_web/bind'>绑定账号</a>
【第二步】点击“我要邀请”生成邀请海报
【第三步】保存图片，发给朋友或朋友圈

推广材料：
点击下方蓝字，将内容转发给好友或朋友圈
<a href='http://admin.yb1v1.com/article_wx/leo_yxyx_introduction'>【理优简介】</a>
<a href='http://www.xmypage.com/model2_28992.html'>【精品内容】</a>
<a href='https://h5.in66.com/inpromo/inweb/in-share/in.html?uid=1EwYvwxb&_ig=share_my_copy'>【学员反馈】</a>

点击蓝字:<a href='http://www.leo1v1.com/market-l/index.html'>预约试听课</a>";
        $_SESSION['wx_openid'] = $request['fromusername'];

        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
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

                'url' => 'http://admin.yb1v1.com/article_wx/parent_side_manual',

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
        $openid = $request['fromusername'];
        $wx_config= \App\Helper\Config::get_config("yxyx_wx");
        $base_url=$wx_config["url"];


        //获取该分类的信息
        $eventKey = $request['eventkey'];
        $content = '收到点击菜单事件，您设置的key是' . $eventKey;
        $tuwenList = array();
        if($eventKey == 'content') {
            $t_agent = new \App\Models\t_agent();
            $agent = $t_agent->get_agent_info_by_openid($openid);
            $phone = '';
            if(isset($agent['phone'])){
                $phone = $agent['phone'];
            }
            $tuwenList[] = array(
                'title' => '精品内容',
                'description' => '',
                'pic_url' => 'http://7u2f5q.com2.z0.glb.qiniucdn.com/fb5c81ed3a220004b71069645f1128671501667305656.png',
                'url' => 'http://wx-parent-web.leo1v1.com/wx-invite-article/index.html?p_phone='.$phone,
            );
        }elseif($eventKey == 'feedback'){
            $tuwenList[] = array(
                'title' => '学员反馈',
                'description' => '',
                'pic_url' => 'http://7u2f5q.com2.z0.glb.qiniucdn.com/a7ee56745585a55a4703baadfbd9f5c11501667371977.JPG',
                'url' => 'https://h5.in66.com/inpromo/inweb/in-share/in.html?uid=1EwYvwxb&_ig=share_my_copy',
            );
        }elseif($eventKey == 'card'){
            $tuwenList[] = array(
                'title' => '每日卡片',
                'description' => '',
                'pic_url' => 'http://7u2f5q.com2.z0.glb.qiniucdn.com/a7ee56745585a55a4703baadfbd9f5c11501667371977.JPG',
                'url' => 'https://m.llspace.com/g-main-3765446.html?platform=ard&client_version=1.14.2&from=singlemessage',
            );
        }elseif($eventKey == 'question') {
            $tuwenList[] = array(
                'title' => '常见问题Q&A',
                'description' => '',
                'pic_url' => 'http://7u2f5q.com2.z0.glb.qiniucdn.com/b3291e92621199f457028e10dc7de8e51500964583043.png',
                'url' => 'http://admin.yb1v1.com/article_wx/leo_yxyx_question',
           );
        }elseif ($eventKey == 'invitation') {
            $t_agent = new \App\Models\t_agent();
            $agent = $t_agent->get_agent_info_by_openid($openid);
            $phone = '';
            if(isset($agent['phone'])){
                $phone = $agent['phone'];
            }
            if(!$phone){
                $content="
【绑定提醒】
您还未绑定手机，请绑定成功后重试
绑定地址：$base_url/wx_yxyx_web/bind";
                $_SESSION['wx_openid'] =   $request['fromusername'];
                session(['wx_openid'=> $request['fromusername']]);

                return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
            }

            //使用客服接口发送消息
            $txt_arr = [
                'touser'   => $openid,
                'msgtype'  => 'text',
                'text'     => [
                    'content' =>
                    '①长按下方图片并保存
②将图片发给朋友或朋友圈'
                ]
            ];
            $txt = self::ch_json_encode($txt_arr);
            $token = AccessToken::getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token;
            $txt_ret = self::https_post($url,$txt);

            $url = "$base_url/common/get_agent_qr?wx_openid=".$openid;
            $img_url = self::get_img_url($url);
            $type = 'image';
            $num = rand();
            $img_Long = file_get_contents($img_url);
            file_put_contents(public_path().'/wximg/'.$num.'.png',$img_Long);
            $img_url = public_path().'/wximg/'.$num.'.png';
            $img_url = realpath($img_url);
            $mediaId = Media::upload($img_url, $type);
            $mediaId = $mediaId['media_id'];
            unlink($img_url);
            return ResponsePassive::image($request['fromusername'], $request['tousername'], $mediaId);
        }elseif ($eventKey == 'invitation_member') {
            $t_agent = new \App\Models\t_agent();
            $agent = $t_agent->get_agent_info_by_openid($openid);
            $phone = '';
            if(isset($agent['phone'])){
                $phone = $agent['phone'];
            }
            if(!$phone){
                $content="
【绑定提醒】
您还未绑定手机，请绑定成功后重试
绑定地址：$base_url/wx_yxyx_web/bind";
                $_SESSION['wx_openid'] =   $request['fromusername'];
                session(['wx_openid'=> $request['fromusername']]);

                return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
            }

            //使用客服接口发送消息
            $txt_arr = [
                'touser'   => $openid,
                'msgtype'  => 'text',
                'text'     => [
                    'content' =>
                    '①长按下方图片并保存
②将图片发给朋友或朋友圈'
                ]
            ];
            $txt = self::ch_json_encode($txt_arr);
            $token = AccessToken::getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token;
            $txt_ret = self::https_post($url,$txt);

            $url = "$base_url/common/get_agent_qr_new?wx_openid=".$openid;

            $img_url = self::get_img_url($url);
            $type = 'image';
            $num = rand();
            $img_Long = file_get_contents($img_url);
            file_put_contents(public_path().'/wximg/'.$num.'.png',$img_Long);
            $img_url = public_path().'/wximg/'.$num.'.png';
            $img_url = realpath($img_url);
            $mediaId = Media::upload($img_url, $type);
            $mediaId = $mediaId['media_id'];
            unlink($img_url);
            return ResponsePassive::image($request['fromusername'], $request['tousername'], $mediaId);
        }elseif ($eventKey == 'introduction') {
            $tuwenList[] = array(
                'title' => '上海理优教育科技有限公司图片简介',
                'description' => '',
                'pic_url' => 'http://7u2f5q.com2.z0.glb.qiniucdn.com/eef708bdb1d02310c9ab7ba5a3605c071501040957308.jpg',
                'url' => 'http://admin.yb1v1.com/article_wx/leo_yxyx_introduction',
            );

            // $tuwenList[] = array(
            //     'title' => '理优1对1用户指南',
            //     'description' => '',
            //     'pic_url' => 'http://7u2f5q.com2.z0.glb.qiniucdn.com/0db16ea2f7fe8bea4d08d39dcd90478e1501039384123.jpg',
            //     'url' => 'http://admin.yb1v1.com/article_wx/leo_yxyx_guide',
            // );
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
        if($eventKey == 'http://www.leo1v1.com/wx-invite-article/index.html'){
            \App\Helper\Utils::logger('yxyx_fff');
            $openid = $request['fromusername'];
            $t_agent = new \App\Models\t_agent();
            $agent = $t_agent->get_agent_info_by_openid($openid);
            if(isset($agent['phone'])){
                $phone = $agent['phone'];
                $eventKey.="?p_phone=$phone";
                \App\Helper\Utils::logger('yxyx_fff_phone:'.$phone);
            }
        }
        $content = '收到跳转链接事件，您设置的key是'.$eventKey;
        \App\Helper\Utils::logger('yxyx_fff_key'.$eventKey);

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
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
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

    public function check_bind($openid){
        $t_agent = new \App\Models\t_agent();
        $agent = $t_agent->get_agent_info_by_openid($openid);
        $phone = '';
        if(isset($agent['phone'])){
            $phone = $agent['phone'];
        }

        $wx_config= \App\Helper\Config::get_config("yxyx_wx");
        $base_url=$wx_config["url"];
        if(!$phone){
            $content="
【绑定提醒】
您还未绑定手机，请绑定成功后重试
绑定地址：$base_url/wx_yxyx_web/bind";
            $_SESSION['wx_openid'] =   $request['fromusername'];
            session(['wx_openid'=> $request['fromusername']]);

            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        }
    }

}
