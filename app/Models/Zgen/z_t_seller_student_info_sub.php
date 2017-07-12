<?php
namespace App\Models\Zgen;
class z_t_seller_student_info_sub  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_seller_student_info_sub";


	/*int(10) unsigned */
	const C_id='id';

	/*varchar(16) */
	const C_phone='phone';

	/*varchar(64) */
	const C_phone_location='phone_location';

	/*varchar(32) */
	const C_origin='origin';

	/*int(10) unsigned */
	const C_add_time='add_time';

	/*int(11) */
	const C_subject='subject';

	/*int(11) */
	const C_grade='grade';

	/*int(11) */
	const C_has_pad='has_pad';

	/*int(11) */
	const C_trial_type='trial_type';

	/*varchar(64) */
	const C_nick='nick';

	/*varchar(64) */
	const C_qq='qq';

	/*int(11) */
	const C_admin_revisiterid='admin_revisiterid';
	function get_phone($id ){
		return $this->field_get_value( $id , self::C_phone );
	}
	function get_phone_location($id ){
		return $this->field_get_value( $id , self::C_phone_location );
	}
	function get_origin($id ){
		return $this->field_get_value( $id , self::C_origin );
	}
	function get_add_time($id ){
		return $this->field_get_value( $id , self::C_add_time );
	}
	function get_subject($id ){
		return $this->field_get_value( $id , self::C_subject );
	}
	function get_grade($id ){
		return $this->field_get_value( $id , self::C_grade );
	}
	function get_has_pad($id ){
		return $this->field_get_value( $id , self::C_has_pad );
	}
	function get_trial_type($id ){
		return $this->field_get_value( $id , self::C_trial_type );
	}
	function get_nick($id ){
		return $this->field_get_value( $id , self::C_nick );
	}
	function get_qq($id ){
		return $this->field_get_value( $id , self::C_qq );
	}
	function get_admin_revisiterid($id ){
		return $this->field_get_value( $id , self::C_admin_revisiterid );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_seller_student_info_sub";
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
  CREATE TABLE `t_seller_student_info_sub` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(16) COLLATE latin1_bin NOT NULL,
  `phone_location` varchar(64) COLLATE latin1_bin NOT NULL,
  `origin` varchar(32) COLLATE latin1_bin NOT NULL,
  `add_time` int(10) unsigned NOT NULL,
  `subject` int(11) NOT NULL,
  `grade` int(11) NOT NULL,
  `has_pad` int(11) NOT NULL,
  `trial_type` int(11) NOT NULL,
  `nick` varchar(64) COLLATE latin1_bin NOT NULL,
  `qq` varchar(64) COLLATE latin1_bin NOT NULL,
  `admin_revisiterid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `phone` (`phone`),
  KEY `admin_revisiterid__add_time` (`admin_revisiterid`,`add_time`),
  KEY `add_time` (`add_time`)
) ENGINE=InnoDB AUTO_INCREMENT=3455 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
