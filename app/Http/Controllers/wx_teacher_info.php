<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;
use Illuminate\Support\Facades\Redis ;


class wx_teacher_info extends Controller
{
    var $appid="";
    var $appsecret="";
    var $check_login_flag =false;
    var $teacherid=0;

    use CacheNick;

    public function goto_wx_login($redirect_url) {
        \App\Helper\Utils::logger(" goto_wx_login redirect_uri: $redirect_url");
        $appid=$this->appid;
        $no=rand(1,10000);
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_url&response_type=code&no=$no&scope=snsapi_userinfo&state=STATE_$no&connect_redirect=1#wechat_redirect";
        header("location: $url");
        exit;
    }

    function __construct()  {
        $this->appid=\App\Helper\Config::get_wx_appid();
        $this->appsecret=\App\Helper\Config::get_wx_appsecret();

        parent::__construct();
        if (\App\Helper\Utils::check_env_is_local() ) {
            session([
                "teacher_nick" => "戴老师123",
                "teacherid"    => "60008"  ,
                "acc"          => "18621696950" ,
                "openid"       => "o97Q8vxpdbvMOslCsDA5jiTptSRo",
            ]);
        }

        $teacherid=session("teacherid");
        \App\Helper\Utils::logger("__construct: teacherid: $teacherid");
        if ($teacherid) {
            $this->teacherid=$teacherid;
        } else{

            $host=@$_SERVER["HTTP_HOST" ];
            $request_uri=@$_SERVER["REQUEST_URI"];
            $redirect_url=urlencode("http://$host$request_uri" );


            $code=$this->get_in_str_val("code");
            if ($code) {
                \App\Helper\Utils::logger("has code");
                $token_info = $this->get_token_from_code($code);
                $openid     = @$token_info["openid"];
                if (!$openid) {
                    $this->goto_wx_login($redirect_url );
                    exit;
                }


                $userid     = $this->t_wx_openid_bind->get_userid($openid,E\Erole::V_TEACHER);


                $teacher_info=$this->t_teacher_info->get_teacher_info($userid);
                if ($teacher_info) {
                    session([
                        "teacher_nick" => $teacher_info["nick"],
                        "teacherid"    => $teacher_info["teacherid"],
                        "acc"    => $teacher_info["phone"],
                        "openid"       => $openid,
                        ]);

                }else{
                    header("location: /wx_teacher/bind?url=$request_uri&openid=$openid");
                }
            }else{
                \App\Helper\Utils::logger("not code");
                $this->goto_wx_login($redirect_url );
                exit;
            }


        }

    }


    public function get_token_from_code($code) {
        $appid=$this->appid;
        $appsecret=$this->appsecret;
        //https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code
        $json_data=file_get_contents( "https://api.weixin.qq.com/sns/oauth2/access_token?grant_type=authorization_code&code=$code&appid=$appid&secret=$appsecret"  );
        $ret_arr=\App\Helper\Utils::json_decode_as_array($json_data);

        return $ret_arr;
    }

    function index() {
        return $this->pageView(__METHOD__);
    }

    function test_lesson_config () {
        $openid=session("openid");
        // $str="{\"touser\":\"$openid\", \"template_id\":\"GIbPl4eva3JeMTUVcgRyYE0AuujsZFJDWm4yGpIR8t0\", \"url\":\"http://www.baidu.com\", \"topcolor\":\"#FF0000\", \"data\":{\"a\":{\"value\":\"消费\", \"color\":\"#173177\"}, \"SSS\":{\"value\":\"人民币260.00元\", \"color\":\"#173177\"}}}";

        $str="{\"touser\":\"$openid\", \"template_id\":\"QjXZ5epFLKbKWX5IaxLUIInvXmK5jdBcM_IW7jUz6wU\", \"url\":\"http://www.baidu.com\", \"topcolor\":\"#FF0000\", \"data\":{\"a\":{\"value\":\"消费\", \"color\":\"#173177\"}, \"SSS\":{\"value\":\"人民币260.00元\", \"color\":\"#173177\"}}}";


        $token=\App\Helper\Utils::wx_get_token();

        $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$token";
        $ret=\App\Helper\Common::http_post_json_str($url, $str );
        dd($ret);


    }
    public function current_course()
    {
        $teacherid   = $this->teacherid;
        return $this->pageView(__METHOD__ );
    }


    public function get_lesson_time_js() {
        $teacherid   = $this->teacherid;

        \App\Helper\Utils::logger("teacherid: $teacherid ");
        $timestamp = $this->get_in_int_val("timestamp");
        $type = $this->get_in_int_val("type",0);
        if ($timestamp == 0) {
            $timestamp = time();
        }

        if($type==0) { //
            $ret_week   = \App\Helper\Utils::get_week_range($timestamp,1);
            $start_time = $ret_week["sdate"];
            $end_time   = $ret_week["edate"];
        }else{
            $ret_week   = \App\Helper\Utils::get_month_range($timestamp) ;
            $start_time = $ret_week["sdate"];
            $end_time   = $ret_week["edate"];
        }

        $lesson_list=$this->t_lesson_info->get_teacher_lesson_info($teacherid,$start_time,$end_time);
        foreach($lesson_list as &$item) {
            $nick=$this->cache_get_student_nick($item["userid"]);
            $item["month_title"]= $nick  ;
            $item["week_title"]=  "学生:$nick";
        }
        return $this->output_succ(["lesson_list"=>$lesson_list]);
    }

    public function do_confirm_test_lesson($teacherid,$seller_student_id ) {
        $this->t_seller_student_info->start_transaction();

        $update_count=$this->t_seller_student_info->set_assinged_teacherid($seller_student_id, $teacherid );
        $assigned_teacherid=$this->t_seller_student_info->get_assigned_teacherid([
            "id" => $seller_student_id,
        ]);
        \App\Helper\Utils::logger("update_count=$update_count; teacherid:$teacherid, assigned_teacherid:$assigned_teacherid");

        $teacher_confirm_flag=-1;
        if ($update_count==0) { //没有设置成功
            if ($assigned_teacherid==$teacherid) {
                $msg = "之前已经抢单成功,等待理优工作人员和你确认^_^";
            }else{
                $msg = "手慢了:(, 已经已经被别人抢先了.";
                $teacher_confirm_flag=2;
            }

        }else{
            $msg="恭喜抢单成功,等待理优工作人员和你确认^_^";
            $teacher_confirm_flag=1;
        }
        if($teacher_confirm_flag != -1) {
            $ret=$this->t_test_lesson_assign_teacher->field_update_list([
                "seller_student_id"  => $seller_student_id,
                "teacherid"  => $teacherid,
            ],[
                "teacher_confirm_flag" => $teacher_confirm_flag,
                "teacher_confirm_time" =>time() ,
            ]);
            if ($ret!=1) {
                $this->t_seller_student_info->rollback();
                $msg="系统异常";
            }

        }
        $this->t_seller_student_info->commit();

        return $msg;
    }

    public function confirm_test_lesson_js() {
        $teacherid=$this->teacherid;
        $seller_student_id=$this->get_in_int_val("seller_student_id");
        $msg=$this->do_confirm_test_lesson($teacherid,$seller_student_id);
        return $this->output_succ(["msg"=>$msg]);
    }

    public function confirm_test_lesson() {
        $teacherid=$this->teacherid;
        $seller_student_id=$this->get_in_int_val("seller_student_id");
        $msg=$this->do_confirm_test_lesson($teacherid,$seller_student_id);
        return $this->pageView(__METHOD__,null,[
            "msg" => $msg
        ]);
    }

    public function test_lesson_list() {
        $teacherid=$this->teacherid;
        if ( \App\Helper\Utils::check_env_is_local() ) {
            $teacherid=-1;
        }
        $opt_type=$this->get_in_opt_type(0); //可抢单
        $page_num=$this->get_in_page_num();
        $ret_info=$this->t_test_lesson_assign_teacher->get_list_by_teacherid( $page_num,$teacherid,$opt_type);

        foreach($ret_info["list"] as &$item ) {
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,"st_class_time","_str","Y-m-d H:i");
            E\Esubject::set_item_value_str($item);
        }
        return $this->pageView(__METHOD__,$ret_info);
        //$this->t_test_lesson_assign_teacher->get
    }
}