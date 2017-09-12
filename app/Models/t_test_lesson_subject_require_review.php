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
        $sql=$this->gen_sql_new (" select r.*,"
                                 ." s.phone,s.nick "
                                 ." from %s r "
                                 ." left join %s s on s.userid = r.userid"
                                 ." where %s "
                                 ,self::DB_TABLE_NAME
                                 ,t_student_info::DB_TABLE_NAME
                                 ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }
}











