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
	machine_id	:any;
	open_door_flag	:any;
	last_post_time	:any;
	sn	:any;
	title	:any;
	desc	:any;
	open_door_flag_str	:any;
}

/*

tofile: 
	 mkdir -p ../admin_manage; vi  ../admin_manage/kaoqin_machine.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_manage-kaoqin_machine.d.ts" />

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
