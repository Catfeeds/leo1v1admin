interface GargsStatic {
	db_name:	string;
	sql:	string;
	page_num:	number;
	page_count:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	courseid	:any;
	lesson_num	:any;
	lesson_name	:any;
	lesson_type	:any;
	userid	:any;
	teacherid	:any;
	assistantid	:any;
	has_quiz	:any;
	lesson_start	:any;
	lesson_end	:any;
	lesson_time	:any;
	lesson_intro	:any;
	lesson_status	:any;
	tea_attend	:any;
	stu_attend	:any;
	real_begin_time	:any;
	real_end_time	:any;
	pause_start_time	:any;
	pause_end_time	:any;
	stu_cw_name	:any;
	stu_cw_upload_time	:any;
	stu_cw_status	:any;
	stu_cw_url	:any;
	tea_cw_name	:any;
	tea_cw_upload_time	:any;
	tea_cw_status	:any;
	tea_cw_url	:any;
	tea_cw_question_list	:any;
	cw_name	:any;
	cw_download_url	:any;
	cw_upload_time	:any;
	cw_send_flag	:any;
	cw_status	:any;
	preview_status	:any;
	del_flag	:any;
	del_time	:any;
	del_forever	:any;
	thumbnail	:any;
	thumb_upload_time	:any;
	draw	:any;
	audio	:any;
	lesson_upload_time	:any;
	teacher_score	:any;
	teacher_comment	:any;
	teacher_effect	:any;
	teacher_quality	:any;
	teacher_interact	:any;
	sys_operator	:any;
	operate_time	:any;
	operate_tip	:any;
	last_modified_time	:any;
	stu_rate_time	:any;
	tea_rate_time	:any;
	stu_score	:any;
	stu_comment	:any;
	stu_attitude	:any;
	stu_attention	:any;
	stu_ability	:any;
	stu_praise	:any;
	parent_confirm	:any;
	lessonid	:any;
	from_type	:any;
	is_complained	:any;
	complain_note	:any;
	stu_stability	:any;
	teacher_last_exist	:any;
	lesson_condition	:any;
	ass_situation	:any;
	tea_situation	:any;
	stu_situation	:any;
	enter_type	:any;
	review_flag	:any;
	subject	:any;
	grade	:any;
	lesson_quiz	:any;
	lesson_quiz_status	:any;
	lesson_quiz_question_list	:any;
	server_type	:any;
	share_forbidden	:any;
	use_ppt	:any;
	ass_comment_audit	:any;
	stu_performance	:any;
	lesson_user_opt_info	:any;
	teacher_clothes	:any;
	from_lessonid	:any;
	can_set_as_from_lessonid	:any;
	packageid	:any;
	lesson_count	:any;
	confirm_flag	:any;
	confirm_adminid	:any;
	confirm_time	:any;
	confirm_reason	:any;
	rand_num	:any;
	level	:any;
	tea_price	:any;
	already_lesson_count	:any;
	origin	:any;
	tea_more_cw_url	:any;
	record_audio_server1	:any;
	record_audio_server2	:any;
	jw_confirm_flag	:any;
	jw_confirm_adminid	:any;
	jw_confirm_time	:any;
	jw_confirm_reason	:any;
	orderid	:any;
	teacher_money_type	:any;
	competition_flag	:any;
	lesson_cancel_reason_type	:any;
	lesson_cancel_reason_next_lesson_time	:any;
	system_version	:any;
	deduct_check_homework	:any;
	deduct_change_class	:any;
	deduct_rate_student	:any;
	deduct_upload_cw	:any;
	deduct_come_late	:any;
	wx_comment_flag	:any;
	wx_upload_flag	:any;
	wx_come_flag	:any;
	wx_homework_flag	:any;
	wx_rate_late_flag	:any;
	wx_tea_price_flag	:any;
	lesson_end_todo_flag	:any;
	lesson_comment_send_email_flag	:any;
	lesson_del_flag	:any;
	lesson_full_num	:any;
	lesson_cancel_time_type	:any;
	enable_video	:any;
	week_comment_num	:any;
	pcm_file_count	:any;
	pcm_file_all_size	:any;
	lesson_user_online_status	:any;
	lesson_login_status	:any;
	lesson_abnormal	:any;
	gen_video_grade	:any;
	lesson_sub_type	:any;
	tea_cw_pic	:any;
	tea_cw_pic_flag	:any;
	train_type	:any;
	attendance_wx_flag	:any;
	attendance_wx_time	:any;
	train_lesson_wx_before	:any;
	train_lesson_wx_after	:any;
	train_email_flag	:any;
	tea_agent	:any;
	stu_agent	:any;
	wx_before_four_hour_cw_flag	:any;
	wx_before_thiry_minute_remind_flag	:any;
	wx_no_comment_count_down_flag	:any;
	trial_train_num	:any;
	wx_absenteeism_flag	:any;
	absenteeism_flag	:any;
}

/*

tofile: 
	 mkdir -p ../table_manage; vi  ../table_manage/query.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/table_manage-query.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			db_name:	$('#id_db_name').val(),
			sql:	$('#id_sql').val()
        });
    }


	$('#id_db_name').val(g_args.db_name);
	$('#id_sql').val(g_args.sql);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">db_name</span>
                <input class="opt-change form-control" id="id_db_name" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">sql</span>
                <input class="opt-change form-control" id="id_sql" />
            </div>
        </div>
*/
