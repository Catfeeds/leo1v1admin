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
	create_time	:any;
	office_device_type	:any;
	device_id	:any;
	device_opt_type	:any;
	office_device_type_str	:any;
	device_opt_type_str	:any;
}

/*

tofile: 
	 mkdir -p ../admin_manage; vi  ../admin_manage/office_cmd_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_manage-office_cmd_list.d.ts" />

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
