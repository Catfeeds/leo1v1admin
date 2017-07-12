<?php
namespace App\Models\Zgen;
class z_t_student_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_student_info";


	/*int(10) unsigned */
	const C_userid='userid';

	/*varchar(32) */
	const C_nick='nick';

	/*varchar(100) */
	const C_face='face';

	/*int(10) unsigned */
	const C_upload_face_time='upload_face_time';

	/*int(10) unsigned */
	const C_praise='praise';

	/*int(10) unsigned */
	const C_exp='exp';

	/*varchar(16) */
	const C_phone='phone';

	/*varchar(16) */
	const C_stu_phone='stu_phone';

	/*tinyint(4) */
	const C_gender='gender';

	/*int(10) unsigned */
	const C_birth='birth';

	/*int(10) unsigned */
	const C_birthday_gift_time='birthday_gift_time';

	/*smallint(6) */
	const C_grade='grade';

	/*tinyint(4) */
	const C_type='type';

	/*tinyint(4) */
	const C_test_status='test_status';

	/*varchar(100) */
	const C_textbook='textbook';

	/*varchar(100) */
	const C_region='region';

	/*varchar(100) */
	const C_school='school';

	/*varchar(100) */
	const C_address='address';

	/*int(10) unsigned */
	const C_addr_code='addr_code';

	/*smallint(6) */
	const C_rate_score='rate_score';

	/*int(10) unsigned */
	const C_one_star='one_star';

	/*int(10) unsigned */
	const C_two_star='two_star';

	/*int(10) unsigned */
	const C_three_star='three_star';

	/*int(10) unsigned */
	const C_four_star='four_star';

	/*int(10) unsigned */
	const C_five_star='five_star';

	/*int(10) unsigned */
	const C_rate_ability='rate_ability';

	/*int(10) unsigned */
	const C_rate_attention='rate_attention';

	/*int(10) unsigned */
	const C_rate_attitude='rate_attitude';

	/*varchar(32) */
	const C_parent_name='parent_name';

	/*int(10) unsigned */
	const C_parentid='parentid';

	/*smallint(6) */
	const C_parent_type='parent_type';

	/*smallint(6) */
	const C_status='status';

	/*int(10) unsigned */
	const C_revisit_cnt='revisit_cnt';

	/*tinyint(4) */
	const C_gift_sent='gift_sent';

	/*smallint(6) */
	const C_reg_grade='reg_grade';

	/*int(10) unsigned */
	const C_reg_time='reg_time';

	/*int(10) unsigned */
	const C_reg_ip='reg_ip';

	/*int(10) unsigned */
	const C_login_cnt='login_cnt';

	/*int(10) unsigned */
	const C_last_login_ip='last_login_ip';

	/*int(10) unsigned */
	const C_last_login_time='last_login_time';

	/*varchar(1024) */
	const C_operator_note='operator_note';

	/*timestamp */
	const C_last_modified_time='last_modified_time';

	/*int(10) unsigned */
	const C_editionid='editionid';

	/*int(10) unsigned */
	const C_current_point='current_point';

	/*tinyint(4) */
	const C_is_called='is_called';

	/*varchar(300) */
	const C_user_agent='user_agent';

	/*varchar(10) */
	const C_host_code='host_code';

	/*varchar(10) */
	const C_guest_code='guest_code';

	/*varchar(20) */
	const C_ios_version='ios_version';

	/*varchar(20) */
	const C_android_version='android_version';

	/*varchar(20) */
	const C_test_room='test_room';

	/*int(11) */
	const C_revisit_status='revisit_status';

	/*int(11) */
	const C_revisit_time='revisit_time';

	/*int(11) */
	const C_hair='hair';

	/*int(11) */
	const C_clothes='clothes';

	/*int(11) */
	const C_assistantid='assistantid';

	/*int(11) */
	const C_is_test_user='is_test_user';

	/*int(11) */
	const C_originid='originid';

	/*int(11) */
	const C_origin_userid='origin_userid';

	/*int(11) */
	const C_lesson_count_all='lesson_count_all';

	/*int(11) */
	const C_lesson_count_left='lesson_count_left';

	/*int(11) */
	const C_seller_adminid='seller_adminid';

	/*varchar(20) */
	const C_origin='origin';

	/*varchar(255) */
	const C_spree='spree';

	/*int(10) unsigned */
	const C_last_lesson_time='last_lesson_time';

	/*int(11) */
	const C_money_all='money_all';

	/*int(11) */
	const C_ass_assign_time='ass_assign_time';

	/*varchar(255) */
	const C_email='email';

	/*varchar(255) */
	const C_init_info_pdf_url='init_info_pdf_url';

	/*varchar(255) */
	const C_phone_location='phone_location';

	/*varchar(32) */
	const C_realname='realname';

	/*int(11) */
	const C_ass_revisit_last_week_time='ass_revisit_last_week_time';

	/*int(11) */
	const C_ass_revisit_last_month_time='ass_revisit_last_month_time';

	/*int(11) */
	const C_sms_notify_flag='sms_notify_flag';

	/*int(11) */
	const C_last_revisit_admin_time='last_revisit_admin_time';

	/*int(11) */
	const C_last_revisit_adminid='last_revisit_adminid';

	/*varchar(255) */
	const C_stu_email='stu_email';

	/*varchar(255) */
	const C_stu_lesson_stop_reason='stu_lesson_stop_reason';

	/*int(11) */
	const C_is_auto_set_type_flag='is_auto_set_type_flag';

	/*int(11) */
	const C_origin_assistantid='origin_assistantid';

	/*int(11) */
	const C_origin_level='origin_level';

	/*int(11) */
	const C_ass_master_adminid='ass_master_adminid';

	/*int(11) */
	const C_master_assign_time='master_assign_time';

	/*int(11) */
	const C_type_change_time='type_change_time';

	/*int(11) */
	const C_stu_end_lesson_time='stu_end_lesson_time';
	function get_nick($userid ){
		return $this->field_get_value( $userid , self::C_nick );
	}
	function get_face($userid ){
		return $this->field_get_value( $userid , self::C_face );
	}
	function get_upload_face_time($userid ){
		return $this->field_get_value( $userid , self::C_upload_face_time );
	}
	function get_praise($userid ){
		return $this->field_get_value( $userid , self::C_praise );
	}
	function get_exp($userid ){
		return $this->field_get_value( $userid , self::C_exp );
	}
	function get_phone($userid ){
		return $this->field_get_value( $userid , self::C_phone );
	}
	function get_stu_phone($userid ){
		return $this->field_get_value( $userid , self::C_stu_phone );
	}
	function get_gender($userid ){
		return $this->field_get_value( $userid , self::C_gender );
	}
	function get_birth($userid ){
		return $this->field_get_value( $userid , self::C_birth );
	}
	function get_birthday_gift_time($userid ){
		return $this->field_get_value( $userid , self::C_birthday_gift_time );
	}
	function get_grade($userid ){
		return $this->field_get_value( $userid , self::C_grade );
	}
	function get_type($userid ){
		return $this->field_get_value( $userid , self::C_type );
	}
	function get_test_status($userid ){
		return $this->field_get_value( $userid , self::C_test_status );
	}
	function get_textbook($userid ){
		return $this->field_get_value( $userid , self::C_textbook );
	}
	function get_region($userid ){
		return $this->field_get_value( $userid , self::C_region );
	}
	function get_school($userid ){
		return $this->field_get_value( $userid , self::C_school );
	}
	function get_address($userid ){
		return $this->field_get_value( $userid , self::C_address );
	}
	function get_addr_code($userid ){
		return $this->field_get_value( $userid , self::C_addr_code );
	}
	function get_rate_score($userid ){
		return $this->field_get_value( $userid , self::C_rate_score );
	}
	function get_one_star($userid ){
		return $this->field_get_value( $userid , self::C_one_star );
	}
	function get_two_star($userid ){
		return $this->field_get_value( $userid , self::C_two_star );
	}
	function get_three_star($userid ){
		return $this->field_get_value( $userid , self::C_three_star );
	}
	function get_four_star($userid ){
		return $this->field_get_value( $userid , self::C_four_star );
	}
	function get_five_star($userid ){
		return $this->field_get_value( $userid , self::C_five_star );
	}
	function get_rate_ability($userid ){
		return $this->field_get_value( $userid , self::C_rate_ability );
	}
	function get_rate_attention($userid ){
		return $this->field_get_value( $userid , self::C_rate_attention );
	}
	function get_rate_attitude($userid ){
		return $this->field_get_value( $userid , self::C_rate_attitude );
	}
	function get_parent_name($userid ){
		return $this->field_get_value( $userid , self::C_parent_name );
	}
	function get_parentid($userid ){
		return $this->field_get_value( $userid , self::C_parentid );
	}
	function get_parent_type($userid ){
		return $this->field_get_value( $userid , self::C_parent_type );
	}
	function get_status($userid ){
		return $this->field_get_value( $userid , self::C_status );
	}
	function get_revisit_cnt($userid ){
		return $this->field_get_value( $userid , self::C_revisit_cnt );
	}
	function get_gift_sent($userid ){
		return $this->field_get_value( $userid , self::C_gift_sent );
	}
	function get_reg_grade($userid ){
		return $this->field_get_value( $userid , self::C_reg_grade );
	}
	function get_reg_time($userid ){
		return $this->field_get_value( $userid , self::C_reg_time );
	}
	function get_reg_ip($userid ){
		return $this->field_get_value( $userid , self::C_reg_ip );
	}
	function get_login_cnt($userid ){
		return $this->field_get_value( $userid , self::C_login_cnt );
	}
	function get_last_login_ip($userid ){
		return $this->field_get_value( $userid , self::C_last_login_ip );
	}
	function get_last_login_time($userid ){
		return $this->field_get_value( $userid , self::C_last_login_time );
	}
	function get_operator_note($userid ){
		return $this->field_get_value( $userid , self::C_operator_note );
	}
	function get_last_modified_time($userid ){
		return $this->field_get_value( $userid , self::C_last_modified_time );
	}
	function get_editionid($userid ){
		return $this->field_get_value( $userid , self::C_editionid );
	}
	function get_current_point($userid ){
		return $this->field_get_value( $userid , self::C_current_point );
	}
	function get_is_called($userid ){
		return $this->field_get_value( $userid , self::C_is_called );
	}
	function get_user_agent($userid ){
		return $this->field_get_value( $userid , self::C_user_agent );
	}
	function get_host_code($userid ){
		return $this->field_get_value( $userid , self::C_host_code );
	}
	function get_guest_code($userid ){
		return $this->field_get_value( $userid , self::C_guest_code );
	}
	function get_ios_version($userid ){
		return $this->field_get_value( $userid , self::C_ios_version );
	}
	function get_android_version($userid ){
		return $this->field_get_value( $userid , self::C_android_version );
	}
	function get_test_room($userid ){
		return $this->field_get_value( $userid , self::C_test_room );
	}
	function get_revisit_status($userid ){
		return $this->field_get_value( $userid , self::C_revisit_status );
	}
	function get_revisit_time($userid ){
		return $this->field_get_value( $userid , self::C_revisit_time );
	}
	function get_hair($userid ){
		return $this->field_get_value( $userid , self::C_hair );
	}
	function get_clothes($userid ){
		return $this->field_get_value( $userid , self::C_clothes );
	}
	function get_assistantid($userid ){
		return $this->field_get_value( $userid , self::C_assistantid );
	}
	function get_is_test_user($userid ){
		return $this->field_get_value( $userid , self::C_is_test_user );
	}
	function get_originid($userid ){
		return $this->field_get_value( $userid , self::C_originid );
	}
	function get_origin_userid($userid ){
		return $this->field_get_value( $userid , self::C_origin_userid );
	}
	function get_lesson_count_all($userid ){
		return $this->field_get_value( $userid , self::C_lesson_count_all );
	}
	function get_lesson_count_left($userid ){
		return $this->field_get_value( $userid , self::C_lesson_count_left );
	}
	function get_seller_adminid($userid ){
		return $this->field_get_value( $userid , self::C_seller_adminid );
	}
	function get_origin($userid ){
		return $this->field_get_value( $userid , self::C_origin );
	}
	function get_spree($userid ){
		return $this->field_get_value( $userid , self::C_spree );
	}
	function get_last_lesson_time($userid ){
		return $this->field_get_value( $userid , self::C_last_lesson_time );
	}
	function get_money_all($userid ){
		return $this->field_get_value( $userid , self::C_money_all );
	}
	function get_ass_assign_time($userid ){
		return $this->field_get_value( $userid , self::C_ass_assign_time );
	}
	function get_email($userid ){
		return $this->field_get_value( $userid , self::C_email );
	}
	function get_init_info_pdf_url($userid ){
		return $this->field_get_value( $userid , self::C_init_info_pdf_url );
	}
	function get_phone_location($userid ){
		return $this->field_get_value( $userid , self::C_phone_location );
	}
	function get_realname($userid ){
		return $this->field_get_value( $userid , self::C_realname );
	}
	function get_ass_revisit_last_week_time($userid ){
		return $this->field_get_value( $userid , self::C_ass_revisit_last_week_time );
	}
	function get_ass_revisit_last_month_time($userid ){
		return $this->field_get_value( $userid , self::C_ass_revisit_last_month_time );
	}
	function get_sms_notify_flag($userid ){
		return $this->field_get_value( $userid , self::C_sms_notify_flag );
	}
	function get_last_revisit_admin_time($userid ){
		return $this->field_get_value( $userid , self::C_last_revisit_admin_time );
	}
	function get_last_revisit_adminid($userid ){
		return $this->field_get_value( $userid , self::C_last_revisit_adminid );
	}
	function get_stu_email($userid ){
		return $this->field_get_value( $userid , self::C_stu_email );
	}
	function get_stu_lesson_stop_reason($userid ){
		return $this->field_get_value( $userid , self::C_stu_lesson_stop_reason );
	}
	function get_is_auto_set_type_flag($userid ){
		return $this->field_get_value( $userid , self::C_is_auto_set_type_flag );
	}
	function get_origin_assistantid($userid ){
		return $this->field_get_value( $userid , self::C_origin_assistantid );
	}
	function get_origin_level($userid ){
		return $this->field_get_value( $userid , self::C_origin_level );
	}
	function get_ass_master_adminid($userid ){
		return $this->field_get_value( $userid , self::C_ass_master_adminid );
	}
	function get_master_assign_time($userid ){
		return $this->field_get_value( $userid , self::C_master_assign_time );
	}
	function get_type_change_time($userid ){
		return $this->field_get_value( $userid , self::C_type_change_time );
	}
	function get_stu_end_lesson_time($userid ){
		return $this->field_get_value( $userid , self::C_stu_end_lesson_time );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="userid";
        $this->field_table_name="db_weiyi.t_student_info";
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
  CREATE TABLE `t_student_info` (
  `userid` int(10) unsigned NOT NULL COMMENT '用户id',
  `nick` varchar(32) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `face` varchar(100) NOT NULL DEFAULT '' COMMENT '学生的头像',
  `upload_face_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后一次设置头像的时间',
  `praise` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收集到的赞的个数',
  `exp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '经验',
  `phone` varchar(16) NOT NULL COMMENT '手机号码',
  `stu_phone` varchar(16) NOT NULL DEFAULT '' COMMENT '学生手机号码',
  `gender` tinyint(4) NOT NULL DEFAULT '0' COMMENT '用户性别（0保密，1男2女）',
  `birth` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '生日（格式如19910101）',
  `birthday_gift_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取生日赞的时间',
  `grade` smallint(6) NOT NULL DEFAULT '0' COMMENT '用户年级（100 小学 200 初中 300 高中 400 大学 500 硕士 600 博士 900 毕业）',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT ' 	学员类型（0 潜在学员 1 正常学员 2 已结课学员 3:停课学员）',
  `test_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '测评状态：0未测评，1已测评',
  `textbook` varchar(100) NOT NULL DEFAULT '' COMMENT '书籍版本',
  `region` varchar(100) NOT NULL DEFAULT '' COMMENT '上学的省份',
  `school` varchar(100) NOT NULL DEFAULT '' COMMENT '学校名称',
  `address` varchar(100) NOT NULL DEFAULT '' COMMENT '用户居住地址',
  `addr_code` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '地址编码',
  `rate_score` smallint(6) NOT NULL DEFAULT '0' COMMENT '学生的评分',
  `one_star` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '一星评价',
  `two_star` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二星评价',
  `three_star` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '三星评价',
  `four_star` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '四星评价',
  `five_star` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '五星评价',
  `rate_ability` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '应用能力',
  `rate_attention` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上课注意力',
  `rate_attitude` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '学习态度',
  `parent_name` varchar(32) NOT NULL DEFAULT '' COMMENT '家长姓名',
  `parentid` int(10) unsigned NOT NULL COMMENT '家长id',
  `parent_type` smallint(6) NOT NULL DEFAULT '0' COMMENT '家长与学生之间的关系（1父亲 2 母亲 3 爷爷 4 奶奶 5 外公 6 外婆 7 其他）',
  `status` smallint(6) NOT NULL DEFAULT '0' COMMENT '学程状态（0 未联系 1 待付款  2 正常上课 3 提出退费申请 4 退费成功 5已结课）',
  `revisit_cnt` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '回访次数',
  `gift_sent` tinyint(4) NOT NULL DEFAULT '0' COMMENT '礼物已经送出（0 未送 1 已送）',
  `reg_grade` smallint(6) NOT NULL COMMENT '用户注册时的年级',
  `reg_time` int(10) unsigned NOT NULL COMMENT '用户注册的时间',
  `reg_ip` int(10) unsigned NOT NULL COMMENT '用户ip',
  `login_cnt` int(10) unsigned NOT NULL COMMENT '登陆次数',
  `last_login_ip` int(10) unsigned NOT NULL COMMENT '最后一次登陆的ip',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后一次登陆的时间',
  `operator_note` varchar(1024) NOT NULL DEFAULT '' COMMENT '学生备注信息',
  `last_modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后修改时间',
  `editionid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '教材版本id',
  `current_point` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户所选择的知识点',
  `is_called` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否已经联系过',
  `user_agent` varchar(300) NOT NULL DEFAULT '' COMMENT '学生的设备信息',
  `host_code` varchar(10) NOT NULL DEFAULT '' COMMENT '邀请他人所用邀请码',
  `guest_code` varchar(10) NOT NULL DEFAULT '' COMMENT '被人邀请所输入邀请码',
  `ios_version` varchar(20) NOT NULL DEFAULT '' COMMENT 'ios version',
  `android_version` varchar(20) NOT NULL DEFAULT '' COMMENT 'android version',
  `test_room` varchar(20) NOT NULL DEFAULT '' COMMENT '试音室房间名称',
  `revisit_status` int(11) DEFAULT '0' COMMENT '回访状态',
  `revisit_time` int(11) DEFAULT NULL COMMENT '回访时间',
  `hair` int(11) DEFAULT '0' COMMENT '学生头饰',
  `clothes` int(11) DEFAULT '0' COMMENT '学生服饰',
  `assistantid` int(11) NOT NULL COMMENT '助教id',
  `is_test_user` int(11) NOT NULL COMMENT '1:测试人员，0:非测试人员',
  `originid` int(11) NOT NULL COMMENT '学员渠道',
  `origin_userid` int(11) NOT NULL COMMENT '转介绍的学生的id',
  `lesson_count_all` int(11) NOT NULL COMMENT '签约课时数',
  `lesson_count_left` int(11) NOT NULL COMMENT '剩余课时数',
  `seller_adminid` int(11) NOT NULL COMMENT '销售 adminid',
  `origin` varchar(20) NOT NULL COMMENT '渠道str',
  `spree` varchar(255) NOT NULL COMMENT '大礼包',
  `last_lesson_time` int(10) unsigned NOT NULL COMMENT '最后一次上课时间',
  `money_all` int(11) NOT NULL COMMENT '所有收入',
  `ass_assign_time` int(11) NOT NULL COMMENT '助教分配时间',
  `email` varchar(255) NOT NULL COMMENT '学生邮箱地址',
  `init_info_pdf_url` varchar(255) NOT NULL COMMENT '销售交接单',
  `phone_location` varchar(255) NOT NULL COMMENT '手机归属',
  `realname` varchar(32) NOT NULL COMMENT '真实姓名',
  `ass_revisit_last_week_time` int(11) NOT NULL COMMENT '周回访时间',
  `ass_revisit_last_month_time` int(11) NOT NULL COMMENT '月回访时间',
  `sms_notify_flag` int(11) NOT NULL DEFAULT '0' COMMENT '是否短信提醒',
  `last_revisit_admin_time` int(11) NOT NULL COMMENT '试听未签 最后一次重入资源库时间',
  `last_revisit_adminid` int(11) NOT NULL COMMENT '试听未签 最后一次重入资源库 获取人',
  `stu_email` varchar(255) NOT NULL COMMENT '邮箱 ',
  `stu_lesson_stop_reason` varchar(255) NOT NULL COMMENT '学生停课原因',
  `is_auto_set_type_flag` int(11) NOT NULL DEFAULT '0' COMMENT '是否系统自动更新学生类型,0系统自动,1,手动修改',
  `origin_assistantid` int(11) NOT NULL COMMENT '转介绍助教id',
  `origin_level` int(11) NOT NULL COMMENT '渠道等级',
  `ass_master_adminid` int(11) NOT NULL COMMENT '分配助教助长',
  `master_assign_time` int(11) NOT NULL COMMENT '分配助教助长时间',
  `type_change_time` int(11) NOT NULL COMMENT '学生类型变更时间',
  `stu_end_lesson_time` int(11) NOT NULL COMMENT '学生结课时间',
  PRIMARY KEY (`userid`),
  KEY `reg_time` (`reg_time`),
  KEY `t_student_info_last_lesson_time_index` (`last_lesson_time`),
  KEY `t_student_info_lesson_count_left_index` (`lesson_count_left`),
  KEY `t_student_info_praise_index` (`praise`),
  KEY `t_student_info_nick_index` (`nick`),
  KEY `t_student_info_realname_index` (`realname`),
  KEY `origin` (`origin`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='学生表'
 */
