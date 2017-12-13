<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Enums as E;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session ;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class wx_login extends Controller
{
    var $check_login_flag=false;

    public function login() {
        $code       = $this->get_in_str_val("code") ;
        $admin_code = $this->get_in_str_val("admin_code") ;
        //验证

        /**  @var   \App\Helper\Wx  $wx  */
        $wx = new \App\Helper\Wx();
        if (!$code) {
            $redirect_url=urlencode("http://wx-parent.leo1v1.com/wx_login/login?admin_code=$admin_code" );
            $wx->goto_wx_login( $redirect_url );
            return;
        }else{
            //得到user_info
            $token_info = $wx->get_token_from_code($code);

            \App\Helper\Utils::logger("token_info:". json_encode($token_info) );
            $openid=@$token_info["openid"];
            if ($openid) {
                $token=$token_info["access_token"];
                $user_info=$wx->get_user_info_from_token($openid,$token);

                $openid=$user_info["openid"];
                $admin_row=$this->t_manager_info->get_info_for_wx_openid($openid);


                if ($admin_row) {
                    \App\Helper\Utils::logger("admin_code:$admin_code");
                    if ($admin_row["del_flag"]==0 ){
                        $info=\App\Helper\Common::redis_get_json($admin_code);
                        if($info){
                            //check tquin
                            $login_tquin= session( "login_tquin" );
                            $account=$admin_row["account"];
                            if ( $login_tquin && $admin_row["tquin"] !=$login_tquin)  {
                                $message =" 出错,  你不是 [$account],不能登录!";
                            }else{
                                $info["check_time"] =time(NULL);
                                $info["account"] =$account;
                                $info["openid"] =$openid;
                                \App\Helper\Common::redis_set_json($admin_code,$info);
                                file_get_contents(\App\Helper\Config::get_monitor_new_url() .":9501/noti_user_login_key?user_login_key=$admin_code");
                                $message ="验证完成[$account]!";
                            }

                        }else{
                            $message= "出错：二维码不对!";

                        }

                    }else{
                        $message= "出错：[".$account ."]账号已注销:<";
                    }



                }else{

                    \App\Helper\Utils::logger("wx_openid_no_find" );
                    $message="这是你第一次登录，请到公司前台 绑定你的账号，需要告诉管理员你的微信姓名［".$user_info["nickname"]."］ 和你的后台账号 . " ;
                }
                return $this->pageView(__METHOD__,[],[
                    "message"=> $message,
                ]);

            }else{
                $message="请重新打开admin.leo1v1.com页面, 微信重新扫一扫 , 拨打电话联系[jim]:15601830297" ;
                return $this->pageView(__METHOD__,[],[
                    "message"=> $message,
                ]);
            }
        }
    }

    public function weixin() {

    }

    public function init() {
        $admin_code = $this->get_in_str_val("admin_code") ;
        $account    = $this->get_in_str_val("account") ;
        $url= $this->get_in_str_val("url") ;
        $info = [
            "admin_code"  => $admin_code,
            "add_time"    => time(NULL),
            "check_time"  => 0,
            "account"     => $account,
            "login_tquin" => session("login_tquin"),
            "openid"      => "",
        ];

        $url="http://admin.leo1v1.com/wx_login/login?admin_code=".$admin_code;
        if ($account) { //通知wx
            $ret=$this->t_manager_info->send_wx_todo_msg($account,"后台系统","后台系统登陆请求","",$url );
            \App\Helper\Utils::logger("XX account xjceshi:$account:$ret");

            if (!$ret) {
                return $this->output_err("用户不存在,或未绑定微信,或没有关注[理优教育在线学习APP]");
            }
        }
        \App\Helper\Common::redis_set_json($admin_code,  $info);
        $wx= new \App\Helper\Wx ();
        $redirect_url=urlencode("http://wx-parent.leo1v1.com/wx_login/login?admin_code=$admin_code" );
        $wx_url=$wx->get_wx_login_url($redirect_url);

        return $this->output_succ(["wx_url"=>$wx_url]);
    }

    public function check() {
        $admin_code = $this->get_in_str_val("admin_code") ;
        $info       = \App\Helper\Common::redis_get_json($admin_code);
        $now        = time(NULL);

        if ( $now - @$info["check_time"] <10   ) {
            $openid=$info["openid"];
            $ret_row=$this->t_manager_info-> get_info_for_wx_openid($openid);
            \App\Helper\Common::redis_set_json($admin_code,[]);

            //LOGIN
            $ip      = $this->get_in_client_ip();
            $account = $ret_row["account"];
            $id      = $ret_row["uid"];
            //更新tquin
            $tquin = session("login_tquin");
            \App\Helper\Utils::logger(" WX TQ login_tquin: $tquin");
            /*
            if ($tquin) {
                $old_info=$this->t_manager_info->get_info_by_tquin($tquin);
                if ($old_info) {
                    $this->t_manager_info->field_update_list($old_info["id"],[
                        "tquin" => NULL,
                    ]);
                }

                $call_phone_type = $this->t_manager_info->get_call_phone_type($id);
                if ( $call_phone_type== E\Ecall_phone_type::V_TQ ) {//tq
                    $this->t_manager_info->field_update_list($id,[
                        "tquin" => $tquin,
                    ]);
                }
            }
            */

            $this->t_login_log->add($account,$ip,1);
            $_SESSION['acc']        = $account;

            $login =new login();
            $permission=  $login->reset_power($account);
            session($_SESSION) ;
            $this->t_admin_users->set_last_ip( $account,$ip );

            return $this->output_succ(["flag"=>true]);
        }else{
            return $this->output_succ(["flag"=>false]);
        }
    }

}
