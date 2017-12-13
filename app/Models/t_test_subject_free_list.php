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

    public function get_call_count( $userid, $start_time=0 ) {
        $sql=$this->gen_sql_new(
            "select  count(*) from %s where userid=%u and add_time >%u "
            ,self::DB_TABLE_NAME,  $userid, $start_time );
        return $this->main_get_value($sql);
    }

    public function get_set_invalid_count( $userid, $start_time=0 ) {
        $sql=$this->gen_sql_new(
            "select  count(*) from %s"
            . " where userid=%u and add_time >%u and test_subject_free_type=%u "
            ,self::DB_TABLE_NAME,  $userid, $start_time,
            E\Etest_subject_free_type::V_3 );
        return $this->main_get_value($sql);
    }


    public function get_list_by_userid( $page_info,  $userid ) {
        $sql=$this->gen_sql_new(
            "select  *  from %s where userid=%u "
            ,self::DB_TABLE_NAME,  $userid );
        return $this->main_get_list_by_page($sql,$page_info) ;
    }

    public function get_all_list_by_userid($userid) {
        $sql=$this->gen_sql_new(
            "select * "
            ."from %s "
            ."where userid=%u "
            ." order by add_time desc limit 1 "
            ,self::DB_TABLE_NAME
            ,$userid
        );
        return $this->main_get_row($sql);
    }

    public function get_free_count($start_time,$end_time,$origin_ex) {
        $where_arr = [];
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_add_time_range($where_arr,'f.add_time',$start_time,$end_time);
        $sql=$this->gen_sql_new(
            " select f.adminid,"
            ." count(distinct(f.userid)) free_count "
            ." from %s f "
            ." left join %s s on s.userid=f.userid "
            ." where %s "
            ." group by f.adminid "
            ,self::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_userid_by_adminid($adminid,$userid){
        $sql=$this->gen_sql_new(
            " select userid "
            ." from %s"
            ." where adminid=%u and userid =%u "
            ,self::DB_TABLE_NAME
            ,$adminid
            ,$userid
        );
        return $this->main_get_value($sql);
    }

    public function get_row_by_userid_adminid($adminid,$userid){
        $where_arr = [
            ['adminid=%u',$adminid],
            ['userid=%u',$userid],
        ];
        $sql=$this->gen_sql_new(
            " select add_time "
            ." from %s "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_value($sql);
    }


    public function get_return_publish_count($userid){
        $sql = "select count(*) from db_weiyi.t_test_subject_free_list where userid = $userid";
        return $this->main_get_value($sql);
    }
}
