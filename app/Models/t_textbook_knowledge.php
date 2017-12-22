<?php
namespace App\Models;
use \App\Enums as E;
class t_textbook_knowledge extends \App\Models\Zgen\z_t_textbook_knowledge
{
	public function __construct()
	{
		parent::__construct();
	}

    public function textbook_knowledge_get($textbook_id,$grade,$subject){
        $sql = $this->gen_sql("select know.knowledge_id,know.title,level.father_id from %s qa
                               left join %s know on qa.knowledge_id = know.knowledge_id
                               left join %s level on qa.knowledge_id = level.knowledge_id
                               where qa.textbook_id = %s and qa.grade = %s and qa.subject = %d 
                               order by know.knowledge_id asc ",
                              self::DB_TABLE_NAME,
                              t_knowledge_point::DB_TABLE_NAME,
                              t_knowledge_level::DB_TABLE_NAME,
                              $textbook_id,
                              $grade,
                              $subject
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


    public function dele_by_id_arr($textbook_id,$grade,$subject,$deleArr){
        $num = 0;
        foreach( $deleArr as $know){
            $sql=$this->gen_sql("delete from %s where textbook_id = %d and grade = %d and subject = %d and knowledge_id = %d"
                                ,self::DB_TABLE_NAME,$textbook_id,$grade,$subject,$know );
            $num += $this->main_update($sql);
        }
        return $num;
    }

    public function add_id_arr($textbook_id,$grade,$subject,$addArr){
        
        $sql = "insert into %s ( textbook_id, subject, grade, knowledge_id ) values ";
        
        foreach( $addArr as $know){
            $sql .= " (".$textbook_id.",".$subject.",".$grade.",".$know."),";
        }
        
        $sqlwhole = substr($sql, 0,-1);
        //dd($sqlwhole);
        $sql=$this->gen_sql($sqlwhole,self::DB_TABLE_NAME);

        return $this->main_update($sql);

    }

}











