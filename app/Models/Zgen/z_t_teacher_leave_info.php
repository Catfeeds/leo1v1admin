<?php
namespace App\Models\Zgen;
class z_t_teacher_leave_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_teacher_leave_info";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_teacherid='teacherid';

	/*int(11) */
	const C_leave_start_time='leave_start_time';

	/*int(11) */
	const C_leave_end_time='leave_end_time';

	/*int(11) */
	const C_leave_set_adminid='leave_set_adminid';

	/*int(11) */
	const C_leave_set_time='leave_set_time';

	/*int(11) */
	const C_leave_remove_adminid='leave_remove_adminid';

	/*int(11) */
	const C_leave_remove_time='leave_remove_time';

	/*varchar(500) */
	const C_leave_reason='leave_reason';
	function get_teacherid($id ){
		return $this->field_get_value( $id , self::C_teacherid );
	}
	function get_leave_start_time($id ){
		return $this->field_get_value( $id , self::C_leave_start_time );
	}
	function get_leave_end_time($id ){
		return $this->field_get_value( $id , self::C_leave_end_time );
	}
	function get_leave_set_adminid($id ){
		return $this->field_get_value( $id , self::C_leave_set_adminid );
	}
	function get_leave_set_time($id ){
		return $this->field_get_value( $id , self::C_leave_set_time );
	}
	function get_leave_remove_adminid($id ){
		return $this->field_get_value( $id , self::C_leave_remove_adminid );
	}
	function get_leave_remove_time($id ){
		return $this->field_get_value( $id , self::C_leave_remove_time );
	}
	function get_leave_reason($id ){
		return $this->field_get_value( $id , self::C_leave_reason );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_teacher_leave_info";
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
  CREATE TABLE `t_teacher_leave_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `teacherid` int(11) NOT NULL COMMENT '请假老师',
  `leave_start_time` int(11) NOT NULL COMMENT '请假开始时间',
  `leave_end_time` int(11) NOT NULL COMMENT '请假结束时间',
  `leave_set_adminid` int(11) NOT NULL COMMENT '请假设置人',
  `leave_set_time` int(11) NOT NULL COMMENT '请假设置时间',
  `leave_remove_adminid` int(11) NOT NULL COMMENT '休课解除设置人',
  `leave_remove_time` int(11) NOT NULL COMMENT '休课解除时间',
  `leave_reason` varchar(500) COLLATE latin1_bin NOT NULL COMMENT '请假理由',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
