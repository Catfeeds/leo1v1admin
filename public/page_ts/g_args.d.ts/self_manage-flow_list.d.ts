interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	post_adminid:	number;
	flow_check_flag:	string;//枚举列表: \App\Enums\Eflow_check_flag
 	flow_type:	number;//枚举: App\Enums\Eflow_type
	page_num:	number;
	page_count:	number;
	page_type:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	flowid	:any;
	nodeid	:any;
	node_type	:any;
	add_time	:any;
	flow_check_flag	:any;
	check_msg	:any;
	check_time	:any;
	adminid	:any;
	flow_status	:any;
	post_adminid	:any;
	post_time	:any;
	post_msg	:any;
	flow_type	:any;
	from_key_int	:any;
	from_key_str	:any;
	from_key2_int	:any;
	flow_type_str	:any;
	node_name	:any;
	post_admin_nick	:any;
	line_data	:any;
	flow_check_flag_str	:any;
	flow_status_str	:any;
}

/*

tofile: 
	 mkdir -p ../self_manage; vi  ../self_manage/flow_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/self_manage-flow_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		post_adminid:	$('#id_post_adminid').val(),
		flow_check_flag:	$('#id_flow_check_flag').val(),
		flow_type:	$('#id_flow_type').val(),
		page_type:	$('#id_page_type').val()
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
	$('#id_post_adminid').val(g_args.post_adminid);
	$('#id_flow_check_flag').admin_set_select_field({
		"enum_type"    : "flow_check_flag",
		"field_name" : "flow_check_flag",
		"select_value" : g_args.flow_check_flag,
		"multi_select_flag"     : true,
		"onChange"     : load_data,
		"th_input_id"  : "th_flow_check_flag",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_flow_type').admin_set_select_field({
		"enum_type"    : "flow_type",
		"field_name" : "flow_type",
		"select_value" : g_args.flow_type,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_flow_type",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_page_type').val(g_args.page_type);


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
                <span class="input-group-addon">post_adminid</span>
                <input class="opt-change form-control" id="id_post_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["post_adminid title", "post_adminid", "th_post_adminid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">flow_check_flag</span>
                <input class="opt-change form-control" id="id_flow_check_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["flow_check_flag title", "flow_check_flag", "th_flow_check_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">类型</span>
                <select class="opt-change form-control" id="id_flow_type" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["flow_type title", "flow_type", "th_flow_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">page_type</span>
                <input class="opt-change form-control" id="id_page_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["page_type title", "page_type", "th_page_type" ]])!!}
*/
