<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use LaneWeChat\Core\UserManage;
use \App\Enums as E;
use Illuminate\Support\Facades\Input ;

// 引入鉴权类
use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

//引入分词类
use Analysis\PhpAnalysis;

require_once  app_path("Libs/Pingpp/init.php");

class account_common extends Controller
{
    use TeaPower;
    use CacheNick;
    var $check_login_flag =false;

    //发送验证码前发送时间戳给前端,回调验证
    public function send_time_code(){

        $phone = $this->get_in_str_val("phone");
        $role = $this->get_in_int_val("role");

        $check_phone =  \App\Helper\Utils::check_phone($phone);
        if(!$check_phone){
            return $this->output_err("手机号码不合法!");
        }

        $time = time()-45000;
        $value = md5("leo".$time.$phone.$role."1v1");//生成验证信息给前端
        $key = $phone."-".$role."time";
        session([
            $key  => $value,
        ]);
        \App\Helper\Utils::logger("value:".$value);

        return $this->output_succ(["time"=>$time]);


    }

    //注册验证码回调验证
    public function send_time_code_for_register(){
        $phone = $this->get_in_str_val("phone");
        $role = $this->get_in_int_val("role");


        //验证用户是否存在
        $exist = $this->t_phone_to_user->get_userid_by_phone($phone,$role);
        if($exist){
            return $this->output_err("用户已存在");
        }

        return $this->send_time_code();

    }

    //重置密码验证码回调验证
    public function send_time_code_for_reset_passwd(){
        $phone = $this->get_in_str_val("phone");
        $role = $this->get_in_int_val("role");

        //验证用户是否存在
        $exist = $this->t_phone_to_user->get_userid_by_phone($phone,$role);
        if(!$exist){
            return $this->output_err("用户不存在");
        }

        return $this->send_time_code();
    }

    //发送验证码
    public function send_verification_code(){
        $phone = $this->get_in_str_val("phone");
        $role = $this->get_in_int_val("role");
        $time_code = $this->get_in_str_val("time_code");
        $reg_ip = $this->get_in_client_ip();

        $check_phone =  \App\Helper\Utils::check_phone($phone);
        if(!$check_phone){
            return $this->output_err("手机号码不合法!");
        }

        // //验证用户是否存在
        // $exist = $this->t_phone_to_user->get_userid($phone,$role);
        // if($exist){
        //     return $this->output_err("用户已存在");
        // }



        $key = $phone."-".$role."time";
        $session_time_code = session($key);
        if($time_code != $session_time_code){
            return $this->output_err("请输入正确的手机号码");
        }

        $phone_code=\App\Helper\Common::gen_rand_code(6);
        $code_key = $phone."-".$role."-code";

        \App\Helper\Common::redis_set_expire_value($code_key, $phone_code,1200);

        // session([
        //     $code_key  => $phone_code,
        // ]);
        

        $phone_index = $this->get_current_verify_num($phone,$role);
        // return $this->output_succ(["msg_num"=>$phone_index,"verify_code"=>$phone_code]);

        // //测试
        // $this->t_manager_info->send_wx_todo_msg_by_adminid(349,"yzm","yzm","code:".$phone_code.",num:".$phone_index,"");


        // \App\Helper\Utils::logger("address::".\App\Helper\Config::get_monitor_new_url()."/notice/sms_register");

        /*
          模板名称: 通用验证
          模板ID: SMS_10671029
          *模板内容: 您的手机验证码为：${code} ，请尽快完成验证 编号为： ${index}
          */
        \App\Helper\Net::send_sms_taobao($phone,$reg_ip, 10671029,[
            "code"  => $phone_code,
            "index" => $phone_index,
        ],1);

        \App\Helper\Utils::logger("code:".$phone_code);
        \App\Helper\Utils::logger("index:".$phone_index);
        return $this->output_succ(["msg_num"=>$phone_index,"verify_code"=>$phone_code]);
    }

    //用户注册
    public function register(){
        $phone = $this->get_in_str_val("phone");
        $role = $this->get_in_int_val("role");
        $passwd = $this->get_in_str_val("passwd");
        //  $verify_code = $this->get_in_str_val("verify_code");
        $reg_ip = ip2long($this->get_in_client_ip());

        $check_phone =  \App\Helper\Utils::check_phone($phone);
        if(!$check_phone){
            return $this->output_err("手机号码不合法!");
        }

        //验证用户是否存在
        $exist = $this->t_phone_to_user->get_userid_by_phone($phone,$role);
        if($exist){
            return $this->output_err("用户已存在");
        }

        $code_key = $phone."-".$role."-code";

        
        // $check_verify_code = session($code_key);
        // $check_verify_code = \App\Helper\Common::redis_get($code_key);

        // $check_flag = $this->check_verify_code( $verify_code,$check_verify_code,$phone,$role);
        // if(!$check_flag){
        //     return $this->output_err("验证码错误或已失效");
        // }


        //用户注册
        $reg_channel = $this->get_in_str_val("reg_channel");
        $passwd_md5_two=md5($passwd."@leo");
        $userid=$this->t_user_info->add( $passwd, $passwd_md5_two, $reg_ip, $reg_channel);
        if (!$userid) {
            return $this->output_err("系统错误,请重新注册");
        }

        $this->t_phone_to_user->row_insert([
            "role" =>$role,
            "phone" =>$phone,
            "userid" =>$userid,
        ]);
        if($role==E\Erole::V_STUDENT){
            $region = $this->get_in_str_val("region");
            $grade = $this->get_in_int_val("grade");
            $addr_code = $this->get_in_int_val("addr_code");
            $editionid = $this->get_in_int_val("editionid");
            $guest_code = $this->get_in_str_val("guest_code");
            $textbook = $this->get_in_str_val("textbook");
            $ret = $this->t_student_info->add_student($userid,$grade,$phone,"",$region);
            if($ret){
                $this->t_student_info->field_update_list($userid,[
                    "addr_code" =>$addr_code, 
                    "editionid" =>$editionid, 
                    "guest_code" =>$guest_code, 
                    "textbook" =>$textbook, 
                ]);
            }
            
 
        }
        return $this->output_succ();
 
    }

    public function check_verify_code_phone(){
        $phone = $this->get_in_str_val("phone");
        $role = $this->get_in_int_val("role");
        $verify_code = $this->get_in_str_val("verify_code");
        $code_key = $phone."-".$role."-code";

        
        //$check_verify_code = session($code_key);
        $check_verify_code = \App\Helper\Common::redis_get($code_key);
        // return $this->output_succ(["code"=>$verify_code,"check_verify_code"=>$check_verify_code,"code_key"=>$code_key]);
        $check_flag = $this->check_verify_code( $verify_code,$check_verify_code,$phone,$role);
        
        if(!$check_flag){
            return $this->output_err("验证码错误或已失效");
        }else{
            return $this->output_succ();
        }


    }


    //重置密码
    public function reset_passwd(){
        $phone = $this->get_in_str_val("phone");
        $role = $this->get_in_int_val("role");
        $passwd = $this->get_in_str_val("passwd");
        //  $verify_code = $this->get_in_str_val("verify_code");

        $check_phone =  \App\Helper\Utils::check_phone($phone);
        if(!$check_phone){
            return $this->output_err("手机号码不合法!");
        }

        //验证用户是否存在
        $userid = $this->t_phone_to_user->get_userid_by_phone($phone,$role);
        if(!$userid){
            return $this->output_err("用户不存在");
        }

        $code_key = $phone."-".$role."-code";

        
        // $check_verify_code = session($code_key);
        // $check_verify_code = \App\Helper\Common::redis_get($code_key);

        //return $this->output_succ(["code"=>$verify_code,"check_verify_code"=>$check_verify_code]);

        // $check_flag = $this->check_verify_code( $verify_code,$check_verify_code,$phone,$role);
        // if(!$check_flag){
        //     return $this->output_err("验证码错误或已失效");
        // }

        //更新密码
        $passwd_md5_two=md5($passwd."@leo");
        $this->t_user_info->field_update_list($userid,[
            "passwd"  =>$passwd,
            "passwd_md5_two" =>$passwd_md5_two
        ]);
        return $this->output_succ();

    }

    //登录
    public function login(){
        $phone = $this->get_in_str_val("phone");
        $role = $this->get_in_int_val("role");
        $passwd = $this->get_in_str_val("passwd");//两次md5之后的数据

        $check_phone =  \App\Helper\Utils::check_phone($phone);
        if(!$check_phone){
            return $this->output_err("手机号码不合法!");
        }

        //验证用户是否存在
        $userid = $this->t_phone_to_user->get_userid_by_phone($phone,$role);
        if(!$userid){
            return $this->output_err("用户不存在");
        }


        $db_passwd=$this->t_user_info->get_passwd_md5_two($userid);
        $check_phone_flag= ($db_passwd == $passwd);
        if (!$check_phone_flag) { // check 临时密码
            $key = "md5_two_".$phone.'_'.$role;
            \App\Helper\Utils::logger( "key=> $key " );

            $redis_passwd = \App\Helper\Common::redis_get($key);;
            if ($redis_passwd == $passwd) { //redis 密码
                $check_phone_flag =true;
            }
        }
        if (!$check_phone_flag) {
            return $this->output_err("密码错误");
        }

        return $this->output_succ(["userid"=>$userid]);
 
    }

    //获取验证码编号
    public function get_current_verify_num($phone,$role){
        $redis_key = $phone."-".$role."-index";
        $list =  \App\Helper\Common::redis_get_json($redis_key);
        if(!$list){
            $data = ["index"=>1,"time"=>time()];
            \App\Helper\Common::redis_set_expire_value($redis_key,$data,43200);
            $index=1;
        }else{
            $pre_time = strtotime(date("Y-m-d 00:00:00", @$list['time']));
            $current_time = strtotime(date("Y-m-d 00:00:00", time()));
            if($pre_time==$current_time){
                $index = @$list["index"]+1;
                $data = ["index"=>$index,"time"=>time()];
                \App\Helper\Common::redis_set_expire_value($redis_key,$data,43200);
            }else{
                $data = ["index"=>1,"time"=>time()];
                \App\Helper\Common::redis_set_expire_value($redis_key,$data,43200);
                $index=1;
            }

        }

        return $index;
    }


    //检验验证码
    public function check_verify_code( $verify_code,$check_verify_code,$phone,$role){
        $redis_key = $phone."-".$role."-index";
        $list =  \App\Helper\Common::redis_get_json($redis_key);
        $time = $list["time"];
        // if($verify_code ==$check_verify_code){
        if($time>=(time()-120) && $verify_code ==$check_verify_code){
            return true;//时效
        }else{
            return false;
        }
 
    }

    public function test(){
        $role=$this->get_in_int_val("role",1);
        $phone = $this->get_in_phone();
        // $key = $phone."-".$role."time";
        // $check_verify_code = session($key);
        // dd($check_verify_code);


        // $redis_key = $phone."-".$role."-index";
        // $list =  \App\Helper\Common::redis_get_json($redis_key);
        // dd($list);
        $code_key = $phone."-".$role."-code";
        \App\Helper\Utils::logger("key:$code_key");


        
        // $check_verify_code = session($code_key);
        $check_verify_code = \App\Helper\Common::redis_get($code_key);
        dd($check_verify_code);
 
    }
   
   

}
