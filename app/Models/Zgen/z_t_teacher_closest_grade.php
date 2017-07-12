<?php
namespace App\Models\Zgen;
class z_t_teacher_closest_grade  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_teacher_closest_grade";


	/*int(11) */
	const C_teacherid='teacherid';

	/*int(11) */
	const C_grade='grade';
	function get_grade($teacherid ){
		return $this->field_get_value( $teacherid , self::C_grade );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="teacherid";
        $this->field_table_name="db_weiyi.t_teacher_closest_grade";
  }
    public function field_get_list( $teacherid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $teacherid, $set_field_arr) {
        return parent::field_update_list( $teacherid, $set_field_arr);
    }


    public function field_get_value(  $teacherid, $field_name ) {
        return parent::field_get_value( $teacherid, $field_name);
    }

    public function row_delete(  $teacherid) {
        return parent::row_delete( $teacherid);
    }

}

/*
  CREATE TABLE `t_teacher_closest_grade` (
  `teacherid` int(11) NOT NULL,
  `grade` int(11) NOT NULL,
  PRIMARY KEY (`teacherid`,`grade`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
