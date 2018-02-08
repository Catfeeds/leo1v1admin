<?php
namespace App\Models;
use \App\Enums as E;
class t_admin_majordomo_group_name extends \App\Models\Zgen\z_t_admin_majordomo_group_name
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_group_list ($main_type) {
        $sql=$this->gen_sql_new("select groupid,group_name,main_assign_percent,master_adminid  from %s where main_type=%u order by group_name  asc " ,
                                self::DB_TABLE_NAME, $main_type);
        return $this->main_get_list($sql);
    }

    public function get_max_main_type(){
        $sql = $this->gen_sql_new("select max(main_type) from %s ",self::DB_TABLE_NAME);
        return $this->main_get_value($sql);
    }

    public function is_master($adminid){
        $sql = $this->gen_sql_new("select groupid from %s where master_adminid='$adminid'",self::DB_TABLE_NAME);
        return $this->main_get_value($sql);
    }

    public function get_all_list($main_type_flag=0){
        $where_arr=[
            ["main_type=%u",$main_type_flag,0]  
        ];

        $sql=$this->gen_sql_new("select * from %s where %s",
                                self::DB_TABLE_NAME,
                                $where_arr
        ) ;
        return $this->main_get_list($sql);
    }

    public function get_majordomo_master_adminid($adminid){
        $where_arr=[
            ["main_type=%u",$main_type_flag,0],
        ];
        $sql=$this->gen_sql_new("select * from %s where %s",
                                self::DB_TABLE_NAME,
                                $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_master_adminid_by_adminid($adminid){
        $sql = $this->gen_sql_new(
            " select groupid "
            ." from %s "
            ." where master_adminid = %u "
            ,self::DB_TABLE_NAME
            ,$adminid
        );
        return $this->main_get_value($sql);
    }

    public function check_is_master($main_type,$account_id){
        $sql = $this->gen_sql_new("select 1 from %s where main_type = %u and master_adminid=%u",
                                  self::DB_TABLE_NAME,
                                  $main_type,
                                  $account_id
        );
        return $this->main_get_value($sql);
    }

}











