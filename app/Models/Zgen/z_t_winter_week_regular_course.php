<?php
namespace App\Models\Zgen;
class z_t_winter_week_regular_course extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_winter_week_regular_course";


	/*int(11) */
	const C_teacherid='teacherid';

	/*int(11) */
	const C_userid='userid';

	/*varchar(20) */
	const C_start_time='start_time';

	/*varchar(20) */
	const C_end_time='end_time';

	/*int(11) */
	const C_lesson_count='lesson_count';

	/*int(11) */
	const C_competition_flag='competition_flag';
	function get_userid($teacherid, $start_time ){
		return $this->field_get_value_2( $teacherid, $start_time  , self::C_userid  );
	}
	function get_end_time($teacherid, $start_time ){
		return $this->field_get_value_2( $teacherid, $start_time  , self::C_end_time  );
	}
	function get_lesson_count($teacherid, $start_time ){
		return $this->field_get_value_2( $teacherid, $start_time  , self::C_lesson_count  );
	}
	function get_competition_flag($teacherid, $start_time ){
		return $this->field_get_value_2( $teacherid, $start_time  , self::C_competition_flag  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="teacherid";
        $this->field_id2_name="start_time";
        $this->field_table_name="db_weiyi.t_winter_week_regular_course";
  }

    public function field_get_value_2(  $teacherid, $start_time,$field_name ) {
        return parent::field_get_value_2(  $teacherid, $start_time,$field_name ) ;
    }

    public function field_get_list_2( $teacherid,  $start_time,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $teacherid, $start_time,  $set_field_arr ) {
        return parent::field_update_list_2( $teacherid, $start_time,  $set_field_arr );
    }
    public function row_delete_2(  $teacherid ,$start_time ) {
        return parent::row_delete_2( $teacherid ,$start_time );
    }


}
/*
  CREATE TABLE `t_winter_week_regular_course` (
  `teacherid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `start_time` varchar(20) COLLATE latin1_bin NOT NULL,
  `end_time` varchar(20) COLLATE latin1_bin NOT NULL,
  `lesson_count` int(11) NOT NULL,
  `competition_flag` int(11) NOT NULL COMMENT '竞赛标示',
  PRIMARY KEY (`teacherid`,`start_time`),
  KEY `t_winter_week_regular_course_userid_index` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
