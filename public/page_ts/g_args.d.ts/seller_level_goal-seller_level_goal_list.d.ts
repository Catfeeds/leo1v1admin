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
	seller_level	:any;
	level_goal	:any;
	level_face	:any;
	create_time	:any;
	pp_level_str	:any;
}

/*

tofile: 
	 mkdir -p ../seller_level_goal; vi  ../seller_level_goal/seller_level_goal_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_level_goal-seller_level_goal_list.d.ts" />

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
