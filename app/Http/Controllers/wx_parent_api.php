<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;
use Illuminate\Support\Facades\Redis ;
use Illuminate\Support\Facades\Session ;

use App\Jobs\deal_wx_pic;



// 引入鉴权类
use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

require_once  app_path("/Libs/Qiniu/functions.php");

//require(app_path("/Libs/OSS/autoload.php"));
//use OSS\OssClient;
//use OSS\Core\OssException;


require_once(app_path("/Libs/OSS/autoload.php"));
use OSS\OssClient;

use OSS\Core\OssException;



class wx_parent_api extends Controller
{

    use CacheNick;
    var $check_login_flag=false;
    public function __construct() {

        parent::__construct();
        if (! $this->get_parentid()  ) {

            echo $this->output_err("未登录");
            exit;
        }

    }

    public function get_parentid(){
        $parentid= $this->get_in_int_val("_parentid") ? $this->get_in_int_val("_parentid") : session("parentid");
        return $parentid;
    }

    public function get_lesson_info() {
        $parentid = $this->get_parentid();

        // $parentid = 54573;//测试

        $ret_list=$this->t_lesson_info_b2->get_list_by_parent_id($parentid);
        foreach ($ret_list as &$item ) {

            $lesson_num= $item["lesson_num"];
            $lessonid= $item["lessonid"];
            $userid= $item["userid"];
            $item["lesson_name"]= "第{$lesson_num}次试听课";
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);

            $this->cache_set_item_student_nick($item);
            $this->cache_set_item_teacher_nick($item);
            $item["lesson_time"]=\App\Helper\Utils::fmt_lesson_time($item["lesson_start"],$item["lesson_end"]);
            if ( $item["ass_comment_audit"]) {
                $item["teacher_report_url"]="http://www.api.yb1v1.com/show_teacher_comment.html?lessonid=$lessonid&userid=$userid";
            }else{
                $item["teacher_report_url"]="";
            }

            if ($item["parent_report_level"]) {
                $item["parent_report_url"]="http://www.api.yb1v1.com/show_parent_comment.html?lessonid=$lessonid&userid=$userid";
            } else {
                $item['parent_report_url'] = '';
            }

            $item['stu_lesson_pic_arr'] = explode(',',$item['stu_lesson_pic']);

            if (!$item['stu_lesson_pic_arr']['0']) {
                $item['stu_lesson_pic_arr'] = [];
            }

        }

        // dd($ret_list);
        return $this->output_succ(["children_lesson_info"=>$ret_list]);
    }

    public function set_parent_confirm_time () {
        $lessonid = $this->get_in_lessonid();
        $parentid = $this->get_parentid();

        $has_lesson_bool = $this->t_lesson_info_b2->check_lesson_parentid( $lessonid, $parentid );
        if ($has_lesson_bool) {
            return $this->output_err('暂无课程');
        }

        $this->t_test_lesson_subject_sub_list->field_update_list($lessonid,[
            "parent_confirm_time" => time(NULL) ,
        ]);
        return $this->output_succ();
    }

    public function set_lesson_evaluate () {

        $interaction        = $this->get_in_str_val("interaction");
        $class_atmos        = $this->get_in_str_val("class_atmos");
        $tea_standard       = $this->get_in_str_val("tea_standard");
        $tea_style          = $this->get_in_str_val("tea_style");
        $level              = $this->get_in_int_val("level");
        $device_level       = $this->get_in_int_val("device_level");
        $record_info        = $this->get_in_str_val("record_info");
        $lessonid           = $this->get_in_lessonid();
        $device_description = $this->get_in_str_val("device_description");
        $device_record      = $this->get_in_str_val("device_record");

        $parentid = $this->get_parentid();

        $has_lesson_bool = $this->t_lesson_info_b2->check_lesson_parentid( $lessonid, $parentid );
        if ($has_lesson_bool) {
            return $this->output_err('暂无课程');
        }


        $ret = $this->t_teacher_label->evaluate_row_delete($lessonid,1);

        $insert_ret=$this->t_teacher_label->row_insert([
            \App\Models\t_teacher_label::C_label_origin       => 1,
            \App\Models\t_teacher_label::C_interaction        => $interaction,
            \App\Models\t_teacher_label::C_class_atmos        => $class_atmos,
            \App\Models\t_teacher_label::C_tea_standard       => $tea_standard,
            \App\Models\t_teacher_label::C_tea_style          => $tea_style ,
            \App\Models\t_teacher_label::C_add_time           => time(NULL),
            \App\Models\t_teacher_label::C_level              => $level,
            \App\Models\t_teacher_label::C_device_level       => $device_level ,
            \App\Models\t_teacher_label::C_record_info        => $record_info,
            \App\Models\t_teacher_label::C_lessonid           => $lessonid,
            \App\Models\t_teacher_label::C_device_description => $device_description,
            \App\Models\t_teacher_label::C_device_record      => $device_record,
        ]);

        return $this->output_succ();
    }

    public function do_qiniu ( $public_flag )  {


        /*
        'qiniu' => [
            "public" => [
                "url"    => env('QINIU_PUBLIC_URL', 'http://7u2f5q.com2.z0.glb.qiniucdn.com'),
                "bucket" => "ybprodpub",
            ] ,
            "private_url" => [
                "url"    => env('QINIU_PRIVATE_URL', 'http://7tszue.com2.z0.glb.qiniucdn.com'),
                "bucket" => "ybprod",
            ] ,
            "access_key" => "yPmhHAZNeHlKndKBLvhwV3fw4pzNBVvGNU5ne6Px",
            "secret_key" => "gPwzN2_b1lVJAr7Iw6W1PCRmUPZyrGF6QPbX1rxz",

        ],
        */
        $config=\App\Helper\Config::get_config("qiniu");


        // 构建鉴权对象
        $auth = new Auth($config["access_key"], $config["secret_key"]);

        if ($public_flag ) {
            $bucket_info=$config["public" ];
        }else{
            $bucket_info=$config["private_url" ];
        }
        $bucket=$bucket_info["bucket"];

        // 生成上传 Token
        $token = $auth->uploadToken($bucket);


        return  $this->output_succ([
            "bucket" =>  $bucket,
            "token" =>$token,
            "url"=> $bucket_info["url"]
        ] );
    }

    function qiniu_upload_token() {
        $public_flag=$this->get_in_int_val("publish_flag",0 );
        return $this->do_qiniu($public_flag );
    }


    /**
     *老师端微信上传图片
    **/
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

        $ref= $ref?$ref:$_SERVER['HTTP_REFERER'];
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


    public function put_wximg_to_ali_by_serverId(){
        $lessonid     = $this->get_in_int_val('lessonid');
        $serverId_str = $this->get_in_str_val('wx_picid_list');


        $sever_name = $_SERVER["SERVER_NAME"];

        $this->t_test_lesson_subject->put_pic_to_alibaba($lessonid, "");


        dispatch(new deal_wx_pic($lessonid,$serverId_str,$sever_name));

        return $this->output_succ();
    }


    public function savePicToServer($serverId ,$appid_tec= 'wxa99d0de03f407627', $appscript_tec= '61bbf741a09300f7f2fd0a861803f920' ) {
        $accessToken = $this->get_wx_token_jssdk( $appid_tec, $appscript_tec);

        // 要存在你服务器哪个位置？
        $route = md5(date('YmdHis'));
        $savePathFile = public_path('wximg').'/'.$route.'.jpg';
        $targetName   = $savePathFile;
        \App\Helper\Utils::logger('wxlujin1'.$savePathFile);

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
        \App\Helper\Utils::logger('baochun1'.$msg['state'] );

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


            return  $this->output_succ([
                "file_url" =>  $config["public"]["url"]."/".$file_name,
                "success" =>true,
            ]);

        } catch (OssException $e) {
            return $this->output_err("init OssClient fail" );
        }
    }

    public function get_wx_tec_js_config(){
        $ref=$this->get_in_str_val("ref");
        $signature_str = $this->get_signature_str($ref);
        $config = [
            'debug' => 'false',
            'appId' => 'wxa99d0de03f407627', // 必填，公众号的唯一标识
            'timestamp' => '1494474414', // 必填，生成签名的时间戳(随意值)
            'nonceStr'  => 'leo123', // 必填，生成签名的随机串(随意值)
            'signature' => $signature_str,// 必填，签名
            'jsApiList' => [
                "checkJsApi",
                "chooseImage",
                "previewImage",
                "uploadImage",
                "downloadImage",
                "getLocalImgData",
            ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        ];
        return $this->output_succ($config);
    }



    public function parent_report_msg(){

        $log_time     = time(NULL);
        $report_uid   = $this->get_parentid();
        $report_msg   = $this->get_in_str_val('report_msg');
        $obj_account_type    = $this->get_in_str_val('obj_account_type',-1);
        $report_account_type = $this->get_in_str_val('report_account_type',-1);

        $from_type    =  1;
        $from_key_int =  0;

        $report_msg_last = $this->t_user_report->get_last_msg($report_uid);
        if (!empty($report_msg_last) && $report_msg_last['0']['report_msg'] == $report_msg) {
            return $this->output_err("投诉已受理,请勿重复提交..");
        }

         // * 插入到投诉数据库中

        $complaint_type = '1'; // 常规投诉类型
        $account_type   = '1'; // 家长类型
        $complaint_img_url = '';// 图片链接 [此功能暂未开通]

         $ret_info_qc = $this->t_complaint_info->row_insert([
             'complaint_type' => $complaint_type,
             'userid'         => $report_uid,
             'account_type'   => $account_type,
             'add_time'       => $log_time,
             'complaint_info' => $report_msg,
             'complaint_img_url' => $complaint_img_url
         ]);



        $ret_info = $this->t_user_report->row_insert([
            'log_time'     => $log_time,
            'report_uid'   => $report_uid,
            'report_msg'   => $report_msg,
            'obj_account_type'    => $obj_account_type,
            'report_account_type' => $report_account_type,
            'from_key_int'   =>$from_key_int,
            'from_type'      =>$from_type
        ]);

        if ($ret_info) {
            $log_time_date = date('Y-m-d H:i:s',$log_time);
            $opt_nick = '';
            if ($from_type == 1 ){
                $opt_nick= $this->cache_get_parent_nick($report_uid);
            }
            $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";//待处理通知
            $data_msg = [
                "first"     => "家长投诉通知",
                "keyword1"  => "家长投诉待处理",
                "keyword2"  => "家长 $opt_nick 投诉 $report_msg",
                "keyword3"  => "投诉时间 $log_time_date ",
            ];
            $url = '';
            $wx=new \App\Helper\Wx();

            $qc_openid_arr = [
                "orwGAswyJC8JUxMxOVo35um7dE8M", // QC wenbin
                "orwGAsxTqusFBCbI6QqR8oxkwwMg",  // QC yunyan
                "orwGAs0ayobuEtO1YZZhW3Yed2To",  // rolon
                "orwGAs4FNcSqkhobLn9hukmhIJDs",  // ted or erick
            ];

            foreach($qc_openid_arr as $qc_item){
                $wx->send_template_msg($qc_item,$template_id,$data_msg ,$url);
            }



            //反馈家长

            /**
               8GYohyn1V6dmhuEB6ZQauz5ZtmqnnJFy-ETM8yesU3I
               {{first.DATA}}
               提交详情：{{keyword1.DATA}}
               反馈内容：{{keyword2.DATA}}
               时间：{{keyword3.DATA}}
               {{remark.DATA}}
               **
               */

            $template_id_parent = "8GYohyn1V6dmhuEB6ZQauz5ZtmqnnJFy-ETM8yesU3I";//反馈给投诉者
            $data_msg    = [
                "first"     => " 您好,您的反馈我们已经收到!",
                "keyword1"  => " $report_msg",
                "keyword2"  => " 已提交",
                "keyword3"  => " $log_time_date",
                "remark"    => " 我们会在3个工作日内进行处理,感激您的反馈!"
            ];
            $url = '';
            //orwGAswyJC8JUxMxOVo35um7dE8M // QC openid
            // orwGAs_IqKFcTuZcU1xwuEtV3Kek // james
            $parent_openid = $this->t_parent_info->get_wx_openid_by_parentid($report_uid);

            $ret = $wx->send_template_msg($parent_openid,$template_id_parent,$data_msg ,$url);


            return $this->output_succ();
        }
    }

}
