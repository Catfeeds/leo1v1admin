<?php
namespace App\Models\Zgen;
class z_t_teacher_meeting_join_info extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_teacher_meeting_join_info";


	/*int(11) */
	const C_teacherid='teacherid';

	/*varchar(20) */
	const C_create_time='create_time';

	/*int(11) */
	const C_join_info='join_info';
	function get_join_info($teacherid, $create_time ){
		return $this->field_get_value_2( $teacherid, $create_time  , self::C_join_info  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="teacherid";
        $this->field_id2_name="create_time";
        $this->field_table_name="db_weiyi.t_teacher_meeting_join_info";
  }

    public function field_get_value_2(  $teacherid, $create_time,$field_name ) {
        return parent::field_get_value_2(  $teacherid, $create_time,$field_name ) ;
    }

    public function field_get_list_2( $teacherid,  $create_time,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $teacherid, $create_time,  $set_field_arr ) {
        return parent::field_update_list_2( $teacherid, $create_time,  $set_field_arr );
    }
    public function row_delete_2(  $teacherid ,$create_time ) {
        return parent::row_delete_2( $teacherid ,$create_time );
    }


}
/*
  CREATE TABLE `t_teacher_meeting_join_info` (
  `teacherid` int(11) NOT NULL,
  `create_time` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '会议时间',
  `join_info` int(11) NOT NULL COMMENT '出席会议信息 0 出席 1 请假 2缺席',
  PRIMARY KEY (`teacherid`,`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
