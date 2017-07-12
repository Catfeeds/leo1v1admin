<?php
namespace App\Models\Zgen;
class z_t_order_course_list  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_order_course_list";


	/*int(10) unsigned */
	const C_courseid='courseid';

	/*varchar(100) */
	const C_course_name='course_name';

	/*int(11) */
	const C_type='type';
	function get_course_name($courseid ){
		return $this->field_get_value( $courseid , self::C_course_name );
	}
	function get_type($courseid ){
		return $this->field_get_value( $courseid , self::C_type );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="courseid";
        $this->field_table_name="db_weiyi.t_order_course_list";
  }
    public function field_get_list( $courseid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $courseid, $set_field_arr) {
        return parent::field_update_list( $courseid, $set_field_arr);
    }


    public function field_get_value(  $courseid, $field_name ) {
        return parent::field_get_value( $courseid, $field_name);
    }

    public function row_delete(  $courseid) {
        return parent::row_delete( $courseid);
    }

}

/*
  CREATE TABLE `t_order_course_list` (
  `courseid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `course_name` varchar(100) NOT NULL COMMENT '课程名',
  `type` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`courseid`)
) ENGINE=InnoDB AUTO_INCREMENT=1005 DEFAULT CHARSET=latin1
 */
