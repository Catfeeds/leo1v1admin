<?php
namespace App\Models\Zgen;
class z_t_change_teacher_list  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_change_teacher_list";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_add_time='add_time';

	/*int(11) */
	const C_ass_adminid='ass_adminid';

	/*int(11) */
	const C_userid='userid';

	/*int(11) */
	const C_teacherid='teacherid';

	/*varchar(500) */
	const C_change_reason='change_reason';

	/*varchar(500) */
	const C_except_teacher='except_teacher';

	/*int(11) */
	const C_subject='subject';

	/*int(11) */
	const C_grade='grade';

	/*varchar(255) */
	const C_textbook='textbook';

	/*varchar(64) */
	const C_phone_location='phone_location';

	/*varchar(255) */
	const C_stu_score_info='stu_score_info';

	/*varchar(255) */
	const C_stu_character_info='stu_character_info';

	/*varchar(255) */
	const C_record_teacher='record_teacher';

	/*varchar(255) */
	const C_accept_reason='accept_reason';

	/*int(11) */
	const C_accept_flag='accept_flag';

	/*int(11) */
	const C_accept_adminid='accept_adminid';

	/*int(11) */
	const C_accept_time='accept_time';

	/*varchar(255) */
	const C_change_reason_url='change_reason_url';

	/*int(11) */
	const C_commend_teacherid='commend_teacherid';

	/*int(11) */
	const C_change_teacher_reason_type='change_teacher_reason_type';

	/*varchar(1024) */
	const C_stu_request_test_lesson_demand='stu_request_test_lesson_demand';

	/*varchar(1024) */
	const C_stu_request_lesson_time_info='stu_request_lesson_time_info';

	/*int(11) */
	const C_stu_request_test_lesson_time='stu_request_test_lesson_time';

	/*int(11) */
	const C_commend_type='commend_type';

	/*int(11) */
	const C_wx_send_time='wx_send_time';

	/*int(11) */
	const C_done_time='done_time';

	/*int(11) */
	const C_is_resubmit_flag='is_resubmit_flag';

	/*int(11) */
	const C_is_done_flag='is_done_flag';
	function get_add_time($id ){
		return $this->field_get_value( $id , self::C_add_time );
	}
	function get_ass_adminid($id ){
		return $this->field_get_value( $id , self::C_ass_adminid );
	}
	function get_userid($id ){
		return $this->field_get_value( $id , self::C_userid );
	}
	function get_teacherid($id ){
		return $this->field_get_value( $id , self::C_teacherid );
	}
	function get_change_reason($id ){
		return $this->field_get_value( $id , self::C_change_reason );
	}
	function get_except_teacher($id ){
		return $this->field_get_value( $id , self::C_except_teacher );
	}
	function get_subject($id ){
		return $this->field_get_value( $id , self::C_subject );
	}
	function get_grade($id ){
		return $this->field_get_value( $id , self::C_grade );
	}
	function get_textbook($id ){
		return $this->field_get_value( $id , self::C_textbook );
	}
	function get_phone_location($id ){
		return $this->field_get_value( $id , self::C_phone_location );
	}
	function get_stu_score_info($id ){
		return $this->field_get_value( $id , self::C_stu_score_info );
	}
	function get_stu_character_info($id ){
		return $this->field_get_value( $id , self::C_stu_character_info );
	}
	function get_record_teacher($id ){
		return $this->field_get_value( $id , self::C_record_teacher );
	}
	function get_accept_reason($id ){
		return $this->field_get_value( $id , self::C_accept_reason );
	}
	function get_accept_flag($id ){
		return $this->field_get_value( $id , self::C_accept_flag );
	}
	function get_accept_adminid($id ){
		return $this->field_get_value( $id , self::C_accept_adminid );
	}
	function get_accept_time($id ){
		return $this->field_get_value( $id , self::C_accept_time );
	}
	function get_change_reason_url($id ){
		return $this->field_get_value( $id , self::C_change_reason_url );
	}
	function get_commend_teacherid($id ){
		return $this->field_get_value( $id , self::C_commend_teacherid );
	}
	function get_change_teacher_reason_type($id ){
		return $this->field_get_value( $id , self::C_change_teacher_reason_type );
	}
	function get_stu_request_test_lesson_demand($id ){
		return $this->field_get_value( $id , self::C_stu_request_test_lesson_demand );
	}
	function get_stu_request_lesson_time_info($id ){
		return $this->field_get_value( $id , self::C_stu_request_lesson_time_info );
	}
	function get_stu_request_test_lesson_time($id ){
		return $this->field_get_value( $id , self::C_stu_request_test_lesson_time );
	}
	function get_commend_type($id ){
		return $this->field_get_value( $id , self::C_commend_type );
	}
	function get_wx_send_time($id ){
		return $this->field_get_value( $id , self::C_wx_send_time );
	}
	function get_done_time($id ){
		return $this->field_get_value( $id , self::C_done_time );
	}
	function get_is_resubmit_flag($id ){
		return $this->field_get_value( $id , self::C_is_resubmit_flag );
	}
	function get_is_done_flag($id ){
		return $this->field_get_value( $id , self::C_is_done_flag );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_change_teacher_list";
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
  CREATE TABLE `t_change_teacher_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `add_time` int(11) NOT NULL COMMENT '申请时间',
  `ass_adminid` int(11) NOT NULL COMMENT '申请者',
  `userid` int(11) NOT NULL COMMENT '学生',
  `teacherid` int(11) NOT NULL COMMENT '当前老师',
  `change_reason` varchar(500) COLLATE latin1_bin NOT NULL COMMENT '申请原因',
  `except_teacher` varchar(500) COLLATE latin1_bin NOT NULL COMMENT '期望老师',
  `subject` int(11) NOT NULL COMMENT '科目',
  `grade` int(11) NOT NULL COMMENT '年级',
  `textbook` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '教材版本',
  `phone_location` varchar(64) COLLATE latin1_bin NOT NULL COMMENT '所在地区',
  `stu_score_info` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '学生成绩',
  `stu_character_info` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '学生性格',
  `record_teacher` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '反馈的老师信息',
  `accept_reason` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '驳回理由',
  `accept_flag` int(11) NOT NULL COMMENT '是否接受申请',
  `accept_adminid` int(11) NOT NULL COMMENT '申请处理人',
  `accept_time` int(11) NOT NULL COMMENT '申请处理时间',
  `change_reason_url` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '上传原因相关图片',
  `commend_teacherid` int(11) NOT NULL COMMENT '推荐的老师',
  `change_teacher_reason_type` int(11) NOT NULL COMMENT '换老师原因类型',
  `stu_request_test_lesson_demand` varchar(1024) COLLATE latin1_bin NOT NULL COMMENT '试听需求',
  `stu_request_lesson_time_info` varchar(1024) COLLATE latin1_bin NOT NULL COMMENT '期待常规课上课时间段',
  `stu_request_test_lesson_time` int(11) NOT NULL COMMENT '期望试听上课时间',
  `commend_type` int(11) NOT NULL COMMENT '类型 1,助教换老师申请;2,销售申请试听推荐老师',
  `wx_send_time` int(11) NOT NULL COMMENT '微信推送教研时间',
  `done_time` int(11) NOT NULL COMMENT '完成时间',
  `is_resubmit_flag` int(11) NOT NULL COMMENT '是否重新提交申请',
  `is_done_flag` int(11) NOT NULL COMMENT '完成标志 0 未设置,1已解决,2未解决',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
