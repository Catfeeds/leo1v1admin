<?php
namespace App\Models;
class t_user_lesson_account_lesson extends \App\Models\Zgen\z_t_user_lesson_account_lesson
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_list($lesson_account_id, $page_num) {
        $sql=$this->gen_sql("select t1.lessonid, t1.lesson_num, t1.price, t1.real_price, t1.reason, t2.lesson_start, t2.lesson_end,t2.teacherid ,t2.lesson_status,assistantid from %s t1, %s t2 where t1.lessonid=t2.lessonid and  lesson_account_id=%u and lesson_del_flag=0 order by t1.lessonid desc",
                            self::DB_TABLE_NAME,Zgen\z_t_lesson_info::DB_TABLE_NAME,
                            $lesson_account_id
        );
        return $this->main_get_list_by_page($sql,$page_num,10);
    }



}











