<?php
namespace App\Models;
use \App\Enums as E;
class t_test_lesson_subject_require_review extends \App\Models\Zgen\z_t_test_lesson_subject_require_review
{
	public function __construct()
	{
		parent::__construct();
	}
    public function get_all_list($page_info){
        $where_arr = [
        ];
        $sql=$this->gen_sql_new (" select *"
                                 ." from %s "
                                 ." where %s "
                                 ,self::DB_TABLE_NAME
                                 ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }
}











