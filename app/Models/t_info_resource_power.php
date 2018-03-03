<?php
namespace App\Models;
class t_info_resource_power extends \App\Models\Zgen\z_t_info_resource_power
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_list() {
        $where_arr = [
            ["is_del = %u", 0 ],
        ];
        $sql=$this->gen_sql_new("select * from %s where %s order by resource_id asc,type_id asc",
                                self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }

}











