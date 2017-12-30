<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log ;
use \App\Enums as E;


class TeaWxController extends Controller
{
    public $check_wx_use_flag=true;

    function __construct()  {
        if ($this->check_wx_use_flag ) {
             $this->check_wx_use();
        }
    }

    function check_wx_use() {
        if (!session("teacher_wx_use_flag")){
            if (!\App\Helper\Utils::check_env_is_test()) {
                \App\Helper\Utils::logger("GOTO: " .$_SERVER["REQUEST_URI"] );
                $url=\App\Helper\Utils::gen_wx_teacher_url("error.html",["msg"=>"正式上课老师,才可看"]);
                header("Location: $url"  );
                exit;
            }else{
                return true;
            }
        }
    }

    public function get_teacherid(){
        $role      = $this->get_in_int_val("_role",0);
        $teacherid = $this->get_in_int_val("_userid",0);

        if (!$role) {
            $role = session("login_user_role" );
        }

        if (!$teacherid) {
            $teacherid = session("login_userid" );
        }

        // 测试
        if($teacherid == 684){
            return '108226'; //alan
        }
        // 测试



        if ($role==2 &&  $teacherid ) {
            return $teacherid;
        }else{
            echo $this->output_err("未登录 ");
            exit;
        }
    }

}
