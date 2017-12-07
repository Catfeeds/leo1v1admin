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
        $sql = $this->gen_sql("select an.*,k.title from %s an
                              left join %s k on an.knowledge_id = k.knowledge_id
                              where  %s order by step asc ",
                              self::DB_TABLE_NAME,
                              t_knowledge_point::DB_TABLE_NAME,
                              [$where_str]
        );
        return  $this->main_get_list($sql);
    }

    public function del_by_id($id){
        $sql=$this->gen_sql("delete from %s where knowledge_id=%u"
                            ,self::DB_TABLE_NAME
                            ,$id
        );
        return $this->main_update($sql);
    }

    
}











