<?php
namespace App\Models;
class t_admin_users extends \App\Models\Zgen\z_t_admin_users
{
	public function __construct()
	{
		parent::__construct();
	}

	public function user_login($account, $password)
	{

		$sql = sprintf("select id from %s where account = '%s' and password = '%s'",
                       self::DB_TABLE_NAME, $this->ensql($account), $this->ensql($password));
		return $this->main_get_row($sql);
	}

	public function set_last_ip($account, $ip )
	{
		$sql = sprintf("update %s  set last_ip = '%s'  where account = '%s'",
                       self::DB_TABLE_NAME,  $this->ensql($ip), $this->ensql($account));
		return !$this->main_update($sql);
	}

	public function check_need_verify ($account, $ip )
	{
		$sql = sprintf("select id from %s where account = '%s' and last_ip = '%s'",
                       self::DB_TABLE_NAME,
                       $this->ensql($account),
                       $this->ensql($ip)
        );
		return !$this->main_get_row($sql);
	}

	public function get_list_for_select($id,$gender, $nick_phone,  $page_num,$main_type)
	{
        $where_arr = array(
            array( "id=%d", $id, -1 ),
        );
        if ($nick_phone!=""){
            $where_arr[]=sprintf( "(account like '%%%s%%'  )",
                                    $this->ensql($nick_phone));
        }
        $where_arr[]=["account_role=%u", $main_type, -1]; 
		$sql =  $this->gen_sql_new( "select id as id , account as  nick,'' as phone,'' as gender  from %s    where %s",
					   self::DB_TABLE_NAME,  $where_arr );
		return $this->main_get_list_by_page($sql,$page_num,10);
	}


    public function is_manage_exist($account)
    {
        $sql = sprintf("select * from %s where account = '%s'",
                       self::DB_TABLE_NAME,
                       $this->ensql($account)
        );
        $ret = $this->main_get_row( $sql  );
        if(!empty($ret))
            return true;
        return false;
    }

    public function add_manager($account, $name, $email, $phone, $passwd)
    {
        $sql1 = sprintf("insert ignore into %s (account, password, create_time) values('%s','%s', %u) ",
                        self::DB_TABLE_NAME,
                        $this->ensql( $account),
                        $this->ensql($passwd),
                        time()
        );

        $this->main_insert($sql1);
        $uid = $this->db_insert_id;
        $sql2 = sprintf("insert  into %s (uid, account, name, email, phone, create_time) ".
                        "values(%u, '%s', '%s', '%s', '%s', %u)",
                        \App\Models\Zgen\z_t_manager_info::DB_TABLE_NAME,
                        $uid,
                        $this->ensql( $account),
                        $this->ensql($name),
                        $this->ensql($email),
                        $this->ensql($phone),
                        time()
        );

        $this->main_insert($sql2);
        return $uid;
    }

    public function get_id_by_account($account) {
		$sql = $this->gen_sql("select id from %s where account = '%s' ",
                              self::DB_TABLE_NAME, $account);
		return $this->main_get_value($sql);
    }

    public function update_password($account, $password)
    {
        $password = md5(md5($password)."#Aaron");
        $sql      = sprintf("update %s set password = '%s' where account = '%s' "
                          ,self::DB_TABLE_NAME
                          ,$this->ensql($password)
                          ,$this->ensql($account)
        );
        return $this->main_update( $sql  );
    }
}
