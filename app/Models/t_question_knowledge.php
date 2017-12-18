<?php
namespace App\Models;
use \App\Enums as E;
class t_question_knowledge extends \App\Models\Zgen\z_t_question_knowledge
{
	public function __construct()
	{
		parent::__construct();
	}

    public function question_know_get($question_id){
        $sql = $this->gen_sql("select know.knowledge_id,know.title from %s qa left join %s know on qa.knowledge_id = know.knowledge_id
                               where qa.question_id = %s and qa.type = 1
                               order by qa.question_id desc ",
                              self::DB_TABLE_NAME,
                              t_knowledge_point::DB_TABLE_NAME,
                              $question_id
        );

        return $this->main_get_list($sql);
    }

    public function answer_know_get($answer_id){
        $sql = $this->gen_sql("select know.knowledge_id,know.title from %s qa left join %s know on qa.knowledge_id = know.knowledge_id
                               where qa.answer_id = %s and qa.type = 2
                               order by qa.answer_id desc ",
                              self::DB_TABLE_NAME,
                              t_knowledge_point::DB_TABLE_NAME,
                              $answer_id
        );

        return $this->main_get_list($sql);

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

    public function dele_by_id_arr($id,$deleArr,$type){
        if( $type == 1 ){
            foreach( $deleArr as $know){
                $sql=$this->gen_sql("delete from %s where question_id = %s and knowledge_id = %s and type =%d"
                                    ,self::DB_TABLE_NAME,$id,$know,$type);

                $this->main_update($sql);
            }
        }

        if( $type == 2 ){
            foreach( $deleArr as $know){
                $sql=$this->gen_sql("delete from %s where answer_id = %s and knowledge_id = %s and type =%d"
                                    ,self::DB_TABLE_NAME,$id,$know,$type);

                $this->main_update($sql);
            }
        }

    }

    public function add_id_arr($id,$addArr,$type){
        
        $sql = "insert into %s ( question_id, answer_id, difficult, type, knowledge_id ) values ";
        if( $type == 1 ){
            foreach( $addArr as $know){
                $sql .= " ( ".$id.", 0 , 0 , ".$type." , ".$know." ),";
            }
        }
        if( $type == 2 ){
            foreach( $addArr as $know){
                $sql .= " ( 0 , ".$id." , 0 , ".$type." , ".$know." ),";
            }
        }
        $sqlwhole = substr($sql, 0,-1);
        //dd($sqlwhole);
        $sql=$this->gen_sql($sqlwhole,self::DB_TABLE_NAME);

        $this->main_update($sql);

    }

}











