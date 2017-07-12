<?php
namespace App\Models;
use \App\Enums as E;
class t_user_origin_info extends \App\Models\Zgen\z_t_user_origin_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function check_phone($phone){
        $sql=$this->gen_sql_new("select count(1) from %s where phone=%u"
                                ,self::DB_TABLE_NAME
                                ,$phone
        );
        return $this->main_get_value($sql);
    }

}
