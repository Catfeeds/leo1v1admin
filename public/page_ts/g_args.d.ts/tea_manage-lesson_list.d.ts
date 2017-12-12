interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	studentid:	number;
	teacherid:	number;
	confirm_flag:	string;//枚举列表: App\Enums\Econfirm_flag
 	seller_adminid:	number;
	lesson_status:	number;
	assistantid:	number;
	grade:	string;//枚举列表: App\Enums\Egrade
 	test_seller_id:	number;
	test_seller_adminid:	number;
	has_performance:	number;
	fulltime_flag:	number;
	lesson_user_online_status:	number;//\App\Enums\Eset_boolean
	lesson_type:	number;
	subject:	number;
	lesson_count:	number;
	lesson_cancel_reason_type:	number;
	lesson_del_flag:	number;
	has_video_flag:	number;//\App\Enums\Eboolean
	is_with_test_user:	number;
	seller_flag:	number;
	lessonid:	number;
	origin:	string;
	page_num:	number;
	page_count:	number;
	fulltime_teacher_type:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	cc_account	:any;
	lessonid	:any;
	lesson_del_flag	:any;
	courseid	:any;
	pcm_file_all_size	:any;
	pcm_file_count	:any;
	lesson_type	:any;
	lesson_count	:any;
	lesson_cancel_reason_type	:any;
	lesson_user_online_status	:any;
	teacherid	:any;
	origin	:any;
	system_version	:any;
	record_audio_server1	:any;
	record_audio_server2	:any;
	lesson_cancel_time_type	:any;
	lesson_start	:any;
	lesson_end	:any;
	real_begin_time	:any;
	gen_video_grade	:any;
	assistantid	:any;
	teacher_money_type	:any;
	stu_id	:any;
	stu_phone	:any;
	stu_nick	:any;
	stu_user_agent	:any;
	origin_str	:any;
	stu_email	:any;
	work_intro	:any;
	work_status	:any;
	issue_url	:any;
	finish_url	:any;
	check_url	:any;
	tea_research_url	:any;
	ass_research_url	:any;
	score	:any;
	issue_time	:any;
	finish_time	:any;
	check_time	:any;
	tea_research_time	:any;
	ass_research_time	:any;
	enable_video	:any;
	lesson_status	:any;
	stu_score	:any;
	stu_comment	:any;
	stu_attitude	:any;
	stu_attention	:any;
	stu_ability	:any;
	stu_stability	:any;
	teacher_score	:any;
	teacher_comment	:any;
	tea_rate_time	:any;
	lesson_intro	:any;
	teacher_effect	:any;
	teacher_quality	:any;
	teacher_interact	:any;
	stu_praise	:any;
	stu_cw_upload_time	:any;
	stu_cw_status	:any;
	stu_cw_url	:any;
	tea_cw_name	:any;
	tea_cw_upload_time	:any;
	tea_cw_status	:any;
	use_ppt	:any;
	tea_cw_url	:any;
	is_complained	:any;
	lesson_upload_time	:any;
	stu_performance	:any;
	audio	:any;
	draw	:any;
	lesson_cancel_reason_next_lesson_time	:any;
	lesson_quiz	:any;
	lesson_quiz_status	:any;
	subject	:any;
	grade	:any;
	confirm_flag	:any;
	confirm_adminid	:any;
	confirm_time	:any;
	confirm_reason	:any;
	lesson_num	:any;
	tea_price	:any;
	level	:any;
	require_adminid	:any;
	fa_phone	:any;
	lesson_name	:any;
	deduct_come_late	:any;
	deduct_change_class	:any;
	deduct_upload_cw	:any;
	deduct_rate_student	:any;
	deduct_check_homework	:any;
	lesson_full_num	:any;
	ass_test_lesson_type	:any;
	require_lesson_success_flow_status	:any;
	success_flag	:any;
	test_confirm_adminid	:any;
	test_confirm_time	:any;
	test_lesson_fail_flag	:any;
	fail_greater_4_hour_flag	:any;
	current_server	:any;
	fail_reason	:any;
	number	:any;
	performance_status	:any;
	performance	:any;
	new_test_listen	:any;
	lesson_time	:any;
	lesson_cancel_reason_type_str	:any;
	require_lesson_success_flow_status_str	:any;
	assistant_nick	:any;
	lesson_end_str	:any;
	real_begin_time_str	:any;
	lesson_status_str	:any;
	lesson_vedio_flag	:any;
	lesson_vedio_flag_str	:any;
	stu_cw_status_str	:any;
	tea_cw_status_str	:any;
	work_status_str	:any;
	lesson_quiz_status_str	:any;
	is_complained_str	:any;
	homework_url	:any;
	lesson_type_str	:any;
	level_str	:any;
	teacher_money_type_str	:any;
	tea_nick	:any;
	require_admin_nick	:any;
	teacher_effect_str	:any;
	teacher_quality_str	:any;
	teacher_interact_str	:any;
	stu_stability_str	:any;
	lesson_diff	:any;
	lesson_user_online_status_str	:any;
	lesson_del_flag_str	:any;
	room_name	:any;
	confirm_admin_nick	:any;
	confirm_flag_str	:any;
	grade_str	:any;
	subject_str	:any;
	ass_test_lesson_type_str	:any;
	lesson_deduct	:any;
	test_lesson_fail_flag_str	:any;
	success_flag_str	:any;
	test_confirm_time_str	:any;
	test_confirm_admin_nick	:any;
}

/*

tofile: 
	 mkdir -p ../tea_manage; vi  ../tea_manage/lesson_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-lesson_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		studentid:	$('#id_studentid').val(),
		teacherid:	$('#id_teacherid').val(),
		confirm_flag:	$('#id_confirm_flag').val(),
		seller_adminid:	$('#id_seller_adminid').val(),
		lesson_status:	$('#id_lesson_status').val(),
		assistantid:	$('#id_assistantid').val(),
		grade:	$('#id_grade').val(),
		test_seller_id:	$('#id_test_seller_id').val(),
		test_seller_adminid:	$('#id_test_seller_adminid').val(),
		has_performance:	$('#id_has_performance').val(),
		fulltime_flag:	$('#id_fulltime_flag').val(),
		lesson_user_online_status:	$('#id_lesson_user_online_status').val(),
		lesson_type:	$('#id_lesson_type').val(),
		subject:	$('#id_subject').val(),
		lesson_count:	$('#id_lesson_count').val(),
		lesson_cancel_reason_type:	$('#id_lesson_cancel_reason_type').val(),
		lesson_del_flag:	$('#id_lesson_del_flag').val(),
		has_video_flag:	$('#id_has_video_flag').val(),
		is_with_test_user:	$('#id_is_with_test_user').val(),
		seller_flag:	$('#id_seller_flag').val(),
		lessonid:	$('#id_lessonid').val(),
		origin:	$('#id_origin').val(),
		fulltime_teacher_type:	$('#id_fulltime_teacher_type').val()
		});
}
$(function(){

	Enum_map.append_option_list("set_boolean",$("#id_lesson_user_online_status"));
	Enum_map.append_option_list("boolean",$("#id_has_video_flag"));

	$('#id_date_range').select_date_range({
		'date_type' : g_args.date_type,
		'opt_date_type' : g_args.opt_date_type,
		'start_time'    : g_args.start_time,
		'end_time'      : g_args.end_time,
		date_type_config : JSON.parse( g_args.date_type_config),
		onQuery :function() {
			load_data();
		});
	$('#id_studentid').admin_select_user_new({
		"user_type"    : "student",
		"select_value" : g_args.studentid,
		"onChange"     : load_data,
		"th_input_id"  : "th_studentid",
<<<<<<< HEAD
		"can_sellect_all_flag"     : true
=======
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
>>>>>>> a7ac4bbec61eb4f093f581e623678aa32bb89f16
	});
	$('#id_teacherid').admin_select_user_new({
		"user_type"    : "teacher",
		"select_value" : g_args.teacherid,
		"onChange"     : load_data,
		"th_input_id"  : "th_teacherid",
<<<<<<< HEAD
		"can_sellect_all_flag"     : true
=======
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
>>>>>>> a7ac4bbec61eb4f093f581e623678aa32bb89f16
	});
	$('#id_confirm_flag').admin_set_select_field({
		"enum_type"    : "confirm_flag",
		"select_value" : g_args.confirm_flag,
		"onChange"     : load_data,
		"th_input_id"  : "th_confirm_flag",
<<<<<<< HEAD
		"btn_id_config"     : {}
=======
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
>>>>>>> a7ac4bbec61eb4f093f581e623678aa32bb89f16
	});
	$('#id_seller_adminid').val(g_args.seller_adminid);
	$('#id_lesson_status').val(g_args.lesson_status);
	$('#id_assistantid').admin_select_user_new({
		"user_type"    : "assistant",
		"select_value" : g_args.assistantid,
		"onChange"     : load_data,
		"th_input_id"  : "th_assistantid",
<<<<<<< HEAD
		"can_sellect_all_flag"     : true
=======
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
>>>>>>> a7ac4bbec61eb4f093f581e623678aa32bb89f16
	});
	$('#id_grade').admin_set_select_field({
		"enum_type"    : "grade",
		"select_value" : g_args.grade,
		"onChange"     : load_data,
		"th_input_id"  : "th_grade",
<<<<<<< HEAD
		"btn_id_config"     : {}
=======
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
>>>>>>> a7ac4bbec61eb4f093f581e623678aa32bb89f16
	});
	$('#id_test_seller_id').val(g_args.test_seller_id);
	$('#id_test_seller_adminid').val(g_args.test_seller_adminid);
	$('#id_has_performance').val(g_args.has_performance);
	$('#id_fulltime_flag').val(g_args.fulltime_flag);
	$('#id_lesson_user_online_status').val(g_args.lesson_user_online_status);
	$('#id_lesson_type').val(g_args.lesson_type);
	$('#id_subject').val(g_args.subject);
	$('#id_lesson_count').val(g_args.lesson_count);
	$('#id_lesson_cancel_reason_type').val(g_args.lesson_cancel_reason_type);
	$('#id_lesson_del_flag').val(g_args.lesson_del_flag);
	$('#id_has_video_flag').val(g_args.has_video_flag);
	$('#id_is_with_test_user').val(g_args.is_with_test_user);
	$('#id_seller_flag').val(g_args.seller_flag);
	$('#id_lessonid').val(g_args.lessonid);
	$('#id_origin').val(g_args.origin);
	$('#id_fulltime_teacher_type').val(g_args.fulltime_teacher_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">studentid</span>
                <input class="opt-change form-control" id="id_studentid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">confirm_flag</span>
                <input class="opt-change form-control" id="id_confirm_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_adminid</span>
                <input class="opt-change form-control" id="id_seller_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_status</span>
                <input class="opt-change form-control" id="id_lesson_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade</span>
                <input class="opt-change form-control" id="id_grade" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_seller_id</span>
                <input class="opt-change form-control" id="id_test_seller_id" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_seller_adminid</span>
                <input class="opt-change form-control" id="id_test_seller_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">has_performance</span>
                <input class="opt-change form-control" id="id_has_performance" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">fulltime_flag</span>
                <input class="opt-change form-control" id="id_fulltime_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">set_boolean</span>
                <select class="opt-change form-control" id="id_lesson_user_online_status" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_type</span>
                <input class="opt-change form-control" id="id_lesson_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_count</span>
                <input class="opt-change form-control" id="id_lesson_count" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_cancel_reason_type</span>
                <input class="opt-change form-control" id="id_lesson_cancel_reason_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_del_flag</span>
                <input class="opt-change form-control" id="id_lesson_del_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_has_video_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_with_test_user</span>
                <input class="opt-change form-control" id="id_is_with_test_user" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_flag</span>
                <input class="opt-change form-control" id="id_seller_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lessonid</span>
                <input class="opt-change form-control" id="id_lessonid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin</span>
                <input class="opt-change form-control" id="id_origin" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">fulltime_teacher_type</span>
                <input class="opt-change form-control" id="id_fulltime_teacher_type" />
            </div>
        </div>
*/
