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
        $sql = $this->gen_sql_new("select file_title,file_link,file_type,file_id,file_use_type,ex_num"
                                  ." from %s f"
                                  ." left join %s r on r.resource_id=f.resource_id"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_resource::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function getResoureList($resource_id){
        $where_arr = [
            "rf.resource_id=$resource_id",
            "rf.status=0",
            "rf.file_use_type=0"//授课课件
        ];

        $sql = $this->gen_sql_new("  select rf.file_title, rf.file_id, rf.file_type, rf.file_link, rf.file_poster, r.tag_three from %s rf "
                                  ." left join %s r on r.resource_id=rf.resource_id"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_resource::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_max_ex_num($resource_id){
        $where_arr = [
            ['resource_id=%u', $resource_id, -1],
            "status=0",
            "file_use_type=3"
        ];

        $sql = $this->gen_sql_new(" select max(ex_num) from %s  "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function getFileIdByUuid($uuid){
        $sql = $this->gen_sql_new("  select file_id from %s rf"
                                  ." where uuid='$uuid'"
                                  ,self::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }

    public function getResourceFileList(){
        $where_arr = [
            "rf.uuid=''",
            "rf.status=0"
        ];
        $sql = $this->gen_sql_new("  select file_link, file_id, file_title from %s rf"
                                  ." where %s limit 10"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function updateStatusByUuid($uuid,$status){
        $sql = $this->gen_sql_new("  update %s set uuid_status=$status where  uuid='$uuid'"
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_update($sql);
    }

    public function getResourceList(){
        $where_arr = [
            "rf.uuid_status=1",
            "rf.status=0",
            "rf.zip_url=''"
        ];

        $sql = $this->gen_sql_new("  select rf.file_id, rf.uuid "
                                  ." from %s rf"
                                  ." where %s limit 10"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function getResourceFileInfoById($resource_id){
        $where_arr = [
            "f.resource_id=$resource_id",
            "f.status=0",
        ];

        $sql = $this->gen_sql_new("  select file_title, file_type, file_link from %s f "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function getH5PosterInfo(){
        $where_arr = [
            "f.status=0",
            "f.file_use_type=0",
            "f.change_status=0"
        ];

        $sql = $this->gen_sql_new("  select file_id, file_type, file_link from %s f "
                                  ." where %s limit 10"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }

}
