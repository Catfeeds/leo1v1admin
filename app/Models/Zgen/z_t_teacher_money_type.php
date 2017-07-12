<?php
namespace App\Models\Zgen;
class z_t_teacher_money_type  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_teacher_money_type";


	/*int(11) */
	const C_teacher_money_type='teacher_money_type';

	/*int(11) */
	const C_level='level';

	/*int(11) */
	const C_grade='grade';

	/*int(11) */
	const C_money='money';

	/*int(11) */
	const C_type='type';
	function get_level($teacher_money_type ){
		return $this->field_get_value( $teacher_money_type , self::C_level );
	}
	function get_grade($teacher_money_type ){
		return $this->field_get_value( $teacher_money_type , self::C_grade );
	}
	function get_money($teacher_money_type ){
		return $this->field_get_value( $teacher_money_type , self::C_money );
	}
	function get_type($teacher_money_type ){
		return $this->field_get_value( $teacher_money_type , self::C_type );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="teacher_money_type";
        $this->field_table_name="db_weiyi.t_teacher_money_type";
  }
    public function field_get_list( $teacher_money_type, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $teacher_money_type, $set_field_arr) {
        return parent::field_update_list( $teacher_money_type, $set_field_arr);
    }


    public function field_get_value(  $teacher_money_type, $field_name ) {
        return parent::field_get_value( $teacher_money_type, $field_name);
    }

    public function row_delete(  $teacher_money_type) {
        return parent::row_delete( $teacher_money_type);
    }

}

/*
  CREATE TABLE `t_teacher_money_type` (
  `teacher_money_type` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `grade` int(11) NOT NULL,
  `money` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`teacher_money_type`,`level`,`grade`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
