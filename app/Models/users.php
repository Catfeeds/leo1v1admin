<?php
namespace App\Models;
class users extends \App\Models\Zgen\z_users
{
	public function __construct()
	{
		parent::__construct();
	}

    public function check_user($userid){
        $sql=$this->gen_sql_new("select count(1) from %s where username='%s'"
                                ,self::DB_TABLE_NAME
                                ,$userid
        );
        return $this->main_get_value($sql);
    }

    public function add_ejabberd_info($userid,$passwd){
        return $this->row_insert([
            "username" => $userid,
            "password" => $passwd,
        ]);
    }

    public function add_ejabberd_account($account, $passwd)
    {
        $this->addData('username',$account);
		$this->addData('password',$passwd);

		$ret_insert = $this->dataInsert(self::DB_TABLE_NAME);
		if ($ret_insert == true) {
			return $this->db_insert_id;
		} else {
			return false;
		}
    }


}











