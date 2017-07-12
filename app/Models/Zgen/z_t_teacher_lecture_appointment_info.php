<?php
namespace App\Models\Zgen;
class z_t_teacher_lecture_appointment_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_teacher_lecture_appointment_info";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_answer_begin_time='answer_begin_time';

	/*int(11) */
	const C_answer_end_time='answer_end_time';

	/*int(11) */
	const C_lecture_appointment_status='lecture_appointment_status';

	/*int(11) */
	const C_lecture_appointment_origin='lecture_appointment_origin';

	/*varchar(255) */
	const C_custom='custom';

	/*varchar(255) */
	const C_name='name';

	/*varchar(255) */
	const C_school='school';

	/*varchar(255) */
	const C_grade_ex='grade_ex';

	/*varchar(255) */
	const C_subject_ex='subject_ex';

	/*varchar(255) */
	const C_textbook='textbook';

	/*varchar(255) */
	const C_teacher_type='teacher_type';

	/*varchar(255) */
	const C_reference='reference';

	/*varchar(256) */
	const C_self_introduction_experience='self_introduction_experience';

	/*varchar(255) */
	const C_email='email';

	/*varchar(255) */
	const C_phone='phone';

	/*varchar(20) */
	const C_acc='acc';

	/*int(11) */
	const C_accept_adminid='accept_adminid';

	/*int(11) */
	const C_accept_time='accept_time';

	/*varchar(255) */
	const C_idcard='idcard';

	/*int(11) */
	const C_bankcard='bankcard';

	/*varchar(255) */
	const C_bank_address='bank_address';

	/*varchar(255) */
	const C_bank_account='bank_account';

	/*int(11) */
	const C_grade_start='grade_start';

	/*int(11) */
	const C_grade_end='grade_end';

	/*varchar(255) */
	const C_not_grade='not_grade';

	/*tinyint(4) */
	const C_trans_grade='trans_grade';

	/*int(11) */
	const C_trans_grade_start='trans_grade_start';

	/*int(11) */
	const C_trans_grade_end='trans_grade_end';

	/*varchar(20) */
	const C_qq='qq';

	/*varchar(255) */
	const C_trans_grade_ex='trans_grade_ex';

	/*tinyint(4) */
	const C_trans_subject_ex='trans_subject_ex';
	function get_answer_begin_time($id ){
		return $this->field_get_value( $id , self::C_answer_begin_time );
	}
	function get_answer_end_time($id ){
		return $this->field_get_value( $id , self::C_answer_end_time );
	}
	function get_lecture_appointment_status($id ){
		return $this->field_get_value( $id , self::C_lecture_appointment_status );
	}
	function get_lecture_appointment_origin($id ){
		return $this->field_get_value( $id , self::C_lecture_appointment_origin );
	}
	function get_custom($id ){
		return $this->field_get_value( $id , self::C_custom );
	}
	function get_name($id ){
		return $this->field_get_value( $id , self::C_name );
	}
	function get_school($id ){
		return $this->field_get_value( $id , self::C_school );
	}
	function get_grade_ex($id ){
		return $this->field_get_value( $id , self::C_grade_ex );
	}
	function get_subject_ex($id ){
		return $this->field_get_value( $id , self::C_subject_ex );
	}
	function get_textbook($id ){
		return $this->field_get_value( $id , self::C_textbook );
	}
	function get_teacher_type($id ){
		return $this->field_get_value( $id , self::C_teacher_type );
	}
	function get_reference($id ){
		return $this->field_get_value( $id , self::C_reference );
	}
	function get_self_introduction_experience($id ){
		return $this->field_get_value( $id , self::C_self_introduction_experience );
	}
	function get_email($id ){
		return $this->field_get_value( $id , self::C_email );
	}
	function get_phone($id ){
		return $this->field_get_value( $id , self::C_phone );
	}
	function get_acc($id ){
		return $this->field_get_value( $id , self::C_acc );
	}
	function get_accept_adminid($id ){
		return $this->field_get_value( $id , self::C_accept_adminid );
	}
	function get_accept_time($id ){
		return $this->field_get_value( $id , self::C_accept_time );
	}
	function get_idcard($id ){
		return $this->field_get_value( $id , self::C_idcard );
	}
	function get_bankcard($id ){
		return $this->field_get_value( $id , self::C_bankcard );
	}
	function get_bank_address($id ){
		return $this->field_get_value( $id , self::C_bank_address );
	}
	function get_bank_account($id ){
		return $this->field_get_value( $id , self::C_bank_account );
	}
	function get_grade_start($id ){
		return $this->field_get_value( $id , self::C_grade_start );
	}
	function get_grade_end($id ){
		return $this->field_get_value( $id , self::C_grade_end );
	}
	function get_not_grade($id ){
		return $this->field_get_value( $id , self::C_not_grade );
	}
	function get_trans_grade($id ){
		return $this->field_get_value( $id , self::C_trans_grade );
	}
	function get_trans_grade_start($id ){
		return $this->field_get_value( $id , self::C_trans_grade_start );
	}
	function get_trans_grade_end($id ){
		return $this->field_get_value( $id , self::C_trans_grade_end );
	}
	function get_qq($id ){
		return $this->field_get_value( $id , self::C_qq );
	}
	function get_trans_grade_ex($id ){
		return $this->field_get_value( $id , self::C_trans_grade_ex );
	}
	function get_trans_subject_ex($id ){
		return $this->field_get_value( $id , self::C_trans_subject_ex );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_teacher_lecture_appointment_info";
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
  CREATE TABLE `t_teacher_lecture_appointment_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `answer_begin_time` int(11) NOT NULL,
  `answer_end_time` int(11) NOT NULL,
  `lecture_appointment_status` int(11) NOT NULL,
  `lecture_appointment_origin` int(11) NOT NULL,
  `custom` varchar(255) COLLATE latin1_bin NOT NULL,
  `name` varchar(255) COLLATE latin1_bin NOT NULL,
  `school` varchar(255) COLLATE latin1_bin NOT NULL,
  `grade_ex` varchar(255) COLLATE latin1_bin NOT NULL,
  `subject_ex` varchar(255) COLLATE latin1_bin NOT NULL,
  `textbook` varchar(255) COLLATE latin1_bin NOT NULL,
  `teacher_type` varchar(255) COLLATE latin1_bin NOT NULL,
  `reference` varchar(255) COLLATE latin1_bin NOT NULL,
  `self_introduction_experience` varchar(256) COLLATE latin1_bin NOT NULL,
  `email` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '邮箱',
  `phone` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '联系电话',
  `acc` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '添加人',
  `accept_adminid` int(11) NOT NULL COMMENT '预约试讲系统分配人id',
  `accept_time` int(11) NOT NULL COMMENT '预约试讲系统分配时间',
  `idcard` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '身份证号',
  `bankcard` int(11) NOT NULL COMMENT '老师银行卡号',
  `bank_address` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '开户行及支行',
  `bank_account` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '持卡人姓名',
  `grade_start` int(11) NOT NULL COMMENT '老师擅长年级范围开始',
  `grade_end` int(11) NOT NULL COMMENT '老师擅长年级范围结束',
  `not_grade` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '老师不擅长的年级段',
  `trans_grade` tinyint(4) NOT NULL COMMENT '扩年级标示',
  `trans_grade_start` int(11) NOT NULL COMMENT '扩年级开始',
  `trans_grade_end` int(11) NOT NULL COMMENT '扩年级结束',
  `qq` varchar(20) COLLATE latin1_bin NOT NULL COMMENT 'qq号码',
  `trans_grade_ex` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '所扩学科的所年级',
  `trans_subject_ex` tinyint(4) NOT NULL COMMENT '扩课科目',
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`phone`),
  KEY `grade_limit` (`grade_start`,`grade_end`),
  KEY `reference` (`reference`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
