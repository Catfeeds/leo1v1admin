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
	create_time	:any;
	create_adminid	:any;
	phone	:any;
	child_realname	:any;
	parent_realname	:any;
	relation_ship	:any;
	region	:any;
	grade	:any;
	cash	:any;
	free_subject	:any;
	region_version	:any;
	notes	:any;
	num	:any;
	relation_ship_str	:any;
	grade_str	:any;
	free_subject_str	:any;
	region_version_str	:any;
	create_admin_nick	:any;
}

/*

tofile: 
	 mkdir -p ../customer_service; vi  ../customer_service/intended_user_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/customer_service-intended_user_info.d.ts" />

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
