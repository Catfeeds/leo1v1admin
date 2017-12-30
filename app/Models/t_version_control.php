<?php
namespace App\Models;
use \App\Enums as E;
class t_version_control extends \App\Models\Zgen\z_t_version_control
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_list($page_info,$start_time,$end_time){
        $where_arr = [
            ["publish_time>%u",$start_time,-1],
            ["publish_time<%u",$end_time,-1]
        ];
        $sql = $this->gen_sql_new(" select * from %s  where %s order by is_publish asc, publish_time desc, id desc"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function update_info(){
        $sql = "update db_weiyi.t_version_control set is_publish  = 2 where is_publish = 1";
        return $this->main_update($sql);
    }
    public function update_info_new($id){
        $sql = "update db_weiyi.t_version_control set is_publish  = 2 where id = $id";
        return $this->main_update($sql);
    }

    public function update_info_filetype($file_type){
        $sql = "update db_weiyi.t_version_control set is_publish = 2 where file_type = $file_type and is_publish = 1";
        return $this->main_update($sql);
    }

    public function get_publish_url(){
        $sql = "select * from db_weiyi.t_version_control where is_publish = 1";
        return $this->main_get_list($sql);
    }


}











