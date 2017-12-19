interface GargsStatic {
	teacherid:	number;
	is_freeze:	number;
	teacher_money_type:	number;
	teacher_ref_type:	number;
	level:	number;
	page_num:	number;
	page_count:	number;
	need_test_lesson_flag:	number;
	textbook_type:	number;
	is_good_flag:	number;
	is_new_teacher:	number;
	gender:	number;
	free_time:	string;
	grade_part_ex:	number;
	subject:	number;
	second_subject:	number;
	trial_flag:	number;
	test_flag:	number;
	seller_flag:	number;
	is_test_user:	number;
	is_quit:	number;
	address:	string;
	limit_plan_lesson_type:	number;
	is_record_flag:	number;
	test_lesson_full_flag:	number;
	train_through_new:	number;
	lesson_hold_flag:	number;
	test_transfor_per:	number;
	week_liveness:	number;
	interview_score:	number;
	set_leave_flag:	number;
	second_interview_score:	number;
	lesson_hold_flag_adminid:	number;
	fulltime_flag:	number;
	teacher_type:	number;
	seller_hold_flag:	number;
	have_wx:	number;
	grade_plan:	number;
	subject_plan:	number;
	fulltime_teacher_type:	number;
	month_stu_num:	number;
	record_score_num:	number;
	identity:	number;
	tea_label_type:	number;
	plan_level:	number;
	teacher_textbook:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	wx_openid	:any;
	need_test_lesson_flag	:any;
	nick	:any;
	realname	:any;
	teacher_type	:any;
	jianli	:any;
	gender	:any;
	teacher_money_type	:any;
	identity	:any;
	is_test_user	:any;
	add_acc	:any;
	train_through_new	:any;
	train_through_new_time	:any;
	phone_spare	:any;
	birth	:any;
	phone	:any;
	email	:any;
	rate_score	:any;
	teacherid	:any;
	user_agent	:any;
	teacher_tags	:any;
	teacher_textbook	:any;
	wx_use_flag	:any;
	create_meeting	:any;
	level	:any;
	work_year	:any;
	advantage	:any;
	base_intro	:any;
	textbook_type	:any;
	is_good_flag	:any;
	create_time	:any;
	address	:any;
	subject	:any;
	second_subject	:any;
	third_subject	:any;
	school	:any;
	tea_note	:any;
	grade_part_ex	:any;
	is_freeze	:any;
	freeze_reason	:any;
	freeze_adminid	:any;
	freeze_time	:any;
	limit_plan_lesson_type	:any;
	limit_plan_lesson_reason	:any;
	limit_plan_lesson_time	:any;
	limit_plan_lesson_account	:any;
	second_grade	:any;
	third_grade	:any;
	interview_access	:any;
	lesson_hold_flag	:any;
	lesson_hold_flag_acc	:any;
	research_note	:any;
	lesson_hold_flag_time	:any;
	interview_score	:any;
	second_interview_score	:any;
	test_transfor_per	:any;
	week_liveness	:any;
	limit_day_lesson_num	:any;
	limit_week_lesson_num	:any;
	limit_month_lesson_num	:any;
	teacher_ref_type	:any;
	saturday_lesson_num	:any;
	grade_start	:any;
	grade_end	:any;
	second_grade_start	:any;
	second_grade_end	:any;
	month_stu_num	:any;
	not_grade	:any;
	not_grade_limit	:any;
	week_lesson_count	:any;
	trial_lecture_is_pass	:any;
	idcard	:any;
	bankcard	:any;
	bank_address	:any;
	bank_account	:any;
	bank_phone	:any;
	bank_type	:any;
	bank_province	:any;
	bank_city	:any;
	is_quit	:any;
	part_remarks	:any;
	record_score	:any;
	free_time	:any;
	class_will_type	:any;
	class_will_sub_type	:any;
	revisit_add_time	:any;
	recover_class_time	:any;
	revisit_record_info	:any;
	teacher_type_str	:any;
	need_test_lesson_flag_str	:any;
	gender_str	:any;
	subject_str	:any;
	second_subject_str	:any;
	third_subject_str	:any;
	grade_part_ex_str	:any;
	second_grade_str	:any;
	third_grade_str	:any;
	identity_str	:any;
	age	:any;
	level_str	:any;
	teacher_money_type_str	:any;
	teacher_ref_type_str	:any;
	textbook_type_str	:any;
	is_good_flag_str	:any;
	limit_plan_lesson_type_str	:any;
	freeze_time_str	:any;
	create_time_str	:any;
	limit_plan_lesson_time_str	:any;
	train_through_new_time_str	:any;
	lesson_hold_flag_time_str	:any;
	class_will_type_str	:any;
	class_will_sub_type_str	:any;
	grade_start_str	:any;
	grade_end_str	:any;
	revisit_add_time_str	:any;
	recover_class_time_str	:any;
	work_day	:any;
	is_freeze_str	:any;
	lesson_info_week	:any;
	test_user_str	:any;
	train_through_new_str	:any;
	phone_ex	:any;
	freeze_adminid_str	:any;
	week_lesson_num	:any;
	week_left_num	:any;
	not_grade_str	:any;
	interview_acc	:any;
	textbook	:any;
	fine_dimension	:any;
	full_flag	:any;
}

/*

tofile: 
	 mkdir -p ../human_resource; vi  ../human_resource/index.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-index.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		teacherid:	$('#id_teacherid').val(),
		is_freeze:	$('#id_is_freeze').val(),
		teacher_money_type:	$('#id_teacher_money_type').val(),
		teacher_ref_type:	$('#id_teacher_ref_type').val(),
		level:	$('#id_level').val(),
		need_test_lesson_flag:	$('#id_need_test_lesson_flag').val(),
		textbook_type:	$('#id_textbook_type').val(),
		is_good_flag:	$('#id_is_good_flag').val(),
		is_new_teacher:	$('#id_is_new_teacher').val(),
		gender:	$('#id_gender').val(),
		free_time:	$('#id_free_time').val(),
		grade_part_ex:	$('#id_grade_part_ex').val(),
		subject:	$('#id_subject').val(),
		second_subject:	$('#id_second_subject').val(),
		trial_flag:	$('#id_trial_flag').val(),
		test_flag:	$('#id_test_flag').val(),
		seller_flag:	$('#id_seller_flag').val(),
		is_test_user:	$('#id_is_test_user').val(),
		is_quit:	$('#id_is_quit').val(),
		address:	$('#id_address').val(),
		limit_plan_lesson_type:	$('#id_limit_plan_lesson_type').val(),
		is_record_flag:	$('#id_is_record_flag').val(),
		test_lesson_full_flag:	$('#id_test_lesson_full_flag').val(),
		train_through_new:	$('#id_train_through_new').val(),
		lesson_hold_flag:	$('#id_lesson_hold_flag').val(),
		test_transfor_per:	$('#id_test_transfor_per').val(),
		week_liveness:	$('#id_week_liveness').val(),
		interview_score:	$('#id_interview_score').val(),
		set_leave_flag:	$('#id_set_leave_flag').val(),
		second_interview_score:	$('#id_second_interview_score').val(),
		lesson_hold_flag_adminid:	$('#id_lesson_hold_flag_adminid').val(),
		fulltime_flag:	$('#id_fulltime_flag').val(),
		teacher_type:	$('#id_teacher_type').val(),
		seller_hold_flag:	$('#id_seller_hold_flag').val(),
		have_wx:	$('#id_have_wx').val(),
		grade_plan:	$('#id_grade_plan').val(),
		subject_plan:	$('#id_subject_plan').val(),
		fulltime_teacher_type:	$('#id_fulltime_teacher_type').val(),
		month_stu_num:	$('#id_month_stu_num').val(),
		record_score_num:	$('#id_record_score_num').val(),
		identity:	$('#id_identity').val(),
		tea_label_type:	$('#id_tea_label_type').val(),
		plan_level:	$('#id_plan_level').val(),
		teacher_textbook:	$('#id_teacher_textbook').val()
		});
}
$(function(){


	$('#id_teacherid').admin_select_user_new({
		"user_type"    : "teacher",
		"select_value" : g_args.teacherid,
		"onChange"     : load_data,
		"th_input_id"  : "th_teacherid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_is_freeze').val(g_args.is_freeze);
	$('#id_teacher_money_type').val(g_args.teacher_money_type);
	$('#id_teacher_ref_type').val(g_args.teacher_ref_type);
	$('#id_level').val(g_args.level);
	$('#id_need_test_lesson_flag').val(g_args.need_test_lesson_flag);
	$('#id_textbook_type').val(g_args.textbook_type);
	$('#id_is_good_flag').val(g_args.is_good_flag);
	$('#id_is_new_teacher').val(g_args.is_new_teacher);
	$('#id_gender').val(g_args.gender);
	$('#id_free_time').val(g_args.free_time);
	$('#id_grade_part_ex').val(g_args.grade_part_ex);
	$('#id_subject').val(g_args.subject);
	$('#id_second_subject').val(g_args.second_subject);
	$('#id_trial_flag').val(g_args.trial_flag);
	$('#id_test_flag').val(g_args.test_flag);
	$('#id_seller_flag').val(g_args.seller_flag);
	$('#id_is_test_user').val(g_args.is_test_user);
	$('#id_is_quit').val(g_args.is_quit);
	$('#id_address').val(g_args.address);
	$('#id_limit_plan_lesson_type').val(g_args.limit_plan_lesson_type);
	$('#id_is_record_flag').val(g_args.is_record_flag);
	$('#id_test_lesson_full_flag').val(g_args.test_lesson_full_flag);
	$('#id_train_through_new').val(g_args.train_through_new);
	$('#id_lesson_hold_flag').val(g_args.lesson_hold_flag);
	$('#id_test_transfor_per').val(g_args.test_transfor_per);
	$('#id_week_liveness').val(g_args.week_liveness);
	$('#id_interview_score').val(g_args.interview_score);
	$('#id_set_leave_flag').val(g_args.set_leave_flag);
	$('#id_second_interview_score').val(g_args.second_interview_score);
	$('#id_lesson_hold_flag_adminid').val(g_args.lesson_hold_flag_adminid);
	$('#id_fulltime_flag').val(g_args.fulltime_flag);
	$('#id_teacher_type').val(g_args.teacher_type);
	$('#id_seller_hold_flag').val(g_args.seller_hold_flag);
	$('#id_have_wx').val(g_args.have_wx);
	$('#id_grade_plan').val(g_args.grade_plan);
	$('#id_subject_plan').val(g_args.subject_plan);
	$('#id_fulltime_teacher_type').val(g_args.fulltime_teacher_type);
	$('#id_month_stu_num').val(g_args.month_stu_num);
	$('#id_record_score_num').val(g_args.record_score_num);
	$('#id_identity').val(g_args.identity);
	$('#id_tea_label_type').val(g_args.tea_label_type);
	$('#id_plan_level').val(g_args.plan_level);
	$('#id_teacher_textbook').val(g_args.teacher_textbook);


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
{!!\App\Helper\Utils::th_order_gen([["teacherid title", "teacherid", "th_teacherid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_freeze</span>
                <input class="opt-change form-control" id="id_is_freeze" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["is_freeze title", "is_freeze", "th_is_freeze" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_money_type</span>
                <input class="opt-change form-control" id="id_teacher_money_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacher_money_type title", "teacher_money_type", "th_teacher_money_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_ref_type</span>
                <input class="opt-change form-control" id="id_teacher_ref_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacher_ref_type title", "teacher_ref_type", "th_teacher_ref_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">level</span>
                <input class="opt-change form-control" id="id_level" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["level title", "level", "th_level" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">need_test_lesson_flag</span>
                <input class="opt-change form-control" id="id_need_test_lesson_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["need_test_lesson_flag title", "need_test_lesson_flag", "th_need_test_lesson_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">textbook_type</span>
                <input class="opt-change form-control" id="id_textbook_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["textbook_type title", "textbook_type", "th_textbook_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_good_flag</span>
                <input class="opt-change form-control" id="id_is_good_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["is_good_flag title", "is_good_flag", "th_is_good_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_new_teacher</span>
                <input class="opt-change form-control" id="id_is_new_teacher" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["is_new_teacher title", "is_new_teacher", "th_is_new_teacher" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">gender</span>
                <input class="opt-change form-control" id="id_gender" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["gender title", "gender", "th_gender" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">free_time</span>
                <input class="opt-change form-control" id="id_free_time" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["free_time title", "free_time", "th_free_time" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade_part_ex</span>
                <input class="opt-change form-control" id="id_grade_part_ex" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["grade_part_ex title", "grade_part_ex", "th_grade_part_ex" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["subject title", "subject", "th_subject" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">second_subject</span>
                <input class="opt-change form-control" id="id_second_subject" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["second_subject title", "second_subject", "th_second_subject" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">trial_flag</span>
                <input class="opt-change form-control" id="id_trial_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["trial_flag title", "trial_flag", "th_trial_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_flag</span>
                <input class="opt-change form-control" id="id_test_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["test_flag title", "test_flag", "th_test_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_flag</span>
                <input class="opt-change form-control" id="id_seller_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["seller_flag title", "seller_flag", "th_seller_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_test_user</span>
                <input class="opt-change form-control" id="id_is_test_user" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["is_test_user title", "is_test_user", "th_is_test_user" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_quit</span>
                <input class="opt-change form-control" id="id_is_quit" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["is_quit title", "is_quit", "th_is_quit" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">address</span>
                <input class="opt-change form-control" id="id_address" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["address title", "address", "th_address" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">limit_plan_lesson_type</span>
                <input class="opt-change form-control" id="id_limit_plan_lesson_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["limit_plan_lesson_type title", "limit_plan_lesson_type", "th_limit_plan_lesson_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_record_flag</span>
                <input class="opt-change form-control" id="id_is_record_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["is_record_flag title", "is_record_flag", "th_is_record_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_lesson_full_flag</span>
                <input class="opt-change form-control" id="id_test_lesson_full_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["test_lesson_full_flag title", "test_lesson_full_flag", "th_test_lesson_full_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">train_through_new</span>
                <input class="opt-change form-control" id="id_train_through_new" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["train_through_new title", "train_through_new", "th_train_through_new" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_hold_flag</span>
                <input class="opt-change form-control" id="id_lesson_hold_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["lesson_hold_flag title", "lesson_hold_flag", "th_lesson_hold_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_transfor_per</span>
                <input class="opt-change form-control" id="id_test_transfor_per" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["test_transfor_per title", "test_transfor_per", "th_test_transfor_per" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">week_liveness</span>
                <input class="opt-change form-control" id="id_week_liveness" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["week_liveness title", "week_liveness", "th_week_liveness" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">interview_score</span>
                <input class="opt-change form-control" id="id_interview_score" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["interview_score title", "interview_score", "th_interview_score" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">set_leave_flag</span>
                <input class="opt-change form-control" id="id_set_leave_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["set_leave_flag title", "set_leave_flag", "th_set_leave_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">second_interview_score</span>
                <input class="opt-change form-control" id="id_second_interview_score" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["second_interview_score title", "second_interview_score", "th_second_interview_score" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_hold_flag_adminid</span>
                <input class="opt-change form-control" id="id_lesson_hold_flag_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["lesson_hold_flag_adminid title", "lesson_hold_flag_adminid", "th_lesson_hold_flag_adminid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">fulltime_flag</span>
                <input class="opt-change form-control" id="id_fulltime_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["fulltime_flag title", "fulltime_flag", "th_fulltime_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_type</span>
                <input class="opt-change form-control" id="id_teacher_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacher_type title", "teacher_type", "th_teacher_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_hold_flag</span>
                <input class="opt-change form-control" id="id_seller_hold_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["seller_hold_flag title", "seller_hold_flag", "th_seller_hold_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">have_wx</span>
                <input class="opt-change form-control" id="id_have_wx" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["have_wx title", "have_wx", "th_have_wx" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade_plan</span>
                <input class="opt-change form-control" id="id_grade_plan" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["grade_plan title", "grade_plan", "th_grade_plan" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">subject_plan</span>
                <input class="opt-change form-control" id="id_subject_plan" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["subject_plan title", "subject_plan", "th_subject_plan" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">fulltime_teacher_type</span>
                <input class="opt-change form-control" id="id_fulltime_teacher_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["fulltime_teacher_type title", "fulltime_teacher_type", "th_fulltime_teacher_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">month_stu_num</span>
                <input class="opt-change form-control" id="id_month_stu_num" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["month_stu_num title", "month_stu_num", "th_month_stu_num" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">record_score_num</span>
                <input class="opt-change form-control" id="id_record_score_num" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["record_score_num title", "record_score_num", "th_record_score_num" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">identity</span>
                <input class="opt-change form-control" id="id_identity" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["identity title", "identity", "th_identity" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">tea_label_type</span>
                <input class="opt-change form-control" id="id_tea_label_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["tea_label_type title", "tea_label_type", "th_tea_label_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">plan_level</span>
                <input class="opt-change form-control" id="id_plan_level" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["plan_level title", "plan_level", "th_plan_level" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_textbook</span>
                <input class="opt-change form-control" id="id_teacher_textbook" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacher_textbook title", "teacher_textbook", "th_teacher_textbook" ]])!!}
*/
