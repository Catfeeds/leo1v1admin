<?php
namespace App\Models;
use \App\Enums as E;
class t_poster_share_log extends \App\Models\Zgen\z_t_poster_share_log
{
	public function __construct()
	{
		parent::__construct();
	}

    public function getStuListData($uid){
        $where_arr = [
            "uid=$uid"
        ];

        $sql = $this->gen_sql_new("  select uid, phone,studentid,FROM_UNIXTIME(add_time) as add_date from %s pl"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }

}











