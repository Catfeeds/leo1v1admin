<?php
namespace App\Models\Zgen;
class z_t_fulltime_teacher_attendance_list  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_fulltime_teacher_attendance_list";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_teacherid='teacherid';

	/*int(11) */
	const C_add_time='add_time';

	/*int(11) */
	const C_attendance_type='attendance_type';

	/*int(11) */
	const C_attendance_time='attendance_time';

	/*int(11) */
	const C_day_num='day_num';

	/*int(11) */
	const C_off_time='off_time';

	/*int(11) */
	const C_cancel_flag='cancel_flag';

	/*varchar(255) */
	const C_cancel_reason='cancel_reason';

	/*int(11) */
	const C_adminid='adminid';
	function get_teacherid($id ){
		return $this->field_get_value( $id , self::C_teacherid );
	}
	function get_add_time($id ){
		return $this->field_get_value( $id , self::C_add_time );
	}
	function get_attendance_type($id ){
		return $this->field_get_value( $id , self::C_attendance_type );
	}
	function get_attendance_time($id ){
		return $this->field_get_value( $id , self::C_attendance_time );
	}
	function get_day_num($id ){
		return $this->field_get_value( $id , self::C_day_num );
	}
	function get_off_time($id ){
		return $this->field_get_value( $id , self::C_off_time );
	}
	function get_cancel_flag($id ){
		return $this->field_get_value( $id , self::C_cancel_flag );
	}
	function get_cancel_reason($id ){
		return $this->field_get_value( $id , self::C_cancel_reason );
	}
	function get_adminid($id ){
		return $this->field_get_value( $id , self::C_adminid );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_fulltime_teacher_attendance_list";
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
  CREATE TABLE `t_fulltime_teacher_attendance_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `teacherid` int(11) NOT NULL COMMENT '全职老师',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `attendance_type` int(11) NOT NULL COMMENT '类型 1正常上班,2提前下班,3节假日延休',
  `attendance_time` int(11) NOT NULL COMMENT '休息开始日/提前下班时间',
  `day_num` int(11) NOT NULL COMMENT '休息天数',
  `off_time` int(11) NOT NULL COMMENT '提前下班时间',
  `cancel_flag` int(11) NOT NULL COMMENT '取消标示',
  `cancel_reason` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '取消理由',
  `adminid` int(11) NOT NULL COMMENT '老师后台id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
