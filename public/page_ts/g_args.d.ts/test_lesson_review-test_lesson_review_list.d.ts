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
	adminid	:any;
	group_adminid	:any;
	group_suc_flag	:any;
	group_time	:any;
	master_adminid	:any;
	master_suc_flag	:any;
	master_time	:any;
	create_time	:any;
	userid	:any;
	review_desc	:any;
	phone	:any;
	nick	:any;
	aid	:any;
	num	:any;
	group_nick	:any;
	master_nick	:any;
	group_suc_flag_str	:any;
	master_suc_flag_str	:any;
}

/*

tofile: 
	 mkdir -p ../test_lesson_review; vi  ../test_lesson_review/test_lesson_review_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test_lesson_review-test_lesson_review_list.d.ts" />

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
