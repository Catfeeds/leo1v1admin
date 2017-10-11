<?php
namespace App\Models;
use \App\Enums as E;
class t_revisit_assess_info extends \App\Models\Zgen\z_t_revisit_assess_info
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_month_assess_info_by_uid($ass_adminid, $start_time,$end_time){
        $where_arr = [
            "ra.uid=$ass_adminid",
            "ra.create_time>=$start_time",
            "ra.create_time<$end_time",
            "r.revisit_time>=$start_time",
            "r.revisit_time<$end_time",
        ];
        $sql = $this->gen_sql_new(
            "select ra.stu_num,count(distinct r.userid) as revisit_num,sum(tq.duration) as call_num"
            ." from %s ra"
            ." left join %s m on m.uid=ra.uid"
            ." left join %s r on r.sys_operator=m.account and r.revisit_type=0"
            ." left join %s tq on tq.id=r.call_phone_id"
            ." where %s"
            ,self::DB_TABLE_NAME
            ,t_manager_info::DB_TABLE_NAME
            ,t_revisit_info::DB_TABLE_NAME
            ,t_tq_call_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_row($sql);
    }

}
