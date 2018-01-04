<?php
namespace App\Models;
use \App\Enums as E;
class t_seller_new_count_get_detail extends \App\Models\Zgen\z_t_seller_new_count_get_detail
{
	public function __construct()
	{
		parent::__construct();
	}
    public function add( $new_count_id, $get_desc ) {
        $this->row_insert([
            "new_count_id"  => $new_count_id,
            "get_time"  =>time(NULL) ,
            "get_desc"  =>$get_desc ,
        ]);
    }

    public function rwo_del_by_detail_id($id){
        $sql=sprintf("delete from %s  where  detail_id='%s' ",
                     self::DB_TABLE_NAME,
                     $this->ensql($id));
        return $this->main_update($sql);
    }

}











