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
	grade	:any;
	assistantid	:any;
	ass_nick	:any;
	nick	:any;
	num	:any;
	grade_str	:any;
}

/*

tofile: 
	 mkdir -p ../user_deal; vi  ../user_deal/cancel_lesson_by_userid.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_deal-cancel_lesson_by_userid.d.ts" />

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
