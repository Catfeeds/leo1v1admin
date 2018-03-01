<?php
namespace App\Models;
use \App\Enums as E;
class t_company_wx_tag_users extends \App\Models\Zgen\z_t_company_wx_tag_users
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_list() {
        $sql = $this->gen_sql_new("select id,userid from %s ",self::DB_TABLE_NAME);
        return $this->main_get_list($sql, function ( $item) {
            return $item['userid'];
        });
    }

    public function get_user($id, $userid) {
        $sql = $this->gen_sql_new("select id from %s where id=$id and userid='$userid'", self::DB_TABLE_NAME);
        return $this->main_get_value($sql);
                                               
    }


}











