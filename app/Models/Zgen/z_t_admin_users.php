<?php
namespace App\Models\Zgen;
class z_t_admin_users  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_admin_users";


	/*int(10) unsigned */
	const C_id='id';

	/*varchar(30) */
	const C_account='account';

	/*varchar(32) */
	const C_password='password';

	/*int(10) unsigned */
	const C_create_time='create_time';

	/*tinyint(4) */
	const C_del_flag='del_flag';

	/*char(16) */
	const C_last_ip='last_ip';
	function get_account($id ){
		return $this->field_get_value( $id , self::C_account );
	}
	function get_password($id ){
		return $this->field_get_value( $id , self::C_password );
	}
	function get_create_time($id ){
		return $this->field_get_value( $id , self::C_create_time );
	}
	function get_del_flag($id ){
		return $this->field_get_value( $id , self::C_del_flag );
	}
	function get_last_ip($id ){
		return $this->field_get_value( $id , self::C_last_ip );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi_admin.t_admin_users";
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
  CREATE TABLE `t_admin_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(30) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户创建时间',
  `del_flag` tinyint(4) NOT NULL DEFAULT '0' COMMENT '删除',
  `last_ip` char(16) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`)
) ENGINE=InnoDB AUTO_INCREMENT=215 DEFAULT CHARSET=utf8
 */
