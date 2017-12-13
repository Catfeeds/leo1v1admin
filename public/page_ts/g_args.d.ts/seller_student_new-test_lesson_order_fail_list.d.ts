interface GargsStatic {
	cur_require_adminid:	number;
	hide_cur_require_adminid:	number;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	page_num:	number;
	page_count:	number;
	origin_userid_flag:	number;//枚举: App\Enums\Eboolean
	order_flag:	number;//枚举: App\Enums\Eboolean
	test_lesson_order_fail_flag:	number;//枚举: App\Enums\Etest_lesson_order_fail_flag
	userid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	origin_level	:any;
	require_id	:any;
	lesson_start	:any;
	lesson_end	:any;
	userid	:any;
	teacherid	:any;
	grade	:any;
	subject	:any;
	cur_require_adminid	:any;
	test_lesson_fail_flag	:any;
	test_lesson_order_fail_set_time	:any;
	test_lesson_order_fail_flag	:any;
	test_lesson_order_fail_desc	:any;
	contract_status	:any;
	student_nick	:any;
	teacher_nick	:any;
	cur_require_admin_nick	:any;
	test_lesson_fail_flag_str	:any;
	test_lesson_order_fail_flag_str	:any;
	contract_status_str	:any;
	subject_str	:any;
	grade_str	:any;
	test_lesson_order_fail_flag_one	:any;
	key1	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student_new; vi  ../seller_student_new/test_lesson_order_fail_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-test_lesson_order_fail_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		cur_require_adminid:	$('#id_cur_require_adminid').val(),
		hide_cur_require_adminid:	$('#id_hide_cur_require_adminid').val(),
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		origin_userid_flag:	$('#id_origin_userid_flag').val(),
		order_flag:	$('#id_order_flag').val(),
		test_lesson_order_fail_flag:	$('#id_test_lesson_order_fail_flag').val(),
		userid:	$('#id_userid').val()
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
	$('#id_cur_require_adminid').val(g_args.cur_require_adminid);
	$('#id_hide_cur_require_adminid').val(g_args.hide_cur_require_adminid);
	$('#id_origin_userid_flag').admin_set_select_field({
		"enum_type"    : "boolean",
		"field_name" : "origin_userid_flag",
		"select_value" : g_args.origin_userid_flag,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_origin_userid_flag",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_order_flag').admin_set_select_field({
		"enum_type"    : "boolean",
		"field_name" : "order_flag",
		"select_value" : g_args.order_flag,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_order_flag",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_test_lesson_order_fail_flag').admin_set_select_field({
		"enum_type"    : "test_lesson_order_fail_flag",
		"field_name" : "test_lesson_order_fail_flag",
		"select_value" : g_args.test_lesson_order_fail_flag,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_test_lesson_order_fail_flag",
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


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">cur_require_adminid</span>
                <input class="opt-change form-control" id="id_cur_require_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["cur_require_adminid title", "cur_require_adminid", "th_cur_require_adminid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">hide_cur_require_adminid</span>
                <input class="opt-change form-control" id="id_hide_cur_require_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["hide_cur_require_adminid title", "hide_cur_require_adminid", "th_hide_cur_require_adminid" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type_config title", "date_type_config", "th_date_type_config" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type title", "date_type", "th_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["opt_date_type title", "opt_date_type", "th_opt_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["start_time title", "start_time", "th_start_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["end_time title", "end_time", "th_end_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_origin_userid_flag" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["origin_userid_flag title", "origin_userid_flag", "th_origin_userid_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_order_flag" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["order_flag title", "order_flag", "th_order_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_lesson_order_fail_flag</span>
                <select class="opt-change form-control" id="id_test_lesson_order_fail_flag" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["test_lesson_order_fail_flag title", "test_lesson_order_fail_flag", "th_test_lesson_order_fail_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["userid title", "userid", "th_userid" ]])!!}
*/
