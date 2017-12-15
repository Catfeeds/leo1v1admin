<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_resource extends \App\Models\Zgen\z_t_teacher_resource
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_tea_collect($teacherid){
        $where_arr = [
            ['teacherid=%u', $teacherid, -1],
            "is_del=0",
        ];

        $sql = $this->gen_sql_new("select file_title,file_size,create_time,tea_res_id from %s where %s"
                                  , self::DB_TABLE_NAME
                                  , $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_tea_all_res($teacherid, $dir_id){
        $where_arr = [
            ["teacherid=%u", $teacherid, -1],
            ["dir_id='%s'", $dir_id, -1],
            "is_del=0",
        ];

        $sql = $this->gen_sql_new("select file_title,file_type,file_size,create_time,tea_res_id,file_id,dir_id from %s where %s"
                                  , self::DB_TABLE_NAME
                                  , $where_arr
        );
        return $this->main_get_list($sql);
    }
}
