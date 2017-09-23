<?php
namespace App\Models;
use \App\Enums as E;
class t_grab_lesson_link_info extends \App\Models\Zgen\z_t_grab_lesson_link_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_grabid_by_link($link){
        $sql = $this->gen_sql_new("select grabid"
                                  ." from %s"
                                  ." where grab_lesson_link='%s'"
                                  ,self::DB_TABLE_NAME
                                  ,$link
        );
        return $this->main_get_value($sql);

    }

}











