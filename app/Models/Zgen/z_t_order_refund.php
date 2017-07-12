<?php
namespace App\Models\Zgen;
class z_t_order_refund extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_order_refund";


	/*int(10) unsigned */
	const C_orderid='orderid';

	/*int(10) unsigned */
	const C_userid='userid';

	/*varchar(100) */
	const C_contractid='contractid';

	/*int(11) */
	const C_contract_type='contract_type';

	/*int(11) */
	const C_should_refund='should_refund';

	/*int(11) */
	const C_real_refund='real_refund';

	/*int(11) */
	const C_apply_time='apply_time';

	/*int(11) */
	const C_refund_status='refund_status';

	/*int(11) */
	const C_lesson_total='lesson_total';

	/*int(11) */
	const C_channelid='channelid';

	/*varchar(128) */
	const C_pay_account='pay_account';

	/*int(11) */
	const C_price='price';

	/*int(11) */
	const C_refund_channel='refund_channel';

	/*varchar(128) */
	const C_refund_number='refund_number';

	/*varchar(128) */
	const C_refund_reason='refund_reason';

	/*int(11) */
	const C_refund_time='refund_time';

	/*int(11) */
	const C_refund_userid='refund_userid';

	/*varchar(1000) */
	const C_save_info='save_info';

	/*varchar(4096) */
	const C_refund_info='refund_info';

	/*tinyint(4) */
	const C_has_receipt='has_receipt';

	/*varchar(255) */
	const C_file_url='file_url';

	/*varchar(1000) */
	const C_qc_other_reason='qc_other_reason';

	/*varchar(1000) */
	const C_qc_analysia='qc_analysia';

	/*varchar(1000) */
	const C_qc_reply='qc_reply';

	/*varchar(255) */
	const C_pay_account_admin='pay_account_admin';
	function get_userid($orderid, $apply_time ){
		return $this->field_get_value_2( $orderid, $apply_time  , self::C_userid  );
	}
	function get_contractid($orderid, $apply_time ){
		return $this->field_get_value_2( $orderid, $apply_time  , self::C_contractid  );
	}
	function get_contract_type($orderid, $apply_time ){
		return $this->field_get_value_2( $orderid, $apply_time  , self::C_contract_type  );
	}
	function get_should_refund($orderid, $apply_time ){
		return $this->field_get_value_2( $orderid, $apply_time  , self::C_should_refund  );
	}
	function get_real_refund($orderid, $apply_time ){
		return $this->field_get_value_2( $orderid, $apply_time  , self::C_real_refund  );
	}
	function get_refund_status($orderid, $apply_time ){
		return $this->field_get_value_2( $orderid, $apply_time  , self::C_refund_status  );
	}
	function get_lesson_total($orderid, $apply_time ){
		return $this->field_get_value_2( $orderid, $apply_time  , self::C_lesson_total  );
	}
	function get_channelid($orderid, $apply_time ){
		return $this->field_get_value_2( $orderid, $apply_time  , self::C_channelid  );
	}
	function get_pay_account($orderid, $apply_time ){
		return $this->field_get_value_2( $orderid, $apply_time  , self::C_pay_account  );
	}
	function get_price($orderid, $apply_time ){
		return $this->field_get_value_2( $orderid, $apply_time  , self::C_price  );
	}
	function get_refund_channel($orderid, $apply_time ){
		return $this->field_get_value_2( $orderid, $apply_time  , self::C_refund_channel  );
	}
	function get_refund_number($orderid, $apply_time ){
		return $this->field_get_value_2( $orderid, $apply_time  , self::C_refund_number  );
	}
	function get_refund_reason($orderid, $apply_time ){
		return $this->field_get_value_2( $orderid, $apply_time  , self::C_refund_reason  );
	}
	function get_refund_time($orderid, $apply_time ){
		return $this->field_get_value_2( $orderid, $apply_time  , self::C_refund_time  );
	}
	function get_refund_userid($orderid, $apply_time ){
		return $this->field_get_value_2( $orderid, $apply_time  , self::C_refund_userid  );
	}
	function get_save_info($orderid, $apply_time ){
		return $this->field_get_value_2( $orderid, $apply_time  , self::C_save_info  );
	}
	function get_refund_info($orderid, $apply_time ){
		return $this->field_get_value_2( $orderid, $apply_time  , self::C_refund_info  );
	}
	function get_has_receipt($orderid, $apply_time ){
		return $this->field_get_value_2( $orderid, $apply_time  , self::C_has_receipt  );
	}
	function get_file_url($orderid, $apply_time ){
		return $this->field_get_value_2( $orderid, $apply_time  , self::C_file_url  );
	}
	function get_qc_other_reason($orderid, $apply_time ){
		return $this->field_get_value_2( $orderid, $apply_time  , self::C_qc_other_reason  );
	}
	function get_qc_analysia($orderid, $apply_time ){
		return $this->field_get_value_2( $orderid, $apply_time  , self::C_qc_analysia  );
	}
	function get_qc_reply($orderid, $apply_time ){
		return $this->field_get_value_2( $orderid, $apply_time  , self::C_qc_reply  );
	}
	function get_pay_account_admin($orderid, $apply_time ){
		return $this->field_get_value_2( $orderid, $apply_time  , self::C_pay_account_admin  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="orderid";
        $this->field_id2_name="apply_time";
        $this->field_table_name="db_weiyi.t_order_refund";
  }

    public function field_get_value_2(  $orderid, $apply_time,$field_name ) {
        return parent::field_get_value_2(  $orderid, $apply_time,$field_name ) ;
    }

    public function field_get_list_2( $orderid,  $apply_time,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $orderid, $apply_time,  $set_field_arr ) {
        return parent::field_update_list_2( $orderid, $apply_time,  $set_field_arr );
    }
    public function row_delete_2(  $orderid ,$apply_time ) {
        return parent::row_delete_2( $orderid ,$apply_time );
    }


}
/*
  CREATE TABLE `t_order_refund` (
  `orderid` int(10) unsigned NOT NULL COMMENT '订单id',
  `userid` int(10) unsigned NOT NULL COMMENT '学生id',
  `contractid` varchar(100) NOT NULL COMMENT '合同编号',
  `contract_type` int(11) NOT NULL DEFAULT '0' COMMENT '合同类型 0 常规 1 赠送 2 试听',
  `should_refund` int(11) NOT NULL DEFAULT '0' COMMENT '应退课时',
  `real_refund` int(11) NOT NULL DEFAULT '0' COMMENT '实际退费金额',
  `apply_time` int(11) NOT NULL DEFAULT '0' COMMENT '提出退费申请的时间',
  `refund_status` int(11) NOT NULL DEFAULT '0' COMMENT '退款状态 0为未退款 1为已退款',
  `lesson_total` int(11) NOT NULL DEFAULT '0' COMMENT '总课时数',
  `channelid` int(11) NOT NULL DEFAULT '0' COMMENT '支付方式 0 未付款 1 支付宝 2银行卡转账',
  `pay_account` varchar(128) NOT NULL DEFAULT '' COMMENT '支付账号，为银行卡号或支付宝账号',
  `price` int(11) NOT NULL DEFAULT '0' COMMENT '支付金额',
  `refund_channel` int(11) NOT NULL DEFAULT '0' COMMENT '退款方式 0 未付款, 1 支付宝 2  银行卡转账',
  `refund_number` varchar(128) NOT NULL DEFAULT '' COMMENT '退款流水号',
  `refund_reason` varchar(128) NOT NULL DEFAULT '' COMMENT '退款原因',
  `refund_time` int(11) NOT NULL DEFAULT '0' COMMENT '退费的时间',
  `refund_userid` int(11) NOT NULL COMMENT '添加人',
  `save_info` varchar(1000) NOT NULL COMMENT '挽单结果',
  `refund_info` varchar(4096) DEFAULT NULL,
  `has_receipt` tinyint(4) NOT NULL COMMENT '是否有发票 0 没有 1 有',
  `file_url` varchar(255) NOT NULL COMMENT '退费手续内容,压缩文件',
  `qc_other_reason` varchar(1000) NOT NULL COMMENT 'qc其他原因',
  `qc_analysia` varchar(1000) NOT NULL COMMENT 'qc整体分析',
  `qc_reply` varchar(1000) NOT NULL COMMENT 'qc 应对方案',
  `pay_account_admin` varchar(255) NOT NULL COMMENT '支付宝帐号持有人 ',
  PRIMARY KEY (`orderid`,`apply_time`),
  KEY `orderid` (`orderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */
