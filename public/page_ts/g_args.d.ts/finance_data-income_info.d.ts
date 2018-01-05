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
	month	:any;
	new_order_money	:any;
	renew_order_money	:any;
	new_order_stu	:any;
	renew_order_stu	:any;
	new_signature_price	:any;
	renew_signature_price	:any;
	month_str	:any;
}

/*

tofile: 
	 mkdir -p ../finance_data; vi  ../finance_data/income_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/finance_data-income_info.d.ts" />

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
