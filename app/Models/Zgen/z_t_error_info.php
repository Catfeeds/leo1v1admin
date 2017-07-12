<?php
namespace App\Models\Zgen;
class z_t_error_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_error_info";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_lessonid='lessonid';

	/*int(11) */
	const C_courseid='courseid';

	/*int(11) */
	const C_lesson_type='lesson_type';

	/*varchar(500) */
	const C_error_info='error_info';

	/*varchar(500) */
	const C_error_info_other='error_info_other';

	/*varchar(500) */
	const C_error_reason='error_reason';

	/*varchar(500) */
	const C_error_solve='error_solve';

	/*varchar(500) */
	const C_server_change='server_change';

	/*varchar(1000) */
	const C_image_url='image_url';

	/*varchar(100) */
	const C_error_return='error_return';

	/*varchar(1000) */
	const C_note='note';

	/*varchar(500) */
	const C_add_user='add_user';

	/*int(11) */
	const C_add_time='add_time';

	/*varchar(100) */
	const C_lesson_time='lesson_time';

	/*varchar(100) */
	const C_tea_nick='tea_nick';

	/*varchar(100) */
	const C_stu_nick_err='stu_nick_err';

	/*varchar(100) */
	const C_stu_nick='stu_nick';

	/*int(11) */
	const C_lesson_start='lesson_start';

	/*int(11) */
	const C_lesson_end='lesson_end';

	/*varchar(500) */
	const C_tea_agent='tea_agent';

	/*varchar(500) */
	const C_stu_agent='stu_agent';

	/*varchar(100) */
	const C_tea_area='tea_area';

	/*varchar(100) */
	const C_stu_area='stu_area';

	/*varchar(500) */
	const C_error_solve_tem='error_solve_tem';

	/*varchar(100) */
	const C_stu_cont='stu_cont';
	function get_lessonid($id ){
		return $this->field_get_value( $id , self::C_lessonid );
	}
	function get_courseid($id ){
		return $this->field_get_value( $id , self::C_courseid );
	}
	function get_lesson_type($id ){
		return $this->field_get_value( $id , self::C_lesson_type );
	}
	function get_error_info($id ){
		return $this->field_get_value( $id , self::C_error_info );
	}
	function get_error_info_other($id ){
		return $this->field_get_value( $id , self::C_error_info_other );
	}
	function get_error_reason($id ){
		return $this->field_get_value( $id , self::C_error_reason );
	}
	function get_error_solve($id ){
		return $this->field_get_value( $id , self::C_error_solve );
	}
	function get_server_change($id ){
		return $this->field_get_value( $id , self::C_server_change );
	}
	function get_image_url($id ){
		return $this->field_get_value( $id , self::C_image_url );
	}
	function get_error_return($id ){
		return $this->field_get_value( $id , self::C_error_return );
	}
	function get_note($id ){
		return $this->field_get_value( $id , self::C_note );
	}
	function get_add_user($id ){
		return $this->field_get_value( $id , self::C_add_user );
	}
	function get_add_time($id ){
		return $this->field_get_value( $id , self::C_add_time );
	}
	function get_lesson_time($id ){
		return $this->field_get_value( $id , self::C_lesson_time );
	}
	function get_tea_nick($id ){
		return $this->field_get_value( $id , self::C_tea_nick );
	}
	function get_stu_nick_err($id ){
		return $this->field_get_value( $id , self::C_stu_nick_err );
	}
	function get_stu_nick($id ){
		return $this->field_get_value( $id , self::C_stu_nick );
	}
	function get_lesson_start($id ){
		return $this->field_get_value( $id , self::C_lesson_start );
	}
	function get_lesson_end($id ){
		return $this->field_get_value( $id , self::C_lesson_end );
	}
	function get_tea_agent($id ){
		return $this->field_get_value( $id , self::C_tea_agent );
	}
	function get_stu_agent($id ){
		return $this->field_get_value( $id , self::C_stu_agent );
	}
	function get_tea_area($id ){
		return $this->field_get_value( $id , self::C_tea_area );
	}
	function get_stu_area($id ){
		return $this->field_get_value( $id , self::C_stu_area );
	}
	function get_error_solve_tem($id ){
		return $this->field_get_value( $id , self::C_error_solve_tem );
	}
	function get_stu_cont($id ){
		return $this->field_get_value( $id , self::C_stu_cont );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_error_info";
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
  CREATE TABLE `t_error_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lessonid` int(11) NOT NULL DEFAULT '0',
  `courseid` int(11) DEFAULT NULL,
  `lesson_type` int(11) DEFAULT NULL,
  `error_info` varchar(500) DEFAULT NULL,
  `error_info_other` varchar(500) DEFAULT NULL,
  `error_reason` varchar(500) DEFAULT NULL,
  `error_solve` varchar(500) DEFAULT NULL,
  `server_change` varchar(500) DEFAULT NULL,
  `image_url` varchar(1000) DEFAULT NULL,
  `error_return` varchar(100) DEFAULT NULL,
  `note` varchar(1000) DEFAULT NULL,
  `add_user` varchar(500) DEFAULT NULL,
  `add_time` int(11) DEFAULT NULL,
  `lesson_time` varchar(100) DEFAULT NULL,
  `tea_nick` varchar(100) DEFAULT NULL,
  `stu_nick_err` varchar(100) DEFAULT NULL,
  `stu_nick` varchar(100) DEFAULT NULL,
  `lesson_start` int(11) DEFAULT NULL,
  `lesson_end` int(11) DEFAULT NULL,
  `tea_agent` varchar(500) DEFAULT NULL,
  `stu_agent` varchar(500) DEFAULT NULL,
  `tea_area` varchar(100) DEFAULT NULL,
  `stu_area` varchar(100) DEFAULT NULL,
  `error_solve_tem` varchar(500) DEFAULT NULL,
  `stu_cont` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=latin1
 */
