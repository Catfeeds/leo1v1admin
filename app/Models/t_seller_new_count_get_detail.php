<?php
namespace App\Models;
use \App\Enums as E;
class t_seller_new_count_get_detail extends \App\Models\Zgen\z_t_seller_new_count_get_detail
{
	public function __construct()
	{
		parent::__construct();
	}
    public function add( $new_count_id, $get_desc,$userid ) {
        $this->row_insert([
            "new_count_id"  => $new_count_id,
            "get_time"  =>time(NULL) ,
            "get_desc"  =>$get_desc ,
            "userid"  =>$userid ,
        ]);
    }

    public function rwo_del_by_detail_id($id){
        $sql=sprintf("delete from %s  where  detail_id='%s' ",
                     self::DB_TABLE_NAME,
                     $this->ensql($id));
        return $this->main_update($sql);
    }

    public function get_row_by_userid($new_count_id,$userid){
        $where_arr = [
            ['new_count_id=%d',$new_count_id],
            ['userid=%d',$userid],
        ];
        $sql = $this->gen_sql_new(
            " select * "
            ." from %s "
            ." where %s ",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }

}











