<?php
namespace App\Models\Zgen;
class z_t_test_lesson_subject_require  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_test_lesson_subject_require";


	/*int(11) */
	const C_require_id='require_id';

	/*varchar(64) */
	const C_origin='origin';

	/*int(11) */
	const C_require_time='require_time';

	/*int(11) */
	const C_test_lesson_subject_id='test_lesson_subject_id';

	/*int(11) */
	const C_accept_flag='accept_flag';

	/*int(11) */
	const C_accept_adminid='accept_adminid';

	/*int(11) */
	const C_accept_time='accept_time';

	/*int(11) */
	const C_test_lesson_student_status='test_lesson_student_status';

	/*int(11) */
	const C_notify_lesson_day1='notify_lesson_day1';

	/*int(11) */
	const C_notify_lesson_day2='notify_lesson_day2';

	/*varchar(255) */
	const C_stu_lesson_content='stu_lesson_content';

	/*varchar(255) */
	const C_stu_lesson_status='stu_lesson_status';

	/*varchar(255) */
	const C_stu_study_status='stu_study_status';

	/*varchar(255) */
	const C_stu_advantages='stu_advantages';

	/*varchar(255) */
	const C_stu_disadvantages='stu_disadvantages';

	/*varchar(1024) */
	const C_stu_lesson_plan='stu_lesson_plan';

	/*varchar(1024) */
	const C_stu_teaching_direction='stu_teaching_direction';

	/*varchar(1024) */
	const C_stu_textbook_info='stu_textbook_info';

	/*varchar(1024) */
	const C_stu_teaching_aim='stu_teaching_aim';

	/*varchar(1024) */
	const C_stu_lesson_count='stu_lesson_count';

	/*varchar(4096) */
	const C_stu_advice='stu_advice';

	/*varchar(255) */
	const C_current_lessonid='current_lessonid';

	/*varchar(255) */
	const C_no_accept_reason='no_accept_reason';

	/*int(11) */
	const C_seller_require_change_type='seller_require_change_type';

	/*int(11) */
	const C_seller_require_change_time='seller_require_change_time';

	/*int(11) */
	const C_seller_require_change_flag='seller_require_change_flag';

	/*int(11) */
	const C_require_change_lesson_time='require_change_lesson_time';

	/*int(11) */
	const C_cur_require_adminid='cur_require_adminid';

	/*int(11) */
	const C_require_assign_adminid='require_assign_adminid';

	/*int(11) */
	const C_require_assign_time='require_assign_time';

	/*int(11) */
	const C_jw_test_lesson_status='jw_test_lesson_status';

	/*int(11) */
	const C_green_channel_teacherid='green_channel_teacherid';

	/*int(11) */
	const C_test_lesson_order_fail_flag='test_lesson_order_fail_flag';

	/*varchar(1024) */
	const C_test_lesson_order_fail_desc='test_lesson_order_fail_desc';

	/*int(11) */
	const C_test_lesson_order_fail_set_time='test_lesson_order_fail_set_time';

	/*int(11) */
	const C_grab_status='grab_status';

	/*int(11) */
	const C_is_green_flag='is_green_flag';

	/*int(11) */
	const C_limit_require_flag='limit_require_flag';

	/*int(11) */
	const C_limit_require_teacherid='limit_require_teacherid';

	/*int(11) */
	const C_limit_require_lesson_start='limit_require_lesson_start';

	/*int(11) */
	const C_limit_require_time='limit_require_time';

	/*int(11) */
	const C_limit_require_adminid='limit_require_adminid';

	/*int(11) */
	const C_limit_require_send_adminid='limit_require_send_adminid';

	/*int(11) */
	const C_limit_accept_flag='limit_accept_flag';

	/*int(11) */
	const C_limit_accept_time='limit_accept_time';

	/*varchar(500) */
	const C_limit_require_reason='limit_require_reason';

	/*int(11) */
	const C_curl_stu_request_test_lesson_time='curl_stu_request_test_lesson_time';

	/*int(11) */
	const C_test_stu_grade='test_stu_grade';

	/*text */
	const C_test_stu_request_test_lesson_demand='test_stu_request_test_lesson_demand';
	function get_origin($require_id ){
		return $this->field_get_value( $require_id , self::C_origin );
	}
	function get_require_time($require_id ){
		return $this->field_get_value( $require_id , self::C_require_time );
	}
	function get_test_lesson_subject_id($require_id ){
		return $this->field_get_value( $require_id , self::C_test_lesson_subject_id );
	}
	function get_accept_flag($require_id ){
		return $this->field_get_value( $require_id , self::C_accept_flag );
	}
	function get_accept_adminid($require_id ){
		return $this->field_get_value( $require_id , self::C_accept_adminid );
	}
	function get_accept_time($require_id ){
		return $this->field_get_value( $require_id , self::C_accept_time );
	}
	function get_test_lesson_student_status($require_id ){
		return $this->field_get_value( $require_id , self::C_test_lesson_student_status );
	}
	function get_notify_lesson_day1($require_id ){
		return $this->field_get_value( $require_id , self::C_notify_lesson_day1 );
	}
	function get_notify_lesson_day2($require_id ){
		return $this->field_get_value( $require_id , self::C_notify_lesson_day2 );
	}
	function get_stu_lesson_content($require_id ){
		return $this->field_get_value( $require_id , self::C_stu_lesson_content );
	}
	function get_stu_lesson_status($require_id ){
		return $this->field_get_value( $require_id , self::C_stu_lesson_status );
	}
	function get_stu_study_status($require_id ){
		return $this->field_get_value( $require_id , self::C_stu_study_status );
	}
	function get_stu_advantages($require_id ){
		return $this->field_get_value( $require_id , self::C_stu_advantages );
	}
	function get_stu_disadvantages($require_id ){
		return $this->field_get_value( $require_id , self::C_stu_disadvantages );
	}
	function get_stu_lesson_plan($require_id ){
		return $this->field_get_value( $require_id , self::C_stu_lesson_plan );
	}
	function get_stu_teaching_direction($require_id ){
		return $this->field_get_value( $require_id , self::C_stu_teaching_direction );
	}
	function get_stu_textbook_info($require_id ){
		return $this->field_get_value( $require_id , self::C_stu_textbook_info );
	}
	function get_stu_teaching_aim($require_id ){
		return $this->field_get_value( $require_id , self::C_stu_teaching_aim );
	}
	function get_stu_lesson_count($require_id ){
		return $this->field_get_value( $require_id , self::C_stu_lesson_count );
	}
	function get_stu_advice($require_id ){
		return $this->field_get_value( $require_id , self::C_stu_advice );
	}
	function get_current_lessonid($require_id ){
		return $this->field_get_value( $require_id , self::C_current_lessonid );
	}
	function get_no_accept_reason($require_id ){
		return $this->field_get_value( $require_id , self::C_no_accept_reason );
	}
	function get_seller_require_change_type($require_id ){
		return $this->field_get_value( $require_id , self::C_seller_require_change_type );
	}
	function get_seller_require_change_time($require_id ){
		return $this->field_get_value( $require_id , self::C_seller_require_change_time );
	}
	function get_seller_require_change_flag($require_id ){
		return $this->field_get_value( $require_id , self::C_seller_require_change_flag );
	}
	function get_require_change_lesson_time($require_id ){
		return $this->field_get_value( $require_id , self::C_require_change_lesson_time );
	}
	function get_cur_require_adminid($require_id ){
		return $this->field_get_value( $require_id , self::C_cur_require_adminid );
	}
	function get_require_assign_adminid($require_id ){
		return $this->field_get_value( $require_id , self::C_require_assign_adminid );
	}
	function get_require_assign_time($require_id ){
		return $this->field_get_value( $require_id , self::C_require_assign_time );
	}
	function get_jw_test_lesson_status($require_id ){
		return $this->field_get_value( $require_id , self::C_jw_test_lesson_status );
	}
	function get_green_channel_teacherid($require_id ){
		return $this->field_get_value( $require_id , self::C_green_channel_teacherid );
	}
	function get_test_lesson_order_fail_flag($require_id ){
		return $this->field_get_value( $require_id , self::C_test_lesson_order_fail_flag );
	}
	function get_test_lesson_order_fail_desc($require_id ){
		return $this->field_get_value( $require_id , self::C_test_lesson_order_fail_desc );
	}
	function get_test_lesson_order_fail_set_time($require_id ){
		return $this->field_get_value( $require_id , self::C_test_lesson_order_fail_set_time );
	}
	function get_grab_status($require_id ){
		return $this->field_get_value( $require_id , self::C_grab_status );
	}
	function get_is_green_flag($require_id ){
		return $this->field_get_value( $require_id , self::C_is_green_flag );
	}
	function get_limit_require_flag($require_id ){
		return $this->field_get_value( $require_id , self::C_limit_require_flag );
	}
	function get_limit_require_teacherid($require_id ){
		return $this->field_get_value( $require_id , self::C_limit_require_teacherid );
	}
	function get_limit_require_lesson_start($require_id ){
		return $this->field_get_value( $require_id , self::C_limit_require_lesson_start );
	}
	function get_limit_require_time($require_id ){
		return $this->field_get_value( $require_id , self::C_limit_require_time );
	}
	function get_limit_require_adminid($require_id ){
		return $this->field_get_value( $require_id , self::C_limit_require_adminid );
	}
	function get_limit_require_send_adminid($require_id ){
		return $this->field_get_value( $require_id , self::C_limit_require_send_adminid );
	}
	function get_limit_accept_flag($require_id ){
		return $this->field_get_value( $require_id , self::C_limit_accept_flag );
	}
	function get_limit_accept_time($require_id ){
		return $this->field_get_value( $require_id , self::C_limit_accept_time );
	}
	function get_limit_require_reason($require_id ){
		return $this->field_get_value( $require_id , self::C_limit_require_reason );
	}
	function get_curl_stu_request_test_lesson_time($require_id ){
		return $this->field_get_value( $require_id , self::C_curl_stu_request_test_lesson_time );
	}
	function get_test_stu_grade($require_id ){
		return $this->field_get_value( $require_id , self::C_test_stu_grade );
	}
	function get_test_stu_request_test_lesson_demand($require_id ){
		return $this->field_get_value( $require_id , self::C_test_stu_request_test_lesson_demand );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="require_id";
        $this->field_table_name="db_weiyi.t_test_lesson_subject_require";
  }
    public function field_get_list( $require_id, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $require_id, $set_field_arr) {
        return parent::field_update_list( $require_id, $set_field_arr);
    }


    public function field_get_value(  $require_id, $field_name ) {
        return parent::field_get_value( $require_id, $field_name);
    }

    public function row_delete(  $require_id) {
        return parent::row_delete( $require_id);
    }

}

/*
  CREATE TABLE `t_test_lesson_subject_require` (
  `require_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '申请id',
  `origin` varchar(64) COLLATE latin1_bin NOT NULL COMMENT '渠道',
  `require_time` int(11) NOT NULL COMMENT '申请时间',
  `test_lesson_subject_id` int(11) NOT NULL,
  `accept_flag` int(11) NOT NULL COMMENT ' 0:未设置, 1:接受, 2:驳回',
  `accept_adminid` int(11) NOT NULL,
  `accept_time` int(11) NOT NULL COMMENT '处理时间',
  `test_lesson_student_status` int(11) NOT NULL COMMENT ' 排课状态 ',
  `notify_lesson_day1` int(11) NOT NULL COMMENT '通知1',
  `notify_lesson_day2` int(11) NOT NULL COMMENT '通知2',
  `stu_lesson_content` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '本节课内容',
  `stu_lesson_status` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '学生课堂状态',
  `stu_study_status` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '学生吸收情况',
  `stu_advantages` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '学生优点（不要过分）',
  `stu_disadvantages` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '学生缺点',
  `stu_lesson_plan` varchar(1024) COLLATE latin1_bin NOT NULL COMMENT '培训计划（简述）',
  `stu_teaching_direction` varchar(1024) COLLATE latin1_bin NOT NULL COMMENT '教学方向',
  `stu_textbook_info` varchar(1024) COLLATE latin1_bin NOT NULL COMMENT '教材及内容',
  `stu_teaching_aim` varchar(1024) COLLATE latin1_bin NOT NULL COMMENT '教学目标',
  `stu_lesson_count` varchar(1024) COLLATE latin1_bin NOT NULL COMMENT '大致推荐课时数',
  `stu_advice` varchar(4096) COLLATE latin1_bin NOT NULL COMMENT '教学目标',
  `current_lessonid` varchar(255) COLLATE latin1_bin DEFAULT NULL COMMENT '当前lessonid',
  `no_accept_reason` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '不接受原因',
  `seller_require_change_type` int(11) NOT NULL COMMENT '销售请求类型',
  `seller_require_change_time` int(11) NOT NULL COMMENT '销售请求更换的时间',
  `seller_require_change_flag` int(11) NOT NULL COMMENT '销售请求进展 0:未请求 1:请求中 2:已完成',
  `require_change_lesson_time` int(11) NOT NULL COMMENT '更改课程目标时间',
  `cur_require_adminid` int(11) NOT NULL COMMENT '当前申请人',
  `require_assign_adminid` int(11) NOT NULL COMMENT '申请未排分配者',
  `require_assign_time` int(11) NOT NULL COMMENT '申请未排分配时间',
  `jw_test_lesson_status` int(11) NOT NULL COMMENT '教务排课状态 0 未设置 1 完成 2 挂起 3 退回',
  `green_channel_teacherid` int(11) NOT NULL COMMENT '绿色通道之销售确认的教师id',
  `test_lesson_order_fail_flag` int(11) NOT NULL COMMENT '签单失败分类',
  `test_lesson_order_fail_desc` varchar(1024) COLLATE latin1_bin NOT NULL COMMENT '签单失败说明',
  `test_lesson_order_fail_set_time` int(11) NOT NULL COMMENT '签单失败设置时间',
  `grab_status` int(11) NOT NULL COMMENT '抢单状态',
  `is_green_flag` int(11) NOT NULL COMMENT '绿色通道标识',
  `limit_require_flag` int(11) NOT NULL COMMENT '限课特殊申请标识',
  `limit_require_teacherid` int(11) NOT NULL COMMENT '限课特殊申请老师id',
  `limit_require_lesson_start` int(11) NOT NULL COMMENT '限课特殊申请上课时间',
  `limit_require_time` int(11) NOT NULL COMMENT '限课特殊申请时间',
  `limit_require_adminid` int(11) NOT NULL COMMENT '限课特殊申请人',
  `limit_require_send_adminid` int(11) NOT NULL COMMENT '限课特殊申请对象',
  `limit_accept_flag` int(11) NOT NULL COMMENT '销售主管接受/驳回',
  `limit_accept_time` int(11) NOT NULL COMMENT '销售主管接受/驳回 时间',
  `limit_require_reason` varchar(500) COLLATE latin1_bin NOT NULL COMMENT '限课特殊申请原因',
  `curl_stu_request_test_lesson_time` int(11) NOT NULL COMMENT '存放期望上课时间',
  `test_stu_grade` int(11) NOT NULL COMMENT '期望 试听课年级',
  `test_stu_request_test_lesson_demand` text COLLATE latin1_bin NOT NULL COMMENT '试听课期望需求',
  PRIMARY KEY (`require_id`),
  UNIQUE KEY `t_test_lesson_subject_require_current_lessonid_unique` (`current_lessonid`),
  KEY `t_test_lesson_subject_require_require_time_index` (`require_time`),
  KEY `t_test_lesson_subject_require_accept_time_index` (`accept_time`),
  KEY `t_test_lesson_subject_require_test_lesson_subject_id_index` (`test_lesson_subject_id`),
  KEY `t_test_lesson_subject_require_require_change_lesson_time_index` (`require_change_lesson_time`),
  KEY `t_test_lesson_subject_require_seller_require_change_time_index` (`seller_require_change_time`),
  KEY `t_test_lesson_subject_require_cur_require_adminid_index` (`cur_require_adminid`),
  KEY `fail_set_time` (`test_lesson_order_fail_set_time`),
  KEY `limit_accept_time` (`limit_accept_time`),
  KEY `curl_stu_request_test_lesson_time` (`curl_stu_request_test_lesson_time`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
