<?php
namespace App\Models\Zgen;
class z_t_student_init_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_student_init_info";


	/*int(11) */
	const C_userid='userid';

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

	/*varchar(100) */
	const C_order_info='order_info';

	/*varchar(10) */
	const C_teacher='teacher';

	/*varchar(20) */
	const C_teacher_info='teacher_info';

	/*varchar(1500) */
	const C_test_lesson_info='test_lesson_info';

	/*varchar(255) */
	const C_mail_addr='mail_addr';

	/*int(11) */
	const C_has_fapiao='has_fapiao';

	/*varchar(125) */
	const C_fapai_title='fapai_title';

	/*varchar(255) */
	const C_lesson_plan='lesson_plan';

	/*varchar(255) */
	const C_parent_other_require='parent_other_require';

	/*int(11) */
	const C_except_lesson_count='except_lesson_count';

	/*int(11) */
	const C_week_lesson_num='week_lesson_num';
	function get_real_name($userid ){
		return $this->field_get_value( $userid , self::C_real_name );
	}
	function get_gender($userid ){
		return $this->field_get_value( $userid , self::C_gender );
	}
	function get_grade($userid ){
		return $this->field_get_value( $userid , self::C_grade );
	}
	function get_birth($userid ){
		return $this->field_get_value( $userid , self::C_birth );
	}
	function get_school($userid ){
		return $this->field_get_value( $userid , self::C_school );
	}
	function get_xingetedian($userid ){
		return $this->field_get_value( $userid , self::C_xingetedian );
	}
	function get_aihao($userid ){
		return $this->field_get_value( $userid , self::C_aihao );
	}
	function get_yeyuanpai($userid ){
		return $this->field_get_value( $userid , self::C_yeyuanpai );
	}
	function get_parent_real_name($userid ){
		return $this->field_get_value( $userid , self::C_parent_real_name );
	}
	function get_parent_email($userid ){
		return $this->field_get_value( $userid , self::C_parent_email );
	}
	function get_relation_ship($userid ){
		return $this->field_get_value( $userid , self::C_relation_ship );
	}
	function get_phone($userid ){
		return $this->field_get_value( $userid , self::C_phone );
	}
	function get_call_time($userid ){
		return $this->field_get_value( $userid , self::C_call_time );
	}
	function get_addr($userid ){
		return $this->field_get_value( $userid , self::C_addr );
	}
	function get_subject_yingyu($userid ){
		return $this->field_get_value( $userid , self::C_subject_yingyu );
	}
	function get_subject_yuwen($userid ){
		return $this->field_get_value( $userid , self::C_subject_yuwen );
	}
	function get_subject_shuxue($userid ){
		return $this->field_get_value( $userid , self::C_subject_shuxue );
	}
	function get_subject_wuli($userid ){
		return $this->field_get_value( $userid , self::C_subject_wuli );
	}
	function get_subject_huaxue($userid ){
		return $this->field_get_value( $userid , self::C_subject_huaxue );
	}
	function get_class_top($userid ){
		return $this->field_get_value( $userid , self::C_class_top );
	}
	function get_grade_top($userid ){
		return $this->field_get_value( $userid , self::C_grade_top );
	}
	function get_subject_info($userid ){
		return $this->field_get_value( $userid , self::C_subject_info );
	}
	function get_order_info($userid ){
		return $this->field_get_value( $userid , self::C_order_info );
	}
	function get_teacher($userid ){
		return $this->field_get_value( $userid , self::C_teacher );
	}
	function get_teacher_info($userid ){
		return $this->field_get_value( $userid , self::C_teacher_info );
	}
	function get_test_lesson_info($userid ){
		return $this->field_get_value( $userid , self::C_test_lesson_info );
	}
	function get_mail_addr($userid ){
		return $this->field_get_value( $userid , self::C_mail_addr );
	}
	function get_has_fapiao($userid ){
		return $this->field_get_value( $userid , self::C_has_fapiao );
	}
	function get_fapai_title($userid ){
		return $this->field_get_value( $userid , self::C_fapai_title );
	}
	function get_lesson_plan($userid ){
		return $this->field_get_value( $userid , self::C_lesson_plan );
	}
	function get_parent_other_require($userid ){
		return $this->field_get_value( $userid , self::C_parent_other_require );
	}
	function get_except_lesson_count($userid ){
		return $this->field_get_value( $userid , self::C_except_lesson_count );
	}
	function get_week_lesson_num($userid ){
		return $this->field_get_value( $userid , self::C_week_lesson_num );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="userid";
        $this->field_table_name="db_weiyi.t_student_init_info";
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
  CREATE TABLE `t_student_init_info` (
  `userid` int(11) NOT NULL,
  `real_name` varchar(20) COLLATE latin1_bin NOT NULL,
  `gender` int(11) NOT NULL,
  `grade` int(11) NOT NULL,
  `birth` int(11) NOT NULL,
  `school` varchar(30) COLLATE latin1_bin NOT NULL,
  `xingetedian` varchar(255) COLLATE latin1_bin NOT NULL,
  `aihao` varchar(255) COLLATE latin1_bin NOT NULL,
  `yeyuanpai` varchar(255) COLLATE latin1_bin NOT NULL,
  `parent_real_name` varchar(20) COLLATE latin1_bin NOT NULL,
  `parent_email` varchar(64) COLLATE latin1_bin NOT NULL,
  `relation_ship` int(11) NOT NULL,
  `phone` varchar(20) COLLATE latin1_bin NOT NULL,
  `call_time` int(11) NOT NULL,
  `addr` varchar(255) COLLATE latin1_bin NOT NULL,
  `subject_yingyu` varchar(50) COLLATE latin1_bin NOT NULL,
  `subject_yuwen` varchar(50) COLLATE latin1_bin NOT NULL,
  `subject_shuxue` varchar(50) COLLATE latin1_bin NOT NULL,
  `subject_wuli` varchar(50) COLLATE latin1_bin NOT NULL,
  `subject_huaxue` varchar(50) COLLATE latin1_bin NOT NULL,
  `class_top` varchar(50) COLLATE latin1_bin NOT NULL,
  `grade_top` varchar(50) COLLATE latin1_bin NOT NULL,
  `subject_info` varchar(500) COLLATE latin1_bin NOT NULL,
  `order_info` varchar(100) COLLATE latin1_bin NOT NULL,
  `teacher` varchar(10) COLLATE latin1_bin NOT NULL,
  `teacher_info` varchar(20) COLLATE latin1_bin NOT NULL,
  `test_lesson_info` varchar(1500) COLLATE latin1_bin DEFAULT NULL,
  `mail_addr` varchar(255) COLLATE latin1_bin NOT NULL,
  `has_fapiao` int(11) NOT NULL,
  `fapai_title` varchar(125) COLLATE latin1_bin NOT NULL,
  `lesson_plan` varchar(255) COLLATE latin1_bin NOT NULL,
  `parent_other_require` varchar(255) COLLATE latin1_bin NOT NULL,
  `except_lesson_count` int(11) NOT NULL COMMENT '每次课时',
  `week_lesson_num` int(11) NOT NULL COMMENT '每周课次',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
