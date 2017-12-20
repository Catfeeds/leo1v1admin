interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	origin:	string;
	grade:	number;//枚举: App\Enums\Egrade
	subject:	number;//枚举: App\Enums\Esubject
	phone:	string;
	st_application_nick:	string;
	status:	number;
	from_type:	number;
	st_arrange_lessonid:	number;
	page_num:	number;
	page_count:	number;
	origin_ex:	string;
	userid:	number;
	teacherid:	number;
	confirm_flag:	number;
	require_user_type:	number;
	test_lesson_cancel_flag:	number;//枚举: App\Enums\Etest_lesson_cancel_flag
	ass_adminid_flag:	number;//枚举: App\Enums\Eboolean
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	id	:any;
	phone	:any;
	ass_adminid	:any;
	st_userid	:any;
	userid	:any;
	lessonid	:any;
	st_arrange_lessonid	:any;
	from_type	:any;
	st_application_time	:any;
	tea_download_paper_time	:any;
	st_application_nick	:any;
	st_demand	:any;
	grade	:any;
	subject	:any;
	nick	:any;
	status	:any;
	origin	:any;
	phone_location	:any;
	st_from_school	:any;
	teacherid	:any;
	confirm_reason	:any;
	confirm_flag	:any;
	lesson_start	:any;
	lesson_end	:any;
	st_class_time	:any;
	has_pad	:any;
	st_test_paper	:any;
	user_desc	:any;
	admin_revisiterid	:any;
	assigned_teacherid	:any;
	last_revisit_time	:any;
	last_revisit_msg	:any;
	courseid	:any;
	stu_test_lesson_level	:any;
	stu_test_ipad_flag	:any;
	stu_score_info	:any;
	stu_character_info	:any;
	stu_request_test_lesson_time_info	:any;
	stu_request_lesson_time_info	:any;
	cancel_lesson_start	:any;
	cancel_flag	:any;
	editionid	:any;
	add_time	:any;
	cancel_adminid	:any;
	cancel_time	:any;
	cancel_teacherid	:any;
	cancel_reason	:any;
	grade_str	:any;
	editionid_str	:any;
	cancel_flag_str	:any;
	subject_str	:any;
	has_pad_str	:any;
	status_str	:any;
	from_type_str	:any;
	stu_test_lesson_level_str	:any;
	stu_test_ipad_flag_str	:any;
	confirm_flag_str	:any;
	cancel_admin_nick	:any;
	assigned_teacher_nick	:any;
	cancel_teacher_nick	:any;
	lesson_time	:any;
	teacher_nick	:any;
	stu_request_lesson_time_info_str	:any;
	stu_request_test_lesson_time_info_str	:any;
	st_test_paper_str	:any;
	admin_revisiterid_nick	:any;
	parent_nick	:any;
	parent_phone	:any;
	address	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student; vi  ../seller_student/test_lesson_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student-test_lesson_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		origin:	$('#id_origin').val(),
		grade:	$('#id_grade').val(),
		subject:	$('#id_subject').val(),
		phone:	$('#id_phone').val(),
		st_application_nick:	$('#id_st_application_nick').val(),
		status:	$('#id_status').val(),
		from_type:	$('#id_from_type').val(),
		st_arrange_lessonid:	$('#id_st_arrange_lessonid').val(),
		origin_ex:	$('#id_origin_ex').val(),
		userid:	$('#id_userid').val(),
		teacherid:	$('#id_teacherid').val(),
		confirm_flag:	$('#id_confirm_flag').val(),
		require_user_type:	$('#id_require_user_type').val(),
		test_lesson_cancel_flag:	$('#id_test_lesson_cancel_flag').val(),
		ass_adminid_flag:	$('#id_ass_adminid_flag').val()
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
	$('#id_origin').val(g_args.origin);
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
	$('#id_phone').val(g_args.phone);
	$('#id_st_application_nick').val(g_args.st_application_nick);
	$('#id_status').val(g_args.status);
	$('#id_from_type').val(g_args.from_type);
	$('#id_st_arrange_lessonid').val(g_args.st_arrange_lessonid);
	$('#id_origin_ex').val(g_args.origin_ex);
	$('#id_userid').admin_select_user_new({
		"user_type"    : "student",
		"select_value" : g_args.userid,
		"onChange"     : load_data,
		"th_input_id"  : "th_userid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_teacherid').admin_select_user_new({
		"user_type"    : "teacher",
		"select_value" : g_args.teacherid,
		"onChange"     : load_data,
		"th_input_id"  : "th_teacherid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_confirm_flag').val(g_args.confirm_flag);
	$('#id_require_user_type').val(g_args.require_user_type);
	$('#id_test_lesson_cancel_flag').admin_set_select_field({
		"enum_type"    : "test_lesson_cancel_flag",
		"field_name" : "test_lesson_cancel_flag",
		"select_value" : g_args.test_lesson_cancel_flag,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_test_lesson_cancel_flag",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_ass_adminid_flag').admin_set_select_field({
		"enum_type"    : "boolean",
		"field_name" : "ass_adminid_flag",
		"select_value" : g_args.ass_adminid_flag,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_ass_adminid_flag",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
{!!\App\Helper\Utils::th_order_gen([["date_type_config title", "date_type_config", "th_date_type_config" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type title", "date_type", "th_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["opt_date_type title", "opt_date_type", "th_opt_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["start_time title", "start_time", "th_start_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["end_time title", "end_time", "th_end_time" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin</span>
                <input class="opt-change form-control" id="id_origin" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["origin title", "origin", "th_origin" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">年级</span>
                <select class="opt-change form-control" id="id_grade" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["grade title", "grade", "th_grade" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">科目</span>
                <select class="opt-change form-control" id="id_subject" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["subject title", "subject", "th_subject" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone</span>
                <input class="opt-change form-control" id="id_phone" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["phone title", "phone", "th_phone" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">st_application_nick</span>
                <input class="opt-change form-control" id="id_st_application_nick" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["st_application_nick title", "st_application_nick", "th_st_application_nick" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">status</span>
                <input class="opt-change form-control" id="id_status" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["status title", "status", "th_status" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">from_type</span>
                <input class="opt-change form-control" id="id_from_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["from_type title", "from_type", "th_from_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">st_arrange_lessonid</span>
                <input class="opt-change form-control" id="id_st_arrange_lessonid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["st_arrange_lessonid title", "st_arrange_lessonid", "th_st_arrange_lessonid" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin_ex</span>
                <input class="opt-change form-control" id="id_origin_ex" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["origin_ex title", "origin_ex", "th_origin_ex" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["userid title", "userid", "th_userid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacherid title", "teacherid", "th_teacherid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">confirm_flag</span>
                <input class="opt-change form-control" id="id_confirm_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["confirm_flag title", "confirm_flag", "th_confirm_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">require_user_type</span>
                <input class="opt-change form-control" id="id_require_user_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["require_user_type title", "require_user_type", "th_require_user_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">课前取消</span>
                <select class="opt-change form-control" id="id_test_lesson_cancel_flag" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["test_lesson_cancel_flag title", "test_lesson_cancel_flag", "th_test_lesson_cancel_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_ass_adminid_flag" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["ass_adminid_flag title", "ass_adminid_flag", "th_ass_adminid_flag" ]])!!}
*/
