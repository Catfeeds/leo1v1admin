<?php
namespace App\Models;
use \App\Enums as E;
class t_user_authority_group extends \App\Models\Zgen\z_t_user_authority_group
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_auth_groups(){
		$sql = sprintf("select group_name,groupid"
                       ." from %s"
                       ." where del_flag = 0"
                       ." order by group_name asc"
                       ,self::DB_TABLE_NAME
        );
		return $this->main_get_list($sql);
	}

	public function get_auth_group_map($groupid)
	{
		$sql = $this->gen_sql("select group_authority from %s where groupid= %u",
					   self::DB_TABLE_NAME, $groupid );
		$power_str = $this->main_get_value($sql);
        $arr       = explode(",",$power_str);
        $ret       = [];
        foreach ($arr as $v) {
            if ($v) {
                $ret[$v]=true;
            }
        }
        return $ret;
	}

}











