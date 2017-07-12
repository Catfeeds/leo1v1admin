<?php
namespace App\Models\Zgen;
class z_t_tongji  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_tongji";


	/*int(10) unsigned */
	const C_id='id';

	/*int(11) */
	const C_log_date='log_date';

	/*double(8,2) */
	const C_new_course_count='new_course_count';

	/*double(8,2) */
	const C_old_course_count='old_course_count';

	/*int(11) */
	const C_new_lesson_count='new_lesson_count';

	/*int(11) */
	const C_old_lesson_count='old_lesson_count';

	/*int(11) */
	const C_test_lesson_count='test_lesson_count';

	/*int(11) */
	const C_money='money';

	/*int(11) */
	const C_real_money='real_money';

	/*int(11) */
	const C_test_free_count='test_free_count';

	/*int(11) */
	const C_test_money_count='test_money_count';

	/*int(11) */
	const C_test_money='test_money';

	/*int(11) */
	const C_new_count='new_count';

	/*int(11) */
	const C_next_count='next_count';

	/*int(11) */
	const C_old_count='old_count';

	/*int(11) */
	const C_stop_count='stop_count';

	/*int(11) */
	const C_finish_count='finish_count';

	/*int(11) */
	const C_teacher_count='teacher_count';
	function get_log_date($id ){
		return $this->field_get_value( $id , self::C_log_date );
	}
	function get_new_course_count($id ){
		return $this->field_get_value( $id , self::C_new_course_count );
	}
	function get_old_course_count($id ){
		return $this->field_get_value( $id , self::C_old_course_count );
	}
	function get_new_lesson_count($id ){
		return $this->field_get_value( $id , self::C_new_lesson_count );
	}
	function get_old_lesson_count($id ){
		return $this->field_get_value( $id , self::C_old_lesson_count );
	}
	function get_test_lesson_count($id ){
		return $this->field_get_value( $id , self::C_test_lesson_count );
	}
	function get_money($id ){
		return $this->field_get_value( $id , self::C_money );
	}
	function get_real_money($id ){
		return $this->field_get_value( $id , self::C_real_money );
	}
	function get_test_free_count($id ){
		return $this->field_get_value( $id , self::C_test_free_count );
	}
	function get_test_money_count($id ){
		return $this->field_get_value( $id , self::C_test_money_count );
	}
	function get_test_money($id ){
		return $this->field_get_value( $id , self::C_test_money );
	}
	function get_new_count($id ){
		return $this->field_get_value( $id , self::C_new_count );
	}
	function get_next_count($id ){
		return $this->field_get_value( $id , self::C_next_count );
	}
	function get_old_count($id ){
		return $this->field_get_value( $id , self::C_old_count );
	}
	function get_stop_count($id ){
		return $this->field_get_value( $id , self::C_stop_count );
	}
	function get_finish_count($id ){
		return $this->field_get_value( $id , self::C_finish_count );
	}
	function get_teacher_count($id ){
		return $this->field_get_value( $id , self::C_teacher_count );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi_admin.t_tongji";
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
  CREATE TABLE `t_tongji` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_date` int(11) NOT NULL COMMENT ' ',
  `new_course_count` double(8,2) NOT NULL COMMENT ' 	新增用户科目总数',
  `old_course_count` double(8,2) NOT NULL COMMENT '老用户科目总数',
  `new_lesson_count` int(11) NOT NULL COMMENT '新增上课次数',
  `old_lesson_count` int(11) NOT NULL COMMENT '老用户上课次数',
  `test_lesson_count` int(11) NOT NULL COMMENT '试听上课次数',
  `money` int(11) NOT NULL COMMENT '收费总额',
  `real_money` int(11) NOT NULL COMMENT '课时消耗累计总额',
  `test_free_count` int(11) NOT NULL COMMENT '试听人数',
  `test_money_count` int(11) NOT NULL COMMENT ' 	付费试听人数',
  `test_money` int(11) NOT NULL COMMENT ' 	付费试听总金额',
  `new_count` int(11) NOT NULL COMMENT '新增人数',
  `next_count` int(11) NOT NULL COMMENT '续费人数',
  `old_count` int(11) NOT NULL COMMENT '老用户数',
  `stop_count` int(11) NOT NULL COMMENT '停课人数',
  `finish_count` int(11) NOT NULL COMMENT '结课人数',
  `teacher_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `log_date` (`log_date`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
