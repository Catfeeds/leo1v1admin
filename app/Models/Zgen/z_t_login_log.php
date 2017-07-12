<?php
namespace App\Models\Zgen;
class z_t_login_log  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_login_log";


	/*int(10) unsigned */
	const C_id='id';

	/*int(10) unsigned */
	const C_login_time='login_time';

	/*char(64) */
	const C_account='account';

	/*char(16) */
	const C_ip='ip';

	/*int(10) unsigned */
	const C_flag='flag';
	function get_login_time($id ){
		return $this->field_get_value( $id , self::C_login_time );
	}
	function get_account($id ){
		return $this->field_get_value( $id , self::C_account );
	}
	function get_ip($id ){
		return $this->field_get_value( $id , self::C_ip );
	}
	function get_flag($id ){
		return $this->field_get_value( $id , self::C_flag );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi_admin.t_login_log";
  }
    public function field_get_list( $id, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $id, $set_field_arr) {
        return parent::field_update_list( $id, $set_field_arr);
    }


    public function field_get_value(  $id, $field_name ) {
        return parent::field_get_value( $id, $field_name);
    }

    public function row_delete(  $id) {
        return parent::row_delete( $id);
    }

}

/*
  CREATE TABLE `t_login_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `login_time` int(10) unsigned NOT NULL,
  `account` char(64) NOT NULL,
  `ip` char(16) NOT NULL,
  `flag` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `account` (`account`),
  KEY `login_time` (`login_time`)
) ENGINE=InnoDB AUTO_INCREMENT=195129 DEFAULT CHARSET=utf8
 */
