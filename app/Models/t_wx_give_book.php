<?php
namespace App\Models;
use \App\Enums as E;
class t_wx_give_book extends \App\Models\Zgen\z_t_wx_give_book
{
	public function __construct()
	{
		parent::__construct();
	}

    public function check_share_flag($openid){
        $sql = $this->gen_sql_new("  select share_num from %s "
                                  ." where openid = '$openid'"
                                  ,self::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }

    public function row_delete_by_parentid($openid){
        $sql = $this->gen_sql_new("   delete from %s where openid = $openid "
                                  ,self::DB_TABLE_NAME
        );

        return $this->main_update($sql);
    }

}











