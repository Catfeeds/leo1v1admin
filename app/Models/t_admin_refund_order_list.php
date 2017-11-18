<?php
namespace App\Models;
use \App\Enums as E;
class t_admin_refund_order_list extends \App\Models\Zgen\z_t_admin_refund_order_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_info($page_info){
        $sql = $this->gen_sql_new("select * from %s order by apply_time",self::DB_TABLE_NAME);
        return $this->main_get_list_by_page($sql,$page_info);
    }


}











