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
        $sql = $this->gen_sql("select kq.id,kq.difficult,kq.knowledge_id,kn.subject,kn.title,kn.detail
                               from %s kq left join %s kn on kq.knowledge_id = kn.knowledge_id
                               where kq.question_id = %s
                               order by question_id desc ",
                              self::DB_TABLE_NAME,
                              t_knowledge_point::DB_TABLE_NAME,
                              $question_id
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

}











