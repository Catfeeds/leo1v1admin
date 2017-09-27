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
	create_time	:any;
	create_adminid	:any;
	username	:any;
	phone	:any;
	complaint_user_type	:any;
	content	:any;
	status	:any;
	operator	:any;
	assign_time	:any;
	process_state	:any;
	solution	:any;
	num	:any;
	complaint_user_type_str	:any;
	create_admin_nick	:any;
}

/*

tofile: 
	 mkdir -p ../customer_service; vi  ../customer_service/complaint_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/customer_service-complaint_info.d.ts" />

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
