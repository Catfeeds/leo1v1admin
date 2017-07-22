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
        $agent_id= $this->get_agent_id();
        \App\Helper\Utils::logger("sessionid:".session_id());
        \App\Helper\Utils::logger("web agent_id:".$agent_id);
        if($agent_id){
            $web_html_url="http://wx-yxyx-web.leo1v1.com";
            $to_url      = $this->get_in_str_val("_url");
            $get_url_arr = preg_split("/\//", $to_url);
            $action      = $get_url_arr[2];
            $url = "$web_html_url/$action.html";
            if($action == 'bind'){
                $url = "$web_html_url/index.html#bind";
            }
            \App\Helper\Utils::logger("yxyx_oid:".session('wx_yxyx_openid'));

            header("Location: $url");
        }else{
            $wx_config=\App\Helper\Config::get_config("yxyx_wx");
            $to_url=bin2hex($this->get_in_str_val("_url"));
            $wx= new \App\Helper\Wx( $wx_config["appid"] , $wx_config["appsecret"] );

            $redirect_url=urlencode("http://wx-yxyx.leo1v1.com/wx_yxyx_common/wx_jump_page?goto_url=$to_url" );
            $wx->goto_wx_login( $redirect_url );
        }
    }

    public function bind(){}
    public function index(){}
}
