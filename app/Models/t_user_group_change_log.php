<?php
namespace App\Models;
use \App\Enums as E;
class t_user_group_change_log extends \App\Models\Zgen\z_t_user_group_change_log
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_user_change_log($adminid){
        $sql = $this->gen_sql_new("  select add_time, old_group, do_adminid from %s "
                                  ." where userid=%d order by add_time desc limit 1"
                                  ,self::DB_TABLE_NAME
                                  ,$adminid
        );

        return $this->main_get_row($sql);
    }

}
