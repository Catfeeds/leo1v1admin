<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;
use Illuminate\Support\Facades\Redis ;
use Illuminate\Support\Facades\Session ;

class wx_yxyx_common extends Controller
{
    var $check_login_flag=false;

    public function wx_jump_page(){
        $code       = $this->get_in_str_val("code");
        $wx_config  = \App\Helper\Config::get_config("yxyx_wx");
        $wx         = new \App\Helper\Wx( $wx_config["appid"] , $wx_config["appsecret"] );
        $token_info = $wx->get_token_from_code($code);
        $openid     = @$token_info["openid"];
        if(!$openid) {
            dd("请关闭 重进");
            exit;
        }
        global $_SERVER;
        session(["wx_yxyx_openid" => $openid]);
        \App\Helper\Utils::logger("HOST:".$_SERVER["HTTP_HOST"] );
        \App\Helper\Utils::logger("new_openid:$openid");
        \App\Helper\Utils::logger("sessionid:".session_id());

        $goto_url     = urldecode(hex2bin($this->get_in_str_val("goto_url")));
        $goto_url_arr = preg_split("/\//", $goto_url);
        $action       = @$goto_url_arr[2];
        $web_html_url = "http://wx-yxyx-web.leo1v1.com";
        if($action=="bind"){
            $url="$web_html_url/index.html#bind";
        }else{
            \App\Helper\Utils::logger('yxyx_www_openid:'.$openid);
            $agent_info = $this->t_agent->get_agent_info_by_openid($openid);
            $id = $agent_info['id'];
            \App\Helper\Utils::logger('yxyx_www_id:'.$id);
            if($id){
                session([
                    "login_user_role" => 10,
                    "agent_id"    => $id,
                ]);
                $url = "/wx_yxyx_web/$action";
            }else{
                $url = "$web_html_url/index.html#bind?".$action;
            }
        }
        \App\Helper\Utils::logger("JUMP URL:$url");
        header("Location: $url");
        return "succ";
    }

    public function logout(){
        session([
            "agent_id" => 0,
            "login_user_role" =>0,
        ]);
        return $this->output_succ('注销成功!');
    }

    public function send_phone_code() {
        $phone = trim($this->get_in_str_val('phone'));
        $wx_openid  = session("wx_yxyx_openid");
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }else{
            $openid = '';
            // $agent_info = $this->t_agent->get_agent_info_by_openid($wx_openid);
            $agent_info = $this->t_agent->get_agent_info_by_phone($phone);
            if(isset($agent_info['wx_openid'])){
                $openid = $agent_info['wx_openid'];
            }
            if($openid){
                $id = $agent_info['id'];
                return $this->output_err("您已绑定过！");
            }
        }

        $msg_num= \App\Helper\Common::redis_set_json_date_add("WX_P_PHONE_$phone",1000000);
        $code = rand(1000,9999);
        $ret=\App\Helper\Utils::sms_common($phone, 10671029,[
            "code" => $code,
            "index" => $msg_num
        ] );

        session([
            'wx_yxyx_code'=>$code,
            'wx_yxyx_phone'=>$phone
        ]);

        return $this->output_succ(["msg_num" =>$msg_num,"code" => $code ]);
    }

    public function bind(){
        $phone      = $this->get_in_str_val("phone");
        $code       = $this->get_in_str_val("code");
        $check_code = session("wx_yxyx_code");
        $wx_openid  = session("wx_yxyx_openid");
        if(!$wx_openid){
            return $this->output_err('微信绑定失败!请重新登录后绑定!"');
        }
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }

        if($code==$check_code){
            $agent_info = [];
            $agent_info = $this->t_agent->get_agent_info_by_phone($phone);
            $user_info  = $this->get_wx_user_info($wx_openid);
            $headimgurl = $user_info['headimgurl'];
            $nickname   = $user_info['nickname'];
            if(isset($agent_info['id'])){
                $id = $this->t_agent->update_field_list('t_agent',['wx_openid'=>$wx_openid,'headimgurl'=>$headimgurl,'nickname'=>$nickname],'id',$agent_info['id']);
                $id = $agent_info['id'];
            }else{
                $userid = null;
                $userid_new = $this->t_phone_to_user->get_userid_by_phone($phone, E\Erole::V_STUDENT );
                if($userid_new){
                    $userid = $userid_new;
                }
                $id = $this->t_agent->add_agent_row_new($phone,$headimgurl,$nickname,$wx_openid,$userid);
            }
            if(!$id){
                return $this->output_err("生成失败！请退出重试！");
            }

            session(["agent_id"=>$id]);
            session(["wx_yxyx_openid" => $wx_openid]);
            session(["login_user_role"=>10]);
            return $this->output_succ("绑定成功!");
        }else{
            return $this->output_err ("验证码错误");
        }
    }

    public function get_wx_user_info($wx_openid){
        $wx_config    = \App\Helper\Config::get_config("yxyx_wx");
        $wx           = new \App\Helper\Wx( $wx_config["appid"] , $wx_config["appsecret"] );
        $access_token = $wx->get_wx_token($wx_config["appid"],$wx_config["appsecret"]);
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$wx_openid."&lang=zh_cn";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($output,true);

        return $data;
    }

    public function agent_add(){
        $p_phone = $this->get_in_str_val('p_phone');
        $phone   = $this->get_in_str_val('phone');
        $type   = $this->get_in_int_val('type');
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }
        if($p_phone == $phone){
            return $this->output_err("不能邀请自己!");
        }
        if(!$type){
            return $this->output_err("请选择报名类型!");
        }
        if($p_phone){
            $phone_str = implode(',',[$phone,$p_phone]);
            $ret_list = $this->t_agent->get_id_by_phone($phone_str);
            foreach($ret_list as $item){
                if($phone == $item['phone']){
                    return $this->output_err("您已被邀请过!");
                }
                if($p_phone = $item['phone']){
                    $parentid = $item['id'];
                }
            }
        }
        if(!isset($parentid)){
            $parentid = 0;
        }
        if($type == 1){//进例子
            $this->t_seller_student_new->book_free_lesson_new($nick='',$phone,$grade=0,$origin='优学优享',$subject=0,$has_pad=0);
        }
        $userid = null;
        $userid_new = $this->t_phone_to_user->get_userid_by_phone($phone, E\Erole::V_STUDENT );
        if($userid_new){
            $userid = $userid_new;
        }
        $ret = $this->t_agent->add_agent_row($parentid,$phone,$userid,$type);
        if($ret){
            return $this->output_succ("邀请成功!");
        }else{
            return $this->output_err("数据请求异常!");
        }
    }

    public function get_wx_yxyx_js_config(){
        $ref = $this->get_in_str_val("ref");
        $signature_str = $this->get_signature_str($ref);
        $config = [
            'debug' => 'false',
            'appId' => 'wxb4f28794ec117af0', // 必填，公众号的唯一标识
            'timestamp' => '1501516800', // 必填，生成签名的时间戳(随意值)
            'nonceStr'  => 'leo456', // 必填，生成签名的随机串(随意值)
            'signature' => $signature_str,// 必填，签名
            'jsApiList' => [
                "checkJsApi",
                "chooseImage",
                "previewImage",
                "uploadImage",
                "downloadImage",
                "getLocalImgData",
            ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        ];
        \App\Helper\Utils::logger('yxyx_sig:'.$signature_str);

        return $this->output_succ($config);
    }

/**
     *老师端微信上传图片
    **/
    public function get_signature_str( $ref, $appid_yxyx= 'wxb4f28794ec117af0', $appscript_yxyx= '4a4bc7c543698b8ac499e5c72c22f242' ){
        $token = $this->get_wx_token_jssdk($appid_yxyx, $appscript_yxyx);
        $key_arr     = "wx_yxyx_jssdk_arr_$appid_yxyx";
        $key_str     = "wx_yxyx_jssdk_str_$appid_yxyx";
        $ret_arr = \App\Helper\Common::redis_get_json($key_arr);
        $now     = time(NULL);
        if (!$ret_arr || !isset($ret_arr["ticket"])  ||  $ret_arr["get_time"]+7000 <  $now ) {
            $jssdk    = $this->get_wx_jsapi_ticket($token);
            $ret_arr  = \App\Helper\Utils::json_decode_as_array($jssdk);
            $ret_arr["get_time"] = time(NULL);
            \App\Helper\Common::redis_set_json($key_arr,$ret_arr );
        }
        $jsapi_ticket = $ret_arr["ticket"];
        // $ref= $ref?$ref:$_SERVER['HTTP_REFERER'];
        $signature = "jsapi_ticket=$jsapi_ticket&noncestr=leo456&timestamp=1501516800";
                   // . "&url=$ref" ;
        \App\Helper\Utils::logger( "signature:$signature" );

        $signature_str = sha1($signature);
        return $signature_str;
    }

    public function get_wx_token_jssdk($appid_yxyx = 'wxb4f28794ec117af0', $appscript_yxyx= '4a4bc7c543698b8ac499e5c72c22f242' ){
        $wx  = new \App\Helper\Wx();
        return $wx->get_wx_token($appid_yxyx,$appscript_yxyx);
    }

    public function get_wx_jsapi_ticket($token){
        $json_jssdk_data=file_get_contents("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$token&type=jsapi");
        return $json_jssdk_data;
    }

    public function get_all_test_pic(){
        //title,date,用户未读取标志（14天内的消息），十张海报（当天之前的，可跳转）
        $grade     = $this->get_in_int_val('grade',-1);
        $subject   = $this->get_in_int_val('subject',-1);
        $test_type = $this->get_in_int_val('test_type',-1);
        $wx_openid = $this->get_in_str_val('wx_openid', 0);
        $page_info = $this->get_in_page_info();
        $ret_info  = $this->t_yxyx_test_pic_info->get_all_for_wx($grade, $subject, $test_type, $page_info, $wx_openid);
        $start_time = strtotime('-14 days');
        $end_time   = strtotime('tomorrow');
        foreach ($ret_info['list'] as &$item) {
            if (!$item['flag'] && $item['create_time'] < $end_time && $item['create_time'] > $start_time) {
                $item['flag'] = 0;
            }
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
        }
        //随机获取十张海报/不足十张，取所有?也取14天之内的
        $all_id     = $this->t_yxyx_test_pic_info->get_all_id_poster(0,$start_time,$end_time);
        $count_num  = count($all_id)-1;
        $poster_arr = [];
        $num_arr    = [];
        $loop_num   = 0;
        if ($count_num <= 10) {
            while ( $loop_num < $count_num) {
                $key = mt_rand(0, $count_num);
                if( !in_array($key, $num_arr)) {
                    $num_arr[]    = $key;
                    $poster_arr[] = $all_id[$key];
                    $loop_num++;
                }
            }
        } else {
            while ( $loop_num < 10) {
                $key = mt_rand(0, $count_num);
                if( !in_array($key, $num_arr)) {
                    $num_arr[]    = $key;
                    $poster_arr[] = $all_id[$key];
                    $loop_num++;
                }
            }
        }
        $ret_info['poster'] = $poster_arr;
        return $this->output_succ(["home_info"=>$ret_info]);
    }

    public function get_one_test_and_other() {
        //title,poster(当天之前的)
        $id   = $this->get_in_int_val('id',-1);
        $flag = $this->get_in_int_val('flag', 1);
        $wx_openid = $this->get_in_int_val('wx_openid', 1);
        if ($id < 0){
            return $this->output_err('信息有误！');
        }
        if (!$flag) {
            $this->t_yxyx_test_pic_visit_info->add_visit_info($id,$wx_openid);//添加到访问记录
        }
        $this->t_yxyx_test_pic_info->add_field_num($id,"visit_num");//添加访问量
        $ret_info = $this->t_yxyx_test_pic_info->get_one_info($id);
        \App\Helper\Utils::unixtime2date_for_item($ret_info,"create_time");
        E\Egrade::set_item_value_str($ret_info,"grade");
        E\Esubject::set_item_value_str($ret_info,"subject");
        E\Etest_type::set_item_value_str($ret_info,"test_type");
        $ret_info['pic_arr'] = explode( '|',$ret_info['pic']);
        unset($ret_info['pic']);
        //获取所有id，随机选取三个(当天之前的14天之内)
        $start_time = strtotime('-15 days');
        $end_time   = strtotime('today');
        $all_id    = $this->t_yxyx_test_pic_info->get_all_id_poster($id, $start_time, $end_time);
        $count_num = count($all_id)-1;
        $id_arr    = [];
        $num_arr   = [];
        $loop_num  = 0;
        while ( $loop_num < 3) {
            $key = mt_rand(0, $count_num);
            if( !in_array($key, $num_arr)) {
                $num_arr[] = $key;
                $id_arr[]  = $all_id[$key]['id'];
                $loop_num++;
            }
        }
        $id_str = '('.join($id_arr,',').')';
        $create_time = strtotime('today');
        $ret_info['other'] = $this->t_yxyx_test_pic_info->get_other_info($id_str, $create_time);
        return $this->output_succ(['list' => $ret_info]);
    }

    public function add_share_num(){
        $id = $this->get_in_int_val('id',-1);
        $this->t_yxyx_test_pic_info->add_field_num($id,"share_num");//添加分享次数
    }

    public function get_yxyx_all_new(){
        $ret_info = $this->t_yxyx_new_list->get_all_for_wx();
        foreach ($ret_info as &$item) {
            $item['new_content'] = mb_substr( str_replace(PHP_EOL, '', strip_tags($item['new_content'])),0,30);
        }
        if ($ret_info) {
            return $this->output_succ(["data"=>$ret_info]);
        } else {
            return $this->output_err("信息有误！");
        }
        // dd($ret_info);
    }

    public function get_yxyx_one_new(){
        $id = $this->get_in_int_val("id", -1);
        $ret_info = $this->t_yxyx_new_list->get_one_new_for_wx($id);
        if ($ret_info) {
            return $this->output_succ(["data"=>$ret_info]);
        } else {
            return $this->output_err("信息有误！");
        }
        // dd($ret_info);
    }


}
