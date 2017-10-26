<?php
namespace App\Models;
use \App\Enums as E;
class t_agent_wx_msg_log extends \App\Models\Zgen\z_t_agent_wx_msg_log
{
	public function __construct()
	{
		parent::__construct();
	}
    //检查是否已经发送过了
    public function check_is_send( $from_agentid, $to_agentid, $agent_wx_msg_type ) {
        $sql=$this->gen_sql_new("select count(1) from %s"
                           . " where from_agentid =%u and to_agentid =%u and agent_wx_msg_type =%u ",
                           self::DB_TABLE_NAME, $from_agentid, $to_agentid, $agent_wx_msg_type );
        return $this->main_get_value($sql) >=1;
    }
    public function add ($from_agentid, $to_agentid,  $agent_wx_msg_type, $msg, $succ_flag) {
        return $this->row_insert([
            "from_agentid"      => $from_agentid,
            "to_agentid"        => $to_agentid,
            "log_time"          => time(NULL),
            "agent_wx_msg_type" => $agent_wx_msg_type,
            "msg"               => $msg,
            "succ_flag"         => $succ_flag,
        ]);

    }

}











