<?php
namespace App\Models;
use \App\Enums as E;
class t_market_department_activity extends \App\Models\Zgen\z_t_market_department_activity
{
	public function __construct()
	{
		parent::__construct();
	}

    public function del_row($openid,$type){
        $sql = $this->gen_sql_new("  delete from %s where openid=%s and type=%s"
                                  ,self::DB_TABLE_NAME
                                  ,$openid
                                  ,$type
        );

        return $this->main_update($sql);
    }

    public function check_flag($openid,$type){
        $sql = $this->gen_sql_new("  select 1 from %s "
                                  ." where openid=%s and type=%s"
                                  ,self::DB_TABLE_NAME
                                  ,$opendid
                                  ,$type
        );

        return $this->main_get_value($sql);
    }
}











