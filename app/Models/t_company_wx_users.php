<?php
namespace App\Models;
use \App\Enums as E;
class t_company_wx_users extends \App\Models\Zgen\z_t_company_wx_users
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_users() {
        $sql = $this->gen_sql_new("select userid,name,mobile from %s ",
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_all_list() {
        $sql = $this->gen_sql_new("select u.id+1000 id,u.userid,u.name,u.position,u.permission,u.isleader,u.department pId,m.power from %s u left join %s m on u.mobile=m.phone order by `order` desc",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_all_list_for_depart($depart) {
        $sql = $this->gen_sql_new("select userid,name username,isleader,permission from %s where department in (".$depart.") order by `order` desc ", self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

    public function get_all_list_for_manager($uid=-1) {
        $where_arr=[
            ["m.uid=%u",$uid,-1]  
        ];
        $sql = $this->gen_sql_new("select m.uid,m.account,m.phone,u.userid,m.power,u.department,u.isleader "
                                  ."from %s u left join %s m on u.mobile=m.phone "
                                  ."where m.uid != '' and %s",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function row_delete_for_department($department) {
        $sql = $this->gen_sql("delete from %s where department=$department", self::DB_TABLE_NAME);
        return $this->main_update($sql);
    }

    public function get_userid_for_adminid($phone) {
        $sql = $this->gen_sql_new("select userid from %s where mobile=$phone", self::DB_TABLE_NAME);
        return $this->main_get_value($sql);
    }

    public function get_userid_for_name($name) {
        $sql = $this->gen_sql_new("select userid from %s where name='$name' ", self::DB_TABLE_NAME);
        return $this->main_get_value($sql);
    }

}











