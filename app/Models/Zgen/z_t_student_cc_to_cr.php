<?php
namespace App\Models\Zgen;
class z_t_student_cc_to_cr  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_student_cc_to_cr";


	/*int(10) unsigned */
	const C_id='id';

	/*int(11) */
	const C_orderid='orderid';

	/*int(11) */
	const C_cc_id='cc_id';

	/*int(11) */
	const C_ass_id='ass_id';

	/*int(11) */
	const C_post_time='post_time';

	/*int(11) */
	const C_reject_flag='reject_flag';

	/*int(11) */
	const C_reject_time='reject_time';

	/*varchar(1024) */
	const C_reject_info='reject_info';

	/*varchar(20) */
	const C_real_name='real_name';

	/*int(11) */
	const C_gender='gender';

	/*int(11) */
	const C_grade='grade';

	/*int(11) */
	const C_birth='birth';

	/*varchar(30) */
	const C_school='school';

	/*varchar(255) */
	const C_xingetedian='xingetedian';

	/*varchar(255) */
	const C_aihao='aihao';

	/*varchar(255) */
	const C_yeyuanpai='yeyuanpai';

	/*varchar(20) */
	const C_parent_real_name='parent_real_name';

	/*varchar(64) */
	const C_parent_email='parent_email';

	/*int(11) */
	const C_relation_ship='relation_ship';

	/*varchar(20) */
	const C_phone='phone';

	/*int(11) */
	const C_call_time='call_time';

	/*varchar(255) */
	const C_addr='addr';

	/*varchar(50) */
	const C_subject_yingyu='subject_yingyu';

	/*varchar(50) */
	const C_subject_yuwen='subject_yuwen';

	/*varchar(50) */
	const C_subject_shuxue='subject_shuxue';

	/*varchar(50) */
	const C_subject_wuli='subject_wuli';

	/*varchar(50) */
	const C_subject_huaxue='subject_huaxue';

	/*varchar(50) */
	const C_class_top='class_top';

	/*varchar(50) */
	const C_grade_top='grade_top';

	/*varchar(500) */
	const C_subject_info='subject_info';

	/*varchar(1000) */
	const C_order_info='order_info';

	/*varchar(64) */
	const C_teacher='teacher';

	/*varchar(200) */
	const C_teacher_info='teacher_info';

	/*varchar(1500) */
	const C_test_lesson_info='test_lesson_info';

	/*varchar(255) */
	const C_mail_addr='mail_addr';

	/*int(11) */
	const C_has_fapiao='has_fapiao';

	/*varchar(125) */
	const C_fapai_title='fapai_title';

	/*varchar(1500) */
	const C_lesson_plan='lesson_plan';

	/*varchar(1500) */
	const C_parent_other_require='parent_other_require';

	/*int(11) */
	const C_except_lesson_count='except_lesson_count';

	/*int(11) */
	const C_week_lesson_num='week_lesson_num';

	/*varchar(255) */
	const C_common_lesson_time='common_lesson_time';

	/*int(11) */
	const C_first_lesson_time='first_lesson_time';
	function get_orderid($id ){
		return $this->field_get_value( $id , self::C_orderid );
	}
	function get_cc_id($id ){
		return $this->field_get_value( $id , self::C_cc_id );
	}
	function get_ass_id($id ){
		return $this->field_get_value( $id , self::C_ass_id );
	}
	function get_post_time($id ){
		return $this->field_get_value( $id , self::C_post_time );
	}
	function get_reject_flag($id ){
		return $this->field_get_value( $id , self::C_reject_flag );
	}
	function get_reject_time($id ){
		return $this->field_get_value( $id , self::C_reject_time );
	}
	function get_reject_info($id ){
		return $this->field_get_value( $id , self::C_reject_info );
	}
	function get_real_name($id ){
		return $this->field_get_value( $id , self::C_real_name );
	}
	function get_gender($id ){
		return $this->field_get_value( $id , self::C_gender );
	}
	function get_grade($id ){
		return $this->field_get_value( $id , self::C_grade );
	}
	function get_birth($id ){
		return $this->field_get_value( $id , self::C_birth );
	}
	function get_school($id ){
		return $this->field_get_value( $id , self::C_school );
	}
	function get_xingetedian($id ){
		return $this->field_get_value( $id , self::C_xingetedian );
	}
	function get_aihao($id ){
		return $this->field_get_value( $id , self::C_aihao );
	}
	function get_yeyuanpai($id ){
		return $this->field_get_value( $id , self::C_yeyuanpai );
	}
	function get_parent_real_name($id ){
		return $this->field_get_value( $id , self::C_parent_real_name );
	}
	function get_parent_email($id ){
		return $this->field_get_value( $id , self::C_parent_email );
	}
	function get_relation_ship($id ){
		return $this->field_get_value( $id , self::C_relation_ship );
	}
	function get_phone($id ){
		return $this->field_get_value( $id , self::C_phone );
	}
	function get_call_time($id ){
		return $this->field_get_value( $id , self::C_call_time );
	}
	function get_addr($id ){
		return $this->field_get_value( $id , self::C_addr );
	}
	function get_subject_yingyu($id ){
		return $this->field_get_value( $id , self::C_subject_yingyu );
	}
	function get_subject_yuwen($id ){
		return $this->field_get_value( $id , self::C_subject_yuwen );
	}
	function get_subject_shuxue($id ){
		return $this->field_get_value( $id , self::C_subject_shuxue );
	}
	function get_subject_wuli($id ){
		return $this->field_get_value( $id , self::C_subject_wuli );
	}
	function get_subject_huaxue($id ){
		return $this->field_get_value( $id , self::C_subject_huaxue );
	}
	function get_class_top($id ){
		return $this->field_get_value( $id , self::C_class_top );
	}
	function get_grade_top($id ){
		return $this->field_get_value( $id , self::C_grade_top );
	}
	function get_subject_info($id ){
		return $this->field_get_value( $id , self::C_subject_info );
	}
	function get_order_info($id ){
		return $this->field_get_value( $id , self::C_order_info );
	}
	function get_teacher($id ){
		return $this->field_get_value( $id , self::C_teacher );
	}
	function get_teacher_info($id ){
		return $this->field_get_value( $id , self::C_teacher_info );
	}
	function get_test_lesson_info($id ){
		return $this->field_get_value( $id , self::C_test_lesson_info );
	}
	function get_mail_addr($id ){
		return $this->field_get_value( $id , self::C_mail_addr );
	}
	function get_has_fapiao($id ){
		return $this->field_get_value( $id , self::C_has_fapiao );
	}
	function get_fapai_title($id ){
		return $this->field_get_value( $id , self::C_fapai_title );
	}
	function get_lesson_plan($id ){
		return $this->field_get_value( $id , self::C_lesson_plan );
	}
	function get_parent_other_require($id ){
		return $this->field_get_value( $id , self::C_parent_other_require );
	}
	function get_except_lesson_count($id ){
		return $this->field_get_value( $id , self::C_except_lesson_count );
	}
	function get_week_lesson_num($id ){
		return $this->field_get_value( $id , self::C_week_lesson_num );
	}
	function get_common_lesson_time($id ){
		return $this->field_get_value( $id , self::C_common_lesson_time );
	}
	function get_first_lesson_time($id ){
		return $this->field_get_value( $id , self::C_first_lesson_time );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_student_cc_to_cr";
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
  CREATE TABLE `t_student_cc_to_cr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `cc_id` int(11) NOT NULL COMMENT '申请人id',
  `ass_id` int(11) NOT NULL COMMENT '助教id',
  `post_time` int(11) NOT NULL COMMENT '提交时间',
  `reject_flag` int(11) NOT NULL COMMENT '是否驳回',
  `reject_time` int(11) NOT NULL,
  `reject_info` varchar(1024) COLLATE latin1_bin NOT NULL COMMENT '驳回备注信息',
  `real_name` varchar(20) COLLATE latin1_bin DEFAULT NULL,
  `gender` int(11) NOT NULL COMMENT '性别',
  `grade` int(11) NOT NULL COMMENT '年纪',
  `birth` int(11) NOT NULL COMMENT '生日',
  `school` varchar(30) COLLATE latin1_bin NOT NULL COMMENT '学校',
  `xingetedian` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '性格特点',
  `aihao` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '爱好',
  `yeyuanpai` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '业余安排',
  `parent_real_name` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '家长真实姓名',
  `parent_email` varchar(64) COLLATE latin1_bin NOT NULL COMMENT '家长邮箱',
  `relation_ship` int(11) NOT NULL COMMENT '关系',
  `phone` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '家长电话',
  `call_time` int(11) NOT NULL COMMENT '联系时间',
  `addr` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '家庭住址',
  `subject_yingyu` varchar(50) COLLATE latin1_bin NOT NULL COMMENT '英语成绩',
  `subject_yuwen` varchar(50) COLLATE latin1_bin NOT NULL COMMENT '语文成绩',
  `subject_shuxue` varchar(50) COLLATE latin1_bin NOT NULL,
  `subject_wuli` varchar(50) COLLATE latin1_bin NOT NULL COMMENT '物理成绩',
  `subject_huaxue` varchar(50) COLLATE latin1_bin NOT NULL COMMENT '化学成绩',
  `class_top` varchar(50) COLLATE latin1_bin NOT NULL COMMENT '班级排名',
  `grade_top` varchar(50) COLLATE latin1_bin NOT NULL COMMENT '年纪排名',
  `subject_info` varchar(500) COLLATE latin1_bin NOT NULL COMMENT '学科情况',
  `order_info` varchar(1000) COLLATE latin1_bin NOT NULL COMMENT '订单情况',
  `teacher` varchar(64) COLLATE latin1_bin NOT NULL COMMENT '辅导老师',
  `teacher_info` varchar(200) COLLATE latin1_bin NOT NULL COMMENT '老师包装信息',
  `test_lesson_info` varchar(1500) COLLATE latin1_bin NOT NULL COMMENT '试听反馈',
  `mail_addr` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '礼包地址',
  `has_fapiao` int(11) NOT NULL COMMENT '开发票',
  `fapai_title` varchar(125) COLLATE latin1_bin NOT NULL COMMENT '发票抬头',
  `lesson_plan` varchar(1500) COLLATE latin1_bin NOT NULL COMMENT '课程安排',
  `parent_other_require` varchar(1500) COLLATE latin1_bin NOT NULL COMMENT '家长需求',
  `except_lesson_count` int(11) NOT NULL COMMENT '每次课时',
  `week_lesson_num` int(11) NOT NULL COMMENT '每周课时',
  `common_lesson_time` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '常规课时间',
  `first_lesson_time` int(11) NOT NULL COMMENT '首次上课时间',
  PRIMARY KEY (`id`),
  KEY `db_weiyi_t_student_cc_to_cr_cc_id_index` (`cc_id`),
  KEY `db_weiyi_t_student_cc_to_cr_ass_id_index` (`ass_id`),
  KEY `db_weiyi_t_student_cc_to_cr_orderid_index` (`orderid`),
  KEY `db_weiyi_t_student_cc_to_cr_post_time_index` (`post_time`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
