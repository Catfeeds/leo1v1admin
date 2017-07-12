<?php
namespace App\Models\Zgen;
class z_t_order_course_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_order_course_info";


	/*int(10) unsigned */
	const C_id='id';

	/*int(10) unsigned */
	const C_courseid='courseid';

	/*int(10) unsigned */
	const C_type='type';

	/*int(10) unsigned */
	const C_start_time='start_time';

	/*int(10) unsigned */
	const C_end_time='end_time';

	/*int(10) unsigned */
	const C_course_people='course_people';

	/*int(10) unsigned */
	const C_course_people_current='course_people_current';
	function get_courseid($id ){
		return $this->field_get_value( $id , self::C_courseid );
	}
	function get_type($id ){
		return $this->field_get_value( $id , self::C_type );
	}
	function get_start_time($id ){
		return $this->field_get_value( $id , self::C_start_time );
	}
	function get_end_time($id ){
		return $this->field_get_value( $id , self::C_end_time );
	}
	function get_course_people($id ){
		return $this->field_get_value( $id , self::C_course_people );
	}
	function get_course_people_current($id ){
		return $this->field_get_value( $id , self::C_course_people_current );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_order_course_info";
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
  CREATE TABLE `t_order_course_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `courseid` int(10) unsigned NOT NULL,
  `type` int(10) unsigned NOT NULL DEFAULT '0',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0',
  `end_time` int(10) unsigned DEFAULT NULL,
  `course_people` int(10) unsigned NOT NULL,
  `course_people_current` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`,`type`,`start_time`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1
 */
