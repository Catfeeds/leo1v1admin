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
        $sql = $this->gen_sql("select kq.id,kq.difficult,kq.kownledge_id,kn.subject,kn.title
                               from %s kq left join %s kn on kq.kownledge_id = kn.kownledge_id
                               where kq.question_id = %s
                               order by question_id desc ",
                              self::DB_TABLE_NAME,
                              t_kownledge_point::DB_TABLE_NAME,
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
}











