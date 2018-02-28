<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use \App\Enums as E;
use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;
use Illuminate\Support\Facades\Redis ;
use Illuminate\Support\Facades\Session ;
use App\Jobs\deal_wx_pic;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
require_once app_path("/Libs/Qiniu/functions.php");
require_once(app_path("/Libs/OSS/autoload.php"));
use OSS\OssClient;
use OSS\Core\OssException;
class wjx_receive_api extends Controller
{
    use CacheNick;
    var $check_login_flag=false;
    public function __construct() {
        parent::__construct();

        if(!$this->check_ip_frequent() ){
            echo $this->output_err("当前操作过于频繁！");
            exit;
        }
    }

    //获取毫秒数
    function getMillisecond() {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
    }

    //检查该ip取数据的频繁次数,限制1秒取1次
    private function check_ip_frequent(){
        $redis= $this->get_redis();
    
        $oldTime = $redis->get($_SERVER["REMOTE_ADDR"]);
        $time = $this->getMillisecond();        
        
        if( $time - $oldTime <= 1000 ){
            return false;
        }else{
            $redis->set($_SERVER["REMOTE_ADDR"],$time);
        }

        return true;
    }

    //获取学生id和试卷id
    public function get_answers(){
        $grade = E\Egrade::$desc_map;
        $subject = E\Esubject::$desc_map;
        $params= $this->get_in_str_val("params");
        $param_arr = explode("-", $params);
        if(count($param_arr) > 2){
            $paper_id = $param_arr[0];
            $user_id = $param_arr[1];
            $phone = $param_arr[2];
        }
        return $this->output_succ(['grade' => $grade,'subject' => $subject]);
    }

    //将学生的答案录入并且给出分数
    public function give_scores(){
        $data = file_get_contents("php://input");
        \App\Helper\Utils::logger("学生的提交数据: $data");
        return $this->output_succ(['grade' => $grade,'subject' => $subject]);
    }

}