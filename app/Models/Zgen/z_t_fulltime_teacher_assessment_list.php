<?php
namespace App\Models\Zgen;
class z_t_fulltime_teacher_assessment_list  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_fulltime_teacher_assessment_list";


	/*int(11) */
	const C_adminid='adminid';

	/*int(11) */
	const C_add_time='add_time';

	/*int(11) */
	const C_modify_time='modify_time';

	/*int(11) */
	const C_assess_time='assess_time';

	/*int(11) */
	const C_assess_adminid='assess_adminid';

	/*int(11) */
	const C_observe_law_score='observe_law_score';

	/*int(11) */
	const C_core_socialist_score='core_socialist_score';

	/*int(11) */
	const C_work_responsibility_score='work_responsibility_score';

	/*int(11) */
	const C_obey_leadership_score='obey_leadership_score';

	/*int(11) */
	const C_dedication_score='dedication_score';

	/*int(11) */
	const C_prepare_lesson_score='prepare_lesson_score';

	/*int(11) */
	const C_upload_handouts_score='upload_handouts_score';

	/*int(11) */
	const C_handout_writing_score='handout_writing_score';

	/*int(11) */
	const C_no_absences_score='no_absences_score';

	/*int(11) */
	const C_late_leave_score='late_leave_score';

	/*int(11) */
	const C_prepare_quality_score='prepare_quality_score';

	/*int(11) */
	const C_class_concent_score='class_concent_score';

	/*int(11) */
	const C_tea_attitude_score='tea_attitude_score';

	/*int(11) */
	const C_after_feedback_score='after_feedback_score';

	/*int(11) */
	const C_modify_homework_score='modify_homework_score';

	/*int(11) */
	const C_teamwork_positive_score='teamwork_positive_score';

	/*int(11) */
	const C_test_lesson_prepare_score='test_lesson_prepare_score';

	/*int(11) */
	const C_undertake_actively_score='undertake_actively_score';

	/*int(11) */
	const C_active_share_score='active_share_score';

	/*int(11) */
	const C_order_per_score='order_per_score';

	/*int(11) */
	const C_stu_num_score='stu_num_score';

	/*int(11) */
	const C_lesson_level_score='lesson_level_score';

	/*int(11) */
	const C_stu_lesson_total_score='stu_lesson_total_score';

	/*int(11) */
	const C_complaint_refund_score='complaint_refund_score';

	/*int(11) */
	const C_moral_education_score='moral_education_score';

	/*int(11) */
	const C_tea_score='tea_score';

	/*int(11) */
	const C_teach_research_score='teach_research_score';

	/*int(11) */
	const C_result_score='result_score';

	/*int(11) */
	const C_total_score='total_score';

	/*int(11) */
	const C_rate_stars='rate_stars';

	/*int(11) */
	const C_tea_score_master='tea_score_master';

	/*int(11) */
	const C_teach_research_score_master='teach_research_score_master';

	/*int(11) */
	const C_total_score_master='total_score_master';

	/*int(11) */
	const C_rate_stars_master='rate_stars_master';

	/*int(11) */
	const C_moral_education_score_master='moral_education_score_master';

	/*int(11) */
	const C_result_score_master='result_score_master';

	/*int(10) unsigned */
	const C_id='id';

	/*int(11) */
	const C_post='post';

	/*int(11) */
	const C_main_department='main_department';

	/*int(11) */
	const C_positive_type='positive_type';

	/*int(11) */
	const C_active_part_score='active_part_score';

	/*varchar(20) */
	const C_order_per='order_per';

	/*int(11) */
	const C_stu_num='stu_num';

	/*int(11) */
	const C_lesson_level='lesson_level';

	/*int(11) */
	const C_stu_lesson_total='stu_lesson_total';
	function get_adminid($id ){
		return $this->field_get_value( $id , self::C_adminid );
	}
	function get_add_time($id ){
		return $this->field_get_value( $id , self::C_add_time );
	}
	function get_modify_time($id ){
		return $this->field_get_value( $id , self::C_modify_time );
	}
	function get_assess_time($id ){
		return $this->field_get_value( $id , self::C_assess_time );
	}
	function get_assess_adminid($id ){
		return $this->field_get_value( $id , self::C_assess_adminid );
	}
	function get_observe_law_score($id ){
		return $this->field_get_value( $id , self::C_observe_law_score );
	}
	function get_core_socialist_score($id ){
		return $this->field_get_value( $id , self::C_core_socialist_score );
	}
	function get_work_responsibility_score($id ){
		return $this->field_get_value( $id , self::C_work_responsibility_score );
	}
	function get_obey_leadership_score($id ){
		return $this->field_get_value( $id , self::C_obey_leadership_score );
	}
	function get_dedication_score($id ){
		return $this->field_get_value( $id , self::C_dedication_score );
	}
	function get_prepare_lesson_score($id ){
		return $this->field_get_value( $id , self::C_prepare_lesson_score );
	}
	function get_upload_handouts_score($id ){
		return $this->field_get_value( $id , self::C_upload_handouts_score );
	}
	function get_handout_writing_score($id ){
		return $this->field_get_value( $id , self::C_handout_writing_score );
	}
	function get_no_absences_score($id ){
		return $this->field_get_value( $id , self::C_no_absences_score );
	}
	function get_late_leave_score($id ){
		return $this->field_get_value( $id , self::C_late_leave_score );
	}
	function get_prepare_quality_score($id ){
		return $this->field_get_value( $id , self::C_prepare_quality_score );
	}
	function get_class_concent_score($id ){
		return $this->field_get_value( $id , self::C_class_concent_score );
	}
	function get_tea_attitude_score($id ){
		return $this->field_get_value( $id , self::C_tea_attitude_score );
	}
	function get_after_feedback_score($id ){
		return $this->field_get_value( $id , self::C_after_feedback_score );
	}
	function get_modify_homework_score($id ){
		return $this->field_get_value( $id , self::C_modify_homework_score );
	}
	function get_teamwork_positive_score($id ){
		return $this->field_get_value( $id , self::C_teamwork_positive_score );
	}
	function get_test_lesson_prepare_score($id ){
		return $this->field_get_value( $id , self::C_test_lesson_prepare_score );
	}
	function get_undertake_actively_score($id ){
		return $this->field_get_value( $id , self::C_undertake_actively_score );
	}
	function get_active_share_score($id ){
		return $this->field_get_value( $id , self::C_active_share_score );
	}
	function get_order_per_score($id ){
		return $this->field_get_value( $id , self::C_order_per_score );
	}
	function get_stu_num_score($id ){
		return $this->field_get_value( $id , self::C_stu_num_score );
	}
	function get_lesson_level_score($id ){
		return $this->field_get_value( $id , self::C_lesson_level_score );
	}
	function get_stu_lesson_total_score($id ){
		return $this->field_get_value( $id , self::C_stu_lesson_total_score );
	}
	function get_complaint_refund_score($id ){
		return $this->field_get_value( $id , self::C_complaint_refund_score );
	}
	function get_moral_education_score($id ){
		return $this->field_get_value( $id , self::C_moral_education_score );
	}
	function get_tea_score($id ){
		return $this->field_get_value( $id , self::C_tea_score );
	}
	function get_teach_research_score($id ){
		return $this->field_get_value( $id , self::C_teach_research_score );
	}
	function get_result_score($id ){
		return $this->field_get_value( $id , self::C_result_score );
	}
	function get_total_score($id ){
		return $this->field_get_value( $id , self::C_total_score );
	}
	function get_rate_stars($id ){
		return $this->field_get_value( $id , self::C_rate_stars );
	}
	function get_tea_score_master($id ){
		return $this->field_get_value( $id , self::C_tea_score_master );
	}
	function get_teach_research_score_master($id ){
		return $this->field_get_value( $id , self::C_teach_research_score_master );
	}
	function get_total_score_master($id ){
		return $this->field_get_value( $id , self::C_total_score_master );
	}
	function get_rate_stars_master($id ){
		return $this->field_get_value( $id , self::C_rate_stars_master );
	}
	function get_moral_education_score_master($id ){
		return $this->field_get_value( $id , self::C_moral_education_score_master );
	}
	function get_result_score_master($id ){
		return $this->field_get_value( $id , self::C_result_score_master );
	}
	function get_post($id ){
		return $this->field_get_value( $id , self::C_post );
	}
	function get_main_department($id ){
		return $this->field_get_value( $id , self::C_main_department );
	}
	function get_positive_type($id ){
		return $this->field_get_value( $id , self::C_positive_type );
	}
	function get_active_part_score($id ){
		return $this->field_get_value( $id , self::C_active_part_score );
	}
	function get_order_per($id ){
		return $this->field_get_value( $id , self::C_order_per );
	}
	function get_stu_num($id ){
		return $this->field_get_value( $id , self::C_stu_num );
	}
	function get_lesson_level($id ){
		return $this->field_get_value( $id , self::C_lesson_level );
	}
	function get_stu_lesson_total($id ){
		return $this->field_get_value( $id , self::C_stu_lesson_total );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_fulltime_teacher_assessment_list";
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
  CREATE TABLE `t_fulltime_teacher_assessment_list` (
  `adminid` int(11) NOT NULL,
  `add_time` int(11) NOT NULL COMMENT '提交时间',
  `modify_time` int(11) NOT NULL COMMENT '修改时间',
  `assess_time` int(11) NOT NULL COMMENT '考评时间',
  `assess_adminid` int(11) NOT NULL COMMENT '考评人',
  `observe_law_score` int(11) NOT NULL COMMENT '遵纪守法得分',
  `core_socialist_score` int(11) NOT NULL COMMENT '价值观得分',
  `work_responsibility_score` int(11) NOT NULL COMMENT '工作责任得分',
  `obey_leadership_score` int(11) NOT NULL COMMENT '服从安排得分',
  `dedication_score` int(11) NOT NULL COMMENT '爱岗敬业得分',
  `prepare_lesson_score` int(11) NOT NULL COMMENT '备课得分',
  `upload_handouts_score` int(11) NOT NULL COMMENT '上传讲义得分',
  `handout_writing_score` int(11) NOT NULL COMMENT '讲义编写得分',
  `no_absences_score` int(11) NOT NULL COMMENT '遵照课表上课得分',
  `late_leave_score` int(11) NOT NULL COMMENT '不迟到早退得分',
  `prepare_quality_score` int(11) NOT NULL COMMENT '备课质量得分',
  `class_concent_score` int(11) NOT NULL COMMENT '上课专注得分',
  `tea_attitude_score` int(11) NOT NULL COMMENT '教学态度得分',
  `after_feedback_score` int(11) NOT NULL COMMENT '课后反馈得分',
  `modify_homework_score` int(11) NOT NULL COMMENT '修改作业得分',
  `teamwork_positive_score` int(11) NOT NULL COMMENT '配合试听得分',
  `test_lesson_prepare_score` int(11) NOT NULL COMMENT '试听备课得分',
  `undertake_actively_score` int(11) NOT NULL COMMENT '承担组长分配任务得分',
  `active_share_score` int(11) NOT NULL COMMENT '积极分享得分',
  `order_per_score` int(11) NOT NULL COMMENT '试听转化率得分',
  `stu_num_score` int(11) NOT NULL COMMENT '常规学生数得分',
  `lesson_level_score` int(11) NOT NULL COMMENT '家长评星得分',
  `stu_lesson_total_score` int(11) NOT NULL COMMENT '周课时数得分',
  `complaint_refund_score` int(11) NOT NULL COMMENT '投诉退费得分',
  `moral_education_score` int(11) NOT NULL COMMENT '德育自评得分',
  `tea_score` int(11) NOT NULL COMMENT '教学自评得分',
  `teach_research_score` int(11) NOT NULL COMMENT '教研自评得分',
  `result_score` int(11) NOT NULL COMMENT '成果自评得分',
  `total_score` int(11) NOT NULL COMMENT '自评总分得分',
  `rate_stars` int(11) NOT NULL COMMENT '自评星级',
  `tea_score_master` int(11) NOT NULL COMMENT '教学考评得分',
  `teach_research_score_master` int(11) NOT NULL COMMENT '教研考评得分',
  `total_score_master` int(11) NOT NULL COMMENT '考评总分得分',
  `rate_stars_master` int(11) NOT NULL COMMENT '考评星级',
  `moral_education_score_master` int(11) NOT NULL COMMENT '德育考评得分',
  `result_score_master` int(11) NOT NULL COMMENT '成果考评得分',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post` int(11) NOT NULL COMMENT '岗位',
  `main_department` int(11) NOT NULL COMMENT '部门',
  `positive_type` int(11) NOT NULL COMMENT '自评转正类型',
  `active_part_score` int(11) NOT NULL COMMENT '积极参与',
  `order_per` varchar(20) COLLATE latin1_bin NOT NULL COMMENT 'è½¬åŒ–çŽ‡',
  `stu_num` int(11) NOT NULL COMMENT '常规学生数',
  `lesson_level` int(11) NOT NULL COMMENT '家长评价星级',
  `stu_lesson_total` int(11) NOT NULL COMMENT '常规学生总课时数',
  PRIMARY KEY (`id`),
  KEY `add_time` (`add_time`),
  KEY `modify_time` (`modify_time`),
  KEY `assess_time` (`assess_time`),
  KEY `assess_adminid` (`assess_adminid`),
  KEY `adminid` (`adminid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
