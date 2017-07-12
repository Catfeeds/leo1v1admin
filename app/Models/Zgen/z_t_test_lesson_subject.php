<?php
namespace App\Models\Zgen;
class z_t_test_lesson_subject  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_test_lesson_subject";


	/*int(11) */
	const C_test_lesson_subject_id='test_lesson_subject_id';

	/*int(11) */
	const C_require_admin_type='require_admin_type';

	/*int(11) */
	const C_require_adminid='require_adminid';

	/*int(11) */
	const C_userid='userid';

	/*int(11) */
	const C_subject='subject';

	/*int(11) */
	const C_grade='grade';

	/*int(11) */
	const C_seller_student_status='seller_student_status';

	/*varchar(1024) */
	const C_stu_request_test_lesson_demand='stu_request_test_lesson_demand';

	/*int(11) */
	const C_stu_request_test_lesson_time='stu_request_test_lesson_time';

	/*varchar(1024) */
	const C_stu_request_test_lesson_time_info='stu_request_test_lesson_time_info';

	/*varchar(1024) */
	const C_stu_request_lesson_time_info='stu_request_lesson_time_info';

	/*varchar(128) */
	const C_stu_test_paper='stu_test_paper';

	/*int(11) */
	const C_tea_download_paper_time='tea_download_paper_time';

	/*int(11) */
	const C_stu_test_lesson_level='stu_test_lesson_level';

	/*int(11) */
	const C_current_require_id='current_require_id';

	/*int(11) */
	const C_ass_test_lesson_type='ass_test_lesson_type';

	/*int(11) */
	const C_history_accept_adminid='history_accept_adminid';

	/*int(11) */
	const C_seller_student_sub_status='seller_student_sub_status';

	/*varchar(255) */
	const C_textbook='textbook';

	/*int(11) */
	const C_paper_send_wx_flag='paper_send_wx_flag';

	/*varchar(2048) */
	const C_stu_lesson_pic='stu_lesson_pic';
	function get_require_admin_type($test_lesson_subject_id ){
		return $this->field_get_value( $test_lesson_subject_id , self::C_require_admin_type );
	}
	function get_require_adminid($test_lesson_subject_id ){
		return $this->field_get_value( $test_lesson_subject_id , self::C_require_adminid );
	}
	function get_userid($test_lesson_subject_id ){
		return $this->field_get_value( $test_lesson_subject_id , self::C_userid );
	}
	function get_subject($test_lesson_subject_id ){
		return $this->field_get_value( $test_lesson_subject_id , self::C_subject );
	}
	function get_grade($test_lesson_subject_id ){
		return $this->field_get_value( $test_lesson_subject_id , self::C_grade );
	}
	function get_seller_student_status($test_lesson_subject_id ){
		return $this->field_get_value( $test_lesson_subject_id , self::C_seller_student_status );
	}
	function get_stu_request_test_lesson_demand($test_lesson_subject_id ){
		return $this->field_get_value( $test_lesson_subject_id , self::C_stu_request_test_lesson_demand );
	}
	function get_stu_request_test_lesson_time($test_lesson_subject_id ){
		return $this->field_get_value( $test_lesson_subject_id , self::C_stu_request_test_lesson_time );
	}
	function get_stu_request_test_lesson_time_info($test_lesson_subject_id ){
		return $this->field_get_value( $test_lesson_subject_id , self::C_stu_request_test_lesson_time_info );
	}
	function get_stu_request_lesson_time_info($test_lesson_subject_id ){
		return $this->field_get_value( $test_lesson_subject_id , self::C_stu_request_lesson_time_info );
	}
	function get_stu_test_paper($test_lesson_subject_id ){
		return $this->field_get_value( $test_lesson_subject_id , self::C_stu_test_paper );
	}
	function get_tea_download_paper_time($test_lesson_subject_id ){
		return $this->field_get_value( $test_lesson_subject_id , self::C_tea_download_paper_time );
	}
	function get_stu_test_lesson_level($test_lesson_subject_id ){
		return $this->field_get_value( $test_lesson_subject_id , self::C_stu_test_lesson_level );
	}
	function get_current_require_id($test_lesson_subject_id ){
		return $this->field_get_value( $test_lesson_subject_id , self::C_current_require_id );
	}
	function get_ass_test_lesson_type($test_lesson_subject_id ){
		return $this->field_get_value( $test_lesson_subject_id , self::C_ass_test_lesson_type );
	}
	function get_history_accept_adminid($test_lesson_subject_id ){
		return $this->field_get_value( $test_lesson_subject_id , self::C_history_accept_adminid );
	}
	function get_seller_student_sub_status($test_lesson_subject_id ){
		return $this->field_get_value( $test_lesson_subject_id , self::C_seller_student_sub_status );
	}
	function get_textbook($test_lesson_subject_id ){
		return $this->field_get_value( $test_lesson_subject_id , self::C_textbook );
	}
	function get_paper_send_wx_flag($test_lesson_subject_id ){
		return $this->field_get_value( $test_lesson_subject_id , self::C_paper_send_wx_flag );
	}
	function get_stu_lesson_pic($test_lesson_subject_id ){
		return $this->field_get_value( $test_lesson_subject_id , self::C_stu_lesson_pic );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="test_lesson_subject_id";
        $this->field_table_name="db_weiyi.t_test_lesson_subject";
  }
    public function field_get_list( $test_lesson_subject_id, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $test_lesson_subject_id, $set_field_arr) {
        return parent::field_update_list( $test_lesson_subject_id, $set_field_arr);
    }


    public function field_get_value(  $test_lesson_subject_id, $field_name ) {
        return parent::field_get_value( $test_lesson_subject_id, $field_name);
    }

    public function row_delete(  $test_lesson_subject_id) {
        return parent::row_delete( $test_lesson_subject_id);
    }

}

/*
  CREATE TABLE `t_test_lesson_subject` (
  `test_lesson_subject_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `require_admin_type` int(11) NOT NULL COMMENT '0:销售, 1:助教 ',
  `require_adminid` int(11) NOT NULL COMMENT '申请者',
  `userid` int(11) NOT NULL,
  `subject` int(11) NOT NULL COMMENT '科目',
  `grade` int(11) NOT NULL COMMENT '年级',
  `seller_student_status` int(11) NOT NULL COMMENT '无效，意向。',
  `stu_request_test_lesson_demand` varchar(1024) COLLATE latin1_bin NOT NULL COMMENT '试听需求',
  `stu_request_test_lesson_time` int(11) NOT NULL COMMENT '期望上课时间',
  `stu_request_test_lesson_time_info` varchar(1024) COLLATE latin1_bin NOT NULL COMMENT '试听时间段 ',
  `stu_request_lesson_time_info` varchar(1024) COLLATE latin1_bin NOT NULL COMMENT '正式上课后时间段  ',
  `stu_test_paper` varchar(128) COLLATE latin1_bin NOT NULL COMMENT '试卷',
  `tea_download_paper_time` int(11) NOT NULL COMMENT '老师试卷下载时间',
  `stu_test_lesson_level` int(11) NOT NULL COMMENT '试听内容：初级，中级，高级   ',
  `current_require_id` int(11) DEFAULT NULL COMMENT '当前请求线索',
  `ass_test_lesson_type` int(11) NOT NULL COMMENT '助教试听类型',
  `history_accept_adminid` int(11) NOT NULL COMMENT '操作过排课的教务老师',
  `seller_student_sub_status` int(11) NOT NULL COMMENT '子状态',
  `textbook` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '教材版本',
  `paper_send_wx_flag` int(11) NOT NULL COMMENT '试卷未下载微信通知',
  `stu_lesson_pic` varchar(2048) COLLATE latin1_bin NOT NULL COMMENT '微信上传的图片路径',
  PRIMARY KEY (`test_lesson_subject_id`),
  UNIQUE KEY `t_test_lesson_subject_current_require_id_unique` (`current_require_id`),
  KEY `t_test_lesson_subject_userid_index` (`userid`),
  KEY `t_test_lesson_subject_stu_request_test_lesson_time_index` (`stu_request_test_lesson_time`)
) ENGINE=InnoDB AUTO_INCREMENT=6281 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
