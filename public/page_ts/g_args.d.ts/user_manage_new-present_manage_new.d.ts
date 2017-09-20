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
	giftid	:any;
	gift_type	:any;
	gift_name	:any;
	gift_intro	:any;
	current_praise	:any;
	gift_pic	:any;
	gift_desc	:any;
	cost_price	:any;
	shop_link	:any;
	gift_status	:any;
	gift_type_str	:any;
	gift_desc_str	:any;
	cost_price_str	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/present_manage_new.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-present_manage_new.d.ts" />

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
