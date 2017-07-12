<?php
namespace App\Models;
use \App\Enums as E;
class t_lecture_revisit_info extends \App\Models\Zgen\z_t_lecture_revisit_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_lecture_revisit_info_by_phone($phone){
        $sql = $this->gen_sql_new("select * from %s where phone = '%s'",
                                  self::DB_TABLE_NAME,
                                  $phone
        );
        return $this->main_get_list($sql);
    }
}











