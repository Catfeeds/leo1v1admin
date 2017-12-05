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


}
