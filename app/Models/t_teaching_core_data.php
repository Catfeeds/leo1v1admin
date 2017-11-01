<?php
namespace App\Models;
use \App\Enums as E;
class t_teaching_core_data extends \App\Models\Zgen\z_t_teaching_core_data
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_info($type){
        $where_arr=[
            ["type=%u",$type,-1]  
        ];
        $sql = $this->gen_sql_new("select * from %s where %s order by time",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list_as_page($sql);
    }
}











