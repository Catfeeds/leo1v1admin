<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;

class common_ex extends Controller
{
    var $check_login_flag =false;

    public function book_free_lesson_with_code() {
        $code       = $this->get_in_str_val('code');
        $phone      = $this->get_in_phone();
        $check_code = \App\Helper\Common::redis_get("JOIN_USER_PHONE_$phone" );

        \App\Helper\Utils::logger("check_code:".$check_code." code:".$code." sessionid:".session_id());
        if ($check_code != $code) {
            return $this->output_err("手机验证码不对,请重新输入");
        }

        return $this->book_free_lesson();
    }

    public function send_phone_code () {
        $phone = trim($this->get_in_str_val('phone'));
        $code_flag= $this->get_in_int_val("code_flag",0) ;

        if ( strlen($phone) != 11) {
            return $this->output_err("电话号码出错");
        }
        \App\Helper\Utils::logger("sessionid:".session_id());

        $msg_num = \App\Helper\Common::redis_set_json_date_add("STU_PHONE_$phone",1000000);
        $code    = rand(1000,9999);

        \App\Helper\Common::redis_set("JOIN_USER_PHONE_$phone", $code );

        $ret=\App\Helper\Utils::sms_common($phone, 10671029,[
            "code" => $code,
            "index" => $msg_num
        ] );


        $ret_arr= ["msg_num" =>$msg_num  ];
        if ( $code_flag ) {
            $ret_arr["code"] =  $code;
        }

        return $this->output_succ($ret_arr);

    }

    public function book_free_lesson()
    {

        $nick     = $this->get_in_str_val("nick");
        $phone    = $this->get_in_str_val("phone");
        $origin_phone = $this->get_in_str_val("origin_phone");
        $sms_type = $this->get_in_str_val("sms_type");
        $grade    = $this->get_in_grade();
        $origin   = urldecode( $this->get_in_str_val("origin")) ;
        $tmp_arr  = preg_split("/[=&]/",$origin);
        $origin   = @$tmp_arr[0];

        $subject      = $this->get_in_subject();
        $has_pad      = $this->get_in_int_val("has_pad");
        $trial_type   = $this->get_in_int_val('trial_type', 0);
        $class_time   = $this->get_in_str_val('class_time');
        $class_time_s = strtotime($class_time);
        $course_name  = $this->get_in_str_val("course_name");
        $qq           = $this->get_in_int_val("qq");
        $add_to_main_flag = $this->get_in_int_val("add_to_main_flag",0);

        if(!preg_match( "/^1[34578]{1}\d{9}$/",$phone)){
            return outputJson(array('ret' => -1, 'info' => "请输入规范的手机号!"));
        }
        if($origin_phone!="" && $origin_phone==$phone){
            return $this->output_err("推荐人手机号不能和报名手机相同！");
        }
        $origin_userid = 0;
        if($origin_phone!=""){
            $origin_userid = $this->t_student_info->get_userid_by_phone($origin_phone);
            if($origin_userid == 0){
                return $this->output_err("不存在此推荐人！请重新填写！");
            }
        }
        $client_ip = $this->get_in_client_ip();
        if ($phone == "15601830297" || $phone == "13917746147"  || $phone == "18817822725" ) {
            $max_user_count=1000;
        }else{
            $max_user_count=1;
        }

        $check_ret=\App\Helper\Common::redis_day_add_with_max_limit("day_phone_$phone",1,$max_user_count);
        if (!$check_ret){
            return outputJson(array('ret' => -1, 'info' => "您预约的次数太多了!"));
        }

        $check_ret= \App\Helper\Common::redis_day_add_with_max_limit (
            "day_$client_ip",1,30);

        if (!$check_ret){
            return outputJson(array('ret' => -1, 'info' => "您预约的次数太多了!"));
        }

        $msg = "资源:手机:$phone<br/>"
            ."渠道:$origin<br/>"
            ."年级:".E\Egrade::get_desc($grade) ."<br/>"
            ."科目:".E\Esubject::get_desc($subject) ."<br/>"
            ."pad:".E\Epad_type::get_desc($has_pad) ."<br/>"
            ."";
        $this->t_book_revisit->add_book_revisit($phone,"COMMING:$msg","system");
        $user_ip = $this->get_in_client_ip();
        /*
         * 预约完成4-28
         * SMS_63750218
         * ${name}家长您好，恭喜您成功预约1节0元名师1对1辅导课！您的专属顾问老师将尽快与您取得联系，
         * 请注意接听${public_num}开头的上海号码。如果您有任何疑问需要咨询，请加微信客服（微信号：leoedu058）
         * 或拨打全国免费咨询电话${public_telphone}。
         */
        $public_telphone = "400-680-6180";
        $sms_id          = 63750218;
        $arr = [
            "name"            => " ",
            "public_num"      => "021或158",
            "public_telphone" => $public_telphone,
        ];
        \App\Helper\Utils::sms_common($phone,$sms_id,$arr);

        $userid = $this->t_seller_student_new->book_free_lesson_new( $nick,$phone,$grade, $origin, $subject, $has_pad );
        if($origin_userid!=0){
            $this->t_student_info->field_update_list($userid,[
               "origin_userid" => $origin_userid
            ]);
        }

        $name    = $phone;
        $name[4] = "*";
        $name[5] = "*";
        $name[6] = "*";
        $name[7] = "*";
        return $this->output_succ(["userid"=> $userid,"name"=>$name]);
    }

    /**
     * 推荐老师接口
     */
    public function recommend_teacher(){
        $reference = $this->get_in_str_val("reference");
        $name      = $this->get_in_str_val("name");
        $phone     = $this->get_in_str_val("phone");
        $email     = $this->get_in_str_val("email");
        $school    = $this->get_in_str_val("school");
        $subject   = $this->get_in_str_val("subject");
        $grade     = $this->get_in_str_val("grade");
        $textbook  = $this->get_in_str_val("textbook");
        $self_introduction_experience= $this->get_in_str_val("self_introduction_experience");


        $check_flag=\App\Helper\Utils::check_phone($phone);
        if(!$check_flag){
            return $this->output_err("请输入正确的手机号！");
        }

        $check_flag = $this->t_teacher_lecture_appointment_info->check_is_exist(0,$phone);
        if($check_flag){
            return $this->output_err("提交失败,此号码已被推荐！");
        }

        \App\Helper\Utils::logger("teacher recommended. phone is :".$phone.".reference is :".$reference);
        $ret = $this->t_teacher_lecture_appointment_info->row_insert([
            "reference"  => $reference,
            "name"       => $name,
            "phone"      => $phone,
            "email"      => $email,
            "school"     => $school,
            "subject_ex" => $subject,
            "grade_ex"   => $grade,
            "textbook"   => $textbook,
            "self_introduction_experience" => $self_introduction_experience,
        ]);

        if($ret){
            return $this->output_succ();
        }else{
            return $this->output_err("提交失败，请重试！");
        }
    }


}
