<?php
namespace App\Models;
use \App\Enums as E;
class t_user_power_group extends \App\Models\Zgen\z_t_user_power_group
{
    public function __construct()
    {
        parent::__construct();
    }

    public function is_user_power_exit($uid,$gid) {
     
        $sql=$this->gen_sql_new(
            "select id from %s where uid =%u  and gid =%u" ,
            self::DB_TABLE_NAME,
            $uid,
            $gid
        );
        $ret=$this->main_get_row($sql);
        return $ret;
    }

    public function get_users($gid){
        $sql=$this->gen_sql_new(
            "select * from %s where gid =%u" ,
            self::DB_TABLE_NAME,
            $gid
        );
        return $this->main_get_row($sql);

    }
}
