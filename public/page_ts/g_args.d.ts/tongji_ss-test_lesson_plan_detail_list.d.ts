interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	page_num:	number;
	page_count:	number;
	set_lesson_adminid:	number;
	subject:	number;//枚举: App\Enums\Esubject
	grade:	number;//枚举: App\Enums\Egrade
	success_flag:	number;//枚举: App\Enums\Eset_boolean
	test_lesson_fail_flag:	number;//枚举: App\Enums\Etest_lesson_fail_flag
	userid:	number;
	require_admin_type:	number;//枚举: App\Enums\Eaccount_role
	require_adminid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	set_lesson_adminid	:any;
	require_adminid	:any;
	lesson_start	:any;
	userid	:any;
	teacherid	:any;
	subject	:any;
	phone	:any;
	nick	:any;
	grade	:any;
	success_flag	:any;
	test_lesson_fail_flag	:any;
	fail_reason	:any;
	teacher_nick	:any;
	require_admin_nick	:any;
	set_lesson_admin_nick	:any;
	test_lesson_fail_flag_str	:any;
	subject_str	:any;
	grade_str	:any;
	success_flag_str	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ss; vi  ../tongji_ss/test_lesson_plan_detail_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-test_lesson_plan_detail_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		set_lesson_adminid:	$('#id_set_lesson_adminid').val(),
		subject:	$('#id_subject').val(),
		grade:	$('#id_grade').val(),
		success_flag:	$('#id_success_flag').val(),
		test_lesson_fail_flag:	$('#id_test_lesson_fail_flag').val(),
		userid:	$('#id_userid').val(),
		require_admin_type:	$('#id_require_admin_type').val(),
		require_adminid:	$('#id_require_adminid').val()
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
	$('#id_set_lesson_adminid').val(g_args.set_lesson_adminid);
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
	$('#id_userid').admin_select_user_new({
		"user_type"    : "student",
		"select_value" : g_args.userid,
		"onChange"     : load_data,
		"th_input_id"  : "th_userid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
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


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
{!!\App\Helper\Utils::th_order_gen([["date_type_config title", "date_type_config", "th_date_type_config" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type title", "date_type", "th_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["opt_date_type title", "opt_date_type", "th_opt_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["start_time title", "start_time", "th_start_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["end_time title", "end_time", "th_end_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">set_lesson_adminid</span>
                <input class="opt-change form-control" id="id_set_lesson_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["set_lesson_adminid title", "set_lesson_adminid", "th_set_lesson_adminid" ]])!!}

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
                <span class="input-group-addon">年级</span>
                <select class="opt-change form-control" id="id_grade" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["grade title", "grade", "th_grade" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">set_boolean</span>
                <select class="opt-change form-control" id="id_success_flag" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["success_flag title", "success_flag", "th_success_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">出错类型</span>
                <select class="opt-change form-control" id="id_test_lesson_fail_flag" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["test_lesson_fail_flag title", "test_lesson_fail_flag", "th_test_lesson_fail_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["userid title", "userid", "th_userid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account_role</span>
                <select class="opt-change form-control" id="id_require_admin_type" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["require_admin_type title", "require_admin_type", "th_require_admin_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">require_adminid</span>
                <input class="opt-change form-control" id="id_require_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["require_adminid title", "require_adminid", "th_require_adminid" ]])!!}
*/
