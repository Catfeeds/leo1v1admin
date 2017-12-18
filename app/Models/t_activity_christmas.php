<?php
namespace App\Models;
use \App\Enums as E;
class t_activity_christmas extends \App\Models\Zgen\z_t_activity_christmas
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_prize_type($parentid){
        $sql = $this->gen_sql_new("  select christmas_price_type from %s ac"
                                  ." where ac.parentid=$parentid"
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_get_value($sql);
    }

}











