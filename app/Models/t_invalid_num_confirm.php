<?php
namespace App\Models;
use \App\Enums as E;
class t_invalid_num_confirm extends \App\Models\Zgen\z_t_invalid_num_confirm
{
	public function __construct()
	{
		parent::__construct();
	}


    public function checkHas($userid){
        $sql = $this->gen_sql_new("  select id from %s where userid=$userid"
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_get_value($sql);
    }


}











