<?php
namespace App\Models;
use \App\Enums as E;
class t_yxyx_custom_type extends \App\Models\Zgen\z_t_yxyx_custom_type
{
	public function __construct()
	{
		parent::__construct();
	}

    public function add_one_type($create_time, $adminid, $type_name) {
        $res = $this->row_insert([
            "create_time" => $create_time,
            "adminid"     => $adminid,
            "type_name"   => $type_name,
        ]);
    }

    public function update_type($custom_type_id,$type_name) {
        $res = $this->field_update_list( ["custom_type_id" => $custom_type_id],[
            "type_name" => $type_name,
        ]);
        return $res;
    }

    public function get_one_type($custom_type_id) {
        $where_arr = [
            ['custom_type_id=%s', $custom_type_id,0],
        ];
        $sql = $this->gen_sql_new( "select custom_type_id,type_name,create_time,adminid"
                                   ." from %s "
                                   ." where %s"
                                   ,self::DB_TABLE_NAME
                                   ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_all_custom_type($page_info){
        $sql = $this->gen_sql_new( "select custom_type_id,type_name,create_time,adminid "
                                    ." from %s "
                                    ,self::DB_TABLE_NAME
        );
        return $this->main_get_list_by_page($sql,$page_info);

    }

    public function get_type_id_name_info()
    {
        $sql = $this->gen_sql_new( "select custom_type_id,type_name"
                                    ." from %s "
                                    ,self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);

    }
}
