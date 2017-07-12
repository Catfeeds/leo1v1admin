<?php
namespace App\Models\Zgen;
class z_t_small_class_user extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_small_class_user";


	/*int(10) unsigned */
	const C_courseid='courseid';

	/*int(10) unsigned */
	const C_userid='userid';

	/*int(10) unsigned */
	const C_join_time='join_time';

	/*tinyint(4) */
	const C_del_flag='del_flag';
	function get_join_time($courseid, $userid ){
		return $this->field_get_value_2( $courseid, $userid  , self::C_join_time  );
	}
	function get_del_flag($courseid, $userid ){
		return $this->field_get_value_2( $courseid, $userid  , self::C_del_flag  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="courseid";
        $this->field_id2_name="userid";
        $this->field_table_name="db_weiyi.t_small_class_user";
  }

    public function field_get_value_2(  $courseid, $userid,$field_name ) {
        return parent::field_get_value_2(  $courseid, $userid,$field_name ) ;
    }

    public function field_get_list_2( $courseid,  $userid,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $courseid, $userid,  $set_field_arr ) {
        return parent::field_update_list_2( $courseid, $userid,  $set_field_arr );
    }
    public function row_delete_2(  $courseid ,$userid ) {
        return parent::row_delete_2( $courseid ,$userid );
    }


}
/*
  CREATE TABLE `t_small_class_user` (
  `courseid` int(10) unsigned NOT NULL COMMENT '课程id',
  `userid` int(10) unsigned NOT NULL COMMENT '用户id',
  `join_time` int(10) unsigned NOT NULL COMMENT '加入时间',
  `del_flag` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否退出小班课，０未退出１已退出',
  PRIMARY KEY (`courseid`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */
