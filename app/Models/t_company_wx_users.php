<?php
namespace App\Models;
use \App\Enums as E;
class t_company_wx_users extends \App\Models\Zgen\z_t_company_wx_users
{
	public function __construct()
	{
		parent::__construct();
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

    public function get_all_list_for_manager() {
        $sql = $this->gen_sql_new("select m.uid,m.account,m.phone,u.userid,m.power,u.department,u.isleader from %s u left join %s m on u.mobile=m.phone where m.uid != ''",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

}











