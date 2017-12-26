interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	contract_type:	number;
	is_test_user:	number;//枚举: App\Enums\Eboolean
	studentid:	number;
	check_money_flag:	number;
	origin:	string;
	from_type:	number;
	account_role:	number;
	sys_operator:	string;
	seller_groupid_ex:	string;
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
	all_price	:any;
	transfer_introduction_price	:any;
	new_price	:any;
	normal_price	:any;
	extend_price	:any;
	all_price_suc	:any;
	all_price_fail	:any;
	main_type	:any;
	up_group_name	:any;
	group_name	:any;
	account	:any;
	main_type_class	:any;
	up_group_name_class	:any;
	group_name_class	:any;
	account_class	:any;
	level	:any;
	main_type_str	:any;
	del_flag	:any;
	del_flag_str	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ss; vi  ../tongji_ss/contract_count.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-contract_count.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		contract_type:	$('#id_contract_type').val(),
		is_test_user:	$('#id_is_test_user').val(),
		studentid:	$('#id_studentid').val(),
		check_money_flag:	$('#id_check_money_flag').val(),
		origin:	$('#id_origin').val(),
		from_type:	$('#id_from_type').val(),
		account_role:	$('#id_account_role').val(),
		sys_operator:	$('#id_sys_operator').val(),
		seller_groupid_ex:	$('#id_seller_groupid_ex').val()
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
	$('#id_contract_type').val(g_args.contract_type);
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
	$('#id_studentid').admin_select_user_new({
		"user_type"    : "student",
		"select_value" : g_args.studentid,
		"onChange"     : load_data,
		"th_input_id"  : "th_studentid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_check_money_flag').val(g_args.check_money_flag);
	$('#id_origin').val(g_args.origin);
	$('#id_from_type').val(g_args.from_type);
	$('#id_account_role').val(g_args.account_role);
	$('#id_sys_operator').val(g_args.sys_operator);
	$('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);


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
                <span class="input-group-addon">contract_type</span>
                <input class="opt-change form-control" id="id_contract_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["contract_type title", "contract_type", "th_contract_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_is_test_user" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["is_test_user title", "is_test_user", "th_is_test_user" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">studentid</span>
                <input class="opt-change form-control" id="id_studentid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["studentid title", "studentid", "th_studentid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">check_money_flag</span>
                <input class="opt-change form-control" id="id_check_money_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["check_money_flag title", "check_money_flag", "th_check_money_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin</span>
                <input class="opt-change form-control" id="id_origin" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["origin title", "origin", "th_origin" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">from_type</span>
                <input class="opt-change form-control" id="id_from_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["from_type title", "from_type", "th_from_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account_role</span>
                <input class="opt-change form-control" id="id_account_role" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["account_role title", "account_role", "th_account_role" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">sys_operator</span>
                <input class="opt-change form-control" id="id_sys_operator" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["sys_operator title", "sys_operator", "th_sys_operator" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_groupid_ex</span>
                <input class="opt-change form-control" id="id_seller_groupid_ex" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["seller_groupid_ex title", "seller_groupid_ex", "th_seller_groupid_ex" ]])!!}
*/
