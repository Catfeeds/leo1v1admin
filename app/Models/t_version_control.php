<?php
namespace App\Models;
use \App\Enums as E;
class t_version_control extends \App\Models\Zgen\z_t_version_control
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_list($page_info,$start_time,$end_time){
        $where_arr = [
            ["publish_time>%u",$start_time,-1],
            ["publish_time<%u",$end_time,-1]
        ];
        $sql = $this->gen_sql_new(" select * from %s  where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }


}











