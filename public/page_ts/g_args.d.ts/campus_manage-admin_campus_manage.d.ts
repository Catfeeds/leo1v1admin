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
	campus_id	:any;
	campus_name	:any;
	main_groupid	:any;
	main_group_name	:any;
}

/*

tofile: 
	 mkdir -p ../campus_manage; vi  ../campus_manage/admin_campus_manage.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/campus_manage-admin_campus_manage.d.ts" />

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
