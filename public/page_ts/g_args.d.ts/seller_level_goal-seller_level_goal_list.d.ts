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
	orderid	:any;
	pid	:any;
	p_price	:any;
	ppid	:any;
	pp_price	:any;
	create_time	:any;
	aid	:any;
	p_level	:any;
	pp_level	:any;
	p_open_price	:any;
	pp_open_price	:any;
	userid	:any;
	phone	:any;
	nickname	:any;
	a_create_time	:any;
	p_phone	:any;
	p_nickname	:any;
	pp_phone	:any;
	pp_nickname	:any;
	price	:any;
	p_level_str	:any;
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
