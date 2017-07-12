<?php
namespace App\Models\Zgen;
class z_t_user_lesson_account_log  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_user_lesson_account_log";


	/*int(10) unsigned */
	const C_id='id';

	/*int(10) unsigned */
	const C_lesson_account_id='lesson_account_id';

	/*int(10) unsigned */
	const C_add_time='add_time';

	/*int(10) */
	const C_reason='reason';

	/*int(10) */
	const C_modify_money='modify_money';

	/*int(10) */
	const C_left_money='left_money';

	/*int(10) */
	const C_lessonid='lessonid';

	/*varchar(4096) */
	const C_info='info';
	function get_lesson_account_id($id ){
		return $this->field_get_value( $id , self::C_lesson_account_id );
	}
	function get_add_time($id ){
		return $this->field_get_value( $id , self::C_add_time );
	}
	function get_reason($id ){
		return $this->field_get_value( $id , self::C_reason );
	}
	function get_modify_money($id ){
		return $this->field_get_value( $id , self::C_modify_money );
	}
	function get_left_money($id ){
		return $this->field_get_value( $id , self::C_left_money );
	}
	function get_lessonid($id ){
		return $this->field_get_value( $id , self::C_lessonid );
	}
	function get_info($id ){
		return $this->field_get_value( $id , self::C_info );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_user_lesson_account_log";
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
  CREATE TABLE `t_user_lesson_account_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `lesson_account_id` int(10) unsigned NOT NULL COMMENT '课程账户id',
  `add_time` int(10) unsigned NOT NULL,
  `reason` int(10) NOT NULL COMMENT '原因',
  `modify_money` int(10) NOT NULL COMMENT '修改金额(分)',
  `left_money` int(10) NOT NULL COMMENT '剩余金额 (分)',
  `lessonid` int(10) NOT NULL COMMENT '课程id',
  `info` varchar(4096) NOT NULL COMMENT '信息',
  PRIMARY KEY (`id`),
  KEY `lesson_account_id` (`lesson_account_id`,`add_time`)
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=utf8
 */
