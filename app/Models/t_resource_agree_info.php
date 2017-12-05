<?php
namespace App\Models;
use \App\Enums as E;
class t_resource_agree_info extends \App\Models\Zgen\z_t_resource_agree_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_agree_resource(){
        $sql = $this->gen_sql_new(
            "select agree_id,resource_type,subject,grade,tag_one,tag_two,tag_three,tag_four,is_ban from %s"
            ." order by resource_type,subject,grade,tag_one,tag_two,tag_three,tag_four"
            , self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }
}











