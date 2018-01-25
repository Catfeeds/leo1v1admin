<?php
namespace App\Models;
use \App\Enums as E;
class t_seller_get_new_log extends \App\Models\Zgen\z_t_seller_get_new_log
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_row_by_adminid_userid($adminid,$userid){
        $where_arr = [];
        $this->where_arr_add_int_field($where_arr, 'adminid', $adminid);
        $this->where_arr_add_int_field($where_arr, 'userid', $userid);
        $sql = $this->gen_sql_new(
            "select id,called_count,no_called_count "
            ."from %s "
            ."where %s limit 1"
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_row($sql);
    }
}











