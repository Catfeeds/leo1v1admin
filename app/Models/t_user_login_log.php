<?php
namespace App\Models;
use \App\Enums as E;
class t_user_login_log extends \App\Models\Zgen\z_t_user_login_log
{
	public function __construct()
	{
		parent::__construct();
	}
   
    public function login_list ($userid){
        $sql = $this->gen_sql_new("select * "
                                  ." from %s "
                                  // ." where userid=%u"
                                  ,self::DB_TABLE_NAME
                                  //,$userid
        );
        //echo $sql;exit;
        return $this->main_get_list_as_page($sql);
    }


}











