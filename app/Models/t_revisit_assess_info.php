<?php
namespace App\Models;
use \App\Enums as E;
class t_revisit_assess_info extends \App\Models\Zgen\z_t_revisit_assess_info
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_stu_num_by_uid($ass_adminid, $start_time,$end_time){
        $where_arr = [
            "uid=$ass_adminid",
            "create_time>=$start_time",
            "create_time<$end_time",
        ];
        $sql = $this->gen_sql_new("select stu_num"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }
}
