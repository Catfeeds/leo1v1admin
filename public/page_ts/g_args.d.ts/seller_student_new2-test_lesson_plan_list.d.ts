interface GargsStatic {
	cur_page:	string;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	grade:	number;//枚举: App\Enums\Egrade
	subject:	number;//枚举: App\Enums\Esubject
	test_lesson_student_status:	number;//枚举: App\Enums\Eseller_student_status
	lessonid:	number;
	page_num:	number;
	page_count:	number;
	userid:	number;
	accept_flag:	number;//枚举: App\Enums\Eset_boolean
	success_flag:	number;//枚举: App\Enums\Eset_boolean
	teacherid:	number;
	jw_teacher:	number;
	is_test_user:	number;//枚举: App\Enums\Eboolean
	jw_test_lesson_status:	number;//枚举: App\Enums\Ejw_test_lesson_status
	require_admin_type:	number;//枚举: App\Enums\Eaccount_role
	require_adminid:	number;
	require_assign_flag:	number;
	seller_groupid_ex:	string;
	tmk_adminid:	number;
	seller_require_change_flag:	number;
	ass_test_lesson_type:	number;//枚举: App\Enums\Eass_test_lesson_type
	test_lesson_fail_flag:	number;//枚举: App\Enums\Etest_lesson_fail_flag
	accept_adminid:	number;
	is_jw:	number;
	is_ass_tran:	number;
	limit_require_flag:	number;
	limit_require_send_adminid:	number;
	require_id:	number;
	has_1v1_lesson_flag:	number;//枚举: App\Enums\Eboolean
	lesson_plan_style:	number;
	account_role_self:	number;
	ass_master_flag:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	accept_status	:any;
	change_teacher_reason	:any;
	change_teacher_reason_img_url	:any;
	change_teacher_reason_type	:any;
	test_lesson_order_fail_flag	:any;
	test_lesson_order_fail_desc	:any;
	test_lesson_order_fail_set_time	:any;
	tmk_adminid	:any;
	confirm_time	:any;
	confirm_adminid	:any;
	lessonid	:any;
	accept_flag	:any;
	require_admin_type	:any;
	origin_userid	:any;
	is_test_user	:any;
	ass_test_lesson_type	:any;
	stu_score_info	:any;
	stu_character_info	:any;
	school	:any;
	editionid	:any;
	stu_test_lesson_level	:any;
	stu_test_ipad_flag	:any;
	stu_request_lesson_time_info	:any;
	stu_request_test_lesson_time_info	:any;
	require_id	:any;
	test_lesson_subject_id	:any;
	add_time	:any;
	test_lesson_student_status	:any;
	userid	:any;
	nick	:any;
	origin	:any;
	phone_location	:any;
	phone	:any;
	require_adminid	:any;
	stu_request_test_lesson_time	:any;
	stu_request_test_lesson_demand	:any;
	intention_level	:any;
	gender	:any;
	origin_assistantid	:any;
	subject	:any;
	grade	:any;
	user_desc	:any;
	has_pad	:any;
	last_revisit_time	:any;
	last_revisit_msg	:any;
	tq_called_flag	:any;
	next_revisit_time	:any;
	lesson_start	:any;
	lesson_del_flag	:any;
	require_time	:any;
	teacherid	:any;
	stu_test_paper	:any;
	tea_download_paper_time	:any;
	success_flag	:any;
	learning_situation	:any;
	fail_greater_4_hour_flag	:any;
	test_lesson_fail_flag	:any;
	fail_reason	:any;
	seller_require_change_flag	:any;
	require_change_lesson_time	:any;
	seller_require_change_time	:any;
	assigned_lesson_count	:any;
	accept_adminid	:any;
	jw_test_lesson_status	:any;
	set_lesson_time	:any;
	green_channel_teacherid	:any;
	cancel_time	:any;
	textbook	:any;
	cur_require_adminid	:any;
	grab_status	:any;
	current_lessonid	:any;
	is_green_flag	:any;
	limit_require_flag	:any;
	limit_require_teacherid	:any;
	limit_require_lesson_start	:any;
	limit_require_time	:any;
	limit_require_adminid	:any;
	limit_require_send_adminid	:any;
	limit_accept_flag	:any;
	limit_require_reason	:any;
	limit_accept_time	:any;
	limit_plan_lesson_reason	:any;
	demand_urgency	:any;
	quotation_reaction	:any;
	knowledge_point_location	:any;
	recent_results	:any;
	advice_flag	:any;
	class_rank	:any;
	grade_rank	:any;
	academic_goal	:any;
	test_stress	:any;
	entrance_school_type	:any;
	interest_cultivation	:any;
	extra_improvement	:any;
	habit_remodel	:any;
	study_habit	:any;
	interests_and_hobbies	:any;
	character_type	:any;
	need_teacher_style	:any;
	new_demand_flag	:any;
	address	:any;
	parent_name	:any;
	seller_top_flag	:any;
	grab_flag	:any;
	rebut_info	:any;
	rebut_flag	:any;
	id	:any;
	lesson_time	:any;
	use_new_flag	:any;
	except_lesson_time	:any;
	limit_require_time_str	:any;
	limit_accept_time_str	:any;
	grade_str	:any;
	editionid_str	:any;
	subject_str	:any;
	grab_status_str	:any;
	has_pad_str	:any;
	test_lesson_student_status_str	:any;
	stu_test_lesson_level_str	:any;
	test_lesson_order_fail_flag_str	:any;
	stu_test_ipad_flag_str	:any;
	grab_flag_str	:any;
	accept_flag_str	:any;
	limit_accept_flag_str	:any;
	teacher_nick	:any;
	confirm_admin_nick	:any;
	tmk_admin_nick	:any;
	stu_request_lesson_time_info_str	:any;
	success_flag_str	:any;
	lesson_used_flag_str	:any;
	fail_greater_4_hour_flag_str	:any;
	advice_flag_str	:any;
	test_lesson_fail_flag_str	:any;
	ass_test_lesson_type_str	:any;
	intention_level_str	:any;
	demand_urgency_str	:any;
	quotation_reaction_str	:any;
	academic_goal_str	:any;
	test_stress_str	:any;
	entrance_school_type_str	:any;
	interest_cultivation_str	:any;
	extra_improvement_str	:any;
	habit_remodel_str	:any;
	gender_str	:any;
	accept_status_str	:any;
	stu_request_test_lesson_time_info_str	:any;
	stu_test_paper_flag_str	:any;
	require_admin_nick	:any;
	limit_require_account	:any;
	limit_require_send_account	:any;
	limit_require_tea_nick	:any;
	is_require_change	:any;
	is_accept_adminid	:any;
	phone_ex	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student_new2; vi  ../seller_student_new2/test_lesson_plan_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-test_lesson_plan_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		cur_page:	$('#id_cur_page').val(),
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		grade:	$('#id_grade').val(),
		subject:	$('#id_subject').val(),
		test_lesson_student_status:	$('#id_test_lesson_student_status').val(),
		lessonid:	$('#id_lessonid').val(),
		userid:	$('#id_userid').val(),
		accept_flag:	$('#id_accept_flag').val(),
		success_flag:	$('#id_success_flag').val(),
		teacherid:	$('#id_teacherid').val(),
		jw_teacher:	$('#id_jw_teacher').val(),
		is_test_user:	$('#id_is_test_user').val(),
		jw_test_lesson_status:	$('#id_jw_test_lesson_status').val(),
		require_admin_type:	$('#id_require_admin_type').val(),
		require_adminid:	$('#id_require_adminid').val(),
		require_assign_flag:	$('#id_require_assign_flag').val(),
		seller_groupid_ex:	$('#id_seller_groupid_ex').val(),
		tmk_adminid:	$('#id_tmk_adminid').val(),
		seller_require_change_flag:	$('#id_seller_require_change_flag').val(),
		ass_test_lesson_type:	$('#id_ass_test_lesson_type').val(),
		test_lesson_fail_flag:	$('#id_test_lesson_fail_flag').val(),
		accept_adminid:	$('#id_accept_adminid').val(),
		is_jw:	$('#id_is_jw').val(),
		is_ass_tran:	$('#id_is_ass_tran').val(),
		limit_require_flag:	$('#id_limit_require_flag').val(),
		limit_require_send_adminid:	$('#id_limit_require_send_adminid').val(),
		require_id:	$('#id_require_id').val(),
		has_1v1_lesson_flag:	$('#id_has_1v1_lesson_flag').val(),
		lesson_plan_style:	$('#id_lesson_plan_style').val(),
		account_role_self:	$('#id_account_role_self').val(),
		ass_master_flag:	$('#id_ass_master_flag').val()
		});
}
$(function(){


	$('#id_date_range').select_date_range({
		'date_type' : g_args.date_type,
		'opt_date_type' : g_args.opt_date_type,
		'start_time'    : g_args.start_time,
		'end_time'      : g_args.end_time,
		date_type_config : JSON.parse( g_args.date_type_config),
		onQuery :function() {
			load_data();
		});
	$('#id_cur_page').val(g_args.cur_page);
	$('#id_grade').admin_set_select_field({
		"enum_type"    : "grade",
		"field_name" : "grade",
		"select_value" : g_args.grade,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_grade",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_subject').admin_set_select_field({
		"enum_type"    : "subject",
		"field_name" : "subject",
		"select_value" : g_args.subject,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_subject",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_test_lesson_student_status').admin_set_select_field({
		"enum_type"    : "seller_student_status",
		"field_name" : "test_lesson_student_status",
		"select_value" : g_args.test_lesson_student_status,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_test_lesson_student_status",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_lessonid').val(g_args.lessonid);
	$('#id_userid').admin_select_user_new({
		"user_type"    : "student",
		"select_value" : g_args.userid,
		"onChange"     : load_data,
		"th_input_id"  : "th_userid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_accept_flag').admin_set_select_field({
		"enum_type"    : "set_boolean",
		"field_name" : "accept_flag",
		"select_value" : g_args.accept_flag,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_accept_flag",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_success_flag').admin_set_select_field({
		"enum_type"    : "set_boolean",
		"field_name" : "success_flag",
		"select_value" : g_args.success_flag,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_success_flag",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_teacherid').admin_select_user_new({
		"user_type"    : "teacher",
		"select_value" : g_args.teacherid,
		"onChange"     : load_data,
		"th_input_id"  : "th_teacherid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_jw_teacher').val(g_args.jw_teacher);
	$('#id_is_test_user').admin_set_select_field({
		"enum_type"    : "boolean",
		"field_name" : "is_test_user",
		"select_value" : g_args.is_test_user,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_is_test_user",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_jw_test_lesson_status').admin_set_select_field({
		"enum_type"    : "jw_test_lesson_status",
		"field_name" : "jw_test_lesson_status",
		"select_value" : g_args.jw_test_lesson_status,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_jw_test_lesson_status",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_require_admin_type').admin_set_select_field({
		"enum_type"    : "account_role",
		"field_name" : "require_admin_type",
		"select_value" : g_args.require_admin_type,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_require_admin_type",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_require_adminid').val(g_args.require_adminid);
	$('#id_require_assign_flag').val(g_args.require_assign_flag);
	$('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);
	$('#id_tmk_adminid').val(g_args.tmk_adminid);
	$('#id_seller_require_change_flag').val(g_args.seller_require_change_flag);
	$('#id_ass_test_lesson_type').admin_set_select_field({
		"enum_type"    : "ass_test_lesson_type",
		"field_name" : "ass_test_lesson_type",
		"select_value" : g_args.ass_test_lesson_type,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_ass_test_lesson_type",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_test_lesson_fail_flag').admin_set_select_field({
		"enum_type"    : "test_lesson_fail_flag",
		"field_name" : "test_lesson_fail_flag",
		"select_value" : g_args.test_lesson_fail_flag,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_test_lesson_fail_flag",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_accept_adminid').val(g_args.accept_adminid);
	$('#id_is_jw').val(g_args.is_jw);
	$('#id_is_ass_tran').val(g_args.is_ass_tran);
	$('#id_limit_require_flag').val(g_args.limit_require_flag);
	$('#id_limit_require_send_adminid').val(g_args.limit_require_send_adminid);
	$('#id_require_id').val(g_args.require_id);
	$('#id_has_1v1_lesson_flag').admin_set_select_field({
		"enum_type"    : "boolean",
		"field_name" : "has_1v1_lesson_flag",
		"select_value" : g_args.has_1v1_lesson_flag,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_has_1v1_lesson_flag",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_lesson_plan_style').val(g_args.lesson_plan_style);
	$('#id_account_role_self').val(g_args.account_role_self);
	$('#id_ass_master_flag').val(g_args.ass_master_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">cur_page</span>
                <input class="opt-change form-control" id="id_cur_page" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">年级</span>
                <select class="opt-change form-control" id="id_grade" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">科目</span>
                <select class="opt-change form-control" id="id_subject" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_student_status</span>
                <select class="opt-change form-control" id="id_test_lesson_student_status" >
                </select>
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
                <span class="input-group-addon">userid</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">set_boolean</span>
                <select class="opt-change form-control" id="id_accept_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">set_boolean</span>
                <select class="opt-change form-control" id="id_success_flag" >
                </select>
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
                <span class="input-group-addon">jw_teacher</span>
                <input class="opt-change form-control" id="id_jw_teacher" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_is_test_user" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">jw_test_lesson_status</span>
                <select class="opt-change form-control" id="id_jw_test_lesson_status" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account_role</span>
                <select class="opt-change form-control" id="id_require_admin_type" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">require_adminid</span>
                <input class="opt-change form-control" id="id_require_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">require_assign_flag</span>
                <input class="opt-change form-control" id="id_require_assign_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_groupid_ex</span>
                <input class="opt-change form-control" id="id_seller_groupid_ex" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">tmk_adminid</span>
                <input class="opt-change form-control" id="id_tmk_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_require_change_flag</span>
                <input class="opt-change form-control" id="id_seller_require_change_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">助教试听分类</span>
                <select class="opt-change form-control" id="id_ass_test_lesson_type" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">出错类型</span>
                <select class="opt-change form-control" id="id_test_lesson_fail_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">accept_adminid</span>
                <input class="opt-change form-control" id="id_accept_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_jw</span>
                <input class="opt-change form-control" id="id_is_jw" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_ass_tran</span>
                <input class="opt-change form-control" id="id_is_ass_tran" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">limit_require_flag</span>
                <input class="opt-change form-control" id="id_limit_require_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">limit_require_send_adminid</span>
                <input class="opt-change form-control" id="id_limit_require_send_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">require_id</span>
                <input class="opt-change form-control" id="id_require_id" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_has_1v1_lesson_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_plan_style</span>
                <input class="opt-change form-control" id="id_lesson_plan_style" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account_role_self</span>
                <input class="opt-change form-control" id="id_account_role_self" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">ass_master_flag</span>
                <input class="opt-change form-control" id="id_ass_master_flag" />
            </div>
        </div>
*/
