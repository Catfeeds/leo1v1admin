<?php
namespace App\Wx\Parent;
/**
 * 处理请求
 * Created by Lane.
 * User: lane
 * Date: 13-12-19
 * Time: 下午11:04
 * Mail: lixuan868686@163.com
 * Website: http://www.lanecn.com
 */

use LaneWeChat\Core\WeChatOAuth;

use LaneWeChat\Core\UserManage;

use LaneWeChat\Core\TemplateMessage;

use LaneWeChat\Core\ResponsePassive;
use LaneWeChat\Core\Media;

class WechatRequest extends \LaneWeChat\Core\WechatRequest {
    /**
     * @descrpition 分发请求
     * @param $request
     * @return array|string
     */
    public static function switchType(&$request){
        // \App\Helper\Utils::logger("switchType1233".$request);

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

        $content = '
您好，如您遇到关于我们课程、学习方面的问题，请联系您的课程老师；如您已报名理优各学科课程，需咨询如作业上传等上课过程中碰到的问题，请联系孩子的助教老师；如在咨询后尚未得到回复，请留下孩子的姓名+您登录app的手机账号，我将在3个工作日内把您的问题提交给相关部门尽快解决!
';


        if($request['content'] == '测试'){

            $tuwenList[] = array(

                'title' => "理优双11  点赞优质教育",

                'description' => "福利来就送  100%中奖率！",

                'pic_url' => "http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E6%95%99%E8%82%B2%E5%9C%A8%E7%BA%BF-%E5%8E%9F%E5%9B%BE/%E6%B4%BB%E5%8A%A8/800%2A800.png",
                'url' => "http://wx-parent.leo1v1.com/wx_parent_gift/get_gift_for_parent",

            );

            $item = array();
            foreach($tuwenList as $tuwen){
                $item[] = ResponsePassive::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
            }
            return  ResponsePassive::news($request['fromusername'], $request['tousername'], $item);

        }elseif($request['content'] == '知识库'){
            $content = "http://wx-parent.leo1v1.com/wx_parent/zhishiku";
        }elseif($request['content'] == '转发'){
            $userOpenid = $request['fromusername'];
            $t_manager_info = new \App\Models\t_manager_info;

            $RoleId = $t_manager_info->checkIsRole($userOpenid);
            if($RoleId > 0){
                $content = "http://wx-parent-web.leo1v1.com/poster/index.html?uid=$RoleId"; // 待定
                # 记录输入转发次数
            }
        }elseif($request['content'] == 'BBC'){
            $content = "家长，您好！点击下方链接，输入密码，即可领取《英国BBC最强纪录片全10部》哦！
链接：https://pan.baidu.com/s/1sm1x2Mh 密码：e4jg";
        }elseif($request['content'] == '春节'){
            $filename = "/home/ybai/market_4.jpg";
            $type = 'image';
            $mediaId = 'nd4j0-_5vtIMcrLo2Fxn7iM2hPXqh5MkXNOZnM4mt4q_EGggAT4uxP6dHiHsO48-';
            // $mediaId_arr = Media::upload($filename, $type);
            // $mediaId = $mediaId_arr['media_id'];
            // \App\Helper\Utils::logger("james:_jsdfh_333: ".json_encode($mediaId_arr));

            return ResponsePassive::image($request['fromusername'], $request['tousername'], $mediaId);
        }elseif($request['content'] == '过年'){
            //$content = "http://wx-parent-web.leo1v1.com/poster/#/generate?type=newyear";
            $tuwenList[] = array(
                'title' => "有一种记忆,叫过年",
                'description' => "快来定制专属于你的贺卡拜年吧!",
                'pic_url' => "http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E6%95%99%E8%82%B2%E5%9C%A8%E7%BA%BF-%E5%8E%9F%E5%9B%BE/%E6%B4%BB%E5%8A%A8/%E8%BF%87%E5%B9%B4.jpg",
                'url' => "http://wx-parent-web.leo1v1.com/poster/#/generate?type=newyear"
            );

            $item = array();
            foreach($tuwenList as $tuwen){
                $item[] = ResponsePassive::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
            }
            return  ResponsePassive::news($request['fromusername'], $request['tousername'], $item);
        }elseif($request['content'] == '开学'){
            $filename = "/home/ybai/marketTr.jpg";
            $type = 'image';
            // $mediaId = 'nd4j0-_5vtIMcrLo2Fxn7iM2hPXqh5MkXNOZnM4mt4q_EGggAT4uxP6dHiHsO48-';
            $mediaId_arr = Media::upload($filename, $type);
            $mediaId = $mediaId_arr['media_id'];
            \App\Helper\Utils::logger("marketTr: ".json_encode($mediaId_arr));

            return ResponsePassive::image($request['fromusername'], $request['tousername'], $mediaId);
        }


        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);

        /**
         * @ 此处用户发送消息, 存取到数据库
         * @ 后台页面展示 客户姓名,昵称,消息内容,发送时间,是否可以收到回复信息
         * @ 后台人员 可以在后台页面上进行回复老师信息
         * @
         * @
         */



    }

    /**
     * @descrpition 图像
     * @param $request
     * @return array
     */
    public static function image(&$request){

        // $get_wx_token
        $content = '收到图片';
        $info = json_encode($request);
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $info);

        // 测试


        $tuwenList[] = array(

            'title' => '家长端下载手册',

            'description' => '家长端下载手册',

            'pic_url' => 'https://mmbiz.qlogo.cn/mmbiz_jpg/DdBO9OC10ic8KLMK1GjXLLYGtbBTXfnBuPXBZzokyv3CpwL5o9kC5ercljmH6TiaKk2BfhbAm6r1KzmT8rctZ3LQ/0?wx_fmt=jpeg',

            'url' => 'www.jiuqitian.com/chou7',

        );

        $item = array();
        foreach($tuwenList as $tuwen){
            $item[] = ResponsePassive::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
        }
        return  ResponsePassive::news($request['fromusername'], $request['tousername'], $item);



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


        $content =
                 "
终于等到你~
欢迎关注理优教育在线学习！
理优教育成立于2014年，是一家专注于中小学在线1对1辅导的教育机构。公司的管理与技术核心团队均来自于国内知名上市公司。同时具备丰富教学经验的老师团队，由在校老师、知名机构老师和全职教研老师组成，致力为中小学生提供专业、专注、有效的教学。
";


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

                //http://wx-parent.leo1v1.com/
                // 'url' => 'http://admin.yb1v1.com/article_wx/parent_side_manual',
                'url' => 'http://wx-parent.leo1v1.com/article_wx/parent_side_manual',

            );

            $item = array();
            foreach($tuwenList as $tuwen){
                $item[] = ResponsePassive::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
            }
            return  ResponsePassive::news($request['fromusername'], $request['tousername'], $item);

        }

        // return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);//old
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
        if ($eventKey == 'activity') {
            $tuwenList[] = array(

                'title' => '理优1对1线下活动与用户反馈',

                'description' => '关于我们 理优1对1隶属与上海理优教育科技有限公司，是一家专注与初高中学生在线1对1辅导教学',

                'pic_url' => 'https://mmbiz.qlogo.cn/mmbiz_png/DdBO9OC10ic8tJ1oRmm6TNYTSQE7NZH5o0hGlufNvd7LXvSkY96ntibaAU1LX6wTLaMxiceYSE4tkhlcGzib7JJdSQ/0?wx_fmt=png',

                // 'url' => 'http://admin.yb1v1.com/article_wx/activity',
                'url' => 'http://wx-teacher-web.leo1v1.com/wx_teacher_share/leo_student_side/activity.html',

            );

        } elseif ($eventKey == 'manual') {
            $tuwenList[] = array(

                'title' => '[手机]家长端使用手册',

                'description' => '[手机]家长端使用手册',

                'pic_url' => 'https://mmbiz.qlogo.cn/mmbiz_png/DdBO9OC10icicYdFxtz1HrGOD1WbNnsZgjfUruthic0JRaqmT59opL9d8EJtNNCkJ7V51GygyaEQcA55U0hYg2NGA/0?wx_fmt=png',

                // 'url' => 'http://admin.yb1v1.com/article_wx/parent_side_manual',
                'url' => 'http://wx-parent.leo1v1.com/article_wx/parent_side_manual',


            );

            $tuwenList[] = array(

                'title' => '[iPad]学生软件使用手册',

                'description' => '[Ipad]学生软件使用手册',

                'pic_url' => 'https://mmbiz.qlogo.cn/mmbiz_png/DdBO9OC10icicYdFxtz1HrGOD1WbNnsZgjXx6M1pRwJuQXZDBhCfR0cgAQMQj9d7ax9dibff8kia3YSHK0trVNGwEg/0?wx_fmt=png',

                // 'url' => 'http://admin.yb1v1.com/article_wx/student_side_manual_ipad',
                'url' => "http://wx-teacher-web.leo1v1.com/wx_teacher_share/leo_student_side/student_side_manual_ipad.html"

            );

            $tuwenList[] = array(

                'title' => '[PC端]学生软件使用手册',

                'description' => '[PC端]学生软件使用手册',

                'pic_url' => 'https://mmbiz.qlogo.cn/mmbiz_png/DdBO9OC10icicYdFxtz1HrGOD1WbNnsZgjrd0ILzqCsBBXx9KBqasKhIgEiaouNVQwNSfLWLMbUr8wgxibd94AAtAg/0?wx_fmt=png',

                // 'url' => 'http://admin.yb1v1.com/article_wx/student_side_manual_pc',
                'url' => 'http://wx-teacher-web.leo1v1.com/wx_teacher_share/leo_student_side/student_side_manual_pc.html',


            );

        }elseif($eventKey == 'content') {
            $openid  = $request['fromusername'];
            $t_agent = new \App\Models\t_agent();
            $agent   = $t_agent->get_agent_info_by_openid($openid);
            $phone = '';
            if(isset($agent['phone'])){
                $phone = $agent['phone'];
            }

            $tuwenList[] = array(
                'title' => '精品内容',
                'description' => '',
                'pic_url' => "http://7u2f5q.com2.z0.glb.qiniucdn.com/fb5c81ed3a220004b71069645f1128671501667305656.png",
                // 'url' => 'http://wx-parent-web.leo1v1.com/wx-invite-article/index.html?p_phone='.$phone,
                'url' => 'http://wx-parent-web.leo1v1.com/wx-invite-article/parent_article.html',
            );
        }
        $item = array();
        foreach($tuwenList as $tuwen){
            $item[] = ResponsePassive::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
        }
      return  ResponsePassive::news($request['fromusername'], $request['tousername'], $item);
        // return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
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

}
