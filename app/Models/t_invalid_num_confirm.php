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
        $sql = $this->gen_sql_new("  select count(1) from %s i"
                                  ." where userid=$userid"
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_get_value($sql);
    }

    public function updateInfoByUserid($userid, $set_field_arr){
        $set_field_list_str=$this->get_sql_set_str( $set_field_arr);
        $sql=$this->gen_sql_new("update %s set  %s  where  userid= '%s' ",
                                self::DB_TABLE_NAME,
                                $set_field_list_str,
                                $userid
        );
        return $this->main_update($sql);
    }

    public function getCCMarktInfo($userid){
        $sql = $this->gen_sql_new("  select cc_adminid, cc_confirm_type, tmk_confirm_time, cc_confirm_time, tmk_adminid, tmk_confirm_type from %s i"
                                  ." where userid=$userid"
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

}











