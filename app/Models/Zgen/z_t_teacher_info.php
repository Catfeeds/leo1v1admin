<?php
namespace App\Models\Zgen;
class z_t_teacher_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_teacher_info";


	/*int(10) unsigned */
	const C_teacherid='teacherid';

	/*varchar(32) */
	const C_nick='nick';

	/*varchar(100) */
	const C_face='face';

	/*varchar(16) */
	const C_phone='phone';

	/*tinyint(4) */
	const C_gender='gender';

	/*int(10) unsigned */
	const C_stu_num='stu_num';

	/*smallint(6) */
	const C_grade='grade';

	/*varchar(100) */
	const C_school='school';

	/*varchar(300) */
	const C_address='address';

	/*smallint(6) */
	const C_rate_score='rate_score';

	/*smallint(6) */
	const C_rate_effect='rate_effect';

	/*smallint(6) */
	const C_rate_quality='rate_quality';

	/*smallint(6) */
	const C_rate_interact='rate_interact';

	/*int(10) unsigned */
	const C_five_star='five_star';

	/*int(10) unsigned */
	const C_four_star='four_star';

	/*int(10) unsigned */
	const C_three_star='three_star';

	/*int(10) unsigned */
	const C_two_star='two_star';

	/*int(10) unsigned */
	const C_one_star='one_star';

	/*varchar(600) */
	const C_base_intro='base_intro';

	/*varchar(600) */
	const C_advantage='advantage';

	/*int(10) unsigned */
	const C_work_year='work_year';

	/*int(10) unsigned */
	const C_tutor_subject='tutor_subject';

	/*int(10) unsigned */
	const C_tutor_grade='tutor_grade';

	/*varchar(32) */
	const C_title='title';

	/*tinyint(4) */
	const C_level='level';

	/*int(10) unsigned */
	const C_birth='birth';

	/*timestamp */
	const C_last_modified_time='last_modified_time';

	/*varchar(50) */
	const C_email='email';

	/*tinyint(4) */
	const C_teacher_type='teacher_type';

	/*varchar(1024) */
	const C_prize='prize';

	/*varchar(1024) */
	const C_achievement='achievement';

	/*varchar(1024) */
	const C_teacher_style='teacher_style';

	/*varchar(1024) */
	const C_quiz_analyse='quiz_analyse';

	/*varchar(1024) */
	const C_quiz_video='quiz_video';

	/*tinyint(4) */
	const C_is_quit='is_quit';

	/*varchar(300) */
	const C_user_agent='user_agent';

	/*int(10) unsigned */
	const C_flower='flower';

	/*varchar(200) */
	const C_teacher_tags='teacher_tags';

	/*varchar(200) */
	const C_teacher_textbook='teacher_textbook';

	/*tinyint(4) */
	const C_create_meeting='create_meeting';

	/*int(11) */
	const C_clothes='clothes';

	/*varchar(32) */
	const C_realname='realname';

	/*int(11) */
	const C_teacher_money_type='teacher_money_type';

	/*varchar(255) */
	const C_wx_openid='wx_openid';

	/*int(11) */
	const C_need_test_lesson_flag='need_test_lesson_flag';

	/*int(11) */
	const C_check_adminid='check_adminid';

	/*int(11) */
	const C_create_time='create_time';

	/*int(11) */
	const C_assess_num='assess_num';

	/*int(11) */
	const C_textbook_type='textbook_type';

	/*int(11) */
	const C_grade_part_ex='grade_part_ex';

	/*int(11) */
	const C_subject='subject';

	/*int(11) */
	const C_putonghua_is_correctly='putonghua_is_correctly';

	/*varchar(255) */
	const C_dialect_notes='dialect_notes';

	/*varchar(255) */
	const C_jianli='jianli';

	/*int(11) */
	const C_is_good_flag='is_good_flag';

	/*int(11) */
	const C_second_subject='second_subject';

	/*int(11) */
	const C_third_subject='third_subject';

	/*varchar(255) */
	const C_interview_access='interview_access';

	/*varchar(255) */
	const C_tea_note='tea_note';

	/*int(11) */
	const C_teacher_money_flag='teacher_money_flag';

	/*tinyint(4) */
	const C_trial_lecture_is_pass='trial_lecture_is_pass';

	/*tinyint(4) */
	const C_identity='identity';

	/*int(11) */
	const C_is_test_user='is_test_user';

	/*int(11) */
	const C_is_freeze='is_freeze';

	/*int(11) */
	const C_freeze_adminid='freeze_adminid';

	/*int(11) */
	const C_freeze_time='freeze_time';

	/*varchar(255) */
	const C_freeze_reason='freeze_reason';

	/*int(11) */
	const C_un_freeze_time='un_freeze_time';

	/*int(11) */
	const C_is_interview_teacher_flag='is_interview_teacher_flag';

	/*int(11) */
	const C_limit_plan_lesson_type='limit_plan_lesson_type';

	/*int(11) */
	const C_limit_plan_lesson_time='limit_plan_lesson_time';

	/*varchar(5000) */
	const C_limit_plan_lesson_reason='limit_plan_lesson_reason';

	/*varchar(255) */
	const C_limit_plan_lesson_account='limit_plan_lesson_account';

	/*int(11) */
	const C_second_grade='second_grade';

	/*int(11) */
	const C_third_grade='third_grade';

	/*int(11) */
	const C_train_through_new='train_through_new';

	/*int(11) */
	const C_is_week_freeze='is_week_freeze';

	/*int(11) */
	const C_week_freeze_adminid='week_freeze_adminid';

	/*int(11) */
	const C_week_freeze_time='week_freeze_time';

	/*varchar(255) */
	const C_week_freeze_reason='week_freeze_reason';

	/*int(11) */
	const C_train_through_new_time='train_through_new_time';

	/*int(11) */
	const C_lesson_hold_flag='lesson_hold_flag';

	/*int(11) */
	const C_interview_score='interview_score';

	/*int(11) */
	const C_second_interview_score='second_interview_score';

	/*int(11) */
	const C_test_transfor_per='test_transfor_per';

	/*int(11) */
	const C_week_liveness='week_liveness';

	/*int(11) */
	const C_idcard='idcard';

	/*int(11) */
	const C_bankcard='bankcard';

	/*varchar(255) */
	const C_bank_address='bank_address';

	/*varchar(255) */
	const C_bank_account='bank_account';

	/*int(11) */
	const C_wx_use_flag='wx_use_flag';

	/*varchar(255) */
	const C_permission='permission';

	/*int(11) */
	const C_limit_day_lesson_num='limit_day_lesson_num';

	/*int(11) */
	const C_limit_week_lesson_num='limit_week_lesson_num';

	/*int(11) */
	const C_limit_month_lesson_num='limit_month_lesson_num';

	/*tinyint(4) */
	const C_teacher_ref_type='teacher_ref_type';

	/*varchar(255) */
	const C_research_note='research_note';

	/*varchar(255) */
	const C_lesson_hold_flag_acc='lesson_hold_flag_acc';

	/*varchar(255) */
	const C_lesson_hold_flag_reason='lesson_hold_flag_reason';

	/*int(11) */
	const C_lesson_hold_flag_time='lesson_hold_flag_time';

	/*int(11) */
	const C_assign_jw_adminid='assign_jw_adminid';

	/*int(11) */
	const C_assign_jw_time='assign_jw_time';

	/*int(11) */
	const C_week_freeze_warning_flag='week_freeze_warning_flag';

	/*int(11) */
	const C_grade_start='grade_start';

	/*int(11) */
	const C_grade_end='grade_end';

	/*tinyint(4) */
	const C_month_type='month_type';

	/*int(11) */
	const C_saturday_lesson_num='saturday_lesson_num';

	/*int(11) */
	const C_change_good_time='change_good_time';

	/*int(11) */
	const C_is_good_wx_flag='is_good_wx_flag';

	/*varchar(255) */
	const C_not_grade='not_grade';

	/*varchar(255) */
	const C_not_grade_limit='not_grade_limit';

	/*int(11) */
	const C_week_lesson_count='week_lesson_count';

	/*tinyint(4) */
	const C_is_record_flag='is_record_flag';

	/*int(11) */
	const C_lesson_hold_flag_adminid='lesson_hold_flag_adminid';

	/*int(11) */
	const C_have_test_lesson_flag='have_test_lesson_flag';

	/*int(11) */
	const C_test_lesson_num='test_lesson_num';

	/*int(11) */
	const C_quit_time='quit_time';

	/*int(11) */
	const C_leave_start_time='leave_start_time';

	/*int(11) */
	const C_leave_end_time='leave_end_time';

	/*int(11) */
	const C_leave_set_adminid='leave_set_adminid';

	/*int(11) */
	const C_leave_set_time='leave_set_time';

	/*int(11) */
	const C_quit_set_adminid='quit_set_adminid';

	/*varchar(500) */
	const C_leave_reason='leave_reason';

	/*int(11) */
	const C_leave_remove_adminid='leave_remove_adminid';

	/*int(11) */
	const C_leave_remove_time='leave_remove_time';

	/*varchar(500) */
	const C_quit_info='quit_info';

	/*varchar(16) */
	const C_phone_spare='phone_spare';

	/*tinyint(4) */
	const C_trial_train_flag='trial_train_flag';

	/*int(11) */
	const C_second_grade_start='second_grade_start';

	/*int(11) */
	const C_second_grade_end='second_grade_end';

	/*varchar(255) */
	const C_second_not_grade='second_not_grade';

	/*varchar(255) */
	const C_add_acc='add_acc';

	/*varchar(1000) */
	const C_part_remarks='part_remarks';

	/*varchar(16) */
	const C_bank_phone='bank_phone';

	/*varchar(255) */
	const C_bank_type='bank_type';

	/*tinyint(4) */
	const C_user_agent_wx_update='user_agent_wx_update';

	/*varchar(255) */
	const C_bank_province='bank_province';

	/*varchar(255) */
	const C_bank_city='bank_city';

	/*int(11) */
	const C_transfer_teacherid='transfer_teacherid';

	/*int(11) */
	const C_transfer_time='transfer_time';
	function get_nick($teacherid ){
		return $this->field_get_value( $teacherid , self::C_nick );
	}
	function get_face($teacherid ){
		return $this->field_get_value( $teacherid , self::C_face );
	}
	function get_phone($teacherid ){
		return $this->field_get_value( $teacherid , self::C_phone );
	}
	function get_gender($teacherid ){
		return $this->field_get_value( $teacherid , self::C_gender );
	}
	function get_stu_num($teacherid ){
		return $this->field_get_value( $teacherid , self::C_stu_num );
	}
	function get_grade($teacherid ){
		return $this->field_get_value( $teacherid , self::C_grade );
	}
	function get_school($teacherid ){
		return $this->field_get_value( $teacherid , self::C_school );
	}
	function get_address($teacherid ){
		return $this->field_get_value( $teacherid , self::C_address );
	}
	function get_rate_score($teacherid ){
		return $this->field_get_value( $teacherid , self::C_rate_score );
	}
	function get_rate_effect($teacherid ){
		return $this->field_get_value( $teacherid , self::C_rate_effect );
	}
	function get_rate_quality($teacherid ){
		return $this->field_get_value( $teacherid , self::C_rate_quality );
	}
	function get_rate_interact($teacherid ){
		return $this->field_get_value( $teacherid , self::C_rate_interact );
	}
	function get_five_star($teacherid ){
		return $this->field_get_value( $teacherid , self::C_five_star );
	}
	function get_four_star($teacherid ){
		return $this->field_get_value( $teacherid , self::C_four_star );
	}
	function get_three_star($teacherid ){
		return $this->field_get_value( $teacherid , self::C_three_star );
	}
	function get_two_star($teacherid ){
		return $this->field_get_value( $teacherid , self::C_two_star );
	}
	function get_one_star($teacherid ){
		return $this->field_get_value( $teacherid , self::C_one_star );
	}
	function get_base_intro($teacherid ){
		return $this->field_get_value( $teacherid , self::C_base_intro );
	}
	function get_advantage($teacherid ){
		return $this->field_get_value( $teacherid , self::C_advantage );
	}
	function get_work_year($teacherid ){
		return $this->field_get_value( $teacherid , self::C_work_year );
	}
	function get_tutor_subject($teacherid ){
		return $this->field_get_value( $teacherid , self::C_tutor_subject );
	}
	function get_tutor_grade($teacherid ){
		return $this->field_get_value( $teacherid , self::C_tutor_grade );
	}
	function get_title($teacherid ){
		return $this->field_get_value( $teacherid , self::C_title );
	}
	function get_level($teacherid ){
		return $this->field_get_value( $teacherid , self::C_level );
	}
	function get_birth($teacherid ){
		return $this->field_get_value( $teacherid , self::C_birth );
	}
	function get_last_modified_time($teacherid ){
		return $this->field_get_value( $teacherid , self::C_last_modified_time );
	}
	function get_email($teacherid ){
		return $this->field_get_value( $teacherid , self::C_email );
	}
	function get_teacher_type($teacherid ){
		return $this->field_get_value( $teacherid , self::C_teacher_type );
	}
	function get_prize($teacherid ){
		return $this->field_get_value( $teacherid , self::C_prize );
	}
	function get_achievement($teacherid ){
		return $this->field_get_value( $teacherid , self::C_achievement );
	}
	function get_teacher_style($teacherid ){
		return $this->field_get_value( $teacherid , self::C_teacher_style );
	}
	function get_quiz_analyse($teacherid ){
		return $this->field_get_value( $teacherid , self::C_quiz_analyse );
	}
	function get_quiz_video($teacherid ){
		return $this->field_get_value( $teacherid , self::C_quiz_video );
	}
	function get_is_quit($teacherid ){
		return $this->field_get_value( $teacherid , self::C_is_quit );
	}
	function get_user_agent($teacherid ){
		return $this->field_get_value( $teacherid , self::C_user_agent );
	}
	function get_flower($teacherid ){
		return $this->field_get_value( $teacherid , self::C_flower );
	}
	function get_teacher_tags($teacherid ){
		return $this->field_get_value( $teacherid , self::C_teacher_tags );
	}
	function get_teacher_textbook($teacherid ){
		return $this->field_get_value( $teacherid , self::C_teacher_textbook );
	}
	function get_create_meeting($teacherid ){
		return $this->field_get_value( $teacherid , self::C_create_meeting );
	}
	function get_clothes($teacherid ){
		return $this->field_get_value( $teacherid , self::C_clothes );
	}
	function get_realname($teacherid ){
		return $this->field_get_value( $teacherid , self::C_realname );
	}
	function get_teacher_money_type($teacherid ){
		return $this->field_get_value( $teacherid , self::C_teacher_money_type );
	}
	function get_wx_openid($teacherid ){
		return $this->field_get_value( $teacherid , self::C_wx_openid );
	}
	function get_need_test_lesson_flag($teacherid ){
		return $this->field_get_value( $teacherid , self::C_need_test_lesson_flag );
	}
	function get_check_adminid($teacherid ){
		return $this->field_get_value( $teacherid , self::C_check_adminid );
	}
	function get_create_time($teacherid ){
		return $this->field_get_value( $teacherid , self::C_create_time );
	}
	function get_assess_num($teacherid ){
		return $this->field_get_value( $teacherid , self::C_assess_num );
	}
	function get_textbook_type($teacherid ){
		return $this->field_get_value( $teacherid , self::C_textbook_type );
	}
	function get_grade_part_ex($teacherid ){
		return $this->field_get_value( $teacherid , self::C_grade_part_ex );
	}
	function get_subject($teacherid ){
		return $this->field_get_value( $teacherid , self::C_subject );
	}
	function get_putonghua_is_correctly($teacherid ){
		return $this->field_get_value( $teacherid , self::C_putonghua_is_correctly );
	}
	function get_dialect_notes($teacherid ){
		return $this->field_get_value( $teacherid , self::C_dialect_notes );
	}
	function get_jianli($teacherid ){
		return $this->field_get_value( $teacherid , self::C_jianli );
	}
	function get_is_good_flag($teacherid ){
		return $this->field_get_value( $teacherid , self::C_is_good_flag );
	}
	function get_second_subject($teacherid ){
		return $this->field_get_value( $teacherid , self::C_second_subject );
	}
	function get_third_subject($teacherid ){
		return $this->field_get_value( $teacherid , self::C_third_subject );
	}
	function get_interview_access($teacherid ){
		return $this->field_get_value( $teacherid , self::C_interview_access );
	}
	function get_tea_note($teacherid ){
		return $this->field_get_value( $teacherid , self::C_tea_note );
	}
	function get_teacher_money_flag($teacherid ){
		return $this->field_get_value( $teacherid , self::C_teacher_money_flag );
	}
	function get_trial_lecture_is_pass($teacherid ){
		return $this->field_get_value( $teacherid , self::C_trial_lecture_is_pass );
	}
	function get_identity($teacherid ){
		return $this->field_get_value( $teacherid , self::C_identity );
	}
	function get_is_test_user($teacherid ){
		return $this->field_get_value( $teacherid , self::C_is_test_user );
	}
	function get_is_freeze($teacherid ){
		return $this->field_get_value( $teacherid , self::C_is_freeze );
	}
	function get_freeze_adminid($teacherid ){
		return $this->field_get_value( $teacherid , self::C_freeze_adminid );
	}
	function get_freeze_time($teacherid ){
		return $this->field_get_value( $teacherid , self::C_freeze_time );
	}
	function get_freeze_reason($teacherid ){
		return $this->field_get_value( $teacherid , self::C_freeze_reason );
	}
	function get_un_freeze_time($teacherid ){
		return $this->field_get_value( $teacherid , self::C_un_freeze_time );
	}
	function get_is_interview_teacher_flag($teacherid ){
		return $this->field_get_value( $teacherid , self::C_is_interview_teacher_flag );
	}
	function get_limit_plan_lesson_type($teacherid ){
		return $this->field_get_value( $teacherid , self::C_limit_plan_lesson_type );
	}
	function get_limit_plan_lesson_time($teacherid ){
		return $this->field_get_value( $teacherid , self::C_limit_plan_lesson_time );
	}
	function get_limit_plan_lesson_reason($teacherid ){
		return $this->field_get_value( $teacherid , self::C_limit_plan_lesson_reason );
	}
	function get_limit_plan_lesson_account($teacherid ){
		return $this->field_get_value( $teacherid , self::C_limit_plan_lesson_account );
	}
	function get_second_grade($teacherid ){
		return $this->field_get_value( $teacherid , self::C_second_grade );
	}
	function get_third_grade($teacherid ){
		return $this->field_get_value( $teacherid , self::C_third_grade );
	}
	function get_train_through_new($teacherid ){
		return $this->field_get_value( $teacherid , self::C_train_through_new );
	}
	function get_is_week_freeze($teacherid ){
		return $this->field_get_value( $teacherid , self::C_is_week_freeze );
	}
	function get_week_freeze_adminid($teacherid ){
		return $this->field_get_value( $teacherid , self::C_week_freeze_adminid );
	}
	function get_week_freeze_time($teacherid ){
		return $this->field_get_value( $teacherid , self::C_week_freeze_time );
	}
	function get_week_freeze_reason($teacherid ){
		return $this->field_get_value( $teacherid , self::C_week_freeze_reason );
	}
	function get_train_through_new_time($teacherid ){
		return $this->field_get_value( $teacherid , self::C_train_through_new_time );
	}
	function get_lesson_hold_flag($teacherid ){
		return $this->field_get_value( $teacherid , self::C_lesson_hold_flag );
	}
	function get_interview_score($teacherid ){
		return $this->field_get_value( $teacherid , self::C_interview_score );
	}
	function get_second_interview_score($teacherid ){
		return $this->field_get_value( $teacherid , self::C_second_interview_score );
	}
	function get_test_transfor_per($teacherid ){
		return $this->field_get_value( $teacherid , self::C_test_transfor_per );
	}
	function get_week_liveness($teacherid ){
		return $this->field_get_value( $teacherid , self::C_week_liveness );
	}
	function get_idcard($teacherid ){
		return $this->field_get_value( $teacherid , self::C_idcard );
	}
	function get_bankcard($teacherid ){
		return $this->field_get_value( $teacherid , self::C_bankcard );
	}
	function get_bank_address($teacherid ){
		return $this->field_get_value( $teacherid , self::C_bank_address );
	}
	function get_bank_account($teacherid ){
		return $this->field_get_value( $teacherid , self::C_bank_account );
	}
	function get_wx_use_flag($teacherid ){
		return $this->field_get_value( $teacherid , self::C_wx_use_flag );
	}
	function get_permission($teacherid ){
		return $this->field_get_value( $teacherid , self::C_permission );
	}
	function get_limit_day_lesson_num($teacherid ){
		return $this->field_get_value( $teacherid , self::C_limit_day_lesson_num );
	}
	function get_limit_week_lesson_num($teacherid ){
		return $this->field_get_value( $teacherid , self::C_limit_week_lesson_num );
	}
	function get_limit_month_lesson_num($teacherid ){
		return $this->field_get_value( $teacherid , self::C_limit_month_lesson_num );
	}
	function get_teacher_ref_type($teacherid ){
		return $this->field_get_value( $teacherid , self::C_teacher_ref_type );
	}
	function get_research_note($teacherid ){
		return $this->field_get_value( $teacherid , self::C_research_note );
	}
	function get_lesson_hold_flag_acc($teacherid ){
		return $this->field_get_value( $teacherid , self::C_lesson_hold_flag_acc );
	}
	function get_lesson_hold_flag_reason($teacherid ){
		return $this->field_get_value( $teacherid , self::C_lesson_hold_flag_reason );
	}
	function get_lesson_hold_flag_time($teacherid ){
		return $this->field_get_value( $teacherid , self::C_lesson_hold_flag_time );
	}
	function get_assign_jw_adminid($teacherid ){
		return $this->field_get_value( $teacherid , self::C_assign_jw_adminid );
	}
	function get_assign_jw_time($teacherid ){
		return $this->field_get_value( $teacherid , self::C_assign_jw_time );
	}
	function get_week_freeze_warning_flag($teacherid ){
		return $this->field_get_value( $teacherid , self::C_week_freeze_warning_flag );
	}
	function get_grade_start($teacherid ){
		return $this->field_get_value( $teacherid , self::C_grade_start );
	}
	function get_grade_end($teacherid ){
		return $this->field_get_value( $teacherid , self::C_grade_end );
	}
	function get_month_type($teacherid ){
		return $this->field_get_value( $teacherid , self::C_month_type );
	}
	function get_saturday_lesson_num($teacherid ){
		return $this->field_get_value( $teacherid , self::C_saturday_lesson_num );
	}
	function get_change_good_time($teacherid ){
		return $this->field_get_value( $teacherid , self::C_change_good_time );
	}
	function get_is_good_wx_flag($teacherid ){
		return $this->field_get_value( $teacherid , self::C_is_good_wx_flag );
	}
	function get_not_grade($teacherid ){
		return $this->field_get_value( $teacherid , self::C_not_grade );
	}
	function get_not_grade_limit($teacherid ){
		return $this->field_get_value( $teacherid , self::C_not_grade_limit );
	}
	function get_week_lesson_count($teacherid ){
		return $this->field_get_value( $teacherid , self::C_week_lesson_count );
	}
	function get_is_record_flag($teacherid ){
		return $this->field_get_value( $teacherid , self::C_is_record_flag );
	}
	function get_lesson_hold_flag_adminid($teacherid ){
		return $this->field_get_value( $teacherid , self::C_lesson_hold_flag_adminid );
	}
	function get_have_test_lesson_flag($teacherid ){
		return $this->field_get_value( $teacherid , self::C_have_test_lesson_flag );
	}
	function get_test_lesson_num($teacherid ){
		return $this->field_get_value( $teacherid , self::C_test_lesson_num );
	}
	function get_quit_time($teacherid ){
		return $this->field_get_value( $teacherid , self::C_quit_time );
	}
	function get_leave_start_time($teacherid ){
		return $this->field_get_value( $teacherid , self::C_leave_start_time );
	}
	function get_leave_end_time($teacherid ){
		return $this->field_get_value( $teacherid , self::C_leave_end_time );
	}
	function get_leave_set_adminid($teacherid ){
		return $this->field_get_value( $teacherid , self::C_leave_set_adminid );
	}
	function get_leave_set_time($teacherid ){
		return $this->field_get_value( $teacherid , self::C_leave_set_time );
	}
	function get_quit_set_adminid($teacherid ){
		return $this->field_get_value( $teacherid , self::C_quit_set_adminid );
	}
	function get_leave_reason($teacherid ){
		return $this->field_get_value( $teacherid , self::C_leave_reason );
	}
	function get_leave_remove_adminid($teacherid ){
		return $this->field_get_value( $teacherid , self::C_leave_remove_adminid );
	}
	function get_leave_remove_time($teacherid ){
		return $this->field_get_value( $teacherid , self::C_leave_remove_time );
	}
	function get_quit_info($teacherid ){
		return $this->field_get_value( $teacherid , self::C_quit_info );
	}
	function get_phone_spare($teacherid ){
		return $this->field_get_value( $teacherid , self::C_phone_spare );
	}
	function get_trial_train_flag($teacherid ){
		return $this->field_get_value( $teacherid , self::C_trial_train_flag );
	}
	function get_second_grade_start($teacherid ){
		return $this->field_get_value( $teacherid , self::C_second_grade_start );
	}
	function get_second_grade_end($teacherid ){
		return $this->field_get_value( $teacherid , self::C_second_grade_end );
	}
	function get_second_not_grade($teacherid ){
		return $this->field_get_value( $teacherid , self::C_second_not_grade );
	}
	function get_add_acc($teacherid ){
		return $this->field_get_value( $teacherid , self::C_add_acc );
	}
	function get_part_remarks($teacherid ){
		return $this->field_get_value( $teacherid , self::C_part_remarks );
	}
	function get_bank_phone($teacherid ){
		return $this->field_get_value( $teacherid , self::C_bank_phone );
	}
	function get_bank_type($teacherid ){
		return $this->field_get_value( $teacherid , self::C_bank_type );
	}
	function get_user_agent_wx_update($teacherid ){
		return $this->field_get_value( $teacherid , self::C_user_agent_wx_update );
	}
	function get_bank_province($teacherid ){
		return $this->field_get_value( $teacherid , self::C_bank_province );
	}
	function get_bank_city($teacherid ){
		return $this->field_get_value( $teacherid , self::C_bank_city );
	}
	function get_transfer_teacherid($teacherid ){
		return $this->field_get_value( $teacherid , self::C_transfer_teacherid );
	}
	function get_transfer_time($teacherid ){
		return $this->field_get_value( $teacherid , self::C_transfer_time );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="teacherid";
        $this->field_table_name="db_weiyi.t_teacher_info";
  }
    public function field_get_list( $teacherid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $teacherid, $set_field_arr) {
        return parent::field_update_list( $teacherid, $set_field_arr);
    }


    public function field_get_value(  $teacherid, $field_name ) {
        return parent::field_get_value( $teacherid, $field_name);
    }

    public function row_delete(  $teacherid) {
        return parent::row_delete( $teacherid);
    }

}

/*
  CREATE TABLE `t_teacher_info` (
  `teacherid` int(10) unsigned NOT NULL COMMENT '老师id',
  `nick` varchar(32) NOT NULL COMMENT '老师昵称',
  `face` varchar(100) NOT NULL DEFAULT '' COMMENT '老师的头像',
  `phone` varchar(16) NOT NULL COMMENT '手机号码',
  `gender` tinyint(4) NOT NULL DEFAULT '0' COMMENT '性别（0 保密 1 男 2 女）',
  `stu_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '学生个数',
  `grade` smallint(6) NOT NULL DEFAULT '0' COMMENT '年级',
  `school` varchar(100) NOT NULL DEFAULT '' COMMENT '学校名称',
  `address` varchar(300) NOT NULL DEFAULT '' COMMENT '所在地区',
  `rate_score` smallint(6) NOT NULL DEFAULT '0' COMMENT '评价分数',
  `rate_effect` smallint(6) NOT NULL DEFAULT '0' COMMENT '老师上课效果',
  `rate_quality` smallint(6) NOT NULL DEFAULT '0' COMMENT '老师课件质量',
  `rate_interact` smallint(6) NOT NULL DEFAULT '0' COMMENT '老师课堂互动',
  `five_star` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '五星评价',
  `four_star` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '四星评价',
  `three_star` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '三星评价',
  `two_star` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二星评价',
  `one_star` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '一星评价',
  `base_intro` varchar(600) NOT NULL DEFAULT '' COMMENT '基本信息',
  `advantage` varchar(600) NOT NULL DEFAULT '' COMMENT '个人优势',
  `work_year` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '工作年限',
  `tutor_subject` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所教课程',
  `tutor_grade` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所教的年级',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '职称',
  `level` tinyint(4) NOT NULL DEFAULT '0' COMMENT '等级',
  `birth` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '生日（格式如19910101）',
  `last_modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后修改时间',
  `email` varchar(50) NOT NULL DEFAULT '0' COMMENT '老师邮箱',
  `teacher_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '老师类型0全职1兼职',
  `prize` varchar(1024) NOT NULL DEFAULT '' COMMENT '曾经获取的奖励或者成就',
  `achievement` varchar(1024) NOT NULL DEFAULT '' COMMENT '教学成就，学生成绩',
  `teacher_style` varchar(1024) NOT NULL DEFAULT '' COMMENT '授课风格',
  `quiz_analyse` varchar(1024) NOT NULL DEFAULT '' COMMENT '试题分析,多张图片，以逗号分割',
  `quiz_video` varchar(1024) NOT NULL DEFAULT '' COMMENT '试题分析视频，以逗号分析，顺序你为封面，笔画，声音',
  `is_quit` tinyint(4) NOT NULL DEFAULT '0' COMMENT '老师是否已经离职 0 未离职 1 已离职',
  `user_agent` varchar(300) NOT NULL DEFAULT '' COMMENT '老师的设备信息',
  `flower` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所得鲜花数',
  `teacher_tags` varchar(200) NOT NULL DEFAULT '0' COMMENT '老师标签',
  `teacher_textbook` varchar(200) NOT NULL DEFAULT '0' COMMENT '老师擅长的教材',
  `create_meeting` tinyint(4) NOT NULL DEFAULT '0' COMMENT '创建会议权限０无权限１有权限',
  `clothes` int(11) DEFAULT '0',
  `realname` varchar(32) NOT NULL COMMENT '真实姓名',
  `teacher_money_type` int(11) NOT NULL COMMENT '老师工资分类',
  `wx_openid` varchar(255) DEFAULT NULL,
  `need_test_lesson_flag` int(11) NOT NULL DEFAULT '1' COMMENT '是否需要试听课 0 不需要 1 需要',
  `check_adminid` int(11) NOT NULL COMMENT '课时检查 adminid',
  `create_time` int(11) NOT NULL COMMENT '老师信息创建时间',
  `assess_num` int(11) NOT NULL DEFAULT '0' COMMENT '考核次数',
  `textbook_type` int(11) NOT NULL COMMENT '教材版本',
  `grade_part_ex` int(11) NOT NULL COMMENT '年级段 1 小学 2 初中 3 高中',
  `subject` int(11) NOT NULL COMMENT '第1科目',
  `putonghua_is_correctly` int(11) NOT NULL COMMENT '普通话是否标准 0 未设置 1是 2否',
  `dialect_notes` varchar(255) NOT NULL COMMENT '方言备注',
  `jianli` varchar(255) NOT NULL COMMENT '简历',
  `is_good_flag` int(11) NOT NULL DEFAULT '0' COMMENT '是否优秀 0 普通 1 优秀',
  `second_subject` int(11) NOT NULL COMMENT '第二科目',
  `third_subject` int(11) NOT NULL COMMENT '第三科目',
  `interview_access` varchar(255) NOT NULL COMMENT '面试评价',
  `tea_note` varchar(255) NOT NULL COMMENT '教务备注',
  `teacher_money_flag` int(11) NOT NULL COMMENT '老师工资发放类型',
  `trial_lecture_is_pass` tinyint(4) NOT NULL DEFAULT '0' COMMENT ' 试讲是否通过',
  `identity` tinyint(4) NOT NULL DEFAULT '0' COMMENT '老师身份 0 未设置 1 在校学生 2 在职老师',
  `is_test_user` int(11) NOT NULL COMMENT '是否是测试老师用户',
  `is_freeze` int(11) NOT NULL COMMENT '排课权利是否冻结 0否 1是',
  `freeze_adminid` int(11) NOT NULL COMMENT '冻结排课操作人',
  `freeze_time` int(11) NOT NULL COMMENT '冻结时间',
  `freeze_reason` varchar(255) NOT NULL COMMENT '冻结排课原因',
  `un_freeze_time` int(11) NOT NULL COMMENT '解除冻结时间',
  `is_interview_teacher_flag` int(11) NOT NULL COMMENT '是否有面试权限',
  `limit_plan_lesson_type` int(11) NOT NULL COMMENT '0 未限制, 1 一周限排1节课 ,2 一周限排3节课 ,3 一周限排5节课',
  `limit_plan_lesson_time` int(11) NOT NULL COMMENT '排课限制操作时间',
  `limit_plan_lesson_reason` varchar(5000) NOT NULL COMMENT '排课限制原因',
  `limit_plan_lesson_account` varchar(255) NOT NULL COMMENT '排课限制操作人',
  `second_grade` int(11) NOT NULL COMMENT '第二科目对应年级段',
  `third_grade` int(11) NOT NULL COMMENT '第三科目对应年级段',
  `train_through_new` int(11) NOT NULL COMMENT '新入职培训通过 0 未通过 1 已通过',
  `is_week_freeze` int(11) NOT NULL COMMENT '一周排课功能是否冻结 0 否 1 冻结',
  `week_freeze_adminid` int(11) NOT NULL COMMENT '一周冻结排课操作人',
  `week_freeze_time` int(11) NOT NULL COMMENT '一周冻结时间',
  `week_freeze_reason` varchar(255) NOT NULL COMMENT '冻结排课一周原因',
  `train_through_new_time` int(11) NOT NULL COMMENT '通过试讲时间',
  `lesson_hold_flag` int(11) NOT NULL COMMENT '暂停排课 0 不暂停 ,1 暂停',
  `interview_score` int(11) NOT NULL COMMENT '第一科目面试得分',
  `second_interview_score` int(11) NOT NULL COMMENT '第二科目面试得分',
  `test_transfor_per` int(11) NOT NULL COMMENT '试听转化率',
  `week_liveness` int(11) NOT NULL COMMENT '一周活跃度',
  `idcard` int(11) NOT NULL COMMENT '老师身份证号',
  `bankcard` int(11) NOT NULL COMMENT '老师银行证号',
  `bank_address` varchar(255) NOT NULL COMMENT '开户行及支行',
  `bank_account` varchar(255) NOT NULL COMMENT '持卡人姓名',
  `wx_use_flag` int(11) NOT NULL DEFAULT '1' COMMENT '微信工资页面能否看到 0 不能 1 能',
  `permission` varchar(255) NOT NULL COMMENT '老师后台权限',
  `limit_day_lesson_num` int(11) NOT NULL DEFAULT '4' COMMENT '老师每日最大排课数量',
  `limit_week_lesson_num` int(11) NOT NULL DEFAULT '8' COMMENT '老师每周最大排课数量',
  `limit_month_lesson_num` int(11) NOT NULL DEFAULT '0' COMMENT '老师每月最大排课数量',
  `teacher_ref_type` tinyint(4) NOT NULL COMMENT '老师推荐人类型 0 无分类 1 廖祝佳工作室 2 王菊香工作室',
  `research_note` varchar(255) NOT NULL COMMENT '教研备注',
  `lesson_hold_flag_acc` varchar(255) NOT NULL COMMENT '暂停接试听课操作人',
  `lesson_hold_flag_reason` varchar(255) NOT NULL COMMENT '暂停接试听课原因 ',
  `lesson_hold_flag_time` int(11) NOT NULL COMMENT '暂停接试听课操作时间 ',
  `assign_jw_adminid` int(11) NOT NULL COMMENT '分配教务adminid',
  `assign_jw_time` int(11) NOT NULL COMMENT '分配教务时间',
  `week_freeze_warning_flag` int(11) NOT NULL COMMENT '周冻结警告',
  `grade_start` int(11) NOT NULL COMMENT '老师擅长年级范围开始',
  `grade_end` int(11) NOT NULL COMMENT '老师擅长年级范围结束',
  `month_type` tinyint(4) NOT NULL COMMENT '发放工资类型',
  `saturday_lesson_num` int(11) NOT NULL DEFAULT '6' COMMENT '教研老师周六排课数',
  `change_good_time` int(11) NOT NULL COMMENT '更改为优秀老师的时间',
  `is_good_wx_flag` int(11) NOT NULL COMMENT '优秀老师微信通知',
  `not_grade` varchar(255) NOT NULL COMMENT '老师不擅长的年级段',
  `not_grade_limit` varchar(255) NOT NULL COMMENT '限课年级段',
  `week_lesson_count` int(11) NOT NULL DEFAULT '' COMMENT '教研老师一周课时上限',
  `is_record_flag` tinyint(4) NOT NULL COMMENT '是否反馈',
  `lesson_hold_flag_adminid` int(11) NOT NULL COMMENT '暂停试听接课老师对应教务',
  `have_test_lesson_flag` int(11) NOT NULL COMMENT '是否上过试听课标识',
  `test_lesson_num` int(11) NOT NULL COMMENT '老师试听课总数',
  `quit_time` int(11) NOT NULL COMMENT '离职时间',
  `leave_start_time` int(11) NOT NULL COMMENT '请假开始时间',
  `leave_end_time` int(11) NOT NULL COMMENT '请假结束时间',
  `leave_set_adminid` int(11) NOT NULL COMMENT '请假设置人',
  `leave_set_time` int(11) NOT NULL COMMENT '请假设置时间',
  `quit_set_adminid` int(11) NOT NULL COMMENT '离职设置人',
  `leave_reason` varchar(500) NOT NULL COMMENT '请假理由',
  `leave_remove_adminid` int(11) NOT NULL COMMENT '休课解除设置人',
  `leave_remove_time` int(11) NOT NULL COMMENT '休课解除时间',
  `quit_info` varchar(500) NOT NULL COMMENT '离职信息',
  `phone_spare` varchar(16) NOT NULL COMMENT '备用手机号',
  `trial_train_flag` tinyint(4) NOT NULL COMMENT '模拟试听标志 0 未通过 1 通过',
  `second_grade_start` int(11) NOT NULL COMMENT '第二科目开始年级',
  `second_grade_end` int(11) NOT NULL COMMENT '第二科目结束',
  `second_not_grade` varchar(255) NOT NULL COMMENT '第二科目禁止年级',
  `add_acc` varchar(255) NOT NULL COMMENT '老师添加人',
  `part_remarks` varchar(1000) NOT NULL COMMENT '有无在其他机构',
  `bank_phone` varchar(16) NOT NULL COMMENT '银行预留手机号',
  `bank_type` varchar(255) NOT NULL COMMENT '银行类型',
  `user_agent_wx_update` tinyint(4) NOT NULL COMMENT '设备版本更新微信通知',
  `bank_province` varchar(255) NOT NULL COMMENT '银行开户省',
  `bank_city` varchar(255) NOT NULL COMMENT '银行开户市',
  `transfer_teacherid` int(11) NOT NULL COMMENT '被转移的原始老师id',
  `transfer_time` int(11) NOT NULL COMMENT '执行转移的时间',
  PRIMARY KEY (`teacherid`),
  UNIQUE KEY `t_teacher_info_wx_openid_unique` (`wx_openid`),
  KEY `t_teacher_info_nick_index` (`nick`),
  KEY `t_teacher_info_realname_index` (`realname`),
  KEY `grade_limit` (`grade_start`,`grade_end`),
  KEY `phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */
