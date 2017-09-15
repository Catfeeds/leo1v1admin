interface GargsStatic {
	page_num:	number;
	page_count:	number;
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
	zfb_name	:any;
	zfb_account	:any;
}

/*

tofile: 
	 mkdir -p ../agent; vi  ../agent/check.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-check.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }




	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
*/
