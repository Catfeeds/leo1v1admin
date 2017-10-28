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
        $sql=$this->gen_sql_new(
            "select from_adminid ,  count(*) as count ,   count(distinct ip) as ip_count "
            . " from %s where web_page_id=%u group by from_adminid order by ip_count desc   ",
            self::DB_TABLE_NAME,  $web_page_id  );

        return $this->main_get_list_as_page($sql);
    }
    public function get_list( $page_info, $web_page_id, $from_adminid )  {
        $where_arr=[
            ["from_adminid=%u", $from_adminid],
            ["web_page_id=%u", $web_page_id  ],
        ];

    }

}











