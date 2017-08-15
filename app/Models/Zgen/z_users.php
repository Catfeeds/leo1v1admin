<?php
namespace App\Models\Zgen;
class z_users  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_ejabberd.users";


	/*varchar(250) */
	const C_username='username';

	/*text */
	const C_password='password';

	/*timestamp */
	const C_created_at='created_at';
	function get_password($username ){
		return $this->field_get_value( $username , self::C_password );
	}
	function get_created_at($username ){
		return $this->field_get_value( $username , self::C_created_at );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="username";
        $this->field_table_name="db_ejabberd.users";
  }
    public function field_get_list( $username, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $username, $set_field_arr) {
        return parent::field_update_list( $username, $set_field_arr);
    }


    public function field_get_value(  $username, $field_name ) {
        return parent::field_get_value( $username, $field_name);
    }

    public function row_delete(  $username) {
        return parent::row_delete( $username);
    }

}

/*
  CREATE TABLE `users` (
  `username` varchar(250) NOT NULL,
  `password` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */
