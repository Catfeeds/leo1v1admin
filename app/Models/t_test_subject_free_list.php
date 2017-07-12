<?php
namespace App\Models;
use \App\Enums as E;
class t_test_subject_free_list extends \App\Models\Zgen\z_t_test_subject_free_list
{
    public function __construct()
    {
        parent::__construct();
    }

    public function set_tel_state($userid,$accountid){

        $ret=$this->row_insert([
            self::C_userid           => $userid,
            self::C_adminid          => $accountid,
            self::C_add_time         => time(NULL),
            self::C_test_subject_free_type    => 0,
            self::C_test_subject_free_reason  => 0,
        ],false,true);
    }

    public function get_count( $userid, $start_time=0 ) {
        $sql=$this->gen_sql_new(
            "select  count(distinct( from_unixtime(add_time, '%%Y%%m%%d'))) from %s where userid=%u and add_time >%u "
            ,self::DB_TABLE_NAME,  $userid, $start_time );
        return $this->main_get_value($sql);
    }

    public function get_list_by_userid( $page_info,  $userid ) {
        $sql=$this->gen_sql_new(
            "select  *  from %s where userid=%u "
            ,self::DB_TABLE_NAME,  $userid );
        return $this->main_get_list_by_page($sql,$page_info) ;
    }

}
