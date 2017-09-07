<?php
namespace App\Models;
use \App\Enums as E;
class t_location_subject_grade_textbook_info extends \App\Models\Zgen\z_t_location_subject_grade_textbook_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_info($page_info){
        $sql = $this->gen_sql_new("select * from %s",self::DB_TABLE_NAME);
        return $this->main_get_list_by_page($sql,$page_info);
    }

}











