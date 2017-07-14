<?php
namespace App\Models;
use \App\Enums as E;
class t_user_login_log extends \App\Models\Zgen\z_t_user_login_log
{
	public function __construct()
	{
		parent::__construct();
	}
   
    public function login_list ($page_info, $userid){
        $sql = $this->gen_sql_new("select * "
                                  ." from %s "
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }
    
    

    
}











