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
                // "orwGAs_IqKFcTuZcU1xwuEtV3Kek" //james
                "orwGAswyJC8JUxMxOVo35um7dE8M", // QC wenbin
                "orwGAsyyvy1YzV0E3mmq7gBB3rms", // QC 李珉劼 
                "orwGAs0ayobuEtO1YZZhW3Yed2To",  // rolon
                "orwGAs4FNcSqkhobLn9hukmhIJDs",  // ted or erick
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




}
