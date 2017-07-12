<?php
namespace App\Models\Zgen;
class z_t_train_lesson_user extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_train_lesson_user";


	/*int(11) */
	const C_lessonid='lessonid';

	/*int(11) */
	const C_add_time='add_time';

	/*int(11) */
	const C_userid='userid';

	/*varchar(100) */
	const C_score='score';
	function get_lessonid($userid, $add_time ){
		return $this->field_get_value_2( $userid, $add_time  , self::C_lessonid  );
	}
	function get_score($userid, $add_time ){
		return $this->field_get_value_2( $userid, $add_time  , self::C_score  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="userid";
        $this->field_id2_name="add_time";
        $this->field_table_name="db_weiyi.t_train_lesson_user";
  }

    public function field_get_value_2(  $userid, $add_time,$field_name ) {
        return parent::field_get_value_2(  $userid, $add_time,$field_name ) ;
    }

    public function field_get_list_2( $userid,  $add_time,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $userid, $add_time,  $set_field_arr ) {
        return parent::field_update_list_2( $userid, $add_time,  $set_field_arr );
    }
    public function row_delete_2(  $userid ,$add_time ) {
        return parent::row_delete_2( $userid ,$add_time );
    }


}
/*
  CREATE TABLE `t_train_lesson_user` (
  `lessonid` int(11) NOT NULL COMMENT '培训课程id',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `userid` int(11) NOT NULL COMMENT '参与者id',
  `score` varchar(100) COLLATE latin1_bin NOT NULL COMMENT '老师测评分数',
  PRIMARY KEY (`lessonid`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
