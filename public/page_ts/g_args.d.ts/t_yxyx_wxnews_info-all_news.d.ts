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
	pic	:any;
	title	:any;
	des	:any;
	adminid	:any;
	wxnew_type	:any;
	create_time	:any;
	new_link	:any;
	wxnew_type_str	:any;
}

/*

tofile: 
	 mkdir -p ../t_yxyx_wxnews_info; vi  ../t_yxyx_wxnews_info/all_news.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/t_yxyx_wxnews_info-all_news.d.ts" />

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
