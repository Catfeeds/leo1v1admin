<?php
namespace App\Models\Zgen;
class z_t_course_order  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_course_order";


	/*int(10) unsigned */
	const C_courseid='courseid';

	/*int(10) unsigned */
	const C_revisit_cnt='revisit_cnt';

	/*int(10) unsigned */
	const C_orderid='orderid';

	/*int(10) unsigned */
	const C_userid='userid';

	/*int(10) unsigned */
	const C_teacherid='teacherid';

	/*int(10) unsigned */
	const C_assistantid='assistantid';

	/*int(10) unsigned */
	const C_course_type='course_type';

	/*smallint(6) */
	const C_grade='grade';

	/*tinyint(4) */
	const C_subject='subject';

	/*int(10) unsigned */
	const C_course_start='course_start';

	/*int(10) unsigned */
	const C_course_end='course_end';

	/*int(10) unsigned */
	const C_lesson_total='lesson_total';

	/*int(10) unsigned */
	const C_lesson_left='lesson_left';

	/*varchar(300) */
	const C_requirement='requirement';

	/*tinyint(4) */
	const C_course_status='course_status';

	/*varchar(600) */
	const C_custom_content='custom_content';

	/*timestamp */
	const C_last_modified_time='last_modified_time';

	/*int(10) unsigned */
	const C_next_courseid='next_courseid';

	/*varchar(100) */
	const C_teacher_intro='teacher_intro';

	/*tinyint(4) */
	const C_intro_status='intro_status';

	/*varchar(100) */
	const C_current_server='current_server';

	/*tinyint(3) unsigned */
	const C_enter_type='enter_type';

	/*varchar(128) */
	const C_course_name='course_name';

	/*int(10) unsigned */
	const C_stu_total='stu_total';

	/*int(10) unsigned */
	const C_stu_current='stu_current';

	/*varchar(100) */
	const C_course_pic='course_pic';

	/*varchar(200) */
	const C_lesson_time='lesson_time';

	/*int(10) unsigned */
	const C_lesson_open='lesson_open';

	/*int(10) unsigned */
	const C_from_type='from_type';

	/*int(10) unsigned */
	const C_del_flag='del_flag';

	/*int(11) */
	const C_packageid='packageid';

	/*int(11) */
	const C_default_lesson_count='default_lesson_count';

	/*int(11) */
	const C_assigned_lesson_count='assigned_lesson_count';

	/*int(11) */
	const C_competition_flag='competition_flag';

	/*int(11) */
	const C_add_time='add_time';

	/*int(11) */
	const C_ass_from_test_lesson_id='ass_from_test_lesson_id';

	/*int(11) */
	const C_send_wx_flag='send_wx_flag';

	/*int(11) */
	const C_week_comment_num='week_comment_num';

	/*tinyint(4) */
	const C_lesson_grade_type='lesson_grade_type';

	/*tinyint(4) */
	const C_enable_video='enable_video';

	/*tinyint(4) */
	const C_is_kk_flag='is_kk_flag';
	function get_revisit_cnt($courseid ){
		return $this->field_get_value( $courseid , self::C_revisit_cnt );
	}
	function get_orderid($courseid ){
		return $this->field_get_value( $courseid , self::C_orderid );
	}
	function get_userid($courseid ){
		return $this->field_get_value( $courseid , self::C_userid );
	}
	function get_teacherid($courseid ){
		return $this->field_get_value( $courseid , self::C_teacherid );
	}
	function get_assistantid($courseid ){
		return $this->field_get_value( $courseid , self::C_assistantid );
	}
	function get_course_type($courseid ){
		return $this->field_get_value( $courseid , self::C_course_type );
	}
	function get_grade($courseid ){
		return $this->field_get_value( $courseid , self::C_grade );
	}
	function get_subject($courseid ){
		return $this->field_get_value( $courseid , self::C_subject );
	}
	function get_course_start($courseid ){
		return $this->field_get_value( $courseid , self::C_course_start );
	}
	function get_course_end($courseid ){
		return $this->field_get_value( $courseid , self::C_course_end );
	}
	function get_lesson_total($courseid ){
		return $this->field_get_value( $courseid , self::C_lesson_total );
	}
	function get_lesson_left($courseid ){
		return $this->field_get_value( $courseid , self::C_lesson_left );
	}
	function get_requirement($courseid ){
		return $this->field_get_value( $courseid , self::C_requirement );
	}
	function get_course_status($courseid ){
		return $this->field_get_value( $courseid , self::C_course_status );
	}
	function get_custom_content($courseid ){
		return $this->field_get_value( $courseid , self::C_custom_content );
	}
	function get_last_modified_time($courseid ){
		return $this->field_get_value( $courseid , self::C_last_modified_time );
	}
	function get_next_courseid($courseid ){
		return $this->field_get_value( $courseid , self::C_next_courseid );
	}
	function get_teacher_intro($courseid ){
		return $this->field_get_value( $courseid , self::C_teacher_intro );
	}
	function get_intro_status($courseid ){
		return $this->field_get_value( $courseid , self::C_intro_status );
	}
	function get_current_server($courseid ){
		return $this->field_get_value( $courseid , self::C_current_server );
	}
	function get_enter_type($courseid ){
		return $this->field_get_value( $courseid , self::C_enter_type );
	}
	function get_course_name($courseid ){
		return $this->field_get_value( $courseid , self::C_course_name );
	}
	function get_stu_total($courseid ){
		return $this->field_get_value( $courseid , self::C_stu_total );
	}
	function get_stu_current($courseid ){
		return $this->field_get_value( $courseid , self::C_stu_current );
	}
	function get_course_pic($courseid ){
		return $this->field_get_value( $courseid , self::C_course_pic );
	}
	function get_lesson_time($courseid ){
		return $this->field_get_value( $courseid , self::C_lesson_time );
	}
	function get_lesson_open($courseid ){
		return $this->field_get_value( $courseid , self::C_lesson_open );
	}
	function get_from_type($courseid ){
		return $this->field_get_value( $courseid , self::C_from_type );
	}
	function get_del_flag($courseid ){
		return $this->field_get_value( $courseid , self::C_del_flag );
	}
	function get_packageid($courseid ){
		return $this->field_get_value( $courseid , self::C_packageid );
	}
	function get_default_lesson_count($courseid ){
		return $this->field_get_value( $courseid , self::C_default_lesson_count );
	}
	function get_assigned_lesson_count($courseid ){
		return $this->field_get_value( $courseid , self::C_assigned_lesson_count );
	}
	function get_competition_flag($courseid ){
		return $this->field_get_value( $courseid , self::C_competition_flag );
	}
	function get_add_time($courseid ){
		return $this->field_get_value( $courseid , self::C_add_time );
	}
	function get_ass_from_test_lesson_id($courseid ){
		return $this->field_get_value( $courseid , self::C_ass_from_test_lesson_id );
	}
	function get_send_wx_flag($courseid ){
		return $this->field_get_value( $courseid , self::C_send_wx_flag );
	}
	function get_week_comment_num($courseid ){
		return $this->field_get_value( $courseid , self::C_week_comment_num );
	}
	function get_lesson_grade_type($courseid ){
		return $this->field_get_value( $courseid , self::C_lesson_grade_type );
	}
	function get_enable_video($courseid ){
		return $this->field_get_value( $courseid , self::C_enable_video );
	}
	function get_is_kk_flag($courseid ){
		return $this->field_get_value( $courseid , self::C_is_kk_flag );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="courseid";
        $this->field_table_name="db_weiyi.t_course_order";
  }
    public function field_get_list( $courseid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $courseid, $set_field_arr) {
        return parent::field_update_list( $courseid, $set_field_arr);
    }


    public function field_get_value(  $courseid, $field_name ) {
        return parent::field_get_value( $courseid, $field_name);
    }

    public function row_delete(  $courseid) {
        return parent::row_delete( $courseid);
    }

}

/*
  CREATE TABLE `t_course_order` (
  `courseid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '课程id',
  `revisit_cnt` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '当前课程回访次数',
  `orderid` int(10) unsigned NOT NULL COMMENT '订单id',
  `userid` int(10) unsigned NOT NULL COMMENT '学生id',
  `teacherid` int(10) unsigned NOT NULL COMMENT '老师id',
  `assistantid` int(10) unsigned NOT NULL COMMENT '助教id',
  `course_type` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '课程类型 0 常规 1 赠送 2 试听',
  `grade` smallint(6) NOT NULL DEFAULT '0' COMMENT '年级',
  `subject` tinyint(4) NOT NULL DEFAULT '0' COMMENT '科目',
  `course_start` int(10) unsigned NOT NULL COMMENT '课程有效开始时间',
  `course_end` int(10) unsigned NOT NULL COMMENT '课程有效结束时间',
  `lesson_total` int(10) unsigned NOT NULL COMMENT '课次总数',
  `lesson_left` int(10) unsigned NOT NULL COMMENT '剩余课次数',
  `requirement` varchar(300) NOT NULL DEFAULT '' COMMENT '排课需求',
  `course_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '学程状态（0 正常上课 1 正常结课 2 退费锁定 3 退费成功 4 课程切换 ）',
  `custom_content` varchar(600) NOT NULL COMMENT '老师针对学生所作出的定制化信息',
  `last_modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后修改时间',
  `next_courseid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下个课程id',
  `teacher_intro` varchar(100) NOT NULL DEFAULT '' COMMENT '课程老师简介',
  `intro_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '课程老师简介播放状态 0未播放 1 已经播放',
  `current_server` varchar(100) NOT NULL DEFAULT '' COMMENT '学生当前使用的服务器信息',
  `enter_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0 正常1v1课程 1 学生在表中有对应课程 2 学生表中无对应课程',
  `course_name` varchar(128) NOT NULL DEFAULT '' COMMENT '课程名称',
  `stu_total` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '小班课所允许的人数',
  `stu_current` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '小班课当前人数',
  `course_pic` varchar(100) NOT NULL DEFAULT '' COMMENT '问题列表',
  `lesson_time` varchar(200) NOT NULL DEFAULT '0' COMMENT '可能的上课时间',
  `lesson_open` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '开课时间',
  `from_type` int(10) unsigned NOT NULL COMMENT '0:课程包,1:按课时购买的',
  `del_flag` int(10) unsigned NOT NULL DEFAULT '0',
  `packageid` int(11) DEFAULT '0' COMMENT '课程包id',
  `default_lesson_count` int(11) NOT NULL COMMENT '每次课几课时',
  `assigned_lesson_count` int(11) NOT NULL COMMENT '待分配课时',
  `competition_flag` int(11) NOT NULL DEFAULT '0' COMMENT '竞赛标志 0 常规课,1竞赛课',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `ass_from_test_lesson_id` int(11) NOT NULL COMMENT '助教-来自哪节试听课',
  `send_wx_flag` int(11) NOT NULL COMMENT '签单微信通知',
  `week_comment_num` int(11) NOT NULL COMMENT '周评价次数 0 每节都评价 num>0 每周评价num节课',
  `lesson_grade_type` tinyint(4) NOT NULL COMMENT '本课程包课程年级的来源 0 学生自身年级 1 课程包年级',
  `enable_video` tinyint(4) NOT NULL COMMENT '课堂是否开启视频 0 不开启 1 开启',
  `is_kk_flag` tinyint(4) NOT NULL COMMENT '是否扩课产生的课程包',
  PRIMARY KEY (`courseid`),
  KEY `userid` (`userid`,`teacherid`,`assistantid`,`course_type`,`grade`,`subject`,`course_start`),
  KEY `ORDER_TYPE` (`orderid`,`course_type`),
  KEY `t_course_order_add_time_index` (`add_time`),
  KEY `t_course_order_ass_from_test_lesson_id_index` (`ass_from_test_lesson_id`)
) ENGINE=InnoDB AUTO_INCREMENT=956 DEFAULT CHARSET=utf8
 */
