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
	new_pic	:any;
	new_title	:any;
	new_content	:any;
	create_time	:any;
	adminid	:any;
	nick	:any;
}

/*

tofile: 
	 mkdir -p ../t_yxyx_new_list; vi  ../t_yxyx_new_list/get_all.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/t_yxyx_new_list-get_all.d.ts" />

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
