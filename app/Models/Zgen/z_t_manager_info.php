<?php
namespace App\Models\Zgen;
class z_t_manager_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_manager_info";


	/*int(10) */
	const C_uid='uid';

	/*varchar(50) */
	const C_account='account';

	/*varchar(32) */
	const C_name='name';

	/*varchar(64) */
	const C_email='email';

	/*char(11) */
	const C_phone='phone';

	/*varchar(1024) */
	const C_permission='permission';

	/*int(11) */
	const C_create_time='create_time';

	/*tinyint(4) */
	const C_del_flag='del_flag';

	/*varchar(10) */
	const C_administrator='administrator';

	/*int(11) */
	const C_account_role='account_role';

	/*varchar(255) */
	const C_wx_openid='wx_openid';

	/*int(11) */
	const C_creater_adminid='creater_adminid';

	/*int(11) */
	const C_cardid='cardid';

	/*int(11) */
	const C_seller_level='seller_level';

	/*varchar(255) */
	const C_wx_id='wx_id';

	/*int(11) */
	const C_tquin='tquin';

	/*int(11) */
	const C_up_adminid='up_adminid';

	/*int(11) */
	const C_admin_work_status='admin_work_status';

	/*int(11) */
	const C_last_login_time='last_login_time';

	/*int(11) */
	const C_day_new_user_flag='day_new_user_flag';

	/*varchar(16) */
	const C_ytx_phone='ytx_phone';

	/*int(11) */
	const C_become_full_member_flag='become_full_member_flag';

	/*varchar(255) */
	const C_fingerprint1='fingerprint1';

	/*varchar(255) */
	const C_fingerprint2='fingerprint2';

	/*longtext */
	const C_headpic='headpic';

	/*int(11) */
	const C_call_phone_type='call_phone_type';

	/*varchar(255) */
	const C_call_phone_passwd='call_phone_passwd';

	/*tinyint(4) */
	const C_company='company';

	/*tinyint(4) */
	const C_gender='gender';

	/*tinyint(4) */
	const C_education='education';

	/*tinyint(4) */
	const C_employee_level='employee_level';

	/*varchar(100) */
	const C_gra_school='gra_school';

	/*varchar(100) */
	const C_gra_major='gra_major';

	/*varchar(30) */
	const C_identity_card='identity_card';

	/*int(11) */
	const C_order_end_time='order_end_time';

	/*tinyint(4) */
	const C_post='post';

	/*tinyint(4) */
	const C_department='department';

	/*int(11) */
	const C_basic_pay='basic_pay';

	/*int(11) */
	const C_merit_pay='merit_pay';

	/*int(11) */
	const C_post_basic_pay='post_basic_pay';

	/*int(11) */
	const C_post_merit_pay='post_merit_pay';

	/*varchar(64) */
	const C_personal_email='personal_email';

	/*tinyint(4) */
	const C_department_group='department_group';

	/*varchar(255) */
	const C_personal_desc='personal_desc';

	/*int(11) */
	const C_become_full_member_time='become_full_member_time';

	/*varchar(255) */
	const C_resume_url='resume_url';

	/*tinyint(4) */
	const C_main_department='main_department';
	function get_account($uid ){
		return $this->field_get_value( $uid , self::C_account );
	}
	function get_name($uid ){
		return $this->field_get_value( $uid , self::C_name );
	}
	function get_email($uid ){
		return $this->field_get_value( $uid , self::C_email );
	}
	function get_phone($uid ){
		return $this->field_get_value( $uid , self::C_phone );
	}
	function get_permission($uid ){
		return $this->field_get_value( $uid , self::C_permission );
	}
	function get_create_time($uid ){
		return $this->field_get_value( $uid , self::C_create_time );
	}
	function get_del_flag($uid ){
		return $this->field_get_value( $uid , self::C_del_flag );
	}
	function get_administrator($uid ){
		return $this->field_get_value( $uid , self::C_administrator );
	}
	function get_account_role($uid ){
		return $this->field_get_value( $uid , self::C_account_role );
	}
	function get_wx_openid($uid ){
		return $this->field_get_value( $uid , self::C_wx_openid );
	}
	function get_creater_adminid($uid ){
		return $this->field_get_value( $uid , self::C_creater_adminid );
	}
	function get_cardid($uid ){
		return $this->field_get_value( $uid , self::C_cardid );
	}
	function get_seller_level($uid ){
		return $this->field_get_value( $uid , self::C_seller_level );
	}
	function get_wx_id($uid ){
		return $this->field_get_value( $uid , self::C_wx_id );
	}
	function get_tquin($uid ){
		return $this->field_get_value( $uid , self::C_tquin );
	}
	function get_up_adminid($uid ){
		return $this->field_get_value( $uid , self::C_up_adminid );
	}
	function get_admin_work_status($uid ){
		return $this->field_get_value( $uid , self::C_admin_work_status );
	}
	function get_last_login_time($uid ){
		return $this->field_get_value( $uid , self::C_last_login_time );
	}
	function get_day_new_user_flag($uid ){
		return $this->field_get_value( $uid , self::C_day_new_user_flag );
	}
	function get_ytx_phone($uid ){
		return $this->field_get_value( $uid , self::C_ytx_phone );
	}
	function get_become_full_member_flag($uid ){
		return $this->field_get_value( $uid , self::C_become_full_member_flag );
	}
	function get_fingerprint1($uid ){
		return $this->field_get_value( $uid , self::C_fingerprint1 );
	}
	function get_fingerprint2($uid ){
		return $this->field_get_value( $uid , self::C_fingerprint2 );
	}
	function get_headpic($uid ){
		return $this->field_get_value( $uid , self::C_headpic );
	}
	function get_call_phone_type($uid ){
		return $this->field_get_value( $uid , self::C_call_phone_type );
	}
	function get_call_phone_passwd($uid ){
		return $this->field_get_value( $uid , self::C_call_phone_passwd );
	}
	function get_company($uid ){
		return $this->field_get_value( $uid , self::C_company );
	}
	function get_gender($uid ){
		return $this->field_get_value( $uid , self::C_gender );
	}
	function get_education($uid ){
		return $this->field_get_value( $uid , self::C_education );
	}
	function get_employee_level($uid ){
		return $this->field_get_value( $uid , self::C_employee_level );
	}
	function get_gra_school($uid ){
		return $this->field_get_value( $uid , self::C_gra_school );
	}
	function get_gra_major($uid ){
		return $this->field_get_value( $uid , self::C_gra_major );
	}
	function get_identity_card($uid ){
		return $this->field_get_value( $uid , self::C_identity_card );
	}
	function get_order_end_time($uid ){
		return $this->field_get_value( $uid , self::C_order_end_time );
	}
	function get_post($uid ){
		return $this->field_get_value( $uid , self::C_post );
	}
	function get_department($uid ){
		return $this->field_get_value( $uid , self::C_department );
	}
	function get_basic_pay($uid ){
		return $this->field_get_value( $uid , self::C_basic_pay );
	}
	function get_merit_pay($uid ){
		return $this->field_get_value( $uid , self::C_merit_pay );
	}
	function get_post_basic_pay($uid ){
		return $this->field_get_value( $uid , self::C_post_basic_pay );
	}
	function get_post_merit_pay($uid ){
		return $this->field_get_value( $uid , self::C_post_merit_pay );
	}
	function get_personal_email($uid ){
		return $this->field_get_value( $uid , self::C_personal_email );
	}
	function get_department_group($uid ){
		return $this->field_get_value( $uid , self::C_department_group );
	}
	function get_personal_desc($uid ){
		return $this->field_get_value( $uid , self::C_personal_desc );
	}
	function get_become_full_member_time($uid ){
		return $this->field_get_value( $uid , self::C_become_full_member_time );
	}
	function get_resume_url($uid ){
		return $this->field_get_value( $uid , self::C_resume_url );
	}
	function get_main_department($uid ){
		return $this->field_get_value( $uid , self::C_main_department );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="uid";
        $this->field_table_name="db_weiyi_admin.t_manager_info";
  }
    public function field_get_list( $uid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $uid, $set_field_arr) {
        return parent::field_update_list( $uid, $set_field_arr);
    }


    public function field_get_value(  $uid, $field_name ) {
        return parent::field_get_value( $uid, $field_name);
    }

    public function row_delete(  $uid) {
        return parent::row_delete( $uid);
    }

}

/*
  CREATE TABLE `t_manager_info` (
  `uid` int(10) NOT NULL,
  `account` varchar(50) NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `email` varchar(64) NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `phone` char(11) NOT NULL DEFAULT '' COMMENT '手机号码',
  `permission` varchar(1024) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL,
  `del_flag` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'shanchu',
  `administrator` varchar(10) DEFAULT NULL,
  `account_role` int(11) NOT NULL COMMENT '角色  0:未设置1：助教2：销售3：other',
  `wx_openid` varchar(255) DEFAULT NULL,
  `creater_adminid` int(11) NOT NULL,
  `cardid` int(11) DEFAULT NULL COMMENT '考勤卡id',
  `seller_level` int(11) NOT NULL COMMENT '咨询师等级',
  `wx_id` varchar(255) NOT NULL COMMENT '微信号',
  `tquin` int(11) DEFAULT NULL COMMENT 'TQ adminid',
  `up_adminid` int(11) NOT NULL,
  `admin_work_status` int(11) NOT NULL COMMENT '教务老师工作状态	0 停止接课 1 开始接课',
  `last_login_time` int(11) NOT NULL COMMENT '最后一次登入时间',
  `day_new_user_flag` int(11) NOT NULL COMMENT '是否每天可获得新例子',
  `ytx_phone` varchar(16) NOT NULL COMMENT '云通讯电话',
  `become_full_member_flag` int(11) NOT NULL COMMENT '转正标识',
  `fingerprint1` varchar(255) NOT NULL COMMENT '指纹1',
  `fingerprint2` varchar(255) NOT NULL COMMENT '指纹2',
  `headpic` longtext NOT NULL COMMENT '头像',
  `call_phone_type` int(11) NOT NULL COMMENT '拨打电话类型',
  `call_phone_passwd` varchar(255) NOT NULL COMMENT '拨打电话密码',
  `company` tinyint(4) NOT NULL COMMENT '公司 1,理优;2,博尔捷',
  `gender` tinyint(4) NOT NULL COMMENT '性别',
  `education` tinyint(4) NOT NULL COMMENT '学历',
  `employee_level` tinyint(4) NOT NULL COMMENT '员工级别 1,员工;2,实习生',
  `gra_school` varchar(100) NOT NULL COMMENT '毕业院校',
  `gra_major` varchar(100) NOT NULL COMMENT '专业',
  `identity_card` varchar(30) NOT NULL COMMENT '身份证',
  `order_end_time` int(11) NOT NULL COMMENT '合同结束时间',
  `post` tinyint(4) NOT NULL COMMENT '岗位',
  `department` tinyint(4) NOT NULL COMMENT '部门',
  `basic_pay` int(11) NOT NULL COMMENT '基本工资',
  `merit_pay` int(11) NOT NULL COMMENT '绩效工资',
  `post_basic_pay` int(11) NOT NULL COMMENT '转正基本工资',
  `post_merit_pay` int(11) NOT NULL COMMENT '转正绩效工资',
  `personal_email` varchar(64) NOT NULL COMMENT '私人邮箱',
  `department_group` tinyint(4) NOT NULL COMMENT '小组',
  `personal_desc` varchar(255) NOT NULL COMMENT '个人备注',
  `become_full_member_time` int(11) NOT NULL COMMENT '转正时间',
  `resume_url` varchar(255) NOT NULL COMMENT '简历地址',
  `main_department` tinyint(4) NOT NULL COMMENT '公司部门',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `user` (`account`),
  UNIQUE KEY `db_weiyi_admin_t_manager_info_wx_openid_unique` (`wx_openid`),
  UNIQUE KEY `db_weiyi_admin_t_manager_info_cardid_unique` (`cardid`),
  UNIQUE KEY `db_weiyi_admin_t_manager_info_tquin_unique` (`tquin`),
  KEY `main_department` (`main_department`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */
