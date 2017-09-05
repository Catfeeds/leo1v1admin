<?php
namespace App\Models;
use \App\Enums as E;
class t_wx_share extends \App\Models\Zgen\z_t_wx_share
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_share_flag($teacherid){
        $sql = $this->gen_sql_new(" select 1 from %s tw where teacherid = $teacherid"
                                  ,self::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }


}











