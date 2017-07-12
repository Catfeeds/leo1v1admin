<?php
namespace App\Models\Zgen;
class z_t_user_login_log  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_user_login_log";


	/*int(10) unsigned */
	const C_id='id';

	/*int(11) */
	const C_userid='userid';

	/*int(11) */
	const C_login_time='login_time';

	/*varchar(50) */
	const C_nick='nick';

	/*varchar(50) */
	const C_ip='ip';

	/*tinyint(4) */
	const C_role='role';

	/*tinyint(4) */
	const C_login_type='login_type';

	/*tinyint(4) */
	const C_dymanic_flag='dymanic_flag';
	function get_userid($id ){
		return $this->field_get_value( $id , self::C_userid );
	}
	function get_login_time($id ){
		return $this->field_get_value( $id , self::C_login_time );
	}
	function get_nick($id ){
		return $this->field_get_value( $id , self::C_nick );
	}
	function get_ip($id ){
		return $this->field_get_value( $id , self::C_ip );
	}
	function get_role($id ){
		return $this->field_get_value( $id , self::C_role );
	}
	function get_login_type($id ){
		return $this->field_get_value( $id , self::C_login_type );
	}
	function get_dymanic_flag($id ){
		return $this->field_get_value( $id , self::C_dymanic_flag );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_user_login_log";
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
  CREATE TABLE `t_user_login_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL COMMENT '登陆用户id',
  `login_time` int(11) NOT NULL COMMENT '登陆时间',
  `nick` varchar(50) COLLATE latin1_bin NOT NULL COMMENT '登陆用户id',
  `ip` varchar(50) COLLATE latin1_bin NOT NULL COMMENT '登陆ip',
  `role` tinyint(4) NOT NULL COMMENT '登陆角色',
  `login_type` tinyint(4) NOT NULL COMMENT '登陆类型 0 app登陆',
  `dymanic_flag` tinyint(4) NOT NULL COMMENT '是否是临时密码登陆 0 不是 1 是',
  PRIMARY KEY (`id`),
  UNIQUE KEY `db_weiyi_t_user_login_log_userid_login_time_ip_unique` (`userid`,`login_time`,`ip`),
  KEY `db_weiyi_t_user_login_log_userid_index` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
