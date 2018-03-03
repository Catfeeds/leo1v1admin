<?php
namespace App\Models;
class t_info_resource_power extends \App\Models\Zgen\z_t_info_resource_power
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_list() {
        $where_arr = [
            ["is_del = %u", 0 ],
        ];
        $sql=$this->gen_sql_new("select * from %s where %s order by resource_id asc,type_id asc",
                                self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }

    public function get_latest_resource_id(){
        $sql=$this->gen_sql_new("select resource_id from %s order by resource_id desc",
                                self::DB_TABLE_NAME);
        return $this->main_get_value($sql);

    }

    public function get_latest_type_id($resource_id){
        $sql=$this->gen_sql_new("select type_id from %s where resource_id = %d order by type_id desc",
                                self::DB_TABLE_NAME,$resource_id);
        return $this->main_get_value($sql);
    }

    public function get_old_resource_name($resource_id,$id){
        $where_arr = [
            ["resource_id = %u", $resource_id ]
        ];
        if($id != 0 ){
            $where_arr[] = ["id != %u", $id ];
        }
        $sql=$this->gen_sql_new("select resource_name from %s where %s
                                order by id desc limit 0,1",
                                self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);

    }

    public function get_same_resource_id_arr($resource_id,$id){
        $sql=$this->gen_sql_new("select id,resource_name from %s
                                where resource_id = %d and id != %d order by id desc",
                                self::DB_TABLE_NAME,$resource_id,$id);
        return $this->main_get_list($sql);

    }

    public function get_by_resource_id($resource_id){
        $sql=$this->gen_sql_new("select id,resource_name from %s
                                where resource_id = %d order by id desc",
                                self::DB_TABLE_NAME,$resource_id);
        return $this->main_get_list($sql);
    }
}











