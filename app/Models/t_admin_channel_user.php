<?php
namespace App\Models;
use \App\Enums as E;

class t_admin_channel_user extends \App\Models\Zgen\z_t_admin_channel_user
{
    public function __construct()
    {
        parent::__construct();
    }

 
    /*
     *@author sam
     *@function 获取userid
     */
    public function get_teacher_ref_type_id_list($channel_id) {
        $where_arr=[
            ["channel_id= %u",$channel_id,-1]
        ];
        $sql=$this->gen_sql_new("select teacher_ref_type_id from %s where %s",
                                self::DB_TABLE_NAME,
                                $where_arr);
        return $this->main_get_list($sql);

    }

    /*
     *@author sam
     *@function 获取admin_id
     */
    public function get_user_list_new($group_id) {
        /*$sql=$this->gen_sql_new("select admin_id,admin_name,admin_phone from %s  where group_id=%u ",
                                self::DB_TABLE_NAME,
                                $groupid);
                                */
        $where_arr=[
            ["teacher_ref_type = %u",$group_id,-1]
        ];
        $sql=$this->gen_sql_new("select teacherid ,realname ,phone,zs_id ,email,teacher_type from %s where %s",
                                t_teacher_info::DB_TABLE_NAME,
                                $where_arr);
        return $this->main_get_list($sql);
    }
    /*
     *@author sam
     *@function set 
     */
    public function set_teacher_ref_type($teacherid,$group_id) {
        $where_arr=[
            [" = %u",$group_id,-1]
        ];
        $sql=$this->gen_sql_new("select teacherid ,realname ,phone  from %s where %s",
                                t_teacher_info::DB_TABLE_NAME,
                                $where_arr);
        return $this->main_get_list($sql);
    }
   
}