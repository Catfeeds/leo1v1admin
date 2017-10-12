<?php
namespace App\Models;
use \App\Enums as E;
class t_ssh_login_log extends \App\Models\Zgen\z_t_ssh_login_log
{
	public function __construct()
	{
		parent::__construct();
	}
    public function  get_list( $page_info, $account, $start_time, $end_time) {
        $where_arr=[
        ];

        //$this->where_arr_add_int_or_idlist($where_arr,$field_name,$value);
        $this->where_arr_add_str_field($where_arr,"account",$account);

        $this->where_arr_add_time_range($where_arr,"login_time",$start_time,$end_time);

        $sql=$this->gen_sql_new(
            "select * from %s where %s ",
            self::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_list_by_page($sql,$page_info);
    }


}











