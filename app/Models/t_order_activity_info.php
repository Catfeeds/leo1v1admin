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

}











