<?php
namespace App\Models\Zgen;
class z_t_seller_student_new  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_seller_student_new";


	/*int(11) */
	const C_userid='userid';

	/*varchar(16) */
	const C_phone='phone';

	/*varchar(64) */
	const C_phone_location='phone_location';

	/*int(11) */
	const C_seller_resource_type='seller_resource_type';

	/*int(11) */
	const C_add_time='add_time';

	/*int(11) */
	const C_has_pad='has_pad';

	/*int(11) */
	const C_admin_assignerid='admin_assignerid';

	/*int(11) */
	const C_sub_assign_adminid_1='sub_assign_adminid_1';

	/*int(11) */
	const C_sub_assign_time_1='sub_assign_time_1';

	/*int(11) */
	const C_sub_assign_adminid_2='sub_assign_adminid_2';

	/*int(11) */
	const C_sub_assign_time_2='sub_assign_time_2';

	/*int(11) */
	const C_admin_revisiterid='admin_revisiterid';

	/*int(11) */
	const C_admin_assign_time='admin_assign_time';

	/*int(11) */
	const C_next_revisit_time='next_revisit_time';

	/*varchar(255) */
	const C_user_desc='user_desc';

	/*int(11) */
	const C_first_revisit_time='first_revisit_time';

	/*int(11) */
	const C_tq_called_flag='tq_called_flag';

	/*int(11) */
	const C_global_tq_called_flag='global_tq_called_flag';

	/*int(11) */
	const C_last_revisit_time='last_revisit_time';

	/*varchar(255) */
	const C_last_revisit_msg='last_revisit_msg';

	/*int(11) */
	const C_stu_test_ipad_flag='stu_test_ipad_flag';

	/*varchar(255) */
	const C_stu_score_info='stu_score_info';

	/*varchar(255) */
	const C_stu_character_info='stu_character_info';

	/*int(11) */
	const C_tmk_join_time='tmk_join_time';

	/*int(11) */
	const C_tmk_adminid='tmk_adminid';

	/*int(11) */
	const C_tmk_assign_time='tmk_assign_time';

	/*int(11) */
	const C_tmk_student_status='tmk_student_status';

	/*int(11) */
	const C_tmk_next_revisit_time='tmk_next_revisit_time';

	/*varchar(500) */
	const C_tmk_desc='tmk_desc';

	/*varchar(255) */
	const C_not_test_ipad_reason='not_test_ipad_reason';

	/*int(11) */
	const C_hold_flag='hold_flag';

	/*int(11) */
	const C_return_publish_count='return_publish_count';

	/*int(11) */
	const C_first_admin_revisiterid='first_admin_revisiterid';

	/*int(11) */
	const C_called_time='called_time';

	/*int(11) */
	const C_first_contact_time='first_contact_time';

	/*int(11) */
	const C_sys_invaild_flag='sys_invaild_flag';

	/*int(11) */
	const C_competition_call_adminid='competition_call_adminid';

	/*int(11) */
	const C_competition_call_time='competition_call_time';

	/*int(11) */
	const C_last_contact_time='last_contact_time';

	/*int(11) */
	const C_global_seller_student_status='global_seller_student_status';

	/*int(11) */
	const C_first_seller_adminid='first_seller_adminid';

	/*int(11) */
	const C_tmk_set_seller_adminid='tmk_set_seller_adminid';

	/*int(11) */
	const C_origin_vaild_flag='origin_vaild_flag';

	/*int(11) */
	const C_first_call_time='first_call_time';
	function get_phone($userid ){
		return $this->field_get_value( $userid , self::C_phone );
	}
	function get_phone_location($userid ){
		return $this->field_get_value( $userid , self::C_phone_location );
	}
	function get_seller_resource_type($userid ){
		return $this->field_get_value( $userid , self::C_seller_resource_type );
	}
	function get_add_time($userid ){
		return $this->field_get_value( $userid , self::C_add_time );
	}
	function get_has_pad($userid ){
		return $this->field_get_value( $userid , self::C_has_pad );
	}
	function get_admin_assignerid($userid ){
		return $this->field_get_value( $userid , self::C_admin_assignerid );
	}
	function get_sub_assign_adminid_1($userid ){
		return $this->field_get_value( $userid , self::C_sub_assign_adminid_1 );
	}
	function get_sub_assign_time_1($userid ){
		return $this->field_get_value( $userid , self::C_sub_assign_time_1 );
	}
	function get_sub_assign_adminid_2($userid ){
		return $this->field_get_value( $userid , self::C_sub_assign_adminid_2 );
	}
	function get_sub_assign_time_2($userid ){
		return $this->field_get_value( $userid , self::C_sub_assign_time_2 );
	}
	function get_admin_revisiterid($userid ){
		return $this->field_get_value( $userid , self::C_admin_revisiterid );
	}
	function get_admin_assign_time($userid ){
		return $this->field_get_value( $userid , self::C_admin_assign_time );
	}
	function get_next_revisit_time($userid ){
		return $this->field_get_value( $userid , self::C_next_revisit_time );
	}
	function get_user_desc($userid ){
		return $this->field_get_value( $userid , self::C_user_desc );
	}
	function get_first_revisit_time($userid ){
		return $this->field_get_value( $userid , self::C_first_revisit_time );
	}
	function get_tq_called_flag($userid ){
		return $this->field_get_value( $userid , self::C_tq_called_flag );
	}
	function get_global_tq_called_flag($userid ){
		return $this->field_get_value( $userid , self::C_global_tq_called_flag );
	}
	function get_last_revisit_time($userid ){
		return $this->field_get_value( $userid , self::C_last_revisit_time );
	}
	function get_last_revisit_msg($userid ){
		return $this->field_get_value( $userid , self::C_last_revisit_msg );
	}
	function get_stu_test_ipad_flag($userid ){
		return $this->field_get_value( $userid , self::C_stu_test_ipad_flag );
	}
	function get_stu_score_info($userid ){
		return $this->field_get_value( $userid , self::C_stu_score_info );
	}
	function get_stu_character_info($userid ){
		return $this->field_get_value( $userid , self::C_stu_character_info );
	}
	function get_tmk_join_time($userid ){
		return $this->field_get_value( $userid , self::C_tmk_join_time );
	}
	function get_tmk_adminid($userid ){
		return $this->field_get_value( $userid , self::C_tmk_adminid );
	}
	function get_tmk_assign_time($userid ){
		return $this->field_get_value( $userid , self::C_tmk_assign_time );
	}
	function get_tmk_student_status($userid ){
		return $this->field_get_value( $userid , self::C_tmk_student_status );
	}
	function get_tmk_next_revisit_time($userid ){
		return $this->field_get_value( $userid , self::C_tmk_next_revisit_time );
	}
	function get_tmk_desc($userid ){
		return $this->field_get_value( $userid , self::C_tmk_desc );
	}
	function get_not_test_ipad_reason($userid ){
		return $this->field_get_value( $userid , self::C_not_test_ipad_reason );
	}
	function get_hold_flag($userid ){
		return $this->field_get_value( $userid , self::C_hold_flag );
	}
	function get_return_publish_count($userid ){
		return $this->field_get_value( $userid , self::C_return_publish_count );
	}
	function get_first_admin_revisiterid($userid ){
		return $this->field_get_value( $userid , self::C_first_admin_revisiterid );
	}
	function get_called_time($userid ){
		return $this->field_get_value( $userid , self::C_called_time );
	}
	function get_first_contact_time($userid ){
		return $this->field_get_value( $userid , self::C_first_contact_time );
	}
	function get_sys_invaild_flag($userid ){
		return $this->field_get_value( $userid , self::C_sys_invaild_flag );
	}
	function get_competition_call_adminid($userid ){
		return $this->field_get_value( $userid , self::C_competition_call_adminid );
	}
	function get_competition_call_time($userid ){
		return $this->field_get_value( $userid , self::C_competition_call_time );
	}
	function get_last_contact_time($userid ){
		return $this->field_get_value( $userid , self::C_last_contact_time );
	}
	function get_global_seller_student_status($userid ){
		return $this->field_get_value( $userid , self::C_global_seller_student_status );
	}
	function get_first_seller_adminid($userid ){
		return $this->field_get_value( $userid , self::C_first_seller_adminid );
	}
	function get_tmk_set_seller_adminid($userid ){
		return $this->field_get_value( $userid , self::C_tmk_set_seller_adminid );
	}
	function get_origin_vaild_flag($userid ){
		return $this->field_get_value( $userid , self::C_origin_vaild_flag );
	}
	function get_first_call_time($userid ){
		return $this->field_get_value( $userid , self::C_first_call_time );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="userid";
        $this->field_table_name="db_weiyi.t_seller_student_new";
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
  CREATE TABLE `t_seller_student_new` (
  `userid` int(11) NOT NULL,
  `phone` varchar(16) COLLATE latin1_bin NOT NULL COMMENT '手机号',
  `phone_location` varchar(64) COLLATE latin1_bin NOT NULL COMMENT '手机归属地',
  `seller_resource_type` int(11) NOT NULL COMMENT '渠道,转介绍,抢未回访,抢试听未签',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `has_pad` int(11) NOT NULL COMMENT '0: 1 2',
  `admin_assignerid` int(11) NOT NULL COMMENT '分配者id',
  `sub_assign_adminid_1` int(11) NOT NULL COMMENT '分配到主管',
  `sub_assign_time_1` int(11) NOT NULL,
  `sub_assign_adminid_2` int(11) NOT NULL COMMENT '分配到组长',
  `sub_assign_time_2` int(11) NOT NULL,
  `admin_revisiterid` int(11) NOT NULL COMMENT '分配给组员',
  `admin_assign_time` int(11) NOT NULL COMMENT '分配时间',
  `next_revisit_time` int(11) NOT NULL COMMENT ' 	下次回访时间',
  `user_desc` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '备注',
  `first_revisit_time` int(11) NOT NULL COMMENT '第一次回访时间',
  `tq_called_flag` int(11) NOT NULL COMMENT 'tq呼叫标志:0,1,2',
  `global_tq_called_flag` int(11) NOT NULL COMMENT 'tq呼叫标志:0,1,2',
  `last_revisit_time` int(11) NOT NULL COMMENT '最后一次回访的时间',
  `last_revisit_msg` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '最后一次回访的内容',
  `stu_test_ipad_flag` int(11) NOT NULL COMMENT '销售是否已经连线测试 ',
  `stu_score_info` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '成绩情况',
  `stu_character_info` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '性格特点',
  `tmk_join_time` int(11) NOT NULL COMMENT '加入tmk资源时间',
  `tmk_adminid` int(11) NOT NULL COMMENT '分配给谁负责',
  `tmk_assign_time` int(11) NOT NULL COMMENT 'tmk分配时间',
  `tmk_student_status` int(11) NOT NULL COMMENT 'tmp标识状态',
  `tmk_next_revisit_time` int(11) NOT NULL COMMENT 'tmk下次回访时间',
  `tmk_desc` varchar(500) COLLATE latin1_bin NOT NULL COMMENT 'tmk说明',
  `not_test_ipad_reason` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '销售未连线测试原因',
  `hold_flag` int(11) NOT NULL COMMENT '是否保留',
  `return_publish_count` int(11) NOT NULL COMMENT '回公海次数',
  `first_admin_revisiterid` int(11) NOT NULL COMMENT '首次分配给的销售',
  `called_time` int(11) NOT NULL COMMENT '未接通电话次数',
  `first_contact_time` int(11) NOT NULL COMMENT '首次接通时间',
  `sys_invaild_flag` int(11) NOT NULL COMMENT '通话是否有效',
  `competition_call_adminid` int(11) NOT NULL COMMENT '抢用户的adminid',
  `competition_call_time` int(11) NOT NULL COMMENT '抢用户的时间',
  `last_contact_time` int(11) NOT NULL COMMENT '最后一次电话时间',
  `global_seller_student_status` int(11) NOT NULL COMMENT '全局的状态',
  `first_seller_adminid` int(11) NOT NULL COMMENT '新例子的cc id',
  `tmk_set_seller_adminid` int(11) NOT NULL COMMENT 'tmk  设置的 cc id ',
  `origin_vaild_flag` int(11) NOT NULL COMMENT '资源是否无效',
  `first_call_time` int(11) NOT NULL COMMENT '第一次拨打时间',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `t_seller_student_new_phone_unique` (`phone`),
  KEY `t_seller_student_new_add_time_index` (`add_time`),
  KEY `t_seller_student_new_sub_assign_time_1_index` (`sub_assign_time_1`),
  KEY `t_seller_student_new_sub_assign_time_2_index` (`sub_assign_time_2`),
  KEY `t_seller_student_new_first_revisit_time_index` (`first_revisit_time`),
  KEY `t_seller_student_new_next_revisit_time_index` (`next_revisit_time`),
  KEY `t_seller_student_new_last_revisit_time_index` (`last_revisit_time`),
  KEY `t_seller_student_new_admin_revisiterid_index` (`admin_revisiterid`),
  KEY `t_seller_student_new_tmk_assign_time_index` (`tmk_assign_time`),
  KEY `t_seller_student_new_tmk_join_time_index` (`tmk_join_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
