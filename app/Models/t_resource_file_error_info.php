<?php
namespace App\Models;
use App\Enums as E;
class t_resource_file_error_info extends \App\Models\Zgen\z_t_resource_file_error_info
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_error_by_file_id($file_id){
        $sql =$this->gen_sql_new("select * from %s where file_id = %u order by add_time desc",
                                 self::DB_TABLE_NAME,
                                 $file_id
        );
        return $this->main_get_list( $sql);

    }

    public function get_error_by_error_id($error_id){
        $sql =$this->gen_sql_new("select * from %s where id = %u",
                                 self::DB_TABLE_NAME,
                                 $error_id
        );
        return $this->main_get_row($sql);

    }

    public function get_count($file_id){
    	$where_arr = [
    		[" file_id=%u",$file_id,-1],
    	];
    	$sql = $this->gen_sql_new(" select count(file_id) "
                                  ." from %s "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr);
    	return $this->main_get_value($sql);

    }

    public function upload_new_file($file_id){
        $sql=$this->gen_sql("update %s set status=2"
                            ." where file_id=%u",
                            self::DB_TABLE_NAME,
                            $file_id
        );
        return $this->main_update($sql); 
    }
}