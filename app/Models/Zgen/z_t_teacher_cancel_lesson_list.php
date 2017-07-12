<?php
namespace App\Models\Zgen;
class z_t_teacher_cancel_lesson_list extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_teacher_cancel_lesson_list";


	/*int(11) */
	const C_teacherid='teacherid';

	/*int(11) */
	const C_cancel_time='cancel_time';

	/*int(11) */
	const C_lessonid='lessonid';

	/*varchar(255) */
	const C_account='account';

	/*int(11) */
	const C_lesson_time='lesson_time';
	function get_lessonid($teacherid, $cancel_time ){
		return $this->field_get_value_2( $teacherid, $cancel_time  , self::C_lessonid  );
	}
	function get_account($teacherid, $cancel_time ){
		return $this->field_get_value_2( $teacherid, $cancel_time  , self::C_account  );
	}
	function get_lesson_time($teacherid, $cancel_time ){
		return $this->field_get_value_2( $teacherid, $cancel_time  , self::C_lesson_time  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="teacherid";
        $this->field_id2_name="cancel_time";
        $this->field_table_name="db_weiyi.t_teacher_cancel_lesson_list";
  }

    public function field_get_value_2(  $teacherid, $cancel_time,$field_name ) {
        return parent::field_get_value_2(  $teacherid, $cancel_time,$field_name ) ;
    }

    public function field_get_list_2( $teacherid,  $cancel_time,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $teacherid, $cancel_time,  $set_field_arr ) {
        return parent::field_update_list_2( $teacherid, $cancel_time,  $set_field_arr );
    }
    public function row_delete_2(  $teacherid ,$cancel_time ) {
        return parent::row_delete_2( $teacherid ,$cancel_time );
    }


}
/*
  CREATE TABLE `t_teacher_cancel_lesson_list` (
  `teacherid` int(11) NOT NULL COMMENT '老师id',
  `cancel_time` int(11) NOT NULL COMMENT '取消时间',
  `lessonid` int(11) NOT NULL COMMENT '课程id',
  `account` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '操作人',
  `lesson_time` int(11) NOT NULL COMMENT '课程时间',
  PRIMARY KEY (`teacherid`,`cancel_time`),
  UNIQUE KEY `t_teacher_cancel_lesson_list_lessonid_unique` (`lessonid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
