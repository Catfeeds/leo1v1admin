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
	parentid	:any;
	userid	:any;
	phone	:any;
	wx_openid	:any;
	create_time	:any;
}

/*

tofile: 
	 mkdir -p ../testbb; vi  ../testbb/assistant_info_new2.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/testbb-assistant_info_new2.d.ts" />

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
