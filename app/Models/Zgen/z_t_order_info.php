<?php
namespace App\Models\Zgen;
class z_t_order_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_order_info";


	/*int(10) unsigned */
	const C_orderid='orderid';

	/*int(10) unsigned */
	const C_is_new_stu='is_new_stu';

	/*varchar(100) */
	const C_contractid='contractid';

	/*int(11) */
	const C_contract_type='contract_type';

	/*int(10) unsigned */
	const C_contract_status='contract_status';

	/*int(10) unsigned */
	const C_contract_starttime='contract_starttime';

	/*int(10) unsigned */
	const C_contract_endtime='contract_endtime';

	/*int(10) unsigned */
	const C_order_time='order_time';

	/*int(10) unsigned */
	const C_pay_time='pay_time';

	/*int(10) unsigned */
	const C_grade='grade';

	/*int(10) unsigned */
	const C_lesson_total='lesson_total';

	/*int(10) unsigned */
	const C_price='price';

	/*varchar(256) */
	const C_presented_reason='presented_reason';

	/*int(11) */
	const C_should_refund='should_refund';

	/*int(10) unsigned */
	const C_userid='userid';

	/*varchar(300) */
	const C_requirement='requirement';

	/*tinyint(3) unsigned */
	const C_need_receipt='need_receipt';

	/*varchar(128) */
	const C_title='title';

	/*int(10) unsigned */
	const C_channelid='channelid';

	/*varchar(128) */
	const C_pay_number='pay_number';

	/*tinyint(4) */
	const C_order_status='order_status';

	/*varchar(32) */
	const C_sys_operator='sys_operator';

	/*varchar(65) */
	const C_tradeid='tradeid';

	/*varchar(100) */
	const C_buyer_email='buyer_email';

	/*tinyint(4) */
	const C_subject='subject';

	/*int(10) unsigned */
	const C_activity_type='activity_type';

	/*int(10) unsigned */
	const C_config_courseid='config_courseid';

	/*int(10) unsigned */
	const C_discount='discount';

	/*int(10) unsigned */
	const C_packageid='packageid';

	/*int(10) unsigned */
	const C_from_type='from_type';

	/*int(10) unsigned */
	const C_config_lesson_account_id='config_lesson_account_id';

	/*varchar(50) */
	const C_taobao_orderid='taobao_orderid';

	/*int(11) */
	const C_default_lesson_count='default_lesson_count';

	/*int(11) */
	const C_check_money_flag='check_money_flag';

	/*int(11) */
	const C_check_money_adminid='check_money_adminid';

	/*int(11) */
	const C_check_money_time='check_money_time';

	/*varchar(255) */
	const C_check_money_desc='check_money_desc';

	/*varchar(255) */
	const C_invoice='invoice';

	/*int(11) */
	const C_is_invoice='is_invoice';

	/*int(11) */
	const C_stu_from_type='stu_from_type';

	/*int(11) */
	const C_lesson_count='lesson_count';

	/*int(11) */
	const C_lesson_count_gift='lesson_count_gift';

	/*int(11) */
	const C_original_price='original_price';

	/*varchar(255) */
	const C_origin='origin';

	/*int(11) */
	const C_discount_price='discount_price';

	/*varchar(255) */
	const C_discount_reason='discount_reason';

	/*int(11) */
	const C_competition_flag='competition_flag';

	/*int(11) */
	const C_lesson_left='lesson_left';

	/*int(11) */
	const C_from_test_lesson_id='from_test_lesson_id';

	/*int(11) */
	const C_from_parent_order_type='from_parent_order_type';

	/*int(11) */
	const C_parent_order_id='parent_order_id';

	/*int(11) */
	const C_seller_get_new_user_count='seller_get_new_user_count';

	/*int(11) */
	const C_order_stamp_flag='order_stamp_flag';

	/*int(11) */
	const C_order_price_type='order_price_type';

	/*int(11) */
	const C_order_promotion_type='order_promotion_type';

	/*int(11) */
	const C_promotion_discount_price='promotion_discount_price';

	/*int(11) */
	const C_promotion_present_lesson='promotion_present_lesson';

	/*int(11) */
	const C_promotion_spec_discount='promotion_spec_discount';

	/*int(11) */
	const C_promotion_spec_present_lesson='promotion_spec_present_lesson';

	/*int(11) */
	const C_get_packge_time='get_packge_time';

	/*int(11) */
	const C_unit_price='unit_price';

	/*varchar(255) */
	const C_from_key='from_key';

	/*varchar(255) */
	const C_from_url='from_url';

	/*int(11) */
	const C_from_parent_order_lesson_count='from_parent_order_lesson_count';

	/*varchar(1024) */
	const C_remark='remark';

	/*int(11) */
	const C_lesson_weeks='lesson_weeks';

	/*int(11) */
	const C_lesson_duration='lesson_duration';

	/*varchar(255) */
	const C_addressee='addressee';

	/*varchar(255) */
	const C_receive_addr='receive_addr';

	/*varchar(255) */
	const C_pdf_url='pdf_url';

	/*varchar(100) */
	const C_receive_phone='receive_phone';

	/*int(11) */
	const C_applicant='applicant';

	/*int(11) */
	const C_app_time='app_time';

	/*varchar(50) */
	const C_main_send_admin='main_send_admin';

	/*varchar(100) */
	const C_mail_send_time='mail_send_time';

	/*varchar(255) */
	const C_mail_code='mail_code';

	/*varchar(255) */
	const C_mail_code_url='mail_code_url';

	/*int(11) */
	const C_is_send_flag='is_send_flag';

	/*varchar(64) */
	const C_channel='channel';
	function get_is_new_stu($orderid ){
		return $this->field_get_value( $orderid , self::C_is_new_stu );
	}
	function get_contractid($orderid ){
		return $this->field_get_value( $orderid , self::C_contractid );
	}
	function get_contract_type($orderid ){
		return $this->field_get_value( $orderid , self::C_contract_type );
	}
	function get_contract_status($orderid ){
		return $this->field_get_value( $orderid , self::C_contract_status );
	}
	function get_contract_starttime($orderid ){
		return $this->field_get_value( $orderid , self::C_contract_starttime );
	}
	function get_contract_endtime($orderid ){
		return $this->field_get_value( $orderid , self::C_contract_endtime );
	}
	function get_order_time($orderid ){
		return $this->field_get_value( $orderid , self::C_order_time );
	}
	function get_pay_time($orderid ){
		return $this->field_get_value( $orderid , self::C_pay_time );
	}
	function get_grade($orderid ){
		return $this->field_get_value( $orderid , self::C_grade );
	}
	function get_lesson_total($orderid ){
		return $this->field_get_value( $orderid , self::C_lesson_total );
	}
	function get_price($orderid ){
		return $this->field_get_value( $orderid , self::C_price );
	}
	function get_presented_reason($orderid ){
		return $this->field_get_value( $orderid , self::C_presented_reason );
	}
	function get_should_refund($orderid ){
		return $this->field_get_value( $orderid , self::C_should_refund );
	}
	function get_userid($orderid ){
		return $this->field_get_value( $orderid , self::C_userid );
	}
	function get_requirement($orderid ){
		return $this->field_get_value( $orderid , self::C_requirement );
	}
	function get_need_receipt($orderid ){
		return $this->field_get_value( $orderid , self::C_need_receipt );
	}
	function get_title($orderid ){
		return $this->field_get_value( $orderid , self::C_title );
	}
	function get_channelid($orderid ){
		return $this->field_get_value( $orderid , self::C_channelid );
	}
	function get_pay_number($orderid ){
		return $this->field_get_value( $orderid , self::C_pay_number );
	}
	function get_order_status($orderid ){
		return $this->field_get_value( $orderid , self::C_order_status );
	}
	function get_sys_operator($orderid ){
		return $this->field_get_value( $orderid , self::C_sys_operator );
	}
	function get_tradeid($orderid ){
		return $this->field_get_value( $orderid , self::C_tradeid );
	}
	function get_buyer_email($orderid ){
		return $this->field_get_value( $orderid , self::C_buyer_email );
	}
	function get_subject($orderid ){
		return $this->field_get_value( $orderid , self::C_subject );
	}
	function get_activity_type($orderid ){
		return $this->field_get_value( $orderid , self::C_activity_type );
	}
	function get_config_courseid($orderid ){
		return $this->field_get_value( $orderid , self::C_config_courseid );
	}
	function get_discount($orderid ){
		return $this->field_get_value( $orderid , self::C_discount );
	}
	function get_packageid($orderid ){
		return $this->field_get_value( $orderid , self::C_packageid );
	}
	function get_from_type($orderid ){
		return $this->field_get_value( $orderid , self::C_from_type );
	}
	function get_config_lesson_account_id($orderid ){
		return $this->field_get_value( $orderid , self::C_config_lesson_account_id );
	}
	function get_taobao_orderid($orderid ){
		return $this->field_get_value( $orderid , self::C_taobao_orderid );
	}
	function get_default_lesson_count($orderid ){
		return $this->field_get_value( $orderid , self::C_default_lesson_count );
	}
	function get_check_money_flag($orderid ){
		return $this->field_get_value( $orderid , self::C_check_money_flag );
	}
	function get_check_money_adminid($orderid ){
		return $this->field_get_value( $orderid , self::C_check_money_adminid );
	}
	function get_check_money_time($orderid ){
		return $this->field_get_value( $orderid , self::C_check_money_time );
	}
	function get_check_money_desc($orderid ){
		return $this->field_get_value( $orderid , self::C_check_money_desc );
	}
	function get_invoice($orderid ){
		return $this->field_get_value( $orderid , self::C_invoice );
	}
	function get_is_invoice($orderid ){
		return $this->field_get_value( $orderid , self::C_is_invoice );
	}
	function get_stu_from_type($orderid ){
		return $this->field_get_value( $orderid , self::C_stu_from_type );
	}
	function get_lesson_count($orderid ){
		return $this->field_get_value( $orderid , self::C_lesson_count );
	}
	function get_lesson_count_gift($orderid ){
		return $this->field_get_value( $orderid , self::C_lesson_count_gift );
	}
	function get_original_price($orderid ){
		return $this->field_get_value( $orderid , self::C_original_price );
	}
	function get_origin($orderid ){
		return $this->field_get_value( $orderid , self::C_origin );
	}
	function get_discount_price($orderid ){
		return $this->field_get_value( $orderid , self::C_discount_price );
	}
	function get_discount_reason($orderid ){
		return $this->field_get_value( $orderid , self::C_discount_reason );
	}
	function get_competition_flag($orderid ){
		return $this->field_get_value( $orderid , self::C_competition_flag );
	}
	function get_lesson_left($orderid ){
		return $this->field_get_value( $orderid , self::C_lesson_left );
	}
	function get_from_test_lesson_id($orderid ){
		return $this->field_get_value( $orderid , self::C_from_test_lesson_id );
	}
	function get_from_parent_order_type($orderid ){
		return $this->field_get_value( $orderid , self::C_from_parent_order_type );
	}
	function get_parent_order_id($orderid ){
		return $this->field_get_value( $orderid , self::C_parent_order_id );
	}
	function get_seller_get_new_user_count($orderid ){
		return $this->field_get_value( $orderid , self::C_seller_get_new_user_count );
	}
	function get_order_stamp_flag($orderid ){
		return $this->field_get_value( $orderid , self::C_order_stamp_flag );
	}
	function get_order_price_type($orderid ){
		return $this->field_get_value( $orderid , self::C_order_price_type );
	}
	function get_order_promotion_type($orderid ){
		return $this->field_get_value( $orderid , self::C_order_promotion_type );
	}
	function get_promotion_discount_price($orderid ){
		return $this->field_get_value( $orderid , self::C_promotion_discount_price );
	}
	function get_promotion_present_lesson($orderid ){
		return $this->field_get_value( $orderid , self::C_promotion_present_lesson );
	}
	function get_promotion_spec_discount($orderid ){
		return $this->field_get_value( $orderid , self::C_promotion_spec_discount );
	}
	function get_promotion_spec_present_lesson($orderid ){
		return $this->field_get_value( $orderid , self::C_promotion_spec_present_lesson );
	}
	function get_get_packge_time($orderid ){
		return $this->field_get_value( $orderid , self::C_get_packge_time );
	}
	function get_unit_price($orderid ){
		return $this->field_get_value( $orderid , self::C_unit_price );
	}
	function get_from_key($orderid ){
		return $this->field_get_value( $orderid , self::C_from_key );
	}
	function get_from_url($orderid ){
		return $this->field_get_value( $orderid , self::C_from_url );
	}
	function get_from_parent_order_lesson_count($orderid ){
		return $this->field_get_value( $orderid , self::C_from_parent_order_lesson_count );
	}
	function get_remark($orderid ){
		return $this->field_get_value( $orderid , self::C_remark );
	}
	function get_lesson_weeks($orderid ){
		return $this->field_get_value( $orderid , self::C_lesson_weeks );
	}
	function get_lesson_duration($orderid ){
		return $this->field_get_value( $orderid , self::C_lesson_duration );
	}
	function get_addressee($orderid ){
		return $this->field_get_value( $orderid , self::C_addressee );
	}
	function get_receive_addr($orderid ){
		return $this->field_get_value( $orderid , self::C_receive_addr );
	}
	function get_pdf_url($orderid ){
		return $this->field_get_value( $orderid , self::C_pdf_url );
	}
	function get_receive_phone($orderid ){
		return $this->field_get_value( $orderid , self::C_receive_phone );
	}
	function get_applicant($orderid ){
		return $this->field_get_value( $orderid , self::C_applicant );
	}
	function get_app_time($orderid ){
		return $this->field_get_value( $orderid , self::C_app_time );
	}
	function get_main_send_admin($orderid ){
		return $this->field_get_value( $orderid , self::C_main_send_admin );
	}
	function get_mail_send_time($orderid ){
		return $this->field_get_value( $orderid , self::C_mail_send_time );
	}
	function get_mail_code($orderid ){
		return $this->field_get_value( $orderid , self::C_mail_code );
	}
	function get_mail_code_url($orderid ){
		return $this->field_get_value( $orderid , self::C_mail_code_url );
	}
	function get_is_send_flag($orderid ){
		return $this->field_get_value( $orderid , self::C_is_send_flag );
	}
	function get_channel($orderid ){
		return $this->field_get_value( $orderid , self::C_channel );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="orderid";
        $this->field_table_name="db_weiyi.t_order_info";
  }
    public function field_get_list( $orderid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $orderid, $set_field_arr) {
        return parent::field_update_list( $orderid, $set_field_arr);
    }


    public function field_get_value(  $orderid, $field_name ) {
        return parent::field_get_value( $orderid, $field_name);
    }

    public function row_delete(  $orderid) {
        return parent::row_delete( $orderid);
    }

}

/*
  CREATE TABLE `t_order_info` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单id',
  `is_new_stu` int(10) unsigned NOT NULL COMMENT '新签:1/续签:0',
  `contractid` varchar(100) NOT NULL COMMENT '合同编号',
  `contract_type` int(11) NOT NULL DEFAULT '0' COMMENT '合同类型 0 常规 1 赠送 2 试听',
  `contract_status` int(10) unsigned DEFAULT '0' COMMENT '合同状态 0 未付款 1执行中  2 已结束 3 提前终止 ',
  `contract_starttime` int(10) unsigned DEFAULT '0' COMMENT '生效日期',
  `contract_endtime` int(10) unsigned DEFAULT NULL COMMENT '截止日期',
  `order_time` int(10) unsigned NOT NULL COMMENT '下订单时间',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单完成时间',
  `grade` int(10) unsigned NOT NULL COMMENT '年级',
  `lesson_total` int(10) unsigned NOT NULL COMMENT '课时数',
  `price` int(10) unsigned NOT NULL COMMENT '实付价格',
  `presented_reason` varchar(256) NOT NULL DEFAULT '' COMMENT '赠送课程原因',
  `should_refund` int(11) NOT NULL DEFAULT '0' COMMENT '如果是赠送课程是否应该退款 1 应退款 0 不应退款',
  `userid` int(10) unsigned NOT NULL COMMENT '学生id',
  `requirement` varchar(300) NOT NULL DEFAULT '' COMMENT '排课需求',
  `need_receipt` tinyint(3) unsigned DEFAULT '0' COMMENT '是否需要发票 0 不需要 1 需要',
  `title` varchar(128) DEFAULT '' COMMENT '发票抬头',
  `channelid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '付款渠道',
  `pay_number` varchar(128) NOT NULL DEFAULT '' COMMENT '卡号',
  `order_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '订单状态0待支付１支付完成',
  `sys_operator` varchar(32) NOT NULL DEFAULT '' COMMENT '下单人',
  `tradeid` varchar(65) NOT NULL DEFAULT '' COMMENT '支付宝订单号',
  `buyer_email` varchar(100) NOT NULL DEFAULT '' COMMENT '买家支付宝',
  `subject` tinyint(4) NOT NULL DEFAULT '0' COMMENT '科目 0未设定 1语文 2数学 3英语 4化学 5物理 6生物 7政治 8历史 9地理',
  `activity_type` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动类型 0正常 1 summer holiday',
  `config_courseid` int(10) unsigned NOT NULL,
  `discount` int(10) unsigned NOT NULL COMMENT '当前订单所做的折扣',
  `packageid` int(10) unsigned NOT NULL COMMENT '课程包id',
  `from_type` int(10) unsigned NOT NULL COMMENT '0:课程包,1:按课时购买的',
  `config_lesson_account_id` int(10) unsigned NOT NULL COMMENT '按课时购买的 id',
  `taobao_orderid` varchar(50) NOT NULL,
  `default_lesson_count` int(11) NOT NULL COMMENT '每次课几课时',
  `check_money_flag` int(11) NOT NULL COMMENT '财务审查flag',
  `check_money_adminid` int(11) NOT NULL COMMENT '财务审查者',
  `check_money_time` int(11) NOT NULL COMMENT '财务审查时间',
  `check_money_desc` varchar(255) NOT NULL COMMENT '财务审查说明',
  `invoice` varchar(255) NOT NULL COMMENT '发票编号',
  `is_invoice` int(11) NOT NULL COMMENT '是否需要发票0:不需要1:需要',
  `stu_from_type` int(11) NOT NULL,
  `lesson_count` int(11) NOT NULL,
  `lesson_count_gift` int(11) NOT NULL,
  `original_price` int(11) NOT NULL,
  `origin` varchar(255) NOT NULL COMMENT '渠道',
  `discount_price` int(11) NOT NULL COMMENT '原始价格',
  `discount_reason` varchar(255) NOT NULL COMMENT '折扣原因',
  `competition_flag` int(11) NOT NULL DEFAULT '0' COMMENT '竞赛标志 0 常规课,1竞赛课',
  `lesson_left` int(11) NOT NULL DEFAULT '0' COMMENT '剩余课时',
  `from_test_lesson_id` int(11) NOT NULL COMMENT '来自哪节试听课',
  `from_parent_order_type` int(11) NOT NULL COMMENT '父合同分类;0:课程包赠送, 1:转介绍赠送, 10:课程异常赠送',
  `parent_order_id` int(11) NOT NULL COMMENT '父合同id',
  `seller_get_new_user_count` int(11) NOT NULL COMMENT '销售因此获得新例子个数',
  `order_stamp_flag` int(11) NOT NULL COMMENT '是否已盖章',
  `order_price_type` int(11) NOT NULL COMMENT '促销id',
  `order_promotion_type` int(11) NOT NULL COMMENT '促销分类',
  `promotion_discount_price` int(11) NOT NULL COMMENT '折扣后价格*100',
  `promotion_present_lesson` int(11) NOT NULL COMMENT '赠送*100',
  `promotion_spec_discount` int(11) NOT NULL COMMENT '特殊折扣后价格*100',
  `promotion_spec_present_lesson` int(11) NOT NULL COMMENT '特殊赠送*100',
  `get_packge_time` int(11) NOT NULL COMMENT '获取大礼包时间',
  `unit_price` int(11) NOT NULL COMMENT '课时单价',
  `from_key` varchar(255) DEFAULT NULL COMMENT '外部关联的订单号',
  `from_url` varchar(255) NOT NULL COMMENT '外部关联的数据地址',
  `from_parent_order_lesson_count` int(11) NOT NULL COMMENT '转赠的课时数',
  `remark` varchar(1024) NOT NULL COMMENT '备注',
  `lesson_weeks` int(11) NOT NULL COMMENT '每周课时',
  `lesson_duration` int(11) NOT NULL COMMENT '每节课时长',
  `addressee` varchar(255) NOT NULL COMMENT '收件人',
  `receive_addr` varchar(255) NOT NULL COMMENT '收件地址',
  `pdf_url` varchar(255) NOT NULL COMMENT '文件地址',
  `receive_phone` varchar(100) DEFAULT NULL,
  `applicant` int(11) NOT NULL COMMENT '申请人',
  `app_time` int(11) NOT NULL COMMENT '申请时间',
  `main_send_admin` varchar(50) DEFAULT NULL,
  `mail_send_time` varchar(100) DEFAULT NULL,
  `mail_code` varchar(255) DEFAULT NULL,
  `mail_code_url` varchar(255) NOT NULL COMMENT '运单号截图',
  `is_send_flag` int(11) NOT NULL COMMENT '是否邮寄合同',
  `channel` varchar(64) NOT NULL COMMENT '付款渠道',
  PRIMARY KEY (`orderid`),
  UNIQUE KEY `db_weiyi_t_order_info_from_key_unique` (`from_key`),
  KEY `order_time` (`order_time`),
  KEY `userid` (`userid`),
  KEY `config_lesson_account_id` (`config_lesson_account_id`),
  KEY `t_order_info_from_test_lesson_id_index` (`from_test_lesson_id`),
  KEY `t_order_info_parent_order_id_index` (`parent_order_id`),
  KEY `db_weiyi_t_order_info_get_packge_time_index` (`get_packge_time`),
  KEY `app_time` (`app_time`)
) ENGINE=InnoDB AUTO_INCREMENT=1172 DEFAULT CHARSET=utf8 COMMENT='合同表'
 */
