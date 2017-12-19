<?php
namespace App\Models;
use \App\Enums as E;
class t_resource_file extends \App\Models\Zgen\z_t_resource_file
{
    public function __construct()
    {
        parent::__construct();
    }

    public function update_file_status($resource_id, $val){

        $sql = $this->gen_sql_new("update %s set status=$val where resource_id=$resource_id  and status<2 "
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_update($sql);
    }

    public function get_info_by_file_id($file_id){
        $where_arr = [
            ['file_id=%u', $file_id, -1]
        ];
        $sql = $this->gen_sql_new(
            "select resource_type,subject,grade,tag_one,tag_two,tag_three,tag_four,file_title,file_link,file_size,file_type,file_id"
            ." from %s f"
            ." left join %s r on r.resource_id=f.resource_id"
            ." where %s"
            ,self::DB_TABLE_NAME
            ,t_resource::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function add_num($field, $file_id){

        $sql = $this->gen_sql_new("update %s set $field=$field+1 where file_id=$file_id "
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_update($sql);
    }

    public function minus_num($field, $file_id){
        $sql = $this->gen_sql_new("update %s set $field=$field-1 where file_id=$file_id "
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_update($sql);
    }


    public function get_files_by_resource_id($resource_id){
        $where_arr = [
            ['f.resource_id=%u', $resource_id, -1],
            'f.status=0',
            'r.is_del=0',
            'r.resource_type in (1,2,3)',
        ];
        $sql = $this->gen_sql_new("select file_title,file_link,file_type,file_id,file_use_type"
                                  ." from %s f"
                                  ." left join %s r on r.resource_id=f.resource_id"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_resource::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

}
