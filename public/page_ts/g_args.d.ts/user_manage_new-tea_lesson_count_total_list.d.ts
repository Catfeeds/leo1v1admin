interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	confirm_flag:	number;//枚举: App\Enums\Eboolean
	pay_flag:	number;//枚举: App\Enums\Eboolean
	show_add_money_flag:	number;
	check_adminid:	number;
	has_check_adminid_flag:	number;//枚举: App\Enums\Eboolean
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	check_adminid	:any;
	teacherid	:any;
	realname	:any;
	nick	:any;
	teacher_money_type	:any;
	level	:any;
	l1v1_lesson_count	:any;
	test_lesson_count	:any;
	all_lesson_money	:any;
	index	:any;
	subject_str	:any;
	teacher_money_type_str	:any;
	level_str	:any;
	real_all_count	:any;
	real_l1v1_count	:any;
	real_test_count	:any;
	real_money_all_count	:any;
	real_money_l1v1_count	:any;
	real_money_test_count	:any;
	confirm_flag	:any;
	confirm_time	:any;
	confirm_adminid	:any;
	pay_flag	:any;
	pay_time	:any;
	pay_adminid	:any;
	confirm_flag_str	:any;
	pay_flag_str	:any;
	confirm_admin_nick	:any;
	pay_admin_nick	:any;
	check_admin_nick	:any;
	all_count	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/tea_lesson_count_total_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-tea_lesson_count_total_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		confirm_flag:	$('#id_confirm_flag').val(),
		pay_flag:	$('#id_pay_flag').val(),
		show_add_money_flag:	$('#id_show_add_money_flag').val(),
		check_adminid:	$('#id_check_adminid').val(),
		has_check_adminid_flag:	$('#id_has_check_adminid_flag').val()
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
	$('#id_confirm_flag').admin_set_select_field({
		"enum_type"    : "boolean",
		"field_name" : "confirm_flag",
		"select_value" : g_args.confirm_flag,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_confirm_flag",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_pay_flag').admin_set_select_field({
		"enum_type"    : "boolean",
		"field_name" : "pay_flag",
		"select_value" : g_args.pay_flag,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_pay_flag",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_show_add_money_flag').val(g_args.show_add_money_flag);
	$('#id_check_adminid').val(g_args.check_adminid);
	$('#id_has_check_adminid_flag').admin_set_select_field({
		"enum_type"    : "boolean",
		"field_name" : "has_check_adminid_flag",
		"select_value" : g_args.has_check_adminid_flag,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_has_check_adminid_flag",
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
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_confirm_flag" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["confirm_flag title", "confirm_flag", "th_confirm_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_pay_flag" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["pay_flag title", "pay_flag", "th_pay_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">show_add_money_flag</span>
                <input class="opt-change form-control" id="id_show_add_money_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["show_add_money_flag title", "show_add_money_flag", "th_show_add_money_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">check_adminid</span>
                <input class="opt-change form-control" id="id_check_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["check_adminid title", "check_adminid", "th_check_adminid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_has_check_adminid_flag" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["has_check_adminid_flag title", "has_check_adminid_flag", "th_has_check_adminid_flag" ]])!!}
*/
