<?php
namespace App\Models\Zgen;
class z_t_test_lesson_subject_sub_list  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_test_lesson_subject_sub_list";


	/*int(11) */
	const C_lessonid='lessonid';

	/*int(11) */
	const C_require_id='require_id';

	/*int(11) */
	const C_set_lesson_adminid='set_lesson_adminid';

	/*int(11) */
	const C_set_lesson_time='set_lesson_time';

	/*int(11) */
	const C_confirm_adminid='confirm_adminid';

	/*int(11) */
	const C_confirm_time='confirm_time';

	/*int(11) */
	const C_success_flag='success_flag';

	/*int(11) */
	const C_fail_greater_4_hour_flag='fail_greater_4_hour_flag';

	/*int(11) */
	const C_test_lesson_fail_flag='test_lesson_fail_flag';

	/*varchar(255) */
	const C_fail_reason='fail_reason';

	/*int(11) */
	const C_orderid='orderid';

	/*int(11) */
	const C_parent_confirm_time='parent_confirm_time';

	/*int(11) */
	const C_seller_require_flag='seller_require_flag';

	/*int(11) */
	const C_call_before_time='call_before_time';

	/*int(11) */
	const C_call_end_time='call_end_time';
	function get_require_id($lessonid ){
		return $this->field_get_value( $lessonid , self::C_require_id );
	}
	function get_set_lesson_adminid($lessonid ){
		return $this->field_get_value( $lessonid , self::C_set_lesson_adminid );
	}
	function get_set_lesson_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_set_lesson_time );
	}
	function get_confirm_adminid($lessonid ){
		return $this->field_get_value( $lessonid , self::C_confirm_adminid );
	}
	function get_confirm_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_confirm_time );
	}
	function get_success_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_success_flag );
	}
	function get_fail_greater_4_hour_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_fail_greater_4_hour_flag );
	}
	function get_test_lesson_fail_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_test_lesson_fail_flag );
	}
	function get_fail_reason($lessonid ){
		return $this->field_get_value( $lessonid , self::C_fail_reason );
	}
	function get_orderid($lessonid ){
		return $this->field_get_value( $lessonid , self::C_orderid );
	}
	function get_parent_confirm_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_parent_confirm_time );
	}
	function get_seller_require_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_seller_require_flag );
	}
	function get_call_before_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_call_before_time );
	}
	function get_call_end_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_call_end_time );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="lessonid";
        $this->field_table_name="db_weiyi.t_test_lesson_subject_sub_list";
  }
    public function field_get_list( $lessonid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $lessonid, $set_field_arr) {
        return parent::field_update_list( $lessonid, $set_field_arr);
    }


    public function field_get_value(  $lessonid, $field_name ) {
        return parent::field_get_value( $lessonid, $field_name);
    }

    public function row_delete(  $lessonid) {
        return parent::row_delete( $lessonid);
    }

}

/*
  CREATE TABLE `t_test_lesson_subject_sub_list` (
  `lessonid` int(11) NOT NULL,
  `require_id` int(11) NOT NULL COMMENT '申请线索id',
  `set_lesson_adminid` int(11) NOT NULL COMMENT '排课人',
  `set_lesson_time` int(11) NOT NULL COMMENT '排课时间',
  `confirm_adminid` int(11) NOT NULL COMMENT '确认人',
  `confirm_time` int(11) NOT NULL COMMENT '确认时间',
  `success_flag` int(11) NOT NULL COMMENT '成功标识:0: 未设置 ,1:成功, 2失败',
  `fail_greater_4_hour_flag` int(11) NOT NULL COMMENT ' 是否上课前4小时取消 ',
  `test_lesson_fail_flag` int(11) NOT NULL COMMENT ' 1:[付] 学生未到,  2:[付] 学生设备网络出错,  3:[付]其它, 100:[不付] 课程取消,   101:[不付] 老师未到, 102:[不付] 老师原因, 103:[不付] 换时间 104:[不付] 换老师,105,排课出错   130:[不付] 其他 ',
  `fail_reason` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '出错原因',
  `orderid` int(11) NOT NULL COMMENT '订单id',
  `parent_confirm_time` int(11) NOT NULL COMMENT '家长确认时间',
  `seller_require_flag` int(11) NOT NULL COMMENT '是否CC要求',
  `call_before_time` int(11) NOT NULL COMMENT '试听课课前回访',
  `call_end_time` int(11) NOT NULL COMMENT '试听课课后回访',
  PRIMARY KEY (`lessonid`),
  KEY `t_test_lesson_subject_sub_list_set_lesson_time_index` (`set_lesson_time`),
  KEY `t_test_lesson_subject_sub_list_require_id_index` (`require_id`),
  KEY `t_test_lesson_subject_sub_list_orderid_index` (`orderid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
