interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	teacherid:	number;
	is_freeze:	number;
	free_time:	string;
	page_num:	number;
	page_count:	number;
	is_test_user:	number;
	gender:	number;
	grade_part_ex:	number;
	subject:	number;
	second_subject:	number;
	address:	string;
	limit_plan_lesson_type:	number;
	lesson_hold_flag:	number;
	train_through_new:	number;
	seller_flag:	number;
	sleep_teacher_flag:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	teacherid	:any;
	nick	:any;
	face	:any;
	phone	:any;
	gender	:any;
	stu_num	:any;
	grade	:any;
	school	:any;
	address	:any;
	rate_score	:any;
	rate_effect	:any;
	rate_quality	:any;
	rate_interact	:any;
	five_star	:any;
	four_star	:any;
	three_star	:any;
	two_star	:any;
	one_star	:any;
	base_intro	:any;
	advantage	:any;
	work_year	:any;
	tutor_subject	:any;
	tutor_grade	:any;
	title	:any;
	level	:any;
	birth	:any;
	last_modified_time	:any;
	email	:any;
	teacher_type	:any;
	prize	:any;
	achievement	:any;
	teacher_style	:any;
	quiz_analyse	:any;
	quiz_video	:any;
	is_quit	:any;
	user_agent	:any;
	flower	:any;
	teacher_tags	:any;
	teacher_textbook	:any;
	create_meeting	:any;
	clothes	:any;
	realname	:any;
	teacher_money_type	:any;
	wx_openid	:any;
	need_test_lesson_flag	:any;
	check_adminid	:any;
	create_time	:any;
	assess_num	:any;
	textbook_type	:any;
	grade_part_ex	:any;
	subject	:any;
	putonghua_is_correctly	:any;
	dialect_notes	:any;
	jianli	:any;
	is_good_flag	:any;
	second_subject	:any;
	third_subject	:any;
	interview_access	:any;
	tea_note	:any;
	teacher_money_flag	:any;
	trial_lecture_is_pass	:any;
	identity	:any;
	is_test_user	:any;
	is_freeze	:any;
	freeze_adminid	:any;
	freeze_time	:any;
	freeze_reason	:any;
	un_freeze_time	:any;
	is_interview_teacher_flag	:any;
	limit_plan_lesson_type	:any;
	limit_plan_lesson_time	:any;
	limit_plan_lesson_reason	:any;
	limit_plan_lesson_account	:any;
	second_grade	:any;
	third_grade	:any;
	train_through_new	:any;
	is_week_freeze	:any;
	week_freeze_adminid	:any;
	week_freeze_time	:any;
	week_freeze_reason	:any;
	train_through_new_time	:any;
	lesson_hold_flag	:any;
	interview_score	:any;
	second_interview_score	:any;
	test_transfor_per	:any;
	week_liveness	:any;
	idcard	:any;
	bankcard	:any;
	bank_address	:any;
	bank_account	:any;
	wx_use_flag	:any;
	permission	:any;
	limit_day_lesson_num	:any;
	limit_week_lesson_num	:any;
	limit_month_lesson_num	:any;
	teacher_ref_type	:any;
	research_note	:any;
	lesson_hold_flag_acc	:any;
	lesson_hold_flag_reason	:any;
	lesson_hold_flag_time	:any;
	assign_jw_adminid	:any;
	assign_jw_time	:any;
	week_freeze_warning_flag	:any;
	grade_start	:any;
	grade_end	:any;
	month_type	:any;
	saturday_lesson_num	:any;
	change_good_time	:any;
	is_good_wx_flag	:any;
	not_grade	:any;
	not_grade_limit	:any;
	week_lesson_count	:any;
	is_record_flag	:any;
	lesson_hold_flag_adminid	:any;
	have_test_lesson_flag	:any;
	test_lesson_num	:any;
	quit_time	:any;
	leave_start_time	:any;
	leave_end_time	:any;
	leave_set_adminid	:any;
	leave_set_time	:any;
	quit_set_adminid	:any;
	leave_reason	:any;
	leave_remove_adminid	:any;
	leave_remove_time	:any;
	quit_info	:any;
	phone_spare	:any;
	trial_train_flag	:any;
	second_grade_start	:any;
	second_grade_end	:any;
	second_not_grade	:any;
	add_acc	:any;
	part_remarks	:any;
	bank_phone	:any;
	bank_type	:any;
	user_agent_wx_update	:any;
	bank_province	:any;
	bank_city	:any;
	transfer_teacherid	:any;
	transfer_time	:any;
	check_subject	:any;
	check_grade	:any;
	teacher_money_type_simulate	:any;
	level_simulate	:any;
	test_quit	:any;
	two_week_test_lesson_num	:any;
	month_stu_num	:any;
	zs_id	:any;
	textbook_check_flag	:any;
	need_check_textbook	:any;
	seniority	:any;
	prove	:any;
	education	:any;
	major	:any;
	hobby	:any;
	speciality	:any;
	train_type	:any;
	new_train_flag	:any;
	phone_location	:any;
	sleep_flag	:any;
	free_time	:any;
	teacher_type_str	:any;
	gender_str	:any;
	subject_str	:any;
	second_subject_str	:any;
	third_subject_str	:any;
	grade_part_ex_str	:any;
	grade_start_str	:any;
	grade_end_str	:any;
	identity_str	:any;
	age	:any;
	level_str	:any;
	teacher_money_type_str	:any;
	textbook_type_str	:any;
	train_through_new_time_str	:any;
	create_time_str	:any;
	work_day	:any;
	limit_plan_lesson_type_str	:any;
	limit_plan_lesson_time_str	:any;
	not_grade_str	:any;
	freeze_adminid_str	:any;
	phone_ex	:any;
}

/*

tofile: 
	 mkdir -p ../human_resource; vi  ../human_resource/teacher_info_new.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-teacher_info_new.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		teacherid:	$('#id_teacherid').val(),
		is_freeze:	$('#id_is_freeze').val(),
		free_time:	$('#id_free_time').val(),
		is_test_user:	$('#id_is_test_user').val(),
		gender:	$('#id_gender').val(),
		grade_part_ex:	$('#id_grade_part_ex').val(),
		subject:	$('#id_subject').val(),
		second_subject:	$('#id_second_subject').val(),
		address:	$('#id_address').val(),
		limit_plan_lesson_type:	$('#id_limit_plan_lesson_type').val(),
		lesson_hold_flag:	$('#id_lesson_hold_flag').val(),
		train_through_new:	$('#id_train_through_new').val(),
		seller_flag:	$('#id_seller_flag').val(),
		sleep_teacher_flag:	$('#id_sleep_teacher_flag').val()
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
        }
    });
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_is_freeze').val(g_args.is_freeze);
	$('#id_free_time').val(g_args.free_time);
	$('#id_is_test_user').val(g_args.is_test_user);
	$('#id_gender').val(g_args.gender);
	$('#id_grade_part_ex').val(g_args.grade_part_ex);
	$('#id_subject').val(g_args.subject);
	$('#id_second_subject').val(g_args.second_subject);
	$('#id_address').val(g_args.address);
	$('#id_limit_plan_lesson_type').val(g_args.limit_plan_lesson_type);
	$('#id_lesson_hold_flag').val(g_args.lesson_hold_flag);
	$('#id_train_through_new').val(g_args.train_through_new);
	$('#id_seller_flag').val(g_args.seller_flag);
	$('#id_sleep_teacher_flag').val(g_args.sleep_teacher_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_freeze</span>
                <input class="opt-change form-control" id="id_is_freeze" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">free_time</span>
                <input class="opt-change form-control" id="id_free_time" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_test_user</span>
                <input class="opt-change form-control" id="id_is_test_user" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">gender</span>
                <input class="opt-change form-control" id="id_gender" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade_part_ex</span>
                <input class="opt-change form-control" id="id_grade_part_ex" />
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
                <span class="input-group-addon">second_subject</span>
                <input class="opt-change form-control" id="id_second_subject" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">address</span>
                <input class="opt-change form-control" id="id_address" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">limit_plan_lesson_type</span>
                <input class="opt-change form-control" id="id_limit_plan_lesson_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_hold_flag</span>
                <input class="opt-change form-control" id="id_lesson_hold_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">train_through_new</span>
                <input class="opt-change form-control" id="id_train_through_new" />
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
                <span class="input-group-addon">sleep_teacher_flag</span>
                <input class="opt-change form-control" id="id_sleep_teacher_flag" />
            </div>
        </div>
*/
