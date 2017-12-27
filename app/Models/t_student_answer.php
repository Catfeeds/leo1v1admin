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
        $sql = $this->gen_sql("select sa.id,sa.score,sa.question_id,sa.step_id,a.score as full_score,a.answer_type,
                              group_concat(k1.knowledge_id) as step_know,group_concat(k2.knowledge_id) as qu_know from %s sa
                              left join %s a on sa.step_id = a.step_id
                              left join %s k1 on sa.step_id = k1.answer_id
                              left join %s k2 on sa.question_id = k2.question_id
                              where sa.room_id = '%s' group by sa.id order by a.answer_no asc"
                              ,self::DB_TABLE_NAME
                              ,t_answer::DB_TABLE_NAME
                              ,t_question_knowledge::DB_TABLE_NAME
                              ,t_question_knowledge::DB_TABLE_NAME
                              ,$room_id );
        return $this->main_get_list($sql);
    }


    public function get_answer_count($room_id){
        $sql = $this->gen_sql("select count(sa.question_id) as count from %s sa inner join %s q on
                               sa.question_id = q.question_id where sa.room_id = '%s' group by sa.question_id"
                              ,self::DB_TABLE_NAME
                              ,t_question::DB_TABLE_NAME
                              ,$room_id );
        return $this->main_get_row($sql);
    }
}











