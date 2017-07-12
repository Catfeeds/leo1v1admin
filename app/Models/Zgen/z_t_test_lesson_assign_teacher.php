<?php
namespace App\Models\Zgen;
class z_t_test_lesson_assign_teacher  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_test_lesson_assign_teacher";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_seller_student_id='seller_student_id';

	/*int(11) */
	const C_teacherid='teacherid';

	/*int(11) */
	const C_assign_time='assign_time';

	/*int(11) */
	const C_teacher_confirm_flag='teacher_confirm_flag';

	/*int(11) */
	const C_teacher_confirm_time='teacher_confirm_time';

	/*int(11) */
	const C_assign_adminid='assign_adminid';

	/*int(11) */
	const C_degree='degree';
	function get_seller_student_id($id ){
		return $this->field_get_value( $id , self::C_seller_student_id );
	}
	function get_teacherid($id ){
		return $this->field_get_value( $id , self::C_teacherid );
	}
	function get_assign_time($id ){
		return $this->field_get_value( $id , self::C_assign_time );
	}
	function get_teacher_confirm_flag($id ){
		return $this->field_get_value( $id , self::C_teacher_confirm_flag );
	}
	function get_teacher_confirm_time($id ){
		return $this->field_get_value( $id , self::C_teacher_confirm_time );
	}
	function get_assign_adminid($id ){
		return $this->field_get_value( $id , self::C_assign_adminid );
	}
	function get_degree($id ){
		return $this->field_get_value( $id , self::C_degree );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_test_lesson_assign_teacher";
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
  CREATE TABLE `t_test_lesson_assign_teacher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_student_id` int(11) NOT NULL COMMENT 'from t_seller_student_info',
  `teacherid` int(11) NOT NULL,
  `assign_time` int(11) NOT NULL COMMENT '派单时间',
  `teacher_confirm_flag` int(11) NOT NULL COMMENT '接受flag, 0:未设置,1:接受,2:不接受',
  `teacher_confirm_time` int(11) NOT NULL,
  `assign_adminid` int(11) NOT NULL COMMENT '派单者',
  `degree` int(11) NOT NULL COMMENT '擅长程度',
  PRIMARY KEY (`id`),
  UNIQUE KEY `t_test_lesson_assign_teacher_seller_student_id_teacherid_unique` (`seller_student_id`,`teacherid`),
  KEY `t_test_lesson_assign_teacher_seller_student_id_index` (`seller_student_id`),
  KEY `t_test_lesson_assign_teacher_teacherid_assign_time_index` (`teacherid`,`assign_time`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
