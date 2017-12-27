<?php
namespace App\Models;
use \App\Enums as E;
class t_student_answer extends \App\Models\Zgen\z_t_student_answer
{
	public function __construct()
	{
		parent::__construct();
	}

    public function student_answer_list($where_arr,$page_num){
        $where_str = $this->where_str_gen($where_arr);
        $sql = $this->gen_sql("select * from %s where  %s order by id desc ",
                              self::DB_TABLE_NAME,
                              [$where_str]
        );
        return  $this->main_get_list_by_page($sql,null);
    }

    public function del_by_id($id){
        $sql=$this->gen_sql("delete from %s where id=%u"
                            ,self::DB_TABLE_NAME
                            ,$id
        );
        return $this->main_update($sql);
 
    }

    public function get_by_id($id){
        $sql=$this->gen_sql("select * from %s where id=%u"
                            ,self::DB_TABLE_NAME
                            ,$id
        );
        return $this->main_get_row($sql); 
    }

    public function get_answer_scores($room_id){
        $where_arr = [
            ["sa.room_id=%d" , $room_id ]
        ];
        $where_str = $this->where_str_gen($where_arr);
        $sql = $this->gen_sql("select sa.*,q.score as count_score from %s sa
                               left join %s q on sa.question_id = q.question_id where %s limit 30"
                              ,self::DB_TABLE_NAME
                              ,t_question::DB_TABLE_NAME
                              ,$where_str );
        return $this->main_get_list($sql);
    }

    public function get_answer_count($teacher_id,$student_id){
        $where_arr = [
            ["sa.room_id=%d" , $room_id ]
        ];
        $where_str = $this->where_str_gen($where_arr);
        $sql = $this->gen_sql("select count(question_id) from %s  where %s group by question_id"
                              ,self::DB_TABLE_NAME
                              ,$where_str );
        return $this->main_get_row($sql);
    }
}











