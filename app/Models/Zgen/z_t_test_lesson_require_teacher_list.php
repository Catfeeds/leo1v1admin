<?php
namespace App\Models\Zgen;
class z_t_test_lesson_require_teacher_list  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_test_lesson_require_teacher_list";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_add_time='add_time';

	/*int(11) */
	const C_require_id='require_id';

	/*varchar(500) */
	const C_teacher_info='teacher_info';

	/*int(11) */
	const C_teacherid='teacherid';
	function get_add_time($id ){
		return $this->field_get_value( $id , self::C_add_time );
	}
	function get_require_id($id ){
		return $this->field_get_value( $id , self::C_require_id );
	}
	function get_teacher_info($id ){
		return $this->field_get_value( $id , self::C_teacher_info );
	}
	function get_teacherid($id ){
		return $this->field_get_value( $id , self::C_teacherid );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_test_lesson_require_teacher_list";
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
  CREATE TABLE `t_test_lesson_require_teacher_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `add_time` int(11) NOT NULL COMMENT 'add时间',
  `require_id` int(11) NOT NULL COMMENT '申请id',
  `teacher_info` varchar(500) COLLATE latin1_bin NOT NULL COMMENT '符合的老师',
  `teacherid` int(11) NOT NULL COMMENT '预分配老师id',
  PRIMARY KEY (`id`),
  KEY `add_time` (`add_time`),
  KEY `require_id` (`require_id`),
  KEY `teacherid` (`teacherid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
