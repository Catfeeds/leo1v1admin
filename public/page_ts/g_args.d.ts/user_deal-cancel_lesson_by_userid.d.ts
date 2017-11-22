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
	userid	:any;
	nick	:any;
	phone	:any;
	type	:any;
	type_str	:any;
}

/*

tofile: 
	 mkdir -p ../user_deal; vi  ../user_deal/cancel_lesson_by_userid.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_deal-cancel_lesson_by_userid.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {

    });
}
$(function(){




	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
*/
