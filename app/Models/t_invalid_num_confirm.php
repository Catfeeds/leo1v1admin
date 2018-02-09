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

    public function checkHasSign($userid,$adminid){
        $where_arr = [
            "userid" => $userid,
            "cc_adminid" => $adminid
        ];

        $sql = $this->gen_sql_new("  select 1 from %s i"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }

    public function getHasSignNum($userid){
        $where_arr = [];
        $sql = $this->gen_sql_new("  select ");
    }

}











