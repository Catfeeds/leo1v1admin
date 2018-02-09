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
        $type       = $this->get_in_str_val("type","");
        $check_code = \App\Helper\Common::redis_get("JOIN_USER_PHONE_$phone" );

        if($type=="zhishiku"){
            \App\Helper\Utils::logger("check_code:".$check_code." code:".$code." sessionid:".session_id());
            if ($check_code != $code) {
                return $this->output_err("手机验证码不对,请重新输入");
            }
            return $this->share_knowledge();
        }else{
            \App\Helper\Utils::logger("check_code:".$check_code." code:".$code." sessionid:".session_id());
            if ($check_code != $code) {
                return $this->output_err("手机验证码不对,请重新输入");
            }
            return $this->book_free_lesson();
        }
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

        $ret = \App\Helper\Utils::sms_common($phone, 10671029,[
            "code"  => (string)$code,
            "index" => (string)$msg_num
        ] );
        $ret_arr= ["msg_num" =>$msg_num  ];
        if ( $code_flag ) {
            $ret_arr["code"] = $code;
        }

        return $this->output_succ($ret_arr);
    }

    public function share_knowledge(){
        $phone = $this->get_in_str_val("phone");
        $p_phone = $this->get_in_str_val("p_phone");
        if(!preg_match( "/^1[34578]{1}\d{9}$/",$phone)){
            return outputJson(array('ret' => -1, 'info' => "请输入规范的手机号!"));
        }
        if($p_phone!="" && $p_phone==$phone){
            return $this->output_err("推荐人手机号不能和报名手机相同！");
        }
        $cc_type = 0;
        if($p_phone == ""){
            $cc_type = 0;
        }
        $userid = $this->t_phone_to_user->get_userid_by_phone($phone, E\Erole::V_STUDENT );
        if($userid){
            //return $this->output_err("此号码已经注册!");
        }
        if($p_phone != ''){
            $account_role = $this->t_manager_info->get_account_role_by_phone($p_phone);
            $account_id   = $this->t_manager_info->get_uid_by_phone($p_phone);
            if($account_role == 2){ //销售cc
                $cc_type = 2;
            }elseif($account_role == 1){//助教cr
                $cc_type = 1;
            }
            $ret_info = $this->t_admin_group_user->get_info_by_adminid($account_id);
            if($ret_info['main_type'] == 2 || $ret_info['main_type'] == 3){
                $key1 = "知识库";
                $key2 = E\Emain_type::get_desc($ret_info['main_type']);
                $key3 = $ret_info['group_name'];
                $key4 = $ret_info['account']."-".date("md",time());
                $value = $key4;
            }else{
                $account = $this->t_manager_info->get_account_by_phone($p_phone);
                $key1 = "知识库";
                $key2 = "其它";
                $key3 = "其它";
                $key4 = $account."-".date("md",time());
                $value = $key4;
            }
        }else{
            $cc_type = 0;
            $key1 = "未定义";
            $key2 = "未定义";
            $key3 = "未定义";
            $key4 = "未定义";
            $value = $key4;
        }
        //qudao
        $ret_origin = $this->t_origin_key->add_by_admind($key1,$key2,$key3,$key4,$value,$origin_level =1,$create_time=0);
        //进例子
        $origin_value = "知识库";
        $new_userid = $this->t_seller_student_new->book_free_lesson_new($nick='',$phone,$grade=0,$origin_value,$subject=0,$has_pad=0);
        if($cc_type == 2){ //分配例子给销售
            $opt_adminid = $account_id; // ccid
            (new  ss_deal() ) ->set_admin_id_ex([$new_userid],$opt_adminid,0);
            //$this->t_seller_student_new->allow_userid_to_cc($opt_adminid, $opt_account, $new_userid);
        }else{
            //$opt_adminid = 212; // ccid
            //$opt_account=$this->t_manager_info->get_account($opt_adminid);
            //$this->t_seller_student_new->allow_userid_to_cc($opt_adminid, $opt_account, $new_userid);
        }

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
        return $this->output_succ(["ret"=> "恭喜您成功预约1节0元名师1对1辅导课！您的专属顾问老师将尽快与您取得联系"]);
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

        if(!preg_match( "/^1[345789]{1}\d{9}$/",$phone)){
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
        if ($phone == "15601830297" || $phone == "13917746147"  || $phone == "18817822725" ) {
            $max_user_count=1000;
        }else{
            $max_user_count=1;
        }

        $check_ret = \App\Helper\Common::redis_day_add_with_max_limit("day_phone_$phone",1,$max_user_count);
        if (!$check_ret) {
            return outputJson(array('ret' => -1, 'info' => "您预约的次数太多了!"));
        }

        $client_ip = $this->get_in_client_ip();
        $check_ret = \App\Helper\Common::redis_day_add_with_max_limit("day_$client_ip",1,30);
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

        # 获取分享链接打开次数 [市场部活动-分享个性海报]
        $uid = $this->get_in_int_val('uid');
        $posterTag = $this->get_in_int_val('posterTag');

        dispatch(new \App\Jobs\new_seller_student($userid,$uid,$posterTag,$phone,$origin,$subject));

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

    //@desn:测试环境模拟拨打
    //@param:call_flag 拨打标识 1 模拟失败 2 模拟成功
    public function test_simulation_call(){
        $call_flag = $this->get_in_int_val('call_flag',0);
        $this->set_in_value('call_flag', $call_flag);
        return $this->tianrun_notify_call_end();
    }

    /**
     * 添加小班课的报错日志
     * @param int report_error_type 报错类型
     * @param string error_msg      报错内容
     */
    public function add_admin_class_err(){
        $report_error_type = $this->get_in_int_val("report_error_type");
        $error_msg         = $this->get_in_str_val("error_msg");

        $id = $this->t_sys_error_info->add(E\Ereport_error_from_type::V_3,$report_error_type,$error_msg);
        if($id>0){
            return $this->output_succ(['id'=>$id]);
        }else{
            return $this->output_err();
        }
    }

    public function get_group_adminid_list($adminid=0){
        $adminid = $adminid>0?$adminid:$this->get_account_id();
        $majordomo_groupid=$this->t_admin_majordomo_group_name->is_master($adminid);
        $admin_main_groupid=$this->t_admin_main_group_name->is_master($adminid);
        $self_groupid=$this->t_admin_group_name->is_master($adminid);
        //主管查看下级例子
        $admin_revisiterid_list = [];
        $son_adminid = [];
        $son_adminid_arr = [];
        if($majordomo_groupid>0){//总监
            $son_adminid = $this->t_admin_main_group_name->get_son_adminid_by_up_groupid($majordomo_groupid);
        }elseif($admin_main_groupid>0){//经理
            $son_adminid = $this->t_admin_group_name->get_son_adminid_by_up_groupid($admin_main_groupid);
        }elseif($self_groupid>0){//组长
            $son_adminid = $this->t_admin_group_user->get_son_adminid_by_up_groupid($self_groupid);
        }
        foreach($son_adminid as $item){
            if($item['adminid']>0){
                $son_adminid_arr[] = $item['adminid'];
            }
        }
        array_unshift($son_adminid_arr,$adminid);
        $admin_revisiterid_list = array_unique($son_adminid_arr);
        return $admin_revisiterid_list;
    }

    public function get_month_group_adminid_list($month,$adminid=0){
        $adminid = $adminid>0?$adminid:$this->get_account_id();
        $majordomo_groupid=$this->t_main_major_group_name_month->is_master($month,$adminid);
        $admin_main_groupid=$this->t_main_group_name_month->is_master($month,$adminid);
        $self_groupid=$this->t_group_name_month->is_master($month,$adminid);
        //主管查看下级例子
        $admin_revisiterid_list = [];
        $son_adminid = [];
        $son_adminid_arr = [];
        if($majordomo_groupid>0){//总监
            $son_adminid = $this->t_main_group_name_month->get_son_adminid_by_up_groupid($month,$majordomo_groupid);
        }elseif($admin_main_groupid>0){//经理
            $son_adminid = $this->t_group_name_month->get_son_adminid_by_up_groupid($month,$admin_main_groupid);
        }elseif($self_groupid>0){//组长
            $son_adminid = $this->t_group_user_month->get_son_adminid_by_up_groupid($month,$self_groupid);
        }
        foreach($son_adminid as $item){
            if($item['adminid']>0){
                $son_adminid_arr[] = $item['adminid'];
            }
        }
        array_unshift($son_adminid_arr,$adminid);
        $admin_revisiterid_list = array_unique($son_adminid_arr);
        return $admin_revisiterid_list;
    }

    public function get_seller_month($start_time,$end_time){
        $month = [$start_time,$end_time];
        $ret_time = $this->t_month_def_type->get_all_list();
        foreach($ret_time as $item){//本月
            if($start_time>=$item['start_time'] && $start_time<$item['end_time']){
                $month = [$item['start_time'],$item['end_time']];
                break;
            }
        }
        return $month;
    }

}
