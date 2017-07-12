<?php
namespace App\Models\Zgen;
class z_t_teacher_closest_subject  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_teacher_closest_subject";


	/*int(11) */
	const C_teacherid='teacherid';

	/*int(11) */
	const C_subject='subject';
	function get_subject($teacherid ){
		return $this->field_get_value( $teacherid , self::C_subject );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="teacherid";
        $this->field_table_name="db_weiyi.t_teacher_closest_subject";
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
  CREATE TABLE `t_teacher_closest_subject` (
  `teacherid` int(11) NOT NULL,
  `subject` int(11) NOT NULL,
  PRIMARY KEY (`teacherid`,`subject`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
