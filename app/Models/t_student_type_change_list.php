<?php
namespace App\Models;
use \App\Enums as E;
class t_student_type_change_list extends \App\Models\Zgen\z_t_student_type_change_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_info_by_userid($userid){
        $where_arr=[
            ["userid = %u",$userid,-1]  
        ];
        $sql = $this->gen_sql_new("select * from %s where %s order by add_time desc",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }
    
    public function get_info_by_userid_last($userid){
        $where_arr=[
            ["userid = %u",$userid,-1]  
        ];
        $sql = $this->gen_sql_new("select * from %s "
                                  ." where %s and add_time = (select max(add_time) from %s where userid=%u)"
                                  ." order by add_time desc",
                                  self::DB_TABLE_NAME,
                                  $where_arr,
                                  self::DB_TABLE_NAME,
                                  $userid
        );
        return $this->main_get_row($sql);
    }

}











