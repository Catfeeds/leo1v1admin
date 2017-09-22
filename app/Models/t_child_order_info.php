<?php
namespace App\Models;
use \App\Enums as E;
class t_child_order_info extends \App\Models\Zgen\z_t_child_order_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_child_order_info($orderid){
        $sql = $this->gen_sql_new("select * from %s where parent_orderid = %u",self::DB_TABLE_NAME,$orderid);
        return $this->main_get_list($sql);
    }

    public function get_info_by_parent_orderid($parent_orderid,$child_order_type){
        $sql = $this->gen_sql_new("select * from %s where parent_orderid = %u and child_order_type=%u",
                                  self::DB_TABLE_NAME,
                                  $parent_orderid,
                                  $child_order_type
        );
        return $this->main_get_row($sql);

    }

    public function get_child_orderid($orderid,$child_order_type){
        $sql = $this->gen_sql_new("select child_orderid from %s where parent_orderid=%u and child_order_type=%u",
                                  self::DB_TABLE_NAME,
                                  $orderid,
                                  $child_order_type
        );
        return $this->main_get_value($sql);
    }

    public function chick_all_order_have_pay($parent_orderid){
        $sql = $this->gen_sql_new("select 1 from %s where parent_orderid=%u and price>0 and pay_status=0",
                                  self::DB_TABLE_NAME,
                                  $parent_orderid
        );
        return $this->main_get_value($sql);
    }

}











