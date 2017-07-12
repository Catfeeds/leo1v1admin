<?php
namespace App\Models\Zgen;
class z_t_teacher_lecture_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_teacher_lecture_info";


	/*int(11) */
	const C_id='id';

	/*varchar(50) */
	const C_nick='nick';

	/*varchar(100) */
	const C_face='face';

	/*varchar(16) */
	const C_phone='phone';

	/*int(11) */
	const C_grade='grade';

	/*int(11) */
	const C_subject='subject';

	/*varchar(500) */
	const C_title='title';

	/*varchar(100) */
	const C_audio='audio';

	/*varchar(100) */
	const C_draw='draw';

	/*int(11) */
	const C_real_begin_time='real_begin_time';

	/*int(11) */
	const C_real_end_time='real_end_time';

	/*varchar(100) */
	const C_identity_image='identity_image';

	/*varchar(50) */
	const C_account='account';

	/*int(11) */
	const C_status='status';

	/*tinyint(4) */
	const C_identity='identity';

	/*varchar(5000) */
	const C_reason='reason';

	/*varchar(100) */
	const C_resume_url='resume_url';

	/*int(11) */
	const C_add_time='add_time';

	/*varchar(255) */
	const C_audio_build='audio_build';

	/*varchar(255) */
	const C_teacher_mental_aura='teacher_mental_aura';

	/*int(11) */
	const C_teacher_mental_aura_score='teacher_mental_aura_score';

	/*varchar(255) */
	const C_teacher_exp='teacher_exp';

	/*int(11) */
	const C_teacher_exp_score='teacher_exp_score';

	/*varchar(255) */
	const C_teacher_point_explanation='teacher_point_explanation';

	/*int(11) */
	const C_teacher_point_explanation_score='teacher_point_explanation_score';

	/*varchar(255) */
	const C_teacher_class_atm='teacher_class_atm';

	/*int(11) */
	const C_teacher_class_atm_score='teacher_class_atm_score';

	/*varchar(255) */
	const C_teacher_method='teacher_method';

	/*int(11) */
	const C_teacher_method_score='teacher_method_score';

	/*varchar(255) */
	const C_teacher_knw_point='teacher_knw_point';

	/*int(11) */
	const C_teacher_knw_point_score='teacher_knw_point_score';

	/*varchar(255) */
	const C_teacher_dif_point='teacher_dif_point';

	/*int(11) */
	const C_teacher_dif_point_score='teacher_dif_point_score';

	/*varchar(255) */
	const C_teacher_blackboard_writing='teacher_blackboard_writing';

	/*int(11) */
	const C_teacher_blackboard_writing_score='teacher_blackboard_writing_score';

	/*varchar(255) */
	const C_teacher_explain_rhythm='teacher_explain_rhythm';

	/*int(11) */
	const C_teacher_explain_rhythm_score='teacher_explain_rhythm_score';

	/*varchar(255) */
	const C_teacher_language_performance='teacher_language_performance';

	/*int(11) */
	const C_teacher_language_performance_score='teacher_language_performance_score';

	/*varchar(255) */
	const C_teacher_operation='teacher_operation';

	/*int(11) */
	const C_teacher_operation_score='teacher_operation_score';

	/*varchar(255) */
	const C_teacher_environment='teacher_environment';

	/*int(11) */
	const C_teacher_environment_score='teacher_environment_score';

	/*int(11) */
	const C_teacher_lecture_score='teacher_lecture_score';

	/*int(11) */
	const C_self_introduction_by_eng='self_introduction_by_eng';

	/*int(11) */
	const C_teacher_accuracy_score='teacher_accuracy_score';

	/*varchar(255) */
	const C_teacher_accuracy='teacher_accuracy';

	/*int(11) */
	const C_teacher_re_submit_num='teacher_re_submit_num';

	/*int(11) */
	const C_confirm_time='confirm_time';

	/*int(11) */
	const C_is_test_flag='is_test_flag';

	/*int(11) */
	const C_lecture_content_design_score='lecture_content_design_score';

	/*int(11) */
	const C_lecture_combined_score='lecture_combined_score';

	/*int(11) */
	const C_course_review_score='course_review_score';

	/*varchar(100) */
	const C_retrial_info='retrial_info';
	function get_nick($id ){
		return $this->field_get_value( $id , self::C_nick );
	}
	function get_face($id ){
		return $this->field_get_value( $id , self::C_face );
	}
	function get_phone($id ){
		return $this->field_get_value( $id , self::C_phone );
	}
	function get_grade($id ){
		return $this->field_get_value( $id , self::C_grade );
	}
	function get_subject($id ){
		return $this->field_get_value( $id , self::C_subject );
	}
	function get_title($id ){
		return $this->field_get_value( $id , self::C_title );
	}
	function get_audio($id ){
		return $this->field_get_value( $id , self::C_audio );
	}
	function get_draw($id ){
		return $this->field_get_value( $id , self::C_draw );
	}
	function get_real_begin_time($id ){
		return $this->field_get_value( $id , self::C_real_begin_time );
	}
	function get_real_end_time($id ){
		return $this->field_get_value( $id , self::C_real_end_time );
	}
	function get_identity_image($id ){
		return $this->field_get_value( $id , self::C_identity_image );
	}
	function get_account($id ){
		return $this->field_get_value( $id , self::C_account );
	}
	function get_status($id ){
		return $this->field_get_value( $id , self::C_status );
	}
	function get_identity($id ){
		return $this->field_get_value( $id , self::C_identity );
	}
	function get_reason($id ){
		return $this->field_get_value( $id , self::C_reason );
	}
	function get_resume_url($id ){
		return $this->field_get_value( $id , self::C_resume_url );
	}
	function get_add_time($id ){
		return $this->field_get_value( $id , self::C_add_time );
	}
	function get_audio_build($id ){
		return $this->field_get_value( $id , self::C_audio_build );
	}
	function get_teacher_mental_aura($id ){
		return $this->field_get_value( $id , self::C_teacher_mental_aura );
	}
	function get_teacher_mental_aura_score($id ){
		return $this->field_get_value( $id , self::C_teacher_mental_aura_score );
	}
	function get_teacher_exp($id ){
		return $this->field_get_value( $id , self::C_teacher_exp );
	}
	function get_teacher_exp_score($id ){
		return $this->field_get_value( $id , self::C_teacher_exp_score );
	}
	function get_teacher_point_explanation($id ){
		return $this->field_get_value( $id , self::C_teacher_point_explanation );
	}
	function get_teacher_point_explanation_score($id ){
		return $this->field_get_value( $id , self::C_teacher_point_explanation_score );
	}
	function get_teacher_class_atm($id ){
		return $this->field_get_value( $id , self::C_teacher_class_atm );
	}
	function get_teacher_class_atm_score($id ){
		return $this->field_get_value( $id , self::C_teacher_class_atm_score );
	}
	function get_teacher_method($id ){
		return $this->field_get_value( $id , self::C_teacher_method );
	}
	function get_teacher_method_score($id ){
		return $this->field_get_value( $id , self::C_teacher_method_score );
	}
	function get_teacher_knw_point($id ){
		return $this->field_get_value( $id , self::C_teacher_knw_point );
	}
	function get_teacher_knw_point_score($id ){
		return $this->field_get_value( $id , self::C_teacher_knw_point_score );
	}
	function get_teacher_dif_point($id ){
		return $this->field_get_value( $id , self::C_teacher_dif_point );
	}
	function get_teacher_dif_point_score($id ){
		return $this->field_get_value( $id , self::C_teacher_dif_point_score );
	}
	function get_teacher_blackboard_writing($id ){
		return $this->field_get_value( $id , self::C_teacher_blackboard_writing );
	}
	function get_teacher_blackboard_writing_score($id ){
		return $this->field_get_value( $id , self::C_teacher_blackboard_writing_score );
	}
	function get_teacher_explain_rhythm($id ){
		return $this->field_get_value( $id , self::C_teacher_explain_rhythm );
	}
	function get_teacher_explain_rhythm_score($id ){
		return $this->field_get_value( $id , self::C_teacher_explain_rhythm_score );
	}
	function get_teacher_language_performance($id ){
		return $this->field_get_value( $id , self::C_teacher_language_performance );
	}
	function get_teacher_language_performance_score($id ){
		return $this->field_get_value( $id , self::C_teacher_language_performance_score );
	}
	function get_teacher_operation($id ){
		return $this->field_get_value( $id , self::C_teacher_operation );
	}
	function get_teacher_operation_score($id ){
		return $this->field_get_value( $id , self::C_teacher_operation_score );
	}
	function get_teacher_environment($id ){
		return $this->field_get_value( $id , self::C_teacher_environment );
	}
	function get_teacher_environment_score($id ){
		return $this->field_get_value( $id , self::C_teacher_environment_score );
	}
	function get_teacher_lecture_score($id ){
		return $this->field_get_value( $id , self::C_teacher_lecture_score );
	}
	function get_self_introduction_by_eng($id ){
		return $this->field_get_value( $id , self::C_self_introduction_by_eng );
	}
	function get_teacher_accuracy_score($id ){
		return $this->field_get_value( $id , self::C_teacher_accuracy_score );
	}
	function get_teacher_accuracy($id ){
		return $this->field_get_value( $id , self::C_teacher_accuracy );
	}
	function get_teacher_re_submit_num($id ){
		return $this->field_get_value( $id , self::C_teacher_re_submit_num );
	}
	function get_confirm_time($id ){
		return $this->field_get_value( $id , self::C_confirm_time );
	}
	function get_is_test_flag($id ){
		return $this->field_get_value( $id , self::C_is_test_flag );
	}
	function get_lecture_content_design_score($id ){
		return $this->field_get_value( $id , self::C_lecture_content_design_score );
	}
	function get_lecture_combined_score($id ){
		return $this->field_get_value( $id , self::C_lecture_combined_score );
	}
	function get_course_review_score($id ){
		return $this->field_get_value( $id , self::C_course_review_score );
	}
	function get_retrial_info($id ){
		return $this->field_get_value( $id , self::C_retrial_info );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_teacher_lecture_info";
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
  CREATE TABLE `t_teacher_lecture_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nick` varchar(50) COLLATE latin1_bin NOT NULL,
  `face` varchar(100) COLLATE latin1_bin NOT NULL,
  `phone` varchar(16) COLLATE latin1_bin NOT NULL,
  `grade` int(11) NOT NULL,
  `subject` int(11) NOT NULL,
  `title` varchar(500) COLLATE latin1_bin NOT NULL,
  `audio` varchar(100) COLLATE latin1_bin NOT NULL,
  `draw` varchar(100) COLLATE latin1_bin NOT NULL,
  `real_begin_time` int(11) NOT NULL,
  `real_end_time` int(11) NOT NULL,
  `identity_image` varchar(100) COLLATE latin1_bin NOT NULL,
  `account` varchar(50) COLLATE latin1_bin NOT NULL COMMENT '试听课检查人',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '试讲课状态 0 未检查 1 通过 2 不通过',
  `identity` tinyint(4) NOT NULL DEFAULT '0' COMMENT '老师身份 0 未设置 1 学生 2 老师',
  `reason` varchar(5000) COLLATE latin1_bin NOT NULL COMMENT '确认情况的原因',
  `resume_url` varchar(100) COLLATE latin1_bin NOT NULL COMMENT '简历地址',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `audio_build` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '转换后的MP3地址',
  `teacher_mental_aura` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '教师气场描述',
  `teacher_mental_aura_score` int(11) NOT NULL COMMENT '教师气场评分',
  `teacher_exp` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '教师经验描述',
  `teacher_exp_score` int(11) NOT NULL COMMENT '教师经验评分',
  `teacher_point_explanation` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '知识点讲解描述',
  `teacher_point_explanation_score` int(11) NOT NULL COMMENT '知识点讲解评分',
  `teacher_class_atm` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '课堂氛围描述',
  `teacher_class_atm_score` int(11) NOT NULL COMMENT '课堂氛围评分',
  `teacher_method` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '讲题方法思路/英语发音,语音读音错误描述',
  `teacher_method_score` int(11) NOT NULL COMMENT '讲题方法思路/英语发音,语音读音错误评分',
  `teacher_knw_point` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '知识点与练习比例描述',
  `teacher_knw_point_score` int(11) NOT NULL COMMENT '知识点与练习比例评分',
  `teacher_dif_point` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '重难点把握描述',
  `teacher_dif_point_score` int(11) NOT NULL COMMENT '重难点把握评分',
  `teacher_blackboard_writing` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '板书描述',
  `teacher_blackboard_writing_score` int(11) NOT NULL COMMENT '板书评分',
  `teacher_explain_rhythm` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '讲解节奏描述',
  `teacher_explain_rhythm_score` int(11) NOT NULL COMMENT '讲解节奏评分',
  `teacher_language_performance` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '语言表达组织能力描述',
  `teacher_language_performance_score` int(11) NOT NULL COMMENT '语言表达组织能力评分',
  `teacher_operation` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '教师端操作描述',
  `teacher_operation_score` int(11) NOT NULL COMMENT '教师端操作评分',
  `teacher_environment` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '周边环境描述',
  `teacher_environment_score` int(11) NOT NULL COMMENT '周边环境评分',
  `teacher_lecture_score` int(11) NOT NULL COMMENT '老师试讲总评分',
  `self_introduction_by_eng` int(11) NOT NULL COMMENT '是否有英文自我介绍',
  `teacher_accuracy_score` int(11) NOT NULL COMMENT '正确率得分',
  `teacher_accuracy` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '正确率描述',
  `teacher_re_submit_num` int(11) NOT NULL COMMENT '审核次数',
  `confirm_time` int(11) NOT NULL COMMENT '审核时间',
  `is_test_flag` int(11) NOT NULL COMMENT '测试试讲 0 不是 1 是',
  `lecture_content_design_score` int(11) NOT NULL COMMENT '讲义设计得分',
  `lecture_combined_score` int(11) NOT NULL COMMENT '讲义结合得分',
  `course_review_score` int(11) NOT NULL COMMENT '讲义设计得分',
  `retrial_info` varchar(100) COLLATE latin1_bin NOT NULL COMMENT '重审淘汰情况',
  PRIMARY KEY (`id`),
  KEY `phone` (`phone`),
  KEY `grade,subject` (`grade`,`subject`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
