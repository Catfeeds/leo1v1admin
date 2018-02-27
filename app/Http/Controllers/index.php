<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

use PdfToImage\Src\Exceptions\InvalidFormat;
use PdfToImage\Src\Exceptions\PageDoesNotExist;
use PdfToImage\Src\Exceptions\PdfDoesNotExist;
use PdfToImage\Src\Pdf;
// include_once(); Libs/PdfToImage/src
include(app_path("Libs/PdfToImage/src/Pdf.php"));
// include(app_path("Wx/Teacher/lanewechat_teacher.php"));


use \App\Enums as E;

class index extends Controller
{
    var $check_login_flag=false;

    public function jump_page() {
        $account_role=$this->t_manager_info->get_account_role( $this->get_account_id() );
        switch ( $account_role ) {
        case E\Eaccount_role::V_1 :
            header('Location: /main_page/assistant');
            break;
        case E\Eaccount_role::V_2 :
            header('Location: /main_page/seller');
            break;
        case E\Eaccount_role::V_3 :
            header('Location: /main_page/jw_teacher');
            break;

        default:
            if ( $this->get_account() =="jim"  ) {
                header('Location: /main_page/admin');
            }else{
                header('Location: /user_manage/all_users');
            }
            break;
        }
    }

    public function wx_teacher_index() {

        $jump_url=$this->get_in_str_val("jump_url");

        if (!$jump_url) {
            $jump_url="/wx_teacher_web/wage_summary";
        }

        if (session("login_user_role" )==2 && session("login_userid" ) >0 ) {
            \App\Helper\Utils::logger("wx_teacher_find111".$jump_url);
            header("Location: $jump_url");
            exit;
        }
        $openid=$this->get_in_str_val("openid");
        if (strpos(@$_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false  || $openid )  {
            $code=$this->get_in_str_val("code");
            if (!$openid) {
                /**  @var  \App\Helper\Wx $wx */

                $wx= new \App\Helper\Wx( \App\Helper\Config::get_teacher_wx_appid()  , \App\Helper\Config::get_teacher_wx_appsecret()  );
                global $_SERVER;
                if (!$code) {

                    $redirect_uri = urlencode("http://wx-teacher.leo1v1.com");
                    \App\Helper\Utils::logger("wx-teacer-login redirect_uri:$redirect_uri");


                    $wx->goto_wx_login( $redirect_uri );
                    return;
                }else{

                    \App\Helper\Utils::logger("wx teacher has code");
                    $token_info = $wx->get_token_from_code($code);
                    $openid     = @$token_info["openid"];
                    $to_url=urldecode(hex2bin($this->get_in_str_val("to_url")));
                    \App\Helper\Utils::logger("WX_to_url2-1:$to_url");


                    if (!$openid) {
                        return $this->output_err("微信code验证失败");
                    }else{

                    }
                }
            }


            if ($openid) {
                session(["wx_openid"=> $openid]);
                $teacherid=$this->t_teacher_info-> get_teacherid_by_openid ($openid);
                if ($teacherid) {
                    $wx_use_flag=$this->t_teacher_info->get_wx_use_flag($teacherid);
                    session([
                        "login_userid" => $teacherid,
                        "login_user_role" => 2,
                        "teacher_wx_use_flag" => $wx_use_flag,
                    ]);

                    \App\Helper\Utils::logger("set login_user_role:".session("login_user_role"));
                    \App\Helper\Utils::logger("set login_userid:".session("login_userid"));
                    \App\Helper\Utils::logger("jump222:".$jump_url);

                    header("Location: $jump_url");
                    return ;
                }else{ //登录界面
                    $to_url= "http://wx-teacher-web.leo1v1.com/login.html?wx_openid=".$openid;
                    header("Location: $to_url");
                    return ;
                }
            }

            return $this->output_err("微信验证失败") ;

        }else{
            return $this->output_err("请在微信中打开") ;
        }

    }

    public function index(){
        global $_SESSION;
        global $_SERVER;
        \App\Helper\Utils::logger("home_page host:".@$_SERVER["HTTP_HOST"]);

        if ( @$_SERVER["HTTP_HOST"] == "wx-teacher.leo1v1.com" ) {
            return $this->wx_teacher_index();
        } else if(@$_SERVER["HTTP_HOST"] == "teacher.leo1v1.com"){
            // return $this->teacher_index();
            header("Location: /login/teacher");
            exit;
        }

        $tq_token=$this->get_in_str_val("token");
        $tq_uin=$this->get_in_int_val("uin");
        session(["login_tquin"=>  $tq_uin ]);
        \App\Helper\Utils::logger("home_page session_acc:".session("acc"));

        if (session("acc")) {
            foreach (  \Illuminate\Support\Facades\Session::all() as $key =>  $value) {
                if ( is_string($key) ) {
                    $_SESSION[$key] =  $value;
                }
            }

            if (\App\Helper\Utils::check_env_is_testing() ) {
                //return redirect("/supervisor/monitor");
                return "/supervisor/monitor";
            }else{
                $this->jump_page();
            }
        }else{
            //微信浏览器
            if (strpos(@$_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false )  {
                \App\Helper\Utils::logger("home_page 1");
                $code=$this->get_in_str_val("code");

                /**  @var  wx \App\Helper\Wx */

                \App\Helper\Utils::logger("login_code 1");

                $wx= new \App\Helper\Wx();
                global $_SERVER;
                if (!$code) {
                    $to_url=$this->get_in_str_val("to_url");
                    $bin_to_url= @hex2bin($to_url );
                    if (   $bin_to_url ) {
                        $to_url=bin2hex( "http://admin.leo1v1.com" . $bin_to_url ) ;
                    }else{
                        $to_url=bin2hex( "http://admin.leo1v1.com" . $to_url ) ;
                    }

                    \App\Helper\Utils::logger("TO URL:$to_url ");

                    $redirect_url=urlencode("http://wx-parent.leo1v1.com?to_url=$to_url" );
                    \App\Helper\Utils::logger("home_page2 redirect_url:".$redirect_url);
                    $wx->goto_wx_login( $redirect_url );
                    return;
                }else{
                    \App\Helper\Utils::logger("home_page3");
                    if ( $_SERVER["HTTP_HOST"] == "wx-parent.leo1v1.com" ) {
                        $url="http://admin.leo1v1.com{$_SERVER["REQUEST_URI"]}";
                        header("Location: $url");
                        return ;
                    }


                    \App\Helper\Utils::logger("has code");
                    $token_info = $wx->get_token_from_code($code);
                    $openid     = @$token_info["openid"];
                    $to_url=urldecode(hex2bin($this->get_in_str_val("to_url")));
                    \App\Helper\Utils::logger("WX_to_url2-2:$to_url");


                    if (!$openid) {
                        dd("ERROR openid");
                        //$code=$this->set
                        /*
                          $openid=\App\Helper\Common::redis_get($code);
                          //for test jim
                          if (!$openid) {
                          $openid="sdfadfasdfadfadddffddddfffddd";
                          }
                        */
                    }else{
                        //\App\Helper\Common::redis_set($code,$openid);
                    }
                    session(["wx_openid" => $openid]);
                    $ret_row=$this->t_manager_info-> get_info_for_wx_openid($openid);
                    if ($ret_row) {
                        $ip      = $this->get_in_client_ip();
                        $account = $ret_row["account"];
                        $id      = $ret_row["uid"];
                        $this->t_login_log->add($account,$ip,1);
                        $_SESSION['acc']     = $account;
                        $_SESSION['adminid'] = $id;

                        $login      = new login();
                        $permission = $login->reset_power($account);
                        session($_SESSION) ;
                        $this->t_admin_users->set_last_ip( $account,$ip );
                        \App\Helper\Utils::logger("XXXLocation: $to_url");

                        return redirect($to_url );
                    }else{
                        $token=$token_info["access_token"];
                        $user_info=$wx->get_user_info_from_token($openid,$token);

                        dd("这是你第一次登录，请让管理员绑定你的账号，需要告诉管理员你的微信姓名［".$user_info["nickname"]."］");
                        //正常登录
                    }
                }
            }else if ( $tq_uin  && false )  { // tq

                $tq_url= "http://passport.sh.tq.cn/check.do?token=$tq_token&uin=$tq_uin";
                \App\Helper\Utils::logger("TQ:$tq_url");
                $ch = curl_init();

                //设置选项，包括URL
                curl_setopt($ch, CURLOPT_URL,$tq_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                //执行并获取HTML文档内容
                $tq_ret= curl_exec($ch);
                //释放curl句柄
                curl_close($ch);

                if ($tq_ret==1){
                    \App\Helper\Utils::logger("TQSUCC");
                    $ret_row=$this->t_manager_info->get_info_by_tquin($tq_uin);
                    if ($ret_row) {
                        //LOGIN
                        if ($ret_row["del_flag"] ) {
                            dd("已离职,不能登录" );
                        }

                        $ip      = $this->get_in_client_ip();
                        $account = $ret_row["account"];
                        $id      = $ret_row["uid"];

                        $this->t_login_log->add($account,$ip,1);
                        $_SESSION['acc']        = $account;
                        $_SESSION['adminid']    = $id;
                        $_SESSION['account_role']    =$ret_row["account_role"];

                        $login =new login();
                        $permission=  $login->reset_power($account);
                        session($_SESSION) ;
                        $this->t_admin_users->set_last_ip( $account,$ip );

                        $this->jump_page();
                    }else{
                        session(["login_tquin"=>  $tq_uin ]);
                        \App\Helper\Utils::logger("TQ LOGIN_tquin: $tq_uin");
                    }
                }
            }

            $passwd_login_flag=true;
            if (\App\Helper\Utils::check_env_is_release()){
                $passwd_login_flag=false;
            }

            return $this->view(__METHOD__,["passwd_login_flag"=> $passwd_login_flag]);
        }
    }


    public function publish() {

        global $g_request;
        /** @var $g_request Illuminate\Http\Request */
        $path=$g_request->path();
        $arr=explode("/", $path);
        $act="index";
        if (isset($arr[1]) ) {
            $act=$arr[1] ;
        }

        $reflectionObj = new \ReflectionClass( "App\\Http\\Controllers\\". $arr[0]);

        if ( $path !=  "index/publish") {
            return $reflectionObj->newInstanceArgs()->$act();
        }else{
            echo $path;
        }


        //$instance= new
        //$url="/".$arr[1]."/".$arr[2];

    }


    public function reset_name() {
        $list=$this->t_paper_info->get_list_for_test();
        foreach ($list as $item)  {
            $new_name=str_replace(["20", "上海市", "区" , "中考", "试卷"  ],"", $item["paper_name"]);

            $new_name=str_replace("黄浦","黄浦区",$new_name);
            $new_name=str_replace("徐汇","徐汇区",$new_name);
            $new_name=str_replace("长宁","长宁区",$new_name);
            $new_name=str_replace("静安","静安区",$new_name);
            $new_name=str_replace("普陀","普陀区",$new_name);
            $new_name=str_replace("虹口","虹口区",$new_name);
            $new_name=str_replace("杨浦","杨浦区",$new_name);
            $new_name=str_replace("闵行","闵行区",$new_name);
            $new_name=str_replace("宝山","宝山区",$new_name);
            $new_name=str_replace("嘉定","嘉定区",$new_name);
            $new_name=str_replace("浦东新","浦东新区",$new_name);
            $new_name=str_replace("金山","金山区",$new_name);
            $new_name=str_replace("松江","松江区",$new_name);
            $new_name=str_replace("青浦","青浦区",$new_name);
            $new_name=str_replace("奉贤","奉贤区",$new_name);
            $new_name=str_replace("闸北","闸北区",$new_name);


            echo  $item["paperid"]. "|" .$item["paper_name"]."|". "$new_name<br/>";

            if ($new_name!=$item["paper_name"]){
                $this->t_paper_info->field_update_list($item["paperid"],[
                    "paper_name" => $new_name
                ]);
            }
        }


    }

    public function get_admin_phone(){
        $account=$this->get_in_str_val("account");

        $id=$this->t_admin_users->get_id_by_account($account);
        $phone="";
        if ($id>0){
            $phone=$this->t_manager_info->get_phone($id);
        }

        return $this->output_succ(["phone"=>$phone]);
    }

    public function check_phone_code() {
        $phone      = $this->get_in_str_val("phone");
        $phone_code = $this->get_in_str_val("phone_code");
        $ret        = false;
        if ($phone) {
            if (session("phone_code")==$phone_code && session("phone")==$phone) {
                $ret=true;
            }
        }
        return $this->output_succ(["check_flag" =>$ret]);
    }

    public function send_phone_code(){
        $phone=$this->get_in_phone();

        $phone_index=session("phone_index");
        $phone_index+=1;
        $phone_code=\App\Helper\Common::gen_rand_code(4);
        session([
            "phone"       => $phone,
            "phone_code"  => $phone_code,
            "phone_index" => $phone_index,
        ]);

        /*
          模板名称: 通用验证
          模板ID: SMS_10671029
          *模板内容: 您的手机验证码为：${code} ，请尽快完成验证 编号为： ${index}
         */
        \App\Helper\Net::send_sms_taobao($phone,0, 10671029,[
                                             "code"  => $phone_code,
                                             "index" => $phone_index,
                                         ]);
        return $this->output_succ(["index" =>$phone_index ]);
    }

    public function set_passwd()
    {
        $account    = $this->get_in_str_val('account',"");
        $check_flag = $this->get_in_str_val("check_flag",'false');
        $passwd     = $this->get_in_str_val('passwd',"");

        if ($check_flag=='false')
            return outputjson_error('验证码不正确!');

        if($account == "" || $passwd == "" )
            return outputjson_error('密码不能为空');

        if($account==$passwd)
            return outputjson_error('密码不能为用户名');

        if(strlen($passwd)<6)
            return outputjson_error('密码不能少于6位！');

        $this->t_admin_users->update_password($account, $passwd);
        return outputJson(array('ret' => 0, 'info' => '修改成功！'));
    }
    public function tt () {

        dispatch( new \App\Jobs\send_error_mail(
            "xcwenn@qq.com","ADMIN ERR 111:tt" ,
            "tt".
            "<br>client_ip:1"
        ));
        dd($_SERVER );
        /*

        dispatch( new \App\Jobs\send_error_mail(
            "xcwenn@qq.com","ADMIN ERR 111:tt" ,
            "tt".
            "<br>client_ip:1"
        ));
        */

        //$this->t_manager_info->sync_kaoqin_re_upload_user_info();
        /*
        $this->t_manager_info->send_wx_todo_msg(
            "zore",
            "申请人:",
            "ss",
            "dsafa"
        );
        */
        /*
        \App\Helper\Utils::sms_common("15601830297",34740049,[
            "teacher_nick" => "xcdd",
            "lesson_time"  => "dfaf:111101",
            "reason"       => "学生原因",
        ]);
        */

                /*
        \App\Helper\Config::get_config("url","app");
        \App\Helper\Utils::logger("SEND FLY");
        $this->t_manager_info->send_wx_todo_msg("fly","xx","fadf","sdaf","");
        \App\Helper\Utils::logger("SEND JIM");
        $this->t_manager_info->send_wx_todo_msg("jim","xx","fadf","sdaf","");
        dd($_SERVER);
                */
        /*
        $wx=new \App\Helper\Wx();
        $template_id="1600puebtp9CfcIg41Oz9VHu6iRXHAJ8VpHKPYvZXT0";
        $ret= $this->t_manager_info->send_template_msg("jim",$template_id,[
            "first" => "ddfffffff",
            "keyword1" => "keyword11333",
            "keyword2" => "keyword12w2",
            "keyword3" => "keywo33",
            "remark" => "reddff",
        ]);
        dd($ret);
        */
    }

    public function pdf_to_img(){
        $pathToPdf = public_path().'/a.pdf';
        // $pathToPdf = "/home/james/work/admin/public/a.pdf";
        // /home/james/work/admin/public
        $pdf = new Pdf($pathToPdf);
        // $pdf = new Pdf($pathToPdf);
        // dd(1);
        $pdfToImage = public_path().'/wximg/1.jpg';
        $pdf->saveImage($pdfToImage);

        // dd( phpinfo());

        // dd(file_exists($pathToPdf));

        // dispatch( new \App\Jobs\send_error_mail());


        $PDF = public_path().'/cc.pdf';
        $Path = public_path().'/wximg';
        //$this->pdf2png($PDF,$Path);
    }


    function pdf2png(){
        // if(!extension_loaded('imagick')){
        //     returnfalse;
        // }
        // if(!file_exists($PDF)){
        //     returnfalse;
        // }
        $Path = public_path().'/wximg';
        $PDF = public_path().'/2.pdf';
        $IM =new \imagick();
        $IM->setResolution(100,100);
        $IM->setCompressionQuality(100);
        //$IM->set desity
        $IM->readImage($PDF);
        foreach($IM as $key => $Var){
            $Var->setImageFormat('png');
            $Filename = $Path."/l_t_pdf_$lessonid_ $key.png" ;
            if($Var->writeImage($Filename)==true){
                $Return[]= $Filename;
            }
        }
        return $Return;
    }


}
