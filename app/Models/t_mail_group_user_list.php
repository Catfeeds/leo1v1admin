<?php
namespace App\Models;
use \App\Enums as E;
class t_mail_group_user_list extends \App\Models\Zgen\z_t_mail_group_user_list
{
	public function __construct()
	{
		parent::__construct();
	}
    public function get_list ($page_info, $groupid, $adminid) {
        $where_arr = [
            "groupid" => $groupid,
            ["adminid =%u", $adminid ,-1 ],
        ];
        $sql=$this->gen_sql_new(
            " select eg.groupid,   eg.adminid , m.email, m.account  "
            ." from %s eg "
            ." left join  %s m on eg.adminid = m.uid "
            ." where %s",
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

}











