<?php
namespace App\Models\Zgen;
class z_t_small_lesson_info extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_small_lesson_info";


	/*int(10) unsigned */
	const C_lessonid='lessonid';

	/*int(10) unsigned */
	const C_userid='userid';

	/*varchar(2048) */
	const C_quest_info='quest_info';

	/*int(10) unsigned */
	const C_attend_time='attend_time';

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

	/*varchar(100) */
	const C_tea_research_url='tea_research_url';

	/*int(10) unsigned */
	const C_tea_research_time='tea_research_time';

	/*varchar(100) */
	const C_ass_research_url='ass_research_url';

	/*int(10) unsigned */
	const C_ass_research_time='ass_research_time';

	/*varchar(32) */
	const C_score='score';

	/*int(10) unsigned */
	const C_work_status='work_status';

	/*varchar(1024) */
	const C_quest_bank_quests='quest_bank_quests';

	/*tinyint(4) */
	const C_work_source='work_source';

	/*tinyint(4) */
	const C_contact_teacher_flag='contact_teacher_flag';

	/*tinyint(4) */
	const C_contact_student_flag='contact_student_flag';

	/*int(10) unsigned */
	const C_quest_bank_quests_work_status='quest_bank_quests_work_status';

	/*varchar(1024) */
	const C_pdf_check_list='pdf_check_list';

	/*int(11) */
	const C_pdf_question_count='pdf_question_count';
	function get_quest_info($lessonid, $userid ){
		return $this->field_get_value_2( $lessonid, $userid  , self::C_quest_info  );
	}
	function get_attend_time($lessonid, $userid ){
		return $this->field_get_value_2( $lessonid, $userid  , self::C_attend_time  );
	}
	function get_issue_time($lessonid, $userid ){
		return $this->field_get_value_2( $lessonid, $userid  , self::C_issue_time  );
	}
	function get_issue_url($lessonid, $userid ){
		return $this->field_get_value_2( $lessonid, $userid  , self::C_issue_url  );
	}
	function get_finish_time($lessonid, $userid ){
		return $this->field_get_value_2( $lessonid, $userid  , self::C_finish_time  );
	}
	function get_finish_url($lessonid, $userid ){
		return $this->field_get_value_2( $lessonid, $userid  , self::C_finish_url  );
	}
	function get_check_time($lessonid, $userid ){
		return $this->field_get_value_2( $lessonid, $userid  , self::C_check_time  );
	}
	function get_check_url($lessonid, $userid ){
		return $this->field_get_value_2( $lessonid, $userid  , self::C_check_url  );
	}
	function get_tea_research_url($lessonid, $userid ){
		return $this->field_get_value_2( $lessonid, $userid  , self::C_tea_research_url  );
	}
	function get_tea_research_time($lessonid, $userid ){
		return $this->field_get_value_2( $lessonid, $userid  , self::C_tea_research_time  );
	}
	function get_ass_research_url($lessonid, $userid ){
		return $this->field_get_value_2( $lessonid, $userid  , self::C_ass_research_url  );
	}
	function get_ass_research_time($lessonid, $userid ){
		return $this->field_get_value_2( $lessonid, $userid  , self::C_ass_research_time  );
	}
	function get_score($lessonid, $userid ){
		return $this->field_get_value_2( $lessonid, $userid  , self::C_score  );
	}
	function get_work_status($lessonid, $userid ){
		return $this->field_get_value_2( $lessonid, $userid  , self::C_work_status  );
	}
	function get_quest_bank_quests($lessonid, $userid ){
		return $this->field_get_value_2( $lessonid, $userid  , self::C_quest_bank_quests  );
	}
	function get_work_source($lessonid, $userid ){
		return $this->field_get_value_2( $lessonid, $userid  , self::C_work_source  );
	}
	function get_contact_teacher_flag($lessonid, $userid ){
		return $this->field_get_value_2( $lessonid, $userid  , self::C_contact_teacher_flag  );
	}
	function get_contact_student_flag($lessonid, $userid ){
		return $this->field_get_value_2( $lessonid, $userid  , self::C_contact_student_flag  );
	}
	function get_quest_bank_quests_work_status($lessonid, $userid ){
		return $this->field_get_value_2( $lessonid, $userid  , self::C_quest_bank_quests_work_status  );
	}
	function get_pdf_check_list($lessonid, $userid ){
		return $this->field_get_value_2( $lessonid, $userid  , self::C_pdf_check_list  );
	}
	function get_pdf_question_count($lessonid, $userid ){
		return $this->field_get_value_2( $lessonid, $userid  , self::C_pdf_question_count  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="lessonid";
        $this->field_id2_name="userid";
        $this->field_table_name="db_weiyi.t_small_lesson_info";
  }

    public function field_get_value_2(  $lessonid, $userid,$field_name ) {
        return parent::field_get_value_2(  $lessonid, $userid,$field_name ) ;
    }

    public function field_get_list_2( $lessonid,  $userid,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $lessonid, $userid,  $set_field_arr ) {
        return parent::field_update_list_2( $lessonid, $userid,  $set_field_arr );
    }
    public function row_delete_2(  $lessonid ,$userid ) {
        return parent::row_delete_2( $lessonid ,$userid );
    }


}
/*
  CREATE TABLE `t_small_lesson_info` (
  `lessonid` int(10) unsigned NOT NULL COMMENT '课次id',
  `userid` int(10) unsigned NOT NULL COMMENT '用户id',
  `quest_info` varchar(2048) NOT NULL COMMENT '回答问题的内容， qid1|ans1|result1,qid2|ans2|result2,',
  `attend_time` int(10) unsigned NOT NULL COMMENT '学生进入课堂时间',
  `issue_time` int(10) unsigned NOT NULL,
  `issue_url` varchar(100) NOT NULL,
  `finish_time` int(10) unsigned NOT NULL,
  `finish_url` varchar(100) NOT NULL,
  `check_time` int(10) unsigned NOT NULL,
  `check_url` varchar(100) NOT NULL,
  `tea_research_url` varchar(100) NOT NULL,
  `tea_research_time` int(10) unsigned NOT NULL,
  `ass_research_url` varchar(100) NOT NULL,
  `ass_research_time` int(10) unsigned NOT NULL,
  `score` varchar(32) NOT NULL,
  `work_status` int(10) unsigned NOT NULL,
  `quest_bank_quests` varchar(1024) NOT NULL DEFAULT '0' COMMENT '当次作业的题库内容',
  `work_source` tinyint(4) NOT NULL DEFAULT '0' COMMENT '作业来源 1pdf 2quest 3 both',
  `contact_teacher_flag` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否联系过老师 0 未联系 1 已联系',
  `contact_student_flag` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否联系过学生 0 未联系 1 已联系',
  `quest_bank_quests_work_status` int(10) unsigned DEFAULT NULL,
  `pdf_check_list` varchar(1024) NOT NULL,
  `pdf_question_count` int(11) NOT NULL,
  PRIMARY KEY (`lessonid`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='小班课信息'
 */
