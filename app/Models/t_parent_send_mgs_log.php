<?php
namespace App\Models;
use \App\Enums as E;
class t_parent_send_mgs_log extends \App\Models\Zgen\z_t_parent_send_mgs_log
{
	public function __construct()
	{
		parent::__construct();
	}

    public function is_has($openid){
        $sql = $this->gen_sql_new("  select 1 from %s ps"
                                  ." left join %s p on p.parentid=ps.parentid"
                                  ." where p.wx_openid='$openid' and ps.is_send_flag=5"
                                  ,self::DB_TABLE_NAME
                                  ,t_parent_info::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }

}











