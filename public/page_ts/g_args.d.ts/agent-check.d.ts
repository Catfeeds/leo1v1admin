interface GargsStatic {
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	userid	:any;
	phone	:any;
	origin	:any;
	add_time	:any;
	global_tq_called_flag	:any;
	last_succ_test_lessonid	:any;
	last_adminid	:any;
	is_order	:any;
}

/*

tofile: 
	 mkdir -p ../agent; vi  ../agent/check.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-check.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,

		});
}
$(function(){




	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
*/
