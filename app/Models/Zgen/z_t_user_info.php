<?php
namespace App\Models\Zgen;
class z_t_user_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_account.t_user_info";


	/*int(10) unsigned */
	const C_userid='userid';

	/*char(32) */
	const C_passwd='passwd';

	/*int(10) unsigned */
	const C_childid='childid';

	/*varchar(50) */
	const C_reg_channel='reg_channel';

	/*int(10) unsigned */
	const C_reg_ip='reg_ip';

	/*int(10) unsigned */
	const C_reg_time='reg_time';
	function get_passwd($userid ){
		return $this->field_get_value( $userid , self::C_passwd );
	}
	function get_childid($userid ){
		return $this->field_get_value( $userid , self::C_childid );
	}
	function get_reg_channel($userid ){
		return $this->field_get_value( $userid , self::C_reg_channel );
	}
	function get_reg_ip($userid ){
		return $this->field_get_value( $userid , self::C_reg_ip );
	}
	function get_reg_time($userid ){
		return $this->field_get_value( $userid , self::C_reg_time );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="userid";
        $this->field_table_name="db_account.t_user_info";
  }
    public function field_get_list( $userid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $userid, $set_field_arr) {
        return parent::field_update_list( $userid, $set_field_arr);
    }


    public function field_get_value(  $userid, $field_name ) {
        return parent::field_get_value( $userid, $field_name);
    }

    public function row_delete(  $userid) {
        return parent::row_delete( $userid);
    }

}

/*
  CREATE TABLE `t_user_info` (
  `userid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `passwd` char(32) NOT NULL COMMENT '用户密码md5(md5(passwd+"@weiyi"))',
  `childid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '学生id，仅家长有效',
  `reg_channel` varchar(50) NOT NULL COMMENT '注册来源',
  `reg_ip` int(10) unsigned NOT NULL COMMENT '注册ip',
  `reg_time` int(10) unsigned NOT NULL COMMENT '注册时间',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=62652 DEFAULT CHARSET=utf8
 */
