<?php
namespace App\Models\Zgen;
class z_t_test_lesson_log_list  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_test_lesson_log_list";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_log_time='log_time';

	/*varchar(20) */
	const C_phone='phone';

	/*varchar(20) */
	const C_phone_location='phone_location';

	/*int(11) */
	const C_test_lesson_bind_adminid='test_lesson_bind_adminid';

	/*int(11) */
	const C_userid='userid';

	/*varchar(64) */
	const C_nick='nick';

	/*varchar(255) */
	const C_origin='origin';

	/*int(11) */
	const C_st_application_time='st_application_time';

	/*int(11) */
	const C_st_class_time='st_class_time';

	/*int(11) */
	const C_lessonid='lessonid';

	/*int(11) */
	const C_teacherid='teacherid';

	/*int(11) */
	const C_lesson_start='lesson_start';

	/*int(11) */
	const C_lesson_end='lesson_end';

	/*int(11) */
	const C_grade='grade';

	/*int(11) */
	const C_subject='subject';

	/*varchar(255) */
	const C_st_application_id='st_application_id';

	/*varchar(255) */
	const C_user_desc='user_desc';

	/*int(11) */
	const C_test_lesson_status='test_lesson_status';

	/*varchar(255) */
	const C_reason='reason';

	/*int(11) */
	const C_st_demand='st_demand';

	/*int(11) */
	const C_del_flag='del_flag';
	function get_log_time($id ){
		return $this->field_get_value( $id , self::C_log_time );
	}
	function get_phone($id ){
		return $this->field_get_value( $id , self::C_phone );
	}
	function get_phone_location($id ){
		return $this->field_get_value( $id , self::C_phone_location );
	}
	function get_test_lesson_bind_adminid($id ){
		return $this->field_get_value( $id , self::C_test_lesson_bind_adminid );
	}
	function get_userid($id ){
		return $this->field_get_value( $id , self::C_userid );
	}
	function get_nick($id ){
		return $this->field_get_value( $id , self::C_nick );
	}
	function get_origin($id ){
		return $this->field_get_value( $id , self::C_origin );
	}
	function get_st_application_time($id ){
		return $this->field_get_value( $id , self::C_st_application_time );
	}
	function get_st_class_time($id ){
		return $this->field_get_value( $id , self::C_st_class_time );
	}
	function get_lessonid($id ){
		return $this->field_get_value( $id , self::C_lessonid );
	}
	function get_teacherid($id ){
		return $this->field_get_value( $id , self::C_teacherid );
	}
	function get_lesson_start($id ){
		return $this->field_get_value( $id , self::C_lesson_start );
	}
	function get_lesson_end($id ){
		return $this->field_get_value( $id , self::C_lesson_end );
	}
	function get_grade($id ){
		return $this->field_get_value( $id , self::C_grade );
	}
	function get_subject($id ){
		return $this->field_get_value( $id , self::C_subject );
	}
	function get_st_application_id($id ){
		return $this->field_get_value( $id , self::C_st_application_id );
	}
	function get_user_desc($id ){
		return $this->field_get_value( $id , self::C_user_desc );
	}
	function get_test_lesson_status($id ){
		return $this->field_get_value( $id , self::C_test_lesson_status );
	}
	function get_reason($id ){
		return $this->field_get_value( $id , self::C_reason );
	}
	function get_st_demand($id ){
		return $this->field_get_value( $id , self::C_st_demand );
	}
	function get_del_flag($id ){
		return $this->field_get_value( $id , self::C_del_flag );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_test_lesson_log_list";
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
  CREATE TABLE `t_test_lesson_log_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_time` int(11) NOT NULL,
  `phone` varchar(20) COLLATE latin1_bin NOT NULL,
  `phone_location` varchar(20) COLLATE latin1_bin NOT NULL,
  `test_lesson_bind_adminid` int(11) NOT NULL COMMENT '试听排课分配人',
  `userid` int(11) NOT NULL,
  `nick` varchar(64) COLLATE latin1_bin NOT NULL,
  `origin` varchar(255) COLLATE latin1_bin NOT NULL,
  `st_application_time` int(11) NOT NULL,
  `st_class_time` int(11) NOT NULL,
  `lessonid` int(11) NOT NULL,
  `teacherid` int(11) NOT NULL,
  `lesson_start` int(11) NOT NULL,
  `lesson_end` int(11) NOT NULL,
  `grade` int(11) NOT NULL,
  `subject` int(11) NOT NULL,
  `st_application_id` varchar(255) COLLATE latin1_bin NOT NULL,
  `user_desc` varchar(255) COLLATE latin1_bin NOT NULL,
  `test_lesson_status` int(11) NOT NULL,
  `reason` varchar(255) COLLATE latin1_bin NOT NULL,
  `st_demand` int(11) NOT NULL COMMENT '试听需求',
  `del_flag` int(11) NOT NULL COMMENT '取消多余记录',
  PRIMARY KEY (`id`),
  KEY `t_test_lesson_log_list_log_time_index` (`log_time`),
  KEY `t_test_lesson_log_list_userid_index` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
