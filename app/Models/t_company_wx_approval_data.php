<?php
namespace App\Models;
use \App\Enums as E;
class t_company_wx_approval_data extends \App\Models\Zgen\z_t_company_wx_approval_data
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_list($userid) {
        $where_arr = [
            ["apply_user_id=%u",$userid,-1]
        ];

        $sql = $this->gen_sql_new("select id,apply_name,apply_user_id,apply_time,data_desc,data_column,require_reason,require_time,acc,data_url,page_url "
                                  ."from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_list_for_user_time($apply_user_id, $apply_time) {
        $sql = $this->gen_sql_new("select id from %s where apply_user_id='$apply_user_id' and apply_time=$apply_time",
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_value($sql);
    }
}











