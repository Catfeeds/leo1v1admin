<?php
namespace App\Models\Zgen;
class z_t_test_lesson_order_info_old extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_test_lesson_order_info_old";


	/*int(11) */
	const C_teacherid='teacherid';

	/*int(11) */
	const C_userid='userid';

	/*int(11) */
	const C_first_lesson_time='first_lesson_time';

	/*int(11) */
	const C_test_lesson_time='test_lesson_time';

	/*int(11) */
	const C_subject='subject';
	function get_first_lesson_time($teacherid, $userid ){
		return $this->field_get_value_2( $teacherid, $userid  , self::C_first_lesson_time  );
	}
	function get_test_lesson_time($teacherid, $userid ){
		return $this->field_get_value_2( $teacherid, $userid  , self::C_test_lesson_time  );
	}
	function get_subject($teacherid, $userid ){
		return $this->field_get_value_2( $teacherid, $userid  , self::C_subject  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="teacherid";
        $this->field_id2_name="userid";
        $this->field_table_name="db_weiyi.t_test_lesson_order_info_old";
  }

    public function field_get_value_2(  $teacherid, $userid,$field_name ) {
        return parent::field_get_value_2(  $teacherid, $userid,$field_name ) ;
    }

    public function field_get_list_2( $teacherid,  $userid,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $teacherid, $userid,  $set_field_arr ) {
        return parent::field_update_list_2( $teacherid, $userid,  $set_field_arr );
    }
    public function row_delete_2(  $teacherid ,$userid ) {
        return parent::row_delete_2( $teacherid ,$userid );
    }


}
/*
  CREATE TABLE `t_test_lesson_order_info_old` (
  `teacherid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `first_lesson_time` int(11) NOT NULL,
  `test_lesson_time` int(11) NOT NULL,
  `subject` int(11) NOT NULL COMMENT '科目',
  PRIMARY KEY (`teacherid`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
