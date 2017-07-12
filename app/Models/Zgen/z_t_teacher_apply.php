<?php
namespace App\Models\Zgen;
class z_t_teacher_apply  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_teacher_apply";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_teacherid='teacherid';

	/*int(11) */
	const C_cc_id='cc_id';

	/*int(11) */
	const C_lessonid='lessonid';

	/*int(11) */
	const C_question_type='question_type';

	/*varchar(1024) */
	const C_question_content='question_content';

	/*int(11) */
	const C_teacher_flag='teacher_flag';

	/*int(11) */
	const C_teacher_time='teacher_time';

	/*int(11) */
	const C_cc_flag='cc_flag';

	/*int(11) */
	const C_cc_time='cc_time';

	/*int(11) */
	const C_create_time='create_time';
	function get_teacherid($id ){
		return $this->field_get_value( $id , self::C_teacherid );
	}
	function get_cc_id($id ){
		return $this->field_get_value( $id , self::C_cc_id );
	}
	function get_lessonid($id ){
		return $this->field_get_value( $id , self::C_lessonid );
	}
	function get_question_type($id ){
		return $this->field_get_value( $id , self::C_question_type );
	}
	function get_question_content($id ){
		return $this->field_get_value( $id , self::C_question_content );
	}
	function get_teacher_flag($id ){
		return $this->field_get_value( $id , self::C_teacher_flag );
	}
	function get_teacher_time($id ){
		return $this->field_get_value( $id , self::C_teacher_time );
	}
	function get_cc_flag($id ){
		return $this->field_get_value( $id , self::C_cc_flag );
	}
	function get_cc_time($id ){
		return $this->field_get_value( $id , self::C_cc_time );
	}
	function get_create_time($id ){
		return $this->field_get_value( $id , self::C_create_time );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_teacher_apply";
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
  CREATE TABLE `t_teacher_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacherid` int(11) NOT NULL COMMENT '讲师id',
  `cc_id` int(11) NOT NULL COMMENT 'ccid',
  `lessonid` int(11) NOT NULL COMMENT '课程id',
  `question_type` int(11) NOT NULL COMMENT '问题类型',
  `question_content` varchar(1024) COLLATE latin1_bin NOT NULL COMMENT '问题描述',
  `teacher_flag` int(11) NOT NULL COMMENT '讲师反馈状态1处理0未处理',
  `teacher_time` int(11) NOT NULL COMMENT '讲师处理反馈时间',
  `cc_flag` int(11) NOT NULL COMMENT 'cc反馈状态1处理0未处理',
  `cc_time` int(11) NOT NULL COMMENT 'cc处理反馈时间',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `db_weiyi_t_teacher_apply_create_time_index` (`create_time`),
  KEY `db_weiyi_t_teacher_apply_lessonid_index` (`lessonid`),
  KEY `db_weiyi_t_teacher_apply_teacherid_teacher_time_index` (`teacherid`,`teacher_time`),
  KEY `db_weiyi_t_teacher_apply_cc_id_create_time_index` (`cc_id`,`create_time`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
