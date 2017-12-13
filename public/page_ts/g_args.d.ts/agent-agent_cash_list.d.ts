interface GargsStatic {
	cash:	number;
	type:	number;
	nickname:	string;
	page_num:	number;
	page_count:	number;
	origin_count:	string;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	cash_range:	string;
	agent_check_money_flag:	number;//枚举: App\Enums\Eagent_check_money_flag
	phone:	string;
	check_money_admin_nick:	string;
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
	aid	:any;
	cash	:any;
	is_suc_flag	:any;
	create_time	:any;
	type	:any;
	check_money_flag	:any;
	check_money_adminid	:any;
	check_money_time	:any;
	check_money_desc	:any;
	nickname	:any;
	phone	:any;
	bankcard	:any;
	bank_type	:any;
	bank_account	:any;
	bank_address	:any;
	bank_phone	:any;
	bank_province	:any;
	bank_city	:any;
	zfb_name	:any;
	zfb_account	:any;
	all_yxyx_money	:any;
	all_open_cush_money	:any;
	all_have_cush_money	:any;
	agent_cash_money_freeze	:any;
	agent_check_money_flag	:any;
	check_money_admin_nick	:any;
	agent_check_money_flag_str	:any;
}

/*

tofile: 
	 mkdir -p ../agent; vi  ../agent/agent_cash_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-agent_cash_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		cash:	$('#id_cash').val(),
		type:	$('#id_type').val(),
		nickname:	$('#id_nickname').val(),
		origin_count:	$('#id_origin_count').val(),
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		cash_range:	$('#id_cash_range').val(),
		agent_check_money_flag:	$('#id_agent_check_money_flag').val(),
		phone:	$('#id_phone').val(),
		check_money_admin_nick:	$('#id_check_money_admin_nick').val()
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
	$('#id_cash').val(g_args.cash);
	$('#id_type').val(g_args.type);
	$('#id_nickname').val(g_args.nickname);
	$('#id_origin_count').val(g_args.origin_count);
	$('#id_cash_range').val(g_args.cash_range);
	$('#id_agent_check_money_flag').admin_set_select_field({
		"enum_type"    : "agent_check_money_flag",
		"field_name" : "agent_check_money_flag",
		"select_value" : g_args.agent_check_money_flag,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_agent_check_money_flag",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_phone').val(g_args.phone);
	$('#id_check_money_admin_nick').val(g_args.check_money_admin_nick);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">cash</span>
                <input class="opt-change form-control" id="id_cash" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["cash title", "cash", "th_cash" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">type</span>
                <input class="opt-change form-control" id="id_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["type title", "type", "th_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">nickname</span>
                <input class="opt-change form-control" id="id_nickname" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["nickname title", "nickname", "th_nickname" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin_count</span>
                <input class="opt-change form-control" id="id_origin_count" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["origin_count title", "origin_count", "th_origin_count" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type_config title", "date_type_config", "th_date_type_config" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type title", "date_type", "th_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["opt_date_type title", "opt_date_type", "th_opt_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["start_time title", "start_time", "th_start_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["end_time title", "end_time", "th_end_time" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">cash_range</span>
                <input class="opt-change form-control" id="id_cash_range" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["cash_range title", "cash_range", "th_cash_range" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">agent_check_money_flag</span>
                <select class="opt-change form-control" id="id_agent_check_money_flag" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["agent_check_money_flag title", "agent_check_money_flag", "th_agent_check_money_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone</span>
                <input class="opt-change form-control" id="id_phone" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["phone title", "phone", "th_phone" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">check_money_admin_nick</span>
                <input class="opt-change form-control" id="id_check_money_admin_nick" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["check_money_admin_nick title", "check_money_admin_nick", "th_check_money_admin_nick" ]])!!}
*/
