<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;
use Illuminate\Support\Facades\Redis ;
use Illuminate\Support\Facades\Session ;

class wx_parent extends Controller
{
    var $check_login_flag=false;

    public function __construct() {
        parent::__construct();
        $this->middleware(function($request,$next){

            if(!session("parentid")){
                \App\Helper\Utils::logger('jiluscore '.$this->get_in_str_val("_url"));

                $wx_config = \App\Helper\Config::get_config("wx");
                $to_url = bin2hex($this->get_in_str_val("_url"));
                $goto_url_arr=preg_split("/\//", $to_url);
                $action=@$goto_url_arr[2];
                if($action=="zhishiku"){
                    $to_url = "zhishiku";
                }
                \App\Helper\Utils::logger("changeurl:  $to_url");

                $wx= new \App\Helper\Wx();
                $redirect_url=urlencode("http://wx-parent.leo1v1.com/wx_parent_common/wx_parent_jump_page?goto_url=$to_url");
                $wx->goto_wx_login( $redirect_url );
            }else{
                \App\Helper\Utils::logger("session_parentid:".session('parentid'));
                $web_html_url="http://wx-parent-web.leo1v1.com";

                $to_url=$this->get_in_str_val("_url");

                $goto_url_arr=preg_split("/\//", $to_url);
                $action=@$goto_url_arr[2];

                if($action=="zhishiku"){
                    $url = "http://wx-parent-web.leo1v1.com/wx_yxyx_BoutiqueContent/index.html?type='zhishiku'";
                    header("Location: $url");
                }else{
                    $url ="$web_html_url/$action?".@$_SERVER["QUERY_STRING"];
                    \App\Helper\Utils::logger("wx_url1:$url");
                    header("Location: $url");
                }
            }
            return $next($request);
        });
}

    public function index() {

    }

    public function binding() {

    }

    public function preview() {

    }
    public function complain () {

    }

    public function adjust_progress() {

    }

    public function scores() {

    }

    public function input_score() {

    }

    public function zhishiku(){

    }

    public function prizes(){
        
    }


}
