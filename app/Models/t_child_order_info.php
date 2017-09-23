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

    public function get_all_period_order_info($start_time,$end_time,$opt_date_type,$page_info,$pay_status,$contract_status,$contract_type){
        $where_arr=[
            ["c.pay_status=%u",$pay_status,-1],
            "s.is_test_user=0"
        ];
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);

        if ($contract_type==-2) {
            $where_arr[]="contract_type in(0,1,3)" ;
            \App\Helper\Utils::logger("stu");

        }else if ( $contract_type==-3){
            $where_arr[]="contract_type in(0,3)" ;
        }else {
            $where_arr[]=["contract_type=%u" , $contract_type, -1];
        }

        if ($contract_status ==-2) {
            $where_arr[] = "contract_status <> 0";
        }else{
            $where_arr[]=["contract_status=%u",$contract_status,-1];
            // $this->where_get_in_str_query("contract_status",$contract_status);
        }
        $sql = $this->gen_sql_new("select s.userid,s.nick,o.order_time,o.pay_time order_pay_time,"
                                  ." c.pay_time,c.pay_status,c.period_num,o.contract_status,o.contract_type,"
                                  ." s.grade",
                                  
        );

    }

}











