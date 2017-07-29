<?php
namespace App\Models;
use \App\Enums as E;
class t_seller_edit_log extends \App\Models\Zgen\z_t_seller_edit_log
{
	public function __construct()
	{
		parent::__construct();
	}
    public function get_all_list($adminid){
        $where_arr = [];
        if($adminid){
            $this->where_arr_add_int_or_idlist($where_arr,'adminid',$adminid);
        }
        $sql = $this->gen_sql_new (
            " select * "
            ." from %s where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_list($sql);
    }
}











