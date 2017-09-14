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
            " select eg.groupid,   eg.adminid , m.name, m.email, m.account, m.email_create_flag , eg.create_flag "
            ." from %s eg "
            ." left join  %s m on eg.adminid = m.uid "
            ." where %s",
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

    function reset_all_create_flag ( $groupid, $email_list ) {

        $sql=$this->gen_sql_new(
            "update %s set  create_flag=0 where groupid = %u ",
            self::DB_TABLE_NAME,$groupid);

        $this->main_update($sql);
        foreach ( $email_list as &$email  ) {
            $email= "'".  trim($email ) . "'";
        }
        $email_list_str=join( ",",$email_list );


        $sql=$this->gen_sql_new(
            " update %s egm , %s m   set create_flag=1"
            ." where egm.adminid= m.uid "
            . " and email in (%s) ",
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            [$email_list_str]
        );
        $this->main_update($sql);

    }

}











