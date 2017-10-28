<?php
namespace App\Models;
use \App\Enums as E;
class t_ruffian_share extends \App\Models\Zgen\z_t_ruffian_share
{
	public function __construct()
	{
		parent::__construct();
	}


    public function delete_row_by_pid($parentid){
        $sql = $this->gen_sql_new("  delete from %s t where parentid=%d"
                                  ,self::DB_TABLE_NAME
                                  ,$parentid
        );

        return $this->main_update($sql);
    }
}











