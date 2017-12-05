<?php
namespace App\Models;

/**
 * @property t_admin_users  $t_admin_users
 */

class t_authority_group extends \App\Models\Zgen\z_t_authority_group
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_groupid_by_group_name($group_name)
    {
        $sql=$this->gen_sql("select groupid from %s where group_name='%s' ",
                            self::DB_TABLE_NAME, $group_name);
        return $this->main_get_value($sql);
    }

    public function get_admin_list_by_group_name($group_name) {
        $groupid=$this->get_groupid_by_group_name($group_name);
		$user_list = array();
        if ($groupid ) {
            $ret_db = $this->t_manager_info->get_manager_permission();
            foreach( $ret_db as $key => $value){
                $perm       = $value['permission'];
                $perm_array = explode(',',$perm);
                if(in_array($groupid, $perm_array)){
                    $user_list[] = $value['account'];
                }
            }
        }

        $ret_list=[];
        foreach( $user_list as $account) {
            //得到id
            $id=$this->t_admin_users->get_id_by_account($account);
            $ret_list[]=["id"=>$id, "account"=> $account];
        }
        return $ret_list;
    }

	public function get_auth_groups()
	{
		$sql = sprintf("select group_name,groupid"
                       ." from %s"
                       ." where del_flag = 0"
                       ." order by group_name asc"
                       ,self::DB_TABLE_NAME
        );
		return $this->main_get_list($sql);
	}

	public function get_auth_groups_new()
	{
		$sql = sprintf("select group_name, groupid, group_authority from %s where del_flag = 0 order by group_name asc",
					   self::DB_TABLE_NAME);
		return $this->main_get_list($sql);
	}

	public function get_auth_group_map($groupid)
	{
		$sql = $this->gen_sql("select   group_authority from %s where groupid= %u",
					   self::DB_TABLE_NAME, $groupid );
		$power_str=$this->main_get_value($sql);
        $arr=explode(",",$power_str);
        $ret=[];
        foreach ($arr as $v) {
            if ($v) {
                $ret[$v]=true;
            }
        }
        return $ret;
	}

    public function get_auth_group_more($groupidArr)
	{
		$sql = $this->gen_sql("select group_authority from %s where groupid in %s",
                              self::DB_TABLE_NAME, $groupidArr );
	    return $this->main_get_list($sql);
	}

    public function get_grp_auth($groupid)
    {
        $sql = $this->gen_sql("select group_authority from %s where groupid = %u ",
                              self::DB_TABLE_NAME,
                              $groupid
        );
        return $this->main_get_row( $sql );
    }

    public function get_group_name_by_groupid($int_eve)
    {
        $sql = $this->gen_sql("select group_name from %s where groupid = %u ",
                              self::DB_TABLE_NAME,
                              $int_eve
        );

        return $this->main_get_list( $sql );

    }

    public function get_all_list()
    {
        $sql=$this->gen_sql_new("select * from %s  order by group_name asc ", self::DB_TABLE_NAME );
        return $this->main_get_list($sql);
    }

}











