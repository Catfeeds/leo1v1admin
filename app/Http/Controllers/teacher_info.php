<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;
//require_once (app_path("Libs/qiniu-7/src/Qiniu/functions.php") );


class teacher_info extends Controller
{
    use CacheNick;
    var $check_login_flag=true;

    function __construct( )  {
        parent::__construct();

    }

    function check_login() {
        if (!session("tid")){
            if (!\App\Helper\Utils::check_env_is_test()) {
                \App\Helper\Utils::logger("GOTO: " .$_SERVER["REQUEST_URI"] );
                header('Location: /login/teacher?to_url='.$_SERVER["REQUEST_URI"]);
                exit;
            }else{
            }
        }
    }


    public function index() {
        return self::get_lesson_list_new();
    }



    public function get_lesson_list_new() {
        $teacherid   = $this->get_login_teacher();
        $userid      = $this->get_in_int_val("userid",-1);
        $start_date  = $this->get_in_str_val('start_date',date('Y-m-d', time(NULL) ));
        $end_date    = $this->get_in_str_val('end_date',date('Y-m-d', time(NULL)+86400*7 ));
        $lesson_type = $this->get_in_int_val('lesson_type',-1);

        $start_time = strtotime($start_date);
        $end_time   = strtotime($end_date)+86400;
        $lesson_type_in_str = "";
        switch ($lesson_type){
        case  E\Econtract_type::V_0:
            $lesson_type_in_str="0,1,2,3";
            break;
        case  E\Econtract_type::V_2 :
            $lesson_type_in_str="2";
            break;
        case  E\Econtract_type::V_1100:
            $lesson_type_in_str="1100";
            break;
        case  E\Econtract_type::V_1001 :
            $lesson_type_in_str="1001,1002,1003";
            break;
        case  E\Econtract_type::V_3001 :
            $lesson_type_in_str="3001";
            break;
        default:
            break;
        }

        $get_flag_color_func = function($v){
            if ($v)  {
                $color="green";
            }else{
                $color="red";
            }
            $desc = E\Eboolean::get_desc($v);
            return "<font color=$color>$desc</font>";
        };
        $ret_info = $this->t_lesson_info_b2->get_teacher_lesson_list_www(
            $teacherid,$userid,$start_time,$end_time,$lesson_type_in_str
        );
        $train_from_lessonid_list = \App\Helper\Config::get_config("trian_lesson_from_lessonid","train_lesson");
        foreach($ret_info["list"] as &$item){
            $lessonid    = $item["lessonid"];
            $lesson_type = $item['lesson_type'];
            $subject     = $item['subject'];
            $grade       = $item['grade'];
            E\Econtract_type::set_item_value_str($item,"lesson_type");
            E\Egrade::set_item_value_str($item,"grade");

            $item["lesson_time"]     = \App\Helper\Utils::fmt_lesson_time($item["lesson_start"],$item["lesson_end"]);
            $item['tea_comment_str'] = "<font color=red>-</font>";

            if ($lesson_type<1000) {
                if($lesson_type==2){
                    $item['cc_id']=$item['require_adminid'];
                }else{
                    $item['cc_id']= $this->t_assistant_info->get_adminid_by_assistand( $item['assistantid']);
                }
                $item['ass_nick'] = $this->cache_get_assistant_nick($item['assistantid']);
                if($item['userid']>0){
                    $item["stu_nick"] = $this->cache_get_student_nick($item["userid"]);
                }else{
                    $item['stu_nick'] = "";
                }

                $this->check_tea_comment($item);
                if($item['confirm_flag']>=2){
                    $item['tea_comment_str'] = "无需评价";
                }else{
                    $item['tea_comment_str'] = $get_flag_color_func($item['tea_comment']);
                }
                if($item['textbook'] == ""){
                    $item['textbook'] = E\Eregion_version::get_desc($item['editionid']);
                }
            }elseif($lesson_type>=3000 && $lesson_type<4000){
                $ret_homework = $this->t_small_lesson_info->get_pdf_homework($item['lessonid']);
                if ($ret_homework) {
                    $item['homework_status']    = $ret_homework['work_status'];
                    $item['issue_url']          = $ret_homework['issue_url'];
                    $item['pdf_question_count'] = $ret_homework['pdf_question_count'];
                }
            }elseif($item['lesson_type']==1100 && $item['train_type']==4){
                $item['stu_nick']                       = "试听培训学生";
                $item['ass_nick']                       = "沈老师";
                $item['ass_phone']                      = "15214368896";
                $item['lesson_type_str']                = "模拟试听";
                @$from_lessonid                          = $train_from_lessonid_list[$subject][$grade];
                $from_lesson_info                       = $this->t_test_lesson_subject->get_from_lesson_info($from_lessonid);
                $item['stu_test_paper']                 = $from_lesson_info['stu_test_paper'];
                $item['stu_request_test_lesson_demand'] = $from_lesson_info['stu_request_test_lesson_demand'];
            }

            $item["pdf_status_str"] = $get_flag_color_func( $item["tea_status"])."/"
                                    . $get_flag_color_func( $item["stu_status"])."/"
                                    . $get_flag_color_func( $item["homework_status"]);
            if (!$item["tea_more_cw_url"] ) {
                $item["tea_more_cw_url"]="[]";
            }

            if($item['stu_request_test_lesson_demand']==""){
                $item['stu_request_test_lesson_demand']="<font color=red>-</font>";
            }
        }
        $student_list = $this->t_lesson_info_b2->get_student_list($teacherid,$start_time,$end_time);
        // dd($ret_info);
        return $this->pageView(__METHOD__,$ret_info,[
            "student_list" => $student_list
        ]);
    }

    public function teacher_apply_list() {
        $teacherid = $this->get_login_teacher();
        $page_info = $this->get_in_page_info();
        $page_num  = $this->get_in_page_num();
        $ret_info  = [];
        $ret_info  = $this->t_teacher_apply->get_teacher_apply_list($teacherid,$page_num);
        foreach($ret_info['list'] as &$item){
            if($item['lesson_type']<1000){
                $item['ass_nick'] = $this->cache_get_assistant_nick($item['assistantid']);
            }elseif($item['lesson_type']==1100 && $item['train_type']==4){
                $item['ass_nick']  = "沈老师";
                $item['ass_phone'] = "15214368896";
            }
            if($item['create_time']){
                $item['create_time'] = date('Y-m-d H:i:s',$item['create_time']);
            }else{
                $item['create_time'] = '';
            }
        }

        return $this->pageView(__METHOD__,$ret_info);
    }

    public function teacher_apply_add() {
        $teacherid          = $this->get_login_teacher();
        $cc_id              = $this->get_in_int_val('cc_id',0);
        $stu_nick           = $this->get_in_str_val('stu_nick',0);
        $lesson_time        = $this->get_in_str_val('lesson_time',0);
        $lesson_type        = $this->get_in_int_val('lesson_type',0);
        $lessonid           = $this->get_in_int_val('lessonid', 0);
        $set_lesson_adminid = $this->get_in_int_val('set_lesson_adminid', 0);
        $question_type      = $this->get_in_int_val('question_type', 0);
        $question_content   = $this->get_in_str_val('question_content', '');

        $this->t_teacher_apply->row_insert([
            "teacherid"        => $teacherid,
            "cc_id"            => $cc_id,
            "lessonid"         => $lessonid,
            "question_type"    => $question_type,
            "question_content" => $question_content,
            "create_time"      => time(NULL),
        ]);
        $adminid = $cc_id;
        if($question_type == 4){
            $ret = $this->t_test_lesson_subject_sub_list->get_set_lesson_adminid_by_lessonid($lessonid);
            $adminid = $ret['set_lesson_adminid'];
        }
        $this->send_wx_apply($adminid,$question_type,$teacherid,$lesson_time,$question_content,$stu_nick);

        return $this->output_succ();
    }

    public function send_wx_apply($adminid,$question_type,$teacherid,$lesson_time,$question_content,$stu_nick){
        $account           = $this->t_manager_info->get_account_by_uid($adminid);
        $question_type_str = $this->get_question_type_str($question_type);
        $wx_url            = "http://admin.yb1v1.com/teacher_apply/teacher_apply_list_one";
        $teacher_nick      = $this->t_teacher_info->get_nick($teacherid);
        $msg               = "上课时间：".$lesson_time.";问题描述：".$question_content;
        $desc              = "为保证试听质量，请您尽快回访处理。";
        $send_wx_data = [
            "account"    => urlencode($account),                                                         //后台账号
            "from_user"  => urlencode($question_type_str),                                               //待办主题
            "header_msg" => urlencode($teacher_nick."老师对".$stu_nick."同学的试听课需求有如下疑问："),  //主题
            "msg"        => $msg,                                                                        //内容
            "url"        => $wx_url,
            "desc"       => $desc,                                                                        //底部内容
        ];
        $post_url = "http://admin.yb1v1.com/common/send_wx_todo_msg?data=".base64_encode(json_encode($send_wx_data));
        return $this->send_curl_post($post_url);
    }

    public function get_question_type_str($question_type){
        if($question_type == 1){
            $question_type_str="试听需求";
        }elseif($question_type == 2){
            $question_type_str="试卷";
        }elseif($question_type == 3){
            $question_type_str="是否转化";
        }elseif($question_type == 4){
            $question_type_str="上课时间调整";
        }elseif($question_type == 5){
            $question_type_str="是否重上";
        }elseif($question_type == 6){
            $question_type_str="其他问题";
        }

        return $question_type_str;
    }

    public function teacher_apply_edit() {
        $id = $this->get_in_id();
        $teacher_flag=$this->get_in_int_val('teacher_flag',0);
        if($teacher_flag){
            $this->t_teacher_apply->field_update_list($id,[
                "teacher_flag" => $teacher_flag,
                "teacher_time" => time(null),
            ]);
        }else{
            $this->t_teacher_apply->field_update_list($id,[
                "teacher_flag" => $teacher_flag,
            ]);
        }
        return $this->output_succ();
    }

    public function teacher_apply_del() {
        $id=$this->get_in_id();
        $this->t_teacher_apply->row_delete($id);
        return $this->output_succ();
    }

    public function check_tea_comment(&$lesson_info){
        if($lesson_info['lesson_type']==2){
            $tea_comment = $this->t_seller_student_info->get_lesson_content($lesson_info['lessonid']);
            if(empty($tea_comment)){
                $tea_comment = $this->t_test_lesson_subject_require->get_lesson_content($lesson_info['lessonid']);
            }
            $lesson_info['cc_account'] = mb_substr($lesson_info['cc_account'],0,1,"utf8")."老师";
        }else{
            $tea_comment = $this->t_lesson_info_b2->get_stu_performance($lesson_info['lessonid']);
        }
        $lesson_info['tea_comment'] = $tea_comment==""?0:1;
    }

    public function get_lesson_list() {
        $teacherid   = $this->get_login_teacher();
        $start_date  = $this->get_in_str_val('start_date',date('Y-m-d', time(NULL) ));
        $end_date    = $this->get_in_str_val('end_date',date('Y-m-d', time(NULL)+86400*7 ));
        $lesson_type = $this->get_in_int_val('lesson_type',-1);
        $page_num    = $this->get_in_page_num();

        $start_time = strtotime($start_date);
        $end_time   = strtotime($end_date)+86400;

        $lesson_type_in_str="";
        switch ($lesson_type){
        case  E\Econtract_type::V_0:
            $lesson_type_in_str="0,1,2,3";
            break;
        case  E\Econtract_type::V_1001 :
            $lesson_type_in_str="1001,1002,1003";
            break;
        case  E\Econtract_type::V_3001 :
            $lesson_type_in_str="3001";
            break;
        default:
            break;
        }

        $ret_info=$this->t_lesson_info_b2->get_teacher_lesson_list($teacherid,$start_time,$end_time,$lesson_type_in_str, $page_num);
        foreach($ret_info["list"] as &$item){
            $item["lesson_time"]     = \App\Helper\Utils::fmt_lesson_time($item["lesson_start"],$item["lesson_end"]);
            $item["lesson_type_str"] = E\Econtract_type::get_desc( $item["lesson_type"]);
            $item["lesson_num_str"]  = "第".$item["lesson_num"]."次课";
            if($item["lesson_type"]<1000){
                $item["lesson_course_name"] = $this->cache_get_student_nick($item["userid"]);
            }elseif($item["lesson_type"]<3000){
                $item["lesson_course_name"]='';
            }else{
                $item["lesson_course_name"]=\App\Helper\Utils::fmt_lesson_name($item["grade"],$item["subject"],$item["lesson_num"]);
            }
            $lessonid    = $item["lessonid"];
            $lesson_type = $item['lesson_type'];

            $item['textbook']='';
            if ($lesson_type<1000) {
                if($lesson_type==2){
                    $editionid = $this->t_student_info->get_editionid($item['userid']);
                    $item['textbook'] = E\Eregion_version::get_desc($editionid);
                }
            }elseif($lesson_type>3000 && $lesson_type<4000){
                $ret_homework = $this->t_small_lesson_info->get_pdf_homework($item['lessonid']);
                $item['homework_status'] = $ret_homework['work_status'];
            }

        }

        return $this->pageView(__METHOD__,$ret_info,[
            "domain_qiniu_public"  => \App\Helper\Config::get_qiniu_public_url(),
            "domain_qiniu_private" => \App\Helper\Config::get_qiniu_private_url(),
        ]);
    }

    public function current_course()
    {
        $teacherid   = $this->get_login_teacher();
        $ret_info=\App\Helper\Utils::list_to_page_info([]);
        return $this->pageView(__METHOD__, $ret_info);
    }
    public function normal_course()
    {
        $teacherid   = $this->get_login_teacher();
        $date=\App\Helper\Utils::get_week_range(time(NULL),1);
        $stat_time=$date["sdate"];
        $ret_info=\App\Helper\Utils::list_to_page_info([]);
        return $this->pageView(__METHOD__, $ret_info);
    }
    public function get_common_config()
    {
        $teacherid   = $this->get_login_teacher();
        $common_lesson_config= $this->t_teacher_freetime_for_week->get_common_lesson_config($teacherid);
        $date=\App\Helper\Utils::get_week_range(time(NULL),1);
        $stime=$date["sdate"];
        foreach ( $common_lesson_config as &$item ) {
            $start_time=$item["start_time"];
            $end_time=$item["end_time"];

            $arr=explode("-",$start_time);

            \App\Helper\Utils::logger("start_time:$start_time");
            $week=$arr[0];
            $start_time=@$arr[1];

            //得到周几的开始时间
            $day_start=$stime + ($week-1)*86400;
            $item["start_time_ex"] = strtotime(date("Y-m-d", $day_start)." $start_time")*1000;
            $item["end_time_ex"]   = strtotime(date("Y-m-d", $day_start)." $end_time")*1000;
            $item["nick"]          = $this->cache_get_student_nick($item["userid"]);
            $item["teacher"]       = $this->cache_get_teacher_nick($teacherid);
        }

        return  $this->output_succ( [ "common_lesson_config" => $common_lesson_config] );
    }
    public function otp_common_config()
    {
        $opt_type  = $this->get_in_str_val("opt_type");
        $teacherid = $this->get_login_teacher();

        $old_key    = $this->get_in_str_val("old_key");
        $start_time = $this->get_in_str_val("start_time");
        $end_time   = $this->get_in_str_val("end_time");
        $tea_type   = $this->get_in_int_val("tea_type",0);
        $freetime   = $this->get_in_int_val("freetime",0);
        $userid     = $this->get_in_userid();

        $teacher_in = $this->t_teacher_freetime_for_week->check_userid($teacherid);
        if($teacher_in!= 1){
            $this->t_teacher_freetime_for_week->add_regular_course($teacherid,$tea_type,$freetime);
        }
        $common_lesson_config = $this->t_teacher_freetime_for_week->get_common_lesson_config($teacherid);

        if($opt_type=="add"){
            if($userid<=0){
                return $this->output_err("请设置学生") ;
            }
            $item = [];
            $item["start_time"]     = $start_time;
            $item["end_time"]       = $end_time;
            $item["userid"]         = $userid;
            $common_lesson_config[]  = $item;
        }else if($opt_type=="update"){
            foreach ( $common_lesson_config as &$u_item) {
                if($u_item["start_time"]==$old_key) {
                    $u_item["start_time"] = $start_time;
                    $u_item["end_time"]   = $end_time;
                    $u_item["userid"]     = $userid;
                }
            }
        }else if($opt_type=="del"){
            $tmp_list=[];
            foreach ( $common_lesson_config as $d_item) {
                if($d_item["start_time"]==$old_key) {
                }else {
                    $tmp_list[]=$d_item;
                }
            }
            $common_lesson_config=$tmp_list;
        }
        $ret_db = $this->t_teacher_freetime_for_week->field_update_list($teacherid,[
            "common_lesson_config" => json_encode($common_lesson_config)
        ]);
        return $this->output_succ();
    }

    public function get_lesson_time_js() {
        $teacherid = $this->get_login_teacher();
        $timestamp = $this->get_in_int_val("timestamp");
        $type      = $this->get_in_int_val("type",0);

        if ($timestamp == 0) {
            $timestamp = time();
        }

        if($type==0) { //
            $ret_week=\App\Helper\Utils::get_week_range($timestamp,1);
            $start_time=$ret_week["sdate"];
            $end_time=$ret_week["edate"];
        }else{
            $ret_week=\App\Helper\Utils::get_month_range($timestamp) ;
            $start_time=$ret_week["sdate"];
            $end_time=$ret_week["edate"];
        }

        $lesson_list=$this->t_lesson_info_b2->get_teacher_lesson_info($teacherid,$start_time,$end_time);
        foreach($lesson_list as &$item) {
            $nick=$this->cache_get_student_nick($item["userid"]);
            $item["month_title"]= $nick  ;
            $item["week_title"]=  "学生:$nick";
        }
        return $this->output_succ(["lesson_list"=>$lesson_list]);
    }

    public function get_lesson_info_b2(){
        $lessonid = $this->get_in_int_val('lessonid',-1);
        $ret_info = $this->t_lesson_info_b2->get_lesson_info_b2($lessonid);

        $ret_info["lesson_time"] = Utils::fmt_lesson_time($ret_info["lesson_start"],$ret_info["lesson_end"]);
        $ret_info["lesson_type_str"] = Econtract_type::get_desc( $ret_info["lesson_type"]) ;

        if($ret_info["lesson_type"]<1000){
            $ret_info["lesson_course_name"] = $this->cache_get_student_nick($ret_info["userid"]);
        }elseif($ret_info["lesson_type"]<3000){
            $ret_info["lesson_course_name"] = '';
        }else{
            $ret_info["lesson_course_name"] = Utils::fmt_lesson_name($ret_info["grade"],$ret_info["subject"],$ret_info["lesson_num"]);
        }

        return $this->output_succ(["data"=>$ret_info]);
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

    function update_teacher_student_pdf() {
        $teacherid          = $this->get_login_teacher();
        $lessonid           = $this->get_in_lessonid();
        $lesson_name        = $this->get_in_str_val("lesson_name");
        $lesson_intro       = $this->get_in_str_val("lesson_intro");
        $tea_cw_url         = trim($this->get_in_str_val("tea_cw_url"));
        $stu_cw_url         = trim($this->get_in_str_val("stu_cw_url"));
        $issue_url          = trim($this->get_in_str_val("issue_url"));
        $pdf_question_count = trim($this->get_in_str_val("pdf_question_count"));
        $tea_more_cw_url    = trim($this->get_in_str_val("tea_more_cw_url"));
        $tea_cw_pic_flag    = $this->get_in_int_val("tea_cw_pic_flag");
        $old_tea_cw_time    = trim($this->get_in_str_val("old_tea_cw_time"));
        $old_stu_cw_time    = trim($this->get_in_str_val("old_stu_cw_time"));
        $old_issue_time     = trim($this->get_in_str_val("old_issue_time"));
        $old_tea_cw_url     = trim($this->get_in_str_val("old_tea_cw_url"));

        $now = time(NULL);
        $db_teacherid = $this->t_lesson_info_b2->get_teacherid($lessonid);
        if ($db_teacherid != $teacherid) {
            $this->output_err("你不是该课程的老师");
        }
        \App\Helper\Utils::logger("upload_pdf lessonid:".$lessonid." time:".date($now)."unixtime:".$now);

        $tea_cw_status      = 0;
        $tea_cw_upload_time = 0;

        if($tea_cw_url!="" || $tea_more_cw_url!="[]") {
            $tea_cw_status      = 1;
            $tea_cw_upload_time = $old_tea_cw_time==0?$now:$old_tea_cw_time;
        }

        $stu_cw_status      = 0;
        $stu_cw_upload_time = 0;
        if($stu_cw_url!="") {
            $stu_cw_status      = 1;
            $stu_cw_upload_time = $old_stu_cw_time==0?$now:$old_stu_cw_time;
        }

        $work_status = 0;
        $issue_time  = 0;
        if($issue_url) {
            $work_status = 1;
            $issue_time  = $old_issue_time==0?$now:$old_issue_time;
        }

        $this->t_lesson_info_b2->field_update_list($lessonid,[
            "tea_cw_status"      => $tea_cw_status ,
            "tea_cw_url"         => $tea_cw_url ,
            "tea_cw_upload_time" => $tea_cw_upload_time,
            "stu_cw_status"      => $stu_cw_status ,
            "stu_cw_url"         => $stu_cw_url ,
            "stu_cw_upload_time" => $stu_cw_upload_time,
            "tea_more_cw_url"    => $tea_more_cw_url,
            "lesson_intro"       => $lesson_intro,
            "lesson_name"        => $lesson_name,
            "tea_cw_pic_flag"    => $tea_cw_pic_flag,
        ]);

        $lesson_type=$this->t_lesson_info_b2->get_lesson_type($lessonid);
        if($lesson_type >=3000 && $lesson_type<4000){
            $ret = $this->t_small_lesson_info->update_pdf($lessonid,$issue_url,$work_status,$pdf_question_count,$issue_time);
        }else{
            $ret = $this->t_homework_info->field_update_list($lessonid,[
                "issue_url"          => $issue_url,
                "work_status"        => $work_status,
                "pdf_question_count" => $pdf_question_count,
                "issue_time"         => $issue_time,
            ]);
        }

        if($tea_cw_pic_flag==1 && $old_tea_cw_url!=$tea_cw_url){
            $admin_url = \App\Helper\Config::get_monitor_new_url();
            $post_url  = $admin_url."/common_new/notify_gen_lesson_teacher_pdf_pic?lessonid=".$lessonid;
            $this->send_curl_post($post_url);
        }

        return $this->output_succ();
    }

    public function tea_lesson_count_detail_list() {
        $teacherid  = $this->get_login_teacher();
        $studentid  = -1;
        $year       = $this->get_in_int_val("year","2016");
        $month      = $this->get_in_int_val("month","11");
        $start_time = strtotime( "$year-$month-01");
        $month++;
        if ($month>=13){
            $month=1;
            $year++;
        }
        $end_time = strtotime("$year-$month-01");
        $old_list = $this->t_lesson_info_b2->get_1v1_lesson_list_by_teacher($teacherid,$studentid,$start_time,$end_time);

        global $cur_key_index;
        $check_init_map_item=function (&$item,$key,$key_class,$value="") {
            global $cur_key_index;
            if (!isset($item [$key]) ) {
                $item[$key] = [
                    "value" => $value,
                    "key_class" => $key_class."-".$cur_key_index,
                    "sub_list"=>[] ,
                    "data" => array(),
                ];
                $cur_key_index++;
            }
        };

        $add_data=function ( &$item, $add_item ) {
            $arr=&$item["data"];
            foreach  ($add_item as $k => $v ) {
                if ( !is_int($k) &&  ($k=="price" || $k=="lesson_count") ) {
                    if (!isset($arr[$k]))  {
                        $arr[$k]=0;
                    }
                    $arr[$k]+=$v;
                }
            }

        };

        $data_map=[]; //studentid -> lesson_count_level -> row
        $check_init_map_item($data_map,"","");
        $price_class="";
        foreach ($old_list as $row_id=> &$item) {

            $already_lesson_count=$item["already_lesson_count"];
            //teacher level
            $level=$item["level"];
            $teacher_money_type =$item["teacher_money_type"];


            $price_class=\App\Config\teacher_price_base::get_price_class($teacher_money_type,$level);

            $diff=($item["lesson_end"]-$item["lesson_start"])/60;
            if ($diff<=40) {
                $def_lesson_count=100;
            } else if ( $diff <= 60) {
                $def_lesson_count=150;
            } else if ( $diff <=90 ) {
                $def_lesson_count=200;
            }else{
                $def_lesson_count= ceil($diff/40)*100 ;
            }
            if ($item["lesson_count"]!=$def_lesson_count ) {
                $item["lesson_count_err"]="background-color:red;";
            }


            $lesson_count_level=$price_class::get_lesson_count_level($already_lesson_count);
            $studentid=$item["userid"];

            $item["tea_level"]  =  E\Elevel::get_desc( $level);
            $grade=$item["grade"];
            if ($item["confirm_flag"]==2) {
                $item["lesson_count"]=0;
            }
            $lesson_count=$item["lesson_count"];

            $pre_price= $price_class::get_price($level,$grade,$lesson_count_level);

            if ($item["lesson_type"] !=2) {
                $item["price"] =$pre_price  * $lesson_count /100;
                $item["pre_price"] =$pre_price ;
            }else{ //试听　５０
                if($lesson_count>0) {
                    $item["price"] =50;
                    $item["pre_price"] =50;
                }else{
                    $item["price"] =0;
                    $item["pre_price"] =0;
                }
            }

            E\Egrade::set_item_value_str($item);
            E\Econfirm_flag::set_item_value_str($item);
            E\Econtract_type::set_item_value_str($item,"lesson_type");

            $item["lesson_time"]=\App\Helper\Utils::fmt_lesson_time($item["lesson_start"], $item["lesson_end"]);


            $key0_map=&$data_map[""];
            $check_init_map_item($key0_map["sub_list"] , $studentid,"key1" );
            $add_data($key0_map, $item );

            $key1_map=&$key0_map["sub_list"][$studentid];
            $check_init_map_item($key1_map["sub_list"] , $lesson_count_level,"key2" );
            $add_data($key1_map, $item );

            $key2_map=&$key1_map["sub_list"][$lesson_count_level];
            $check_init_map_item($key2_map["sub_list"] ,$row_id,"key3" );
            $add_data($key2_map, $item );

            $key3_map=&$key2_map["sub_list"][$row_id];
            $key3_map["data"]=$item;
        }

        if( $price_class) {
            $level_desc_map=$price_class:: gen_level_name_config();
        }

        //to_list
        $list=[];
        if (count($old_list)>0) {
            foreach ($data_map as  $studentid=> $item0 ) {
                $data=$item0["data"];
                $data["key1"]="全部";
                $data["key2"]="";
                $data["key3"]="";
                $data["key1_class"]="";
                $data["key2_class"]="";
                $data["key3_class"]="";
                $data["level"]="l-0";
                $list[]=$data;

                foreach ( $item0["sub_list"] as $key1=> $item1  ) { // student
                    $data=$item1["data"];
                    $data["key1"]=$key1;
                    $data["stu_nick"]=$this->cache_get_student_nick($key1);
                    $data["key2"]="";
                    $data["key3"]="";
                    $data["key1_class"]=$item1["key_class"];
                    $data["key2_class"]="";
                    $data["key3_class"]="";
                    $data["level"]="l-1";

                    $list[]=$data;

                    foreach ( $item1["sub_list"] as $key2=> $item2  ) { //lesson_count_level
                        $data=$item2["data"];
                        $data["key1"]=$key1;
                        $data["key2"]=$key2;
                        $data["lesson_count_level_str"]= $level_desc_map[$key2];
                        $data["key3"]="";
                        $data["key1_class"]=$item1["key_class"];
                        $data["key2_class"]=$item2["key_class"];
                        $data["key3_class"]="";
                        $data["level"]="l-2";

                        $list[]=$data;
                        foreach ( $item2["sub_list"] as $key3=> $item3  ) {
                            $data=$item3["data"];
                            $data["key1"]=$key1;
                            $data["key2"]=$key2;
                            $data["key3"]=$key3;
                            $data["key1_class"]=$item1["key_class"];
                            $data["key2_class"]=$item2["key_class"];
                            $data["key3_class"]=$item3["key_class"];
                            $data["level"]="l-3";
                            $data["lesson_count_level_str"]="-";

                            $list[]=$data;
                        }
                    }
                }
            }

        }
        $ret_list=\App\Helper\Utils::list_to_page_info($list);

        return $this->Pageview(__METHOD__,$ret_list );
    }

    public function tea_comment(){
        return $this->view(__METHOD__);
    }

    public function teacher_all_info(){
        $teacherid   = $this->get_login_teacher();
        if(!$teacherid){
            return $this->output_err("老师登陆信息错误，请退出重新登陆!");
        }
        $tea_info=$this->t_teacher_info->get_teacher_info_all($teacherid);

        $tea_info['gender_str'] = @E\Egender::get_desc( $tea_info['gender']);
        $tea_info['grade_part_ex_str'] = empty($tea_info['grade_part_ex'])?"":@E\Egrade_part_ex::get_desc($tea_info['grade_part_ex']);
        $tea_info['subject_str'] = empty($tea_info['subject'])?"":@E\Esubject::get_desc($tea_info['subject']);
        $tea_info['putonghua_is_correctly_str'] = @E\Eputonghua_is_correctly::get_desc($tea_info['putonghua_is_correctly']);
        $tea_info['birth_str'] = substr(@$tea_info['birth'],0,4)."-"
                               .substr(@$tea_info['birth'],4,2)."-"
                               .substr(@$tea_info['birth'],6,2);
        $arr = explode(",",@$tea_info['quiz_analyse']);
        $tea_info['quiz_analyse'] = $arr[0];
        if(!empty($tea_info['create_time'])){
            $tea_info['create_time'] = date('Y-m-d H:i:s',$tea_info['create_time']);
        }else{
            $tea_info['create_time'] = "";
        }
        return $this->pageView(__METHOD__,null,['tea_info'=>$tea_info]);
    }

    public function set_teacher_face(){
        $teacherid = $this->get_in_int_val("teacherid");
        $face = $this->get_in_str_val("face");
        $domain = config('admin')['qiniu']['public']['url'];
        $face = $domain.'/'.$face;
        $this->t_teacher_info->field_update_list($teacherid,[
            "face" => $face,
        ]);
        return $this->output_succ();
    }

    public function set_teacher_info(){
        $teacherid     = $this->get_in_int_val("teacherid");
        $nick          = $this->get_in_str_val("nick");
        $realname      = $this->get_in_str_val("realname");
        $gender        = $this->get_in_int_val("gender");
        $birth         = $this->get_in_str_val("birth");
        $work_year     = $this->get_in_int_val("work_year");
        $phone         = $this->get_in_int_val("phone");
        $grade_part_ex = $this->get_in_int_val("grade_part_ex");
        $subject       = $this->get_in_int_val("subject");
        $putonghua_is_correctly = $this->get_in_int_val("putonghua_is_correctly");
        $email         = $this->get_in_str_val("email");
        $base_intro    = $this->get_in_str_val("base_intro");
        $dialect_notes = $this->get_in_str_val("dialect_notes");

        if(!empty($email)){
            if(preg_match('/^[1-9]\d{4,10}$/',$email)){
                $email = $email."@qq.com";
            }else{
                $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
                if ( !preg_match( $pattern, $email )){
                   return outputJson(array(
                        'ret' => -1,
                        'info' => "邮箱格式有误 ",
                    ));
                }
            }
        }

        if(strlen($phone) != 11){
           return outputJson(array(
                'ret' => -1,
                'info' => "手机号码长度有误 ",
            ));
        }

        if(!empty($birth)){
            $birth = substr($birth,0,4).''.substr($birth,5,2).''.substr($birth,8,2);
        }

        $this->t_teacher_info->field_update_list($teacherid,[
            "nick"          => $nick,
            "realname"      => $realname,
            "phone"         => $phone,
            "email"         => $email,
            "gender"        => $gender,
            "birth"         => $birth,
            "work_year"     => $work_year,
            "base_intro"    => $base_intro,
            "grade_part_ex" => $grade_part_ex,
            "subject"       => $subject,
            "dialect_notes" => $dialect_notes,
            "putonghua_is_correctly" => $putonghua_is_correctly,
        ]);
        return $this->output_succ();
    }

    public function tea_wages_info(){
        $teacherid  = $this->get_login_teacher();
        $year       = $this->get_in_str_val("year","2017-1");
        if(!in_array($year,["2016-12","2017-1"])){
            $year = "2017-1";
        }

        $start_time = strtotime("$year-01");

        $begin_time = strtotime("2016-12-01");
        $end_time   = time();
        if($start_time<$begin_time || $start_time>$end_time){
            $start_time = $begin_time;
        }
        $end_time = strtotime("+1 month",$start_time);

        if($teacherid==0){
            $ret_list=\App\Helper\Utils::list_to_page_info([]);
            return $this->Pageview(__METHOD__,$ret_list);
        }

        $teacher_money_flag = $this->t_teacher_info->get_teacher_money_flag($teacherid);
        $teacher_money_type = $this->t_teacher_info->get_teacher_money_type($teacherid);
        $teacher_honor      = $this->t_teacher_money_list->get_teacher_honor_money($teacherid,$start_time,$end_time,1);
        $teacher_trial      = $this->t_teacher_money_list->get_teacher_honor_money($teacherid,$start_time,$end_time,2);
        $old_list           = $this->t_lesson_info_b2->get_lesson_list_for_wages($teacherid,$start_time,$end_time);

        if($teacher_money_type==4){
            $start = strtotime("-1 month",strtotime(date("Y-m-01",$start_time)));
            $end   = strtotime("+1 month",$start);
            $already_lesson_count = $this->t_lesson_info_b2->get_teacher_last_month_lesson_count($teacherid,$start,$end);
        }

        global $cur_key_index;
        $check_init_map_item = function(&$item,$key,$key_class,$value="") {
            global $cur_key_index;
            if (!isset($item[$key])) {
                $item[$key] = [
                    "value"     => $value,
                    "key_class" => $key_class."-".$cur_key_index,
                    "sub_list"  => [],
                    "data"      => [],
                ];
                $cur_key_index++;
            }
        };

        $add_data = function(&$item,$add_item){
            $arr  = &$item["data"];
            foreach ($add_item as $k => $v ) {
                if (!isset($arr[$k])) {
                    $arr[$k]="";
                }
                if ($k=="price" || $k=="lesson_count" || $k=="lesson_reward" || $k=="lesson_full_reward") {
                    $arr[$k]+=$v;
                }
                if($k=="lesson_cost"){
                    $arr[$k]-=$v;
                }
            }
        };

        $data_map = [];
        $check_init_map_item($data_map,"","");
        foreach ($old_list as $row_id => &$item) {
            $studentid            = $item["userid"];
            $grade                = $item["grade"];
            $pre_price            = $item['money'];
            $lesson_count         = $item["lesson_count"];
            if($teacher_money_type!=4){
                $already_lesson_count = $item["already_lesson_count"];
            }

            if($item['type']!=0){
                $rule_type = \App\Config\teacher_rule::get_teacher_rule($item['type']);
            }else{
                $rule_type = [];
            }

            if(!empty($rule_type)){
                $i=0;
                $lesson_count_level = count($rule_type);
                foreach($rule_type as $key=>$val){
                    $i++;
                    if($already_lesson_count<$key){
                        $lesson_count_level=$key==0?$i:($i-1);
                        break;
                    }
                }
            }else{
                $lesson_count_level = 1;
            }

            $diff = ($item["lesson_end"]-$item["lesson_start"])/60;
            if ($diff<=40) {
                $def_lesson_count = 100;
            } else if ( $diff <= 60) {
                $def_lesson_count = 150;
            } else if ( $diff <=90 ) {
                $def_lesson_count = 200;
            }else{
                $def_lesson_count = ceil($diff/40)*100 ;
            }

            if ($lesson_count != $def_lesson_count ) {
                $item["lesson_count_err"] = "background-color:red;";
            }

            $item['lesson_full_reward'] = $this->get_lesson_full_reward($item['lesson_full_num']);
            $this->get_lesson_cost_info($item);

            if($item['confirm_flag']==2){
                $item['lesson_price'] = 0;
                $item['pre_reward']   = 0;
                $item['price']        = 0;
            }else{
                $item['lesson_price']  /= 100;
                if($item["lesson_type"]!=2){
                    $item['pre_reward'] = $this->get_teacher_lesson_money($item['type'],$already_lesson_count);
                    $item["price"] = ($pre_price+$item['pre_reward'])*$lesson_count/100
                                   +$item['lesson_full_reward']
                                   -$item['lesson_cost'];
                    $item["pre_price"] = $pre_price;
                }else{
                    $item['pre_reward'] = 0;
                    if($lesson_count>0) {
                        $trial_base = \App\Helper\Utils::get_trial_base_price($teacher_money_type);

                        $item["price"]        = $trial_base+$item['lesson_full_reward']-$item['lesson_cost'];
                        $item["pre_price"]    = $trial_base;
                        $item["lesson_count"] = 100;
                    }else{
                        $item["price"]        = 0;
                        $item["pre_price"]    = 0;
                        $item["lesson_count"] = 0;
                    }
                }
            }

            $item['lesson_reward'] = $item['pre_reward']*$lesson_count/100;
            E\Elevel::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            E\Econfirm_flag::set_item_value_str($item);
            E\Econtract_type::set_item_value_str($item,"lesson_type");
            E\Eteacher_money_type::set_item_value_str($item);

            $item["lesson_time"] = \App\Helper\Utils::fmt_lesson_time($item["lesson_start"], $item["lesson_end"]);

            $key0_map=&$data_map[""];
            $check_init_map_item($key0_map["sub_list"] , $studentid,"key1");
            $add_data($key0_map, $item );

            $key1_map=&$key0_map["sub_list"][$studentid];
            $check_init_map_item($key1_map["sub_list"] ,$lesson_count_level,"key2",$item['type'] );
            $add_data($key1_map, $item );

            $key2_map=&$key1_map["sub_list"][$lesson_count_level];
            $check_init_map_item($key2_map["sub_list"] ,$row_id,"key3");
            $add_data($key2_map, $item );

            $key3_map=&$key2_map["sub_list"][$row_id];
            $key3_map["data"]=$item;
        }

        $list=[];
        if (count($old_list)>0) {
            foreach ($data_map as  $studentid=> $item0 ) {
                $data=$item0["data"];
                $data["key1"]="全部";
                $data["key2"]="";
                $data["key3"]="";
                $data["key1_class"]="";
                $data["key2_class"]="";
                $data["key3_class"]="";
                $data["level"]="l-0";
                $list[]=$data;

                foreach ( $item0["sub_list"] as $key1=> $item1  ) { // student
                    $data=$item1["data"];
                    $data["stu_nick"]=$this->cache_get_student_nick($key1);
                    $data["key1"]=$key1;
                    $data["key2"]="";
                    $data["key3"]="";
                    $data["key1_class"]=$item1["key_class"];
                    $data["key2_class"]="";
                    $data["key3_class"]="";
                    $data["level"]="l-1";
                    $list[]=$data;

                    foreach ( $item1["sub_list"] as $key2 => $item2 ) { //lesson_count_level
                        $data=$item2["data"];
                        $data["key1"]=$key1;
                        $data["key2"]=$key2;

                        if($item2['value']>0){
                            $lesson_count_range = \App\Config\teacher_rule::get_teacher_lesson_count_range($item2['value']);
                            $data["lesson_count_level_str"] = $lesson_count_range[$key2];
                        }else{
                            $data["lesson_count_level_str"] = "未确认";
                        }

                        $data["key3"]="";
                        $data["key1_class"]=$item1["key_class"];
                        $data["key2_class"]=$item2["key_class"];
                        $data["key3_class"]="";
                        $data["level"]="l-2";

                        $list[]=$data;
                        foreach ( $item2["sub_list"] as $key3=> $item3  ) {
                            $data=$item3["data"];
                            $data["key1"]=$key1;
                            $data["key2"]=$key2;
                            $data["key3"]=$key3;
                            $data["key1_class"]=$item1["key_class"];
                            $data["key2_class"]=$item2["key_class"];
                            $data["key3_class"]=$item3["key_class"];
                            $data["level"]="l-3";
                            $data["lesson_count_level_str"]="-";

                            $list[]=$data;
                        }
                    }
                }
            }
        }
        $ret_list = \App\Helper\Utils::list_to_page_info($list);

        if(isset($list) && !empty($list)){
            $teacher_all_money = $list[0]['price']+$teacher_honor/100+$teacher_trial/100;
        }else{
            $teacher_all_money=0;
        }

        $teacher_tax_money = 0;
        if($teacher_money_flag!=1){
            if(in_array($teacher_money_type,[0,1,2,3])){
                if($teacher_all_money>800){
                    $tax_price = $teacher_all_money-800;
                    $teacher_tax_money = round($tax_price*0.02,2);
                    $teacher_all_money -= $teacher_tax_money;
                }
            }else{
                $teacher_tax_money = round($teacher_all_money*0.02,2);
                $teacher_all_money -= $teacher_tax_money;
            }
        }

        /**
         if($teacher_all_money>800 && $teacher_money_flag!=1){
         $tax_price = $teacher_all_money-800;
         $teacher_tax_money = round($tax_price*0.02,2);
         $teacher_all_money -= $teacher_tax_money;
         }else{
         $teacher_tax_money = 0;
         }
        */

        return $this->Pageview(__METHOD__,$ret_list,[
            "teacher_honor"      => $teacher_honor/100,
            "teacher_trial"      => $teacher_trial/100,
            "teacher_money_type" => $teacher_money_type,
            "teacher_tax_money"  => $teacher_tax_money,
            "teacher_all_money"  => $teacher_all_money,
        ]);
    }

    /**
     * 检测此节课是否获得全勤奖
     */
    private function get_lesson_full_reward($lesson_full_num){
        $full_num = $this->teacher_money['lesson_full_num'];
        if($lesson_full_num%$full_num==0 && $lesson_full_num!=0){
            $lesson_full_reward = $this->teacher_money['lesson_full_reward']/100;
        }else{
            $lesson_full_reward=0;
        }
        return $lesson_full_reward;
    }

    private function get_lesson_cost_info(&$val){
        $lesson_all_cost = 0;
        $lesson_info     = "";
        $deduct_type     = E\Elesson_deduct::$s2v_map;
        $deduct_info     = E\Elesson_deduct::$desc_map;
        $month           = 0;
        $lesson_month    = date("m",$val['lesson_start']);
        if($month!=$lesson_month){
            $month = $lesson_month;
            $this->change_num=0;
            $this->late_num=0;
        }

        if($val['confirm_flag']==2 && $val['deduct_change_class']>0){
            if($val['lesson_cancel_reason_type']==21){
                $lesson_all_cost = $this->teacher_money['lesson_miss_cost']/100;
                $info            = "上课旷课!";
            }elseif(($val['lesson_cancel_reason_type']==2 || $val['lesson_cancel_reason_type']==12)
                    && $val['lesson_cancel_time_type']==1){
                if($this->change_num>=3){
                    $lesson_all_cost = $this->teacher_money['lesson_cost']/100;
                    $lesson_info     = "课前４小时内取消上课！";
                }else{
                    $this->change_num++;
                    $lesson_info     = "本月第".$this->change_num."次换课";
                    $lesson_all_cost = 0;
                }
            }
        }else{
            $lesson_cost = $this->teacher_money['lesson_cost']/100;
            foreach($deduct_type as $key=>$item){
                if($val['deduct_change_class']==0){
                    if($val[$key]>0){
                        if($key=="deduct_come_late" && $this->late_num<3){
                            $this->late_num++;
                        }else{
                            $lesson_all_cost += $lesson_cost;
                            $lesson_info     .= $deduct_info[$item]."/";
                        }
                    }
                }
            }
        }

        $val['lesson_cost']      = $lesson_all_cost;
        $val['lesson_cost_info'] = $lesson_info;
    }

    private function get_teacher_lesson_money($type,$already_lesson_count){
        $rule_type = \App\Config\teacher_rule::$rule_type;
        $reward    = 0;
        if(isset($rule_type[$type])){
            foreach($rule_type[$type] as $key=>$val){
                if($already_lesson_count>=$key){
                    $reward = $val;
                }elseif($already_lesson_count<$key){
                    break;
                }
            }
        }
        return $reward;
    }

    public function teacher_lecture_appointment_info(){

        list($start_time,$end_time) = $this->get_in_date_range(date("Y-m-01",time()),0,0,[],3);
        $page_num  = $this->get_in_page_num();
        $teacherid = $this->get_login_teacher();
        $status    = $this->get_in_int_val("status",-1);

        $phone    = $this->t_teacher_info->get_phone($teacherid);
        //dd($phone);
        $ret_info = $this->t_teacher_lecture_appointment_info->get_all_info_b1($page_num,$start_time,$end_time,$phone,$status);
        //dd($ret_info);

        if($phone=="15366667766"){
             $show_teacher_info=1;
        }else{
             $show_teacher_info=0;
        }

        if(!empty($ret_info)){

            foreach($ret_info["list"] as &$item){
                $item["answer_time"] = date("Y-m-d H:i:s",$item["answer_begin_time"])."-".date("H:i:s",$item["answer_end_time"]);
                if($item['confirm_time']!=0){
                    $item["confirm_time_str"] = date("Y-m-d H:i:s",$item['confirm_time']);
                }else{
                    $item["confirm_time_str"] = "";
                }

                if($item['status']=="-2"){
                    $item['status_str']="无试讲";
                }else{
                    E\Electure_status::set_item_value_str($item,"status");
                }
                if(!$show_teacher_info){
                    $item['name']  = mb_substr($item['name'],0,1,"utf-8")."老师";
                    $item['phone'] = substr($item['phone'],0,3)."****".substr($item['phone'],7);
                }
            }
        }else{

           // return $this->pageView(__METHOD__,[],[
             //   "lecture_status" => 0
            //]);
        }

        $all_info  = $this->t_teacher_lecture_appointment_info->get_lecture_count_info($start_time,$end_time,$phone);
        $count_num = [
            "all"      => 0,
            "pass"     => 0,
            "not_pass" => 0,
        ];
        foreach($all_info as &$item){
            if($item['status']==1){
                $count_num["pass"]++;
            }else{
                $count_num["not_pass"]++;
            }
            $count_num["all"]++;
        }

        return $this->pageView(__METHOD__,$ret_info,[
            "lecture_status"    => 1,
            "count_num"         => $count_num
        ]);
    }

    /**
     * @param text base64加密的require_id
     * @param time 链接生成时间，检测有效时间
     */
    public function grab_trial_lesson_list(){
        $text       = $this->get_in_str_val("text");
        $time       = $this->get_in_str_val("time","0");
        $require_id = trim(base64_decode($text),",");

        $ret_info = [];
        $err_info = "";
        if($require_id != ""){
            $ret_info = $this->t_test_lesson_subject_require->grab_trial_lesson_list($require_id);
            foreach($ret_info as &$item){
                E\Eregion_version::set_item_value_str($item,"editionid");
                if(!empty($item["textbook"])){
                    $item["editionid_str"] = $item["textbook"];
                }
                if($item['stu_test_paper'] != ""){
                    $item['stu_paper_str'] = "<font color='green'>有试卷</font>";
                }else{
                    $item['stu_paper_str'] = "<font color='red'>无试卷</font>";
                }
                $item['accept_account'] = $this->t_manager_info->get_name($item['accept_adminid']);
                E\Egrade::set_item_value_str($item);
                E\Esubject::set_item_value_str($item);
                \App\Helper\Utils::unixtime2date_for_item($item,"stu_request_test_lesson_time","_str");
            }
        }
        if(empty($ret_info) || $time<time()){
            if($time=="0"){
                $err_info="此链接不是一个有效链接！";
            }else{
                $err_info = "（。・＿・。）ﾉ非常抱歉，您来晚了，此链接的课程已被抢完。";
            }
            $ret_info=[];
        }

        $ret_info = \App\Helper\Utils::list_to_page_info($ret_info);
        //dd($ret_info);
        return $this->pageView(__METHOD__,$ret_info,[
            "err_info"=>$err_info
        ]);
    }

    public function course_set_new() {
        $teacherid    = $this->get_login_teacher();
        $require_id   = $this->get_in_int_val("require_id");
        $lesson_start = $this->get_in_str_val('lesson_start');
        $grade        = $this->get_in_int_val('grade');
        $lesson_end   = $lesson_start+2400;
        $orderid      = 1;

        $db_lessonid=$this->t_test_lesson_subject_require->get_current_lessonid($require_id);
        if ($db_lessonid) {
            return $this->output_err("此课程已被其他老师预约。");
        }
        if($lesson_start < time()){
            return $this->output_err("此课程的申请时间已超时!");
        }

        //周时间计算
        $date_week = \App\Helper\Utils::get_week_range($lesson_start,1);
        $lstart = $date_week["sdate"];
        $lend   = $date_week["edate"];

        //检查老师一周排课功能是否冻结
        $week_freeze_info = $this->t_teacher_info->field_get_list($teacherid,"is_freeze,is_week_freeze,week_freeze_time,lesson_hold_flag,is_test_user");
        $is_test = $week_freeze_info["is_test_user"];
        if($week_freeze_info["is_freeze"]==1){
            return $this->output_err("您已被冻结，无法排课!");
        }
        if($week_freeze_info["is_week_freeze"]==1 && $is_test==0){
            if($week_freeze_info["week_freeze_time"]>=($lstart-7*86400) && $week_freeze_info["week_freeze_time"]<($lend-7*86400)){
                return $this->output_err("您本周被冻结,下一周开始可以排课!");
            }
        }

        // 检查老师是否暂停接试听课
        // if($week_freeze_info["lesson_hold_flag"]==1 && $is_test==0){
        //     return $this->output_err("您已被限制排课!");
        // }

        //老师需满足培训通过的条件才能排试听课
        $teacher_type_train_info = $this->t_teacher_info->field_get_list($teacherid,"teacher_money_type,train_through_new");
        if($teacher_type_train_info["teacher_money_type"]>3 && $teacher_type_train_info["train_through_new"]==0 && $is_test==0){
            return $this->output_err("您培训未通过,暂不能排试听课!");
        }

        //老师科目/年级限制,包含冻结年级
        $test_lesson_subject_id = $this->t_test_lesson_subject_require->get_test_lesson_subject_id($require_id);
        $tt_item = $this->t_test_lesson_subject->field_get_list($test_lesson_subject_id,"subject, grade, userid ");
        $userid  = $tt_item["userid"];
        $subject = $tt_item["subject"];
        $tt_item['grade']=$grade;
        $teacher_subject = $this->t_teacher_info->field_get_list($teacherid,"subject,second_subject,third_subject,grade_part_ex,second_grade,third_grade,grade_start,grade_end,not_grade,not_grade_limit,limit_plan_lesson_type");

        if($teacher_subject['grade_start']==0){
            $check_subject= $this->check_teacher_subject_and_grade(
                $subject,$grade,$teacher_subject["subject"],$teacher_subject["second_subject"],$teacher_subject["third_subject"],
                $teacher_subject["grade_part_ex"],$teacher_subject["second_grade"],$teacher_subject["third_grade"],$is_test,
                $teacher_subject['not_grade']
            );
        }else{
            $check_subject=$this->check_teacher_grade_range_new($tt_item,$teacher_subject);
        }
        if($check_subject!=1){
            return $check_subject;
        }

        //系统限课
        $test_lesson_num = $this->t_lesson_info_b2->get_limit_type_teacher_lesson_num($teacherid,$lstart,$lend);
        if($test_lesson_num >= $teacher_subject["limit_plan_lesson_type"]
           && $is_test==0
           && $teacher_subject["limit_plan_lesson_type"] !=0
        ){
            return $this->output_err(
                "您排课受限制,一周限排".$teacher_subject["limit_plan_lesson_type"]."节,"
                ."当周您已排".$test_lesson_num."节,不能继续排课!"
            );
        }

        //新入职老师当周限排6节课,其他老师每周限排8节课,一天限排4节课
        $limit_num_info = $this->t_teacher_info->field_get_list($teacherid,"limit_day_lesson_num,limit_week_lesson_num");
        $ret = $this->t_lesson_info_b2->check_teacher_have_test_lesson_pre_week($teacherid,$lstart);
        if($ret ==1){
            if($test_lesson_num>=$limit_num_info["limit_week_lesson_num"] && $is_test==0){
                return $this->output_err("您试听课一周限排".$limit_num_info["limit_week_lesson_num"]."节!");
            }
        }else{
            if($test_lesson_num >=6 && $is_test==0){
                return $this->output_err("您是新入职老师,试听课一周限排6节!");
            }
        }

        $day_st    = date("Y-m-d",$lesson_start);
        $day_start = strtotime($day_st);
        $day_end   = strtotime("+1 day",$day_start);
        $test_lesson_num_day = $this->t_lesson_info_b2->get_limit_type_teacher_lesson_num($teacherid,$day_start,$day_end);
        if($test_lesson_num_day>=$limit_num_info["limit_day_lesson_num"] && $is_test==0){
            return $this->output_err(
                "试听课一天限排".$limit_num_info["limit_day_lesson_num"]."节!"
            );
        }

        //检查时间是否冲突
        $stu_free = $this->t_lesson_info_b2->check_student_time_free($userid,0,$lesson_start,$lesson_end);
        if($stu_free) {
            $error_lessonid = $stu_free["lessonid"];
            return $this->output_err("有现存的学生课程与该课程时间冲突！请联系教务老师！");
        }
        $tea_free=$this->t_lesson_info_b2->check_teacher_time_free(
            $teacherid,0,$lesson_start,$lesson_end);
        if($tea_free) {
            $error_lessonid=$tea_free["lessonid"];
            return $this->output_err("您有一堂课和此课程冲突，无法排课!");
        }
        $teacher_info = $this->t_teacher_info->field_get_list($teacherid,"teacher_money_type,level");

        $this->t_course_order->start_transaction();
        $courseid = $this->t_course_order->add_course_info_new($orderid,$userid,$grade,$subject,100,2,0,1,1,0,$teacherid);
        $lessonid = $this->t_lesson_info_b2->add_lesson(
            $courseid,0,$userid,0,2,
            $teacherid,0,$lesson_start,$lesson_end,$grade,
            $subject,100,$teacher_info["teacher_money_type"],$teacher_info["level"]
        );

        $stu_free = $this->t_lesson_info_b2->check_student_time_free($userid,$lessonid,$lesson_start,$lesson_end);
        if($stu_free){
            $this->t_course_order->rollback();
            $error_lessonid = $stu_free["lessonid"];
            return $this->output_err("本节课已经被抢！请刷新页面重新查看！");
        }
        $this->t_course_order->commit();
        $this->t_homework_info->add($courseid,0,$userid,$lessonid,$grade,$subject,$teacherid);
        $accept_adminid = $this->t_test_lesson_subject_require->get_accept_adminid($require_id);
        $this->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
            "history_accept_adminid" => $accept_adminid,
            "grade"                  => $grade,
        ]);

        $this->t_test_lesson_subject_sub_list->row_insert([
            "lessonid"           => $lessonid,
            "require_id"         => $require_id,
            "set_lesson_adminid" => $accept_adminid,
            "set_lesson_time"    => time(NULL) ,
        ]);

        $this->t_test_lesson_subject_require->field_update_list($require_id,[
            'current_lessonid'      => $lessonid,
            'accept_flag'           => E\Eset_boolean::V_1 ,
            'accept_time'           => time(NULL),
            'jw_test_lesson_status' => 1,
            'grab_status'           => 2,
        ]);

        $account = $this->t_manager_info->get_account($accept_adminid);
        $this->t_test_lesson_subject_require->set_test_lesson_status(
            $require_id,E\Eseller_student_status::V_210,$account);

        $this->t_lesson_info_b2->reset_lesson_list($courseid);
        $this->t_seller_student_new->field_update_list($userid,[
            "global_tq_called_flag" => 2,
            "tq_called_flag"        => 2,
        ]);

        if (\App\Helper\Utils::check_env_is_release()){
            $require_adminid = $this->t_test_lesson_subject->get_require_adminid($test_lesson_subject_id);
            $userid          = $this->t_test_lesson_subject->get_userid($test_lesson_subject_id);
            $phone           = $this->t_seller_student_new->get_phone($userid);
            $nick            = $this->t_student_info->get_nick($userid);

            $teacher_nick = $this->cache_get_teacher_nick($teacherid);

            $lesson_time_str    = \App\Helper\Utils::fmt_lesson_time($lesson_start,$lesson_end);
            $require_admin_nick = $this->t_manager_info->get_account($require_adminid);

            $from_user  = "来自:".$account;
            $header_msg = "排课[$phone][$nick] 老师[$teacher_nick]上课时间[$lesson_time_str]";
            $data = [
                "account"    => urlencode($require_admin_nick),
                "from_user"  => urlencode($from_user),
                "header_msg" => urlencode($header_msg),
                "msg"        => "",
                "url"        => "",
            ];

            $post_url = "http://admin.yb1v1.com/common/send_wx_todo_msg?data=".base64_encode(json_encode($data));
            $this->send_curl_post($post_url);
        }

        return $this->output_succ();
    }

    public function check_teacher_subject_and_grade($subject,$grade,$first_subject,$second_subject,$third_subject,$grade_part_ex,$second_grade,$third_grade,$is_test,$not_grade){
        if($is_test ==0){
            if($subject != $first_subject && $subject != $second_subject && $subject != $third_subject){
                return $this->output_err("请安排与老师科目相符合的课程!");
            }

            if($subject==$first_subject){
                if($grade==106){
                    if($grade_part_ex !=1 && $grade_part_ex!=6 && $grade_part_ex!=4 ){
                        return $this->output_err("请安排与老师年级段相符合的课程!");
                    }
                }elseif($grade>=100 && $grade <200){
                    if($grade_part_ex !=1 && $grade_part_ex!=4 ){
                        return $this->output_err("请安排与老师年级段相符合的课程!");
                    }
                }else if($grade>=200 && $grade <300){
                    if($grade_part_ex !=2 && $grade_part_ex !=4 && $grade_part_ex !=5 && $grade_part_ex!=6){
                        return $this->output_err("请安排与老师年级段相符合的课程!");
                    }
                }else if($grade>=300 ){
                    if($grade_part_ex !=3 && $grade_part_ex !=5){
                        return $this->output_err("请安排与老师年级段相符合的课程!");
                    }
                }
            }else if($subject==$second_subject){
                if($grade>=100 && $grade <200){
                    if($second_grade !=1 && $second_grade !=4){
                        return $this->output_err("请安排与老师年级段相符合的课程!");
                    }
                }else if($grade>=200 && $grade <300){
                    if($second_grade !=2 && $second_grade !=4 && $second_grade !=5){
                        return $this->output_err("请安排与老师年级段相符合的课程!");
                    }
                }else if($grade>=300 ){
                    if($second_grade !=3 && $second_grade !=5){
                        return $this->output_err("请安排与老师年级段相符合的课程!");
                    }
                }
            }else if($subject==$third_subject){
                if($grade>=100 && $grade <200){
                    if($third_grade !=1 && $third_grade !=4){
                        return $this->output_err("请安排与老师年级段相符合的课程!");
                    }
                }else if($grade>=200 && $grade <300){
                    if($third_grade !=2 && $third_grade !=4 && $third_grade !=5){
                        return $this->output_err("请安排与老师年级段相符合的课程!");
                    }
                }else if($grade>=300 ){
                    if($third_grade !=3 && $third_grade !=5){
                         return $this->output_err("请安排与老师年级段相符合的课程!");
                    }
                }
            }

            $not_grade_arr = explode(",",$not_grade);
            if(in_array($grade,$not_grade_arr) && $subject==$first_subject){
                return $this->output_err("该老师对应年级段已被冻结!");
            }

            return 1;
        }else{
            return 1;
        }
    }

    public function check_teacher_grade_range_new($stu_info,$tea_info){
        $stu_grade_range = $this->get_grade_range_new($stu_info['grade']);
        $not_grade       = explode(",",$tea_info['not_grade']);
        $grade_start     = $tea_info['grade_start'];
        $grade_end       = $tea_info['grade_end'];

        if($stu_grade_range<$grade_start || $stu_grade_range>$grade_end){
            return $this->output_err("学生年级与老师年级范围不匹配!");
        }
        if($stu_info['subject']!=$tea_info['subject']){
            return $this->output_err("学生科目与老师科目不匹配!");
        }

        if(in_array($stu_info['grade'],$not_grade)){
            return $this->output_err("该老师对应年级段已被冻结!");
        }

        return 1;
    }

    public function get_grade_range_new($grade){
        switch($grade){
        case 101:case 102:case 103:
            $grade_range=1;
            break;
        case 104:case 105:case 106:
            $grade_range=2;
            break;
        case 201:case 202:
            $grade_range=3;
            break;
        case 203:
            $grade_range=4;
            break;
        case 301:case 302:
            $grade_range=5;
            break;
        case 303:
            $grade_range=6;
            break;
        default:
            $grade_range=0;
        }
        return $grade_range;
    }

    public function send_curl_post($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
    }

    public function tea_ref_money_list() {

        $start_date      = $this->get_in_str_val("start_date",date("Y-m",time()));
        $teacherid       = $this->get_in_int_val("teacherid",-1);
        $start_time      = strtotime($start_date);
        $end_time        = strtotime("+1 month",$start_time);
        $last_start_time = strtotime("-1 month",$start_time);
        $last_end_time   = strtotime("-1 month",$end_time);
        $uid             = $this->get_login_teacher();

        $teacher_info     = $this->t_teacher_info->get_teacher_info_all($uid);
        $teacher_ref_type = 0;
        if(!empty($teacher_info)){
            if($teacher_info['teacher_ref_type']>0 && $teacher_info['teacher_type']==21){
                $teacher_ref_type = $teacher_info['teacher_ref_type'];
            }
        }

        if($teacher_ref_type==1){
            $teacher_ref_rate = \App\Helper\Config::get_config_2("teacher_ref_rate",$teacher_ref_type);
        }elseif($teacher_ref_type!=0){
            $teacher_ref_num  = $this->t_teacher_info->get_teacher_ref_num($start_time,$teacher_ref_type);
            $teacher_ref_rate = \App\Helper\Utils::get_teacher_ref_rate($teacher_ref_num);
        }

        $teacher_money        = [];
        $already_lesson_count = [];
        $all_money            = [
            "teacher_money" => 0,
            "all_money"     => 0,
        ];

        $check_date = "2017-6-1";
        if($teacher_ref_type!=0){
            $teaid_list    = $this->t_teacher_info->get_tea_list_by_ref_type($teacher_ref_type,$teacherid);
            $teacherid_str = "";
            foreach($teaid_list as $tea_val){
                if($tea_val['create_time']>strtotime($check_date) && $tea_val['teacher_type']<20){
                    continue;
                }
                $tea_id = $tea_val['teacherid'];
                $teacherid_str .= $tea_id.",";
                if(!isset($teacher_money[$tea_id])){
                    $reward_money     = $this->t_teacher_money_list->get_teacher_reward_money($tea_id,$start_time,$end_time,3);
                    $reward_ref_money = $reward_money*$teacher_ref_rate;
                    $teacher_money[$tea_id]['money']             = $reward_money;
                    $teacher_money[$tea_id]['cost']              = 0;
                    $teacher_money[$tea_id]['teacher_ref_money'] = $reward_ref_money;
                    $teacher_money[$tea_id]['realname']          = mb_substr($tea_val['realname'],0,1,"utf8")."老师";
                    $teacher_money[$tea_id]['reference_name']    = $tea_val['reference_name'];
                    $teacher_money[$tea_id]['phone']             = substr($tea_val['phone'],0,3)."****".substr($tea_val['phone'],7,11);
                    $all_money['teacher_money']                 += $reward_money;
                    $all_money['all_money']                     += $reward_ref_money;
                }
            }

            $tea_list = $this->t_lesson_info_b2->get_tea_month_list($start_time,$end_time,trim($teacherid_str,","));
            foreach($tea_list as &$val){
                $tid          = $val['teacherid'];
                $lesson_count = $val['lesson_count']/100;
                if(!isset($already_lesson_count[$tid])){
                    $already_lesson_count[$tid] =
                        $this->t_lesson_info_b2->get_teacher_last_month_lesson_count($tid,$last_start_time,$last_end_time);
                }

                $val['already_lesson_count'] = $already_lesson_count[$tid];
                $this->get_lesson_cost_info($val);
                $reward = \App\Helper\Utils::get_teacher_lesson_money($val['type'],$val['already_lesson_count']);
                $money  = ($val['money']+$reward)*$lesson_count-$val['lesson_cost'];
                $teacher_ref_money = $money*$teacher_ref_rate;

                $teacher_money[$tid]['money']             += $money;
                $teacher_money[$tid]['teacher_ref_money'] += $teacher_ref_money;
                $all_money['teacher_money']               += $money;
                $all_money['all_money']                   += $teacher_ref_money;
            }
        }else{
              $show_teacher_money = [];
             return $this->pageView(__METHOD__,$show_teacher_money,[
                    "teacher_ref_type" => 1,
                    "all_money"        => 0,
                    "uid"              => $uid,
                ]);
        }

        $order_money_list   = [];
        $show_teacher_money = [];
        foreach($teacher_money as $money_val){
            if($money_val['money']>0){
                $show_teacher_money[] = $money_val;
                $order_money_list[]   = $money_val['money'];
            }
        }

        array_multisort($order_money_list,SORT_DESC,$show_teacher_money);
        $all_money['all_money'] = round($all_money['all_money'],2);

        $show_teacher_money = \App\Helper\Utils::list_to_page_info($show_teacher_money);


        return $this->pageView(__METHOD__,$show_teacher_money,[
            "teacher_ref_type" => $teacher_ref_type,
            "all_money"        => $all_money,
            "uid"              => $uid,
        ]);
    }

    public function set_train_lesson_time(){
        $lessonid     = $this->get_in_int_val("lessonid");
        $start_date   = $this->get_in_str_val("start_date",date("Y-m-d H:i",time()));

        $lesson_start = strtotime($start_date);
        $lesson_end   = $lesson_start+1800;

        $ret = $this->t_lesson_info_b2->field_update_list($lessonid,[
            "lesson_start" => $lesson_start,
            "lesson_end"   => $lesson_end,
        ]);

        if(!$ret){
            return $this->output_err("更改失败!请重试!");
        }
        return $this->output_succ();
    }


    public function add_complaint_info(){
        $msg            = $this->get_in_str_val('complaint_info');
        $teacherid      = $this->get_login_teacher();
        $complaint_type = 2;//课程投诉

        $account_type   = '2'; // 老师类型
        $ret_info_qc = $this->t_complaint_info->row_insert([
            'complaint_type' => $complaint_type,
            'userid'         => $teacherid,
            'account_type'   => $account_type,
            'add_time'       => time(NULL),
            'complaint_info' => $msg,
        ]);

        if ($ret_info_qc) {
            // 通知QC处理
            $log_time_date = date('Y-m-d H:i:s',time(NULL));
            $opt_nick      = $this->cache_get_teacher_nick($teacherid);

            $qc_openid_arr = [
                "wenbin",
                "李珉劼",
                "王浩鸣",
                "夏宏东",
                "ted"
            ];

            foreach($qc_openid_arr as $qc_item){
                $this->send_teacher_msg($qc_item,$opt_nick,$msg);
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
            $data['first']        = "您好,您的反馈我们已经收到! ";
            $data['keyword1']     = $msg;
            $data['keyword2']     = "已提交";
            $data['remark']       = "我们会在3个工作日内处理,感谢您的反馈!";

            $wx  = new \App\Helper\Wx();
            $ret = $wx->send_template_msg($teacher_openid,$template_id_teacher,$data ,$url);
            \App\Helper\Utils::logger("teac_msg:$ret,teacher_openid:$teacher_openid");

            return $this->output_succ();
        }
    }

    public function send_teacher_msg($qc_account,$opt_nick,$msg){
        $from_user  = "课程投诉";
        $header_msg = "$opt_nick 老师发布了一条投诉";
        $data = [
            "account"    => urlencode($qc_account),
            "from_user"  => urlencode($from_user),
            "header_msg" => urlencode($header_msg),
            "msg"        => "老师投诉内容:$msg",
            "url"        => "http://admin.yb1v1.com/user_manage/qc_complaint/"
        ];

        $post_url = "http://admin.yb1v1.com/common/send_wx_todo_msg?data=".base64_encode(json_encode($data));
        $qc_log   =  $this->send_curl_post($post_url);

    }
    public function test () {
        return $this->pageView(__METHOD__,[]);
    }

    public function  file_store()   {

        $teacherid      = $this->get_login_teacher();
        $dir= $this->get_in_str_val("dir");
        if (!$dir) {
            $dir= "/";
        }

        $store=new \App\FileStore\file_store_tea();
        $ret_list=$store->list_dir($teacherid, $dir);
        foreach ( $ret_list  as &$item  ) {
            if (!$item["is_dir"]) {
                \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            }
            $item["abs_path"] =  $dir .$item["file_name"];
            $item["file_size"]= \App\Helper\Common::size_str(@$item["file_size"] );
        }

        array_unshift( $ret_list, [ "is_dir" => true,
                                    "no_share_flag" =>true,
                                    "file_name" => "返回上级目录" ,
                                    "abs_path" => dirname($dir),
                                    "file_size" =>"",
                                    "create_time" =>"",
        ] );


        return $this->pageView(
            __METHOD__,
            \App\Helper\Utils::list_to_page_info($ret_list) ,["cur_dir"=>$dir] );

    }

    public function file_store_add_dir()  {
        $teacherid      = $this->get_login_teacher();
        $dir= $this->get_in_str_val("dir");
        $dir_name = trim($this->get_in_str_val("dir_name"));
        $obj_dir=$dir.$dir_name;

        \App\Helper\Utils::logger("obj_dir:$obj_dir");
        $store=new \App\FileStore\file_store_tea();
        $store->add_dir($teacherid,$obj_dir);
        \App\Helper\Utils::logger("ok ..");

        return $this->output_succ();
    }
    public function get_upload_token() {
        $store=new \App\FileStore\file_store_tea();
        $dir = $this->get_in_str_val("dir");
        $teacherid      = $this->get_login_teacher();

        $pre_dir=$store->get_dir($teacherid,$dir );
        $token=$store->get_upload_token();
        return $this->output_succ(["upload_token"=> $token, "pre_dir" => $pre_dir ]);

    }
    public function get_download_url() {
        $file_path = $this->get_in_str_val("file_path");
        $teacherid      = $this->get_login_teacher();

        $store=new \App\FileStore\file_store_tea();
        $auth=$store->get_auth();
        $file_path = $store->get_file_path($teacherid,$file_path);
        $authUrl = $auth->privateDownloadUrl("http://file-store.leo1v1.com/". $file_path );
        return $this->output_succ(["url" => $authUrl]);
    }
    public function get_share_link() {
        $teacherid      = $this->get_login_teacher();
        $share_path = $this->get_in_str_val("share_path");

        $now=time();
        $create_time=$now;
        $end_time=$create_time+86400*10;
        $arr=[
            "teacherid" => $teacherid,
            "share_path" => $share_path,
            "create_time" => $create_time,
            "end_time" => $end_time ,
            "md5_sum" => substr( md5("$teacherid:$share_path:$create_time,$end_time"), 0, 16)
        ];
        $key= "xcwen142857xcwAB";
        $sign= \App\Helper\Common::encrypt( json_encode($arr),$key);

        return $this->output_succ(["sign"=> $sign] );
        //echo urlencode($sign)."<br/>";
        //echo \App\Helper\Common::decrypt($sign,$key);

    }

    public function base_info() {

    }

    public function file_store_del_file() {
        $teacherid      = $this->get_login_teacher();
        $path= $this->get_in_str_val("path");
        $store=new \App\FileStore\file_store_tea();
        $store->del_file($teacherid,$path);
        return $this->output_succ();
    }

    public function file_store_rename() {
        $teacherid      = $this->get_login_teacher();
        $old_path= $this->get_in_str_val("old_path");
        $new_name= $this->get_in_str_val("new_name");
        $store=new \App\FileStore\file_store_tea();
        $store->rename_file($teacherid,$old_path,$new_name);
        return $this->output_succ();
    }
    public  function file_share() {

        $sign=$this->get_in_str_val("sign");
        $dir = $this->get_in_str_val("dir");
        if (!$dir) {
            $dir="/";
        }
        $key= "xcwen142857xcwAB";
        $data=@\App\Helper\Utils::json_decode_as_array(\App\Helper\Common::decrypt($sign,$key));
        if (!is_array($data)) {
            //check md5
            return $this->error_view([
                "无效链接"
            ]);
        }
        $teacherid = $data["teacherid"] ;
        $share_path = $data["share_path"] ;
        $create_time = $data["create_time"] ;
        $end_time = $data["end_time"] ;
        $md5_sum= $data["md5_sum"] ;



        if( $md5_sum!== substr( md5("$teacherid:$share_path:$create_time,$end_time"), 0, 16)) {
            return $this->error_view([
                "md5 校验失败"
            ]);
        }

        $file_name="";
        if ($share_path[strlen( $share_path)-1]!="/") {
            $file_name=basename($share_path );
            $share_path=dirname($share_path );
        }


        $store=new \App\FileStore\file_store_tea();
        $obj_dir=  rtrim(  rtrim ($share_path,"/"). "/" . trim( $dir , "/" ), "/")  ."/";
        $ret_list=$store->list_dir($teacherid, $obj_dir);
        $list=[];
        foreach ( $ret_list  as $item  ) {
            if (!$item["is_dir"]) {
                \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            }
            $item["abs_path"] =  $dir .$item["file_name"];
            $item["file_size"]= \App\Helper\Common::size_str(@$item["file_size"] );
            if ( $file_name ==""  ) {
                $list[]=$item;
            }else if ( $file_name== $item["file_name"]) {
                $list[]=$item;
            }
        }

        array_unshift( $list, [
            "is_dir"      => 1,
            "file_name"   => "返回上级目录" ,
            "abs_path"    => dirname($dir),
            "file_size"   => "",
            "create_time" => "",
        ]);

        return $this->pageView(
                __METHOD__,
                \App\Helper\Utils::list_to_page_info($list) ,["cur_dir"=>$dir] );

    }

    public function get_share_download_url( ) {


        $sign=$this->get_in_str_val("sign");
        $file_path= $this->get_in_str_val("file_path");
        $key= "xcwen142857xcwAB";
        $data=@\App\Helper\Utils::json_decode_as_array(\App\Helper\Common::decrypt($sign,$key));
        if (!is_array($data)) {
            //check md5
            return $this->error_view([
                "无效链接"
            ]);
        }
        $teacherid = $data["teacherid"] ;
        $share_path = $data["share_path"] ;
        $create_time = $data["create_time"] ;
        $end_time = $data["end_time"] ;
        $md5_sum= $data["md5_sum"] ;

        if( $md5_sum!== substr( md5("$teacherid:$share_path:$create_time,$end_time"), 0, 16)) {
            return $this->error_view([
                "md5 校验失败"
            ]);
        }


        $store=new \App\FileStore\file_store_tea();

        $auth=$store->get_auth();

        $file_path =    rtrim ($share_path,"/"). "/" . trim( $file_path)   ;
        $file_path = $store->get_file_path($teacherid,$file_path);
        $authUrl = $auth->privateDownloadUrl("http://file-store.leo1v1.com/". $file_path );
        return $this->output_succ(["url" => $authUrl]);
    }

    public function get_teacher_basic_info(){
        $teacherid = $this->get_login_teacher();
        $ret_info  = $this->t_teacher_info->get_teacher_info_to_teacher($teacherid);
        foreach ($ret_info['list'] as &$item) {
            E\Esubject::set_item_value_str($item);
            // E\Egarde_part_ex::set_item_value_str($item);
            E\Etextbook_type::set_item_value_str($item);
            E\Eidentity::set_item_value_str($item);
            E\Eteacher_ref_type::set_item_value_str($item);
            E\Egender::set_item_value_str($item);
            $now_day      = strtotime( 'today' );
            $first_day    = strtotime( date('Y-m-d', $item['create_time']) );
            $item['days'] = ($now_day - $first_day)/86400;
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            if($item['teacher_money_type'] == 0) {
                $item['level'] = $item['level'] + 2;
            } else {
                $item['level'] = $item['level'] + 1;
            }
            $item['normal_count'] = $item['normal_count']/100;
        }
        return $this->pageView(__METHOD__,$ret_info,[
            "my_info" => $ret_info['list'][0],
        ]);
    }

    public function edit_teacher_info(){
        $teacherid = $this->get_login_teacher();
        $nick      = $this->get_in_str_val('nick','');
        $gender    = $this->get_in_str_val('gender','');
        $birth     = $this->get_in_int_val('birth','');
        $email     = $this->get_in_str_val('email','');
        $work_year = $this->get_in_int_val('work_year','');
        $phone     = $this->get_in_int_val('phone','');
        $school    = $this->get_in_str_val('school','');
        if(!$teacherid) {
            return $this->output_err('信息有误，请重新登录！');
        }
        if ($nick == '') {
            return $this->output_err('姓名不能为空！');
        }
        if ($birth == '') {
            return $this->output_err('出生日期不能为空！');
        }
        if ($email == '') {
            return $this->output_err('邮箱不能为空！');
        }

        $is_email = preg_match("/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $email);
        if (!$is_email) {
            return $this->output_err('请填写正确的邮箱地址！');
        }

        if ($phone == '') {
            return $this->output_err('手机号不能为空！');
        }
        $is_phone = preg_match("/^1[34578]\d{9}$/", $phone);
        if (!$is_phone) {
            return $this->output_err('请填写正确的手机号码！');
        }
        if ($school == '') {
            return $this->output_err('毕业院校不能为空！');
        }

        $ret_info = $this->t_teacher_info->update_teacher_info($teacherid, $nick, $gender, $birth, $email, $work_year, $phone, $school);
        return outputjson_success();
    }

    public function edit_teacher_bank_info(){
        $teacherid = $this->get_login_teacher();
        $bank_account  = $this->get_in_str_val('bank_account','');
        $idcard        = trim( $this->get_in_str_val('idcard','') );
        $bank_type     = trim($this->get_in_str_val('bank_type','') );
        $bank_address  = $this->get_in_str_val('bank_address','');
        $bank_province = $this->get_in_str_val('bank_province','');
        $bank_city     = $this->get_in_str_val('bank_city','');
        $bankcard      = trim($this->get_in_str_val('bankcard','') );
        $bank_phone    = trim($this->get_in_int_val('bank_phone','') );
        if(!$teacherid) {
            return $this->output_err('信息有误，请重新登录！');
        }
        if ($bank_account == '') {
            return $this->output_err('持卡人不能为空！');
        }
        if ($idcard == '') {
            return $this->output_err('身份证号不能为空！');
        }
        if (strlen($idcard) !== 18) {
            return $this->output_err('身份证号码不正确！');
        }
        if ($bankcard == '') {
            return $this->output_err('银行卡号不能为空！');
        }
        if ($bank_phone == '') {
            return $this->output_err('手机号不能为空！');
        }
        $is_phone = preg_match("/^1[34578]\d{9}$/", $bank_phone);
        if (!$is_phone) {
            return $this->output_err('请填写正确的手机号码！');
        }

        $ret_info = $this->t_teacher_info->update_teacher_bank_info($teacherid, $bank_account, $idcard, $bankcard, $bank_phone, $bank_type, $bank_address, $bank_province, $bank_city);
        return outputjson_success();
    }

    public function get_teacher_money_info(){
        $teacherid = $this->get_login_teacher();
        return $this->pageView(__METHOD__);
    }

    public function edit_teacher_status(){
        $teacherid = $this->get_login_teacher();
        $status = $this->get_in_str_val('status');
        if ( $status == 'full' ) {
            $need_test_lesson_flag = 1;
        } else {
            $need_test_lesson_flag = 0;
        }

        $res_info = $this->t_teacher_info->update_teacher_status($teacherid, $need_test_lesson_flag);
        if ($res_info) {
            return outputjson_success();
        } else {
            return outputjson_error('发生错误，设置失败！');
        }
    }

    public function update_teacher_pdf_info(){
        $teacherid = $this->get_login_teacher();
        $field     = $this->get_in_str_val('opt_field', '');
        $url       = $this->get_in_str_val('get_pdf_url', '');
        if ( $field=='' || $url=='' ) {
            $this->output_err("上传信息为空！");
        }
        $res_info = $this->t_teacher_info->field_update_list(["teacherid" => $teacherid],
            [$field  => $url]
        );

        if ($res_info) {
            return outputjson_success();
        } else {
            return outputjson_error('发生错误，设置失败！');
        }

    }
}
