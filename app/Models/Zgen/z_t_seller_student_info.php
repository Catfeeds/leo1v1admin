<?php
namespace App\Models\Zgen;
class z_t_seller_student_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_seller_student_info";


	/*int(10) unsigned */
	const C_id='id';

	/*varchar(20) */
	const C_phone='phone';

	/*int(11) */
	const C_userid='userid';

	/*int(11) */
	const C_add_time='add_time';

	/*varchar(255) */
	const C_origin='origin';

	/*int(11) */
	const C_admin_assignerid='admin_assignerid';

	/*int(11) */
	const C_admin_assign_time='admin_assign_time';

	/*int(11) */
	const C_admin_revisiterid='admin_revisiterid';

	/*int(11) */
	const C_grade='grade';

	/*int(11) */
	const C_subject='subject';

	/*varchar(255) */
	const C_nick='nick';

	/*int(11) */
	const C_status='status';

	/*int(11) */
	const C_revisit_count='revisit_count';

	/*int(11) */
	const C_last_revisit_time='last_revisit_time';

	/*varchar(255) */
	const C_last_revisit_msg='last_revisit_msg';

	/*int(11) */
	const C_has_pad='has_pad';

	/*varchar(255) */
	const C_phone_location='phone_location';

	/*int(11) */
	const C_next_revisit_time='next_revisit_time';

	/*varchar(255) */
	const C_user_desc='user_desc';

	/*int(11) */
	const C_trial_type='trial_type';

	/*varchar(20) */
	const C_qq='qq';

	/*int(11) */
	const C_st_application_time='st_application_time';

	/*varchar(64) */
	const C_st_application_nick='st_application_nick';

	/*varchar(64) */
	const C_st_from_school='st_from_school';

	/*varchar(64) */
	const C_st_demand='st_demand';

	/*varchar(64) */
	const C_st_test_paper='st_test_paper';

	/*int(11) */
	const C_st_class_time='st_class_time';

	/*int(11) */
	const C_st_arrange_lessonid='st_arrange_lessonid';

	/*int(11) */
	const C_money_all='money_all';

	/*int(11) */
	const C_from_type='from_type';

	/*int(11) */
	const C_first_money='first_money';

	/*int(11) */
	const C_first_revisite_time='first_revisite_time';

	/*int(11) */
	const C_assigned_teacherid='assigned_teacherid';

	/*int(11) */
	const C_test_lesson_bind_adminid='test_lesson_bind_adminid';

	/*int(11) */
	const C_notify_lesson_day1='notify_lesson_day1';

	/*int(11) */
	const C_notify_lesson_day2='notify_lesson_day2';

	/*varchar(255) */
	const C_stu_score_info='stu_score_info';

	/*varchar(255) */
	const C_stu_character_info='stu_character_info';

	/*varchar(255) */
	const C_stu_request_test_lesson_time_info='stu_request_test_lesson_time_info';

	/*varchar(255) */
	const C_stu_request_lesson_time_info='stu_request_lesson_time_info';

	/*int(11) */
	const C_stu_test_lesson_level='stu_test_lesson_level';

	/*int(11) */
	const C_stu_test_ipad_flag='stu_test_ipad_flag';

	/*varchar(255) */
	const C_stu_lesson_content='stu_lesson_content';

	/*varchar(255) */
	const C_stu_lesson_status='stu_lesson_status';

	/*varchar(255) */
	const C_stu_study_status='stu_study_status';

	/*varchar(255) */
	const C_stu_advantages='stu_advantages';

	/*varchar(255) */
	const C_stu_disadvantages='stu_disadvantages';

	/*varchar(255) */
	const C_stu_lesson_plan='stu_lesson_plan';

	/*varchar(255) */
	const C_stu_teaching_direction='stu_teaching_direction';

	/*varchar(255) */
	const C_stu_textbook_info='stu_textbook_info';

	/*varchar(255) */
	const C_stu_teaching_aim='stu_teaching_aim';

	/*int(11) */
	const C_stu_lesson_count='stu_lesson_count';

	/*blob */
	const C_stu_advice='stu_advice';

	/*int(11) */
	const C_cancel_lesson_start='cancel_lesson_start';

	/*int(11) */
	const C_cancel_flag='cancel_flag';

	/*int(11) */
	const C_test_lesson_parentid='test_lesson_parentid';

	/*int(11) */
	const C_cancel_adminid='cancel_adminid';

	/*int(11) */
	const C_cancel_time='cancel_time';

	/*int(11) */
	const C_cancel_teacherid='cancel_teacherid';

	/*varchar(255) */
	const C_cancel_reason='cancel_reason';

	/*int(11) */
	const C_ass_adminid='ass_adminid';

	/*int(11) */
	const C_tq_called_flag='tq_called_flag';

	/*int(11) */
	const C_seller_resource_type='seller_resource_type';

	/*int(11) */
	const C_sub_assign_adminid='sub_assign_adminid';

	/*int(11) */
	const C_sub_assign_time='sub_assign_time';

	/*int(11) */
	const C_tea_download_paper_time='tea_download_paper_time';
	function get_id($phone ){
		return $this->field_get_value( $phone , self::C_id );
	}
	function get_userid($phone ){
		return $this->field_get_value( $phone , self::C_userid );
	}
	function get_add_time($phone ){
		return $this->field_get_value( $phone , self::C_add_time );
	}
	function get_origin($phone ){
		return $this->field_get_value( $phone , self::C_origin );
	}
	function get_admin_assignerid($phone ){
		return $this->field_get_value( $phone , self::C_admin_assignerid );
	}
	function get_admin_assign_time($phone ){
		return $this->field_get_value( $phone , self::C_admin_assign_time );
	}
	function get_admin_revisiterid($phone ){
		return $this->field_get_value( $phone , self::C_admin_revisiterid );
	}
	function get_grade($phone ){
		return $this->field_get_value( $phone , self::C_grade );
	}
	function get_subject($phone ){
		return $this->field_get_value( $phone , self::C_subject );
	}
	function get_nick($phone ){
		return $this->field_get_value( $phone , self::C_nick );
	}
	function get_status($phone ){
		return $this->field_get_value( $phone , self::C_status );
	}
	function get_revisit_count($phone ){
		return $this->field_get_value( $phone , self::C_revisit_count );
	}
	function get_last_revisit_time($phone ){
		return $this->field_get_value( $phone , self::C_last_revisit_time );
	}
	function get_last_revisit_msg($phone ){
		return $this->field_get_value( $phone , self::C_last_revisit_msg );
	}
	function get_has_pad($phone ){
		return $this->field_get_value( $phone , self::C_has_pad );
	}
	function get_phone_location($phone ){
		return $this->field_get_value( $phone , self::C_phone_location );
	}
	function get_next_revisit_time($phone ){
		return $this->field_get_value( $phone , self::C_next_revisit_time );
	}
	function get_user_desc($phone ){
		return $this->field_get_value( $phone , self::C_user_desc );
	}
	function get_trial_type($phone ){
		return $this->field_get_value( $phone , self::C_trial_type );
	}
	function get_qq($phone ){
		return $this->field_get_value( $phone , self::C_qq );
	}
	function get_st_application_time($phone ){
		return $this->field_get_value( $phone , self::C_st_application_time );
	}
	function get_st_application_nick($phone ){
		return $this->field_get_value( $phone , self::C_st_application_nick );
	}
	function get_st_from_school($phone ){
		return $this->field_get_value( $phone , self::C_st_from_school );
	}
	function get_st_demand($phone ){
		return $this->field_get_value( $phone , self::C_st_demand );
	}
	function get_st_test_paper($phone ){
		return $this->field_get_value( $phone , self::C_st_test_paper );
	}
	function get_st_class_time($phone ){
		return $this->field_get_value( $phone , self::C_st_class_time );
	}
	function get_st_arrange_lessonid($phone ){
		return $this->field_get_value( $phone , self::C_st_arrange_lessonid );
	}
	function get_money_all($phone ){
		return $this->field_get_value( $phone , self::C_money_all );
	}
	function get_from_type($phone ){
		return $this->field_get_value( $phone , self::C_from_type );
	}
	function get_first_money($phone ){
		return $this->field_get_value( $phone , self::C_first_money );
	}
	function get_first_revisite_time($phone ){
		return $this->field_get_value( $phone , self::C_first_revisite_time );
	}
	function get_assigned_teacherid($phone ){
		return $this->field_get_value( $phone , self::C_assigned_teacherid );
	}
	function get_test_lesson_bind_adminid($phone ){
		return $this->field_get_value( $phone , self::C_test_lesson_bind_adminid );
	}
	function get_notify_lesson_day1($phone ){
		return $this->field_get_value( $phone , self::C_notify_lesson_day1 );
	}
	function get_notify_lesson_day2($phone ){
		return $this->field_get_value( $phone , self::C_notify_lesson_day2 );
	}
	function get_stu_score_info($phone ){
		return $this->field_get_value( $phone , self::C_stu_score_info );
	}
	function get_stu_character_info($phone ){
		return $this->field_get_value( $phone , self::C_stu_character_info );
	}
	function get_stu_request_test_lesson_time_info($phone ){
		return $this->field_get_value( $phone , self::C_stu_request_test_lesson_time_info );
	}
	function get_stu_request_lesson_time_info($phone ){
		return $this->field_get_value( $phone , self::C_stu_request_lesson_time_info );
	}
	function get_stu_test_lesson_level($phone ){
		return $this->field_get_value( $phone , self::C_stu_test_lesson_level );
	}
	function get_stu_test_ipad_flag($phone ){
		return $this->field_get_value( $phone , self::C_stu_test_ipad_flag );
	}
	function get_stu_lesson_content($phone ){
		return $this->field_get_value( $phone , self::C_stu_lesson_content );
	}
	function get_stu_lesson_status($phone ){
		return $this->field_get_value( $phone , self::C_stu_lesson_status );
	}
	function get_stu_study_status($phone ){
		return $this->field_get_value( $phone , self::C_stu_study_status );
	}
	function get_stu_advantages($phone ){
		return $this->field_get_value( $phone , self::C_stu_advantages );
	}
	function get_stu_disadvantages($phone ){
		return $this->field_get_value( $phone , self::C_stu_disadvantages );
	}
	function get_stu_lesson_plan($phone ){
		return $this->field_get_value( $phone , self::C_stu_lesson_plan );
	}
	function get_stu_teaching_direction($phone ){
		return $this->field_get_value( $phone , self::C_stu_teaching_direction );
	}
	function get_stu_textbook_info($phone ){
		return $this->field_get_value( $phone , self::C_stu_textbook_info );
	}
	function get_stu_teaching_aim($phone ){
		return $this->field_get_value( $phone , self::C_stu_teaching_aim );
	}
	function get_stu_lesson_count($phone ){
		return $this->field_get_value( $phone , self::C_stu_lesson_count );
	}
	function get_stu_advice($phone ){
		return $this->field_get_value( $phone , self::C_stu_advice );
	}
	function get_cancel_lesson_start($phone ){
		return $this->field_get_value( $phone , self::C_cancel_lesson_start );
	}
	function get_cancel_flag($phone ){
		return $this->field_get_value( $phone , self::C_cancel_flag );
	}
	function get_test_lesson_parentid($phone ){
		return $this->field_get_value( $phone , self::C_test_lesson_parentid );
	}
	function get_cancel_adminid($phone ){
		return $this->field_get_value( $phone , self::C_cancel_adminid );
	}
	function get_cancel_time($phone ){
		return $this->field_get_value( $phone , self::C_cancel_time );
	}
	function get_cancel_teacherid($phone ){
		return $this->field_get_value( $phone , self::C_cancel_teacherid );
	}
	function get_cancel_reason($phone ){
		return $this->field_get_value( $phone , self::C_cancel_reason );
	}
	function get_ass_adminid($phone ){
		return $this->field_get_value( $phone , self::C_ass_adminid );
	}
	function get_tq_called_flag($phone ){
		return $this->field_get_value( $phone , self::C_tq_called_flag );
	}
	function get_seller_resource_type($phone ){
		return $this->field_get_value( $phone , self::C_seller_resource_type );
	}
	function get_sub_assign_adminid($phone ){
		return $this->field_get_value( $phone , self::C_sub_assign_adminid );
	}
	function get_sub_assign_time($phone ){
		return $this->field_get_value( $phone , self::C_sub_assign_time );
	}
	function get_tea_download_paper_time($phone ){
		return $this->field_get_value( $phone , self::C_tea_download_paper_time );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="phone";
        $this->field_table_name="db_weiyi.t_seller_student_info";
  }
    public function field_get_list( $phone, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $phone, $set_field_arr) {
        return parent::field_update_list( $phone, $set_field_arr);
    }


    public function field_get_value(  $phone, $field_name ) {
        return parent::field_get_value( $phone, $field_name);
    }

    public function row_delete(  $phone) {
        return parent::row_delete( $phone);
    }

}

/*
  CREATE TABLE `t_seller_student_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '手机号',
  `userid` int(11) NOT NULL COMMENT '注册才有',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `origin` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '渠道',
  `admin_assignerid` int(11) NOT NULL COMMENT '分配者id',
  `admin_assign_time` int(11) NOT NULL COMMENT '分配时间',
  `admin_revisiterid` int(11) NOT NULL COMMENT '分配给谁',
  `grade` int(11) NOT NULL COMMENT '年级',
  `subject` int(11) NOT NULL COMMENT '科目',
  `nick` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '客户姓名',
  `status` int(11) NOT NULL COMMENT '无效，意向。',
  `revisit_count` int(11) NOT NULL COMMENT '回访的总次数',
  `last_revisit_time` int(11) NOT NULL COMMENT '最后一次回访的时间',
  `last_revisit_msg` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '最后一次回访的内容',
  `has_pad` int(11) NOT NULL COMMENT '0: 1 2',
  `phone_location` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '手机归属地',
  `next_revisit_time` int(11) NOT NULL COMMENT ' 	下次回访时间',
  `user_desc` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '备注',
  `trial_type` int(11) NOT NULL,
  `qq` varchar(20) COLLATE latin1_bin NOT NULL,
  `st_application_time` int(11) NOT NULL COMMENT '申请时间',
  `st_application_nick` varchar(64) COLLATE latin1_bin NOT NULL COMMENT '申请人',
  `st_from_school` varchar(64) COLLATE latin1_bin NOT NULL COMMENT '在读学校',
  `st_demand` varchar(64) COLLATE latin1_bin NOT NULL COMMENT '试听需求',
  `st_test_paper` varchar(64) COLLATE latin1_bin NOT NULL COMMENT '试卷',
  `st_class_time` int(11) NOT NULL COMMENT '期望上课时间',
  `st_arrange_lessonid` int(11) DEFAULT NULL,
  `money_all` int(11) NOT NULL COMMENT '所有收入',
  `from_type` int(11) NOT NULL,
  `first_money` int(11) NOT NULL COMMENT '首次金额',
  `first_revisite_time` int(11) NOT NULL COMMENT '首次回访时间',
  `assigned_teacherid` int(11) NOT NULL COMMENT '派单抢到者id',
  `test_lesson_bind_adminid` int(11) NOT NULL COMMENT '试听排课分配人',
  `notify_lesson_day1` int(11) NOT NULL COMMENT '第一次通知上课时间',
  `notify_lesson_day2` int(11) NOT NULL COMMENT '第二次通知上课时间',
  `stu_score_info` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '成绩情况 ',
  `stu_character_info` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '性格特点 ',
  `stu_request_test_lesson_time_info` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '试听时间段 ',
  `stu_request_lesson_time_info` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '正式上课后时间段  ',
  `stu_test_lesson_level` int(11) NOT NULL COMMENT '试听内容：初级，中级，高级   ',
  `stu_test_ipad_flag` int(11) NOT NULL COMMENT '销售是否已经连线测试 ',
  `stu_lesson_content` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '本节课内容       ',
  `stu_lesson_status` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '学生课堂状态     ',
  `stu_study_status` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '学生吸收情况     ',
  `stu_advantages` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '学生优点（不要过分）',
  `stu_disadvantages` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '学生缺点    ',
  `stu_lesson_plan` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '培训计划（简述）',
  `stu_teaching_direction` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '教学方向    ',
  `stu_textbook_info` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '教材及内容  ',
  `stu_teaching_aim` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '教学目标    ',
  `stu_lesson_count` int(11) NOT NULL COMMENT '大致推荐课时数   ',
  `stu_advice` blob,
  `cancel_lesson_start` int(11) NOT NULL COMMENT '取消的课程上课时间',
  `cancel_flag` int(11) NOT NULL COMMENT '取消标识,0:无,1:取消,2:换时间',
  `test_lesson_parentid` int(11) NOT NULL COMMENT '这节试听课的上级节点是哪个',
  `cancel_adminid` int(11) NOT NULL COMMENT '取消人',
  `cancel_time` int(11) NOT NULL COMMENT '取消时间',
  `cancel_teacherid` int(11) NOT NULL COMMENT '取消老师',
  `cancel_reason` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '取消原因',
  `ass_adminid` int(11) NOT NULL COMMENT '转介绍 助教 adminid',
  `tq_called_flag` int(11) NOT NULL COMMENT 'tq呼叫标志:0,1,2',
  `seller_resource_type` int(11) NOT NULL COMMENT '资源库分类',
  `sub_assign_adminid` int(11) DEFAULT NULL,
  `sub_assign_time` int(11) DEFAULT NULL,
  `tea_download_paper_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `t_seller_student_info_phone_unique` (`phone`),
  UNIQUE KEY `st_arrange_lessonid` (`st_arrange_lessonid`),
  KEY `t_seller_student_info_add_time_index` (`add_time`),
  KEY `t_seller_student_info_admin_assignerid_index` (`admin_assignerid`),
  KEY `t_seller_student_info_admin_revisiterid_admin_assign_time_index` (`admin_revisiterid`,`admin_assign_time`),
  KEY `admin_revisiterid__next_revisit_time` (`admin_revisiterid`,`next_revisit_time`),
  KEY `t_seller_student_info_first_revisite_time_index` (`first_revisite_time`),
  KEY `t_seller_student_info_cancel_time_index` (`cancel_time`),
  KEY `t_seller_student_info_cancel_lesson_start_index` (`cancel_lesson_start`)
) ENGINE=InnoDB AUTO_INCREMENT=8129 DEFAULT CHARSET=latin1 COLLATE=latin1_bin COMMENT='销售维护的用户消息'
 */
