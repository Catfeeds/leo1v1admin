<?php
namespace App\Models;
use \App\Enums as E;
class t_luck_draw_yxyx_for_ruffian extends \App\Models\Zgen\z_t_luck_draw_yxyx_for_ruffian
{
	public function __construct()
	{
		parent::__construct();
	}


    public function get_total_money($today){

        $where_arr = [];

        $end_time = $today+86400;

        $this->where_arr_add_time_range($where_arr,"luck_draw_time",$today,$end_time);

        $sql = $this->gen_sql_new("  select sum(tx.money)/100 from %s tx"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }

    public function get_prize_num($parentid){
        $where_arr = [
            "tl.luck_draw_adminid = $parentid"
        ];
        $sql = $this->gen_sql_new("  select count(*) from %s tl"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }

    public function get_ruffian_money($userid){
        $sql = $this->gen_sql_new("  select sum(money)/100 from %s ld"
                                  ." where luck_draw_adminid = $userid"
                                  ,self::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }
}











