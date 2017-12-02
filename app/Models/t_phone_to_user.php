<?php
namespace App\Models;
use App\Enums as E;
class t_phone_to_user extends \App\Models\Zgen\z_t_phone_to_user
{
    public function __construct()
    {
        parent::__construct();
    }
    public function delete_user($userid , $role)
    {
        $sql = sprintf("delete from %s where userid = %u and role = %u",
                       self::DB_TABLE_NAME,
                       $userid,
                       $role
        );
        $this->main_update($sql);
        $sql = sprintf("delete from %s where userid = %u",
                       \App\Models\Zgen\z_t_user_info::DB_TABLE_NAME,
                       $userid
        );
        return $this->main_update($sql);
    }

    public function is_phone_valid($phone, $role)
    {
        $sql = sprintf("select count(*) as num from %s where role = %u and phone = '%s'",
                       self::DB_TABLE_NAME,
                       $role,
                       $phone
        );
        return $this->main_get_row( $sql  );
    }

    public function add_phone_to_ass($userid, $phone)
    {
        return $this->add($phone,E\Erole::V_ASSISTENT,$userid);
    }

    public function add( $phone,$role,$userid)
    {
        return $this->row_insert([
            "phone"  => $phone,
            "role"   => $role,
            "userid" => $userid,
        ]);
    }

    public function get_info_from_role_phone($role, $phone)
    {
        $sql = sprintf("select userid from %s where role = %u and phone = %u ",
                       self::DB_TABLE_NAME,
                       $role,
                       $phone
        );
        $userid =  $this->main_get_value($sql);
        $nick = $this->get_user_nick_by_id($userid, $role);
        if(!$nick)
            return false;
        return array('nick' => $nick, 'userid' => $userid);
    }

    public function get_user_nick_by_id($userid, $role)
    {
        switch($role){
        case E\Erole::V_STUDENT :
            $sql = sprintf("select nick from %s where userid = %u",
                           t_student_info::DB_TABLE_NAME,
                           $userid
            );
            break;
        case E\Erole::V_TEACHER :
            $sql = sprintf("select nick from %s where teacherid = %u",
                           t_teacher_info::DB_TABLE_NAME,
                           $userid
            );
            break;
        case E\Erole::V_PARENT :
            $sql =sprintf("select nick from %s where parentid = %u",
                          t_parent_info::DB_TABLE_NAME,
                          $userid
            );
            break;
        default:
            return false;
        }

        $nick = $this->main_get_value($sql, "");
        if("" == $nick)
            return false;
        return $nick;
    }

    public function get_phone_by_userid($userid)
    {
        $sql = sprintf("select phone from %s where userid = %u",
                       self::DB_TABLE_NAME,
                       $userid
        );
        return $this->main_get_value($sql);
    }

    public function get_phone_role_by_userid($userid)
    {
        $sql = sprintf("select phone, role from %s where userid = %u",
                       self::DB_TABLE_NAME,
                       $userid
        );
        return $this->main_get_row($sql);
    }

    public function get_userid_by_phone( $phone,$role=1) {
        $sql=$this->gen_sql("select userid  from %s where phone like '%s%%' and role =%u for update "
                            ,self::DB_TABLE_NAME
                            ,$phone
                            ,$role
        );
        return $this->main_get_value($sql);
    }

    public function get_phone($userid)
    {
        $sql=$this->gen_sql("select phone from %s where userid = %u ",
                            self::DB_TABLE_NAME,
                            $userid
        );
        return $this->main_get_value($sql);
    }

    public function get_list($page_num,$phone ,$userid) {
        $where_arr=[
            [ "phone like '%%%s%%'", $phone,"" ] ,
            [ "userid=%u", $userid,-1 ] ,
        ];
        $sql=$this->gen_sql("select phone,role,userid from %s where %s ",
                            self::DB_TABLE_NAME,
                            [$this->where_str_gen($where_arr)]);
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function set_userid($phone, $role, $userid )  {
        $sql = $this->gen_sql("update %s set userid=%u where phone='%s' and role=%u "
                              ,self::DB_TABLE_NAME
                              ,$userid
                              ,$phone
                              ,$role
        );
        return $this->main_update($sql);
    }

    public function set_phone($phone,$role,$userid){
        $sql=$this->gen_sql_new("update %s set phone='%s' where userid=%u and role=%u"
                                ,self::DB_TABLE_NAME
                                ,$phone
                                ,$userid
                                ,$role
        );
        return $this->main_update($sql);
    }

    public function get_info_by_userid( $userid )  {
        $sql=$this->gen_sql("select * from %s   where userid=%u ",
                            self::DB_TABLE_NAME,
                            $userid);
        return $this->main_get_row($sql);
    }

    public function get_userid_by_acc($acc)
    {
        $sql=$this->gen_sql("select userid from %s where phone = '%s' ",
                            self::DB_TABLE_NAME,
                            $acc
        );
        return $this->main_get_value($sql);
    }

    public function check_is_exist_by_phone_and_userid($userid,$phone,$role){
        $where_arr = [
            ["userid = %u",$userid,-1],
            ["phone = %u",$phone,-1],
            ["role = %u",$role,-1],
        ];
        $sql = $this->gen_sql_new("select * from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function delete_user_account($phone,$userid,$role)
    {
        $where_arr=[
            ["phone=%u",$phone,0],
            ["userid=%u",$userid,0],
            ["role=%u",$role,0],
        ];
        $sql = $this->gen_sql_new("delete from %s where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_update($sql);
    }

    public function get_all_phone($role=1){
        $where_arr=[
            ["role=%u",$role,-1]
        ];
        $sql = $this->gen_sql_new("select phone from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['phone'];
        });
    }

    public function get_teacherid($phone )
    {
        $sql = $this->gen_sql("select  userid ".
                              " from  %s ".
                              " where phone= '%s' and role = %u",
                              self::DB_TABLE_NAME,
                              $phone,
                              \App\Enums\Erole::V_TEACHER
        );
        return $this->main_get_value( $sql );
    }

}