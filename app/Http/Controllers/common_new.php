<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;

use Qiniu\Auth;

use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

use App\Jobs\deal_pdf_to_image;

require_once  app_path("/Libs/Qiniu/functions.php");


class common_new extends Controller
{
    var $check_login_flag = false;
    use TeaPower;

    function get_env() {
        return outputjson_success(["env"=>
                                   \Illuminate\Support\Facades\App::environment()
        ]);
    }
    public function get_account_name() {
        $wiki_key=$this->get_in_str_val("wiki_key");
        $account=\App\Helper\Common::redis_get($wiki_key);
        \App\Helper\Common::redis_del($wiki_key);
        return $this->output_succ(["account"=> $account]);
    }



    function send_err_mail()
    {
        $to=$this->get_in_str_val("to");
        $title=$this->get_in_str_val("title");
        $body=trim($this->get_in_str_val("body"));

        $body.="<br/>from:  " .$this->get_in_client_ip();

        dispatch( new \App\Jobs\send_error_mail( $to,$title,$body ) );

    }


    public function send_mail() {
        //$ret=\App\Helper\Common::send_mail("xcwenn@qq.com", "asdfa", "content ..."  );
    }
    public function upload_xls_data() {

        $xls_data=$this->get_in_str_val("xls_data");

        session([
            "xls_data"=>json_decode($xls_data,true),
        ]);
        return outputjson_success();

    }
    public function download_xls ()  {
        $xls_data= session("xls_data" );
        if(!is_array($xls_data)) {
            return $this->output_err("download error");
        }

        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator("jim ")
                             ->setLastModifiedBy("jim")
                             ->setTitle("jim title")
                             ->setSubject("jim subject")
                             ->setDescription("jim Desc")
                             ->setKeywords("jim key")
                             ->setCategory("jim  category");

        $objPHPExcel->setActiveSheetIndex(0);

        $col_list=[
            "A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T", "U","V","W","X","Y","Z"
            ,"AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ"
            ,"BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM","BN","BO","BP","BQ","BR","BS","BT","BU","BV","BW","BX","BY","BZ"
            ,"CA","CB","CC","CD","CE","CF","CG","CH","CI","CJ","CK","CL","CM","CN","CO","CP","CQ","CR","CS","CT","CU","CV","CW","CX","CY","CZ"

        ];

        foreach( $xls_data as $index=> $item ) {
            foreach ( $item as $key => $cell_data ) {
                $index_str = $index+1;
                $pos_str   = $col_list[$key].$index_str;
                $objPHPExcel->getActiveSheet()->setCellValue( $pos_str, $cell_data);
            }
        }

      $date=\App\Helper\Utils::unixtime2date (time(NULL));
      header('Content-type: application/vnd.ms-excel');
      header( "Content-Disposition:attachment;filename=\"$date.xlsx\"");

      $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

      $objWriter->save('php://output');
    }

    public function recode_server_init () {
        $ip=$this->get_in_client_ip();
        $this->t_audio_record_server->add_server($ip);
        $config_userid=$this->t_audio_record_server->get_config_userid($ip);
        if (!$config_userid) {

            $arr=preg_split("/\\./", $ip);

            for( $config_userid=$arr[3]; true; $config_userid+=1000 ) {
                $db_ip=$this->t_audio_record_server->get_ip_from_config_userid($config_userid);
                if(!$db_ip) { //可以insert
                    $this->t_audio_record_server->field_update_list($ip,[
                        "config_userid" => $config_userid,
                    ]);
                    break;
                }
            }
        }
        return $this->output_succ(["config_userid" => $config_userid]);

    }

    public function reset_lesson_count () {
        $studentid=$this->get_in_studentid();
        $job= new \App\Jobs\StdentResetLessonCount($studentid);
        dispatch($job);
        return outputjson_success();
    }
    public function get_cur_lesson_count() {
        $ret_list=$this->t_lesson_info->get_current_lessons("");
        $logtime=time(NULL);

        $online_count=  count($ret_list);
        $this->t_online_count_log->add($logtime-$logtime%60,$online_count);

        $ret = $this->t_lesson_info_b2->get_finish_lessons();
        $finish_count=  count($ret);

        return date("Y-m-d H:i:s")." ".$online_count ."\n";
    }

    public function get_need_recode_lesson_list() {
        $client_ip=$this->get_in_client_ip();
        $get_self_flag=$this->get_in_int_val("get_self_flag",0);
        if (!$get_self_flag) {
            $client_ip="";
        }
        if ($client_ip) {
            $this->t_audio_record_server->add_server($client_ip);
        }

        //处理
        $ret_list=$this->t_lesson_info->get_current_lessons($client_ip);
        foreach($ret_list as &$item){
            $item['room_id'] = \App\Helper\Utils::gen_roomid_name(
                $item["lesson_type"],
                $item["courseid"],
                $item["lesson_num"]);
        }

        return $this->output_succ(["data"=>$ret_list]);
    }

    public function goto_url(){
        $code = $this->get_in_str_val("code");
        $url  = $this->get_in_str_val("url");
        header("Location: $url&code=$code" );
    }

    public function noti_record_lesson() {
        dispatch(new \App\Jobs\do_record_audio());
        return $this->output_succ();
    }

    public function get_seller_menu_info() {
        $admin_str = $this->get_in_str_val("adminid");
        $split_arr = preg_split("/,/",$admin_str);
        $adminid   = $split_arr[0];

        $next_revisit_count = $this->t_seller_student_new->get_today_next_revisit_count($adminid);
        $require_info     = $this->t_test_lesson_subject->get_require_and_return_back_count($adminid);
        $notify_lesson_info = $this->t_test_lesson_subject_require->get_notify_lesson_info($adminid);
        $row_item=$this->t_seller_student_new-> get_lesson_status_count($adminid );
        $no_confirm_count = $this->t_test_lesson_subject_require->get_no_confirm_count($adminid);

        $row_item["adminid"]=$admin_str;
        return $this->output_succ(
            [  "data"=> array_merge($row_item, $require_info,$notify_lesson_info , [
                "next_revisit_count"=> $next_revisit_count,
                "no_confirm_count"=> $no_confirm_count,
            ] )]
        );
    }

    public function check_login_jump_key() {
        $admin_str = $this->get_in_str_val("adminid");
        $split_arr = preg_split("/,/",$admin_str);
        $adminid   = $split_arr[0];

        $login_jump_key=$this->get_in_str_val("login_jump_key");
        $arr=json_decode(\App\Helper\Utils::decode_str($login_jump_key));
        if ($adminid==$arr[0] && time(NULL)-$arr[1] <300 ) {
            $login_flag=1;
        }else{
            $login_flag=0;
        }
        return $this->output_succ(["login_flag"=>$login_flag]);
    }

    public function test_php() {
        print_r($_REQUEST);
    }

    public function add_teacher_lecture_appoinment_info_for_new(){
        $answer_begin_time            = strtotime($this->get_in_str_val("answer_begin_time"));
        $answer_end_time              = strtotime($this->get_in_str_val("answer_end_time"));
        $name                         = $this->get_in_str_val("name");
        $phone                        = trim($this->get_in_str_val("phone"));
        $email                        = $this->get_in_str_val("email");
        $grade                        = $this->get_in_int_val("grade");
        $grade_start                  = $this->get_in_int_val("grade_start");
        $grade_end                    = $this->get_in_int_val("grade_end");
        $subject_ex                   = $this->get_in_str_val("subject_ex");
        $textbook                     = trim($this->get_in_str_val("textbook"),",");
        $school                       = $this->get_in_str_val("school");
        $teacher_type                 = $this->get_in_str_val("teacher_type");
        $self_introduction_experience = $this->get_in_str_val("self_introduction_experience");
        $reference                    = substr(trim($this->get_in_str_val("reference"),"="),0,11);
        $custom                       = $this->get_in_str_val("custom");
        $lecture_appointment_status   = $this->get_in_int_val("lecture_appointment_status",0);
        $lecture_appointment_origin   = $this->get_in_int_val("lecture_appointment_origin",0);
        $qq                           = $this->get_in_str_val("qq","");
        $full_time                    = $this->get_in_int_val("full_time");
        $is_test_user                 = $this->get_in_int_val("is_test_user");

        if(!preg_match("/^1[34578]{1}\d{9}$/",$phone) && !in_array($reference,["13661763881","18790256265"])){
            return $this->output_err("请填写正确的手机号！");
        }
        $check_flag = $this->t_teacher_lecture_appointment_info->check_is_exist(0,$phone);
        if($check_flag){
            return $this->output_err("该手机号已提交过了,不能重新提交!");
        }
        $teacher_info = $this->t_teacher_info->get_teacher_info_by_phone($phone);
        if(!empty($teacher_info) && $teacher_info['trial_lecture_is_pass']==1){
            return $this->output_err("该手机号已通过试讲,不能重新提交!");
        }
        if($qq!="" && !ctype_digit(trim($qq,""))){
            return $this->output_err("请填写正确的qq号码!");
        }
        if($subject_ex==0){
            return $this->output_err("请选择科目!");
        }
        if($teacher_type=="" || $teacher_type==0){
            return $this->output_err("请选择您的教学经历!");
        }
        //合并田克平两个推荐渠道到一个账号中
        if($reference=="18707976382"){
            $reference = "13387970861";
        }

        $grade = $this->check_grade_by_subject($grade,$subject_ex);
        if($grade!=0){
            $grade_range = \App\Helper\Utils::change_grade_to_grade_range($grade);
            $grade_start = $grade_range['grade_start'];
            $grade_end   = $grade_range['grade_end'];
        }
        if($grade_start==0 || $grade_end==0){
            return $this->output_err("请选择规范的年级!");
        }

        if($full_time==1){
            $accept_adminid=492; 
        }else{
            $accept_adminid = $this->get_zs_accept_adminid($reference); 
        }
        $accept_time=0;
        if($accept_adminid>0){
            $accept_time = time();
        }

        $data = [
            "answer_begin_time"            => time(NULL),
            "answer_end_time"              => time(NULL)+3600,
            "name"                         => $name,
            "phone"                        => $phone,
            "email"                        => $email,
            "grade_start"                  => $grade_start,
            "grade_end"                    => $grade_end,
            "grade_ex"                     => $grade,
            "subject_ex"                   => $subject_ex,
            "school"                       => $school,
            "textbook"                     => $textbook,
            "teacher_type"                 => $teacher_type,
            "self_introduction_experience" => $self_introduction_experience,
            "reference"                    => $reference,
            "custom"                       => $custom,
            "lecture_appointment_status"   => $lecture_appointment_status,
            "lecture_appointment_origin"   => $lecture_appointment_origin,
            "qq"                           => $qq,
            "full_time"                    => $full_time,
            "accept_adminid"               => $accept_adminid,
            "accept_time"                  => $accept_time
        ];

        $ret = $this->t_teacher_lecture_appointment_info->row_insert($data);
        if($ret){
            $teacher_info['phone']         = $phone;
            $teacher_info['tea_nick']      = $name;
            $teacher_info['send_sms_flag'] = 0;
            $teacher_info['wx_use_flag']   = 0;
            $teacher_info['use_easy_pass'] = 2;
            $teacher_info['is_test_user']  = $is_test_user;

            \App\Helper\Utils::logger("teacher appointment:".$phone."data:".json_encode($data));
            if($email!=""){
                if($full_time==1){
                    $html = $this->get_full_time_html($data);
                }else{
                    $this->add_teacher_common($teacher_info);
                    $html = $this->get_email_html_new($name);
                }
                $title = "【理优1对1】试讲邀请和安排";
                $ret   = \App\Helper\Common::send_paper_mail_new($email,$title,$html);
            }

            /**
             * 模板类型:短信通知
             * 模板名称:老师报名模板 8-16
             * 模板ID:SMS_86000023
             * 模板内容:${name}老师，您好！您已成功报名！请在${time}前，按照要求进行15分钟的课程试讲，相关信息已发至您邮箱（如找不到请检查垃圾箱），请尽快查阅。请关注并绑定“理优1对1老师帮”随时随地了解入职进度。理优致力于打造高水平的教学服务团队，期待您的到来，加油！
             */
            $template_code = 86000023;
            $time = date("Y-m-d",strtotime("+3 day",time()));
            $sms_data = [
                "name" => $name,
                "time" => $time,
            ];
            \App\Helper\Utils::sms_common($phone,$template_code,$sms_data);

            if($reference != ""){
                /**
                 * 模板ID : kvkJPCc9t5LDc8sl0ll0imEWK7IGD1NrFKAiVSMwGwc
                 * 标题   : 反馈进度通知
                 * {{first.DATA}}
                 * 反馈内容：{{keyword1.DATA}}
                 * 处理结果：{{keyword2.DATA}}
                 * {{remark.DATA}}
                 */
                $reference_info = $this->t_teacher_info->get_reference_info_by_phone($phone);
                \App\Helper\Utils::logger("reference_info".json_encode($reference_info));
                $wx_openid      = $reference_info['wx_openid'];
                $teacher_type   = $reference_info['teacher_type'];
                if($wx_openid!="" && !in_array($teacher_type,[21,22,31])){
                    \App\Helper\Utils::logger("微信推送".$reference);

                    $record_info = $name."已填写报名信息";
                    $status_str  = "已报名";
                    \App\Helper\Utils::send_reference_msg_for_wx($wx_openid,$record_info,$status_str);
                }
            }

            //全职老师推送蔡老师,范老师
            if($full_time==1 && $accept_adminid>0){
                $this->t_manager_info->send_wx_todo_msg_by_adminid ($accept_adminid,"全职老师注册成功","全职老师注册成功",$name."老师已经成功注册报名,请尽快安排1对1面试课程","");
                $this->t_manager_info->send_wx_todo_msg_by_adminid (986,"全职老师注册成功","全职老师注册成功",$name."老师已经成功注册报名,请尽快安排1对1面试课程","");
                $this->t_manager_info->send_wx_todo_msg_by_adminid (1043,"全职老师注册成功","全职老师注册成功",$name."老师已经成功注册报名,请尽快安排1对1面试课程","");
            }

            return $this->output_succ();
        }else{
            return $this->output_err("添加失败，请重试！");
        }
    }

    public function send_lecture_email(){
        $email = $this->get_in_str_val("email");
        $id    = $this->get_in_int_val("id");
        $name  = $this->get_in_str_val("name");

        \App\Helper\Utils::logger("lecture email:".$email."time :".date("Y-m-d H:i",time()));
        if($email!=""){
            $html  = $this->get_email_html_new($name);
            $title = "【理优1对1】试讲邀请和安排";
            $ret   = \App\Helper\Common::send_paper_mail_new($email,$title,$html);
            if(!$ret){
                return $this->output_err("邮件发送失败!");
            }
        }

        return $this->output_succ();
    }


    public function upload_from_xls_data($obj_file) {
        $grade_map = [
            '200'    => 201,
            '小学'   => 100,
            '初中'   => 200,
            '高中'   => 300,
            '八年级' => 202,
            '初二'   => 202,
            '初三'   => 203,
            '初一'   => 201,
            '二年级' => 102,
            '高二'   => 302,
            '高三'   => 303,
            '高一'   => 301,
            '九年级' => 203,
            '六年级' => 201,
            '七年级' => 202,
            '三年级' => 103,
            '四年级' => 104,
            '未填写' => 100,
            '五年级' => 105,
            '小二'   => 102,
            '小六'   => 106,
            '小三'   => 103,
            '小四'   => 104,
            '小五'   => 106,
            '小学'   => 100,
            '小一'   => 101,
            '学龄前' => 101,
            '一年级' => 101,
        ];
        $subject_map = array(
            "语文" => 1,
            "数学" => 2,
            "英语" => 3,
            "化学" => 4,
            "物理" => 5,
            "生物" => 6,
            "政治" => 7,
            "历史" => 8,
            "地理" => 9,
        );
        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');

        $objPHPExcel = $objReader->load($obj_file);
        $objPHPExcel->setActiveSheetIndex(0);
        $arr=$objPHPExcel->getActiveSheet()->toArray();
        foreach ($arr as $index => $item) {
            if ($index== 0) { //标题
                //验证字段名
                if (trim($item[0]) != "手机号"
                    ||trim($item[1]) != "归属地"
                    ||trim($item[3]) != "来源"
                ) {
                    return "xxx" ;
                }
            } else {
                //导入数据
                /*
                  0 => "手机号"
                  1 => "归属地"
                  2 => "时间"
                  3 => "来源"
                  4 => "姓名"
                  5 => "用户备注"
                  6 => "年级"
                  7 => "科目"
                  8 => "是否有pad"
                  9 => "家长姓名"
                */
                $phone          = $item[0]*1;
                $phone_location = $item[1];
                $origin         = $item[3];
                $nick           = $item[4];
                $user_desc      = $item[5];
                $grade          = trim($item[6]);
                $subject        = $item[7];
                $has_pad        = $item[8];
                $parent_name = @$item[9] ;
                \App\Helper\Utils::logger("DO phone:$phone");


                if (isset($grade_map[$grade])) {
                    $grade = $grade_map[$grade] ;
                }

                $subject_str=$subject;
                if (isset($subject_map[$subject])) {
                    $subject = $subject_map[$subject] ;
                }


                if (strpos($has_pad, "iPad")!== false) {
                    $has_pad=1;
                } elseif (strpos($has_pad, "安卓") !== false) {
                    $has_pad=2;
                } else{
                    $has_pad=0;
                }


                if ($phone>10000) {
                    $this->t_seller_student_new->book_free_lesson_new(
                        $nick,$phone,$grade,$origin,$subject,
                        $has_pad,$user_desc,$parent_name);
                }
            }
        }
    }

    public function get_qiniu_download() {
        $file_url=$this->get_in_str_val("file_url");
        $public_flag=$this->get_in_int_val("public_flag",0);
        if ($public_flag) {
            $config=\App\Helper\Config::get_config("qiniu");
            $base_url=$config["public"]["url"];
            $url=$base_url."/".$file_url;
         }else{
           $url= \App\Helper\Utils::gen_download_url($file_url);
        }

        return $this->output_succ([
            "url"=> $url
        ]);
    }

    public function get_rebind_ssh_flag( ){
        return "1";
    }

    public function set_lesson_abnormal(){
        $lessonid        = $this->get_in_int_val("lessonid");
        $lesson_abnormal = $this->get_in_str_val("lesson_abnormal");
        if($lessonid==0){
            return $this->output_err("课程id出错！请重新进入本页面反馈！");
        }
        if($lesson_abnormal==""){
            return $this->output_err("请填写反馈内容！");
        }


        $ret = $this->t_lesson_info->field_update_list($lessonid,[
            "lesson_abnormal" => $lesson_abnormal
        ]);

        if(!$ret){
            return $this->output_err("提交失败！请重试！");
        }
        return $this->output_succ();
    }

    public function  tianrun_notify_called() {
        /*
          enterpriseId 企业Id 企业号，7位数字；如：3000290
          customerNumber 客户号码 客户的号码；如：01087128906
          customerNumberType 客户号码类型 客户号码类型，其值为1或2；1--固话，2--手机
          customerAreaCode 客户号码所在地区 010
          cno 座席工号 2000
          callType 呼叫类型 1:呼入 2:web400呼入
          mainUniqueId 通话记录唯一标识 一通呼叫的唯一标识；如：ccic2_202-1367040082.6
          taskId 任务id
          -------------
        */
        $phone=$this->get_in_str_val("customerNumber");
        $cno =$this->get_in_str_val("cno");
        \App\Helper\Utils::logger("$phone, $cno");

        /*
        // check
        $userid=$this->t_seller_student_new->get_userid_by_phone($phone);
        $ss_info= $this->t_seller_student_new->field_get_list($userid,"*");
        if ($ss_info["admin_revisiterid"] ==0  ) {
            $opt_type=0;
            $admin_info= $this->t_manager_info->get_info_by_tquin($cno);
            if ($admin_info) {
                $adminid= $admin_info["uid"];
                $account=$admin_info["account"];
                if(!$this->t_seller_student_new->check_admin_add($adminid,$get_count,$max_day_count )){
                    $this->t_manager_info->send_wx_todo_msg($account,"抢单", "目前你持有的例子数[$get_count]>=最高上限[$max_day_count]");
                    return json_encode(["result"=>"success"]);
                }
                if (!$this->t_seller_new_count->check_and_add_new_count($adminid,"获取新例子"))  {
                    $this->t_manager_info->send_wx_todo_msg($account,"抢单", "今天的配额,已经用完了");
                    return json_encode(["result"=>"success"]);
                }

                $this->t_manager_info->send_wx_todo_msg($account,"抢单", "成功!电话[$phone]");
                $this->t_seller_student_new->set_admin_info(
                    $opt_type, [$userid], $adminid, $adminid   );

            }
        }
        */
        return json_encode(["result"=>"success"]);
    }

    public function  tianrun_notify_call_end() {
        /*
          cdr_main_unique_id 通话记录唯一标识 一通呼叫的唯一标识；如：ccic2_202-1367040082.6
          cdr_enterprise_id 企业Id 企业号，7位数字；如：3000290
          cdr_customer_number 客户号码 客户的号码；如：01087128906
          cdr_customer_area_code 呼入或外呼座席接听后的座席区号 3位或4位电话区号；如：010
          cdr_customer_number_type 来电或外呼客户号码类型: 手机/固话 客户号码类型，其值为1或2；1--固话，2--手机
          cdr_start_time 呼叫座席时间 UNIX时间戳；如：1367040082（易理解格式为2013-04-27 13:21:22）
          cdr_answer_time 座席接听时间 UNIX时间戳；同上
          cdr_bridge_time 客户接听时间 UNIX时间戳；同上
          cdr_end_time 挂机时间 UNIX时间戳；同上
          cdr_call_type 呼叫类型 3:点击外呼 4:预览外呼
          cdr_status 通话状态 21:（点击外呼、预览外呼时）座席接听，客户未接听(超时) 22:（点击外呼、预览外呼时）座席接听，客户未接听(空号拥塞) 24:（点击外呼、预览外呼时）座席未接听 28:双方接听
          cdr_mark 标识 1：留言 2：咨询 3：转移 7：交互
          cdr_number_trunk 外显号码 没有区号的8为号码，如：59222903
          cdr_bridged_cno 呼出接听电话的座席号码 如：2000
          CDR(userfield) 使用第三方外呼调用接口时传递了参数userField 该值只是第三方外呼调用接口发起的呼叫，且传递了userField参数，在挂机推送时用来获取userField传递的值。

        */
        $cdr_bridge_time=$this->get_in_int_val("cdr_bridge_time");
        //$cdr_answer_time=$this->get_in_int_val("cdr_answer_time");
        $uniqueId= $this->get_in_str_val("cdr_main_unique_id");

        $cdr_answer_time = intval( preg_split("/\-/", $uniqueId)[1]);
        $cdr_end_time=$this->get_in_int_val("cdr_end_time");

        $cdr_bridged_cno = $this->get_in_int_val("cdr_bridged_cno");
        $cdr_status = $this->get_in_int_val("cdr_status");

        $recid= ($cdr_bridged_cno<<32 ) + $cdr_answer_time;
        $cdr_customer_number = $this->get_in_str_val("cdr_customer_number");

        $duration=0;
        if ($cdr_bridge_time ) {
            $duration= $cdr_end_time-$cdr_bridge_time;
        }
        \App\Helper\Utils::logger("duration ,$duration, $cdr_bridge_time");


        $called_flag=($cdr_status==28 && $duration>30  )?2:1;

        $this->t_tq_call_info->add(
            $recid,
            $cdr_bridged_cno,
            $cdr_customer_number,
            $cdr_answer_time,
            $cdr_end_time,
            $duration,
            $called_flag==2?1:0
            ,
            "");
        $this->t_seller_student_new->sync_tq($cdr_customer_number ,$called_flag, $cdr_answer_time, $cdr_bridged_cno );
        return json_encode(["result"=>"success"]);
    }

    public function notify_gen_lesson_teacher_pdf_pic() {
        $lessonid = $this->get_in_lessonid();
        $pdf_url  = $this->t_lesson_info->get_tea_cw_url($lessonid);

        // dispatch(new deal_pdf_to_image($pdf_url, $lessonid));

        $this->t_pdf_to_png_info->row_insert([
            'lessonid'    => $lessonid,
            'pdf_url'     => $pdf_url,
            'create_time' => time()
        ]);
    }

    public function get_banner_pic_list(){
        $type       = $this->get_in_int_val("type");
        $usage_type = $this->get_in_int_val("usage_type");

        $list = $this->t_pic_manage_info->get_banner_pic_list($type,$usage_type);

        return $this->output_succ(["list"=>$list]);
    }

    public function   get_teacher_login_token( )  {
        $teacherid = $this->get_in_teacherid();
        $gen_time  = time(NULL);
        $str       = json_encode( [
            "gen_time" => $gen_time,
            "uid"      => $teacherid,
            "md5"      => md5( $gen_time ),
        ]);

        return $this->output_succ(
            \OUTPUT_get_login_token_out::class
            ,[
                "teacherid"  => $teacherid,
                "login_token"  => bin2hex( \App\Helper\Common::encrypt($str, "xcwen@jim142857kk001!" )),
            ]);
    }

    public function check_grade_by_subject($grade,$subject){
        if($subject>3 && $subject<10 && $grade==100){
            $grade=200;
        }elseif($subject==10){
            $grade=200;
        }
        return $grade;
    }

    public function lesson_require_obtain  () {
        $teacherid= $this->get_in_teacherid();
        $require_id= $this->get_in_int_val("require_id");
        $require_info= $this->t_test_lesson_subject_require->field_get_list($require_id,"curl_stu_request_test_lesson_time ,test_stu_grade");
        $lesson_start=$require_info["curl_stu_request_test_lesson_time"];
        $grade=$require_info["test_stu_grade"];
        $adminid="886";
        $account="老师抢单";

        return $this->course_set_new_ex($require_id,$teacherid,$lesson_start,$grade,$adminid,$account);

    }

    public function notice_reference_for_lecture(){
        $phone = $this->get_in_str_val("phone");

        $teacher_info   = $this->t_teacher_info->get_teacher_info_by_phone($phone);
        $reference_info = $this->t_teacher_info->get_reference_info_by_phone($phone);
        if(!empty($reference_info)){
            $wx_openid    = $reference_info['wx_openid'];
            $teacher_type = $reference_info['teacher_type'];
            if($wx_openid!="" && !in_array($teacher_type,[21,22,31])){
                $record_info = $teacher_info['nick']."已录制试讲视频";
                $status_str  = "已录制";
                \App\Helper\Utils::send_reference_msg_for_wx($wx_openid,$record_info,$status_str);
            }
        }
    }
    public function get_qiniu_file ()  {
        $file=$this->get_in_str_val("file");
        $file=\App\Helper\Utils::decode_str($file);
        $url= \App\Helper\Utils::gen_download_url($file);
        header ( "Location: $url");
    }
    //JIM
    public function get_office_cmd() {
        $item=\App\Helper\office_cmd::do_one();
        return $this->output_succ( ["cmd"=>$item] );
    }

    public function show_create_table_list() {
        if ( !\App\Helper\Utils::check_env_is_local() ){
            return $this->output_err("没有权限");
        }

        $table_list = json_decode($this->get_in_str_val("table_list"));
        $ret_map    = [];
        foreach ($table_list as $db_table_name) {
            $create_sql = sprintf("show create table %s", $db_table_name );
            $desc_sql   = sprintf("desc %s", $db_table_name );
            $tmp_arr    = preg_split("/\./",$db_table_name);
            $db_name    = $tmp_arr[0];
            if ($db_name=="db_question") {
                $this->question_model->main_get_value(  "set names utf8" );
                $row  = $this->question_model->main_get_row($create_sql);
                $list = $this->question_model->main_get_list($desc_sql);
            }else{
                $this->t_lesson_info ->main_get_value(  "set names utf8" );
                $row  = $this->t_lesson_info ->main_get_row($create_sql);
                $list = $this->t_lesson_info->main_get_list($desc_sql);
            }
            $ret_map[$db_table_name] = ["table_desc" => $row["Create Table"],
                  "desc_list" => $list
            ];
        }
        return $this->output_succ(["table_desc_list" => $ret_map]);
    }

    public function get_wx_group_pic(){
        $type       = $this->get_in_int_val("type",4);
        $usage_type = $this->get_in_int_val("usage_type",401);
        if($type==0 || $usage_type==0){
            return $this->output_err("图片为空");
        }

        $pic_list=$this->t_pic_manage_info->get_pic_info_list($type,$usage_type,1);

        return $this->output_succ(['data' => $pic_list['list']]);
    }

    public function get_stu_lesson_title() {
        $parentid = $this->get_in_str_val('parentid');
        if (!$parentid) {
            return $this->output_err("请重新绑定");
        }

        $list = $this->t_lesson_info_b2->get_stu_id_face_left($parentid);
        if ($list) {
            $userid = $list['userid'];
            if ($userid === '') {
                return $this->output_err("未查到学生信息,请重新绑定您的学生！");
            }

            $start_time = $this->t_lesson_info_b2->get_stu_first_order_time($userid);
            if (!$start_time) {
                $start_time    = 11111;
                $list['start'] = '0000.00.00';
            } else {
                $list['start'] = date('Y.m.d', $start_time);
            }

            $subject_list = $this->t_lesson_info_b2->get_stu_title($userid, $start_time);
            $list['end'] = date('Y.m.d', time());
            $list['stu_subject_count'] = count($subject_list);
            if ( count($subject_list) >= 3 ) {
                $stu_lesson_title = '全能大王';
            } else if(count($subject_list) > 0){

                $total = 0;
                foreach ($subject_list as $v) {
                    $total += $v["count"];
                }
                foreach ($subject_list as $v) {
                    if ( ($v["count"]/$total) > 0.75 ) {
                        switch( $v["subject"] ) {
                        case 1:
                            $stu_lesson_title = "语文巧匠";
                            break;
                        case 2:
                            $stu_lesson_title = "数学能手";
                            break;
                        case 3:
                            $stu_lesson_title = "英语达人";
                            break;
                        case 4:
                            $stu_lesson_title = "化学大师";
                            break;
                        case 5:
                            $stu_lesson_title = "物理博士";
                            break;
                        case 10:
                            $stu_lesson_titel = "科学强者";
                            break;
                        default:
                            $stu_lesson_title = '学习勇士';
                            break;
                        }
                    } else {
                        $stu_lesson_title = @$stu_lesson_title?$stu_lesson_title:"学习勇士";
                    }
                }
            } else {
                $stu_lesson_title = "学习勇士";
            }

            $list['stu_title'] = $stu_lesson_title;
            $stu_praise = $this->t_lesson_info_b2->get_stu_praise_total($userid);
            //现在最高的是21849,最低1(以95%为满级（20755），除以5，等分为五个级别,每级加4151)
            $list['praise'] = $stu_praise;
            $list['stu_praise_star'] = intval( ceil( $stu_praise/4151 ) <5?ceil( $stu_praise/4151 ):5 ).'星学员';
            $list['excess_nums'] = str_pad(intval( $list['stu_praise_star']*19),2,'0',STR_PAD_LEFT);

            $first_info  = $this->t_lesson_info_b2->get_stu_first($userid);
            $subject     = '无';
            $normal_time = 0;
            $list['first_free_lesson_time'] = '无';
            foreach ($first_info as &$item) {
                if ($item["lesson_type"] == 2) {
                    $list['first_free_lesson_time'] = date('Y-m-d', $item['lesson_start']);
                } else {
                    if($normal_time === 0 || $normal_time > $item['lesson_start']) {
                        $normal_time = $item['lesson_start'];
                    }
                    if ($item['lesson_type'] === '0') {
                        E\Esubject::set_item_value_str($item);
                        $subject = ($normal_time == $item['lesson_start'])?$item['subject_str']:$subject;
                    }
                }
            }

            if (  $normal_time ) {
                $list['first_normal_lesson_time'] = date('Y-m-d', $normal_time);
            } else {
                $list['first_normal_lesson_time'] = '无';
            }

            $list['first_subject'] = $subject;
            $open_lesson = $this->t_lesson_info_b2->get_stu_first_open_lesson($userid);
            $list['first_open_lesson_time'] = $open_lesson ? date('Y-m-d', $open_lesson) : '无';
            $homework_info = $this->t_lesson_info_b2->get_stu_homework($userid, $start_time);
            $a = '00';
            $b = '00';
            $c = '00';
            $d = '00';
            foreach ($homework_info as $v) {
                $a = ($v['score'] == "A")?str_pad($v['score_count'],2,'0',STR_PAD_LEFT):$a;
                $b = ($v['score'] == "B")?str_pad($v['score_count'],2,'0',STR_PAD_LEFT):$b;
                $c = ($v['score'] == "C")?str_pad($v['score_count'],2,'0',STR_PAD_LEFT):$c;
                // $d = ($v['score'] == "未完成")?str_pad($v['score_count'],2,'0',STR_PAD_LEFT):$d;
            }
            $list['A'] = "A级作业{$a}次";
            $list['B'] = "B级作业{$b}次";
            $list['C'] = "C级作业{$c}次";
            // $list['D'] = "未完成作业{$d}次";

            $homework_finish_info = $this->t_lesson_info_b2->get_stu_homework_finish($userid, $start_time);
            if ($homework_finish_info['count']) {
                $nofinish_num = str_pad($homework_finish_info['nofinish'],2,'0',STR_PAD_LEFT);
                $list['D'] = "未完成作业{$nofinish_num}次";
                $rate = intval (round( ( 1-($homework_finish_info['nofinish']/$homework_finish_info['count']) )*100 ) );
                $list['finish_rate'] = str_pad($rate, 2, '0',STR_PAD_LEFT);
            } else {
                $list['finish_rate'] = '00';
                $list['D'] = "未完成作业00次";
            }

            if ( $list['finish_rate'] > 50 ) {
                $list['homework_type'] = 1;
            } else {
                $list['homework_type'] = 0;
            }

            $like_teacher = $this->t_lesson_info_b2->get_stu_like_teacher($userid, $start_time);
            if ($like_teacher) {
                if($like_teacher['taday'] == 1) {
                    $end_day_time = strtotime('tomorrow');
                } else {
                    $end_day_time = strtotime( date('Y-m-d',$like_teacher['lesson_end']) ) + 86400;
                }

                $start_day_time = strtotime( date('Y-m-d',$like_teacher['lesson_start']) );
                E\Esubject::set_item_value_str($like_teacher);
                $lesson_count_num = $like_teacher['teacher_lesson_count']/100;
                $lesson_days_num  = intval( ($end_day_time-$start_day_time)/86400 );
                $list['teacher_for_stu_lesson']  = str_pad($lesson_count_num, 2, '0',STR_PAD_LEFT);
                $list['teacher']                 = mb_substr($like_teacher['realname'], 0, 1, 'utf-8');
                $list['teacher_for_stu_subject'] = $like_teacher['subject_str'];
                $list['teacher_for_stu_days']    = str_pad($lesson_days_num, 2, '0',STR_PAD_LEFT);
            } else {
                $list['teacher_for_stu_lesson']  = '00';
                $list['teacher']                 = "";
                $list['teacher_for_stu_subject'] = "";
                $list['teacher_for_stu_days']    = '00';

            }

            $star_info = $this->t_lesson_info_b2->get_stu_score_info($userid, $start_time);
            $list['five_star']  = '00';
            $list['four_star']  = '00';
            $list['three_star'] = '00';
            if ($star_info) {
                foreach ($star_info as $v) {
                    $score_num = str_pad($v['teacher_score_count'],2,'0',STR_PAD_LEFT);
                    $list['five_star']  = ($v['teacher_score'] == 5)?$score_num:$list['five_star'];
                    $list['four_star']  = ($v['teacher_score'] == 4)?$score_num:$list['four_star'];
                    $list['three_star'] = ($v['teacher_score'] == 3)?$score_num:$list['three_star'];
                }
            }

            $lesson_total          = $this->t_lesson_info_b2->get_stu_lesson_time_total($userid) / 100;
            $list['past_lesson']   = str_pad($lesson_total, 3, '0', STR_PAD_LEFT);
            $list['reduce_gas']    = $lesson_total? number_format($lesson_total * 200/3, 2):'000';
            $list['add_greenland'] = $lesson_total? number_format($lesson_total * 0.63/3, 2):'00';
            $list['add_sky']       = $lesson_total? number_format($lesson_total * 0.92/3, 2):'00';
            $list['lesson_count_left'] = str_pad($list['lesson_count_left']/100,2,'0',STR_PAD_LEFT);
            if ($list['lesson_count_left'] > 1) {
                $list['last_title'] = '“理优1对1永远和你在一起”';
            } else if ( $list['first_normal_lesson_time'] !== '无' ) {
                $list['last_title'] = '“理优1对1十分想念你”';
            } else {
                $list['last_title'] = '“理优1对1期待你的加入”';
            }
            return $this->output_succ(["list"=>$list]);
        } else {
            return $this->output_err("请重新绑定您的学生！");
        }
    }

    public function show_message_info(){
        $messageid = $this->get_in_int_val("messageid");
        if($messageid>0){
            $content=$this->t_baidu_msg->get_content($messageid);
        }else{
            $content="";
        }

        return $content;
    }

    public function send_msg_to_parent(){
        $lessonid = $this->get_in_int_val('lessonid');
        $type = $this->get_in_int_val('type'); // 1:试卷 2:作业

        /***

            待办事项提醒
            x月x日

            家长您好，x月x日xx:xx-xx:xx的xx课讲义，xx老师已经上传
            待办主题：讲义已上传
            待办内容：xx课学生讲义已上传
            日期：2017/06/01
            可登录学生端进行课前预习。


            {{first.DATA}}
            待办主题：{{keyword1.DATA}}
            待办内容：{{keyword2.DATA}}
            日期：{{keyword3.DATA}}
            {{remark.DATA}}

         **/


        $lesson_info = $this->t_lesson_info_b2->get_lesson_info_by_lessonid($lessonid);

        if($type == 1){ // 试卷
            $keyword1 = '讲义已上传';
        }elseif($type==2){ // 作业
            $keyword1 = '课后作业已上传';
        }

        $wx = new \App\Helper\Wx();
        $now = date('Y-m-d');

        $subject_str = E\Esubject::get_desc($lesson_info['subject']);
        $data_msg = [
            'first' => "家长您好，".date('m月d日 H:i',$lesson_info['lesson_start']).' ~ '.date('H:i',$lesson_info['lesson_end'])."的 $subject_str 课讲义，".$lesson_info['tea_nick']."老师已经上传",
            'keyword1' => "$keyword1",
            'keyword2' => "$subject_str 课课后后作业已上传",
            'keyword3' => "$now",
            'remark'   => '可登录学生端进行课前预习。'
        ];

        $template_id_parent = '9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU'; // 待办主题

        if($lesson_info['wx_openid']){
            $wx->send_template_msg($lesson_info['wx_openid'],$template_id_parent,$data_msg ,'');
        }


    }

    public function add_teacher(){
        $info = hex2bin($this->get_in_str_val("info"));
        if($info==""){
            return $this->output_err("老师信息错误！");
        }

        $teacher_info['phone']         = $info;
        $teacher_info['send_sms_flag'] = 0;
        $teacher_info['wx_use_flag']   = 0;
        $teacher_info['use_easy_pass'] = 2;
        $ret = $this->add_teacher_common($teacher_info);
        if($ret>0){
            return $this->output_succ();
        }else{
            return $this->output_err($ret);
        }
    }

    public function send_to_no_contract_stu(){
        $start_time = strtotime('2017-06-01');
        $end_time   = strtotime('2017-09-01');
        // $ret_info   = $this->t_student_info->get_stu_id_phone($start_time, $end_time);

        // $job = new \App\Jobs\SendStuMessage($ret_info,"86720105",[]);
        // dispatch($job);
    }
    public function check_ssh_login_time() {
        $account=$this->get_in_str_val("account");
        $remote_host=$this->get_in_str_val("remote_host");
        $ssh_login_time=\App\Helper\Common::redis_get("SSH_LOGIN_TIME_$account");
        $check_ip_list=[
            //公司网络
            "116.226.191.6",
            //外网网互通
            "118.190.115.161",
            "118.190.135.205",
            "118.190.113.96",
            "121.42.186.59",
            "121.42.183.163",
            "114.215.98.161",
            "114.215.40.128",
            "115.28.89.73",
            "114.215.66.38",
            "118.190.65.189",
            "118.190.65.193",
        ];
        if(in_array($remote_host, $check_ip_list )){
            return "1";
        }

        if (time(NULL)-$ssh_login_time  < 3600 ){
            return "1";
        }else{
            return "0";
        }
    }

}
