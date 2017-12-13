<?php
namespace App\Models;
use \App\Enums as E;
class t_area extends \App\Models\Zgen\z_t_area
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_province(){
        $sql = $this->gen_sql_new("select area_id,name from %s where level=1",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

    public function get_city($parent_id=-1){
        $where_arr = [
            ['parent_id=%s', $parent_id, -1],
            'level=2',
        ];
        $sql = $this->gen_sql_new("select area_id,parent_id from %s where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

}











