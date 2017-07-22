<?php
namespace App\Models;
use \App\Enums as E;
class t_ass_weekly_info extends \App\Models\Zgen\z_t_ass_weekly_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_info($week,$time_type=1){
        $where_arr=[
            ["week=%u",$week,-1], 
            ["time_type=%u",$time_type,-1]  
        ];
        $sql = $this->gen_sql_new("select * from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql,function($item){
            return $item["adminid"];
        });
    }

    public function get_id_by_unique_record($adminid,$week,$time_type){
        $where_arr=[
            ["week=%u",$week,-1], 
            ["time_type=%u",$time_type,-1],
            ["adminid=%u",$adminid,-1],
        ];
        $sql = $this->gen_sql_new("select id from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);
    }

    public function get_all_info_by_time($start_time,$end_time,$time_type=1){
        $where_arr=[
            ["week>=%u",$start_time,-1], 
            ["week<%u",$end_time,-1], 
            ["time_type=%u",$time_type,-1]  
        ];
        $sql = $this->gen_sql_new("select * from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);

    }
}











