<?php
namespace App\Models\Zgen;
class z_t_parent_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_parent_info";


	/*int(10) unsigned */
	const C_parentid='parentid';

	/*varchar(32) */
	const C_nick='nick';

	/*varchar(16) */
	const C_phone='phone';

	/*tinyint(4) */
	const C_gender='gender';

	/*varchar(100) */
	const C_face='face';

	/*timestamp */
	const C_last_modified_time='last_modified_time';

	/*tinyint(4) */
	const C_has_login='has_login';

	/*varchar(255) */
	const C_email='email';

	/*varchar(255) */
	const C_wx_openid='wx_openid';
	function get_nick($parentid ){
		return $this->field_get_value( $parentid , self::C_nick );
	}
	function get_phone($parentid ){
		return $this->field_get_value( $parentid , self::C_phone );
	}
	function get_gender($parentid ){
		return $this->field_get_value( $parentid , self::C_gender );
	}
	function get_face($parentid ){
		return $this->field_get_value( $parentid , self::C_face );
	}
	function get_last_modified_time($parentid ){
		return $this->field_get_value( $parentid , self::C_last_modified_time );
	}
	function get_has_login($parentid ){
		return $this->field_get_value( $parentid , self::C_has_login );
	}
	function get_email($parentid ){
		return $this->field_get_value( $parentid , self::C_email );
	}
	function get_wx_openid($parentid ){
		return $this->field_get_value( $parentid , self::C_wx_openid );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="parentid";
        $this->field_table_name="db_weiyi.t_parent_info";
  }
    public function field_get_list( $parentid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $parentid, $set_field_arr) {
        return parent::field_update_list( $parentid, $set_field_arr);
    }


    public function field_get_value(  $parentid, $field_name ) {
        return parent::field_get_value( $parentid, $field_name);
    }

    public function row_delete(  $parentid) {
        return parent::row_delete( $parentid);
    }

}

/*
  CREATE TABLE `t_parent_info` (
  `parentid` int(10) unsigned NOT NULL COMMENT '家长id',
  `nick` varchar(32) NOT NULL COMMENT '家长姓名',
  `phone` varchar(16) NOT NULL COMMENT '家长联系方式',
  `gender` tinyint(4) NOT NULL DEFAULT '0' COMMENT '用户性别（0保密，1男2女）',
  `face` varchar(100) NOT NULL DEFAULT '' COMMENT '家长头像',
  `last_modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后修改时间',
  `has_login` tinyint(4) NOT NULL DEFAULT '0' COMMENT '家长是否登陆过 0 未登录 1 曾登陆',
  `email` varchar(255) NOT NULL,
  `wx_openid` varchar(255) DEFAULT NULL COMMENT '微信 openid',
  PRIMARY KEY (`parentid`),
  UNIQUE KEY `db_weiyi_t_parent_info_wx_openid_unique` (`wx_openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */
