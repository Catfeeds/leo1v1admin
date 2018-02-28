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

require_once app_path('/Libs/TCPDF/tcpdf.php');
require_once app_path('/Libs/TCPDF/config/tcpdf_config.php');


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
        if (!$this->get_parentid()) {
            echo $this->output_err("未登录");
            exit;
        }
    }

    public function get_parentid(){
        $parentid= $this->get_in_int_val("_parentid")?$this->get_in_int_val("_parentid") : session("parentid");
        return $parentid;
    }

    public function get_lesson_info() {
        $parentid = $this->get_parentid();
        $type = $this->get_in_int_val('type',0); // 0: 常规课 2: 试听课
        // $parentid = 54573;//测试
        $now = time();

        if($type == 0){
            $lesson_type_str = '常规课';
            $type_str = "0,1,3";
        }elseif($type == 2){
            $type_str = "2";
            $lesson_type_str = '试听课';
        }else{
            $lesson_type_str = '';
        }

        $ret_list=$this->t_lesson_info_b2->get_list_by_parent_id($parentid,$lessonid=-1,$type_str);
        foreach ($ret_list as &$item ) {
            //判断是否可以申请调课
            if($item['lesson_start']-$now>86400){
                $is_change_flag = 1;
            }else{
                $is_change_flag = 0;
            }

            $item['parent_modify_time'] = $item['parent_modify_time']?$item['parent_modify_time']:0;

            if($item['parent_modify_time'] || $is_change_flag==0){ // 不可以调时间
                $item['is_change'] = 0;
            }else{
                $item['is_change'] = 1;
            }

            $lesson_num= $item["lesson_num"];
            $lessonid= $item["lessonid"];
            $userid= $item["userid"];
            $item["lesson_name"]= "第 $lesson_num 次 $lesson_type_str ";
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);

            $this->cache_set_item_student_nick($item);
            $this->cache_set_item_teacher_nick($item);
            $item["lesson_time"]=\App\Helper\Utils::fmt_lesson_time($item["lesson_start"],$item["lesson_end"]);
            if ( $item["ass_comment_audit"] || $item['tea_rate_time']>0) {
                $item["teacher_report_url"]="http://www.api.leo1v1.com/show_teacher_comment.html?lessonid=$lessonid&userid=$userid";
            }else{
                $item["teacher_report_url"]="";
            }

            if ($item["parent_report_level"]) {
                $item["parent_report_url"]="http://www.api.leo1v1.com/show_parent_comment.html?lessonid=$lessonid&userid=$userid";
            } else {
                $item['parent_report_url'] = '';
            }

            $item['stu_lesson_pic_arr'] = explode(',',$item['stu_lesson_pic']);

            if (!$item['stu_lesson_pic_arr']['0']) {
                $item['stu_lesson_pic_arr'] = [];
            }

        }

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

        if($insert_ret){
            /**
               向老师发送微信推送
               课程评价通知
               x月x日
               xx:xx的xx课xx同学已经提交了课程评价
               课程：{时间课程名称}
               时间：xx-xx xx:xx~xx:xx
               请登录老师端查看详情，谢谢！
               D5MRwT7Cq-Eri19auVEBuR-_LMJprScEigWab7Eox2A

               {{first.DATA}}
               课程：{{keyword1.DATA}}
               时间：{{keyword2.DATA}}
               {{remark.DATA}}
             **/

            $lesson_info = $this->t_lesson_info_b2->get_lesson_info_by_lessonid($lessonid);

            $subject_str = E\Esubject::get_desc($lesson_info['subject']);

            $template_id_teacher = 'D5MRwT7Cq-Eri19auVEBuR-_LMJprScEigWab7Eox2A';

            $data_tea = [
                'first'    => date('H:i',$lesson_info['lesson_start'])."的 $subject_str 课 ".$lesson_info['stu_nick']."同学已经提交了课程评价",
                'keyword1' => $subject_str,
                'keyword2' => date('m-d H:i',$lesson_info['lesson_start']).' ~ '.date('H:i', $lesson_info['lesson_end']),
                'remark'   => "请登录老师端查看详情，谢谢！",
            ];

            \App\Helper\Utils::send_teacher_msg_for_wx($lesson_info['wx_openid'],$template_id_teacher, $data_tea,'');

        }

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
            \App\Helper\Utils::logger("JSSDK RET_ARR:". json_encode( $ret_arr ) );

            if ($ret_arr["errcode"]==45009 ) {
                return "";
            }

            $ret_arr["get_time"] = time(NULL);
            \App\Helper\Common::redis_set_json($key_arr,$ret_arr );


        }

        $jsapi_ticket = @$ret_arr["ticket"];
        if (!$jsapi_ticket) {
            return "";
        }

        if(isset($_SERVER["HTTP_REFERER"])){
            $http_ref = $_SERVER["HTTP_REFERER"];
        }else{
            $http_ref = "";
        }
        $ref= $ref?$ref:$http_ref;
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
        if (!$signature_str) {
            return $this->output_err("微信限制")  ;
        }

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
            return $this->output_err("您的反馈投诉已收到,谢谢您的反馈!");
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
            $url = "http://admin.leo1v1.com/user_manage/complaint_department_deal_parent";
            $wx=new \App\Helper\Wx();

            $qc_openid_arr = [
                "orwGAswflHkLg-4PgNuJwsQZZKFE", // 沈玉莹
                "orwGAs4dM5Z-nc2VKAnG1oP0VfuQ", //谢汝毅
                "orwGAs08kfcXpQ4HZxeNV7_UqyBE", //班洁
                "orwGAs4HRuV3DIrMqWLazE0WKStY", //王皎嵘
                "orwGAs0oIUoS4fyZ2rUnMCaRro4Y",//王洪艳
                "orwGAs1KiLCE4Gdp4IZ07_6lCjpU", //童宇周
                "orwGAs16c87dXRwE5b5vE9N6zZCk", // 孙佳旭
                "orwGAs5S7U5N-FDA9ydoTpofkpCU", // 郑璞
                "orwGAswyJC8JUxMxOVo35um7dE8M", // QC wenbin
                "orwGAsyyvy1YzV0E3mmq7gBB3rms", // QC 李珉劼
                "orwGAs2Cq6JQKTqZghzcv3tUE5dU", // 王浩鸣
                "orwGAs4-nyzZL2rAdqT2o63GvxG0", // 郭冀江
                "orwGAs0ayobuEtO1YZZhW3Yed2To",  // rolon
                "orwGAs4FNcSqkhobLn9hukmhIJDs",  // ted or erick
                "orwGAs1H3MQBeo0rFln3IGk4eGO8",  // sunny
                "orwGAs87gepYCYKpau66viHluRGI",  // 傅文莉
                // "orwGAs6J8tzBAO3mSKez8SX-DWq4"   // 孙瞿

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
                "remark"    => " 我们会在3个工作日内进行处理,感谢您的反馈!"
            ];
            $url = '';
            //orwGAswyJC8JUxMxOVo35um7dE8M // QC openid
            // orwGAs_IqKFcTuZcU1xwuEtV3Kek // james
            $parent_openid = $this->t_parent_info->get_wx_openid_by_parentid($report_uid);

            $ret = $wx->send_template_msg($parent_openid,$template_id_parent,$data_msg ,$url);


            return $this->output_succ();
        }
    }

    //此处处理家长调整时间功能

    public function get_vaild_change_time(){ // 获取可调时间段
        $lessonid = $this->get_in_int_val('lessonid');

        $lesson_end = $this->t_lesson_info_b2->get_lesson_end($lessonid);
        $lesson_start = $this->t_lesson_info_b2->get_lesson_start($lessonid);
        $start = time(NULL)+86400;
        $end   = $lesson_end+3*86400;

        if($start>$lesson_start){
            return $this->output_err('只能调整24小时后的课程．．');
        }

        $arr = [
            "start" => $start,
            "end"   => $end
        ];

        return $this->output_succ(['data'=>$arr]);
    }

    public function get_teacher_free_time_by_lessonid(){ // 获取老师和学生的上课时间 ／／１０２４
        $lessonid = $this->get_in_int_val('lessonid');

        $lesson_start = $this->t_lesson_info_b2->get_lesson_start($lessonid);
        $lesson_end   = $this->t_lesson_info_b2->get_lesson_end($lessonid);

        $filter_lesson_time_start = time(NULL)+86400;
        $filter_lesson_time_end   = $lesson_end+3*86400;

        if($lesson_start<$filter_lesson_time_start){
            return $this->output_err('课程开始时间需在申请时间的24小时后....');
        }

        $teacher_lesson_time = $this->t_lesson_info_b2->get_teacher_time_by_lessonid($lessonid, $filter_lesson_time_start, $filter_lesson_time_end);
        $student_lesson_time = $this->t_lesson_info_b2->get_student_lesson_time_by_lessonid($lessonid, $filter_lesson_time_start, $filter_lesson_time_end);
        $time = ['start'=>$filter_lesson_time_start,'end'=>$filter_lesson_time_end];

        $all_tea_stu_lesson_time = array_merge($teacher_lesson_time, $student_lesson_time);

        return $this->output_succ(['data'=>$all_tea_stu_lesson_time,'start'=>$filter_lesson_time_start,'end'=>$filter_lesson_time_end,'lesson_start'=>$lesson_start,'lesson_end'=>$lesson_end]);
    }


    public function set_modify_lesson_time_by_parent(){ // 1025 // 家长设置上课时间段
        $parent_modify_time   = $this->get_in_str_val('parent_modify_time');
        $parent_modify_remark = $this->get_in_str_val('parent_modify_remark');
        $lessonid = $this->get_in_int_val('lessonid');

        $lesson_start_time = $this->t_lesson_info_b2->get_lesson_start($lessonid);
        $lesson_end_time   = $this->t_lesson_info_b2->get_lesson_end($lessonid);
        $stu_nick          = $this->t_student_info->get_stu_nick_by_lessonid($lessonid);

        // 家长只能发起一次调课申请
        $check_flag = $this->t_lesson_time_modify->get_parent_modify_time($lessonid);
        if($check_flag){
            return $this->output_err('您好,本节课已经申请,请勿重复申请!');
        }

        $ret = $this->t_lesson_time_modify->row_insert([
            'lessonid'             => $lessonid,
            'parent_modify_time'   => $parent_modify_time,
            'parent_modify_remark' => $parent_modify_remark,
            'parent_deal_time'     => time(NULL),
            'backstage_type'       => 1
        ]);

        if($ret){
            // 发送微信推送[家长]
            $parent_wx_openid = $this->t_parent_info->get_parent_wx_openid($lessonid);
            // $parent_wx_openid = 'orwGAs8nLI4DEU2jsXRKDLWY0n5w';

            $lesson_start_date = date('Y-m-d H:i',$lesson_start_time );
            $lesson_end_date   = date('H:i',$lesson_end_time );
            // $result = "原因:{".$parent_modify_remark."}";
            $result = "";

            if(!$parent_modify_remark){
                $result = '';
            }

            $day_time = date('Y-m-d H:i:s');
            $wx     = new \App\Helper\Wx();
            //
            // $url = "http://wx-teacher-web.leo1v1.com/adjust-progress?lessonid=".$lessonid;
            $url ="http://wx-parent.leo1v1.com/wx_parent/adjust_progress?lessonid=".$lessonid;

            $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";//待处理通知
            $data_msg = [
                "first"     => " 调课申请受理中",
                "keyword1"  => " 调换上课时间",
                "keyword2"  => " 原上课时间: $lesson_start_date ~ $lesson_end_date ,申请受理中,请稍等!",
                "keyword3"  => " $day_time",
                "remark"    => " 详细进度稍后将以推送的形式发送给您,请注意查看!",
            ];
            $ret_parent = $wx->send_template_msg($parent_wx_openid,$template_id,$data_msg ,$url);


            // 发送微信推送[老师]
            $teacher_wx_openid = $this->t_teacher_info->get_wx_openid_by_lessonid($lessonid);
            $teacher_url = "http://wx-teacher-web.leo1v1.com/handle-adjust/index.html?lessonid=".$lessonid; //待定
            //$teach http://wx-teacher-web.leo1v1.com/handle-adjust/index.html
            $template_id_teacher  = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
            $data['first']      = " 调课申请 ";
            $data['keyword1']   = " 您的学生".$stu_nick."的家长申请修改上课时间";
            $data['keyword2']   = " 原上课时间: $lesson_start_date ~ $lesson_end_date ;$result";
            $data['keyword3']   = "$day_time";
            $data['remark']     = "请点击详情查看家长勾选的时间并进行处理!";
            $ret_teacher = \App\Helper\Utils::send_teacher_msg_for_wx($teacher_wx_openid,$template_id_teacher, $data,$teacher_url);

            return $this->output_succ();
        }else{
            return $this->output_err('提交失败,请稍后再试..');
        }

    }


    public function get_modify_status(){ //1026 // 获取推送的状态 [详情]
        $lessonid = $this->get_in_int_val('lessonid');
        $lesson_time_arr = [];
        $lesson_time     = $this->t_lesson_info_b2->get_lesson_time($lessonid);

        $original_time = $this->t_lesson_time_modify->get_original_time_by_lessonid($lessonid);

        if($original_time ){
            $original_time_arr = explode(',',$original_time);
            $lesson_time_arr['lesson_time_old'] = date('Y年m月d日 H:i:s',$original_time_arr[0]).'-'.date('H:i:s',$original_time_arr[1]);
            $lesson_time_arr['lesson_time_new'] = date('Y年m月d日 H:i:s',$lesson_time[0]['lesson_start']).' - '.date('H:i:s',$lesson_time[0]['lesson_end']);
        }else{
            $lesson_time_arr['lesson_time_old'] = date('Y年m月d日 H:i:s',$lesson_time[0]['lesson_start']).' - '.date('H:i:s',$lesson_time[0]['lesson_end']);
            $lesson_time_arr['lesson_time_new'] = '';
        }

        $lesson_time_arr['status']       = 0;
        $lesson_modify_arr = $this->t_lesson_time_modify->get_parent_modify_time_by_lessonid($lessonid);

        if($lesson_modify_arr['parent_modify_time']){
            if(  $lesson_modify_arr['is_modify_time_flag'] == 0  ){
                $lesson_time_arr['status'] = 1;// 已提交
            }elseif( $lesson_modify_arr['is_modify_time_flag'] == 1){
                $lesson_time_arr['status'] = 2;// 已完成
            }elseif( $lesson_modify_arr['is_modify_time_flag'] == 2){
                $lesson_time_arr['status'] = 3; // 被拒绝
            }
        }

        $lesson_time_arr['ass_phone']     = $this->t_assistant_info->get_ass_phone_by_lessonid($lessonid);
        $lesson_time_arr['seller_phone']  = $this->t_lesson_info_b2->get_seller_phone_by_lessonid($lessonid);

        return $this->output_succ(['data'=>$lesson_time_arr]);
    }


    public function set_lesson_time_by_parent(){ // 1032 // 家长同意老师申请的时间
        $lessonid = $this->get_in_int_val('lessonid');
        $lesson_time_str = $this->get_in_str_val('lesson_time_str');

        $lesson_time_arr = explode(',',$lesson_time_str);

        $lesson_old_start = $this->t_lesson_info_b2->get_lesson_start($lessonid);
        $lesson_old_end   = $this->t_lesson_info_b2->get_lesson_end($lessonid);
        $original_lesson_time = $lesson_old_start.','.$lesson_old_end;

        $ret1 = $this->t_lesson_info_b2->field_update_list($lessonid,[
            'lesson_start'  => $lesson_time_arr[0],
            'lesson_end'    => $lesson_time_arr[1],
        ]);

        $ret2 = $this->t_lesson_time_modify->field_update_list($lessonid,[
            'is_modify_time_flag' =>1, // 0:默认值 未设置 1:成功 2:拒绝
            'original_time' => $original_lesson_time,
        ]);

        if($ret1 && $ret2){
            $is_teacher_agree = 1; //家长同意
            $this->send_wx_msg_by_agree($lessonid,$is_teacher_agree);
            return $this->output_succ();
        }else{
            return $this->output_err('提交失败,请稍后重试....');
        }
    }



    public function send_wx_msg_by_agree($lessonid,$is_teacher_agree){ // 家长或老师 同意了时间后 发送微信推送

        $teacher_nick      = $this->t_teacher_info->get_teacher_nick_lessonid($lessonid);
        $lesson_name       = $this->t_lesson_info_b2->get_lesson_name($lessonid);
        $stu_nick          = $this->t_student_info->get_stu_nick_by_lessonid($lessonid);
        $lesson_time_arr   = $this->t_lesson_info_b2->get_modify_lesson_time($lessonid);
        $lesson_new_time   = date('m月d日',$lesson_time_arr[0]).' - '.date('H:i:s',$lesson_time_arr[1]);

        $lesson_old_time_str  = $this->t_lesson_time_modify->get_original_time_by_lessonid($lessonid);
        $lesson_old_time_arr  = explode(',',$lesson_old_time_str);
        $lesson_old_time      = date('m月d日 H:i:s',$lesson_old_time_arr[0]).' - '.date('H:i:s',$lesson_old_time_arr[1]);

        $lesson_type = $this->t_lesson_info_b2->get_lesson_type($lessonid);

        if($is_teacher_agree == 1){ // 家长同意
            $data['first']        = "$teacher_nick 老师您好,  $stu_nick 的家长同意将时间做出如下修改,原课程时间: $lesson_old_time ,最终时间调整至 $lesson_new_time ";

            $data_parent['first'] = "$stu_nick 的家长您好,您发起的调课申请更改如下: 原课程时间: $lesson_old_time ; 最终时间调整至 $lesson_new_time ";

            $data_leo['first'] = " $teacher_nick  老师申请调整 $stu_nick 的家长发起的调课申请,已获得 $stu_nick 家长的同意,原课程时间 $lesson_old_time ,最终时间调整至 $lesson_new_time ";
        }elseif($is_teacher_agree == 2){ //老师同意
            $data['first']      = " $teacher_nick 老师您好,您于".$lesson_old_time."的".$lesson_name.",已调整至".$lesson_new_time." ";

            $data_parent['first'] = "$stu_nick 的家长您好,您的调课申请已经得到 $teacher_nick 老师的同意, $lesson_old_time 已调整至 $lesson_new_time ";

            $data_leo['first']    = "$stu_nick 的家长的调课申请,已经得到 $teacher_nick 老师的同意, $lesson_old_time 已经调整至 $lesson_new_time ";

        }


        // 给老师推送结果

        /**
           {{first.DATA}}
           课程名称：{{keyword1.DATA}}
           课程时间：{{keyword2.DATA}}
           学生姓名：{{keyword3.DATA}}
           {{remark.DATA}}
           //J57C9QLB-K3SeKgIwdvBMz1RfjUinhwWsN3lEM-Xo5o
           **/

        $teacher_wx_openid = $this->t_teacher_info->get_wx_openid_by_lessonid($lessonid);
        $teacher_url = ''; //待定
        $template_id_teacher  = "J57C9QLB-K3SeKgIwdvBMz1RfjUinhwWsN3lEM-Xo5o";
        $data['keyword1']   = $lesson_name;
        $data['keyword2']   = $lesson_new_time;
        $data['keyword3']   = $stu_nick;
        $data['remark']     = "感谢老师的支持!";

        \App\Helper\Utils::send_teacher_msg_for_wx($teacher_wx_openid,$template_id_teacher, $data,$teacher_url);


        // 给家长推送结果

        /**
           {{first.DATA}}
           课程名称：{{keyword1.DATA}}
           课程时间：{{keyword2.DATA}}
           学生姓名：{{keyword3.DATA}}
           {{remark.DATA}}
           // Wch1WZWbJvIckNJ8kA9r7v72nZeXlHM2cGFNLevfAQI

           **/
        $parent_wx_openid = $this->t_parent_info->get_parent_wx_openid($lessonid);
        $parent_template_id      = 'Wch1WZWbJvIckNJ8kA9r7v72nZeXlHM2cGFNLevfAQI';
        $data_parent = [
            'keyword1' =>"$lesson_name",
            'keyword2' => "$lesson_new_time",
            'keyword3' => "$stu_nick",
            'remark'   => '请注意调整后的时间,感谢家长的支持!'
        ];
        $url_parent = '';
        $wx = new \App\Helper\Wx();
        $wx->send_template_msg($parent_wx_openid, $parent_template_id, $data_parent, $url_parent);


        // 给助教// 销售 // 教务 推送结果

        // $wx_openid_arr[0] = $this->t_lesson_info_b2->get_ass_wx_openid($lessonid);
        $wx_openid_arr[1] = $this->t_lesson_info_b2->get_seller_wx_openid($lessonid);
        $wx_openid_arr[2] = $this->t_test_lesson_subject_sub_list->get_jiaowu_wx_openid($lessonid);


        if($lesson_type == 0){
            $wx_openid_arr[3] = $this->t_lesson_info_b2->get_ass_wx_openid($lessonid);
        }elseif($lesson_type == 2){
            $wx_openid_arr[3] = $this->t_test_lesson_subject_require->get_cur_require_adminid_by_lessonid($lessonid);
        }



        $data_leo = [
            'keyword1' => "$lesson_name",
            'keyword2' => "$lesson_new_time",
            'keyword3' => "$stu_nick",
            'remark'   => "请注意调整您的时间安排!"
        ];

        $url_leo = '';

        foreach($wx_openid_arr as $item_openid ){
            $wx->send_template_msg($item_openid, $parent_template_id, $data_leo, $url_leo);
        }

    }


    public function send_wx_msg_by_keep($lessonid,$is_teacher_keep){ // 家长或老师维持原有时间 发送微信推送

        $lesson_start_time = $this->t_lesson_info_b2->get_lesson_start($lessonid);
        $lesson_start_date = date('m月d日',$lesson_start_time );
        $stu_nick          = $this->t_student_info->get_stu_nick_by_lessonid($lessonid);
        $teacher_nick      = $this->t_teacher_info->get_teacher_nick_lessonid($lessonid);

        $wx = new \App\Helper\Wx();
        $day_date = date('Y-m-d H:i:s');

        if($is_teacher_keep == 1){ // 1:家长

            $teacher_keep_original_remark = $this->t_lesson_time_modify->get_teacher_keep_original_remark($lessonid);
            $result = "原因: $teacher_keep_original_remark";

            $first    = "您的学生 $stu_nick 的家长申请修改 $lesson_start_date 上课时间被 $teacher_nick 老师拒绝!";
            $keyword1 = "老师拒绝调课申请";
            $keyword2 = "原上课时间: $lesson_start_date ; $result";

            // 给家长推送结果
            $parent_wx_openid    = $this->t_parent_info->get_parent_wx_openid($lessonid);
            $parent_template_id  = '9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU';
            $data_parent = [
                'first' => "您已拒绝 $teacher_nick 老师要求调换您发起的换时间申请",
                'keyword1' =>"拒绝调课申请",
                'keyword2' => "原上课时间: $lesson_old_date ,您已拒绝",
                'keyword3' => "$day_date",
                'remark'   => "详细进度稍后将以推送的形式发给您,请注意查看!"
            ];
            $url_parent = '';
            $wx->send_template_msg($parent_wx_openid, $parent_template_id, $data_parent, $url_parent);

        } elseif($is_teacher_keep == 2){ // 2:老师
            //推送给老师

            $parent_keep_original_remark = $this->t_lesson_time_modify->get_parent_keep_original_remark($lessonid);
            // $result = "原因: $parent_keep_original_remark ";
            $result = " ";


            $first    = "您的学生 $stu_nick 的家长申请修改 $lesson_start_date 上课时间被 $teacher_nick 老师拒绝!";
            $keyword1 = "老师拒绝调课申请";

            $keyword2 = "原上课时间: $lesson_start_date ; $result";

            $teacher_wx_openid = $this->t_teacher_info->get_wx_openid_by_lessonid($lessonid);
            $teacher_url = ''; //待定
            $template_id_teacher  = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
            $data['first']      = " 您的学生 $stu_nick 的家长申请修改 $lesson_start_date 上课时间,您已拒绝! ";
            $data['keyword1']   = " 拒绝调课申请";
            $data['keyword2']   = " 原上课时间:".$lesson_start_date.";您已拒绝";
            $data['keyword3']   = "$day_date";
            $data['remark']     = "详细进度稍后将以推送的形式发给您,请注意查收!";
            \App\Helper\Utils::send_teacher_msg_for_wx($teacher_wx_openid,$template_id_teacher, $data,$teacher_url);
        }

        //推送给 助教 / 咨询
        $parent_template_id  = '9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU';
        // $wx_openid_arr[0]    = $this->t_lesson_info_b2->get_ass_wx_openid($lessonid);

        $lesson_type = $this->t_lesson_info_b2->get_lesson_type($lessonid);
        if($lesson_type == 0){
            $wx_openid_arr[0] = $this->t_lesson_info_b2->get_ass_wx_openid($lessonid);
        }elseif($lesson_type == 2){
            $wx_openid_arr[0] = $this->t_test_lesson_subject_require->get_cur_require_adminid_by_lessonid($lessonid);
        }
        $wx_openid_arr[1]    = $this->t_lesson_info_b2->get_seller_wx_openid($lessonid);


        $data_leo = [
            'first'    => "$first",
            'keyword1' => "$keyword1",
            'keyword2' => "$keyword2",
            'keyword3' => "$day_date",
            'remark'   => "请尽快联系家长和老师进行处理!"
        ];
        $url_leo = '';

        foreach($wx_openid_arr as $item_openid ){
            $wx->send_template_msg($item_openid, $parent_template_id, $data_leo, $url_leo);
        }

    }


    public function deal_keep_lesson_time_by_jiaowu_send_wx_msg($lessonid){ // 教务处理完成后 推送微信消息

        $teacher_nick      = $this->t_teacher_info->get_teacher_nick_lessonid($lessonid);
        $stu_nick          = $this->t_student_info->get_stu_nick_by_lessonid($lessonid);

        $lesson_old_time_str  = $this->t_lesson_time_modify->get_original_time_by_lessonid($lessonid);
        $lesson_old_time_arr  = explode(',',$lesson_old_time_str);
        $lesson_old_time      = date('m月d日 H:i:s',$lesson_old_time_arr[0]);

        $lesson_time_arr   = $this->t_lesson_info_b2->get_modify_lesson_time($lessonid);
        $lesson_new_time   = date('m月d日',$lesson_time_arr[0]).'-'.date('H:i:s',$lesson_time_arr[1]);

        //推送给老师
        $teacher_wx_openid = $this->t_teacher_info->get_wx_openid_by_lessonid($lessonid);
        $teacher_url = ''; //待定
        $template_id_teacher  = "J57C9QLB-K3SeKgIwdvBMz1RfjUinhwWsN3lEM-Xo5o";
        $data['first']      = " $teacher_nick 老师您好,由$stu_nick 的家长发起的调课申请最终更改如下, 原课程时间: $lesson_old_time   , 最终时间调整至".date('m月d日',$lesson_time_arr[0]);
        $data['keyword1']   = "  $lesson_name ";
        $data['keyword2']   = "$lesson_new_time";
        $data['keyword3']   = " $stu_nick";
        $data['remark']     = "请注意调整后的时间,感谢老师的支持!";
        \App\Helper\Utils::send_teacher_msg_for_wx($teacher_wx_openid,$template_id_teacher, $data,$teacher_url);

        // 给家长推送结果

        /**
           {{first.DATA}}
           课程名称：{{keyword1.DATA}}
           课程时间：{{keyword2.DATA}}
           学生姓名：{{keyword3.DATA}}
           {{remark.DATA}}
           // Wch1WZWbJvIckNJ8kA9r7v72nZeXlHM2cGFNLevfAQI

           **/
        $parent_wx_openid = $this->t_parent_info->get_parent_wx_openid($lessonid);
        $parent_template_id      = 'Wch1WZWbJvIckNJ8kA9r7v72nZeXlHM2cGFNLevfAQI';
        $data_parent = [
            'first' => "$stu_nick 的家长您好, 您的调课申请经过协商已经做出修改, 原课程时间: $lesson_old_time   , 最终时间调整至".date('m月d日',$lesson_time_arr[0]),
            'keyword1' =>"$lesson_name",
            'keyword2' => "$lesson_new_time",
            'keyword3' => "$stu_nick",
            'remark'   => "请注意调整后的时间,感谢家长的支持!"
        ];
        $url_parent = '';
        $wx = new \App\Helper\Wx();
        $wx->send_template_msg($parent_wx_openid, $parent_template_id, $data_parent, $url_parent);


        // 给助教// 销售 // 教务 推送结果
        $parent_template_id      = 'Wch1WZWbJvIckNJ8kA9r7v72nZeXlHM2cGFNLevfAQI';

        // $wx_openid_arr[0] = $this->t_lesson_info_b2->get_ass_wx_openid($lessonid);

        $lesson_type = $this->t_lesson_info_b2->get_lesson_type($lessonid);
        if($lesson_type == 0){
            $wx_openid_arr[0] = $this->t_lesson_info_b2->get_ass_wx_openid($lessonid);
        }elseif($lesson_type == 2){
            $wx_openid_arr[0] = $this->t_test_lesson_subject_require->get_cur_require_adminid_by_lessonid($lessonid);
        }

        $wx_openid_arr[1] = $this->t_lesson_info_b2->get_seller_wx_openid($lessonid);
        $wx_openid_arr[2] = $this->t_test_lesson_subject_sub_list->get_jiaowu_wx_openid($lessonid);

        $data_leo = [
            'first'    => "由 $stu_nick 的家长发起的调课申请已经处理完成, 原课程时间: $lesson_old_time   , 最终时间调整至".date('m月d日',$lesson_time_arr[0]),
            'keyword1' => "$lesson_name",
            'keyword2' => "$lesson_new_time",
            'keyword3' => "$stu_nick",
            'remark'   => "请注意调整您的时间安排!"
        ];
        $url_leo = '';
        foreach($wx_openid_arr as $item_openid ){
            $wx->send_template_msg($item_openid, $parent_template_id, $data_leo, $url_leo);
        }

    }



    // 家长微信端上传试卷

    public function get_all_stu_info(){
        $parentid = $this->get_parentid();

        $student_info = $this->t_student_info->get_stu_info_by_parentid($parentid);

        return $this->output_succ(['data'=>$student_info]);
    }


    public function input_student_score (){ //家长录入学生成绩
        $userid           = $this->get_in_int_val("userid");
        $serverId_list    = $this->get_in_str_val('serverids');
        $create_time      = time();
        $create_adminid   = $this->get_parentid();
        $subject          = $this->get_in_int_val("subject");
        $stu_score_type   = $this->get_in_int_val("stu_score_type");
        $stu_score_time   = strtotime($this->get_in_str_val("stu_score_time"));
        $score            = $this->get_in_str_val("score");
        $rank             = $this->get_in_str_val("rank");
        $file_url         = $this->get_in_str_val("file_url");
        $semester         = $this->get_in_int_val("semester");
        $total_score      = $this->get_in_int_val("total_score");
        $grade            = $this->get_in_int_val("grade");
        $grade_rank       = $this->get_in_str_val("grade_rank");
        $rank_arr = explode("/",$grade_rank);
        $rank_now = $rank_arr[0];
        $grade_rank_last = $this->t_student_score_info->get_last_grade_rank($subject,$userid);
        if( $score > $total_score){
            return $this->output_err("成绩输入有误!");
        }

        if($grade_rank_last  && $rank_now != ''){
            $grade_rank_last = $grade_rank_last[0]["grade_rank"];
            $rank_last_arr = explode("/",$grade_rank_last);
            $rank_last = $rank_last_arr[0];
            if($rank_last - $rank_now >= 0){
                $rank_up = $rank_last-$rank_now;
                $rank_down = '';
            }else{
                $rank_up = '';
                $rank_down = $rank_now - $rank_last;
            }
        }else{
            $rank_up = '';
            $rank_down = '';
        }

        $appid     = config('admin')['wx']['appid'];
        $appscript = config('admin')['wx']['appsecret'];
        $sever_name = $_SERVER["SERVER_NAME"];

        $file_url = '';
        if($serverId_list){
            $ret_arr = \App\Helper\Utils::deal_feedback_img($serverId_list,$sever_name, $appid, $appscript);
            $file_url = $ret_arr['alibaba_url_str'];
        }

        $score = $score*10;
        $ret_info = $this->t_student_score_info->row_insert([
            "userid"                => $userid,
            "create_time"           => $create_time,
            "create_adminid"        => $create_adminid,
            "subject"               => $subject,
            "stu_score_type"        => $stu_score_type,
            "stu_score_time"        => $stu_score_time,
            "score"                 => $score,
            "rank"                  => $rank,
            "file_url"              => $file_url,
            "semester"              => $semester,
            "total_score"           => $total_score,
            "grade"                 => $grade,
            "grade_rank"            => $grade_rank,
            "rank_up"               => $rank_up,
            "rank_down"             => $rank_down,
            "admin_type"            => 1
        ],false,false,true);



        if($ret_info){
            $send_openid = $this->t_student_info->get_ass_openid($userid);

            if(!$send_openid){
                $send_openid = $this->t_seller_student_new->get_seller_openid($userid);
            }

            \App\Helper\Utils::logger(" seller_send_upload4: $send_openid userid:$userid" );

            $stu_nick = $this->cache_get_student_nick($userid);

            $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";//待办事项提醒
            $data_msg = [
                "first"     => "$stu_nick 同学的家长上传了学生成绩",
                "keyword1"  => "成绩录入提醒",
                "keyword2"  => "点击详情进行查看",
                "keyword3"  => date('Y-m-d H:i:s'),
            ];
            $url = 'http://admin.leo1v1.com/stu_manage/score_list?sid='.$userid;
            $wx = new \App\Helper\Wx();


            $wx->send_template_msg($send_openid,$template_id,$data_msg ,$url);

            return $this->output_succ();
        }else{
            return $this->output_err('成绩录入失败,请稍后重试!');
        }

    }


    public function get_history_for_stu_score_type(){ // 获取学生的历史记录
        $userid = $this->get_in_int_val('userid');
        $stu_score_list = $this->t_student_score_info->get_stu_score_list_for_score_type($userid);

        foreach($stu_score_list as &$item){
            $item['score'] = $item['score']/10;
        }

        return $this->output_succ(['data'=>$stu_score_list]);
    }

    public function get_score_info(){ // 获取成绩详情
        $id         = $this->get_in_int_val('scoreid');
        $score_info = $this->t_student_score_info->get_score_info($id);
        return $this->output_succ(['data'=>$score_info]);
    }


    public function deal_paper_upload(){ // 处理家长上传[试卷 | 作业]
        $serverId_list = $this->get_in_str_val('serverids');

        $lessonid = $this->get_in_int_val('lessonid');

        $type = $this->get_in_int_val('type'); // 课程类型

        $paper_type = $this->get_in_int_val('paper_type'); // 试卷类型 //1 : 代表试卷 2: 代表作业
        // 家长微信号
        $appid     = 'wx636f1058abca1bc1';
        $appscript = '756ca8483d61fa9582d9cdedf202e73e';
        $sever_name = $_SERVER["SERVER_NAME"];

        $ret_arr = \App\Helper\Utils::deal_feedback_img($serverId_list,$sever_name, $appid, $appscript);

        $img_arr = explode(',',$ret_arr['alibaba_url_str']);
        $homework_pdf_url = \App\Helper\Utils::img_to_pdf($img_arr);

        if($type == 2){ // 试听课
            if($paper_type == 1){ // 存放试卷
                $ret = $this->t_test_lesson_subject->update_paper($lessonid,$ret_arr['alibaba_url_str'],$ret_arr['file_name_origi']);
            }elseif($paper_type == 2){ // 存放作业
                $ret = $this->t_test_lesson_subject->update_homework($lessonid,$homework_pdf_url);
            }
        }else{ // 常规课
            if($paper_type == 1){ // 存放试卷
                $ret = $this->t_lesson_info_b2->field_update_list($lessonid,[
                    "stu_test_paper" => $ret_arr['alibaba_url_str'],
                    "stu_test_paper_compress" => $ret_arr['file_name_origi']
                ]);
            }elseif($paper_type == 2){ // 存放作业
                $ret = $this->t_lesson_info_b2->field_update_list($lessonid,[
                    "stu_cw_url" => $homework_pdf_url // 作业包
                ]);
            }
        }

        if($ret){
            if($type == 2){
                $lesson_info = $this->t_lesson_info_b2->get_lesson_info_by_lessonid($lessonid);
                $subject_str = E\Esubject::get_desc($lesson_info['subject']);
                $first = date('m月d日 H:i',$lesson_info['lesson_start'])."的 $subject_str 课程，".$lesson_info['stu_nick']."同学的家长已经上传了";

                $template_id_teacher = 'rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o';
                if($paper_type == 1){ // 存放试卷

                    /**
                       待办事项提醒
                       x月x日

                       x月x日xx：xx的xx课程，xx同学的家长已经上传了试卷
                       待办主题：家长已上传试卷
                       待办内容：xx同学的试卷已上传
                       日期：2017/06/01
                       请尽快登录老师后台，进行查看。

                       rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
                       {{first.DATA}}
                       待办主题：{{keyword1.DATA}}
                       待办内容：{{keyword2.DATA}}
                       日期：{{keyword3.DATA}}
                       {{remark.DATA}}
                    **/
                    $first .='试卷';
                    $keyword1 = '家长已上传试卷';
                    $keyword2 = $lesson_info['stu_nick']."同学的试卷已上传";

                }elseif($paper_type == 2){ //　存放作业
                    $first .='作业';
                    $keyword1 = '家长已上传作业';
                    $keyword2 = $lesson_info['stu_nick']."同学的作业已上传";
                }

                $data_msg = [
                    'first'    => "$first",
                    'keyword1' => "$keyword1",
                    'keyword2' => "$keyword2",
                    'keyword3' => date("Y-m-d"),
                    'remark'   => "请尽快登录老师后台，进行查看。"
                ];

                \App\Helper\Utils::send_teacher_msg_for_wx($data_msg,$template_id_teacher, $data_msg,'');
            }

            return $this->output_succ();
        }else{
            return $this->output_err('图片上传失败,请稍后重试.....');
        }
    }



    public function get_pdf_download_url()
    {
        $file_url = $this->get_in_str_val("file_url");

        if (strlen($file_url) == 0) {
            return $this->output_err(array( 'info' => '文件名为空', 'file' => $file_url));
        }

        if (preg_match("/http/", $file_url)) {
            return $this->output_succ( array('ret' => 0, 'info' => '成功', 'file' => $file_url));
        } else {
            $new_url=$this->gen_download_url($file_url);
            // dd($new_url);
            return $this->output_succ(array('ret' => 0, 'info' => '成功',
                             'file' => urlencode($new_url),
                             'file_ex' => $new_url,
            ));
        }
    }

    private function gen_download_url($file_url)
    {
        // 构建鉴权对象
        $auth = new \Qiniu\Auth(
            \App\Helper\Config::get_qiniu_access_key(),
            \App\Helper\Config::get_qiniu_secret_key()
        );

        $file_url = \App\Helper\Config::get_qiniu_private_url()."/" .$file_url;

        $base_url=$auth->privateDownloadUrl($file_url );
        return $base_url;
    }



    public function get_paper_url(){ // 获取预览图片
        $lesson_id   = $this->get_in_int_val('lessonid');
        $lesson_type = $this->get_in_int_val('lesson_type');
        $paper_type  = $this->get_in_int_val('paper_type');

        if($lesson_type == 2){ // 试听课
            if($paper_type == 1){ // 试卷
                $paper_url = $this->t_test_lesson_subject->get_stu_lesson_pic_and_homework($lessonid);

                return $this->output_succ(['data'=>$paper_url['stu_lesson_pic']]);
            }elseif($paper_type == 2){ // 作业
                $paper_url = $this->t_test_lesson_subject->get_stu_lesson_pic_and_homework($lessonid);

                if($paper_url['homework_pdf']){
                    return $this->output_succ(['data'=>$paper_url['homework_pdf']]);
                }
            }
        }else{ // 常规课
            if($paper_type == 1){ // 试卷
                $paper_url = $this->t_lesson_info_b2->get_stu_test_paper($lessonid);
                return $this->output_succ(['data'=>$paper_url]);
            }elseif($paper_type == 2){ // 作业
                $paper_url = $this->t_lesson_info_b2->get_stu_cw_url($lessonid);

                if($paper_url){
                    return $this->output_succ(['data'=>$paper_url]);
                }
            }
        }
    }

    public function check_is_admin(){
        $parentid = session("parentid");
        $wx_openid = $this->t_parent_info->get_wx_openid($parentid);
        if($wx_openid == ""){
            return $this->output_err("请绑定微信");
        }
        $ret = $this->t_manager_info->check_admin($wx_openid);
        if($ret){
            return $this->output_succ(['phone'=>$ret]);
        }else{
            return $this->output_err("false");
        }
    }



    # 处理家长微信端调时间

    # 家长请假功能 || 需要新增字段标记课程取消类别
    public function leaveByParent(){
        $lessonid = $this->get_in_int_val('lessonid');
        $parentid = $this->get_parentid();
        $lessonStartTime = $this->t_lesson_info_b3->get_lesson_start($lessonid);
        $lessonEndTime   = $this->t_lesson_info_b3->get_lesson_end($lessonid);

        $isSelf = $this->t_parent_child->getParentidByLessonId($lessonid,$parentid);

        if($lessonStartTime<time()+24*60*60){
            return $this->output_err('只可请假24小时之后的课程!');
        }else{
            if($isSelf==1){
                $this->t_lesson_info_b3->field_update_list($lessonid, [
                    "lesson_del_flag" => 1
                ]);
                return $this->output_succ();
            }else{
                return $this->output_err("操作失败,请及时联系助教老师!");
            }
        }
    }

    # 获取调时间选项
    # 做冲突检测 : 如有连续的时间也不可以
    #
    #
    public function getChangeTimeList(){
        $lessonid  = $this->get_in_int_val('lessonid');
        $userid    = $this->t_lesson_info_b3->get_userid($lessonid);
        $teacherid = $this->t_lesson_info_b3->get_teacherid($lessonid);
        $limitTimeStart = strtotime(date('Y-m-d'));
        $limitTimeEnd   = $limitTimeStart+86400;
        $lessonTimeList = $this->t_lesson_info_b3->getLessonTimeList($userid,$teacherid,$limitTimeStart,$limitTimeEnd);
        $lesson_start   = $this->t_lesson_info_b3->get_lesson_start($lessonid);
        $lesson_end     = $this->t_lesson_info_b3->get_lesson_end($lessonid);
        $lessonDuration = $lesson_end-$lesson_start;
        dd($lessonTimeList);

        $start = strtotime(date('Y-m-d 06:00:00'));
        $end   = strtotime('+1 day');
        $date_list = [];
        foreach($lessonTimeList as &$item){

        }
    }


}









