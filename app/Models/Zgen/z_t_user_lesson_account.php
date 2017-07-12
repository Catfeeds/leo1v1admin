<?php
namespace App\Models\Zgen;
class z_t_user_lesson_account  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_user_lesson_account";


	/*int(10) unsigned */
	const C_lesson_account_id='lesson_account_id';

	/*int(10) unsigned */
	const C_userid='userid';

	/*int(10) unsigned */
	const C_add_time='add_time';

	/*char(255) */
	const C_course_name='course_name';

	/*int(10) */
	const C_total_money='total_money';

	/*int(10) */
	const C_left_money='left_money';

	/*int(10) */
	const C_lesson_1v1_price='lesson_1v1_price';

	/*int(10) unsigned */
	const C_courseid='courseid';
	function get_userid($lesson_account_id ){
		return $this->field_get_value( $lesson_account_id , self::C_userid );
	}
	function get_add_time($lesson_account_id ){
		return $this->field_get_value( $lesson_account_id , self::C_add_time );
	}
	function get_course_name($lesson_account_id ){
		return $this->field_get_value( $lesson_account_id , self::C_course_name );
	}
	function get_total_money($lesson_account_id ){
		return $this->field_get_value( $lesson_account_id , self::C_total_money );
	}
	function get_left_money($lesson_account_id ){
		return $this->field_get_value( $lesson_account_id , self::C_left_money );
	}
	function get_lesson_1v1_price($lesson_account_id ){
		return $this->field_get_value( $lesson_account_id , self::C_lesson_1v1_price );
	}
	function get_courseid($lesson_account_id ){
		return $this->field_get_value( $lesson_account_id , self::C_courseid );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="lesson_account_id";
        $this->field_table_name="db_weiyi.t_user_lesson_account";
  }
    public function field_get_list( $lesson_account_id, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $lesson_account_id, $set_field_arr) {
        return parent::field_update_list( $lesson_account_id, $set_field_arr);
    }


    public function field_get_value(  $lesson_account_id, $field_name ) {
        return parent::field_get_value( $lesson_account_id, $field_name);
    }

    public function row_delete(  $lesson_account_id) {
        return parent::row_delete( $lesson_account_id);
    }

}

/*
  CREATE TABLE `t_user_lesson_account` (
  `lesson_account_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `userid` int(10) unsigned NOT NULL,
  `add_time` int(10) unsigned NOT NULL,
  `course_name` char(255) NOT NULL COMMENT '课程名称',
  `total_money` int(10) NOT NULL COMMENT '总共金额 (分)',
  `left_money` int(10) NOT NULL COMMENT '剩余金额 (分)',
  `lesson_1v1_price` int(10) NOT NULL COMMENT '1对1课程价格',
  `courseid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`lesson_account_id`),
  KEY `userid` (`userid`,`add_time`),
  KEY `courseid` (`courseid`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8
 */
