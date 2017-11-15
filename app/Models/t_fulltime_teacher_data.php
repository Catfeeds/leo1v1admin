<?php
namespace App\Models;
use \App\Enums as E;
class t_fulltime_teacher_data extends \App\Models\Zgen\z_t_fulltime_teacher_data
{
	public function __construct()
	{
		parent::__construct();
	}
    public function get_info_by_type_and_time($type,$create_time){
        $where_arr = [
            ["create_time=%u",$create_time,-1],
            ["teacher_type=%u",$type,-1]
        ];
        $sql = $this->gen_sql_new("select id from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);
    }

    public function get_info_by_time($start_time){
        $where_arr = [
            ["create_time=%u",$start_time,-1],
        ];
        $sql = $this->gen_sql_new("select * from %s where %s order by id",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }
}