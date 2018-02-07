<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;
use Illuminate\Support\Facades\Redis ;
use Illuminate\Support\Facades\Session ;

class wx_parent_common extends Controller
{
    var $check_login_flag=false;

    public function wx_parent_jump_page () {
        $code       = $this->get_in_str_val("code");
        /**  @var  wx \App\Helper\Wx */
        $wx= new \App\Helper\Wx( );
        global $_SERVER;
        $token_info = $wx->get_token_from_code($code);
        $openid     = @$token_info["openid"];
        // dd($openid);
        if (!$openid) {
            dd( "请关闭 重进");
            exit;
        }
        session(["wx_parent_openid" => $openid ] );

        $goto_url     = urldecode(hex2bin($this->get_in_str_val("goto_url")));
        $goto_url_arr = preg_split("/\//", $goto_url);
        $action       = @$goto_url_arr[2];
        $web_html_url="http://wx-parent-web.leo1v1.com";
        if ($action=="binding" ){
            if($goto_url=="zhishiku"){
                $url="$web_html_url/binding?goto_url=$goto_url";
            }
            $url="$web_html_url/binding?goto_url=";
        }else{
            $parentid= $this->t_parent_info->get_parentid_by_wx_openid($openid);
            if ($parentid) {
                session([
                    "parentid" => $parentid,
                ]);

                if($action=="zhishiku"){
                    $url = "http://wx-parent-web.leo1v1.com/wx_yxyx_BoutiqueContent/index.html?type='zhishiku'";
                }else{
                    $url="$web_html_url/$action";
                }

            }else{
                $url="$web_html_url/binding?goto_url=/$action";
            }
        }
        \App\Helper\Utils::logger("JUMP URL:$url");

        header("Location: $url");
        return "succ";
    }

    public function wx_send_phone_code () {
        $phone = trim($this->get_in_str_val('phone'));
        $market_activity_type = $this->get_in_str_val('type',-1); // 区分是否是市场的活动

        if ( strlen($phone) != 11) {
            return $this->output_err("电话号码出错");
        }
        $parentid = $this->t_phone_to_user->get_userid_by_phone($phone,E\Erole::V_PARENT );
        if(!$parentid && ($market_activity_type<0)) {
            return $this->output_err("你的孩子还没有注册理优1对1,不能绑定!");
        }

        $msg_num = \App\Helper\Common::redis_set_json_date_add("WX_P_PHONE_$phone",1000000);
        $code = rand(1000,9999);
        \App\Helper\Common::redis_set("JOIN_USER_PHONE_$phone", $code );
        $ret  = \App\Helper\Utils::sms_common($phone,10671029,[
            "code"  => $code,
            "index" => $msg_num
        ]);

        session([
            'wx_parent_code'=>$code,
            'wx_parent_phone'=>$phone,
            'market_activity_type' => $market_activity_type
        ]);

        return $this->output_succ(["msg_num" =>$msg_num,"code" => $code ]);
    }

    public function do_wx_bind(){
        $code      = $this->get_in_str_val('code');
        $wx_openid = session("wx_parent_openid");
        $openid    = $this->get_in_str_val("openid");
        if(!$wx_openid){
            $wx_openid = $openid;
        }

        if (!$wx_openid){
            return $this->output_err("请重新绑定");
        }

        $phone = session("wx_parent_phone");
        if (!$phone){
            return $this->output_err("请重新绑定");
        }

        $parentid = $this->t_phone_to_user->get_userid_by_phone($phone,E\Erole::V_PARENT );
        $check_is_admin = $this->t_manager_info->check_admin_by_phone($phone);
        $market_activity_type = session("market_activity_type");
        if(!$parentid && ($market_activity_type<0) && !$check_is_admin) {
            return $this->output_err("你的孩子还没有注册理优1对1,不能绑定!");
        }

        if($market_activity_type >= 1 ){
            $passwd      = 111111;
            $reg_channel = '';
            $ip          = 0;
            $nick        = "";
            $parentid    = $this->t_parent_info->register($phone, $passwd, $reg_channel , $ip,$nick);

            $this->t_parent_info->field_update_list($parentid,[
                "wx_openid" => $wx_openid
            ]);
            session(["parentid" => $parentid]);

            return $this->output_succ(["type"=>$market_activity_type,"parentid"=> $parentid]);
        }

        $db_parentid = $this->t_parent_info->get_parentid_by_wx_openid($wx_openid );
        if ($db_parentid) {
            $this->t_parent_info->field_update_list($db_parentid,[
                "wx_openid" => NULL,
            ]);
        }

        $this->t_parent_info->field_update_list($parentid,[
            "wx_openid" => $wx_openid,
        ]);
        session(["parentid" => $parentid]);

        return $this->output_succ(["type"=>$market_activity_type]);
    }

    public function get_lesson_evaluate () {
        $lessonid     = $this->get_in_lessonid();
        $userid       = $this->get_in_userid();
        $label_origin = 1;
        $item = $this->t_teacher_label->get_info_by_lessonid($lessonid,$userid,$label_origin);
        if ($item)  {
            return $this->output_succ($item);
        }else{
            return $this->output_err("没有数据");
        }

    }


    public function check_parent_info(){

    }

    public function get_user_info_for_market(){
        /**
           获取code
           https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect
           define("WECHAT_APPID", 'wx636f1058abca1bc1'); //理优公众号
           define("WECHAT_APPSECRET",'756ca8483d61fa9582d9cdedf202e73e');//理优

        ***/
        $parent_appid = "wx636f1058abca1bc1";
        $url = "http://admin.leo1v1.com/wx_parent_common/check_parent_info";

        $redirect_url = urlencode($url);

        $url = " https://open.weixin.qq.com/connect/oauth2/authorize?appid=$parent_appid&redirect_uri=$redirect_url&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";


        dd($url);
    }


    public function logout() {
        session([
            "parentid" => 0,
            "wx_parent_openid" =>"",
            "wx_parent_phone" => "" ,
        ]);
        return $this->output_succ();
    }
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
            \App\Helper\Utils::logger("SHARE_KNOWLEDGE");

            return $this->share_knowledge();
        }else{
            \App\Helper\Utils::logger("check_code:".$check_code." code:".$code." sessionid:".session_id());
            if ($check_code != $code) {
                return $this->output_err("手机验证码不对,请重新输入");
            }
            return $this->output_err("Error!");
            return $this->book_free_lesson();
        }
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
            return $this->output_err("此号码已经注册!");
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
        $ret_origin = $this->t_origin_key->add_by_admind($key1,$key2,$key3,$key4,$value,$origin_level =1,$create_time=0);
        //进例子
        $origin_value = "知识库";
        $new_userid = $this->t_seller_student_new->book_free_lesson_new($nick='',$phone,$grade=0,$origin_value,$subject=0,$has_pad=0);
        if($cc_type == 2){ //分配例子给销售
            $opt_adminid = $account_id; // ccid
            $this->t_seller_student_new->set_admin_id_ex([$new_userid],$opt_adminid,0);
            $this->t_manager_info->send_wx_todo_msg_by_adminid ($account_id,
            "来自：知识库",
            "你收到1个例子",
            "需要你及时联系",
            "http://admin.leo1v1.com/seller_student_new/seller_student_list_all");
            //$task->t_manager_info->send_wx_todo_msg_by_adminid ($account_id,"国庆延休统计","全职老师国庆延休安排情况如下","如下".$num."位老师满足条件,具体名单如下:".$namelist,""); 
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


        return $this->output_succ("恭喜您成功预约1节0元名师1对1辅导课！您的专属顾问老师将尽快与您取得联系");
    }
}
