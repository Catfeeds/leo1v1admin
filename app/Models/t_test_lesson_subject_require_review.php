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

    public function get_row_by_adminid_userid($adminid,$userid){
        $where_arr = [
            ['adminid = %u',$adminid,-1],
            ['userid = %u',$userid,-1],
            'group_suc_flag = 1',
            'master_suc_flag = 1',
        ];
        $sql=$this->gen_sql_new (" select * "
                                 ." from %s "
                                 ." where %s limit 1 "
                                 ,self::DB_TABLE_NAME
                                 ,$where_arr
        );
        return $this->main_get_row($sql);
    }
}











