<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log ;

use \App\Enums as E;

use Illuminate\Support\Facades\Session;

require_once( app_path() ."/Helper/functions.php"  );
/**
 * @property  \App\Models\t_lesson_info_b2       $t_lesson_info_b2
 * @property  \App\Models\t_lesson_info_b3       $t_lesson_info_b3
 * @property  \App\Models\t_seller_student_new_b2     $t_seller_student_new_b2
 * @property  \App\Models\t_jobs       $t_jobs
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
            $this->set_call_ctl_init();
        }
        $this->setUpTraits();
        //$this->check_approval_require(); // 检测数据页面权限 (仅申请人与研发部可见)
    }

    public function check_approval_require() {
        if (!isset($_SERVER["REQUEST_URI"])) { // 处理执行 migrate 报错
            return null;
        }
        $burl = $_SERVER["REQUEST_URI"];
        $pattern = '/^\/require[0-9]*/';
        preg_match($pattern, $burl, $matches);
        $url = explode("/", $burl);
        if (isset($url[1]) && isset($matches[0]) && $url[1] != 'requirement') {
            // 检测权限
            $acc = $this->get_account_id();
            $info = $this->t_manager_info->field_get_list($acc, "phone,account_role");
            if (intval($info["account_role"]) !== E\Eaccount_role::V_12) {

                $own_power = $this->t_company_wx_approval_data->get_id_for_page_url($burl);
                $power = "";
                if ($own_power) {
                    foreach($own_power as $item) {
                        $power[] = $item["user_id"];
                    }
                    $phone = $info["phone"];
                    $userid = $this->t_company_wx_users->get_userid_for_adminid($phone);
                    if (!in_array($userid, $power)) {
                        exit("您无权限操作此页面");
                    }
                } else {
                    exit("您无权限操作此页面");
                }
            } else {
            }

        }
    }


    public function  set_call_ctl_init(){
        if (\App\Helper\Utils::check_env_is_testing()) {
            return;
        }

        $url_input_define = session('url_input_define') ? json_decode(session('url_input_define'),true) : [];
        $url_desc_power = session('url_desc_power') ? json_decode(session('url_desc_power'),true) : [];
        $url = $_SERVER['REQUEST_URI'];
        //dd($url);
        $hide_desc_power = [];
        if(!empty($url_desc_power)){
            foreach($url_desc_power as $v){
                if( $url == $v['url'] && $v['open_flag'] == 0 ){
                    array_push($hide_desc_power,$v['opt_key']);
                }
            }
        }

        $this->html_power_list_add($hide_desc_power);
        //dd($hide_desc_power);
        if(!empty($url_input_define)){
            foreach( $url_input_define as $v ){
                if( $url == $v['url'] ){
                    if( $v['field_type'] != 'function'){
                        $this->set_in_value($v['field_name'], $v['field_val_str']);
                    }else{
                        switch ($v['field_val'])
                        {
                        case 1:
                            $this->set_in_value($v['field_name'], $this->get_account());
                            break;
                        case 2:
                            $this->set_in_value($v['field_name'], $this->get_account_id());
                            break;
                        case 3:
                            $this->set_in_value($v['field_name'], $this->get_account_role());
                            break;
                        }
                    }
                }
            }
        }
        //dd($url_input_define);
        //$this->html_power_list_add([ "grade","opt_grade", "input_grade" ]);
        /*
        $this->set_in_value("grade", 101);
        $this->set_in_value("grade",  this->get_account_id());

        //$sys_operator_uid= $this->get_account_id();
        //$this->get_account_role();
        $this->set_in_value("adminid", $this->get_account_id())  ;

        */
    }

    //保存要隐藏元素 列表
    public $html_power_list=[];

    public function html_power_list_add( $key ) {
        if (is_array($key)) {
            foreach ($key as $item) {
                $this->html_power_list[$item]= true;
            }
        }else{
            $this->html_power_list[$key]= true;
        }
    }

    public function html_power_list_del( $key ) {
        if (is_array($key)) {
            foreach ($key as $item) {
                unset ( $this->html_power_list[$item] ) ;
            }
        }else{
            unset ( $this->html_power_list[$key] ) ;
        }
    }

    // 用于 慢查询 的 域名, 免得 admin 返回504
    public function check_and_switch_tongji_domain() {
        if ( \App\Helper\Utils::check_env_is_release() ){
            $server_name= $_SERVER["HTTP_HOST"];
            if (!($server_name == "admin-tongji.leo1v1.com" || $server_name == "p.admin-tongji.leo1v1.com"   )){
                if (!isset($_GET["callback"])) {
                    \App\Helper\Utils::logger("CHECK FOR $server_name");
                    if ( $server_name== "admin.leo1v1.com" ) {
                        \App\Helper\Utils::logger(" DO admin.leo1v1.com ");

                        header('Location: http://admin-tongji.leo1v1.com/'. trim($_SERVER["REQUEST_URI"],"/")  );
                    }else{
                        header('Location: http://p.admin-tongji.leo1v1.com/'.  trim($_SERVER["REQUEST_URI"],"/")  );
                    }

                }else{
                    if ( $server_name== "admin.leo1v1.com" ) {
                        $resp= $this->output_err(1101, ["jump_url" =>  "http://admin-tongji.leo1v1.com/"]);
                    }else{
                        $resp=$this->output_err(1101, ["jump_url" =>  "http://p.admin-tongji.leo1v1.com/"]);
                    }
                    $resp->send();
                }

                exit;
            }
            $this->switch_tongji_database();
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
            if (!\App\Helper\Utils::check_env_is_testing()) {
                \App\Helper\Utils::logger("GOTO: " .$_SERVER["REQUEST_URI"] );

                if ($this->get_in_str_val("callback"))  {
                    $resp= $this->output_err( 1005 );
                    $resp->send();
                    exit;

                }else{
                    header('Location: /?to_url='. urlencode(  $_SERVER["REQUEST_URI"] ) );
                    exit;
                }
            }else{

            }
        }
    }


    public function check_account_in_arr($arr ) {
        return in_array(session("acc"), $arr ) ;
    }

    public function get_action_str() {
        $path= $this->get_in_str_val("_url");
        \App\Helper\Utils::logger("path:$path");

        if (preg_match("/\/([a-zA-Z0-9_]+)\/([a-zA-Z0-9_]+)/",$path, $matches)  )  {
            return strtolower($matches[2]);
        }else{
            return "index" ;
        }
    }
    function get_account(){
        return  session("acc");
    }
    function get_account_id(){
        return  session("adminid");
    }

    public function get_login_teacher() {
        if (!session("tid") || session("tid")==null){
            \App\Helper\Utils::logger("GOTO:".$_SERVER["REQUEST_URI"]);
            header('Location: /login/teacher?to_url='.$_SERVER["REQUEST_URI"]);
            exit;
        }else{
            return session("tid");
        }
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

    public function get_teacherid_new(){
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
            return 0;
        }
    }



}
