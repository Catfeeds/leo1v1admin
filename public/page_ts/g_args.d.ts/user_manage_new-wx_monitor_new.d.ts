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
	template_id	:any;
	send_time	:any;
	template_type	:any;
	title	:any;
	first_sentence	:any;
	end_sentence	:any;
	keyword1	:any;
	keyword2	:any;
	keyword3	:any;
	keyword4	:any;
	url	:any;
	account	:any;
	send_time_str	:any;
	template_type_str	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/wx_monitor_new.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-wx_monitor_new.d.ts" />

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
