<?php
namespace App\Models;
use \App\Enums as E;
class t_order_activity_info extends \App\Models\Zgen\z_t_order_activity_info
{
	public function __construct()
	{
		parent::__construct();
	}
    public function add_order_info( $orderid, $activity_list) {
        foreach($activity_list as $i=> $item ) {
            $this->row_insert([
                "orderid" =>$orderid,
                "subid" => $i,
                "order_activity_type" => $item["order_activity_type"  ],
                "activity_desc"  =>$item["activity_desc"],
                "succ_flag"  =>$item["succ_flag"],
                "cur_price" => $item["cur_price"  ],
                "cur_present_lesson_count" => $item["cur_present_lesson_count"  ],
                "can_period_flag" => $item["can_period_flag"  ],
                "change_value" => $item["change_value"  ],
            ]);

        }
    }
    public function get_order_activity_list ($orderid) {
        $sql=$this->gen_sql_new(
            "select * from %s  "
            . " where orderid=%u  order by subid asc ",
            self::DB_TABLE_NAME, $orderid );
        return $this->main_get_list($sql);
    }
    public function get_count_by_order_activity_type( $order_activity_type) {
        $where_arr=[];
        $this->where_arr_add_int_or_idlist($where_arr, "order_activity_type", $order_activity_type );

        $sql = $this ->gen_sql_new(
            "select count(*) from %s where   succ_flag =1  and %s ",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }
    public function get_all_change_value_by_order_activity_type( $order_activity_type) {
        $where_arr=[];
        $this->where_arr_add_int_or_idlist($where_arr, "order_activity_type", $order_activity_type );

        $sql = $this ->gen_sql_new(
            "select sum(change_value) from %s where   succ_flag =1  and %s ",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function del_by_orderid($orderid ) {
        $sql=$this->gen_sql_new(
            "delete  from %s  "
            . " where orderid=%u  ",
            self::DB_TABLE_NAME, $orderid );
        return $this->main_update($sql);
    }
    //@desn:获取某活动该月份使用的配额
    //@param: $order_activity_desc 活动名称
    public function get_used_quota($order_activity_desc,$start_time,$end_time){
        $where_arr = [
            ["oai.activity_desc = '%s' ",$order_activity_desc,''],
        ];
        $this->where_arr_add_time_range($where_arr,'oi.order_time',$start_time,$end_time);
        $sql = $this->gen_sql_new(
            'select sum(off_money) from %s oai '.
            'left join %s oi on oai.orderid = oi.orderid '.
            'where %s',
            self::DB_TABLE_NAME,
            t_order_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }

}











