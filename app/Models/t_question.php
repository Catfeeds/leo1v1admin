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
        $question_id_str = '';
        if(!empty($knowledge_str)){
            $where_arr[] = ['knowledge_id in %s' , $knowledge_str ];
            $where_str = $this->where_str_gen($where_arr);
            $question_sql = $this->gen_sql("select question_id from %s where %s",t_question_knowledge::DB_TABLE_NAME,[$where_str]);
            $question_arr = $this->main_get_list($question_sql);
         
            if($question_arr){
                $question_arr = array_column($question_arr, 'question_id');               
                $question_arr = array_unique($question_arr);
                $question_id_str = '('.implode(',',$question_arr).')';
            }
        }

        //dd($question_id_str);
        $where_arr = [
            ["qu.question_type=%u", $question_type, -1] ,
            ["qu.question_resource_type=%u", $question_resource_type, -1] ,
            ["qu.difficult=%u", $difficult, -1] ,
        ];
        if(!empty($question_id_str)){
            $where_arr[] = ['qu.question_id in %s' , $question_id_str ];
        }
        $where_str = $this->where_str_gen($where_arr);
        $sql = $this->gen_sql("select qu.*,qt.name as question_type_str from %s qu
                              left join %s qt on qu.question_type = qt.id
                              where %s order by qu.question_id desc ",
                              self::DB_TABLE_NAME,
                              t_question_type::DB_TABLE_NAME,
                              [$where_str]
        );
        return  $this->main_get_list_by_page($sql,$page_num);
    }

    public function question_get_by_id($kn,$difficult_str,$question_str,$question_type,$question_resource_type,$difficult){
        $where_arr = [
            ['know.knowledge_id = %s' , $kn ],
            ["qu.question_id not in %s", $question_str ] ,
            ["qu.question_type=%u", $question_type, -1] ,
            ["qu.question_resource_type=%u", $question_resource_type, -1] ,
            ["qu.difficult=%u", $difficult, -1] ,
        ];

        $where_str = $this->where_str_gen($where_arr);
        $sql = $this->gen_sql("select distinct(qu.question_id),qu.*,qt.name as question_type_str from %s qu
                              left join %s know on qu.question_id = know.question_id
                              left join %s qt on qu.question_type = qt.id
                              where  %s order by FIELD%s,qu.question_id desc limit 10",
                              self::DB_TABLE_NAME,
                              t_question_knowledge::DB_TABLE_NAME,
                              t_question_type::DB_TABLE_NAME,
                              [$where_str],
                              $difficult_str
        );
        return  $this->main_get_list($sql);

    }

    public function question_check($question_id,$subject,$question_type){
        $where_arr = [
            ['subject = %u' , $subject ],
            //["question_type=%u", $question_type] ,
            ["open_flag=%u", 1] ,
        ];
        if(!empty($question_id)){
            $where_arr[] = ["question_id != %u",$question_id];
        }
        $where_str = $this->where_str_gen($where_arr);
        $sql = $this->gen_sql("select question_id,title,detail from %s where %s order by question_id asc",
                              self::DB_TABLE_NAME,[$where_str]);
        return $this->main_get_list($sql);
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











