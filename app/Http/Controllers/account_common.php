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

    
    public function send_time_code(){
        $phone = $this->get_in_str_val("phone");
        $role = $this->get_in_int_val("role");
        $time = time()-45000;
        $value = md5("leo".$time.$phone.$role."1v1");
        $key = $phone."-".$role."time";

        session([
            $key  => $value,
        ]);
        return $this->output_succ(["time"=>$time]);


    }

    public function send_verification_code(){
        $phone = $this->get_in_str_val("phone");
        $role = $this->get_in_int_val("role");
        $time_code = $this->get_in_str("time_code");

        $key = $phone."-".$role."time";
        $session_time_code = session($key);
        if($time_code != $session_time_code){
            return $this->output_err("请输入正确的手机号码");
        }

    }
   
   

}
