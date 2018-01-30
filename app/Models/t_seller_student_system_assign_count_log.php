<?php
namespace App\Models;
use \App\Enums as E;
class t_seller_student_system_assign_count_log extends \App\Models\Zgen\z_t_seller_student_system_assign_count_log
{
	public function __construct()
	{
		parent::__construct();
	}
    public function get_last_item() {
        $sql= $this->gen_sql_new("select * from %s order by logtime desc limit 1 ", self::DB_TABLE_NAME );
        return $this->main_get_row($sql);
    }

}











