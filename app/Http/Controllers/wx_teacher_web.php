<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;
use Illuminate\Support\Facades\Redis ;
use Illuminate\Support\Facades\Session ;

class wx_teacher_web extends Controller
{
    var $check_login_flag = false;
    public function __construct(){
        parent::__construct();

        $to_url       = $this->get_in_str_val("_url");
        $goto_url_arr = preg_split("/\//", $to_url);
        $action       = @$goto_url_arr[2];

        \App\Helper\Utils::logger("wx_url_new: $to_url");

        if($action == 'tea'){
            $url = "http://wx-teacher-web.leo1v1.com/tea.html?reference";
            header("Location: $url");
            return ;
        }

        if (session("login_user_role")==2 && session("login_userid")) {
            $web_html_url="http://wx-teacher-web.leo1v1.com";

            $teacherid   = session("login_userid");
            $wx_use_flag = $this->t_teacher_info->get_wx_use_flag($teacherid);
            $filter_url  = ['course_arrange','comment_list','complaint'];
            if(in_array($action,$filter_url) && $wx_use_flag == 0){
                $action = 'guide_apply';
            }

            $url="$web_html_url/$action.html";
            if($action == 'tea'){
                $url.='?reference';
            }

            header("Location: $url");
        }else{
            $wx_config = \App\Helper\Config::get_config("teacher_wx");
            $to_url    = bin2hex($this->get_in_str_val("_url"));
            $wx        = new \App\Helper\Wx( $wx_config["appid"] , $wx_config["appsecret"] );

            $redirect_url = urlencode("http://wx-teacher.leo1v1.com/wx_teacher_common/wx_jump_page?goto_url=$to_url" );
            $wx->goto_wx_login( $redirect_url );
        }
    }

    public function wage_summary() {}
    public function tea(){}
    public function comment_list() {}
    public function course_arrange() {}
    public function complaint() {}
    public function honor_rank() {}
    public function index (){}
    public function teacher_day(){
        $url = "http://wx-teacher-web.leo1v1.com/teacher_day/index.html";
        header("Location: $url");
    }

    public function teacher_activity(){
        $url = "http://wx-teacher-web.leo1v1.com/teacher_activity/index.html";
        header("Location: $url");
    }

    public function feedback(){
        $url = "http://wx-teacher-web.leo1v1.com/wx_complain/feedback.html";
        header("Location: $url");
    }

    public function wx_comment(){

    }

    # 跳转至老师工资页面[解决session失效问题]
    public function gotoWage(){
        $url = "http://wx-teacher-web.leo1v1.com/wage_details.html";
        header("Location: $url");
    }

    



}