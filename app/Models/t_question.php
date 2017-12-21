<?php
namespace App\Models;
use \App\Enums as E;
class t_question extends \App\Models\Zgen\z_t_question
{
	public function __construct()
	{
		parent::__construct();
	}

    public function question_list($where_arr,$page_num){
        $where_str = $this->where_str_gen($where_arr);
        $sql = $this->gen_sql("select qu.*,qt.name as question_type_str from %s qu
                              left join %s qt on qu.question_type = qt.id where  %s order by qu.question_id desc ",
                              self::DB_TABLE_NAME,
                              t_question_type::DB_TABLE_NAME,
                              [$where_str]
        );
        return  $this->main_get_list_by_page($sql,$page_num,10);
    }

    public function question_get($knowledge_str,$question_type,$question_resource_type,$difficult,$page_num){
        $where_arr = [
            ['know.knowledge_id in %s' , $knowledge_str ],
            ["qu.question_type=%u", $question_type, -1] ,
            ["qu.question_resource_type=%u", $question_resource_type, -1] ,
            ["qu.difficult=%u", $difficult, -1] ,
        ];

        $where_str = $this->where_str_gen($where_arr);
        $sql = $this->gen_sql("select distinct(qu.question_id),qu.*,qt.name as question_type_str from %s qu
                              left join %s know on qu.question_id = know.question_id
                              left join %s qt on qu.question_type = qt.id
                              where  %s order by qu.question_id desc ",
                              self::DB_TABLE_NAME,
                              t_question_knowledge::DB_TABLE_NAME,
                              t_question_type::DB_TABLE_NAME,
                              [$where_str]
        );
        return  $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_question_info($question_id){
        $where_arr = [
            ["question_id=%d" , $question_id ],
        ];
        $where_str = $this->where_str_gen($where_arr);
        $sql = $this->gen_sql("select * from %s where  %s ",
                              self::DB_TABLE_NAME,
                              [$where_str]
        );
        return  $this->main_get_row($sql);

    }

    public function del_by_id($question_id){
        $sql=$this->gen_sql("delete from %s where question_id=%u"
                            ,self::DB_TABLE_NAME
                            ,$question_id
        );
        return $this->main_update($sql);
    }

    public function get_by_id($question_id){
        $sql=$this->gen_sql("select * from %s where question_id=%u"
                            ,self::DB_TABLE_NAME
                            ,$question_id
        );
        return $this->main_get_row($sql);
    }

}











