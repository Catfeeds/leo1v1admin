<?php
namespace App\Helper;
use Illuminate\Support\Facades\Log;

class Config{

    static public function get_config($key1,$group= "admin") {
        $config = config($group);
        return  @$config[$key1];
    }

    static public function get_config_2($key1,$key2="",$group= "admin") {
        $config = config($group);

        if($key2!=""){
            $ret = $config[$key1][$key2];
        }else{
            $ret = $config[$key1];
        }
        return $ret;
    }
    static public function get_qiniu_access_key() {
        $config=self::get_config("qiniu");
        return $config["access_key"];
    }
    static public function get_qiniu_secret_key() {
        $config=self::get_config("qiniu");
        return $config["secret_key"];
    }
    static public function get_qiniu_private_url() {
        $config=self::get_config("qiniu");
        return $config["private_url"]["url"];
    }



    static  public function get_menu() {
        return \App\Config\menu::get_config();
    }

    static  public function get_url_power_map() {
        return \App\Config\url_power_map::get_config();
    }

    static  public function get_stu_menu() {
        return \App\Config\stu_menu::get_config();
    }

    static  public function get_tea_menu() {
        return \App\Config\tea_menu::get_config();
    }

    static  public function get_tea_admin_menu() {
        return config("tea_admin_menu");
    }

    static public function get_wx_appid() {
        $config=self::get_config("wx");
        return $config["appid"];
    }

    static public function get_wx_appsecret() {
        $config=self::get_config("wx");
        return $config["appsecret"];
    }

    static public function get_yxyx_wx_appid() {
        $config=self::get_config("yxyx_wx");
        return $config["appid"];
    }

    static public function get_yxyx_wx_appsecret() {
        $config=self::get_config("yxyx_wx");
        return $config["appsecret"];
    }


    static public function get_teacher_wx_appid() {
        $config=self::get_config("teacher_wx");
        return $config["appid"];
    }

    static public function get_teacher_wx_appsecret() {
        $config=self::get_config("teacher_wx");
        return $config["appsecret"];
    }

    static public function get_qiniu_public_url() {
        $config=self::get_config("qiniu");
        return $config["public"]["url"];
    }

    static public function get_taobao_shop_app() {
        return self::get_config("taobao_shop");
    }

    static public function get_api_url() {
        return self::get_config("api_url");
    }

    static public function get_monitor_url() {
        return  self::get_config("monitor_url");
    }

    static public function get_monitor_new_url() {
        return  self::get_config("monitor_new_url");
    }

    static public function get_teacher_wx_url() {
        return  self::get_config("teacher_wx_url");
    }

    static public function get_test_password() {
        $config=self::get_config("test");
        return  $config["password"];
    }

    static public function get_test_username() {
        $config=self::get_config("test");
        return  $config["username"];
    }

    static public function check_in_admin_list( $account) {
        return   in_array( $account , self::get_config("admin_list"));
    }

    static public function get_liyou_public_ip_list() {
        return  self::get_config("liyou_public_ip_list");
    }
    static function  get_day_system_assign_count() {
        return  self::get_config("day_system_assign_count");
    }

    static public function get_lesson_confirm_start_time() {
        return  self::get_config("lesson_confirm_start_time");
    }

    static public function get_seller_test_lesson_user_month_limit () {
        return  self::get_config("seller_test_lesson_user_month_limit");
    }
    static public function get_seller_new_user_day_count () {
        return  self::get_config("seller_new_user_day_count");
    }
    static  public function get_seller_hold_user_count() {
        return  self::get_config("seller_hold_user_count");
    }
    static  public function get_current_ratio() {
        return  120;
    }

    static public function get_admin_domain_url( $admin_domain_type ) {
        return  self::get_config("admin_domain_url_config")[ $admin_domain_type ] ;
    }

};