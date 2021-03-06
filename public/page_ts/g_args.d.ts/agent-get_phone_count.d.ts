interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	phone:	string;
	is_called_phone:	number;//枚举: App\Enums\Eboolean
	uid:	number;
	page_num:	number;
	page_count:	number;
	seller_student_status:	string;//枚举列表: \App\Enums\Eseller_student_status
 	agent_type:	number;
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
	uid	:any;
	phone	:any;
	start_time	:any;
	end_time	:any;
	duration	:any;
	is_called_phone	:any;
	record_url	:any;
	account	:any;
	seller_student_status	:any;
	load_wav_self_flag	:any;
	is_called_phone_str	:any;
	seller_student_status_str	:any;
}

/*

tofile: 
	 mkdir -p ../agent; vi  ../agent/get_phone_count.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-get_phone_count.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		phone:	$('#id_phone').val(),
		is_called_phone:	$('#id_is_called_phone').val(),
		uid:	$('#id_uid').val(),
		seller_student_status:	$('#id_seller_student_status').val(),
		agent_type:	$('#id_agent_type').val()
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
	$('#id_phone').val(g_args.phone);
	$('#id_is_called_phone').admin_set_select_field({
		"enum_type"    : "boolean",
		"field_name" : "is_called_phone",
		"select_value" : g_args.is_called_phone,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_is_called_phone",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_uid').val(g_args.uid);
	$('#id_seller_student_status').admin_set_select_field({
		"enum_type"    : "seller_student_status",
		"field_name" : "seller_student_status",
		"select_value" : g_args.seller_student_status,
		"multi_select_flag"     : true,
		"onChange"     : load_data,
		"th_input_id"  : "th_seller_student_status",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_agent_type').val(g_args.agent_type);


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
                <span class="input-group-addon">phone</span>
                <input class="opt-change form-control" id="id_phone" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["phone title", "phone", "th_phone" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_is_called_phone" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["is_called_phone title", "is_called_phone", "th_is_called_phone" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">uid</span>
                <input class="opt-change form-control" id="id_uid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["uid title", "uid", "th_uid" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_student_status</span>
                <input class="opt-change form-control" id="id_seller_student_status" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["seller_student_status title", "seller_student_status", "th_seller_student_status" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">agent_type</span>
                <input class="opt-change form-control" id="id_agent_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["agent_type title", "agent_type", "th_agent_type" ]])!!}
*/
