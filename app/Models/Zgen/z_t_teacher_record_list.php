<?php
namespace App\Models\Zgen;
class z_t_teacher_record_list  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_teacher_record_list";


	/*int(11) */
	const C_teacherid='teacherid';

	/*int(11) */
	const C_type='type';

	/*varchar(5000) */
	const C_record_info='record_info';

	/*int(11) */
	const C_add_time='add_time';

	/*varchar(32) */
	const C_acc='acc';

	/*varchar(20) */
	const C_record_score='record_score';

	/*varchar(255) */
	const C_courseware_flag='courseware_flag';

	/*int(11) */
	const C_courseware_flag_score='courseware_flag_score';

	/*varchar(255) */
	const C_lesson_preparation_content='lesson_preparation_content';

	/*int(11) */
	const C_lesson_preparation_content_score='lesson_preparation_content_score';

	/*varchar(255) */
	const C_courseware_quality='courseware_quality';

	/*int(11) */
	const C_courseware_quality_score='courseware_quality_score';

	/*varchar(255) */
	const C_tea_process_design='tea_process_design';

	/*int(11) */
	const C_tea_process_design_score='tea_process_design_score';

	/*varchar(255) */
	const C_class_atm='class_atm';

	/*int(11) */
	const C_class_atm_score='class_atm_score';

	/*varchar(255) */
	const C_tea_method='tea_method';

	/*int(11) */
	const C_tea_method_score='tea_method_score';

	/*varchar(255) */
	const C_knw_point='knw_point';

	/*int(11) */
	const C_knw_point_score='knw_point_score';

	/*varchar(255) */
	const C_dif_point='dif_point';

	/*int(11) */
	const C_dif_point_score='dif_point_score';

	/*varchar(255) */
	const C_teacher_blackboard_writing='teacher_blackboard_writing';

	/*int(11) */
	const C_teacher_blackboard_writing_score='teacher_blackboard_writing_score';

	/*varchar(255) */
	const C_tea_rhythm='tea_rhythm';

	/*int(11) */
	const C_tea_rhythm_score='tea_rhythm_score';

	/*varchar(255) */
	const C_content_fam_degree='content_fam_degree';

	/*int(11) */
	const C_content_fam_degree_score='content_fam_degree_score';

	/*varchar(255) */
	const C_answer_question_cre='answer_question_cre';

	/*int(11) */
	const C_answer_question_cre_score='answer_question_cre_score';

	/*varchar(255) */
	const C_language_performance='language_performance';

	/*int(11) */
	const C_language_performance_score='language_performance_score';

	/*varchar(255) */
	const C_tea_attitude='tea_attitude';

	/*int(11) */
	const C_tea_attitude_score='tea_attitude_score';

	/*varchar(255) */
	const C_tea_concentration='tea_concentration';

	/*int(11) */
	const C_tea_concentration_score='tea_concentration_score';

	/*varchar(255) */
	const C_tea_accident='tea_accident';

	/*int(11) */
	const C_tea_accident_score='tea_accident_score';

	/*varchar(255) */
	const C_tea_operation='tea_operation';

	/*int(11) */
	const C_tea_operation_score='tea_operation_score';

	/*varchar(255) */
	const C_tea_environment='tea_environment';

	/*int(11) */
	const C_tea_environment_score='tea_environment_score';

	/*varchar(255) */
	const C_class_abnormality='class_abnormality';

	/*int(11) */
	const C_class_abnormality_score='class_abnormality_score';

	/*varchar(5000) */
	const C_record_monitor_class='record_monitor_class';

	/*varchar(255) */
	const C_record_rank='record_rank';

	/*varchar(255) */
	const C_record_lesson_list='record_lesson_list';

	/*int(11) */
	const C_limit_plan_lesson_type='limit_plan_lesson_type';

	/*int(11) */
	const C_is_freeze='is_freeze';

	/*int(11) */
	const C_class_will_type='class_will_type';

	/*int(11) */
	const C_class_will_sub_type='class_will_sub_type';

	/*int(11) */
	const C_recover_class_time='recover_class_time';

	/*int(11) */
	const C_limit_week_lesson_num_new='limit_week_lesson_num_new';

	/*int(11) */
	const C_limit_week_lesson_num_old='limit_week_lesson_num_old';

	/*int(11) */
	const C_seller_require_flag='seller_require_flag';

	/*int(11) */
	const C_is_freeze_old='is_freeze_old';

	/*int(11) */
	const C_limit_plan_lesson_type_old='limit_plan_lesson_type_old';

	/*int(11) */
	const C_grade_range='grade_range';

	/*int(11) */
	const C_no_tea_related_score='no_tea_related_score';

	/*tinyint(4) */
	const C_trial_train_status='trial_train_status';

	/*int(10) unsigned */
	const C_id='id';

	/*int(11) */
	const C_train_lessonid='train_lessonid';

	/*varchar(64) */
	const C_current_acc='current_acc';

	/*varchar(16) */
	const C_phone_spare='phone_spare';
	function get_teacherid($id ){
		return $this->field_get_value( $id , self::C_teacherid );
	}
	function get_type($id ){
		return $this->field_get_value( $id , self::C_type );
	}
	function get_record_info($id ){
		return $this->field_get_value( $id , self::C_record_info );
	}
	function get_add_time($id ){
		return $this->field_get_value( $id , self::C_add_time );
	}
	function get_acc($id ){
		return $this->field_get_value( $id , self::C_acc );
	}
	function get_record_score($id ){
		return $this->field_get_value( $id , self::C_record_score );
	}
	function get_courseware_flag($id ){
		return $this->field_get_value( $id , self::C_courseware_flag );
	}
	function get_courseware_flag_score($id ){
		return $this->field_get_value( $id , self::C_courseware_flag_score );
	}
	function get_lesson_preparation_content($id ){
		return $this->field_get_value( $id , self::C_lesson_preparation_content );
	}
	function get_lesson_preparation_content_score($id ){
		return $this->field_get_value( $id , self::C_lesson_preparation_content_score );
	}
	function get_courseware_quality($id ){
		return $this->field_get_value( $id , self::C_courseware_quality );
	}
	function get_courseware_quality_score($id ){
		return $this->field_get_value( $id , self::C_courseware_quality_score );
	}
	function get_tea_process_design($id ){
		return $this->field_get_value( $id , self::C_tea_process_design );
	}
	function get_tea_process_design_score($id ){
		return $this->field_get_value( $id , self::C_tea_process_design_score );
	}
	function get_class_atm($id ){
		return $this->field_get_value( $id , self::C_class_atm );
	}
	function get_class_atm_score($id ){
		return $this->field_get_value( $id , self::C_class_atm_score );
	}
	function get_tea_method($id ){
		return $this->field_get_value( $id , self::C_tea_method );
	}
	function get_tea_method_score($id ){
		return $this->field_get_value( $id , self::C_tea_method_score );
	}
	function get_knw_point($id ){
		return $this->field_get_value( $id , self::C_knw_point );
	}
	function get_knw_point_score($id ){
		return $this->field_get_value( $id , self::C_knw_point_score );
	}
	function get_dif_point($id ){
		return $this->field_get_value( $id , self::C_dif_point );
	}
	function get_dif_point_score($id ){
		return $this->field_get_value( $id , self::C_dif_point_score );
	}
	function get_teacher_blackboard_writing($id ){
		return $this->field_get_value( $id , self::C_teacher_blackboard_writing );
	}
	function get_teacher_blackboard_writing_score($id ){
		return $this->field_get_value( $id , self::C_teacher_blackboard_writing_score );
	}
	function get_tea_rhythm($id ){
		return $this->field_get_value( $id , self::C_tea_rhythm );
	}
	function get_tea_rhythm_score($id ){
		return $this->field_get_value( $id , self::C_tea_rhythm_score );
	}
	function get_content_fam_degree($id ){
		return $this->field_get_value( $id , self::C_content_fam_degree );
	}
	function get_content_fam_degree_score($id ){
		return $this->field_get_value( $id , self::C_content_fam_degree_score );
	}
	function get_answer_question_cre($id ){
		return $this->field_get_value( $id , self::C_answer_question_cre );
	}
	function get_answer_question_cre_score($id ){
		return $this->field_get_value( $id , self::C_answer_question_cre_score );
	}
	function get_language_performance($id ){
		return $this->field_get_value( $id , self::C_language_performance );
	}
	function get_language_performance_score($id ){
		return $this->field_get_value( $id , self::C_language_performance_score );
	}
	function get_tea_attitude($id ){
		return $this->field_get_value( $id , self::C_tea_attitude );
	}
	function get_tea_attitude_score($id ){
		return $this->field_get_value( $id , self::C_tea_attitude_score );
	}
	function get_tea_concentration($id ){
		return $this->field_get_value( $id , self::C_tea_concentration );
	}
	function get_tea_concentration_score($id ){
		return $this->field_get_value( $id , self::C_tea_concentration_score );
	}
	function get_tea_accident($id ){
		return $this->field_get_value( $id , self::C_tea_accident );
	}
	function get_tea_accident_score($id ){
		return $this->field_get_value( $id , self::C_tea_accident_score );
	}
	function get_tea_operation($id ){
		return $this->field_get_value( $id , self::C_tea_operation );
	}
	function get_tea_operation_score($id ){
		return $this->field_get_value( $id , self::C_tea_operation_score );
	}
	function get_tea_environment($id ){
		return $this->field_get_value( $id , self::C_tea_environment );
	}
	function get_tea_environment_score($id ){
		return $this->field_get_value( $id , self::C_tea_environment_score );
	}
	function get_class_abnormality($id ){
		return $this->field_get_value( $id , self::C_class_abnormality );
	}
	function get_class_abnormality_score($id ){
		return $this->field_get_value( $id , self::C_class_abnormality_score );
	}
	function get_record_monitor_class($id ){
		return $this->field_get_value( $id , self::C_record_monitor_class );
	}
	function get_record_rank($id ){
		return $this->field_get_value( $id , self::C_record_rank );
	}
	function get_record_lesson_list($id ){
		return $this->field_get_value( $id , self::C_record_lesson_list );
	}
	function get_limit_plan_lesson_type($id ){
		return $this->field_get_value( $id , self::C_limit_plan_lesson_type );
	}
	function get_is_freeze($id ){
		return $this->field_get_value( $id , self::C_is_freeze );
	}
	function get_class_will_type($id ){
		return $this->field_get_value( $id , self::C_class_will_type );
	}
	function get_class_will_sub_type($id ){
		return $this->field_get_value( $id , self::C_class_will_sub_type );
	}
	function get_recover_class_time($id ){
		return $this->field_get_value( $id , self::C_recover_class_time );
	}
	function get_limit_week_lesson_num_new($id ){
		return $this->field_get_value( $id , self::C_limit_week_lesson_num_new );
	}
	function get_limit_week_lesson_num_old($id ){
		return $this->field_get_value( $id , self::C_limit_week_lesson_num_old );
	}
	function get_seller_require_flag($id ){
		return $this->field_get_value( $id , self::C_seller_require_flag );
	}
	function get_is_freeze_old($id ){
		return $this->field_get_value( $id , self::C_is_freeze_old );
	}
	function get_limit_plan_lesson_type_old($id ){
		return $this->field_get_value( $id , self::C_limit_plan_lesson_type_old );
	}
	function get_grade_range($id ){
		return $this->field_get_value( $id , self::C_grade_range );
	}
	function get_no_tea_related_score($id ){
		return $this->field_get_value( $id , self::C_no_tea_related_score );
	}
	function get_trial_train_status($id ){
		return $this->field_get_value( $id , self::C_trial_train_status );
	}
	function get_train_lessonid($id ){
		return $this->field_get_value( $id , self::C_train_lessonid );
	}
	function get_current_acc($id ){
		return $this->field_get_value( $id , self::C_current_acc );
	}
	function get_phone_spare($id ){
		return $this->field_get_value( $id , self::C_phone_spare );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_teacher_record_list";
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
  CREATE TABLE `t_teacher_record_list` (
  `teacherid` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '记录类型 1 反馈记录',
  `record_info` varchar(5000) COLLATE latin1_bin NOT NULL COMMENT '记录信息',
  `add_time` int(11) NOT NULL COMMENT '评价时间',
  `acc` varchar(32) COLLATE latin1_bin NOT NULL COMMENT '评价人',
  `record_score` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '评分',
  `courseware_flag` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '试听课有无课件',
  `courseware_flag_score` int(11) NOT NULL COMMENT '试听课有无课件评分',
  `lesson_preparation_content` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '备课内容与试听需求匹配度描述',
  `lesson_preparation_content_score` int(11) NOT NULL COMMENT '备课内容与试听需求匹配度评分',
  `courseware_quality` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '课件质量描述',
  `courseware_quality_score` int(11) NOT NULL COMMENT '课件质量评分',
  `tea_process_design` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '教学过程设计描述',
  `tea_process_design_score` int(11) NOT NULL COMMENT '教学过程设计评分',
  `class_atm` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '课堂氛围描述',
  `class_atm_score` int(11) NOT NULL COMMENT '课堂氛围评分',
  `tea_method` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '讲题方法思路描述',
  `tea_method_score` int(11) NOT NULL COMMENT '讲题方法思路评分',
  `knw_point` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '知识点讲解描述',
  `knw_point_score` int(11) NOT NULL COMMENT '知识点讲解评分',
  `dif_point` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '重难点把握描述',
  `dif_point_score` int(11) NOT NULL COMMENT '重难点把握评分',
  `teacher_blackboard_writing` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '板书书写描述',
  `teacher_blackboard_writing_score` int(11) NOT NULL COMMENT '板书书写评分',
  `tea_rhythm` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '课程节奏描述',
  `tea_rhythm_score` int(11) NOT NULL COMMENT '课程节奏评分',
  `content_fam_degree` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '课本内容是否熟悉描述',
  `content_fam_degree_score` int(11) NOT NULL COMMENT '课本内容是否熟悉评分',
  `answer_question_cre` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '题目解答描述',
  `answer_question_cre_score` int(11) NOT NULL COMMENT '题目解答评分',
  `language_performance` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '语言表达组织能力描述',
  `language_performance_score` int(11) NOT NULL COMMENT '语言表达组织能力评分',
  `tea_attitude` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '教学态度描述',
  `tea_attitude_score` int(11) NOT NULL COMMENT '教学态度评分',
  `tea_concentration` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '教学专注度描述',
  `tea_concentration_score` int(11) NOT NULL COMMENT '教学专注度评分',
  `tea_accident` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '教学事故描述',
  `tea_accident_score` int(11) NOT NULL COMMENT '教学事故评分',
  `tea_operation` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '软件操作描述',
  `tea_operation_score` int(11) NOT NULL COMMENT '软件操作评分',
  `tea_environment` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '周边环境描述',
  `tea_environment_score` int(11) NOT NULL COMMENT '周边环境评分',
  `class_abnormality` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '课程异常情况处理描述',
  `class_abnormality_score` int(11) NOT NULL COMMENT '课程异常情况处理评分',
  `record_monitor_class` varchar(5000) COLLATE latin1_bin NOT NULL COMMENT '监课情况',
  `record_rank` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '评分等级',
  `record_lesson_list` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '试听课id list',
  `limit_plan_lesson_type` int(11) NOT NULL COMMENT '限制排课类型',
  `is_freeze` int(11) NOT NULL COMMENT '是否冻结排课',
  `class_will_type` int(11) NOT NULL COMMENT '接课意愿',
  `class_will_sub_type` int(11) NOT NULL COMMENT '接课意愿子分类',
  `recover_class_time` int(11) NOT NULL COMMENT '恢复接课时间',
  `limit_week_lesson_num_new` int(11) NOT NULL COMMENT '每周试听课排课次数',
  `limit_week_lesson_num_old` int(11) NOT NULL COMMENT '每周试听课排课次数(修改前)',
  `seller_require_flag` int(11) NOT NULL COMMENT '是否销售要求',
  `is_freeze_old` int(11) NOT NULL COMMENT '冻结排课状态(修改前)',
  `limit_plan_lesson_type_old` int(11) NOT NULL COMMENT '限制排课状态(修改前)',
  `grade_range` int(11) NOT NULL COMMENT '冻结/限课年级段',
  `no_tea_related_score` int(11) NOT NULL COMMENT '非教学相关得分',
  `trial_train_status` tinyint(4) NOT NULL COMMENT '模拟试听通过状态 0 未通过 1 通过',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `train_lessonid` int(11) NOT NULL COMMENT '模拟试讲的课程id',
  `current_acc` varchar(64) COLLATE latin1_bin NOT NULL COMMENT '当前操作人',
  `phone_spare` varchar(16) COLLATE latin1_bin NOT NULL COMMENT '面试老师手机',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_record` (`teacherid`,`type`,`add_time`),
  KEY `phone_spare` (`phone_spare`),
  KEY `train_lessonid` (`train_lessonid`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
