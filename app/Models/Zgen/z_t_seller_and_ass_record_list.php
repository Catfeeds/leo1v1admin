<?php
namespace App\Models\Zgen;
class z_t_seller_and_ass_record_list  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_seller_and_ass_record_list";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_add_time='add_time';

	/*int(11) */
	const C_type='type';

	/*int(11) */
	const C_adminid='adminid';

	/*int(11) */
	const C_userid='userid';

	/*int(11) */
	const C_teacherid='teacherid';

	/*varchar(500) */
	const C_record_info='record_info';

	/*varchar(255) */
	const C_record_info_url='record_info_url';

	/*int(11) */
	const C_subject='subject';

	/*int(11) */
	const C_grade='grade';

	/*varchar(255) */
	const C_textbook='textbook';

	/*varchar(1024) */
	const C_stu_request_test_lesson_demand='stu_request_test_lesson_demand';

	/*varchar(255) */
	const C_stu_score_info='stu_score_info';

	/*varchar(255) */
	const C_stu_character_info='stu_character_info';

	/*varchar(500) */
	const C_record_scheme='record_scheme';

	/*varchar(255) */
	const C_record_scheme_url='record_scheme_url';

	/*int(11) */
	const C_accept_adminid='accept_adminid';

	/*int(11) */
	const C_accept_time='accept_time';

	/*int(11) */
	const C_is_change_teacher='is_change_teacher';

	/*int(11) */
	const C_tea_time='tea_time';

	/*int(11) */
	const C_lessonid='lessonid';

	/*int(11) */
	const C_is_done_flag='is_done_flag';

	/*int(11) */
	const C_done_time='done_time';

	/*int(11) */
	const C_is_resubmit_flag='is_resubmit_flag';

	/*tinyint(4) */
	const C_add_type='add_type';
	function get_add_time($id ){
		return $this->field_get_value( $id , self::C_add_time );
	}
	function get_type($id ){
		return $this->field_get_value( $id , self::C_type );
	}
	function get_adminid($id ){
		return $this->field_get_value( $id , self::C_adminid );
	}
	function get_userid($id ){
		return $this->field_get_value( $id , self::C_userid );
	}
	function get_teacherid($id ){
		return $this->field_get_value( $id , self::C_teacherid );
	}
	function get_record_info($id ){
		return $this->field_get_value( $id , self::C_record_info );
	}
	function get_record_info_url($id ){
		return $this->field_get_value( $id , self::C_record_info_url );
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
	function get_stu_request_test_lesson_demand($id ){
		return $this->field_get_value( $id , self::C_stu_request_test_lesson_demand );
	}
	function get_stu_score_info($id ){
		return $this->field_get_value( $id , self::C_stu_score_info );
	}
	function get_stu_character_info($id ){
		return $this->field_get_value( $id , self::C_stu_character_info );
	}
	function get_record_scheme($id ){
		return $this->field_get_value( $id , self::C_record_scheme );
	}
	function get_record_scheme_url($id ){
		return $this->field_get_value( $id , self::C_record_scheme_url );
	}
	function get_accept_adminid($id ){
		return $this->field_get_value( $id , self::C_accept_adminid );
	}
	function get_accept_time($id ){
		return $this->field_get_value( $id , self::C_accept_time );
	}
	function get_is_change_teacher($id ){
		return $this->field_get_value( $id , self::C_is_change_teacher );
	}
	function get_tea_time($id ){
		return $this->field_get_value( $id , self::C_tea_time );
	}
	function get_lessonid($id ){
		return $this->field_get_value( $id , self::C_lessonid );
	}
	function get_is_done_flag($id ){
		return $this->field_get_value( $id , self::C_is_done_flag );
	}
	function get_done_time($id ){
		return $this->field_get_value( $id , self::C_done_time );
	}
	function get_is_resubmit_flag($id ){
		return $this->field_get_value( $id , self::C_is_resubmit_flag );
	}
	function get_add_type($id ){
		return $this->field_get_value( $id , self::C_add_type );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_seller_and_ass_record_list";
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
  CREATE TABLE `t_seller_and_ass_record_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `add_time` int(11) NOT NULL COMMENT '申请时间',
  `type` int(11) NOT NULL COMMENT '类型',
  `adminid` int(11) NOT NULL COMMENT '申请者',
  `userid` int(11) NOT NULL COMMENT '学生',
  `teacherid` int(11) NOT NULL COMMENT '老师',
  `record_info` varchar(500) COLLATE latin1_bin NOT NULL COMMENT '问题反馈',
  `record_info_url` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '问题反馈图片地址',
  `subject` int(11) NOT NULL COMMENT '科目',
  `grade` int(11) NOT NULL COMMENT '年级',
  `textbook` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '教材版本',
  `stu_request_test_lesson_demand` varchar(1024) COLLATE latin1_bin NOT NULL COMMENT '试听需求',
  `stu_score_info` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '学生成绩',
  `stu_character_info` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '学生性格',
  `record_scheme` varchar(500) COLLATE latin1_bin NOT NULL COMMENT '处理方案',
  `record_scheme_url` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '处理方案图片地址',
  `accept_adminid` int(11) NOT NULL COMMENT '处理人',
  `accept_time` int(11) NOT NULL COMMENT '处理时间',
  `is_change_teacher` int(11) NOT NULL COMMENT '试听后是否更换过老师',
  `tea_time` int(11) NOT NULL COMMENT '老师给学生的上课时长',
  `lessonid` int(11) NOT NULL COMMENT 'lessonid',
  `is_done_flag` int(11) NOT NULL COMMENT '完成标志 0 未设置,1已解决,2未解决',
  `done_time` int(11) NOT NULL COMMENT '解决时间',
  `is_resubmit_flag` int(11) NOT NULL COMMENT '是否重新提交申诉',
  `add_type` tinyint(4) NOT NULL COMMENT '添加类型,0 系统;1手动',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
