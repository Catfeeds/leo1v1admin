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
	teacherid	:any;
	nick	:any;
	subject	:any;
	phone	:any;
	bank_account	:any;
	bankcard	:any;
	bank_type	:any;
	bank_province	:any;
	bank_city	:any;
	bank_address	:any;
	bank_phone	:any;
	idcard	:any;
	bind_bankcard_time	:any;
	bind_bankcard_time_str	:any;
	subject_str	:any;
}

/*

tofile: 
	 mkdir -p ../teacher_money; vi  ../teacher_money/show_teacher_bank_info_human.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_money-show_teacher_bank_info_human.d.ts" />

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
