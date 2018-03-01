interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	page_num:	number;
	page_count:	number;
	gift_type:	number;//枚举: App\Enums\Econtract_type
	status:	number;//枚举: App\Enums\Econtract_type
	assistantid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	exchangeid	:any;
	nick	:any;
	phone	:any;
	exchange_time	:any;
	gift_name	:any;
	gift_type	:any;
	account	:any;
	address	:any;
	status	:any;
	consignee	:any;
	consignee_phone	:any;
	ecg_express_name	:any;
	status_str	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/commodity_exchange_management.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-commodity_exchange_management.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		gift_type:	$('#id_gift_type').val(),
		status:	$('#id_status').val(),
		assistantid:	$('#id_assistantid').val()
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
	$('#id_gift_type').admin_set_select_field({
		"enum_type"    : "contract_type",
		"field_name" : "gift_type",
		"select_value" : g_args.gift_type,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_gift_type",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_status').admin_set_select_field({
		"enum_type"    : "contract_type",
		"field_name" : "status",
		"select_value" : g_args.status,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_status",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_assistantid').admin_select_user_new({
		"user_type"    : "assistant",
		"select_value" : g_args.assistantid,
		"onChange"     : load_data,
		"th_input_id"  : "th_assistantid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
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
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">contract_type</span>
                <select class="opt-change form-control" id="id_gift_type" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["gift_type title", "gift_type", "th_gift_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">contract_type</span>
                <select class="opt-change form-control" id="id_status" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["status title", "status", "th_status" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["assistantid title", "assistantid", "th_assistantid" ]])!!}
*/
