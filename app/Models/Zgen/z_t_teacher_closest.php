<?php
namespace App\Models\Zgen;
class z_t_teacher_closest  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_teacher_closest";


	/*int(11) */
	const C_teacherid='teacherid';

	/*int(11) */
	const C_subject='subject';

	/*int(11) */
	const C_grade='grade';

	/*int(11) */
	const C_degree='degree';

	/*varchar(255) */
	const C_introduction='introduction';
	function get_teacherid($uid ){
		return $this->field_get_value( $uid , self::C_teacherid );
	}
	function get_subject($uid ){
		return $this->field_get_value( $uid , self::C_subject );
	}
	function get_grade($uid ){
		return $this->field_get_value( $uid , self::C_grade );
	}
	function get_degree($uid ){
		return $this->field_get_value( $uid , self::C_degree );
	}
	function get_introduction($uid ){
		return $this->field_get_value( $uid , self::C_introduction );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="uid";
        $this->field_table_name="db_weiyi.t_teacher_closest";
  }
    public function field_get_list( $uid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $uid, $set_field_arr) {
        return parent::field_update_list( $uid, $set_field_arr);
    }


    public function field_get_value(  $uid, $field_name ) {
        return parent::field_get_value( $uid, $field_name);
    }

    public function row_delete(  $uid) {
        return parent::row_delete( $uid);
    }

}

/*
  CREATE TABLE `t_teacher_closest` (
  `teacherid` int(11) NOT NULL,
  `subject` int(11) NOT NULL,
  `grade` int(11) NOT NULL,
  `degree` int(11) NOT NULL COMMENT '程度',
  `introduction` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '说明',
  PRIMARY KEY (`teacherid`,`subject`,`grade`),
  KEY `t_teacher_closest_subject_grade_index` (`subject`,`grade`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
