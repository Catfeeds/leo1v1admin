<?php
namespace App\Models;
class t_opt_table_log extends \App\Models\Zgen\z_t_opt_table_log
{
	public function __construct()
	{
		parent::__construct();
	}
    public function get_list($page_num, $start_time,$end_time, $adminid, $sql_str  )
    {
        $where_arr=[
            ["opt_time >= %u ", $start_time , -1 ],
            ["opt_time <= %u ", $end_time , -1 ],
            ["adminid=%u ", $adminid , -1 ],
            ["sql_str like '%%%s%%' ", $sql_str, "" ],
        ];
        
        $sql=$this->gen_sql("select * from %s where  %s  ", self::DB_TABLE_NAME,
                            [$this->where_str_gen($where_arr)] );
        return $this->main_get_list_by_page($sql,$page_num);
        
    }

}











