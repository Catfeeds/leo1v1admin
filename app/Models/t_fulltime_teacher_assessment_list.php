<?php
namespace App\Models;
use \App\Enums as E;
class t_fulltime_teacher_assessment_list extends \App\Models\Zgen\z_t_fulltime_teacher_assessment_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_info_by_adminid($adminid){
        $where_arr=[
            ["f.adminid=%u",$adminid,-1],
        ];
        $sql = $this->gen_sql_new("select * from %s f where %s and  not exists(select 1 from %s where f.adminid=adminid and f.add_time<add_time)",
                                  self::DB_TABLE_NAME,
                                  $where_arr,
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_row($sql);
    }
}











