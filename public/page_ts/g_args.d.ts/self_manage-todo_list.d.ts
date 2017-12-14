interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	todo_type:	string;//枚举列表: \App\Enums\Etodo_type
 	todo_status:	string;//枚举列表: \App\Enums\Etodo_status
 	page_num:	number;
	page_count:	number;
	assign_lesson_count:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
}

/*

tofile: 
	 mkdir -p ../self_manage; vi  ../self_manage/todo_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/self_manage-todo_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		todo_type:	$('#id_todo_type').val(),
		todo_status:	$('#id_todo_status').val(),
		assign_lesson_count:	$('#id_assign_lesson_count').val()
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
	$('#id_todo_type').admin_set_select_field({
		"enum_type"    : "todo_type",
		"field_name" : "todo_type",
		"select_value" : g_args.todo_type,
		"multi_select_flag"     : true,
		"onChange"     : load_data,
		"th_input_id"  : "th_todo_type",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_todo_status').admin_set_select_field({
		"enum_type"    : "todo_status",
		"field_name" : "todo_status",
		"select_value" : g_args.todo_status,
		"multi_select_flag"     : true,
		"onChange"     : load_data,
		"th_input_id"  : "th_todo_status",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_assign_lesson_count').val(g_args.assign_lesson_count);


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
                <span class="input-group-addon">todo_type</span>
                <input class="opt-change form-control" id="id_todo_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["todo_type title", "todo_type", "th_todo_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">todo_status</span>
                <input class="opt-change form-control" id="id_todo_status" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["todo_status title", "todo_status", "th_todo_status" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assign_lesson_count</span>
                <input class="opt-change form-control" id="id_assign_lesson_count" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["assign_lesson_count title", "assign_lesson_count", "th_assign_lesson_count" ]])!!}
*/
