<?php namespace App\Http\Controllers;

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
        $to    = $this->get_in_str_val("to");
        $title = $this->get_in_str_val("title");
        $body  = trim($this->get_in_str_val("body"));

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
        $xls_data = session("xls_data" );

        if(!is_array($xls_data)) {
            return $this->output_err("download error");
        }

        $xls_data = array_filter($xls_data);
        $xls_data_new = [];
        foreach($xls_data as $item){
            $xls_data_new[] = $item;
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
            ,"DA","DB","DC","DD","DE","DF","DG","DH","DI","DJ","DK","DL","DM","DN","DO","DP","DQ","DR","DS","DT","DU","DV","DW","DX","DY","DZ"

        ];
        foreach( $xls_data_new as $index=> $item ) {
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
        $max_record_count=$this->get_in_int_val("max_record_count");
        if ($max_record_count) {
            if (!$this->t_audio_record_server->get_max_record_count($ip)) {
                $this->t_audio_record_server->field_update_list($ip, [
                    "max_record_count" => $max_record_count,
                ]);
            }
        }
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
        return $this->output_succ(["config_userid" => $config_userid, "client_ip" =>$ip ]);

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
        $opt_time=$logtime-$logtime%60;
        $this->t_online_count_log->add($opt_time ,$online_count);

        $ret = $this->t_lesson_info_b2->get_finish_lessons();
        $finish_count=  count($ret);
        $this->t_tongji_log->add(E\Etongji_log_type::V_SYS_NEED_GEN_LESSON_VIDEO_COUNT,
                                 $opt_time,$finish_count);

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

    /**
     * 老师报名
     */
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
            $teacher_info['identity']      = $teacher_type;
            $teacher_info['is_test_user']  = $is_test_user;

            \App\Helper\Utils::logger("teacher appointment:".$phone."data:".json_encode($data));
            if($full_time==1){
                $html = $this->get_full_time_html($data);
            }else{
                $this->add_teacher_common($teacher_info);
                $html = $this->get_email_html_new($name);
            }

            if($email!=""){
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
                if($wx_openid!="" && !in_array($teacher_type,[E\Eteacher_type::V_21,E\Eteacher_type::V_22,E\Eteacher_type::V_31])){
                    \App\Helper\Utils::logger("微信推送".$reference);

                    $record_info = $name."已填写报名信息";
                    $status_str  = "已报名";
                    \App\Helper\Utils::send_reference_msg_for_wx($wx_openid,$record_info,$status_str);
                }
            }

            /**
             * @ 处理老师圣诞节|元旦节活动
             * @ 从老师分享页进入注册的 老师
             * @ christmas_type  0:正常用户 1:从分享页面进来的老师
             */
            $shareId   = $this->get_in_str_val('shareId');
            $currentId = $this->get_in_str_val('currentId');


            if($shareId){
                $isHasAdd = $this->t_teacher_christmas->checkHasAdd($shareId,$currentId,2);
                if(!$isHasAdd){
                    $this->t_teacher_christmas->row_insert([
                        "shareId"   => $shareId,
                        "currentId" => $currentId,
                        "add_time"    => time(),
                        "score"       => 10,
                        "type"        => 2 // 注册
                     ]);
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
        if(\App\Helper\Utils::check_env_is_test() || \App\Helper\Utils::check_env_is_local()){
            $call_flag = $this->get_in_int_val('call_flag');
            $obj_start_time = time(NULL);
            $adminid = $this->get_account_id();
            //获取用户tquin
            $cdr_bridged_cno = $this->t_manager_info->get_tquin($adminid);
            $cdr_customer_number = $this->get_in_str_val('phone');
            $cdr_answer_time = time(NULL);
            if($call_flag == 1){
                //模拟拨打失败数据
                $cdr_end_time = $obj_start_time;
                $cdr_status = 21;
            }elseif($call_flag == 2){
                //模拟拨打成功数据
                $cdr_end_time = strtotime('+ 20 minutes',$obj_start_time);
                $cdr_status = 28;
            }

            $recid= ($cdr_bridged_cno<<32 ) + $cdr_answer_time;
            $duration=0;
            if ($obj_start_time) {
                $duration= $cdr_end_time-$obj_start_time;
            }
            $sipCause = 0;
            $client_number = '';
            $endReason = 1;//销售挂断
        }else{

            //$cdr_bridge_time=$this->get_in_int_val("cdr_bridge_time");
            $obj_start_time=$this->get_in_int_val("cdr_bridge_time");
            //$cdr_answer_time=$this->get_in_int_val("cdr_answer_time");
            $uniqueId= $this->get_in_str_val("cdr_main_unique_id");

            $cdr_answer_time = intval( preg_split("/\-/", $uniqueId)[1]);
            $cdr_end_time=$this->get_in_int_val("cdr_end_time");

            $cdr_bridged_cno = $this->get_in_int_val("cdr_bridged_cno");
            $cdr_status = $this->get_in_int_val("cdr_status");

            $recid= ($cdr_bridged_cno<<32 ) + $cdr_answer_time;
            $cdr_customer_number = $this->get_in_str_val("cdr_customer_number");

            $duration=0;
            if ($obj_start_time) {
                $duration= $cdr_end_time-$obj_start_time;
            }

            $sipCause = $this->get_in_int_val('sipCause');
            $client_number = $this->get_in_str_val('clientNumber');
            $endReason = 0;
            if($this->get_in_str_val('endReason')=='是'){//客户
                $endReason = 2;
            }elseif($this->get_in_str_val('endReason')=='否'){//销售
                $endReason = 1;
            }

            \App\Helper\Utils::logger("duration ,$duration, $obj_start_time");
        }

        $this->t_tq_call_info->add(
            $recid,
            $cdr_bridged_cno,
            $cdr_customer_number,
            $cdr_answer_time,
            $cdr_end_time,
            $duration,
            $cdr_status==28?1:0,
            "",
            0,
            0,
            $obj_start_time,
            $sipCause,
            $client_number,
            $endReason
        );

        $called_flag=($cdr_status==28 && $duration>60)?2:1;
        $is_called_phone = ($cdr_status==28)?1:0;
        $this->t_seller_student_new->sync_tq($cdr_customer_number ,$called_flag, $cdr_answer_time, $cdr_bridged_cno,$is_called_phone,$duration);
        return json_encode(["result"=>"success"]);
    }

    public function notify_gen_lesson_teacher_pdf_pic() {
        $lessonid = $this->get_in_lessonid();
        $pdf_url  = $this->t_lesson_info->get_tea_cw_url($lessonid);

        $arr = explode('.', $pdf_url);
        if($pdf_url ){
            $this->t_pdf_to_png_info->row_insert([
                'lessonid'    => $lessonid,
                'pdf_url'     => $pdf_url,
                'create_time' => time()
            ]);
        }

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
        if(is_array($table_list)){
            foreach ($table_list as $db_table_name) {
                $create_sql = sprintf("show create table %s", $db_table_name );
                $desc_sql   = sprintf("desc %s", $db_table_name );
                $tmp_arr    = preg_split("/\./",$db_table_name);
                $db_name    = $tmp_arr[0];
                // if ($db_name=="db_question") {
                //     $this->question_model->main_get_value(  "set names utf8" );
                //     $row  = $this->question_model->main_get_row($create_sql);
                //     $list = $this->question_model->main_get_list($desc_sql);
                // }else{
                $this->t_lesson_info->main_get_value(  "set names utf8" );
                $row  = $this->t_lesson_info ->main_get_row($create_sql);
                $list = $this->t_lesson_info->main_get_list($desc_sql);
                // }
                $ret_map[$db_table_name] = [
                    "table_desc" => $row["Create Table"],
                    "desc_list"  => $list
                ];
            }
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
                \App\Helper\Utils::logger("james_22898: ".$homework_finish_info['count']);

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

            $prize_type = $this->t_activity_christmas->getPrizeType($parentid);
            $list['prize_type'] = $prize_type;
            if($prize_type >0 ){
                $list['has_done'] = 1;
                switch ($prize_type)
                {
                case 1:
                    $list['prize_str'] = "抽中10元折扣券一张";
                    break;
                case 2:
                    $list['prize_str'] = "抽中20元折扣券一张";
                    break;
                case 3:
                    $list['prize_str'] = "抽中50元折扣券一张";
                    break;
                case 4:
                    $list['prize_str'] = "获得价值200元的试听课一节";
                    break;
                }
            }else{
                $list['has_done']  = 0;
                $list['prize_str'] = '';
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

        /**
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
        $subject_str = E\Esubject::get_desc($lesson_info['subject']);

        if($type == 1){ // 讲义
            $data_msg = [
                'first' => "家长您好，".date('m月d日 H:i',$lesson_info['lesson_start']).' ~ '.date('H:i',$lesson_info['lesson_end'])."的 $subject_str 课讲义，".$lesson_info['tea_nick']."老师已经上传",
                'keyword1' => "讲义已上传",
                'keyword2' => $subject_str."课讲义已上传",
                'keyword3' => date('Y-m-d H:i:s'),
                'remark'   => '可登录学生端进行课前预习。'
            ];
        }elseif($type==2){ // 作业
            $data_msg = [
                'first' => "家长您好，".date('m月d日 H:i',$lesson_info['lesson_start']).' ~ '.date('H:i',$lesson_info['lesson_end'])."的 $subject_str 课后作业，".$lesson_info['tea_nick']."老师已经上传",
                'keyword1' => "课后作业已上传",
                'keyword2' => $subject_str."课课后后作业已上传",
                'keyword3' => date('Y-m-d H:i:s'),
                'remark'   => '请督促孩子及时完成课后作业，谢谢！'
            ];
        }

        $wx = new \App\Helper\Wx();
        $template_id_parent = '9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU'; // 待办主题

        if($lesson_info['wx_openid'] && $lesson_info['userid']>0){
            $wx->send_template_msg($lesson_info['wx_openid'],$template_id_parent,$data_msg ,'');
        }
    }


    public function send_wx_to_par(){
        $lessonid = $this->get_in_int_val('lessonid');

        /**
           //课程反馈通知
           // bW8mP8cCxszBrM2qLBIlj0MOsGgTGwQtWbvoGYhhGtw
           {{first.DATA}}
           课程名称：{{keyword1.DATA}}
           课程时间：{{keyword2.DATA}}
           学生姓名：{{keyword3.DATA}}
           {{remark.DATA}}
           xx:xx的xx课xx老师已经提交了课程评价
           课程名称：{课程名称}
           课程时间：xx-xx xx:xx~xx:xx
           学生姓名：xxx
           可登录学生端查看详情，谢谢！
         */

        $template_id_teacher = 'bW8mP8cCxszBrM2qLBIlj0MOsGgTGwQtWbvoGYhhGtw';
        $teacher_info = $this->t_teacher_info->get_info_to_teacher($lessonid);
        $subject_str = E\Esubject::get_desc($teacher_info['subject']);
        $data = [
            "first" => date('m月d日 H:i:s',$teacher_info['lesson_start'])."的".$subject_str."课".$teacher_info['tea_nick']."老师已提交了课程评价",
            "keyword1" => $subject_str,
            "keyword2" => date('m月d日 H:i')." ~ ".date('H:i'),
            "keyword3" => $teacher_info['stu_nick'],
            "remark"   => '可登录学生端查看详情，谢谢！',
        ];
        $ret_teacher = \App\Helper\Utils::send_teacher_msg_for_wx($teacher_info['wx_openid'],$template_id_teacher, $data,'');
    }



    public function add_teacher(){
        $info = hex2bin($this->get_in_str_val("info"));
        if($info==""){
            return $this->output_err("老师信息错误！");
        }

        $teacher_info['phone']         = $info;
        $teacher_info['send_sms_flag'] = 0;
        $teacher_info['wx_use_flag']   = 0;
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
        $server_ip=$this->get_in_str_val("server_ip");
        if (!$server_ip ){
            $server_ip= $this->get_in_client_ip();
        }
        $ssh_login_time=\App\Helper\Common::redis_get("SSH_LOGIN_TIME_$account");
        $check_ip_list=[
            //公司网络
            "116.226.191.6",
            "101.81.224.61",
            "116.226.191.120",
            "116.226.184.94",
            //外网网互通
            //课堂视频生成
            "121.42.186.59",
            "115.28.89.73",

            //ssh_login_server
            "118.190.115.161",

            //admin
            "114.215.66.38",
            "118.190.65.189",

        ];

        $login_flag=false;
        if(in_array($remote_host, $check_ip_list )){
            $login_flag=true;
        }


        if (  $account != "ybai" && time(NULL)-$ssh_login_time  < 3600  ){
            $login_flag=true;
        }

        $this->t_ssh_login_log->row_insert([
            "server_ip"=> ip2long($server_ip),
            "login_ip"=> ip2long($remote_host),
            'account' => $account,
            'login_succ_flag' => $login_flag?1:0,
            "login_time" =>time(),
        ]);

        if ($login_flag ) {
            return "1";
        }else{
            dispatch( new \App\Jobs\send_error_mail( "","ssh 异常登录 登录 ip: $remote_host ,服务器: $server_ip  账号 :$account" , " 登录 ip $remote_host 服务器: $server_ip  账号 :$account " ) );
            return "0";

        }
    }

    //百度有钱花回调地址
    public function baidu_callback_return_info(){
        $orderNo = $this->get_in_str_val("orderid");
        $status = $this->get_in_int_val("status");
        $period_new = $this->get_in_int_val("period");
        $sign = $this->get_in_str_val("sign");
        $data = $_REQUEST;
        foreach($data as $k=>$v){
            if($k=="_url" || $k=="_ctl" || $k =="_act" || $k=="_role" || $k=="_userid" || $k=="sign"){
                unset($data[$k]);
            }
        }
        $orderid=  $this->t_orderid_orderno_list->get_orderid($orderNo);
        $check_exist = $this->t_child_order_info->get_parent_orderid($orderid);
        if(empty($check_exist)){
            if($status==8){
                $parent_orderid = $this->t_orderid_orderno_list->get_parent_orderid($orderNo);
                $userid = $this->t_order_info->get_userid($parent_orderid);

                //更新家长课程信息
                $this->reset_parent_course_info($userid,$orderNo);


                if($parent_orderid>0){
                    $this->t_manager_info->send_wx_todo_msg(
                        "jack",
                        "百度分期付款异常",
                        "百度分期付款异常",
                        "学生id:".$userid." 百度分期付款成功(后台子合同异常),支付方式:百度有钱花,订单号:".$orderNo,
                        "");
                    $this->t_orderid_orderno_list->field_update_list($orderNo,[
                        "pay_flag" =>1,
                        "channel"  =>"baidu",
                        "pay_time" =>time()
                    ]);

                }
            }
            return $this->output_succ(["status"=>1,"msg"=>"订单不存在"]);
        }else{
            //期待贷款额度(分单位)
            // $money = $this->t_child_order_info->get_price($orderid);

            //分期期数
            // $period = $this->t_child_order_info->get_period_num($orderid);

            //成交价格
            $parent_orderid = $this->t_child_order_info->get_parent_orderid($orderid);
            //  $dealmoney = $this->t_order_info->get_price($parent_orderid);

            $userid = $this->t_order_info->get_userid($parent_orderid);
            $sys_operator = $this->t_order_info->get_sys_operator($parent_orderid);
            $user_info = $this->t_student_info->field_get_list($userid,"nick,phone,email");



            $arrParams = [];

            $strSecretKey = '9v4DvTxOz3';// 分配的key
            $arrParams['sign'] = $this->createBaseSign($data, $strSecretKey);
            if($arrParams['sign'] != $sign){
                return $this->output_succ(["status"=>2,"msg"=>"参数错误"]);
            }else{
                if($status==8){
                    $old_list = $this->t_child_order_info->field_get_list($orderid,"pay_status,pay_time,channel");
                    if($old_list["pay_status"]==1 && $old_list["pay_time"]>0 && $old_list["channel"]=="baidu"){
                        return $this->output_succ(["status"=>0,"msg"=>"success"]);
                    }
                    $parentid= $this->t_student_info->get_parentid($userid);
                    $parent_name = $this->t_parent_info->get_nick($parentid);
                    $this->t_child_order_info->field_update_list($orderid,[
                        "pay_status"  =>1,
                        "pay_time"    =>time(),
                        "channel"     =>"baidu",
                        "from_orderno"=>$orderNo,
                        "period_num"  =>$period_new,
                        "parent_name" =>$parent_name
                    ]);


                    $this->t_manager_info->send_wx_todo_msg(
                        "jack",
                        "百度分期付款通知",
                        "百度分期付款通知",
                        "学生:".$user_info["nick"]." 百度分期付款成功,支付方式:百度有钱花,订单号:".$orderNo,
                        "");
                    $this->t_manager_info->send_wx_todo_msg(
                        $sys_operator,
                        "百度分期付款通知",
                        "百度分期付款通知",
                        "学生:".$user_info["nick"]." 百度分期付款成功,支付方式:百度有钱花,订单号:".$orderNo,
                        "");
                    $this->t_manager_info->send_wx_todo_msg(
                        "echo",
                        "百度分期付款通知",
                        "百度分期付款通知",
                        "学生:".$user_info["nick"]." 百度分期付款成功,支付方式:百度有钱花,订单号:".$orderNo,
                        "");
                    $this->t_manager_info->send_wx_todo_msg(
                        "zero",
                        "百度分期付款通知",
                        "百度分期付款通知",
                        "学生:".$user_info["nick"]." 百度分期付款成功,支付方式:百度有钱花,订单号:".$orderNo,
                        "");

                    // 更新家长课程信息
                    $this->reset_parent_course_info($userid,$orderNo);


                    //生成还款信息
                    $data = $this->get_baidu_money_charge_pay_info($orderid);
                    if($data["status"]==0 && isset($data["data"]) && is_array($data["data"])){
                        $ret = $data["data"];
                        foreach($ret as $item){
                            $period = $item["period"];
                            $is_exist = $this->t_period_repay_list->get_bid($orderid,$period);
                            if(!$is_exist){
                                if($item["bStatus"] != 48){
                                    $item["paidTime"]=0;
                                }
                                $this->t_period_repay_list->row_insert([
                                    "orderid" =>$orderid,
                                    "period"  =>$period,
                                    "bid"     =>$item["bid"],
                                    "b_status"=>$item["bStatus"],
                                    "paid_time"=>$item["paidTime"],
                                    "due_date" =>$item["dueDate"],
                                    "money"    =>$item["money"],
                                    "paid_money"=>$item["paidMoney"],
                                    "un_paid_money"=>$item["unpaidMoney"]
                                ]);
                            }
                        }
                    }


                    $all_order_pay = $this->t_child_order_info->chick_all_order_have_pay($parent_orderid);
                    if(empty($all_order_pay)){
                        $this->t_order_info->field_update_list($parent_orderid,[
                            "order_status" =>1,
                            "contract_status"=>1,
                            "pay_time"       =>time()
                        ]);
                        $this->t_manager_info->send_wx_todo_msg(
                            "echo",
                            "合同付款通知",
                            "合同已支付全款",
                            "学生:".$user_info["nick"]." 合同已支付全款",
                            "/user_manage_new/money_contract_list?studentid=$userid");
                        $this->t_manager_info->send_wx_todo_msg(
                            "zero",
                            "合同付款通知",
                            "合同已支付全款",
                            "学生:".$user_info["nick"]." 合同已支付全款",
                            "/user_manage_new/money_contract_list?studentid=$userid");

                        $this->t_manager_info->send_wx_todo_msg(
                            $sys_operator,
                            "合同付款通知",
                            "合同已支付全款",
                            "学生:".$user_info["nick"]." 合同已支付全款",
                            "");
                        $this->t_manager_info->send_wx_todo_msg(
                            "jack",
                            "合同付款通知",
                            "合同已支付全款",
                            "学生:".$user_info["nick"]." 合同已支付全款",
                            "");


                    }


                }
                return $this->output_succ(["status"=>0,"msg"=>"success"]);
            }

        }
        // dd(111);
    }

    /**
     * @param $data
     * @return string
     * rsa 加密(百度有钱花)
     */
    public function enrsa($data){
        $public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC3//sR2tXw0wrC2DySx8vNGlqt
3Y7ldU9+LBLI6e1KS5lfc5jlTGF7KBTSkCHBM3ouEHWqp1ZJ85iJe59aF5gIB2kl
Bd6h4wrbbHA2XE1sq21ykja/Gqx7/IRia3zQfxGv/qEkyGOx+XALVoOlZqDwh76o
2n1vP1D+tD3amHsK7QIDAQAB
-----END PUBLIC KEY-----';
        $pu_key = openssl_pkey_get_public($public_key);
        $str = json_encode($data);
        $encrypted = "";
        // 公钥加密  padding使用OPENSSL_PKCS1_PADDING这个
        if (openssl_public_encrypt($str, $encrypted, $pu_key, OPENSSL_PKCS1_PADDING)){
            $encrypted = base64_encode($encrypted);
        }
        return $encrypted;
    }


    /**
     * @param $param
     * @param string $strSecretKey
     * @return bool|string
     * 生成签名(百度有钱花)
     */
    public function createBaseSign($param, $strSecretKey){
        if (!is_array($param) || empty($param)){
            return false;
        }
        ksort($param);
        $concatStr = '';
        foreach ($param as $k=>$v) {
            $concatStr .= $k.'='.$v.'&';
        }
        $concatStr .= 'key='.$strSecretKey;
        return strtoupper(md5($concatStr));
    }


    //更新家长百度有钱花课程信息
    public function reset_parent_course_info($userid,$orderNo){

        $pp_info = $this->t_student_info->field_get_list($userid,"parentid,grade");
        $courseid = $this->t_orderid_orderno_list->get_courseid($orderNo);
        $grade=$pp_info["grade"];
        $parent_orderid = $this->t_orderid_orderno_list->get_parent_orderid($orderNo);
        $competition_flag = $this->t_order_info->get_competition_flag($parent_orderid);
        if($competition_flag==1){
            if(!$courseid){
                $courseid = "SHLEOZ3101006";
            }
            $str = $this->get_parent_courseid($courseid,4,$pp_info["parentid"]);

            // $course_list = $this->t_parent_info->get_baidu_class_info($pp_info["parentid"]);
            // if($course_list){
            //     $list=json_decode($course_list,true);
            // }else{
            //     $list=[];
            // }
            // @$list[4][]=$courseid;
            // $str = json_encode($list);

        }elseif($grade >=100 && $grade<200){
            if(!$courseid){
                $courseid = "SHLEOZ3101001";
            }
            $str = $this->get_parent_courseid($courseid,1,$pp_info["parentid"]);
            // $course_list = $this->t_parent_info->get_baidu_class_info($pp_info["parentid"]);
            // if($course_list){
            //     $list=json_decode($course_list,true);
            // }else{
            //     $list=[];
            // }
            // @$list[1][]=$courseid;
            // $str = json_encode($list);
        }elseif($grade >=200 && $grade<300){
            if(!$courseid){
                $courseid = "SHLEOZ3101011";
            }
            $str = $this->get_parent_courseid($courseid,2,$pp_info["parentid"]);
            // $course_list = $this->t_parent_info->get_baidu_class_info($pp_info["parentid"]);
            // if($course_list){
            //     $list=json_decode($course_list,true);
            // }else{
            //     $list=[];
            // }
            // @$list[2][]=$courseid;
            // $str = json_encode($list);
        }elseif($grade >=300 && $grade<400){
            if(!$courseid){
                $courseid = "SHLEOZ3101016";
            }
            $str = $this->get_parent_courseid($courseid,3,$pp_info["parentid"]);
            // $course_list = $this->t_parent_info->get_baidu_class_info($pp_info["parentid"]);
            // if($course_list){
            //     $list=json_decode($course_list,true);
            // }else{
            //     $list=[];
            // }
            // @$list[3][]=$courseid;
            // $str = json_encode($list);
        }
        $this->t_parent_info->field_update_list($pp_info["parentid"],[
            "baidu_class_info" =>$str
        ]);




    }


    //家长各年级百度分期课程号更新
    public function get_parent_courseid($courseid,$num,$parentid){
        $course_list = $this->t_parent_info->get_baidu_class_info($parentid);
        if($course_list){
            $list=json_decode($course_list,true);
            if(isset($list[$num])){
                $course_arr = $list[$num];
                $i=0;
                foreach($course_arr as $val){
                    if($val==$courseid){
                        $i=1;
                    }
                }
                if($i==0){
                    @$list[$num][]=$courseid;
                }
            }else{
                @$list[$num][]=$courseid;
            }
        }else{
            $list=[];
            @$list[$num][]=$courseid;
        }
        $str = json_encode($list);
        return $str;

    }



    //建行分期回调地址
    public function ccb_callback_return_info(){

        $orderNo = $this->get_in_str_val("ORDERID","701748525753");
        $posid   = $this->get_in_str_val("POSID","002171923");
        $branchid = $this->get_in_str_val("BRANCHID","310000000");
        $payment  = $this->get_in_str_val("PAYMENT","1.00");
        $curcode = $this->get_in_str_val("CURCODE","01");
        $remark1 = $this->get_in_str_val("REMARK1","");
        $remark2 = $this->get_in_str_val("REMARK2","");
        $success = $this->get_in_str_val("SUCCESS","N");
        $acc_type = $this->get_in_str_val("ACC_TYPE","30");
        $type = $this->get_in_str_val("TYPE","1");
        $referer = $this->get_in_str_val("REFERER","");
        $clientip = $this->get_in_str_val("CLIENTIP","116.226.191.6");
        $installnum = $this->get_in_str_val("INSTALLNUM","");
        $errmsg = $this->get_in_str_val("ERRMSG");
        $sign = $this->get_in_str_val("SIGN","&CLIENTIP=116.226.191.6&INSTALLNUM=12&ERRMSG=&SIGN=5d00745445c4e3cc4dc99653bb2516cdac417701431e591088b5fdfddb984a116760e6156641ddd46cb6d434a6b5150aa4c37f7cf4732b2b94241ea926b0e1d4234b53f458d3ab2f80d6df3f6fc785450240105ace4b76dc6525191cbca54e1c09377b67cd6f42de89582e2987de1fd557368fa18dca273541f2d5a823ff30f6");
        $data = "POSID=".$posid."&BRANCHID=".$branchid."&ORDERID=".$orderNo."&PAYMENT=".$payment."&CURCODE=".$curcode."&REMARK1=".$remark1."&REMARK2=".$remark2."&ACC_TYPE=".$acc_type."&SUCCESS=".$success."&TYPE=".$type."&REFERER=".$referer."&CLIENTIP=".$clientip."&INSTALLNUM=".$installnum."&ERRMSG=".$errmsg;
        // $data = "POSID=".$posid."&BRANCHID=".$branchid."&ORDERID=".$orderNo."&PAYMENT=".$payment."&CURCODE=".$curcode."&REMARK1=".$remark1."&REMARK2=".$remark2."&SUCCESS=".$success;
        $der_data = "30819d300d06092a864886f70d010101050003818b0030818702818100d3248e9cfda6a7ca49fb480bc9539415e3083c07a82b3bded3fd39e33550228c6d9283b36219b78dab80783c01e241963e91dd2b8de8e400c8b0d19ce312d29fb790ec7d9257fbc421501ea0155f252635d52a7d5d8c5e0d5fe64202e41a096615b1e6a0164dd7ce3e4ce66e814fa3c1096c6d33c23710c736ebb69c1e9da205020111";

        $pay_channel=$cmd="";
        if($posid=="002171923"){
            $cmd ='cd /home/ybai/bin/Cbb/ && java Main "'.$data.'" "'.$sign.'"';
            $pay_channel = "建行分期";
        }elseif($posid=="002171916"){
            $cmd ='cd /home/ybai/bin/Cbb/ && java Other "'.$data.'" "'.$sign.'"';
            $pay_channel = "建行网关支付";
        }
        // echo $cmd;
        //dd(11);
        // dd($cmd);
        $verifyResult = \App\Helper\Utils::exec_cmd($cmd);
        // dd($verifyResult);

        if(!$verifyResult){
            $this->t_manager_info->send_wx_todo_msg(
                "jack",
                "合同付款通知",
                "合同付款验签失败",
                "学生:".$user_info["nick"]." 渠道:".$pay_channel.",订单号:".$orderNo,
                "");

        }


        //当前默认为true
        //$verifyResult=true;
        if($verifyResult && $success=="Y" ){
            $orderid=  $this->t_orderid_orderno_list->get_orderid($orderNo);
            $check_exist = $this->t_child_order_info->get_parent_orderid($orderid);
            if(empty($check_exist)){
                return $this->output_succ(["status"=>1,"msg"=>"订单不存在"]);
                // return false;
            }else{
                $parent_orderid = $this->t_child_order_info->get_parent_orderid($orderid);
                //  $dealmoney = $this->t_order_info->get_price($parent_orderid);

                $userid = $this->t_order_info->get_userid($parent_orderid);
                $sys_operator = $this->t_order_info->get_sys_operator($parent_orderid);
                $user_info = $this->t_student_info->field_get_list($userid,"nick,phone,email");

                $this->t_child_order_info->field_update_list($orderid,[
                    "pay_status"  =>1,
                    "pay_time"    =>time(),
                    "channel"     =>$pay_channel,
                    "from_orderno"=>$orderNo,
                    // "period_num"  =>$period_new
                ]);
                $this->t_manager_info->send_wx_todo_msg(
                    "jack",
                    "合同付款通知",
                    "合同付款通知",
                    "学生:".$user_info["nick"]." 渠道:".$pay_channel.",订单号:".$orderNo,
                    "");
                $this->t_manager_info->send_wx_todo_msg(
                    "zero",
                    "合同付款通知",
                    "合同付款通知",
                    "学生:".$user_info["nick"]." 渠道:".$pay_channel.",订单号:".$orderNo,
                    "");
                $this->t_manager_info->send_wx_todo_msg(
                    $sys_operator,
                    "合同付款通知",
                    "合同付款通知",
                    "学生:".$user_info["nick"]." 渠道:".$pay_channel.",订单号:".$orderNo,
                    "");


                /* $this->t_manager_info->send_wx_todo_msg(
                    $sys_operator,
                    "百度分期付款通知",
                    "百度分期付款通知",
                    "学生:".$user_info["nick"]." 百度分期付款成功,支付方式:百度有钱花,订单号:".$orderNo,
                    "");
                $this->t_manager_info->send_wx_todo_msg(
                    "echo",
                    "百度分期付款通知",
                    "百度分期付款通知",
                    "学生:".$user_info["nick"]." 百度分期付款成功,支付方式:百度有钱花,订单号:".$orderNo,
                    "");*/

                $all_order_pay = $this->t_child_order_info->chick_all_order_have_pay($parent_orderid);
                if(empty($all_order_pay)){
                    $this->t_order_info->field_update_list($parent_orderid,[
                        "order_status" =>1,
                        "contract_status"=>1,
                        "pay_time"       =>time()
                    ]);
                    /* $this->t_manager_info->send_wx_todo_msg(
                        "echo",
                        "合同付款通知",
                        "合同已支付全款",
                        "学生:".$user_info["nick"]." 合同已支付全款",
                        "/user_manage_new/money_contract_list?studentid=$userid");*/
                    $this->t_manager_info->send_wx_todo_msg(
                        $sys_operator,
                        "合同付款通知",
                        "合同已支付全款",
                        "学生:".$user_info["nick"]." 合同已支付全款",
                        "");
                    $this->t_manager_info->send_wx_todo_msg(
                        "jack",
                        "合同付款通知",
                        "合同已支付全款",
                        "学生:".$user_info["nick"]." 合同已支付全款",
                        "");
                    $this->t_manager_info->send_wx_todo_msg(
                        "zero",
                        "合同付款通知",
                        "合同已支付全款",
                        "学生:".$user_info["nick"]." 合同已支付全款",
                        "");



                }


            }
            return $this->output_succ(["status"=>0,"msg"=>"success"]);
            //return true;


        }else{
            // return false;
            return $this->output_succ(["status"=>1,"msg"=>"验证失败"]);
        }


    }

    public function set_lesson_end() {
        $lessonid=$this->get_in_lessonid();
        $lesson_end= $this->t_lesson_info_b3->get_lesson_end($lessonid);
        $now=time(NULL);
        if ($lesson_end > $now   ) { //
            return $this->output_err("课程还没结束");
        }

        $this->t_lesson_info->field_update_list($lessonid,[
            "lesson_status" => E\Elesson_status::V_END
        ]);

        return $this->output_succ();
    }


    public function get_check_lesson_end_list()
    {
        $client_ip = $this->get_in_client_ip();
        $lesson_list = $this->t_lesson_info->get_need_set_lesson_end_list();
        $server_name_map = $this->t_xmpp_server_config->get_server_name_map();

        $ret_list=[];
        foreach ($lesson_list as $item) {
            $teacherid   = $item["teacherid"];
            $userid      = $item["userid"];
            $lessonid    = $item["lessonid"];
            $courseid    = $item["courseid"];
            $lesson_type = $item["lesson_type"];
            $lesson_num  = $item["lesson_num"];
            $lesson_end  = $item["lesson_end"];
            $roomid    = \App\Helper\Utils::gen_roomid_name($lesson_type,$courseid,$lesson_num);

            $xmpp_server_name=$item["xmpp_server_name"];
            $current_server=$item["current_server"];

            $server_config = $this->t_lesson_info_b3->eval_real_xmpp_server($xmpp_server_name,$current_server ,$server_name_map  );
            if(@$server_config['ip'] == $client_ip ){ //同一个ip
                $ret_list[] = [
                    "roomid"   => $roomid,
                    "lessonid" => $lessonid,
                    "teacherid" => $teacherid,
                ];
            }
        }

        return $this->output_succ(["list"=> $ret_list]);
    }
    public function xmpp_server_get_end_time_by_roomid() {
        $room_name = $this->get_in_str_val("room_name");
        if (preg_match("/[lp]_([0-9]+)y([0-9]+)y[0-9]+/",$room_name, $matches)) {
            $courseid=$matches[1];
            $lesson_num=$matches[2];
            $ret=$this->t_lesson_info_b3->get_lesson_info_for_check_lesson_end($courseid, $lesson_num);
            if ($ret) {
                $lesson_end=$ret["lesson_end"];
                $now=time(NULL);
                $room_del_flag =   ($now-$lesson_end >3600);
                return $this->output_succ([
                    "lesson_end" => $lesson_end,
                    "room_del_flag" =>$room_del_flag
                ]);
            }
        }
        return $this->output_err("no find $room_name");

    }
    public function web_page_log()    {
        $web_page_id=$this->get_in_int_val("web_page_id");
        $from_adminid=$this->get_in_int_val("from_adminid");
        $share_wx_flag=$this->get_in_int_val("share_wx_flag");

        $ip=ip2long( $this->get_in_client_ip() );
        $this->t_web_page_trace_log->row_insert([
            "from_adminid" =>$from_adminid,
            "web_page_id" =>$web_page_id,
            "share_wx_flag" =>$share_wx_flag,
            "ip" =>$ip,
            "log_time" =>time(NULL),
        ]);
        return $this->output_succ();
    }

    public  function test_web_page(){
        $web_page_id= $this->get_in_int_val("web_page_id");
        $from_adminid= $this->get_in_int_val("from_adminid");
        return $this->pageView(__METHOD__);
    }

    public function load_to_teacher() {
        $file = '/tmp/bank.txt';
        $str = file_get_contents($file);
        $info = explode("\n",$str);
        dd($info);
        echo '正在添加数据,请稍等 ...'.PHP_EOL;
        foreach($info as $item) {
            if ($item) {
                $val = explode("\t",$item);
                $teacherid = $val[0];
                $bankcard = $this->t_teacher_info->get_bankcard($teacherid);
                if (!$bankcard) {
                    $this->t_teacher_info->field_update_list($teacherid,[
                        "bank_phone" => $val[1],
                        "bank_account" => $val[2],
                        "bankcard" => $val[3],
                        "bank_type" => $val[4],
                        "bank_province" => $val[5],
                        "bank_city" => $val[6],
                        "bank_address" => $val[7],
                        "idcard" => $val[8]
                    ]);

                }
                echo "添加完成 current id : ".$teacherid.PHP_EOL;
            }
        }
        exit("添加数据完成 ...");
        $file = 'storage/teacher_bind.xlsx';
        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');

        $objPHPExcel = $objReader->load($file);
        $objPHPExcel->setActiveSheetIndex(0);
        $arr=$objPHPExcel->getActiveSheet()->toArray();
        //
        foreach ($arr as $index => $item) {
            dd($item);
        }
    }
    public function wx_notifi_admin () {
        $account=trim($this->get_in_str_val("account"));
        $noti_info= $this->get_in_str_val("noti_info");
        $this->t_manager_info->send_wx_todo_msg($account, "SYS", $noti_info, $noti_info);
        return $this->output_succ();
    }

    public function tongji_date_add_m_html_count( ) {
        $count=$this->get_in_int_val("count" );
        $log_time=$this->get_in_int_val("log_time" );

        $log_type = $this->get_in_int_val("log_type",  2017120201 );
        //2017120201 ,m_html 访问量
        //2017122101 ,m_html -> 调用 预约接口的量

        //按小时
        $log_time-=$log_time%3600;

        $this->t_tongji_date->del_log_time($log_type, $log_time);
        $this->t_tongji_date->add( $log_type,$log_time,0,$count);
        return $this->output_succ();
    }

        public function get_mobile_image() {
        $this->set_in_value("usage_type", 303);
        $this->set_in_value("type", 'mobile');
        return $this->get_image();
    }

    public function get_pc_image() {
        $this->set_in_value("usage_type", 302);
        $this->set_in_value("type", 'pc');
        return $this->get_image();
    }

    public function get_image() {
        $usage_type = $this->get_in_int_val('usage_type');
        $type = $this->get_in_str_val("type");
        $info = $this->t_pic_manage_info->get_pic_or_mobile_info($usage_type);
        $banner_count = count($info);
        foreach($info as &$item) {
            if ($item['status'] == 0) {
                $item['jump_type'] = "9";
            }
        }
        //$custom_type = $video_type = $page_type = '';
        //$i = $j = $k = 0;
        // foreach ($info as $item) {
        //     if ($item['jump_type'] == 2) {
        //         $custom_type[$i]['img_url'] = $item['img_url'];
        //         $custom_type[$i]['jump_url'] = $item['jump_url'];
        //         $custom_type[$i]['order_by'] = $item['order_by'];
        //         $i ++;
        //     }
        //     if ($item['jump_type'] == 1) {
        //         $video_type[$j]['img_url'] = $item['img_url'];
        //         $video_type[$j]['jump_url'] = $item['jump_url'];
        //         $video_type[$j]['order_by'] = $item['order_by'];
        //         $j ++;
        //     }
        //     if ($item['jump_type'] == 0) {
        //         $page_type[$k]['img_url'] = $item['img_url'];
        //         $page_type[$k]['jump_url'] = $item['jump_url'];
        //         $page_type[$k]['order_by'] = $item['order_by'];
        //         $k ++;
        //     }
        // }
        $res = [$type => ['data' => [
            'banner_count' => $banner_count,
            'info' => $info
            // 'custom_type' => $custom_type,
            // 'video_type' => $video_type,
            // 'page_type' => $page_type
        ]]];
        return $this->output_succ($res);
        //return outputJson($res);
    }

    public function get_version_control(){
        $ret_info = $this->t_version_control->get_publish_url();
        $data['window_exe_url'] = '';
        $data['window_yml_url'] = '';
        $data['mac_dmg_url']    = '';
        if($ret_info){
           foreach ($ret_info as $key => $value) {
                if($value['file_type'] == 1){
                    $data['window_exe_url'] = $value['file_url'];
                }else if($value['file_type'] == 2){
                    $data['window_yml_url'] = $value['file_url'];
                }else if($value['file_type'] == 3){
                    $data['mac_dmg_url'] = $value['file_url'];
                }
            }
        }

        return $this->output_succ(['data' => $data]);
    }

    public function origin_jump(  ){

    }

    # 42服务器上请求此接口
    public function updateTranResult(){
        $lessonid = $this->get_in_int_val('lessonid');
        $zip_url  = $this->get_in_str_val('zip_url');
        $is_tea   = $this->get_in_int_val('is_tea');
        \App\Helper\Utils::logger("2_1zip_url: $zip_url; is_tea:$is_tea");

        if($is_tea == 1 ){ # 老师
            $this->t_lesson_info_b3->field_update_list($lessonid,[
                "zip_url" => $zip_url
            ]);
        }else{ # 学生
            $this->t_lesson_info_b3->field_update_list($lessonid,[
                "zip_url_stu" => $zip_url
            ]);
        }
        return $this->output_succ();
    }

    # 42服务器端请求此接口 获取数据
    public function getNeedTranLessonUid(){
        $ret_info = $this->t_lesson_info_b3->getNeedTranLessonUid();
        return $this->output_succ(['data'=>$ret_info]);
    }

    # 42服务器获取老师上传ppt文件
    public function getTeaUploadPPTLink(){
        $ret_info = $this->t_lesson_info_b3->getTeaUploadPPTLink();
        return $this->output_succ(['data'=>$ret_info]);
    }

    # 42服务器更新 lesson_info uuid
    public function updateLessonUUid(){
        $lessonid = $this->get_in_int_val('lessonid');
        $uuid     = $this->get_in_str_val('uuid');
        $is_tea   = $this->get_in_int_val('is_tea');

        if($is_tea == 1){
            $this->t_lesson_info->field_update_list($lessonid, [
                "uuid"=>$uuid
            ]);
        }else{
            $this->t_lesson_info->field_update_list($lessonid, [
                "uuid_stu"=>$uuid
            ]);
        }
        return $this->output_succ();
    }

    //@desn:测试环境模拟拨打
    //@param:call_flag 拨打标识 1 模拟失败 2 模拟成功
    public function test_simulation_call(){
        \App\Helper\Utils::logger("模拟拨打开始!");
        $call_flag = $this->get_in_int_val('call_flag',0);
        $phone = $this->get_in_int_val('phone','');
        $this->set_in_value('call_flag', $call_flag);
        $this->set_in_value('phone', $phone);
        return $this->tianrun_notify_call_end();
    }

    public function redirectForPdf(){
        $url = $this->get_in_str_val('url');
        $orderid =  $this->get_in_int_val('orderid');
        $checkTime = $this->t_order_info->get_first_check_time_by_orderid($orderid);
        if(!$checkTime){
            $this->t_order_info->field_update_list($orderid, [
                "first_check_time" => time()
            ]);
        }
        header("Location: $url");
        return;
    }

}
