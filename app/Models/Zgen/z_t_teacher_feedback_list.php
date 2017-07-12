<?php
namespace App\Models\Zgen;
class z_t_teacher_feedback_list  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_teacher_feedback_list";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_teacherid='teacherid';

	/*int(11) */
	const C_lessonid='lessonid';

	/*int(11) */
	const C_feedback_type='feedback_type';

	/*int(11) */
	const C_status='status';

	/*int(11) */
	const C_lesson_count='lesson_count';

	/*varchar(2000) */
	const C_tea_reason='tea_reason';

	/*varchar(2000) */
	const C_back_reason='back_reason';

	/*int(11) */
	const C_add_time='add_time';

	/*int(11) */
	const C_check_time='check_time';

	/*varchar(50) */
	const C_sys_operator='sys_operator';
	function get_id($teacherid ){
		return $this->field_get_value( $teacherid , self::C_id );
	}
	function get_lessonid($teacherid ){
		return $this->field_get_value( $teacherid , self::C_lessonid );
	}
	function get_feedback_type($teacherid ){
		return $this->field_get_value( $teacherid , self::C_feedback_type );
	}
	function get_status($teacherid ){
		return $this->field_get_value( $teacherid , self::C_status );
	}
	function get_lesson_count($teacherid ){
		return $this->field_get_value( $teacherid , self::C_lesson_count );
	}
	function get_tea_reason($teacherid ){
		return $this->field_get_value( $teacherid , self::C_tea_reason );
	}
	function get_back_reason($teacherid ){
		return $this->field_get_value( $teacherid , self::C_back_reason );
	}
	function get_add_time($teacherid ){
		return $this->field_get_value( $teacherid , self::C_add_time );
	}
	function get_check_time($teacherid ){
		return $this->field_get_value( $teacherid , self::C_check_time );
	}
	function get_sys_operator($teacherid ){
		return $this->field_get_value( $teacherid , self::C_sys_operator );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="teacherid";
        $this->field_table_name="db_weiyi.t_teacher_feedback_list";
  }
    public function field_get_list( $teacherid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $teacherid, $set_field_arr) {
        return parent::field_update_list( $teacherid, $set_field_arr);
    }


    public function field_get_value(  $teacherid, $field_name ) {
        return parent::field_get_value( $teacherid, $field_name);
    }

    public function row_delete(  $teacherid) {
        return parent::row_delete( $teacherid);
    }

}

/*
  CREATE TABLE `t_teacher_feedback_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacherid` int(11) NOT NULL,
  `lessonid` int(11) NOT NULL,
  `feedback_type` int(11) NOT NULL COMMENT '反馈类型',
  `status` int(11) NOT NULL COMMENT '反馈状态 0 未处理 1 反馈通过 2 反馈不通过',
  `lesson_count` int(11) NOT NULL COMMENT '反馈课时问题的补录课时',
  `tea_reason` varchar(2000) COLLATE latin1_bin NOT NULL COMMENT '老师提交的反馈问题原因',
  `back_reason` varchar(2000) COLLATE latin1_bin NOT NULL COMMENT '助教或系统驳回反馈的原因',
  `add_time` int(11) NOT NULL COMMENT '反馈提交时间',
  `check_time` int(11) NOT NULL COMMENT '反馈处理时间',
  `sys_operator` varchar(50) COLLATE latin1_bin NOT NULL COMMENT '反馈操作人员',
  PRIMARY KEY (`id`),
  KEY `t_teacher_feedback_list_teacherid_index` (`teacherid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
