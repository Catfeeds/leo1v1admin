<?php
namespace App\Models\Zgen;
class z_t_quiz_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_quiz_info";


	/*int(10) unsigned */
	const C_quizid='quizid';

	/*int(10) unsigned */
	const C_courseid='courseid';

	/*int(10) unsigned */
	const C_course_type='course_type';

	/*int(10) unsigned */
	const C_quiz_num='quiz_num';

	/*tinyint(4) */
	const C_quiz_type='quiz_type';

	/*int(10) unsigned */
	const C_userid='userid';

	/*int(10) unsigned */
	const C_teacherid='teacherid';

	/*int(10) unsigned */
	const C_assistantid='assistantid';

	/*int(10) unsigned */
	const C_work_start='work_start';

	/*int(10) unsigned */
	const C_work_end='work_end';

	/*tinyint(4) */
	const C_work_status='work_status';

	/*varchar(100) */
	const C_work_intro='work_intro';

	/*varchar(100) */
	const C_work_name='work_name';

	/*int(10) unsigned */
	const C_issue_time='issue_time';

	/*varchar(100) */
	const C_issue_url='issue_url';

	/*int(10) unsigned */
	const C_finish_time='finish_time';

	/*varchar(100) */
	const C_finish_url='finish_url';

	/*int(10) unsigned */
	const C_check_time='check_time';

	/*varchar(100) */
	const C_check_url='check_url';

	/*int(10) unsigned */
	const C_score='score';

	/*tinyint(4) */
	const C_del_flag='del_flag';

	/*timestamp */
	const C_last_modified_time='last_modified_time';

	/*tinyint(4) */
	const C_parent_confirm='parent_confirm';

	/*tinyint(4) */
	const C_contact_teacher_flag='contact_teacher_flag';

	/*tinyint(4) */
	const C_contact_student_flag='contact_student_flag';

	/*varchar(100) */
	const C_tea_research_url='tea_research_url';

	/*int(10) unsigned */
	const C_tea_research_time='tea_research_time';

	/*varchar(100) */
	const C_ass_research_url='ass_research_url';

	/*int(10) unsigned */
	const C_ass_research_time='ass_research_time';

	/*int(10) unsigned */
	const C_quiz_cost='quiz_cost';

	/*tinyint(4) */
	const C_rs_check_flag='rs_check_flag';

	/*tinyint(4) */
	const C_subject='subject';
	function get_courseid($quizid ){
		return $this->field_get_value( $quizid , self::C_courseid );
	}
	function get_course_type($quizid ){
		return $this->field_get_value( $quizid , self::C_course_type );
	}
	function get_quiz_num($quizid ){
		return $this->field_get_value( $quizid , self::C_quiz_num );
	}
	function get_quiz_type($quizid ){
		return $this->field_get_value( $quizid , self::C_quiz_type );
	}
	function get_userid($quizid ){
		return $this->field_get_value( $quizid , self::C_userid );
	}
	function get_teacherid($quizid ){
		return $this->field_get_value( $quizid , self::C_teacherid );
	}
	function get_assistantid($quizid ){
		return $this->field_get_value( $quizid , self::C_assistantid );
	}
	function get_work_start($quizid ){
		return $this->field_get_value( $quizid , self::C_work_start );
	}
	function get_work_end($quizid ){
		return $this->field_get_value( $quizid , self::C_work_end );
	}
	function get_work_status($quizid ){
		return $this->field_get_value( $quizid , self::C_work_status );
	}
	function get_work_intro($quizid ){
		return $this->field_get_value( $quizid , self::C_work_intro );
	}
	function get_work_name($quizid ){
		return $this->field_get_value( $quizid , self::C_work_name );
	}
	function get_issue_time($quizid ){
		return $this->field_get_value( $quizid , self::C_issue_time );
	}
	function get_issue_url($quizid ){
		return $this->field_get_value( $quizid , self::C_issue_url );
	}
	function get_finish_time($quizid ){
		return $this->field_get_value( $quizid , self::C_finish_time );
	}
	function get_finish_url($quizid ){
		return $this->field_get_value( $quizid , self::C_finish_url );
	}
	function get_check_time($quizid ){
		return $this->field_get_value( $quizid , self::C_check_time );
	}
	function get_check_url($quizid ){
		return $this->field_get_value( $quizid , self::C_check_url );
	}
	function get_score($quizid ){
		return $this->field_get_value( $quizid , self::C_score );
	}
	function get_del_flag($quizid ){
		return $this->field_get_value( $quizid , self::C_del_flag );
	}
	function get_last_modified_time($quizid ){
		return $this->field_get_value( $quizid , self::C_last_modified_time );
	}
	function get_parent_confirm($quizid ){
		return $this->field_get_value( $quizid , self::C_parent_confirm );
	}
	function get_contact_teacher_flag($quizid ){
		return $this->field_get_value( $quizid , self::C_contact_teacher_flag );
	}
	function get_contact_student_flag($quizid ){
		return $this->field_get_value( $quizid , self::C_contact_student_flag );
	}
	function get_tea_research_url($quizid ){
		return $this->field_get_value( $quizid , self::C_tea_research_url );
	}
	function get_tea_research_time($quizid ){
		return $this->field_get_value( $quizid , self::C_tea_research_time );
	}
	function get_ass_research_url($quizid ){
		return $this->field_get_value( $quizid , self::C_ass_research_url );
	}
	function get_ass_research_time($quizid ){
		return $this->field_get_value( $quizid , self::C_ass_research_time );
	}
	function get_quiz_cost($quizid ){
		return $this->field_get_value( $quizid , self::C_quiz_cost );
	}
	function get_rs_check_flag($quizid ){
		return $this->field_get_value( $quizid , self::C_rs_check_flag );
	}
	function get_subject($quizid ){
		return $this->field_get_value( $quizid , self::C_subject );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="quizid";
        $this->field_table_name="db_weiyi.t_quiz_info";
  }
    public function field_get_list( $quizid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $quizid, $set_field_arr) {
        return parent::field_update_list( $quizid, $set_field_arr);
    }


    public function field_get_value(  $quizid, $field_name ) {
        return parent::field_get_value( $quizid, $field_name);
    }

    public function row_delete(  $quizid) {
        return parent::row_delete( $quizid);
    }

}

/*
  CREATE TABLE `t_quiz_info` (
  `quizid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '测评id',
  `courseid` int(10) unsigned NOT NULL COMMENT '课程id',
  `course_type` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '课程类型 0 常规 1 赠送 2 试听',
  `quiz_num` int(10) unsigned NOT NULL COMMENT '第几次测评',
  `quiz_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '测评类型0普通，1期中考,2期末考',
  `userid` int(10) unsigned NOT NULL COMMENT '学生id',
  `teacherid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '教课老师id',
  `assistantid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '助教id',
  `work_start` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '测评开始时间',
  `work_end` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '测评结束时间',
  `work_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '作业状态（0未上传，1已上传，2学生已提交，3老师已批改, 4教研点评，5助教点评）',
  `work_intro` varchar(100) NOT NULL DEFAULT '' COMMENT '作业信息',
  `work_name` varchar(100) NOT NULL COMMENT '作业或测评名称',
  `issue_time` int(10) unsigned NOT NULL COMMENT '作业发布时间',
  `issue_url` varchar(100) NOT NULL COMMENT '发布的作业的地址',
  `finish_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '作业完成时间',
  `finish_url` varchar(100) NOT NULL DEFAULT '' COMMENT '完成的作业的地址',
  `check_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '作业批改时间',
  `check_url` varchar(100) NOT NULL DEFAULT '' COMMENT '批改后的作业地址',
  `score` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '作业打分',
  `del_flag` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否被删除 0 未删除 1 已经删除',
  `last_modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后修改时间',
  `parent_confirm` tinyint(4) NOT NULL DEFAULT '0' COMMENT '家长对本次测评进行确认',
  `contact_teacher_flag` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否联系过老师 0 未联系 1 已联系',
  `contact_student_flag` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否联系过学生 0 未联系 1 已联系',
  `tea_research_url` varchar(100) NOT NULL DEFAULT '' COMMENT '教研老师点评后的作业地址',
  `tea_research_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '教研老师点评后批改时间',
  `ass_research_url` varchar(100) NOT NULL DEFAULT '' COMMENT '助教老师点评后的作业地址',
  `ass_research_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '教研老师点评后批改时间',
  `quiz_cost` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '测评所用时间',
  `rs_check_flag` tinyint(4) NOT NULL DEFAULT '0' COMMENT '作业批改处理flag 0未处理 1已处理',
  `subject` tinyint(4) NOT NULL DEFAULT '0' COMMENT '科目 0未设定 1语文 2数学 3英语 4化学 5物理 6生物 7政治 8历史 9地理',
  PRIMARY KEY (`quizid`)
) ENGINE=InnoDB AUTO_INCREMENT=228 DEFAULT CHARSET=utf8
 */
