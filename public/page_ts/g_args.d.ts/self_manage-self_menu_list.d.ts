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
	id	:any;
	adminid	:any;
	order_index	:any;
	title	:any;
	url	:any;
	icon	:any;
}

/*

tofile: 
	 mkdir -p ../self_manage; vi  ../self_manage/self_menu_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/self_manage-self_menu_list.d.ts" />

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
