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

        //验证用户是否存在
        // $exist = $this->t_student_info->

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
        return $this->output_succ(["time"=>$time]);


    }

    //发送验证码
    public function send_verification_code(){
        $phone = $this->get_in_str_val("phone");
        $role = $this->get_in_int_val("role");
        $time_code = $this->get_in_str_val("time_code");

        $check_phone =  \App\Helper\Utils::check_phone($phone);
        if(!$check_phone){
            return $this->output_err("手机号码不合法!");
        }


        $key = $phone."-".$role."time";
        $session_time_code = session($key);
        if($time_code != $session_time_code){
            // return $this->output_err("请输入正确的手机号码");
        }

        $phone_code=\App\Helper\Common::gen_rand_code(6);
      
        $index = $this->get_current_verify_num($phone,$role);
        


    }

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
                $data = ["index"=>$index,"time"=>$list['time']];
                \App\Helper\Common::redis_set_expire_value($redis_key,$data,43200);
            }else{
                $data = ["index"=>1,"time"=>time()];
                \App\Helper\Common::redis_set_expire_value($redis_key,$data,43200);
                $index=1;
            }

        }

        return $index;
    }
   
   

}
