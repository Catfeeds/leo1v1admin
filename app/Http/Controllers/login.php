<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use Illuminate\Support\Facades\Redis ;

use Illuminate\Support\Facades\Session;

class login extends Controller
{

    var $check_login_flag=false;
    function gen_account_role_menu( $menu, &$power_map ,&$url_power_map ,$check_item_count=true ,$admin_domain_type = E\Eadmin_domain_type::V_ADMIN_1V1) {

        $menu_str        = "";
        $item_count      = 0;
        $item_1          = "";
        $role_str        = "";
        $role_item_count = 0;

        foreach ($menu as $item) {
            $item_name=$item["name"];

            $tmp = $this->gen_account_role_one_item( $item, $power_map,$url_power_map ,
                                                     $check_item_count, $admin_domain_type );

            if($tmp) {
                $item_count++;
                if(is_array($tmp)) {
                    $item_1=$tmp[1];
                     $menu_str.=$tmp[0];
                }else{
                    $menu_str.=$tmp;
                }
            }else{

            }
        }

        if ( $check_item_count && $item_count==1) {
            $menu_str=$item_1;
        }
        return $menu_str;
    }

    function  gen_account_role_one_item ($node,&$power_map,&$url_power_map ,$check_item_count=true, $admin_domain_type=  E\Eadmin_domain_type::V_ADMIN_1V1 ) {
        if (isset($node["list"])) {

            // if($node['name'] == '角色-教研'){
            //     unset($node);
            // }

            $sub_list_str="";
            $add_count=0 ;
            $item_1="" ;
            $sub_list_str_tmp="";
            foreach ($node["list"] as $item) {
                $tmp=$this->gen_account_role_one_item( $item, $power_map, $url_power_map);
                if($tmp) {
                    $add_count++;
                    if ( is_array( $tmp)  ) {
                        $sub_list_str_tmp.= $tmp[0];
                        $item_1=$tmp[1];
                    }else{
                        $sub_list_str_tmp.= $tmp;
                    }
                }
            }

            if ($add_count==1 && $item_1) {
                $sub_list_str.= $item_1;
            }else{
                $sub_list_str.= $sub_list_str_tmp;
            }

            if ($sub_list_str || $check_item_count==false) {
                $icon= @$node["icon"];
                if (!$icon)  {
                    $icon="fa-folder-o";
                }

                return  array('<li class="treeview " > <a href="#"> <i class="fa '.$icon.'  "></i> <span>'.$node["name"].'</span> <i class="fa fa-angle-left pull-right"></i> </a> <ul class="treeview-menu"> '.$sub_list_str.'</ul> </li>', $sub_list_str);

            }else{

                return "";
            }

        }else{
            $url_split_arr = preg_split( "/\//", $node["url"]) ;
            $check_url="/". @$url_split_arr[count($url_split_arr)-2] . "/". @$url_split_arr[count($url_split_arr)-1] ;

            @$check_powerid = $url_power_map[ $check_url] ;
            //\App\Helper\Utils::logger(" check_url: $check_url, ". $node["url"]  );
            if (isset( $url_power_map[ $check_url ]) && isset($power_map[$check_powerid ])) {
                //不再显示
                unset($power_map[$check_powerid ]);

                $icon=@$node["icon"];
                if (!$icon) {
                    $icon="fa-circle-o";
                }

                if ( substr($node["url"], 0, 4) =="http"  ) {
                    $url_base= "";
                }else{
                    $url_base= \App\Helper\Config::get_admin_domain_url( $this->get_menu_node_admin_domain_type($node, $admin_domain_type) );
                }
                return '<li> <a href="'.$url_base.$node["url"].'"><i class="fa '.$icon.'"></i><span>'.
                                       $node["name"].'</span></a></li>';
            }else{
                return "";
            }
        }
    }
    public function get_menu_node_admin_domain_type( $node , $def ) {
        if (isset( $node[ "domain_type"]  ) ) {
            return  $node[ "domain_type"] ;
        }else{
            return $def;
        }
    }


    function  gen_one_item ($node,$power_fix,$level,$power_map,$admin_domain_type) {
        $power_id= $power_fix*100+$node["power_id"];
        if (isset($node["list"])) {
            $sub_list_str="";
            $add_count=0 ;
            $item_1="" ;
            $sub_list_str_tmp="";
            foreach ($node["list"] as $item) {
                $tmp=$this->gen_one_item( $item, $power_id ,$level+1,$power_map,$admin_domain_type);
                if($tmp) {
                    $add_count++;
                    if ( is_array( $tmp)  ) {
                        $sub_list_str_tmp.= $tmp[0];
                        $item_1=$tmp[1];
                    }else{
                        $sub_list_str_tmp.= $tmp;
                    }
                }
            }
            if ($add_count==1 && $item_1) {
                $sub_list_str.= $item_1;
            }else{
                $sub_list_str.= $sub_list_str_tmp;
            }

            if($sub_list_str){
                return  array('<li class="treeview " > <a href="#"> <i class="fa fa-folder-o "></i> <span>'.$node["name"].'</span> <i class="fa fa-angle-left pull-right"></i> </a> <ul class="treeview-menu"> '.$sub_list_str.'</ul> </li>', $sub_list_str);

            }else{
                return "";
            }

        }else{
            switch (  $level) {
            case 1 :
                $power_id*=10000;
                break;
            case 2 :
                $power_id*=100;
                break;
            default:
                break;
            }


            if (isset($power_map[ $power_id])) {
                //\App\Helper\Utils::logger("do:".$node["name"].":yes");
                $icon=@$node["icon"];
                if (!$icon) {
                    $icon="fa-circle-o";
                }
                $url_base= \App\Helper\Config::get_admin_domain_url( $this->get_menu_node_admin_domain_type($node, $admin_domain_type) );


                return '<li> <a href="'.$url_base.$node["url"].'"><i class="fa '.$icon.'"></i><span>'.
                                       $node["name"].'</span></a></li>';
            }else{
                //\App\Helper\Utils::logger("do:".$node["name"].":null--$power_id");
                return "";
            }
        }
    }

    private function  gen_menu($power_map,$menu,$start,$level, $admin_domain_type = E\Eadmin_domain_type::V_ADMIN_1V1 ){
        $menu_str        = "";
        $item_count      = 0;
        $item_1          = "";
        $role_str        = "";
        $role_item_count = 0;
        $is_jiaose = 0;

        foreach ($menu as $item) {
            $item_name=$item["name"];
            $tmp=$this->gen_one_item( $item, $start,$level,$power_map, $admin_domain_type);
            if($tmp) {
                $item_count++;
                if(is_array($tmp)) {
                    $item_1=$tmp[1];
                    if ( substr($item_name,0,7)== "角色-"  ) {
                        $role_item_count++;
                        $role_str.=$tmp[0];
                    }else{
                        $menu_str.=$tmp[0];
                    }

                }else{
                    $menu_str.=$tmp;
                }
            }
        }

        if ($item_count==1) {
            $menu_str=$item_1;
        }else{ //角色
            if ($role_item_count<3) {
                $menu_str=$role_str.$menu_str;
            }else{
                $menu_str.='<li class="treeview " > <a href="#"> <i class="fa fa-folder-o"></i> <span>角色列表</span> <i class="fa fa-angle-left pull-right"></i> </a> <ul class="treeview-menu"> '.$role_str.'</ul> </li>';
            }
        }

        return $menu_str;
    }

    public function login_check_verify_code(){
        $account = $this->get_in_str_val("account");
        $ip      = $this->get_in_client_ip();

        if (\App\Helper\Utils::check_env_is_release()
        ) {
            $need_verify_flag = $this->t_admin_users->check_need_verify($account,$ip);
        } else{
            $need_verify_flag = false;
        }

        return outputjson_success(array( "need_verify_flag"=>$need_verify_flag ));
    }

    public function reset_power($account) {
        \App\Helper\Utils::logger("loginx4");

        $ret_permission = $this->t_manager_info->get_user_permission(array($account));
        $permission = array();
        foreach($ret_permission as $key => $value) {
            $permission[$value['account']] = $value['permission'];
        }

        $ret_row = $this->t_manager_info->get_info_by_account($account);

        //判断是否为系统分配 销售 且现在例子已被释放
        if($ret_row['account_role'] == 2 && $ret_row['seller_student_assign_type'] == 1){
            list($start_time, $end_time)=$this->task->get_in_date_range_day(0);
            $system_assign_count = $this->t_seller_student_system_assign_log->get_cc_assign_count($ret_row['uid'],$start_time, $end_time);
            if(!$system_assign_count){
                $system_assign = new \App\Console\Commands\seller_student_system_assign();
                $system_assign->do_handle();
            }
        }

        $_SESSION['login_userid']    = $ret_row["uid"];
        $_SESSION['login_user_role'] = 1;
        $_SESSION['acc']             = $account;
        $_SESSION['adminid']         = $ret_row["uid"];
        $_SESSION['account_role']    = $ret_row["account_role"];
        $_SESSION['seller_level']    = $ret_row["seller_level"];
        $_SESSION['face_pic']        = isset($ret_row["face_pic"])?$ret_row["face_pic"]:'';
        $_SESSION['power_set_time']  = time(NULL);

        $_SESSION['permission'] = @$permission[$account];
        //dd($permission[$account]);
        $url_input_define = $this->t_url_input_define->url_input_define_by_gid(@$permission[$account]);
        $_SESSION['url_input_define'] = json_encode($url_input_define);

        $url_desc_power = $this->t_url_desc_power->url_desc_power_by_gid(@$permission[$account]);
        $_SESSION['url_desc_power'] = json_encode($url_desc_power);

        $menu_config=preg_split("/,/", $ret_row["menu_config"] );

        //power_list
        $power_list = $this->t_manager_info->get_permission_list($account);
        $arr        = array();
        foreach( $power_list as $item ){
            $arr[$item] = true;
        }
        $power_map=$arr;

        $url_power_map=\App\Config\url_power_map::get_config();

        $menu_html ="";

        $uid = $ret_row["uid"];
        //收藏列表
        $self_menu_config=$this->t_admin_self_menu->get_menu_config($uid);

        $tmp_arr=$arr;
        $tmp_url_power_map= $url_power_map ;
        $menu_html.=$this->gen_account_role_menu( $self_menu_config , $tmp_arr,  $tmp_url_power_map ,false );

        $main_department = $this->t_manager_info->get_main_department($uid);

        if( in_array( E\Emain_department::V_2, $menu_config ) || $main_department == 2 ){ // 教学管理事业部
            $menu_html.=$this->gen_account_role_menu( \App\Config\teaching_menu::get_config(), $arr,  $url_power_map ,  false);
        }
        // if (\App\Helper\Utils::check_env_is_local() ) {
        if( in_array( E\Emain_department::V_1, $menu_config )  ||  $ret_row["account_role"] == 2){ // 销售部
            $menu_html.=$this->gen_account_role_menu( \App\Config\seller_menu::get_config(), $arr,  $url_power_map ,  false);
        }
        \App\Helper\Utils::logger("2 menu_html strlen ".strlen( "$menu_html") );

        //小班课
        $class_menu_html = $this->gen_menu( $arr, \App\ClassMenu\menu::get_config() ,3,1, E\Eadmin_domain_type::V_ADMIN_CLASS);
        $menu_html.=$class_menu_html;

        $menu      = \App\Helper\Config::get_menu();
        $menu_html .= $this->gen_menu( $arr,$menu,1,1);

        $stu_menu = \App\Helper\Config::get_stu_menu();
        $tea_menu = \App\Helper\Config::get_tea_menu();

        $stu_menu_html = $this->gen_menu( $arr,$stu_menu,201,2);
        $tea_menu_html = $this->gen_menu( $arr,$tea_menu,202,2);

        $_SESSION['menu_html']     = $menu_html;
        $_SESSION['stu_menu_html'] = $stu_menu_html;
        $_SESSION['tea_menu_html'] = $tea_menu_html;
        $_SESSION['power_list']    = json_encode($power_map);
        //dd($_SESSION);
        session($_SESSION) ;

        return @$permission[$account];
    }

    public function login()
    {
        $account  = strtolower(trim($this->get_in_str_val("account")));
        $password = $this->get_in_str_val('password');
        $seccode  = $this->get_in_str_val('seccode') ;
        $ip       = $this->get_in_client_ip();

        if (\App\Helper\Utils::check_env_is_release()) {
            $need_verify_flag = $this->t_admin_users->check_need_verify($account,$ip);
        }else{
            $need_verify_flag = false;
        }

        if ($need_verify_flag){
            if (  empty($seccode) || $seccode !== session('verify')) {
                return outputJson_error( E\Eerror::V_WRONG_VERIFY_CODE ,
                                         array( 'code' =>  session('verify')));
            }
        }

        $password = md5($password."#Aaron");
        $ret_db   = $this->t_admin_users->user_login($account, $password);

        if (!$ret_db ) {
            $this->t_login_log->add($account,$ip,0);
            return outputjson_error( E\Eerror::V_WRONG_ACCOUNT_PASSWD );
        }

        if (empty($ret_db['id']) || $ret_db['id'] == 0) {
            $this->t_login_log->add($account,$ip,0);
            return outputjson_error( E\Eerror::V_WRONG_ACCOUNT_PASSWD );
        }

        $this->t_login_log->add($account,$ip,1);
        $_SESSION['acc']          = $account;
        $_SESSION['adminid']      = $ret_db['id'];
        $_SESSION['account_role'] = $this->t_manager_info->get_account_role($ret_db['id']);

        if (session("wx_openid")) {
            $this->t_manager_info->field_update_list($ret_db["id"],[
                "wx_openid" =>  session("wx_openid")
            ]);
        }

        $permission = $this->reset_power($account);
        session($_SESSION);
        $this->t_admin_users->set_last_ip( $account,$ip );

        return $this->output_succ( array(
            'permission' => $permission,
        ));
    }

    private function login_with_dymanic_passwd($phone, $role, $passwd)
    {
        Redis::select(10);
        $key = $phone."_".$role;
        $ret_redis=Redis::get($key);
        if(strcmp($ret_redis,$passwd) == 0) {
            return true;
        }
        \App\Helper\Utils::logger("[$key]ret_redis:$ret_redis");
        return false;
    }

    public function login_teacher()
    {
        global $_SESSION;
        $account  = strtolower(trim($this->get_in_str_val("account")));
        $password = $this->get_in_str_val('password');
        $seccode  = $this->get_in_str_val('seccode') ;
        $ip       = $this->get_in_client_ip();

        if (  empty($seccode) || $seccode !== session('verify')) {
            return $this->output_err( E\Eerror::V_WRONG_VERIFY_CODE );
        }

        $userid = $this->t_user_info->check_login_userid($account, $password, E\Erole::V_TEACHER);
        if($userid>0){
            $teacherid = $userid;
        }else{
            $ret_dynamic = $this->login_with_dymanic_passwd($account, E\Erole::V_TEACHER , $password  );
            if ($ret_dynamic == false) {
                return $this->output_err("用户名密码出错");
            }
            $teacherid = $this->t_phone_to_user->get_teacherid($account);
        }
        $tea_item = $this->t_teacher_info->field_get_list($teacherid,"nick,face");

        $sess['tid']  = $teacherid;
        $sess["acc"]  = $teacherid;
        $sess['nick'] = $tea_item["nick"] ;
        $sess['face'] = $tea_item["face"] ;
        $sess['role'] = E\Erole::V_TEACHER;
        session($sess);
        return $this->output_succ();
    }

    public function login_other() {
        if(!$this->check_account_in_arr(["jim","adrian","seven","ricky", "james","jack","michael","ted","夏宏东",'tom',"boby","sam","孙瞿","顾培根","alan",'abner']) ) {
            return $this->output_err("没权限");
        }
        
        $login_adminid=$this->get_in_int_val("login_adminid");
        $ret_db = $this->t_admin_users->field_get_list($login_adminid,"*");

        $account=$ret_db["account"];

        $operate_account = $this->get_account();
        $operate_id = $this->get_account_id();

        $this->t_user_log->row_insert([
            "add_time" => time(),
            "adminid"  => $this->get_account_id(),
            "msg"      => "登录其它账号: [被登录的账号:$account,操作人账号:$operate_account,操作人id:$operate_id]",
            "user_log_type" => E\Euser_log_type::V_5, //登录其他账户
        ]);

        $_SESSION = array();
        Session::clear();

        $_SESSION['acc']          = $account;
        $_SESSION['adminid']      = $ret_db['id'];
        $_SESSION['account_role'] = $this->t_manager_info->get_account_role( $ret_db['id']);

        $permission = $this->reset_power($account);
        session($_SESSION) ;

        return $this->output_succ();
    }

    public function logout()
    {

        global $_SESSION;
        $_SESSION = array();
        Session::clear();

        if (strpos(@$_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false )  {
            //WX 退出
            \App\Helper\Utils::logger("WX_LOGOUT");
            //return outputjson_success();

        }else{
            return outputjson_success();
        }

    }
    public function logout_teacher()
    {
        global $_SESSION;
        $_SESSION = array();
        Session::clear();
        if (strpos(@$_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false )  {
            //WX 退出
            //\App\Helper\Utils::logger("WX_LOGOUT");
            return outputjson_success();

        }else{
            return outputjson_success();
        }

    }

    public function get_verify_code()
    {
        return \App\Helper\Image::buildImageVerify(4, 1, false, 'png', 91, 34);
    }

    public function register(){
        $phone  = $this->get_in_str_val("telphone");
        $passwd = $this->get_in_str_val("passwd");
        $grade  = $this->get_in_int_val("grade");

        if(!preg_match("/^1\d{10}$/",$phone)){
            return outputJson(array('ret' => -1, 'info' => "请输入规范的手机号!"));
        }

        $passwd = md5($passwd);
        $reg_channel="后台添加";
        $ip=0;
        $region=0;

        $ret=$this->t_phone_to_user->get_userid_by_phone($phone,E\Erole::V_STUDENT);
        if($ret>0){
            return $this->output_err("用户已存在!");
        }

        $userid= $this->t_user_info->user_reg($passwd,$reg_channel,ip2long($ip));
        if(!$userid){
            return $this->output_err("用户注册失败!");
        }

        $ret = $this->t_phone_to_user->add($phone,E\Erole::V_STUDENT,$userid);
        if(!$ret){
            return $this->output_err("添加用户角色表失败,用户id".$userid);
        }

        $ret = $this->t_student_info->add_student($userid,$grade,$phone,$nick,$region);
        if(!$ret){
            return $this->output_err("添加学生表失败,用户id".$userid);
        }

        return $this->output_succ();
    }

    public function teacher() {
        global $_SESSION;
        $download = $this->get_in_str_val('download',-1);
        return $this->pageView(__METHOD__,[],['downflag' => $download]);
    }

    //@desn:优学优享团长登录[显示]
    public function agent(){
        global $_SESSION;
        $download = $this->get_in_str_val('download',-1);
        return $this->pageView(__METHOD__,[],['downflag' => $download]);
    }
    //@desn:优学优享团登录
    public function agent_login(){
        global $_SESSION;
        $phone  = strtolower(trim($this->get_in_str_val("phone")));
        $password = $this->get_in_str_val('password');
        $seccode  = $this->get_in_str_val('seccode') ;
        // $remember  = $this->get_in_str_val('remember') ;
        $ip       = $this->get_in_client_ip();


        if (  empty($seccode) || $seccode !== session('verify')) {
            return $this->output_err( E\Eerror::V_WRONG_VERIFY_CODE );
        }

        $userid = $this->t_agent->check_login_userid($phone, $password);
        //dd($userid);
        if($userid>0){
            $agentid = $userid;
        }else{
            $ret_dynamic = $this->login_with_dymanic_passwd($phone, E\Erole::V_TEACHER , $password  );
            if ($ret_dynamic == false) {
                return $this->output_err("用户名密码出错");
            }
            $agentid= $this->t_agent->get_agentid($phone);
        }
        //dd("success");
        $tea_item= $this->t_agent->field_get_list($agentid,"nickname,headimgurl ");

        $sess['aid']  = $agentid;
        $sess["acc"]  = $agentid;
        $sess['nickname'] = $tea_item["nickname"] ;
        $sess['headimgurl'] = $tea_item["headimgurl"] ;
        //$sess['role'] = E\Erole::V_TEACHER;

        session($sess);

        // if ( $remember ) {
        //     $sessionId   = session()->getId();
        //     $sessionName = session()->getName();
        //     setcookie($sessionName, $sessionId, time()+3600*24*7);//有效期7天
        // }
        return $this->output_succ();
    }

    public function down_leo_file(){
        $filepath = './理优讲义模板.zip';
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($filepath));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit();
    }

}
