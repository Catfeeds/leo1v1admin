<?php
namespace App\Models;
use \App\Enums as E;
class t_process_info extends \App\Models\Zgen\z_t_process_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_process($start_time, $end_time){
        $where_arr = [
            ['create_time>=%u',$start_time, -1],
            ['create_time<%u',$end_time, -1],
        ];
        $sql = $this->gen_sql_new("select process_id,name,create_time from %s where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_process_info($process_id){
        $where_arr = [
            ['process_id=%u',$process_id, -1],
        ];
        $sql = $this->gen_sql_new(
            "select process_id, name, fit_range, department, pro_explain, attention, pro_img, create_time from  %s  where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_row($sql);
    }
}











