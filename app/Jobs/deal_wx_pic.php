<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Controllers\Controller;

// 引入鉴权类
use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

require_once(app_path("/Libs/OSS/autoload.php"));
use OSS\OssClient;

use OSS\Core\OssException;


class deal_wx_pic extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $lessonid;
    public $serverid_list;
    // public $serverid;
    public $sever_name;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($lessonid, $serverid_list, $sever_name)
    {
        $this->lessonid        = $lessonid ;
        $this->serverid_list   = $serverid_list ;
        $this->sever_name      = $sever_name ;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \App\Helper\Utils::logger('job JIM');

        //
        $lessonid            =  $this->lessonid ;
        $serverId_str        =  $this->serverid_list;
        $sever_name          =  $this->sever_name;

        $t_test_lesson_subject  = new \App\Models\t_test_lesson_subject();

        $serverIdLists = json_decode($serverId_str,true);
        $alibaba_url   = array();
        $alibaba_url_origi   = array();


        if(count($serverIdLists) == 1){

            $imgStateInfo = $this->savePicToServer($serverIdLists['0']);

            $savePathFile_one = $imgStateInfo['savePathFile'];
            \App\Helper\Utils::logger("savePathFile_one_url:$savePathFile_one");

            $file_name        = $this->put_img_to_alibaba($savePathFile_one);

            $ret = $t_test_lesson_subject->save_pic_compress_url($lessonid, $file_name);
            unlink($savePathFile_one);

        }

        foreach($serverIdLists as $serverId){
            \App\Helper\Utils::logger("xunhuakaishi:$serverId");
            $imgStateInfo = $this->savePicToServer($serverId);

            $savePathFile = $imgStateInfo['savePathFile'];

            $alibaba_url_origi[] = $savePathFile;

            $file_name = $this->put_img_to_alibaba($savePathFile);
            \App\Helper\Utils::logger("JIM urlxx $serverId $savePathFile  $file_name ");

            $alibaba_url[] = $file_name ;
        }


        // 处理压缩图片

        if ( count($serverIdLists)>1) {

            $alibaba_url_str_compress = implode(' ',$alibaba_url_origi);
            // $tar_name = "/".public_path()."/wximg/".md5(date('YmdHis').rand()).".tar.gz";
            $tar_name = "/tmp/".md5(date('YmdHis').rand()).".tar.gz";

            $cmd = "tar -cvzf $tar_name $alibaba_url_str_compress ";

            $ret_tar = \App\Helper\Utils::exec_cmd($cmd);

            \App\Helper\Utils::logger("tar_name:$tar_name");

            $file_name_origi = $this->put_img_to_alibaba($tar_name);

            \App\Helper\Utils::logger("file_name_origi_str_two:$file_name_origi");

            $ret = $t_test_lesson_subject->save_pic_compress_url($lessonid, $file_name_origi);

            unlink($tar_name);
        }

        foreach($alibaba_url_origi as $item_orgi){
            unlink($item_orgi);
        }

        // 处理压缩图片

        $alibaba_url_str = implode(',',$alibaba_url);

        $ret = $t_test_lesson_subject->put_pic_to_alibaba($lessonid, $alibaba_url_str);
    }


    public function savePicToServer($serverId ,$appid_tec= 'wxa99d0de03f407627', $appscript_tec= '61bbf741a09300f7f2fd0a861803f920' ) {

        $accessToken = $this->get_wx_token_jssdk( $appid_tec, $appscript_tec);
        \App\Helper\Utils::logger('other1'.$accessToken);
        \App\Helper\Utils::logger('mediawx'.$serverId);

        // 要存在你服务器哪个位置？
        $route = md5(date('YmdHis').rand());
        $savePathFile = '/tmp/'.$route.'.jpg';
        // $savePathFile = public_path('wximg').'/'.$route.'.jpg';
        $targetName   = $savePathFile;
        \App\Helper\Utils::logger('savePathFileurl'.$savePathFile);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $fp = fopen($targetName,'wb');
        curl_setopt($ch,CURLOPT_URL,"http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$accessToken&media_id=$serverId");
        curl_setopt($ch,CURLOPT_FILE,$fp);
        curl_setopt($ch,CURLOPT_HEADER,0);
        $msg['state'] = curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        $msg['savePathFile'] = $savePathFile;

        return $msg;
    }

    public function put_img_to_alibaba($target){
        try {
            $config=\App\Helper\Config::get_config("ali_oss");
            $file_name=basename($target);

            $ossClient = new OssClient(
                $config["oss_access_id"],
                $config["oss_access_key"],
                $config["oss_endpoint"], false);


            $bucket=$config["public"]["bucket"];
            $ossClient->uploadFile($bucket, $file_name, $target  );

            \App\Helper\Utils::logger('config_url'. $config["public"]["url"]."/".$file_name);

            return $config["public"]["url"]."/".$file_name;

        } catch (OssException $e) {
            \App\Helper\Utils::logger( "init OssClient fail");
            return "" ;
        }

    }





    function outputJson($array){
        global $_GET;
        $json_data=json_encode($array ,JSON_UNESCAPED_UNICODE) ;
        if ( !$json_data) { //数据出问题了
            deal_json_utf8_encode( $array );
            $json_data=json_encode($array ,JSON_UNESCAPED_UNICODE) ;

        }
        if (!$json_data) {
            $json_data=json_encode(["ret" => 6002,  "info"=> "JSON　出错"]  ,JSON_UNESCAPED_UNICODE);
            logger("ERROR  JSON ..");
        }

        if( isset ($_GET['callback']) ) {
            return htmlspecialchars($_GET['callback']) . '(' . $json_data . ')';
        }else{
            return  $json_data ;
        }
    }



        public function get_signature_str( $ref, $appid_tec= 'wxa99d0de03f407627', $appscript_tec= '61bbf741a09300f7f2fd0a861803f920' ){
        $token = $this->get_wx_token_jssdk(  $appid_tec, $appscript_tec);

        $key_arr     = "wx_tec_jssdk_arr_$appid_tec";
        $key_str     = 'wx_tec_jssdk_str_$appid_tec';

        $ret_arr = \App\Helper\Common::redis_get_json($key_arr);
        $now     = time(NULL);

        if (!$ret_arr || !isset($ret_arr["ticket"])  ||  $ret_arr["get_time"]+7000 <  $now ) {

            $jssdk    = $this->get_wx_jsapi_ticket($token);
            $ret_arr  = \App\Helper\Utils::json_decode_as_array($jssdk);
            $ret_arr["get_time"] = time(NULL);
            \App\Helper\Common::redis_set_json($key_arr,$ret_arr );

        }

        $jsapi_ticket = $ret_arr["ticket"];

        $signature = "jsapi_ticket=$jsapi_ticket&noncestr=leo123&timestamp=1494474414"
                   . "&url=$ref" ;

        \App\Helper\Utils::logger( "signature:$signature" );

        $signature_str = sha1($signature);
        return $signature_str;
    }


    public function get_wx_token_jssdk($appid_tec= 'wxa99d0de03f407627', $appscript_tec= '61bbf741a09300f7f2fd0a861803f920' ){

        $wx        = new \App\Helper\Wx();
        return $wx->get_wx_token($appid_tec,$appscript_tec);
    }

    public function get_wx_jsapi_ticket($token){
        $json_jssdk_data=file_get_contents("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$token&type=jsapi ");

        return $json_jssdk_data;
    }

    public function output_succ($arr=null) {
        return $this->outputjson_success($arr );
    }


    function outputjson_success($array=null){
        if (!is_array( $array) ){
            $array=array();
        }

        $array['ret']  = 0;
        $array['info'] = '成功';

        return $this->outputJson( $array );
    }



}
