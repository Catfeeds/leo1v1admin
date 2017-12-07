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
                return $this->output_err();
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

        if($lesson_info['lesson_del_flag']==1){
            $lesson_info['status'] = 2;
        }

        $ret_info['subject_str'] = E\Esubject::get_desc($ret_info['subject']);
        $ret_info['grade_str']   = E\Egrade::get_desc($ret_info['grade']);
        $ret_info['lesson_time_str'] = date('m-d H:i',$ret_info['lesson_start'])." ~ ".date('H:i',$ret_info['lesson_end']);
        $ret_info['gender_str'] = E\Egender::get_desc($ret_info['gender']);

        //上课要求标签[未定]
        $ret_info['style'] = '风格标签';
        $ret_info['major'] = '专业标签';
        $ret_info['identity'] = '身份标签';
        $ret_info['atmosphere'] = '课堂氛围标签';
        $ret_info['courseware'] = '课件要求';
        //学科化内容标签[未定]
        $ret_info['subject_tag_a'] = '学科标签A';
        $ret_info['subject_tag_b'] = '学科标签B';

        // 数据待确认
        $ret_info['handout_flag'] = 0; //无讲义

        return $this->output_succ(["data"=>$ret_info]);
    }

    public function get_resource_list(){ // 讲义系统 boby

    }

    public function update_accept_status(){ //更新接受状态并发送微信推送
        $lessonid = $this->get_in_int_val('lessonid');
        $status   = $this->get_in_int_val('status');

        $lesson_info = $this->t_lesson_info_b3->get_lesson_info_for_tag($lessonid);
        $tea_nick = $this->cache_get_teacher_nick($lesson_info['teacherid']);
        $subject_str = E\Esubject::get_desc($lesson_info['subject']);
        $stu_nick = $this->cache_get_teacher_nick($lesson_info['userid']);
        $jw_nick  = $this->cache_get_account_nick($lesson_info['accept_adminid']);
        $lesson_time_str = date('m-d H:i',$lesson_info['lesson_start'])." ~ ".date("H:i",$lesson_info['lesson_end']);

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
            $url = "http://wx-teacher-web.leo1v1.com/student_info.html?lessonid=".$lessonid; //待定

            $wx = new \App\Helper\WxSendMsg();
            $wx->send_ass_for_first("orwGAs_IqKFcTuZcU1xwuEtV3Kek", $data, $url);//james
            // $wx->send_ass_for_first($lesson_info['wx_openid'], $data, $url);


            // $parentid = $this->t_student_info->get_parentid_by_lessonid($lessonid);
            $parentid = 271968;//james
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


            $data = [
                "first" => "$stu_nick 同学的试听课已拒绝",
                "keyword1" => "老师拒绝试听课程",
                "keyword2" => $stu_nick."同学".$lesson_time_str."的[".$subject_str."]试听课已被".$tea_nick."老师拒绝，请尽快重新排课",
                "keyword3" => date("Y-m-d H:i:s"),
            ];
            $url = "http://admin.leo1v1.com/seller_student_new2/test_lesson_plan_list_jx";
            $wx = new \App\Helper\WxSendMsg();
            $jw_openid = $this->t_manager_info->get_wx_openid($lesson_info['accept_adminid']);
            $wx->send_ass_for_first("orwGAs_IqKFcTuZcU1xwuEtV3Kek", $data, $url);//james
           // $wx->send_ass_for_first($jw_openid, $data, $url);
        }

        $require_id = $this->t_test_lesson_subject_sub_list->get_require_id($lessonid);
        $this->t_test_lesson_subject_require->field_update_list($require_id, [
            "accept_status"=>$status
        ]);

        return $this->output_succ(["status"=>$status]);
    }

    public function get_test_teacher_info(){ //排课人推送 点击详情数据接口
        $lessonid = $this->get_in_int_val('lessonid');
        $teacher_info = $this->t_teacher_info->get_test_teacher_info($lessonid);
        dd($teacher_info);
        $teacher_info['tea_gender_str'] = E\Egender::get_desc($teacher_info['tea_gender']);
        $teacher_info['identity_str'] = E\Eidentity::get_desc($teacher_info['identity']);
        $teacher_info['textbook_type_str'] = E\Etextbook_type::get_desc($teacher_info['textbook_type']);


        $tea_label_type_arr = json_decode($teacher_info['tea_label_type'],true);
        $tea_label_type_str = "";

        if($tea_label_type_arr){
            foreach($tea_label_type_arr as $item){
                $tea_label_type_str.=E\Etea_label_type::get_desc($item)."  ";
            }
        }

        $teacher_info['harvest'] = "教学成果";
        $teacher_info['evaluate'] = "家长/学元评价";

        $teacher_info['tea_label_str'] = $tea_label_type_str;
        return $this->output_succ(["data"=>$teacher_info]);
    }



}
