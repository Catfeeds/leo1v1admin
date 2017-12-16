<?php
namespace App\Models;
use \App\Enums as E;
class t_knowledge_point extends \App\Models\Zgen\z_t_knowledge_point
{
	public function __construct()
	{
		parent::__construct();
	}

    public function knowledge_list($where_arr,$page_num){
        $where_str = $this->where_str_gen($where_arr);
        $sql = $this->gen_sql("select point.*,level.* from %s point
                              left join %s level on point.knowledge_id = level.knowledge_id
                              where  %s order by point.knowledge_id desc ",
                              self::DB_TABLE_NAME,
                              t_knowledge_level::DB_TABLE_NAME,
                              [$where_str]
        );
        return  $this->main_get_list_by_page($sql,null);
    }

    public function knowledge_get($where_arr,$page_num){
        $where_str = $this->where_str_gen($where_arr);
        $sql = $this->gen_sql("select knowledge_id as id,title,subject,detail from %s where  %s order by knowledge_id desc ",
                              self::DB_TABLE_NAME,
                              [$where_str]
        );
        return  $this->main_get_list_by_page($sql,$page_num,10);
    }

    public function del_by_id($id){
        $sql=$this->gen_sql("delete from %s where knowledge_id=%u"
                            ,self::DB_TABLE_NAME
                            ,$id
        );
        return $this->main_update($sql);
 
    }

    public function get_by_id($id){
        $sql=$this->gen_sql("select * from %s where knowledge_id=%u"
                            ,self::DB_TABLE_NAME
                            ,$id
        );
        return $this->main_get_row($sql);
 
    }

}











