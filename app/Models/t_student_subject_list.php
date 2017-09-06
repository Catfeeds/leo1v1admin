<?php
namespace App\Models;
use \App\Enums as E;
class t_student_subject_list extends \App\Models\Zgen\z_t_student_subject_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_info_by_userid($userid){
        $where_arr=[
            ["userid=%u",$userid,-1]  
        ];
        $sql = $this->gen_sql_new("select * from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }

}











