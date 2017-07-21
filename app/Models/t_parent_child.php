<?php
namespace App\Models;
class t_parent_child extends \App\Models\Zgen\z_t_parent_child
{
    public function __construct()
    {
        parent::__construct();
    }

    public function set_parentid( $userid, $parent_type,$parentid) {
        $sql=$this->gen_sql("update %s  set parentid=%u  where userid=%u and parent_type=%u"
                            ,self::DB_TABLE_NAME
                            ,$parentid
                            ,$userid
                            ,$parent_type
        );
        return $this->main_update($sql);
    }

    public function del( $userid, $parent_type,$parentid) {
        $sql=$this->gen_sql("delete from %s  where parentid=%u  and userid=%u and parent_type=%u "
                            ,self::DB_TABLE_NAME
                            ,$parentid
                            ,$userid
                            ,$parent_type
        );
        return $this->main_update($sql);
    }


    public function check_has_parent($parentid,$studentid){
        $sql=$this->gen_sql("select count(1) from %s where parentid=%u and userid=%u"
                            ,self::DB_TABLE_NAME
                            ,$parentid
                            ,$studentid
        );
        return $this->main_get_value($sql);
    }

    public function check_parent_exists_info($parent_type,$userid){
        $sql=$this->gen_sql("select parentid from %s where parent_type=%u and userid=%u"
                            ,self::DB_TABLE_NAME
                            ,$parent_type
                            ,$userid
        );
        return $this->main_get_value($sql);
    }

    public function set_student_parent($parentid,$studentid,$parent_type=7){
        $sql = $this->gen_sql("insert into %s (parentid,userid,parent_type) "
                              ." value(%u,%u,%u)"
                              ,self::DB_TABLE_NAME
                              ,$parentid
                              ,$studentid
                              ,$parent_type
        );
        return $this->main_update($sql);
    }

    public function get_relationship($page_num,$studentid,$parentid){
        $where_arr=[];

        if ($studentid>0){
            $where_arr=[["a.userid=%u" , $studentid, -1]];
        }
        if ($parentid>0){
            $where_arr=[["a.parentid=%u" , $parentid, -1]];
        }

        $sql=$this->gen_sql_new("select a.parentid, a.parent_type, a.userid, b.phone,fb.role, fb.phone as login_phone "
                                ." from %s a "
                                ." left join %s b on a.parentid=b.parentid "
                                ." left join %s fb on fb.userid=b.parentid "
                                ." where %s"
                                ,self::DB_TABLE_NAME
                                ,\App\Models\t_parent_info::DB_TABLE_NAME
                                ,t_phone_to_user::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_stu_parent_info_list($userid){
        $sql = $this->gen_sql_new("select parent_type,nick parent_name,phone parent_phone,email parent_eamil".
                                  " from %s pc left join %s p on pc.parentid = p.parentid".
                                  " where pc.userid = %u"
                                  ,self::DB_TABLE_NAME
                                  ,t_parent_info::DB_TABLE_NAME
                                  ,$userid
        );
        // dd($sql);
        return $this->main_get_list($sql);
    }

    public function get_children_ids($parentid) {
        $sql = $this->gen_sql_new("select userid from %s where parentid = %u"
                                  ,self::DB_TABLE_NAME
                                  ,$parentid
        );
        return $this->main_get_list($sql);
    }

}
