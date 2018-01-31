<?php
namespace App\Models;
use \App\Enums as E;
class t_power_back extends \App\Models\Zgen\z_t_power_back
{
	public function __construct()
	{
		parent::__construct();
	}
    public function delete_before_time( ) {
        $time=time() -86400*30;

        $sql=$this->gen_sql_new(
            "delete from  %s  where log_date <%u  ",
            self::DB_TABLE_NAME,
            $time
        );
        return $this->main_update($sql);

    }

    public function back() {
        $log_date=time(NULL);
        $sql=$this->gen_sql_new(
            "insert into %s  select $log_date, groupid, group_name, group_authority,  del_flag, role_groupid  from %s  ",
            self::DB_TABLE_NAME,
            t_authority_group::DB_TABLE_NAME
        );
        return $this->main_update($sql);
    }

    public function get_list($start_time, $end_time, $page_info) {
        $where_arr = [
            ["log_date>=%u", $start_time, -1],
            ["log_date<%u", $end_time, -1],
        ];
        $sql = $this->gen_sql_new("select log_date,groupid,group_name,group_authority,role_groupid from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

}











