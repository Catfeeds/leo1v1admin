<?php
namespace App\Models\Zgen;
class z_t_open_lesson_user extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_open_lesson_user";


	/*int(10) unsigned */
	const C_lessonid='lessonid';

	/*int(10) unsigned */
	const C_userid='userid';

	/*int(10) unsigned */
	const C_join_time='join_time';
	function get_join_time($lessonid, $userid ){
		return $this->field_get_value_2( $lessonid, $userid  , self::C_join_time  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="lessonid";
        $this->field_id2_name="userid";
        $this->field_table_name="db_weiyi.t_open_lesson_user";
  }

    public function field_get_value_2(  $lessonid, $userid,$field_name ) {
        return parent::field_get_value_2(  $lessonid, $userid,$field_name ) ;
    }

    public function field_get_list_2( $lessonid,  $userid,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $lessonid, $userid,  $set_field_arr ) {
        return parent::field_update_list_2( $lessonid, $userid,  $set_field_arr );
    }
    public function row_delete_2(  $lessonid ,$userid ) {
        return parent::row_delete_2( $lessonid ,$userid );
    }


}
/*
  CREATE TABLE `t_open_lesson_user` (
  `lessonid` int(10) unsigned NOT NULL COMMENT '公开课id',
  `userid` int(10) unsigned NOT NULL COMMENT '用户id',
  `join_time` int(10) unsigned NOT NULL COMMENT '加入时间',
  PRIMARY KEY (`lessonid`,`userid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */
