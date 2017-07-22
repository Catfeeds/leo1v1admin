<?php
namespace App\Models;
use \App\Enums as E;
class t_ass_warning_renw_flag_modefiy_list extends \App\Models\Zgen\z_t_ass_warning_renw_flag_modefiy_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_info_by_warning_id($warning_id){
        $sql = $this->gen_sql_new("select * from %s where warning_id = %u order by add_time desc",self::DB_TABLE_NAME,$warning_id);
        return $this->main_get_list($sql);
    }

}











