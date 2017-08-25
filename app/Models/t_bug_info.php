<?php
namespace App\Models;
use \App\Enums as E;
class t_bug_info extends \App\Models\Zgen\z_t_bug_info
{
	public function __construct()
	{
		parent::__construct();
	}
    public function get_list($page_info,$userid)
    {
        $where_arr=[
            //           ["test_status=%u",$test_status,-1],
        ];
        $sql = $this->gen_sql_new("select * from %s "
                                  ."where id = %d order by create_time desc ",
                                  self::DB_TABLE_NAME,
                                  $userid
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }
}