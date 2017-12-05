<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;
use Illuminate\Support\Facades\Redis ;
use Illuminate\Support\Facades\Session ;

class wx_yxyx_web extends Controller
{
    var $check_login_flag=false;

    public function get_agent_id(){
        $agent_id = $this->get_in_int_val("_agent_id")?$this->get_in_int_val("_agent_id"):session("agent_id");
        return $agent_id;
    }

    public function __construct() {
        parent::__construct();

        $agent_id = $this->get_agent_id();
        $agent = $this->t_agent->get_agent_info_by_id($agent_id);

        $agent_id_new = $agent['id'];
        \App\Helper\Utils::logger("agent_idxx: $agent_id agent_id_new: $agent_id_new ");

        $wx_config=\App\Helper\Config::get_config("yxyx_wx");
        $base_url=$wx_config["url"];

        if($agent_id_new){


            $web_html_url= preg_replace("/wx-yxyx/","wx-yxyx-web", $base_url ) ;

            $to_url      = $this->get_in_str_val("_url");
            $get_url_arr = preg_split("/\//", $to_url);
            $action      = $get_url_arr[2];

            $url = "$web_html_url/$action.html?v=1101";
            // 测试环境
            if(\App\Helper\Utils::check_env_is_test() || \App\Helper\Utils::check_env_is_release())
                $url=$wx_config["test_url"].'/index.html';

            if($action == 'bind'){
                // if($action == 'bind' or !$agent_id_new){
                $url = "$web_html_url/index.html#bind";
                //测试环境
                if(\App\Helper\Utils::check_env_is_test() || \App\Helper\Utils::check_env_is_release())
                    $url = $wx_config["test_url"].'/login.html';

            }
            header("Location: $url");
        }else{
            \App\Helper\Utils::logger('yxyx_yyy');
            $to_url=bin2hex($this->get_in_str_val("_url"));
            $wx= new \App\Helper\Wx( $wx_config["appid"] , $wx_config["appsecret"] );
            $redirect_url=urlencode("$base_url/wx_yxyx_common/wx_jump_page?goto_url=$to_url" );
            $wx->goto_wx_login( $redirect_url );
        }
    }

    public function bind(){}
    public function index(){}
}
