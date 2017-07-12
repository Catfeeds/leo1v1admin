<?php
namespace App\Models\Zgen;
class z_t_teacher_freetime_for_week  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_teacher_freetime_for_week";


	/*int(10) unsigned */
	const C_teacherid='teacherid';

	/*tinyint(3) unsigned */
	const C_teacher_type='teacher_type';

	/*varchar(128) */
	const C_free_time='free_time';

	/*varchar(8192) */
	const C_common_lesson_config='common_lesson_config';

	/*varchar(2048) */
	const C_free_time_new='free_time_new';

	/*varchar(5000) */
	const C_common_week_free_time='common_week_free_time';
	function get_teacher_type($teacherid ){
		return $this->field_get_value( $teacherid , self::C_teacher_type );
	}
	function get_free_time($teacherid ){
		return $this->field_get_value( $teacherid , self::C_free_time );
	}
	function get_common_lesson_config($teacherid ){
		return $this->field_get_value( $teacherid , self::C_common_lesson_config );
	}
	function get_free_time_new($teacherid ){
		return $this->field_get_value( $teacherid , self::C_free_time_new );
	}
	function get_common_week_free_time($teacherid ){
		return $this->field_get_value( $teacherid , self::C_common_week_free_time );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="teacherid";
        $this->field_table_name="db_weiyi.t_teacher_freetime_for_week";
  }
    public function field_get_list( $teacherid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $teacherid, $set_field_arr) {
        return parent::field_update_list( $teacherid, $set_field_arr);
    }


    public function field_get_value(  $teacherid, $field_name ) {
        return parent::field_get_value( $teacherid, $field_name);
    }

    public function row_delete(  $teacherid) {
        return parent::row_delete( $teacherid);
    }

}

/*
  CREATE TABLE `t_teacher_freetime_for_week` (
  `teacherid` int(10) unsigned NOT NULL COMMENT '老师或助教的id',
  `teacher_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '老师或助教 0 老师 1助教',
  `free_time` varchar(128) NOT NULL DEFAULT '0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0' COMMENT '>空闲时间字符串 用逗号分割 0是无空闲 1是有空闲',
  `common_lesson_config` varchar(8192) NOT NULL COMMENT '常规课表 :json:[{ start:1-10:10,length:90,userid:60001  }..]',
  `free_time_new` varchar(2048) NOT NULL COMMENT '新版空闲时间',
  `common_week_free_time` varchar(5000) NOT NULL COMMENT '教师常规一周空闲时间',
  PRIMARY KEY (`teacherid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */
