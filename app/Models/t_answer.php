<?php
namespace App\Models;
use \App\Enums as E;
class t_answer extends \App\Models\Zgen\z_t_answer
{
	public function __construct()
	{
		parent::__construct();
	}

    public function answer_list($where_arr){
        $where_str = $this->where_str_gen($where_arr);
        $sql = $this->gen_sql("select * from %s where  %s order by answer_type asc,step asc ",
                              self::DB_TABLE_NAME,
                              [$where_str]
        );
        return  $this->main_get_list($sql);
    }

    public function del_by_id($id){
        $sql=$this->gen_sql("delete from %s where answer_id=%u"
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

    public function is_exit_step($question_id,$step){
        $sql = $this->gen_sql("select answer_id,detail from %s 
                              where  question_id = %d and step = %d",
                              self::DB_TABLE_NAME,
                              $question_id,
                              $step
        );
        return  $this->main_get_row($sql);
    }

    public function is_exit_edit_step($answer_id,$question_id,$step){
        $sql = $this->gen_sql("select answer_id,detail from %s 
                              where  answer_id != %d and question_id = %d and step = %d",
                              self::DB_TABLE_NAME,
                              $answer_id,
                              $question_id,
                              $step
        );
        return  $this->main_get_row($sql);

    }
}











