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

    public function get_row_by_adminid($adminid,$confirm_type){
        $where_arr = [];
        if($confirm_type==E\Eseller_student_sub_status::V_1001){
            $this->where_arr_add_int_field($where_arr, 'cc_confirm_type', $confirm_type);
        }elseif($confirm_type>1001){
            $where_arr[] = "cc_confirm_type>1001";
        }
        $this->where_arr_add_int_field($where_arr, 'cc_adminid', $adminid);
        $sql = $this->gen_sql_new(" select cc_confirm_type "
                                  ." from %s "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }
}











