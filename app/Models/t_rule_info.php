<?php
namespace App\Models;
use \App\Enums as E;
class t_rule_info extends \App\Models\Zgen\z_t_rule_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_rule($start_time, $end_time,$page_info){
        $where_arr = [
            ['create_time>=%u',$start_time, -1],
            ['create_time<%u',$end_time, -1],
        ];
        $sql = $this->gen_sql_new("select rule_id,title,tip,create_time from %s where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_rule_info($rule_id){
        $where_arr = ["rule_id=$rule_id"];
        $sql = $this->gen_sql_new("select rule_id,title,tip,create_time from %s where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }


}
