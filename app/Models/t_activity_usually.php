<?php
namespace App\Models;
use \App\Enums as E;
class t_activity_usually extends \App\Models\Zgen\z_t_activity_usually
{
	public function __construct()
	{
		parent::__construct();
	}

    public function getActivityList($type,$start_time,$end_time){
        $where_arr = [
            ["gift_type=%d",$type,-1]
        ];

        $sql = $this->gen_sql_new("  select gift_type, title, act_descr, url, activity_status, add_time, au.uid, m.account from %s au "
                           ." left join %s m on m.uid=au.uid"
                           ." where %s "
                           ,self::DB_TABLE_NAME
                           ,t_manager_info::DB_TABLE_NAME
                           ,$where_arr
        );

        return $this->main_get_list($sql);
    }

}











