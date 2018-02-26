<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;
use Illuminate\Support\Facades\Redis ;
use Illuminate\Support\Facades\Session ;

use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

require_once  app_path("/Libs/Qiniu/functions.php");

require_once(app_path("/Libs/OSS/autoload.php"));
use OSS\OssClient;

use OSS\Core\OssException;


class wx_teacher extends Controller
{

    var $appid="wx0e046235d4632c3b";
    var $appsecret="6635bf93476620f103102d34aa16b3ae";
    var $check_login_flag =false;

    public function get_token() {
        $appid     = $this->appid;
        $appsecret = $this->appsecret;
        $wx        = new \App\Helper\Wx();
        return $wx->get_wx_token($appid,$appsecret);
    }

    public function get_token_from_code($code) {
        $appid     = $this->appid;
        $appsecret = $this->appsecret;
        //https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code
        $json_data=file_get_contents( "https://api.weixin.qq.com/sns/oauth2/access_token?grant_type=authorization_code&code=$code&appid=$appid&secret=$appsecret"  );
        $ret_arr=\App\Helper\Utils::json_decode_as_array($json_data);

        return $ret_arr;
    }

    public function set_menu() {
        $str='{"button":[{"type":"click", "name":"xx", "key":"V1001_TODAY_MUSIC"}, {"name":"老师", "sub_button":[{"type":"view", "name":"管理系统", "url":"http://admin.leo1v1.com/wx_teacher_info/index"}, {"type":"view", "name":"抢课设置", "url":"http://admin.leo1v1.com/wx_teacher_info/test_lesson_config"}, {"type":"click", "name":"q23reqe", "key":"V1001_GOOD"}]}]}';

        $token=$this->get_token();
        $url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$token";

        echo \App\Helper\Common::http_post_json_str($url,$str);
    }

    public function page1() {
        dd("11". Cookie::get("laravel_session") );
    }

    public function page2() {
        dd( "xx". Cookie::get("laravel_session") );
    }

    //接收事件消息
    private function receiveEvent($object)
    {
        $content = "";
        switch ($object->Event)
        {
            case "subscribe":
                $content = "欢迎关注理优1对1 \n 你可以绑定在这里绑定 理优账户 随时关注孩子的学习情况 \n open id ={$object->FromUserName}";
                //$content .= (!empty($object->EventKey))?("\n来自二维码场景 ".str_replace("qrscene_","",$object->EventKey)):"";
                break;
            case "CLICK":
                switch ($object->EventKey)
                {
                    case "V1001_TODAY_MUSIC":
                        $openid=$object->FromUserName;
                        //$content = array();
                        $content = " 你可以绑定在这里绑定 老师账号 <a href=\"http://admin.leo1v1.com/wx_teacher/bind?openid=" . $openid."\" > 点击这里</a>  然后就可以相关功能 ";
                        break;
                    default:
                        $content = "点击菜单：".$object->EventKey;
                        break;
                }
                break;
        }
        $result = $this->transmitText($object, $content);

        return $result;
    }

    //回复文本消息
    private function transmitText($object, $content)
    {
        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }

    public function  wx_notify() {
        global $_GET;
        global $GLOBALS;
        $post_str = @$GLOBALS["HTTP_RAW_POST_DATA"];

        \App\Helper\Utils::logger("POST:$post_str");
        if (!empty($post_str)){
            $postObj = simplexml_load_string($post_str, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);

            //消息类型分离
            switch ($RX_TYPE)
            {
                case "event":
                    $result = $this->receiveEvent($postObj);
                    break;
                case "text":
                    $result = $this->receiveText($postObj);
                    break;
            }
            \App\Helper\Utils::logger("result:$result"  );
            echo $result;
            exit;
        }else {
            echo "";
            exit;
        }

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce     = $_GET["nonce"];

        $token  = "123456";
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
            return $_GET["echostr"];
            exit;
        }else{
            exit;
        }
    }

    public function bind() {
        $openid = $this->get_in_str_val("openid");
        $url    = $this->get_in_str_val("url");

        $page_info=\App\Helper\Utils::list_to_page_info([]);
        return $this->pageView(__METHOD__,$page_info);
    }

    public function bind_off()  {
        $ret=$this->t_wx_openid_bind->row_delete_2(session("openid"),E\Erole::V_TEACHER );
        $_SESSION = array();
        Session::flush() ;
        return $this->output_bool_ret($ret==1);
    }


    public function do_bind()  {
        $openid   = $this->get_in_str_val("openid");
        $account  = $this->get_in_str_val("account");
        $password = $this->get_in_str_val('password');
        $seccode  = $this->get_in_str_val('seccode');
        $ip       = $this->get_in_client_ip();
        if (empty($seccode) || $seccode !== session('verify')) {
            return  $this->output_err( E\Eerror::V_WRONG_VERIFY_CODE);
        }

        $user_info=$this->t_phone_to_user->get_info_from_role_phone(E\Erole::V_TEACHER, $account);
        $userid=@$user_info["userid"];
        $ret=false;
        if ($userid) {
            if ($password == $this->t_user_info->get_passwd($userid))  {
                $ret=true;
            }
            if(!$ret) {
                $key = $account.'_'. E\Erole::V_TEACHER ;
                $dynamic_passwd=\App\Helper\Common::redis_get($key,10);
                if ($password==$dynamic_passwd)  {
                    $ret=true;
                }
            }
            if ($ret) {
                $this->t_wx_openid_bind->row_insert([
                    "openid"  => $openid,
                    "role"  => E\Erole::V_TEACHER,
                    "userid" => $userid,
                ]);
            }
        }
        if ($ret) {
            return $this->output_succ();
        }else{
            return $this->output_err("用户名密码不匹配");
        }
    }



    public function stu_upload_homework(){
        $lessonid = $this->get_in_int_val("lessonid");

        $wx = new \App\Console\Tasks\TeacherTask();
        $wx->notice_teacher_check_stu_homework($lessonid);
    }

    /*
      开发中.......
    */
    public function imgupload(){
        $token = $this->get_wx_token_jssdk();

        $key_arr     = "wx_tec_jssdk_arr";
        $key_str     = 'wx_tec_jssdk_str';
        $ret_arr = \App\Helper\Common::redis_get_json($key_arr);
        $now     = time(NULL);


        if (!$ret_arr || !isset($ret_arr["ticket"])  ||  $ret_arr["get_time"]+7000 <  $now ) {

            $jssdk    = $this->get_wx_jsapi_ticket($token);
            $ret_arr  = \App\Helper\Utils::json_decode_as_array($jssdk);
            $ret_arr["get_time"] = time(NULL);
            \App\Helper\Common::redis_set_json($key_arr,$ret_arr );

            $jsapi_ticket = $ret_arr["ticket"];

            $signature = "jsapi_ticket=$jsapi_ticket&noncestr=leo123&timestamp=1494474414&url=http://admin.leo1v1.com/wx_teacher/imgupload";

            $signature_str = sha1($signature);
            \App\Helper\Common::redis_set_json($key_str,$signature_str );

        } else {
            $signature_str =   \App\Helper\Common::redis_get_json($key_str);
        }

        return $this->pageView(__METHOD__,null,
                        ['signature_str'=>$signature_str]
                      );
    }

    public function get_wx_token_jssdk(){

        $appid_tec     = 'wxa99d0de03f407627';
        $appscript_tec = '61bbf741a09300f7f2fd0a861803f920';

        $wx        = new \App\Helper\Wx();
        return $wx->get_wx_token($appid_tec,$appscript_tec);

    }

    public function get_wx_jsapi_ticket($token){
        $json_jssdk_data=file_get_contents("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$token&type=jsapi ");

        return $json_jssdk_data;
    }


    public function get_wximg_by_serverId(){
        $serverId_str = $this->get_in_str_val('serverId');
        $lessonid     = $this->get_in_int_val('lessonid');
        \App\Helper\Utils::logger('tupianids');

        $serverIdLists = json_decode($serverId_str,true);

        \App\Helper\Utils::logger('tupianids2');
        $alibaba_url = array();


        \App\Helper\Utils::logger('wxserverid2'.$serverId_str);

        foreach($serverIdLists as $serverId){
            \App\Helper\Utils::logger('xunhuanid55'.$serverId);

            $imgStateInfo = $this->savePicToServer($serverId);
            $result = $this->put_img_to_alibaba($imgStateInfo['savePathFile']);
            \App\Helper\Utils::logger('xunhuanid2');
            $result_arr = json_decode($result,true);
            \App\Helper\Utils::logger('xunhuanid3'.$result);
            $alibaba_url[] = $result_arr['file_url'];

            \App\Helper\Utils::logger('xunhuanid44'.$result_arr['file_url']);

        }

        $alibaba_url_str = json_encode($alibaba_url);
        // $imgStateInfo = $this->savePicToServer($serverId);

        // $result = $this->put_img_to_alibaba($imgStateInfo['savePathFile']);

        \App\Helper\Utils::logger('jieguoimg1'.json_encode($alibaba_url));

        $this->t_test_lesson_subject->put_pic_to_alibaba($lessonid, $alibaba_url_str);


        // return outputjson_success(["data"=> $result  ]);
        // return outputjson_success(["data"=> $serverId  ]);
    }


    public function savePicToServer($serverId) {
        $accessToken = $this->get_wx_token_jssdk();
        // 要存在你服务器哪个位置？
        $route = md5(date('YmdHis'));
        $savePathFile = public_path('wximg').'/'.$route.'.jpg';
        $targetName   = $savePathFile;
        \App\Helper\Utils::logger('wxlujin'.$savePathFile);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $fp = fopen($targetName,'wb');
        curl_setopt($ch,CURLOPT_URL,"http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$accessToken&media_id=$serverId");
        curl_setopt($ch,CURLOPT_FILE,$fp);
        curl_setopt($ch,CURLOPT_HEADER,0);
        $msg['state'] = curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        \App\Helper\Utils::logger('xuejiwximg'.$msg['state']);
        $msg['savePathFile'] = $savePathFile;
        return $msg;
    }

    public function put_img_to_alibaba($target){
        \App\Helper\Utils::logger('wxalibaba1');

        try {
            $config=\App\Helper\Config::get_config("ali_oss");
            $file_name=basename($target);

            $ossClient = new OssClient(
                $config["oss_access_id"],
                $config["oss_access_key"],
                $config["oss_endpoint"], false);

            \App\Helper\Utils::logger('shangchun');

            $bucket=$config["public"]["bucket"];
            $ossClient->uploadFile($bucket, $file_name, $target  );

            \App\Helper\Utils::logger('wxfile_url1'. $config["public"]["url"]."/".$file_name);

            return  $this->output_succ([
                "file_url" =>  $config["public"]["url"]."/".$file_name,
                "success" =>true,
            ] );

        } catch (OssException $e) {
            \App\Helper\Utils::logger('shibai');
            return $this->output_err("init OssClient fail" );
        }

    }


}
