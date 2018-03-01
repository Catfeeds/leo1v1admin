<?php
namespace App\Models;
class t_student_test_paper extends \App\Models\Zgen\z_t_student_test_paper
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_list($paper_id,$subject,$grade,$book,$volume,$start_time,$end_time,$page_info){
        $where_arr = [
            ['p.paper_id=%u', $paper_id, -1],
            ['p.subject=%u', $subject, -1],
            ['p.grade=%u', $grade, -1],
            ['p.book=%u', $book, -1],
            ['p.volume=%u', $volume, -1],
            ['p.modify_time>=%u',$start_time,-1],
            ['p.modify_time<=%u',$end_time,-1],

        ];
        $sql = $this->gen_sql_new("select p.*,group_concat(a.id) as use_arr from %s p
                                  left join %s a on p.paper_id = a.paper_id
                                  where %s
                                  group by p.paper_id order by p.paper_id desc",
                                  self::DB_TABLE_NAME,
                                  t_student_test_answer::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_list_by_page($sql,$page_info,10,true);
    }

    public function check_paper_exist($paper_id){
        $sql=$this->gen_sql_new("select paper_id from %s where paper_id=%u"
                                ,self::DB_TABLE_NAME
                                ,$paper_id
        );
        return $this->main_get_value($sql);
    }

    public function get_paper($paper_id){
        $sql=$this->gen_sql_new("select * from %s where paper_id=%u"
                                ,self::DB_TABLE_NAME
                                ,$paper_id
        );
        return $this->main_get_row($sql);

    }

    public function dele_paper($paper_id){
        $sql=$this->gen_sql_new("delete from %s where paper_id=%u"
                                ,self::DB_TABLE_NAME
                                ,$paper_id
        );

        return $this->main_update($sql);
    }

    public function get_papers($subject,$grade,$book,$page_num){
        $where_arr = [
            ['subject=%u', $subject, -1],
            ['grade=%u', $grade, -1],
            ['book=%u', $book, -1],

        ];
        $sql = $this->gen_sql_new("select * from %s where %s order by paper_id desc",
                                  self::DB_TABLE_NAME,$where_arr);
        //echo $sql;
        return $this->main_get_list_by_page($sql,$page_num,10,true);

    }
}











