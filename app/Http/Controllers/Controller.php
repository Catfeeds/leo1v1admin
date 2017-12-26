<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log ;
use \App\Enums as E;

//use Illuminate\Support\Facades\Session;

require_once( app_path() ."/Helper/functions.php"  );
/**
 * @property  \App\Models\t_lesson_info_b2       $t_lesson_info_b2
 * @property  \App\Models\t_lesson_info_b3       $t_lesson_info_b3
 */
class Controller extends ControllerEx
{
    var $last_in_values   = array();
    var $last_in_types    = array();
    var $check_login_flag = true;
    var $switch_tongji_database_flag = false;

    use ViewDeal;
    use InputDeal;

    function __construct()  {
        if ($this->check_login_flag ) {
            $this->check_login();
        }
        $this->setUpTraits();
    }

    // 用于 慢查询 的 域名, 免得 admin 返回504
    public function check_and_switch_tongji_domain() {
        if ( \App\Helper\Utils::check_env_is_release() ){
            $server_name= $_SERVER["SERVER_NAME"];
            if ($server_name != "admin-tongji.leo1v1.com"){
                header('Location: http://admin-tongji.leo1v1.com/'. $_SERVER["REQUEST_URI"]  );
                exit;
            }
        }
    }

    protected function switch_tongji_database( $flag = true) {
        $this->switch_tongji_database_flag = $flag;
    }

    protected function setUpTraits()
    {
        $uses = array_flip(class_uses_recursive(get_class($this)));

        if (isset($uses[CacheNick::class])) {
            $this->CacheNickInit();
        }
    }

    public function __get( $name ){
        if (substr($name ,0,2  ) == "t_" || $name=="users") {
            $reflectionObj = new \ReflectionClass( "App\\Models\\$name");
            $this->$name= $reflectionObj->newInstanceArgs();
            if ($this->switch_tongji_database_flag){
                $this->$name->switch_tongji_database();
            }
            return $this->$name;
        }else if ($name == "account" ){
            return $this->get_account();
        }else{
            throw new \Exception() ;
        }
    }

    public function __call($method,$arg )  {
        if ( preg_match("/^get_in_e_(.*)$/",$method,$ret_arr)) {
            $def_value=0;
            $field_name="";
            if (isset($arg[0] )) {
                $def_value=$arg[0];
            }
            if (isset($arg[1] )) {
                $field_name=$arg[1];
            }
            $class_name= "\\App\\Enums\\E{$ret_arr[1]}";
            return $this->get_in_enum_val($class_name ,$def_value ,$field_name);
        }else if ( preg_match("/^get_in_el_(.*)$/",$method,$ret_arr)) {
            $def_value=-1;
            $field_name="";
            if (isset($arg[0] )) {
                $def_value=$arg[0];
            }
            if (isset($arg[1] )) {
                $field_name=$arg[1];
            }
            $class_name= "\\App\\Enums\\E{$ret_arr[1]}";
            return $this->get_in_enum_list ( $class_name, $def_value ,$field_name);
        }

        throw new \Exception("$method  no find ");
    }


    function check_login() {
        if (!session("acc")){
            Log::debug(" DO: Location: / ");
            if (!\App\Helper\Utils::check_env_is_test()) {
                \App\Helper\Utils::logger("GOTO: " .$_SERVER["REQUEST_URI"] );

                if ($this->get_in_str_val("callback"))  {
                    echo $this->output_err( 1005 );
                    exit;

                }else{
                    header('Location: /?to_url='.  $_SERVER["REQUEST_URI"]  );
                    exit;
                }
            }else{

            }
        }
    }


    public function check_account_in_arr($arr ) {
        return in_array(session("acc"), $arr ) ;
    }

    function get_account(){
        return  session("acc");
    }
    function get_account_id(){
        return  session("adminid");
    }

    public function get_login_teacher() {
        return session("tid");
    }

    public function get_login_agent() {
        return session("aid");
    }

    function get_wx_teacherid(){
        return session("login_userid");
    }

    function get_wx_role(){
        return session("login_user_role");
    }

    function get_wx_parentid(){
        return  session("parentid");
    }

    static public function check_power($powerid){
        $power_list = json_decode(session("power_list"),true);
        return @$power_list[$powerid];
    }

    static public function check_user_and_power_do_exit($powerid){
        if (!static::check_power($powerid) ){
            return $this->view_with_header_info ( "common.without-power", [],[
                "_ctr"          => "xx",
                "_act"          => "xx",
                "js_values_str" => "",
            ] );
        }else{
            return false;
        }
    }

    public function get_seller_adminid_and_branch(){
        $adminid      = $this->get_account_id();
        $groupid      = $this->t_admin_group_user->get_groupid_value($adminid);
        $adminid_list = [];
        //超权限人员账号集合
        $super_id = [60,186,188,303,323,349];
        //leowang/jim 暂时设定可以看全部
        if ($this->check_account_in_arr(["jim","leowang", "fly"])  ) {
            return $adminid_list;
        }

        if(empty($groupid)){

        }else{
            $master_adminid = $this->t_admin_group_name->get_master_adminid($groupid );
            $up_groupid = $this->t_admin_group_name->get_up_groupid($groupid );
            $main_type = $this->t_admin_group_name->get_main_type($groupid );
            $main_master_adminid = $this->t_admin_main_group_name->get_master_adminid($up_groupid );
            if($adminid != $master_adminid && $adminid != $main_master_adminid){
                $adminid_list[] = $adminid;
            }else if($adminid == $main_master_adminid){
                $list = $this->t_admin_group_name->get_adminid_list_by_up_groupid($up_groupid);
                foreach($list as $item){
                    $adminid_list[]= $item['adminid'];
                }
            }else{
                $adminid_list = $this->t_admin_group_user->get_userid_arr($groupid);
            }

        }
        return $adminid_list;
    }

    public function get_seller_adminid_and_right(){
        $adminid = $this->get_account_id();
        $account = $this->get_account();
        $groupid = $this->t_admin_group_user->get_groupid_value($adminid);
        $adminid_right = [];
        if(empty($groupid)){

        }else{
            $group_name = $this->t_admin_group_name->get_group_name($groupid);
            $master_adminid = $this->t_admin_group_name->get_master_adminid($groupid );
            $up_groupid = $this->t_admin_group_name->get_up_groupid($groupid );
            $up_group_name = $this->t_admin_main_group_name->get_group_name($up_groupid);
            $main_type = $this->t_admin_group_name->get_main_type($groupid );
            $main_type_name = E\Emain_type::get_desc($main_type);

            $main_master_adminid = $this->t_admin_main_group_name->get_master_adminid($up_groupid );
            if($adminid != $master_adminid && $adminid != $main_master_adminid){
                $adminid_right=[0=>$main_type_name,1=>$up_group_name,2=>$group_name,3=>$account];
            }else if($adminid == $main_master_adminid){
                $adminid_right=[0=>$main_type_name,1=>$up_group_name,2=>"",3=>""];
            }else{
                $adminid_right=[0=>$main_type_name,1=>$up_group_name,2=>$group_name,3=>""];
            }

        }
        return $adminid_right;
    }

    public function check_lesson_clash($teacherid,$userid,$lessonid,$lesson_start,$lesson_end){
        $ret_row1 = $this->t_lesson_info->check_student_time_free(
            $userid,$lessonid,$lesson_start,$lesson_end);

        if($ret_row1) {
            $error_lessonid=$ret_row1["lessonid"];
            return $this->output_err(
                "<div>有现存的学生课程与该课程时间冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div>"
            );
        }

        $ret_row2=$this->t_lesson_info->check_teacher_time_free(
            $teacherid,$lessonid,$lesson_start,$lesson_end);

        if($ret_row2) {
            $error_lessonid=$ret_row2["lessonid"];
            return $this->output_err(
                "<div>有现存的老师课程与该课程时间冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div>"
            );
        }
    }

    public function get_account_role() {
        if(\App\Helper\Utils::check_env_is_release()){
            return session("account_role");
        }else{
            return E\Eaccount_role::V_12;
        }
    }

    public function del( $userid) {
        $this->t_seller_student_new->row_delete($userid);
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

        if ($role==2 &&  $teacherid ) {
            return $teacherid;
        }else{
            echo $this->output_err("未登录");
            exit;
        }
    }




}
