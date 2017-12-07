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
        $sql = $this->gen_sql("select * from %s where  %s order by question_id desc ",
                              self::DB_TABLE_NAME,
                              [$where_str]
        );
        return  $this->main_get_list_by_page($sql,$page_num,10);
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
}











