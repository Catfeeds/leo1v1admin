<?php
namespace App\Models;
use \App\Enums as E;
class t_qingjia extends \App\Models\Zgen\z_t_qingjia
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_list( $page_num,$adminid, $start_time,$end_time ) {
        $where_arr=[
            ["q.adminid=%d", $adminid, -1] ,
            ["f.flow_type=%u", E\Eflow_type::V_QINGJIA , -1] ,
        ];
        $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);

        $sql=$this->gen_sql_new("select q.id, q.adminid, q.add_time, q.type, q.start_time, q.end_time,"
                                ." q.hour_count, q.del_flag, q.msg,f.flow_status ,f.flowid "
                                ." from %s q "
                                ." left join %s f on q.id=f.from_key_int "
                                ." where %s"
                                ." order by add_time desc"
                                ,self::DB_TABLE_NAME
                                ,t_flow::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

}











