interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	mark	:any;
	subject	:any;
	subject_str	:any;
	adminid	:any;
	nick	:any;
	resource_type	:any;
	resource_type_str	:any;
	file_num	:any;
	visit_num	:any;
	error_num	:any;
	use_num	:any;
	visit	:any;
	use	:any;
	error	:any;
	visit_rate	:any;
	error_rate	:any;
	use_rate	:any;
	score	:any;
}

/*

tofile: 
	 mkdir -p ../resource; vi  ../resource/resource_count.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/resource-resource_count.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val()
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


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
*/
