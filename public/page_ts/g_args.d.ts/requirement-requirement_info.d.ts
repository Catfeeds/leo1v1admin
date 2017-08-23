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
	title	:any;
	name	:any;
	create_adminid	:any;
	create_time	:any;
	expect_time	:any;
	priority	:any;
	significance	:any;
	notes	:any;
	statement	:any;
	content_pic	:any;
	product_solution	:any;
	product_operator	:any;
	product_phone	:any;
	product_add_time	:any;
	product_submit_time	:any;
	product_reject	:any;
	product_reject_time	:any;
	development_operator	:any;
	development_phone	:any;
	development_add_time	:any;
	development_submit_time	:any;
	development_reject	:any;
	development_reject_time	:any;
	test_operator	:any;
	test_phone	:any;
	test_add_time	:any;
	test_submit_time	:any;
	test_reject	:any;
	test_reject_time	:any;
	product_status	:any;
	development_status	:any;
	test_status	:any;
	status	:any;
	del_flag	:any;
	num	:any;
	name_str	:any;
	priority_str	:any;
	significance_str	:any;
	status_str	:any;
	create_admin_nick	:any;
	flag	:any;
	operator_status	:any;
}

/*

tofile: 
	 mkdir -p ../requirement; vi  ../requirement/requirement_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/requirement-requirement_info.d.ts" />

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
