<?php
namespace App\Models\Zgen;
class z_t_agent  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_agent";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_parentid='parentid';

	/*int(11) */
	const C_userid='userid';

	/*varchar(16) */
	const C_phone='phone';

	/*varchar(128) */
	const C_wx_openid='wx_openid';

	/*int(11) */
	const C_create_time='create_time';

	/*varchar(255) */
	const C_bank_address='bank_address';

	/*varchar(255) */
	const C_bank_account='bank_account';

	/*varchar(255) */
	const C_bank_phone='bank_phone';

	/*varchar(255) */
	const C_bank_province='bank_province';

	/*varchar(255) */
	const C_bank_city='bank_city';

	/*varchar(255) */
	const C_bank_type='bank_type';

	/*varchar(255) */
	const C_bankcard='bankcard';

	/*varchar(255) */
	const C_idcard='idcard';

	/*varchar(255) */
	const C_zfb_name='zfb_name';

	/*varchar(255) */
	const C_zfb_account='zfb_account';
	function get_parentid($id ){
		return $this->field_get_value( $id , self::C_parentid );
	}
	function get_userid($id ){
		return $this->field_get_value( $id , self::C_userid );
	}
	function get_phone($id ){
		return $this->field_get_value( $id , self::C_phone );
	}
	function get_wx_openid($id ){
		return $this->field_get_value( $id , self::C_wx_openid );
	}
	function get_create_time($id ){
		return $this->field_get_value( $id , self::C_create_time );
	}
	function get_bank_address($id ){
		return $this->field_get_value( $id , self::C_bank_address );
	}
	function get_bank_account($id ){
		return $this->field_get_value( $id , self::C_bank_account );
	}
	function get_bank_phone($id ){
		return $this->field_get_value( $id , self::C_bank_phone );
	}
	function get_bank_province($id ){
		return $this->field_get_value( $id , self::C_bank_province );
	}
	function get_bank_city($id ){
		return $this->field_get_value( $id , self::C_bank_city );
	}
	function get_bank_type($id ){
		return $this->field_get_value( $id , self::C_bank_type );
	}
	function get_bankcard($id ){
		return $this->field_get_value( $id , self::C_bankcard );
	}
	function get_idcard($id ){
		return $this->field_get_value( $id , self::C_idcard );
	}
	function get_zfb_name($id ){
		return $this->field_get_value( $id , self::C_zfb_name );
	}
	function get_zfb_account($id ){
		return $this->field_get_value( $id , self::C_zfb_account );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_agent";
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
  CREATE TABLE `t_agent` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `parentid` int(11) NOT NULL COMMENT '上级转介绍id',
  `userid` int(11) DEFAULT NULL COMMENT '用户id',
  `phone` varchar(16) COLLATE latin1_bin NOT NULL COMMENT '手机号',
  `wx_openid` varchar(128) COLLATE latin1_bin DEFAULT NULL COMMENT '微信id',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `bank_address` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '开户行和支行',
  `bank_account` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '持卡人姓名',
  `bank_phone` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '银行预留手机号',
  `bank_province` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '银行卡开户省',
  `bank_city` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '银行卡开户市',
  `bank_type` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '银行卡类型',
  `bankcard` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '银行卡号',
  `idcard` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '身份证号码',
  `zfb_name` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '支付宝姓名',
  `zfb_account` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '支付宝账户',
  PRIMARY KEY (`id`),
  UNIQUE KEY `db_weiyi_t_agent_phone_unique` (`phone`),
  UNIQUE KEY `db_weiyi_t_agent_userid_unique` (`userid`),
  UNIQUE KEY `db_weiyi_t_agent_wx_openid_unique` (`wx_openid`),
  KEY `db_weiyi_t_agent_parentid_index` (`parentid`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
