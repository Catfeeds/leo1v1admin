interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	subject:	number;//枚举: \App\Enums\Esubject
	grade:	number;//枚举: \App\Enums\Egrade
	require_flag:	number;//枚举: \App\Enums\Eboolean
	class_hour:	number;//枚举: \App\Enums\Eboolean
	account_role:	number;//枚举: \App\Enums\Eaccount_role
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
	account_role	:any;
	sys_operator	:any;
	orderid	:any;
	userid	:any;
	discount_price	:any;
	promotion_discount_price	:any;
	price	:any;
	subject	:any;
	grade	:any;
	t_2_lesson_count	:any;
	t_1_lesson_count	:any;
	student_nick	:any;
	subject_str	:any;
	grade_str	:any;
	account_role_str	:any;
	cost_price	:any;
	discount_rate	:any;
}

/*

tofile: 
	 mkdir -p ../contract_present; vi  ../contract_present/contract_present_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/contract_present-contract_present_info.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		subject:	$('#id_subject').val(),
		grade:	$('#id_grade').val(),
		require_flag:	$('#id_require_flag').val(),
		class_hour:	$('#id_class_hour').val(),
		account_role:	$('#id_account_role').val()
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
	$('#id_require_flag').admin_set_select_field({
		"enum_type"    : "boolean",
		"field_name" : "require_flag",
		"select_value" : g_args.require_flag,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_require_flag",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_class_hour').admin_set_select_field({
		"enum_type"    : "boolean",
		"field_name" : "class_hour",
		"select_value" : g_args.class_hour,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_class_hour",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_account_role').admin_set_select_field({
		"enum_type"    : "account_role",
		"field_name" : "account_role",
		"select_value" : g_args.account_role,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_account_role",
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
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_require_flag" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["require_flag title", "require_flag", "th_require_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_class_hour" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["class_hour title", "class_hour", "th_class_hour" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account_role</span>
                <select class="opt-change form-control" id="id_account_role" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["account_role title", "account_role", "th_account_role" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
