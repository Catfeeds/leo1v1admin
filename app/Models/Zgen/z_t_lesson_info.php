<?php
namespace App\Models\Zgen;
class z_t_lesson_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_lesson_info";


	/*int(10) unsigned */
	const C_courseid='courseid';

	/*int(10) unsigned */
	const C_lesson_num='lesson_num';

	/*varchar(100) */
	const C_lesson_name='lesson_name';

	/*int(10) */
	const C_lesson_type='lesson_type';

	/*int(10) unsigned */
	const C_userid='userid';

	/*int(10) unsigned */
	const C_teacherid='teacherid';

	/*int(10) unsigned */
	const C_assistantid='assistantid';

	/*tinyint(4) */
	const C_has_quiz='has_quiz';

	/*int(10) unsigned */
	const C_lesson_start='lesson_start';

	/*int(10) unsigned */
	const C_lesson_end='lesson_end';

	/*tinyint(4) */
	const C_lesson_time='lesson_time';

	/*varchar(100) */
	const C_lesson_intro='lesson_intro';

	/*tinyint(4) */
	const C_lesson_status='lesson_status';

	/*int(10) unsigned */
	const C_tea_attend='tea_attend';

	/*int(10) unsigned */
	const C_stu_attend='stu_attend';

	/*int(10) unsigned */
	const C_real_begin_time='real_begin_time';

	/*int(10) unsigned */
	const C_real_end_time='real_end_time';

	/*int(10) unsigned */
	const C_pause_start_time='pause_start_time';

	/*int(10) unsigned */
	const C_pause_end_time='pause_end_time';

	/*varchar(100) */
	const C_stu_cw_name='stu_cw_name';

	/*int(10) unsigned */
	const C_stu_cw_upload_time='stu_cw_upload_time';

	/*tinyint(4) */
	const C_stu_cw_status='stu_cw_status';

	/*varchar(100) */
	const C_stu_cw_url='stu_cw_url';

	/*varchar(100) */
	const C_tea_cw_name='tea_cw_name';

	/*int(10) unsigned */
	const C_tea_cw_upload_time='tea_cw_upload_time';

	/*tinyint(4) */
	const C_tea_cw_status='tea_cw_status';

	/*varchar(100) */
	const C_tea_cw_url='tea_cw_url';

	/*varchar(512) */
	const C_tea_cw_question_list='tea_cw_question_list';

	/*varchar(100) */
	const C_cw_name='cw_name';

	/*varchar(100) */
	const C_cw_download_url='cw_download_url';

	/*int(10) unsigned */
	const C_cw_upload_time='cw_upload_time';

	/*tinyint(4) */
	const C_cw_send_flag='cw_send_flag';

	/*tinyint(4) */
	const C_cw_status='cw_status';

	/*tinyint(4) */
	const C_preview_status='preview_status';

	/*tinyint(4) */
	const C_del_flag='del_flag';

	/*int(10) unsigned */
	const C_del_time='del_time';

	/*tinyint(4) */
	const C_del_forever='del_forever';

	/*varchar(100) */
	const C_thumbnail='thumbnail';

	/*int(10) unsigned */
	const C_thumb_upload_time='thumb_upload_time';

	/*varchar(100) */
	const C_draw='draw';

	/*varchar(100) */
	const C_audio='audio';

	/*int(10) unsigned */
	const C_lesson_upload_time='lesson_upload_time';

	/*tinyint(4) */
	const C_teacher_score='teacher_score';

	/*varchar(100) */
	const C_teacher_comment='teacher_comment';

	/*int(10) unsigned */
	const C_teacher_effect='teacher_effect';

	/*int(10) unsigned */
	const C_teacher_quality='teacher_quality';

	/*int(10) unsigned */
	const C_teacher_interact='teacher_interact';

	/*varchar(32) */
	const C_sys_operator='sys_operator';

	/*int(10) unsigned */
	const C_operate_time='operate_time';

	/*varchar(300) */
	const C_operate_tip='operate_tip';

	/*timestamp */
	const C_last_modified_time='last_modified_time';

	/*int(10) unsigned */
	const C_stu_rate_time='stu_rate_time';

	/*int(10) unsigned */
	const C_tea_rate_time='tea_rate_time';

	/*tinyint(4) */
	const C_stu_score='stu_score';

	/*varchar(300) */
	const C_stu_comment='stu_comment';

	/*int(10) unsigned */
	const C_stu_attitude='stu_attitude';

	/*int(10) unsigned */
	const C_stu_attention='stu_attention';

	/*int(10) unsigned */
	const C_stu_ability='stu_ability';

	/*int(10) unsigned */
	const C_stu_praise='stu_praise';

	/*tinyint(4) */
	const C_parent_confirm='parent_confirm';

	/*int(10) unsigned */
	const C_lessonid='lessonid';

	/*int(10) unsigned */
	const C_from_type='from_type';

	/*tinyint(4) */
	const C_is_complained='is_complained';

	/*varchar(128) */
	const C_complain_note='complain_note';

	/*int(10) unsigned */
	const C_stu_stability='stu_stability';

	/*int(10) unsigned */
	const C_teacher_last_exist='teacher_last_exist';

	/*varchar(1024) */
	const C_lesson_condition='lesson_condition';

	/*varchar(1024) */
	const C_ass_situation='ass_situation';

	/*varchar(1024) */
	const C_tea_situation='tea_situation';

	/*varchar(1024) */
	const C_stu_situation='stu_situation';

	/*tinyint(3) unsigned */
	const C_enter_type='enter_type';

	/*tinyint(4) */
	const C_review_flag='review_flag';

	/*tinyint(4) */
	const C_subject='subject';

	/*smallint(6) */
	const C_grade='grade';

	/*varchar(1024) */
	const C_lesson_quiz='lesson_quiz';

	/*tinyint(4) */
	const C_lesson_quiz_status='lesson_quiz_status';

	/*varchar(4096) */
	const C_lesson_quiz_question_list='lesson_quiz_question_list';

	/*tinyint(4) */
	const C_server_type='server_type';

	/*tinyint(4) */
	const C_share_forbidden='share_forbidden';

	/*tinyint(4) */
	const C_use_ppt='use_ppt';

	/*tinyint(4) */
	const C_ass_comment_audit='ass_comment_audit';

	/*blob */
	const C_stu_performance='stu_performance';

	/*blob */
	const C_lesson_user_opt_info='lesson_user_opt_info';

	/*int(11) */
	const C_teacher_clothes='teacher_clothes';

	/*int(11) */
	const C_from_lessonid='from_lessonid';

	/*int(11) */
	const C_can_set_as_from_lessonid='can_set_as_from_lessonid';

	/*int(10) */
	const C_packageid='packageid';

	/*int(11) */
	const C_lesson_count='lesson_count';

	/*int(11) */
	const C_confirm_flag='confirm_flag';

	/*int(11) */
	const C_confirm_adminid='confirm_adminid';

	/*int(11) */
	const C_confirm_time='confirm_time';

	/*varchar(255) */
	const C_confirm_reason='confirm_reason';

	/*int(11) */
	const C_rand_num='rand_num';

	/*int(11) */
	const C_level='level';

	/*int(11) */
	const C_tea_price='tea_price';

	/*int(11) */
	const C_already_lesson_count='already_lesson_count';

	/*varchar(255) */
	const C_origin='origin';

	/*varchar(500) */
	const C_tea_more_cw_url='tea_more_cw_url';

	/*varchar(255) */
	const C_record_audio_server1='record_audio_server1';

	/*varchar(255) */
	const C_record_audio_server2='record_audio_server2';

	/*int(11) */
	const C_jw_confirm_flag='jw_confirm_flag';

	/*int(11) */
	const C_jw_confirm_adminid='jw_confirm_adminid';

	/*int(11) */
	const C_jw_confirm_time='jw_confirm_time';

	/*varchar(255) */
	const C_jw_confirm_reason='jw_confirm_reason';

	/*int(11) */
	const C_orderid='orderid';

	/*int(11) */
	const C_teacher_money_type='teacher_money_type';

	/*int(11) */
	const C_competition_flag='competition_flag';

	/*int(11) */
	const C_lesson_cancel_reason_type='lesson_cancel_reason_type';

	/*int(11) */
	const C_lesson_cancel_reason_next_lesson_time='lesson_cancel_reason_next_lesson_time';

	/*varchar(200) */
	const C_system_version='system_version';

	/*int(11) */
	const C_deduct_check_homework='deduct_check_homework';

	/*int(11) */
	const C_deduct_change_class='deduct_change_class';

	/*int(11) */
	const C_deduct_rate_student='deduct_rate_student';

	/*int(11) */
	const C_deduct_upload_cw='deduct_upload_cw';

	/*int(11) */
	const C_deduct_come_late='deduct_come_late';

	/*int(11) */
	const C_wx_comment_flag='wx_comment_flag';

	/*int(11) */
	const C_wx_upload_flag='wx_upload_flag';

	/*int(11) */
	const C_wx_come_flag='wx_come_flag';

	/*int(11) */
	const C_wx_homework_flag='wx_homework_flag';

	/*int(11) */
	const C_wx_rate_late_flag='wx_rate_late_flag';

	/*int(11) */
	const C_wx_tea_price_flag='wx_tea_price_flag';

	/*int(11) */
	const C_lesson_end_todo_flag='lesson_end_todo_flag';

	/*int(11) */
	const C_lesson_comment_send_email_flag='lesson_comment_send_email_flag';

	/*int(11) */
	const C_lesson_del_flag='lesson_del_flag';

	/*int(11) */
	const C_lesson_full_num='lesson_full_num';

	/*int(11) */
	const C_lesson_cancel_time_type='lesson_cancel_time_type';

	/*int(11) */
	const C_enable_video='enable_video';

	/*int(11) */
	const C_week_comment_num='week_comment_num';

	/*int(11) */
	const C_pcm_file_count='pcm_file_count';

	/*int(11) */
	const C_pcm_file_all_size='pcm_file_all_size';

	/*int(11) */
	const C_lesson_user_online_status='lesson_user_online_status';

	/*int(11) */
	const C_lesson_login_status='lesson_login_status';

	/*varchar(200) */
	const C_lesson_abnormal='lesson_abnormal';

	/*int(11) */
	const C_gen_video_grade='gen_video_grade';

	/*tinyint(4) */
	const C_lesson_sub_type='lesson_sub_type';

	/*text */
	const C_tea_cw_pic='tea_cw_pic';

	/*tinyint(4) */
	const C_tea_cw_pic_flag='tea_cw_pic_flag';

	/*tinyint(4) */
	const C_train_type='train_type';

	/*int(11) */
	const C_attendance_wx_flag='attendance_wx_flag';

	/*int(11) */
	const C_attendance_wx_time='attendance_wx_time';

	/*tinyint(4) */
	const C_train_lesson_wx_before='train_lesson_wx_before';

	/*tinyint(4) */
	const C_train_lesson_wx_after='train_lesson_wx_after';

	/*tinyint(4) */
	const C_train_email_flag='train_email_flag';
	function get_courseid($lessonid ){
		return $this->field_get_value( $lessonid , self::C_courseid );
	}
	function get_lesson_num($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_num );
	}
	function get_lesson_name($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_name );
	}
	function get_lesson_type($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_type );
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
	function get_has_quiz($lessonid ){
		return $this->field_get_value( $lessonid , self::C_has_quiz );
	}
	function get_lesson_start($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_start );
	}
	function get_lesson_end($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_end );
	}
	function get_lesson_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_time );
	}
	function get_lesson_intro($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_intro );
	}
	function get_lesson_status($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_status );
	}
	function get_tea_attend($lessonid ){
		return $this->field_get_value( $lessonid , self::C_tea_attend );
	}
	function get_stu_attend($lessonid ){
		return $this->field_get_value( $lessonid , self::C_stu_attend );
	}
	function get_real_begin_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_real_begin_time );
	}
	function get_real_end_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_real_end_time );
	}
	function get_pause_start_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_pause_start_time );
	}
	function get_pause_end_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_pause_end_time );
	}
	function get_stu_cw_name($lessonid ){
		return $this->field_get_value( $lessonid , self::C_stu_cw_name );
	}
	function get_stu_cw_upload_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_stu_cw_upload_time );
	}
	function get_stu_cw_status($lessonid ){
		return $this->field_get_value( $lessonid , self::C_stu_cw_status );
	}
	function get_stu_cw_url($lessonid ){
		return $this->field_get_value( $lessonid , self::C_stu_cw_url );
	}
	function get_tea_cw_name($lessonid ){
		return $this->field_get_value( $lessonid , self::C_tea_cw_name );
	}
	function get_tea_cw_upload_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_tea_cw_upload_time );
	}
	function get_tea_cw_status($lessonid ){
		return $this->field_get_value( $lessonid , self::C_tea_cw_status );
	}
	function get_tea_cw_url($lessonid ){
		return $this->field_get_value( $lessonid , self::C_tea_cw_url );
	}
	function get_tea_cw_question_list($lessonid ){
		return $this->field_get_value( $lessonid , self::C_tea_cw_question_list );
	}
	function get_cw_name($lessonid ){
		return $this->field_get_value( $lessonid , self::C_cw_name );
	}
	function get_cw_download_url($lessonid ){
		return $this->field_get_value( $lessonid , self::C_cw_download_url );
	}
	function get_cw_upload_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_cw_upload_time );
	}
	function get_cw_send_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_cw_send_flag );
	}
	function get_cw_status($lessonid ){
		return $this->field_get_value( $lessonid , self::C_cw_status );
	}
	function get_preview_status($lessonid ){
		return $this->field_get_value( $lessonid , self::C_preview_status );
	}
	function get_del_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_del_flag );
	}
	function get_del_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_del_time );
	}
	function get_del_forever($lessonid ){
		return $this->field_get_value( $lessonid , self::C_del_forever );
	}
	function get_thumbnail($lessonid ){
		return $this->field_get_value( $lessonid , self::C_thumbnail );
	}
	function get_thumb_upload_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_thumb_upload_time );
	}
	function get_draw($lessonid ){
		return $this->field_get_value( $lessonid , self::C_draw );
	}
	function get_audio($lessonid ){
		return $this->field_get_value( $lessonid , self::C_audio );
	}
	function get_lesson_upload_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_upload_time );
	}
	function get_teacher_score($lessonid ){
		return $this->field_get_value( $lessonid , self::C_teacher_score );
	}
	function get_teacher_comment($lessonid ){
		return $this->field_get_value( $lessonid , self::C_teacher_comment );
	}
	function get_teacher_effect($lessonid ){
		return $this->field_get_value( $lessonid , self::C_teacher_effect );
	}
	function get_teacher_quality($lessonid ){
		return $this->field_get_value( $lessonid , self::C_teacher_quality );
	}
	function get_teacher_interact($lessonid ){
		return $this->field_get_value( $lessonid , self::C_teacher_interact );
	}
	function get_sys_operator($lessonid ){
		return $this->field_get_value( $lessonid , self::C_sys_operator );
	}
	function get_operate_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_operate_time );
	}
	function get_operate_tip($lessonid ){
		return $this->field_get_value( $lessonid , self::C_operate_tip );
	}
	function get_last_modified_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_last_modified_time );
	}
	function get_stu_rate_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_stu_rate_time );
	}
	function get_tea_rate_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_tea_rate_time );
	}
	function get_stu_score($lessonid ){
		return $this->field_get_value( $lessonid , self::C_stu_score );
	}
	function get_stu_comment($lessonid ){
		return $this->field_get_value( $lessonid , self::C_stu_comment );
	}
	function get_stu_attitude($lessonid ){
		return $this->field_get_value( $lessonid , self::C_stu_attitude );
	}
	function get_stu_attention($lessonid ){
		return $this->field_get_value( $lessonid , self::C_stu_attention );
	}
	function get_stu_ability($lessonid ){
		return $this->field_get_value( $lessonid , self::C_stu_ability );
	}
	function get_stu_praise($lessonid ){
		return $this->field_get_value( $lessonid , self::C_stu_praise );
	}
	function get_parent_confirm($lessonid ){
		return $this->field_get_value( $lessonid , self::C_parent_confirm );
	}
	function get_from_type($lessonid ){
		return $this->field_get_value( $lessonid , self::C_from_type );
	}
	function get_is_complained($lessonid ){
		return $this->field_get_value( $lessonid , self::C_is_complained );
	}
	function get_complain_note($lessonid ){
		return $this->field_get_value( $lessonid , self::C_complain_note );
	}
	function get_stu_stability($lessonid ){
		return $this->field_get_value( $lessonid , self::C_stu_stability );
	}
	function get_teacher_last_exist($lessonid ){
		return $this->field_get_value( $lessonid , self::C_teacher_last_exist );
	}
	function get_lesson_condition($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_condition );
	}
	function get_ass_situation($lessonid ){
		return $this->field_get_value( $lessonid , self::C_ass_situation );
	}
	function get_tea_situation($lessonid ){
		return $this->field_get_value( $lessonid , self::C_tea_situation );
	}
	function get_stu_situation($lessonid ){
		return $this->field_get_value( $lessonid , self::C_stu_situation );
	}
	function get_enter_type($lessonid ){
		return $this->field_get_value( $lessonid , self::C_enter_type );
	}
	function get_review_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_review_flag );
	}
	function get_subject($lessonid ){
		return $this->field_get_value( $lessonid , self::C_subject );
	}
	function get_grade($lessonid ){
		return $this->field_get_value( $lessonid , self::C_grade );
	}
	function get_lesson_quiz($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_quiz );
	}
	function get_lesson_quiz_status($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_quiz_status );
	}
	function get_lesson_quiz_question_list($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_quiz_question_list );
	}
	function get_server_type($lessonid ){
		return $this->field_get_value( $lessonid , self::C_server_type );
	}
	function get_share_forbidden($lessonid ){
		return $this->field_get_value( $lessonid , self::C_share_forbidden );
	}
	function get_use_ppt($lessonid ){
		return $this->field_get_value( $lessonid , self::C_use_ppt );
	}
	function get_ass_comment_audit($lessonid ){
		return $this->field_get_value( $lessonid , self::C_ass_comment_audit );
	}
	function get_stu_performance($lessonid ){
		return $this->field_get_value( $lessonid , self::C_stu_performance );
	}
	function get_lesson_user_opt_info($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_user_opt_info );
	}
	function get_teacher_clothes($lessonid ){
		return $this->field_get_value( $lessonid , self::C_teacher_clothes );
	}
	function get_from_lessonid($lessonid ){
		return $this->field_get_value( $lessonid , self::C_from_lessonid );
	}
	function get_can_set_as_from_lessonid($lessonid ){
		return $this->field_get_value( $lessonid , self::C_can_set_as_from_lessonid );
	}
	function get_packageid($lessonid ){
		return $this->field_get_value( $lessonid , self::C_packageid );
	}
	function get_lesson_count($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_count );
	}
	function get_confirm_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_confirm_flag );
	}
	function get_confirm_adminid($lessonid ){
		return $this->field_get_value( $lessonid , self::C_confirm_adminid );
	}
	function get_confirm_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_confirm_time );
	}
	function get_confirm_reason($lessonid ){
		return $this->field_get_value( $lessonid , self::C_confirm_reason );
	}
	function get_rand_num($lessonid ){
		return $this->field_get_value( $lessonid , self::C_rand_num );
	}
	function get_level($lessonid ){
		return $this->field_get_value( $lessonid , self::C_level );
	}
	function get_tea_price($lessonid ){
		return $this->field_get_value( $lessonid , self::C_tea_price );
	}
	function get_already_lesson_count($lessonid ){
		return $this->field_get_value( $lessonid , self::C_already_lesson_count );
	}
	function get_origin($lessonid ){
		return $this->field_get_value( $lessonid , self::C_origin );
	}
	function get_tea_more_cw_url($lessonid ){
		return $this->field_get_value( $lessonid , self::C_tea_more_cw_url );
	}
	function get_record_audio_server1($lessonid ){
		return $this->field_get_value( $lessonid , self::C_record_audio_server1 );
	}
	function get_record_audio_server2($lessonid ){
		return $this->field_get_value( $lessonid , self::C_record_audio_server2 );
	}
	function get_jw_confirm_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_jw_confirm_flag );
	}
	function get_jw_confirm_adminid($lessonid ){
		return $this->field_get_value( $lessonid , self::C_jw_confirm_adminid );
	}
	function get_jw_confirm_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_jw_confirm_time );
	}
	function get_jw_confirm_reason($lessonid ){
		return $this->field_get_value( $lessonid , self::C_jw_confirm_reason );
	}
	function get_orderid($lessonid ){
		return $this->field_get_value( $lessonid , self::C_orderid );
	}
	function get_teacher_money_type($lessonid ){
		return $this->field_get_value( $lessonid , self::C_teacher_money_type );
	}
	function get_competition_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_competition_flag );
	}
	function get_lesson_cancel_reason_type($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_cancel_reason_type );
	}
	function get_lesson_cancel_reason_next_lesson_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_cancel_reason_next_lesson_time );
	}
	function get_system_version($lessonid ){
		return $this->field_get_value( $lessonid , self::C_system_version );
	}
	function get_deduct_check_homework($lessonid ){
		return $this->field_get_value( $lessonid , self::C_deduct_check_homework );
	}
	function get_deduct_change_class($lessonid ){
		return $this->field_get_value( $lessonid , self::C_deduct_change_class );
	}
	function get_deduct_rate_student($lessonid ){
		return $this->field_get_value( $lessonid , self::C_deduct_rate_student );
	}
	function get_deduct_upload_cw($lessonid ){
		return $this->field_get_value( $lessonid , self::C_deduct_upload_cw );
	}
	function get_deduct_come_late($lessonid ){
		return $this->field_get_value( $lessonid , self::C_deduct_come_late );
	}
	function get_wx_comment_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_wx_comment_flag );
	}
	function get_wx_upload_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_wx_upload_flag );
	}
	function get_wx_come_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_wx_come_flag );
	}
	function get_wx_homework_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_wx_homework_flag );
	}
	function get_wx_rate_late_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_wx_rate_late_flag );
	}
	function get_wx_tea_price_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_wx_tea_price_flag );
	}
	function get_lesson_end_todo_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_end_todo_flag );
	}
	function get_lesson_comment_send_email_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_comment_send_email_flag );
	}
	function get_lesson_del_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_del_flag );
	}
	function get_lesson_full_num($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_full_num );
	}
	function get_lesson_cancel_time_type($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_cancel_time_type );
	}
	function get_enable_video($lessonid ){
		return $this->field_get_value( $lessonid , self::C_enable_video );
	}
	function get_week_comment_num($lessonid ){
		return $this->field_get_value( $lessonid , self::C_week_comment_num );
	}
	function get_pcm_file_count($lessonid ){
		return $this->field_get_value( $lessonid , self::C_pcm_file_count );
	}
	function get_pcm_file_all_size($lessonid ){
		return $this->field_get_value( $lessonid , self::C_pcm_file_all_size );
	}
	function get_lesson_user_online_status($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_user_online_status );
	}
	function get_lesson_login_status($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_login_status );
	}
	function get_lesson_abnormal($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_abnormal );
	}
	function get_gen_video_grade($lessonid ){
		return $this->field_get_value( $lessonid , self::C_gen_video_grade );
	}
	function get_lesson_sub_type($lessonid ){
		return $this->field_get_value( $lessonid , self::C_lesson_sub_type );
	}
	function get_tea_cw_pic($lessonid ){
		return $this->field_get_value( $lessonid , self::C_tea_cw_pic );
	}
	function get_tea_cw_pic_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_tea_cw_pic_flag );
	}
	function get_train_type($lessonid ){
		return $this->field_get_value( $lessonid , self::C_train_type );
	}
	function get_attendance_wx_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_attendance_wx_flag );
	}
	function get_attendance_wx_time($lessonid ){
		return $this->field_get_value( $lessonid , self::C_attendance_wx_time );
	}
	function get_train_lesson_wx_before($lessonid ){
		return $this->field_get_value( $lessonid , self::C_train_lesson_wx_before );
	}
	function get_train_lesson_wx_after($lessonid ){
		return $this->field_get_value( $lessonid , self::C_train_lesson_wx_after );
	}
	function get_train_email_flag($lessonid ){
		return $this->field_get_value( $lessonid , self::C_train_email_flag );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="lessonid";
        $this->field_table_name="db_weiyi.t_lesson_info";
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
  CREATE TABLE `t_lesson_info` (
  `courseid` int(10) unsigned NOT NULL COMMENT '课程id',
  `lesson_num` int(10) unsigned NOT NULL COMMENT '第几次课',
  `lesson_name` varchar(100) DEFAULT NULL COMMENT '课程名',
  `lesson_type` int(10) NOT NULL COMMENT '0 常规 1赠送 2 试听 3 续费 1001 可交互公开课 1002 不可交互公开课 1003 1v1公开课 3001 小班课 4001 机器人课',
  `userid` int(10) unsigned NOT NULL COMMENT '学生id',
  `teacherid` int(10) unsigned NOT NULL COMMENT '教课老师id',
  `assistantid` int(10) unsigned NOT NULL COMMENT '助教id',
  `has_quiz` tinyint(4) NOT NULL DEFAULT '0' COMMENT '上课完成之后是否有测验0无测验，1有测验',
  `lesson_start` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上课开始时间',
  `lesson_end` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上课结束时间',
  `lesson_time` tinyint(4) NOT NULL DEFAULT '0' COMMENT '课程在当天的时间段 目前 1 8：00-9：30 2 10：00-11：30 3 12：30-14：00 4 14：15-15：45 5 16：00-17：30 6 18：30-20：00 7 20：15-21：45',
  `lesson_intro` varchar(100) NOT NULL DEFAULT '' COMMENT '课程知识点',
  `lesson_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '上课状态（0 未开始 1 课程正在进行 2 本次课结束 3 课程终结）',
  `tea_attend` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '老师进入课堂时间',
  `stu_attend` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '学生进入课堂时间',
  `real_begin_time` int(10) unsigned DEFAULT '0' COMMENT '老师决定的课程开始时间',
  `real_end_time` int(10) unsigned DEFAULT '0' COMMENT '老师决定的课程结束时间',
  `pause_start_time` int(10) unsigned DEFAULT '0' COMMENT '课程暂停开始的时间',
  `pause_end_time` int(10) unsigned DEFAULT '0' COMMENT '课程暂停结束的时间 一次课程只允许暂停一次',
  `stu_cw_name` varchar(100) DEFAULT NULL,
  `stu_cw_upload_time` int(10) unsigned NOT NULL COMMENT '学生课件上传时间',
  `stu_cw_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '学生课件状态（0 未上传 1 已上传）',
  `stu_cw_url` varchar(100) NOT NULL COMMENT '学生课件地址',
  `tea_cw_name` varchar(100) NOT NULL COMMENT '老师课件名称',
  `tea_cw_upload_time` int(10) unsigned NOT NULL COMMENT '教师课件上传时间',
  `tea_cw_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '教师课件状态（0 未上传 1 已上传）',
  `tea_cw_url` varchar(100) NOT NULL COMMENT '老师课件地址',
  `tea_cw_question_list` varchar(512) DEFAULT NULL COMMENT '课件题目列表',
  `cw_name` varchar(100) NOT NULL COMMENT '课件名称',
  `cw_download_url` varchar(100) NOT NULL COMMENT '课件下载地址',
  `cw_upload_time` int(10) unsigned NOT NULL COMMENT '课件上传时间',
  `cw_send_flag` tinyint(4) NOT NULL DEFAULT '0' COMMENT '课件是否发送给学生（0未发送1已发送）',
  `cw_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '课件状态（0 未上传 1 已上传）',
  `preview_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '预习状态0未预习，1已预习',
  `del_flag` tinyint(4) NOT NULL DEFAULT '0' COMMENT '课件是否已删除（0未删除，1已删除）',
  `del_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '课件删除时间（删除后7天，如果再次触发课件查询>，永久删除）',
  `del_forever` tinyint(4) NOT NULL DEFAULT '0' COMMENT '课件是否已经永久删除（0 未删除 1已删除）',
  `thumbnail` varchar(100) NOT NULL DEFAULT '' COMMENT '缩略图',
  `thumb_upload_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '缩略图上传时间',
  `draw` varchar(100) NOT NULL DEFAULT '' COMMENT '画笔信息',
  `audio` varchar(100) NOT NULL DEFAULT '' COMMENT '声音信息',
  `lesson_upload_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '讲课内容提交时间',
  `teacher_score` tinyint(4) NOT NULL DEFAULT '0' COMMENT '老师的评分',
  `teacher_comment` varchar(100) NOT NULL DEFAULT '' COMMENT '老师评价',
  `teacher_effect` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '老师上课效果',
  `teacher_quality` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '老师课件质量',
  `teacher_interact` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '老师课堂互动',
  `sys_operator` varchar(32) NOT NULL COMMENT '排课人',
  `operate_time` int(10) unsigned NOT NULL COMMENT '排课时间',
  `operate_tip` varchar(300) NOT NULL COMMENT '排课状况',
  `last_modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后修改时间',
  `stu_rate_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '学生评论老师的时间',
  `tea_rate_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '老师评论学生的时间',
  `stu_score` tinyint(4) NOT NULL DEFAULT '0' COMMENT '学生的评分',
  `stu_comment` varchar(300) NOT NULL DEFAULT '' COMMENT '学生的评价',
  `stu_attitude` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '学习态度',
  `stu_attention` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上课注意力',
  `stu_ability` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '应用能力',
  `stu_praise` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '学生获取到的赞的个数',
  `parent_confirm` tinyint(4) NOT NULL DEFAULT '0' COMMENT '家长对本次可进行确认',
  `lessonid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '课次id',
  `from_type` int(10) unsigned NOT NULL COMMENT '0:课程包,1:按课时购买的',
  `is_complained` tinyint(4) NOT NULL DEFAULT '0' COMMENT '课程是否被投诉 0 未被投诉 1 已被投诉',
  `complain_note` varchar(128) NOT NULL DEFAULT '' COMMENT '投诉原因',
  `stu_stability` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '学生端的系统稳定性',
  `teacher_last_exist` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '老师最后一次退出时间',
  `lesson_condition` varchar(1024) DEFAULT NULL,
  `ass_situation` varchar(1024) NOT NULL DEFAULT '' COMMENT '助教用户状态',
  `tea_situation` varchar(1024) NOT NULL DEFAULT '' COMMENT '老师用户状态',
  `stu_situation` varchar(1024) NOT NULL DEFAULT '' COMMENT '学生用户状态',
  `enter_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0 正常1v1课程 1 学生在表中有对应课程 2 学生表中无对应课程',
  `review_flag` tinyint(4) NOT NULL DEFAULT '0' COMMENT '复习上课视频 0未复习 1已经复习',
  `subject` tinyint(4) NOT NULL DEFAULT '0' COMMENT '科目 0未设定 1语文 2数学 3英语 4化学 5物理 6生物 7政治 8历史 9地理',
  `grade` smallint(6) NOT NULL DEFAULT '0' COMMENT '科目 0未设定 1语文 2数学 3英语 4化学 5物理 6生物 7政治 8历史 9地理',
  `lesson_quiz` varchar(1024) NOT NULL DEFAULT '' COMMENT '课堂测验',
  `lesson_quiz_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '课堂测验状态０未上传１已上传',
  `lesson_quiz_question_list` varchar(4096) NOT NULL,
  `server_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 telpresence 2 agora',
  `share_forbidden` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否禁止分享 0 允许分享１禁止分享',
  `use_ppt` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否使用ppt，0不使用，１使用',
  `ass_comment_audit` tinyint(4) NOT NULL DEFAULT '0' COMMENT '助教对老师评价的审核状态0未评价1未审批 2未通过 3已经通过',
  `stu_performance` blob,
  `lesson_user_opt_info` blob COMMENT '用户课堂情况数据',
  `teacher_clothes` int(11) DEFAULT '0' COMMENT '老师服饰(暂不使用)',
  `from_lessonid` int(11) NOT NULL COMMENT '机器人课程的源lessonid',
  `can_set_as_from_lessonid` int(11) NOT NULL COMMENT '是否可以设置为from_lessonid',
  `packageid` int(10) NOT NULL COMMENT '课程包id',
  `lesson_count` int(11) NOT NULL COMMENT '课时数 *100',
  `confirm_flag` int(11) NOT NULL COMMENT '课时确认 0:未确认,1:有效课程 2:无效课程,3无效课程,需给老师工资',
  `confirm_adminid` int(11) NOT NULL COMMENT '课时确认人',
  `confirm_time` int(11) NOT NULL COMMENT '课时确认时间',
  `confirm_reason` varchar(255) NOT NULL COMMENT '课时确认原因',
  `rand_num` int(11) NOT NULL COMMENT '公开课随机人数',
  `level` int(11) NOT NULL COMMENT '教师等级',
  `tea_price` int(11) NOT NULL COMMENT '老师金额',
  `already_lesson_count` int(11) NOT NULL COMMENT '老师当前累计课时',
  `origin` varchar(255) NOT NULL COMMENT '渠道',
  `tea_more_cw_url` varchar(500) NOT NULL COMMENT '老师更多课件',
  `record_audio_server1` varchar(255) NOT NULL COMMENT '声音记录服务器1',
  `record_audio_server2` varchar(255) NOT NULL COMMENT '声音记录服务器2',
  `jw_confirm_flag` int(11) NOT NULL COMMENT '教务课时确认   0:未确认,1:有效课程 2:无效课程,',
  `jw_confirm_adminid` int(11) NOT NULL COMMENT '教务课时确认人',
  `jw_confirm_time` int(11) NOT NULL COMMENT '教务课时确认时间',
  `jw_confirm_reason` varchar(255) NOT NULL COMMENT '教务课时确认原因',
  `orderid` int(11) NOT NULL DEFAULT '0' COMMENT '消耗的课时所属合同',
  `teacher_money_type` int(11) NOT NULL DEFAULT '0' COMMENT '老师工资类型',
  `competition_flag` int(11) NOT NULL DEFAULT '0' COMMENT '竞赛标志 0 常规课,1竞赛课',
  `lesson_cancel_reason_type` int(11) NOT NULL COMMENT '课程取消 原因',
  `lesson_cancel_reason_next_lesson_time` int(11) NOT NULL COMMENT '换时间 ,调整到什么时间',
  `system_version` varchar(200) NOT NULL COMMENT '学生本次课的设备版本信息',
  `deduct_check_homework` int(11) NOT NULL COMMENT '学生提交作业后,48小时未批改作业',
  `deduct_change_class` int(11) NOT NULL COMMENT '换课未提前24小时',
  `deduct_rate_student` int(11) NOT NULL COMMENT '未对学生评价(试听 课后45分钟,常规 2天)',
  `deduct_upload_cw` int(11) NOT NULL COMMENT '课前未上传讲义',
  `deduct_come_late` int(11) NOT NULL COMMENT '上课迟到5分钟',
  `wx_comment_flag` int(11) NOT NULL COMMENT '微信通知课后评价学生 0 未通知 1 已通知 2已通知但老师未绑定微信号',
  `wx_upload_flag` int(11) NOT NULL COMMENT '微信通知课前上传讲义 0 未通知 1 已通知 2已通知但老师未绑定微信号',
  `wx_come_flag` int(11) NOT NULL COMMENT '微信通知上课迟到 0 未通知 1 已通知 2已通知但老师未绑定微信号',
  `wx_homework_flag` int(11) NOT NULL COMMENT '微信通知微信未批改 0 未通知 1 已通知 2已通知但老师未绑定微信号',
  `wx_rate_late_flag` int(11) NOT NULL COMMENT '微信通知超时未评价 0 未通知 1 已通知 2已通知但老师未绑定微信号',
  `wx_tea_price_flag` int(11) NOT NULL COMMENT '微信通知课后老师金额 0 未通知 1 已通知 2已通知但老师未绑定微信号',
  `lesson_end_todo_flag` int(11) NOT NULL DEFAULT '0' COMMENT '0 课堂结束未发送讲义等, 1 已处理',
  `lesson_comment_send_email_flag` int(11) NOT NULL DEFAULT '0' COMMENT '0 未发送课堂反馈, 1 已发送',
  `lesson_del_flag` int(11) NOT NULL COMMENT '课程删除标识',
  `lesson_full_num` int(11) NOT NULL COMMENT '全勤奖标示',
  `lesson_cancel_time_type` int(11) NOT NULL COMMENT '课程取消时间类型 1 ４小时以内　２４小时以外',
  `enable_video` int(11) NOT NULL COMMENT '课堂是否开启视频',
  `week_comment_num` int(11) NOT NULL COMMENT '周评价次数 0 每节都评价 num>0 每周评价num节课',
  `pcm_file_count` int(11) NOT NULL COMMENT 'pcm生成的文件数',
  `pcm_file_all_size` int(11) NOT NULL COMMENT 'pcm生成的文件总大小',
  `lesson_user_online_status` int(11) NOT NULL,
  `lesson_login_status` int(11) NOT NULL COMMENT '0:未设置,1:老师学生都登录过,2: 老师学生有一个没登录',
  `lesson_abnormal` varchar(200) NOT NULL COMMENT '老师反馈课程异常状况',
  `gen_video_grade` int(11) NOT NULL COMMENT '视频是否优先生成',
  `lesson_sub_type` tinyint(4) NOT NULL COMMENT '课程子分类',
  `tea_cw_pic` text NOT NULL COMMENT '老师上传的图片',
  `tea_cw_pic_flag` tinyint(4) NOT NULL COMMENT '老师讲义是否转化为图片',
  `train_type` tinyint(4) NOT NULL COMMENT '培训课程的类型 ',
  `attendance_wx_flag` int(11) NOT NULL COMMENT '课程取消考勤调休微信通知',
  `attendance_wx_time` int(11) NOT NULL COMMENT '课程取消考勤调休微信通知时间',
  `train_lesson_wx_before` tinyint(4) NOT NULL COMMENT '1对1面试 课前1小时发送微信通知',
  `train_lesson_wx_after` tinyint(4) NOT NULL COMMENT '1对1面试 课后1小时发送微信通知',
  `train_email_flag` tinyint(4) NOT NULL COMMENT '1对1面试邮件发送标识',
  PRIMARY KEY (`lessonid`),
  KEY `courseid` (`courseid`,`lesson_num`),
  KEY `userid` (`userid`),
  KEY `teacherid` (`teacherid`),
  KEY `lesson_start` (`lesson_start`),
  KEY `userid_2` (`userid`,`lesson_start`),
  KEY `teacherid_2` (`teacherid`,`lesson_start`),
  KEY `t_lesson_info_lesson_end_index` (`lesson_end`)
) ENGINE=InnoDB AUTO_INCREMENT=63023 DEFAULT CHARSET=utf8
 */
