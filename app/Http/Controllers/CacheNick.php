<?php
namespace App\Http\Controllers;


use Illuminate\Support\Facades\Redis;
/**
 * 
 * @use Controller
 */
trait  CacheNick {


    public function CacheNickInit() {
        $this->cache_map_config = [
            /* ["table_name", "id","name" ] */
           "teacher"   => ["db_weiyi.t_teacher_info", "teacherid","nick" ],
           "assistant" => ["db_weiyi.t_assistant_info", "assistantid","nick" ],
           "student"   => ["db_weiyi.t_student_info", "userid","nick" ],
           "account"   => ["db_weiyi_admin.t_manager_info", "uid","account" ],
           "seller"    => ["db_weiyi.t_seller_info", "sellerid","nick" ],
           "parent"    => ["db_weiyi.t_parent_info", "parentid","nick" ],
           "origin"    => ["db_weiyi.t_origin_key", "value","key0" ],
        ];

    }
    public function get_key($type_str,$id ) {
        return "$type_str:$id";
    }

    public function get_redis(){
        return Redis::connection('cache_nick');
    }

    public function cache_get_map_str( $type_str,$id ) {
        if ($id<=0) {
            return "" ;
        }
        $redis= $this->get_redis();

        /* use cache redis */
        $key=$this->get_key($type_str,$id);
        $nick=$redis->get($key);
        if (trim($nick)) {
            return $nick;
        }

        $config_item = $this->cache_map_config[$type_str];
        $table_name  = $config_item[0];
        $id_str      = $config_item[1];
        $name_str    = $config_item[2];

        $sql     = sprintf("select $name_str as name from $table_name where $id_str='%s'",
                           $this->t_student_info->ensql( $id)
        );
        $ret_row = $this->t_student_info->main_get_row($sql);
        if ($ret_row) {
            $nick = $ret_row["name"];
            $redis->set($key,$nick);
        }else{
            $nick="";
        }
        return $nick;
    }

    public function cache_get_map_str_new( $type_str,$id ) {
        if (($id<=0 && is_int($id)) || ($id=='' && is_string($id))) {
            return "" ;
        }
        $redis= $this->get_redis();
        /* use cache redis */
        $key=$this->get_key($type_str,$id);
        $nick=$redis->get($key);
        if (trim($nick)) {
            return $nick;
        }

        $config_item = $this->cache_map_config[$type_str];
        $table_name  = $config_item[0];
        $id_str      = $config_item[1];
        $name_str    = $config_item[2];
        $sql     = sprintf("select $name_str as name from $table_name where $id_str='%s'",
                           $this->t_student_info->ensql( $id)
        );
        if($config_item){
            $table_name = explode('.',$table_name)[1];
            $ret_row = $this->$table_name->main_get_row($sql);
        }else{
            $ret_row = $this->t_student_info->main_get_row($sql);
        }
        if ($ret_row) {
            $nick = $ret_row["name"];
            $redis->set($key,$nick);
        }else{
            $nick="";
        }
        return $nick;
    }

    public function cache_get_teacher_nick( $id ) {
        return $this->cache_get_map_str("teacher",$id);
    }


    public function cache_get_origin_key0 ( $id ) {
        return $this->cache_get_map_str_new("origin",$id);
    }

    public function cache_get_assistant_nick( $id ) {
        return $this->cache_get_map_str("assistant",$id);
    }

    public function cache_get_student_nick( $id ) {
        return $this->cache_get_map_str("student",$id);
    }

    public function cache_del_student_nick($id) {
        $this->cache_del_nick("student",$id);
    }

    public function cache_del_teacher_nick($id) {
        $this->cache_del_nick("teacher",$id);

    }

    public function cache_del_nick( $type_str,$id ) {
        $key=$this->get_key($type_str,$id);
        $redis= $this->get_redis();
        $redis->del($key);
    }

    public function cache_get_parent_nick( $id ) {
        return $this->cache_get_map_str("parent",$id);
    }

    public function cache_get_account_nick( $id ) {
        return $this->cache_get_map_str("account",$id);
    }

    public function cache_get_seller_nick( $id ) {
        return $this->cache_get_map_str("seller",$id);
    }

    public function cache_set_item_origin_key0( &$item,$field_name="origin",$nick_field_name="key0" ) {
        $item[$nick_field_name] = $this->cache_get_teacher_nick($item[$field_name]);
    }

    public function cache_set_item_teacher_nick( &$item,$field_name="teacherid",$nick_field_name="teacher_nick" ) {
        $item[$nick_field_name] = $this->cache_get_teacher_nick($item[$field_name]);
    }
    public function cache_set_item_account_nick_time (&$item, $show_field , $adminid_field, $time_field ) {
        $item["$show_field"]=
            $this->cache_get_account_nick( $item[$adminid_field])."/".
            \App\Helper\Utils::unixtime2date($item[$time_field]);
    }


    public function cache_set_item_account_nick( &$item,$field_name="adminid",$nick_field_name="admin_nick" ) {
        $item[$nick_field_name] = $this->cache_get_account_nick($item[$field_name]);
    }

    public function cache_set_item_parent_nick( &$item,$field_name="parentid",$nick_field_name="parent_nick" ) {
        $item[$nick_field_name] = $this->cache_get_parent_nick($item[$field_name]);
    }



    public function cache_set_item_assistant_nick( &$item,$field_name="assistantid",$nick_field_name="assistant_nick" ) {
        $item[$nick_field_name] = $this->cache_get_assistant_nick($item[$field_name]);
    }


    public function cache_set_item_student_nick( &$item,$field_name="userid",$nick_field_name="student_nick" ) {
        $item[$nick_field_name] = $this->cache_get_student_nick($item[$field_name]);
    }






}