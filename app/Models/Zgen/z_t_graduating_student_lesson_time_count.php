<?php
namespace App\Models\Zgen;
class z_t_graduating_student_lesson_time_count  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_graduating_student_lesson_time_count";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_userid='userid';

	/*int(11) */
	const C_start_time='start_time';

	/*int(11) */
	const C_plan_lesson_time='plan_lesson_time';
	function get_userid($id ){
		return $this->field_get_value( $id , self::C_userid );
	}
	function get_start_time($id ){
		return $this->field_get_value( $id , self::C_start_time );
	}
	function get_plan_lesson_time($id ){
		return $this->field_get_value( $id , self::C_plan_lesson_time );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_graduating_student_lesson_time_count";
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
  CREATE TABLE `t_graduating_student_lesson_time_count` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `userid` int(11) NOT NULL COMMENT '用户id',
  `start_time` int(11) NOT NULL COMMENT '起始时间',
  `plan_lesson_time` int(11) NOT NULL COMMENT '计划课时',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_opt_time` (`userid`,`start_time`)
) ENGINE=InnoDB AUTO_INCREMENT=864 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
