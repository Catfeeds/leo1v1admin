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
}