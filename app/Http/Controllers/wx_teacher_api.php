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
use Teacher\Core\WeChatOAuth;

use Teacher\Core\UserManage;

use Teacher\Core\TemplateMessage;

use Teacher\Core\Media;

use Teacher\Core\AccessToken;

use App\Jobs\marketActivityPoster;

include(app_path("Wx/Teacher/lanewechat_teacher.php"));


require_once  app_path("/Libs/Qiniu/functions.php");
require_once(app_path("/Libs/OSS/autoload.php"));
use OSS\OssClient;
use OSS\Core\OssException;
class wx_teacher_api extends Controller
{
    use CacheNick;
    use TeaPower;
    var $check_login_flag=false;
    public function __construct() {
        parent::__construct();
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
        $account_type = '2'; // 老师类型
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
            $url = 'http://admin.leo1v1.com/user_manage/qc_complaint/';
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
                "orwGAswxkjf1agdPpFYmZxSwYJsI", // coco 老师 [张科]
                "orwGAs87gepYCYKpau66viHluRGI",  // 傅文莉
                // "orwGAs6J8tzBAO3mSKez8SX-DWq4"   // 孙瞿
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
        // $complaint_type   = $this->get_in_int_val('complaint_type');

        \App\Helper\Utils::logger("serverId_str_work: $serverId_str");

        $complaint_type   = 1;
        $now = time();

        $last_info_arr = $this->t_complaint_info->get_last_msg($teacherid);
        if(!empty($last_info_arr)){
            $last_info = $last_info_arr[0];
            if($last_info['complaint_info'] == $complaint_info && ($last_info['add_time']+120) > $now){
                return $this->output_err("您的反馈我们已收到,我们会及时处理,谢谢您的反馈!");
            }
        }

        $sever_name = $_SERVER['SERVER_NAME'];

        // 老师帮微信号
        $appid = 'wxa99d0de03f407627';
        $appscript = '61bbf741a09300f7f2fd0a861803f920';

        $ret_arr = \App\Helper\Utils::deal_feedback_img($serverId_str,$sever_name, $appid, $appscript);
        $complaint_img_url = $ret_arr['alibaba_url_str'];

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
        ]);


        if ($ret_info_qc) {
            // 通知QC处理
            $log_time_date = date('Y-m-d H:i:s',time());
            $opt_nick= $this->cache_get_teacher_nick($teacherid);

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
                "keyword2"  => "老师投诉内容:$complaint_info",
                "keyword3"  => "投诉时间 $log_time_date ",
            ];
            $url = 'http://admin.leo1v1.com/user_manage/qc_complaint/';
            $wx=new \App\Helper\Wx();

            $qc_openid_arr = [
                // "orwGAs_IqKFcTuZcU1xwuEtV3Kek" ,//james
                "orwGAswyJC8JUxMxOVo35um7dE8M", // QC wenbin
                "orwGAsyyvy1YzV0E3mmq7gBB3rms", // QC 李珉劼
                "orwGAs2Cq6JQKTqZghzcv3tUE5dU", // 王浩鸣
                "orwGAs4-nyzZL2rAdqT2o63GvxG0", // 郭冀江
                "orwGAs0ayobuEtO1YZZhW3Yed2To",  // rolon
                "orwGAs4FNcSqkhobLn9hukmhIJDs",  // ted or erick
                "orwGAs1H3MQBeo0rFln3IGk4eGO8",  // sunny
                "orwGAswxkjf1agdPpFYmZxSwYJsI" // coco 老师 [张科]
            ];

            foreach($qc_openid_arr as $qc_item){
                $wx->send_template_msg($qc_item,$template_id,$data_msg ,$url); // 暂时注释
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
            $teacher_openid   = $this->t_teacher_info->get_wx_openid_by_teacherid($teacherid);

            $template_id_teacher  = "kvkJPCc9t5LDc8sl0ll0imEWK7IGD1NrFKAiVSMwGwc";
            $data['first']      = "您好,您的反馈我们已经收到! ";
            $data['keyword1']   = $complaint_info;
            $data['keyword2']   = "已提交";
            $data['remark']     = "我们会在3个工作日内处理,感谢您的反馈!";
            \App\Helper\Utils::send_teacher_msg_for_wx($teacher_openid,$template_id_teacher, $data,$url);

            return $this->output_succ();
        }
    }

    public function teacher_feed_back_software(){ // 软件反馈处理
        $complaint_type    = $this->get_in_int_val('complaint_type'); // 新增4 软件反馈
        $complaint_info    = $this->get_in_str_val('complaint_info');
        $serverId_str      = $this->get_in_str_val('serverId_str'); // 图片ids
        $teacherid         = $this->get_teacherid();
        $now = time();

        $last_info_arr = $this->t_complaint_info->get_last_msg($teacherid);
        if(!empty($last_info_arr)){
            $last_info = $last_info_arr[0];
            if($last_info['complaint_info'] == $complaint_info && ($last_info['add_time']+120) > $now){
                return $this->output_err('');
            }
        }



        $sever_name = $_SERVER["SERVER_NAME"];

        $complaint_img_url = '';

        if($serverId_str){
            $ret_arr = \App\Helper\Utils::deal_feedback_img($serverId_str,$sever_name);
            $complaint_img_url = $ret_arr['alibaba_url_str'];
        }

        \App\Helper\Utils::logger("complaint_img_url: ".$complaint_img_url);


        //插入到投诉数据库中
        $account_type = '2'; // 投诉人身份 [老师]
        $ret_info_qc = $this->t_complaint_info->row_insert([
            'complaint_type' => $complaint_type,
            'userid'         => $teacherid,
            'account_type'   => $account_type,
            'add_time'       => time(NULL),
            'complaint_info' => $complaint_info,
            'complaint_img_url' => $complaint_img_url,
        ]);

        if ($ret_info_qc) {
            // 通知QC处理
            $log_time_date = date('Y-m-d H:i:s',time(NULL));
            $opt_nick= $this->cache_get_teacher_nick($teacherid);

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
                "keyword2"  => "老师反馈内容:$complaint_info",
                "keyword3"  => "反馈时间 $log_time_date ",
            ];
            $url = 'http://admin.leo1v1.com/user_manage/complaint_department_deal_product';
            $wx=new \App\Helper\Wx();

            $qc_openid_arr = [
                // "orwGAs_IqKFcTuZcU1xwuEtV3Kek" ,//james
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
            $teacher_openid   = $this->t_teacher_info->get_wx_openid_by_teacherid($teacherid);

            $template_id_teacher  = "kvkJPCc9t5LDc8sl0ll0imEWK7IGD1NrFKAiVSMwGwc";
            $data['first']      = "您好,您的反馈我们已经收到! ";
            $data['keyword1']   = $complaint_info;
            $data['keyword2']   = "已提交";
            $data['remark']     = "我们会在3个工作日内处理,感谢您的反馈!";
            \App\Helper\Utils::send_teacher_msg_for_wx($teacher_openid,$template_id_teacher, $data,$url);

            return $this->output_succ();
        }
    }

    public function get_teacher_info_for_teacher_day(){ // 教师节活动 获取老师信息
        $this->switch_tongji_database();
        $ret_info = [];
        $teacherid = $this->get_teacherid();

        $test_lesson_info = $this->t_teacher_info->get_test_lesson_info_for_teacher_day($teacherid);

        $test_lesson_info['pass_time'] = $test_lesson_info["work_day"]?date("Y.m.d",$test_lesson_info["work_day"]):"";
        $test_lesson_info["work_day"] = $test_lesson_info["work_day"]?ceil((time()-$test_lesson_info["work_day"])/86400):0;
        $test_lesson_info["test_lesson_time"] = $test_lesson_info['test_lesson_time']?date("Y.m.d",$test_lesson_info['test_lesson_time']):"";

        $common_lesson_info = $this->t_teacher_info->get_common_lesson_info_for_teacher_day($teacherid);
        $common_lesson_info["common_lesson_start"] = $common_lesson_info['common_lesson_start']?date("Y.m.d",$common_lesson_info['common_lesson_start']):"";
        $common_lesson_num = $this->t_teacher_info->get_common_lesson_num_for_teacher_day($teacherid);

        $stu_num = $this->t_teacher_info->get_student_num_for_teacher_day($teacherid);

        $ret_info = array_merge($test_lesson_info, $common_lesson_info, $common_lesson_num, $stu_num);

        // $url = "http://admin.leo1v1.com/teacher_money/get_teacher_total_money?type=admin&teacherid=".$teacherid;
        // $ret =\App\Helper\Utils::send_curl_post($url);
        // $ret = json_decode($ret,true);
        // if(isset($ret) && is_array($ret) && isset($ret["data"][0]["lesson_price"])){
        //     $money = $ret["data"][0]["lesson_price"];
        // }else{
        //     $money = 0;
        // }

        // $ret_info['money'] = $money.'元';

        return $this->output_succ(["data"=>$ret_info]);
    }

    public function get_month(){
        $month = intval( date('n', strtotime ("-1 month") ));
        \App\Helper\Utils::logger("get month");

        return $this->output_succ(['month'=> $month]);
    }

    public function get_teacher_lesson(){//p 2
        $teacherid = $this->get_teacherid();

        \App\Helper\Utils::logger("yuebao".$teacherid);
        if (!$teacherid) {
            return $this->output_err("信息有误，未查询到老师信息！");
        }
        $end_time   = strtotime(date("Y-m-01",time()));
        $start_time = strtotime("-1 month",$end_time);
        $ret_info   = $this->t_teacher_info->get_tea_lesson_info($teacherid, $start_time, $end_time);
        $ret_info['normal_count'] = $ret_info['normal_count']/100;
        $ret_info['test_count']   = $ret_info['test_count']/100;
        $ret_info['other_count']  = $ret_info['other_count']/100;
        return $this->output_succ(["lesson_info"=>$ret_info]);
    }

    public function get_teacher_level(){//p3
        // $teacherid = $this->get_in_int_val("teacherid");
        $teacherid = $this->get_teacherid();
        if (!$teacherid) {
            return $this->output_err("信息有误，未查询到老师信息！");
        }

        $tea_title = [
            1 => "一星教师",
            2 => "二星教师",
            3 => "三星教师",
            4 => "四星教师",
            5 => "五星教师",
        ];
        $tea_des = [
            1 => "老师加油，马上就会升级啦",
            2 => "努力一点点，三星教师就在眼前",
            3 => "只要功夫深，四星教师不是梦",
            4 => "使出洪荒之力，五星教师就是你",
            5 => "荣耀五星教师，你值得拥有",
        ];
        $ret_info = $this->t_teacher_info->get_teacher_true_level($teacherid);
        if($ret_info['teacher_money_type'] == 0) {
            $level = $ret_info['level'] + 2;
        } else {
            $level = $ret_info['level'] + 1;
        }
        if($level > 5){
            $level = 5;
        }
        $list['level'] = $level;
        $list['tea_title'] = $tea_title[$level];
        $list['tea_des'] = $tea_des[$level];
        return $this->output_succ(["tea_info"=>$list]);
    }

    public function get_teacher_student(){//p4
        // $teacherid = $this->get_in_int_val("teacherid");
        $teacherid = $this->get_teacherid();
        if (!$teacherid) {
            return $this->output_err("信息有误，未查询到老师信息！");
        }
        $end_time   = strtotime(date("Y-m-01",time()));
        $start_time = strtotime("-1 month",$end_time);
        $ret_info   = $this->t_teacher_info->get_student_by_teacherid($teacherid,$start_time, $end_time);
        $face       = [];
        foreach ($ret_info as $item) {
            $face[] = @$item['face'];
        }
        $stu_info['stu_num'] = count($ret_info);
        $stu_info['face']    = $face;
        return $this->output_succ(["stu_info"=>$stu_info]);
    }

    public function get_tea_lesson_some_info(){//p5
        // $teacherid = $this->get_in_int_val("teacherid");
        $teacherid = $this->get_teacherid();
        if (!$teacherid) {
            return $this->output_err("信息有误，未查询到老师信息！");
        }
        $end_time   = strtotime(date("Y-m-01",time()));
        $start_time = strtotime("-1 month",$end_time);
        $ret_info = $this->t_teacher_info->get_teacher_lesson_detail($teacherid,$start_time, $end_time);
        foreach ($ret_info as &$item) {
            $item = intval($item);
        }
        return $this->output_succ(["list"=>$ret_info]);
    }

    public function get_teacher_money(){
        $teacherid = $this->get_teacherid();

        \App\Helper\Utils::logger("month report teacherid".$teacherid);
        $end_time = date("Y-m-01",time());
        $start_time = date("Y-m-d",strtotime("-1 month",strtotime($end_time)));

        $url = "http://admin.leo1v1.com/teacher_money/get_teacher_total_money?type=admin&teacherid=".$teacherid
             ."&start_time=".$start_time."&end_time=".$end_time;
        $ret = \App\Helper\Utils::send_curl_post($url);
        $ret = json_decode($ret,true);

        if(isset($ret) && is_array($ret) && isset($ret["data"][0]["lesson_price"])){
            $money = $ret["data"][0]["lesson_price"];
        }else{
            $money = 0;
        }

        return $this->output_succ(["money"=>$money]);
    }


    // 教师节抽奖接口 [招师部]

    public function get_luck_draw_num(){ // 获取当前老师的抽奖次数
        $teacherid = $this->get_teacherid();
        $is_share  = $this->t_wx_share->get_share_flag($teacherid);
        $is_video  = $this->t_teacher_lecture_info->get_video_flag($teacherid);

        $num = $this->t_teacher_day_luck_draw->compute_time($teacherid); // 已使用次数

        $total_num = 1;
        if($is_share || $is_video){ //抽奖次数 增加3次
            $total_num = 4;
        }

        $left_num = $total_num-$num;

        return $this->output_succ(['num'=>$left_num]);

    }


    public function update_is_share(){ // 更新是否分享朋友圈
        $teacherid = $this->get_teacherid();

        $ret = $this->t_wx_share->row_insert([
            'teacherid'  => $teacherid,
            'type'       => 1, // 分享类型 1:微信朋友圈
            'share_time' => time()
        ]);

        return $this->output_succ();
    }

    public function teacher_day_luck_draw(){ //教师节抽奖活动//

        $teacherid = $this->get_teacherid();

        // 计算目前的奖金总额
        $total_money = $this->t_teacher_day_luck_draw->get_total_money();

        if($total_money > 2000){ // 超过经费额度 则所有人都显示未中奖
            return $this->output_succ(['money'=>0]);
        }

        // 判断是否有 录制试讲||分享朋友圈
        $is_share = $this->t_wx_share->get_share_flag($teacherid);
        $is_video = $this->t_teacher_lecture_info->get_video_flag($teacherid);

        $total_num = 1;
        if($is_share || $is_video){ //抽奖次数 增加3次
            $total_num = 4;
        }

        $num = $this->t_teacher_day_luck_draw->compute_time($teacherid);

        if($num>=$total_num){
            if($teacherid !=240314){  // 测试使用
                return $this->output_err('您的抽奖次数已用完!');
            }
        }

        $rand  = mt_rand(0,100000);
        $money = 0;

        if($rand>1000 && $rand<=1035){ // 中 91.0元
            $money = '9100'; // 单位分
        }elseif($rand>2000 && $rand <=3000){ // 中9.1元
            $money = '910'; // 单位分
        }elseif($rand>20000 && $rand<=33000){ // 中0.91元
            $money = '91'; // 单位分
        }else{

        }

        $ret = $this->t_teacher_day_luck_draw->row_insert([
            'do_time'   => time(),
            'teacherid' => $teacherid,
            'money'     => $money,
        ]);

        // dd($money);
        $real_money = $money/100;
        $left_num = $total_num-$num;
        return $this->output_succ(['money'=>$real_money,'num'=>$left_num]);
    }


    public function get_modify_lesson_time_by_teacher(){//2006 // 老师 点击家长调课 推送详情
        $lessonid = $this->get_in_int_val('lessonid');
        $is_modify_time_flag = $this->t_lesson_time_modify->get_is_modify_time_flag($lessonid);
        $parent_deal_time = $this->t_lesson_time_modify->get_parent_deal_time($lessonid);

        if($parent_deal_time<time()-3600){
            $ret_info['has_do'] = 2; //超时
            return $this->output_succ(['data'=>$ret_info]);
        }

        if($is_modify_time_flag > 0){
            $ret_info['has_do'] = 1;// 已处理
            return $this->output_succ(['data'=>$ret_info]);
        }else{
            $time_info = $this->t_lesson_info_b3->get_lesson_info($lessonid);
            $time_info['subject'] = E\Esubject::get_desc($time_info['subject']);
            $time_info['grade'] = E\Egrade::get_desc($time_info['grade']);
            $time_info['parent_modify_time']  = $this->t_lesson_time_modify->get_parent_modify_time($lessonid);

            $date_modify = json_decode($time_info['parent_modify_time'],true);
            $day_date = [];
            foreach($date_modify as $item){
                $day_date[] = date('Y-m-d',$item);
            }
            $b = array_flip(array_flip($day_date));
            $time_info['teacher_lesson_time'] = [];
            foreach($b as $val){
                $begin_time = strtotime($val);
                $end_time   = $begin_time+86400;
                $tea_time[] = $this->t_lesson_info_b2->get_teacher_time_by_lessonid($lessonid, $begin_time, $end_time);
            }

            $teacher_time = [];
            foreach($tea_time as $v){
                foreach($v as $vv){
                    $teacher_time[] = $vv;
                }
            }
            $time_info['teacher_lesson_time'] = $teacher_time;

            $time_info['has_do'] = 0;  // 未处理
            return $this->output_succ(['data'=>$time_info]);

        }
    }


    public function keep_lesson_time(){ // 2007 // 老师|家长  维持原有时间 [提交原因]
        $lessonid        = $this->get_in_int_val('lessonid');
        $is_role_account = $this->get_in_int_val('role_account'); // 1:家长 2:老师

        \App\Helper\Utils::logger("keep_lesson_time");

        if($is_role_account == 1){
            $parent_keep_original_remark  = $this->get_in_str_val('parent_keep_original_remark');
            $ret = $this->t_lesson_time_modify->field_update_list($lessonid,[
                'parent_keep_original_remark' => $parent_keep_original_remark,
                'is_modify_time_flag'  => 2,// 家长维持有时间
            ]);
        }else{
            $teacher_keep_original_remark = $this->get_in_str_val('teacher_keep_original_remark');
            $ret = $this->t_lesson_time_modify->field_update_list($lessonid,[
                'teacher_keep_original_remark' => $teacher_keep_original_remark,
                'is_modify_time_flag'  => 2,// 老师维持有时间
                'teacher_deal_time'    => time(NULL)
            ]);
        }

        if($ret){
            $ret_wx = $this->send_wx_msg_by_keep($lessonid,$is_role_account );

            return $this->output_succ();
        }

        \App\Helper\Utils::logger("ret_wx: $ret_wx  ret: $ret");

    }



    public function teacher_get_modify_lesson_time(){  // 2008 // 老师更换时间
        $lessonid = $this->get_in_int_val('lessonid');

        $lesson_time = $this->t_lesson_info_b2->get_lesson_time($lessonid);
        $parent_modify_time  = $this->t_lesson_time_modify->get_parent_modify_time($lessonid);

        $time_info['lseeon_time'] = $lesson_time;
        $time_info['parent_modify_time'] = $parent_modify_time;

        return $this->output_succ(['data'=>$time_info]);
    }


    public function set_lesson_time_by_teacher(){ //2009 // 老师同意时间调整 并设置了自己的时间
        $lessonid        = $this->get_in_int_val('lessonid');
        $lesson_time_start  = $this->get_in_int_val('lesson_time');

        $lesson_old_start = $this->t_lesson_info_b2->get_lesson_start($lessonid);
        $lesson_old_end   = $this->t_lesson_info_b2->get_lesson_end($lessonid);
        $original_lesson_time  = $lesson_old_start.','.$lesson_old_end;

        $lesson_time_end = $lesson_old_end-$lesson_old_start+$lesson_time_start;

        // 校验老师时间
        $start_time = strtotime(date('Y-m-d',$lesson_time_start));
        $end_time   = $start_time+86400;
        $teacher_lesson_time = $this->t_lesson_info_b2->get_teacher_time_by_lessonid($lessonid, $start_time, $end_time);
        $student_lesson_time = $this->t_lesson_info_b2->get_student_lesson_time_by_lessonid($lessonid, $start_time, $end_time);

        $is_teacher_flag = 0;
        $is_student_flag = 0;
        $conflict_time_tea  = '';
        $conflict_time_stu  = '';
        foreach($teacher_lesson_time as $tea_item){
            if($tea_item['lesson_start']<$lesson_time_start && $tea_item['lesson_end']>$lesson_time_end){
                $is_teacher_flag = 1;
                $conflict_time_tea = date('H:i',$tea_item['lesson_start']).' ~ '.date('H:i',$tea_item['lesson_end']);
            }elseif($tea_item['lesson_start']<$lesson_time_start && $tea_item['lesson_end']>$lesson_time_start){
                $is_teacher_flag = 1;
                $conflict_time_tea = date('H:i',$tea_item['lesson_start']).' ~ '.date('H:i',$tea_item['lesson_end']);
            }elseif($tea_item['lesson_start']<$lesson_time_end && $tea_item['lesson_end']>$lesson_time_end){
                $is_teacher_flag = 1;
                $conflict_time_tea = date('H:i',$tea_item['lesson_start']).' ~ '.date('H:i',$tea_item['lesson_end']);
            }elseif($tea_item['lesson_start']>$lesson_time_end && $tea_item['lesson_end']<$lesson_time_end){
                $is_teacher_flag = 1;
                $conflict_time_tea = date('H:i',$tea_item['lesson_start']).' ~ '.date('H:i',$tea_item['lesson_end']);
            }
        }

        foreach($student_lesson_time as $stu_item){
            if($stu_item['lesson_start']<$lesson_time_start && $stu_item['lesson_end']>$lesson_time_end){
                $is_student_flag = 1;
                $conflict_time_stu = date('H:i',$stu_item['lesson_start']).' ~ '.date('H:i',$stu_item['lesson_end']);
            }elseif($stu_item['lesson_start']<$lesson_time_start && $stu_item['lesson_end']>$lesson_time_start){
                $is_student_flag = 1;
                $conflict_time_stu = date('H:i',$stu_item['lesson_start']).' ~ '.date('H:i',$stu_item['lesson_end']);
            }elseif($stu_item['lesson_start']<$lesson_time_end && $stu_item['lesson_end']>$lesson_time_end){
                $is_student_flag = 1;
                $conflict_time_stu = date('H:i',$stu_item['lesson_start']).' ~ '.date('H:i',$stu_item['lesson_end']);
            }elseif($stu_item['lesson_start']>$lesson_time_end && $stu_item['lesson_end']<$lesson_time_end){
                $is_student_flag = 1;
                $conflict_time_stu = date('H:i',$stu_item['lesson_start']).' ~ '.date('H:i',$stu_item['lesson_end']);
            }
        }


        if($is_student_flag){
            return $this->output_err('您选择的课程与学生的上课时间重叠,请您重新选择时间! [冲突时间:'.$conflict_time_stu.']');
        }elseif($is_teacher_flag){
            return $this->output_err('您选择的课程与您的上课时间重叠,请您重新选择时间!  [冲突时间:'.$conflict_time_tea.']');
        }



        $ret1 = $this->t_lesson_info_b2->field_update_list($lessonid,[
            'lesson_start'  => $lesson_time_start,
            'lesson_end'    => $lesson_time_end,
        ]);

        $ret2 = $this->t_lesson_time_modify->field_update_list($lessonid,[
            'is_modify_time_flag' =>1,// 调课成功
            'original_time' => $original_lesson_time,
            'teacher_deal_time' => time(NULL)
        ]);

        $is_teacher_agree = 2; //老师同意
        $this->send_wx_msg_by_agree($lessonid,$is_teacher_agree);
        return $this->output_succ();
    }

    public function send_wx_msg_by_keep($lessonid,$is_teacher_keep){ // 家长或老师维持原有时间 发送微信推送
        $lesson_start_time = $this->t_lesson_info_b2->get_lesson_start($lessonid);
        $lesson_start_date = date('m月d日 H:i:s',$lesson_start_time );
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
                'first' => "您已拒绝 $teacher_nick  老师要求调换您发起的换时间申请",
                'keyword1' =>"拒绝调课申请",
                'keyword2' => "原上课时间:$lesson_start_date ,您已拒绝",
                'keyword3' => "$day_date",
                'remark'   => "详细进度稍后将以推送的形式发给您,请注意查看!"
            ];
            $url_parent = '';
            $wx->send_template_msg($parent_wx_openid, $parent_template_id, $data_parent, $url_parent);

        } elseif($is_teacher_keep == 2){ // 2:老师
            //推送给老师

            $parent_keep_original_remark = $this->t_lesson_time_modify->get_parent_keep_original_remark($lessonid);
            $result = "原因: $parent_keep_original_remark ";

            $first    = "您的学生 $stu_nick 的家长申请修改 $lesson_start_date 上课时间被 $teacher_nick 老师拒绝!";
            $keyword1 = "老师拒绝调课申请";

            $keyword2 = "原上课时间: $lesson_start_date";

            $teacher_wx_openid = $this->t_teacher_info->get_wx_openid_by_lessonid($lessonid);
            $teacher_url = ''; //待定
            $template_id_teacher  = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";  // 待办事项

            $data['first']      = " 拒绝调课申请";
            $data['keyword1']   = " 您的学生 $stu_nick 的家长申请修改 $lesson_start_date 上课时间,您已拒绝! ";
            $data['keyword2']   = " 原上课时间:".$lesson_start_date;
            $data['keyword3']   = date('Y-m-d H:i:s');
            $data['remark']     = "";

            \App\Helper\Utils::send_teacher_msg_for_wx($teacher_wx_openid,$template_id_teacher, $data,$teacher_url);



            // 向家长发送推送

            $lesson_type = $this->t_lesson_info_b2->get_lesson_type($lessonid);

            if($lesson_type == 0){ // 常规课发送给家长
                $parent_wx_openid    = $this->t_parent_info->get_parent_wx_openid($lessonid);
                $parent_template_id  = '9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU';
                $data_parent = [
                    'first' => "调课申请被拒绝",
                    'keyword1' =>"调换 $lesson_start_date 上课时间被拒绝",
                    'keyword2' => "由于此时间段老师时间不方便,故调课申请未成功",
                    'keyword3' => date('Y-m-d H:i:s'),
                    'remark'   => "请耐心等待助教老师进行沟通!"
                ];
                $url_parent = '';
                $wx->send_template_msg($parent_wx_openid, $parent_template_id, $data_parent, $url_parent);
            }
        }

        //推送给 助教 / 咨询
        $parent_template_id  = '9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU';

        if($lesson_type == 0){ //常规课
            $wx_openid_arr[0]    = $this->t_lesson_info_b2->get_ass_wx_openid($lessonid);
            $wx_openid_arr[1]    = $this->t_lesson_info_b2->get_seller_wx_openid($lessonid);
        }elseif($lesson_type == 2){ // 试听课
            $wx_openid_arr[] = $this->t_test_lesson_subject_require->get_cur_require_adminid_by_lessonid($lessonid);
        }

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

    public function set_modify_lesson_time_by_teacher(){ // 2010  老师提交选择的时间段
        $lessonid = $this->get_in_int_val('lessonid');
        $teacher_modify_remark = $this->get_in_str_val('teacher_modify_remark');
        $teacher_modify_time   = $this->get_in_str_val('teacher_modify_time');

        $lesson_old_time = $this->t_lesson_info_b2->get_lesson_start($lessonid);

        $ret = $this->t_lesson_time_modify->field_update_list($lessonid,[
            'teacher_modify_remark' => $teacher_modify_remark,
            'teacher_modify_time'   => $teacher_modify_time
        ]);

        if($ret){
            // 微信推送给老师
            $day_date = date('Y-m-d H:i:s');
            $lesson_old_date = date('m月d日 H:i:s',$lesson_old_time);

            if($teacher_modify_remark){
                $result   = " 原因:  $teacher_modify_remark  ";
            }else{
                $result   = '';
            }

            $teacher_nick      = $this->t_teacher_info->get_teacher_nick_lessonid($lessonid);

            $teacher_wx_openid = $this->t_teacher_info->get_wx_openid_by_lessonid($lessonid);
            $teacher_url = ''; //待定
            $template_id_teacher  = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
            $data['first']      = "您申请修改学生 $stu_nick  的家长发起的申请修改 $lesson_old_date  的上课时间 ";
            $data['keyword1']   = " 调换 $stu_nick  的家长发起的换时间申请";
            $data['keyword2']   = "原上课时间: $lesson_old_date ";
            $data['keyword3']   = " $day_date";
            $data['remark']     = "详细进度稍后将以推送形式发给您,请注意查看!";
            \App\Helper\Utils::send_teacher_msg_for_wx($teacher_wx_openid,$template_id_teacher, $data,$teacher_url);

            // 给家长推送结果
            $parent_wx_openid    = $this->t_parent_info->get_parent_wx_openid($lessonid);
            $parent_template_id  = '9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU';
            $data_parent = [
                'first' => " $teacher_nick  老师要求调换您发起的换时间申请",
                'keyword1' =>"调换 $lesson_old_date 上课时间",
                'keyword2' => "原上课时间: $lesson_old_date ,$result",
                'keyword3' => "$day_date",
                'remark'   => "请点击详情查看老师勾选的时间并进行处理!"
            ];
            $url_parent = '';
            $wx = new \App\Helper\Wx();
            $wx->send_template_msg($parent_wx_openid, $parent_template_id, $data_parent, $url_parent);
        }

        return $this->output_succ();
    }

    public function send_wx_msg_by_agree($lessonid,$is_teacher_agree){ // 家长或老师 同意了时间后 发送微信推送

        $teacher_nick      = $this->t_teacher_info->get_teacher_nick_lessonid($lessonid);
        $subject       = $this->t_lesson_info_b2->get_subject($lessonid);
        $stu_nick          = $this->t_student_info->get_stu_nick_by_lessonid($lessonid);
        $lesson_time_arr   = $this->t_lesson_info_b2->get_modify_lesson_time($lessonid);
        $lesson_new_time   = date('m月d日 H:i',$lesson_time_arr['lesson_start']).' - '.date('H:i',$lesson_time_arr['lesson_end']);

        $lesson_old_time_str  = $this->t_lesson_time_modify->get_original_time_by_lessonid($lessonid);
        $lesson_old_time_arr  = explode(',',$lesson_old_time_str);
        $lesson_old_time      = date('m月d日 H:i',$lesson_old_time_arr[0]).' - '.date('H:i',$lesson_old_time_arr[1]);

        $lesson_name = E\Esubject::get_desc($subject);

        if($is_teacher_agree == 1){ // 家长同意
            $data['first']        = "$teacher_nick 老师您好,  $stu_nick 的家长同意将时间做出如下修改,原课程时间: $lesson_old_time ,最终时间调整至 $lesson_new_time ";

            $data_parent['first'] = "$stu_nick 的家长您好,您发起的调课申请更改如下: 原课程时间: $lesson_old_time ; 最终时间调整至 $lesson_new_time ";

            $data_leo['first'] = " $teacher_nick 老师申请调整 $stu_nick 的家长发起的调课申请,已获得 $stu_nick 家长的同意,原课程时间 $lesson_old_time ,最终时间调整至 $lesson_new_time ";
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
        $data['keyword2']   = '原时间:'.$lesson_old_time.' 修改后时间'. $lesson_new_time;
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
            'keyword2'  => '原时间:'.$lesson_old_time.' 修改后时间'. $lesson_new_time,
            'keyword3' => "$stu_nick",
            'remark'   => '请注意调整后的时间,感谢家长的支持!'
        ];
        $url_parent = '';
        $wx = new \App\Helper\Wx();
        $wx->send_template_msg($parent_wx_openid, $parent_template_id, $data_parent, $url_parent);


        // 给助教// 销售 // 教务[试听课] 推送结果
        // $wx_openid_arr[0] = $this->t_lesson_info_b2->get_ass_wx_openid($lessonid);
        // $wx_openid_arr[1] = $this->t_lesson_info_b2->get_seller_wx_openid($lessonid);
        $lesson_type = $this->t_lesson_info->get_lesson_type($lessonid);


        if($lesson_type == 0){ //常规课
            $wx_openid_arr[0]    = $this->t_lesson_info_b2->get_ass_wx_openid($lessonid);
            $wx_openid_arr[1]    = $this->t_lesson_info_b2->get_seller_wx_openid($lessonid);
        }elseif($lesson_type == 2){ // 试听课
            $wx_openid_arr[] = $this->t_test_lesson_subject_require->get_cur_require_adminid_by_lessonid($lessonid);
        }


        if($lesson_type == 2){ // 只有试听课才会有教务 [常规课由助教直接排课]
            $wx_openid_arr[2] = $this->t_test_lesson_subject_sub_list->get_jiaowu_wx_openid($lessonid);
        }

        $data_leo = [
            'keyword1' => "$lesson_name",
            'keyword2'  => '原时间:'.$lesson_old_time.' 修改后时间'. $lesson_new_time,
            'keyword3' => "$stu_nick",
            'remark'   => "请注意调整您的时间安排!"
        ];

        $url_leo = '';
        foreach($wx_openid_arr as $item_openid ){
            $wx->send_template_msg($item_openid, $parent_template_id, $data_leo, $url_leo);
        }

    }


    /**
     * @ 标签系统 微信推送
     */

    public function get_student_info(){ // 获取向老师推送的模板数据
        $lessonid  = $this->get_in_int_val("lessonid");
        $test_info = $this->t_lesson_info_b3->get_student_info_to_tea($lessonid);
        return $this->output_succ(['data'=>$test_info]);
    }


    /**
     * @ 标签 Tom对接
     * @
     */
    public function get_test_lesson_info(){
        $lessonid  = $this->get_in_int_val('lessonid',-1);
        $ret_info  = $this->t_test_lesson_subject->get_test_require_info($lessonid);
        $ret_info['teacherid'] = $this->t_lesson_info->get_teacherid($lessonid);

        // $checkIsFullTime = $this->t_teacher_info->checkIsFullTime($ret_info['teacherid']);
        $success_flag = $this->t_test_lesson_subject_sub_list->get_success_flag($lessonid);
        if($ret_info['lesson_del_flag']==1 || $success_flag==2){
            $ret_info['status'] = 2;
        }

        $ret_info['subject_str'] = E\Esubject::get_desc($ret_info['subject']);
        $ret_info['grade_str']   = E\Egrade::get_desc($ret_info['grade']);
        $ret_info['lesson_time_str'] = date('m-d H:i',$ret_info['lesson_start'])." ~ ".date('H:i',$ret_info['lesson_end']);
        $ret_info['gender_str'] = E\Egender::get_desc($ret_info['gender']);

        $default_tag = "无要求";
        $subject_tag_arr = json_decode($ret_info['subject_tag'],true);
        $ret_info['style'] = $subject_tag_arr['风格性格']?$subject_tag_arr['风格性格']:$default_tag;
        $ret_info['major'] = $subject_tag_arr['专业能力']?$subject_tag_arr['专业能力']:$default_tag;

        if($ret_info['tea_identity']){
            $ret_info['identity'] = E\Eidentity::get_desc($ret_info['tea_identity']);
        }else{
            $ret_info['identity'] = $default_tag;
        }

        $ret_info['atmosphere'] = $subject_tag_arr['课堂气氛']?$subject_tag_arr['课堂气氛']:$default_tag;
        $ret_info['courseware'] = $subject_tag_arr['课件要求']?$subject_tag_arr['课件要求']:$default_tag;
        $subject_tag_arr['学科化标签'] = rtrim($subject_tag_arr['学科化标签'],',');
        $ret_info['subject_tag'] = $subject_tag_arr['学科化标签']?$subject_tag_arr['学科化标签']:$default_tag;

        // 数据待确认
        // if($checkIsFullTime == 1){// 全职老师可以看
            $checkHasHandout = $this->t_lesson_info->get_tea_cw_url($lessonid);
            $resource_id_arr = $this->t_resource->getResourceId($ret_info['subject'],$ret_info['grade']);
            $resource_id_str = '';
            foreach($resource_id_arr as $item){
                $resource_id_str.=$item['resource_id'].",";
            }
            $resource_id_str = rtrim($resource_id_str,',');
            $ret_info['resource_id_str'] = $resource_id_str;

            $hasResourceId = $this->t_lesson_info_b3->getResourceId($lessonid);
            if($hasResourceId>0){
                $ret_info['handout_flag'] = 1;

            }elseif(!empty($resource_id_arr) && !$checkHasHandout){
                    $ret_info['handout_flag'] = 1;

            }else{
                $ret_info['handout_flag'] = 0;

            }
        // }else{
        //     $ret_info['handout_flag'] = 0; //无讲义
        // }

        return $this->output_succ(["data"=>$ret_info]);
    }

    public function getResourceList(){ // 讲义系统
        $resource_id_str  = $this->get_in_str_val('resource_id');
        $lessonid     = $this->get_in_int_val('lessonid');
        $file_id = $this->t_lesson_info->get_tea_cw_file_id($lessonid);

        if($file_id>0){
            $resourceList = $this->t_resource_file->getResoureInfoById($file_id);
        }else{
            $resourceList = $this->t_resource_file->getResoureList($resource_id_str);
        }

        foreach($resourceList as &$item){
            $item['file_type_str'] = E\Efile_type::get_desc($item['file_type']);
            $item['level'] = E\Eresource_diff_level::get_desc($item['tag_three']);
        }

        return $this->output_succ(["resourceList"=>$resourceList]);
    }

    public function chooseResource(){
        $file_id   = $this->get_in_int_val('file_id');
        $teacherid = $this->get_in_int_val("teacherid");
        $lessonid  = $this->get_in_int_val("lessonid");

        $ret_info['checkIsUse'] = $this->t_lesson_info_b3->checkIsUse($lessonid);
        $ret_info['wx_index']  = $this->t_resource_file->get_filelinks($file_id);


        $this->t_resource_file_visit_info->row_insert([ // 增加浏览记录
            'file_id'      => $file_id,
            'visitor_type' => 1,
            'visitor_id'   => $teacherid,
            'create_time'  => time(),
            'ip'           => $_SERVER["REMOTE_ADDR"],
        ]);

        if($ret_info['checkIsUse']){
            return $this->output_succ(["data"=>$ret_info]);
        }

        $this->t_resource_file->add_num("visit_num", $file_id);

        return $this->output_succ(["data"=>$ret_info]);
    }

    /**
     * @ 获取fie_id
     * @ 更新lesson_info 中的字段
     * @
     */
    public function useResource(){
        $lessonid = $this->get_in_int_val("lessonid");
        $file_id  = $this->get_in_int_val('file_id');
        $teacherid = $this->t_lesson_info->get_teacherid($lessonid);
        $resource_id = $this->t_resource_file->get_resource_id($file_id);

        $resourceFileInfo = $this->t_resource_file->getResourceFileInfoById($resource_id);
        $this->t_resource_file_visit_info->row_insert([ //使用
            'file_id'      => $file_id,
            'visit_type'   => 7,
            'visitor_type' => 1,
            'visitor_id'   => $teacherid,
            'create_time'  => time(),
            'ip'           => $_SERVER["REMOTE_ADDR"],
        ]);

        $this->t_resource_file->add_num("use_num", $file_id); //增加使用次数
        // 更新lesson_info 表中信息 授课讲义存入lesson_info 老师讲义
        $pdfToImg = '';
        foreach($resourceFileInfo as $i=> $item){
            if($item['file_use_type'] == 0){ //
                $teaFileId = $item['file_id'];
                $pdfToImg  = $item['file_link'];
                $this->t_lesson_info->field_update_list($lessonid, [
                    "tea_cw_url" => $item['file_link']
                ]);
                unset($resourceFileInfo[$i]);
            }elseif($item['file_use_type'] == 2){
                $stuFileId = $item['file_id'];
                $this->t_lesson_info->field_update_list($lessonid, [
                    "stu_cw_url" => $item['file_link']
                ]);
                unset($resourceFileInfo[$i]);
            }
        }

        foreach($resourceFileInfo as $i => $item){
            $sort[$i] = $item['file_type'];
        }
        array_multisort($sort,SORT_ASC,$resourceFileInfo);//此处对数组进行降序排列；SORT_DESC按降序排列
        $filelinks = $this->t_resource_file->get_filelinks($file_id);
        $this->t_lesson_info->field_update_list($lessonid, [
            "tea_more_cw_url" => json_encode($resourceFileInfo),
            "tea_cw_origin"   => 3, // 理优资源
            "stu_cw_origin"   => 3,// 理优资源
            "tea_cw_file_id"  => $teaFileId,
            "stu_cw_file_id"  => $stuFileId,
            "tea_cw_pic"      => $filelinks,
            "tea_cw_status"   => 1,
            "stu_cw_status"   => 1,
            "tea_cw_upload_time" => time()
        ]);

        $courseid = $this->t_lesson_info->get_courseid($lessonid);
        $this->t_homework_info->field_update_list($courseid, [
            "work_status"   => 1,
            "issue_origin"  => 3,
            "issue_file_id" => $stuFileId
        ]);
        return $this->output_succ();
    }



    public function get_pdf_url($pdf_file_path, $lessonid,$pdf_url){
        $savePathFile = public_path('wximg').'/'.$pdf_url;

        if($pdf_url){
            \App\Helper\Utils::savePicToServer($pdf_file_path,$savePathFile);
            $path = public_path().'/wximg';

            @chmod($savePathFile, 0777);
            $imgs_url_list = @$this->pdf2png($savePathFile,$path,$lessonid);

            // dd($imgs_url_list);
            $file_name_origi = array();
            foreach($imgs_url_list as $item){
                $file_name_origi[] = @$this->put_img_to_alibaba($item);
            }

            $file_name_origi_str = implode(',',$file_name_origi);
            $ret = $this->t_lesson_info->save_tea_pic_url($lessonid, $file_name_origi_str);

            foreach($imgs_url_list as $item_orgi){
                @unlink($item_orgi);
            }
            @unlink($savePathFile);
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

    //
    public function pdf2png($pdf,$path, $lessonid){

        if(!extension_loaded('imagick')){
            return false;
        }
        if(!$pdf){
            return false;
        }
        $IM =new \imagick();
        $IM->setResolution(100,100);
        $IM->setCompressionQuality(100);

        $is_exit = file_exists($pdf);

        if($is_exit){
            @$IM->readImage($pdf);
            foreach($IM as $key => $Var){
                @$Var->setImageFormat('png');
                $Filename = $path."/l_t_pdf_".$lessonid."_".$key.".png" ;
                if($Var->writeImage($Filename)==true){
                    $Return[]= $Filename;
                }
            }
            return $Return;
        }else{
            return [];
        }

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

            \App\Helper\Utils::logger('shangchun55'. $config["public"]["url"]."/".$file_name);

            return $config["public"]["url"]."/".$file_name;

        } catch (OssException $e) {
            \App\Helper\Utils::logger( "init OssClient fail");
            return "" ;
        }

    }







    public function update_accept_status(){ //更新接受状态并发送微信推送
        $lessonid = $this->get_in_int_val('lessonid');
        $status   = $this->get_in_int_val('status');

        $lesson_info = $this->t_lesson_info_b3->get_lesson_info_for_tag($lessonid);
        $tea_nick = $this->cache_get_teacher_nick($lesson_info['teacherid']);
        $subject_str = E\Esubject::get_desc($lesson_info['subject']);
        $stu_nick = $this->cache_get_student_nick($lesson_info['userid']);
        $jw_nick  = $this->cache_get_account_nick($lesson_info['accept_adminid']);
        $lesson_time_str = date('m-d H:i',$lesson_info['lesson_start'])." ~ ".date("H:i",$lesson_info['lesson_end']);

        $checkStatus = $this->t_lesson_info->get_accept_status($lessonid);
        if($checkStatus>0){
            return $this->output_succ(["status"=>$checkStatus]);
        }

        if($status == 1){ //接受 []
            /**
             * @ 教务排课的推送 家长 | CC推送需要取消
             **/
            $data = [
                "first" => "$stu_nick 同学的试听课排课成功",
                "keyword1" => "排课成功提醒",
                "keyword2" => "\n 学员姓名:$stu_nick \n 老师姓名:$tea_nick \n 教务姓名:$jw_nick \n 上课时间:$lesson_time_str",
                "keyword3" => date("Y-m-d H:i:s"),
            ];

            $url = "http://wx-teacher-web.leo1v1.com/teacher_info.html?lessonid=".$lessonid;

            $wx = new \App\Helper\WxSendMsg();
            $wx->send_ass_for_first($lesson_info['wx_openid'], $data, $url);


            $parentid = $this->t_student_info->get_parentid_by_lessonid($lessonid);
            // $parentid = 271968;//james
            if($parentid>0){
                $this->t_parent_info->send_wx_todo_msg($parentid,"课程反馈","您的试听课已预约成功!", "上课时间[$lesson_time_str]","http://wx-parent.leo1v1.com/wx_parent/index", "点击查看详情" );
            }

        }else{ // 拒绝
            /**
             * @ 给教务发送微信推送
             **/

            $this->t_lesson_info->field_update_list($lessonid,[
                "lesson_del_flag" => 1,
            ]);
            $this->t_test_lesson_subject_sub_list->field_update_list($lessonid,[
                "confirm_time"           => time(NULL),
                "success_flag"           => 2,
                "fail_reason"            => "老师微信端拒绝课程",
                "test_lesson_fail_flag"  => 113, // [不付] 老师个人原因取消
            ]);

            $require_id = $this->t_test_lesson_subject->get_test_lesson_subject_id_by_lessonid($lessonid);
            $this->t_test_lesson_subject_require->field_update_list($require_id, [
                "test_lesson_student_status" => 120
            ]);


            $data = [
                "first" => "$stu_nick 同学的试听课已拒绝",
                "keyword1" => "老师拒绝试听课程",
                "keyword2" => $stu_nick."同学".$lesson_time_str."的[".$subject_str."]试听课已被".$tea_nick."老师拒绝，请尽快重新排课",
                "keyword3" => date("Y-m-d H:i:s"),
            ];
            $url = "http://admin.leo1v1.com/seller_student_new2/test_lesson_plan_list_jx";
            $wx = new \App\Helper\WxSendMsg();
            $jw_openid = $this->t_manager_info->get_wx_openid($lesson_info['accept_adminid']);
            $wx->send_ass_for_first($jw_openid, $data, $url);
        }

        $this->t_lesson_info->field_update_list($lessonid, [
            "accept_status"=>$status
        ]);

        return $this->output_succ(["status"=>$status]);
    }

    public function get_test_teacher_info(){ //排课人推送 点击详情数据接口
        $lessonid = $this->get_in_int_val('lessonid');
        $teacher_info = $this->t_teacher_info->get_test_teacher_info($lessonid);
        $teacher_info['tea_gender_str'] = E\Egender::get_desc($teacher_info['tea_gender']);
        $teacher_info['identity_str'] = E\Eidentity::get_desc($teacher_info['identity']);
        $tag_arr = @array_keys(json_decode($teacher_info['teacher_tags'],true));

        $arr_text= explode(",",@$teacher_info["teacher_textbook"]);
        $tea_info['textbook'] = '';
        foreach($arr_text as $val){
            @$tea_info["textbook"] .=  E\Eregion_version::get_desc ($val).",";
        }
        $teacher_info["textbook_type_str"] = trim($tea_info["textbook"],",");

        $tag_l1_sort  = '教师相关';
        $tag_l2_sort  = '风格性格';
        $tag_lib_arr_two = $this->t_tag_library->getTeacherCharacter($tag_l1_sort, $tag_l2_sort);
        $tag_list = [];
        foreach($tag_lib_arr_two as $v){
            $tag_list[] = $v['tag_name'];
        }
        $tea_label_arr = array_intersect($tag_list,$tag_arr);
        $tea_label_type_str = "";
        foreach($tea_label_arr as $v){
            $tea_label_type_str.=$v." ";
        }

        $teacher_info['tea_label_str'] = $tea_label_type_str;
        return $this->output_succ(["data"=>$teacher_info]);
    }


    /**
     * @需求 ppt => h5
     * @使用 此接口为微演示服务商调用, 返回ppt转化状态
     */
    public function getConversionStatus(){
        $uuid = $this->get_in_str_val('uuid');
        $status = $this->get_in_str_val('s');
        if($status == 1){
            $status = 0;
        }else{
            $status = 1;
        }

        $this->t_deal_ppt_to_h5->updateStatusByUuid($uuid,$status);
        return $this->output_succ();
        // $stu_lessonid = $this->t_lesson_info_b3->checkIsStu($uuid);
        // $tea_lessonid = $this->t_lesson_info_b3->checkIsTea($uuid);
        // if($stu_lessonid){
        //     $this->t_lesson_info_b3->field_update_list($stu_lessonid, [
        //         "ppt_status_stu" => $status
        //     ]);
        // }elseif($tea_lessonid){
        //     $this->t_lesson_info_b3->field_update_list($tea_lessonid, [
        //         "ppt_status" => $status
        //     ]);
        // }

    }


    public function updateStatusByUuid($uuid,$status){
        $sql = $this->gen_sql_new("  update %s set ppt_status=$status where uuid='$uuid'"
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_update($sql);
    }



    /**
     * @ 老师圣诞节活动 积分
     * @ 老师分享链接后 后续人员点击链接+1
     * @ 从分享链接注册进来的老师 则分享人积分+10
     * @
     * @
     **/

    public function christmasTeaLink () {
        $Tea_appid     = \App\Helper\Config::get_teacher_wx_appid();
        $Tea_appsecret = \App\Helper\Config::get_teacher_wx_appsecret();
        $shareId = $this->get_in_str_val("shareId");

        $wx= new \App\Helper\Wx($Tea_appid,$Tea_appsecret);
        $redirect_url=urlencode("http://wx-teacher.leo1v1.com/wx_teacher_api/rewriteLink?shareId=".$shareId );
        $wx->goto_wx_login($redirect_url);
    }

    public function rewriteLink(){
        $Tea_appid     = \App\Helper\Config::get_teacher_wx_appid();
        $Tea_appsecret = \App\Helper\Config::get_teacher_wx_appsecret();
        $shareId = $this->get_in_str_val('shareId');

        $code       = $this->get_in_str_val('code');
        $wx         = new \App\Helper\Wx($Tea_appid,$Tea_appsecret);
        $token_info = $wx->get_token_from_code($code);
        $currentId  = @$token_info["openid"];


        /**
         * @ 老用户首次进入时 获得20积分
         */
        // $currentTeacherId = $this->t_teacher_info->get_teacherid_by_openid($currentId);
        // $train_through_new_time = $this->t_teacher_info->get_train_through_new_time();
        // $checkTime = strtotime('2017-12-25');

        // if($shareId == 0 && $currentTeacherId  == 0){ // 分享人和自己都是非老师
        //     header("Location: http://wx-teacher-web.leo1v1.com/tea.html?reference=?shareId=".$shareId ."&currentId=".$currentId);//链接待定
        //     return ;
        // }else{
        header("Location: http://wx-parent-web.leo1v1.com/teachris/index.html?shareId=".$shareId ."&currentId=".$currentId);
        return ;
        // }
        // $token      = $wx->get_wx_token($Tea_appid,$Tea_appsecret);
        // $user_info  = $wx->get_user_info($openid,$token);
        // $subscribe = @$user_info['subscribe'];
        // $parentid = $this->t_parent_info->get_parentid_by_wx_openid($openid);
    }


    /**
     * @ 记录积分值
     * @ type: 积分来源类型 0:点击 +2 1:分享 +1 2:注册 +10 3:赠送 +20
     * @ 重复点击不算入计算
     **/
    public function addClickLog(){
        $shareId = $this->get_in_str_val('shareId');//分享人openid
        $currentId = $this->get_in_str_val('currentId');
        $checkScore= 0;

        \App\Helper\Utils::logger("addClickLog111: $shareId ");


        $isHasAdd = $this->t_teacher_christmas->checkHasAdd($shareId,$currentId,$checkScore);
        if(!$isHasAdd && $shareId){
            $this->t_teacher_christmas->row_insert([
                "shareId"   => $shareId,
                "currentId" => $currentId,
                "add_time"    => time(),
                "score"       => 2,
                "type"        => 0
            ]);
        }
        return $this->output_succ();
    }


    /**
     * @ 记录积分值
     * @ type: 积分来源类型 0:点击 +2 1:分享 +1 2:注册 +10 3:赠送 +20
     * @ 非注册用户可以转发但不计入排名统计
     * @ 给上级+2 自己+1
     * @
     **/
    public function shareClickLog(){
        $currentId = $this->get_in_str_val('currentId');
        // $currentTeacherId = $this->t_teacher_info->get_teacherid_by_openid($currentId);

        \App\Helper\Utils::logger("shareClickLog2222: $currentId ");

        if ($currentId == 'oJ_4fxCmcY4CKtE7YY9xrBt2DiB0' || $currentId == 'oJ_4fxAjIZGjUxy4Gk4mW8wR3vmM' || $currentId == 'oJ_4fxIcdLLlk9BisycIAuUFXhP4') {
            return $this->output_succ();
        }

        if($currentId){ // 若自己已经是老师 分享+1
            $this->t_teacher_christmas->row_insert([
                "shareId"   => $currentId,
                "currentId" => '',
                "add_time"    => time(),
                "score"       => 1,
                "type"        => 1 //分享
            ]);
        }

        return $this->output_succ();
    }

    public function getShareDate(){
        $openid = $this->get_in_str_val('openid');

        $start_time = strtotime("2017-12-23");
        $ret_info = $this->t_teacher_christmas->getChriDate($openid,$start_time);
        $ret_info['totalList'] = $this->t_teacher_christmas->getTotalList();
        $ret_info['end_time'] = strtotime('2018-1-2')-time();
        $phone = $this->t_teacher_info->get_phone_by_wx_openid($openid);
        if($phone>0){
            $ret_info['currentPhone'] = substr($phone,0,3)."****".substr($phone,7);
        }else{
            $ret_info['currentPhone'] = 0;
        }

        $ret_info['ranking'] = 0;
        foreach($ret_info['totalList'] as $i => &$item){
            if($item['shareId'] == $openid){
                $ret_info['ranking'] = $i+1;
            }
            $item['phone'] = substr($item['phone'],0,3)."****".substr($item['phone'],7);

            // $userInfo = UserManage::getUserInfo($item['wx_openid']);
            // $item['wx_nick'] = @$userInfo['nickname'];
        }

        $ret_info['ranking'] = $ret_info['ranking']?$ret_info['ranking']:0;
        $ret_info['click_num'] = $ret_info['click_num']?$ret_info['click_num']:0;
        $ret_info['share_num'] = $ret_info['share_num']?$ret_info['share_num']:0;
        $ret_info['register_num'] = $ret_info['register_num']?$ret_info['register_num']:0;
        $ret_info['register_num'] = $ret_info['register_num']?$ret_info['register_num']:0;
        $ret_info['currentScore'] = $ret_info['currentScore']?$ret_info['currentScore']:0;

        return $this->output_succ($ret_info);
    }


    //兼职老师晋升相关接口 #增加老师姓名
    public function get_teacher_advance_info_for_wx(){
        // $teacherid = $this->get_in_int_val("teacherid");
        $teacherid  = $this->get_teacherid();
        if(!$teacherid){
            return $this->output_err("老师id缺失!");
        }
        $season     = ceil((date('n'))/3);//上季度是第几季度
        $start_time = strtotime(date('Y-m-d H:i:s',mktime(0, 0, 0,$season*3-3+1,1,date('Y'))));
        $info = $this->t_teacher_advance_list->field_get_list_2($start_time, $teacherid, "*");
        $data=[];
        //判断2018年1月1日之后是否有课
        $start=strtotime("2018-01-01");
        $lesson_flag = $this->t_lesson_info_b3->get_tea_lesson_total($start,time(),$teacherid);
        $tea_info = $this->t_teacher_info->field_get_list($teacherid,"*");
        if(@$lesson_flag["stu_num"]>0 && $tea_info["teacher_money_type"]==6){
            $show_flag=1;//是否显示晋升数据一级规则 1,显示
            $score = @$info["total_score"]/100;
            $level = @$info["level_before"]?@$info["level_before"]:$tea_info["level"];
            list($level_degree,$level_score_info)=$this->get_tea_level_str($score,$level);
            $face = $this->get_tea_face_url_for_wx($tea_info);
            $lesson_score = @$info["lesson_count_score"]/100;
            $tea_score = @$info["record_final_score"]/100;
            $cc_score = @$info["cc_order_score"]/100;
            $cr_score = @$info["other_order_score"]/100;
            $c_score = $cc_score+$cr_score;
            if($c_score>10){
                $c_score=10;
            }
            $stu_score = $c_score+@$info["stu_num_score"]/100;
            $data["total_score"] =  $score;
            $data["level"]       =  $level;
            $data["level_degree"]=  $level_degree;
            $data["level_score_info"]=  $level_score_info;
            $data["face"]            =  $face;
            $data["lesson_score"]    =  $lesson_score;
            $data["tea_score"]=  $tea_score;
            $data["stu_score"]=  $stu_score;
            $data["tea_nick"] = $this->t_teacher_info->get_nick($teacherid);
        }else{
            $show_flag=0;
        }

        return $this->output_succ(["list"=>$data,"show_flag"=>$show_flag]);
    }


    public function get_teacher_lesson_count(){
        $teacherid  = $this->get_teacherid();
        if(!$teacherid){
            return $this->output_err("老师id缺失!");
        }
        $start_time = strtotime(date("Y-m-d",time()));
        //$end_time   = $start_time + 86400;
        $end_time   = time();
        //$teacherid  = 202149;

        $total_count = $this->t_lesson_info_b2->get_teacher_lesson_total($teacherid,$start_time,$end_time);
        $consume_count = $this->t_teacher_spring->get_total($teacherid,$start_time,$end_time);

        $count = $total_count + 1 - $consume_count;
        if($count < 0 ){
            $count = 0;
        }
        return $this->output_succ(['count'=>$count]);
    }

    public function draw_lottery(){
        $teacherid  = $this->get_teacherid();
        if(!$teacherid){
            return $this->output_err("老师id缺失!");
        }

        $start_time = strtotime(date("Y-m-d",time()));
        //$end_time   = $start_time + 86400;
        $end_time   = time();
        //$teacherid  = 202149;

        $total_count = $this->t_lesson_info_b2->get_teacher_lesson_total($teacherid,$start_time,$end_time);
        $consume_count = $this->t_teacher_spring->get_total($teacherid,$start_time,$end_time);

        $count = $total_count + 1 - $consume_count;
        if($count < 1 ){
            return $this->output_err("抽奖次数已用完!");
        }

        $rank = $this->t_teacher_spring->get_last_rank($start_time);
        if(!$rank){
            $rank = 0;
        }
        $result = 0;
        if($rank >= 0){
            $rank = $rank + 1;
            if($rank == 10 || $rank == 30 || $rank == 50
            || $rank == 70 || $rank == 90 || $rank == 110){
                $result = 1;
            }
        }
        
        $ret = $this->t_teacher_spring->row_insert([
            'teacherid' => $teacherid,
            'add_time'  => time(),
            'rank'      => $rank,
            'result'    => $result,
        ]);

        return $this->output_succ(['result'=>$result,'rank'=>$rank]);
    }
}
