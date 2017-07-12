<?php
namespace App\Models\Zgen;
class z_t_gift_consign  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_gift_consign";


	/*int(10) unsigned */
	const C_exchangeid='exchangeid';

	/*int(10) unsigned */
	const C_userid='userid';

	/*int(10) unsigned */
	const C_exchange_time='exchange_time';

	/*tinyint(4) */
	const C_status='status';

	/*int(10) unsigned */
	const C_giftid='giftid';

	/*int(10) unsigned */
	const C_amount='amount';

	/*int(10) unsigned */
	const C_praise='praise';

	/*varchar(32) */
	const C_account='account';

	/*int(10) unsigned */
	const C_consign_time='consign_time';

	/*varchar(32) */
	const C_consignee='consignee';

	/*varchar(300) */
	const C_address='address';

	/*varchar(16) */
	const C_consignee_phone='consignee_phone';

	/*varchar(32) */
	const C_express_name='express_name';

	/*varchar(20) */
	const C_express_num='express_num';

	/*int(10) unsigned */
	const C_send_time='send_time';

	/*varchar(32) */
	const C_consigner='consigner';

	/*varchar(16) */
	const C_consigner_phone='consigner_phone';

	/*int(10) unsigned */
	const C_delivery_time='delivery_time';

	/*timestamp */
	const C_last_modified_time='last_modified_time';
	function get_userid($exchangeid ){
		return $this->field_get_value( $exchangeid , self::C_userid );
	}
	function get_exchange_time($exchangeid ){
		return $this->field_get_value( $exchangeid , self::C_exchange_time );
	}
	function get_status($exchangeid ){
		return $this->field_get_value( $exchangeid , self::C_status );
	}
	function get_giftid($exchangeid ){
		return $this->field_get_value( $exchangeid , self::C_giftid );
	}
	function get_amount($exchangeid ){
		return $this->field_get_value( $exchangeid , self::C_amount );
	}
	function get_praise($exchangeid ){
		return $this->field_get_value( $exchangeid , self::C_praise );
	}
	function get_account($exchangeid ){
		return $this->field_get_value( $exchangeid , self::C_account );
	}
	function get_consign_time($exchangeid ){
		return $this->field_get_value( $exchangeid , self::C_consign_time );
	}
	function get_consignee($exchangeid ){
		return $this->field_get_value( $exchangeid , self::C_consignee );
	}
	function get_address($exchangeid ){
		return $this->field_get_value( $exchangeid , self::C_address );
	}
	function get_consignee_phone($exchangeid ){
		return $this->field_get_value( $exchangeid , self::C_consignee_phone );
	}
	function get_express_name($exchangeid ){
		return $this->field_get_value( $exchangeid , self::C_express_name );
	}
	function get_express_num($exchangeid ){
		return $this->field_get_value( $exchangeid , self::C_express_num );
	}
	function get_send_time($exchangeid ){
		return $this->field_get_value( $exchangeid , self::C_send_time );
	}
	function get_consigner($exchangeid ){
		return $this->field_get_value( $exchangeid , self::C_consigner );
	}
	function get_consigner_phone($exchangeid ){
		return $this->field_get_value( $exchangeid , self::C_consigner_phone );
	}
	function get_delivery_time($exchangeid ){
		return $this->field_get_value( $exchangeid , self::C_delivery_time );
	}
	function get_last_modified_time($exchangeid ){
		return $this->field_get_value( $exchangeid , self::C_last_modified_time );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="exchangeid";
        $this->field_table_name="db_weiyi.t_gift_consign";
  }
    public function field_get_list( $exchangeid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $exchangeid, $set_field_arr) {
        return parent::field_update_list( $exchangeid, $set_field_arr);
    }


    public function field_get_value(  $exchangeid, $field_name ) {
        return parent::field_get_value( $exchangeid, $field_name);
    }

    public function row_delete(  $exchangeid) {
        return parent::row_delete( $exchangeid);
    }

}

/*
  CREATE TABLE `t_gift_consign` (
  `exchangeid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '兑换id',
  `userid` int(10) unsigned NOT NULL COMMENT '学生账号',
  `exchange_time` int(10) unsigned NOT NULL COMMENT '兑换时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '礼品发放状态 0待处理，1已发货，2已收货, 3已锁定,4已取消',
  `giftid` int(10) unsigned NOT NULL COMMENT '获取的礼品id（获取）',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '本次兑换的礼品的个数',
  `praise` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '消耗赞的个数',
  `account` varchar(32) NOT NULL DEFAULT '' COMMENT '礼品接收账号,仅供虚拟商品',
  `consign_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '礼物发送时间',
  `consignee` varchar(32) NOT NULL DEFAULT '' COMMENT '收货人',
  `address` varchar(300) NOT NULL DEFAULT '' COMMENT '收货地址',
  `consignee_phone` varchar(16) NOT NULL DEFAULT '' COMMENT '收货人联系方式',
  `express_name` varchar(32) NOT NULL DEFAULT '' COMMENT '快递公司名称',
  `express_num` varchar(20) NOT NULL DEFAULT '' COMMENT '快递单号',
  `send_time` int(10) unsigned NOT NULL COMMENT '礼品发放时间',
  `consigner` varchar(32) NOT NULL DEFAULT '' COMMENT '礼品发放人员',
  `consigner_phone` varchar(16) NOT NULL DEFAULT '' COMMENT '礼品发放人员联系方式',
  `delivery_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '礼品签收时间',
  `last_modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后修改时间',
  PRIMARY KEY (`exchangeid`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8
 */
