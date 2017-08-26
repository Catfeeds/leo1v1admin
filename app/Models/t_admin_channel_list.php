<?php
namespace App\Models;
use \App\Enums as E;
class t_admin_channel_list extends \App\Models\Zgen\z_t_admin_channel_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_admin_channel_info(){
        $sql = $this->gen_sql_new("select channel_id,channel_name"
                           ." from %s",
                           self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

}











