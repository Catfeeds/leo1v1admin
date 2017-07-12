<?php
namespace App\Models\Zgen;
class z_t_homework_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_homework_info";


	/*int(10) unsigned */
	const C_courseid='courseid';

	/*int(10) unsigned */
	const C_lesson_num='lesson_num';

	/*tinyint(4) */
	const C_lesson_type='lesson_type';

	/*tinyint(4) */
	const C_work_type='work_type';

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

	/*varchar(32) */
	const C_score='score';

	/*timestamp */
	const C_last_modified_time='last_modified_time';

	/*int(10) unsigned */
	const C_lessonid='lessonid';

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

	/*tinyint(4) */
	const C_rs_upload_flag='rs_upload_flag';

	/*tinyint(4) */
	const C_rs_check_flag='rs_check_flag';

	/*tinyint(4) */
	const C_subject='subject';

	/*smallint(6) */
	const C_grade='grade';

	/*tinyint(4) */
	const C_work_source='work_source';

	/*varchar(1024) */
	const C_quest_bank_quests='quest_bank_quests';

	/*int(10) unsigned */
	const C_quest_bank_quests_work_status='quest_bank_quests_work_status';

	/*varchar(1024) */
	const C_pdf_check_list='pdf_check_list';

	/*int(11) */
	const C_pdf_question_count='pdf_question_count';
	function get_courseid($lessonid ){
		return $this->field_get_value( $lessonid , self::C_courseid );
	}
	function get_lesson_num($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_num );
	}
	function get_lesson_type($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_type );
	}
	function get_work_type($lessonid ){
		return $this->field_get_value( $lessonid , self::C_work_type );
	}
	function get_userid($lessonid ){
		return $this->field_get_value( $lessonid , self::C_userid );
	}
	function get_teacherid($lessonid ){
		return $this->field_get_value( $lessonid , self::C_teacherid );
	}
	function get_assistantid($lessonid ){
		return $this->field_get_value( $lessonid , self::C_assistantid );
	}
	function get_work_start($lessonid ){
		return $this->field_get_value( $lessonid , self::C_work_start );
	}
	function get_work_end($lessonid ){
		return $this->field_get_value( $lessonid , self::C_work_end );
	}
	function get_work_status($lessonid ){
		return $this->field_get_value( $lessonid , self::C_work_status );
	}
	function get_work_intro($lessonid ){
		return $this->field_get_value( $lessonid , self::C_work_intro );
	}
	function get_work_name($lessonid ){
		return $this->field_get_value( $lessonid , self::C_work_name );
	}
	function get_issue_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_issue_time );
	}
	function get_issue_url($lessonid ){
		return $this->field_get_value( $lessonid , self::C_issue_url );
	}
	function get_finish_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_finish_time );
	}
	function get_finish_url($lessonid ){
		return $this->field_get_value( $lessonid , self::C_finish_url );
	}
	function get_check_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_check_time );
	}
	function get_check_url($lessonid ){
		return $this->field_get_value( $lessonid , self::C_check_url );
	}
	function get_score($lessonid ){
		return $this->field_get_value( $lessonid , self::C_score );
	}
	function get_last_modified_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_last_modified_time );
	}
	function get_contact_teacher_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_contact_teacher_flag );
	}
	function get_contact_student_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_contact_student_flag );
	}
	function get_tea_research_url($lessonid ){
		return $this->field_get_value( $lessonid , self::C_tea_research_url );
	}
	function get_tea_research_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_tea_research_time );
	}
	function get_ass_research_url($lessonid ){
		return $this->field_get_value( $lessonid , self::C_ass_research_url );
	}
	function get_ass_research_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_ass_research_time );
	}
	function get_rs_upload_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_rs_upload_flag );
	}
	function get_rs_check_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_rs_check_flag );
	}
	function get_subject($lessonid ){
		return $this->field_get_value( $lessonid , self::C_subject );
	}
	function get_grade($lessonid ){
		return $this->field_get_value( $lessonid , self::C_grade );
	}
	function get_work_source($lessonid ){
		return $this->field_get_value( $lessonid , self::C_work_source );
	}
	function get_quest_bank_quests($lessonid ){
		return $this->field_get_value( $lessonid , self::C_quest_bank_quests );
	}
	function get_quest_bank_quests_work_status($lessonid ){
		return $this->field_get_value( $lessonid , self::C_quest_bank_quests_work_status );
	}
	function get_pdf_check_list($lessonid ){
		return $this->field_get_value( $lessonid , self::C_pdf_check_list );
	}
	function get_pdf_question_count($lessonid ){
		return $this->field_get_value( $lessonid , self::C_pdf_question_count );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="lessonid";
        $this->field_table_name="db_weiyi.t_homework_info";
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
  CREATE TABLE `t_homework_info` (
  `courseid` int(10) unsigned NOT NULL COMMENT '课程id',
  `lesson_num` int(10) unsigned NOT NULL COMMENT '第几次课',
  `lesson_type` tinyint(4) NOT NULL COMMENT '0正常课次，1预约课次',
  `work_type` tinyint(4) NOT NULL COMMENT '作业类型：0课后作业，1测评',
  `userid` int(10) unsigned NOT NULL COMMENT '学生id',
  `teacherid` int(10) unsigned NOT NULL COMMENT '教课老师id',
  `assistantid` int(10) unsigned NOT NULL COMMENT '助教id',
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
  `score` varchar(32) NOT NULL DEFAULT '' COMMENT '作业打分 目前分为  A  B 2 未完成',
  `last_modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后修改时间',
  `lessonid` int(10) unsigned NOT NULL COMMENT '课次id',
  `contact_teacher_flag` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否联系过老师 0 未联系 1 已联系',
  `contact_student_flag` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否联系过学生 0 未联系 1 已联系',
  `tea_research_url` varchar(100) NOT NULL DEFAULT '' COMMENT '教研老师点评后的作业地址',
  `tea_research_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '教研老师点评后批改时间',
  `ass_research_url` varchar(100) NOT NULL DEFAULT '' COMMENT '助教老师点评后的作业地址',
  `ass_research_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '教研老师点评后批改时间',
  `rs_upload_flag` tinyint(4) NOT NULL DEFAULT '0' COMMENT '作业上传处理flag 0未处理 1已处理',
  `rs_check_flag` tinyint(4) NOT NULL DEFAULT '0' COMMENT '作业批改处理flag 0未处理 1已处理',
  `subject` tinyint(4) NOT NULL DEFAULT '0' COMMENT '科目 0未设定 1语文 2数学 3英语 4化学 5物理 6生物 7政治 8历史 9地理',
  `grade` smallint(6) NOT NULL DEFAULT '0' COMMENT '科目 0未设定 1语文 2数学 3英语 4化学 5物理 6生物 7政治 8历史 9地理',
  `work_source` tinyint(4) NOT NULL DEFAULT '0' COMMENT '作业来源 1pdf 2quest 3 both',
  `quest_bank_quests` varchar(1024) NOT NULL DEFAULT '' COMMENT '当次作业的题库内容',
  `quest_bank_quests_work_status` int(10) unsigned DEFAULT '0',
  `pdf_check_list` varchar(1024) NOT NULL,
  `pdf_question_count` int(11) NOT NULL,
  PRIMARY KEY (`lessonid`),
  KEY `courseid_2` (`courseid`,`lesson_num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */
