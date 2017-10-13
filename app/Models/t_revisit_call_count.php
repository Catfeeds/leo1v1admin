<?php
namespace App\Models;
use \App\Enums as E;
class t_revisit_call_count extends \App\Models\Zgen\z_t_revisit_call_count
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_call_phone_id($start_time,$end_time){
        $sql = $this->gen_sql_new("select group_concat(call_phone_id) "
                                  ." from %s "
                                  ." where revisit_time2>=$start_time revisit_time2<$end_time"
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_get_value($sql);
    }

}
