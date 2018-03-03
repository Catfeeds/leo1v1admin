<?php
namespace App\Models;
use \App\Enums as E;
class t_leave_lesson_log extends \App\Models\Zgen\z_t_leave_lesson_log
{
	public function __construct()
	{
		parent::__construct();
	}

    public function getCheckFlag($lessonid){
        $sql = $this->gen_sql_new("  select 1 from %s where lessonid=$lessonid"
                                  ,self::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }
}











