<?php
namespace App\Models;
use \App\Enums as E;
class t_online_count_xmpp_log extends \App\Models\Zgen\z_t_online_count_xmpp_log
{
	public function __construct()
	{
		parent::__construct();
	}
    public function get_list($xmpp_id,$start_time, $end_time) {
        $where_arr[]= ["xmpp_id='%u' ", $xmpp_id];
        $this->where_arr_add_time_range($where_arr,"logtime",$start_time,$end_time);
        $sql=$this->gen_sql_new(
            "select logtime,online_count from %s where %s order by logtime asc "
            ,self::DB_TABLE_NAME,$where_arr );


        return $this->main_get_list($sql);
    }



    public function add($xmpp_id, $logtime, $online_count ) {
        return $this->row_insert([
            "xmpp" =>$xmpp_id,
            "logtime" =>$logtime,
            "online_count" => $online_count,
        ],true );
    }

}











