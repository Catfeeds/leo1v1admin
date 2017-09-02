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
            $url = 'http://admin.yb1v1.com/user_manage/qc_complaint/';
            $wx=new \App\Helper\Wx();

            $qc_openid_arr = [
                // "orwGAs_IqKFcTuZcU1xwuEtV3Kek" ,//james
                "orwGAswyJC8JUxMxOVo35um7dE8M", // QC wenbin
                "orwGAsyyvy1YzV0E3mmq7gBB3rms", // QC 李珉劼
                "orwGAs2Cq6JQKTqZghzcv3tUE5dU", // 王浩鸣
                "orwGAs0ayobuEtO1YZZhW3Yed2To",  // rolon
                "orwGAs4FNcSqkhobLn9hukmhIJDs",  // ted or erick
                "orwGAs1H3MQBeo0rFln3IGk4eGO8",  // sunny
                "orwGAswxkjf1agdPpFYmZxSwYJsI" // coco 老师 [张科]
            ];

            foreach($qc_openid_arr as $qc_item){
                // $wx->send_template_msg($qc_item,$template_id,$data_msg ,$url);
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
        $complaint_type   = 1;

        $sever_name = $_SERVER['SERVER_NAME'];

        \App\Helper\Utils::logger("wx_tousu: ".$teacherid);

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
                "first"     => "$opt_nick 老师发布了一条投诉",
                "keyword1"  => "常规投诉",
                "keyword2"  => "老师投诉内容:$complaint_info",
                "keyword3"  => "投诉时间 $log_time_date ",
            ];
            $url = 'http://admin.yb1v1.com/user_manage/qc_complaint/';
            $wx=new \App\Helper\Wx();

            $qc_openid_arr = [
                // "orwGAs_IqKFcTuZcU1xwuEtV3Kek" ,//james
                "orwGAswyJC8JUxMxOVo35um7dE8M", // QC wenbin
                "orwGAsyyvy1YzV0E3mmq7gBB3rms", // QC 李珉劼
                "orwGAs2Cq6JQKTqZghzcv3tUE5dU", // 王浩鸣
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
        $serverId_str      = $this->get_in_str_val('serverId_str',''); // 图片ids
        $teacherid         = $this->get_teacherid();
        \App\Helper\Utils::logger("wx_software: ".$teacherid);


        $sever_name = $_SERVER["SERVER_NAME"];

        $ret_arr = \App\Helper\Utils::deal_feedback_img($serverId_str,$sever_name);
        $complaint_img_url = $ret_arr['alibaba_url_str'];

        //插入到投诉数据库中
        $account_type = '2'; // 投诉人身份 [老师]
        $ret_info_qc = $this->t_complaint_info->row_insert([
            'complaint_type' => $complaint_type,
            'userid'         => $teacherid,
            'account_type'   => $account_type,
            'add_time'       => time(NULL),
            'complaint_info' => $complaint_info,
            'complaint_img_url' => $complaint_img_url,
            // 'complained_feedback_type' => 2
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

        $test_lesson_info['pass_time'] = date("Y.m.d",$test_lesson_info["work_day"]);
        $test_lesson_info["work_day"] = ceil((time()-$test_lesson_info["work_day"])/86400);
        $test_lesson_info["test_lesson_time"] = date("Y.m.d",$test_lesson_info['test_lesson_time']);

        $common_lesson_info = $this->t_teacher_info->get_common_lesson_info_for_teacher_day($teacherid);
        $common_lesson_info["common_lesson_start"] = date("Y.m.d",$common_lesson_info['common_lesson_start']);
        $common_lesson_num = $this->t_teacher_info->get_common_lesson_num_for_teacher_day($teacherid);

        $stu_num = $this->t_teacher_info->get_student_num_for_teacher_day($teacherid);

        $ret_info = array_merge($test_lesson_info, $common_lesson_info, $common_lesson_num, $stu_num);

        $url = "http://admin.yb1v1.com/teacher_money/get_teacher_total_money?type=admin&teacherid=".$teacherid;
        $ret =\App\Helper\Utils::send_curl_post($url);
        $ret = json_decode($ret,true);
        if(isset($ret) && is_array($ret) && isset($ret["data"][0]["lesson_price"])){
            $money = $ret["data"][0]["lesson_price"];
        }else{
            $money = 0;
        }

        $ret_info['money'] = $money.'元';

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

        $url = "http://admin.yb1v1.com/teacher_money/get_teacher_total_money?type=admin&teacherid=".$teacherid;
        $ret =\App\Helper\Utils::send_curl_post($url);
        $ret = json_decode($ret,true);
        if(isset($ret) && is_array($ret) && isset($ret["data"][0]["lesson_price"])){
            $money = $ret["data"][0]["lesson_price"];
        }else{
            $money = 0;
        }

        return $this->output_succ(["money"=>$money]);
    }


    public function teacher_day_luck_draw(){ //教师节抽奖活动//
        $teacherid = $this->get_teacherid();
    }


}
