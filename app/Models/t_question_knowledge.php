<?php
namespace App\Models;
use \App\Enums as E;
class t_question_knowledge extends \App\Models\Zgen\z_t_question_knowledge
{
	public function __construct()
	{
		parent::__construct();
	}

    public function question_know_list($question_id){
        $sql = $this->gen_sql("select * from %s where question_id = %s and type = 1
                               order by question_id desc ",
                              self::DB_TABLE_NAME,
                              $question_id
        );

        return $this->main_get_list($sql);
    }

    public function answer_know_list($answer_id){
        $sql = $this->gen_sql("select * from %s where answer_id = %s and type = 2
                               order by question_id desc ",
                              self::DB_TABLE_NAME,
                              $answer_id
        );

        return $this->main_get_list($sql);
    }


    public function is_question_know_exit($question_id,$knowledge_id,$difficult){
        $sql = $this->gen_sql("select * from %s 
                               where question_id = %s and knowledge_id = %d and difficult = %d",
                              self::DB_TABLE_NAME,
                              $question_id,
                              $knowledge_id,
                              $difficult
        );

        return $this->main_get_row($sql);

    }

    public function del_by_id($id){
        $sql=$this->gen_sql("delete from %s where id=%u"
                            ,self::DB_TABLE_NAME
                            ,$id
        );
        return $this->main_update($sql);
 
    }

    public function del_by_question_id($question_id){
        $sql=$this->gen_sql("delete from %s where question_id=%u"
                            ,self::DB_TABLE_NAME
                            ,$question_id
        );
        return $this->main_update($sql);
    }
}











