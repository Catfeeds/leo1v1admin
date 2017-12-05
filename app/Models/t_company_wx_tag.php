<?php
namespace App\Models;
use \App\Enums as E;
class t_company_wx_tag extends \App\Models\Zgen\z_t_company_wx_tag
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_list() {
        $sql = $this->gen_sql_new("select id,name,leader_power,no_leader_power from %s ",self::DB_TABLE_NAME);
        return $this->main_get_list($sql, function($item) {
            return $item['id'];
        });
    }

    public function get_all_department() {
        $sql = $this->gen_sql_new("select t.name,d.id,d.department from %s t left join %s d on t.id=d.id ",
                                  self::DB_TABLE_NAME,
                                  t_company_wx_tag_department::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }
}











