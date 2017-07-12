<?php
namespace App\Models\Zgen;
class z_t_teacher_meeting_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_teacher_meeting_info";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_create_time='create_time';

	/*varchar(255) */
	const C_summary='summary';

	/*varchar(255) */
	const C_theme='theme';

	/*varchar(20) */
	const C_moderator='moderator';

	/*varchar(255) */
	const C_address='address';

	/*varchar(15000) */
	const C_teacher_join_info='teacher_join_info';
	function get_create_time($id ){
		return $this->field_get_value( $id , self::C_create_time );
	}
	function get_summary($id ){
		return $this->field_get_value( $id , self::C_summary );
	}
	function get_theme($id ){
		return $this->field_get_value( $id , self::C_theme );
	}
	function get_moderator($id ){
		return $this->field_get_value( $id , self::C_moderator );
	}
	function get_address($id ){
		return $this->field_get_value( $id , self::C_address );
	}
	function get_teacher_join_info($id ){
		return $this->field_get_value( $id , self::C_teacher_join_info );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_teacher_meeting_info";
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
  CREATE TABLE `t_teacher_meeting_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_time` int(11) NOT NULL COMMENT '会议时间',
  `summary` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '会议纪要',
  `theme` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '会议主题',
  `moderator` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '会议主持人',
  `address` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '会议地点',
  `teacher_join_info` varchar(15000) COLLATE latin1_bin NOT NULL COMMENT '与会人信息',
  PRIMARY KEY (`id`),
  KEY `t_teacher_meeting_info_id_index` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
