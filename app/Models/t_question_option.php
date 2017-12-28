<?php
namespace App\Models;
use \App\Enums as E;
class t_question_option extends \App\Models\Zgen\z_t_question_option
{
	public function __construct()
	{
		parent::__construct();
	}
    public function question_option_list($question_id){         
        $where_arr=[
            ["question_id=%u", $question_id]
        ];
        
        $where_str=$this->where_str_gen( $where_arr);
        $sql = $this->gen_sql("select * from %s where %s order by id asc ",                             
                              self::DB_TABLE_NAME,
                              $where_str
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

}











