<?php
namespace App\Models;
use \App\Enums as E;
class t_psychological_teacher_time_list extends \App\Models\Zgen\z_t_psychological_teacher_time_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_info(){
        $sql = $this->gen_sql_new("select * from %s ",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

    public function get_info_by_time($start_time,$end_time){
        $where_arr=[
            ["day>=%u",$start_time,0],
            ["day<%u",$end_time,0],
        ];
        $sql = $this->gen_sql_new("select * from %s where %s ",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
 
    }
}











