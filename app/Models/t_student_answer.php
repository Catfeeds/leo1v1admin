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
            ["sa.room_id=%d" , $room_id ],
        ];
        $where_str = $this->where_str_gen($where_arr);
        $sql = $this->gen_sql("select sa.score,sa.question_id,a.score as full_score,a.answer_type,group_concat(k.knowledge_id)
                              as know_str from %s sa
                              left join %s a on sa.step_id = a.step_id
                              left join %s k on a.step_id = k.answer_id
                              where %s order by a.answer_no asc"
                              ,self::DB_TABLE_NAME
                              ,t_answer::DB_TABLE_NAME
                              ,t_question_knowledge::DB_TABLE_NAME
                              ,$where_str );
        return $this->main_get_list($sql);
    }

    public function get_answer_count($teacher_id,$student_id){
        $where_arr = [
            ["sa.room_id=%d" , $room_id ]
        ];
        $where_str = $this->where_str_gen($where_arr);
        $sql = $this->gen_sql("select count(sa.question_id) as count from %s as inner join %s q on
                               sa.question_id = q.question_id where %s group by sa.question_id"
                              ,self::DB_TABLE_NAME
                              ,t_question::DB_TABLE_NAME
                              ,$where_str );
        return $this->main_get_row($sql);
    }
}











