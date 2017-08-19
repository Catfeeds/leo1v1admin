<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;
use Illuminate\Support\Facades\Redis ;
use Illuminate\Support\Facades\Session ;




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



class wx_teacher_api extends Controller
{

    use CacheNick;
    var $check_login_flag=false;
    public function __construct() {
        parent::__construct();
    }

    public function get_teacherid(){
        $teacherid= $this->get_in_int_val("teacherid") ? $this->get_in_int_val("teacherid") : session("teacherid");
        return $teacherid;

    }



    public function teacher_report_msg(){
        $report_uid   = $this->get_teacherid();
        $report_msg   = $this->get_in_str_val('report_msg');
        $complaint_type = $this->get_in_int_val('complaint_type');

        $report_msg_last = $this->t_complaint_info->get_last_msg($report_uid);
        if (!empty($report_msg_last) && $report_msg_last['0']['complaint_info'] == $report_msg) {
            return $this->output_err("投诉已受理,请勿重复提交..");
        }

        // * 插入到投诉数据库中
        $account_type   = '2'; // 老师类型

        $ret_info_qc = $this->t_complaint_info->row_insert([
            'complaint_type' => $complaint_type,
            'userid'         => $report_uid,
            'account_type'   => $account_type,
            'add_time'       => time(NULL),
            'complaint_info' => $report_msg,
        ]);


        if ($ret_info_qc) {

            // 通知QC处理
            $log_time_date = date('Y-m-d H:i:s',time(NULL));
            $opt_nick= $this->cache_get_teacher_nick($report_uid);

            /**
               {{first.DATA}}
               待办主题：{{keyword1.DATA}}
               待办内容：{{keyword2.DATA}}
               日期：{{keyword3.DATA}}
               {{remark.DATA}}
             **/

            $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";//待处理通知
            $data_msg = [
                "first"     => "$opt_nick 老师发布了一条投诉",
                "keyword1"  => "常规投诉",
                "keyword2"  => "老师投诉内容:$report_msg",
                "keyword3"  => "投诉时间 $log_time_date ",
            ];
            $url = 'http://admin.yb1v1.com/user_manage/qc_complaint/';
            $wx=new \App\Helper\Wx();

            $qc_openid_arr = [
                // "orwGAs_IqKFcTuZcU1xwuEtV3Kek" ,//james
                "orwGAswyJC8JUxMxOVo35um7dE8M", // QC wenbin
                "orwGAsyyvy1YzV0E3mmq7gBB3rms", // QC 李珉劼
                "orwGAs0ayobuEtO1YZZhW3Yed2To",  // rolon
                "orwGAs4FNcSqkhobLn9hukmhIJDs",  // ted or erick
                "orwGAs1H3MQBeo0rFln3IGk4eGO8",  // sunny
                "orwGAswxkjf1agdPpFYmZxSwYJsI" // coco 老师 [张科]
            ];

            foreach($qc_openid_arr as $qc_item){
                $wx->send_template_msg($qc_item,$template_id,$data_msg ,$url);
            }

            // 给投诉老师反馈
            /**
             *kvkJPCc9t5LDc8sl0ll0imEWK7IGD1NrFKAiVSMwGwc
             {{first.DATA}}
             反馈内容：{{keyword1.DATA}}
             处理结果：{{keyword2.DATA}}
             {{remark.DATA}}
            **/
            $url = '';
            $teacher_openid   = $this->t_teacher_info->get_wx_openid_by_teacherid($report_uid);

            $template_id_teacher  = "kvkJPCc9t5LDc8sl0ll0imEWK7IGD1NrFKAiVSMwGwc";
            $data['first']      = "您好,您的反馈我们已经收到! ";
            $data['keyword1']   = $report_msg;
            $data['keyword2']   = "已提交";
            $data['remark']     = "我们会在3个工作日内处理,感谢您的反馈!";
            \App\Helper\Utils::send_teacher_msg_for_wx($teacher_openid,$template_id_teacher, $data,$url);

            return $this->output_succ();
        }
    }




    // 返回需要反馈的部门
    public function  get_feedback_department(){
        $department_arr = [
            1=>'教研部',
            2=>'教学质量组',
            3=>'培训部',
            4=>"师训部",
            5=>"老师运营组",
            8=>"咨询一部",
            9=>"咨询二部",
            10=>"咨询三部",
            11=>"销售运营部",
            12=>"助教部",
            13=>"教务部",
        ];


        return $this->output_succ(['data'=>$department_arr]);
    }


    public function teacher_feed_back_work(){ // 老师微信老师帮 反馈工作处理
        $complaint_info    = $this->get_in_str_val('complaint_info','');
        $serverId_str      = $this->get_in_str_val('serverId_str','');
        $suggest_info      = $this->get_in_str_val('suggest_info','');
        $teacherid         = $this->get_teacherid();
        $complained_adminid_nick = $this->get_in_str_val('complained_adminid_nick');
        $complained_department   = $this->get_in_int_val('complained_department',0);// 被投诉人部门 [需新增字段]
        $complaint_type   = $this->get_in_int_val('complaint_type');

        $sever_name = $_SERVER['SERVER_NAME'];
        // $complaint_img_url = $this->deal_feedback_img($serverId_str,$sever_name); [原始]

        // 老师帮微信号
        $appid = 'wxa99d0de03f407627';
        $appscript = '61bbf741a09300f7f2fd0a861803f920';

        $ret_arr = \App\Helper\Utils::deal_feedback_img($serverId_str,$sever_name, $appid, $appscript);
        $complaint_img_url = $ret_arr['complaint_img_url'];

        $report_msg_last = $this->t_complaint_info->get_last_msg($teacherid);
        if (!empty($report_msg_last) && $report_msg_last['0']['complaint_info'] == $complaint_info) {
            return $this->output_err("投诉已受理,请勿重复提交..");
        }

        // * 插入到投诉数据库中
        $account_type   = '2'; // 投诉人身份 [老师]

        $ret_info_qc = $this->t_complaint_info->row_insert([
            'complaint_type' => $complaint_type,
            'userid'         => $teacherid,
            'account_type'   => $account_type,
            'add_time'       => time(NULL),
            'suggest_info'   => $suggest_info,
            'complaint_info' => $complaint_info,
            'complaint_img_url'       => $complaint_img_url,
            'complained_department'   => $complained_department,
            'complained_adminid_nick' => $complained_adminid_nick,
            'complained_feedback_type' => 1
        ]);


        if ($ret_info_qc) {
            // 通知QC处理
            $log_time_date = date('Y-m-d H:i:s',time(NULL));
            $opt_nick= $this->cache_get_teacher_nick($report_uid);

            /**
               {{first.DATA}}
               待办主题：{{keyword1.DATA}}
               待办内容：{{keyword2.DATA}}
               日期：{{keyword3.DATA}}
               {{remark.DATA}}
             **/

            $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";//待处理通知
            $data_msg = [
                "first"     => "$opt_nick 老师发布了一条投诉",
                "keyword1"  => "常规投诉",
                "keyword2"  => "老师投诉内容:$report_msg",
                "keyword3"  => "投诉时间 $log_time_date ",
            ];
            $url = 'http://admin.yb1v1.com/user_manage/qc_complaint/';
            $wx=new \App\Helper\Wx();

            $qc_openid_arr = [
                // "orwGAs_IqKFcTuZcU1xwuEtV3Kek" ,//james
                "orwGAswyJC8JUxMxOVo35um7dE8M", // QC wenbin
                "orwGAsyyvy1YzV0E3mmq7gBB3rms", // QC 李珉劼
                "orwGAs0ayobuEtO1YZZhW3Yed2To",  // rolon
                "orwGAs4FNcSqkhobLn9hukmhIJDs",  // ted or erick
                "orwGAs1H3MQBeo0rFln3IGk4eGO8",  // sunny
                "orwGAswxkjf1agdPpFYmZxSwYJsI" // coco 老师 [张科]
            ];

            foreach($qc_openid_arr as $qc_item){
                $wx->send_template_msg($qc_item,$template_id,$data_msg ,$url);
            }

            // 给投诉老师反馈
            /**
             *kvkJPCc9t5LDc8sl0ll0imEWK7IGD1NrFKAiVSMwGwc
             {{first.DATA}}
             反馈内容：{{keyword1.DATA}}
             处理结果：{{keyword2.DATA}}
             {{remark.DATA}}
            **/
            $url = '';
            $teacher_openid   = $this->t_teacher_info->get_wx_openid_by_teacherid($report_uid);

            $template_id_teacher  = "kvkJPCc9t5LDc8sl0ll0imEWK7IGD1NrFKAiVSMwGwc";
            $data['first']      = "您好,您的反馈我们已经收到! ";
            $data['keyword1']   = $complained_info;
            $data['keyword2']   = "已提交";
            $data['remark']     = "我们会在3个工作日内处理,感谢您的反馈!";
            \App\Helper\Utils::send_teacher_msg_for_wx($teacher_openid,$template_id_teacher, $data,$url);

            return $this->output_succ();
        }
    }

    public function teacher_feed_back_software(){ // 软件反馈处理
        $complaint_type    = $this->get_in_int_val('complaint_type'); // 新增4 软件反馈
        $complaint_info    = $this->get_in_str_val('complaint_info');
        $serverId_str      = $this->get_in_str_val('serverId_str',''); // 图片ids
        $teacherid         = $this->get_teacherid();

        $sever_name = $_SERVER["SERVER_NAME"];

        $ret_arr = \App\Helper\Utils::deal_feedback_img($serverId_str,$sever_name);
        $complaint_img_url = $ret_arr['complaint_img_url'];

        $report_msg_last = $this->t_complaint_info->get_last_msg($teacherid);
        if (!empty($report_msg_last) && $report_msg_last['0']['complaint_info'] == $complaint_info) {
            return $this->output_err("投诉已受理,请勿重复提交..");
        }

        // * 插入到投诉数据库中
        $account_type   = '2'; // 投诉人身份 [老师]

        $ret_info_qc = $this->t_complaint_info->row_insert([
            'complaint_type' => $complaint_type,
            'userid'         => $teacherid,
            'account_type'   => $account_type,
            'add_time'       => time(NULL),
            'complaint_info' => $complaint_info,
            'complaint_img_url' => $complaint_img_url,
            'complained_feedback_type' => 2
        ]);


        if ($ret_info_qc) {
            // 通知QC处理
            $log_time_date = date('Y-m-d H:i:s',time(NULL));
            $opt_nick= $this->cache_get_teacher_nick($report_uid);

            /**
               {{first.DATA}}
               待办主题：{{keyword1.DATA}}
               待办内容：{{keyword2.DATA}}
               日期：{{keyword3.DATA}}
               {{remark.DATA}}
             **/

            $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";//待处理通知
            $data_msg = [
                "first"     => "$opt_nick 老师发布了一条软件使用反馈",
                "keyword1"  => "软件使用反馈",
                "keyword2"  => "老师反馈内容:$report_msg",
                "keyword3"  => "反馈时间 $log_time_date ",
            ];
            $url = 'http://admin.yb1v1.com/user_manage/qc_complaint/';
            $wx=new \App\Helper\Wx();

            $qc_openid_arr = [
                // "orwGAs_IqKFcTuZcU1xwuEtV3Kek" ,//james
                // "orwGAs4FNcSqkhobLn9hukmhIJDs",  // ted or erick
                "orwGAs0ayobuEtO1YZZhW3Yed2To",  // rolon
                "orwGAs1H3MQBeo0rFln3IGk4eGO8",  // sunny
                "orwGAs9GLgIN85K4nViZZ-MH5ZM8",  // haku
                "orwGAs3JTSM8qO0Yn0e9HrI9GCUI",  // 付玉文
            ];

            foreach($qc_openid_arr as $qc_item){
                $wx->send_template_msg($qc_item,$template_id,$data_msg ,$url);
            }

            // 给投诉老师反馈
            /**
             *kvkJPCc9t5LDc8sl0ll0imEWK7IGD1NrFKAiVSMwGwc
             {{first.DATA}}
             反馈内容：{{keyword1.DATA}}
             处理结果：{{keyword2.DATA}}
             {{remark.DATA}}
            **/
            $url = '';
            $teacher_openid   = $this->t_teacher_info->get_wx_openid_by_teacherid($report_uid);

            $template_id_teacher  = "kvkJPCc9t5LDc8sl0ll0imEWK7IGD1NrFKAiVSMwGwc";
            $data['first']      = "您好,您的反馈我们已经收到! ";
            $data['keyword1']   = $complained_info;
            $data['keyword2']   = "已提交";
            $data['remark']     = "我们会在3个工作日内处理,感谢您的反馈!";
            \App\Helper\Utils::send_teacher_msg_for_wx($teacher_openid,$template_id_teacher, $data,$url);

            return $this->output_succ();
        }
    }


    //  处理反馈图片上传
    /**
       老师帮 微信
       只有一张图片时 直接将图片放入数据库 不需要压缩
    **/
    public function deal_feedback_img($serverId_str,$sever_namem, $appid, $appscript)
    {
        $serverIdLists = json_decode($serverId_str,true);
        $alibaba_url   = [];

        foreach($serverIdLists as $serverId){
            $imgStateInfo = $this->savePicToServer($serverId);
            $savePathFile = $imgStateInfo['savePathFile'];
            $file_name = $this->put_img_to_alibaba($savePathFile);

            $alibaba_url[] = $file_name ;
            unlink($savePathFile);
        }

        $alibaba_url_str = implode(',',$alibaba_url);
        return $alibaba_url_str;
    }


    public function savePicToServer($serverId ,$appid_tec= 'wxa99d0de03f407627', $appscript_tec= '61bbf741a09300f7f2fd0a861803f920' ) {

        $accessToken = $this->get_wx_token_jssdk( $appid_tec, $appscript_tec);

        // 要存在你服务器哪个位置？
        $route = md5(date('YmdHis').rand());
        $savePathFile = '/tmp/'.$route.'.jpg';
        $targetName   = $savePathFile;

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

            return $config["public"]["url"]."/".$file_name;

        } catch (OssException $e) {
            return "" ;
        }
    }

    public function get_wx_token_jssdk($appid_tec= 'wxa99d0de03f407627', $appscript_tec= '61bbf741a09300f7f2fd0a861803f920' ){

        $wx        = new \App\Helper\Wx();
        return $wx->get_wx_token($appid_tec,$appscript_tec);
    }


}
