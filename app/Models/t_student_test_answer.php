<?php
namespace App\Models;
class t_student_test_answer extends \App\Models\Zgen\z_t_student_test_answer
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_list($paper_id,$subject,$grade,$book,$volume,$start_time,$end_time,$page_info){
        $where_arr = [
            ['paper_id=%u', $paper_id, -1],
            ['subject=%u', $subject, -1],
            ['grade=%u', $grade, -1],
            ['book=%u', $book, -1],
            ['volume=%u', $volume, -1],
            ['modify_time>=%u',$start_time,-1],
            ['modify_time<=%u',$end_time,-1],

        ];
        $sql = $this->gen_sql_new("select * from %s where %s order by paper_id desc",
                                  self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list_by_page($sql,$page_info,10,true);
    }

    public function get_last_answer(){
        $sql=$this->gen_sql_new("select * from %s order by id desc limit 0,1"
                                ,self::DB_TABLE_NAME);
        return $this->main_get_row($sql);
    }

   
}











