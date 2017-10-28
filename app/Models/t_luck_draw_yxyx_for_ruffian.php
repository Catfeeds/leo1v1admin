<?php
namespace App\Models;
use \App\Enums as E;
class t_luck_draw_yxyx_for_ruffian extends \App\Models\Zgen\z_t_luck_draw_yxyx_for_ruffian
{
	public function __construct()
	{
		parent::__construct();
	}


    public function get_total_money(){
        $sql = $this->gen_sql_new("  select sum(tx.money)/100 from %s tx "
                                  ,self::DB_TABLE_NAME
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
}











