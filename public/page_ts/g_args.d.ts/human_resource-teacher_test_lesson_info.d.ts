interface GargsStatic {
	order_by_str:	string;
	page_num:	number;
	page_count:	number;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	teacherid:	number;
	subject:	number;
	teacher_subject:	number;
	identity:	number;
	grade_part_ex:	number;
	tea_status:	number;
	teacher_account:	number;
	qzls_flag:	number;
	fulltime_flag:	number;
	create_now:	number;
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
	teacherid	:any;
	realname	:any;
	train_through_new_time	:any;
	identity	:any;
	interview_access	:any;
	school	:any;
	is_freeze	:any;
	account	:any;
	limit_plan_lesson_time	:any;
	limit_plan_lesson_type	:any;
	limit_plan_lesson_account	:any;
	limit_plan_lesson_reason	:any;
	grade_part_ex	:any;
	second_grade	:any;
	freeze_time	:any;
	freeze_reason	:any;
	freeze_adminid	:any;
	freeze_account	:any;
	account_role	:any;
	not_grade_limit	:any;
	not_grade	:any;
	work_day	:any;
	identity_str	:any;
	grade_part_ex_str	:any;
	second_grade_str	:any;
	limit_plan_lesson_type_str	:any;
	test_person_num	:any;
	all_lesson	:any;
	lesson_num	:any;
	lesson_num_old	:any;
	have_order	:any;
	lesson_num_other	:any;
	have_order_other	:any;
	order_number	:any;
	success_lesson	:any;
	success_not_in_lesson	:any;
	subject_num	:any;
	kk_num	:any;
	kk_order	:any;
	kk_per	:any;
	change_num	:any;
	change_order	:any;
	change_per	:any;
	status_str	:any;
	success_per	:any;
	order_per	:any;
	regular_stu_num	:any;
	test_lesson_num	:any;
	test_lesson_num_week	:any;
	teacher_lesson_count_total	:any;
	order_num_per	:any;
	order_num_per_other	:any;
	kk_lesson_per	:any;
	freeze_time_str	:any;
	limit_plan_lesson_time_str	:any;
	not_grade_str	:any;
	freeze_adminid_str	:any;
}

/*

tofile: 
	 mkdir -p ../human_resource; vi  ../human_resource/teacher_test_lesson_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-teacher_test_lesson_info.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		order_by_str:	$('#id_order_by_str').val(),
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		teacherid:	$('#id_teacherid').val(),
		subject:	$('#id_subject').val(),
		teacher_subject:	$('#id_teacher_subject').val(),
		identity:	$('#id_identity').val(),
		grade_part_ex:	$('#id_grade_part_ex').val(),
		tea_status:	$('#id_tea_status').val(),
		teacher_account:	$('#id_teacher_account').val(),
		qzls_flag:	$('#id_qzls_flag').val(),
		fulltime_flag:	$('#id_fulltime_flag').val(),
		create_now:	$('#id_create_now').val(),
		fulltime_teacher_type:	$('#id_fulltime_teacher_type').val()
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
	$('#id_order_by_str').val(g_args.order_by_str);
	$('#id_teacherid').admin_select_user_new({
		"user_type"    : "teacher",
		"select_value" : g_args.teacherid,
		"onChange"     : load_data,
		"th_input_id"  : "th_teacherid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_subject').val(g_args.subject);
	$('#id_teacher_subject').val(g_args.teacher_subject);
	$('#id_identity').val(g_args.identity);
	$('#id_grade_part_ex').val(g_args.grade_part_ex);
	$('#id_tea_status').val(g_args.tea_status);
	$('#id_teacher_account').val(g_args.teacher_account);
	$('#id_qzls_flag').val(g_args.qzls_flag);
	$('#id_fulltime_flag').val(g_args.fulltime_flag);
	$('#id_create_now').val(g_args.create_now);
	$('#id_fulltime_teacher_type').val(g_args.fulltime_teacher_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_by_str</span>
                <input class="opt-change form-control" id="id_order_by_str" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["order_by_str title", "order_by_str", "th_order_by_str" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type_config title", "date_type_config", "th_date_type_config" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type title", "date_type", "th_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["opt_date_type title", "opt_date_type", "th_opt_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["start_time title", "start_time", "th_start_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["end_time title", "end_time", "th_end_time" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacherid title", "teacherid", "th_teacherid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["subject title", "subject", "th_subject" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_subject</span>
                <input class="opt-change form-control" id="id_teacher_subject" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacher_subject title", "teacher_subject", "th_teacher_subject" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">identity</span>
                <input class="opt-change form-control" id="id_identity" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["identity title", "identity", "th_identity" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade_part_ex</span>
                <input class="opt-change form-control" id="id_grade_part_ex" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["grade_part_ex title", "grade_part_ex", "th_grade_part_ex" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">tea_status</span>
                <input class="opt-change form-control" id="id_tea_status" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["tea_status title", "tea_status", "th_tea_status" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_account</span>
                <input class="opt-change form-control" id="id_teacher_account" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacher_account title", "teacher_account", "th_teacher_account" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">qzls_flag</span>
                <input class="opt-change form-control" id="id_qzls_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["qzls_flag title", "qzls_flag", "th_qzls_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">fulltime_flag</span>
                <input class="opt-change form-control" id="id_fulltime_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["fulltime_flag title", "fulltime_flag", "th_fulltime_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">create_now</span>
                <input class="opt-change form-control" id="id_create_now" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["create_now title", "create_now", "th_create_now" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">fulltime_teacher_type</span>
                <input class="opt-change form-control" id="id_fulltime_teacher_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["fulltime_teacher_type title", "fulltime_teacher_type", "th_fulltime_teacher_type" ]])!!}
*/
