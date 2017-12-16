<?php
namespace App\Models;
use \App\Enums as E;
class t_textbook extends \App\Models\Zgen\z_t_textbook
{
	public function __construct()
	{
		parent::__construct();
	}

    public function textbook_list($textbook_id){
        $sql = $this->gen_sql("select * from %s order by textbook_id asc ",
                              self::DB_TABLE_NAME,
                              $textbook_id
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











