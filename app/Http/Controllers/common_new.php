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
    var $check_login_flag =false;
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
        $reference                    = $this->get_in_int_val("reference");
        $custom                       = $this->get_in_str_val("custom");
        $lecture_appointment_status   = $this->get_in_int_val("lecture_appointment_status",0);
        $lecture_appointment_origin   = $this->get_in_int_val("lecture_appointment_origin",0);
        $qq                           = $this->get_in_str_val("qq","");

        $phone      = substr($phone,0,11);
        $check_flag = $this->t_teacher_lecture_appointment_info->check_is_exist(0,$phone);
        if($check_flag){
            return $this->output_err("该手机号已提交过了,不能重新提交!");
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
        $grade = $this->check_grade_by_subject($grade,$subject_ex);
        if($grade!=0){
            $grade_range = \App\Helper\Utils::change_grade_to_grade_range($grade);
            $grade_start = $grade_range['grade_start'];
            $grade_end   = $grade_range['grade_end'];
        }
        if($grade_start==0 || $grade_end==0){
            return $this->output_err("请选择规范的年级!");
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
        ];

        $ret = $this->t_teacher_lecture_appointment_info->row_insert($data);
        if($ret){
            \App\Helper\Utils::logger("teacher appointment:".$phone."data:".json_encode($data));
            if($email!=""){
                $html  = $this->get_email_html($subject_ex,$grade_start,$grade_end,$grade,$name);
                $title = "【理优1对1】试讲邀请和安排";
                $ret   = \App\Helper\Common::send_paper_mail($email,$title,$html);
            }

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
                $wx_openid      = $reference_info['wx_openid'];
                $teacher_type   = $reference_info['teacher_type'];
                if($wx_openid!="" && !in_array($teacher_type,[21,22,31])){
                    $record_info = $name."已填写报名信息";
                    $status_str  = "已报名";
                    \App\Helper\Utils::send_reference_msg_for_wx($wx_openid,$record_info,$status_str);
                }
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
            $html  = $this->get_email_html(0,0,0,0,$name);
            $title = "【理优1对1】试讲邀请和安排";
            $ret   = \App\Helper\Common::send_paper_mail($email,$title,$html);
            if(!$ret){
                return $this->output_err("邮件发送失败!");
            }
        }

        return $this->output_succ();
    }

    public function get_email_html($subject=0,$grade_start=0,$grade_end=0,$grade=0,$name=""){
        $file_url = \App\Helper\Utils::get_teacher_lecture_file_by_grade($subject,$grade);
        $html     = "
<html>
    <head>
        <meta charset='utf-8'>
        <style>
         .red{color:#ff3451;}
         .leo_blue{color:#0bceff;}
         body{font-size:24px;line-height:48px;color:#666;}
         .t20{margin-top:20px;}
         .underline{text-decoration:underline;}
         .download-pc-url{cursor:pointer;}
        </style>
    </head>
    <body>
        <div align='center'>
            <div style='width:800px;' align='left'>
                <div align='left'>尊敬的".$name."老师：</div>
                <div class='t20'>
                    感谢您对理优1对1的关注，您的报名申请已收到！
                    <br/>
                    为了更好的评估您的教学能力，需要您尽快按照如下要求提交试讲视频
                    <br/>
                    【试讲要求】
                    <br/>
                    请准备好<span class='red'>耳机和话筒</span>，用<span class='red'>指定内容</span>
                    在理优老师客户端录制一段试讲视频，并提交
                </div>
                <div>
                    <div class='red t20'>
                        【相关资料】↓↓↓
                    </div>
                    <ul>
                        <li>1、理优老师端<a class='leo_blue' href='http://www.leo1v1.com/common/download'>点击下载</a></li>
                        <li>2、试讲内容<a class='leo_blue' href='http://file.leo1v1.com/index.php/s/4TwePiQyP1PkZMD?path=%2F'>点击下载</a></li>
                        <li>3、简历模板<a class='leo_blue' href='http://leowww.oss-cn-shanghai.aliyuncs.com/JianLi.docx'>点击下载</a></li>
                        <li>4、录制教程<a class='leo_blue' href='http://leowww.oss-cn-shanghai.aliyuncs.com/TeacherLecturePPT/MianShiLiuCheng.mp4' target='_blank'>点击播放</a></li>
                        <li>5、统一试讲账号 :<span class='red'>99900010001&nbsp;&nbsp;&nbsp;&nbsp;密码：173175</span></li>
                    </ul>
                </div>
                <div>
                    请<span class='red'>尽快提交</span>试讲视频，教研老师会按照提交先后顺序审核，并在第一时间通知到您
                </div>
                <div>
                    <div class='t20'>
                        【通关攻略】
                    </div>
                    <ul>
                        <li>1、保证相对安静的录制环境和稳定的网络环境</li>
                        <li>2、要上传讲义和板书，试讲要结合板书</li>
                        <li>3、请务必把你所选PPT里的题目讲完，不能挑某一题讲解</li>
                        <li>4、要注意跟学生的互动（假设电脑的另一端坐着学生）</li>
                        <li>5、简历、PPT完善后需转成PDF格式才能上传</li>
                        <li>6、准备充分再录制，面试机会只有一次，要认真对待</li>
                    </ul>
                </div>
                <div class='red'>
                    （温馨提示：为方便审核，请在每次翻页后在白板中画一笔）
                </div>
                <div >
                    <div class='t20'>
                        【联系我们】
                    </div>
                    如有疑问请加【LEO】试讲-答疑QQ群 : 608794924 <br/>
                    <img width='240' src='http://7u2f5q.com2.z0.glb.qiniucdn.com/9b4c10cff422a9d0ca9ca60025604e6c1498550175839.png'/><br>
                    （关注理优1对1老师帮公众号：观看优秀试听课视频）<br/>
                    <img width='240' src='http://7u2f5q.com2.z0.glb.qiniucdn.com/ce78e7582c7b841b38a1c95e639f37f01496399082593.png'/><br>
                </div>
                <div>
                    <div class='t20'>
                        【岗位介绍】
                    </div>
                    名称：理优在线1对1授课教师（通过理优教师端进行网络语音或视频授课）
                    <br/>
                    时薪：50-100RMB
                </div>
                <div>
                    <div class='t20'>
                        【关于理优】
                    </div>
                    理优1对1致力于为初高中学生提供专业、专注、有效的教学，帮助更多家庭打破师资、时间、地域、费用的局限，获得四维一体的专业学习体验。作为在线教育行业内首家专注于移动Pad端研发的公司，理优1对1在1年内成功获得GGV数千万元A轮投资（GGV风投曾投资阿里巴巴集团、优酷土豆、去哪儿、小红书等知名企业）
                </div>
            </div>
    </body>
</html>
";
        return $html;
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
                $phone          = $item[0];
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
          cdr_bridge_cno 呼出接听电话的座席号码 如：2000
          CDR(userfield) 使用第三方外呼调用接口时传递了参数userField 该值只是第三方外呼调用接口发起的呼叫，且传递了userField参数，在挂机推送时用来获取userField传递的值。

        */
        $cdr_bridge_time=$this->get_in_int_val("cdr_bridge_time");
        $cdr_end_time=$this->get_in_int_val("cdr_end_time");

        $cdr_bridge_cno = $this->get_in_int_val("cdr_bridge_cno");
        $cdr_status = $this->get_in_int_val("cdr_status");

        $recid= ($cdr_bridge_cno<<32 ) + $cdr_bridge_time;
        $cdr_customer_number = $this->get_in_str_val("cdr_customer_number");

        $called_flag=$cdr_status==28?2:1;

        $this->t_tq_call_info->add(
            $recid,
            $cdr_bridge_cno,
            $cdr_customer_number,
            $cdr_bridge_time,
            $cdr_end_time,
            $cdr_end_time-$cdr_bridge_time,
            $called_flag
            ,
            "");
        $this->t_seller_student_new->sync_tq($cdr_customer_number ,$called_flag, $cdr_bridge_time);
        return json_encode(["result"=>"success"]);
    }

    public function notify_gen_lesson_teacher_pdf_pic() {
        $lessonid = $this->get_in_lessonid();
        $pdf_url  = $this->t_lesson_info->get_tea_cw_url($lessonid);
        dispatch(new deal_pdf_to_image($pdf_url, $lessonid));
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

}