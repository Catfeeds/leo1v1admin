<?php
namespace App\Models;
use \App\Enums as E;
class t_revisit_assess_info extends \App\Models\Zgen\z_t_revisit_assess_info
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_month_assess_info_by_uid($ass_adminid, $start_time,$end_time,$uid_str = -1){
        $where_arr = [
            "ra.create_time>=$start_time",
            "ra.create_time<$end_time",
        ];
        if ($uid_str != -1 && $uid_str !== null) {
            $where_arr[] = "ra.uid in ($uid_str)";
        } else {
            $where_arr[] = ["ra.uid=%u",$ass_adminid,-1];
        }

        $sql = $this->gen_sql_new(
            "select ra.uid,m.name,ra.stu_num,count(distinct r.userid) as revisit_num,sum(tq.duration) as call_num"
            ." from %s ra"
            ." left join %s m on m.uid=ra.uid"
            ." left join %s r on r.sys_operator=m.account and r.revisit_type=0 and r.revisit_time>=$start_time and r.revisit_time<$end_time"
            ." left join %s rc on rc.uid=m.uid and rc.revisit_time1=r.revisit_time"
            ." left join %s tq on tq.id=rc.call_phone_id and tq.id>0"
            ." where %s"
            ." group by ra.uid"
            ,self::DB_TABLE_NAME
            ,t_manager_info::DB_TABLE_NAME
            ,t_revisit_info::DB_TABLE_NAME
            ,t_revisit_call_count::DB_TABLE_NAME
            ,t_tq_call_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_stu_num_info( $uid_str, $cur_start, $cur_end){
        $where_arr = [
            "create_time>=$cur_start ",
            "create_time<$cur_end ",
        ];
        if ($uid_str) {
            $where_arr[] = ["uid in ($uid_str)"];
        } else {
            return 0;
        }
        $sql = $this->gen_sql_new(
            "select sum(stu_num) "
            ." from %s "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_value($sql);
    }

}
