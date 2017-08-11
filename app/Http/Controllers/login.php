<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use Illuminate\Support\Facades\Redis ;

use Illuminate\Support\Facades\Session;

class login extends Controller
{

    var $check_login_flag=false;
    function gen_account_role_menu( $menu, &$power_map ,&$url_power_map ) {

        $menu_str        = "";
        $item_count      = 0;
        $item_1          = "";
        $role_str        = "";
        $role_item_count = 0;

        $is_teaching_flag = 0;

        \App\Helper\Utils::logger("yuanshii22: ".json_encode($url_power_map));

        foreach ($menu as $item) {
            $item_name=$item["name"];

            \App\Helper\Utils::logger("list_show1: ".json_encode($item['list']));

            $tmp = $this->gen_account_role_one_item( $item, $power_map,$url_power_map);

            \App\Helper\Utils::logger("hhh33: ".json_encode($tmp));


            if($tmp) {
                \App\Helper\Utils::logger("panduian22: $item_name");

                $item_count++;
                if(is_array($tmp)) {
                    $item_1=$tmp[1];
                    // $menu_str.=$tmp[0];

                    // 修改
                    if ( substr($item_name,-3)== "部"  ) {
                        $is_teaching_flag = 1;
                        $role_str.=$tmp[0];
                    }else{
                        $role_str.=$tmp[0];
                    }

                }else{
                    $menu_str.=$tmp;
                }
            }else{
                \App\Helper\Utils::logger("name_jiaoxue78: $tmp, name: $item_name");

            }
        }

        //修改
        if($is_teaching_flag == 1){
            $menu_str.='<li class="treeview " > <a href="#"> <i class="fa fa-folder-o"></i> <span>教学管理事业部</span> <i class="fa fa-angle-left pull-right"></i> </a> <ul class="treeview-menu"> '.$role_str.'</ul> </li>';
            return $menu_str;
        }




        if ($item_count==1) {
            $menu_str=$item_1;
        }
        return $menu_str;
    }

    function  gen_account_role_one_item ($node,&$power_map,&$url_power_map ) {
        \App\Helper\Utils::logger("do1:".$node["name"]);

        if (isset($node["list"])) {
            \App\Helper\Utils::logger("if3333");

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
            // if ($add_count>0 && $item_1) {
            if ($add_count==1 && $item_1) {
                $sub_list_str.= $item_1;
            }else{
                $sub_list_str.= $sub_list_str_tmp;
            }

            if ($sub_list_str) {
                return  array('<li class="treeview " > <a href="#"> <i class="fa fa-folder-o "></i> <span>'.$node["name"].'</span> <i class="fa fa-angle-left pull-right"></i> </a> <ul class="treeview-menu"> '.$sub_list_str.'</ul> </li>', $sub_list_str);

            }else{
                return "";
            }

        }else{

            // \App\Helper\Utils::logger("uehbhd:".$node['name']);

            @$check_powerid = $url_power_map[$node["url"]] ;

            if (isset($power_map[$check_powerid ])) {
                //不再显示
                unset($power_map[$check_powerid ]);

                $icon=@$node["icon"];
                if (!$icon) {
                    $icon="fa-circle-o";
                }

                return '<li> <a href="'.$node["url"].'"><i class="fa '.$icon.'"></i><span>'.
                                       $node["name"].'</span></a></li>';
            }else{

                // \App\Helper\Utils::logger("do222:".$node["name"].":null-$check_powerid");
                return "";
            }
        }
    }

    function  gen_one_item ($node,$power_fix,$level,$power_map) {
        //\App\Helper\Utils::logger("do:".$node["name"]);

        $power_id= $power_fix*100+$node["power_id"];
        if (isset($node["list"])) {
            $sub_list_str="";
            $add_count=0 ;
            $item_1="" ;
            $sub_list_str_tmp="";
            foreach ($node["list"] as $item) {
                $tmp=$this->gen_one_item( $item, $power_id ,$level+1,$power_map);
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

            if ($sub_list_str) {
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


                return '<li> <a href="'.$node["url"].'"><i class="fa '.$icon.'"></i><span>'.
                                       $node["name"].'</span></a></li>';
            }else{

                //\App\Helper\Utils::logger("do:".$node["name"].":null--$power_id");
                return "";
            }
        }
    }


    private function  gen_menu($power_map,$menu,$start,$level){
        $menu_str        = "";
        $item_count      = 0;
        $item_1          = "";
        $role_str        = "";
        $role_item_count = 0;
        $is_jiaose = 0;
        // $role_str_jiaoxue = "";

        foreach ($menu as $item) {
            $item_name=$item["name"];
            \App\Helper\Utils::logger("XX:". substr($item_name,0,7));
            $tmp=$this->gen_one_item( $item, $start,$level,$power_map);
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

        \App\Helper\Utils::logger("menu_str_show: $menu_str");

        return $menu_str;
    }

    public function login_check_verify_code(){
        $account          = $this->get_in_str_val("account");
        $ip               = $this->get_in_client_ip();

        if (\App\Helper\Utils::check_env_is_release()
        ) {
            $need_verify_flag = $this->t_admin_users->check_need_verify($account,$ip);
        } else{
            $need_verify_flag = false;
        }

        return outputjson_success(array( "need_verify_flag"=>$need_verify_flag ));
    }

    public function reset_power($account) {
        $ret_permission = $this->t_manager_info->get_user_permission(array($account));

        $permission = array();
        foreach($ret_permission as $key => $value) {
            $permission[$value['account']] = $value['permission'];
        }

        $ret_row = $this->t_manager_info->get_info_by_account($account);

        $_SESSION['login_userid']    = $ret_row["uid"];
        $_SESSION['login_user_role'] = 1;
        $_SESSION['acc']             = $account;
        $_SESSION['adminid']         = $ret_row["uid"];
        $_SESSION['account_role']    = $ret_row["account_role"];
        $_SESSION['seller_level']    = $ret_row["seller_level"];
        $_SESSION['power_set_time']  = time(NULL);


        $_SESSION['permission'] = @$permission[$account];



        //power_list
        $power_list = $this->t_manager_info->get_permission_list($account);
        $arr        = array();
        foreach( $power_list as $item ){
            $arr[$item] = true;
        }
        $power_map=$arr;

        $url_power_map=\App\Config\url_power_map::get_config();
        $menu_html ="";

        $uid = $this->get_account_id();

        $permission = $this->t_manager_info->get_permission($uid);

        $per_arr = explode(',',$permission);

        $jiaoxue_part_arr = ['66','52','96','91','70','39','71','97','105','95','0'];

        $result = array_intersect($per_arr,$jiaoxue_part_arr);

        if(!empty($result)){
            $menu_html=$this->gen_account_role_menu( \App\Config\teaching_menu::get_config(), $arr,  $url_power_map  );
        }

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

        /*
        $env=\App\Helper\Utils::check_env_is_release();
        if($password==md5($account) && $env)
            return outputjson_error("密码不能和用户名相同，请重置密码！");
        */

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
        session($_SESSION) ;
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
        if (strcmp($ret_redis, $passwd) == 0) {
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

        $userid = $this->t_user_info->check_login_userid($account, $password);
        //dd($userid);
        if($userid>0){
            $teacherid = $userid;
        }else{
            $ret_dynamic = $this->login_with_dymanic_passwd($account, E\Erole::V_TEACHER , $password  );
            if ($ret_dynamic == false) {
                return $this->output_err("用户名密码出错");
            }
            $teacherid= $this->t_phone_to_user->get_teacherid($account);
        }
        //dd("success");
        $_SESSION['phone'] = $account;
        $_SESSION['acc']   = $account;
        $_SESSION['tid']   = $teacherid;
        $_SESSION['role']  = E\Erole::V_TEACHER;

        session($_SESSION) ;

        return $this->output_succ([
            //"permission" => $permission,
        ]);
    }

    public function login_other() {
        if(!$this->check_account_in_arr(["jim","adrian","seven", "james","jack","michael","ted"]) ) {
            return $this->output_err("没权限");
        }

        $login_adminid=$this->get_in_int_val("login_adminid");
        $ret_db = $this->t_admin_users->field_get_list($login_adminid,"*");

        $account=$ret_db["account"];
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

        return $this->pageView(__METHOD__);
    }
}
