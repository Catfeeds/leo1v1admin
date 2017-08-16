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
	custom_type_id	:any;
	type_name	:any;
	create_time	:any;
	adminid	:any;
	nick	:any;
}

/*

tofile: 
	 mkdir -p ../t_yxyx_custom_type; vi  ../t_yxyx_custom_type/get_all.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/t_yxyx_custom_type-get_all.d.ts" />

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
