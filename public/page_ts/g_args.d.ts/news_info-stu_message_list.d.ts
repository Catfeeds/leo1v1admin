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
	messageid	:any;
	message_type	:any;
	message_content	:any;
}

/*

tofile: 
	 mkdir -p ../news_info; vi  ../news_info/stu_message_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/news_info-stu_message_list.d.ts" />

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
