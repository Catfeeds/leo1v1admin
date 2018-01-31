<?php
namespace App\Models;
use \App\Enums as E;
class t_company_wx_approval_notify extends \App\Models\Zgen\z_t_company_wx_approval_notify
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_list_for_user_id($id, $userid) {
        $sql = $this->gen_sql_new("select d_id from %s where d_id=$id and user_id='$userid' ",
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_value($sql);
    }

}











