<?php
namespace App\Models;
class t_user_info extends \App\Models\Zgen\z_t_user_info
{
	public function __construct()
	{
		parent::__construct();
	}
    public function delete_user($userid , $role)
    {
        $sql = sprintf("delete from %s where userid = %u and role = %u",
                       \App\Models\Zgen\z_t_phone_to_user::DB_TABLE_NAME,  
                       $userid,
                       $role
        );
        $this->main_update($sql);
        $sql = sprintf("delete from %s where userid = %u",
                       self::DB_TABLE_NAME,
                       $userid
        );
        return $this->main_update($sql);
    }


    public function update_password($userid,$passwd) {
        return $this->field_update_list($userid,[
            "passwd" => $passwd,
        ]) ;
    }

    public function user_reg($passwd,$reg_channel,$ip){
        $this->row_insert([
            "passwd"      => $passwd,
            "reg_channel" => $reg_channel,
            "reg_ip"      => $ip,
            "reg_time"    => time(),
        ]);
        return $this->get_last_insertid();
    }

    public function parent_reg($passwd,$reg_channel,$ip){
        $this->row_insert([
            "passwd"      => $passwd,
            "reg_channel" => $reg_channel,
            "reg_ip"      => $ip,
            "reg_time"    => time(),
        ]);
        return $this->get_last_insertid();
    }

    public function add_parent_info(){
        $ret = $this->row_insert([
            'passwd'        => md5("111111@weiyi"),            
        ]);

        if ($ret ==1 ) {
            return $this->get_last_insertid();
        } else {
            return false;
        }

    }

    public function check_login_userid($phone, $passwd, $role = \App\Enums\Erole::V_TEACHER  )
    {
        $sql = $this->gen_sql("select p.userid ".
                       "from %s u, %s p where u.userid = p.userid and p.phone = '%s' and u.passwd = '%s' ".
                       "and role = %u",
                       self::DB_TABLE_NAME,
                       t_phone_to_user::DB_TABLE_NAME,
                       $phone, $passwd, $role );
        return $this->main_get_value( $sql  );
    }

}
