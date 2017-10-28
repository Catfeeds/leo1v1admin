<?php
namespace App\Models;
use \App\Enums as E;
class t_web_page_trace_log extends \App\Models\Zgen\z_t_web_page_trace_log
{
	public function __construct()
	{
		parent::__construct();
	}
    public function get_admin_info($web_page_id) {
        $sql=$this->gen_sql_new("select from_adminid ,  count(*) as count ,   count(distinct ip) as ip_count ,  ",$__args__)

    }

}











